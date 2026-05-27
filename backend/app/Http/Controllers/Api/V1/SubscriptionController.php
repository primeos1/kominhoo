<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserNotification;
use App\Services\LoyaltyService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * GET /api/v1/subscriptions/my
     * Current (active or paused) subscription for the auth user.
     */
    public function my(Request $request)
    {
        $sub = Subscription::where('user_id', $request->user()->id)
            ->whereIn('status', ['active', 'paused'])
            ->latest()
            ->first();

        return response()->json(['success' => true, 'data' => $sub]);
    }

    /**
     * GET /api/v1/subscriptions/my/history
     * All subscription records for the auth user.
     */
    public function history(Request $request)
    {
        $history = Subscription::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $history]);
    }

    /**
     * POST /api/v1/subscriptions
     * Subscribe the auth user to a plan.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_id'      => 'required|string|max:64',
            'plan_name'    => 'required|string|max:128',
            'plan_price'   => 'required|integer|min:0',
            'billing_cycle'=> 'nullable|in:monthly,quarterly,biannual,annual',
        ]);

        $user = $request->user();

        // Cancel any existing active/paused subscription first
        Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active', 'paused'])
            ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        $nextBilling = $this->nextBillingDate($data['billing_cycle'] ?? 'monthly');

        $sub = Subscription::create([
            'user_id'          => $user->id,
            'plan_id'          => $data['plan_id'],
            'plan_name'        => $data['plan_name'],
            'plan_price'       => $data['plan_price'],
            'billing_cycle'    => $data['billing_cycle'] ?? 'monthly',
            'status'           => 'active',
            'next_billing_date'=> $nextBilling,
            'started_at'       => now(),
        ]);

        UserNotification::create([
            'user_id' => $user->id,
            'type'    => 'subscription',
            'title'   => "Welcome to {$data['plan_name']}! 📬",
            'message' => "Your {$data['plan_name']} subscription is now active. Your first box will be curated and shipped before {$nextBilling->format('F j, Y')}.",
            'data'    => ['plan_id' => $data['plan_id'], 'subscription_id' => $sub->id],
        ]);

        return response()->json(['success' => true, 'data' => $sub], 201);
    }

    /**
     * PATCH /api/v1/subscriptions/{id}
     * Pause, resume or cancel the subscription.
     */
    public function update(Request $request, $id)
    {
        $sub = Subscription::where('user_id', $request->user()->id)->findOrFail($id);

        $data = $request->validate([
            'action' => 'required|in:pause,resume,cancel',
            'notes'  => 'nullable|string|max:1000',
        ]);

        switch ($data['action']) {
            case 'pause':
                $sub->update(['status' => 'paused', 'paused_at' => now(), 'notes' => $data['notes'] ?? null]);
                UserNotification::create([
                    'user_id' => $request->user()->id,
                    'type'    => 'subscription',
                    'title'   => 'Subscription paused',
                    'message' => "Your {$sub->plan_name} subscription has been paused. Resume any time from your dashboard.",
                    'data'    => ['subscription_id' => $sub->id],
                ]);
                break;

            case 'resume':
                $nextBilling = $this->nextBillingDate($sub->billing_cycle);
                $sub->update(['status' => 'active', 'paused_at' => null, 'next_billing_date' => $nextBilling]);
                UserNotification::create([
                    'user_id' => $request->user()->id,
                    'type'    => 'subscription',
                    'title'   => 'Subscription resumed! 🎉',
                    'message' => "Your {$sub->plan_name} subscription is active again. Next box due: {$nextBilling->format('F j, Y')}.",
                    'data'    => ['subscription_id' => $sub->id],
                ]);
                break;

            case 'cancel':
                $sub->update(['status' => 'cancelled', 'cancelled_at' => now(), 'notes' => $data['notes'] ?? null]);
                UserNotification::create([
                    'user_id' => $request->user()->id,
                    'type'    => 'subscription',
                    'title'   => 'Subscription cancelled',
                    'message' => "Your {$sub->plan_name} subscription has been cancelled. You can resubscribe any time.",
                    'data'    => ['subscription_id' => $sub->id],
                ]);
                break;
        }

        return response()->json(['success' => true, 'data' => $sub->fresh()]);
    }

    /**
     * GET /api/v1/subscriptions (admin)
     * All subscriptions with user details.
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 50), 200);
        $subs = Subscription::with('user:id,name,email,tier,loyalty_points')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json(['success' => true, 'data' => $subs]);
    }

    private function nextBillingDate(string $cycle): Carbon
    {
        return match ($cycle) {
            'quarterly' => now()->addMonths(3),
            'biannual'  => now()->addMonths(6),
            'annual'    => now()->addYear(),
            default     => now()->addMonth(),
        };
    }
}
