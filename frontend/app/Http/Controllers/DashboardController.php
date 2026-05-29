<?php

namespace App\Http\Controllers;

use App\Support\CommunityStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    private string $api;

    public function __construct(private CommunityStore $communityStore)
    {
        $this->api = config('app.api_base_url');
    }

    public function index()
    {
        $user   = session('user');
        $token  = session('api_token');
        $orders = Http::withToken($token)
            ->get("{$this->api}/orders", ['per_page' => 5])->json('data.data') ?? [];

        // 1. Try the session quiz_result set immediately after quiz submission
        $latestQuizResult = $this->quizResultFromSession();

        // 2. If not in session, fetch from the API history (linked results)
        if (!$latestQuizResult) {
            $latestQuizResult = $this->fetchLatestQuizResult();
        }

        // Membership data — load in parallel where possible
        $loyaltySummary  = $this->fetchSafe("{$this->api}/loyalty/summary", $token);
        $subscription    = $this->fetchSafe("{$this->api}/subscriptions/my", $token);
        $referralData    = $this->fetchSafe("{$this->api}/referrals/my", $token);
        $notifData       = $this->fetchSafe("{$this->api}/notifications", $token);
        $pointEvents     = Http::withToken($token)->get("{$this->api}/loyalty/events", ['per_page' => 10])->json('data.data') ?? [];
        $subHistory      = Http::withToken($token)->get("{$this->api}/subscriptions/my/history")->json('data') ?? [];
        $walletData         = $this->fetchSafe("{$this->api}/wallet", $token);
        $walletTransactions = $this->fetchSafe("{$this->api}/wallet/transactions?per_page=20", $token);

        // Load config JSON
        $loyaltyConfig   = $this->loadJson('loyalty_tiers');
        $subscriptionPlans = $this->loadJson('subscription_plans');
        $paystackKey     = config('services.paystack.public_key');

        return view('pages.dashboard', compact(
            'user', 'orders', 'latestQuizResult',
            'loyaltySummary', 'subscription', 'referralData', 'notifData',
            'pointEvents', 'subHistory', 'loyaltyConfig', 'subscriptionPlans',
            'walletData', 'walletTransactions', 'paystackKey'
        ));
    }

    private function fetchSafe(string $url, ?string $token): array
    {
        try {
            return Http::withToken($token)->get($url)->json('data') ?? [];
        } catch (\Throwable) {
            return [];
        }
    }

    private function loadJson(string $name): array
    {
        $path = storage_path("app/cms/{$name}.json");
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?? [];
    }

    private function quizResultFromSession(): ?array
    {
        $data = session('quiz_result');
        if (!$data) return null;

        // Normalise into a flat structure the view can use
        $result = $data['result'] ?? [];
        return [
            'skin_type'   => $data['skin_type']   ?? $result['skin_type']   ?? null,
            'skin_scores' => $data['skin_scores']  ?? $result['skin_scores'] ?? null,
            'answers'     => $result['answers']    ?? [],
            'created_at'  => $result['created_at'] ?? null,
        ];
    }

    private function fetchLatestQuizResult(): ?array
    {
        try {
            $history = Http::withToken(session('api_token'))
                ->get("{$this->api}/quiz/history")
                ->json('data') ?? [];

            if (!empty($history)) {
                return $history[0]; // already sorted latest first
            }
        } catch (\Throwable $e) {
            // backend unavailable — fall through
        }
        return null;
    }

    public function orders()
    {
        $token  = session('api_token');
        $orders = Http::withToken($token)->get("{$this->api}/orders")->json('data.data') ?? [];

        $loyaltySummary    = $this->fetchSafe("{$this->api}/loyalty/summary", $token);
        $subscription      = $this->fetchSafe("{$this->api}/subscriptions/my", $token);
        $referralData      = $this->fetchSafe("{$this->api}/referrals/my", $token);
        $notifData         = $this->fetchSafe("{$this->api}/notifications", $token);
        $pointEvents       = Http::withToken($token)->get("{$this->api}/loyalty/events", ['per_page' => 10])->json('data.data') ?? [];
        $subHistory        = Http::withToken($token)->get("{$this->api}/subscriptions/my/history")->json('data') ?? [];
        $walletData         = $this->fetchSafe("{$this->api}/wallet", $token);
        $walletTransactions = $this->fetchSafe("{$this->api}/wallet/transactions?per_page=20", $token);
        $loyaltyConfig      = $this->loadJson('loyalty_tiers');
        $subscriptionPlans  = $this->loadJson('subscription_plans');
        $latestQuizResult   = $this->quizResultFromSession() ?? $this->fetchLatestQuizResult();

        return view('pages.dashboard', compact(
            'orders', 'loyaltySummary', 'subscription', 'referralData',
            'notifData', 'pointEvents', 'subHistory', 'loyaltyConfig',
            'subscriptionPlans', 'latestQuizResult', 'walletData', 'walletTransactions'
        ) + ['user' => session('user'), 'active_tab' => 'orders']);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
        ]);

        $response = Http::withToken(session('api_token'))
            ->patch("{$this->api}/auth/me", $request->only('name', 'phone', 'birthday'));

        if ($response->successful()) {
            session(['user' => $response->json('data')]);
            return back()->with('profile_success', 'Personal info saved!');
        }

        return back()->with('profile_error', 'Could not save. Please try again.');
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'state'         => 'nullable|string|max:100',
        ]);

        $response = Http::withToken(session('api_token'))
            ->patch("{$this->api}/auth/me", $request->only('address_line1', 'address_line2', 'city', 'state'));

        if ($response->successful()) {
            session(['user' => $response->json('data')]);
            return back()->with('address_success', 'Address saved!');
        }

        return back()->with('address_error', 'Could not save address. Please try again.');
    }

    public function updateEmailPrefs(Request $request)
    {
        $prefs = [
            'routine_tips'    => (bool) $request->input('routine_tips'),
            'new_products'    => (bool) $request->input('new_products'),
            'subscription'    => (bool) $request->input('subscription'),
            'promotions'      => (bool) $request->input('promotions'),
            'loyalty_updates' => (bool) $request->input('loyalty_updates'),
        ];

        $response = Http::withToken(session('api_token'))
            ->patch("{$this->api}/auth/me", ['email_prefs' => $prefs]);

        if ($response->successful()) {
            session(['user' => $response->json('data')]);
            return back()->with('prefs_success', 'Email preferences updated!');
        }

        return back()->with('prefs_error', 'Could not save preferences. Please try again.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp',
        ]);

        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $path = $request->file('avatar')->storePublicly('avatars', ['disk' => $disk]);
        $url  = Storage::disk($disk)->url($path);

        $response = Http::withToken(session('api_token'))
            ->patch("{$this->api}/auth/me", ['avatar' => $url]);

        if ($response->successful()) {
            session(['user' => $response->json('data')]);
            return back()->with('avatar_success', 'Profile picture updated!');
        }

        return back()->with('avatar_error', 'Could not upload photo. Please try again.');
    }

    public function routineData(): \Illuminate\Http\JsonResponse
    {
        $token = session('api_token');

        $steps = $this->fetchSafe("{$this->api}/routine/steps", $token);
        if (empty($steps)) {
            $steps = ['am' => [], 'pm' => []];
        }

        $logs = $this->fetchSafe("{$this->api}/routine/logs", $token);

        return response()->json(['success' => true, 'data' => array_merge($steps, $logs)]);
    }

    public function logRoutine(Request $request): \Illuminate\Http\JsonResponse
    {
        $token = session('api_token');

        try {
            $payload = [
                'tab'       => $request->input('tab'),
                'steps'     => $request->input('steps', []),
                'mark_done' => (bool) $request->input('mark_done', false),
            ];
            if ($request->has('date')) {
                $payload['date'] = $request->input('date');
            }

            $resp = Http::withToken($token)->asJson()
                ->post("{$this->api}/routine/log", $payload);

            return response()->json($resp->json() ?? ['success' => false, 'message' => 'Empty response from server.'], $resp->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Could not save routine. Please try again.'], 503);
        }
    }

    // ── Security ──────────────────────────────────────────────────

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        $response = Http::withToken(session('api_token'))
            ->post("{$this->api}/auth/change-password", [
                'current_password'      => $request->current_password,
                'password'              => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

        if ($response->successful()) {
            $this->logSecurityEvent('password_change', 'User changed their account password', $request);
            return back()->with('security_success', 'Password updated successfully!');
        }

        $errors  = $response->json('errors') ?? [];
        $message = $response->json('message') ?? 'Could not update password. Please try again.';

        if (!empty($errors['current_password'])) {
            return back()->with('security_error', 'Current password is incorrect. Please try again.');
        }

        return back()->with('security_error', $message);
    }

    public function updateSecuritySettings(Request $request)
    {
        $settings = [
            'two_factor'          => $request->boolean('two_factor'),
            'login_notifications' => $request->boolean('login_notifications'),
            'sms_alerts'          => $request->boolean('sms_alerts'),
            'save_sessions'       => $request->boolean('save_sessions'),
        ];

        $user       = session('user') ?? [];
        $emailPrefs = is_array($user['email_prefs'] ?? null) ? $user['email_prefs'] : [];
        $emailPrefs['security'] = $settings;

        $response = Http::withToken(session('api_token'))
            ->patch("{$this->api}/auth/me", ['email_prefs' => $emailPrefs]);

        if ($response->successful()) {
            $updated = $response->json('data');
            if ($updated) session(['user' => $updated]);

            $changed = $request->input('changed_setting', 'security settings');
            $this->logSecurityEvent('settings_change', "Security setting changed: {$changed}", $request);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Could not save settings.'], 422);
    }

    public function requestAccountDeletion(Request $request)
    {
        $request->validate(['reason' => 'nullable|string|max:1000']);

        $user = session('user') ?? [];

        $this->logSecurityEvent(
            'account_deletion_request',
            'Account deletion requested. Reason: ' . ($request->reason ?: 'Not provided'),
            $request,
            'high'
        );

        try {
            Http::withToken(session('api_token'))->post("{$this->api}/notifications/send", [
                'type'    => 'account_deletion',
                'title'   => 'Account Deletion Request',
                'message' => ($user['name'] ?? 'A user') . ' (' . ($user['email'] ?? '') . ') requested account deletion. Reason: ' . ($request->reason ?: 'Not provided'),
            ]);
        } catch (\Throwable) {
            // Non-fatal
        }

        $email = $user['email'] ?? 'your email address';
        return back()->with('security_success', "Your deletion request has been received. Our team will contact you at {$email} within 3 business days.");
    }

    private function logSecurityEvent(string $type, string $description, Request $request, string $severity = 'normal'): void
    {
        $user   = session('user') ?? [];
        $events = $this->loadSecurityEvents();

        array_unshift($events, [
            'id'          => uniqid('sec_', true),
            'user_id'     => $user['id'] ?? null,
            'user_name'   => $user['name'] ?? 'Unknown',
            'user_email'  => $user['email'] ?? 'Unknown',
            'type'        => $type,
            'description' => $description,
            'severity'    => $severity,
            'ip'          => $request->ip(),
            'user_agent'  => $request->header('User-Agent', ''),
            'created_at'  => now()->toISOString(),
        ]);

        if (count($events) > 500) {
            $events = array_slice($events, 0, 500);
        }

        file_put_contents(
            storage_path('app/security_events.json'),
            json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function loadSecurityEvents(): array
    {
        $path = storage_path('app/security_events.json');
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?? [];
    }

    public function communityPosts(Request $request)
    {
        $userEmail = session('user.email') ?? $request->ip();
        $all   = $this->communityStore->getPosts('all');
        $posts = array_values(array_filter($all, fn($p) => ($p['user']['email'] ?? '') === $userEmail));

        usort($posts, fn($a, $b) => strcmp($b['submitted_at'] ?? '', $a['submitted_at'] ?? ''));

        foreach ($posts as &$post) {
            $post['time'] = $this->relativeTime($post['submitted_at'] ?? null);
            unset($post['liked_by'], $post['saved_by']);
        }
        unset($post);

        return response()->json(['posts' => array_values($posts)]);
    }

    private function relativeTime(?string $iso): string
    {
        if (!$iso) return 'Recently';
        try {
            $diff = now()->diffInSeconds(\Carbon\Carbon::parse($iso));
        } catch (\Exception $e) {
            return 'Recently';
        }
        if ($diff < 60)     return 'Just now';
        if ($diff < 3600)   return floor($diff / 60) . ' min ago';
        if ($diff < 86400)  return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800) return floor($diff / 86400) . ' day' . ($diff / 86400 >= 2 ? 's' : '') . ' ago';
        return floor($diff / 604800) . ' week' . ($diff / 604800 >= 2 ? 's' : '') . ' ago';
    }
}
