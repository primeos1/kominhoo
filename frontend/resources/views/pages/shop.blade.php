@extends('layouts.app')
@section('title', 'Shop — Kominhoo Beauty')

@section('head')
<style>
.shop-hero { background: var(--rose-dark); color: #fff; padding: 48px 0; }
.shop-hero-inner { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 24px; }
.sort-bar { display: flex; gap: 12px; align-items: center; padding: 16px 0; flex-wrap: wrap; }
.sort-select { padding: 10px 36px 10px 14px; border-radius: var(--r-pill); border: 1.5px solid var(--border); font-size: .85rem; font-weight: 600; background: #fff; cursor: pointer; outline: none; }
.products-found { font-size: .88rem; color: var(--text-muted); font-weight: 600; }
.shop-tabs { display: flex; gap: 8px; margin-bottom: 0; border-bottom: 2px solid var(--border); padding-bottom: 0; }
.shop-tab { padding: 12px 24px; font-size: .9rem; font-weight: 700; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: var(--t-fast); color: var(--text-secondary); }
.shop-tab.active { color: var(--black); border-bottom-color: var(--lime); }
.tab-content { display: none; }
.tab-content.active { display: block; }
.filter-tag { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: var(--lime-pale); color: var(--gray-700); border-radius: var(--r-pill); font-size: .78rem; font-weight: 600; cursor: pointer; }
.filter-tag .remove { color: var(--gray-500); font-size: .8rem; }
.shop-layout { display: grid; grid-template-columns: 240px 1fr; gap: 32px; align-items: start; }
.filter-sidebar { background: #fff; border-radius: var(--r-xl); padding: 28px; border: 1.5px solid var(--border); position: sticky; top: calc(72px + 58px + 16px); }
.filter-title { font-size: 1rem; font-weight: 700; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
.filter-group { margin-bottom: 24px; border-top: 1px solid var(--border); padding-top: 18px; }
.filter-group-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--text-muted); margin-bottom: 12px; }
.filter-options { display: flex; flex-direction: column; gap: 8px; }
.sub-plans { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 48px; }
.sub-plan-card { background: #fff; border-radius: var(--r-xl); padding: 36px; border: 2px solid var(--border); transition: var(--t-base); position: relative; overflow: hidden; }
.sub-plan-card.featured { border-color: var(--red); background: var(--rose); }
.sub-plan-card:hover { box-shadow: var(--s-xl); transform: translateY(-4px); }
.sub-plan-tag { font-size: .66rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; padding: 4px 12px; border-radius: 99px; display: inline-block; margin-bottom: 16px; background: var(--blush-pale); color: var(--rose); }
.sub-plan-card.featured .sub-plan-tag { background: rgba(212,217,148,.15); color: var(--chartreuse); }
.plan-name { font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; margin-bottom: 4px; color: var(--black); }
.sub-plan-card.featured .plan-name { color: #fff; }
.plan-price { font-family: var(--font-display); font-size: 2.2rem; font-weight: 700; color: var(--rose); margin-bottom: 2px; line-height: 1; }
.sub-plan-card.featured .plan-price { color: #fff; }
.plan-period { font-size: .8rem; color: var(--text-muted); margin-bottom: 20px; }
.sub-plan-card.featured .plan-period { color: rgba(255,255,255,.4); }
.plan-features { display: flex; flex-direction: column; gap: 10px; margin-bottom: 28px; }
.plan-feature { display: flex; gap: 10px; font-size: .88rem; color: var(--text-secondary); }
.plan-feature::before { content: '✓'; color: var(--lime-dark); font-weight: 700; flex-shrink: 0; }
.sub-plan-card.featured .plan-feature { color: rgba(255,255,255,.76); }
.sub-plan-card.featured .plan-feature::before { color: var(--chartreuse); }
/* Mobile filter button */
.mobile-filter-btn {
  display: none;
  align-items: center; gap: 8px;
  padding: 9px 16px;
  background: #fff; border: 1.5px solid var(--border);
  border-radius: var(--r-pill);
  font-size: .82rem; font-weight: 700; color: var(--black);
  cursor: pointer; transition: var(--t-fast);
  white-space: nowrap;
}
.mobile-filter-btn:hover { background: var(--black); color: #fff; border-color: var(--black); }
.mobile-filter-count {
  background: var(--lime); color: var(--black);
  font-size: .65rem; font-weight: 700;
  width: 18px; height: 18px; border-radius: 50%;
  display: grid; place-items: center;
}

/* Mobile filter drawer */
.filter-drawer-overlay {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,.5); z-index: 2000;
  opacity: 0; visibility: hidden;
  transition: opacity .25s, visibility .25s;
}
.filter-drawer-overlay.open { opacity: 1; visibility: visible; }
.filter-drawer {
  position: fixed; left: 0; top: 0; bottom: 0;
  width: min(320px, 90vw);
  background: #fff;
  z-index: 2001;
  overflow-y: auto;
  transform: translateX(-100%);
  transition: transform .3s cubic-bezier(.4,0,.2,1);
  display: flex; flex-direction: column;
}
.filter-drawer.open { transform: translateX(0); }
.filter-drawer-header {
  padding: 20px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; background: #fff; z-index: 1;
}
.filter-drawer-header h3 { font-size: 1rem; font-weight: 700; }
.filter-drawer-close {
  width: 34px; height: 34px; border-radius: 50%;
  background: var(--gray-100); border: none;
  font-size: 1rem; cursor: pointer; display: grid; place-items: center;
  transition: background .15s;
}
.filter-drawer-close:hover { background: var(--gray-200); }
.filter-drawer-body { padding: 20px; flex: 1; }
.filter-drawer-footer {
  padding: 16px 20px;
  border-top: 1px solid var(--border);
  display: flex; gap: 10px;
  position: sticky; bottom: 0; background: #fff;
}

@media(max-width:768px) {
  .sub-plans { grid-template-columns: 1fr; }
  .shop-layout { grid-template-columns: 1fr; }
  .filter-sidebar { display: none; }
  .mobile-filter-btn { display: flex; }
  .filter-drawer-overlay { display: block; }
  .shop-hero { padding: 32px 0; }
  .shop-hero-inner { flex-direction: column; align-items: flex-start; gap: 16px; }
  .sort-bar { gap: 8px; }
  .sort-select { font-size: .82rem; padding: 8px 28px 8px 12px; }
}
@media(max-width:768px) {
  .shop-tabs { overflow-x: auto; scrollbar-width: none; flex-wrap: nowrap; -webkit-overflow-scrolling: touch; }
  .shop-tabs::-webkit-scrollbar { display: none; }
  .shop-tab { white-space: nowrap; flex-shrink: 0; padding: 12px 16px; font-size: .82rem; }
}
@media(max-width:480px) {
  .sub-plans { gap: 16px; }
  .sub-plan-card { padding: 24px 20px; }
}
.guide-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.guide-card-img { background-size: cover; background-position: center; border-radius: 24px; aspect-ratio: 1 / 1.2; position: relative; overflow: hidden; cursor: pointer; transition: transform var(--t-base), box-shadow var(--t-base); }
.guide-card-img:hover { transform: translateY(-6px); box-shadow: 0 24px 56px rgba(137,57,65,.18); }
.guide-card-img.featured { grid-column: span 2; aspect-ratio: 2 / 1.1; }
.guide-img-inner { position: absolute; inset: 0; background: linear-gradient(160deg, rgba(28,20,22,.52) 0%, rgba(28,20,22,.18) 50%, rgba(28,20,22,.62) 100%); padding: 24px; display: flex; flex-direction: column; justify-content: flex-end; color: #fff; }
.guide-img-title { font-family: 'Bodoni Moda', Georgia, serif; font-size: 1.5rem; font-weight: 600; margin-bottom: 6px; letter-spacing: -.01em; }
.guide-card-img.featured .guide-img-title { font-size: 1.9rem; }
.guide-img-desc { font-family: 'Jost', sans-serif; font-size: .8rem; opacity: .78; margin-bottom: 14px; max-width: 80%; line-height: 1.5; }
.guide-img-footer { display: flex; justify-content: space-between; align-items: center; }
.guide-img-count { background: rgba(255,255,255,.15); backdrop-filter: blur(6px); padding: 4px 12px; border-radius: 99px; font-family: 'Jost', sans-serif; font-size: .72rem; font-weight: 600; }
.guide-img-arrow { font-family: 'Jost', sans-serif; font-weight: 700; font-size: .82rem; border-bottom: 1.5px solid var(--chartreuse); color: var(--chartreuse); }
@media(max-width:768px) { .guide-grid { grid-template-columns: repeat(2, 1fr); } .guide-card-img.featured { grid-column: span 2; } }
@media(max-width:480px) { .guide-grid { grid-template-columns: 1fr; } .guide-card-img.featured { grid-column: span 1; aspect-ratio: 1 / 1.2; } }
.guide-modal-overlay { position: fixed; inset: 0; background: rgba(28,20,22,.86); backdrop-filter: blur(10px); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; visibility: hidden; transition: opacity .35s ease, visibility .35s ease; }
.guide-modal-overlay.open { opacity: 1; visibility: visible; }
.guide-modal { background: #faf6f3; border-radius: 28px; width: 100%; max-width: 980px; max-height: 88vh; overflow-y: auto; position: relative; transform: translateY(24px); transition: transform .38s cubic-bezier(.34,1.56,.64,1); }
.guide-modal-overlay.open .guide-modal { transform: translateY(0); }
.guide-modal-header { padding: 32px 36px 22px; display: flex; align-items: flex-start; gap: 18px; border-bottom: 1px solid rgba(137,57,65,.12); }
.guide-modal-icon { width: 58px; height: 58px; border-radius: 16px; background: #fff; display: grid; place-items: center; font-size: 1.7rem; flex-shrink: 0; box-shadow: 0 4px 18px rgba(0,0,0,.09); }
.guide-modal-close { position: absolute; top: 18px; right: 22px; background: rgba(255,255,255,.85); border: none; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; font-size: 1rem; display: grid; place-items: center; transition: background .2s; }
.guide-modal-close:hover { background: #fff; }
.guide-modal-products { padding: 24px 36px 36px; display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px; }
@media(max-width:640px) { .guide-modal-header { padding: 24px 20px 18px; } .guide-modal-products { padding: 16px 20px 28px; grid-template-columns: 1fr 1fr; gap: 12px; } }
</style>
@endsection

@section('content')

@php
  $shopCms    = data_get($siteContent, 'pages.shop', []);
  $subSection = data_get($siteContent, 'subscription_section', []);
@endphp

{{-- ── Shop Hero ── --}}
<div class="shop-hero">
  <div class="container">
    <div class="shop-hero-inner">
      <div>
        <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:12px">{{ data_get($shopCms, 'hero_eyebrow', 'Authentic Korean Skincare') }}</div>
        <h1 style="font-family:var(--font-display);font-size:clamp(2rem,4vw,3.5rem);margin-bottom:12px">{{ data_get($shopCms, 'hero_title_line_1', 'Shop') }} <em>{{ data_get($shopCms, 'hero_title_line_2', 'All Products') }}</em></h1>
        <p style="color:rgba(255,255,255,.5);font-size:.95rem">{{ data_get($shopCms, 'hero_description', '200+ authentic K-beauty products — matched to your skin profile') }}</p>
      </div>
      <a href="{{ route('quiz') }}" class="btn btn-primary btn-lg">{{ data_get($shopCms, 'hero_cta_text', '✨ Find My Match') }}</a>
    </div>
  </div>
</div>

{{-- ── Sticky Tabs ── --}}
<div style="background:#fff;border-bottom:1px solid var(--border);position:sticky;top:72px;z-index:99">
  <div class="container">
    <div class="shop-tabs">
      <div class="shop-tab active" onclick="switchTab('all',this)">{{ data_get($shopCms, 'tab_all', 'All Products') }}</div>
      <div class="shop-tab" onclick="switchTab('bundles',this)">{{ data_get($shopCms, 'tab_bundles', 'Bundle Kits') }}</div>
      <div class="shop-tab" onclick="switchTab('subscription',this)">{{ data_get($shopCms, 'tab_subscription', 'Subscription') }}</div>
      <div class="shop-tab" onclick="switchTab('new',this)">{{ data_get($shopCms, 'tab_new', 'New Drops 🔴') }}</div>
      <div class="shop-tab" onclick="switchTab('sale',this)">{{ data_get($shopCms, 'tab_sale', 'On Sale 🔥') }}</div>
      <div class="shop-tab" onclick="switchTab('guides',this)">{{ data_get($shopCms, 'tab_guides', 'Buying Guides') }}</div>
    </div>
  </div>
</div>

<section class="section-sm" style="background:var(--cream)">
  <div class="container">

    {{-- ── ALL PRODUCTS TAB ── --}}
    <div class="tab-content active" id="tab-all">
      <div class="shop-layout">

        {{-- Filter Sidebar --}}
        <aside class="filter-sidebar">
          <div class="filter-title">
            Filters
            <button onclick="clearFilters()" style="font-size:.75rem;font-weight:600;color:var(--red)">Clear All</button>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Category</div>
            <div class="filter-options">
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Cleanser"><span class="checkbox-box"></span>Cleansers</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Toner"><span class="checkbox-box"></span>Toners</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Serum"><span class="checkbox-box"></span>Serums &amp; Essences</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Moisturizer"><span class="checkbox-box"></span>Moisturizers</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Sunscreen"><span class="checkbox-box"></span>SPF / Sunscreen</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Mask"><span class="checkbox-box"></span>Masks</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Treatment"><span class="checkbox-box"></span>Treatments</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Makeup"><span class="checkbox-box"></span>Makeup</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Haircare"><span class="checkbox-box"></span>Haircare</label>
            </div>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Skin Type</div>
            <div class="filter-options">
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Oily"><span class="checkbox-box"></span>Oily</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Dry"><span class="checkbox-box"></span>Dry</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Combination"><span class="checkbox-box"></span>Combination</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Normal"><span class="checkbox-box"></span>Normal</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Sensitive"><span class="checkbox-box"></span>Sensitive</label>
            </div>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Skin Concern</div>
            <div class="filter-options">
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Acne"><span class="checkbox-box"></span>Acne / Breakouts</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Hyperpigmentation"><span class="checkbox-box"></span>Hyperpigmentation</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Dehydration"><span class="checkbox-box"></span>Dehydration</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Dullness"><span class="checkbox-box"></span>Dullness</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Fine Lines"><span class="checkbox-box"></span>Fine Lines</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Wrinkles"><span class="checkbox-box"></span>Wrinkles</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Large Pores"><span class="checkbox-box"></span>Large Pores</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Texture"><span class="checkbox-box"></span>Uneven Texture</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Redness"><span class="checkbox-box"></span>Redness</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Sensitivity"><span class="checkbox-box"></span>Sensitivity</label>
            </div>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Price Tier</div>
            <div class="filter-options">
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Basic"><span class="checkbox-box"></span>Basic (Under ₦12,000)</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Mid"><span class="checkbox-box"></span>Mid-Range (₦12k–₦30k)</label>
              <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Premium"><span class="checkbox-box"></span>Premium (₦30k+)</label>
            </div>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Key Ingredients</div>
            <div style="display:flex;flex-wrap:wrap;gap:6px">
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Niacinamide">Niacinamide</span>
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Salicylic Acid">BHA</span>
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Hyaluronic Acid">Hyaluronic Acid</span>
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Retinol">Retinol</span>
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Vitamin C">Vitamin C</span>
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Centella Asiatica">Centella</span>
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Ceramides">Ceramides</span>
              <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Snail Mucin">Snail Mucin</span>
            </div>
          </div>
        </aside>

        {{-- Product Grid --}}
        <div>
          <div class="sort-bar">
            <button class="mobile-filter-btn" id="mobileFilterBtn" onclick="openFilterDrawer()">
              ⚙ Filters <span class="mobile-filter-count" id="mobileFilterCount" style="display:none">0</span>
            </button>
            <span class="products-found" id="product-count">20 products</span>
            <div style="flex:1"></div>
            <div style="display:flex;gap:6px;flex-wrap:wrap" id="active-filters"></div>
            <select class="sort-select select" onchange="applyFilters()">
              <option value="default">Sort: Featured</option>
              <option value="price-asc">Price: Low to High</option>
              <option value="price-desc">Price: High to Low</option>
              <option value="rating">Best Rated</option>
              <option value="reviews">Most Reviewed</option>
            </select>
          </div>
          <div class="product-grid" id="products-grid"></div>
          <div style="text-align:center;margin-top:40px">
            <button class="btn btn-outline" onclick="showToast('✓','Loading more products...')">Load More Products →</button>
          </div>
        </div>
      </div>
    </div>

    {{-- ── BUNDLES TAB ── --}}
    <div class="tab-content" id="tab-bundles">
      <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:8px">{{ data_get($shopCms, 'bundles_title', 'Bundle Kits') }}</h2>
      <p style="color:var(--text-secondary);margin-bottom:36px">{{ data_get($shopCms, 'bundles_description', 'Complete routines for specific concerns. Save 15–25% vs. buying individually.') }}</p>
      <div class="bundle-grid" style="margin-bottom:48px" id="shop-bundle-grid"></div>
      <div style="background:var(--black);color:#fff;border-radius:var(--r-xl);padding:48px;text-align:center">
        <h3 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:12px">{{ data_get($shopCms, 'bundles_cta_title', "Can't Find Your Match?") }}</h3>
        <p style="color:rgba(255,255,255,.6);margin-bottom:24px">{{ data_get($shopCms, 'bundles_cta_description', "Take the Skin Quiz and we'll automatically build a bundle matched to your concerns.") }}</p>
        <a href="{{ route('quiz') }}" class="btn btn-primary btn-lg">{{ data_get($shopCms, 'bundles_cta_button_text', 'Take the Skin Quiz →') }}</a>
      </div>
    </div>

    {{-- ── SUBSCRIPTION TAB ── --}}
    @php
      $subSection    = data_get($siteContent, 'subscription_section', []);
      $subHeading    = data_get($subSection, 'heading', 'Your Skin Expert, On Autopilot');
      $subHeadingParts = explode(',', $subHeading, 2);
    @endphp
    <div class="tab-content" id="tab-subscription">
      <div style="text-align:center;max-width:600px;margin:0 auto 48px">
        <div class="sec-kicker" style="justify-content:center">{{ data_get($subSection, 'kicker', 'Quarterly Subscription') }}</div>
        <h2 style="font-family:var(--font-display);font-size:2.5rem;margin-bottom:12px">
          @if(count($subHeadingParts) === 2)
            {{ trim($subHeadingParts[0]) }}, <em style="font-weight:400;font-style:italic">{{ trim($subHeadingParts[1]) }}</em>
          @else
            {{ $subHeading }}
          @endif
        </h2>
        <p style="color:var(--text-secondary);line-height:1.7">{{ data_get($subSection, 'description', 'Expert-curated routines delivered every 3 months — personalized, free shipping, easy to pause or cancel.') }}</p>
      </div>
      <div class="sub-plans">
        @forelse($subscriptionPlans as $planIdx => $plan)
        @php
          $planFeatured = $plan['is_popular'] ?? false;
          $planBadge    = $planFeatured ? '⭐ Most Popular' : ($plan['badge'] ?? ucfirst($plan['billing_cycle'] ?? 'Standard'));
          $planPeriod   = $plan['frequency_label'] ?? 'per month';
          $planProducts = $plan['products_count'] ?? '';
          $delayClass   = $planIdx === 0 ? '' : ($planIdx === 1 ? ' reveal-delay-2' : ' reveal-delay-4');
        @endphp
        <div class="sub-plan-card {{ $planFeatured ? 'featured' : '' }} reveal{{ $delayClass }}">
          <div class="sub-plan-tag">{{ $planBadge }}</div>
          <div class="plan-name">{{ $plan['name'] }}</div>
          <div class="plan-price">₦{{ number_format($plan['price']) }}</div>
          <div class="plan-period">{{ $planPeriod }}{{ $planProducts ? ' · ' . $planProducts . ' products' : '' }}</div>
          <div class="sub-plan-hr" style="height:1px;background:var(--border);margin:0 0 20px"></div>
          <div class="plan-features">
            @foreach($plan['features'] ?? [] as $feat)
            <div class="plan-feature">{{ $feat }}</div>
            @endforeach
          </div>
          <a href="{{ route('shop', ['tab' => 'subscription']) }}" class="btn {{ $planFeatured ? 'btn-primary' : 'btn-outline' }}" style="width:100%">{{ $planFeatured ? 'Subscribe Now →' : 'Get Started' }}</a>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted)">No subscription plans available yet.</div>
        @endforelse
      </div>
    </div>

    {{-- ── NEW DROPS TAB ── --}}
    <div class="tab-content" id="tab-new">
      <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:8px">{{ data_get($shopCms, 'new_title', 'New This Quarter') }}</h2>
      <p style="color:var(--text-secondary);margin-bottom:36px">{{ data_get($shopCms, 'new_description', 'The freshest additions to our collection — all tagged and quiz-ready.') }}</p>
      <div class="product-grid" id="new-products-grid"></div>
    </div>
 

    {{-- ── MAKEUP TAB ── --}}
    <div class="tab-content" id="tab-makeup">
      <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:8px">{{ data_get($shopCms, 'makeup_title', 'Makeup') }}</h2>
      <p style="color:var(--text-secondary);margin-bottom:36px">{{ data_get($shopCms, 'makeup_description', 'K-beauty inspired makeup — light coverage, skin-loving formulas.') }}</p>
      <div class="product-grid" id="makeup-products-grid"></div>
    </div>

    {{-- ── HAIRCARE TAB ── --}}
    <div class="tab-content" id="tab-haircare">
      <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:8px">{{ data_get($shopCms, 'haircare_title', 'Haircare') }}</h2>
      <p style="color:var(--text-secondary);margin-bottom:36px">{{ data_get($shopCms, 'haircare_description', 'Nourishing K-beauty haircare — shampoos, treatments, and more.') }}</p>
      <div class="product-grid" id="haircare-products-grid"></div>
    </div>

    {{-- ── SALE TAB ── --}}
    <div class="tab-content" id="tab-sale">
      <div style="background:var(--red);color:#fff;border-radius:var(--r-xl);padding:28px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:32px">
        <div>
          <h2 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:4px">{{ data_get($shopCms, 'sale_title', '🔥 Sale Products') }}</h2>
          <p style="color:rgba(255,255,255,.8)">{{ data_get($shopCms, 'sale_description', "Limited time offers — grab them before they're gone") }}</p>
        </div>
        <div id="sale-countdown" style="display:flex;gap:12px">
          <div style="text-align:center"><div style="font-size:1.5rem;font-weight:700" id="s-h">--</div><div style="font-size:.7rem;opacity:.7">HRS</div></div>
          <div style="font-size:1.5rem;font-weight:300;opacity:.5">:</div>
          <div style="text-align:center"><div style="font-size:1.5rem;font-weight:700" id="s-m">--</div><div style="font-size:.7rem;opacity:.7">MIN</div></div>
          <div style="font-size:1.5rem;font-weight:300;opacity:.5">:</div>
          <div style="text-align:center"><div style="font-size:1.5rem;font-weight:700" id="s-s">--</div><div style="font-size:.7rem;opacity:.7">SEC</div></div>
        </div>
      </div>
      <div class="product-grid" id="sale-products-grid"></div>
    </div>

    {{-- ── BUYING GUIDES TAB ── --}}
    <div class="tab-content" id="tab-guides">
      <h2 style="font-family:var(--font-display);font-size:2rem;margin-bottom:8px">{{ data_get($shopCms, 'guides_title', 'Buying Guides') }}</h2>
      <p style="color:var(--text-secondary);margin-bottom:36px">{{ data_get($shopCms, 'guides_description', 'Expert-curated routines and recommendations for every skin concern.') }}</p>
      <div class="guide-grid" id="shop-guide-grid"></div>
    </div>

  </div>
</section>

{{-- ── Mobile Filter Drawer ── --}}
<div class="filter-drawer-overlay" id="filterDrawerOverlay" onclick="closeFilterDrawer()"></div>
<div class="filter-drawer" id="filterDrawer" aria-label="Filters">
  <div class="filter-drawer-header">
    <h3>Filters</h3>
    <button class="filter-drawer-close" onclick="closeFilterDrawer()" aria-label="Close filters">✕</button>
  </div>
  <div class="filter-drawer-body">

    <div class="filter-group">
      <div class="filter-group-title">Category</div>
      <div class="filter-options">
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Cleanser"><span class="checkbox-box"></span>Cleansers</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Toner"><span class="checkbox-box"></span>Toners</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Serum"><span class="checkbox-box"></span>Serums &amp; Essences</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Moisturizer"><span class="checkbox-box"></span>Moisturizers</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Sunscreen"><span class="checkbox-box"></span>SPF / Sunscreen</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Mask"><span class="checkbox-box"></span>Masks</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Treatment"><span class="checkbox-box"></span>Treatments</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Makeup"><span class="checkbox-box"></span>Makeup</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Haircare"><span class="checkbox-box"></span>Haircare</label>
      </div>
    </div>

    <div class="filter-group">
      <div class="filter-group-title">Skin Type</div>
      <div class="filter-options">
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Oily"><span class="checkbox-box"></span>Oily</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Dry"><span class="checkbox-box"></span>Dry</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Combination"><span class="checkbox-box"></span>Combination</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Normal"><span class="checkbox-box"></span>Normal</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Sensitive"><span class="checkbox-box"></span>Sensitive</label>
      </div>
    </div>

    <div class="filter-group">
      <div class="filter-group-title">Skin Concern</div>
      <div class="filter-options">
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Acne"><span class="checkbox-box"></span>Acne / Breakouts</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Hyperpigmentation"><span class="checkbox-box"></span>Hyperpigmentation</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Dehydration"><span class="checkbox-box"></span>Dehydration</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Dullness"><span class="checkbox-box"></span>Dullness</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Fine Lines"><span class="checkbox-box"></span>Fine Lines</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Wrinkles"><span class="checkbox-box"></span>Wrinkles</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Large Pores"><span class="checkbox-box"></span>Large Pores</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Texture"><span class="checkbox-box"></span>Uneven Texture</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Redness"><span class="checkbox-box"></span>Redness</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Sensitivity"><span class="checkbox-box"></span>Sensitivity</label>
      </div>
    </div>

    <div class="filter-group">
      <div class="filter-group-title">Price Tier</div>
      <div class="filter-options">
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Basic"><span class="checkbox-box"></span>Basic (Under ₦12,000)</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Mid"><span class="checkbox-box"></span>Mid-Range (₦12k–₦30k)</label>
        <label class="checkbox-label"><input type="checkbox" onchange="applyFilters()" value="Premium"><span class="checkbox-box"></span>Premium (₦30k+)</label>
      </div>
    </div>

    <div class="filter-group" style="border-bottom:none;margin-bottom:0;padding-bottom:0">
      <div class="filter-group-title">Key Ingredients</div>
      <div style="display:flex;flex-wrap:wrap;gap:6px">
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Niacinamide">Niacinamide</span>
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Salicylic Acid">BHA</span>
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Hyaluronic Acid">Hyaluronic Acid</span>
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Retinol">Retinol</span>
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Vitamin C">Vitamin C</span>
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Centella Asiatica">Centella</span>
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Ceramides">Ceramides</span>
        <span class="tag" onclick="this.classList.toggle('active');applyFilters()" data-ingredient="Snail Mucin">Snail Mucin</span>
      </div>
    </div>

  </div>
  <div class="filter-drawer-footer">
    <button class="btn btn-outline" style="flex:1;justify-content:center" onclick="clearFilters();closeFilterDrawer()">Clear All</button>
    <button class="btn btn-primary" style="flex:2;justify-content:center" onclick="applyFilterDrawer()">Show Results</button>
  </div>
</div>

{{-- ── Bundle Modal ──────────────────────────────────────────────── --}}
<div class="guide-modal-overlay" id="shopBundleModalOverlay" onclick="if(event.target===this)closeShopBundleModal()" role="dialog" aria-modal="true">
  <div class="guide-modal">
    <button class="guide-modal-close" onclick="closeShopBundleModal()" aria-label="Close">✕</button>
    <div class="guide-modal-header" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">
      <div style="flex:1;min-width:0;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
          <span id="shopBundleModalTag" style="font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:var(--lime);color:var(--black);padding:3px 10px;border-radius:20px;"></span>
        </div>
        <h2 id="shopBundleModalTitle" style="font-family:'Bodoni Moda',Georgia,serif;font-size:1.8rem;font-weight:600;margin-bottom:6px;"></h2>
        <p id="shopBundleModalDesc" style="font-family:'Jost',sans-serif;font-size:.9rem;color:var(--text-secondary);line-height:1.5;max-width:55ch;"></p>
      </div>
      <div style="text-align:right;flex-shrink:0;">
        <div style="font-size:1.5rem;font-weight:700;font-family:'Jost',sans-serif;color:var(--black);" id="shopBundleModalPrice"></div>
        <div id="shopBundleModalOrigPrice" style="font-size:.85rem;color:var(--text-secondary);text-decoration:line-through;margin-top:2px;"></div>
        <button id="shopBundleModalAddBtn" class="btn btn-primary" style="margin-top:12px;font-size:.82rem;padding:10px 20px;">Add Bundle to Cart</button>
      </div>
    </div>
    <div class="guide-modal-products bundle-modal-products"></div>
  </div>
</div>

{{-- ── Guide Products Modal ────────────────────────────────────── --}}
<div class="guide-modal-overlay" id="shopGuideModalOverlay" onclick="if(event.target===this)closeGuideModal()" role="dialog" aria-modal="true">
  <div class="guide-modal">
    <button class="guide-modal-close" onclick="closeGuideModal()" aria-label="Close">✕</button>
    <div class="guide-modal-header">
      <div class="guide-modal-icon">📖</div>
      <div>
        <h2 id="shopGuideModalTitle" style="font-family:'Bodoni Moda',Georgia,serif;font-size:1.8rem;font-weight:600;margin-bottom:6px;"></h2>
        <p id="shopGuideModalDesc" style="font-family:'Jost',sans-serif;font-size:.9rem;color:var(--text-secondary);line-height:1.5;max-width:55ch;"></p>
      </div>
    </div>
    <div class="guide-modal-products"></div>
  </div>
</div>

@endsection

@section('scripts')
@if(!empty($products) && count($products) > 0)
<script>
// Replace static PRODUCTS with live DB products before DOMContentLoaded fires
(function() {
  var srvProds = @json($products);
  if (!Array.isArray(srvProds) || !srvProds.length) return;
  var normalized = srvProds.map(function(p) {
    var imgs   = p.images;
    var imgUrl = Array.isArray(imgs) ? (imgs[0] || '') : (imgs || '');
    return {
      id:            p.id,
      name:          p.name,
      brand:         p.brand,
      category:      p.category,
      price:         parseFloat(p.price) || 0,
      originalPrice: p.original_price ? parseFloat(p.original_price) : null,
      skinType:      Array.isArray(p.skin_types) ? p.skin_types : [],
      concern:       [],
      inStock:       p.is_active !== false && (p.stock === undefined || p.stock > 0),
      isNew:         false,
      image:         imgUrl,
      badge:         null,
      desc:          p.description || '',
      rating:        parseFloat(p.rating) || 4.5,
      reviews:       p.review_count || 0,
      priceTier:     parseFloat(p.price) > 30000 ? 'Premium' : parseFloat(p.price) > 15000 ? 'Mid' : 'Basic',
      ingredients:   [],
      routineStep:   p.category || 'Serum',
    };
  });
  PRODUCTS.length = 0;
  normalized.forEach(function(p) { PRODUCTS.push(p); });
})();
</script>
@endif
<script>
let currentFilters = { categories:[], skinTypes:[], concerns:[], priceTiers:[], ingredients:[], sort:'default' };

document.addEventListener('DOMContentLoaded', () => {
  renderProducts(PRODUCTS);

  document.getElementById('shop-bundle-grid').innerHTML = BUNDLES.map(b => `
    <div class="bundle-card" onclick="openShopBundleModal(${b.id})" style="cursor:pointer">
      <img src="${b.image}" alt="${b.name}">
      <div class="bundle-overlay">
        <div class="bundle-tag"><span class="badge badge-lime">${b.tag || ''}</span></div>
        <div class="bundle-name">${b.name}</div>
        <div class="bundle-includes">${(b.products||[]).length} products · ${(b.desc||'').slice(0,40)}…</div>
        <div class="bundle-price">₦${b.price.toLocaleString()}${b.originalPrice ? ` <span style="font-size:.82rem;color:rgba(255,255,255,.5);text-decoration:line-through;font-weight:400">₦${b.originalPrice.toLocaleString()}</span>` : ''}</div>
        <button class="bundle-btn" onclick="event.stopPropagation();openShopBundleModal(${b.id})">View Bundle →</button>
      </div>
    </div>`).join('');

  window.openShopBundleModal = function(id) {
    const b = BUNDLES.find(x => x.id === id);
    if (!b) return;
    const overlay = document.getElementById('shopBundleModalOverlay');
    if (!overlay) return;
    overlay.querySelector('#shopBundleModalTitle').textContent = b.name;
    overlay.querySelector('#shopBundleModalDesc').textContent  = b.desc || '';
    overlay.querySelector('#shopBundleModalTag').textContent   = b.tag  || '';
    overlay.querySelector('#shopBundleModalPrice').textContent = '₦' + b.price.toLocaleString();
    const origEl = overlay.querySelector('#shopBundleModalOrigPrice');
    if (origEl) { origEl.textContent = b.originalPrice ? '₦' + b.originalPrice.toLocaleString() : ''; origEl.style.display = b.originalPrice ? '' : 'none'; }
    const products = (b.products || []).map(pid => PRODUCTS.find(p => p.id === pid)).filter(Boolean);
    const grid = overlay.querySelector('.bundle-modal-products');
    grid.innerHTML = products.length
      ? products.map(p => buildProductCard(p, '100%')).join('')
      : '<p style="color:var(--text-muted);text-align:center;padding:40px 0;grid-column:1/-1;">No products in this bundle yet.</p>';
    const addBtn = overlay.querySelector('#shopBundleModalAddBtn');
    if (addBtn) addBtn.onclick = function() { (b.products||[]).forEach(pid => addToCart(pid)); closeShopBundleModal(); };
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  window.closeShopBundleModal = function() {
    const overlay = document.getElementById('shopBundleModalOverlay');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = '';
  };

  document.getElementById('new-products-grid').innerHTML = PRODUCTS.filter(p => p.isNew || p.badge === 'New').map(p => buildProductCard(p, '100%')).join('');
  document.getElementById('sale-products-grid').innerHTML = PRODUCTS.filter(p => p.originalPrice).map(p => buildProductCard(p, '100%')).join('');
  document.getElementById('makeup-products-grid').innerHTML = PRODUCTS.filter(p => p.category === 'Makeup').map(p => buildProductCard(p, '100%')).join('');
  document.getElementById('haircare-products-grid').innerHTML = PRODUCTS.filter(p => p.category === 'Haircare').map(p => buildProductCard(p, '100%')).join('');

  document.getElementById('shop-guide-grid').innerHTML = GUIDES.map((g, i) => `
    <div class="guide-card-img${i === 0 ? ' featured' : ''}"
         style="background-image:url('${g.image}')"
         onclick="openGuideModal(${g.id})"
         role="button" tabindex="0" aria-label="${g.title}">
      <div class="guide-img-inner">
        <div class="guide-img-icon">${g.icon || '📖'}</div>
        <div class="guide-img-title">${g.title}</div>
        <div class="guide-img-desc">${g.desc}</div>
        <div class="guide-img-footer">
          <span class="guide-img-count">${(g.products||[]).length} products</span>
          <span class="guide-img-arrow">Explore →</span>
        </div>
      </div>
    </div>`).join('');

  window.openGuideModal = function(id) {
    const g = GUIDES.find(x => x.id === id);
    if (!g) return;
    const overlay = document.getElementById('shopGuideModalOverlay');
    if (!overlay) return;
    overlay.querySelector('.guide-modal-icon').textContent = g.icon || '📖';
    overlay.querySelector('#shopGuideModalTitle').textContent = g.title;
    overlay.querySelector('#shopGuideModalDesc').textContent = g.desc;
    const products = (g.products || []).map(pid => PRODUCTS.find(p => p.id === pid)).filter(Boolean);
    const grid = overlay.querySelector('.guide-modal-products');
    grid.innerHTML = products.length
      ? products.map(p => buildProductCard(p, '100%')).join('')
      : '<p style="color:var(--text-muted);text-align:center;padding:40px 0;grid-column:1/-1;">No products in this guide yet.</p>';
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  window.closeGuideModal = function() {
    const overlay = document.getElementById('shopGuideModalOverlay');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = '';
  };
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeGuideModal(); closeShopBundleModal(); } });

  startSaleCountdown();

  const urlParams = new URLSearchParams(window.location.search);
  let tab = urlParams.get('tab')
    || (urlParams.get('bundles') ? 'bundles' : null)
    || (urlParams.get('type') === 'bundle' ? 'bundles' : null)
    || (urlParams.get('type') === 'guide' ? 'guides' : null);
  if (tab) {
    document.querySelectorAll('.shop-tab').forEach(t => t.classList.remove('active'));
    const tabEl = document.querySelector(`[onclick*="'${tab}'"]`);
    if (tabEl) { tabEl.classList.add('active'); document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active')); document.getElementById('tab-' + tab)?.classList.add('active'); }
  }

  const categoryParam = urlParams.get('category');
  if (categoryParam) {
    const categoryValue = categoryParam.charAt(0).toUpperCase() + categoryParam.slice(1).toLowerCase();
    const checkbox = document.querySelector(`.filter-options input[value="${categoryValue}"]`);
    if (checkbox) {
      checkbox.checked = true;
      applyFilters();
    }
  }
});

function renderProducts(products) {
  const sort = document.querySelector('.sort-select')?.value || 'default';
  let sorted = [...products];
  if (sort === 'price-asc') sorted.sort((a,b) => a.price - b.price);
  else if (sort === 'price-desc') sorted.sort((a,b) => b.price - a.price);
  else if (sort === 'rating') sorted.sort((a,b) => b.rating - a.rating);
  else if (sort === 'reviews') sorted.sort((a,b) => b.reviews - a.reviews);
  const grid = document.getElementById('products-grid');
  if (grid) grid.innerHTML = sorted.map(p => buildProductCard(p, '100%')).join('');
  const count = document.getElementById('product-count');
  if (count) count.textContent = `${sorted.length} products`;
}

function applyFilters() {
  const checkedCats = [...document.querySelectorAll('.filter-options input[value]:checked')].map(i => i.value);
  const activeIngredients = [...document.querySelectorAll('.tag.active[data-ingredient]')].map(t => t.dataset.ingredient);
  let filtered = PRODUCTS.filter(p => {
    if (checkedCats.length) {
      const cats = checkedCats.filter(v => ['Cleanser','Toner','Serum','Moisturizer','Sunscreen','Mask','Treatment','Makeup','Haircare'].includes(v));
      const types = checkedCats.filter(v => ['Oily','Dry','Combination','Normal','Sensitive'].includes(v));
      const concerns = checkedCats.filter(v => ['Acne','Hyperpigmentation','Dehydration','Dullness','Fine Lines','Wrinkles','Large Pores','Texture','Redness','Sensitivity'].includes(v));
      const tiers = checkedCats.filter(v => ['Basic','Mid','Premium'].includes(v));
      if (cats.length && !cats.includes(p.category)) return false;
      if (types.length && !types.some(t => p.skinType.includes(t))) return false;
      if (concerns.length && !concerns.some(c => p.concern.includes(c))) return false;
      if (tiers.length && !tiers.includes(p.priceTier)) return false;
    }
    if (activeIngredients.length && !activeIngredients.some(i => p.ingredients.includes(i))) return false;
    return true;
  });
  renderProducts(filtered);
}

function clearFilters() {
  document.querySelectorAll('.filter-options input').forEach(i => i.checked = false);
  document.querySelectorAll('.tag.active').forEach(t => t.classList.remove('active'));
  renderProducts(PRODUCTS);
}

function switchTab(tab, el) {
  document.querySelectorAll('.shop-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('tab-' + tab)?.classList.add('active');
}

// ── Mobile filter drawer ──────────────────────────────────────────────
function openFilterDrawer() {
  document.getElementById('filterDrawerOverlay').classList.add('open');
  document.getElementById('filterDrawer').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeFilterDrawer() {
  document.getElementById('filterDrawerOverlay').classList.remove('open');
  document.getElementById('filterDrawer').classList.remove('open');
  document.body.style.overflow = '';
}
function applyFilterDrawer() {
  applyFilters();
  closeFilterDrawer();
  // update mobile filter count badge
  const active = document.querySelectorAll('.filter-sidebar input:checked, .filter-sidebar .tag.active').length
              + document.querySelectorAll('.filter-drawer-body input:checked, .filter-drawer-body .tag.active').length;
  const badge = document.getElementById('mobileFilterCount');
  if (badge) { badge.textContent = active; badge.style.display = active ? 'grid' : 'none'; }
}

function startSaleCountdown() {
  const end = new Date(); end.setHours(end.getHours() + 12);
  function update() {
    const diff = end - new Date(); if (diff <= 0) return;
    const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
    const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
    const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
    const sh = document.getElementById('s-h'), sm = document.getElementById('s-m'), ss = document.getElementById('s-s');
    if (sh) sh.textContent = h; if (sm) sm.textContent = m; if (ss) ss.textContent = s;
  }
  update(); setInterval(update, 1000);
}
</script>
@endsection



