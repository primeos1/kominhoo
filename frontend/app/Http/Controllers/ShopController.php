<?php

namespace App\Http\Controllers;

use App\Data\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    private string $api;

    public function __construct()
    {
        $this->api = config('app.api_base_url');
    }

    public function index(Request $request)
    {
        $params = $request->only('category', 'brand', 'skin_type', 'search', 'page');
        $params['per_page'] = 12;

        try {
            $response = Http::get("{$this->api}/products", $params)->json('data') ?? [];
            $products = $response['data'] ?? $response ?? [];
            $bundles  = Http::get("{$this->api}/bundles")->json('data.data') ?? [];
            $guides   = Http::get("{$this->api}/guides")->json('data.data') ?? [];
        } catch (\Exception $e) {
            $products = [];
            $bundles  = [];
            $guides   = [];
        }

        $subscriptionPlans = [];
        try {
            $raw = Storage::disk('local')->get('cms/subscription_plans.json');
            if ($raw) {
                $subscriptionPlans = collect(json_decode($raw, true))
                    ->filter(fn($p) => $p['is_active'] ?? true)
                    ->values()
                    ->all();
            }
        } catch (\Throwable $e) {}

        return view('pages.shop', compact('products', 'bundles', 'guides', 'subscriptionPlans'));
    }

    public function product($id)
    {
        // ── Main product ──────────────────────────────────────────────────────
        try {
            $apiProduct = Http::get("{$this->api}/products/{$id}")->json('data');
            $product = $apiProduct ? $this->normalizeProduct($apiProduct) : null;
        } catch (\Exception $e) {
            $product = null;
        }

        // Fallback to local static data if API unavailable
        if (!$product) {
            $local = Products::find((int) $id);
            $product = $local ? $this->normalizeProduct($local) : null;
        }

        if (!$product) abort(404);

        // ── Reviews (approved only) ────────────────────────────────────────────
        try {
            $reviews = Http::get("{$this->api}/reviews", [
                'product_id' => $id,
                'status'     => 'approved',
                'per_page'   => 20,
            ])->json('data.data') ?? [];
        } catch (\Exception $e) {
            $reviews = [];
        }

        // ── Related products (same category, from API) ─────────────────────────
        try {
            $relResponse = Http::get("{$this->api}/products", [
                'category' => $product['category'],
                'per_page' => 9,
            ])->json('data.data') ?? [];

            $relatedProducts = collect($relResponse)
                ->filter(fn($p) => (int)($p['id'] ?? 0) !== (int)$id)
                ->take(8)
                ->map(fn($p) => $this->normalizeProduct($p))
                ->values()
                ->all();
        } catch (\Exception $e) {
            $relatedProducts = collect(Products::all())
                ->filter(fn($p) => strtolower($p['category']) === strtolower($product['category'])
                    && (int)$p['id'] !== (int)$id)
                ->take(8)
                ->values()
                ->all();
        }

        return view('pages.product', compact('product', 'reviews', 'relatedProducts'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'reviewer_name' => 'required|string',
            'rating'        => 'required|integer|between:1,5',
            'body'          => 'required|string|min:10',
        ]);

        $token = session('api_token');
        $http  = $token ? Http::withToken($token) : Http::withHeaders([]);

        $response = $http->post("{$this->api}/reviews", array_merge(
            $request->only('reviewer_name', 'rating', 'title', 'body', 'skin_type'),
            ['product_id' => $id]
        ));

        if (!$response->successful()) {
            return back()->withErrors(['body' => $response->json('message') ?? 'Please log in to leave a review.']);
        }

        return back()->with('review_success', 'Your review has been published! Thank you for sharing your experience.');
    }

    // ── Normalize product array to ensure the view always has every key ────────
    private function normalizeProduct(array $p): array
    {
        // Compute in_stock from stock column (API appends it, local data has it directly)
        if (!array_key_exists('in_stock', $p)) {
            $p['in_stock'] = ($p['stock'] ?? 0) > 0;
        }

        // Normalize concern → concerns (local data uses 'concern')
        if (!isset($p['concerns']) && isset($p['concern'])) {
            $p['concerns'] = $p['concern'];
        }

        // Ensure array fields are actually arrays (API casts them, local data has native arrays)
        foreach (['skin_types', 'concerns', 'ingredients', 'images'] as $key) {
            if (!isset($p[$key])) {
                $p[$key] = [];
            } elseif (is_string($p[$key])) {
                $p[$key] = json_decode($p[$key], true) ?? [];
            }
        }

        // If images is empty, wrap the single 'image' URL into an array
        if (empty($p['images']) && !empty($p['image'])) {
            $p['images'] = [$p['image']];
        }

        return $p;
    }
}
