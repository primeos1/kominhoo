@extends('layouts.app')
@section('title', 'Your Skin Results — Kominhoo Beauty')

@section('head')
<style>
/* —— Confetti —— */
.results-hero { background: var(--rose-dark); padding: 60px 0; }
.confetti { position: fixed; pointer-events: none; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; }
.confetti-piece { position: absolute; width: 10px; height: 10px; border-radius: 2px; animation: fall linear forwards; }
@keyframes fall { from { transform: translateY(-20px) rotate(0deg); opacity: 1; } to { transform: translateY(100vh) rotate(720deg); opacity: 0; } }

/* —— Disclaimer —— */
.disclaimer { background: var(--lime-pale); border: 1.5px solid var(--lime-dark); border-radius: var(--r-md); padding: 14px 18px; font-size: .82rem; color: var(--gray-700); display: flex; gap: 10px; align-items: flex-start; }

/* —— Path Cards Grid — always 3 columns —— */
.paths-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 0; align-items: start; }

.path-card {
  background: #fff;
  border: 2px solid var(--border);
  border-radius: var(--r-xl);
  padding: 28px 24px;
  cursor: pointer;
  transition: var(--t-base);
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.path-card:hover { border-color: var(--gray-400); box-shadow: var(--s-lg); transform: translateY(-4px); }
.path-card.active { border-color: var(--rose); box-shadow: var(--s-lg); transform: translateY(-4px); }

.path-featured { border-color: var(--lime-dark); background: var(--lime-pale); }
.path-featured:hover, .path-featured.active { border-color: var(--rose); }

.path-badge-recommended {
  position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
  background: var(--rose-dark); color: var(--lime); font-size: .7rem; font-weight: 700;
  letter-spacing: .1em; text-transform: uppercase; padding: 4px 18px;
  border-radius: var(--r-pill); white-space: nowrap;
}

/* —— Source Badges —— */
.path-source {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: .72rem; font-weight: 700; letter-spacing: .04em;
  padding: 5px 12px; border-radius: var(--r-pill); width: fit-content;
}
.ai-source     { background: rgba(59,130,246,.1); color: #1D4ED8; border: 1px solid rgba(59,130,246,.25); }
.expert-source { background: rgba(168,194,24,.15); color: #4a5e00; border: 1px solid rgba(168,194,24,.35); }
.shop-source   { background: rgba(245,158,11,.1); color: #92400e; border: 1px solid rgba(245,158,11,.25); }

/* —— Card Inner —— */
.path-card-header { display: flex; align-items: center; gap: 14px; }
.path-icon-wrap { font-size: 1.9rem; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; background: var(--gray-100); border-radius: var(--r-md); flex-shrink: 0; }
.path-featured .path-icon-wrap { background: rgba(168,194,24,.2); }
.path-card-title { font-size: 1.1rem; font-weight: 700; line-height: 1.2; }
.path-card-sub   { font-size: .78rem; color: var(--text-muted); margin-top: 3px; }
.path-card-desc  { font-size: .87rem; color: var(--text-secondary); line-height: 1.65; flex: 1; }

.path-highlights { display: flex; flex-direction: column; gap: 8px; }
.path-highlight  { font-size: .82rem; color: var(--text-secondary); font-weight: 500; display: flex; align-items: center; gap: 7px; }

.path-price {
  font-size: 1.05rem; font-weight: 700; color: var(--text-primary);
  padding-top: 12px; border-top: 1.5px solid var(--border);
}

.path-card-cta {
  display: flex; align-items: center; justify-content: space-between;
  font-size: .85rem; font-weight: 700; color: var(--text-muted);
  background: var(--gray-100); border-radius: var(--r-md);
  padding: 10px 16px; transition: var(--t-fast);
}
.path-featured .path-card-cta { background: rgba(168,194,24,.2); color: #4a5e00; }
.path-card:hover .path-card-cta, .path-card.active .path-card-cta { background: var(--rose-dark); color: var(--lime); }

.path-card-cta-chevron { font-size: 1rem; transition: transform var(--t-fast); }
.path-card.active .path-card-cta-chevron { transform: rotate(180deg); }

/* —— Inline Dropdown Panels —— */
#path-dropdown-area { margin-top: 20px; margin-bottom: 32px; }

.path-dropdown-panel {
  display: none;
  border: 2px solid var(--border);
  border-radius: var(--r-xl);
  padding: 36px 32px;
  background: #fff;
  position: relative;
  animation: panelIn .35s cubic-bezier(.4,0,.2,1);
}
.path-dropdown-panel.open { display: block; }

/* Caret pointing upward toward active card */
.path-dropdown-panel::before {
  content: '';
  position: absolute;
  top: -10px;
  left: var(--caret-pos, 50%);
  transform: translateX(-50%) rotate(45deg);
  width: 18px; height: 18px;
  background: #fff;
  border-top: 2px solid var(--border);
  border-left: 2px solid var(--border);
  border-radius: 3px 0 0 0;
}

/* Per-panel border accents */
#panel-routine.path-dropdown-panel   { border-color: rgba(59,130,246,.4); }
#panel-routine.path-dropdown-panel::before { border-top-color: rgba(59,130,246,.4); border-left-color: rgba(59,130,246,.4); }
#panel-subscription.path-dropdown-panel   { border-color: var(--lime-dark); background: var(--lime-pale); }
#panel-subscription.path-dropdown-panel::before { border-top-color: var(--lime-dark); border-left-color: var(--lime-dark); background: var(--lime-pale); }
#panel-products.path-dropdown-panel   { border-color: rgba(245,158,11,.45); }
#panel-products.path-dropdown-panel::before { border-top-color: rgba(245,158,11,.45); border-left-color: rgba(245,158,11,.45); }

@keyframes panelIn { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }

.panel-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
.panel-close {
  display: flex; align-items: center; gap: 6px;
  padding: 9px 20px; border: 1.5px solid var(--border); border-radius: var(--r-pill);
  font-size: .82rem; font-weight: 700; cursor: pointer; transition: var(--t-fast);
  background: #fff; color: var(--text-primary);
}
.panel-close:hover { background: var(--rose-dark); color: #fff; border-color: var(--rose-dark); }

/* —— Comparison Table —— */
.paths-compare {
  border: 1.5px solid var(--border); border-radius: var(--r-lg);
  overflow: hidden; background: #fff; margin-top: 8px;
}
.compare-row { display: grid; grid-template-columns: 150px 1fr 1fr 1fr; border-bottom: 1px solid var(--border); }
.compare-row:last-child { border-bottom: none; }
.compare-row > div { padding: 13px 20px; font-size: .84rem; color: var(--text-secondary); border-right: 1px solid var(--border); }
.compare-row > div:last-child { border-right: none; }
.compare-label { font-weight: 700; color: var(--text-primary) !important; background: var(--gray-100); }
.compare-head  { font-weight: 700 !important; font-size: .78rem !important; text-transform: uppercase; letter-spacing: .07em; background: var(--gray-100); color: var(--text-primary) !important; }
.compare-head.featured-head { background: var(--lime-pale) !important; color: #4a5e00 !important; }
.compare-row .featured-col { background: var(--lime-pale); font-weight: 600 !important; color: var(--text-primary) !important; }

/* —— Routine Steps —— */
.routine-steps { display: flex; flex-direction: column; gap: 10px; }
.routine-step { display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: #fff; border: 1.5px solid var(--border); border-radius: var(--r-md); transition: var(--t-fast); }
.routine-step:hover { border-color: var(--gray-400); box-shadow: var(--s-sm); }
.step-num { width: 32px; height: 32px; border-radius: 50%; background: var(--gray-200); color: var(--text-primary); font-size: .82rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.step-name { font-size: .82rem; font-weight: 700; margin-bottom: 2px; }
.step-product { font-size: .8rem; color: var(--text-muted); }
.step-action { flex-shrink: 0; }

/* —— Subscription Tiers —— */
.sub-tiers { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.sub-tier { border: 2px solid var(--border); border-radius: var(--r-xl); padding: 28px 24px; display: flex; flex-direction: column; gap: 14px; background: #fff; }
.sub-tier.popular { border-color: var(--lime-dark); background: rgba(168,194,24,.08); }
.sub-tier-badge { display: inline-block; background: var(--rose-dark); color: var(--lime); font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: 4px 14px; border-radius: var(--r-pill); width: fit-content; }
.sub-tier-name { font-size: 1.3rem; font-weight: 700; }
.sub-tier-desc { font-size: .85rem; color: var(--text-secondary); line-height: 1.5; }
.sub-tier-price { font-size: 2.1rem; font-weight: 700; line-height: 1; margin: 4px 0; }
.sub-tier-price span { font-size: .85rem; font-weight: 400; color: var(--text-muted); }
.sub-tier-divider { border: none; border-top: 1.5px solid var(--border); }
.sub-tier-features { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.sub-tier-features li { font-size: .88rem; color: var(--text-secondary); display: flex; align-items: flex-start; gap: 8px; }
.sub-tier-features li::before { content: "✓"; color: var(--lime-dark); font-weight: 700; flex-shrink: 0; }

/* —— Responsive —— */
@media (max-width: 1024px) {
  .paths-grid { gap: 16px; }
  .sub-tiers { grid-template-columns: 1fr; }
  .path-card { padding: 22px 18px; }
}

/* -- Tablet: tighten cards, keep 3 cols -- */
@media (max-width: 768px) {
  .paths-compare { display: none; }
  .results-hero { padding: 40px 0; }
  .skin-profile-card { padding: 24px; }

  /* 3 cols stay — strip non-essential card content */
  .paths-grid { gap: 10px; }
  .path-card { padding: 16px 10px; gap: 10px; }
  .path-source { display: none; }
  .path-card-desc { display: none; }
  .path-highlights { display: none; }
  .path-card-sub { display: none; }
  .path-card-title { font-size: .85rem; line-height: 1.2; }
  .path-icon-wrap { width: 40px; height: 40px; font-size: 1.4rem; }
  .path-price { font-size: .8rem; padding-top: 8px; }
  .path-card-cta { padding: 8px 10px; font-size: .74rem; }

  /* Dropdown panel: full-width readable content */
  .path-dropdown-panel { padding: 24px 16px; }
  .panel-header { flex-direction: column; align-items: flex-start; margin-bottom: 20px; }
  .routine-cols { grid-template-columns: 1fr !important; gap: 24px !important; }
  .sub-tiers { grid-template-columns: 1fr !important; }
}

/* -- Mobile: very compact cards, 3 cols -- */
@media (max-width: 480px) {
  .paths-grid { gap: 6px; }
  .path-card { padding: 12px 8px; gap: 8px; }
  .path-card-title { font-size: .76rem; }
  .path-card-header { gap: 6px; }
  .path-icon-wrap { width: 30px; height: 30px; font-size: 1.1rem; }
  .path-price { font-size: .72rem; padding-top: 6px; }
  .path-card-cta { padding: 6px 8px; font-size: .68rem; }
  .path-card-cta-chevron { display: none; }
  .path-badge-recommended { font-size: .55rem; padding: 3px 8px; top: -11px; }
  .path-dropdown-panel { padding: 20px 14px; }
  .panel-header h3 { font-size: 1.2rem !important; }
}
</style>
@endsection

@section('content')

@php
  $skinType = $skin_type ?? 'Normal';

  $skinLabels = [
    'Oily'        => 'Oily & Acne-Prone',
    'Dry'         => 'Dry & Dehydrated',
    'Combination' => 'Combination & Acne-Prone',
    'Normal'      => 'Balanced & Healthy',
    'Sensitive'   => 'Sensitive & Reactive',
  ];
  $skinLabel = $skinLabels[$skinType] ?? $skinType;

  // ── Parse concerns from answers ──────────────────────────────────────────
  $rawConcerns = $answers['concerns'] ?? [];
  if (is_string($rawConcerns)) {
    $rawConcerns = array_filter(array_map('trim', explode(',', $rawConcerns)));
  }
  $concerns = array_values((array) $rawConcerns);

  // ── Description — enriched with actual quiz answers ──────────────────────
  $envLabel = match($answers['environment'] ?? '') {
    'aircon' => 'AC environments stripping your barrier',
    'humid'  => 'Lagos / humid-climate heat',
    default  => 'Nigerian heat and climate changes',
  };
  $descriptions = [
    'Oily'        => "Your skin produces excess sebum across the T-zone and cheeks, leading to persistent shine and breakouts. {$envLabel} amplifies oil production — you need lightweight, mattifying products with pore-clearing actives.",
    'Dry'         => "Your skin lacks sufficient moisture and may feel tight, flaky, or dull after cleansing. Combined with {$envLabel}, you need rich hydration layering and gentle, non-stripping formulas.",
    'Combination' => "Your T-zone produces excess oil while your cheeks tend toward dryness. You need products that balance sebum without stripping moisture. {$envLabel} affects your pore visibility.",
    'Normal'      => "Your skin is well-balanced. Focus on maintaining your barrier, consistent SPF under Nigeria's intense UV, and antioxidant-rich products to preserve what you've got.",
    'Sensitive'   => "Your skin reacts quickly to new products and environmental triggers. Fragrance-free, minimal-ingredient formulas are essential. {$envLabel} can flare reactivity.",
  ];

  // ── Badges — base per skin type, augmented by selected concerns ──────────
  $baseBadges = [
    'Oily'        => [['Oily All Over','amber'],['Large Pores','blue'],['Humid Climate','lime']],
    'Dry'         => [['Dehydrated','blue'],['Tight Skin','red'],['Flaky','amber']],
    'Combination' => [['Oily T-Zone','amber'],['Dehydrated','blue'],['Humid Climate','lime']],
    'Normal'      => [['Balanced','lime'],['Healthy Barrier','blue']],
    'Sensitive'   => [['Reactive','red'],['Fragrance-Free','blue'],['Gentle Routine','lime']],
  ];
  $concernBadgeMap = [
    'acne'        => ['Acne-Prone','red'],
    'dark_spots'  => ['Hyperpigmentation','amber'],
    'dull'        => ['Dull Skin','blue'],
    'texture'     => ['Uneven Texture','amber'],
    'fine_lines'  => ['Anti-Ageing','lime'],
    'sensitive'   => ['Sensitive','red'],
    'dehydration' => ['Dehydrated','blue'],
    'large_pores' => ['Large Pores','blue'],
  ];
  $skinBadgesOut = $baseBadges[$skinType] ?? $baseBadges['Normal'];
  foreach ($concerns as $c) {
    if (isset($concernBadgeMap[$c])) {
      // avoid duplicate badge labels
      $exists = collect($skinBadgesOut)->contains(fn($b) => $b[0] === $concernBadgeMap[$c][0]);
      if (!$exists) $skinBadgesOut[] = $concernBadgeMap[$c];
    }
  }
  $skinBadgesOut = array_slice($skinBadgesOut, 0, 5); // cap at 5

  // ── Scores — use backend-computed values when available; ──────────────────
  //    otherwise fall back to enriched static defaults
  $colorMap = ['red'=>'var(--red)','lime'=>'var(--lime-dark)','blue'=>'#3B82F6','amber'=>'#F59E0B'];
  $bgMap    = ['red'=>'rgba(137,57,65,.2)','lime'=>'rgba(212,217,148,.2)','blue'=>'rgba(59,130,246,.2)','amber'=>'rgba(245,158,11,.2)'];
  $textMap  = ['red'=>'#ff6b6b','lime'=>'var(--lime)','blue'=>'#93C5FD','amber'=>'#FCD34D'];

  $metricColorFn = function(string $metric, int $val): string {
    // High = bad for risk/sensitivity/oil; high = good for hydration/barrier
    if (in_array($metric, ['Acne Risk', 'Sensitivity', 'Oil Level'])) {
      return $val >= 7 ? 'red' : ($val >= 5 ? 'amber' : 'lime');
    }
    return $val >= 7 ? 'lime' : ($val >= 5 ? 'blue' : 'red');
  };

  if (!empty($skin_scores) && is_array($skin_scores)) {
    $skinScores = [];
    foreach ($skin_scores as $metric => $val) {
      $skinScores[$metric] = [(int) $val, $metricColorFn($metric, (int) $val)];
    }
  } else {
    // Static fallback (no backend scores in session)
    $staticScores = [
      'Oily'        => ['Hydration'=>[4,'red'],'Acne Risk'=>[7,'red'],'Sensitivity'=>[4,'blue'],'Oil Level'=>[8,'amber'],'Barrier Health'=>[4,'red']],
      'Dry'         => ['Hydration'=>[3,'red'],'Acne Risk'=>[3,'lime'],'Sensitivity'=>[6,'blue'],'Oil Level'=>[2,'lime'],'Barrier Health'=>[3,'red']],
      'Combination' => ['Hydration'=>[5,'blue'],'Acne Risk'=>[6,'red'],'Sensitivity'=>[5,'blue'],'Oil Level'=>[6,'amber'],'Barrier Health'=>[5,'blue']],
      'Normal'      => ['Hydration'=>[7,'lime'],'Acne Risk'=>[2,'lime'],'Sensitivity'=>[3,'lime'],'Oil Level'=>[4,'lime'],'Barrier Health'=>[8,'lime']],
      'Sensitive'   => ['Hydration'=>[5,'blue'],'Acne Risk'=>[4,'amber'],'Sensitivity'=>[8,'red'],'Oil Level'=>[4,'lime'],'Barrier Health'=>[4,'red']],
    ];
    $skinScores = $staticScores[$skinType] ?? $staticScores['Normal'];
  }

  // ── Ingredients — base list enriched by actual concerns ──────────────────
  $baseIngredients = [
    'Oily'        => ['Niacinamide','Salicylic Acid','BHA','Clay','Tea Tree'],
    'Dry'         => ['Hyaluronic Acid','Ceramides','Squalane','Shea Butter','Panthenol'],
    'Combination' => ['Niacinamide','Salicylic Acid','Hyaluronic Acid','Centella Asiatica','BHA'],
    'Normal'      => ['Vitamin C','Retinol','Hyaluronic Acid','SPF 50+','Niacinamide'],
    'Sensitive'   => ['Centella Asiatica','Niacinamide','Ceramides','Aloe Vera','Panthenol'],
  ];
  $concernIngredMap = [
    'dark_spots'  => 'Vitamin C',
    'fine_lines'  => 'Retinol',
    'dehydration' => 'Hyaluronic Acid',
    'texture'     => 'AHA / Glycolic Acid',
    'sensitive'   => 'Centella Asiatica',
    'dull'        => 'Vitamin C',
  ];
  $skinIngred = $baseIngredients[$skinType] ?? $baseIngredients['Normal'];
  foreach ($concerns as $c) {
    if (isset($concernIngredMap[$c]) && !in_array($concernIngredMap[$c], $skinIngred)) {
      $skinIngred[] = $concernIngredMap[$c];
    }
  }
  $skinIngred = array_slice($skinIngred, 0, 7);

  $skinIcon = ['Oily'=>'💧','Dry'=>'🌵','Combination'=>'☯️','Normal'=>'✨','Sensitive'=>'🌸'];
  $skinDesc = $descriptions[$skinType] ?? $descriptions['Normal'];
@endphp

<!-- Hero / Results Header -->
<div class="results-hero">
  <div class="container">
    <div style="text-align:center;margin-bottom:48px">
      <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(212,217,148,.15);border:1px solid rgba(212,217,148,.3);border-radius:var(--r-pill);padding:6px 16px;font-size:.78rem;font-weight:700;color:var(--lime);text-transform:uppercase;letter-spacing:.1em;margin-bottom:20px">
        🎉 Your Skin Profile is Ready!
      </div>
      <h1 style="font-family:var(--font-display);font-size:clamp(2rem,4vw,4rem);color:#fff;margin-bottom:12px">
        Hello, <em>{{ $skinLabel }}</em>
      </h1>
      <p style="color:rgba(255,255,255,.6);font-size:1.05rem">Here's what we found — and 3 ways to transform your skin.</p>
    </div>

    <!-- Skin Profile Card -->
    <div class="skin-profile-card reveal">
      <div>
        <div class="skin-type-badge">{{ $skinIcon[$skinType] ?? '🧴' }} {{ $skinType }} Skin</div>
        <h2 style="color:#fff;font-family:var(--font-display);font-size:1.8rem;margin-bottom:16px;line-height:1.2">
          {{ $skinLabel }} Skin
        </h2>
        <p style="color:rgba(255,255,255,.6);font-size:.92rem;line-height:1.6;margin-bottom:24px">
          {{ $skinDesc }}
        </p>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          @foreach($skinBadgesOut as [$label, $color])
            <span class="badge" style="background:{{ $bgMap[$color] }};color:{{ $textMap[$color] }}">{{ $label }}</span>
          @endforeach
        </div>
      </div>
      <div>
        <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:16px">Skin Score</div>
        <div class="skin-score-bars">
          @foreach($skinScores as $label => [$score, $color])
            <div class="skin-score-row">
              <div class="skin-score-label">
                <span style="color:rgba(255,255,255,.8)">{{ $label }}</span>
                <span style="color:{{ $colorMap[$color] }}">{{ $score }}/10</span>
              </div>
              <div class="skin-score-bar">
                <div class="skin-score-fill" style="width:0%;background:{{ $colorMap[$color] }}" data-target="{{ $score * 10 }}%"></div>
              </div>
            </div>
          @endforeach
        </div>
        <div style="margin-top:24px;padding:16px;background:rgba(212,217,148,.1);border:1px solid rgba(212,217,148,.2);border-radius:var(--r-md)">
          <div style="font-size:.78rem;font-weight:700;color:var(--lime);margin-bottom:6px">🔑 Key Ingredients for You</div>
          <div style="display:flex;flex-wrap:wrap;gap:6px">
            @foreach($skinIngred as $ing)
              <span class="tag" style="background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.7)">{{ $ing }}</span>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- —— 3 PATH CARDS + INLINE DROPDOWNS —— -->
<section class="section" id="paths" style="background:var(--cream)">
  <div class="container">

    <!-- Section header -->
    <div class="section-header centered reveal" style="margin-bottom:48px">
      <div class="section-eyebrow"><span class="dot"></span> Act on Your Results</div>
      <h2 class="display-sm section-title">3 Ways to <em class="serif" style="font-weight:400">Transform Your Skin</em></h2>
      <p class="section-desc">Each option is personalized to your {{ $skinLabel }} skin. Click any card to expand the full details.</p>
    </div>

    <!-- Cards — always 3 in a row -->
    <div class="paths-grid">

      <!-- Card 1: AI Routine -->
      <div class="path-card reveal" id="card-routine" onclick="selectPath('routine')">
        <div class="path-source ai-source">
          <span>⚡</span> Autogenerated by Kominhoo Skin OS
        </div>
        <div class="path-card-header">
          <div class="path-icon-wrap">📋</div>
          <div>
            <div class="path-card-title">Your AI Routine</div>
            <div class="path-card-sub">9 products · AM + PM steps</div>
          </div>
        </div>
        <p class="path-card-desc">A full step-by-step AM and PM skincare routine built instantly from your quiz answers. Optimized for Nigerian humidity and your skin's exact combination of concerns.</p>
        <div class="path-highlights">
          <div class="path-highlight">⚡ &nbsp;Instant — ready right now</div>
          <div class="path-highlight">🎯 &nbsp;Matched to your quiz answers</div>
          <div class="path-highlight">🌡️ &nbsp;Nigerian climate optimized</div>
          <div class="path-highlight">🛒 &nbsp;Add full routine to cart in one click</div>
        </div>
        <div class="path-price">₦152,500 total · 9 products</div>
        <div class="path-card-cta">
          <span>View Full Routine</span>
          <span class="path-card-cta-chevron">↓</span>
        </div>
      </div>

      <!-- Card 2: Expert Subscription (FEATURED) -->
      <div class="path-card path-featured reveal reveal-delay-1" id="card-subscription" onclick="selectPath('subscription')">
        <div class="path-badge-recommended">⭐ Recommended</div>
        <div class="path-source expert-source">
          <span>👩‍⚕️</span> Curated by cosmetic doctors &amp; skin experts
        </div>
        <div class="path-card-header">
          <div class="path-icon-wrap">📦</div>
          <div>
            <div class="path-card-title">Expert Subscription</div>
            <div class="path-card-sub">Quarterly · Free shipping</div>
          </div>
        </div>
        <p class="path-card-desc">Real cosmetic doctors and skin experts personally select products for your skin profile every quarter. Updated each season, always free delivery — not an algorithm, actual humans.</p>
        <div class="path-highlights">
          <div class="path-highlight">👩‍⚕️ &nbsp;Human-reviewed, not just algorithms</div>
          <div class="path-highlight">🔄 &nbsp;Updated every season</div>
          <div class="path-highlight">🚚 &nbsp;Free shipping always included</div>
          <div class="path-highlight">✅ &nbsp;Cancel anytime, no lock-in</div>
        </div>
        <div class="path-price">From ₦40,000 / quarter</div>
        <div class="path-card-cta">
          <span>View All Plans</span>
          <span class="path-card-cta-chevron">↓</span>
        </div>
      </div>

      <!-- Card 3: Individual Products -->
      <div class="path-card reveal reveal-delay-2" id="card-products" onclick="selectPath('products')">
        <div class="path-source shop-source">
          <span>🏪</span> Matched to your skin profile by Skin OS
        </div>
        <div class="path-card-header">
          <div class="path-icon-wrap">🪄</div>
          <div>
            <div class="path-card-title">Individual Products</div>
            <div class="path-card-sub">8 matched products</div>
          </div>
        </div>
        <p class="path-card-desc">Browse and buy exactly what you want — individually. Your skin profile has been matched to 8 products from our catalog. No commitment, no subscription, full control.</p>
        <div class="path-highlights">
          <div class="path-highlight">🛍️ &nbsp;Full control — pick what you want</div>
          <div class="path-highlight">💰 &nbsp;No subscription, no commitment</div>
          <div class="path-highlight">🔍 &nbsp;AI-filtered for your skin</div>
          <div class="path-highlight">📦 &nbsp;Ships within 48 hours</div>
        </div>
        <div class="path-price">From ₦4,500 per product</div>
        <div class="path-card-cta">
          <span>Browse Matched Products</span>
          <span class="path-card-cta-chevron">↓</span>
        </div>
      </div>

    </div>

    <!-- ————————————————————————————————————————————————— -->
    <!-- INLINE DROPDOWN PANELS                            -->
    <!-- ————————————————————————————————————————————————— -->
    <div id="path-dropdown-area">

      <!-- Panel A: AI Routine — caret under card 1 (left third) -->
      <div id="panel-routine" class="path-dropdown-panel" style="--caret-pos: 16.67%">

        <div class="panel-header">
          <div>
            <div class="path-source ai-source" style="display:inline-flex;margin-bottom:14px">
              <span>⚡</span> Autogenerated by Kominhoo Skin OS
            </div>
            <h3 style="font-family:var(--font-display);font-size:clamp(1.4rem,2.5vw,2rem);margin-bottom:6px">Your Personalized Routine</h3>
            <p style="font-size:.9rem;color:var(--text-muted)">Built for {{ $skinLabel }} skin · Nigerian climate optimized · 9 products total</p>
          </div>
          <button class="panel-close" onclick="closePanels()">✕ &nbsp;Close</button>
        </div>

        <div class="disclaimer" style="margin-bottom:36px">
          <span>⚠️</span>
          <span><strong>Disclaimer:</strong> This routine is auto-generated by our Skin OS based on your quiz answers. Individual results may vary. If you have a skin condition, please consult a dermatologist. For a human-curated routine, consider our Subscription Plan above.</span>
        </div>

        <div class="routine-cols" style="display:grid;grid-template-columns:1fr 1fr;gap:32px">
          <!-- AM Routine -->
          <div class="reveal">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
              <div style="background:var(--lime);color:var(--black);padding:6px 16px;border-radius:var(--r-pill);font-size:.82rem;font-weight:700">☀️ AM Routine</div>
              <span style="font-size:.82rem;color:var(--text-muted)">5 steps · 3–5 mins</span>
            </div>
            <div class="routine-steps">
              <div class="routine-step">
                <div class="step-num">1</div>
                <div style="flex:1">
                  <div class="step-name">Cleanser</div>
                  <div class="step-product">COSRX Low pH Cleanser — Gentle, pH-balanced</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(1)">+ Add</button>
              </div>
              <div class="routine-step">
                <div class="step-num">2</div>
                <div style="flex:1">
                  <div class="step-name">Toner</div>
                  <div class="step-product">Some By Mi AHA BHA PHA Toner — Exfoliating &amp; clarifying</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(4)">+ Add</button>
              </div>
              <div class="routine-step">
                <div class="step-num">3</div>
                <div style="flex:1">
                  <div class="step-name">Serum</div>
                  <div class="step-product">The Ordinary Niacinamide 10% — Pore control, oil balance</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(5)">+ Add</button>
              </div>
              <div class="routine-step">
                <div class="step-num">4</div>
                <div style="flex:1">
                  <div class="step-name">Moisturizer</div>
                  <div class="step-product">COSRX Oil-Free Gel Moisturizer — Lightweight, non-comedogenic</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(13)">+ Add</button>
              </div>
              <div class="routine-step" style="border-color:var(--lime)">
                <div class="step-num" style="background:var(--lime);color:var(--black)">5</div>
                <div style="flex:1">
                  <div class="step-name">SPF <span class="badge badge-lime" style="font-size:.65rem;padding:2px 8px;margin-left:6px">CRITICAL</span></div>
                  <div class="step-product">Beauty of Joseon Tone Up SPF 50+ — Must for Nigerian sun!</div>
                </div>
                <button class="step-action btn btn-sm btn-primary" onclick="addToCart(6)">+ Add</button>
              </div>
            </div>
          </div>

          <!-- PM Routine -->
          <div class="reveal reveal-delay-2">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
              <div style="background:var(--dark);color:#fff;padding:6px 16px;border-radius:var(--r-pill);font-size:.82rem;font-weight:700">🌙 PM Routine</div>
              <span style="font-size:.82rem;color:var(--text-muted)">4 steps · 2–3 mins</span>
            </div>
            <div class="routine-steps">
              <div class="routine-step">
                <div class="step-num">1</div>
                <div style="flex:1">
                  <div class="step-name">Cleanser</div>
                  <div class="step-product">COSRX Low pH Cleanser — Double cleanse if you wore SPF</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(1)">+ Add</button>
              </div>
              <div class="routine-step">
                <div class="step-num">2</div>
                <div style="flex:1">
                  <div class="step-name">Treatment</div>
                  <div class="step-product">Anua Heartleaf Serum — Calming, pore-tightening, anti-acne</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(7)">+ Add</button>
              </div>
              <div class="routine-step">
                <div class="step-num">3</div>
                <div style="flex:1">
                  <div class="step-name">Moisturizer</div>
                  <div class="step-product">COSRX Oil-Free Gel Moisturizer — Seal hydration in overnight</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(13)">+ Add</button>
              </div>
              <div class="routine-step" style="background:var(--lime-pale);border-color:var(--lime)">
                <div class="step-num" style="background:var(--rose-dark);color:var(--lime)">+</div>
                <div style="flex:1">
                  <div class="step-name">Weekly Treatment (2×/week PM)</div>
                  <div class="step-product">The Ordinary Retinol 0.2% — Start slow, build tolerance</div>
                </div>
                <button class="step-action btn btn-sm btn-outline" onclick="addToCart(18)">+ Add</button>
              </div>
            </div>
            <div style="margin-top:20px;padding:16px;background:var(--gray-100);border-radius:var(--r-lg);border:1.5px solid var(--border)">
              <div style="font-size:.82rem;font-weight:700;margin-bottom:8px">💡 Pro Tip for Your Skin</div>
              <p style="font-size:.82rem;color:var(--text-secondary);line-height:1.5">With {{ strtolower($skinType) }} skin in Nigeria's humidity, use lightweight products in AM. You can use a slightly richer moisturizer at night since AC can dehydrate your skin. Always patch test new actives for 7 days.</p>
            </div>
          </div>
        </div>

        <div style="text-align:center;margin-top:36px;display:flex;flex-direction:column;align-items:center;gap:10px">
          <button class="btn btn-dark btn-lg" onclick="addAllRoutine()">🛒 Add Full Routine to Cart</button>
          <div style="font-size:.82rem;color:var(--text-muted)">9 products · Total: ₦152,500 · Free shipping on orders over ₦30K</div>
        </div>

      </div>


      <!-- Panel B: Expert Subscription — caret under card 2 (center) -->
      <div id="panel-subscription" class="path-dropdown-panel" style="--caret-pos: 50%">

        <div class="panel-header">
          <div>
            <div class="path-source expert-source" style="display:inline-flex;margin-bottom:14px">
              <span>👩‍⚕️</span> Curated by cosmetic doctors &amp; skin experts
            </div>
            <h3 style="font-family:var(--font-display);font-size:clamp(1.4rem,2.5vw,2rem);margin-bottom:6px">Expert Subscription Plans</h3>
            <p style="font-size:.9rem;color:var(--text-muted)">Quarterly delivery · Free shipping · Cancel anytime · Human-reviewed for every skin type</p>
          </div>
          <button class="panel-close" onclick="closePanels()">✕ &nbsp;Close</button>
        </div>

        <div style="background:rgba(168,194,24,.15);border:1.5px solid var(--lime-dark);border-radius:var(--r-md);padding:18px 22px;display:flex;gap:14px;align-items:flex-start;margin-bottom:40px">
          <div style="font-size:1.5rem;flex-shrink:0">👩‍⚕️</div>
          <div>
            <div style="font-weight:700;font-size:.95rem;margin-bottom:4px">Why choose expert curation over an algorithm?</div>
            <p style="font-size:.85rem;color:var(--text-secondary);line-height:1.6">Our team of cosmetic doctors and certified skin experts review your profile, research the latest formulations, and hand-pick products that work synergistically — not just individually. They also consider product interactions, seasonal skin changes in Nigeria, and emerging clinical research. This is the difference between software and skin science.</p>
          </div>
        </div>

        <!-- 3 Tiers -->
        <div class="sub-tiers">

          <!-- Tier 1: Essential -->
          <div class="sub-tier">
            <div class="sub-tier-name">Essential</div>
            <div class="sub-tier-desc">Perfect entry point. Core products for your skin type, reviewed by a certified skin expert.</div>
            <div class="sub-tier-price">₦40,000 <span>/ quarter</span></div>
            <hr class="sub-tier-divider">
            <ul class="sub-tier-features">
              <li>3–4 expert-selected products</li>
              <li>Full-size products, no samples</li>
              <li>Personalized product card inside box</li>
              <li>Free standard shipping</li>
              <li>Access to skin expert chat (once/quarter)</li>
              <li>Cancel anytime</li>
            </ul>
            <button class="btn btn-outline" style="width:100%;margin-top:auto" onclick="showToast('📦','Essential plan — checkout coming in full build!')">Start Essential Plan →</button>
          </div>

          <!-- Tier 2: Advanced (POPULAR) -->
          <div class="sub-tier popular">
            <div class="sub-tier-badge">Most Popular</div>
            <div class="sub-tier-name">Advanced</div>
            <div class="sub-tier-desc">Deeper treatment. More products, a dedicated skin expert review, and treatment boosters.</div>
            <div class="sub-tier-price">₦65,000 <span>/ quarter</span></div>
            <hr class="sub-tier-divider">
            <ul class="sub-tier-features">
              <li>5–6 expert-selected products</li>
              <li>Includes 1 targeted treatment serum</li>
              <li>Dedicated skin expert profile review</li>
              <li>Seasonal adjustment notes included</li>
              <li>Free express shipping</li>
              <li>Priority skin expert chat (2×/quarter)</li>
              <li>10% off any additional shop purchases</li>
            </ul>
            <button class="btn btn-dark" style="width:100%;margin-top:auto" onclick="showToast('📦','Advanced plan — checkout coming in full build!')">Start Advanced Plan →</button>
          </div>

          <!-- Tier 3: Master -->
          <div class="sub-tier">
            <div class="sub-tier-name">Master</div>
            <div class="sub-tier-desc">The complete experience. Premium products, cosmetic doctor consultation, and monthly skin check-ins.</div>
            <div class="sub-tier-price">₦90,000 <span>/ quarter</span></div>
            <hr class="sub-tier-divider">
            <ul class="sub-tier-features">
              <li>7–8 premium expert-selected products</li>
              <li>Includes luxury treatment + eye/lip care</li>
              <li>Cosmetic doctor 1-on-1 consultation call</li>
              <li>Monthly skin progress check-in</li>
              <li>Free express shipping, gift-wrapped</li>
              <li>Unlimited expert chat access</li>
              <li>15% off any additional shop purchases</li>
              <li>Early access to new product launches</li>
            </ul>
            <button class="btn btn-outline" style="width:100%;margin-top:auto" onclick="showToast('📦','Master plan — checkout coming in full build!')">Start Master Plan →</button>
          </div>

        </div>

        <div style="text-align:center;margin-top:24px;font-size:.82rem;color:var(--text-muted)">
          All plans include free cancellation · Products are fully personalized to your skin profile · Delivered every 3 months
        </div>

      </div>


      <!-- Panel C: Individual Products — caret under card 3 (right third) -->
      <div id="panel-products" class="path-dropdown-panel" style="--caret-pos: 83.33%">

        <div class="panel-header">
          <div>
            <div class="path-source shop-source" style="display:inline-flex;margin-bottom:14px">
              <span>🏪</span> Matched to your skin profile by Skin OS
            </div>
            <h3 style="font-family:var(--font-display);font-size:clamp(1.4rem,2.5vw,2rem);margin-bottom:6px">Products Matched to You</h3>
            <p style="font-size:.9rem;color:var(--text-muted)">8 products filtered for {{ $skinLabel }} skin · Buy one, a few, or all of them</p>
          </div>
          <button class="panel-close" onclick="closePanels()">✕ &nbsp;Close</button>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px">
          <div style="font-size:.88rem;color:var(--text-muted)">Showing products matched to your skin concerns</div>
          <div class="carousel-nav">
            <button class="carousel-btn" onclick="scrollTrack('results-track',-1)">←</button>
            <button class="carousel-btn" onclick="scrollTrack('results-track',1)">→</button>
          </div>
        </div>
        <div class="scroll-track" id="results-track"></div>

        <div style="text-align:center;margin-top:28px">
          <a href="{{ route('shop') }}" class="btn btn-dark btn-lg">View Full Catalog →</a>
          <div style="font-size:.82rem;color:var(--text-muted);margin-top:8px">Browse all products filtered to your skin profile in our full shop</div>
        </div>

      </div>

    </div><!-- /#path-dropdown-area -->

    <!-- Comparison Table -->
    <div class="paths-compare reveal">
      <div class="compare-row">
        <div class="compare-label" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em">Compare</div>
        <div class="compare-head">AI Routine</div>
        <div class="compare-head featured-head">Expert Subscription</div>
        <div class="compare-head">Individual Products</div>
      </div>
      <div class="compare-row">
        <div class="compare-label">Personalization</div>
        <div>Algorithm-based, instant</div>
        <div class="featured-col">Doctor-reviewed, human touch</div>
        <div>Self-selected from matched list</div>
      </div>
      <div class="compare-row">
        <div class="compare-label">Who picks</div>
        <div>Kominhoo Skin OS</div>
        <div class="featured-col">Cosmetic doctors &amp; experts</div>
        <div>You</div>
      </div>
      <div class="compare-row">
        <div class="compare-label">Cost</div>
        <div>₦152,500 one-off</div>
        <div class="featured-col">₦40K–₦90K / quarter</div>
        <div>₦4,500+ per item</div>
      </div>
      <div class="compare-row">
        <div class="compare-label">Effort</div>
        <div>Zero — done for you</div>
        <div class="featured-col">Zero — delivered to door</div>
        <div>Browse &amp; add to cart</div>
      </div>
      <div class="compare-row">
        <div class="compare-label">Updates</div>
        <div>One-time snapshot</div>
        <div class="featured-col">Every season, automatically</div>
        <div>Shop whenever you want</div>
      </div>
      <div class="compare-row">
        <div class="compare-label">Best for</div>
        <div>Quick start, exploring</div>
        <div class="featured-col">Long-term skin transformation</div>
        <div>DIY &amp; specific items</div>
      </div>
    </div>

  </div>
</section>


<!-- —— Save Profile CTA —— -->
<section style="background:var(--rose-dark);padding:48px 0">
  <div class="container" style="text-align:center;max-width:600px">
    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(212,217,148,.15);border:1px solid rgba(212,217,148,.3);border-radius:var(--r-pill);padding:5px 14px;font-size:.75rem;font-weight:700;color:var(--lime);text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px">
      Your Skin Profile is Ready
    </div>
    <h3 style="font-family:var(--font-display);font-size:clamp(1.5rem,3vw,2.1rem);color:#fff;margin-bottom:12px;line-height:1.25">Don't lose your results — save them for free</h3>
    <p style="color:rgba(255,255,255,.65);font-size:.92rem;line-height:1.7;margin-bottom:28px">Create an account to save your skin profile, track your progress, and get personalized restocking reminders. Free forever, no credit card needed.</p>
    <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;align-items:center">
      @if(!session('api_token'))
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg" style="font-size:1rem;padding:14px 32px">Create Free Account →</a>
        <a href="{{ route('login') }}" style="font-size:.85rem;color:rgba(255,255,255,.55);text-decoration:underline">Already have an account? Log in</a>
      @else
        <a href="{{ route('dashboard.index') }}" class="btn btn-primary btn-lg" style="font-size:1rem;padding:14px 32px">View Your Dashboard →</a>
      @endif
    </div>
  </div>
</section>

<!-- —— SKIN TIPS —— -->
<section class="section" id="tips" style="background:#fff">
  <div class="container" style="max-width:800px">
    <div class="section-header centered reveal" style="margin-bottom:36px">
      <div class="section-eyebrow"><span class="dot"></span> Expert Advice</div>
      <h2 class="display-sm section-title">Tips for <em class="serif" style="font-weight:400">Your Skin Type</em></h2>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card" style="padding:24px" onclick="this.querySelector('.tip-body').style.display = this.querySelector('.tip-body').style.display==='none'?'block':'none'">
        <div style="display:flex;justify-content:space-between;align-items:center;cursor:pointer">
          <div style="font-weight:700;font-size:1rem">🧪 Why BHA/Salicylic Acid is your best friend</div>
          <span>+</span>
        </div>
        <div class="tip-body" style="display:none;margin-top:12px;font-size:.9rem;color:var(--text-secondary);line-height:1.7">
          BHA (Beta Hydroxy Acid / Salicylic Acid) is oil-soluble, meaning it can penetrate deep into pores to dissolve the sebum and dead skin cells that cause blackheads and breakouts. For combination-oily skin, use it 2–3x a week in your PM routine. Start with a low concentration (1–2%) and build up.
        </div>
      </div>
      <div class="card" style="padding:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;cursor:pointer" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display==='none'?'block':'none'">
          <div style="font-weight:700;font-size:1rem">☀️ SPF is non-negotiable in Nigeria</div>
          <span>+</span>
        </div>
        <div style="display:none;margin-top:12px;font-size:.9rem;color:var(--text-secondary);line-height:1.7">
          Nigeria sits close to the equator — UV index can hit 9–11 during peak hours. Hyperpigmentation from acne scars is 10x worse without SPF. Apply 2 finger lengths every morning, and reapply every 2 hours if outdoors. A lightweight SPF like Beauty of Joseon won't feel heavy in the heat.
        </div>
      </div>
      <div class="card" style="padding:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;cursor:pointer" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display==='none'?'block':'none'">
          <div style="font-weight:700;font-size:1rem">💧 {{ $skinType }} skin still needs hydration</div>
          <span>+</span>
        </div>
        <div style="display:none;margin-top:12px;font-size:.9rem;color:var(--text-secondary);line-height:1.7">
          Skipping moisturizer because you're oily actually makes things worse — your skin overproduces oil to compensate for dehydration. Use a lightweight, oil-free moisturizer or gel to keep skin balanced. Look for hyaluronic acid and niacinamide combos that hydrate AND control oil.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Sticky Add to Cart Bar -->
<div class="sticky-cta-bar show" style="position:sticky;bottom:0;z-index:500;background:#fff;border-top:1px solid var(--border);padding:16px 24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;box-shadow:0 -4px 20px rgba(0,0,0,.08)">
  <div>
    <div style="font-weight:700">Your Routine — 9 products</div>
    <div style="font-size:.82rem;color:var(--text-muted)">Complete AM + PM · Save 20% vs. buying separately</div>
  </div>
  <div style="display:flex;gap:12px">
    @if(!session('api_token'))
      <a href="{{ route('register') }}" class="btn btn-ghost btn-sm">Create Account</a>
    @else
      <a href="{{ route('dashboard.index') }}" class="btn btn-ghost btn-sm">Save Results</a>
    @endif
    <button class="btn btn-primary" onclick="addAllRoutine()">🛒 Add Full Routine → ₦152,500</button>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Animate score bars
  document.querySelectorAll('.skin-score-fill').forEach(el => {
    setTimeout(() => { el.style.transition = 'width 1s ease'; el.style.width = el.dataset.target; }, 600);
  });

  // Confetti
  const colors = ['#D4D994','#893941','#FFFFFF','#1C1416','#F59E0B'];
  const confWrap = document.createElement('div'); confWrap.className = 'confetti'; document.body.appendChild(confWrap);
  for (let i = 0; i < 60; i++) {
    const el = document.createElement('div');
    el.className = 'confetti-piece';
    el.style.cssText = `left:${Math.random()*100}%;top:-10px;background:${colors[Math.floor(Math.random()*colors.length)]};width:${6+Math.random()*10}px;height:${6+Math.random()*10}px;animation-duration:${2+Math.random()*3}s;animation-delay:${Math.random()*2}s`;
    confWrap.appendChild(el);
  }
  setTimeout(() => confWrap.remove(), 5000);

  // Populate products track
  const track = document.getElementById('results-track');
  if (track && typeof PRODUCTS !== 'undefined') {
    const concerns = ['Acne','Pores','Texture','Dehydration','Dark Spots','Dryness'];
    track.innerHTML = PRODUCTS.filter(p => p.concern && p.concern.some(c => concerns.includes(c))).slice(0,8).map(p => buildProductCard(p,'280px')).join('');
  }
});

/* —— Path selection — inline dropdown —— */
function selectPath(name) {
  const card  = document.getElementById('card-' + name);
  const panel = document.getElementById('panel-' + name);
  const isOpen = panel.classList.contains('open');

  // Close all panels + deactivate all cards
  document.querySelectorAll('.path-dropdown-panel').forEach(p => p.classList.remove('open'));
  document.querySelectorAll('.path-card').forEach(c => c.classList.remove('active'));

  if (!isOpen) {
    card.classList.add('active');
    panel.classList.add('open');
    // Scroll so the top of the dropdown is visible, keeping cards above in view
    setTimeout(() => {
      panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 60);
  }
}

function closePanels() {
  document.querySelectorAll('.path-dropdown-panel').forEach(p => p.classList.remove('open'));
  document.querySelectorAll('.path-card').forEach(c => c.classList.remove('active'));
}

function addAllRoutine() {
  [1,4,5,13,6,7,18].forEach(id => addToCart(id));
  setTimeout(() => {
    document.getElementById('cart-drawer').classList.add('open');
    const o = document.getElementById('cart-overlay');
    if(o) { o.style.opacity = '1'; o.style.visibility = 'visible'; }
  }, 300);
}
</script>
@endsection
