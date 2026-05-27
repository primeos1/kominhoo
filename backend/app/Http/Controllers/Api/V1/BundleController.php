<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BundleController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index(Request $request)
    {
        $query = Bundle::with('products')->where('is_active', true);
        if ($request->skin_type) $query->where('skin_type', $request->skin_type);
        return $this->apiResponse($query->latest()->paginate($request->per_page ?? 12));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'skin_type' => 'nullable|string',
            'image' => 'nullable|string',
            'product_ids' => 'nullable|array',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $bundle = Bundle::create($data);
        if ($productIds) $bundle->products()->attach($productIds);

        return $this->apiResponse($bundle->load('products'), 'Bundle created', true, 201);
    }

    public function show($id)
    {
        return $this->apiResponse(Bundle::with('products')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $bundle = Bundle::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'skin_type' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
            'product_ids' => 'nullable|array',
        ]);

        $productIds = $data['product_ids'] ?? null;
        unset($data['product_ids']);
        $bundle->update($data);
        if ($productIds !== null) $bundle->products()->sync($productIds);

        return $this->apiResponse($bundle->load('products'), 'Bundle updated');
    }

    public function destroy($id)
    {
        Bundle::findOrFail($id)->delete();
        return $this->apiResponse([], 'Bundle deleted');
    }
}
