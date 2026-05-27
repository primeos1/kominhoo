<?php

namespace App\Http\Controllers;

use App\Support\CmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuizController extends Controller
{
    private string $api;

    public function __construct(
        private CmsContent $cmsContent
    ) {
        $this->api = config('app.api_base_url');
    }

    public function index()
    {
        $quizConfig = $this->cmsContent->all()['quiz'] ?? [];
        return view('pages.quiz', compact('quizConfig'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        // Forward auth token when user is logged in so the backend can link
        // the quiz result to the user account and update their skin profile.
        $http = session('api_token')
            ? Http::withToken(session('api_token'))
            : Http::asJson();

        $response = $http->post("{$this->api}/quiz", [
            'answers' => $request->input('answers'),
        ]);

        if ($response->successful()) {
            $data = $response->json('data');
            session(['quiz_result' => $data]);

            if (session('api_token') && !empty($data['skin_type'])) {
                $token   = session('api_token');
                $http    = Http::withToken($token);

                // 1. Update the user's skin profile (auth:sanctum route — always works)
                $http->patch("{$this->api}/auth/me", ['skin_type' => $data['skin_type']]);

                // 2. Claim the quiz result so it is linked to this user's account.
                //    This runs through a protected route, so auth is guaranteed.
                $resultId = $data['result']['id'] ?? null;
                if ($resultId) {
                    $http->post("{$this->api}/quiz/{$resultId}/claim");
                }

                // 3. Keep the session user in sync
                $sessionUser = session('user', []);
                $sessionUser['skin_type'] = $data['skin_type'];
                session(['user' => $sessionUser]);
            }

            return redirect()->route('results');
        }

        return back()->with('error', 'Quiz submission failed. Please try again.');
    }

    /**
     * Called via AJAX after each stage transition to persist the best current
     * skin-type estimate onto the user's profile without waiting for full submit.
     */
    public function saveProgress(Request $request)
    {
        if (!session('api_token')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 200);
        }

        $request->validate([
            'answers' => 'required|array',
            'stage'   => 'required|integer|min:1|max:5',
        ]);

        $response = Http::withToken(session('api_token'))
            ->post("{$this->api}/quiz/progress", [
                'answers' => $request->input('answers'),
                'stage'   => $request->input('stage'),
            ]);

        if ($response->successful()) {
            $data = $response->json('data');

            // Sync session user with latest estimated skin type
            if (session('user') && !empty($data['skin_type'])) {
                $user = session('user');
                $user['skin_type'] = $data['skin_type'];
                session(['user' => $user]);
            }

            return response()->json(['success' => true, 'skin_type' => $data['skin_type'] ?? null]);
        }

        return response()->json(['success' => false], 200);
    }
}
