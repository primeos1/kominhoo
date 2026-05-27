<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class CmsContent
{
    private const STORAGE_PATH = 'cms/content.json';

    public function all(): array
    {
        $stored = [];

        if (Storage::disk('local')->exists(self::STORAGE_PATH)) {
            $stored = json_decode(Storage::disk('local')->get(self::STORAGE_PATH), true) ?: [];
        }

        return $this->mergeRecursiveDistinct($this->defaults(), $stored);
    }

    public function save(array $content): array
    {
        $merged = $this->mergeRecursiveDistinct($this->defaults(), $content);

        Storage::disk('local')->put(
            self::STORAGE_PATH,
            json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $merged;
    }

    public function updateContent(array $content): array
    {
        $current = $this->all();
        $current['content'] = $this->mergeRecursiveDistinct($current['content'] ?? [], $content);

        return $this->save($current);
    }

    public function updateQuiz(array $quiz): array
    {
        $current = $this->all();
        $current['quiz'] = $quiz;

        return $this->save($current);
    }

    public function defaults(): array
    {
        return [
            'content' => [
                'announcement_bar' => [
                    'visible' => true,
                    'speed' => 'normal',
                    'items' => [
                        ['emoji' => '🚀', 'text' => 'Free shipping on orders over ₦50,000', 'link' => ''],
                        ['emoji' => '✨', 'text' => 'Take the Skin Quiz — Personalized routine in 60 seconds', 'link' => '/quiz'],
                        ['emoji' => '🎁', 'text' => 'Join Glow Starter — Earn points on every purchase', 'link' => ''],
                        ['emoji' => '🌿', 'text' => 'Authentic Korean skincare delivered to your door', 'link' => ''],
                        ['emoji' => '💎', 'text' => 'New arrivals every week — Shop now', 'link' => '/shop'],
                        ['emoji' => '🔥', 'text' => 'Deal of the Day — Save up to 22% today only', 'link' => ''],
                    ],
                ],
                'hero' => [
                    'visible' => true,
                    'eyebrow' => 'Personalized Korean Beauty',
                    'title_line_1' => 'Your Skin,',
                    'title_line_2' => 'Decoded.',
                    'title_line_3' => 'Perfected.',
                    'description' => 'Stop guessing. Take our 60-second Skin Quiz and get a personalized Korean skincare routine — matched to your skin type, concerns, and lifestyle.',
                    'primary_cta_text' => '✨ Take the Skin Quiz',
                    'primary_cta_link' => '/quiz',
                    'secondary_cta_text' => 'Browse All Products',
                    'secondary_cta_link' => '/shop',
                    'image_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&h=900&fit=crop&q=80',
                ],
                'deal_of_the_day' => [
                    'visible' => true,
                    'show_countdown' => true,
                    'product_id' => null,
                    'badge' => 'Deal of the Day',
                    'headline' => 'Retinol Serum — A More Luminous Night Ritual',
                    'description' => 'A refined treatment for smoother texture, softer fine lines, and a rested morning glow. Today\'s spotlight pairs premium actives with an elegant finish at a rare one-day price.',
                    'deal_price' => 29000,
                    'original_price' => 35000,
                    'units_remaining' => 47,
                ],
                'section_visibility' => [
                    'quiz_cta_banner' => true,
                    'why_section' => true,
                    'recommended_for_you' => true,
                    'new_drops_grid' => true,
                    'bundle_kits' => true,
                    'buying_guides' => true,
                    'community_gallery' => true,
                    'subscription_cta' => true,
                    'loyalty_tiers' => true,
                    'newsletter_section' => true,
                    'welcome_quiz_popup' => true,
                ],
                'why_section' => [
                    'kicker' => 'Why Kominhoo',
                    'heading_line_1' => 'Luxury skincare, refined for',
                    'heading_line_2' => 'your routine.',
                    'lead' => 'Curated formulas, authentic sourcing, and elevated service for a more considered skincare ritual.',
                    'cards' => [
                        ['icon' => '', 'title' => 'Skin-Quiz Matched',  'desc' => 'Every recommendation is personalized to your unique skin profile.'],
                        ['icon' => '', 'title' => 'Authentic K-Beauty', 'desc' => 'Sourced directly from top Korean brands — 100% authentic, every time.'],
                        ['icon' => '', 'title' => 'Fast Delivery',       'desc' => 'Free shipping on orders over ₦50,000. Subscribers always ship free.'],
                        ['icon' => '', 'title' => 'Earn & Glow',         'desc' => 'Earn loyalty points on every order. Redeem for products and exclusive perks.'],
                    ],
                ],
                'quiz_cta_banner' => [
                    'eyebrow'      => 'Free · 60 Seconds · No Account Needed',
                    'title_line_1' => 'Not sure where to start?',
                    'title_line_2' => "We'll figure it out together.",
                    'description'  => 'Our 14-question Skin Quiz builds your perfect Korean routine in 60 seconds — personalized, science-backed, and completely free.',
                    'cta_text'     => 'Start Skin Quiz — Free',
                    'cta_link'     => '/quiz',
                    'meta'         => '60 secs · No account needed',
                ],
                'community_section' => [
                    'kicker'        => 'Real Results',
                    'heading_line_1' => 'The Kominhoo',
                    'heading_line_2' => 'Community',
                    'description'   => 'Real transformations from real customers. Share your glow-up with #KominhooSkin',
                ],
                'newsletter_section' => [
                    'eyebrow'           => 'Stay in the Know',
                    'heading_line_1'    => 'Get Skin Tips &',
                    'heading_line_2'    => 'Exclusive Deals',
                    'subtext'           => 'Join 50,000+ Kominhoo skin lovers. Personalized tips, launch alerts, and subscriber-only deals — straight to your inbox.',
                    'input_placeholder' => 'Enter your email address…',
                    'button_text'       => 'Subscribe →',
                    'note'             => 'No spam. Unsubscribe any time.',
                ],
                'quiz_popup' => [
                    'banner_strong_1' => 'Free',
                    'banner_text'     => 'No account needed',
                    'banner_strong_2' => '60 seconds',
                    'eyebrow'         => 'Kominhoo Skin Quiz',
                    'title_line_1'    => 'Get Your',
                    'title_em'        => 'Personalized',
                    'title_line_2'    => 'Korean Skincare Routine',
                    'subtitle'        => 'in 60 seconds — matched to your skin type, concerns & lifestyle',
                    'cta_text'        => 'Start Skin Quiz — Free',
                    'perks'           => [
                        'Tailored to your unique skin type & concerns',
                        'Expert-backed K-beauty recommendations',
                        'Shop your complete routine instantly',
                    ],
                ],
                'new_drops' => [
                    'eyebrow' => 'New This Quarter',
                    'title' => 'Latest Drops',
                    'product_ids' => [],
                ],
                'subscription_section' => [
                    'kicker'      => 'Quarterly Subscription',
                    'heading'     => 'Your Skin Expert, On Autopilot',
                    'description' => 'Expert-curated routines delivered every 3 months — personalized, free shipping, easy to pause or cancel.',
                ],
                'loyalty_section' => [
                    'kicker'      => 'Loyalty Program',
                    'heading'     => 'Glow More, Earn More',
                    'description' => 'Every purchase earns points. Every point unlocks rewards. The more you shop, the more you glow.',
                ],
                'media' => [
                    'library' => [
                        ['id' => 'home-why-1', 'page' => 'home', 'slot' => 'why_1', 'url' => 'https://images.unsplash.com/photo-1770732766528-d0e9fd0df233?auto=format&fit=crop&w=600&q=60', 'alt' => 'Luxury serum', 'enabled' => true],
                        ['id' => 'home-why-2', 'page' => 'home', 'slot' => 'why_2', 'url' => 'https://images.unsplash.com/photo-1679394270597-e90694d70350?auto=format&fit=crop&w=600&q=60', 'alt' => 'K-beauty', 'enabled' => true],
                        ['id' => 'home-why-3', 'page' => 'home', 'slot' => 'why_3', 'url' => 'https://images.unsplash.com/photo-1745141063798-7fa04698ea80?auto=format&fit=crop&w=600&q=60', 'alt' => 'Fast delivery', 'enabled' => true],
                        ['id' => 'home-why-4', 'page' => 'home', 'slot' => 'why_4', 'url' => 'https://images.unsplash.com/photo-1748543669178-efd3de4e64e0?auto=format&fit=crop&w=600&q=60', 'alt' => 'Earn and glow', 'enabled' => true],
                        ['id' => 'home-community-1', 'page' => 'home', 'slot' => 'community_1', 'url' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=600&h=600&fit=crop&q=80', 'alt' => 'Community feature image', 'enabled' => true],
                        ['id' => 'home-community-2', 'page' => 'home', 'slot' => 'community_2', 'url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=300&h=300&fit=crop&q=80', 'alt' => 'Community image 2', 'enabled' => true],
                        ['id' => 'home-community-3', 'page' => 'home', 'slot' => 'community_3', 'url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&h=300&fit=crop&q=80', 'alt' => 'Community image 3', 'enabled' => true],
                        ['id' => 'home-community-4', 'page' => 'home', 'slot' => 'community_4', 'url' => 'https://images.unsplash.com/photo-1545208935-9a7b23524f41?w=300&h=300&fit=crop&q=80', 'alt' => 'Community image 4', 'enabled' => true],
                        ['id' => 'home-community-5', 'page' => 'home', 'slot' => 'community_5', 'url' => 'https://images.unsplash.com/photo-1582560475093-ba66accbc095?w=300&h=300&fit=crop&q=80', 'alt' => 'Community image 5', 'enabled' => true],
                        ['id' => 'home-community-6', 'page' => 'home', 'slot' => 'community_6', 'url' => 'https://images.unsplash.com/photo-1555487505-8603a1a69755?w=300&h=300&fit=crop&q=80', 'alt' => 'Community image 6', 'enabled' => true],
                        ['id' => 'home-community-7', 'page' => 'home', 'slot' => 'community_7', 'url' => 'https://images.unsplash.com/photo-1557053910-d9eadeed1c58?w=300&h=300&fit=crop&q=80', 'alt' => 'Community image 7', 'enabled' => true],
                        ['id' => 'community-collage-1', 'page' => 'community', 'slot' => 'hero_collage_1', 'url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=500&fit=crop', 'alt' => 'Community collage 1', 'enabled' => true],
                        ['id' => 'community-collage-2', 'page' => 'community', 'slot' => 'hero_collage_2', 'url' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=400&h=500&fit=crop', 'alt' => 'Community collage 2', 'enabled' => true],
                        ['id' => 'community-collage-3', 'page' => 'community', 'slot' => 'hero_collage_3', 'url' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=400&h=500&fit=crop', 'alt' => 'Community collage 3', 'enabled' => true],
                        ['id' => 'community-collage-4', 'page' => 'community', 'slot' => 'hero_collage_4', 'url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400&h=500&fit=crop', 'alt' => 'Community collage 4', 'enabled' => true],
                        ['id' => 'community-collage-5', 'page' => 'community', 'slot' => 'hero_collage_5', 'url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=500&fit=crop', 'alt' => 'Community collage 5', 'enabled' => true],
                        ['id' => 'community-collage-6', 'page' => 'community', 'slot' => 'hero_collage_6', 'url' => 'https://images.unsplash.com/photo-1545208935-9a7b23524f41?w=400&h=500&fit=crop', 'alt' => 'Community collage 6', 'enabled' => true],
                        ['id' => 'community-floating-1', 'page' => 'community', 'slot' => 'hero_floating_1', 'url' => 'https://images.unsplash.com/photo-1557053910-d9eadeed1c58?w=350&h=450&fit=crop', 'alt' => 'Community floating image 1', 'enabled' => true],
                        ['id' => 'community-floating-2', 'page' => 'community', 'slot' => 'hero_floating_2', 'url' => 'https://images.unsplash.com/photo-1555487505-8603a1a69755?w=260&h=340&fit=crop', 'alt' => 'Community floating image 2', 'enabled' => true],
                        ['id' => 'community-featured-main', 'page' => 'community', 'slot' => 'featured_main', 'url' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=800&h=550&fit=crop', 'alt' => 'Featured transformation', 'enabled' => true],
                    ],
                ],
                'pages' => [
                    'faq' => [
                        ['question' => 'How long does delivery take?', 'answer' => 'Standard delivery takes 2–5 business days. Express delivery (₦3,000) takes 1–2 days. Subscribers always ship free.'],
                        ['question' => 'Are all products authentic?', 'answer' => 'Yes, 100%. All products are sourced directly from official brand distributors and authorized Korean suppliers. We never stock imitations.'],
                        ['question' => 'How does the Skin Quiz work?', 'answer' => 'Answer 14 quick questions about your skin type, concerns, lifestyle, and budget. Our Skin OS engine matches you to the right products in under 60 seconds — no account needed.'],
                        ['question' => 'Can I return opened products?', 'answer' => 'Opened products are non-refundable unless damaged or incorrect. Unopened items may be returned within 7 days of delivery in original packaging.'],
                    ],
                    'shipping_policy' => "Kominhoo ships all across Nigeria.\n\nStandard Delivery: 2–5 business days | FREE on orders over ₦50,000 (otherwise ₦1,500)\nExpress Delivery: 1–2 business days | ₦3,000 flat fee\nSubscription Box: Always free, shipped quarterly\n\nOrders are processed within 24 hours Monday–Saturday. You'll receive a tracking number via email once your order ships.",
                    'returns_policy' => 'We accept returns within 7 days of delivery for unopened products in original packaging. To initiate a return, email hello@kominhoo.com with your order number and reason. Exchange requests are processed within 3–5 business days.',
                    'community' => [
                        'hero_eyebrow' => 'Real Skin · Real People · Real Glow',
                        'hero_title_line_1' => 'The Kominhoo',
                        'hero_title_line_2' => 'Community',
                        'hero_description' => '50,000+ skin lovers sharing honest results, routines, and transformations. Your next favourite product is one post away.',
                        'hero_primary_cta_text' => '✨ Share Your Glow',
                        'hero_secondary_cta_text' => 'Take Skin Quiz →',
                        'live_label' => '247 sharing now',
                        'stats' => [
                            ['value' => '50K+', 'label' => 'Members'],
                            ['value' => '18K+', 'label' => 'Posts'],
                            ['value' => '4.8★', 'label' => 'Avg Rating'],
                        ],
                        'share_title' => 'Your Skin Story Deserves to Be Heard',
                        'share_description' => 'Join thousands sharing honest Korean skincare journeys — before & afters, routine breakdowns, and real results.',
                        'share_button_text' => '📸 Share My Story',
                        'share_tags_text' => '#KominhooSkin · #KominhooResults',
                    ],
                    'shop' => [
                        'hero_eyebrow' => 'Authentic Korean Skincare',
                        'hero_title_line_1' => 'Shop',
                        'hero_title_line_2' => 'All Products',
                        'hero_description' => '200+ authentic K-beauty products — matched to your skin profile',
                        'hero_cta_text' => '✨ Find My Match',
                        'tab_all' => 'All Products',
                        'tab_bundles' => 'Bundle Kits',
                        'tab_subscription' => 'Subscription',
                        'tab_new' => 'New Drops 🔴',
                        'tab_sale' => 'On Sale 🔥',
                        'bundles_title' => 'Bundle Kits',
                        'bundles_description' => 'Complete routines for specific concerns. Save 15–25% vs. buying individually.',
                        'bundles_cta_title' => "Can't Find Your Match?",
                        'bundles_cta_description' => "Take the Skin Quiz and we'll automatically build a bundle matched to your concerns.",
                        'bundles_cta_button_text' => 'Take the Skin Quiz →',
                        'subscription_eyebrow' => 'Quarterly Subscription',
                        'subscription_title' => 'Your Skincare Box, Expertly Curated',
                        'subscription_description' => 'Delivered every 3 months. Tested, effective, personalized. Free shipping always included.',
                        'subscription_terms_title' => 'Subscription Terms & Highlights',
                        'subscription_plans' => [
                            [
                                'icon'     => '🌱',
                                'name'     => 'Beginner',
                                'nickname' => '"What\'s Skincare?" Box',
                                'price'    => '₦40,000',
                                'period'   => 'every 3 months · 3–4 products',
                                'features' => [
                                    'Essential cleanser, toner, moisturizer + SPF',
                                    '50 loyalty points per quarter',
                                    'Free delivery included',
                                    'Easy cancel anytime',
                                    'Expert skin notes in every box',
                                ],
                                'featured'  => false,
                                'btn_class' => 'btn-outline',
                            ],
                            [
                                'icon'     => '🌿',
                                'name'     => 'Advanced',
                                'nickname' => '"Say Less, I\'m Glowing" Box',
                                'price'    => '₦100,000',
                                'period'   => 'every 3 months · 5–6 products',
                                'features' => [
                                    'Full AM + PM routine (all steps)',
                                    '100 loyalty points per quarter',
                                    'Free delivery + seasonal add-ons',
                                    'Priority access to new launches',
                                    'Personalized routine notes',
                                    'Quiz-updated each quarter',
                                ],
                                'featured'  => true,
                                'btn_class' => 'btn-dark',
                            ],
                            [
                                'icon'     => '✨',
                                'name'     => 'Master',
                                'nickname' => '"Okay… I\'m Listening" Box',
                                'price'    => '₦70,000',
                                'period'   => 'every 3 months · 4–5 products',
                                'features' => [
                                    'Base routine + targeted concern products',
                                    '80 loyalty points per quarter',
                                    'Free delivery included',
                                    'Concern-focused active ingredients',
                                    'Option to add-on previous items',
                                ],
                                'featured'  => false,
                                'btn_class' => 'btn-outline',
                            ],
                        ],
                        'subscription_terms' => [
                            ['icon' => '📅', 'title' => 'Billed quarterly',      'text' => 'every 3 months, processed on the 1st'],
                            ['icon' => '🔄', 'title' => 'Auto-renews',           'text' => 'cancel anytime, up to 7 days before shipment'],
                            ['icon' => '🎯', 'title' => 'Expert-curated',        'text' => 'real humans pick your products, not just algorithms'],
                            ['icon' => '➕', 'title' => 'Add-ons available',     'text' => 'request past products or extras with any box'],
                            ['icon' => '🚀', 'title' => 'Always free shipping',  'text' => 'no minimums for subscribers'],
                            ['icon' => '🔁', 'title' => 'Retake quiz',           'text' => 'anytime — your box updates with your profile'],
                        ],
                        'new_title' => 'New This Quarter',
                        'new_description' => 'The freshest additions to our collection — all tagged and quiz-ready.',
                        'sale_title' => '🔥 Sale Products',
                        'sale_description' => "Limited time offers — grab them before they're gone",
                    ],
                    'login' => [
                        'brand_eyebrow' => 'Korean Beauty, Personalized',
                        'brand_title_line_1' => 'Glow up starts',
                        'brand_title_line_2' => 'right here.',
                        'brand_description' => 'Sign in to access your personalized skin routine, loyalty points, and exclusive member deals.',
                        'form_title' => 'Welcome back',
                        'form_subtitle' => 'Sign in to your Kominhoo account',
                        'submit_text' => 'Sign In →',
                    ],
                    'signup' => [
                        'brand_eyebrow' => 'Join the glow club',
                        'brand_title' => 'Your perfect routine awaits.',
                        'brand_description' => 'Create your free account and unlock a personalized Korean skincare routine in 60 seconds.',
                        'perk_1_title' => 'Personalized Skin Quiz',
                        'perk_1_description' => 'Get matched to products that actually work for your skin type.',
                        'perk_2_title' => 'Earn Loyalty Points',
                        'perk_2_description' => 'Every purchase earns Glow Points. Redeem for products and perks.',
                        'perk_3_title' => 'Free Shipping at ₦50K+',
                        'perk_3_description' => 'Subscribers always ship free, no minimum required.',
                        'perk_4_title' => 'Exclusive Member Deals',
                        'perk_4_description' => 'Early access to launches, flash sales, and seasonal edits.',
                        'form_title' => 'Create your account',
                        'form_subtitle' => 'Join free and start your glow journey today',
                        'submit_text' => 'Create Free Account →',
                    ],
                    'product' => [
                        'trust_shipping'        => '🚚 Free shipping over ₦50k',
                        'trust_authentic'       => '✅ 100% Authentic',
                        'tab_about'             => 'About',
                        'tab_ingredients'       => 'Ingredients',
                        'tab_howto'             => 'How to Use',
                        'ingredients_subtitle'  => 'Key active ingredients and why they work for your skin:',
                        'pro_tip_label'         => '💡 Pro Tip:',
                        'reviews_empty_icon'    => '✍🏾',
                        'reviews_empty_title'   => 'No reviews yet',
                        'reviews_empty_body'    => 'Be the first to share your experience with this product.',
                        'review_form_title'     => 'Share Your Experience',
                        'review_form_subtitle'  => 'Tried this product? Your honest review helps other Nigerian shoppers make confident choices.',
                        'review_form_submit'    => 'Submit Review →',
                        'review_login_points'   => '50 loyalty points',
                        'related_eyebrow'       => 'You Might Also Like',
                        'related_title_line_1'  => 'Related',
                        'related_title_line_2'  => 'Products',
                        'breadcrumb_home'       => 'Home',
                        'breadcrumb_shop'       => 'Shop',
                    ],
                    'checkout' => [
                        'page_title' => 'Checkout',
                        'shipping_title' => 'Shipping Address',
                        'payment_title' => 'Payment Method',
                        'notes_title' => 'Order Notes',
                        'notes_optional_label' => '(optional)',
                        'summary_title' => 'Order Summary',
                        'place_order_text' => 'Place Order →',
                        'secure_checkout_text' => '🔒 Secure checkout',
                        'authentic_text' => '✅ 100% Authentic',
                    ],
                    'results' => [
                        'hero_badge' => '🎉 Your Skin Profile is Ready!',
                        'hero_greeting_prefix' => 'Hello,',
                        'hero_description' => "Here's what we found — and 3 ways to transform your skin.",
                        'paths_eyebrow' => 'Act on Your Results',
                        'paths_title' => '3 Ways to Transform Your Skin',
                        'paths_description' => 'Each option is personalized to your skin. Click any card to explore the full breakdown.',
                        'tips_eyebrow' => 'Expert Advice',
                        'tips_title' => 'Tips for Your Skin Type',
                        'sticky_title' => 'Your Routine — 9 products',
                        'sticky_description' => 'Complete AM + PM · Save 20% vs. buying separately',
                        'sticky_save_text' => 'Save Results',
                        'sticky_add_text' => '🛒 Add Full Routine → ₦152,500',
                    ],
                ],
            ],
            'quiz' => [
                'slides' => [],
                'concerns' => [],
                'stageTransitions' => [],
                'loadingSteps' => [],
                'settings' => [
                    'enabled' => true,
                    'maxConcernSelections' => 3,
                    'loadingDelayMs' => 3500,
                ],
                'tagWeights' => [],
            ],
        ];
    }

    private function mergeRecursiveDistinct(array $base, array $override): array
    {
        foreach ($override as $key => $value) {
            if (is_array($value) && Arr::isAssoc($value) && isset($base[$key]) && is_array($base[$key]) && Arr::isAssoc($base[$key])) {
                $base[$key] = $this->mergeRecursiveDistinct($base[$key], $value);
                continue;
            }

            $base[$key] = $value;
        }

        return $base;
    }
}
