<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Bundle;
use App\Models\UserNotification;
use App\Services\LoyaltyService;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);
        if ($request->user()->role !== 'admin') {
            $query->where('user_id', $request->user()->id);
        }
        if ($request->status) $query->where('status', $request->status);
        return $this->apiResponse($query->latest()->paginate($request->per_page ?? 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,bundle',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|array',
            'coupon_code'       => 'nullable|string',
            'payment_method'    => 'nullable|string',
            'payment_reference' => 'nullable|string',
            'notes'             => 'nullable|string',
        ]);

        $subtotal = 0;
        $orderItems = [];

        foreach ($data['items'] as $item) {
            if ($item['type'] === 'bundle') {
                $model = Bundle::find($item['id']);
            } else {
                $model = Product::find($item['id']);
            }

            // Use backend price when product exists (prevents price tampering);
            // fall back to frontend-provided price for cart items not yet in DB.
            $name     = $model ? $model->name  : ($item['name']  ?? 'Item');
            $price    = $model ? $model->price  : (float) ($item['price'] ?? 0);
            $modelId   = $model ? $model->id    : $item['id'];
            $modelType = $item['type'] === 'bundle' ? Bundle::class : Product::class;

            $subtotal += $price * $item['quantity'];
            $orderItems[] = [
                'itemable_id'   => $modelId,
                'itemable_type' => $modelType,
                'name'          => $name,
                'quantity'      => $item['quantity'],
                'price'         => $price,
            ];
        }

        // Apply coupon discount if provided
        $discount = 0;
        if (!empty($data['coupon_code'])) {
            $promo = \App\Models\Promotion::where('code', $data['coupon_code'])
                ->where('is_active', true)
                ->first();
            if ($promo && !($promo->expires_at && $promo->expires_at->isPast())
                && !($promo->usage_limit && $promo->used_count >= $promo->usage_limit)) {
                $discount = $promo->type === 'percentage'
                    ? round($subtotal * $promo->value / 100, 2)
                    : (float) $promo->value;
                $promo->increment('used_count');
            }
        }

        $shipping      = $subtotal >= 50000 ? 0 : 2500;
        $orderTotal    = round($subtotal - $discount + $shipping, 2);
        $paymentMethod = $data['payment_method'] ?? null;
        $paymentRef    = $data['payment_reference'] ?? null;
        $paymentStatus = 'pending';

        // Server-side Paystack verification — prevents forged references
        if ($paymentMethod === 'paystack') {
            if (empty($paymentRef)) {
                return $this->apiResponse([], 'Payment reference is required for Paystack payments.', false, 422);
            }

            $paystack = new PaystackService();
            $txData   = $paystack->verifyTransaction($paymentRef);

            if (!$txData) {
                return $this->apiResponse([], 'Payment verification failed. Please try again or use another payment method.', false, 402);
            }

            if (!$paystack->amountMatches($txData, $orderTotal)) {
                return $this->apiResponse([], 'Payment amount does not match order total.', false, 402);
            }

            $paymentStatus = 'paid';
        }

        $order = Order::create([
            'order_number'      => 'KMH-' . strtoupper(Str::random(8)),
            'user_id'           => $request->user()->id,
            'subtotal'          => $subtotal,
            'discount'          => $discount,
            'shipping'          => $shipping,
            'total'             => $orderTotal,
            'shipping_address'  => $data['shipping_address'],
            'coupon_code'       => $data['coupon_code'] ?? null,
            'payment_method'    => $paymentMethod,
            'payment_reference' => $paymentRef,
            'payment_status'    => $paymentStatus,
            'notes'             => $data['notes'] ?? null,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        // Award loyalty points — 10 pts per ₦1,000 spent (after discount), multiplied by tier multiplier
        $user         = $request->user();
        $billableTotal = max(0, $subtotal - $discount);
        $basePoints   = (int) floor($billableTotal / 1000) * 10;
        $multiplier   = LoyaltyService::multiplierForTier($user->tier ?? 'starter');
        $earnedPoints = (int) round($basePoints * $multiplier);

        if ($earnedPoints > 0) {
            $isFirstOrder = Order::where('user_id', $user->id)->count() === 1;
            $eventType    = $isFirstOrder ? 'first_order' : 'purchase';
            $firstBonus   = $isFirstOrder ? 300 : 0;

            LoyaltyService::award($user, $eventType, $earnedPoints + $firstBonus,
                $order->order_number, 'order', $order->id);

            UserNotification::create([
                'user_id' => $user->id,
                'type'    => 'order',
                'title'   => "Order placed + {$earnedPoints} pts earned! 🎉",
                'message' => "Your order {$order->order_number} is confirmed. You earned {$earnedPoints} loyalty points." . ($isFirstOrder ? " Plus a 300-pt first order bonus!" : ""),
                'data'    => ['order_id' => $order->id, 'points' => $earnedPoints + $firstBonus],
            ]);
        }

        return $this->apiResponse($order->load('items'), 'Order placed', true, 201);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with(['items', 'user'])->findOrFail($id);
        if ($request->user()->role !== 'admin' && $order->user_id !== $request->user()->id) {
            return $this->apiResponse([], 'Unauthorized', false, 403);
        }
        return $this->apiResponse($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->validate([
            'status'          => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
            'payment_status'  => 'nullable|string',
            'admin_note'      => 'nullable|string|max:1000',
        ]);
        $order->update($data);
        return $this->apiResponse($order, 'Order updated');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return $this->apiResponse([], 'Order deleted');
    }
}
