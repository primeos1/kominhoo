<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PointEvent;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    /**
     * GET /api/v1/loyalty/events
     * Paginated point event history for the authenticated user.
     */
    public function events(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 20), 100);

        $events = PointEvent::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json(['success' => true, 'data' => $events]);
    }

    /**
     * GET /api/v1/loyalty/summary
     * Current points, tier, next tier info and progress percentage.
     */
    public function summary(Request $request)
    {
        $user   = $request->user();
        $points = (int) ($user->loyalty_points ?? 0);

        // Always derive tier from actual points — never trust a stale stored value
        $tier = LoyaltyService::tierForPoints($points);
        if ($user->tier !== $tier) {
            $user->update(['tier' => $tier]);
        }

        $next = LoyaltyService::nextTier($tier);

        $tierNames = [
            'starter' => 'Starter Glow',
            'glow'    => 'Glow Member',
            'radiant' => 'Radiant Insider',
            'iconic'  => 'Iconic Luminary',
        ];

        $progressPct = 0;
        if ($next) {
            $currentMin  = $this->tierMin($tier);
            $range       = $next['min'] - $currentMin;
            $earned      = $points - $currentMin;
            $progressPct = $range > 0 ? min(100, (int) round($earned / $range * 100)) : 100;
        } else {
            $progressPct = 100;
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'points'       => $points,
                'tier'         => $tier,
                'tier_name'    => $tierNames[$tier] ?? ucfirst($tier),
                'next_tier'    => $next ? $next['id'] : null,
                'next_tier_name' => $next ? ($tierNames[$next['id']] ?? ucfirst($next['id'])) : null,
                'next_tier_min'  => $next ? $next['min'] : null,
                'points_to_next' => $next ? max(0, $next['min'] - $points) : 0,
                'progress_pct'   => $progressPct,
                'multiplier'     => LoyaltyService::multiplierForTier($tier),
            ],
        ]);
    }

    /**
     * POST /api/v1/loyalty/award  (admin only)
     * Manually award or deduct points from a user.
     */
    public function award(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points'  => 'required|integer|not_in:0',
            'note'    => 'nullable|string|max:255',
        ]);

        $user  = \App\Models\User::findOrFail($data['user_id']);
        $event = LoyaltyService::award($user, 'manual', $data['points'], $data['note'] ?? '');

        return response()->json(['success' => true, 'data' => $event]);
    }

    /**
     * POST /api/v1/loyalty/redeem  (auth user)
     * Redeem points (deduct from balance).
     */
    public function redeem(Request $request)
    {
        $data = $request->validate([
            'points' => 'required|integer|min:1',
            'note'   => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        if ($user->loyalty_points < $data['points']) {
            return response()->json(['success' => false, 'message' => 'Insufficient points.'], 422);
        }

        $event = LoyaltyService::award($user, 'redeem', -$data['points'], $data['note'] ?? 'Points redeemed');

        return response()->json(['success' => true, 'data' => $event]);
    }

    private function tierMin(string $tier): int
    {
        $mins = ['starter' => 0, 'glow' => 500, 'radiant' => 1500, 'iconic' => 5000];
        return $mins[$tier] ?? 0;
    }
}
