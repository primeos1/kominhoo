@extends('layouts.app')
@section('title', ($product['name'] ?? 'Product') . ' — Kominhoo Beauty')

@section('head')
<style>
/* ── Product Detail Page ── */
.pdp-wrap {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 56px; padding: 40px 0 56px; align-items: start;
}
.pdp-gallery { position: sticky; top: calc(var(--nav-h) + 24px); }
.pdp-main-img {
  border-radius: var(--r-xl); overflow: hidden;
  aspect-ratio: 1; background: var(--gray-100);
  margin-bottom: 12px; cursor: zoom-in; position: relative;
}
.pdp-main-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s ease; display: block; }
.pdp-main-img:hover img { transform: scale(1.05); }
.pdp-thumbs { display: flex; gap: 10px; }
.pdp-thumb {
  border-radius: var(--r-md); overflow: hidden; flex: 1;
  aspect-ratio: 1; cursor: pointer; border: 2px solid transparent;
  transition: border-color .2s; background: var(--gray-100);
}
.pdp-thumb.active { border-color: var(--lime); }
.pdp-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* Info */
.pdp-brand { font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; }
.pdp-name { font-family: var(--font-display); font-size: clamp(1.5rem, 2.5vw, 2.2rem); margin-bottom: 14px; line-height: 1.2; }
.pdp-stars-row { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; }
.pdp-stars-row a { color: var(--text-muted); font-size: .88rem; text-decoration: underline; text-underline-offset: 3px; }
.pdp-price-row { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
.pdp-price-current { font-size: 2rem; font-weight: 700; }
.pdp-price-original { font-size: 1rem; color: var(--text-muted); text-decoration: line-through; }
.pdp-save-badge { background: var(--red); color: #fff; font-size: .72rem; font-weight: 700; padding: 4px 10px; border-radius: var(--r-pill); }
.pdp-divider { height: 1px; background: var(--border); margin: 20px 0; }
.pdp-attrs { display: flex; flex-direction: column; gap: 11px; margin-bottom: 24px; }
.pdp-attr { display: flex; gap: 12px; font-size: .88rem; align-items: flex-start; }
.pdp-attr-label { font-weight: 700; min-width: 106px; color: var(--text-secondary); padding-top: 2px; flex-shrink: 0; }
.pdp-tag { display: inline-flex; padding: 3px 10px; background: var(--gray-100); border-radius: var(--r-pill); font-size: .76rem; font-weight: 600; margin: 2px 2px 2px 0; }

/* Quantity + actions */
.pdp-qty-row { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
.pdp-qty-label { font-size: .82rem; font-weight: 700; color: var(--text-muted); }
.pdp-qty-ctrl { display: flex; align-items: center; border: 1.5px solid var(--border); border-radius: var(--r-pill); overflow: hidden; }
.pdp-qty-btn { width: 36px; height: 36px; display: grid; place-items: center; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: background .15s; background: none; }
.pdp-qty-btn:hover { background: var(--gray-100); }
.pdp-qty-val { min-width: 36px; text-align: center; font-weight: 700; font-size: .95rem; }

.pdp-actions { display: flex; gap: 12px; margin-bottom: 18px; }
.pdp-add-btn {
  flex: 1; padding: 16px 20px; background: var(--rose-dark); color: #fff;
  font-size: .92rem; font-weight: 700; border-radius: var(--r-pill);
  transition: var(--t-base); letter-spacing: .01em;
}
.pdp-add-btn:hover { background: var(--rose); transform: translateY(-2px); box-shadow: 0 12px 32px rgba(137,57,65,.3); }
.pdp-add-btn:disabled { opacity: .45; cursor: not-allowed; transform: none; }
.pdp-wish-btn {
  width: 52px; height: 52px; border-radius: 50%; border: 2px solid var(--border);
  display: grid; place-items: center; font-size: 1.2rem;
  transition: var(--t-base); flex-shrink: 0;
}
.pdp-wish-btn:hover { border-color: var(--red); color: var(--red); }
.pdp-trust { display: flex; gap: 18px; flex-wrap: wrap; }
.pdp-trust-item { display: flex; align-items: center; gap: 6px; font-size: .78rem; color: var(--text-muted); font-weight: 600; }
.pdp-stock-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; margin-right: 4px; }
.pdp-stock-dot.in  { background: var(--success, #22c55e); }
.pdp-stock-dot.out { background: var(--red); }

/* Tabs */
.pdp-tabs { border-bottom: 2px solid var(--border); display: flex; gap: 0; margin-top: 48px; overflow-x: auto; }
.pdp-tab {
  padding: 14px 24px; font-weight: 700; font-size: .88rem; cursor: pointer;
  border-bottom: 3px solid transparent; margin-bottom: -2px;
  color: var(--text-muted); transition: color .2s, border-color .2s;
  white-space: nowrap;
}
.pdp-tab.active { color: var(--black); border-bottom-color: var(--lime); }
.pdp-panel { display: none; padding-top: 36px; }
.pdp-panel.active { display: block; }

/* Reviews */
.review-summary {
  display: grid; grid-template-columns: 160px 1fr; gap: 40px;
  align-items: center; padding: 32px; background: var(--gray-100);
  border-radius: var(--r-xl); margin-bottom: 32px;
}
.review-big-num { font-size: 5rem; font-weight: 700; line-height: 1; }
.review-stars-big { color: #F59E0B; font-size: 1.4rem; margin: 6px 0; }
.review-bar-row { display: flex; align-items: center; gap: 10px; font-size: .82rem; margin-bottom: 7px; }
.review-bar-track { flex: 1; height: 7px; background: var(--gray-200); border-radius: 4px; overflow: hidden; }
.review-bar-fill { height: 100%; background: var(--lime); border-radius: 4px; transition: width .6s ease; }
.review-card { padding: 24px; border: 1.5px solid var(--border); border-radius: var(--r-lg); margin-bottom: 16px; }
.review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
.review-name { font-weight: 700; font-size: .95rem; margin-bottom: 4px; }
.review-date { font-size: .76rem; color: var(--text-muted); }
.review-stars { color: #F59E0B; font-size: .9rem; }
.review-body { font-size: .9rem; line-height: 1.75; color: var(--text-secondary); }
.review-verified { font-size: .7rem; font-weight: 700; color: var(--success, #22c55e); background: rgba(34,197,94,.1); padding: 3px 9px; border-radius: var(--r-pill); display: inline-block; margin-top: 10px; }

/* Review success / error alerts */
.review-alert { padding: 14px 18px; border-radius: var(--r-md); margin-bottom: 24px; font-size: .9rem; font-weight: 600; display: flex; align-items: flex-start; gap: 10px; }
.review-alert.success { background: rgba(34,197,94,.1); border: 1.5px solid rgba(34,197,94,.35); color: #166534; }
.review-alert.error   { background: rgba(232,56,46,.07); border: 1.5px solid rgba(232,56,46,.3); color: #991b1b; }

/* Review form */
.review-form-wrap {
  margin-top: 40px; padding: 32px 36px; background: var(--gray-100);
  border-radius: var(--r-xl); border: 1.5px solid var(--border);
}
.review-form-title { font-family: var(--font-display); font-size: 1.4rem; margin-bottom: 6px; }
.review-form-sub { font-size: .85rem; color: var(--text-muted); margin-bottom: 24px; }
.review-form { display: flex; flex-direction: column; gap: 16px; }
.review-form-field label { display: block; font-size: .8rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .07em; }
.review-form-field input,
.review-form-field textarea,
.review-form-field select {
  width: 100%; padding: 11px 14px; background: #fff; border: 1.5px solid var(--border);
  border-radius: var(--r-md); font-size: .9rem; font-family: inherit; transition: border-color .2s;
  outline: none; color: var(--text-primary);
}
.review-form-field input:focus,
.review-form-field textarea:focus,
.review-form-field select:focus { border-color: var(--lime); }
.review-form-field textarea { resize: vertical; min-height: 100px; }
.review-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

/* Star picker */
.star-picker-wrap { margin-bottom: 4px; }
.star-picker-label { font-size: .8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 8px; display: block; }
.star-picks { display: flex; gap: 4px; }
.star-pick {
  font-size: 2rem; cursor: pointer; color: var(--gray-300, #d1d5db);
  transition: color .15s, transform .15s; user-select: none;
}
.star-pick.lit  { color: #F59E0B; }
.star-pick:hover { transform: scale(1.15); }
.star-error { font-size: .78rem; color: var(--red); margin-top: 4px; display: none; }

.review-submit-btn {
  padding: 14px 28px; background: var(--rose-dark); color: #fff;
  font-weight: 700; font-size: .92rem; border-radius: var(--r-pill);
  transition: var(--t-base); align-self: flex-start; letter-spacing: .01em;
}
.review-submit-btn:hover { background: var(--rose); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(137,57,65,.3); }

.review-login-prompt {
  padding: 20px 24px; background: var(--lime-pale, #f7fce7); border-radius: var(--r-md);
  border-left: 4px solid var(--lime); display: flex; align-items: center; gap: 14px;
  font-size: .9rem;
}
.review-login-prompt a { font-weight: 700; color: var(--black); text-decoration: underline; text-underline-offset: 3px; }

/* How-to steps */
.howto-step { display: flex; gap: 16px; align-items: flex-start; padding: 18px 20px; background: var(--gray-100); border-radius: var(--r-lg); margin-bottom: 12px; }
.howto-num { width: 32px; height: 32px; background: var(--rose-dark); color: #fff; border-radius: 50%; display: grid; place-items: center; font-size: .78rem; font-weight: 700; flex-shrink: 0; }
.howto-title { font-weight: 700; font-size: .9rem; margin-bottom: 4px; }
.howto-body { font-size: .84rem; color: var(--text-muted); line-height: 1.55; }
.tip-box { padding: 16px 20px; background: var(--lime-pale, #f7fce7); border-radius: var(--r-md); border-left: 4px solid var(--lime); font-size: .9rem; line-height: 1.6; margin-top: 20px; }

/* Ingredient grid */
.ingredient-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; }
.ingredient-card { padding: 18px; background: var(--gray-100); border-radius: var(--r-md); border: 1.5px solid transparent; transition: border-color .2s; }
.ingredient-card:hover { border-color: var(--lime); }
.ingredient-name { font-weight: 700; font-size: .9rem; margin-bottom: 5px; }
.ingredient-benefit { font-size: .78rem; color: var(--text-muted); line-height: 1.5; }

/* Breadcrumb */
.breadcrumb { display: flex; gap: 8px; align-items: center; padding: 20px 0 0; font-size: .82rem; color: var(--text-muted); flex-wrap: wrap; }
.breadcrumb a { color: var(--text-muted); transition: color .2s; }
.breadcrumb a:hover { color: var(--black); }
.breadcrumb-sep { font-size: .7rem; }

/* Related */
.related-bundle-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }

@media (max-width: 900px) {
  .pdp-wrap { grid-template-columns: 1fr; gap: 28px; }
  .pdp-gallery { position: static; }
  .review-summary { grid-template-columns: 1fr; gap: 20px; }
  .pdp-tab { padding: 12px 14px; font-size: .82rem; }
  .review-form-wrap { padding: 24px 20px; }
  .review-form-row { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .pdp-thumbs { gap: 6px; }
}
</style>
@endsection

@section('content')

@php
  $productCms = data_get($siteContent, 'pages.product', []);
  $p = $product;
  $rating      = (float)($p['rating'] ?? 0);
  $reviewCount = (int)($p['review_count'] ?? 0);
  $price       = (float)($p['price'] ?? 0);
  $origPrice   = !empty($p['original_price']) ? (float)$p['original_price'] : null;
  $savePct     = $origPrice ? round((1 - $price / $origPrice) * 100) : 0;
  $skinTypes   = $p['skin_types'] ?? [];
  $concerns    = $p['concerns'] ?? $p['concern'] ?? [];
  $routineStep = $p['routine_step'] ?? $p['routineStep'] ?? 'Serum';
  $timeOfUse   = $p['time_of_use'] ?? $p['timeOfUse'] ?? 'AM/PM';
  $texture     = $p['texture'] ?? 'Gel';
  $sensitivity = $p['sensitivity'] ?? 'Moderate';
  $ingredients = $p['ingredients'] ?? [];
  $inStock     = $p['in_stock'] ?? $p['inStock'] ?? true;
  $badge       = $p['badge'] ?? null;
  $category    = $p['category'] ?? 'Skincare';
  $size        = $p['size'] ?? null;
  $isLoggedIn  = (bool)session('api_token');

  // Gallery images
  $images = $p['images'] ?? [];
  if (empty($images) && !empty($p['image'])) $images = [$p['image']];
  if (count($images) < 2 && !empty($images)) {
    $base = $images[0];
    $sep  = str_contains($base, '?') ? '&' : '?';
    $images = [
      $base,
      $base . $sep . 'crop=top',
      $base . $sep . 'crop=entropy',
      $base . $sep . 'crop=bottom',
    ];
  }

  $ingredientInfo  = $p['ingredient_info'] ?? [];
  $proTip          = $p['pro_tip'] ?? '';
  $firstIngredient = $ingredients[0] ?? 'active ingredients';

  $displayReviews = $reviews ?? [];

  // Compute rating distribution and aggregate stats from actual reviews
  $starCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
  foreach ($displayReviews as $r) {
    $s = (int)($r['rating'] ?? $r['star'] ?? 5);
    if ($s >= 1 && $s <= 5) $starCounts[$s]++;
  }
  $totalReviewsForBars = array_sum($starCounts) ?: 1;
  $ratingBars = array_map(fn($c) => (int)round($c / $totalReviewsForBars * 100), $starCounts);

  // Always use the actual loaded reviews as the source of truth for count
  $reviewCount = count($displayReviews);
  if ($rating == 0 && count($displayReviews) > 0) {
    $sumStars = array_sum(array_map(fn($r) => (int)($r['rating'] ?? $r['star'] ?? 5), $displayReviews));
    $rating   = round($sumStars / count($displayReviews), 1);
  }
@endphp

<div id="pdp-root">
  <div class="container">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
      <a href="{{ route('home') }}">{{ data_get($productCms, 'breadcrumb_home', 'Home') }}</a>
      <span class="breadcrumb-sep">›</span>
      <a href="{{ route('shop') }}">{{ data_get($productCms, 'breadcrumb_shop', 'Shop') }}</a>
      <span class="breadcrumb-sep">›</span>
      <a href="{{ route('shop', ['category' => strtolower($category)]) }}">{{ $category }}</a>
      <span class="breadcrumb-sep">›</span>
      <span style="color:var(--text-primary)">{{ $p['name'] }}</span>
    </div>

    <div class="pdp-wrap">

      {{-- ── Gallery ── --}}
      <div class="pdp-gallery">
        <div class="pdp-main-img">
          <img id="pdp-main-img" src="{{ $images[0] ?? '' }}" alt="{{ $p['name'] }}">
        </div>
        @if(count($images) > 1)
          <div class="pdp-thumbs">
            @foreach($images as $i => $imgUrl)
              <div class="pdp-thumb {{ $i === 0 ? 'active' : '' }}"
                   onclick="switchView('{{ $imgUrl }}', this)">
                <img src="{{ $imgUrl }}" alt="View {{ $i + 1 }}" loading="lazy">
              </div>
            @endforeach
          </div>
        @endif
      </div>

      {{-- ── Info ── --}}
      <div>
        @if($badge)
          @php $badgeClass = $badge === 'Sale' ? 'badge-red' : ($badge === 'New' ? 'badge-lime' : 'badge-dark'); @endphp
          <span class="badge {{ $badgeClass }}" style="display:inline-block;margin-bottom:14px">{{ $badge }}</span>
        @endif

        <div class="pdp-brand">{{ $p['brand'] }}</div>
        <h1 class="pdp-name">{{ $p['name'] }}</h1>

        <div class="pdp-stars-row">
          <span style="color:#F59E0B;font-size:1.1rem">
            {{ str_repeat('★', (int)round($rating)) }}{{ str_repeat('☆', 5 - (int)round($rating)) }}
          </span>
          <span style="font-weight:700;font-size:.95rem">{{ $rating }}</span>
          <a href="#tab-reviews" onclick="switchTab('reviews');return false">
            {{ number_format($reviewCount) }} {{ Str::plural('review', $reviewCount) }}
          </a>
        </div>

        <div class="pdp-price-row">
          <span class="pdp-price-current">₦{{ number_format($price) }}</span>
          @if($origPrice)
            <span class="pdp-price-original">₦{{ number_format($origPrice) }}</span>
            <span class="pdp-save-badge">Save {{ $savePct }}%</span>
          @endif
        </div>

        <div class="pdp-divider"></div>

        <div class="pdp-attrs">
          @if(count($skinTypes))
            <div class="pdp-attr">
              <span class="pdp-attr-label">Skin Type</span>
              <span>@foreach($skinTypes as $type)<span class="pdp-tag">{{ $type }}</span>@endforeach</span>
            </div>
          @endif
          @if(count($concerns))
            <div class="pdp-attr">
              <span class="pdp-attr-label">Concerns</span>
              <span>@foreach($concerns as $c)<span class="pdp-tag">{{ $c }}</span>@endforeach</span>
            </div>
          @endif
          <div class="pdp-attr">
            <span class="pdp-attr-label">Routine Step</span>
            <span><span class="pdp-tag">{{ $routineStep }}</span></span>
          </div>
          <div class="pdp-attr">
            <span class="pdp-attr-label">When to Use</span>
            <span><span class="pdp-tag">{{ $timeOfUse }}</span></span>
          </div>
          <div class="pdp-attr">
            <span class="pdp-attr-label">Texture</span>
            <span><span class="pdp-tag">{{ $texture }}</span></span>
          </div>
          <div class="pdp-attr">
            <span class="pdp-attr-label">Sensitivity</span>
            <span><span class="pdp-tag">{{ $sensitivity }}</span></span>
          </div>
          @if($size)
            <div class="pdp-attr">
              <span class="pdp-attr-label">Size</span>
              <span><span class="pdp-tag">{{ $size }}</span></span>
            </div>
          @endif
        </div>

        {{-- Quantity selector --}}
        <div class="pdp-qty-row">
          <span class="pdp-qty-label">Qty</span>
          <div class="pdp-qty-ctrl">
            <button class="pdp-qty-btn" onclick="changeQty(-1)" aria-label="Decrease">−</button>
            <span class="pdp-qty-val" id="qty-val">1</span>
            <button class="pdp-qty-btn" onclick="changeQty(1)" aria-label="Increase">+</button>
          </div>
        </div>

        <div class="pdp-actions">
          @if($inStock)
            <button class="pdp-add-btn" id="add-to-cart-btn"
                    onclick="handleAddToCart({{ $p['id'] }})">
              Add to Cart → ₦{{ number_format($price) }}
            </button>
          @else
            <button class="pdp-add-btn" disabled>Out of Stock</button>
          @endif
          <button class="pdp-wish-btn" onclick="toggleSave({{ $p['id'] }},this)" title="Save to wishlist">♡</button>
        </div>

        <div class="pdp-trust">
          <span class="pdp-trust-item">
            <span class="pdp-stock-dot {{ $inStock ? 'in' : 'out' }}"></span>
            {{ $inStock ? 'In Stock' : 'Out of Stock' }}
          </span>
          <span class="pdp-trust-item">{{ data_get($productCms, 'trust_shipping', '🚚 Free shipping over ₦50k') }}</span>
          <span class="pdp-trust-item">{{ data_get($productCms, 'trust_authentic', '✅ 100% Authentic') }}</span>
        </div>
      </div>
    </div>

    {{-- ── Tabs ── --}}
    <div class="pdp-tabs">
      <div class="pdp-tab active"  onclick="switchTab('about')">{{ data_get($productCms, 'tab_about', 'About') }}</div>
      <div class="pdp-tab"         onclick="switchTab('ingredients')">{{ data_get($productCms, 'tab_ingredients', 'Ingredients') }}</div>
      <div class="pdp-tab"         onclick="switchTab('howto')">{{ data_get($productCms, 'tab_howto', 'How to Use') }}</div>
      <div class="pdp-tab"         onclick="switchTab('reviews')">
        Reviews ({{ number_format($reviewCount) }})
      </div>
    </div>

    {{-- About --}}
    <div id="tab-about" class="pdp-panel active" style="max-width:760px">
      @if(!empty($p['description']))
        <p style="font-size:1.05rem;font-weight:500;line-height:1.8;margin-bottom:18px;color:var(--text-primary)">
          {{ $p['description'] }}
        </p>
      @endif
      @if(count($skinTypes) || count($concerns))
        <p style="font-size:.95rem;line-height:1.8;color:var(--text-secondary);margin-bottom:16px">
          Formulated for {{ implode(' and ', $skinTypes) }} skin types, this {{ strtolower($category) }}
          directly targets {{ strtolower(implode(', ', $concerns)) }}.
          {{ in_array($sensitivity, ['Low', 'Sensitive']) ? 'Thoroughly tested for sensitive skin — free from common irritants and allergens.' : 'Contains clinically-tested active concentrations for visible, measurable results.' }}
        </p>
      @endif
      <p style="font-size:.95rem;line-height:1.8;color:var(--text-secondary);margin-bottom:16px">
        Best used
        @if($timeOfUse === 'AM/PM') morning and evening
        @elseif($timeOfUse === 'AM') in the morning
        @else at night
        @endif
        as your {{ strtolower($routineStep) }} step.
        The {{ strtolower($texture) }} formula absorbs
        {{ in_array('Oily', $skinTypes) ? 'without any greasy residue' : 'beautifully into the skin to deliver lasting results' }}.
      </p>
      <p style="font-size:.95rem;line-height:1.8;color:var(--text-secondary)">
        Sourced directly from {{ $p['brand'] }} — one of the most trusted names in Korean skincare — and verified authentic before every shipment.
      </p>
    </div>

    {{-- Ingredients --}}
    <div id="tab-ingredients" class="pdp-panel">
      <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:20px">{{ data_get($productCms, 'ingredients_subtitle', 'Key active ingredients and why they work for your skin:') }}</p>
      @if(count($ingredients))
        <div class="ingredient-grid">
          @foreach($ingredients as $ing)
            <div class="ingredient-card">
              <div class="ingredient-name">{{ $ing }}</div>
              <div class="ingredient-benefit">{{ $ingredientInfo[$ing] ?? 'Active skincare compound with proven efficacy.' }}</div>
            </div>
          @endforeach
        </div>
      @else
        <p style="color:var(--text-muted)">Ingredient details not available for this product.</p>
      @endif
    </div>

    {{-- How to Use --}}
    <div id="tab-howto" class="pdp-panel" style="max-width:640px">
      @php
        $howtoSteps = [
          [
            'title' => 'Start with clean skin',
            'body'  => str_contains($timeOfUse, 'AM')
              ? 'Wash your face with a gentle cleanser suitable for your skin type.'
              : 'Double-cleanse to fully remove sunscreen, makeup and daily grime.',
          ],
          [
            'title' => 'Apply the ' . strtolower($routineStep),
            'body'  => in_array($texture, ['Gel','Water'])
              ? 'Dispense a small amount onto fingertips. Pat gently into skin — never rub.'
              : 'Warm a pea-sized amount between fingertips and press into skin with gentle upward strokes.',
          ],
          [
            'title' => 'Layer in the right order',
            'body'  => 'In K-beauty you layer from thinnest to thickest. This ' . strtolower($routineStep) . ' goes ' . (
              $routineStep === 'Cleanser'     ? 'first' :
              ($routineStep === 'Toner'       ? 'after cleansing' :
              ($routineStep === 'Essence'     ? 'after toning' :
              ($routineStep === 'Serum'       ? 'before your moisturizer' :
              ($routineStep === 'Moisturizer' ? 'near the end of your routine' :
              ($routineStep === 'Sunscreen'   ? 'as the very last AM step' : 'at the correct step in your routine')))))
            ) . '.',
          ],
        ];
        if (str_contains($timeOfUse, 'AM')) {
          $howtoSteps[] = [
            'title' => 'Always finish with SPF',
            'body'  => 'Especially when using ' . $firstIngredient . ' — actives increase sun sensitivity. SPF 50 is the Kominhoo standard.',
          ];
        }
      @endphp

      @foreach($howtoSteps as $i => $step)
        <div class="howto-step">
          <div class="howto-num">{{ $i + 1 }}</div>
          <div>
            <div class="howto-title">{{ $step['title'] }}</div>
            <div class="howto-body">{{ $step['body'] }}</div>
          </div>
        </div>
      @endforeach

      @if($proTip)
        <div class="tip-box"><strong>{{ data_get($productCms, 'pro_tip_label', '💡 Pro Tip:') }}</strong> {{ $proTip }}</div>
      @endif
    </div>

    {{-- Reviews --}}
    <div id="tab-reviews" class="pdp-panel">

      {{-- Flash messages --}}
      @if(session('review_success'))
        <div class="review-alert success">
          <span style="font-size:1.1rem">🌿</span>
          <span>{{ session('review_success') }}</span>
        </div>
      @endif
      @if($errors->has('body'))
        <div class="review-alert error">
          <span style="font-size:1.1rem">⚠️</span>
          <span>{{ $errors->first('body') }}</span>
        </div>
      @endif

      {{-- Rating summary --}}
      <div class="review-summary">
        <div style="text-align:center">
          <div class="review-big-num">{{ $rating }}</div>
          <div class="review-stars-big">
            {{ str_repeat('★', (int)round($rating)) }}{{ str_repeat('☆', 5 - (int)round($rating)) }}
          </div>
          <div style="font-size:.8rem;color:var(--text-muted)">{{ number_format($reviewCount) }} {{ Str::plural('review', $reviewCount) }}</div>
        </div>
        <div>
          @foreach($ratingBars as $star => $pct)
            <div class="review-bar-row">
              <span style="min-width:12px;font-weight:700;font-size:.82rem">{{ $star }}</span>
              <span style="color:#F59E0B;font-size:.78rem">★</span>
              <div class="review-bar-track">
                <div class="review-bar-fill" style="width:{{ $pct }}%"></div>
              </div>
              <span style="min-width:30px;color:var(--text-muted);font-size:.78rem">{{ $pct }}%</span>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Review cards --}}
      @forelse($displayReviews as $review)
        <div class="review-card">
          <div class="review-header">
            <div>
              <div class="review-name">{{ $review['reviewer_name'] ?? $review['name'] ?? 'Anonymous' }}</div>
              <div class="review-stars">
                @php $s = (int)($review['rating'] ?? $review['star'] ?? 5); @endphp
                {{ str_repeat('★', $s) }}{{ str_repeat('☆', 5 - $s) }}
              </div>
            </div>
            <div class="review-date">
              {{ isset($review['created_at']) ? \Carbon\Carbon::parse($review['created_at'])->format('d M Y') : ($review['date'] ?? '') }}
            </div>
          </div>
          @if(!empty($review['title']))
            <div style="font-weight:600;font-size:.9rem;margin-bottom:6px">{{ $review['title'] }}</div>
          @endif
          <div class="review-body">{{ $review['body'] }}</div>
          @if(!empty($review['verified']))
            <div class="review-verified">✓ Verified Purchase</div>
          @endif
        </div>
      @empty
        <div style="text-align:center;padding:40px 24px;background:var(--gray-100);border-radius:var(--r-xl);margin-bottom:24px">
          <div style="font-size:2.4rem;margin-bottom:12px">{{ data_get($productCms, 'reviews_empty_icon', '✍🏾') }}</div>
          <div style="font-weight:700;font-size:1rem;margin-bottom:6px">{{ data_get($productCms, 'reviews_empty_title', 'No reviews yet') }}</div>
          <div style="font-size:.88rem;color:var(--text-muted)">{{ data_get($productCms, 'reviews_empty_body', 'Be the first to share your experience with this product.') }}</div>
        </div>
      @endforelse

      {{-- ── Write a review ── --}}
      <div class="review-form-wrap" id="write-review">
        <div class="review-form-title">{{ data_get($productCms, 'review_form_title', 'Share Your Experience') }}</div>
        <div class="review-form-sub">{{ data_get($productCms, 'review_form_subtitle', 'Tried this product? Your honest review helps other Nigerian shoppers make confident choices.') }}</div>

        @if($isLoggedIn)
          <form method="POST" action="{{ route('review.submit', $p['id']) }}"
                class="review-form" id="review-form" onsubmit="return validateReviewForm()">
            @csrf

            {{-- Star picker --}}
            <div class="star-picker-wrap">
              <span class="star-picker-label">Your Rating *</span>
              <div class="star-picks" id="star-picks">
                @for($i = 1; $i <= 5; $i++)
                  <span class="star-pick" data-val="{{ $i }}">☆</span>
                @endfor
              </div>
              <input type="hidden" name="rating" id="rating-val" value="">
              <div class="star-error" id="star-error">Please select a star rating.</div>
            </div>

            <div class="review-form-row">
              <div class="review-form-field">
                <label for="reviewer_name">Your Name *</label>
                <input type="text" id="reviewer_name" name="reviewer_name"
                       placeholder="e.g. Adaeze O."
                       value="{{ old('reviewer_name', session('user.name') ?? '') }}"
                       required maxlength="100">
              </div>
              <div class="review-form-field">
                <label for="skin_type">Your Skin Type</label>
                <select id="skin_type" name="skin_type">
                  <option value="">Select (optional)</option>
                  @foreach(['Oily','Dry','Combination','Normal','Sensitive','Acne-Prone'] as $st)
                    <option value="{{ $st }}" {{ old('skin_type') === $st ? 'selected' : '' }}>{{ $st }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="review-form-field">
              <label for="title">Review Title</label>
              <input type="text" id="title" name="title"
                     placeholder="Summarise your experience (optional)"
                     value="{{ old('title') }}" maxlength="200">
            </div>

            <div class="review-form-field">
              <label for="body">Your Review *</label>
              <textarea id="body" name="body" required minlength="10"
                        placeholder="What did you love? How did your skin respond? Any tips for others?">{{ old('body') }}</textarea>
            </div>

            <button type="submit" class="review-submit-btn">{{ data_get($productCms, 'review_form_submit', 'Submit Review →') }}</button>
          </form>

        @else
          <div class="review-login-prompt">
            <span style="font-size:1.4rem">{{ data_get($productCms, 'reviews_empty_icon', '✍🏾') }}</span>
            <span>
              <a href="{{ route('login') }}">Sign in</a> or
              <a href="{{ route('register') }}">create a free account</a>
              to leave a review and earn <strong>{{ data_get($productCms, 'review_login_points', '50 loyalty points') }}</strong>.
            </span>
          </div>
        @endif
      </div>
    </div>

    {{-- ── Similar Products ── --}}
    @if(!empty($relatedProducts) && count($relatedProducts))
      <div style="padding:56px 0 72px;border-top:1px solid var(--border);margin-top:48px">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:32px">
          <div>
            <div class="section-eyebrow" style="margin-bottom:8px">
              <span class="dot"></span> Similar Products
            </div>
            <h2 class="display-sm" style="margin:0">
              More in <em class="serif" style="font-weight:400">{{ $category }}</em>
            </h2>
          </div>
          <a href="{{ route('shop', ['category' => strtolower($category)]) }}"
             class="btn btn-outline btn-sm" style="flex-shrink:0">
            View All {{ $category }} →
          </a>
        </div>

        <div class="product-grid">
          @foreach($relatedProducts as $rp)
            @php
              $rpPrice    = (float)($rp['price'] ?? 0);
              $rpOrig     = !empty($rp['original_price']) ? (float)$rp['original_price'] : null;
              $rpBadge    = $rp['badge'] ?? null;
              $rpImg      = $rp['images'][0] ?? ($rp['image'] ?? '');
            @endphp
            <a href="{{ route('product', $rp['id']) }}" class="product-card reveal">

              {{-- Badge --}}
              @if($rpBadge)
                @php $rpBadgeClass = $rpBadge === 'Sale' ? 'badge-red' : ($rpBadge === 'New' ? 'badge-lime' : 'badge-dark'); @endphp
                <div class="product-badges">
                  <span class="badge {{ $rpBadgeClass }}">{{ $rpBadge }}</span>
                </div>
              @endif

              <div class="product-img-wrap">
                <img src="{{ $rpImg }}" alt="{{ $rp['name'] }}" loading="lazy">
              </div>

              <div class="product-info">
                <div class="product-brand">{{ $rp['brand'] }}</div>
                <div class="product-name">{{ $rp['name'] }}</div>
                <div class="product-price" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                  <span class="price-current">₦{{ number_format($rpPrice) }}</span>
                  @if($rpOrig)
                    <span class="price-original">₦{{ number_format($rpOrig) }}</span>
                  @endif
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</div>

@endsection

@section('scripts')
<script>
// ── Tab switching ────────────────────────────────────────────────────────────
function switchTab(id) {
  document.querySelectorAll('.pdp-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.pdp-panel').forEach(p => p.classList.remove('active'));
  const panel = document.getElementById('tab-' + id);
  if (panel) panel.classList.add('active');
  const tabMap = { about: 0, ingredients: 1, howto: 2, reviews: 3 };
  const tabs   = document.querySelectorAll('.pdp-tab');
  if (tabMap[id] !== undefined && tabs[tabMap[id]]) tabs[tabMap[id]].classList.add('active');
}

// Auto-open reviews tab after submission or if hash present
document.addEventListener('DOMContentLoaded', () => {
  @if(session('review_success') || $errors->has('body'))
    switchTab('reviews');
    document.getElementById('write-review')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  @elseif(request()->is('*'))
    if (window.location.hash === '#tab-reviews') switchTab('reviews');
  @endif
});

// ── Gallery ──────────────────────────────────────────────────────────────────
function switchView(src, el) {
  document.getElementById('pdp-main-img').src = src;
  document.querySelectorAll('.pdp-thumb').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
}

// ── Quantity selector ────────────────────────────────────────────────────────
let pdpQty = 1;
function changeQty(delta) {
  pdpQty = Math.max(1, pdpQty + delta);
  document.getElementById('qty-val').textContent = pdpQty;
}

// ── Add to cart with quantity ────────────────────────────────────────────────
function handleAddToCart(productId) {
  for (let i = 0; i < pdpQty; i++) {
    if (typeof addToCart === 'function') addToCart(productId);
  }
  const btn = document.getElementById('add-to-cart-btn');
  if (btn) {
    const orig = btn.textContent;
    btn.textContent = '✓ Added!';
    btn.style.background = '#22c55e';
    setTimeout(() => { btn.textContent = orig; btn.style.background = ''; }, 1800);
  }
}

// ── Star picker ───────────────────────────────────────────────────────────────
(function () {
  const picks = document.querySelectorAll('.star-pick');
  const input = document.getElementById('rating-val');
  if (!picks.length) return;

  function renderStars(upTo) {
    picks.forEach((s, i) => {
      s.textContent = i < upTo ? '★' : '☆';
      s.classList.toggle('lit', i < upTo);
    });
  }

  picks.forEach(s => {
    s.addEventListener('click', () => {
      input.value = s.dataset.val;
      renderStars(+s.dataset.val);
      document.getElementById('star-error').style.display = 'none';
    });
    s.addEventListener('mouseenter', () => renderStars(+s.dataset.val));
  });

  document.getElementById('star-picks')?.addEventListener('mouseleave', () => {
    renderStars(+(input.value || 0));
  });
})();

// ── Review form validation ────────────────────────────────────────────────────
function validateReviewForm() {
  const rating = document.getElementById('rating-val')?.value;
  if (!rating) {
    const err = document.getElementById('star-error');
    if (err) { err.style.display = 'block'; err.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
    return false;
  }
  return true;
}
</script>
@endsection
