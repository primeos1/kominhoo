<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class QuizController extends Controller
{
    private function apiResponse(mixed $data, string $message = '', bool $success = true, int $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'answers' => 'required|array',
        ]);

        $answers  = $data['answers'];
        $skinType = $this->computeSkinType($answers);
        $skinScores = $this->computeSkinScores($skinType, $answers);

        // Fetch matched products — up to 8, pad with active products if needed
        $recommended = Product::where('is_active', true)
            ->whereJsonContains('skin_types', $skinType)
            ->limit(8)->pluck('id')->toArray();

        if (count($recommended) < 8) {
            $extra = Product::where('is_active', true)
                ->whereNotIn('id', $recommended)
                ->limit(8 - count($recommended))->pluck('id')->toArray();
            $recommended = array_merge($recommended, $extra);
        }

        // On a public route $request->user() is always null even with a valid
        // Bearer token because the auth:sanctum middleware never ran.
        // Resolve the caller by looking up the token directly.
        $user = $request->user();
        if (!$user && ($bearer = $request->bearerToken())) {
            $pat = PersonalAccessToken::findToken($bearer);
            $user = $pat?->tokenable;
        }

        $result = QuizResult::create([
            'user_id'                 => $user?->id,
            'skin_type'               => $skinType,
            'answers'                 => $answers,
            'skin_scores'             => $skinScores,
            'recommended_product_ids' => $recommended,
        ]);

        // Persist latest skin type onto the user profile
        $user?->update(['skin_type' => $skinType]);

        $products = Product::whereIn('id', $recommended)->get();

        return $this->apiResponse([
            'result'               => $result,
            'skin_type'            => $skinType,
            'skin_scores'          => $skinScores,
            'recommended_products' => $products,
        ], 'Quiz completed', true, 201);
    }

    /**
     * Save partial quiz progress after each stage and update the user's
     * skin_type field with the best current estimate.
     */
    public function saveProgress(Request $request)
    {
        $data = $request->validate([
            'answers' => 'required|array',
            'stage'   => 'required|integer|min:1|max:5',
        ]);

        $skinType = $this->computeSkinType($data['answers']);

        if ($user = $request->user()) {
            $user->update(['skin_type' => $skinType]);
        }

        return $this->apiResponse([
            'skin_type' => $skinType,
            'stage'     => $data['stage'],
        ], 'Progress saved');
    }

    /**
     * Explicitly claim a quiz result for the authenticated user.
     * Used as a fallback when the public submit route cannot resolve the user.
     */
    public function claim(Request $request, int $id)
    {
        $result = QuizResult::findOrFail($id);

        // Only allow claiming an unclaimed result
        if ($result->user_id === null) {
            $result->update(['user_id' => $request->user()->id]);
        }

        return $this->apiResponse(['result_id' => $result->id, 'user_id' => $result->user_id]);
    }

    public function history(Request $request)
    {
        $results = QuizResult::where('user_id', $request->user()->id)->latest()->get();
        return $this->apiResponse($results);
    }

    /**
     * Admin: all quiz results, paginated.
     * Includes linked user data where available; guest results show user=null.
     */
    public function listResults(Request $request)
    {
        $results = QuizResult::with('user:id,name,email,avatar,skin_type')
            ->latest()
            ->paginate($request->input('per_page', 100));

        return $this->apiResponse($results);
    }

    // ─── Algorithm ────────────────────────────────────────────────────────────

    /**
     * Compute skin type from full semantic answers using a weighted scoring system.
     *
     * Answer keys (all from quiz slide answers):
     *   skin_feel, shine, pores          — Stage 1: Skin Type (slides 1-3)
     *   concerns[], severity             — Stage 2: Concerns (slides 4-5)
     *   reactivity, breakouts, actives   — Stage 3: Behavior (slides 6-8)
     *   water, sun, environment          — Stage 4: Lifestyle (slides 9-11)
     *   budget, results_goal, treatments — Stage 5: Personalization (slides 12-14)
     */
    private function computeSkinType(array $answers): string
    {
        $scores = ['Oily' => 0, 'Dry' => 0, 'Combination' => 0, 'Normal' => 0, 'Sensitive' => 0];

        // Slide 1 — Post-wash skin feel (weight 4 — strongest single indicator)
        $this->applyScores($scores, [
            'dry'         => ['Dry' => 4],
            'normal'      => ['Normal' => 3],
            'combination' => ['Combination' => 4],
            'oily'        => ['Oily' => 4],
            'unsure'      => ['Normal' => 1],
        ][$answers['skin_feel'] ?? ''] ?? []);

        // Slide 2 — Daytime shine frequency (weight 3)
        $this->applyScores($scores, [
            'rarely'    => ['Normal' => 2, 'Dry' => 1],
            'tzone'     => ['Combination' => 3],
            'frequently' => ['Oily' => 2, 'Combination' => 1],
            'very_oily'  => ['Oily' => 3],
        ][$answers['shine'] ?? ''] ?? []);

        // Slide 3 — Pore visibility (weight 2)
        $this->applyScores($scores, [
            'barely'      => ['Normal' => 2, 'Dry' => 1],
            'tzone_pores' => ['Combination' => 2],
            'large'       => ['Oily' => 2, 'Combination' => 1],
            'very_large'  => ['Oily' => 3],
        ][$answers['pores'] ?? ''] ?? []);

        // Slide 4 — Concerns (multi-select signals)
        $concerns = $this->parseConcerns($answers['concerns'] ?? []);
        if (in_array('sensitive', $concerns))                       $scores['Sensitive'] += 2;
        if (in_array('dehydration', $concerns))                     $scores['Dry'] += 1;
        if (in_array('acne', $concerns) || in_array('large_pores', $concerns)) $scores['Oily'] += 1;

        // Slide 5 — Severity amplifies the leading type
        if (($answers['severity'] ?? '') === 'severe') {
            $leader = $this->topType($scores, 'Sensitive');
            if ($leader) $scores[$leader] += 2;
        }

        // Slide 6 — Product reactivity (weight 3, Sensitive detector)
        $this->applyScores($scores, [
            'no_reaction'      => ['Normal' => 1],
            'occasional'       => ['Oily' => 1, 'Combination' => 1],
            'easily_irritated' => ['Sensitive' => 2],
            'very_sensitive'   => ['Sensitive' => 3],
        ][$answers['reactivity'] ?? ''] ?? []);

        // Slide 7 — Breakout frequency (weight 2)
        $this->applyScores($scores, [
            'rarely'     => ['Normal' => 1],
            'periodic'   => ['Combination' => 1],
            'frequently' => ['Oily' => 2, 'Combination' => 1],
            'constantly' => ['Oily' => 2],
        ][$answers['breakouts'] ?? ''] ?? []);

        // Slide 9 — Daily water intake (weight 1)
        $this->applyScores($scores, [
            'less_1l' => ['Dry' => 2],
            '1_2l'    => ['Dry' => 1],
            '2_3l'    => ['Normal' => 1],
            '3l_plus' => ['Normal' => 2],
        ][$answers['water'] ?? ''] ?? []);

        // Slide 11 — Environment (weight 1)
        $this->applyScores($scores, [
            'aircon' => ['Dry' => 1, 'Combination' => 1],
            'humid'  => ['Oily' => 1],
            'mixed'  => ['Normal' => 1],
        ][$answers['environment'] ?? ''] ?? []);

        // Sensitive overrides all others when score ≥ 3
        if ($scores['Sensitive'] >= 3) {
            return 'Sensitive';
        }

        arsort($scores);
        return (string)(array_key_first($scores) ?? 'Normal');
    }

    /**
     * Compute a personalised set of 5 skin metric scores (1-10) adjusted by
     * the user's specific answers rather than a static lookup table.
     */
    private function computeSkinScores(string $skinType, array $answers): array
    {
        $base = [
            'Oily'        => ['Hydration' => 4, 'Acne Risk' => 7, 'Sensitivity' => 4, 'Oil Level' => 8, 'Barrier Health' => 4],
            'Dry'         => ['Hydration' => 3, 'Acne Risk' => 3, 'Sensitivity' => 6, 'Oil Level' => 2, 'Barrier Health' => 3],
            'Combination' => ['Hydration' => 5, 'Acne Risk' => 6, 'Sensitivity' => 5, 'Oil Level' => 6, 'Barrier Health' => 5],
            'Normal'      => ['Hydration' => 7, 'Acne Risk' => 2, 'Sensitivity' => 3, 'Oil Level' => 4, 'Barrier Health' => 8],
            'Sensitive'   => ['Hydration' => 5, 'Acne Risk' => 4, 'Sensitivity' => 8, 'Oil Level' => 4, 'Barrier Health' => 4],
        ];

        $s = $base[$skinType] ?? $base['Normal'];

        // Severity
        match ($answers['severity'] ?? '') {
            'severe' => [$s['Acne Risk'] = min(10, $s['Acne Risk'] + 2), $s['Barrier Health'] = max(1, $s['Barrier Health'] - 1)],
            'mild'   => [$s['Acne Risk'] = max(1, $s['Acne Risk'] - 1), $s['Barrier Health'] = min(10, $s['Barrier Health'] + 1)],
            default  => null,
        };

        // Reactivity
        match ($answers['reactivity'] ?? '') {
            'very_sensitive'   => [$s['Sensitivity'] = min(10, $s['Sensitivity'] + 2), $s['Barrier Health'] = max(1, $s['Barrier Health'] - 1)],
            'easily_irritated' => [$s['Sensitivity'] = min(10, $s['Sensitivity'] + 1)],
            default            => null,
        };

        // Water intake
        match ($answers['water'] ?? '') {
            'less_1l' => [$s['Hydration'] = max(1, $s['Hydration'] - 2)],
            '1_2l'    => [$s['Hydration'] = max(1, $s['Hydration'] - 1)],
            '3l_plus' => [$s['Hydration'] = min(10, $s['Hydration'] + 1)],
            default   => null,
        };

        // Environment
        match ($answers['environment'] ?? '') {
            'aircon' => [$s['Hydration'] = max(1, $s['Hydration'] - 1), $s['Oil Level'] = max(1, $s['Oil Level'] - 1)],
            'humid'  => [$s['Oil Level'] = min(10, $s['Oil Level'] + 1)],
            default  => null,
        };

        // Concern adjustments
        $concerns = $this->parseConcerns($answers['concerns'] ?? []);
        if (in_array('acne', $concerns))        $s['Acne Risk']   = min(10, $s['Acne Risk'] + 1);
        if (in_array('dehydration', $concerns)) $s['Hydration']   = max(1, $s['Hydration'] - 1);
        if (in_array('sensitive', $concerns))   $s['Sensitivity'] = min(10, $s['Sensitivity'] + 1);

        // Breakout frequency
        if (($answers['breakouts'] ?? '') === 'constantly') {
            $s['Acne Risk'] = min(10, $s['Acne Risk'] + 1);
        }

        // Clamp everything to 1–10
        foreach ($s as $k => $v) {
            $s[$k] = max(1, min(10, (int) $v));
        }

        return $s;
    }

    private function applyScores(array &$scores, array $additions): void
    {
        foreach ($additions as $type => $points) {
            if (array_key_exists($type, $scores)) {
                $scores[$type] += $points;
            }
        }
    }

    /** Returns the highest-scoring type, optionally excluding one label. */
    private function topType(array $scores, string $exclude = ''): ?string
    {
        $filtered = array_filter($scores, fn($k) => $k !== $exclude, ARRAY_FILTER_USE_KEY);
        if (empty($filtered)) return null;
        arsort($filtered);
        return array_key_first($filtered);
    }

    /** Normalises concerns that may arrive as a CSV string or an array. */
    private function parseConcerns(mixed $raw): array
    {
        if (is_array($raw)) {
            return array_values(array_filter($raw));
        }
        if (is_string($raw)) {
            return array_values(array_filter(array_map('trim', explode(',', $raw))));
        }
        return [];
    }
}
