<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true);

        if ($request->category) $query->where('category', $request->category);
        if ($request->brand)    $query->where('brand', $request->brand);
        if ($request->skin_type) $query->whereJsonContains('skin_types', $request->skin_type);
        if ($request->featured) $query->where('is_featured', true);
        if ($request->search)   $query->where('name', 'like', "%{$request->search}%");

        $products = $query->latest()->paginate($request->per_page ?? 12);

        return $this->apiResponse($products);
    }

    public function show($id)
    {
        $product = Product::with([
            'reviews' => fn($q) => $q->where('status', 'approved')->latest()->limit(20),
        ])->findOrFail($id);

        return $this->apiResponse($product);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'brand'          => 'required|string',
            'category'       => 'required|string',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'skin_types'     => 'nullable|array',
            'images'         => 'nullable|array',
            'size'           => 'nullable|string',
            'is_featured'    => 'boolean',
            'concerns'        => 'nullable|array',
            'routine_step'    => 'nullable|string',
            'time_of_use'     => 'nullable|string',
            'texture'         => 'nullable|string',
            'sensitivity'     => 'nullable|string',
            'ingredients'     => 'nullable|array',
            'badge'           => 'nullable|string',
            'pro_tip'         => 'nullable|string',
            'ingredient_info' => 'nullable|array',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $product = Product::create($data);

        return $this->apiResponse($product, 'Product created', true, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name'           => 'sometimes|string|max:255',
            'brand'          => 'sometimes|string',
            'category'       => 'sometimes|string',
            'price'          => 'sometimes|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock'          => 'sometimes|integer|min:0',
            'description'    => 'nullable|string',
            'skin_types'     => 'nullable|array',
            'images'         => 'nullable|array',
            'size'           => 'nullable|string',
            'is_featured'    => 'boolean',
            'is_active'      => 'boolean',
            'concerns'        => 'nullable|array',
            'routine_step'    => 'nullable|string',
            'time_of_use'     => 'nullable|string',
            'texture'         => 'nullable|string',
            'sensitivity'     => 'nullable|string',
            'ingredients'     => 'nullable|array',
            'badge'           => 'nullable|string',
            'pro_tip'         => 'nullable|string',
            'ingredient_info' => 'nullable|array',
        ]);

        $product->update($data);
        return $this->apiResponse($product, 'Product updated');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return $this->apiResponse([], 'Product deleted');
    }
}
