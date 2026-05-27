<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index()
    {
        return $this->apiResponse(Promotion::latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:promotions',
            'name' => 'required|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        return $this->apiResponse(Promotion::create($data), 'Coupon created', true, 201);
    }

    public function show($id)
    {
        return $this->apiResponse(Promotion::findOrFail($id));
    }

    public function applyCode(Request $request)
    {
        $request->validate(['code' => 'required|string', 'order_total' => 'required|numeric']);

        $promo = Promotion::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$promo) return $this->apiResponse([], 'Invalid coupon code', false, 422);
        if ($promo->expires_at && $promo->expires_at->isPast()) return $this->apiResponse([], 'Coupon has expired', false, 422);
        if ($promo->usage_limit && $promo->used_count >= $promo->usage_limit) return $this->apiResponse([], 'Coupon usage limit reached', false, 422);
        if ($request->order_total < $promo->minimum_order) return $this->apiResponse([], "Minimum order of ₦{$promo->minimum_order} required", false, 422);

        $discount = $promo->type === 'percentage'
            ? ($request->order_total * $promo->value / 100)
            : $promo->value;

        return $this->apiResponse(['promotion' => $promo, 'discount' => $discount], 'Coupon applied');
    }

    public function update(Request $request, $id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->update($request->validate([
            'name' => 'sometimes|string',
            'type' => 'sometimes|in:percentage,fixed',
            'value' => 'sometimes|numeric|min:0',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date',
        ]));
        return $this->apiResponse($promo, 'Coupon updated');
    }

    public function destroy($id)
    {
        Promotion::findOrFail($id)->delete();
        return $this->apiResponse([], 'Coupon deleted');
    }
}
