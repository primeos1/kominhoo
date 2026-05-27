<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index(Request $request)
    {
        $query = Review::with('product');
        if ($request->product_id) $query->where('product_id', $request->product_id);
        if ($request->status) $query->where('status', $request->status);
        return $this->apiResponse($query->latest()->paginate($request->per_page ?? 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'reviewer_name' => 'required|string|max:100',
            'rating'        => 'required|integer|between:1,5',
            'title'         => 'nullable|string|max:200',
            'body'          => 'required|string',
            'skin_type'     => 'nullable|string',
        ]);

        $data['user_id'] = $request->user()?->id;
        $data['status']  = 'approved';

        $review = Review::create($data);

        // Immediately recalculate product rating and review count
        $product  = Product::find($data['product_id']);
        $approved = Review::where('product_id', $product->id)->where('status', 'approved');
        $product->update([
            'rating'       => round($approved->avg('rating'), 2),
            'review_count' => $approved->count(),
        ]);

        // Award loyalty points to authenticated reviewer
        if ($request->user()) {
            LoyaltyService::award($request->user(), 'review', 50, 'Product review submitted', 'review', $review->id);
        }

        return $this->apiResponse($review, 'Review published', true, 201);
    }

    public function show($id)
    {
        return $this->apiResponse(Review::with('product')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $data = $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $review->update($data);

        if ($data['status'] === 'approved') {
            $product = $review->product;
            $approved = Review::where('product_id', $product->id)->where('status', 'approved');
            $product->update([
                'rating' => round($approved->avg('rating'), 2),
                'review_count' => $approved->count(),
            ]);
        }

        return $this->apiResponse($review, 'Review updated');
    }

    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return $this->apiResponse([], 'Review deleted');
    }
}
