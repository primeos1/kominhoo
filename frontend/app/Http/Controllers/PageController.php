<?php

namespace App\Http\Controllers;

use App\Support\CmsContent;
use App\Support\CommunityStore;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    private string $api;

    public function __construct(
        private CmsContent $cmsContent,
        private CommunityStore $communityStore
    ) {
        $this->api = config('app.api_base_url');
    }

    public function home()
    {
        $products = Http::get("{$this->api}/products", ['featured' => true, 'per_page' => 8])->json('data.data') ?? [];
        $bundles = Http::get("{$this->api}/bundles", ['per_page' => 4])->json('data.data') ?? [];
        $guides = Http::get("{$this->api}/guides", ['per_page' => 3])->json('data.data') ?? [];
        $allProducts = Http::get("{$this->api}/products", ['per_page' => 100])->json('data.data') ?? [];
        $content = $this->cmsContent->all()['content'] ?? [];

        $productCollection = collect($allProducts);
        $newDropProducts = $this->resolveProductsByIds(
            $productCollection,
            data_get($content, 'new_drops.product_ids', []),
            4
        );
        $dealProduct = $this->resolveProductById(
            $productCollection,
            data_get($content, 'deal_of_the_day.product_id')
        ) ?? ($productCollection->first() ?: null);

        // Load subscription plans (active only) and loyalty tiers from JSON stores
        $subscriptionPlans = [];
        $loyaltyTiers = [];
        try {
            $spRaw = Storage::disk('local')->get('cms/subscription_plans.json');
            if ($spRaw) {
                $subscriptionPlans = collect(json_decode($spRaw, true))
                    ->filter(fn ($p) => $p['is_active'] ?? true)
                    ->values()
                    ->all();
            }
            $ltRaw = Storage::disk('local')->get('cms/loyalty_tiers.json');
            if ($ltRaw) {
                $loyaltyTiers = json_decode($ltRaw, true)['tiers'] ?? [];
            }
        } catch (\Throwable $e) {
            // JSON unavailable — home page falls back to empty arrays (sections still visible)
        }

        $communityGalleryItems = $this->communityGalleryItems(7);

        return view('pages.home', compact('products', 'bundles', 'guides', 'newDropProducts', 'dealProduct', 'subscriptionPlans', 'loyaltyTiers', 'communityGalleryItems'));
    }

    public function community()
    {
        $posts = Http::get("{$this->api}/community", ['per_page' => 12])->json('data.data') ?? [];
        return view('pages.community', compact('posts'));
    }

    public function results()
    {
        if (!session('quiz_result')) {
            return redirect()->route('quiz');
        }

        $result = session('quiz_result', []);
        $skin_type           = $result['skin_type'] ?? $result['result']['skin_type'] ?? 'Normal';
        $answers             = $result['result']['answers'] ?? [];
        $recommended_products = $result['recommended_products'] ?? [];
        // skin_scores is returned at the top-level of the API data object
        $skin_scores         = $result['skin_scores'] ?? $result['result']['skin_scores'] ?? null;

        return view('pages.results', compact('skin_type', 'answers', 'recommended_products', 'skin_scores'));
    }

    public function faq()
    {
        $faqItems = data_get($this->cmsContent->all(), 'content.pages.faq', []);

        return view('pages.faq', compact('faqItems'));
    }

    public function blog()
    {
        $posts = [
            [
                'tag'     => 'Skincare Tips',
                'title'   => 'The K-Beauty Routine That Works for Nigerian Skin',
                'excerpt' => 'High humidity, harmattan dust, and melanin-rich skin call for a different approach. Here\'s how to adapt the classic 10-step routine for our climate.',
                'author'  => 'Kominhoo Team',
                'date'    => 'May 18, 2026',
                'read'    => '5 min read',
                'image'   => null,
                'featured' => true,
            ],
            [
                'tag'     => 'Ingredients',
                'title'   => 'Niacinamide vs. Vitamin C — Which One Do You Actually Need?',
                'excerpt' => 'Both brighten skin and fade dark spots, but they work differently. We break down which ingredient suits your concern.',
                'author'  => 'Adaeze O.',
                'date'    => 'May 12, 2026',
                'read'    => '4 min read',
                'image'   => null,
                'featured' => false,
            ],
            [
                'tag'     => 'Product Reviews',
                'title'   => 'We Tested 6 Korean Sunscreens — Here\'s the One That Doesn\'t Leave a Cast',
                'excerpt' => 'White cast is a real problem for deeper skin tones. Our team put six popular K-beauty SPFs to the test over four weeks.',
                'author'  => 'Fatima A.',
                'date'    => 'May 5, 2026',
                'read'    => '6 min read',
                'image'   => null,
                'featured' => false,
            ],
            [
                'tag'     => 'Makeup',
                'title'   => 'Glass Skin Makeup: How to Get That Dewy Finish That Lasts All Day',
                'excerpt' => 'The glass skin trend isn\'t just a filter. With the right base prep and products, it\'s totally achievable — even in Lagos heat.',
                'author'  => 'Kominhoo Team',
                'date'    => 'April 29, 2026',
                'read'    => '4 min read',
                'image'   => null,
                'featured' => false,
            ],
            [
                'tag'     => 'Haircare',
                'title'   => 'K-Beauty Hair Rituals: Scalp Care Is the New Skincare',
                'excerpt' => 'Korean hair brands have long treated the scalp like skin. Here\'s what to borrow from their playbook for stronger, healthier hair.',
                'author'  => 'Amaka N.',
                'date'    => 'April 20, 2026',
                'read'    => '5 min read',
                'image'   => null,
                'featured' => false,
            ],
            [
                'tag'     => 'Wellness',
                'title'   => '7 Nighttime Habits That Make Your Morning Skin Routine Work Better',
                'excerpt' => 'Products do most of their work while you sleep. Small bedtime habits can dramatically improve how your skin responds to actives.',
                'author'  => 'Kominhoo Team',
                'date'    => 'April 11, 2026',
                'read'    => '3 min read',
                'image'   => null,
                'featured' => false,
            ],
        ];

        return view('pages.blog', compact('posts'));
    }

    public function shippingPolicy()
    {
        $title = 'Shipping Policy';
        $body = data_get($this->cmsContent->all(), 'content.pages.shipping_policy', '');

        return view('pages.policy', compact('title', 'body'));
    }

    public function returnsPolicy()
    {
        $title = 'Returns & Exchanges';
        $body = data_get($this->cmsContent->all(), 'content.pages.returns_policy', '');

        return view('pages.policy', compact('title', 'body'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function ourPromise()
    {
        return view('pages.our-promise');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function loyaltyProgram()
    {
        $loyaltyTiers = [];
        $pointEvents  = [];
        try {
            $raw = Storage::disk('local')->get('cms/loyalty_tiers.json');
            if ($raw) {
                $data         = json_decode($raw, true);
                $loyaltyTiers = $data['tiers']        ?? [];
                $pointEvents  = $data['point_events'] ?? [];
            }
        } catch (\Throwable $e) {}

        return view('pages.loyalty-program', compact('loyaltyTiers', 'pointEvents'));
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    private function communityGalleryItems(int $limit = 7): array
    {
        $settings = $this->communityStore->getSettings();
        $posts    = $this->communityStore->getPosts('approved', false);

        if (!$posts || $limit <= 0) return [];

        $postsById = collect($posts)->keyBy('id');
        $picked    = [];
        $items     = [];

        $pick = function (?array $post) use (&$picked, &$items, $limit): void {
            if (!$post) return;
            $id = $post['id'] ?? null;
            if (!$id || isset($picked[$id])) return;

            $img = $this->communityPostImageUrl($post);
            if (!$img) return;

            $picked[$id] = true;
            $items[] = [
                'id'  => $id,
                'url' => $img,
                'alt' => trim((string) ($post['caption'] ?? '')) ?: 'Community post',
            ];
        };

        $featuredId = $settings['featured_post_id'] ?? null;
        if ($featuredId && $postsById->has($featuredId)) {
            $pick($postsById->get($featuredId));
        }

        foreach (($settings['pinned_post_ids'] ?? []) as $pinnedId) {
            if (count($items) >= $limit) break;
            if ($postsById->has($pinnedId)) $pick($postsById->get($pinnedId));
        }

        foreach ($posts as $post) {
            if (count($items) >= $limit) break;
            $pick($post);
        }

        return $items;
    }

    private function communityPostImageUrl(array $post): ?string
    {
        $after = (string) ($post['after_img'] ?? '');
        if ($after !== '') return $after;

        $before = (string) ($post['before_img'] ?? '');
        if ($before !== '') return $before;

        $img = (string) ($post['img'] ?? '');
        if ($img !== '') return $img;

        $images = $post['images'] ?? null;
        if (is_array($images) && !empty($images[0])) {
            return (string) $images[0];
        }

        return null;
    }

    private function resolveProductsByIds(Collection $products, array $ids, int $limit): array
    {
        $selected = collect($ids)
            ->map(fn ($id) => $this->resolveProductById($products, $id))
            ->filter()
            ->values();

        if ($selected->count() < $limit) {
            $fallback = $products
                ->reject(fn ($product) => $selected->contains(fn ($selectedProduct) => $selectedProduct['id'] === $product['id']))
                ->take($limit - $selected->count());

            $selected = $selected->concat($fallback);
        }

        return $selected->take($limit)->values()->all();
    }

    private function resolveProductById(Collection $products, mixed $id): ?array
    {
        if (!$id) {
            return null;
        }

        return $products->first(fn ($product) => (int) Arr::get($product, 'id') === (int) $id);
    }
}
