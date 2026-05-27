<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\CouponService;
use App\Support\CmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct(
        private CmsContent $cmsContent
    ) {
    }

    private function api(): string
    {
        return rtrim(config('app.api_base_url'), '/');
    }

    private function backendToken(bool $fresh = false): ?string
    {
        if (!$fresh) {
            $token = session('backend_admin_token');
            if ($token) return $token;
        }

        try {
            $resp = Http::timeout(5)->post("{$this->api()}/auth/login", [
                'email'    => env('BACKEND_ADMIN_EMAIL', 'admin@kominhoo.com'),
                'password' => env('BACKEND_ADMIN_PASSWORD', 'admin1234'),
            ]);
            if ($resp->successful() && $resp->json('success')) {
                $token = $resp->json('data.token');
                session(['backend_admin_token' => $token]);
                return $token;
            }
        } catch (\Exception $e) {
            // Backend unavailable
        }
        return null;
    }

    /** Proxy a backend GET/POST/etc. — retries once with a fresh token on 401. */
    private function backendProxy(string $method, string $path, array $data = []): \Illuminate\Http\JsonResponse
    {
        $api   = $this->api();
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        }

        $resp = Http::withToken($token)->$method("{$api}{$path}", $data);

        // Stale token — clear cache and retry once
        if ($resp->status() === 401) {
            session()->forget('backend_admin_token');
            $token = $this->backendToken(fresh: true);
            if (!$token) {
                return response()->json(['success' => false, 'message' => 'Backend authentication failed.'], 503);
            }
            $resp = Http::withToken($token)->$method("{$api}{$path}", $data);
        }

        $json = $resp->json();
        if ($json === null) {
            return response()->json(['success' => false, 'message' => 'Backend returned an unexpected response.'], 502);
        }

        return response()->json($json, $resp->status());
    }

    private function saveUploadedImages(Request $request): array
    {
        $urls = array_filter((array) $request->input('image_urls', []));

        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $file) {
                $path = $file->store('products', 'public');
                $urls[] = asset("storage/{$path}");
            }
        }

        return array_values($urls);
    }

    public function storeProduct(Request $request)
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Could not authenticate with backend. Ensure the backend is running and the DB is seeded.'], 503);
        }

        $data = $request->except(['image_files', 'image_urls']);
        $data['images']     = $this->saveUploadedImages($request);
        $data['skin_types'] = $request->input('skin_types', []);
        $data['is_featured'] = (bool) $request->input('is_featured', false);
        $data['is_active']   = (bool) $request->input('is_active', false);

        $resp = Http::withToken($token)->post("{$this->api()}/products", $data);
        if ($resp->successful()) self::clearIndexCache();
        return response()->json($resp->json(), $resp->status());
    }

    public function updateProduct(Request $request, $id)
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Could not authenticate with backend.'], 503);
        }

        $data = $request->except(['image_files', 'image_urls']);
        $imageUrls = $this->saveUploadedImages($request);
        if (!empty($imageUrls)) {
            $data['images'] = $imageUrls;
        }
        if ($request->has('skin_types')) {
            $data['skin_types'] = $request->input('skin_types', []);
        }

        $resp = Http::withToken($token)->put("{$this->api()}/products/{$id}", $data);
        if ($resp->successful()) self::clearIndexCache();
        return response()->json($resp->json(), $resp->status());
    }

    public function deleteProduct($id)
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Could not authenticate with backend.'], 503);
        }
        $resp = Http::withToken($token)->delete("{$this->api()}/products/{$id}");
        if ($resp->successful()) self::clearIndexCache();
        return response()->json($resp->json(), $resp->status());
    }

    public function listReviews(Request $request)
    {
        $params = array_filter([
            'product_id' => $request->product_id,
            'per_page'   => 500,
        ]);
        $resp = Http::get("{$this->api()}/reviews", $params);
        return response()->json($resp->json(), $resp->status());
    }

    public function deleteReview($id)
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Could not authenticate with backend.'], 503);
        }
        $resp = Http::withToken($token)->delete("{$this->api()}/reviews/{$id}");
        return response()->json($resp->json(), $resp->status());
    }

    public function index()
    {
        $admin      = session('admin_user');
        $cmsContent = $this->cmsContent->all();
        $api        = config('app.api_base_url');
        $token      = $this->backendToken();
        $blogPosts  = Schema::hasTable('blog_posts')
            ? BlogPost::query()->orderByDesc('updated_at')->limit(100)->get()
            : collect([]);

        // ── Parallel HTTP calls (all fire at once, result in ~1 RTT) ─────────
        [$catalogProducts, $adminUsers, $quizResults, $adminOrders] = Cache::remember(
            'admin_index_data',
            60, // cache for 60 seconds
            function () use ($api, $token) {
                if (!$token) {
                    return [[], [], [], []];
                }
                try {
                    $responses = Http::pool(fn ($pool) => [
                        $pool->as('products')->get("{$api}/products", ['per_page' => 100]),
                        $pool->as('users')->withToken($token)->get("{$api}/users", ['per_page' => 100]),
                        $pool->as('quiz')->withToken($token)->get("{$api}/quiz/results", ['per_page' => 100]),
                        $pool->as('orders')->withToken($token)->get("{$api}/orders", ['per_page' => 200]),
                    ]);

                    return [
                        $responses['products']->json('data.data') ?? [],
                        $responses['users']->json('data.data') ?? [],
                        $responses['quiz']->json('data.data') ?? [],
                        $responses['orders']->json('data.data') ?? [],
                    ];
                } catch (\Throwable $e) {
                    return [[], [], [], []];
                }
            }
        );

        // ── Community data (JSON file, fast) ─────────────────────────────────
        [$communityPosts, $communitySettings] = Cache::remember('admin_community_data', 60, function () {
            try {
                $store = app(\App\Support\CommunityStore::class);
                return [$store->getPosts('all', true), $store->getSettings()];
            } catch (\Throwable $e) {
                return [[], []];
            }
        });

        // ── Loyalty config + subscription plans (JSON files, very fast) ───────
        [$loyaltyConfig, $subscriptionPlans] = Cache::remember('admin_config_data', 120, function () {
            $loyaltyConfig = $subscriptionPlans = [];
            try {
                $ltPath = Storage::disk('local')->path('cms/loyalty_tiers.json');
                $spPath = Storage::disk('local')->path('cms/subscription_plans.json');
                if (file_exists($ltPath)) $loyaltyConfig     = json_decode(file_get_contents($ltPath), true) ?? [];
                if (file_exists($spPath)) $subscriptionPlans = json_decode(file_get_contents($spPath), true) ?? [];
            } catch (\Throwable $e) {}
            return [$loyaltyConfig, $subscriptionPlans];
        });

        // ── Overview dashboard stats ──────────────────────────────────────────
        $now          = \Carbon\Carbon::now();
        $currentMonth = $now->month;
        $currentYear  = $now->year;

        $ordersCollection = collect($adminOrders);

        $totalRevenue    = (int) $ordersCollection->sum('total');
        $ordersThisMonth = $ordersCollection->filter(function ($o) use ($currentMonth, $currentYear) {
            try { $d = \Carbon\Carbon::parse($o['created_at'] ?? ''); return $d->month === $currentMonth && $d->year === $currentYear; } catch (\Throwable $e) { return false; }
        })->count();

        $prevMonth       = $currentMonth === 1 ? 12 : $currentMonth - 1;
        $prevYear        = $currentMonth === 1 ? $currentYear - 1 : $currentYear;
        $ordersPrevMonth = $ordersCollection->filter(function ($o) use ($prevMonth, $prevYear) {
            try { $d = \Carbon\Carbon::parse($o['created_at'] ?? ''); return $d->month === $prevMonth && $d->year === $prevYear; } catch (\Throwable $e) { return false; }
        })->count();

        $monthlyRevenue     = [];
        $monthlyOrderCounts = [];
        for ($m = 1; $m <= 12; $m++) {
            $filtered = $ordersCollection->filter(function ($o) use ($m, $currentYear) {
                try { $d = \Carbon\Carbon::parse($o['created_at'] ?? ''); return $d->month === $m && $d->year === $currentYear; } catch (\Throwable $e) { return false; }
            });
            $monthlyRevenue[$m]     = (int) $filtered->sum('total');
            $monthlyOrderCounts[$m] = $filtered->count();
        }

        $tierCounts = ['starter' => 0, 'glow' => 0, 'radiant' => 0, 'iconic' => 0];
        foreach ($adminUsers as $u) {
            $tier = strtolower($u['tier'] ?? 'starter');
            $tierCounts[$tier] = ($tierCounts[$tier] ?? 0) + 1;
        }
        $totalUsers = count($adminUsers);

        $skinTypeCounts  = ['combination' => 0, 'oily' => 0, 'dry' => 0, 'normal' => 0];
        $totalQuizTakers = count($quizResults);
        foreach ($quizResults as $r) {
            $skin = strtolower($r['skin_type'] ?? ($r['answers']['skin_feel'] ?? ''));
            if      (str_contains($skin, 'combination') || str_contains($skin, 'tzone') || str_contains($skin, 't-zone')) $skinTypeCounts['combination']++;
            elseif  (str_contains($skin, 'oily'))   $skinTypeCounts['oily']++;
            elseif  (str_contains($skin, 'dry'))    $skinTypeCounts['dry']++;
            else                                     $skinTypeCounts['normal']++;
        }

        $lowStockThreshold = 10;
        $lowStockItems     = array_values(array_filter($catalogProducts, fn($p) => ($p['stock'] ?? $p['stock_quantity'] ?? 99) > 0 && ($p['stock'] ?? $p['stock_quantity'] ?? 99) <= $lowStockThreshold));
        $outOfStockCount   = count(array_filter($catalogProducts, fn($p) => ($p['stock'] ?? $p['stock_quantity'] ?? 1) == 0));
        $lowStockCount     = count($lowStockItems);
        $inStockCount      = count($catalogProducts) - $lowStockCount - $outOfStockCount;

        $recentActivity = array_slice(array_reverse($adminOrders), 0, 5);

        // ── Analytics panel stats ─────────────────────────────────────────────
        $quizCollection = collect($quizResults);
        $analyticsQuizThisMonth = $quizCollection->filter(function ($r) use ($currentMonth, $currentYear) {
            try { $d = \Carbon\Carbon::parse($r['created_at'] ?? ''); return $d->month === $currentMonth && $d->year === $currentYear; } catch (\Throwable $e) { return false; }
        })->count();
        $analyticsQuizPrevMonth = $quizCollection->filter(function ($r) use ($prevMonth, $prevYear) {
            try { $d = \Carbon\Carbon::parse($r['created_at'] ?? ''); return $d->month === $prevMonth && $d->year === $prevYear; } catch (\Throwable $e) { return false; }
        })->count();

        // Quiz-to-order: signed-in quiz users who have at least one order
        $quizSignedInUserIds = $quizCollection
            ->filter(fn($r) => isset($r['user']['id']))
            ->pluck('user.id')->unique();
        $orderUserIds = collect($adminOrders)
            ->map(fn($o) => $o['user_id'] ?? ($o['user']['id'] ?? null))
            ->filter()->unique();
        $quizUsersWithOrders  = $quizSignedInUserIds->intersect($orderUserIds)->count();
        $analyticsQuizToCartRate = $quizSignedInUserIds->count() > 0
            ? round($quizUsersWithOrders / $quizSignedInUserIds->count() * 100)
            : 0;

        // Repeat purchase rate: % of ordering users with 2+ orders
        $ordersByUser = collect($adminOrders)
            ->groupBy(fn($o) => $o['user_id'] ?? ($o['user']['id'] ?? null))
            ->forget(null)->forget('');
        $usersWithAnyOrder    = $ordersByUser->count();
        $usersWithRepeatOrder = $ordersByUser->filter(fn($g) => $g->count() >= 2)->count();
        $analyticsRepeatRate  = $usersWithAnyOrder > 0
            ? round($usersWithRepeatOrder / $usersWithAnyOrder * 100)
            : 0;

        // Top skin concerns from quiz answers
        $concernCounts = [];
        foreach ($quizResults as $r) {
            $concerns = $r['answers']['concerns'] ?? [];
            if (is_string($concerns)) $concerns = array_filter(array_map('trim', explode(',', $concerns)));
            foreach ((array) $concerns as $c) {
                $c = trim(strtolower(str_replace('_', ' ', $c)));
                if ($c) $concernCounts[$c] = ($concernCounts[$c] ?? 0) + 1;
            }
        }
        arsort($concernCounts);
        $analyticsTopConcerns   = array_slice($concernCounts, 0, 5, true);
        $analyticsConcernTotal  = max(1, $totalQuizTakers);

        // Top locations from order shipping addresses
        $locationCounts = [];
        foreach ($adminOrders as $o) {
            $addr = $o['shipping_address'] ?? [];
            $loc  = trim($addr['state'] ?? $addr['city'] ?? '');
            if ($loc) $locationCounts[$loc] = ($locationCounts[$loc] ?? 0) + 1;
        }
        arsort($locationCounts);
        $analyticsTopLocations   = array_slice($locationCounts, 0, 5, true);
        $analyticsLocationTotal  = max(1, array_sum($locationCounts) ?: 1);

        $productRevenue = [];
        foreach ($adminOrders as $o) {
            foreach ($o['items'] ?? [] as $item) {
                $pid = $item['product_id'] ?? $item['id'] ?? null;
                if ($pid) {
                    $productRevenue[$pid] = ($productRevenue[$pid] ?? 0) + (($item['price'] ?? 0) * ($item['quantity'] ?? 1));
                }
            }
        }
        arsort($productRevenue);
        $topProductRevenue = array_slice($productRevenue, 0, 5, true);

        return view('admin.index', compact(
            'admin', 'cmsContent', 'catalogProducts', 'communityPosts', 'communitySettings',
            'adminUsers', 'quizResults', 'adminOrders', 'loyaltyConfig', 'subscriptionPlans',
            'totalRevenue', 'ordersThisMonth', 'ordersPrevMonth',
            'monthlyRevenue', 'monthlyOrderCounts', 'currentYear', 'currentMonth',
            'tierCounts', 'totalUsers',
            'analyticsQuizThisMonth', 'analyticsQuizPrevMonth',
            'analyticsQuizToCartRate', 'analyticsRepeatRate',
            'analyticsTopConcerns', 'analyticsConcernTotal',
            'analyticsTopLocations', 'analyticsLocationTotal',
            'skinTypeCounts', 'totalQuizTakers',
            'lowStockItems', 'lowStockCount', 'outOfStockCount', 'inStockCount',
            'recentActivity', 'topProductRevenue',
            'blogPosts'
        ));
    }

    /** Clear the admin index cache (call after product/order mutations). */
    public static function clearIndexCache(): void
    {
        Cache::forget('admin_index_data');
        Cache::forget('admin_community_data');
        Cache::forget('admin_config_data');
    }

    public function routineStats(): \Illuminate\Http\JsonResponse
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        }

        $resp = Http::withToken($token)->get("{$this->api()}/routine/admin/stats");
        return response()->json($resp->json(), $resp->status());
    }

    public function routineUserLogs($userId): \Illuminate\Http\JsonResponse
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        }

        try {
            $resp = Http::withToken($token)->get("{$this->api()}/routine/admin/user/{$userId}/logs");
            return response()->json($resp->json() ?? ['success' => false], $resp->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Could not load user routine.'], 503);
        }
    }

    public function routineUpdateLog(Request $request, $userId, $date): \Illuminate\Http\JsonResponse
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        }

        try {
            $payload = [
                'am_steps' => $request->input('am_steps', []),
                'pm_steps' => $request->input('pm_steps', []),
                'am_done'  => (bool) $request->input('am_done', false),
                'pm_done'  => (bool) $request->input('pm_done', false),
            ];
            $resp = Http::withToken($token)->asJson()
                ->put("{$this->api()}/routine/admin/user/{$userId}/log/{$date}", $payload);
            return response()->json($resp->json() ?? ['success' => false], $resp->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Could not update routine.'], 503);
        }
    }

    public function updateOrder(Request $request, $id)
    {
        $token = $this->backendToken();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Could not authenticate with backend.'], 503);
        }
        $data = $request->validate([
            'status'          => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
            'payment_status'  => 'nullable|string',
            'admin_note'      => 'nullable|string|max:1000',
        ]);
        $resp = Http::withToken($token)->put("{$this->api()}/orders/{$id}", $data);
        if ($resp->successful()) Cache::forget('admin_index_data');
        return response()->json($resp->json(), $resp->status());
    }

    public function updateContent(Request $request)
    {
        $payload = $request->validate([
            'content' => 'required|array',
        ]);

        $content = $this->cmsContent->updateContent($payload['content']);

        return response()->json([
            'success' => true,
            'message' => 'Content updated successfully.',
            'content' => $content['content'] ?? [],
        ]);
    }

    public function updateQuiz(Request $request)
    {
        $payload = $request->validate([
            'quiz' => 'required|array',
        ]);

        $content = $this->cmsContent->updateQuiz($payload['quiz']);

        return response()->json([
            'success' => true,
            'message' => 'Quiz configuration updated successfully.',
            'quiz' => $content['quiz'] ?? [],
        ]);
    }

    // ── Coupon CRUD ─────────────────────────────────────────────

    public function listCoupons()
    {
        return response()->json(['success' => true, 'data' => (new CouponService())->all()]);
    }

    public function storeCoupon(Request $request)
    {
        $data = $request->validate([
            'code'                 => 'required|string|max:64',
            'discount_type'        => 'required|in:percentage,fixed,free_shipping',
            'discount_value'       => 'nullable|numeric|min:0',
            'free_shipping'        => 'nullable|boolean',
            'min_order'            => 'nullable|numeric|min:0',
            'max_uses'             => 'nullable|integer|min:1',
            'uses_per_customer'    => 'nullable|integer|min:1',
            'start_date'           => 'nullable|date',
            'expiry_date'          => 'nullable|date',
            'applicable_to'        => 'nullable|string',
            'customer_restriction' => 'nullable|string',
            'description'          => 'nullable|string|max:255',
        ]);

        $service = new CouponService();

        if ($service->find($data['code'])) {
            return response()->json(['success' => false, 'message' => 'A coupon with that code already exists.'], 422);
        }

        $coupon = $service->create($data);
        return response()->json(['success' => true, 'message' => 'Coupon created.', 'data' => $coupon], 201);
    }

    public function updateCoupon(Request $request, string $id)
    {
        $data = $request->validate([
            'code'                 => 'sometimes|string|max:64',
            'discount_type'        => 'sometimes|in:percentage,fixed,free_shipping',
            'discount_value'       => 'nullable|numeric|min:0',
            'free_shipping'        => 'nullable|boolean',
            'min_order'            => 'nullable|numeric|min:0',
            'max_uses'             => 'nullable|integer|min:1',
            'uses_per_customer'    => 'nullable|integer|min:1',
            'start_date'           => 'nullable|date',
            'expiry_date'          => 'nullable|date',
            'applicable_to'        => 'nullable|string',
            'customer_restriction' => 'nullable|string',
            'description'          => 'nullable|string|max:255',
            'active'               => 'nullable|boolean',
        ]);

        $coupon = (new CouponService())->update($id, $data);

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Coupon not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Coupon updated.', 'data' => $coupon]);
    }

    public function deleteCoupon(string $id)
    {
        $deleted = (new CouponService())->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Coupon not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Coupon deleted.']);
    }

    // ── Gift Cards ─────────────────────────────────────────────────────

    public function listGiftCards(Request $request)
    {
        $service = new \App\Services\GiftCardService();
        return response()->json([
            'success'            => true,
            'data'               => $service->all(),
            'stats'              => $service->stats(),
            'denominations'      => $service->denominations(),
            'denomination_stats' => $service->denominationStats(),
        ]);
    }

    public function storeGiftCard(Request $request)
    {
        $data = $request->validate([
            'amount'          => 'required|integer|min:1000',
            'purchaser_name'  => 'nullable|string|max:255',
            'purchaser_email' => 'nullable|email|max:255',
            'recipient_name'  => 'required|string|max:255',
            'recipient_email' => 'required|email|max:255',
            'message'         => 'nullable|string|max:500',
        ]);

        $data['purchaser_name']  = $data['purchaser_name']  ?? 'Kominhoo Admin';
        $data['purchaser_email'] = $data['purchaser_email'] ?? 'admin@kominhoo.com';

        $card = (new \App\Services\GiftCardService())->generate($data);
        return response()->json(['success' => true, 'message' => 'Gift card issued.', 'data' => $card], 201);
    }

    public function updateGiftCard(Request $request, string $id)
    {
        $data = $request->validate([
            'status'  => 'sometimes|in:active,redeemed,expired,partially_used',
            'balance' => 'sometimes|integer|min:0',
        ]);

        $card = (new \App\Services\GiftCardService())->update($id, $data);

        if (!$card) {
            return response()->json(['success' => false, 'message' => 'Gift card not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Gift card updated.', 'data' => $card]);
    }

    public function deleteGiftCard(string $id)
    {
        $deleted = (new \App\Services\GiftCardService())->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Gift card not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Gift card deleted.']);
    }

    public function storeDenomination(Request $request)
    {
        $data = $request->validate([
            'amount'      => 'required|integer|min:1000',
            'label'       => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'is_popular'  => 'nullable|boolean',
        ]);

        $denom = (new \App\Services\GiftCardService())->addDenomination($data);
        return response()->json(['success' => true, 'message' => 'Denomination added.', 'data' => $denom], 201);
    }

    public function updateDenomination(Request $request, string $id)
    {
        $data = $request->validate([
            'amount'      => 'sometimes|integer|min:1000',
            'label'       => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'is_popular'  => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
        ]);

        $denom = (new \App\Services\GiftCardService())->updateDenomination($id, $data);

        if (!$denom) {
            return response()->json(['success' => false, 'message' => 'Denomination not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Denomination updated.', 'data' => $denom]);
    }

    public function uploadMedia(Request $request)
    {
        $payload = $request->validate([
            'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,gif,webp,mp4,mp3', // max 5120 KB = 5 MB
        ]);

        $file = $request->file('file');
        $path = $file->store('cms_media', 'public');

        $url = asset("storage/{$path}");

        return response()->json([
            'success' => true,
            'url' => $url,
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getClientMimeType(),
        ]);
    }

    // ── Loyalty Tier Config ────────────────────────────────────────

    public function getLoyaltyConfig()
    {
        $json = Storage::disk('local')->get('cms/loyalty_tiers.json');
        return response()->json(['success' => true, 'data' => json_decode($json, true)]);
    }

    public function updateLoyaltyConfig(Request $request)
    {
        $data = $request->validate(['config' => 'required|array']);
        Storage::disk('local')->put('cms/loyalty_tiers.json', json_encode($data['config'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return response()->json(['success' => true, 'message' => 'Loyalty configuration saved.']);
    }

    // ── Subscription Plans ────────────────────────────────────────

    public function listSubscriptionPlans()
    {
        $json = Storage::disk('local')->get('cms/subscription_plans.json');
        return response()->json(['success' => true, 'data' => json_decode($json, true)]);
    }

    public function storeSubscriptionPlan(Request $request)
    {
        $data = $request->validate([
            'id'              => 'required|string|max:64',
            'name'            => 'required|string|max:128',
            'price'           => 'required|integer|min:0',
            'billing_cycle'   => 'required|in:monthly,quarterly,biannual,annual',
            'frequency_label' => 'required|string|max:64',
            'products_count'  => 'required|integer|min:1',
            'description'     => 'required|string',
            'features'        => 'required|array',
            'is_active'       => 'nullable|boolean',
            'is_popular'      => 'nullable|boolean',
            'tier_required'   => 'nullable|string',
            'color'           => 'nullable|string|max:20',
            'badge'           => 'nullable|string|max:64',
        ]);

        $plans = json_decode(Storage::disk('local')->get('cms/subscription_plans.json'), true);

        if (collect($plans)->contains('id', $data['id'])) {
            return response()->json(['success' => false, 'message' => 'A plan with that ID already exists.'], 422);
        }

        $data['is_active']  = $data['is_active']  ?? true;
        $data['is_popular'] = $data['is_popular'] ?? false;
        $plans[] = $data;
        Storage::disk('local')->put('cms/subscription_plans.json', json_encode($plans, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return response()->json(['success' => true, 'message' => 'Plan created.', 'data' => $data], 201);
    }

    public function updateSubscriptionPlan(Request $request, string $id)
    {
        $plans  = json_decode(Storage::disk('local')->get('cms/subscription_plans.json'), true);
        $idx    = collect($plans)->search(fn($p) => $p['id'] === $id);

        if ($idx === false) {
            return response()->json(['success' => false, 'message' => 'Plan not found.'], 404);
        }

        $plans[$idx] = array_merge($plans[$idx], $request->except('id'));
        Storage::disk('local')->put('cms/subscription_plans.json', json_encode(array_values($plans), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return response()->json(['success' => true, 'message' => 'Plan updated.', 'data' => $plans[$idx]]);
    }

    public function deleteSubscriptionPlan(string $id)
    {
        $plans  = json_decode(Storage::disk('local')->get('cms/subscription_plans.json'), true);
        $filtered = array_values(array_filter($plans, fn($p) => $p['id'] !== $id));

        if (count($filtered) === count($plans)) {
            return response()->json(['success' => false, 'message' => 'Plan not found.'], 404);
        }

        Storage::disk('local')->put('cms/subscription_plans.json', json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return response()->json(['success' => true, 'message' => 'Plan deleted.']);
    }

    // ── Member Management ──────────────────────────────────────────

    public function listMembers(Request $request)
    {
        $token = $this->backendToken();
        $api   = config('app.api_base_url');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        }
        $users = Http::withToken($token)
            ->get("{$api}/users", ['per_page' => $request->input('per_page', 100)])
            ->json() ?? [];
        return response()->json($users);
    }

    public function awardMemberPoints(Request $request)
    {
        $data  = $request->validate(['user_id' => 'required|integer', 'points' => 'required|integer|not_in:0', 'note' => 'nullable|string']);
        $token = $this->backendToken();
        $api   = config('app.api_base_url');
        if (!$token) return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        $resp  = Http::withToken($token)->post("{$api}/loyalty/award", $data);
        return response()->json($resp->json(), $resp->status());
    }

    public function sendMemberNotification(Request $request)
    {
        $data  = $request->validate([
            'user_id' => 'nullable|integer',
            'type'    => 'required|string|max:64',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        $token = $this->backendToken();
        $api   = config('app.api_base_url');
        if (!$token) return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        $resp  = Http::withToken($token)->post("{$api}/notifications/send", $data);
        return response()->json($resp->json(), $resp->status());
    }

    public function listMemberSubscriptions(Request $request)
    {
        $token = $this->backendToken();
        $api   = config('app.api_base_url');
        if (!$token) return response()->json(['success' => false, 'message' => 'Backend unavailable.'], 503);
        $resp  = Http::withToken($token)->get("{$api}/subscriptions", $request->only('per_page', 'page'));
        return response()->json($resp->json(), $resp->status());
    }

    // ── Security Event Monitoring ─────────────────────────────────

    public function listSecurityEvents(Request $request)
    {
        $events = $this->loadSecurityEvents();

        if ($type = $request->input('type')) {
            $events = array_values(array_filter($events, fn($e) => ($e['type'] ?? '') === $type));
        }

        if ($severity = $request->input('severity')) {
            $events = array_values(array_filter($events, fn($e) => ($e['severity'] ?? '') === $severity));
        }

        $unread = count(array_filter($events, fn($e) => ($e['severity'] ?? '') === 'high'));

        return response()->json([
            'success'        => true,
            'data'           => array_slice($events, 0, 100),
            'total'          => count($events),
            'high_severity'  => $unread,
        ]);
    }

    public function clearSecurityEvents()
    {
        file_put_contents(storage_path('app/security_events.json'), json_encode([]));
        return response()->json(['success' => true, 'message' => 'Security events cleared.']);
    }

    private function loadSecurityEvents(): array
    {
        $path = storage_path('app/security_events.json');
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?? [];
    }

    // ── Wallet Management ─────────────────────────────────────────

    public function listWallets(Request $request)
    {
        $qs = http_build_query($request->only('per_page', 'page', 'status', 'search'));
        return $this->backendProxy('get', '/admin/wallets' . ($qs ? "?{$qs}" : ''));
    }

    public function showWallet(int $walletId)
    {
        return $this->backendProxy('get', "/admin/wallets/{$walletId}");
    }

    public function listWalletTransactions(Request $request)
    {
        $qs = http_build_query($request->only('per_page', 'page', 'status', 'category', 'wallet_id', 'user_id', 'search', 'date_from', 'date_to'));
        return $this->backendProxy('get', '/admin/wallets/transactions' . ($qs ? "?{$qs}" : ''));
    }

    public function listWalletAuditLogs(Request $request)
    {
        $qs = http_build_query($request->only('per_page', 'page', 'wallet_id', 'user_id'));
        return $this->backendProxy('get', '/admin/wallets/audit-logs' . ($qs ? "?{$qs}" : ''));
    }

    public function grantWalletBonus(Request $request)
    {
        $data = $request->validate([
            'user_id'     => 'required|integer',
            'amount'      => 'required|numeric|min:1',
            'description' => 'required|string|max:500',
            'category'    => 'nullable|in:admin_bonus,campaign_bonus',
        ]);
        return $this->backendProxy('post', '/admin/wallets/bonus', $data);
    }

    public function grantWalletCampaignBonus(Request $request)
    {
        $data = $request->validate([
            'user_ids'      => 'required|array|min:1',
            'user_ids.*'    => 'required|integer',
            'amount'        => 'required|numeric|min:1',
            'campaign_name' => 'required|string|max:255',
            'campaign_id'   => 'required|string|max:100',
        ]);
        return $this->backendProxy('post', '/admin/wallets/campaign-bonus', $data);
    }

    public function updateWalletStatus(Request $request, int $walletId)
    {
        $data = $request->validate(['status' => 'required|in:active,suspended,frozen']);
        return $this->backendProxy('patch', "/admin/wallets/{$walletId}/status", $data);
    }

    public function getWalletBonusConfig()
    {
        return $this->backendProxy('get', '/admin/wallets/bonus-config');
    }

    public function updateWalletBonusConfig(Request $request)
    {
        $data = $request->validate([
            'signup_bonus'            => 'required|numeric|min:0',
            'first_deposit_bonus_pct' => 'required|numeric|min:0|max:100',
            'first_deposit_bonus_cap' => 'required|numeric|min:0',
            'referral_bonus'          => 'required|numeric|min:0',
            'zero_balance_bonus'      => 'required|numeric|min:0',
        ]);
        return $this->backendProxy('post', '/admin/wallets/bonus-config', $data);
    }

    public function getWalletBonusStats()
    {
        return $this->backendProxy('get', '/admin/wallets/bonus-stats');
    }

    public function grantMissingSignupBonuses()
    {
        return $this->backendProxy('post', '/admin/wallets/grant-signup-bonus');
    }

    public function initWallets()
    {
        return $this->backendProxy('post', '/admin/wallets/init-wallets');
    }

    public function grantZeroBalanceBonus(Request $request)
    {
        $data = $request->validate([
            'amount'      => 'required|numeric|min:1',
            'campaign_id' => 'required|string|max:100',
            'description' => 'required|string|max:255',
        ]);
        return $this->backendProxy('post', '/admin/wallets/grant-zero-balance-bonus', $data);
    }

    // ── Influencer Applications ───────────────────────────────────

    public function listInfluencers()
    {
        $apps = app(\App\Services\InfluencerService::class)->all();
        usort($apps, fn ($a, $b) => strcmp($b['submitted_at'] ?? '', $a['submitted_at'] ?? ''));
        return response()->json(['success' => true, 'data' => $apps]);
    }

    public function updateInfluencerStatus(Request $request, string $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'notes'  => 'nullable|string|max:1000',
        ]);
        $ok = app(\App\Services\InfluencerService::class)
            ->updateStatus($id, $data['status'], $data['notes'] ?? '');

        return $ok
            ? response()->json(['success' => true, 'message' => 'Status updated.'])
            : response()->json(['success' => false, 'message' => 'Application not found.'], 404);
    }

    public function deleteInfluencer(string $id)
    {
        $ok = app(\App\Services\InfluencerService::class)->delete($id);

        return $ok
            ? response()->json(['success' => true, 'message' => 'Application deleted.'])
            : response()->json(['success' => false, 'message' => 'Application not found.'], 404);
    }
}
