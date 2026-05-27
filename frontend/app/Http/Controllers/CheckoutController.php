<?php

namespace App\Http\Controllers;

use App\Services\CouponService;
use App\Services\GiftCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    private string $api;

    public function __construct()
    {
        $this->api = config('app.api_base_url');
    }

    public function index()
    {
        $walletBalance = 0.0;
        $walletStatus  = 'inactive';

        if (session('api_token')) {
            try {
                $wd = Http::withToken(session('api_token'))
                    ->get("{$this->api}/wallet")
                    ->json('data.wallet') ?? [];
                $walletBalance = (float) ($wd['available_balance'] ?? 0);
                $walletStatus  = $wd['status'] ?? 'active';
            } catch (\Throwable) {}
        }

        return view('pages.checkout', [
            'paystackKey'   => config('services.paystack.public_key'),
            'user'          => session('user'),
            'isGuest'       => !session('api_token'),
            'walletBalance' => $walletBalance,
            'walletStatus'  => $walletStatus,
        ]);
    }

    public function listVouchers()
    {
        $today   = now()->toDateString();
        $all     = (new CouponService())->all();
        $visible = array_values(array_filter($all, function ($c) use ($today) {
            return ($c['active'] ?? false)
                && (empty($c['expiry_date']) || $c['expiry_date'] >= $today)
                && (empty($c['start_date'])  || $c['start_date']  <= $today)
                && (empty($c['max_uses'])    || ($c['use_count'] ?? 0) < $c['max_uses']);
        }));

        return response()->json(['success' => true, 'data' => $visible]);
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:64',
            'order_total' => 'required|numeric|min:0',
        ]);

        $result = (new CouponService())->validate(
            $request->input('code'),
            (float) $request->input('order_total'),
            $this->buildUserContext()
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    private function buildUserContext(): array
    {
        $user    = session('user') ?? [];
        $isGuest = !session('api_token');

        // Loyalty tier — normalise to lowercase short form (glow | radiant | luxe | null)
        $rawTier = strtolower($user['loyalty_tier'] ?? $user['tier'] ?? $user['membership_tier'] ?? '');
        $tier = match (true) {
            str_contains($rawTier, 'luxe')    => 'luxe',
            str_contains($rawTier, 'radiant') => 'radiant',
            str_contains($rawTier, 'glow')    => 'glow',
            default                           => null,
        };

        // New customer = logged-in user with zero completed orders
        $orderCount    = (int) ($user['orders_count'] ?? $user['total_orders'] ?? -1);
        $isNewCustomer = !$isGuest && $orderCount === 0;

        return [
            'is_guest'       => $isGuest,
            'is_new_customer'=> $isNewCustomer,
            'tier'           => $tier,
            'user_id'        => $user['id'] ?? null,
        ];
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'items'            => 'required|array',
            'shipping_address' => 'required|array',
        ]);

        // Increment coupon use count (global + per-user) when order is placed
        if ($request->filled('coupon_code')) {
            $userId = session('user.id') ?? session('user')['id'] ?? null;
            (new CouponService())->incrementUseCount($request->input('coupon_code'), $userId);
        }

        // Redeem gift card balance if applied
        if ($request->filled('gift_card_code') && (int) $request->input('gift_card_discount', 0) > 0) {
            (new GiftCardService())->redeem(
                $request->input('gift_card_code'),
                (int) $request->input('gift_card_discount')
            );
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->withToken(session('api_token'))
                ->post("{$this->api}/orders", $request->all());

            $json = $response->json();

            if ($response->successful()) {
                return response()->json($json, $response->status());
            }

            return response()->json([
                'success' => false,
                'message' => $json['message'] ?? 'Could not place your order. Please try again.',
            ], $response->status());

        } catch (\Illuminate\Http\Client\ConnectionException) {
            return response()->json([
                'success' => false,
                'message' => 'Could not connect to the server. Please try again later.',
            ], 503);
        }
    }
}
