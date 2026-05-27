<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    private string $api;

    public function __construct()
    {
        $this->api = config('app.api_base_url');
    }

    // ── Loyalty ──────────────────────────────────────────────────

    public function loyaltySummary()
    {
        $resp = Http::withToken(session('api_token'))
            ->get("{$this->api}/loyalty/summary");
        return response()->json($resp->json(), $resp->status());
    }

    public function loyaltyEvents(Request $request)
    {
        $resp = Http::withToken(session('api_token'))
            ->get("{$this->api}/loyalty/events", $request->only('per_page', 'page'));
        return response()->json($resp->json(), $resp->status());
    }

    public function redeemPoints(Request $request)
    {
        $resp = Http::withToken(session('api_token'))
            ->post("{$this->api}/loyalty/redeem", $request->only('points', 'note'));
        return response()->json($resp->json(), $resp->status());
    }

    public function loyaltyTiersConfig()
    {
        $json = Storage::disk('local')->get('cms/loyalty_tiers.json');
        return response()->json(json_decode($json, true));
    }

    // ── Subscriptions ────────────────────────────────────────────

    public function mySubscription()
    {
        $resp = Http::withToken(session('api_token'))
            ->get("{$this->api}/subscriptions/my");
        return response()->json($resp->json(), $resp->status());
    }

    public function subscriptionHistory()
    {
        $resp = Http::withToken(session('api_token'))
            ->get("{$this->api}/subscriptions/my/history");
        return response()->json($resp->json(), $resp->status());
    }

    public function subscribe(Request $request)
    {
        $resp = Http::withToken(session('api_token'))
            ->post("{$this->api}/subscriptions", $request->only('plan_id', 'plan_name', 'plan_price', 'billing_cycle'));

        if ($resp->successful()) {
            // Refresh user session with updated subscription info
            $me = Http::withToken(session('api_token'))->get("{$this->api}/auth/me")->json('data');
            if ($me) session(['user' => $me]);
        }

        return response()->json($resp->json(), $resp->status());
    }

    public function updateSubscription(Request $request, $id)
    {
        $resp = Http::withToken(session('api_token'))
            ->patch("{$this->api}/subscriptions/{$id}", $request->only('action', 'notes'));
        return response()->json($resp->json(), $resp->status());
    }

    public function subscriptionPlans()
    {
        $json = Storage::disk('local')->get('cms/subscription_plans.json');
        return response()->json(json_decode($json, true));
    }

    // ── Referrals ────────────────────────────────────────────────

    public function myReferrals()
    {
        $resp = Http::withToken(session('api_token'))
            ->get("{$this->api}/referrals/my");
        return response()->json($resp->json(), $resp->status());
    }

    public function applyReferral(Request $request)
    {
        $resp = Http::withToken(session('api_token'))
            ->post("{$this->api}/referrals/apply", $request->only('referral_code'));
        return response()->json($resp->json(), $resp->status());
    }

    // ── Notifications ─────────────────────────────────────────────

    public function notifications(Request $request)
    {
        $resp = Http::withToken(session('api_token'))
            ->get("{$this->api}/notifications", $request->only('per_page', 'page'));
        return response()->json($resp->json(), $resp->status());
    }

    public function markNotificationRead($id)
    {
        $resp = Http::withToken(session('api_token'))
            ->post("{$this->api}/notifications/{$id}/read");
        return response()->json($resp->json(), $resp->status());
    }

    public function markAllNotificationsRead()
    {
        $resp = Http::withToken(session('api_token'))
            ->post("{$this->api}/notifications/read-all");
        return response()->json($resp->json(), $resp->status());
    }

    public function deleteNotification($id)
    {
        $resp = Http::withToken(session('api_token'))
            ->delete("{$this->api}/notifications/{$id}");
        return response()->json($resp->json(), $resp->status());
    }
}
