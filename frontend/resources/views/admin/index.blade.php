@extends('admin.layouts.app')
@section('title', 'Admin Panel')

@section('content')
{{-- Extra CSS vars not defined in layout --}}
@push('admin_page_css')
  :root {
    --success: #16a34a;
    --warning: #f59e0b;
    --gray-100: #f4f5f7;
    --text-muted: rgba(10,10,10,.45);
  }
  /* Panel Tabs */
  .panel-tabs { display:flex; gap:0; border-bottom:2px solid #e8eaed; margin-bottom:24px; }
  .panel-tab { padding:10px 20px; font-size:.85rem; font-weight:600; cursor:pointer; color:rgba(10,10,10,.45); border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .2s; white-space:nowrap; }
  .panel-tab:hover { color:var(--black); }
  .panel-tab.active { color:var(--rose); border-bottom-color:var(--rose); }
  .panel-tab-content { display:none; }
  .panel-tab-content.active { display:block; }
  /* Wallet bonus form labels */
  .w-cfg-lbl { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(10,10,10,.45); display:block; margin-bottom:6px; }
  .w-cfg-hint { font-size:.72rem; color:#9CA3AF; margin-top:4px; }
  /* Toggle */
  .toggle-wrap { display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid #f4f5f7; }
  .toggle-wrap:last-child { border-bottom:none; }
  .toggle-info strong { font-size:.88rem; font-weight:600; color:var(--black); display:block; }
  .toggle-info span { font-size:.77rem; color:rgba(10,10,10,.45); margin-top:1px; display:block; }
  .toggle { position:relative; width:44px; height:24px; flex-shrink:0; }
  .toggle input { opacity:0; width:0; height:0; }
  .toggle-slider { position:absolute; inset:0; background:#e8eaed; border-radius:12px; transition:.2s; cursor:pointer; }
  .toggle-slider::before { content:''; position:absolute; width:18px; height:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s; box-shadow:0 1px 3px rgba(0,0,0,.15); }
  .toggle input:checked + .toggle-slider { background:var(--rose); }
  .toggle input:checked + .toggle-slider::before { transform:translateX(20px); }
  /* Stars */
  .stars { color:#f59e0b; letter-spacing:1px; }
  /* Ann editor */
  .ann-item { display:flex; align-items:center; gap:8px; padding:10px 0; border-bottom:1px solid #f4f5f7; }
  .ann-item:last-child { border-bottom:none; }
  .ann-drag { color:rgba(10,10,10,.22); cursor:grab; font-size:1.1rem; flex-shrink:0; }
  .ann-emoji-pick { width:52px; padding:8px 6px; text-align:center; font-size:1.1rem; flex-shrink:0; }
  .ann-text-field { flex:1; }
  .ann-link-field { width:160px; flex-shrink:0; }
  /* Hero preview */
  .hero-preview-bar { background:linear-gradient(135deg,#1a1a1a,#2d2d2d); border-radius:12px; padding:20px 24px; color:#fff; margin-bottom:20px; position:relative; overflow:hidden; }
  .hero-preview-bar::before { content:'LIVE PREVIEW'; position:absolute; top:10px; right:12px; font-size:.58rem; font-weight:700; letter-spacing:1.5px; color:rgba(255,255,255,.2); }
  /* Content block */
  .content-block { border:1.5px solid #e8eaed; border-radius:14px; padding:20px; margin-bottom:20px; }
  .content-block-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
  .content-block-header strong { font-size:.95rem; font-weight:700; }
  .content-block-header span { font-size:.78rem; color:rgba(10,10,10,.4); }
  /* Review rows */
  .review-row { display:flex; align-items:flex-start; gap:14px; padding:16px 22px; border-bottom:1px solid #f4f5f7; }
  .review-row:last-child { border-bottom:none; }
  .review-body { flex:1; min-width:0; }
  .review-title { font-size:.85rem; font-weight:600; display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:3px; }
  .review-text { font-size:.82rem; color:rgba(10,10,10,.6); line-height:1.5; margin-top:4px; }
  .review-meta { font-size:.72rem; color:rgba(10,10,10,.38); margin-top:5px; }
  .review-actions { display:flex; flex-direction:column; gap:6px; flex-shrink:0; }
  /* ── Coupon / Voucher Cards — Premium Ticket Style ─────────── */
  .coupon-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 24px;
  }
  .coupon-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 14px rgba(0,0,0,.07), 0 0 0 1.5px rgba(0,0,0,.055);
    overflow: hidden;
    position: relative;
    border: none;
    transition: transform .18s ease, box-shadow .18s ease;
    display: flex;
    flex-direction: column;
  }
  .coupon-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 36px rgba(0,0,0,.13), 0 0 0 1.5px rgba(0,0,0,.07);
  }
  .coupon-card.active-coupon { background: #fff; border: none; }

  /* Top accent stripe by type */
  .coupon-stripe { height: 5px; width: 100%; flex-shrink: 0; }
  .cs-lime { background: linear-gradient(90deg, #c8e634 0%, #a5c400 100%); }
  .cs-dark { background: linear-gradient(90deg, #0a0a0a 0%, #3c3c3c 100%); }
  .cs-blue { background: linear-gradient(90deg, #4f94ea 0%, #6e6af4 100%); }
  .cs-red  { background: linear-gradient(90deg, #e63434 0%, #ff7b7b 100%); }

  /* Card body */
  .coupon-body { padding: 18px 18px 0; flex: 1; }

  /* Type row */
  .coupon-type-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
  .coupon-type-pill {
    font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    padding: 3px 9px; border-radius: 20px;
  }
  .ctp-pct  { background: rgba(212,217,148,.2);  color: #5E6623; }
  .ctp-fix  { background: rgba(10,10,10,.08);   color: #2a2a2a; }
  .ctp-ship { background: rgba(79,148,234,.13); color: #1a4f9e; }

  /* Code box */
  .coupon-code-box {
    background: linear-gradient(135deg, #f9f9f7 0%, #f1f0ea 100%);
    border-radius: 11px;
    padding: 14px 16px;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
  }
  .coupon-code-box::after {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
      -55deg,
      transparent, transparent 10px,
      rgba(0,0,0,.018) 10px, rgba(0,0,0,.018) 20px
    );
    pointer-events: none;
  }
  .coupon-code {
    font-size: 1.35rem; font-weight: 700; letter-spacing: .14em;
    font-family: 'Courier New', monospace;
    color: var(--black); position: relative; z-index: 1;
    margin-bottom: 0;
  }
  .coupon-discount-big {
    font-size: .78rem; font-weight: 700; color: rgba(10,10,10,.42);
    margin-top: 3px; position: relative; z-index: 1;
    letter-spacing: .01em;
  }

  /* Perforated divider */
  .coupon-perf {
    display: flex; align-items: center;
    margin: 0 -18px;
  }
  .coupon-perf-notch {
    width: 20px; height: 20px; border-radius: 50%;
    background: #f4f5f7;
    flex-shrink: 0;
    box-shadow: inset 0 0 0 1.5px rgba(0,0,0,.07);
  }
  .coupon-perf-line {
    flex: 1; height: 0;
    border: none;
    border-top: 2px dashed #e2e1dc;
    margin: 0;
  }

  /* Meta info */
  .coupon-meta { padding: 11px 0 6px; display: grid; gap: 5px; }
  .coupon-meta-row { display: flex; align-items: center; gap: 7px; font-size: .73rem; color: rgba(10,10,10,.48); }
  .coupon-meta-icon { font-size: .8rem; flex-shrink: 0; width: 14px; text-align: center; }

  /* Usage bar */
  .coupon-use-bar-track { height: 3px; border-radius: 2px; background: #eeedea; margin-top: 5px; }
  .coupon-use-bar-fill  { height: 100%; border-radius: 2px; background: linear-gradient(90deg,#c8e634,#a5c400); transition: width .4s; }

  /* Free-shipping badge on card */
  .coupon-ship-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(79,148,234,.1); color: #1a4f9e;
    font-size: .68rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; margin-top: 6px;
    position: relative; z-index: 1;
  }

  /* Footer actions */
  .coupon-foot {
    border-top: 1.5px solid #f4f4f0;
    padding: 11px 16px;
    display: flex;
    gap: 6px;
    align-items: center;
    flex-shrink: 0;
  }
  .coupon-foot-edit { flex: 1; justify-content: center; font-size: .78rem; }

  /* New-code placeholder card */
  .coupon-card-add {
    background: #fafaf8;
    border: 2px dashed #ddddd8;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    min-height: 220px;
    transition: border-color .2s, background .2s;
  }
  .coupon-card-add:hover {
    border-color: var(--black);
    background: #f5f5f2;
  }
  .coupon-card-add-inner { text-align: center; color: rgba(10,10,10,.28); }
  .coupon-card-add-icon { font-size: 2rem; margin-bottom: 8px; }
  .coupon-card-add-label { font-size: .82rem; font-weight: 700; }

  /* Flash sale redesign */
  .flash-row {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 22px; border-bottom: 1px solid #f4f4f0;
    flex-wrap: wrap;
    transition: background .15s;
  }
  .flash-row:last-child { border-bottom: none; }
  .flash-row:hover { background: #fafaf8; }
  .flash-discount-pill {
    background: var(--red); color: #fff;
    font-size: .7rem; font-weight: 700;
    padding: 4px 12px; border-radius: 20px; white-space: nowrap;
    letter-spacing: .04em;
  }
  .flash-ends { font-size: .73rem; color: rgba(10,10,10,.38); white-space: nowrap; }

  /* Gift card admin */
  .gc-admin-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
  @media(max-width:1100px){ .gc-admin-grid { grid-template-columns:repeat(2,1fr); } }
  .gc-admin-denomination { border:1.5px solid var(--border); border-radius:16px; overflow:hidden; background:#fff; transition:box-shadow .2s,transform .2s; }
  .gc-admin-denomination:hover { box-shadow:0 6px 24px rgba(0,0,0,.08); transform:translateY(-2px); }
  .gc-admin-denom-vis {
    aspect-ratio:1.75; position:relative; overflow:hidden;
    background: linear-gradient(148deg,#2a1215 0%,#5a2228 45%,#7b3340 100%);
  }
  .gc-admin-denom-vis::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background: radial-gradient(ellipse at 80% 18%,rgba(180,96,112,.5) 0%,transparent 55%),
                radial-gradient(ellipse at 18% 82%,rgba(107,42,48,.6) 0%,transparent 50%);
  }
  .gc-admin-denom-vis::after {
    content:''; position:absolute; inset:0; pointer-events:none;
    background: linear-gradient(140deg,rgba(255,255,255,.18) 0%,rgba(255,255,255,.04) 40%,transparent 100%);
  }
  .gc-admin-denom-vis-inner { position:relative; z-index:2; padding:12px 14px; height:100%; display:flex; flex-direction:column; justify-content:space-between; color:#fff; }
  .gc-admin-denom-vis-brand { font-family:var(--font-display,serif); font-size:.48rem; letter-spacing:.18em; opacity:.4; }
  .gc-admin-denom-vis-amt { font-family:var(--font-display,serif); font-size:1.2rem; line-height:1; }
  .gc-admin-denom-vis-tag { font-size:.5rem; font-weight:700; letter-spacing:.14em; opacity:.3; text-transform:uppercase; }
  .gc-admin-denom-body { padding:12px 14px 14px; }
  .gc-admin-denomination .amount  { font-size:1.05rem; font-weight:700; color:var(--black); margin-bottom:2px; }
  .gc-admin-denomination .sold    { font-size:.75rem; color:rgba(10,10,10,.5); }
  .gc-admin-denomination .revenue { font-size:.75rem; color:rgba(10,10,10,.35); margin-bottom:10px; }

  /* Quiz Config accordion */
  .qz-acc-item { border:1.5px solid #e8eaed; border-radius:12px; margin-bottom:10px; overflow:hidden; }
  .qz-acc-header { display:flex; align-items:center; gap:12px; padding:14px 18px; cursor:pointer; background:#fafbfc; transition:background .15s; user-select:none; }
  .qz-acc-header:hover { background:#f0f2f4; }
  .qz-acc-item.open .qz-acc-header { background:#f0f2f4; border-bottom:1.5px solid #e8eaed; }
  .qz-acc-body { display:none; padding:20px 22px; }
  .qz-acc-item.open .qz-acc-body { display:block; }
  .qz-chevron { font-size:1.2rem; color:rgba(10,10,10,.3); transition:transform .2s; flex-shrink:0; }
  .qz-acc-item.open .qz-chevron { transform:rotate(90deg); }
  .qz-stage-card { border:1.5px solid #e8eaed; border-radius:14px; padding:20px; }
  .qz-stage-card-header { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
  .qz-color-swatch { width:16px; height:16px; border-radius:50%; flex-shrink:0; }
@endpush

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Overview                                  -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel active" id="panel-overview">
      @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $adminDisplayName = $admin['name'] ?? 'Admin';
        $revenueDisplay = $totalRevenue >= 1000000
          ? '₦' . number_format($totalRevenue / 1000000, 1) . 'M'
          : ($totalRevenue >= 1000 ? '₦' . number_format($totalRevenue / 1000, 1) . 'K' : '₦' . number_format($totalRevenue));
        $ordersDiff = $ordersThisMonth - $ordersPrevMonth;
      @endphp
      <div class="panel-header">
        <div>
          <h1>{{ $greeting }}, {{ $adminDisplayName }} 👋</h1>
          <p>Here's what's happening with Kominhoo today.</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
          <button class="action-btn primary" onclick="openAddModal()">+ Add Product</button>
        </div>
      </div>
      <div class="kpi-grid">
        <div class="kpi-card lime"><div class="kpi-icon">💰</div><div class="kpi-label">Total Revenue</div><div class="kpi-value">{{ $revenueDisplay }}</div><div class="kpi-sub">All orders combined</div></div>
        <div class="kpi-card red"><div class="kpi-icon">🛍️</div><div class="kpi-label">Orders This Month</div><div class="kpi-value">{{ number_format($ordersThisMonth) }}</div><div class="kpi-sub">@if($ordersDiff > 0)<span class="kpi-up">↑ {{ $ordersDiff }}</span>@elseif($ordersDiff < 0)<span class="kpi-down">↓ {{ abs($ordersDiff) }}</span>@else —@endif vs last month</div></div>
        <div class="kpi-card blue"><div class="kpi-icon">👥</div><div class="kpi-label">Registered Users</div><div class="kpi-value">{{ number_format($totalUsers) }}</div><div class="kpi-sub">Total customers</div></div>
        <div class="kpi-card amber"><div class="kpi-icon">🧴</div><div class="kpi-label">Products in Catalog</div><div class="kpi-value">{{ count($catalogProducts) }}</div><div class="kpi-sub">@if($lowStockCount > 0)<span class="kpi-down">{{ $lowStockCount }}</span> low stock alerts@else All in stock @endif</div></div>
      </div>
      <div class="dash-row-3">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Revenue Overview</h3><p>Monthly revenue (₦) — current year</p></div><div style="font-size:.78rem;color:rgba(10,10,10,.4);">{{ $currentYear }}</div></div>
          <div class="chart-placeholder"><div class="chart-bars" id="revenueChart"></div><div class="chart-legend"><div class="chart-legend-item"><div class="chart-legend-dot" style="background:var(--lime)"></div> Revenue</div><div class="chart-legend-item"><div class="chart-legend-dot" style="background:var(--red);opacity:.7"></div> Orders</div></div></div>
        </div>
        <div class="section-card">
          <div class="section-card-header"><div><h3>Skin Type Breakdown</h3><p>From quiz submissions</p></div></div>
          @php
            $qtTotal = max(1, $totalQuizTakers);
            $stComboPct   = round($skinTypeCounts['combination'] / $qtTotal * 100);
            $stOilyPct    = round($skinTypeCounts['oily']        / $qtTotal * 100);
            $stDryPct     = round($skinTypeCounts['dry']         / $qtTotal * 100);
            $stNormalPct  = max(0, 100 - $stComboPct - $stOilyPct - $stDryPct);
          @endphp
          <div class="donut-wrap">
            <div style="font-size:.78rem;font-weight:600;color:rgba(10,10,10,.4);margin-bottom:4px;">{{ number_format($totalQuizTakers) }} quiz taker{{ $totalQuizTakers !== 1 ? 's' : '' }}</div>
            <div class="donut-circle"></div>
            <div class="donut-labels">
              <div class="donut-row"><div class="donut-label-left"><div class="donut-dot" style="background:var(--lime)"></div> Combination</div><div class="donut-pct">{{ $stComboPct }}%</div></div>
              <div class="donut-row"><div class="donut-label-left"><div class="donut-dot" style="background:var(--red)"></div> Oily</div><div class="donut-pct">{{ $stOilyPct }}%</div></div>
              <div class="donut-row"><div class="donut-label-left"><div class="donut-dot" style="background:#4f94ea"></div> Dry</div><div class="donut-pct">{{ $stDryPct }}%</div></div>
              <div class="donut-row"><div class="donut-label-left"><div class="donut-dot" style="background:#f59e0b"></div> Normal/Other</div><div class="donut-pct">{{ $stNormalPct }}%</div></div>
            </div>
          </div>
        </div>
      </div>
      <div class="dash-row">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Recent Activity</h3><p>Latest events across the platform</p></div><button class="action-btn edit" style="font-size:.75rem;">View All</button></div>
          <div class="activity-list">
            @forelse($recentActivity as $ra)
              @php
                $raAddr    = $ra['shipping_address'] ?? [];
                $raName    = ($ra['user']['name'] ?? $raAddr['name'] ?? 'Customer');
                $raItems   = $ra['items'] ?? [];
                $raFirst   = $raItems[0]['name'] ?? 'Order items';
                $raExtra   = count($raItems) > 1 ? ' +' . (count($raItems) - 1) . ' more' : '';
                $raTotal   = '₦' . number_format($ra['total'] ?? 0);
                $raTime    = isset($ra['created_at']) ? \Carbon\Carbon::parse($ra['created_at'])->diffForHumans() : '—';
              @endphp
              <div class="activity-item">
                <div class="activity-dot order">🛍️</div>
                <div class="activity-text">
                  <strong>New order #{{ $ra['order_number'] ?? $ra['id'] }}</strong>
                  <p>{{ $raName }} — {{ $raFirst }}{{ $raExtra }} · {{ $raTotal }}</p>
                </div>
                <div class="activity-time">{{ $raTime }}</div>
              </div>
            @empty
              @if($lowStockCount > 0)
                @foreach(array_slice($lowStockItems, 0, 3) as $ls)
                <div class="activity-item">
                  <div class="activity-dot stock">⚠️</div>
                  <div class="activity-text">
                    <strong>Low stock alert</strong>
                    <p>{{ $ls['name'] ?? 'Product' }} — {{ $ls['stock'] ?? $ls['stock_quantity'] ?? 0 }} units left</p>
                  </div>
                  <div class="activity-time">Now</div>
                </div>
                @endforeach
              @else
                <div style="text-align:center;padding:32px;color:rgba(10,10,10,.35);font-size:.85rem;">No recent activity yet.</div>
              @endif
            @endforelse
          </div>
        </div>
        <div class="section-card">
          <div class="section-card-header"><div><h3>Top Products</h3><p>By revenue this month</p></div></div>
          <div id="topProductsList"></div>
        </div>
      </div>
      @php
        $tierTotal   = max(1, $totalUsers);
        $starterCount  = ($tierCounts['starter'] ?? 0) + ($tierCounts['glow'] ?? 0);
        $radiantCount  = $tierCounts['radiant'] ?? 0;
        $iconicCount   = $tierCounts['iconic'] ?? 0;
        $otherCount    = $tierTotal - $starterCount - $radiantCount - $iconicCount;
        $starterCount += max(0, $otherCount);
        $starterPct  = round($starterCount  / $tierTotal * 100);
        $radiantPct  = round($radiantCount  / $tierTotal * 100);
        $iconicPct   = max(0, 100 - $starterPct - $radiantPct);
      @endphp
      <div class="section-card" style="margin-bottom:0;">
        <div class="section-card-header"><div><h3>Loyalty Tier Distribution</h3><p>Customer tiers across {{ number_format($totalUsers) }} registered users</p></div></div>
        <div class="tier-progress-wrap">
          @php
            $tierDefs = $loyaltyConfig['tiers'] ?? [];
            $tier1Name = $tierDefs[0]['name'] ?? 'Glow Starter';
            $tier2Name = $tierDefs[1]['name'] ?? 'Radiant Insider';
            $tier3Name = $tierDefs[2]['name'] ?? 'Luxe Luminary';
          @endphp
          <div class="tier-row"><div class="tier-name">✨ {{ $tier1Name }}</div><div class="tier-bar-track"><div class="tier-bar-fill lime" style="width:{{ $starterPct }}%"></div></div><div class="tier-count" style="color:var(--black);">{{ number_format($starterCount) }}</div></div>
          <div class="tier-row"><div class="tier-name">💎 {{ $tier2Name }}</div><div class="tier-bar-track"><div class="tier-bar-fill red" style="width:{{ $radiantPct }}%"></div></div><div class="tier-count" style="color:var(--red);">{{ number_format($radiantCount) }}</div></div>
          <div class="tier-row"><div class="tier-name">👑 {{ $tier3Name }}</div><div class="tier-bar-track"><div class="tier-bar-fill blue" style="width:{{ $iconicPct }}%"></div></div><div class="tier-count" style="color:#4f94ea;">{{ number_format($iconicCount) }}</div></div>
        </div>
      </div>
    </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Content Manager                            -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-content">
      <div class="panel-header">
        <div><h1>Content Manager</h1><p>Edit all homepage content — text, images, sections, and visibility</p></div>
        <div style="display:flex;gap:8px;align-items:center;">
          <button class="action-btn primary" onclick="saveContentManager()">Save All Changes</button>
        </div>
      </div>

      <!-- CMS Tab Bar -->
      <div class="panel-tabs" id="cmsTabBar" style="overflow-x:auto;flex-wrap:nowrap;white-space:nowrap;">
        <div class="panel-tab active" onclick="cmSwitchTab('cms-hero',this)">Hero</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-slides',this)">Slides &amp; Pins</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-why',this)">Why Kominhoo</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-quiz-banner',this)">Quiz Banner</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-deal',this)">Deal of the Day</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-new-drops',this)">New Drops</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-newsletter',this)">Newsletter</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-quiz-popup',this)">Quiz Popup</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-sections',this)">Sections</div>
        <div class="panel-tab" onclick="cmSwitchTab('cms-announcement',this)">Announcement</div>
      </div>

      <!-- ─ HERO ─ -->
      <div class="panel-tab-content active" id="cms-hero">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Hero Text &amp; CTAs</h3><p>Main banner text, buttons, and bottom stats bar</p></div>
            <div class="toggle-wrap" style="border:none;padding:0;"><div class="toggle-info"><strong style="font-size:.82rem;">Hero Visible</strong></div><label class="toggle" style="margin-left:12px;"><input type="checkbox" id="cm_hero_visible" checked><span class="toggle-slider"></span></label></div>
          </div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:1fr 1fr;gap:28px;">
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Eyebrow</label><input id="cm_hero_eyebrow" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Personalized Korean Beauty"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Line 1</label><input id="cm_hero_title_1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Your Skin,"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Line 2 (italic)</label><input id="cm_hero_title_2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Decoded."/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Line 3</label><input id="cm_hero_title_3" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Perfected."/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Description</label><textarea id="cm_hero_desc" class="form-input" style="width:100%;padding:9px 12px;min-height:70px;" placeholder="Authentic K-beauty formulas..."></textarea></div>
            </div>
            <div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">
                <div><label class="w-cfg-lbl">Primary CTA Text</label><input id="cm_hero_cta_text" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Take the Skin Quiz"/></div>
                <div><label class="w-cfg-lbl">Primary CTA Link</label><input id="cm_hero_cta_link" class="form-input" style="width:100%;padding:9px 12px;" placeholder="/quiz"/></div>
                <div><label class="w-cfg-lbl">Secondary CTA Text</label><input id="cm_hero_cta2_text" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Browse Products"/></div>
                <div><label class="w-cfg-lbl">Secondary CTA Link</label><input id="cm_hero_cta2_link" class="form-input" style="width:100%;padding:9px 12px;" placeholder="/shop"/></div>
              </div>
              <div style="font-size:.85rem;font-weight:700;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #f4f5f7;">Stats Bar (below CTAs)</div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div><label class="w-cfg-lbl">Stat 1 Number</label><input id="cm_stat_1_num" class="form-input" style="width:100%;padding:8px 10px;" placeholder="50K+"/></div>
                <div><label class="w-cfg-lbl">Stat 1 Label</label><input id="cm_stat_1_label" class="form-input" style="width:100%;padding:8px 10px;" placeholder="Happy Skin Lovers"/></div>
                <div><label class="w-cfg-lbl">Stat 2 Number</label><input id="cm_stat_2_num" class="form-input" style="width:100%;padding:8px 10px;" placeholder="200+"/></div>
                <div><label class="w-cfg-lbl">Stat 2 Label</label><input id="cm_stat_2_label" class="form-input" style="width:100%;padding:8px 10px;" placeholder="Curated Products"/></div>
                <div><label class="w-cfg-lbl">Stat 3 Number</label><input id="cm_stat_3_num" class="form-input" style="width:100%;padding:8px 10px;" placeholder="4.8★"/></div>
                <div><label class="w-cfg-lbl">Stat 3 Label</label><input id="cm_stat_3_label" class="form-input" style="width:100%;padding:8px 10px;" placeholder="Average Rating"/></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ SLIDES & PINS ─ -->
      <div class="panel-tab-content" id="cms-slides">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Hero Slides</h3><p>3 rotating background images with overlay pin labels. Upload or paste a URL for each slide.</p></div></div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
            <!-- Slide 1 -->
            <div style="border:1.5px solid #e8eaed;border-radius:14px;padding:18px;">
              <div class="w-cfg-lbl" style="margin-bottom:10px;font-size:.72rem;">SLIDE 1</div>
              <div id="cms_s1_thumb" style="height:110px;border-radius:10px;overflow:hidden;margin-bottom:10px;background:#f4f5f7;background-size:cover;background-position:center;display:flex;align-items:center;justify-content:center;font-size:.76rem;color:rgba(10,10,10,.35);">Preview</div>
              <label class="w-cfg-lbl">Image URL</label>
              <div style="display:flex;gap:6px;margin-bottom:10px;"><input id="cm_slide1_url" class="form-input" style="flex:1;padding:8px 10px;" placeholder="https://..." oninput="cmSlideThumb(1)"/><label class="action-btn" style="cursor:pointer;padding:8px 10px;white-space:nowrap;">Upload<input type="file" accept="image/*" style="display:none;" onchange="cmUploadSlide(1,this)"></label></div>
              <label class="w-cfg-lbl">Alt Text</label><input id="cm_slide1_alt" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:12px;"/>
              <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.35);margin:10px 0 8px;padding-top:10px;border-top:1px solid #f4f5f7;">PIN A</div>
              <label class="w-cfg-lbl">Name</label><input id="cm_s1_pa_name" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:8px;" placeholder="Glowing Skin Kit"/>
              <label class="w-cfg-lbl">Detail</label><input id="cm_s1_pa_detail" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:12px;" placeholder="3-step ritual · Best Value"/>
              <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.35);margin:0 0 8px;padding-top:10px;border-top:1px solid #f4f5f7;">PIN B</div>
              <label class="w-cfg-lbl">Name</label><input id="cm_s1_pb_name" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:8px;" placeholder="Most Loved ✦"/>
              <label class="w-cfg-lbl">Detail</label><input id="cm_s1_pb_detail" class="form-input" style="width:100%;padding:8px 10px;" placeholder="4.9★ · 1,200 reviews"/>
            </div>
            <!-- Slide 2 -->
            <div style="border:1.5px solid #e8eaed;border-radius:14px;padding:18px;">
              <div class="w-cfg-lbl" style="margin-bottom:10px;font-size:.72rem;">SLIDE 2</div>
              <div id="cms_s2_thumb" style="height:110px;border-radius:10px;overflow:hidden;margin-bottom:10px;background:#f4f5f7;background-size:cover;background-position:center;display:flex;align-items:center;justify-content:center;font-size:.76rem;color:rgba(10,10,10,.35);">Preview</div>
              <label class="w-cfg-lbl">Image URL</label>
              <div style="display:flex;gap:6px;margin-bottom:10px;"><input id="cm_slide2_url" class="form-input" style="flex:1;padding:8px 10px;" placeholder="https://..." oninput="cmSlideThumb(2)"/><label class="action-btn" style="cursor:pointer;padding:8px 10px;white-space:nowrap;">Upload<input type="file" accept="image/*" style="display:none;" onchange="cmUploadSlide(2,this)"></label></div>
              <label class="w-cfg-lbl">Alt Text</label><input id="cm_slide2_alt" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:12px;"/>
              <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.35);margin:10px 0 8px;padding-top:10px;border-top:1px solid #f4f5f7;">PIN A</div>
              <label class="w-cfg-lbl">Name</label><input id="cm_s2_pa_name" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:8px;" placeholder="COSRX Snail 96"/>
              <label class="w-cfg-lbl">Detail</label><input id="cm_s2_pa_detail" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:12px;" placeholder="Best Seller · Free shipping"/>
              <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.35);margin:0 0 8px;padding-top:10px;border-top:1px solid #f4f5f7;">PIN B</div>
              <label class="w-cfg-lbl">Name</label><input id="cm_s2_pb_name" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:8px;" placeholder="100% Authentic"/>
              <label class="w-cfg-lbl">Detail</label><input id="cm_s2_pb_detail" class="form-input" style="width:100%;padding:8px 10px;" placeholder="Sourced from Seoul"/>
            </div>
            <!-- Slide 3 -->
            <div style="border:1.5px solid #e8eaed;border-radius:14px;padding:18px;">
              <div class="w-cfg-lbl" style="margin-bottom:10px;font-size:.72rem;">SLIDE 3</div>
              <div id="cms_s3_thumb" style="height:110px;border-radius:10px;overflow:hidden;margin-bottom:10px;background:#f4f5f7;background-size:cover;background-position:center;display:flex;align-items:center;justify-content:center;font-size:.76rem;color:rgba(10,10,10,.35);">Preview</div>
              <label class="w-cfg-lbl">Image URL</label>
              <div style="display:flex;gap:6px;margin-bottom:10px;"><input id="cm_slide3_url" class="form-input" style="flex:1;padding:8px 10px;" placeholder="https://..." oninput="cmSlideThumb(3)"/><label class="action-btn" style="cursor:pointer;padding:8px 10px;white-space:nowrap;">Upload<input type="file" accept="image/*" style="display:none;" onchange="cmUploadSlide(3,this)"></label></div>
              <label class="w-cfg-lbl">Alt Text</label><input id="cm_slide3_alt" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:12px;"/>
              <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.35);margin:10px 0 8px;padding-top:10px;border-top:1px solid #f4f5f7;">PIN A</div>
              <label class="w-cfg-lbl">Name</label><input id="cm_s3_pa_name" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:8px;" placeholder="14-Step Skin Quiz"/>
              <label class="w-cfg-lbl">Detail</label><input id="cm_s3_pa_detail" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:12px;" placeholder="Free · Takes 60 seconds"/>
              <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.35);margin:0 0 8px;padding-top:10px;border-top:1px solid #f4f5f7;">PIN B</div>
              <label class="w-cfg-lbl">Name</label><input id="cm_s3_pb_name" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:8px;" placeholder="Glass Skin Goal"/>
              <label class="w-cfg-lbl">Detail</label><input id="cm_s3_pb_detail" class="form-input" style="width:100%;padding:8px 10px;" placeholder="1,200+ five-star reviews"/>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ WHY KOMINHOO ─ -->
      <div class="panel-tab-content" id="cms-why">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Why Kominhoo — Section Header</h3><p>Kicker, heading, lead text and 4 feature cards</p></div>
            <div class="toggle-wrap" style="border:none;padding:0;"><div class="toggle-info"><strong style="font-size:.82rem;">Section Visible</strong></div><label class="toggle" style="margin-left:12px;"><input type="checkbox" id="cm_why_visible" checked><span class="toggle-slider"></span></label></div>
          </div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Kicker</label><input id="cm_why_kicker" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Why Kominhoo"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Heading Line 1</label><input id="cm_why_heading_1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Luxury skincare, refined for"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Heading Line 2</label><input id="cm_why_heading_2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="your routine."/></div>
            </div>
            <div><label class="w-cfg-lbl">Lead Text</label><textarea id="cm_why_lead" class="form-input" style="width:100%;padding:9px 12px;min-height:80px;" placeholder="Curated formulas, authentic sourcing..."></textarea></div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <!-- Card 1 -->
          <div class="section-card" style="margin-bottom:0;">
            <div class="section-card-header"><div><h3>Card 1 — Skin-Quiz Matched</h3></div></div>
            <div style="padding:16px 22px;">
              <div style="display:grid;grid-template-columns:72px 1fr;gap:10px;margin-bottom:10px;">
                <div><label class="w-cfg-lbl">Icon (emoji)</label><input id="cm_why_c1_icon" class="form-input" style="width:100%;padding:9px 10px;text-align:center;font-size:1.1rem;"/></div>
                <div><label class="w-cfg-lbl">Card Title</label><input id="cm_why_c1_title" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Skin-Quiz Matched"/></div>
              </div>
              <label class="w-cfg-lbl">Description</label><textarea id="cm_why_c1_desc" class="form-input" style="width:100%;padding:8px 10px;min-height:56px;margin-bottom:10px;" placeholder="Every recommendation is personalized..."></textarea>
              <label class="w-cfg-lbl">Card Image URL</label>
              <div style="display:flex;gap:6px;margin-bottom:6px;"><input id="cm_why1_url" class="form-input" style="flex:1;padding:8px 10px;" oninput="cmWhyThumbRefresh(1)"/><label class="action-btn" style="cursor:pointer;padding:8px 10px;">Upload<input type="file" accept="image/*" style="display:none;" onchange="cmUploadWhy(1,this)"></label></div>
              <label class="w-cfg-lbl">Alt Text</label><input id="cm_why1_alt" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:6px;"/>
              <div id="cms_why1_thumb" style="height:70px;border-radius:8px;background:#f4f5f7;background-size:cover;background-position:center;"></div>
            </div>
          </div>
          <!-- Card 2 -->
          <div class="section-card" style="margin-bottom:0;">
            <div class="section-card-header"><div><h3>Card 2 — Authentic K-Beauty</h3></div></div>
            <div style="padding:16px 22px;">
              <div style="display:grid;grid-template-columns:72px 1fr;gap:10px;margin-bottom:10px;">
                <div><label class="w-cfg-lbl">Icon (emoji)</label><input id="cm_why_c2_icon" class="form-input" style="width:100%;padding:9px 10px;text-align:center;font-size:1.1rem;"/></div>
                <div><label class="w-cfg-lbl">Card Title</label><input id="cm_why_c2_title" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Authentic K-Beauty"/></div>
              </div>
              <label class="w-cfg-lbl">Description</label><textarea id="cm_why_c2_desc" class="form-input" style="width:100%;padding:8px 10px;min-height:56px;margin-bottom:10px;" placeholder="Sourced directly from top Korean brands..."></textarea>
              <label class="w-cfg-lbl">Card Image URL</label>
              <div style="display:flex;gap:6px;margin-bottom:6px;"><input id="cm_why2_url" class="form-input" style="flex:1;padding:8px 10px;" oninput="cmWhyThumbRefresh(2)"/><label class="action-btn" style="cursor:pointer;padding:8px 10px;">Upload<input type="file" accept="image/*" style="display:none;" onchange="cmUploadWhy(2,this)"></label></div>
              <label class="w-cfg-lbl">Alt Text</label><input id="cm_why2_alt" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:6px;"/>
              <div id="cms_why2_thumb" style="height:70px;border-radius:8px;background:#f4f5f7;background-size:cover;background-position:center;"></div>
            </div>
          </div>
          <!-- Card 3 -->
          <div class="section-card" style="margin-bottom:0;">
            <div class="section-card-header"><div><h3>Card 3 — Fast Delivery</h3></div></div>
            <div style="padding:16px 22px;">
              <div style="display:grid;grid-template-columns:72px 1fr;gap:10px;margin-bottom:10px;">
                <div><label class="w-cfg-lbl">Icon (emoji)</label><input id="cm_why_c3_icon" class="form-input" style="width:100%;padding:9px 10px;text-align:center;font-size:1.1rem;"/></div>
                <div><label class="w-cfg-lbl">Card Title</label><input id="cm_why_c3_title" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Fast Delivery"/></div>
              </div>
              <label class="w-cfg-lbl">Description</label><textarea id="cm_why_c3_desc" class="form-input" style="width:100%;padding:8px 10px;min-height:56px;margin-bottom:10px;" placeholder="Free shipping on orders over ₦50,000..."></textarea>
              <label class="w-cfg-lbl">Card Image URL</label>
              <div style="display:flex;gap:6px;margin-bottom:6px;"><input id="cm_why3_url" class="form-input" style="flex:1;padding:8px 10px;" oninput="cmWhyThumbRefresh(3)"/><label class="action-btn" style="cursor:pointer;padding:8px 10px;">Upload<input type="file" accept="image/*" style="display:none;" onchange="cmUploadWhy(3,this)"></label></div>
              <label class="w-cfg-lbl">Alt Text</label><input id="cm_why3_alt" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:6px;"/>
              <div id="cms_why3_thumb" style="height:70px;border-radius:8px;background:#f4f5f7;background-size:cover;background-position:center;"></div>
            </div>
          </div>
          <!-- Card 4 -->
          <div class="section-card" style="margin-bottom:0;">
            <div class="section-card-header"><div><h3>Card 4 — Earn &amp; Glow</h3></div></div>
            <div style="padding:16px 22px;">
              <div style="display:grid;grid-template-columns:72px 1fr;gap:10px;margin-bottom:10px;">
                <div><label class="w-cfg-lbl">Icon (emoji)</label><input id="cm_why_c4_icon" class="form-input" style="width:100%;padding:9px 10px;text-align:center;font-size:1.1rem;"/></div>
                <div><label class="w-cfg-lbl">Card Title</label><input id="cm_why_c4_title" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Earn &amp; Glow"/></div>
              </div>
              <label class="w-cfg-lbl">Description</label><textarea id="cm_why_c4_desc" class="form-input" style="width:100%;padding:8px 10px;min-height:56px;margin-bottom:10px;" placeholder="Earn loyalty points on every order..."></textarea>
              <label class="w-cfg-lbl">Card Image URL</label>
              <div style="display:flex;gap:6px;margin-bottom:6px;"><input id="cm_why4_url" class="form-input" style="flex:1;padding:8px 10px;" oninput="cmWhyThumbRefresh(4)"/><label class="action-btn" style="cursor:pointer;padding:8px 10px;">Upload<input type="file" accept="image/*" style="display:none;" onchange="cmUploadWhy(4,this)"></label></div>
              <label class="w-cfg-lbl">Alt Text</label><input id="cm_why4_alt" class="form-input" style="width:100%;padding:8px 10px;margin-bottom:6px;"/>
              <div id="cms_why4_thumb" style="height:70px;border-radius:8px;background:#f4f5f7;background-size:cover;background-position:center;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ QUIZ BANNER ─ -->
      <div class="panel-tab-content" id="cms-quiz-banner">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Skin Quiz CTA Banner</h3><p>Rose-coloured banner prompting the skin quiz</p></div>
            <div class="toggle-wrap" style="border:none;padding:0;"><label class="toggle"><input type="checkbox" id="cm_qb_visible" checked><span class="toggle-slider"></span></label></div>
          </div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Eyebrow</label><input id="cm_qb_eyebrow" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Free · 60 Seconds · No Account Needed"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Line 1</label><input id="cm_qb_title_1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Not sure where to start?"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Line 2</label><input id="cm_qb_title_2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="We'll figure it out together."/></div>
            </div>
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Description</label><textarea id="cm_qb_desc" class="form-input" style="width:100%;padding:9px 12px;min-height:70px;" placeholder="Our 14-question Skin Quiz..."></textarea></div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <div><label class="w-cfg-lbl">CTA Button Text</label><input id="cm_qb_cta_text" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Start Skin Quiz — Free"/></div>
                <div><label class="w-cfg-lbl">CTA Link</label><input id="cm_qb_cta_link" class="form-input" style="width:100%;padding:9px 12px;" placeholder="/quiz"/></div>
              </div>
              <div><label class="w-cfg-lbl">Meta Text (below button)</label><input id="cm_qb_meta" class="form-input" style="width:100%;padding:9px 12px;" placeholder="60 secs · No account needed"/></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ DEAL OF THE DAY ─ -->
      <div class="panel-tab-content" id="cms-deal">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Deal of the Day</h3><p>Featured flash-sale product with countdown timer</p></div>
            <div class="toggle-wrap" style="border:none;padding:0;"><label class="toggle"><input type="checkbox" id="cm_deal_visible" checked><span class="toggle-slider"></span></label></div>
          </div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Badge Text</label><input id="cm_deal_badge" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Deal of the Day"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Headline</label><input id="cm_deal_headline" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Today's Featured Ritual"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Description</label><textarea id="cm_deal_desc" class="form-input" style="width:100%;padding:9px 12px;min-height:60px;"></textarea></div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <div><label class="w-cfg-lbl">Deal Price (₦)</label><input id="cm_deal_price" type="number" class="form-input" style="width:100%;padding:9px 12px;" placeholder="29000"/></div>
                <div><label class="w-cfg-lbl">Original Price (₦)</label><input id="cm_deal_orig" type="number" class="form-input" style="width:100%;padding:9px 12px;" placeholder="35000"/></div>
              </div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Units Remaining</label><input id="cm_deal_units" type="number" class="form-input" style="width:100%;padding:9px 12px;" placeholder="47"/></div>
              <div class="toggle-wrap" style="padding:10px 0;"><div class="toggle-info"><strong>Show Countdown Timer</strong><span>Display live HH:MM:SS timer</span></div><label class="toggle"><input type="checkbox" id="cm_deal_countdown" checked><span class="toggle-slider"></span></label></div>
            </div>
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Product ID <span class="w-cfg-hint" style="font-weight:400;">(links to catalog product)</span></label><input id="cm_deal_product_id" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Enter product ID"/></div>
              <p class="w-cfg-hint" style="margin-top:8px;">The product image, rating, and "Add to Cart" button are pulled automatically from the catalog when a Product ID is set.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ NEW DROPS ─ -->
      <div class="panel-tab-content" id="cms-new-drops">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>New Drops Section</h3><p>Latest arrivals grid — eyebrow and title</p></div>
            <div class="toggle-wrap" style="border:none;padding:0;"><label class="toggle"><input type="checkbox" id="cm_nd_visible" checked><span class="toggle-slider"></span></label></div>
          </div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><label class="w-cfg-lbl">Eyebrow / Kicker</label><input id="cm_nd_eyebrow" class="form-input" style="width:100%;padding:9px 12px;" placeholder="New This Quarter"/></div>
            <div><label class="w-cfg-lbl">Section Title</label><input id="cm_nd_title" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Latest Drops"/></div>
          </div>
          <div style="padding:0 22px 16px;">
            <label class="w-cfg-lbl" style="margin-top:4px;">Product IDs (comma-separated)</label>
            <input id="cm_nd_product_ids" class="form-input" style="width:100%;padding:9px 12px;" placeholder="1,2,3,4"/>
            <p class="w-cfg-hint" style="margin-top:6px;">Products are matched by ID from the catalog. Leave empty to show the 4 most recent products.</p>
          </div>
        </div>
      </div>

      <!-- ─ NEWSLETTER ─ -->
      <div class="panel-tab-content" id="cms-newsletter">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Newsletter Section</h3><p>Dark footer email capture section</p></div>
            <div class="toggle-wrap" style="border:none;padding:0;"><label class="toggle"><input type="checkbox" id="cm_nl_visible" checked><span class="toggle-slider"></span></label></div>
          </div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Eyebrow</label><input id="cm_nl_eyebrow" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Stay in the Know"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Heading Line 1</label><input id="cm_nl_heading_1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Get Skin Tips &amp;"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Heading Line 2</label><input id="cm_nl_heading_2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Exclusive Deals"/></div>
              <div><label class="w-cfg-lbl">Subtext</label><textarea id="cm_nl_subtext" class="form-input" style="width:100%;padding:9px 12px;min-height:70px;" placeholder="Join 50,000+ Kominhoo skin lovers..."></textarea></div>
            </div>
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Email Input Placeholder</label><input id="cm_nl_placeholder" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Enter your email address…"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Button Text</label><input id="cm_nl_btn" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Subscribe →"/></div>
              <div><label class="w-cfg-lbl">Fine Print</label><input id="cm_nl_note" class="form-input" style="width:100%;padding:9px 12px;" placeholder="No spam. Unsubscribe any time."/></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ QUIZ POPUP ─ -->
      <div class="panel-tab-content" id="cms-quiz-popup">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Welcome Quiz Popup</h3><p>Modal shown to new visitors 0.9 seconds after page load</p></div>
            <div class="toggle-wrap" style="border:none;padding:0;"><label class="toggle"><input type="checkbox" id="cm_qp_visible" checked><span class="toggle-slider"></span></label></div>
          </div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Top Banner — Bold 1</label><input id="cm_qp_banner_s1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Free"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Top Banner — Middle Text</label><input id="cm_qp_banner_mid" class="form-input" style="width:100%;padding:9px 12px;" placeholder="No account needed"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Top Banner — Bold 2</label><input id="cm_qp_banner_s2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="60 seconds"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Eyebrow</label><input id="cm_qp_eyebrow" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Kominhoo Skin Quiz"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Line 1</label><input id="cm_qp_title_1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Get Your"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Italic Word</label><input id="cm_qp_title_em" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Personalized"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Title Line 2</label><input id="cm_qp_title_2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Korean Skincare Routine"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Subtitle</label><input id="cm_qp_subtitle" class="form-input" style="width:100%;padding:9px 12px;" placeholder="in 60 seconds — matched to your skin type..."/></div>
              <div><label class="w-cfg-lbl">CTA Button Text</label><input id="cm_qp_cta" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Start Skin Quiz — Free"/></div>
            </div>
            <div>
              <div style="font-size:.85rem;font-weight:700;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid #f4f5f7;">Perks / Checkmarks (3 items)</div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Perk 1</label><input id="cm_qp_perk1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Tailored to your unique skin type &amp; concerns"/></div>
              <div style="margin-bottom:12px;"><label class="w-cfg-lbl">Perk 2</label><input id="cm_qp_perk2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Expert-backed K-beauty recommendations"/></div>
              <div><label class="w-cfg-lbl">Perk 3</label><input id="cm_qp_perk3" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Shop your complete routine instantly"/></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ SECTIONS ─ -->
      <div class="panel-tab-content" id="cms-sections">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Section Visibility</h3><p>Toggle homepage sections on or off</p></div></div>
          <div style="padding:0 22px;">
            <div class="toggle-wrap"><div class="toggle-info"><strong>Recommended For You</strong><span>Personalized product carousel</span></div><label class="toggle"><input type="checkbox" id="cm_vis_rec" checked><span class="toggle-slider"></span></label></div>
            <div class="toggle-wrap"><div class="toggle-info"><strong>New Drops Grid</strong><span>Latest product arrivals</span></div><label class="toggle"><input type="checkbox" id="cm_vis_nd" checked><span class="toggle-slider"></span></label></div>
            <div class="toggle-wrap"><div class="toggle-info"><strong>Bundle Kits</strong><span>Curated product bundles</span></div><label class="toggle"><input type="checkbox" id="cm_vis_bundles" checked><span class="toggle-slider"></span></label></div>
            <div class="toggle-wrap"><div class="toggle-info"><strong>Buying Guides</strong><span>Expert curation cards</span></div><label class="toggle"><input type="checkbox" id="cm_vis_guides" checked><span class="toggle-slider"></span></label></div>
            <div class="toggle-wrap"><div class="toggle-info"><strong>Community Gallery</strong><span>Customer photos grid</span></div><label class="toggle"><input type="checkbox" id="cm_vis_community" checked><span class="toggle-slider"></span></label></div>
            <div class="toggle-wrap"><div class="toggle-info"><strong>Subscription Plans</strong><span>Quarterly subscription cards</span></div><label class="toggle"><input type="checkbox" id="cm_vis_sub" checked><span class="toggle-slider"></span></label></div>
            <div class="toggle-wrap"><div class="toggle-info"><strong>Loyalty Tiers</strong><span>Loyalty program tier cards</span></div><label class="toggle"><input type="checkbox" id="cm_vis_loyalty" checked><span class="toggle-slider"></span></label></div>
          </div>
        </div>
        <!-- Community section text -->
        <div class="section-card">
          <div class="section-card-header"><div><h3>Community Gallery — Section Text</h3></div></div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
            <div><label class="w-cfg-lbl">Kicker</label><input id="cm_com_kicker" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Real Results"/></div>
            <div><label class="w-cfg-lbl">Heading Line 1</label><input id="cm_com_h1" class="form-input" style="width:100%;padding:9px 12px;" placeholder="The Kominhoo"/></div>
            <div><label class="w-cfg-lbl">Heading Line 2 (italic)</label><input id="cm_com_h2" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Community"/></div>
            <div><label class="w-cfg-lbl">Description</label><input id="cm_com_desc" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Real transformations from real customers..."/></div>
          </div>
        </div>
        <!-- Subscription section text -->
        <div class="section-card">
          <div class="section-card-header"><div><h3>Subscription Plans — Section Text</h3></div></div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
            <div><label class="w-cfg-lbl">Kicker</label><input id="cm_sub_kicker" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Quarterly Subscription"/></div>
            <div><label class="w-cfg-lbl">Heading <span class="w-cfg-hint" style="font-weight:400;">(comma = italic 2nd part)</span></label><input id="cm_sub_heading" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Your Skin Expert, On Autopilot"/></div>
            <div><label class="w-cfg-lbl">Description</label><textarea id="cm_sub_desc" class="form-input" style="width:100%;padding:9px 12px;min-height:60px;"></textarea></div>
          </div>
        </div>
        <!-- Loyalty section text -->
        <div class="section-card">
          <div class="section-card-header"><div><h3>Loyalty Tiers — Section Text</h3></div></div>
          <div style="padding:16px 22px;display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
            <div><label class="w-cfg-lbl">Kicker</label><input id="cm_loyalty_kicker" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Loyalty Program"/></div>
            <div><label class="w-cfg-lbl">Heading <span class="w-cfg-hint" style="font-weight:400;">(comma = italic 2nd part)</span></label><input id="cm_loyalty_heading" class="form-input" style="width:100%;padding:9px 12px;" placeholder="Glow More, Earn More"/></div>
            <div><label class="w-cfg-lbl">Description</label><textarea id="cm_loyalty_desc" class="form-input" style="width:100%;padding:9px 12px;min-height:60px;"></textarea></div>
          </div>
        </div>
      </div>

      <!-- ─ ANNOUNCEMENT BAR ─ -->
      <div class="panel-tab-content" id="cms-announcement">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Announcement Bar</h3><p>Top scrolling banner messages</p></div></div>
          <div style="padding:16px 22px;">
            <div style="display:flex;gap:16px;align-items:center;margin-bottom:16px;flex-wrap:wrap;">
              <label style="font-size:.85rem;font-weight:600;">Scroll Speed:</label>
              <select id="cm_ann_speed" style="padding:6px 8px;font-size:.85rem;border:1.5px solid #e8eaed;border-radius:8px;">
                <option value="static">Static</option>
                <option value="slow">Slow</option>
                <option value="normal">Normal</option>
                <option value="fast">Fast</option>
              </select>
              <label style="font-size:.85rem;display:flex;align-items:center;gap:6px;cursor:pointer;"><input type="checkbox" id="cm_ann_visible"/> Visible</label>
            </div>
            <div id="cm_ann_items" style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px;"></div>
            <button class="action-btn" onclick="cmAddAnnItem()" type="button">+ Add Announcement</button>
          </div>
        </div>
      </div>

    </div>

    <script>
      const CSRF = '{{ csrf_token() }}';
      const API_BASE = '{{ config("app.api_base_url") }}';
      const ADMIN_UPLOAD = '{{ route("admin.cms.media.upload") }}';
      const ADMIN_URL = '{{ url("/admin") }}';
      const ADMIN_PRODUCT_STORE_URL  = '{{ route("admin.products.store") }}';
      const ADMIN_PRODUCT_UPDATE_URL = '{{ url("admin/products") }}';
      const ADMIN_PRODUCT_DELETE_URL = '{{ url("admin/products") }}';
      const cmsContent = @json($cmsContent);

      // ── Tab switching ───────────────────────────────────────────────────────
      function cmSwitchTab(id, el) {
        document.querySelectorAll('#panel-content .panel-tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('#cmsTabBar .panel-tab').forEach(t => t.classList.remove('active'));
        const target = document.getElementById(id);
        if (target) target.classList.add('active');
        if (el) el.classList.add('active');
      }

      // ── Announcement bar ────────────────────────────────────────────────────
      function cmAddAnnItem(item) {
        item = item || {emoji: '✨', text: 'New announcement', link: ''};
        const container = document.getElementById('cm_ann_items');
        const wrap = document.createElement('div');
        wrap.className = 'ann-item';
        wrap.innerHTML =
          '<div class="ann-drag">&#8942;</div>' +
          '<div class="ann-emoji-pick"><input data-field="emoji" style="width:40px;border:none;background:transparent;font-size:1.1rem;" value="' + escapeHtml(item.emoji) + '" /></div>' +
          '<div class="ann-text-field"><input data-field="text" style="width:100%;padding:8px 10px;border:1px solid #e8eaed;border-radius:8px;" value="' + escapeHtml(item.text) + '" /></div>' +
          '<div class="ann-link-field"><input data-field="link" style="width:100%;padding:8px 10px;border:1px solid #e8eaed;border-radius:8px;" placeholder="Link URL (optional)" value="' + escapeHtml(item.link) + '" /></div>' +
          '<div style="flex-shrink:0;"><button class="action-btn danger" type="button">Remove</button></div>';
        wrap.querySelector('button').onclick = function() { wrap.remove(); };
        container.appendChild(wrap);
      }

      // ── Slide thumbnail preview ──────────────────────────────────────────────
      function cmSlideThumb(n) {
        var url = (document.getElementById('cm_slide'+n+'_url') || {}).value || '';
        var thumb = document.getElementById('cms_s'+n+'_thumb');
        if (!thumb) return;
        if (url) { thumb.style.backgroundImage = 'url('+url+')'; thumb.textContent = ''; }
        else { thumb.style.backgroundImage = ''; thumb.textContent = 'Preview'; }
      }
      function cmUploadSlide(n, inp) {
        var file = inp.files[0]; if (!file) return;
        uploadMedia(file, function(url) {
          var el = document.getElementById('cm_slide'+n+'_url');
          if (el) { el.value = url; cmSlideThumb(n); }
        });
      }

      // ── Why section image thumbnail preview ─────────────────────────────────
      function cmWhyThumbRefresh(n) {
        var url = (document.getElementById('cm_why'+n+'_url') || {}).value || '';
        var thumb = document.getElementById('cms_why'+n+'_thumb');
        if (thumb) thumb.style.backgroundImage = url ? 'url('+url+')' : '';
      }
      function cmUploadWhy(n, inp) {
        var file = inp.files[0]; if (!file) return;
        uploadMedia(file, function(url) {
          var el = document.getElementById('cm_why'+n+'_url');
          if (el) { el.value = url; cmWhyThumbRefresh(n); }
        });
      }

      // ── Populate all tabs from cmsContent ────────────────────────────────────
      function cmPopulateAll() {
        var c   = cmsContent.content || {};
        var hero = c.hero || {};
        var sv   = c.section_visibility || {};

        function setVal(id, val) { var el = document.getElementById(id); if (el) el.value = val || ''; }
        function setChk(id, val) { var el = document.getElementById(id); if (el) el.checked = (val !== false); }

        // Hero
        setChk('cm_hero_visible',  hero.visible);
        setVal('cm_hero_eyebrow',  hero.eyebrow);
        setVal('cm_hero_title_1',  hero.title_line_1);
        setVal('cm_hero_title_2',  hero.title_line_2);
        setVal('cm_hero_title_3',  hero.title_line_3);
        setVal('cm_hero_desc',     hero.description);
        setVal('cm_hero_cta_text', hero.primary_cta_text);
        setVal('cm_hero_cta_link', hero.primary_cta_link);
        setVal('cm_hero_cta2_text',hero.secondary_cta_text);
        setVal('cm_hero_cta2_link',hero.secondary_cta_link);
        setVal('cm_stat_1_num',    hero.stat_1_num);
        setVal('cm_stat_1_label',  hero.stat_1_label);
        setVal('cm_stat_2_num',    hero.stat_2_num);
        setVal('cm_stat_2_label',  hero.stat_2_label);
        setVal('cm_stat_3_num',    hero.stat_3_num);
        setVal('cm_stat_3_label',  hero.stat_3_label);

        // Slides (from media library)
        var mediaBySlot = {};
        ((c.media || {}).library || []).forEach(function(m) { if (m.slot) mediaBySlot[m.slot] = m; });
        [1,2,3].forEach(function(n) {
          var m = mediaBySlot['hero_slide_'+n] || {};
          setVal('cm_slide'+n+'_url', m.url);
          setVal('cm_slide'+n+'_alt', m.alt);
          cmSlideThumb(n);
        });

        // Slide pins
        setVal('cm_s1_pa_name',   hero.slide_1_pin_a_name);   setVal('cm_s1_pa_detail', hero.slide_1_pin_a_detail);
        setVal('cm_s1_pb_name',   hero.slide_1_pin_b_name);   setVal('cm_s1_pb_detail', hero.slide_1_pin_b_detail);
        setVal('cm_s2_pa_name',   hero.slide_2_pin_a_name);   setVal('cm_s2_pa_detail', hero.slide_2_pin_a_detail);
        setVal('cm_s2_pb_name',   hero.slide_2_pin_b_name);   setVal('cm_s2_pb_detail', hero.slide_2_pin_b_detail);
        setVal('cm_s3_pa_name',   hero.slide_3_pin_a_name);   setVal('cm_s3_pa_detail', hero.slide_3_pin_a_detail);
        setVal('cm_s3_pb_name',   hero.slide_3_pin_b_name);   setVal('cm_s3_pb_detail', hero.slide_3_pin_b_detail);

        // Why section
        var why = c.why_section || {};
        setChk('cm_why_visible',   sv.why_section);
        setVal('cm_why_kicker',    why.kicker);
        setVal('cm_why_heading_1', why.heading_line_1);
        setVal('cm_why_heading_2', why.heading_line_2);
        setVal('cm_why_lead',      why.lead);
        var whyCards = why.cards || [];
        [1,2,3,4].forEach(function(n) {
          var card = whyCards[n-1] || {};
          setVal('cm_why_c'+n+'_icon',  card.icon);
          setVal('cm_why_c'+n+'_title', card.title);
          setVal('cm_why_c'+n+'_desc',  card.desc);
          var wm = mediaBySlot['why_'+n] || {};
          setVal('cm_why'+n+'_url', wm.url);
          setVal('cm_why'+n+'_alt', wm.alt);
          cmWhyThumbRefresh(n);
        });

        // Quiz Banner
        var qb = c.quiz_cta_banner || {};
        setChk('cm_qb_visible',  sv.quiz_cta_banner);
        setVal('cm_qb_eyebrow',  qb.eyebrow);
        setVal('cm_qb_title_1',  qb.title_line_1);
        setVal('cm_qb_title_2',  qb.title_line_2);
        setVal('cm_qb_desc',     qb.description);
        setVal('cm_qb_cta_text', qb.cta_text);
        setVal('cm_qb_cta_link', qb.cta_link);
        setVal('cm_qb_meta',     qb.meta);

        // Deal of the Day
        var deal = c.deal_of_the_day || {};
        setChk('cm_deal_visible',    deal.visible);
        setVal('cm_deal_badge',      deal.badge);
        setVal('cm_deal_headline',   deal.headline);
        setVal('cm_deal_desc',       deal.description);
        var dpEl = document.getElementById('cm_deal_price'); if (dpEl) dpEl.value = deal.deal_price || '';
        var doEl = document.getElementById('cm_deal_orig');  if (doEl) doEl.value = deal.original_price || '';
        var duEl = document.getElementById('cm_deal_units'); if (duEl) duEl.value = deal.units_remaining || '';
        setChk('cm_deal_countdown',  deal.show_countdown !== false);
        setVal('cm_deal_product_id', deal.product_id);

        // New Drops
        var nd = c.new_drops || {};
        setChk('cm_nd_visible', sv.new_drops_grid);
        setVal('cm_nd_eyebrow', nd.eyebrow);
        setVal('cm_nd_title',   nd.title);
        var ndIds = document.getElementById('cm_nd_product_ids');
        if (ndIds) ndIds.value = (nd.product_ids || []).join(',');

        // Newsletter
        var nl = c.newsletter_section || {};
        setChk('cm_nl_visible',     sv.newsletter_section);
        setVal('cm_nl_eyebrow',     nl.eyebrow);
        setVal('cm_nl_heading_1',   nl.heading_line_1);
        setVal('cm_nl_heading_2',   nl.heading_line_2);
        setVal('cm_nl_subtext',     nl.subtext);
        setVal('cm_nl_placeholder', nl.input_placeholder);
        setVal('cm_nl_btn',         nl.button_text);
        setVal('cm_nl_note',        nl.note);

        // Quiz Popup
        var qp = c.quiz_popup || {};
        setChk('cm_qp_visible',    sv.welcome_quiz_popup);
        setVal('cm_qp_banner_s1',  qp.banner_strong_1);
        setVal('cm_qp_banner_mid', qp.banner_text);
        setVal('cm_qp_banner_s2',  qp.banner_strong_2);
        setVal('cm_qp_eyebrow',    qp.eyebrow);
        setVal('cm_qp_title_1',    qp.title_line_1);
        setVal('cm_qp_title_em',   qp.title_em);
        setVal('cm_qp_title_2',    qp.title_line_2);
        setVal('cm_qp_subtitle',   qp.subtitle);
        setVal('cm_qp_cta',        qp.cta_text);
        var perks = qp.perks || [];
        setVal('cm_qp_perk1', perks[0]); setVal('cm_qp_perk2', perks[1]); setVal('cm_qp_perk3', perks[2]);

        // Section visibility
        setChk('cm_vis_rec',       sv.recommended_for_you);
        setChk('cm_vis_nd',        sv.new_drops_grid);
        setChk('cm_vis_bundles',   sv.bundle_kits);
        setChk('cm_vis_guides',    sv.buying_guides);
        setChk('cm_vis_community', sv.community_gallery);
        setChk('cm_vis_sub',       sv.subscription_cta);
        setChk('cm_vis_loyalty',   sv.loyalty_tiers);

        // Community / Subscription / Loyalty section text
        var com = c.community_section || {};
        setVal('cm_com_kicker', com.kicker); setVal('cm_com_h1', com.heading_line_1);
        setVal('cm_com_h2', com.heading_line_2); setVal('cm_com_desc', com.description);
        var sub = c.subscription_section || {};
        setVal('cm_sub_kicker', sub.kicker); setVal('cm_sub_heading', sub.heading); setVal('cm_sub_desc', sub.description);
        var loyalty = c.loyalty_section || {};
        setVal('cm_loyalty_kicker', loyalty.kicker); setVal('cm_loyalty_heading', loyalty.heading); setVal('cm_loyalty_desc', loyalty.description);

        // Announcement bar
        var annContainer = document.getElementById('cm_ann_items');
        annContainer.innerHTML = '';
        var ann = c.announcement_bar || {};
        var speedEl = document.getElementById('cm_ann_speed'); if (speedEl) speedEl.value = ann.speed || 'normal';
        var visEl   = document.getElementById('cm_ann_visible'); if (visEl) visEl.checked = (ann.visible !== false);
        var annItems = ann.items || [];
        if (annItems.length === 0) cmAddAnnItem({emoji:'🚀', text:'Free shipping on orders over ₦50,000', link:''});
        else annItems.forEach(function(it) { cmAddAnnItem(it); });
      }

      // ── Collect all fields into content object ────────────────────────────────
      function cmCollect() {
        var c = JSON.parse(JSON.stringify(cmsContent.content || {}));
        function getVal(id) { var el = document.getElementById(id); return el ? el.value.trim() : ''; }
        function getChk(id) { var el = document.getElementById(id); return el ? el.checked : true; }

        c.hero = c.hero || {};
        c.hero.visible            = getChk('cm_hero_visible');
        c.hero.eyebrow            = getVal('cm_hero_eyebrow');
        c.hero.title_line_1       = getVal('cm_hero_title_1');
        c.hero.title_line_2       = getVal('cm_hero_title_2');
        c.hero.title_line_3       = getVal('cm_hero_title_3');
        c.hero.description        = getVal('cm_hero_desc');
        c.hero.primary_cta_text   = getVal('cm_hero_cta_text');
        c.hero.primary_cta_link   = getVal('cm_hero_cta_link');
        c.hero.secondary_cta_text = getVal('cm_hero_cta2_text');
        c.hero.secondary_cta_link = getVal('cm_hero_cta2_link');
        c.hero.stat_1_num         = getVal('cm_stat_1_num');   c.hero.stat_1_label = getVal('cm_stat_1_label');
        c.hero.stat_2_num         = getVal('cm_stat_2_num');   c.hero.stat_2_label = getVal('cm_stat_2_label');
        c.hero.stat_3_num         = getVal('cm_stat_3_num');   c.hero.stat_3_label = getVal('cm_stat_3_label');
        c.hero.slide_1_pin_a_name   = getVal('cm_s1_pa_name');   c.hero.slide_1_pin_a_detail = getVal('cm_s1_pa_detail');
        c.hero.slide_1_pin_b_name   = getVal('cm_s1_pb_name');   c.hero.slide_1_pin_b_detail = getVal('cm_s1_pb_detail');
        c.hero.slide_2_pin_a_name   = getVal('cm_s2_pa_name');   c.hero.slide_2_pin_a_detail = getVal('cm_s2_pa_detail');
        c.hero.slide_2_pin_b_name   = getVal('cm_s2_pb_name');   c.hero.slide_2_pin_b_detail = getVal('cm_s2_pb_detail');
        c.hero.slide_3_pin_a_name   = getVal('cm_s3_pa_name');   c.hero.slide_3_pin_a_detail = getVal('cm_s3_pa_detail');
        c.hero.slide_3_pin_b_name   = getVal('cm_s3_pb_name');   c.hero.slide_3_pin_b_detail = getVal('cm_s3_pb_detail');

        // Media library — slides + why images (preserve other slots)
        var existingMedia = (c.media || {}).library || [];
        var managedSlots  = ['hero_slide_1','hero_slide_2','hero_slide_3','why_1','why_2','why_3','why_4'];
        var otherMedia    = existingMedia.filter(function(m) { return managedSlots.indexOf(m.slot) === -1; });
        var newMedia = [];
        [1,2,3].forEach(function(n) {
          var url = getVal('cm_slide'+n+'_url'), alt = getVal('cm_slide'+n+'_alt');
          if (url) newMedia.push({slot:'hero_slide_'+n, url:url, alt:alt, enabled:true});
        });
        [1,2,3,4].forEach(function(n) {
          var url = getVal('cm_why'+n+'_url'), alt = getVal('cm_why'+n+'_alt');
          if (url) newMedia.push({slot:'why_'+n, url:url, alt:alt, enabled:true});
        });
        c.media = { library: otherMedia.concat(newMedia) };

        c.why_section = {
          kicker: getVal('cm_why_kicker'), heading_line_1: getVal('cm_why_heading_1'), heading_line_2: getVal('cm_why_heading_2'), lead: getVal('cm_why_lead'),
          cards: [1,2,3,4].map(function(n) { return { icon: getVal('cm_why_c'+n+'_icon'), title: getVal('cm_why_c'+n+'_title'), desc: getVal('cm_why_c'+n+'_desc') }; })
        };

        c.quiz_cta_banner = {
          eyebrow: getVal('cm_qb_eyebrow'), title_line_1: getVal('cm_qb_title_1'), title_line_2: getVal('cm_qb_title_2'),
          description: getVal('cm_qb_desc'), cta_text: getVal('cm_qb_cta_text'), cta_link: getVal('cm_qb_cta_link'), meta: getVal('cm_qb_meta')
        };

        c.deal_of_the_day = {
          visible: getChk('cm_deal_visible'), badge: getVal('cm_deal_badge'), headline: getVal('cm_deal_headline'),
          description: getVal('cm_deal_desc'), deal_price: parseFloat(getVal('cm_deal_price')) || 0,
          original_price: parseFloat(getVal('cm_deal_orig')) || 0, units_remaining: parseInt(getVal('cm_deal_units')) || 0,
          show_countdown: getChk('cm_deal_countdown'), product_id: getVal('cm_deal_product_id') || null
        };

        c.new_drops = {
          eyebrow: getVal('cm_nd_eyebrow'), title: getVal('cm_nd_title'),
          product_ids: getVal('cm_nd_product_ids').split(',').map(function(s){ return s.trim(); }).filter(Boolean)
        };

        c.newsletter_section = {
          eyebrow: getVal('cm_nl_eyebrow'), heading_line_1: getVal('cm_nl_heading_1'), heading_line_2: getVal('cm_nl_heading_2'),
          subtext: getVal('cm_nl_subtext'), input_placeholder: getVal('cm_nl_placeholder'), button_text: getVal('cm_nl_btn'), note: getVal('cm_nl_note')
        };

        c.quiz_popup = {
          banner_strong_1: getVal('cm_qp_banner_s1'), banner_text: getVal('cm_qp_banner_mid'), banner_strong_2: getVal('cm_qp_banner_s2'),
          eyebrow: getVal('cm_qp_eyebrow'), title_line_1: getVal('cm_qp_title_1'), title_em: getVal('cm_qp_title_em'),
          title_line_2: getVal('cm_qp_title_2'), subtitle: getVal('cm_qp_subtitle'), cta_text: getVal('cm_qp_cta'),
          perks: [getVal('cm_qp_perk1'), getVal('cm_qp_perk2'), getVal('cm_qp_perk3')].filter(Boolean)
        };

        c.section_visibility = {
          quiz_cta_banner:     getChk('cm_qb_visible'),
          why_section:         getChk('cm_why_visible'),
          recommended_for_you: getChk('cm_vis_rec'),
          new_drops_grid:      getChk('cm_vis_nd'),
          bundle_kits:         getChk('cm_vis_bundles'),
          buying_guides:       getChk('cm_vis_guides'),
          community_gallery:   getChk('cm_vis_community'),
          subscription_cta:    getChk('cm_vis_sub'),
          loyalty_tiers:       getChk('cm_vis_loyalty'),
          newsletter_section:  getChk('cm_nl_visible'),
          welcome_quiz_popup:  getChk('cm_qp_visible')
        };

        c.community_section    = { kicker: getVal('cm_com_kicker'), heading_line_1: getVal('cm_com_h1'), heading_line_2: getVal('cm_com_h2'), description: getVal('cm_com_desc') };
        c.subscription_section = { kicker: getVal('cm_sub_kicker'), heading: getVal('cm_sub_heading'), description: getVal('cm_sub_desc') };
        c.loyalty_section      = { kicker: getVal('cm_loyalty_kicker'), heading: getVal('cm_loyalty_heading'), description: getVal('cm_loyalty_desc') };

        // Announcement bar
        var annContainer = document.getElementById('cm_ann_items');
        var annItems = Array.from(annContainer.children).map(function(node) {
          return {
            emoji: (node.querySelector('input[data-field="emoji"]') || {}).value || '',
            text:  (node.querySelector('input[data-field="text"]')  || {}).value || '',
            link:  (node.querySelector('input[data-field="link"]')  || {}).value || ''
          };
        });
        c.announcement_bar = {
          speed:   (document.getElementById('cm_ann_speed')   || {}).value || 'normal',
          visible: (document.getElementById('cm_ann_visible') || {}).checked !== false,
          items:   annItems
        };

        return c;
      }

      // ── Save ────────────────────────────────────────────────────────────────
      function saveContentManager() {
        var content = cmCollect();
        cmsContent.content = content;
        fetch(ADMIN_URL + '/cms/content', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
          body: JSON.stringify({ content: content })
        }).then(function(r) { return r.json(); }).then(function(resp) {
          if (resp.success) {
            if (resp.content) cmsContent.content = resp.content;
            showToast('✅', 'Content saved successfully');
          } else {
            showToast('⚠️', resp.message || 'Save failed');
          }
        }).catch(function() { showToast('⚠️', 'Save failed — check connection'); });
      }

      // ── Helpers ─────────────────────────────────────────────────────────────
      function uploadMedia(file, cb) {
        var fd = new FormData(); fd.append('file', file);
        fetch(ADMIN_UPLOAD, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: fd, credentials: 'include' })
          .then(function(r) { return r.json(); })
          .then(function(resp) {
            if (resp && resp.success && resp.url) { if (typeof cb === 'function') cb(resp.url); }
            else showToast('⚠️', (resp && resp.message) || 'Upload failed');
          })
          .catch(function() { showToast('⚠️', 'Upload failed'); });
      }

      function escapeHtml(s) {
        if (!s) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
      }

      document.addEventListener('DOMContentLoaded', function() {
        cmPopulateAll();
      });
    </script>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- PANEL: Blog                                        -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-blog">
      <div class="panel-header">
        <div><h1>Blog</h1><p>Create, edit, and publish posts for the public blog page</p></div>
        <div style="display:flex;gap:8px;align-items:center;">
          <button class="action-btn primary" onclick="openBlogModal()">+ New Post</button>
        </div>
      </div>

      <div class="section-card" style="margin-bottom:0;">
        <div class="section-card-header">
          <div><h3>Posts</h3><p>{{ count($blogPosts) }} total</p></div>
        </div>
        <div style="padding:0;overflow:auto;">
          <table class="data-table" id="blogPostsTable" style="min-width:860px;">
            <thead>
              <tr>
                <th>Title</th>
                <th>Tag</th>
                <th>Status</th>
                <th>Published</th>
                <th style="text-align:right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($blogPosts as $bp)
                <tr>
                  <td style="min-width:360px;">
                    <strong>{{ $bp->title }}</strong>
                    <div style="font-size:.75rem;color:rgba(10,10,10,.45);margin-top:2px;">/blog/{{ $bp->slug }}</div>
                  </td>
                  <td style="font-size:.82rem;">{{ $bp->tag ?: '—' }}</td>
                  <td>
                    @if($bp->is_published)
                      <span class="status-badge active">Published</span>
                    @else
                      <span class="status-badge pending">Draft</span>
                    @endif
                    @if($bp->is_featured)
                      <span class="tag-chip" style="margin-left:6px;background:#0a0a0a;color:#fff;">Featured</span>
                    @endif
                  </td>
                  <td style="font-size:.82rem;color:rgba(10,10,10,.55);">
                    {{ $bp->published_at ? $bp->published_at->format('M d, Y') : '—' }}
                  </td>
                  <td style="text-align:right;white-space:nowrap;">
                    <a class="action-btn" style="padding:5px 10px;text-decoration:none;" href="{{ $bp->is_published ? route('blog.show', $bp->slug) : '#' }}" target="_blank" @if(!$bp->is_published) onclick="event.preventDefault();showToast('⚠️','Publish this post to preview it on the site.');" @endif>Preview</a>
                    <button class="action-btn edit" style="padding:5px 10px;" onclick="editBlogPost({{ $bp->id }})">Edit</button>
                    <button class="action-btn danger" style="padding:5px 10px;" onclick="deleteBlogPost({{ $bp->id }})">Delete</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" style="padding:18px 22px;color:rgba(10,10,10,.55);">No blog posts yet. Click <strong>New Post</strong> to create your first one.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Products                                  -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-products">
      <div class="panel-header">
        <div><h1>Products</h1><p>Manage your catalog and Skin OS tags</p></div>
        <button class="action-btn primary" onclick="openAddModal()">+ Add New Product</button>
      </div>
      <div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
        <div class="admin-search-bar" style="width:280px;background:#fff;border-color:#e8eaed;">🔍 &nbsp; <input style="border:none;background:transparent;outline:none;font-size:.85rem;color:rgba(10,10,10,.7);flex:1;" placeholder="Search products…" oninput="filterProducts(this.value)" /></div>
        <select class="form-input" style="width:150px;padding:8px 12px;font-size:.82rem;"><option>All Categories</option><option>Cleanser</option><option>Toner</option><option>Serum</option><option>Moisturizer</option><option>Sunscreen</option><option>Mask</option></select>
        <select class="form-input" style="width:140px;padding:8px 12px;font-size:.82rem;"><option>All Skin Types</option><option>Oily</option><option>Dry</option><option>Combination</option><option>Sensitive</option></select>
        <select class="form-input" style="width:130px;padding:8px 12px;font-size:.82rem;"><option>All Status</option><option>In Stock</option><option>Low Stock</option><option>Out of Stock</option></select>
      </div>
      <div class="section-card" style="margin-bottom:0;">
        <table class="data-table" id="adminProductsTable">
          <thead><tr><th>Product</th><th>Category</th><th>Skin OS Tags</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody id="adminProductsTbody"></tbody>
        </table>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Bundle Kits                               -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-bundles">
      <div class="panel-header">
        <div><h1>Bundle Kits</h1><p>Create and manage curated product bundles sold in the shop</p></div>
        <button class="action-btn primary" onclick="openBundleEditor()">+ Create Bundle</button>
      </div>
      <div class="section-card">
        <div class="section-card-header"><div><h3>Active Bundles</h3><p id="bundlesCountLabel"></p></div></div>
        <div class="bundle-builder" id="bundleAdminList"></div>
      </div>
    </div>

    <!-- Bundle Editor Modal -->
    <div class="modal-overlay" id="bundleEditorOverlay" onclick="if(event.target===this)closeBundleEditor()">
      <div class="add-modal" style="max-width:660px;">
        <div class="add-modal-header">
          <h2 id="bundleEditorHeading">Create Bundle Kit</h2>
          <button onclick="closeBundleEditor()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:rgba(10,10,10,.4);line-height:1;">✕</button>
        </div>
        <div class="add-modal-body" style="display:flex;flex-direction:column;gap:14px;">
          <input type="hidden" id="bundleEditorId">
          <div class="form-grid ratio-13-07 gap-14">
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Bundle Name</label>
              <input class="form-input" id="bundleEditorName" placeholder="e.g. Acne Starter Kit" style="width:100%;padding:9px 12px;font-size:.88rem;">
            </div>
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Badge Tag</label>
              <input class="form-input" id="bundleEditorTag" placeholder="e.g. Clear Skin" style="width:100%;padding:9px 12px;font-size:.88rem;">
            </div>
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Description</label>
            <input class="form-input" id="bundleEditorDesc" placeholder="Short description of what this bundle is for…" style="width:100%;padding:9px 12px;font-size:.88rem;">
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Cover Image URL</label>
            <input class="form-input" id="bundleEditorImage" placeholder="https://images.unsplash.com/…" style="width:100%;padding:9px 12px;font-size:.88rem;">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Bundle Price (₦)</label>
              <input class="form-input" id="bundleEditorPrice" type="number" min="0" placeholder="48500" style="width:100%;padding:9px 12px;font-size:.88rem;">
            </div>
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Original Price (₦) <span style="font-weight:400;color:rgba(10,10,10,.4);">for strikethrough</span></label>
              <input class="form-input" id="bundleEditorOrigPrice" type="number" min="0" placeholder="63500" style="width:100%;padding:9px 12px;font-size:.88rem;">
            </div>
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:8px;">Products in this Bundle <span id="bundleProductCount" style="font-weight:400;color:rgba(10,10,10,.4);"></span></label>
            <div id="bundleProductCheckboxes" class="choice-grid"></div>
          </div>
        </div>
        <div class="add-modal-footer">
          <button class="action-btn" onclick="closeBundleEditor()">Cancel</button>
          <button class="action-btn primary" onclick="saveBundleEditor()">Save Bundle</button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Buying Guides                             -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-guides">
      <div class="panel-header">
        <div><h1>Buying Guides</h1><p>Create and manage expert buying guides with curated product selections</p></div>
        <button class="action-btn primary" onclick="openGuideEditor()">+ Create Guide</button>
      </div>
      <div class="section-card">
        <div class="section-card-header"><div><h3>Active Guides</h3><p id="guidesCountLabel"></p></div></div>
        <div class="bundle-builder" id="guideAdminList"></div>
      </div>
    </div>

    <!-- Guide Editor Modal -->
    <div class="modal-overlay" id="guideEditorOverlay" onclick="if(event.target===this)closeGuideEditor()">
      <div class="add-modal" style="max-width:620px;">
        <div class="add-modal-header">
          <h2 id="guideEditorHeading">Create Buying Guide</h2>
          <button onclick="closeGuideEditor()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:rgba(10,10,10,.4);line-height:1;">✕</button>
        </div>
        <div class="add-modal-body" style="display:flex;flex-direction:column;gap:14px;">
          <input type="hidden" id="guideEditorId">
          <div style="display:grid;grid-template-columns:1fr 120px;gap:12px;">
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Title</label>
              <input class="form-input" id="guideEditorTitleInput" placeholder="e.g. Acne Solutions" style="width:100%;padding:9px 12px;font-size:.88rem;">
            </div>
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Icon</label>
              <input class="form-input" id="guideEditorIcon" placeholder="🧴" style="width:100%;padding:9px 12px;font-size:1.3rem;text-align:center;">
            </div>
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Description</label>
            <input class="form-input" id="guideEditorDesc" placeholder="Short description of what this guide covers…" style="width:100%;padding:9px 12px;font-size:.88rem;">
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Cover Image URL</label>
            <input class="form-input" id="guideEditorImage" placeholder="https://images.unsplash.com/…" style="width:100%;padding:9px 12px;font-size:.88rem;">
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:8px;">Products in this Guide <span id="guideProductCount" style="font-weight:400;color:rgba(10,10,10,.4);"></span></label>
            <div id="guideProductCheckboxes" style="display:grid;grid-template-columns:1fr 1fr;gap:5px;max-height:260px;overflow-y:auto;padding:2px;"></div>
          </div>
        </div>
        <div class="add-modal-footer">
          <button class="action-btn" onclick="closeGuideEditor()">Cancel</button>
          <button class="action-btn primary" onclick="saveGuideEditor()">Save Guide</button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Routines                                  -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-routines">
      <div class="panel-header">
        <div><h1>Routine Builder</h1><p>View user routine activity, override recommendations, or create manual routines for VIP clients</p></div>
        <button class="action-btn primary" onclick="document.getElementById('routineEditorOverlay').classList.add('open')">+ Create Manual Routine</button>
      </div>
      <div class="kpi-grid">
        <div class="kpi-card lime"><span class="kpi-icon">📋</span><div class="kpi-label">Total Logs</div><div class="kpi-value" id="rt-kpi-total">—</div><div class="kpi-sub">All time completions</div></div>
        <div class="kpi-card blue"><span class="kpi-icon">👤</span><div class="kpi-label">Active Users</div><div class="kpi-value" id="rt-kpi-users">—</div><div class="kpi-sub">Tracking routines</div></div>
        <div class="kpi-card amber"><span class="kpi-icon">📅</span><div class="kpi-label">This Month</div><div class="kpi-value" id="rt-kpi-month">—</div><div class="kpi-sub">Logs so far</div></div>
        <div class="kpi-card red"><span class="kpi-icon">🔄</span><div class="kpi-label">Avg Completion</div><div class="kpi-value" id="rt-kpi-completion">—</div><div class="kpi-sub">Steps marked done</div></div>
      </div>
      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'rtab-activity')">User Activity</div>
        <div class="panel-tab" onclick="switchTab(this,'rtab-manual')">Manual / VIP</div>
        <div class="panel-tab" onclick="switchTab(this,'rtab-overrides')">Overrides</div>
      </div>
      <div class="panel-tab-content active" id="rtab-activity">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Routine Consistency Leaderboard</h3><p>Top users by days logged this month</p></div><button class="action-btn edit" onclick="loadRoutineStats()">↻ Refresh</button></div>
          <table class="data-table"><thead><tr><th>#</th><th>Customer</th><th>Days Logged</th><th>Pts Earned</th><th></th></tr></thead>
          <tbody id="rt-leaderboard-body">
            <tr><td colspan="4" style="text-align:center;padding:24px;color:rgba(10,10,10,.4)">Loading…</td></tr>
          </tbody></table>
        </div>
      </div>
      <div class="panel-tab-content" id="rtab-manual">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Manual Routines</h3><p>Hand-curated for VIP / Luxe Luminary clients</p></div><button class="action-btn primary" onclick="document.getElementById('routineEditorOverlay').classList.add('open')">+ New Routine</button></div>
          <div style="padding:16px 22px;" id="manualRoutinesList">
            <div class="routine-card"><div class="routine-card-avatar" style="background:#fef3c7;color:#92400e;">AO</div><div class="routine-card-body"><div class="routine-card-name">Adaeze Okonkwo — Hyperpigmentation Recovery</div><div class="routine-card-meta">Combination skin · 👑 VIP Manual</div><div class="routine-steps"><span class="routine-step-chip"><span class="step-num">1</span> Cleanser</span><span class="routine-step-chip"><span class="step-num">2</span> Toner</span><span class="routine-step-chip"><span class="step-num">3</span> Vitamin C Serum</span><span class="routine-step-chip"><span class="step-num">4</span> Niacinamide</span><span class="routine-step-chip"><span class="step-num">5</span> Moisturiser</span><span class="routine-step-chip"><span class="step-num">6</span> SPF50</span></div><div class="routine-card-actions"><button class="action-btn edit" onclick="document.getElementById('routineEditorOverlay').classList.add('open')">✏️ Edit</button><button class="action-btn tag-btn">📤 Send to Client</button><button class="action-btn danger" onclick="if(confirm('Delete routine?'))this.closest('.routine-card').remove()">🗑️</button></div></div></div>
            <div class="routine-card"><div class="routine-card-avatar" style="background:#ede9fe;color:#5b21b6;">NK</div><div class="routine-card-body"><div class="routine-card-name">Ngozi Kalu — Acne-Prone AM Protocol</div><div class="routine-card-meta">Oily skin · 👑 VIP Manual</div><div class="routine-steps"><span class="routine-step-chip"><span class="step-num">1</span> Salicylic Cleanser</span><span class="routine-step-chip"><span class="step-num">2</span> BHA Toner</span><span class="routine-step-chip"><span class="step-num">3</span> Niacinamide Serum</span><span class="routine-step-chip"><span class="step-num">4</span> Oil-Free Moisturiser</span><span class="routine-step-chip"><span class="step-num">5</span> Mattifying SPF</span></div><div class="routine-card-actions"><button class="action-btn edit" onclick="document.getElementById('routineEditorOverlay').classList.add('open')">✏️ Edit</button><button class="action-btn tag-btn">📤 Send to Client</button><button class="action-btn danger" onclick="if(confirm('Delete routine?'))this.closest('.routine-card').remove()">🗑️</button></div></div></div>
          </div>
        </div>
      </div>
      <div class="panel-tab-content" id="rtab-overrides">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Admin Overrides</h3><p>Products you've manually swapped in AI-generated routines</p></div></div>
          <table class="data-table"><thead><tr><th>Customer</th><th>Skin Type</th><th>Original Rec.</th><th>Replaced With</th><th>Date</th><th>Admin</th><th></th></tr></thead><tbody><tr><td><strong>Chidinma Eze</strong><br><span style="font-size:.75rem;color:rgba(10,10,10,.45)">Dry · Redness concern</span></td><td><span class="tag-chip skin">Dry</span></td><td>CeraVe Moisturising Cream</td><td><span style="font-weight:600;color:var(--black);">Laneige Water Cream</span></td><td style="font-size:.78rem;color:rgba(10,10,10,.45)">Apr 14, 2026</td><td style="font-size:.78rem;">Super Admin</td><td><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('tr').remove()">Revert</button></td></tr><tr><td><strong>Yetunde Adeyemi</strong><br><span style="font-size:.75rem;color:rgba(10,10,10,.45)">Oily · Acne concern</span></td><td><span class="tag-chip skin">Oily</span></td><td>COSRX Snail Mucin</td><td><span style="font-weight:600;color:var(--black);">COSRX BHA Blackhead Power</span></td><td style="font-size:.78rem;color:rgba(10,10,10,.45)">Apr 12, 2026</td><td style="font-size:.78rem;">Super Admin</td><td><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('tr').remove()">Revert</button></td></tr></tbody></table>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Inventory                                 -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-inventory">
      <div class="panel-header"><div><h1>Inventory</h1><p>Stock levels and reorder management</p></div><div style="display:flex;gap:8px;"><button class="action-btn edit" onclick="invExportCsv()">📥 Export CSV</button><button class="action-btn primary" onclick="showToast('✅','All stock levels saved!')">💾 Save All Changes</button></div></div>
      @if($lowStockCount > 0)
      @php
        $lowStockSummary = collect($lowStockItems)->map(fn($p) => ($p['name'] ?? 'Product') . ' (' . ($p['stock'] ?? $p['stock_quantity'] ?? 0) . ' units)')->take(3)->implode(' · ');
        if($lowStockCount > 3) $lowStockSummary .= ' + ' . ($lowStockCount - 3) . ' more';
      @endphp
      <div class="alert-banner"><div class="alert-banner-icon">⚠️</div><div class="alert-banner-text"><strong>{{ $lowStockCount }} product{{ $lowStockCount !== 1 ? 's are' : ' is' }} running low on stock</strong><span>{{ $lowStockSummary }}</span></div><button class="action-btn" style="border-color:#fed7aa;color:#c2410c;background:transparent;white-space:nowrap;" onclick="filterInventory('low')">View Low Stock →</button></div>
      @endif
      <div class="mini-stats"><div class="mini-stat"><div class="mini-stat-val">{{ count($catalogProducts) }}</div><div class="mini-stat-label">Total SKUs</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:#16a34a;">{{ $inStockCount }}</div><div class="mini-stat-label">In Stock</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:#f59e0b;">{{ $lowStockCount }}</div><div class="mini-stat-label">Low Stock</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:var(--red);">{{ $outOfStockCount }}</div><div class="mini-stat-label">Out of Stock</div></div></div>
      <div class="section-card" style="margin-bottom:0;">
        <div class="section-card-header"><div><h3>Stock Management</h3><p>Edit stock levels inline · changes apply on click</p></div><div style="display:flex;gap:8px;"><input type="text" class="form-input" placeholder="🔍 Search products…" style="width:200px;padding:7px 12px;font-size:.82rem;" oninput="filterInventory(this.value)" /><select class="form-input" style="width:130px;padding:7px 12px;font-size:.82rem;"><option>All Status</option><option>In Stock</option><option>Low Stock</option><option>Out of Stock</option></select></div></div>
        <div style="padding:10px 22px 0;display:flex;align-items:center;gap:8px;"><span style="font-size:.75rem;color:rgba(10,10,10,.45);">Batch &amp; expiry tracking is</span><span class="status-badge active" style="font-size:.7rem;">● Enabled</span><span style="font-size:.75rem;color:rgba(10,10,10,.45);margin-left:4px;">— Critical for skincare authenticity &amp; safety compliance</span></div>
        <table class="data-table"><thead><tr><th>Product</th><th>Category</th><th>Batch #</th><th>Expiry Date</th><th>Stock Qty</th><th>Level</th><th>Status</th></tr></thead><tbody id="inventoryTbody"></tbody></table>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Orders                                    -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-orders">
      <div class="panel-header"><div><h1>Orders</h1><p>Manage customer orders and fulfilment</p></div><div style="display:flex;gap:8px;"><button class="action-btn edit">📥 Export CSV</button><select class="form-input" style="width:160px;padding:8px 12px;font-size:.82rem;"><option>All Status</option><option>Pending</option><option>Shipped</option><option>Delivered</option><option>Cancelled</option></select></div></div>
      @php
        $totalOrders     = count($adminOrders);
        $pendingOrders   = count(array_filter($adminOrders, fn($o) => in_array($o['status'] ?? '', ['pending', 'processing'])));
        $shippedOrders   = count(array_filter($adminOrders, fn($o) => ($o['status'] ?? '') === 'shipped'));
        $deliveredOrders = count(array_filter($adminOrders, fn($o) => ($o['status'] ?? '') === 'delivered'));
      @endphp
      <div class="mini-stats"><div class="mini-stat"><div class="mini-stat-val">{{ $totalOrders }}</div><div class="mini-stat-label">Total Orders</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:#f59e0b;">{{ $pendingOrders }}</div><div class="mini-stat-label">Pending</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:#3b82f6;">{{ $shippedOrders }}</div><div class="mini-stat-label">Shipped</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:#16a34a;">{{ $deliveredOrders }}</div><div class="mini-stat-label">Delivered</div></div></div>
      <div class="section-card">
        <table class="data-table"><thead><tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Total</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody id="ordersTableBody">
          @forelse($adminOrders as $order)
          @php
            $oAddr      = $order['shipping_address'] ?? [];
            $oCity      = $oAddr['city'] ?? ($oAddr['state'] ?? '');
            $oItemNames = array_column($order['items'] ?? [], 'name');
            $oSummary   = implode(', ', array_slice($oItemNames, 0, 2));
            if (count($oItemNames) > 2) $oSummary .= ' + ' . (count($oItemNames) - 2) . ' more';
            if (!$oSummary) $oSummary = 'Order items';
            $oStatusClass = match($order['status'] ?? 'pending') {
              'processing' => 'pending',
              'shipped'    => 'shipped',
              'delivered'  => 'active',
              'cancelled'  => 'cancelled',
              default      => 'pending',
            };
          @endphp
          <tr>
            <td><strong>#{{ $order['order_number'] ?? '' }}</strong></td>
            <td>{{ $order['user']['name'] ?? ($oAddr['name'] ?? 'Customer') }}<br><span style="font-size:.75rem;color:rgba(10,10,10,.4);">{{ $oCity }}</span></td>
            <td>{{ $oSummary }}</td>
            <td><strong>₦{{ number_format($order['total'] ?? 0) }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}</td>
            <td><span class="status-badge {{ $oStatusClass }}">{{ ucfirst($order['status'] ?? 'Pending') }}</span></td>
            <td><button class="action-btn edit" onclick="openOrderModal({{ $order['id'] }})">View</button></td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:rgba(10,10,10,.4)">No orders found.</td></tr>
          @endforelse
        </tbody></table>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Subscribers                               -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-subscribers">
      @php
        $subTotal    = count($adminUsers);
        $subActive   = collect($adminUsers)->filter(fn($u) => ($u['subscription_status'] ?? '') === 'active' || ($u['subscription'] ?? null) !== null)->count();
        $subPaused   = collect($adminUsers)->filter(fn($u) => ($u['subscription_status'] ?? '') === 'paused')->count();
        $subCancelled= collect($adminUsers)->filter(fn($u) => ($u['subscription_status'] ?? '') === 'cancelled')->count();
        $planOptions = collect($subscriptionPlans)->map(fn($p) => $p['name'] . ' ₦' . number_format($p['price'] / 1000) . 'k')->all();
      @endphp
      <div class="panel-header"><div><h1>Subscribers</h1><p>Quarterly box subscription management</p></div><div style="display:flex;gap:8px;"><button class="action-btn edit" onclick="loadAdminSubscriptions()">📥 Load Subs</button><button class="action-btn primary" onclick="switchAdminPanel('subscriptions',null)">View All Plans</button></div></div>
      <div class="mini-stats"><div class="mini-stat"><div class="mini-stat-val">{{ number_format($subTotal) }}</div><div class="mini-stat-label">Registered Users</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:#16a34a;" id="sub-stat-active">{{ $subActive ?: '—' }}</div><div class="mini-stat-label">Active Subs</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:#f59e0b;" id="sub-stat-paused">{{ $subPaused ?: '—' }}</div><div class="mini-stat-label">Paused</div></div><div class="mini-stat"><div class="mini-stat-val" style="color:var(--red);" id="sub-stat-cancelled">{{ $subCancelled ?: '—' }}</div><div class="mini-stat-label">Cancelled</div></div></div>
      <div class="section-card">
        <div class="section-card-header"><div><h3>Subscriber List</h3><p>Showing registered users · load active subscriptions above</p></div><select class="form-input" style="width:160px;padding:7px 12px;font-size:.78rem;"><option>All Plans</option>@foreach($subscriptionPlans as $sp)<option>{{ $sp['name'] }}</option>@endforeach</select></div>
        <div class="subscriber-grid">
          <div class="sub-card"><div class="sub-card-top"><div class="sub-avatar a">AO</div><div><div class="sub-name">Adaeze Okonkwo</div><div class="sub-plan">Advanced Plan · ₦100,000/qtr</div></div></div><div class="sub-info-row"><span>Joined</span><strong>Jan 2026</strong></div><div class="sub-info-row"><span>Next Box</span><strong>May 2026</strong></div><div class="sub-info-row"><span>Skin Type</span><strong>Combination</strong></div><div class="sub-info-row"><span>Status</span><span class="status-badge active">Active</span></div><div style="display:flex;gap:6px;margin-top:10px;"><button class="action-btn tag-btn" style="flex:1;justify-content:center;" onclick="openManageBoxModal('Adaeze Okonkwo')">📦 Manage Box</button><button class="action-btn edit" style="flex:1;justify-content:center;" onclick="showToast('🚚','Shipment triggered for Adaeze!')">🚚 Ship Now</button></div></div>
          <div class="sub-card"><div class="sub-card-top"><div class="sub-avatar b">CA</div><div><div class="sub-name">Chinyere Adaeze</div><div class="sub-plan">Master Plan · ₦70,000/qtr</div></div></div><div class="sub-info-row"><span>Joined</span><strong>Oct 2025</strong></div><div class="sub-info-row"><span>Next Box</span><strong>May 2026</strong></div><div class="sub-info-row"><span>Skin Type</span><strong>Dry</strong></div><div class="sub-info-row"><span>Status</span><span class="status-badge active">Active</span></div><div style="display:flex;gap:6px;margin-top:10px;"><button class="action-btn tag-btn" style="flex:1;justify-content:center;" onclick="openManageBoxModal('Chinyere Adaeze')">📦 Manage Box</button><button class="action-btn edit" style="flex:1;justify-content:center;" onclick="showToast('🚚','Shipment triggered for Chinyere!')">🚚 Ship Now</button></div></div>
          <div class="sub-card"><div class="sub-card-top"><div class="sub-avatar c">NE</div><div><div class="sub-name">Ngozi Eze</div><div class="sub-plan">Beginner Plan · ₦40,000/qtr</div></div></div><div class="sub-info-row"><span>Joined</span><strong>Feb 2026</strong></div><div class="sub-info-row"><span>Next Box</span><strong>May 2026</strong></div><div class="sub-info-row"><span>Skin Type</span><strong>Oily</strong></div><div class="sub-info-row"><span>Status</span><span class="status-badge pending">Paused</span></div><div style="display:flex;gap:6px;margin-top:10px;"><button class="action-btn tag-btn" style="flex:1;justify-content:center;" onclick="openManageBoxModal('Ngozi Eze')">📦 Manage Box</button><button class="action-btn edit" style="flex:1;justify-content:center;" onclick="showToast('▶️','Subscription resumed for Ngozi!')">▶️ Resume</button></div></div>
          <div class="sub-card"><div class="sub-card-top"><div class="sub-avatar d">FB</div><div><div class="sub-name">Fatimah Bello</div><div class="sub-plan">Advanced Plan · ₦100,000/qtr</div></div></div><div class="sub-info-row"><span>Joined</span><strong>Jul 2025</strong></div><div class="sub-info-row"><span>Next Box</span><strong>May 2026</strong></div><div class="sub-info-row"><span>Skin Type</span><strong>Sensitive</strong></div><div class="sub-info-row"><span>Status</span><span class="status-badge active">Active</span></div><div style="display:flex;gap:6px;margin-top:10px;"><button class="action-btn tag-btn" style="flex:1;justify-content:center;" onclick="openManageBoxModal('Fatimah Bello')">📦 Manage Box</button><button class="action-btn edit" style="flex:1;justify-content:center;" onclick="showToast('🚚','Shipment triggered for Fatimah!')">🚚 Ship Now</button></div></div>
          <div class="sub-card"><div class="sub-card-top"><div class="sub-avatar e">AO</div><div><div class="sub-name">Amaka Obi</div><div class="sub-plan">Master Plan · ₦70,000/qtr</div></div></div><div class="sub-info-row"><span>Joined</span><strong>Apr 2025</strong></div><div class="sub-info-row"><span>Next Box</span><strong>May 2026</strong></div><div class="sub-info-row"><span>Skin Type</span><strong>Combination</strong></div><div class="sub-info-row"><span>Status</span><span class="status-badge active">Active</span></div><div style="display:flex;gap:6px;margin-top:10px;"><button class="action-btn tag-btn" style="flex:1;justify-content:center;" onclick="openManageBoxModal('Amaka Obi')">📦 Manage Box</button><button class="action-btn edit" style="flex:1;justify-content:center;" onclick="showToast('⏭️','Next box skipped for Amaka!')">⏭️ Skip Box</button></div></div>
          <div class="sub-card"><div class="sub-card-top"><div class="sub-avatar f">ZM</div><div><div class="sub-name">Zainab Musa</div><div class="sub-plan">Beginner Plan · ₦40,000/qtr</div></div></div><div class="sub-info-row"><span>Joined</span><strong>Mar 2026</strong></div><div class="sub-info-row"><span>Next Box</span><strong>May 2026</strong></div><div class="sub-info-row"><span>Skin Type</span><strong>Normal</strong></div><div class="sub-info-row"><span>Status</span><span class="status-badge cancelled">Cancelled</span></div><div style="display:flex;gap:6px;margin-top:10px;"><button class="action-btn tag-btn" style="flex:1;justify-content:center;" onclick="openManageBoxModal('Zainab Musa')">📦 Manage Box</button><button class="action-btn primary" style="flex:1;justify-content:center;" onclick="showToast('✅','Subscription reactivated for Zainab!')">Reactivate</button></div></div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Users                                     -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-users">
      <div class="panel-header">
        <div><h1>Users</h1><p>All registered customers</p></div>
        <div style="display:flex;gap:8px;align-items:center">
          <input type="text" class="form-input" placeholder="Search users…" style="width:200px;font-size:.82rem;padding:7px 12px;" oninput="filterUsersTable(this.value)">
          <button class="action-btn edit">📥 Export CSV</button>
        </div>
      </div>

      @php
        $tierColors = ['Bronze'=>'#cd7f32','Silver'=>'#9ca3af','Gold'=>'#f59e0b','Platinum'=>'#6366f1'];
      @endphp

      <div class="section-card" style="padding:0;overflow:hidden">
        <table class="data-table" id="usersTable">
          <thead><tr><th>User</th><th>Phone</th><th>Address</th><th>Skin Type</th><th>Tier</th><th>Points</th><th>Joined</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($adminUsers as $u)
              @php
                $initLetter = strtoupper(substr($u['name'] ?? 'U', 0, 1));
                $tier = $u['tier'] ?? 'Bronze';
                $tierColor = $tierColors[$tier] ?? '#9ca3af';
                $joinedAt = $u['created_at'] ?? null;
                $joinedFmt = $joinedAt ? \Carbon\Carbon::parse($joinedAt)->format('M Y') : '—';
                $address = array_filter([$u['city'] ?? null, $u['state'] ?? null]);
                $addressStr = !empty($address) ? implode(', ', $address) : '—';
              @endphp
              <tr data-name="{{ strtolower($u['name'] ?? '') }}" data-email="{{ strtolower($u['email'] ?? '') }}">
                <td>
                  <div style="display:flex;align-items:center;gap:12px">
                    @if(!empty($u['avatar']))
                      <img src="{{ $u['avatar'] }}" alt="{{ $u['name'] }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid var(--border);flex-shrink:0">
                    @else
                      <div style="width:38px;height:38px;border-radius:50%;background:var(--black);color:var(--lime);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0">{{ $initLetter }}</div>
                    @endif
                    <div>
                      <div style="font-weight:700;font-size:.88rem">{{ $u['name'] ?? '—' }}</div>
                      <div style="font-size:.72rem;color:rgba(10,10,10,.4)">{{ $u['email'] ?? '' }}</div>
                    </div>
                  </div>
                </td>
                <td style="font-size:.82rem">{{ $u['phone'] ?? '—' }}</td>
                <td style="font-size:.82rem">{{ $addressStr }}</td>
                <td style="font-size:.82rem">{{ $u['skin_type'] ?? '—' }}</td>
                <td><span style="color:{{ $tierColor }};font-weight:700;font-size:.8rem">{{ $tier }}</span></td>
                <td style="font-weight:700;font-size:.88rem">{{ number_format($u['loyalty_points'] ?? 0) }}</td>
                <td style="font-size:.82rem;color:rgba(10,10,10,.5)">{{ $joinedFmt }}</td>
                <td><button class="action-btn edit" onclick="openUserProfileModal({{ json_encode($u) }})">View</button></td>
              </tr>
            @empty
              <tr><td colspan="8" style="text-align:center;padding:40px;color:rgba(10,10,10,.35);font-size:.88rem">No users found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- User Profile Detail Modal --}}
      <div id="userProfileModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9000;overflow-y:auto;padding:40px 20px" onclick="if(event.target===this)closeUserProfileModal()">
        <div style="background:#fff;border-radius:20px;max-width:560px;margin:0 auto;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.22)">
          <div style="background:var(--black);padding:28px 32px;position:relative">
            <button onclick="closeUserProfileModal()" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,.12);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center">✕</button>
            <div style="display:flex;align-items:center;gap:18px">
              <div id="upModal_avatar" style="width:64px;height:64px;border-radius:50%;border:3px solid rgba(212,217,148,.4);overflow:hidden;flex-shrink:0;background:var(--black);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.6rem;color:var(--lime)"></div>
              <div>
                <div id="upModal_name" style="font-size:1.1rem;font-weight:700;color:#fff"></div>
                <div id="upModal_email" style="font-size:.8rem;color:rgba(255,255,255,.45);margin-top:3px"></div>
                <div id="upModal_tier" style="margin-top:6px;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px;display:inline-block;background:rgba(212,217,148,.15);color:var(--lime)"></div>
              </div>
            </div>
          </div>
          <div style="padding:28px 32px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div>
              <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Phone</div>
              <div id="upModal_phone" style="font-size:.9rem;font-weight:600">—</div>
            </div>
            <div>
              <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Birthday</div>
              <div id="upModal_birthday" style="font-size:.9rem;font-weight:600">—</div>
            </div>
            <div>
              <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Skin Type</div>
              <div id="upModal_skin" style="font-size:.9rem;font-weight:600">—</div>
            </div>
            <div>
              <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Loyalty Points</div>
              <div id="upModal_points" style="font-size:.9rem;font-weight:700;color:var(--lime-dark)">—</div>
            </div>
            <div style="grid-column:1/-1">
              <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Delivery Address</div>
              <div id="upModal_address" style="font-size:.88rem;font-weight:600;line-height:1.5">—</div>
            </div>
            <div>
              <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Member Since</div>
              <div id="upModal_since" style="font-size:.9rem;font-weight:600">—</div>
            </div>
            <div>
              <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Email Preferences</div>
              <div id="upModal_prefs" style="font-size:.8rem;color:rgba(10,10,10,.55);line-height:1.6">—</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Loyalty & Members                         -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-loyalty">
      <div class="panel-header">
        <div><h1>Loyalty & Members</h1><p>Manage tiers, points, members, and send notifications</p></div>
        <button class="action-btn primary" onclick="adminSendNotifModal()">📢 Send Notification</button>
      </div>

      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'ltab-members')">👥 Members</div>
        <div class="panel-tab" onclick="switchTab(this,'ltab-tiers')">🏆 Tier Config</div>
        <div class="panel-tab" onclick="switchTab(this,'ltab-events')">⚡ Point Events</div>
        <div class="panel-tab" onclick="switchTab(this,'ltab-notif')">📢 Notifications</div>
      </div>

      {{-- MEMBERS TAB --}}
      <div class="panel-tab-content active" id="ltab-members">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>All Members</h3><p>{{ count($adminUsers) }} members total</p></div>
            <div style="display:flex;gap:8px;">
              <input type="text" class="form-input" placeholder="Search name or email…" style="width:220px;font-size:.82rem;padding:7px 12px;" oninput="filterMemberTable(this.value)">
              <select class="form-input" style="width:150px;font-size:.82rem;padding:7px 12px;" onchange="filterMemberTier(this.value)">
                <option value="">All Tiers</option>
                @foreach($loyaltyConfig['tiers'] ?? [] as $t)
                <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <table class="data-table" id="member-table">
            <thead>
              <tr>
                <th>Member</th>
                <th>Tier</th>
                <th>Points</th>
                <th>Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($adminUsers as $mu)
              @php
                $muTier    = $mu['tier'] ?? 'starter';
                $muPts     = number_format($mu['loyalty_points'] ?? 0);
                $muJoined  = isset($mu['created_at']) ? date('M j, Y', strtotime($mu['created_at'])) : '—';
                $tierColors= ['starter'=>'#6B7280','glow'=>'#F59E0B','radiant'=>'#893941','iconic'=>'#D4D994'];
                $muColor   = $tierColors[$muTier] ?? '#6B7280';
                $muInit    = strtoupper(substr($mu['name'] ?? 'U', 0, 1));
              @endphp
              <tr data-name="{{ strtolower($mu['name'] ?? '') }}" data-email="{{ strtolower($mu['email'] ?? '') }}" data-tier="{{ $muTier }}">
                <td>
                  <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:50%;background:{{ $muColor }};display:grid;place-items:center;font-weight:700;font-size:.75rem;flex-shrink:0;color:{{ in_array($muTier,['starter','glow','iconic']) ? '#1C1416' : '#fff' }}">{{ $muInit }}</div>
                    <div>
                      <div style="font-weight:600;font-size:.85rem;">{{ $mu['name'] ?? '—' }}</div>
                      <div style="font-size:.75rem;color:rgba(10,10,10,.4);">{{ $mu['email'] ?? '' }}</div>
                    </div>
                  </div>
                </td>
                <td><span class="loyalty-tier-chip {{ $muTier }}" style="background:{{ $muColor }}1a;color:{{ $muColor }}">{{ ucfirst($muTier) }}</span></td>
                <td style="font-weight:700;">{{ $muPts }}</td>
                <td style="font-size:.82rem;color:rgba(10,10,10,.45);">{{ $muJoined }}</td>
                <td>
                  <div class="loyalty-pts-adj">
                    <button class="pts-adj-btn plus" title="Award points" onclick="adminAwardPoints({{ $mu['id'] }}, '{{ addslashes($mu['name'] ?? '') }}', 1)">+</button>
                    <button class="pts-adj-btn minus" title="Deduct points" onclick="adminAwardPoints({{ $mu['id'] }}, '{{ addslashes($mu['name'] ?? '') }}', -1)">−</button>
                    <button class="action-btn edit" style="font-size:.72rem;padding:4px 8px;" onclick="adminUserNotif({{ $mu['id'] }}, '{{ addslashes($mu['name'] ?? '') }}')">Notify</button>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="5" style="text-align:center;padding:32px;color:rgba(10,10,10,.4);">No members found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- TIER CONFIG TAB --}}
      <div class="panel-tab-content" id="ltab-tiers">

        {{-- Home Page Section Text --}}
        <div class="content-block" style="margin-bottom:20px;background:rgba(200,230,52,.04);border-color:rgba(200,230,52,.25);">
          <div class="content-block-header">
            <strong>🏠 Home Page Section Text</strong>
            <span>Controls the heading and description shown above the loyalty tier cards on the homepage</span>
          </div>
          <div class="form-grid">
            <div class="form-group" style="margin-bottom:0;">
              <label>Kicker Label</label>
              <input class="form-input" id="loyalty-cms-kicker" value="{{ data_get($cmsContent['content'] ?? [], 'loyalty_section.kicker', 'Loyalty Program') }}" placeholder="e.g. Loyalty Program">
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label>Section Heading <span style="font-size:.75rem;font-weight:400;color:rgba(10,10,10,.4);">(use comma to split for italic style)</span></label>
              <input class="form-input" id="loyalty-cms-heading" value="{{ data_get($cmsContent['content'] ?? [], 'loyalty_section.heading', 'Glow More, Earn More') }}" placeholder="e.g. Glow More, Earn More">
            </div>
            <div class="form-group" style="grid-column:1/-1;margin-bottom:0;">
              <label>Section Description</label>
              <textarea class="form-input" id="loyalty-cms-description" rows="2" style="font-family:inherit;resize:vertical;">{{ data_get($cmsContent['content'] ?? [], 'loyalty_section.description', 'Every purchase earns points. Every point unlocks rewards. The more you shop, the more you glow.') }}</textarea>
            </div>
          </div>
          <div style="margin-top:14px;display:flex;justify-content:flex-end;">
            <button class="action-btn primary" onclick="saveLoyaltySectionCms()">💾 Save Home Page Text</button>
          </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
          <div style="font-size:.88rem;color:rgba(10,10,10,.5);">Edit tier thresholds, benefits, and upgrade gifts. Changes apply to all members immediately.</div>
          <button class="action-btn primary" onclick="saveLoyaltyTierConfig()">💾 Save Tier Config</button>
        </div>
        <div id="tier-config-form">
          @foreach($loyaltyConfig['tiers'] ?? [] as $idx => $t)
          <div class="content-block" data-tier-idx="{{ $idx }}">
            <div class="content-block-header">
              <strong>{{ $t['name'] }} <span style="font-size:.75rem;color:rgba(10,10,10,.4);font-weight:400;">({{ $t['id'] }})</span></strong>
              <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:.8rem;color:rgba(10,10,10,.4);">Min {{ number_format($t['min_points']) }} pts</span>
                @if($t['is_popular'] ?? false)<span style="background:var(--lime);color:#1C1416;font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:99px;letter-spacing:.08em;">FEATURED ON HOME</span>@endif
                <button type="button" onclick="deleteLoyaltyTier(this)" style="background:none;border:1px solid rgba(220,38,38,.35);color:#dc2626;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:6px;cursor:pointer;letter-spacing:.04em;" title="Delete this tier">✕ Delete</button>
              </div>
            </div>
            <div class="form-grid" style="margin-bottom:14px;">
              <div class="form-group" style="margin-bottom:0">
                <label>Tier Name</label>
                <input class="form-input" name="tiers[{{ $idx }}][name]" value="{{ $t['name'] }}">
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Min Points Required</label>
                <input class="form-input" type="number" name="tiers[{{ $idx }}][min_points]" value="{{ $t['min_points'] }}">
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Points Multiplier (e.g. 1.5)</label>
                <input class="form-input" type="number" step=".01" name="tiers[{{ $idx }}][multiplier]" value="{{ $t['multiplier'] }}">
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Tier Color (hex)</label>
                <input class="form-input" name="tiers[{{ $idx }}][color]" value="{{ $t['color'] }}">
              </div>
              <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;">
                  <input type="checkbox" name="tiers[{{ $idx }}][is_popular]" {{ ($t['is_popular'] ?? false) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#1C1416;">
                  Featured on Home Page — highlights this tier card on the homepage
                </label>
              </div>
            </div>
            <div class="form-group">
              <label>Benefits (one per line)</label>
              <textarea class="form-input" rows="3" name="tiers[{{ $idx }}][benefits]" style="font-family:inherit;resize:vertical;">{{ implode("\n", $t['benefits'] ?? []) }}</textarea>
            </div>
            @if(!empty($t['gift']))
            <div class="form-grid">
              <div class="form-group" style="margin-bottom:0">
                <label>Gift Name (on tier upgrade)</label>
                <input class="form-input" name="tiers[{{ $idx }}][gift][name]" value="{{ $t['gift']['name'] ?? '' }}">
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Gift Value (₦)</label>
                <input class="form-input" type="number" name="tiers[{{ $idx }}][gift][value]" value="{{ $t['gift']['value'] ?? 0 }}">
              </div>
              <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                <label>Gift Description</label>
                <input class="form-input" name="tiers[{{ $idx }}][gift][description]" value="{{ $t['gift']['description'] ?? '' }}">
              </div>
            </div>
            @else
            <div style="font-size:.8rem;color:rgba(10,10,10,.4);">No upgrade gift configured for this tier.</div>
            @endif
          </div>
          @endforeach
        </div>
      </div>

      {{-- POINT EVENTS TAB --}}
      <div class="panel-tab-content" id="ltab-events">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
          <div style="font-size:.88rem;color:rgba(10,10,10,.5);">Configure how many points each action awards. Changes apply to new events only.</div>
          <button class="action-btn primary" onclick="savePointEventsConfig()">💾 Save Events Config</button>
        </div>
        <div class="section-card">
          <table class="data-table" id="point-events-table">
            <thead><tr><th>Event</th><th>Description</th><th>Points</th><th>Type</th></tr></thead>
            <tbody>
              @foreach($loyaltyConfig['point_events'] ?? [] as $key => $evt)
              <tr>
                <td style="font-weight:700;">{{ $evt['label'] ?? $key }}</td>
                <td style="font-size:.82rem;color:rgba(10,10,10,.5);">{{ $evt['description'] ?? '' }}</td>
                <td>
                  @if(isset($evt['points_per_1000']))
                  <input class="form-input" type="number" style="width:80px;font-size:.82rem;padding:5px 8px;" data-event="{{ $key }}" data-field="points_per_1000" value="{{ $evt['points_per_1000'] }}">
                  <span style="font-size:.78rem;color:rgba(10,10,10,.4);"> pts/₦1K</span>
                  @else
                  <input class="form-input" type="number" style="width:80px;font-size:.82rem;padding:5px 8px;" data-event="{{ $key }}" data-field="points" value="{{ $evt['points'] ?? 0 }}">
                  <span style="font-size:.78rem;color:rgba(10,10,10,.4);"> pts</span>
                  @endif
                </td>
                <td>
                  @if(!empty($evt['one_time']))<span class="tag-chip" style="background:#d1fae5;color:#065f46;">One-time</span>@endif
                  @if(!empty($evt['recurring']))<span class="tag-chip" style="background:#dbeafe;color:#1e40af;">{{ ucfirst($evt['recurring']) }}</span>@endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- NOTIFICATIONS TAB --}}
      <div class="panel-tab-content" id="ltab-notif">
        <div class="content-block">
          <div class="content-block-header"><strong>📢 Send Notification to Members</strong><span>Broadcast or target a single user</span></div>
          <div class="form-grid">
            <div class="form-group">
              <label>Recipient</label>
              <select class="form-input" id="notif-recipient-type" onchange="toggleNotifUserSelect(this.value)">
                <option value="all">All Members (Broadcast)</option>
                <option value="single">Specific Member</option>
              </select>
            </div>
            <div class="form-group" id="notif-user-select-wrap" style="display:none;">
              <label>Select Member</label>
              <select class="form-input" id="notif-user-id">
                @foreach($adminUsers as $mu)
                <option value="{{ $mu['id'] }}">{{ $mu['name'] }} ({{ $mu['email'] }})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Notification Type</label>
              <select class="form-input" id="notif-type">
                <option value="system">System</option>
                <option value="promotion">Promotion</option>
                <option value="tier_upgrade">Tier Upgrade</option>
                <option value="order">Order Update</option>
                <option value="subscription">Subscription</option>
                <option value="gift">Gift / Reward</option>
              </select>
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Title</label>
              <input class="form-input" id="notif-title" placeholder="e.g. 🎁 Exclusive sale just for you!">
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Message</label>
              <textarea class="form-input" id="notif-message" rows="3" placeholder="Write the notification message here…" style="font-family:inherit;resize:vertical;"></textarea>
            </div>
          </div>
          <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button class="action-btn edit" onclick="document.getElementById('notif-title').value='';document.getElementById('notif-message').value='';">Clear</button>
            <button class="action-btn primary" onclick="adminSendNotification()">📢 Send Notification</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Subscriptions                             -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-subscriptions">
      <div class="panel-header">
        <div><h1>Subscription Plans</h1><p>Create and manage subscription box plans available to members</p></div>
        <button class="action-btn primary" onclick="openCreatePlanModal()">+ New Plan</button>
      </div>

      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'stab-plans')">📋 Plans</div>
        <div class="panel-tab" onclick="switchTab(this,'stab-subscribers')">👥 Active Subscribers</div>
        <div class="panel-tab" onclick="switchTab(this,'stab-home-cms')">🏠 Home Page</div>
      </div>

      {{-- Plans list --}}
      <div class="panel-tab-content active" id="stab-plans">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;" id="admin-plans-grid">
          @forelse($subscriptionPlans as $plan)
          <div class="content-block" style="position:relative;">
            @if($plan['is_popular']??false)<span style="position:absolute;top:14px;right:14px;background:var(--lime);color:#1C1416;font-size:.62rem;font-weight:700;padding:3px 10px;border-radius:999px;letter-spacing:.08em;">POPULAR</span>@endif
            <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(10,10,10,.4);margin-bottom:6px;">{{ $plan['id'] }}</div>
            <div style="font-size:1.05rem;font-weight:700;margin-bottom:3px;">{{ $plan['name'] }}</div>
            <div style="font-size:1.3rem;font-weight:700;margin-bottom:2px;">₦{{ number_format($plan['price']) }}<span style="font-size:.78rem;font-weight:400;color:rgba(10,10,10,.4);"> / {{ $plan['billing_cycle'] }}</span></div>
            <div style="font-size:.8rem;color:rgba(10,10,10,.5);margin-bottom:10px;">{{ $plan['products_count'] }} products/box · {{ $plan['frequency_label'] }}</div>
            <div style="font-size:.82rem;color:rgba(10,10,10,.6);margin-bottom:12px;line-height:1.5;">{{ $plan['description'] }}</div>
            <ul style="list-style:none;padding:0;margin:0 0 16px;display:flex;flex-direction:column;gap:4px;">
              @foreach($plan['features']??[] as $feat)
              <li style="font-size:.78rem;display:flex;gap:7px;"><span style="color:#16a34a;font-weight:700;">✓</span>{{ $feat }}</li>
              @endforeach
            </ul>
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
              <span class="status-badge {{ ($plan['is_active']??true) ? 'active' : 'cancelled' }}">{{ ($plan['is_active']??true) ? 'Active' : 'Inactive' }}</span>
              <div style="display:flex;gap:6px;">
                <button class="action-btn edit" onclick="editPlan('{{ $plan['id'] }}')">Edit</button>
                <button class="action-btn danger" onclick="if(confirm('Delete this plan?')) deletePlan('{{ $plan['id'] }}')">Delete</button>
              </div>
            </div>
          </div>
          @empty
          <div style="text-align:center;padding:40px;color:rgba(10,10,10,.4);grid-column:1/-1;">No subscription plans yet. Create your first plan above.</div>
          @endforelse
        </div>
      </div>

      {{-- Active subscribers --}}
      <div class="panel-tab-content" id="stab-subscribers">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Active Subscriptions</h3><p>Members currently subscribed to a plan</p></div><button class="action-btn edit" onclick="loadAdminSubscriptions()">↻ Refresh</button></div>
          <div id="admin-subscriptions-list">
            <div style="text-align:center;padding:32px;color:rgba(10,10,10,.4);">
              <button class="action-btn primary" onclick="loadAdminSubscriptions()">Load Subscriptions</button>
            </div>
          </div>
        </div>
      </div>

      {{-- Home Page Display --}}
      <div class="panel-tab-content" id="stab-home-cms">
        <div class="content-block" style="margin-bottom:20px;">
          <div class="content-block-header">
            <strong>🏠 Home Page Section Text</strong>
            <span>Controls the heading and description shown above the subscription plans on the homepage</span>
          </div>
          <div class="form-grid">
            <div class="form-group" style="margin-bottom:0;">
              <label>Kicker Label <span style="font-size:.75rem;font-weight:400;color:rgba(10,10,10,.4);">(small text above heading)</span></label>
              <input class="form-input" id="sub-cms-kicker" value="{{ data_get($cmsContent['content'] ?? [], 'subscription_section.kicker', 'Quarterly Subscription') }}" placeholder="e.g. Quarterly Subscription">
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label>Section Heading <span style="font-size:.75rem;font-weight:400;color:rgba(10,10,10,.4);">(use comma to split into two parts for italic style)</span></label>
              <input class="form-input" id="sub-cms-heading" value="{{ data_get($cmsContent['content'] ?? [], 'subscription_section.heading', 'Your Skin Expert, On Autopilot') }}" placeholder="e.g. Your Skin Expert, On Autopilot">
            </div>
            <div class="form-group" style="grid-column:1/-1;margin-bottom:0;">
              <label>Section Description</label>
              <textarea class="form-input" id="sub-cms-description" rows="2" style="font-family:inherit;resize:vertical;" placeholder="Short description shown under the heading…">{{ data_get($cmsContent['content'] ?? [], 'subscription_section.description', 'Expert-curated routines delivered every 3 months — personalized, free shipping, easy to pause or cancel.') }}</textarea>
            </div>
          </div>
          <div style="margin-top:16px;display:flex;justify-content:flex-end;gap:10px;align-items:center;">
            <span style="font-size:.78rem;color:rgba(10,10,10,.4);">Plan cards are driven by the <strong>Plans</strong> tab above — edit plans there to update the cards.</span>
            <button class="action-btn primary" onclick="saveSubSectionCms()">💾 Save Home Page Text</button>
          </div>
        </div>

        <div class="content-block">
          <div class="content-block-header">
            <strong>📋 Plans on Home Page</strong>
            <span>Active plans are shown on the homepage. Mark a plan "Most Popular" to highlight it.</span>
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;margin-top:4px;">
            @forelse($subscriptionPlans as $plan)
            @php $planActive = $plan['is_active'] ?? true; $planPopular = $plan['is_popular'] ?? false; @endphp
            <div style="border:1.5px solid {{ $planPopular ? 'var(--lime)' : '#e8eaed' }};border-radius:14px;padding:16px 18px;background:{{ $planPopular ? 'rgba(200,230,52,.05)' : '#fff' }};">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <div style="font-weight:700;font-size:.9rem;">{{ $plan['name'] }}</div>
                @if($planPopular)<span style="background:var(--lime);color:#1C1416;font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:99px;letter-spacing:.08em;">FEATURED</span>@endif
              </div>
              <div style="font-size:1.05rem;font-weight:700;color:var(--black);margin-bottom:3px;">₦{{ number_format($plan['price']) }} <span style="font-size:.75rem;font-weight:400;color:rgba(10,10,10,.4);">{{ $plan['frequency_label'] ?? '' }}</span></div>
              <div style="font-size:.78rem;color:rgba(10,10,10,.45);margin-bottom:10px;">{{ $plan['products_count'] ?? '?' }} products · {{ $plan['billing_cycle'] ?? 'monthly' }}</div>
              <div style="display:flex;gap:8px;align-items:center;">
                <span class="status-badge {{ $planActive ? 'active' : 'cancelled' }}">{{ $planActive ? 'Active' : 'Inactive' }}</span>
                <button class="action-btn edit" style="font-size:.72rem;padding:4px 8px;" onclick="editPlan('{{ $plan['id'] }}');switchTab(document.querySelector('[onclick*=stab-plans]'),'stab-plans');">Edit</button>
              </div>
            </div>
            @empty
            <div style="color:rgba(10,10,10,.4);font-size:.88rem;padding:16px 0;">No active plans. Create one in the Plans tab.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- Create / Edit Plan Modal --}}
    <div class="modal-overlay" id="plan-modal">
      <div class="add-modal" style="max-width:560px;">
        <div class="add-modal-header">
          <h2 id="plan-modal-title">New Subscription Plan</h2>
          <button class="tag-modal-close" onclick="document.getElementById('plan-modal').classList.remove('open')">×</button>
        </div>
        <div class="add-modal-body">
          <input type="hidden" id="plan-modal-editing-id">
          <div class="form-grid">
            <div class="form-group">
              <label>Plan ID <span style="font-size:.75rem;font-weight:400;color:rgba(10,10,10,.4);">(slug, no spaces)</span></label>
              <input class="form-input" id="pm-id" placeholder="e.g. glow-box">
            </div>
            <div class="form-group">
              <label>Plan Name</label>
              <input class="form-input" id="pm-name" placeholder="e.g. Glow Box">
            </div>
            <div class="form-group">
              <label>Price (₦)</label>
              <input class="form-input" type="number" id="pm-price" min="0" placeholder="30000">
            </div>
            <div class="form-group">
              <label>Billing Cycle</label>
              <select class="form-input" id="pm-cycle">
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="biannual">Bi-annual</option>
                <option value="annual">Annual</option>
              </select>
            </div>
            <div class="form-group">
              <label>Products per Box</label>
              <input class="form-input" type="number" id="pm-count" min="1" placeholder="5">
            </div>
            <div class="form-group">
              <label>Tier Required (optional)</label>
              <select class="form-input" id="pm-tier">
                <option value="">None (available to all)</option>
                @foreach($loyaltyConfig['tiers'] ?? [] as $t)
                <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Description</label>
              <textarea class="form-input" id="pm-desc" rows="2" style="font-family:inherit;resize:vertical;" placeholder="Brief description of this plan…"></textarea>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Features (one per line)</label>
              <textarea class="form-input" id="pm-features" rows="4" style="font-family:inherit;resize:vertical;" placeholder="5 full-size products&#10;Free shipping&#10;15% discount"></textarea>
            </div>
            <div class="form-group">
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;">
                <input type="checkbox" id="pm-popular" style="width:16px;height:16px;accent-color:#1C1416;"> Mark as Most Popular
              </label>
            </div>
            <div class="form-group">
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;">
                <input type="checkbox" id="pm-active" checked style="width:16px;height:16px;accent-color:#1C1416;"> Plan is Active
              </label>
            </div>
          </div>
        </div>
        <div class="add-modal-footer">
          <button class="action-btn edit" onclick="document.getElementById('plan-modal').classList.remove('open')">Cancel</button>
          <button class="action-btn primary" onclick="savePlan()">Save Plan</button>
        </div>
      </div>
    </div>

    {{-- Award Points Modal --}}
    <div class="modal-overlay" id="award-pts-modal">
      <div class="add-modal" style="max-width:420px;">
        <div class="add-modal-header">
          <h2>Award / Deduct Points</h2>
          <button class="tag-modal-close" onclick="document.getElementById('award-pts-modal').classList.remove('open')">×</button>
        </div>
        <div class="add-modal-body">
          <input type="hidden" id="award-user-id">
          <p style="font-size:.88rem;color:rgba(10,10,10,.5);margin-bottom:16px;">Member: <strong id="award-user-name"></strong></p>
          <div class="form-group">
            <label>Points (use negative to deduct, e.g. -100)</label>
            <input class="form-input" type="number" id="award-pts-value" placeholder="e.g. 200">
          </div>
          <div class="form-group">
            <label>Note / Reason</label>
            <input class="form-input" id="award-pts-note" placeholder="e.g. VIP bonus, event reward…">
          </div>
        </div>
        <div class="add-modal-footer">
          <button class="action-btn edit" onclick="document.getElementById('award-pts-modal').classList.remove('open')">Cancel</button>
          <button class="action-btn primary" onclick="submitAwardPoints()">Award Points</button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Analytics                                 -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-analytics">
      <div class="panel-header"><div><h1>Analytics</h1><p>Platform insights and skin data</p></div></div>
      @php
        $quizMonthDiff = $analyticsQuizThisMonth - $analyticsQuizPrevMonth;
        $quizMonthPct  = $analyticsQuizPrevMonth > 0
          ? round(abs($quizMonthDiff / $analyticsQuizPrevMonth) * 100, 1)
          : ($analyticsQuizThisMonth > 0 ? 100 : 0);
        $concernBarColors = ['red','lime','blue','amber','red'];
        $concernTextStyles = ['color:var(--red)','','color:#4f94ea','color:#f59e0b','color:rgba(10,10,10,.4)'];
        $locationBarColors = ['lime','red','blue','amber','red'];
        $locationTextStyles = ['','color:var(--red)','color:#4f94ea','color:#f59e0b','color:rgba(10,10,10,.4)'];
      @endphp
      <div class="kpi-grid">
        <div class="kpi-card lime">
          <div class="kpi-icon">🎯</div>
          <div class="kpi-label">Quiz Completions</div>
          <div class="kpi-value">{{ number_format($totalQuizTakers) }}</div>
          <div class="kpi-sub">
            @if($quizMonthDiff > 0)<span class="kpi-up">↑ {{ $quizMonthPct }}%</span>@elseif($quizMonthDiff < 0)<span class="kpi-down">↓ {{ $quizMonthPct }}%</span>@else —@endif vs last month
          </div>
        </div>
        <div class="kpi-card red">
          <div class="kpi-icon">🛒</div>
          <div class="kpi-label">Quiz-to-Order Rate</div>
          <div class="kpi-value">{{ $analyticsQuizToCartRate }}%</div>
          <div class="kpi-sub">signed-in quiz users with an order</div>
        </div>
        <div class="kpi-card blue">
          <div class="kpi-icon">⭐</div>
          <div class="kpi-label">Avg Product Rating</div>
          <div class="kpi-value" id="analytics-avg-rating">—</div>
          <div class="kpi-sub" id="analytics-review-count">loading…</div>
        </div>
        <div class="kpi-card amber">
          <div class="kpi-icon">🔁</div>
          <div class="kpi-label">Repeat Purchase Rate</div>
          <div class="kpi-value">{{ $analyticsRepeatRate }}%</div>
          <div class="kpi-sub">customers with 2+ orders</div>
        </div>
      </div>
      <div class="dash-row">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Top Concerns (from Quiz)</h3><p>Most common skin concerns across {{ number_format($totalQuizTakers) }} quiz takers</p></div></div>
          <div style="padding:20px 22px;">
            @forelse($analyticsTopConcerns as $concern => $count)
              @php
                $pct = round($count / $analyticsConcernTotal * 100);
                $idx = $loop->index;
                $barColor  = $concernBarColors[$idx]  ?? 'red';
                $textStyle = $concernTextStyles[$idx]  ?? 'color:rgba(10,10,10,.4)';
                $opacity   = $idx === 4 ? ';opacity:.5' : '';
              @endphp
              <div class="tier-row">
                <div class="tier-name">{{ ucfirst($concern) }}</div>
                <div class="tier-bar-track"><div class="tier-bar-fill {{ $barColor }}" style="width:{{ $pct }}%{{ $opacity }}"></div></div>
                <div class="tier-count" style="{{ $textStyle }}">{{ $pct }}%</div>
              </div>
            @empty
              <div style="text-align:center;padding:24px;color:rgba(10,10,10,.4);font-size:.85rem;">No concern data yet — quiz answers will appear here.</div>
            @endforelse
          </div>
        </div>
        <div class="section-card">
          <div class="section-card-header"><div><h3>Top Locations</h3><p>Orders by state ({{ number_format(count($adminOrders)) }} total)</p></div></div>
          <div style="padding:20px 22px;">
            @forelse($analyticsTopLocations as $location => $count)
              @php
                $pct = round($count / $analyticsLocationTotal * 100);
                $idx = $loop->index;
                $barColor  = $locationBarColors[$idx]  ?? 'red';
                $textStyle = $locationTextStyles[$idx]  ?? 'color:rgba(10,10,10,.4)';
                $opacity   = $idx === 4 ? ';opacity:.4' : '';
              @endphp
              <div class="tier-row">
                <div class="tier-name">{{ $location }}</div>
                <div class="tier-bar-track"><div class="tier-bar-fill {{ $barColor }}" style="width:{{ $pct }}%{{ $opacity }}"></div></div>
                <div class="tier-count" style="{{ $textStyle }}">{{ $pct }}%</div>
              </div>
            @empty
              <div style="text-align:center;padding:24px;color:rgba(10,10,10,.4);font-size:.85rem;">No location data yet — order shipping addresses will appear here.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Reviews                                   -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-reviews">
      <div class="panel-header">
        <div><h1>Reviews</h1><p>View and manage product reviews</p></div>
        <div style="display:flex;gap:8px;">
          <select class="form-input" id="rvw-filter-product" style="width:170px;padding:8px 12px;font-size:.82rem;" onchange="reviewsApplyFilter()">
            <option value="">All Products</option>
            @foreach($catalogProducts as $p)
              <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
            @endforeach
          </select>
          <select class="form-input" id="rvw-filter-rating" style="width:130px;padding:8px 12px;font-size:.82rem;" onchange="reviewsApplyFilter()">
            <option value="">All Ratings</option>
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
          </select>
          <button class="action-btn" onclick="reviewsLoad()">↺ Refresh</button>
        </div>
      </div>
      <div class="mini-stats">
        <div class="mini-stat"><div class="mini-stat-val" id="rvw-stat-total">—</div><div class="mini-stat-label">Total Reviews</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="rvw-stat-avg" style="color:#f59e0b;">—</div><div class="mini-stat-label">Avg Rating</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="rvw-stat-5star" style="color:#16a34a;">—</div><div class="mini-stat-label">5-Star Reviews</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="rvw-stat-low" style="color:var(--red);">—</div><div class="mini-stat-label">Low Ratings (≤2★)</div></div>
      </div>
      <div class="section-card" style="margin-bottom:0;">
        <div class="section-card-header">
          <div><h3>All Reviews</h3><p id="rvw-table-subtitle">Loading…</p></div>
        </div>
        <table class="data-table">
          <thead><tr><th>Product</th><th>Reviewer</th><th>Rating</th><th>Review</th><th>Date</th><th>Actions</th></tr></thead>
          <tbody id="rvw-table-body"><tr><td colspan="6" style="text-align:center;padding:24px;color:#9CA3AF">Loading…</td></tr></tbody>
        </table>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Community Gallery                         -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-community">
      <div class="panel-header">
        <div><h1>Community Gallery</h1><p>Moderate user-submitted photos and testimonials</p></div>
        <div style="display:flex;gap:8px;">
          <button class="action-btn" onclick="commLoadAll()" id="comm-refresh-btn">↺ Refresh</button>
          <button class="action-btn edit" onclick="commExport()">📥 Export CSV</button>
        </div>
      </div>

      <div class="mini-stats">
        <div class="mini-stat"><div class="mini-stat-val" id="cs-total">—</div><div class="mini-stat-label">Total Posts</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="cs-total-likes" style="color:#e63946;">—</div><div class="mini-stat-label">Total Likes</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="cs-total-comments" style="color:#3B82F6;">—</div><div class="mini-stat-label">Total Comments</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="cs-pending" style="color:#f59e0b;">—</div><div class="mini-stat-label">Pending Review</div></div>
      </div>

      <div class="panel-tabs" style="margin-top:0;">
        <div class="panel-tab active" onclick="commSwitchTab('all',this)">📋 All Posts</div>
        <div class="panel-tab" onclick="commSwitchTab('activity',this)">📊 Activity Feed <span id="comm-badge-activity" class="nav-badge" style="display:none"></span></div>
        <div class="panel-tab" onclick="commSwitchTab('settings',this)">⚙️ Settings</div>
      </div>

      <!-- All posts table -->
      <div class="panel-tab-content active" id="comm-tab-all">
        <div class="section-card" style="margin-bottom:0;">
          <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;align-items:center;">
            <span style="font-size:.78rem;font-weight:600;color:#6B7280;">Filter:</span>
            <button class="action-btn" id="comm-filter-all"    style="padding:4px 12px;font-size:.75rem;background:#1C1416;color:#fff;" onclick="commSetFilter('all',this)">All</button>
            <button class="action-btn" id="comm-filter-pending"  style="padding:4px 12px;font-size:.75rem;" onclick="commSetFilter('pending',this)">⏳ Pending</button>
            <button class="action-btn" id="comm-filter-approved" style="padding:4px 12px;font-size:.75rem;" onclick="commSetFilter('approved',this)">✅ Approved</button>
            <button class="action-btn" id="comm-filter-rejected" style="padding:4px 12px;font-size:.75rem;" onclick="commSetFilter('rejected',this)">🚫 Rejected</button>
          </div>
          <table class="data-table">
            <thead><tr><th style="width:56px;">Thumb</th><th>User</th><th>Caption</th><th>Type</th><th>Submitted</th><th>Status</th><th>Likes</th><th>Comments</th><th>Actions</th></tr></thead>
            <tbody id="commAllTable"><tr><td colspan="9" style="text-align:center;padding:24px;color:#9CA3AF">Loading…</td></tr></tbody>
          </table>
        </div>
      </div>

      <!-- Activity Feed -->
      <div class="panel-tab-content" id="comm-tab-activity">
        <div class="section-card" style="margin-bottom:0;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <div>
              <h3 style="font-size:1rem;margin-bottom:4px;">Community Activity</h3>
              <p style="font-size:.8rem;color:#6B7280;">Real-time log of likes and comments from community members</p>
            </div>
            <button class="action-btn" onclick="commLoadActivity()">↺ Refresh</button>
          </div>
          <div id="commActivityFeed">
            <div style="text-align:center;padding:60px;color:#9CA3AF;font-size:.9rem">Loading activity…</div>
          </div>
        </div>
      </div>

      <!-- Settings -->
      <div class="panel-tab-content" id="comm-tab-settings">
        <div class="section-card">
          <h3 style="margin-bottom:20px;font-size:1rem;">Gallery Settings</h3>
          <div style="display:flex;flex-direction:column;gap:16px;max-width:480px;">
            <div class="toggle-wrap">
              <div class="toggle-info"><strong>Submissions Open</strong><span>Allow users to submit new posts</span></div>
              <label class="toggle"><input type="checkbox" id="comm-setting-open" checked onchange="commSaveSetting('submissions_open',this.checked)"><span class="toggle-slider"></span></label>
            </div>
            <div class="toggle-wrap">
              <div class="toggle-info"><strong>Auto-Approve Mode</strong><span>Posts go live instantly without manual review</span></div>
              <label class="toggle"><input type="checkbox" id="comm-setting-auto" onchange="commSaveSetting('moderation_mode',this.checked?'auto':'manual')"><span class="toggle-slider"></span></label>
            </div>
          </div>
          <div style="margin-top:20px;padding:14px 16px;background:#f8f9fa;border-radius:10px;font-size:.82rem;color:#6B7280;line-height:1.6;">
            <strong>Featured post</strong> — set from the All Posts tab via the ⭐ Feature button on any approved post. It appears as the hero card on the community page.
          </div>
        </div>
      </div>
    </div><!-- /panel-community -->

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Promotions                                -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-promotions">
      <div class="panel-header">
        <div><h1>Promotions</h1><p>Vouchers, discount codes and flash sales</p></div>
        <button class="action-btn primary" onclick="openCreateCoupon()">+ Create Voucher</button>
      </div>
      <div class="mini-stats">
        <div class="mini-stat"><div class="mini-stat-val" id="promo-stat-active" style="color:#16a34a;">—</div><div class="mini-stat-label">Active Codes</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="promo-stat-total">—</div><div class="mini-stat-label">Total Codes</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="promo-stat-uses">—</div><div class="mini-stat-label">Total Uses</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="promo-stat-expired" style="color:#e63434;">—</div><div class="mini-stat-label">Expired / Inactive</div></div>
      </div>
      <div class="section-card" style="margin-bottom:24px;">
        <div class="section-card-header">
          <div><h3>Voucher & Coupon Codes</h3><p id="promo-coupon-subtext">Loading…</p></div>
          <div style="display:flex;gap:8px;align-items:center;">
            <select id="promo-filter" class="form-input" style="width:140px;padding:7px 10px;font-size:.82rem;" onchange="renderCoupons()">
              <option value="all">All codes</option>
              <option value="active">Active only</option>
              <option value="inactive">Inactive / Expired</option>
            </select>
          </div>
        </div>
        <div class="coupon-grid" id="admin-coupon-grid">
          <div style="grid-column:1/-1;text-align:center;padding:40px;color:rgba(10,10,10,.35);">
            <div style="font-size:1.5rem;margin-bottom:8px;">⏳</div>
            <div style="font-size:.85rem;">Loading vouchers…</div>
          </div>
        </div>
      </div>
      <div class="section-card" style="margin-bottom:0;">
        <div class="section-card-header"><div><h3>Flash Sales</h3><p>Time-limited product discounts</p></div><button class="action-btn primary">+ Add Flash Sale</button></div>
        <div style="padding:0 22px;">
          <div class="flash-row"><img class="product-thumb" src="https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=100&h=100&fit=crop" /><div style="flex:1;"><div style="font-weight:600;font-size:.88rem;">Laneige Sleep Mask</div><div style="font-size:.75rem;color:rgba(10,10,10,.45);">₦35,000 → <strong style="color:var(--black);">₦29,000</strong></div></div><div class="flash-discount-pill">−17%</div><div class="flash-ends">⏱️ Ends in 18h 42m</div><span class="status-badge active">Live</span><div style="display:flex;gap:6px;"><button class="action-btn edit" style="padding:4px 8px;">✏️</button><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.flash-row').remove()">🗑️</button></div></div>
          <div class="flash-row"><img class="product-thumb" src="https://images.unsplash.com/photo-1620916566396-4c7aa9a87879?w=100&h=100&fit=crop" /><div style="flex:1;"><div style="font-weight:600;font-size:.88rem;">COSRX Snail Mucin 96% Essence</div><div style="font-size:.75rem;color:rgba(10,10,10,.45);">₦22,000 → <strong style="color:var(--black);">₦18,000</strong></div></div><div class="flash-discount-pill">−18%</div><div class="flash-ends">⏱️ Ends in 18h 42m</div><span class="status-badge active">Live</span><div style="display:flex;gap:6px;"><button class="action-btn edit" style="padding:4px 8px;">✏️</button><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.flash-row').remove()">🗑️</button></div></div>
          <div class="flash-row"><img class="product-thumb" src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=100&h=100&fit=crop" /><div style="flex:1;"><div style="font-weight:600;font-size:.88rem;">The Ordinary Niacinamide 10% + Zinc</div><div style="font-size:.75rem;color:rgba(10,10,10,.45);">₦8,500 (scheduled discount)</div></div><div class="flash-discount-pill" style="background:#f59e0b;">−20%</div><div class="flash-ends">📅 Starts Jul 20, 2026</div><span class="status-badge pending">Scheduled</span><div style="display:flex;gap:6px;"><button class="action-btn edit" style="padding:4px 8px;">✏️</button><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.flash-row').remove()">🗑️</button></div></div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Influencers                               -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-influencers">
      <div class="panel-header">
        <div><h1>Influencers</h1><p>Review and manage partnership applications</p></div>
        <div style="display:flex;gap:8px;align-items:center">
          <button class="action-btn edit" onclick="exportInfluencersCsv()">📥 Export CSV</button>
        </div>
      </div>

      <div class="mini-stats" style="margin-bottom:24px">
        <div class="mini-stat"><div class="mini-stat-val" id="inf-stat-total">—</div><div class="mini-stat-label">Total Applications</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="inf-stat-pending" style="color:#f59e0b">—</div><div class="mini-stat-label">Pending Review</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="inf-stat-approved" style="color:#16a34a">—</div><div class="mini-stat-label">Approved</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="inf-stat-rejected" style="color:#e63434">—</div><div class="mini-stat-label">Rejected</div></div>
      </div>

      <div class="section-card" style="margin-bottom:24px">
        <div class="section-card-header" style="padding:18px 22px 0">
          <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <input type="text" class="form-input" placeholder="Search by name, email, handle…" id="inf-search" style="width:240px;font-size:.82rem;padding:7px 12px;" oninput="renderInfluencerTable()">
            <select id="inf-filter-status" class="form-input" style="width:150px;font-size:.82rem;padding:7px 10px;" onchange="renderInfluencerTable()">
              <option value="all">All statuses</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
            <select id="inf-filter-niche" class="form-input" style="width:160px;font-size:.82rem;padding:7px 10px;" onchange="renderInfluencerTable()">
              <option value="all">All niches</option>
              <option value="Skincare">Skincare</option>
              <option value="Beauty">Beauty & Makeup</option>
              <option value="Lifestyle">Lifestyle</option>
              <option value="Fashion">Fashion & Style</option>
              <option value="Wellness">Wellness</option>
              <option value="Haircare">Haircare</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>
        <div style="overflow-x:auto">
          <table class="data-table" id="inf-table">
            <thead>
              <tr>
                <th>Applicant</th>
                <th>Social</th>
                <th>Followers</th>
                <th>Niche</th>
                <th>Location</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="inf-tbody">
              <tr><td colspan="8" style="text-align:center;padding:48px;color:rgba(10,10,10,.35);font-size:.88rem">
                <div style="font-size:1.6rem;margin-bottom:8px">⏳</div>Loading applications…
              </td></tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Detail / Status Modal --}}
      <div id="infModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9000;overflow-y:auto;padding:40px 20px" onclick="if(event.target===this)closeInfModal()">
        <div style="background:#fff;border-radius:20px;max-width:600px;margin:0 auto;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.22)">
          <div style="background:var(--black);padding:28px 32px;position:relative">
            <button onclick="closeInfModal()" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,.12);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center">✕</button>
            <div style="display:flex;align-items:center;gap:18px">
              <div id="infM_avatar" style="width:60px;height:60px;border-radius:50%;background:var(--black);border:2px solid rgba(212,217,148,.4);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.5rem;color:var(--lime);flex-shrink:0"></div>
              <div>
                <div id="infM_name" style="font-size:1.1rem;font-weight:700;color:#fff"></div>
                <div id="infM_email" style="font-size:.8rem;color:rgba(255,255,255,.45);margin-top:2px"></div>
                <div id="infM_status_badge" style="margin-top:6px;display:inline-block;font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:999px"></div>
              </div>
            </div>
          </div>
          <div style="padding:28px 32px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:22px">
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Phone</div>
                <div id="infM_phone" style="font-size:.9rem;font-weight:600">—</div>
              </div>
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Location</div>
                <div id="infM_location" style="font-size:.9rem;font-weight:600">—</div>
              </div>
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Instagram</div>
                <div id="infM_instagram" style="font-size:.9rem;font-weight:600">—</div>
              </div>
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">TikTok</div>
                <div id="infM_tiktok" style="font-size:.9rem;font-weight:600">—</div>
              </div>
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Followers</div>
                <div id="infM_followers" style="font-size:.9rem;font-weight:600">—</div>
              </div>
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Niche</div>
                <div id="infM_niche" style="font-size:.9rem;font-weight:600">—</div>
              </div>
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Skin Type</div>
                <div id="infM_skin" style="font-size:.9rem;font-weight:600">—</div>
              </div>
              <div>
                <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:4px">Submitted</div>
                <div id="infM_date" style="font-size:.9rem;font-weight:600">—</div>
              </div>
            </div>
            <div style="margin-bottom:22px">
              <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:8px">Why they want to partner</div>
              <div id="infM_message" style="font-size:.86rem;line-height:1.7;color:rgba(10,10,10,.75);background:#f8f9fa;border-radius:12px;padding:16px 18px"></div>
            </div>
            <div style="margin-bottom:22px">
              <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35);margin-bottom:8px">Internal Notes</div>
              <textarea id="infM_notes" rows="3" class="form-input" style="width:100%;font-size:.84rem;resize:vertical" placeholder="Add internal notes about this applicant…"></textarea>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
              <button class="action-btn primary" style="background:#16a34a;border-color:#16a34a;color:#fff" onclick="updateInfStatus('approved')">✓ Approve</button>
              <button class="action-btn danger" onclick="updateInfStatus('rejected')">✕ Reject</button>
              <button class="action-btn edit" onclick="updateInfStatus('pending')">↩ Set Pending</button>
              <button class="action-btn danger" style="margin-left:auto" onclick="deleteInfluencer()">🗑️ Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Content Manager (legacy — replaced)       -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-content-legacy" style="display:none!important">
      <div class="panel-header"><div><h1>Content Manager</h1><p>Control what appears on your storefront in real-time</p></div><button class="action-btn primary" onclick="saveCmsContent()">🚀 Publish Changes</button></div>
      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'ct-homepage')">🏠 Homepage</div>
        <div class="panel-tab" onclick="switchTab(this,'ct-pages')">📄 Pages</div>
        <div class="panel-tab" onclick="switchTab(this,'ct-media')">🖼️ Media</div>
      </div>
      <div class="panel-tab-content active" id="ct-homepage">
        <div class="content-block">
          <div class="content-block-header"><strong>📢 Announcement Bar</strong><div style="display:flex;align-items:center;gap:12px;"><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Scroll speed</span><select class="form-input" style="width:120px;padding:6px 10px;font-size:.78rem;"><option>Normal (30s)</option><option>Fast (20s)</option><option>Slow (45s)</option></select><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Visible</span><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div></div>
          <div id="annItemList">
            <div class="ann-item"><span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" value="🚀" /><input type="text" class="form-input ann-text-field" value="Free shipping on orders over ₦50,000" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>
            <div class="ann-item"><span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" value="✨" /><input type="text" class="form-input ann-text-field" value="Take the Skin Quiz — Personalized routine in 60 seconds" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" value="{{ route('quiz') }}" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>
            <div class="ann-item"><span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" value="🎁" /><input type="text" class="form-input ann-text-field" value="Join Glow Starter — Earn points on every purchase" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>
            <div class="ann-item"><span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" value="🌿" /><input type="text" class="form-input ann-text-field" value="Authentic Korean skincare delivered to your door" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>
            <div class="ann-item"><span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" value="💎" /><input type="text" class="form-input ann-text-field" value="New arrivals every week — Shop now" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" value="{{ route('shop') }}" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>
            <div class="ann-item"><span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" value="🔥" /><input type="text" class="form-input ann-text-field" value="Deal of the Day — Save up to 22% today only" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>
          </div>
          <button class="action-btn edit" style="margin-top:12px;" onclick="addAnnItem()">+ Add Announcement</button>
        </div>
        <div class="content-block">
          <div class="content-block-header"><strong>🦸 Hero Section</strong><div style="display:flex;align-items:center;gap:10px;"><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Visible</span><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div></div>
          <div class="hero-preview-bar"><div style="font-size:.62rem;color:rgba(255,255,255,.4);letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px;" id="heroEyebrowPreview">Personalized Korean Beauty</div><div style="font-size:1.4rem;font-weight:700;color:#fff;line-height:1.2;margin-bottom:4px;">Your Skin, <em style="font-style:italic;">Decoded.</em> <span style="color:var(--lime);" id="heroLine3Preview">Perfected.</span></div><div style="font-size:.78rem;color:rgba(255,255,255,.5);" id="heroSubPreview">Stop guessing. Take our 60-second Skin Quiz and get a personalized Korean skincare routine.</div></div>
          <div class="form-grid">
            <div class="form-group"><label>Eyebrow Text</label><input type="text" class="form-input" value="Personalized Korean Beauty" oninput="document.getElementById('heroEyebrowPreview').textContent=this.value" /></div>
            <div class="form-group"><label>Title Line 1</label><input type="text" class="form-input" value="Your Skin," /></div>
            <div class="form-group"><label>Title Line 2 (italic)</label><input type="text" class="form-input" value="Decoded." /></div>
            <div class="form-group"><label>Title Line 3 (accent color)</label><input type="text" class="form-input" value="Perfected." oninput="document.getElementById('heroLine3Preview').textContent=this.value" /></div>
            <div class="form-group" style="grid-column:1/-1;"><label>Hero Description</label><textarea class="form-input" rows="2" oninput="document.getElementById('heroSubPreview').textContent=this.value">Stop guessing. Take our 60-second Skin Quiz and get a personalized Korean skincare routine — matched to your skin type, concerns, and lifestyle.</textarea></div>
            <div class="form-group"><label>Primary CTA Text</label><input type="text" class="form-input" value="✨ Take the Skin Quiz" /></div>
            <div class="form-group"><label>Primary CTA Link</label><input type="text" class="form-input" value="{{ route('quiz') }}" /></div>
            <div class="form-group"><label>Secondary CTA Text</label><input type="text" class="form-input" value="Browse All Products" /></div>
            <div class="form-group"><label>Secondary CTA Link</label><input type="text" class="form-input" value="{{ route('shop') }}" /></div>
            <div class="form-group" style="grid-column:1/-1;"><label>Hero Image URL</label><input type="url" class="form-input" value="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&h=900&fit=crop&q=80" /></div>
          </div>
        </div>
        <div class="content-block">
          <div class="content-block-header"><strong>🔥 Deal of the Day</strong><div style="display:flex;align-items:center;gap:12px;"><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Show countdown</span><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Section visible</span><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div></div>
          <div class="form-grid">
            <div class="form-group"><label>Featured Product</label><select class="form-input"><option selected>Laneige Sleep Mask (₦29,000)</option><option>COSRX Snail Mucin 96% Essence (₦18,000)</option><option>Beauty of Joseon SPF 50+ (₦22,000)</option><option>Dr.Jart+ Cica Ampoule (₦38,500)</option><option>Laneige Water Bank HA Serum (₦42,000)</option></select></div>
            <div class="form-group"><label>Deal Price (₦)</label><input type="number" class="form-input" value="29000" /></div>
            <div class="form-group"><label>Original Price (₦)</label><input type="number" class="form-input" value="35000" /></div>
            <div class="form-group"><label>Deal Badge Text</label><input type="text" class="form-input" value="🔥 Deal of the Day" /></div>
            <div class="form-group"><label>Deal Headline</label><input type="text" class="form-input" value="Laneige Sleep Mask — The Overnight Miracle" /></div>
            <div class="form-group"><label>Units Remaining</label><input type="number" class="form-input" value="47" /></div>
            <div class="form-group" style="grid-column:1/-1;"><label>Deal Description</label><textarea class="form-input" rows="2">Wake up to plump, glowing skin every morning. This cult-classic overnight mask has 5,000+ glowing reviews and it's yours at 22% off — today only.</textarea></div>
          </div>
        </div>
        <div class="content-block">
          <div class="content-block-header"><strong>👁️ Section Visibility</strong><span>Show or hide homepage sections instantly</span></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Quiz CTA Banner</strong><span>"Not sure where to start?" banner with skin quiz CTA</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Recommended For You</strong><span>Personalized product carousel based on skin quiz result</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>New Drops Grid</strong><span>Latest arrivals in a 4-column product grid</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Bundle Kits</strong><span>Curated product bundle cards with overlay text</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Buying Guides</strong><span>Concern-based image guide grid (7 cards)</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Community Gallery</strong><span>User-submitted transformation photos with #KominhooSkin</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Subscription CTA</strong><span>Quarterly subscription plan section with pricing cards</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Loyalty Tiers</strong><span>Glow Starter / Radiant Insider / Luxe Luminary cards</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Newsletter Section</strong><span>Email signup with "Get Skin Tips &amp; Exclusive Deals"</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap"><div class="toggle-info"><strong>Welcome Quiz Popup</strong><span>Show popup to new visitors after 0.9s delay (session-based)</span></div><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label></div>
        </div>
        <div class="content-block">
          <div class="content-block-header"><strong>💎 New Drop Section — Product Slots</strong><span>Choose which 4 products appear in the New Drop grid</span></div>
          <div class="form-grid">
            <div class="form-group"><label>Slot 1</label><select class="form-input"><option>Anua Heartleaf Serum (New)</option><option>Laneige Water Bank HA Serum (New)</option></select></div>
            <div class="form-group"><label>Slot 2</label><select class="form-input"><option>Laneige Water Bank HA Serum (New)</option><option>Anua Heartleaf Serum (New)</option></select></div>
            <div class="form-group"><label>Slot 3</label><select class="form-input"><option>Beauty of Joseon SPF 50+ (Staff Pick)</option><option>COSRX Snail Mucin Essence (Fan Fave)</option></select></div>
            <div class="form-group"><label>Slot 4</label><select class="form-input"><option>COSRX Snail Mucin Essence (Fan Fave)</option><option>Beauty of Joseon SPF 50+ (Staff Pick)</option></select></div>
            <div class="form-group"><label>Section Eyebrow Label</label><input type="text" class="form-input" value="New This Quarter" /></div>
            <div class="form-group"><label>Section Title</label><input type="text" class="form-input" value="Latest Drops" /></div>
          </div>
        </div>
      </div>
      <div class="panel-tab-content" id="ct-pages">
        <div class="content-block">
          <div class="content-block-header"><strong>❓ FAQ Page</strong><span>Last edited 3 days ago</span></div>
          <div id="faqItems">
            <div style="border:1.5px solid #e8eaed;border-radius:10px;margin-bottom:10px;overflow:hidden;"><div style="background:#fafbfc;padding:10px 16px;display:flex;align-items:center;gap:10px;"><input type="text" class="form-input" value="How long does delivery take?" style="flex:1;border:none;background:transparent;padding:0;font-weight:600;" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('div').parentElement.remove()">🗑️</button></div><textarea class="form-input" rows="2" style="border:none;border-top:1px solid #f0f2f4;border-radius:0;resize:vertical;">Standard delivery takes 2–5 business days. Express delivery (₦3,000) takes 1–2 days. Subscribers always ship free.</textarea></div>
            <div style="border:1.5px solid #e8eaed;border-radius:10px;margin-bottom:10px;overflow:hidden;"><div style="background:#fafbfc;padding:10px 16px;display:flex;align-items:center;gap:10px;"><input type="text" class="form-input" value="Are all products authentic?" style="flex:1;border:none;background:transparent;padding:0;font-weight:600;" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('div').parentElement.remove()">🗑️</button></div><textarea class="form-input" rows="2" style="border:none;border-top:1px solid #f0f2f4;border-radius:0;resize:vertical;">Yes, 100%. All products are sourced directly from official brand distributors and authorized Korean suppliers. We never stock imitations.</textarea></div>
            <div style="border:1.5px solid #e8eaed;border-radius:10px;margin-bottom:10px;overflow:hidden;"><div style="background:#fafbfc;padding:10px 16px;display:flex;align-items:center;gap:10px;"><input type="text" class="form-input" value="How does the Skin Quiz work?" style="flex:1;border:none;background:transparent;padding:0;font-weight:600;" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('div').parentElement.remove()">🗑️</button></div><textarea class="form-input" rows="2" style="border:none;border-top:1px solid #f0f2f4;border-radius:0;resize:vertical;">Answer 14 quick questions about your skin type, concerns, lifestyle, and budget. Our Skin OS engine matches you to the right products in under 60 seconds — no account needed.</textarea></div>
            <div style="border:1.5px solid #e8eaed;border-radius:10px;margin-bottom:10px;overflow:hidden;"><div style="background:#fafbfc;padding:10px 16px;display:flex;align-items:center;gap:10px;"><input type="text" class="form-input" value="Can I return opened products?" style="flex:1;border:none;background:transparent;padding:0;font-weight:600;" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('div').parentElement.remove()">🗑️</button></div><textarea class="form-input" rows="2" style="border:none;border-top:1px solid #f0f2f4;border-radius:0;resize:vertical;">Opened products are non-refundable unless damaged or incorrect. Unopened items may be returned within 7 days of delivery in original packaging.</textarea></div>
          </div>
          <button class="action-btn edit" onclick="addFaqItem()" style="margin-top:4px;">+ Add FAQ</button>
        </div>
        <div class="content-block"><div class="content-block-header"><strong>🚚 Shipping Policy</strong><span>Last edited 12 days ago</span></div><div class="form-group"><label>Policy Content</label><textarea class="form-input" rows="6" style="resize:vertical;">Kominhoo ships all across Nigeria.

Standard Delivery: 2–5 business days | FREE on orders over ₦50,000 (otherwise ₦1,500)
Express Delivery: 1–2 business days | ₦3,000 flat fee
Subscription Box: Always free, shipped quarterly

Orders are processed within 24 hours Monday–Saturday. You'll receive a tracking number via email once your order ships.</textarea></div></div>
        <div class="content-block"><div class="content-block-header"><strong>🔄 Returns &amp; Exchanges</strong><span>Last edited 12 days ago</span></div><div class="form-group"><label>Policy Content</label><textarea class="form-input" rows="4" style="resize:vertical;">We accept returns within 7 days of delivery for unopened products in original packaging. To initiate a return, email hello@kominhoo.com with your order number and reason. Exchange requests are processed within 3–5 business days.</textarea></div></div>
      </div>
      <div class="panel-tab-content" id="ct-media"></div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Automation                                -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-automation">
      <div class="panel-header"><div><h1>Automation</h1><p>Manage email campaigns, SMS reminders, and trigger-based automations</p></div><div style="display:flex;gap:8px;"><span class="status-badge active" style="font-size:.8rem;padding:6px 14px;">● All Systems Active</span><button class="action-btn primary" onclick="showToast('⚡','New automation rule saved!')">+ New Rule</button></div></div>
      <div class="kpi-grid">
        <div class="kpi-card lime"><span class="kpi-icon">📧</span><div class="kpi-label">Emails Sent (30d)</div><div class="kpi-value">12.4K</div><div class="kpi-sub"><span class="kpi-up">↑ 8%</span> vs last month</div></div>
        <div class="kpi-card blue"><span class="kpi-icon">💬</span><div class="kpi-label">SMS Sent (30d)</div><div class="kpi-value">3,820</div><div class="kpi-sub">Avg delivery rate: 98%</div></div>
        <div class="kpi-card amber"><span class="kpi-icon">📬</span><div class="kpi-label">Avg Open Rate</div><div class="kpi-value">42%</div><div class="kpi-sub"><span class="kpi-up">↑ 5%</span> above industry avg</div></div>
        <div class="kpi-card red"><span class="kpi-icon">⚡</span><div class="kpi-label">Triggers Fired (30d)</div><div class="kpi-value">8,901</div><div class="kpi-sub">Automated actions</div></div>
      </div>
      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'atab-email')">📧 Email Campaigns</div>
        <div class="panel-tab" onclick="switchTab(this,'atab-sms')">💬 SMS Reminders</div>
        <div class="panel-tab" onclick="switchTab(this,'atab-triggers')">⚡ Trigger-Based</div>
      </div>
      <div class="panel-tab-content active" id="atab-email">
        <div class="section-card"><div class="section-card-header"><div><h3>Email Campaigns</h3><p>Ongoing &amp; scheduled campaigns</p></div></div>
          <div>
            <div class="automation-rule"><div class="automation-icon email">📧</div><div class="automation-info"><strong>Acne Routine Drop — Weekly Digest</strong><span>Sent every Monday · Targets oily &amp; acne-prone skin segment</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">47.2% open</div><div class="sent-count">2,341 sent</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon email">📧</div><div class="automation-info"><strong>Hydration Routine Newsletter</strong><span>Bi-weekly · Targets dry &amp; combination skin segment</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">38.6% open</div><div class="sent-count">1,890 sent</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon email">📧</div><div class="automation-info"><strong>Post-Purchase Routine Guide</strong><span>Triggers 24h after order delivery confirmation</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">61.4% open</div><div class="sent-count">4,120 sent</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon email">📧</div><div class="automation-info"><strong>Win-Back Campaign</strong><span>Targets customers inactive for 60+ days with 15% coupon</span></div><div class="automation-stats"><div class="open-rate" style="color:#f59e0b;">22.1% open</div><div class="sent-count">890 sent</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
          </div>
        </div>
      </div>
      <div class="panel-tab-content" id="atab-sms">
        <div class="section-card"><div class="section-card-header"><div><h3>SMS Reminders</h3><p>Text-based nudges and notifications</p></div></div>
          <div>
            <div class="automation-rule"><div class="automation-icon sms">💬</div><div class="automation-info"><strong>Reorder Reminder</strong><span>Sent 25 days after last product purchase · Personalised with product name</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">89% CTR</div><div class="sent-count">1,204 sent</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon sms">💬</div><div class="automation-info"><strong>Subscription Renewal Alert</strong><span>Sent 5 days before subscription billing date</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">94% CTR</div><div class="sent-count">412 sent</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon sms">💬</div><div class="automation-info"><strong>Order Shipped Notification</strong><span>Triggered when order status changes to "Shipped" with tracking link</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">97% CTR</div><div class="sent-count">2,204 sent</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
          </div>
        </div>
      </div>
      <div class="panel-tab-content" id="atab-triggers">
        <div class="section-card"><div class="section-card-header"><div><h3>Trigger-Based Automations</h3><p>Actions fired by customer events</p></div></div>
          <div>
            <div class="automation-rule"><div class="automation-icon trigger">⚡</div><div class="automation-info"><strong>After Quiz Completion</strong><span>Email: Your personalised routine is ready + product recommendations</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">72.3% open</div><div class="sent-count">3,401 fired</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon trigger">⚡</div><div class="automation-info"><strong>After First Purchase</strong><span>Email: Welcome to Kominhoo + how-to-use guide for purchased products</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">68.1% open</div><div class="sent-count">1,820 fired</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon trigger">⚡</div><div class="automation-info"><strong>Birthday Month</strong><span>SMS + Email: Happy birthday + 10% exclusive discount code</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">81.4% open</div><div class="sent-count">340 fired</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon trigger">⚡</div><div class="automation-info"><strong>Tier Upgrade</strong><span>Email: Congratulations on reaching [new tier] + unlocked benefits</span></div><div class="automation-stats"><div class="open-rate" style="color:#16a34a;">84.2% open</div><div class="sent-count">210 fired</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
            <div class="automation-rule"><div class="automation-icon trigger">⚡</div><div class="automation-info"><strong>Subscription Paused</strong><span>Email: We noticed you paused + option to skip or modify box</span></div><div class="automation-stats"><div class="open-rate" style="color:#f59e0b;">34.8% open</div><div class="sent-count">128 fired</div></div><div style="display:flex;gap:8px;align-items:center;"><label class="toggle"><input type="checkbox" checked /><span class="toggle-slider"></span></label><button class="action-btn edit" style="padding:5px 10px;">✏️ Edit</button></div></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Roles & Permissions                       -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-roles">
      <div class="panel-header"><div><h1>Roles &amp; Permissions</h1><p>Manage team member access levels and platform permissions</p></div><button class="action-btn primary" onclick="document.getElementById('inviteTeamOverlay').classList.add('open')">+ Invite Team Member</button></div>
      <div class="dash-row">
        <div class="section-card" style="flex:1;">
          <div class="section-card-header"><div><h3>Team Members</h3><p>5 members with platform access</p></div></div>
          <div id="teamMembersList">
            <div class="team-member-row"><div class="team-avatar" style="background:var(--lime);color:var(--black);">KA</div><div class="team-member-info"><strong>Kolade Admin</strong><span>akolade.s@kominhoo.ng · Active now</span></div><span class="role-badge super">👑 Super Admin</span><div style="display:flex;gap:6px;margin-left:8px;"><button class="action-btn edit" style="padding:4px 10px;">✏️ Edit</button></div></div>
            <div class="team-member-row"><div class="team-avatar" style="background:#dbeafe;color:#1e40af;">FO</div><div class="team-member-info"><strong>Funke Osei</strong><span>funke@kominhoo.ng · 2 hrs ago</span></div><span class="role-badge ops">⚙️ Operations</span><div style="display:flex;gap:6px;margin-left:8px;"><button class="action-btn edit" style="padding:4px 10px;">✏️ Edit</button><button class="action-btn danger" style="padding:4px 8px;" onclick="if(confirm('Remove access?'))this.closest('.team-member-row').remove()">🗑️</button></div></div>
            <div class="team-member-row"><div class="team-avatar" style="background:#d1fae5;color:#065f46;">AM</div><div class="team-member-info"><strong>Amina Mohammed</strong><span>amina@kominhoo.ng · Yesterday</span></div><span class="role-badge support">💬 Customer Support</span><div style="display:flex;gap:6px;margin-left:8px;"><button class="action-btn edit" style="padding:4px 10px;">✏️ Edit</button><button class="action-btn danger" style="padding:4px 8px;" onclick="if(confirm('Remove access?'))this.closest('.team-member-row').remove()">🗑️</button></div></div>
            <div class="team-member-row"><div class="team-avatar" style="background:#ede9fe;color:#5b21b6;">TI</div><div class="team-member-info"><strong>Temi Idowu</strong><span>temi@kominhoo.ng · Apr 15, 2026</span></div><span class="role-badge marketing">📣 Marketing</span><div style="display:flex;gap:6px;margin-left:8px;"><button class="action-btn edit" style="padding:4px 10px;">✏️ Edit</button><button class="action-btn danger" style="padding:4px 8px;" onclick="if(confirm('Remove access?'))this.closest('.team-member-row').remove()">🗑️</button></div></div>
            <div class="team-member-row"><div class="team-avatar" style="background:#f0f2f4;color:rgba(10,10,10,.6);">JA</div><div class="team-member-info"><strong>Jide Adewale</strong><span>jide@kominhoo.ng · Apr 10, 2026</span></div><span class="role-badge employee">👤 Employee</span><div style="display:flex;gap:6px;margin-left:8px;"><button class="action-btn edit" style="padding:4px 10px;">✏️ Edit</button><button class="action-btn danger" style="padding:4px 8px;" onclick="if(confirm('Remove access?'))this.closest('.team-member-row').remove()">🗑️</button></div></div>
          </div>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card-header"><div><h3>Permissions Matrix</h3><p>What each role can access and modify</p></div></div>
        <div style="overflow-x:auto;padding:0 4px 4px;">
          <table class="permissions-matrix">
            <thead><tr><th>Module</th><th>👑 Super</th><th>⚙️ Ops</th><th>💬 Support</th><th>📣 Marketing</th><th>👤 Employee</th></tr></thead>
            <tbody>
              <tr><td>Products &amp; Inventory</td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-limited">View</span></td></tr>
              <tr><td>Orders &amp; Fulfilment</td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-limited">View</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Customer Profiles</td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-limited">View</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Subscriptions</td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-limited">Modify</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Loyalty &amp; Points</td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-limited">Award</span></td><td><span class="perm-limited">View</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Content Manager</td><td><span class="perm-yes">✓</span></td><td><span class="perm-limited">View</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Promotions &amp; Coupons</td><td><span class="perm-yes">✓</span></td><td><span class="perm-limited">View</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Automation &amp; Emails</td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Analytics</td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Reviews &amp; Community</td><td><span class="perm-yes">✓</span></td><td><span class="perm-limited">Approve</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td></tr>
              <tr><td>Roles &amp; Settings</td><td><span class="perm-yes">✓</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-no">✗</span></td><td><span class="perm-no">✗</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Spa & Clinic                              -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-spa">
      <div class="panel-header"><div><h1>Spa &amp; Clinic</h1><p>Manage treatment appointments, services, and skin-based recommendations</p></div><button class="action-btn primary" onclick="showToast('🏥','New appointment scheduled!')">+ Schedule Appointment</button></div>
      <div class="kpi-grid">
        <div class="kpi-card lime"><span class="kpi-icon">📅</span><div class="kpi-label">Upcoming Appointments</div><div class="kpi-value">12</div><div class="kpi-sub">Next 7 days</div></div>
        <div class="kpi-card blue"><span class="kpi-icon">🧴</span><div class="kpi-label">Active Treatments</div><div class="kpi-value">8</div><div class="kpi-sub">Ongoing programmes</div></div>
        <div class="kpi-card amber"><span class="kpi-icon">✨</span><div class="kpi-label">Skin-Match Rate</div><div class="kpi-value">94%</div><div class="kpi-sub">Services matched to skin profile</div></div>
        <div class="kpi-card red"><span class="kpi-icon">💰</span><div class="kpi-label">Spa Revenue (MTD)</div><div class="kpi-value">₦380K</div><div class="kpi-sub"><span class="kpi-up">↑ 22%</span> vs last month</div></div>
      </div>
      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'spatab-appts')">📅 Appointments</div>
        <div class="panel-tab" onclick="switchTab(this,'spatab-services')">🧴 Services</div>
        <div class="panel-tab" onclick="switchTab(this,'spatab-recs')">✨ Skin Recommendations</div>
      </div>
      <div class="panel-tab-content active" id="spatab-appts">
        <div class="appointment-card"><div class="appt-date-box"><div class="appt-day">19</div><div class="appt-month">Apr</div></div><div class="appt-info"><strong>Adaeze Okonkwo — Deep Hydration Facial</strong><span>10:00 AM · 90 mins · Lagos Island Clinic · Combination skin · Hyperpigmentation concern</span></div><div class="appt-actions"><button class="action-btn primary" onclick="showToast('✅','Appointment confirmed!')">Confirm</button><button class="action-btn danger" onclick="if(confirm('Cancel appointment?'))this.closest('.appointment-card').remove()">Cancel</button></div></div>
        <div class="appointment-card"><div class="appt-date-box"><div class="appt-day">20</div><div class="appt-month">Apr</div></div><div class="appt-info"><strong>Ngozi Kalu — Acne Extraction &amp; Treatment</strong><span>2:00 PM · 60 mins · Victoria Island Clinic · Oily skin · Acne &amp; Breakouts concern</span></div><div class="appt-actions"><button class="action-btn primary" onclick="showToast('✅','Appointment confirmed!')">Confirm</button><button class="action-btn danger" onclick="if(confirm('Cancel appointment?'))this.closest('.appointment-card').remove()">Cancel</button></div></div>
        <div class="appointment-card"><div class="appt-date-box" style="background:#f59e0b;"><div class="appt-day">22</div><div class="appt-month">Apr</div></div><div class="appt-info"><strong>Chidinma Eze — Brightening Peel Session</strong><span>11:30 AM · 75 mins · Lekki Clinic · Dry skin · Dark spots &amp; Uneven tone concern</span></div><div class="appt-actions"><button class="action-btn primary" onclick="showToast('✅','Appointment confirmed!')">Confirm</button><button class="action-btn danger" onclick="if(confirm('Cancel appointment?'))this.closest('.appointment-card').remove()">Cancel</button></div></div>
        <div class="appointment-card"><div class="appt-date-box"><div class="appt-day">24</div><div class="appt-month">Apr</div></div><div class="appt-info"><strong>Yetunde Adeyemi — Anti-Aging Collagen Boost</strong><span>3:30 PM · 120 mins · Lagos Island Clinic · Normal skin · Fine lines &amp; Firmness concern</span></div><div class="appt-actions"><button class="action-btn edit" onclick="showToast('📤','Reminder sent to client!')">Send Reminder</button><button class="action-btn danger" onclick="if(confirm('Cancel appointment?'))this.closest('.appointment-card').remove()">Cancel</button></div></div>
      </div>
      <div class="panel-tab-content" id="spatab-services">
        <div class="service-card"><div class="service-card-header"><strong>Deep Hydration Facial</strong><div><span class="svc-price">₦45,000</span> &nbsp; <span style="font-size:.75rem;color:rgba(10,10,10,.4);">90 mins</span></div></div><div class="service-concerns"><span class="tag-chip concern">Dehydration</span><span class="tag-chip concern">Dry Skin</span><span class="tag-chip concern">Dullness</span></div><div style="font-size:.78rem;color:rgba(10,10,10,.45);margin-bottom:8px;">Linked products:</div><div class="service-linked-products"><span class="tag-chip">Laneige Water Sleeping Mask</span><span class="tag-chip">COSRX Hyaluronic Acid</span><span class="tag-chip">Innisfree Green Tea Cream</span></div><div style="margin-top:10px;display:flex;gap:8px;"><button class="action-btn edit">✏️ Edit</button><button class="action-btn tag-btn">Link Products</button></div></div>
        <div class="service-card"><div class="service-card-header"><strong>Acne Extraction &amp; BHA Treatment</strong><div><span class="svc-price">₦35,000</span> &nbsp; <span style="font-size:.75rem;color:rgba(10,10,10,.4);">60 mins</span></div></div><div class="service-concerns"><span class="tag-chip concern">Acne</span><span class="tag-chip concern">Oily Skin</span><span class="tag-chip concern">Breakouts</span></div><div style="font-size:.78rem;color:rgba(10,10,10,.45);margin-bottom:8px;">Linked products:</div><div class="service-linked-products"><span class="tag-chip">COSRX Salicylic Acid</span><span class="tag-chip">Some By Mi Snail True Cica Toner</span><span class="tag-chip">COSRX BHA Blackhead Power</span></div><div style="margin-top:10px;display:flex;gap:8px;"><button class="action-btn edit">✏️ Edit</button><button class="action-btn tag-btn">Link Products</button></div></div>
        <div class="service-card"><div class="service-card-header"><strong>Brightening &amp; Pigmentation Peel</strong><div><span class="svc-price">₦55,000</span> &nbsp; <span style="font-size:.75rem;color:rgba(10,10,10,.4);">75 mins</span></div></div><div class="service-concerns"><span class="tag-chip concern">Hyperpigmentation</span><span class="tag-chip concern">Dark Spots</span><span class="tag-chip concern">Uneven Tone</span></div><div style="font-size:.78rem;color:rgba(10,10,10,.45);margin-bottom:8px;">Linked products:</div><div class="service-linked-products"><span class="tag-chip">Beauty of Joseon Glow Serum</span><span class="tag-chip">Numbuzin No.5 Vitamin C</span><span class="tag-chip">COSRX Vitamin C 23 Serum</span></div><div style="margin-top:10px;display:flex;gap:8px;"><button class="action-btn edit">✏️ Edit</button><button class="action-btn tag-btn">Link Products</button></div></div>
      </div>
      <div class="panel-tab-content" id="spatab-recs">
        <div class="section-card"><div class="section-card-header"><div><h3>Skin-Based Spa Recommendations</h3><p>Auto-generated from customer quiz profiles</p></div><button class="action-btn edit" onclick="showToast('🔄','Recommendations refreshed!')">🔄 Refresh</button></div>
          <div>
            <div class="spa-rec-row"><div class="spa-rec-avatar" style="background:#fef3c7;color:#92400e;">AO</div><div class="spa-rec-info"><strong>Adaeze Okonkwo — Combination · Hyperpigmentation</strong><div class="spa-rec-chips"><span class="tag-chip concern">Brightening Peel</span><span class="tag-chip routine">Vitamin C Serum add-on</span><span class="tag-chip skin">Monthly facial</span></div></div><button class="action-btn primary" style="padding:6px 12px;" onclick="showToast('📅','Appointment invitation sent!')">Invite</button></div>
            <div class="spa-rec-row"><div class="spa-rec-avatar" style="background:#dbeafe;color:#1e40af;">NK</div><div class="spa-rec-info"><strong>Ngozi Kalu — Oily · Acne &amp; Breakouts</strong><div class="spa-rec-chips"><span class="tag-chip concern">Acne Extraction</span><span class="tag-chip routine">BHA peel programme</span><span class="tag-chip skin">Bi-weekly treatment</span></div></div><button class="action-btn primary" style="padding:6px 12px;" onclick="showToast('📅','Appointment invitation sent!')">Invite</button></div>
            <div class="spa-rec-row"><div class="spa-rec-avatar" style="background:#d1fae5;color:#065f46;">CE</div><div class="spa-rec-info"><strong>Chidinma Eze — Dry · Redness &amp; Sensitivity</strong><div class="spa-rec-chips"><span class="tag-chip concern">Calming Facial</span><span class="tag-chip routine">Barrier repair programme</span><span class="tag-chip skin">Monthly treatment</span></div></div><button class="action-btn primary" style="padding:6px 12px;" onclick="showToast('📅','Appointment invitation sent!')">Invite</button></div>
            <div class="spa-rec-row"><div class="spa-rec-avatar" style="background:#ede9fe;color:#5b21b6;">YA</div><div class="spa-rec-info"><strong>Yetunde Adeyemi — Normal · Anti-Aging</strong><div class="spa-rec-chips"><span class="tag-chip concern">Collagen Boost Facial</span><span class="tag-chip routine">LED therapy add-on</span><span class="tag-chip skin">Quarterly deep treatment</span></div></div><button class="action-btn primary" style="padding:6px 12px;" onclick="showToast('📅','Appointment invitation sent!')">Invite</button></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Skin Results                               -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-skin-results">
      <div class="panel-header">
        <div>
          <h1>Skin Results</h1>
          <p>Quiz results for all signed-in users — skin type, scores, and top concerns</p>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
          <input type="text" class="form-input" id="skinResultsSearch" placeholder="Search by name or skin type…" style="width:220px;font-size:.82rem;padding:7px 12px;" oninput="filterSkinResults(this.value)">
          <button class="action-btn edit" onclick="showToast('📥','Export coming soon!')">📥 Export CSV</button>
        </div>
      </div>

      {{-- Summary KPIs --}}
      @php
        $srTotal      = count($quizResults);
        $srTypeCounts = collect($quizResults)->countBy(fn($r) => $r['skin_type'] ?? 'Unknown')->sortDesc();
        $srTopType    = $srTypeCounts->keys()->first() ?? '—';
        $srTopCount   = $srTypeCounts->first() ?? 0;
      @endphp
      <div class="mini-stats" style="margin-bottom:28px">
        <div class="mini-stat"><div class="mini-stat-val" style="color:var(--lime-dark)">{{ $srTotal }}</div><div class="mini-stat-label">Total Results</div></div>
        <div class="mini-stat"><div class="mini-stat-val">{{ $srTypeCounts['Oily'] ?? 0 }}</div><div class="mini-stat-label">Oily</div></div>
        <div class="mini-stat"><div class="mini-stat-val">{{ $srTypeCounts['Combination'] ?? 0 }}</div><div class="mini-stat-label">Combination</div></div>
        <div class="mini-stat"><div class="mini-stat-val">{{ $srTypeCounts['Dry'] ?? 0 }}</div><div class="mini-stat-label">Dry</div></div>
        <div class="mini-stat"><div class="mini-stat-val">{{ $srTypeCounts['Sensitive'] ?? 0 }}</div><div class="mini-stat-label">Sensitive</div></div>
        <div class="mini-stat"><div class="mini-stat-val">{{ $srTypeCounts['Normal'] ?? 0 }}</div><div class="mini-stat-label">Normal</div></div>
        <div class="mini-stat"><div class="mini-stat-val" style="color:var(--lime-dark)">{{ $srTopType }}</div><div class="mini-stat-label">Most Common</div></div>
      </div>

      <div class="section-card" style="padding:0;overflow:hidden">
        <table class="data-table" id="skinResultsTable">
          <thead>
            <tr>
              <th>User</th>
              <th>Skin Type</th>
              <th>Concerns</th>
              <th>Severity</th>
              <th>Environment</th>
              <th>Budget</th>
              <th>Hydration</th>
              <th>Acne Risk</th>
              <th>Oil Level</th>
              <th>Sensitivity</th>
              <th>Barrier</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($quizResults as $qr)
              @php
                $qrUser     = is_array($qr['user'] ?? null) ? $qr['user'] : [];
                $qrIsGuest  = empty($qrUser);
                $qrName     = $qrUser['name'] ?? 'Guest';
                $qrEmail    = $qrUser['email'] ?? '(not signed in)';
                $qrAvatar   = $qrUser['avatar'] ?? null;
                $qrInit     = $qrIsGuest ? '?' : strtoupper(substr($qrName, 0, 1));
                $qrType     = $qr['skin_type'] ?? '—';
                $qrAnswers  = is_array($qr['answers']) ? $qr['answers'] : [];
                $qrScores   = is_array($qr['skin_scores'] ?? null) ? $qr['skin_scores'] : [];
                $qrConcerns = $qrAnswers['concerns'] ?? [];
                if (is_string($qrConcerns)) $qrConcerns = array_filter(explode(',', $qrConcerns));
                $qrConcernsStr = implode(', ', array_map(fn($c) => ucfirst(str_replace('_', ' ', $c)), (array)$qrConcerns));
                $qrDate = $qr['created_at'] ? \Carbon\Carbon::parse($qr['created_at'])->format('d M Y') : '—';

                $typeColorMap = ['Oily'=>'#f59e0b','Dry'=>'#4f94ea','Combination'=>'#a5c400','Normal'=>'#16a34a','Sensitive'=>'#e8382e'];
                $typeColor = $typeColorMap[$qrType] ?? '#9ca3af';

                $scoreBadge = function(string $metric, ?array $scores) use ($typeColor): string {
                  if (empty($scores[$metric])) return '<span style="color:rgba(10,10,10,.3)">—</span>';
                  $v = (int)$scores[$metric];
                  $color = in_array($metric, ['Acne Risk','Sensitivity','Oil Level'])
                    ? ($v >= 7 ? '#e8382e' : ($v >= 5 ? '#f59e0b' : '#16a34a'))
                    : ($v >= 7 ? '#16a34a' : ($v >= 5 ? '#4f94ea' : '#e8382e'));
                  return "<span style=\"font-weight:700;color:{$color}\">{$v}/10</span>";
                };
              @endphp
              <tr data-name="{{ strtolower($qrName) }}" data-type="{{ strtolower($qrType) }}">
                <td>
                  <div style="display:flex;align-items:center;gap:10px">
                    @if($qrAvatar)
                      <img src="{{ $qrAvatar }}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0">
                    @elseif($qrIsGuest)
                      <div style="width:34px;height:34px;border-radius:50%;background:#e8eaed;color:rgba(10,10,10,.35);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0">?</div>
                    @else
                      <div style="width:34px;height:34px;border-radius:50%;background:var(--black);color:var(--lime);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0">{{ $qrInit }}</div>
                    @endif
                    <div>
                      <div style="font-weight:700;font-size:.85rem;display:flex;align-items:center;gap:6px">
                        {{ $qrName }}
                        @if($qrIsGuest)
                          <span style="font-size:.6rem;font-weight:700;background:#f4f5f7;color:rgba(10,10,10,.4);padding:2px 6px;border-radius:4px;">GUEST</span>
                        @endif
                      </div>
                      <div style="font-size:.7rem;color:rgba(10,10,10,.4)">{{ $qrEmail }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <span style="background:{{ $typeColor }}22;color:{{ $typeColor }};font-weight:700;font-size:.78rem;padding:3px 10px;border-radius:999px;">{{ $qrType }}</span>
                </td>
                <td style="font-size:.78rem;color:rgba(10,10,10,.6);max-width:160px">{{ $qrConcernsStr ?: '—' }}</td>
                <td style="font-size:.82rem">{{ ucfirst($qrAnswers['severity'] ?? '—') }}</td>
                <td style="font-size:.82rem">{{ ucfirst(str_replace('_', ' ', $qrAnswers['environment'] ?? '—')) }}</td>
                <td style="font-size:.82rem">{{ ucfirst($qrAnswers['budget'] ?? '—') }}</td>
                <td>{!! $scoreBadge('Hydration', $qrScores) !!}</td>
                <td>{!! $scoreBadge('Acne Risk', $qrScores) !!}</td>
                <td>{!! $scoreBadge('Oil Level', $qrScores) !!}</td>
                <td>{!! $scoreBadge('Sensitivity', $qrScores) !!}</td>
                <td>{!! $scoreBadge('Barrier Health', $qrScores) !!}</td>
                <td style="font-size:.78rem;color:rgba(10,10,10,.45)">{{ $qrDate }}</td>
                <td>
                  <button class="action-btn edit" style="font-size:.72rem"
                    onclick="openSkinResultDetail({{ json_encode($qr) }})">Detail</button>
                </td>
              </tr>
            @empty
              <tr><td colspan="13" style="text-align:center;padding:40px;color:rgba(10,10,10,.35);font-size:.88rem">No quiz results yet. Results appear here once signed-in users complete the skin quiz.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Detail drawer / modal --}}
      <div id="skinResultDetailModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9000;overflow-y:auto;padding:40px 20px" onclick="if(event.target===this)document.getElementById('skinResultDetailModal').style.display='none'">
        <div style="background:#fff;border-radius:20px;max-width:600px;margin:0 auto;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.22)">
          <div style="background:var(--black);padding:28px 32px;position:relative">
            <button onclick="document.getElementById('skinResultDetailModal').style.display='none'" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,.12);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem">✕</button>
            <div style="display:flex;align-items:center;gap:18px">
              <div id="srModal_avatar" style="width:58px;height:58px;border-radius:50%;border:3px solid rgba(212,217,148,.4);background:var(--black);display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--lime);font-family:var(--font-display);flex-shrink:0"></div>
              <div>
                <div id="srModal_name" style="font-size:1.05rem;font-weight:700;color:#fff"></div>
                <div id="srModal_email" style="font-size:.78rem;color:rgba(255,255,255,.45);margin-top:2px"></div>
                <div id="srModal_type" style="margin-top:8px;font-size:.72rem;font-weight:700;padding:3px 12px;border-radius:999px;display:inline-block;background:rgba(212,217,148,.15);color:var(--lime)"></div>
              </div>
            </div>
          </div>
          <div style="padding:28px 32px">
            <h4 style="margin:0 0 16px;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35)">Skin Scores</h4>
            <div id="srModal_scores" style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px"></div>
            <h4 style="margin:0 0 12px;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.35)">Full Answers</h4>
            <div id="srModal_answers" style="display:grid;grid-template-columns:1fr 1fr;gap:10px"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Quiz Config                               -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-quiz-config">
      <div class="panel-header">
        <div><h1>Quiz Configuration</h1><p>Edit questions, answers, stage transitions, and settings for the Skin OS quiz</p></div>
        <div style="display:flex;gap:10px;align-items:center;">
          <button class="action-btn edit" onclick="resetQuizConfig()">↩ Reset Defaults</button>
          <button class="action-btn primary" onclick="saveQuizConfig()">💾 Save Changes</button>
        </div>
      </div>
      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'qtab-questions')">📝 Questions</div>
        <div class="panel-tab" onclick="switchTab(this,'qtab-stages')">🎯 Stage Transitions</div>
        <div class="panel-tab" onclick="switchTab(this,'qtab-concerns')">⚠️ Concerns</div>
        <div class="panel-tab" onclick="switchTab(this,'qtab-weights')">⚖️ Tag Weights</div>
        <div class="panel-tab" onclick="switchTab(this,'qtab-settings')">⚙️ Settings</div>
      </div>

      <!-- Questions Tab -->
      <div class="panel-tab-content active" id="qtab-questions">
        <div id="qz-accordion"></div>
      </div>

      <!-- Stage Transitions Tab -->
      <div class="panel-tab-content" id="qtab-stages">
        <div id="qz-stages-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;"></div>
      </div>

      <!-- Concerns Tab -->
      <div class="panel-tab-content" id="qtab-concerns">
        <div class="section-card">
          <div class="section-card-header"><div><h3>Skin Concerns</h3><p>Options shown on the multi-select slide (Slide 4)</p></div><button class="action-btn edit" onclick="addConcernRow()">+ Add Concern</button></div>
          <div style="padding:0 22px 22px;">
            <table class="data-table" style="font-size:.82rem;">
              <thead><tr><th>Emoji</th><th>Value Key</th><th>Display Label</th><th></th></tr></thead>
              <tbody id="concerns-editor-body"></tbody>
            </table>
            <div style="margin-top:16px;display:flex;align-items:center;gap:12px;">
              <label style="font-size:.82rem;font-weight:600;">Max Selections:</label>
              <input type="number" class="form-input" id="qz-max-sel" value="3" min="1" max="10" style="width:80px;padding:6px 10px;" />
            </div>
          </div>
        </div>
      </div>

      <!-- Tag Weights Tab -->
      <div class="panel-tab-content" id="qtab-weights">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Tag Weight Matrix</h3><p>How quiz answers map to product tags for recommendations</p></div>
            <button class="action-btn edit" onclick="addTagWeightRow()">+ Add Mapping</button>
          </div>
          <div style="padding:0 22px 22px;">
            <p style="font-size:.85rem;color:rgba(10,10,10,.5);margin:0 0 16px;">Higher weights prioritize that tag in product recommendations. Tag Type controls the chip colour.</p>
            <div style="overflow-x:auto;">
              <table class="data-table" style="font-size:.82rem;min-width:760px;">
                <thead><tr><th>Question</th><th>Answer Option</th><th>Maps To Tag</th><th>Tag Type</th><th style="width:80px">Weight</th><th></th></tr></thead>
                <tbody id="tag-weights-body"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Settings Tab -->
      <div class="panel-tab-content" id="qtab-settings">
        <div class="section-card" style="margin-bottom:20px;">
          <div class="section-card-header"><div><h3>Quiz Settings</h3><p>Global configuration</p></div></div>
          <div style="padding:22px;">
            <div class="toggle-wrap"><div class="toggle-info"><strong>Quiz Enabled</strong><span>Show the Skin OS quiz on the frontend</span></div><label class="toggle"><input type="checkbox" id="qz-enabled" checked /><span class="toggle-slider"></span></label></div>
            <div style="margin-top:16px;"><div class="form-group"><label>Loading Animation Delay (ms)</label><input type="number" class="form-input" id="qz-loading-delay" value="3500" min="1000" max="10000" step="100" style="max-width:200px;" /></div></div>
          </div>
        </div>
        <div class="section-card">
          <div class="section-card-header"><div><h3>Loading Screen Steps</h3><p>Analysis steps shown while processing quiz results</p></div><button class="action-btn edit" onclick="addLoadingStep()">+ Add Step</button></div>
          <div style="padding:0 22px 22px;" id="qz-loading-steps"></div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Gift Cards                                -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-giftcards">
      <div class="panel-header">
        <div><h1>Gift Cards</h1><p>Issue, manage, and track gift card redemptions</p></div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <button class="action-btn" onclick="gcLoadAll()" id="gc-refresh-btn">↺ Refresh</button>
          <button class="action-btn edit" onclick="gcExportCsv()">📥 Export CSV</button>
          <button class="action-btn primary" onclick="document.getElementById('gcIssueOverlay').classList.add('open')">+ Issue Gift Card</button>
          <button class="action-btn" onclick="document.getElementById('gcAddOverlay').classList.add('open')">+ Denomination</button>
        </div>
      </div>
      <div class="mini-stats" style="margin-bottom:28px">
        <div class="mini-stat"><div class="mini-stat-val" id="gcStatValue" style="color:#a5c400;">—</div><div class="mini-stat-label">Total Issued (value)</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="gcStatTotal">—</div><div class="mini-stat-label">Cards Issued</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="gcStatRedeemed" style="color:#16a34a;">—</div><div class="mini-stat-label">Redeemed</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="gcStatOutstanding" style="color:#f59e0b;">—</div><div class="mini-stat-label">Outstanding</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="gcStatRate">—</div><div class="mini-stat-label">Redemption Rate</div></div>
      </div>

      <h3 style="font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.45);margin-bottom:16px">Denominations</h3>
      <div class="gc-admin-grid" style="margin-bottom:32px" id="gcDenomGrid">
        <div style="text-align:center;padding:20px;color:rgba(10,10,10,.35);font-size:.85rem;grid-column:1/-1">Loading denominations…</div>
      </div>

      <h3 style="font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(10,10,10,.45);margin-bottom:12px">Gift Card Transactions</h3>
      <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
        <input type="text" class="form-input" id="gc-search" placeholder="Search code, name, email…" oninput="gcFilterTable()" style="max-width:280px;font-size:.82rem;padding:7px 12px;">
        <select class="form-input" id="gc-filter-status" onchange="gcFilterTable()" style="width:150px;font-size:.82rem;padding:7px 10px;">
          <option value="">All Statuses</option>
          <option value="active">Active</option>
          <option value="partially_used">Partially Used</option>
          <option value="redeemed">Redeemed</option>
          <option value="expired">Expired</option>
        </select>
      </div>
      <div id="gc-table-wrap">
        <div style="text-align:center;padding:40px;color:rgba(10,10,10,.35)">Loading gift cards…</div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Settings                                  -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-settings">
      <div class="panel-header"><div><h1>Settings</h1><p>Platform configuration and preferences</p></div><button class="action-btn primary">💾 Save All Changes</button></div>
      <div class="section-card" style="margin-bottom:20px;">
        <div class="section-card-header"><div><h3>Store Settings</h3><p>General store configuration</p></div></div>
        <div class="settings-grid">
          <div class="form-group"><label>Store Name</label><input type="text" class="form-input" value="Kominhoo Beauty" /></div>
          <div class="form-group"><label>Store Email</label><input type="email" class="form-input" value="hello@kominhoo.com" /></div>
          <div class="form-group"><label>Currency</label><select class="form-input"><option>NGN (₦) — Nigerian Naira</option></select></div>
          <div class="form-group"><label>Default Language</label><select class="form-input"><option>English</option></select></div>
          <div class="form-group"><label>Free Shipping Threshold</label><input type="text" class="form-input" value="₦50,000" /></div>
          <div class="form-group"><label>Express Delivery Fee</label><input type="text" class="form-input" value="₦3,000" /></div>
        </div>
      </div>
      <div class="section-card" style="margin-bottom:20px;">
        <div class="section-card-header"><div><h3>Subscription Settings</h3><p>Quarterly box configuration</p></div></div>
        <div class="settings-grid">
          <div class="form-group"><label>Next Dispatch Date</label><input type="date" class="form-input" value="2026-05-01" /></div>
          <div class="form-group"><label>Swap Deadline</label><input type="date" class="form-input" value="2026-04-25" /></div>
          <div class="form-group"><label>Beginner Plan Price (₦)</label><input type="number" class="form-input" value="40000" /></div>
          <div class="form-group"><label>Master Plan Price (₦)</label><input type="number" class="form-input" value="70000" /></div>
          <div class="form-group"><label>Advanced Plan Price (₦)</label><input type="number" class="form-input" value="100000" /></div>
          <div class="form-group"><label>Max Swaps per Subscriber</label><input type="number" class="form-input" value="2" /></div>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card-header"><div><h3>Loyalty Program Settings</h3><p>Points and tier thresholds</p></div></div>
        <div class="settings-grid">
          <div class="form-group"><label>Points per ₦1,000 Spent</label><input type="number" class="form-input" value="10" /></div>
          <div class="form-group"><label>Radiant Insider Threshold (pts)</label><input type="number" class="form-input" value="2000" /></div>
          <div class="form-group"><label>Luxe Luminary Threshold (pts)</label><input type="number" class="form-input" value="5000" /></div>
          <div class="form-group"><label>Glow Starter Multiplier</label><input type="number" step="0.1" class="form-input" value="1.0" /></div>
          <div class="form-group"><label>Radiant Insider Multiplier</label><input type="number" step="0.1" class="form-input" value="1.5" /></div>
          <div class="form-group"><label>Luxe Luminary Multiplier</label><input type="number" step="0.1" class="form-input" value="2.0" /></div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PANEL: Security Events                           -->
    <!-- ═══════════════════════════════════════════════ -->
    <div class="admin-panel" id="panel-security-events">
      <div class="panel-header">
        <div>
          <h1>Security Events</h1>
          <p>Monitor user security activity — password changes, setting updates, and account deletion requests</p>
        </div>
        <div style="display:flex;gap:8px;">
          <select class="form-input" id="sec-type-filter" style="width:200px;font-size:.82rem;padding:7px 12px" onchange="secLoadEvents()">
            <option value="">All Events</option>
            <option value="password_change">Password Changes</option>
            <option value="settings_change">Settings Changes</option>
            <option value="account_deletion_request">Deletion Requests</option>
          </select>
          <button class="action-btn edit" onclick="secLoadEvents()">↺ Refresh</button>
          <button class="action-btn danger" onclick="secClearEvents()">🗑️ Clear Log</button>
        </div>
      </div>

      {{-- KPI strip --}}
      <div class="mini-stats cols-3" id="sec-kpi-strip">
        <div class="mini-stat"><div class="mini-stat-val" id="sec-kpi-total">—</div><div class="mini-stat-label">Total Events</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="sec-kpi-high" style="color:#f59e0b">—</div><div class="mini-stat-label">High Severity</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="sec-kpi-today">—</div><div class="mini-stat-label">Today</div></div>
      </div>

      <div class="section-card">
        <div class="section-card-header">
          <div><h3>Event Log</h3><p id="sec-event-count">Loading…</p></div>
        </div>
        <div id="sec-events-list">
          <div style="text-align:center;padding:60px;color:#9CA3AF;font-size:.9rem">Loading security events…</div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- WALLET MANAGEMENT PANEL                                        --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="admin-panel" id="panel-wallet">
      <div class="panel-header">
        <div>
          <h1>Wallet Management</h1>
          <p>Balances · Full transaction ledger · Bonus rules · Targeted grants</p>
        </div>
        <div style="display:flex;gap:8px;">
          <button class="action-btn edit" onclick="walletLoadOverview()">↺ Refresh</button>
          <button class="action-btn primary" onclick="switchTab(document.querySelector('#panel-wallet .panel-tab:nth-child(2)'), 'wtab-transactions');walletLoadTransactions()">📋 All Transactions</button>
          <button class="action-btn primary" onclick="switchTab(document.querySelector('#panel-wallet .panel-tab:nth-child(3)'), 'wtab-bonus');walletLoadBonusPanel()">🎁 Bonuses</button>
        </div>
      </div>

      {{-- KPI strip --}}
      <div class="mini-stats cols-5" style="margin-bottom:24px" id="wallet-kpi-strip">
        <div class="mini-stat"><div class="mini-stat-val" id="wkpi-users">—</div><div class="mini-stat-label">Users</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="wkpi-total-bal" style="color:#16a34a">—</div><div class="mini-stat-label">Total Balance</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="wkpi-tx-count">—</div><div class="mini-stat-label">Transactions</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="wkpi-pending" style="color:#f59e0b">—</div><div class="mini-stat-label">Pending</div></div>
        <div class="mini-stat"><div class="mini-stat-val" id="wkpi-zero-bal" style="color:#e8382e">—</div><div class="mini-stat-label">Zero Balance</div></div>
      </div>

      {{-- Panel tabs --}}
      <div class="panel-tabs">
        <div class="panel-tab active" onclick="switchTab(this,'wtab-wallets')">💰 Wallets</div>
        <div class="panel-tab" onclick="switchTab(this,'wtab-transactions');walletLoadTransactions()">📋 All Transactions</div>
        <div class="panel-tab" onclick="switchTab(this,'wtab-bonus');walletLoadBonusPanel()">🎁 Bonuses</div>
        <div class="panel-tab" onclick="switchTab(this,'wtab-audit')">🔍 Audit Log</div>
      </div>

      {{-- ── Wallets tab ── --}}
      <div class="panel-tab-content active" id="wtab-wallets">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>User Wallets</h3><p id="w-wallet-count">Loading…</p></div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
              <input class="form-input" type="text" id="w-wallet-search" placeholder="Search name or email…"
                     style="width:200px;font-size:.82rem;padding:7px 12px"
                     oninput="clearTimeout(window._wST);window._wST=setTimeout(()=>walletLoadWallets(1),400)">
              <select class="form-input" id="w-status-filter" style="width:150px;font-size:.82rem;padding:7px 12px" onchange="walletLoadWallets()">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
                <option value="frozen">Frozen</option>
              </select>
              <button class="action-btn edit" onclick="walletLoadWallets()">↺ Reload</button>
              <button class="action-btn edit" id="w-init-btn" onclick="walletInitWallets()" title="Create wallets for all users who don't have one yet">⚡ Init Wallets</button>
            </div>
          </div>
          <div id="w-init-msg" style="display:none;font-size:.82rem;padding:10px 14px;border-radius:8px;margin-bottom:12px"></div>
          {{-- Card grid — mirrors user balance card style --}}
          <div id="w-wallets-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;padding:4px 0"></div>
          <div id="w-wallets-pagination" style="padding:16px 0;display:flex;gap:8px;align-items:center;justify-content:flex-end"></div>
        </div>
      </div>

      {{-- ── All Transactions tab ── --}}
      <div class="panel-tab-content" id="wtab-transactions">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Transaction Ledger</h3><p id="w-tx-count">Full immutable history of every wallet movement</p></div>
          </div>

          {{-- Filter bar --}}
          <div style="display:flex;gap:8px;flex-wrap:wrap;padding:0 0 16px;border-bottom:1px solid var(--border);margin-bottom:4px">
            <input class="form-input" type="text" id="w-tx-search" placeholder="Search user name or email…"
                   style="flex:1;min-width:180px;font-size:.82rem;padding:7px 12px"
                   oninput="clearTimeout(window._wTxST);window._wTxST=setTimeout(()=>walletLoadTransactions(1),400)">
            <select class="form-input" id="w-tx-category" style="width:180px;font-size:.82rem;padding:7px 12px" onchange="walletLoadTransactions(1)">
              <option value="">All Categories</option>
              <option value="deposit">Deposits</option>
              <option value="purchase">Purchases</option>
              <option value="signup_bonus">Signup Bonus</option>
              <option value="first_deposit_bonus">First Deposit Bonus</option>
              <option value="referral_bonus">Referral Bonus</option>
              <option value="admin_bonus">Admin Bonus</option>
              <option value="campaign_bonus">Campaign Bonus</option>
              <option value="refund">Refunds</option>
            </select>
            <select class="form-input" id="w-tx-status" style="width:140px;font-size:.82rem;padding:7px 12px" onchange="walletLoadTransactions(1)">
              <option value="">All Statuses</option>
              <option value="successful">Successful</option>
              <option value="pending">Pending</option>
              <option value="failed">Failed</option>
            </select>
            <input class="form-input" type="date" id="w-tx-date-from" style="width:150px;font-size:.82rem;padding:7px 10px" onchange="walletLoadTransactions(1)" title="From date">
            <input class="form-input" type="date" id="w-tx-date-to"   style="width:150px;font-size:.82rem;padding:7px 10px" onchange="walletLoadTransactions(1)" title="To date">
            <button class="action-btn edit" onclick="walletClearTxFilters()">✕ Clear</button>
          </div>

          {{-- Transaction list — user-side row format --}}
          <div id="w-tx-list" style="border-top:1px solid var(--border)">
            <div style="text-align:center;padding:40px;color:#9CA3AF;font-size:.88rem">Loading…</div>
          </div>
          <div id="w-tx-pagination" style="padding:14px 24px;display:flex;gap:8px;align-items:center;justify-content:space-between;border-top:1px solid var(--border)">
            <span id="w-tx-page-info" style="font-size:.78rem;color:#9CA3AF"></span>
            <div style="display:flex;gap:8px" id="w-tx-page-btns"></div>
          </div>
        </div>
      </div>

      {{-- ── Bonuses tab ── --}}
      <div class="panel-tab-content" id="wtab-bonus">

        {{-- ── Bonus Rules config ── --}}
        <div class="section-card" style="margin-bottom:24px">
          <div class="section-card-header">
            <div>
              <h3>⚙ Bonus Rules Configuration</h3>
              <p>These amounts are applied automatically by the system for new users, first deposits, and referrals</p>
            </div>
            <button class="action-btn primary" onclick="walletSaveBonusConfig()">Save Rules</button>
          </div>
          <div id="w-cfg-loading" style="padding:24px;text-align:center;color:#9CA3AF;font-size:.88rem">Loading config…</div>
          <div id="w-cfg-form" style="display:none;padding:4px 0">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
              <div>
                <label class="w-cfg-lbl">Signup Welcome Bonus (₦)</label>
                <input class="form-input" type="number" min="0" id="wcfg-signup" placeholder="e.g. 500">
                <p class="w-cfg-hint">Credited to every new account instantly</p>
              </div>
              <div>
                <label class="w-cfg-lbl">First Deposit Bonus (%)</label>
                <input class="form-input" type="number" min="0" max="100" step="0.5" id="wcfg-fdep-pct" placeholder="e.g. 10">
                <p class="w-cfg-hint">% of the user's first deposit amount</p>
              </div>
              <div>
                <label class="w-cfg-lbl">First Deposit Bonus Cap (₦)</label>
                <input class="form-input" type="number" min="0" id="wcfg-fdep-cap" placeholder="e.g. 2000">
                <p class="w-cfg-hint">Maximum bonus regardless of deposit size</p>
              </div>
              <div>
                <label class="w-cfg-lbl">Referral Bonus (₦)</label>
                <input class="form-input" type="number" min="0" id="wcfg-referral" placeholder="e.g. 300">
                <p class="w-cfg-hint">Paid to the referrer when a friend joins</p>
              </div>
              <div>
                <label class="w-cfg-lbl">Zero-Balance Re-engagement (₦)</label>
                <input class="form-input" type="number" min="0" id="wcfg-zero-bal" placeholder="e.g. 200">
                <p class="w-cfg-hint">Default amount for the zero-balance action below</p>
              </div>
            </div>
            <div id="w-cfg-msg" style="display:none;font-size:.82rem;padding:10px 14px;border-radius:8px;margin-top:14px"></div>
          </div>
        </div>

        {{-- ── Targeted bulk actions ── --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">

          {{-- Signup bonus for new users --}}
          <div class="section-card">
            <div class="section-card-header">
              <div>
                <h3>🎁 Missing Signup Bonus</h3>
                <p>Users who joined but never received the welcome bonus</p>
              </div>
            </div>
            <div style="padding:4px 0">
              <div style="background:#f9fafb;border-radius:10px;padding:14px 16px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between">
                <div>
                  <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9CA3AF">Eligible Users</div>
                  <div style="font-size:1.5rem;font-weight:700;color:#1C1416" id="w-stat-missing-signup">—</div>
                </div>
                <div style="font-size:2rem">👤</div>
              </div>
              <p style="font-size:.8rem;color:#6B7280;margin-bottom:14px">Grants the configured signup bonus (₦<span id="w-cfg-signup-preview">—</span>) to every user who hasn't received one. Idempotent — safe to run multiple times.</p>
              <div id="w-signup-bonus-msg" style="display:none;font-size:.82rem;padding:10px 14px;border-radius:8px;margin-bottom:12px"></div>
              <button class="action-btn primary" onclick="walletGrantMissingSignup()" id="w-signup-bonus-btn">Grant to All Eligible →</button>
            </div>
          </div>

          {{-- Zero-balance re-engagement --}}
          <div class="section-card">
            <div class="section-card-header">
              <div>
                <h3>💸 Zero-Balance Re-engagement</h3>
                <p>Users with an empty wallet — nudge them to start shopping</p>
              </div>
            </div>
            <div style="padding:4px 0">
              <div style="background:#fef2f2;border-radius:10px;padding:14px 16px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between">
                <div>
                  <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9CA3AF">Zero-Balance Wallets</div>
                  <div style="font-size:1.5rem;font-weight:700;color:#e8382e" id="w-stat-zero-bal">—</div>
                </div>
                <div style="font-size:2rem">🪙</div>
              </div>
              <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:14px">
                <div>
                  <label class="w-cfg-lbl">Bonus Amount (₦) *</label>
                  <input class="form-input" type="number" min="1" id="w-zero-amount" placeholder="e.g. 200" oninput="this.dataset.userEdited='1'">
                </div>
                <div>
                  <label class="w-cfg-lbl">Campaign ID (unique slug) *</label>
                  <input class="form-input" type="text" id="w-zero-campaign-id" placeholder="e.g. re-engage-may-2026">
                </div>
                <div>
                  <label class="w-cfg-lbl">Description *</label>
                  <input class="form-input" type="text" id="w-zero-desc" placeholder="e.g. Re-engagement bonus – May 2026">
                </div>
              </div>
              <div id="w-zero-bonus-msg" style="display:none;font-size:.82rem;padding:10px 14px;border-radius:8px;margin-bottom:12px"></div>
              <button class="action-btn primary" onclick="walletGrantZeroBalance()" id="w-zero-bonus-btn">Send Re-engagement Bonus →</button>
            </div>
          </div>

        </div>

        {{-- ── Manual grants ── --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

          {{-- Single user bonus --}}
          <div class="section-card">
            <div class="section-card-header"><div><h3>🎯 Single User Bonus</h3><p>Credit a bonus directly to one user's wallet</p></div></div>
            <div style="display:flex;flex-direction:column;gap:12px;padding-top:4px">
              <div>
                <label class="w-cfg-lbl">User ID *</label>
                <input class="form-input" type="number" id="w-bonus-user-id" placeholder="Enter user ID">
              </div>
              <div>
                <label class="w-cfg-lbl">Amount (₦) *</label>
                <input class="form-input" type="number" id="w-bonus-amount" placeholder="e.g. 1000" min="1">
              </div>
              <div>
                <label class="w-cfg-lbl">Category</label>
                <select class="form-input" id="w-bonus-category">
                  <option value="admin_bonus">Admin Bonus</option>
                  <option value="campaign_bonus">Campaign Bonus</option>
                </select>
              </div>
              <div>
                <label class="w-cfg-lbl">Description *</label>
                <input class="form-input" type="text" id="w-bonus-desc" placeholder="e.g. VIP appreciation credit">
              </div>
              <div id="w-bonus-msg" style="display:none;font-size:.82rem;padding:10px 14px;border-radius:8px"></div>
              <button class="action-btn primary" onclick="walletGrantBonus()" style="align-self:flex-start">Grant →</button>
            </div>
          </div>

          {{-- Bulk campaign bonus --}}
          <div class="section-card">
            <div class="section-card-header"><div><h3>📢 Campaign Bonus</h3><p>Distribute to multiple users at once — idempotent</p></div></div>
            <div style="display:flex;flex-direction:column;gap:12px;padding-top:4px">
              <div>
                <label class="w-cfg-lbl">User IDs (comma-separated) *</label>
                <textarea class="form-input" id="w-bulk-user-ids" rows="3" placeholder="e.g. 1, 4, 7, 12"></textarea>
              </div>
              <div>
                <label class="w-cfg-lbl">Amount per user (₦) *</label>
                <input class="form-input" type="number" id="w-bulk-amount" placeholder="e.g. 500" min="1">
              </div>
              <div>
                <label class="w-cfg-lbl">Campaign Name *</label>
                <input class="form-input" type="text" id="w-bulk-campaign-name" placeholder="e.g. Eid 2026 Promo">
              </div>
              <div>
                <label class="w-cfg-lbl">Campaign ID (unique slug) *</label>
                <input class="form-input" type="text" id="w-bulk-campaign-id" placeholder="e.g. eid-2026">
              </div>
              <div id="w-bulk-msg" style="display:none;font-size:.82rem;padding:10px 14px;border-radius:8px"></div>
              <button class="action-btn primary" onclick="walletGrantBulkBonus()" style="align-self:flex-start">Distribute →</button>
            </div>
          </div>

        </div>
      </div>

      {{-- ── Audit Log tab ── --}}
      <div class="panel-tab-content" id="wtab-audit">
        <div class="section-card">
          <div class="section-card-header">
            <div><h3>Wallet Audit Log</h3><p>Immutable record of all wallet operations</p></div>
            <button class="action-btn edit" onclick="walletLoadAudit()">↺ Load</button>
          </div>
          <div style="overflow-x:auto">
            <table class="data-table">
              <thead><tr>
                <th>Date</th><th>User</th><th>Action</th><th>Amount</th><th>Balance Before → After</th><th>IP</th><th>By Admin</th>
              </tr></thead>
              <tbody id="w-audit-tbody">
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#9CA3AF">Click "Load" to fetch audit log</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
    {{-- END WALLET PANEL --}}

    {{-- ── User Wallet Detail Modal ── --}}
    <div id="wUserModal" style="display:none;position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,.55);overflow-y:auto;padding:32px 16px" onclick="if(event.target===this)walletCloseUserModal()">
      <div style="max-width:680px;margin:0 auto;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.18)">

        {{-- Dark balance card --}}
        <div id="wum-card" style="background:linear-gradient(135deg,#1C1416 0%,#1c1c1c 60%,#2a2a2a 100%);padding:32px 36px;color:#fff;position:relative;overflow:hidden">
          <div style="position:absolute;top:-80px;right:-80px;width:280px;height:280px;background:radial-gradient(circle,rgba(212,217,148,.13) 0%,transparent 65%);pointer-events:none"></div>
          <button onclick="walletCloseUserModal()" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,.1);border:none;color:#fff;width:32px;height:32px;border-radius:50%;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center">✕</button>
          <div style="position:relative;z-index:1">
            <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.16em;color:rgba(255,255,255,.35);margin-bottom:4px" id="wum-name">—</div>
            <div style="font-size:.6rem;color:rgba(255,255,255,.25);margin-bottom:18px" id="wum-email">—</div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:22px">
              <div>
                <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.16em;color:rgba(255,255,255,.3);margin-bottom:6px">Available Balance</div>
                <div style="font-size:2.4rem;font-weight:700;line-height:1"><span style="font-size:1.2rem;font-weight:700;color:#D4D994">₦</span><span id="wum-balance">0.00</span></div>
                <div style="font-size:.7rem;color:rgba(255,255,255,.3);margin-top:4px">Nigerian Naira · Kominhoo Wallet</div>
              </div>
              <span id="wum-status-badge" style="display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:999px;font-size:.68rem;font-weight:700;text-transform:uppercase"></span>
            </div>
            <div style="display:flex;gap:28px;flex-wrap:wrap;margin-bottom:20px">
              <div>
                <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.25)">Locked</div>
                <div style="font-size:.88rem;font-weight:700;color:rgba(255,255,255,.6);margin-top:3px">₦<span id="wum-locked">0.00</span></div>
              </div>
              <div>
                <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.25)">Total Transactions</div>
                <div style="font-size:.88rem;font-weight:700;color:rgba(255,255,255,.6);margin-top:3px" id="wum-tx-count">—</div>
              </div>
              <div>
                <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.25)">Loyalty Tier</div>
                <div style="font-size:.88rem;font-weight:700;color:rgba(255,255,255,.6);margin-top:3px" id="wum-tier">—</div>
              </div>
            </div>
            {{-- Status change + Quick bonus --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap">
              <select id="wum-status-sel" style="background:rgba(255,255,255,.1);border:1.5px solid rgba(255,255,255,.15);color:#fff;border-radius:8px;padding:7px 12px;font-size:.78rem;font-weight:700;cursor:pointer" onchange="walletChangeStatusFromModal()">
                <option value="active">● Active</option>
                <option value="suspended">● Suspended</option>
                <option value="frozen">● Frozen</option>
              </select>
              <button onclick="walletOpenQuickBonus()" style="background:rgba(212,217,148,.15);border:1.5px solid rgba(212,217,148,.3);color:#D4D994;border-radius:8px;padding:7px 14px;font-size:.78rem;font-weight:700;cursor:pointer">＋ Quick Bonus</button>
            </div>
            {{-- Quick bonus inline form (hidden by default) --}}
            <div id="wum-quick-bonus" style="display:none;margin-top:14px;background:rgba(255,255,255,.07);border-radius:10px;padding:14px 16px">
              <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end">
                <div style="flex:1;min-width:100px">
                  <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);margin-bottom:5px">Amount (₦)</div>
                  <input id="wum-bonus-amt" type="number" min="1" placeholder="e.g. 500" style="background:rgba(255,255,255,.1);border:1.5px solid rgba(255,255,255,.15);color:#fff;border-radius:8px;padding:8px 12px;font-size:.88rem;font-weight:700;width:100%;box-sizing:border-box">
                </div>
                <div style="flex:2;min-width:140px">
                  <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);margin-bottom:5px">Description</div>
                  <input id="wum-bonus-desc" type="text" placeholder="e.g. VIP appreciation" style="background:rgba(255,255,255,.1);border:1.5px solid rgba(255,255,255,.15);color:#fff;border-radius:8px;padding:8px 12px;font-size:.88rem;width:100%;box-sizing:border-box">
                </div>
                <button onclick="walletSendQuickBonus()" style="background:#D4D994;border:none;color:#1C1416;border-radius:8px;padding:9px 16px;font-size:.8rem;font-weight:700;cursor:pointer;white-space:nowrap">Grant →</button>
              </div>
              <div id="wum-bonus-msg" style="display:none;font-size:.78rem;padding:8px 12px;border-radius:6px;margin-top:8px"></div>
            </div>
          </div>
        </div>

        {{-- Transaction list — exact user-side format --}}
        <div style="background:#fff">
          <div style="display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid #f0f0f0">
            <div style="font-size:.95rem;font-weight:700">Transaction History</div>
            <div style="display:flex;gap:6px" id="wum-filter-btns">
              <button onclick="walletUMFilter('all',this)"    style="border:1.5px solid #1C1416;background:#1C1416;color:#fff;border-radius:999px;padding:4px 12px;font-size:.72rem;font-weight:700;cursor:pointer">All</button>
              <button onclick="walletUMFilter('credit',this)" style="border:1.5px solid #e8eaed;background:#fff;color:#374151;border-radius:999px;padding:4px 12px;font-size:.72rem;font-weight:700;cursor:pointer">Credits</button>
              <button onclick="walletUMFilter('debit',this)"  style="border:1.5px solid #e8eaed;background:#fff;color:#374151;border-radius:999px;padding:4px 12px;font-size:.72rem;font-weight:700;cursor:pointer">Debits</button>
              <button onclick="walletUMFilter('bonus',this)"  style="border:1.5px solid #e8eaed;background:#fff;color:#374151;border-radius:999px;padding:4px 12px;font-size:.72rem;font-weight:700;cursor:pointer">Bonuses</button>
            </div>
          </div>
          <div id="wum-tx-list" style="min-height:120px">
            <div style="text-align:center;padding:40px;color:#9CA3AF">Loading transactions…</div>
          </div>
        </div>

      </div>
    </div>

@endsection

@section('modals')
{{-- Add Denomination Modal (Gift Cards) --}}
<div class="modal-overlay" id="gcAddOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" onclick="event.stopPropagation()" style="max-width:440px">
    <div class="add-modal-header"><h2>Add Denomination</h2><button class="tag-modal-close" onclick="document.getElementById('gcAddOverlay').classList.remove('open')">✕</button></div>
    <div class="add-modal-body">
      <div style="display:flex;flex-direction:column;gap:14px">
        <div><label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(10,10,10,.45);display:block;margin-bottom:6px">Amount (₦) *</label><input class="form-input" type="number" min="1000" step="500" placeholder="e.g. 15000" id="gcDenomAmount"></div>
        <div><label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(10,10,10,.45);display:block;margin-bottom:6px">Label</label><input class="form-input" type="text" placeholder="e.g. Mid-Range Gift" id="gcDenomLabel"></div>
        <div><label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(10,10,10,.45);display:block;margin-bottom:6px">Description</label><input class="form-input" type="text" placeholder="Short tagline for this card" id="gcDenomDesc"></div>
        <div style="display:flex;align-items:center;gap:10px"><input type="checkbox" id="gc-popular-chk" style="width:16px;height:16px"><label for="gc-popular-chk" style="font-size:.88rem;font-weight:600">Mark as Popular</label></div>
        <div id="gcDenomError" style="color:#e8382e;font-size:.8rem;display:none"></div>
      </div>
    </div>
    <div class="add-modal-footer">
      <button class="action-btn edit" onclick="document.getElementById('gcAddOverlay').classList.remove('open')">Cancel</button>
      <button class="action-btn primary" id="gcSaveDenomBtn" onclick="gcSaveDenomination()">Save Denomination</button>
    </div>
  </div>
</div>

{{-- Issue Gift Card Modal --}}
<div class="modal-overlay" id="gcIssueOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" onclick="event.stopPropagation()" style="max-width:500px">
    <div class="add-modal-header"><h2>Issue Gift Card</h2><button class="tag-modal-close" onclick="document.getElementById('gcIssueOverlay').classList.remove('open')">✕</button></div>
    <div class="add-modal-body">
      <div style="display:flex;flex-direction:column;gap:14px">
        <div><label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(10,10,10,.45);display:block;margin-bottom:6px">Amount (₦) *</label><input class="form-input" type="number" min="1000" step="500" placeholder="e.g. 10000" id="gcIssueAmount"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <div><label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(10,10,10,.45);display:block;margin-bottom:6px">Recipient Name *</label><input class="form-input" type="text" placeholder="Recipient full name" id="gcIssueRecipientName"></div>
          <div><label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(10,10,10,.45);display:block;margin-bottom:6px">Recipient Email *</label><input class="form-input" type="email" placeholder="recipient@email.com" id="gcIssueRecipientEmail"></div>
        </div>
        <div><label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(10,10,10,.45);display:block;margin-bottom:6px">Message (optional)</label><textarea class="form-input" rows="2" placeholder="Personal note to include…" id="gcIssueMessage" style="resize:vertical"></textarea></div>
        <div id="gcIssueResult" style="display:none;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px">
          <div style="font-size:.82rem;font-weight:700;color:#15803d;margin-bottom:6px">✓ Gift card issued!</div>
          <div style="font-size:.85rem">Code: <strong id="gcIssuedCode" style="font-family:monospace;font-size:.95rem;letter-spacing:.06em"></strong></div>
          <div style="font-size:.78rem;color:rgba(10,10,10,.5);margin-top:4px" id="gcIssuedMeta"></div>
        </div>
        <div id="gcIssueError" style="color:#e8382e;font-size:.8rem;display:none"></div>
      </div>
    </div>
    <div class="add-modal-footer">
      <button class="action-btn edit" onclick="document.getElementById('gcIssueOverlay').classList.remove('open')">Close</button>
      <button class="action-btn primary" id="gcIssueSaveBtn" onclick="gcIssueCard()">Issue Gift Card</button>
    </div>
  </div>
</div>

{{-- Blog Post Modal --}}
<div class="modal-overlay" id="blogPostOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" onclick="event.stopPropagation()" style="max-width:840px;">
    <div class="add-modal-header">
      <h2 id="blogPostModalTitle">New Blog Post</h2>
      <button class="tag-modal-close" onclick="document.getElementById('blogPostOverlay').classList.remove('open')">✖</button>
    </div>
    <div class="add-modal-body">
      <input type="hidden" id="blogPostId" value="" />
      <div class="form-grid ratio-13-07 gap-14">
        <div class="form-group">
          <label>Title *</label>
          <input class="form-input" id="blogTitle" type="text" placeholder="Post title" />
        </div>
        <div class="form-group">
          <label>Slug (optional)</label>
          <input class="form-input" id="blogSlug" type="text" placeholder="auto-generated" />
        </div>
      </div>

      <div class="form-grid cols-3 gap-14">
        <div class="form-group">
          <label>Tag</label>
          <input class="form-input" id="blogTag" type="text" placeholder="e.g. Ingredients" />
        </div>
        <div class="form-group">
          <label>Author</label>
          <input class="form-input" id="blogAuthor" type="text" placeholder="e.g. Kominhoo Team" />
        </div>
        <div class="form-group">
          <label>Reading Time</label>
          <input class="form-input" id="blogReadTime" type="text" placeholder="e.g. 5 min read" />
        </div>
      </div>

      <div class="form-grid gap-14">
        <div class="form-group">
          <label>Cover Image (upload)</label>
          <input class="form-input" id="blogCoverFile" type="file" accept=".jpg,.jpeg,.png,.webp" />
          <div style="font-size:.74rem;color:rgba(10,10,10,.45);margin-top:6px;">Recommended: 1600×900 (JPG/WebP).</div>
        </div>
        <div class="form-group">
          <label>Cover Image URL (optional)</label>
          <input class="form-input" id="blogCoverUrl" type="url" placeholder="https://..." oninput="blogCoverPreview(this.value)" />
        </div>
      </div>

      <div id="blogCoverPreview" style="display:none;margin-top:10px;border:1.5px solid #e8eaed;border-radius:12px;overflow:hidden;">
        <img id="blogCoverPreviewImg" alt="Cover preview" style="width:100%;height:220px;object-fit:cover;display:block;" />
      </div>

      <div class="form-group" style="margin-top:14px;">
        <label>Excerpt</label>
        <textarea class="form-input" id="blogExcerpt" rows="3" placeholder="Short summary used on the blog listing…" style="resize:vertical;"></textarea>
      </div>

      <div class="form-group">
        <label>Content</label>
        <textarea class="form-input" id="blogContent" rows="10" placeholder="Write the post body here (HTML supported)..." style="resize:vertical;"></textarea>
      </div>

      <div style="display:flex;gap:18px;align-items:center;flex-wrap:wrap;margin-top:12px;">
        <label style="display:flex;align-items:center;gap:10px;font-weight:600;">
          <input type="checkbox" id="blogPublished" style="width:16px;height:16px;" onchange="blogTogglePublished(this.checked)" />
          Published
        </label>
        <label style="display:flex;align-items:center;gap:10px;font-weight:600;">
          <input type="checkbox" id="blogFeatured" style="width:16px;height:16px;" />
          Featured
        </label>
        <div style="display:flex;align-items:center;gap:10px;min-width:260px;">
          <div style="font-size:.8rem;color:rgba(10,10,10,.6);font-weight:700;text-transform:uppercase;letter-spacing:.06em;">Publish Date</div>
          <input class="form-input" id="blogPublishedAt" type="datetime-local" style="width:220px;padding:8px 10px;" disabled />
        </div>
        <div id="blogPostError" style="color:#e8382e;font-size:.82rem;display:none;flex-basis:100%;"></div>
      </div>
    </div>
    <div class="add-modal-footer">
      <button class="action-btn edit" onclick="document.getElementById('blogPostOverlay').classList.remove('open')">Cancel</button>
      <button class="action-btn primary" id="blogPostSaveBtn" onclick="saveBlogPost()">Save Post</button>
    </div>
  </div>
</div>

{{-- Product Tagging Modal --}}
<div class="modal-overlay" id="tagModalOverlay" onclick="closeTagModal(event)">
  <div class="tag-modal" onclick="event.stopPropagation()">
    <div class="tag-modal-header">
      <img id="tagModalImg" src="" alt="" />
      <div><h2 id="tagModalName">Product Name</h2><p id="tagModalBrand">Brand · Category</p></div>
      <button class="tag-modal-close" onclick="document.getElementById('tagModalOverlay').classList.remove('open')">✕</button>
    </div>
    <div class="tag-modal-body">
      <div class="tag-section"><div class="tag-section-title">Skin Type</div><div class="tag-checkboxes"><label class="tag-check-label skin" onclick="toggleTagLabel(this)"><input type="checkbox" value="oily" /> Oily</label><label class="tag-check-label skin" onclick="toggleTagLabel(this)"><input type="checkbox" value="dry" /> Dry</label><label class="tag-check-label skin" onclick="toggleTagLabel(this)"><input type="checkbox" value="combination" /> Combination</label><label class="tag-check-label skin" onclick="toggleTagLabel(this)"><input type="checkbox" value="sensitive" /> Sensitive</label><label class="tag-check-label skin" onclick="toggleTagLabel(this)"><input type="checkbox" value="normal" /> Normal</label><label class="tag-check-label skin" onclick="toggleTagLabel(this)"><input type="checkbox" value="all" /> All Skin Types</label></div></div>
      <div class="tag-section"><div class="tag-section-title">Skin Concerns</div><div class="tag-checkboxes"><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="acne" /> Acne / Breakouts</label><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="brightening" /> Hyperpigmentation</label><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="dehydration" /> Dehydration</label><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="pores" /> Large Pores</label><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="antiaging" /> Anti-aging</label><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="sensitivity" /> Sensitivity</label><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="uneven-texture" /> Uneven Texture</label><label class="tag-check-label concern" onclick="toggleTagLabel(this)"><input type="checkbox" value="dark-circles" /> Dark Circles</label></div></div>
      <div class="tag-section"><div class="tag-section-title">Routine Step</div><div class="tag-checkboxes"><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="cleanser" /> Cleanser</label><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="toner" /> Toner / Essence</label><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="serum" /> Serum / Ampoule</label><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="moisturizer" /> Moisturizer</label><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="spf" /> Sunscreen / SPF</label><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="eye-cream" /> Eye Cream</label><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="mask" /> Mask / Treatment</label><label class="tag-check-label routine" onclick="toggleTagLabel(this)"><input type="checkbox" value="exfoliant" /> Exfoliant</label></div></div>
      <div class="tag-section"><div class="tag-section-title">Key Ingredients</div><div class="tag-checkboxes"><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="niacinamide" /> Niacinamide</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="hyaluronic-acid" /> Hyaluronic Acid</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="aha" /> AHA</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="bha" /> BHA / Salicylic Acid</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="retinol" /> Retinol</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="vitamin-c" /> Vitamin C</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="centella" /> Centella Asiatica</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="ceramides" /> Ceramides</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="snail-mucin" /> Snail Mucin</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="peptides" /> Peptides</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="probiotics" /> Probiotics</label><label class="tag-check-label ingred" onclick="toggleTagLabel(this)"><input type="checkbox" value="galactomyces" /> Galactomyces</label></div></div>
      <div class="tag-section"><div class="tag-section-title">Time of Use</div><div class="tag-checkboxes"><label class="tag-check-label" onclick="toggleTagLabel(this)"><input type="checkbox" value="am" /> AM Routine</label><label class="tag-check-label" onclick="toggleTagLabel(this)"><input type="checkbox" value="pm" /> PM Routine</label><label class="tag-check-label" onclick="toggleTagLabel(this)"><input type="checkbox" value="both" /> AM &amp; PM</label><label class="tag-check-label" onclick="toggleTagLabel(this)"><input type="checkbox" value="weekly" /> Weekly Treatment</label></div></div>
      <div class="tag-section"><div class="tag-section-title">Climate / Environment</div><div class="tag-checkboxes"><label class="tag-check-label climate" onclick="toggleTagLabel(this)"><input type="checkbox" value="humid" /> Humid</label><label class="tag-check-label climate" onclick="toggleTagLabel(this)"><input type="checkbox" value="dry-climate" /> Dry Climate</label><label class="tag-check-label climate" onclick="toggleTagLabel(this)"><input type="checkbox" value="tropical" /> Tropical</label><label class="tag-check-label climate" onclick="toggleTagLabel(this)"><input type="checkbox" value="harmattan" /> Harmattan Season</label><label class="tag-check-label climate" onclick="toggleTagLabel(this)"><input type="checkbox" value="urban" /> Urban / Pollution</label></div></div>
      <div class="tag-section"><div class="tag-section-title">Price Tier</div><div class="tag-checkboxes"><label class="tag-check-label" onclick="toggleTagLabel(this)"><input type="checkbox" value="budget" /> Budget (under ₦8k)</label><label class="tag-check-label" onclick="toggleTagLabel(this)"><input type="checkbox" value="mid" /> Mid-range (₦8k–₦18k)</label><label class="tag-check-label" onclick="toggleTagLabel(this)"><input type="checkbox" value="premium" /> Premium (₦18k+)</label></div></div>
    </div>
    <div class="tag-modal-footer"><button class="action-btn edit" onclick="document.getElementById('tagModalOverlay').classList.remove('open')">Cancel</button><button class="action-btn primary" onclick="saveProductTags()">✓ Save Tags</button></div>
  </div>
</div>

{{-- Add Product Modal --}}
<div class="modal-overlay" id="addModalOverlay" onclick="closeAddModal(event)">
  <div class="add-modal" style="max-width:820px;width:96vw;" onclick="event.stopPropagation()">
    <div class="add-modal-header">
      <div><h2>Add New Product</h2><p style="font-size:.78rem;color:rgba(10,10,10,.45);margin-top:2px;">Fill in all sections · save as draft or publish immediately</p></div>
      <div style="display:flex;align-items:center;gap:10px;">
        <select class="form-input" id="addProductStatus" style="width:130px;font-size:.82rem;padding:7px 10px;"><option value="active">● Published</option><option value="draft" selected>○ Draft</option><option value="archived">✕ Archived</option></select>
        <button class="tag-modal-close" onclick="document.getElementById('addModalOverlay').classList.remove('open')">✕</button>
      </div>
    </div>
    <div style="display:flex;gap:0;border-bottom:2px solid #e8eaed;padding:0 26px;overflow-x:auto;">
      <div class="panel-tab active" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'aptab-basic')">📋 Basic Info</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'aptab-media')">📸 Media</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'aptab-desc')">📝 Description</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'aptab-pricing')">💰 Pricing &amp; Stock</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'aptab-skinos')">🧬 Skin OS</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'aptab-seo')">🔗 SEO &amp; Settings</div>
    </div>
    <div class="add-modal-body" style="max-height:62vh;overflow-y:auto;">
      <div class="panel-tab-content active" id="aptab-basic">
        <div class="form-grid">
          <div class="form-group" style="grid-column:1/-1;"><label>Product Name *</label><input type="text" class="form-input" id="addName" placeholder="e.g. COSRX Advanced Snail 96 Mucin Power Essence" /></div>
          <div class="form-group"><label>Brand *</label><input type="text" class="form-input" id="addBrand" placeholder="e.g. COSRX" /></div>
          <div class="form-group"><label>Country of Origin</label><select class="form-input" id="addOrigin"><option value="Korea" selected>🇰🇷 South Korea</option><option value="Japan">🇯🇵 Japan</option><option value="USA">🇺🇸 USA</option><option value="Nigeria">🇳🇬 Nigeria</option></select></div>
          <div class="form-group"><label>Category *</label><select class="form-input" id="addCategory"><option value="">Select category…</option><option>Cleanser</option><option>Toner</option><option>Serum</option><option>Moisturiser</option><option>Sunscreen</option><option>Eye Cream</option><option>Mask</option><option>Exfoliant</option><option>Essence</option><option>Lip Care</option><option>Body Care</option><option>Treatment</option></select></div>
          <div class="form-group"><label>Sub-Category / Routine Step</label><select class="form-input" id="addRoutineStep"><option value="">None</option><option>Step 1 — Oil Cleanser</option><option>Step 2 — Water Cleanser</option><option>Step 4 — Toner</option><option>Step 5 — Essence</option><option>Step 6 — Treatment / Serum</option><option>Step 8 — Moisturiser</option><option>Step 10 — SPF (AM only)</option></select></div>
          <div class="form-group"><label>Volume / Size</label><input type="text" class="form-input" id="addVolume" placeholder="e.g. 100ml, 50g" /></div>
          <div class="form-group"><label>SKU</label><input type="text" class="form-input" id="addSku" placeholder="Auto-generated if blank" /></div>
          <div class="form-group"><label>Badge</label><select class="form-input" id="addBadge"><option value="">None</option><option>Best Seller</option><option>New</option><option>Staff Pick</option><option>Limited Edition</option><option>Viral</option><option>Low Stock</option></select></div>
          <div class="form-group"><label>Shelf Life (months)</label><input type="number" class="form-input" id="addShelfLife" placeholder="e.g. 24" /></div>
        </div>
        <div style="display:flex;gap:20px;margin-top:4px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="addIsNew" /> Mark as New Arrival</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="addInStock" checked /> In Stock</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="addFeatured" /> Featured on Homepage</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="addVegan" /> Vegan</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="addCrueltyFree" checked /> Cruelty-Free</label>
        </div>
      </div>
      <div class="panel-tab-content" id="aptab-media">
        <div class="form-group">
          <label>Product Images <span style="font-weight:400;color:rgba(10,10,10,.4);font-size:.78rem;">First image = main. Up to 8 images.</span></label>
          <div id="addImageGrid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px;"></div>
          <label style="display:flex;align-items:center;justify-content:center;gap:8px;border:2px dashed #e8eaed;border-radius:12px;padding:18px;cursor:pointer;color:rgba(10,10,10,.5);font-size:.85rem;transition:border .2s,background .2s;">
            <input type="file" accept="image/*" multiple style="display:none;" onchange="handleAddImages(this)" />
            <span style="font-size:1.2rem;">📸</span> <span><strong>Click to upload images</strong> or drag &amp; drop · PNG, JPG, WEBP up to 5MB each</span>
          </label>
        </div>
        <div class="form-group" style="margin-top:20px;"><label>Product Image URL <span style="font-weight:400;opacity:.5;font-size:.78rem;">optional</span></label><input type="url" class="form-input" id="addImageUrl" placeholder="https://…" oninput="previewAddImageUrl(this.value)" /><div id="addImageUrlPreview" style="margin-top:8px;display:none;"><img id="addImageUrlThumb" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1.5px solid #e8eaed;" /></div></div>
      </div>
      <div class="panel-tab-content" id="aptab-desc">
        <div class="form-group"><label>Short Description <span style="font-weight:400;opacity:.5;font-size:.78rem;">shown in product cards (max 160 chars)</span></label><textarea class="form-input" id="addShortDesc" rows="2" maxlength="160" placeholder="One-liner that sells the product…" style="resize:vertical;" oninput="document.getElementById('shortDescCount').textContent=this.value.length"></textarea><div style="text-align:right;font-size:.72rem;color:rgba(10,10,10,.35);margin-top:3px;"><span id="shortDescCount">0</span>/160</div></div>
        <div class="form-group"><label>Long Description <span style="font-weight:400;opacity:.5;font-size:.78rem;">full product page copy</span></label><div style="border:1.5px solid #e8eaed;border-radius:10px;overflow:hidden;"><div style="background:#fafbfc;border-bottom:1px solid #f0f2f4;padding:8px 12px;display:flex;gap:6px;flex-wrap:wrap;"><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('bold')"><strong>B</strong></button><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('italic')"><em>I</em></button><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('underline')"><u>U</u></button><span style="width:1px;background:#e8eaed;margin:0 4px;"></span><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('insertUnorderedList')">• List</button><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('insertOrderedList')">1. List</button></div><div id="addLongDesc" contenteditable="true" style="min-height:140px;padding:14px;font-size:.85rem;line-height:1.7;outline:none;" data-placeholder="Write a compelling full description…"></div></div></div>
        <div class="form-group"><label>How to Use</label><textarea class="form-input" id="addHowToUse" rows="3" placeholder="Step-by-step usage instructions…" style="resize:vertical;"></textarea></div>
        <div class="form-grid">
          <div class="form-group"><label>Key Active Ingredients</label><input type="text" class="form-input" id="addActiveIngredients" placeholder="e.g. Niacinamide 10%, Snail Secretion Filtrate 96%" /></div>
          <div class="form-group"><label>Fragrance / Alcohol Status</label><select class="form-input" id="addFragrance"><option>Fragrance-Free</option><option>Lightly Fragranced</option><option>Fragranced</option><option>Alcohol-Free</option><option>Contains Denatured Alcohol</option></select></div>
        </div>
        <div class="form-group"><label>Full Ingredients List (INCI)</label><textarea class="form-input" id="addIngredients" rows="3" placeholder="Water, Niacinamide, Glycerin…" style="resize:vertical;font-size:.78rem;"></textarea></div>
        <div class="form-group"><label>Warnings / Contraindications</label><textarea class="form-input" id="addWarnings" rows="2" placeholder="e.g. Do not use with retinol." style="resize:vertical;"></textarea></div>
      </div>
      <div class="panel-tab-content" id="aptab-pricing">
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin-bottom:12px;">Pricing</div>
        <div class="form-grid">
          <div class="form-group"><label>Selling Price (₦) *</label><input type="number" class="form-input" id="addPrice" placeholder="e.g. 14500" oninput="calcMargin()" /></div>
          <div class="form-group"><label>Compare-at Price (₦)</label><input type="number" class="form-input" id="addOriginalPrice" placeholder="Leave blank if not on sale" /></div>
          <div class="form-group"><label>Cost Price (₦)</label><input type="number" class="form-input" id="addCostPrice" placeholder="Your purchase cost" oninput="calcMargin()" /></div>
          <div class="form-group"><label>Gross Margin</label><div style="display:flex;align-items:center;height:44px;padding:0 14px;background:#fafbfc;border:1.5px solid #e8eaed;border-radius:10px;font-size:.88rem;font-weight:700;" id="addMarginDisplay">—</div></div>
        </div>
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin:18px 0 12px;">Inventory &amp; Batch Tracking</div>
        <div class="form-grid">
          <div class="form-group"><label>Stock Quantity *</label><input type="number" class="form-input" id="addStock" placeholder="e.g. 50" /></div>
          <div class="form-group"><label>Low Stock Alert At</label><input type="number" class="form-input" id="addRestockLevel" placeholder="e.g. 10" value="10" /></div>
          <div class="form-group"><label>Batch Number</label><input type="text" class="form-input" id="addBatch" placeholder="e.g. KMH-B512" /></div>
          <div class="form-group"><label>Expiry Date <span style="color:var(--red);font-weight:700;">*</span></label><input type="month" class="form-input" id="addExpiry" /></div>
          <div class="form-group"><label>Warehouse Location</label><input type="text" class="form-input" id="addWarehouse" placeholder="e.g. Lagos Warehouse A, Shelf 3" /></div>
          <div class="form-group"><label>Track Inventory</label><select class="form-input" id="addTrackInventory"><option value="track">Track quantity</option><option value="notrack">Don't track</option></select></div>
        </div>
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin:18px 0 12px;">Shipping</div>
        <div class="form-grid">
          <div class="form-group"><label>Weight (kg)</label><input type="number" class="form-input" id="addWeight" placeholder="e.g. 0.15" step="0.01" /></div>
          <div class="form-group"><label>Dimensions (L × W × H cm)</label><input type="text" class="form-input" id="addDimensions" placeholder="e.g. 12 × 6 × 4" /></div>
          <div class="form-group"><label>Requires Cold Chain?</label><select class="form-input" id="addColdChain"><option value="no">No</option><option value="yes">Yes — refrigerate</option></select></div>
          <div class="form-group"><label>Custom Shipping Note</label><input type="text" class="form-input" id="addShipNote" placeholder="e.g. Handle with care · fragile" /></div>
        </div>
      </div>
      <div class="panel-tab-content" id="aptab-skinos">
        <div style="background:#fafbfc;border:1.5px solid #e8eaed;border-radius:12px;padding:16px;margin-bottom:20px;"><div style="font-size:.78rem;color:rgba(10,10,10,.5);line-height:1.6;">The Skin OS tagging system links this product to customer quiz profiles. Tag accurately — these tags drive routine recommendations and bundle matching.</div></div>
        <div class="form-group"><label>Suitable Skin Types</label><div style="display:flex;flex-wrap:wrap;gap:8px;padding:12px;border:1.5px solid #e8eaed;border-radius:10px;"><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="addSkinType" value="All Skin Types" checked /> All Skin Types</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="addSkinType" value="Oily" /> Oily</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="addSkinType" value="Dry" /> Dry</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="addSkinType" value="Combination" /> Combination</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="addSkinType" value="Sensitive" /> Sensitive</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="addSkinType" value="Normal" /> Normal</label></div></div>
        <div class="form-grid">
          <div class="form-group"><label>Routine Time</label><select class="form-input" id="addRoutineTime"><option value="">Both AM &amp; PM</option><option>AM Only</option><option>PM Only</option><option>Weekly Treatment</option></select></div>
          <div class="form-group"><label>Sensitivity Score</label><select class="form-input" id="addSensitivityScore"><option value="1">1 — Very Gentle</option><option value="2" selected>2 — Gentle</option><option value="3">3 — Moderate</option><option value="4">4 — Active</option><option value="5">5 — Strong</option></select></div>
          <div class="form-group"><label>Pairs Well With</label><input type="text" class="form-input" id="addPairsWith" placeholder="e.g. Laneige Water Sleeping Mask" /></div>
          <div class="form-group"><label>Do NOT Combine With</label><input type="text" class="form-input" id="addAvoidWith" placeholder="e.g. Retinol, AHA/BHA" /></div>
        </div>
      </div>
      <div class="panel-tab-content" id="aptab-seo">
        <div class="form-group"><label>URL Slug</label><div style="display:flex;align-items:center;border:1.5px solid #e8eaed;border-radius:10px;overflow:hidden;"><span style="padding:0 12px;font-size:.8rem;color:rgba(10,10,10,.4);background:#fafbfc;border-right:1px solid #e8eaed;white-space:nowrap;height:44px;display:flex;align-items:center;">kominhoo.ng/shop/</span><input type="text" class="form-input" id="addSlug" placeholder="cosrx-snail-mucin-96-essence" style="border:none;border-radius:0;flex:1;" /></div></div>
        <div class="form-group"><label>SEO Title <span style="font-weight:400;opacity:.5;font-size:.78rem;">max 60 chars</span></label><input type="text" class="form-input" id="addSeoTitle" maxlength="60" placeholder="e.g. COSRX Snail Mucin 96 Essence — Kominhoo Beauty Nigeria" oninput="document.getElementById('seoTitleCount').textContent=this.value.length" /><div style="text-align:right;font-size:.72rem;color:rgba(10,10,10,.35);margin-top:3px;"><span id="seoTitleCount">0</span>/60</div></div>
        <div class="form-group"><label>SEO Meta Description <span style="font-weight:400;opacity:.5;font-size:.78rem;">max 160 chars</span></label><textarea class="form-input" id="addSeoDesc" maxlength="160" rows="2" placeholder="Buy COSRX Snail Mucin Essence in Nigeria. Free delivery Lagos." style="resize:vertical;" oninput="document.getElementById('seoDescCount').textContent=this.value.length"></textarea><div style="text-align:right;font-size:.72rem;color:rgba(10,10,10,.35);margin-top:3px;"><span id="seoDescCount">0</span>/160</div></div>
        <div style="background:#fafbfc;border:1.5px solid #e8eaed;border-radius:12px;padding:16px;margin-bottom:20px;"><div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin-bottom:10px;">Google Preview</div><div style="font-size:.9rem;color:#1a0dab;font-weight:500;margin-bottom:2px;" id="serpTitle">Product Title — Kominhoo Beauty Nigeria</div><div style="font-size:.75rem;color:#006621;margin-bottom:4px;">kominhoo.ng/shop/product-slug</div><div style="font-size:.82rem;color:#545454;" id="serpDesc">Product description will appear here…</div></div>
        <div class="form-grid">
          <div class="form-group"><label>Related Products</label><input type="text" class="form-input" id="addRelated" placeholder="e.g. COSRX BHA Toner, Laneige Water Sleeping Mask" /></div>
          <div class="form-group"><label>Collections / Tags</label><input type="text" class="form-input" id="addTags" placeholder="e.g. Korean, Bestseller, Under-20k" /></div>
          <div class="form-group"><label>Availability</label><select class="form-input" id="addAvailability"><option>Available to all customers</option><option>Subscribers only</option><option>Loyalty members only</option><option>Hidden (internal use)</option></select></div>
          <div class="form-group"><label>Product Sort Priority</label><input type="number" class="form-input" id="addSortOrder" placeholder="Lower = appears first" value="0" /></div>
        </div>
      </div>
    </div>
    <div class="add-modal-footer" style="justify-content:space-between;">
      <div style="display:flex;gap:8px;"><button class="action-btn edit" onclick="document.getElementById('addModalOverlay').classList.remove('open')">Cancel</button><button class="action-btn edit" onclick="addProductSave('draft')">💾 Save as Draft</button></div>
      <div style="display:flex;gap:8px;"><button class="action-btn tag-btn" onclick="addProductSave('active')">Publish Product →</button><button class="action-btn primary" onclick="addProductSave('tag')">Publish &amp; Tag Skin OS →</button></div>
    </div>
  </div>
</div>

{{-- Edit Product Modal --}}
<div class="modal-overlay" id="editModalOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" style="max-width:820px;width:96vw;" onclick="event.stopPropagation()">
    <div class="add-modal-header">
      <div style="display:flex;align-items:center;gap:14px;"><img id="editModalImg" style="width:50px;height:50px;border-radius:10px;object-fit:cover;background:#eee;" src="" /><div><h2 id="editModalTitle">Edit Product</h2><p id="editModalSub" style="font-size:.78rem;color:rgba(10,10,10,.4);margin-top:1px;"></p></div></div>
      <div style="display:flex;align-items:center;gap:10px;">
        <select class="form-input" id="editProductStatus" style="width:130px;font-size:.82rem;padding:7px 10px;"><option value="active">● Published</option><option value="draft">○ Draft</option><option value="archived">✕ Archived</option></select>
        <button class="tag-modal-close" onclick="document.getElementById('editModalOverlay').classList.remove('open')">✕</button>
      </div>
    </div>
    <div style="display:flex;gap:0;border-bottom:2px solid #e8eaed;padding:0 26px;overflow-x:auto;">
      <div class="panel-tab active" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'etab-basic')">📋 Basic Info</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'etab-media')">📸 Media</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'etab-desc')">📝 Description</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'etab-pricing')">💰 Pricing &amp; Stock</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'etab-skinos')">🧬 Skin OS</div>
      <div class="panel-tab" style="font-size:.8rem;padding:10px 16px;" onclick="switchAddProductTab(this,'etab-seo')">🔗 SEO &amp; Settings</div>
    </div>
    <div class="add-modal-body" style="max-height:62vh;overflow-y:auto;">
      <div class="panel-tab-content active" id="etab-basic">
        <div class="form-grid">
          <div class="form-group" style="grid-column:1/-1;"><label>Product Name *</label><input type="text" class="form-input" id="editName" placeholder="e.g. COSRX Advanced Snail 96 Mucin Power Essence" /></div>
          <div class="form-group"><label>Brand *</label><input type="text" class="form-input" id="editBrand" placeholder="e.g. COSRX" /></div>
          <div class="form-group"><label>Country of Origin</label><select class="form-input" id="editOrigin"><option value="Korea">🇰🇷 South Korea</option><option value="Japan">🇯🇵 Japan</option><option value="USA">🇺🇸 USA</option><option value="Nigeria">🇳🇬 Nigeria</option></select></div>
          <div class="form-group"><label>Category *</label><select class="form-input" id="editCategory"><option value="">Select category…</option><option>Cleanser</option><option>Toner</option><option>Serum</option><option>Moisturiser</option><option>Sunscreen</option><option>Eye Cream</option><option>Mask</option><option>Exfoliant</option><option>Essence</option><option>Lip Care</option><option>Body Care</option><option>Treatment</option></select></div>
          <div class="form-group"><label>Sub-Category / Routine Step</label><select class="form-input" id="editRoutineStep"><option value="">None</option><option>Step 1 — Oil Cleanser</option><option>Step 2 — Water Cleanser</option><option>Step 4 — Toner</option><option>Step 5 — Essence</option><option>Step 6 — Treatment / Serum</option><option>Step 8 — Moisturiser</option><option>Step 10 — SPF (AM only)</option></select></div>
          <div class="form-group"><label>Volume / Size</label><input type="text" class="form-input" id="editVolume" placeholder="e.g. 100ml, 50g" /></div>
          <div class="form-group"><label>SKU</label><input type="text" class="form-input" id="editSku" placeholder="Auto-generated if blank" /></div>
          <div class="form-group"><label>Badge</label><select class="form-input" id="editBadge"><option value="">None</option><option>Best Seller</option><option>New</option><option>Staff Pick</option><option>Limited Edition</option><option>Viral</option><option>Low Stock</option></select></div>
          <div class="form-group"><label>Shelf Life (months)</label><input type="number" class="form-input" id="editShelfLife" placeholder="e.g. 24" /></div>
        </div>
        <div style="display:flex;gap:20px;margin-top:4px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="editIsNew" /> Mark as New Arrival</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="editInStock" /> In Stock</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="editFeatured" /> Featured on Homepage</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="editVegan" /> Vegan</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;"><input type="checkbox" id="editCrueltyFree" /> Cruelty-Free</label>
        </div>
      </div>
      <div class="panel-tab-content" id="etab-media">
        <div class="form-group">
          <label>Product Images <span style="font-weight:400;color:rgba(10,10,10,.4);font-size:.78rem;">First image = main.</span></label>
          <div id="editImageGrid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px;"></div>
        </div>
        <div class="form-group"><label>Add / Replace Image URL <span style="font-weight:400;opacity:.5;font-size:.78rem;">optional</span></label><input type="url" class="form-input" id="editImageUrl" placeholder="https://…" oninput="previewEditImageUrl(this.value)" /><div id="editImageUrlPreview" style="margin-top:8px;display:none;"><img id="editImageUrlThumb" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1.5px solid #e8eaed;" /></div></div>
      </div>
      <div class="panel-tab-content" id="etab-desc">
        <div class="form-group"><label>Short Description <span style="font-weight:400;opacity:.5;font-size:.78rem;">shown in product cards (max 160 chars)</span></label><textarea class="form-input" id="editShortDesc" rows="2" maxlength="160" placeholder="One-liner that sells the product…" style="resize:vertical;" oninput="document.getElementById('editShortDescCount').textContent=this.value.length"></textarea><div style="text-align:right;font-size:.72rem;color:rgba(10,10,10,.35);margin-top:3px;"><span id="editShortDescCount">0</span>/160</div></div>
        <div class="form-group"><label>Long Description <span style="font-weight:400;opacity:.5;font-size:.78rem;">full product page copy</span></label><div style="border:1.5px solid #e8eaed;border-radius:10px;overflow:hidden;"><div style="background:#fafbfc;border-bottom:1px solid #f0f2f4;padding:8px 12px;display:flex;gap:6px;flex-wrap:wrap;"><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('bold')"><strong>B</strong></button><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('italic')"><em>I</em></button><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('underline')"><u>U</u></button><span style="width:1px;background:#e8eaed;margin:0 4px;"></span><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('insertUnorderedList')">• List</button><button type="button" class="action-btn edit" style="padding:3px 8px;font-size:.75rem;" onclick="fmtDoc('insertOrderedList')">1. List</button></div><div id="editLongDesc" contenteditable="true" style="min-height:140px;padding:14px;font-size:.85rem;line-height:1.7;outline:none;" data-placeholder="Write a compelling full description…"></div></div></div>
        <div class="form-group"><label>How to Use</label><textarea class="form-input" id="editHowToUse" rows="3" placeholder="Step-by-step usage instructions…" style="resize:vertical;"></textarea></div>
        <div class="form-grid">
          <div class="form-group"><label>Key Active Ingredients</label><input type="text" class="form-input" id="editActiveIngredients" placeholder="e.g. Niacinamide 10%, Snail Secretion Filtrate 96%" /></div>
          <div class="form-group"><label>Fragrance / Alcohol Status</label><select class="form-input" id="editFragrance"><option>Fragrance-Free</option><option>Lightly Fragranced</option><option>Fragranced</option><option>Alcohol-Free</option><option>Contains Denatured Alcohol</option></select></div>
        </div>
        <div class="form-group"><label>Full Ingredients List (INCI)</label><textarea class="form-input" id="editIngredients" rows="3" placeholder="Water, Niacinamide, Glycerin…" style="resize:vertical;font-size:.78rem;"></textarea></div>
        <div class="form-group"><label>Warnings / Contraindications</label><textarea class="form-input" id="editWarnings" rows="2" placeholder="e.g. Do not use with retinol." style="resize:vertical;"></textarea></div>
      </div>
      <div class="panel-tab-content" id="etab-pricing">
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin-bottom:12px;">Pricing</div>
        <div class="form-grid">
          <div class="form-group"><label>Selling Price (₦) *</label><input type="number" class="form-input" id="editPrice" placeholder="e.g. 14500" oninput="calcEditMargin()" /></div>
          <div class="form-group"><label>Compare-at Price (₦)</label><input type="number" class="form-input" id="editOriginalPrice" placeholder="Leave blank if not on sale" /></div>
          <div class="form-group"><label>Cost Price (₦)</label><input type="number" class="form-input" id="editCostPrice" placeholder="Your purchase cost" oninput="calcEditMargin()" /></div>
          <div class="form-group"><label>Gross Margin</label><div style="display:flex;align-items:center;height:44px;padding:0 14px;background:#fafbfc;border:1.5px solid #e8eaed;border-radius:10px;font-size:.88rem;font-weight:700;" id="editMarginDisplay">—</div></div>
        </div>
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin:18px 0 12px;">Inventory &amp; Batch Tracking</div>
        <div class="form-grid">
          <div class="form-group"><label>Stock Quantity *</label><input type="number" class="form-input" id="editStockQty" placeholder="e.g. 50" /></div>
          <div class="form-group"><label>Low Stock Alert At</label><input type="number" class="form-input" id="editRestockLevel" placeholder="e.g. 10" /></div>
          <div class="form-group"><label>Batch Number</label><input type="text" class="form-input" id="editBatch" placeholder="e.g. KMH-B512" /></div>
          <div class="form-group"><label>Expiry Date</label><input type="month" class="form-input" id="editExpiry" /></div>
          <div class="form-group"><label>Warehouse Location</label><input type="text" class="form-input" id="editWarehouse" placeholder="e.g. Lagos Warehouse A, Shelf 3" /></div>
          <div class="form-group"><label>Track Inventory</label><select class="form-input" id="editTrackInventory"><option value="track">Track quantity</option><option value="notrack">Don't track</option></select></div>
        </div>
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin:18px 0 12px;">Shipping</div>
        <div class="form-grid">
          <div class="form-group"><label>Weight (kg)</label><input type="number" class="form-input" id="editWeight" placeholder="e.g. 0.15" step="0.01" /></div>
          <div class="form-group"><label>Dimensions (L × W × H cm)</label><input type="text" class="form-input" id="editDimensions" placeholder="e.g. 12 × 6 × 4" /></div>
          <div class="form-group"><label>Requires Cold Chain?</label><select class="form-input" id="editColdChain"><option value="no">No</option><option value="yes">Yes — refrigerate</option></select></div>
          <div class="form-group"><label>Custom Shipping Note</label><input type="text" class="form-input" id="editShipNote" placeholder="e.g. Handle with care · fragile" /></div>
        </div>
      </div>
      <div class="panel-tab-content" id="etab-skinos">
        <div style="background:#fafbfc;border:1.5px solid #e8eaed;border-radius:12px;padding:16px;margin-bottom:20px;"><div style="font-size:.78rem;color:rgba(10,10,10,.5);line-height:1.6;">The Skin OS tagging system links this product to customer quiz profiles. Tag accurately — these tags drive routine recommendations and bundle matching.</div></div>
        <div class="form-group"><label>Suitable Skin Types</label><div style="display:flex;flex-wrap:wrap;gap:8px;padding:12px;border:1.5px solid #e8eaed;border-radius:10px;"><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="editSkinType" value="All Skin Types" /> All Skin Types</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="editSkinType" value="Oily" /> Oily</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="editSkinType" value="Dry" /> Dry</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="editSkinType" value="Combination" /> Combination</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="editSkinType" value="Sensitive" /> Sensitive</label><label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;"><input type="checkbox" name="editSkinType" value="Normal" /> Normal</label></div></div>
        <div class="form-grid">
          <div class="form-group"><label>Routine Time</label><select class="form-input" id="editRoutineTime"><option value="">Both AM &amp; PM</option><option>AM Only</option><option>PM Only</option><option>Weekly Treatment</option></select></div>
          <div class="form-group"><label>Sensitivity Score</label><select class="form-input" id="editSensitivityScore"><option value="1">1 — Very Gentle</option><option value="2">2 — Gentle</option><option value="3">3 — Moderate</option><option value="4">4 — Active</option><option value="5">5 — Strong</option></select></div>
          <div class="form-group"><label>Pairs Well With</label><input type="text" class="form-input" id="editPairsWith" placeholder="e.g. Laneige Water Sleeping Mask" /></div>
          <div class="form-group"><label>Do NOT Combine With</label><input type="text" class="form-input" id="editAvoidWith" placeholder="e.g. Retinol, AHA/BHA" /></div>
        </div>
      </div>
      <div class="panel-tab-content" id="etab-seo">
        <div class="form-group"><label>URL Slug</label><div style="display:flex;align-items:center;border:1.5px solid #e8eaed;border-radius:10px;overflow:hidden;"><span style="padding:0 12px;font-size:.8rem;color:rgba(10,10,10,.4);background:#fafbfc;border-right:1px solid #e8eaed;white-space:nowrap;height:44px;display:flex;align-items:center;">kominhoo.ng/shop/</span><input type="text" class="form-input" id="editSlug" placeholder="product-url-slug" style="border:none;border-radius:0;flex:1;" /></div></div>
        <div class="form-group"><label>SEO Title <span style="font-weight:400;opacity:.5;font-size:.78rem;">max 60 chars</span></label><input type="text" class="form-input" id="editSeoTitle" maxlength="60" placeholder="e.g. COSRX Snail Mucin 96 Essence — Kominhoo Beauty Nigeria" oninput="document.getElementById('editSeoTitleCount').textContent=this.value.length" /><div style="text-align:right;font-size:.72rem;color:rgba(10,10,10,.35);margin-top:3px;"><span id="editSeoTitleCount">0</span>/60</div></div>
        <div class="form-group"><label>SEO Meta Description <span style="font-weight:400;opacity:.5;font-size:.78rem;">max 160 chars</span></label><textarea class="form-input" id="editSeoDesc" maxlength="160" rows="2" placeholder="Buy this product in Nigeria. Free delivery Lagos." style="resize:vertical;" oninput="document.getElementById('editSeoDescCount').textContent=this.value.length"></textarea><div style="text-align:right;font-size:.72rem;color:rgba(10,10,10,.35);margin-top:3px;"><span id="editSeoDescCount">0</span>/160</div></div>
        <div style="background:#fafbfc;border:1.5px solid #e8eaed;border-radius:12px;padding:16px;margin-bottom:20px;"><div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin-bottom:10px;">Google Preview</div><div style="font-size:.9rem;color:#1a0dab;font-weight:500;margin-bottom:2px;" id="editSerpTitle">Product Title — Kominhoo Beauty Nigeria</div><div style="font-size:.75rem;color:#006621;margin-bottom:4px;">kominhoo.ng/shop/product-slug</div><div style="font-size:.82rem;color:#545454;" id="editSerpDesc">Product description will appear here…</div></div>
        <div class="form-grid">
          <div class="form-group"><label>Related Products</label><input type="text" class="form-input" id="editRelated" placeholder="e.g. COSRX BHA Toner, Laneige Water Sleeping Mask" /></div>
          <div class="form-group"><label>Collections / Tags</label><input type="text" class="form-input" id="editTags" placeholder="e.g. Korean, Bestseller, Under-20k" /></div>
          <div class="form-group"><label>Availability</label><select class="form-input" id="editAvailability"><option>Available to all customers</option><option>Subscribers only</option><option>Loyalty members only</option><option>Hidden (internal use)</option></select></div>
          <div class="form-group"><label>Product Sort Priority</label><input type="number" class="form-input" id="editSortOrder" placeholder="Lower = appears first" value="0" /></div>
        </div>
      </div>
    </div>
    <div class="add-modal-footer" style="justify-content:space-between;">
      <div style="display:flex;gap:8px;"><button class="action-btn danger" onclick="deleteProduct(window._editProductId)">🗑️ Delete</button><button class="action-btn edit" onclick="document.getElementById('editModalOverlay').classList.remove('open')">Cancel</button></div>
      <div><button class="action-btn primary" onclick="saveEditProduct()">💾 Save Changes</button></div>
    </div>
  </div>
</div>

{{-- Order Detail Modal --}}
<div class="modal-overlay" id="orderModalOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" style="max-width:680px;" onclick="event.stopPropagation()">
    <div class="add-modal-header"><div><h2 id="orderModalTitle">Order #KMH-2847</h2><p id="orderModalSub" style="font-size:.78rem;color:rgba(10,10,10,.4);margin-top:2px;">Apr 12, 2026 · Adaeze Okonkwo</p></div><button class="tag-modal-close" onclick="document.getElementById('orderModalOverlay').classList.remove('open')">✕</button></div>
    <div class="add-modal-body">
      <div style="background:#fafbfc;border:1.5px solid #e8eaed;border-radius:12px;padding:16px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div><div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(10,10,10,.4);margin-bottom:6px;">Order Status</div><span class="status-badge pending" id="orderCurrentStatus">Pending</span></div>
        <div style="display:flex;gap:8px;align-items:center;"><select class="form-input" id="orderStatusSelect" style="width:160px;padding:7px 12px;"><option>Pending</option><option>Processing</option><option>Shipped</option><option>Delivered</option><option>Cancelled</option></select><button class="action-btn primary" onclick="updateOrderStatus()">Update</button></div>
      </div>
      <div class="form-grid" style="margin-bottom:16px;">
        <div><div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(10,10,10,.4);margin-bottom:8px;">Customer</div><div id="oCustomerName" style="font-size:.88rem;font-weight:600;">Adaeze Okonkwo</div><div id="oCustomerEmail" style="font-size:.78rem;color:rgba(10,10,10,.45);">adaeze@email.com</div></div>
        <div><div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(10,10,10,.4);margin-bottom:8px;">Delivery Address</div><div id="oAddress" style="font-size:.85rem;line-height:1.6;color:rgba(10,10,10,.7);">12 Admiralty Way, Lekki Phase 1<br>Lagos State, Nigeria</div></div>
      </div>
      <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(10,10,10,.4);margin-bottom:10px;">Order Items</div>
      <div style="border:1.5px solid #e8eaed;border-radius:12px;overflow:hidden;margin-bottom:16px;" id="oItemsList">
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #f4f5f7;"><img style="width:44px;height:44px;border-radius:8px;object-fit:cover;" src="https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=100&h=100&fit=crop" /><div style="flex:1;"><div style="font-weight:600;font-size:.85rem;">Advanced Bundle Kit (Deep Hydration)</div><div style="font-size:.75rem;color:rgba(10,10,10,.4);">Qty: 1</div></div><div style="font-weight:700;">₦81,500</div></div>
        <div style="background:#fafbfc;padding:12px 16px;"><div style="display:flex;justify-content:space-between;font-size:.82rem;color:rgba(10,10,10,.5);margin-bottom:3px;"><span>Subtotal</span><span>₦81,500</span></div><div style="display:flex;justify-content:space-between;font-size:.82rem;color:rgba(10,10,10,.5);margin-bottom:3px;"><span>Delivery</span><span style="color:#16a34a;">Free</span></div><div style="display:flex;justify-content:space-between;font-size:.92rem;font-weight:700;margin-top:8px;padding-top:8px;border-top:1px solid #e8eaed;"><span>Total</span><span id="oTotal">₦81,500</span></div></div>
      </div>
      <div class="form-group"><label>Tracking Number</label><input type="text" id="oTrackingNumber" class="form-input" placeholder="e.g. NG123456789" /></div>
      <div class="form-group"><label>Note to Customer <span style="font-size:.72rem;font-weight:400;color:rgba(10,10,10,.45)">(visible on customer dashboard)</span></label><textarea id="oAdminNote" class="form-input" rows="2" placeholder="e.g. Your order is being packed carefully. Expected delivery: Friday." style="resize:vertical;"></textarea></div>
    </div>
    <div class="add-modal-footer"><button class="action-btn danger" onclick="cancelOrder()">Cancel Order</button><button class="action-btn edit" onclick="document.getElementById('orderModalOverlay').classList.remove('open')">Close</button><button class="action-btn primary" onclick="saveOrderChanges()">💾 Save Changes</button></div>
  </div>
</div>

{{-- User Detail Modal --}}
<div class="modal-overlay" id="userModalOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" style="max-width:680px;" onclick="event.stopPropagation()">
    <div class="add-modal-header"><h2>Customer Profile</h2><button class="tag-modal-close" onclick="document.getElementById('userModalOverlay').classList.remove('open')">✕</button></div>
    <div class="add-modal-body">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px;padding:16px;background:#fafbfc;border-radius:14px;border:1.5px solid #e8eaed;"><div id="uAvatar" style="width:56px;height:56px;border-radius:50%;background:var(--lime);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem;color:var(--black);flex-shrink:0;">AO</div><div style="flex:1;"><div id="uName" style="font-size:1rem;font-weight:700;">Adaeze Okonkwo</div><div id="uEmail" style="font-size:.78rem;color:rgba(10,10,10,.45);">adaeze@email.com</div><div style="margin-top:6px;"><span class="status-badge active">Active</span></div></div><div style="text-align:right;"><div style="font-size:.7rem;color:rgba(10,10,10,.4);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Loyalty Tier</div><div id="uTier" style="font-size:.9rem;font-weight:700;color:var(--red);">💎 Radiant Insider</div></div></div>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:#f0f2f4;border-radius:12px;overflow:hidden;margin-bottom:18px;"><div style="background:#fff;padding:14px 16px;text-align:center;"><div id="uStatOrders" style="font-size:1.3rem;font-weight:700;">7</div><div style="font-size:.72rem;color:rgba(10,10,10,.4);margin-top:2px;">Orders</div></div><div style="background:#fff;padding:14px 16px;text-align:center;"><div id="uStatSpend" style="font-size:1.3rem;font-weight:700;">₦312K</div><div style="font-size:.72rem;color:rgba(10,10,10,.4);margin-top:2px;">Total Spend</div></div><div style="background:#fff;padding:14px 16px;text-align:center;"><div id="uStatPoints" style="font-size:1.3rem;font-weight:700;">3,120</div><div style="font-size:.72rem;color:rgba(10,10,10,.4);margin-top:2px;">Points</div></div><div style="background:#fff;padding:14px 16px;text-align:center;"><div id="uStatReviews" style="font-size:1.3rem;font-weight:700;">3</div><div style="font-size:.72rem;color:rgba(10,10,10,.4);margin-top:2px;">Reviews</div></div></div>
      <div class="user-modal-tabs"><div class="user-modal-tab active" onclick="switchUserModalTab(this,'umtab-overview')">Overview</div><div class="user-modal-tab" onclick="switchUserModalTab(this,'umtab-skin')">Skin Profile</div><div class="user-modal-tab" onclick="switchUserModalTab(this,'umtab-quiz')">Quiz History</div><div class="user-modal-tab" onclick="switchUserModalTab(this,'umtab-orders')">Order History</div></div>
      <div class="user-modal-tab-content active" id="umtab-overview">
        <div class="form-grid">
          <div class="form-group"><label>Skin Type (from Quiz)</label><select class="form-input" id="uSkinType"><option>Combination</option><option>Oily</option><option>Dry</option><option>Sensitive</option><option>Normal</option></select></div>
          <div class="form-group"><label>Joined</label><input type="text" class="form-input" id="uJoined" value="Jan 2026" readonly style="background:#fafbfc;" /></div>
          <div class="form-group"><label>Manually Award Points</label><input type="number" class="form-input" placeholder="e.g. 500" /></div>
          <div class="form-group"><label>Override Loyalty Tier</label><select class="form-input"><option>Auto (based on spend)</option><option>Glow Starter</option><option selected>Radiant Insider</option><option>Luxe Luminary</option></select></div>
        </div>
        <div class="form-group"><label>Internal Note</label><textarea class="form-input" rows="2" placeholder="Add a note about this customer…" style="resize:vertical;"></textarea></div>
      </div>
      <div class="user-modal-tab-content" id="umtab-skin">
        <div style="background:#fafbfc;border-radius:12px;padding:16px;border:1.5px solid #e8eaed;margin-bottom:16px;"><div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:rgba(10,10,10,.4);margin-bottom:12px;">Skin OS Profile</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;"><div><div style="font-size:.75rem;color:rgba(10,10,10,.4);margin-bottom:4px;">Skin Type</div><span class="tag-chip skin">Combination</span></div><div><div style="font-size:.75rem;color:rgba(10,10,10,.4);margin-bottom:4px;">Sensitivity Level</div><span class="tag-chip" style="background:#fef3c7;color:#92400e;">Moderate</span></div><div><div style="font-size:.75rem;color:rgba(10,10,10,.4);margin-bottom:4px;">Primary Concerns</div><div style="display:flex;flex-wrap:wrap;gap:4px;"><span class="tag-chip concern">Hyperpigmentation</span><span class="tag-chip concern">Dullness</span></div></div><div><div style="font-size:.75rem;color:rgba(10,10,10,.4);margin-bottom:4px;">Routine Focus</div><div style="display:flex;flex-wrap:wrap;gap:4px;"><span class="tag-chip routine">AM Routine</span><span class="tag-chip routine">PM Routine</span></div></div></div></div>
        <div class="form-grid"><div class="form-group"><label>Override Skin Type</label><select class="form-input"><option>Combination</option><option>Oily</option><option>Dry</option><option>Sensitive</option><option>Normal</option></select></div><div class="form-group"><label>Sensitivity Level</label><select class="form-input"><option>None</option><option selected>Moderate</option><option>High</option></select></div></div>
      </div>
      <div class="user-modal-tab-content" id="umtab-quiz">
        <div style="margin-bottom:14px;">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;border:1.5px solid #e8eaed;border-radius:12px;margin-bottom:8px;"><div><div style="font-weight:600;font-size:.88rem;">Skin OS Quiz — Full Assessment</div><div style="font-size:.75rem;color:rgba(10,10,10,.45);margin-top:2px;">Completed Mar 18, 2026 · Result: Combination + Hyperpigmentation</div></div><div style="display:flex;gap:8px;align-items:center;"><span class="status-badge active">Completed</span><button class="action-btn edit" style="padding:4px 10px;">View</button></div></div>
        </div>
        <div style="padding:14px;background:#fafbfc;border-radius:12px;border:1.5px solid #f0f2f4;"><div style="font-size:.78rem;color:rgba(10,10,10,.45);">Assigned routine after last quiz:</div><div class="routine-steps" style="margin-top:8px;"><span class="routine-step-chip"><span class="step-num">1</span> Gentle Cleanser</span><span class="routine-step-chip"><span class="step-num">2</span> Niacinamide Toner</span><span class="routine-step-chip"><span class="step-num">3</span> Vitamin C Serum</span><span class="routine-step-chip"><span class="step-num">4</span> Moisturiser</span><span class="routine-step-chip"><span class="step-num">5</span> SPF50+</span></div></div>
      </div>
      <div class="user-modal-tab-content" id="umtab-orders">
        <table class="data-table" style="font-size:.82rem;"><thead><tr><th>Order ID</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th></tr></thead><tbody><tr><td><strong>#KMH-2847</strong></td><td>Apr 12, 2026</td><td>COSRX BHA, Laneige Mask</td><td><strong>₦81,500</strong></td><td><span class="status-badge pending">Pending</span></td></tr><tr><td><strong>#KMH-2801</strong></td><td>Mar 20, 2026</td><td>Skincare Bundle — Hydration Kit</td><td><strong>₦65,000</strong></td><td><span class="status-badge shipped">Shipped</span></td></tr><tr><td><strong>#KMH-2755</strong></td><td>Feb 28, 2026</td><td>Numbuzin Vitamin C, SPF Serum</td><td><strong>₦54,000</strong></td><td><span class="status-badge active">Delivered</span></td></tr></tbody></table>
      </div>
    </div>
    <div class="add-modal-footer"><button class="action-btn danger" onclick="showToast('⛔','Account suspended.');document.getElementById('userModalOverlay').classList.remove('open')">Suspend Account</button><button class="action-btn edit" onclick="document.getElementById('userModalOverlay').classList.remove('open')">Close</button><button class="action-btn primary" onclick="showToast('✅','Customer profile updated!');document.getElementById('userModalOverlay').classList.remove('open')">💾 Save Changes</button></div>
  </div>
</div>

{{-- Coupon Create / Edit Modal --}}
<div class="modal-overlay" id="couponModalOverlay" onclick="if(event.target===this)closeCouponModal()">
  <div class="add-modal" onclick="event.stopPropagation()" style="max-width:640px;border-radius:20px;overflow:hidden;">

    {{-- Gradient accent bar --}}
    <div style="height:5px;background:linear-gradient(90deg,#c8e634 0%,#a5c400 40%,#4f94ea 100%);"></div>

    <div class="add-modal-header" style="border-bottom:1.5px solid #f0f0ec;padding:20px 24px;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#c8e634,#a5c400);display:grid;place-items:center;font-size:1.2rem;flex-shrink:0;box-shadow:0 4px 12px rgba(165,196,0,.35);">🎟️</div>
        <div>
          <h2 id="couponModalTitle" style="font-size:1.05rem;font-weight:700;margin:0;line-height:1.2;">Create Voucher</h2>
          <div style="font-size:.73rem;color:rgba(10,10,10,.38);margin-top:2px;">Discount code · Validity · Targeting</div>
        </div>
      </div>
      <button class="tag-modal-close" onclick="closeCouponModal()">✕</button>
    </div>

    <div class="add-modal-body" style="padding:20px 24px;display:grid;gap:16px;">
      <input type="hidden" id="coupon_edit_id" value="">

      {{-- Identity block --}}
      <div style="background:linear-gradient(135deg,#f9f9f7,#f1f0ea);border-radius:14px;padding:18px;">
        <div style="font-size:.65rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(10,10,10,.32);margin-bottom:12px;">Voucher Identity</div>
        <div class="form-grid" style="margin-bottom:12px;">
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Code <span style="color:#e63434;">*</span></label>
            <input type="text" id="coupon_code" class="form-input"
              placeholder="e.g. SAVE20"
              style="text-transform:uppercase;font-family:'Courier New',monospace;font-size:1.1rem;font-weight:700;letter-spacing:.12em;margin-top:6px;background:#fff;" oninput="this.value=this.value.toUpperCase().replace(/\s/g,'')" />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Discount Type <span style="color:#e63434;">*</span></label>
            <select id="coupon_discount_type" class="form-input" onchange="toggleDiscountValue()" style="margin-top:6px;background:#fff;">
              <option value="percentage">📉 Percentage off (%)</option>
              <option value="fixed">💰 Fixed amount (₦)</option>
              <option value="free_shipping">🚚 Free shipping only</option>
            </select>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:0;">
          <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Short Description</label>
          <input type="text" id="coupon_description" class="form-input" placeholder="e.g. 15% off entire order" style="margin-top:6px;background:#fff;" />
        </div>
      </div>

      {{-- Discount config block --}}
      <div style="border:1.5px solid #eeedea;border-radius:14px;padding:18px;">
        <div style="font-size:.65rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(10,10,10,.32);margin-bottom:12px;">Discount Configuration</div>
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group" id="coupon_value_wrap" style="margin-bottom:0;">
            <label id="coupon_value_label" style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Discount Value (%)</label>
            <input type="number" id="coupon_discount_value" class="form-input" placeholder="e.g. 15" min="0" style="margin-top:6px;" />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Minimum Order (₦)</label>
            <input type="number" id="coupon_min_order" class="form-input" placeholder="0 = no minimum" min="0" style="margin-top:6px;" />
          </div>
        </div>
        <label style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:12px 14px;border-radius:10px;border:1.5px solid #eeedea;transition:border-color .15s,background .15s;background:#fafaf8;" onmouseover="this.style.borderColor='#a5c400';this.style.background='#f6fce0'" onmouseout="this.style.borderColor='#eeedea';this.style.background='#fafaf8'">
          <input type="checkbox" id="coupon_free_shipping" style="width:17px;height:17px;accent-color:#a5c400;flex-shrink:0;" />
          <div>
            <div style="font-size:.84rem;font-weight:700;">🚚 Also include free shipping</div>
            <div style="font-size:.72rem;color:rgba(10,10,10,.4);margin-top:2px;">Customer pays ₦0 shipping even if below free-shipping threshold</div>
          </div>
        </label>
      </div>

      {{-- Limits + Dates block --}}
      <div style="border:1.5px solid #eeedea;border-radius:14px;padding:18px;">
        <div style="font-size:.65rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(10,10,10,.32);margin-bottom:12px;">Limits & Validity</div>
        <div class="form-grid" style="margin-bottom:0;">
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Max Total Uses</label>
            <input type="number" id="coupon_max_uses" class="form-input" placeholder="Blank = unlimited" min="1" style="margin-top:6px;" />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Uses Per Customer</label>
            <input type="number" id="coupon_uses_per_customer" class="form-input" value="1" min="1" style="margin-top:6px;" />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Start Date</label>
            <input type="date" id="coupon_start_date" class="form-input" style="margin-top:6px;" />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Expiry Date</label>
            <input type="date" id="coupon_expiry_date" class="form-input" style="margin-top:6px;" />
          </div>
        </div>
      </div>

      {{-- Targeting block --}}
      <div style="border:1.5px solid #eeedea;border-radius:14px;padding:18px;">
        <div style="font-size:.65rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(10,10,10,.32);margin-bottom:12px;">Targeting</div>
        <div class="form-grid" style="margin-bottom:0;">
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Applicable To</label>
            <select id="coupon_applicable_to" class="form-input" style="margin-top:6px;">
              <option value="all">All products</option>
              <option value="bundles">Bundles only</option>
              <option value="subscription">Subscription only</option>
            </select>
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:.78rem;font-weight:700;color:rgba(10,10,10,.7);">Customer Restriction</label>
            <select id="coupon_customer_restriction" class="form-input" style="margin-top:6px;">
              <option value="all">All customers</option>
              <option value="new_only">New customers only</option>
              <option value="tier:glow">Glow Starter tier only</option>
              <option value="tier:radiant">Radiant Insider tier only</option>
              <option value="tier:luxe">Luxe Luminary tier only</option>
            </select>
          </div>
        </div>
      </div>

      <div id="coupon-modal-error" style="display:none;background:#fef2f2;border:1.5px solid #fca5a5;border-radius:10px;padding:12px 16px;font-size:.83rem;color:#dc2626;font-weight:500;"></div>
    </div>

    <div class="add-modal-footer" style="border-top:1.5px solid #f0f0ec;padding:16px 24px;">
      <button class="action-btn edit" onclick="closeCouponModal()">Cancel</button>
      <button class="action-btn primary" id="coupon-save-btn" onclick="saveCoupon()" style="min-width:160px;">Create Voucher →</button>
    </div>
  </div>
</div>

{{-- Routine Editor Modal --}}
<div class="modal-overlay" id="routineEditorOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" style="max-width:640px;" onclick="event.stopPropagation()">
    <div class="add-modal-header"><h2>Routine Editor</h2><button class="tag-modal-close" onclick="document.getElementById('routineEditorOverlay').classList.remove('open')">✕</button></div>
    <div class="add-modal-body">
      <div class="form-grid">
        <div class="form-group"><label>Customer / Client *</label><input type="text" class="form-input" placeholder="Search customer name…" value="Adaeze Okonkwo" /></div>
        <div class="form-group"><label>Routine Name *</label><input type="text" class="form-input" placeholder="e.g. AM Brightening Routine" /></div>
        <div class="form-group"><label>Skin Type</label><select class="form-input"><option>Combination</option><option>Oily</option><option>Dry</option><option>Sensitive</option><option>Normal</option></select></div>
        <div class="form-group"><label>Routine Type</label><select class="form-input"><option>AM Routine</option><option>PM Routine</option><option>AM + PM</option><option>Weekly Treatment</option></select></div>
      </div>
      <div class="form-group"><label>Skin Concern Focus</label><div style="display:flex;flex-wrap:wrap;gap:8px;padding:12px;border:1.5px solid #e8eaed;border-radius:10px;"><label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer;"><input type="checkbox" checked /> Hyperpigmentation</label><label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer;"><input type="checkbox" /> Acne &amp; Breakouts</label><label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer;"><input type="checkbox" /> Hydration</label><label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer;"><input type="checkbox" /> Anti-Aging</label><label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer;"><input type="checkbox" /> Brightening</label><label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer;"><input type="checkbox" /> Sensitivity</label></div></div>
      <div class="form-group"><label>Routine Steps <button class="action-btn edit" style="padding:3px 8px;font-size:.72rem;margin-left:8px;" onclick="addRoutineStep()">+ Add Step</button></label>
        <div id="routineStepsList">
          <div class="routine-step-edit-row" style="display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px solid #f4f5f7;"><span style="font-size:.75rem;font-weight:700;color:rgba(10,10,10,.35);width:20px;text-align:center;">1</span><select class="form-input" style="width:140px;font-size:.82rem;padding:7px 10px;flex-shrink:0;"><option>Cleanser</option><option>Toner</option><option>Serum</option><option>Moisturiser</option><option>Eye Cream</option><option>SPF</option><option>Treatment</option><option>Mask</option></select><input type="text" class="form-input" style="flex:1;" placeholder="Product name or 'AI choose'…" value="COSRX Low pH Good Morning Gel" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.routine-step-edit-row').remove()">🗑️</button></div>
          <div class="routine-step-edit-row" style="display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px solid #f4f5f7;"><span style="font-size:.75rem;font-weight:700;color:rgba(10,10,10,.35);width:20px;text-align:center;">2</span><select class="form-input" style="width:140px;font-size:.82rem;padding:7px 10px;flex-shrink:0;"><option>Cleanser</option><option selected>Toner</option><option>Serum</option><option>Moisturiser</option><option>Eye Cream</option><option>SPF</option></select><input type="text" class="form-input" style="flex:1;" placeholder="Product name or 'AI choose'…" value="Some By Mi AHA BHA PHA 30 Days Miracle" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.routine-step-edit-row').remove()">🗑️</button></div>
          <div class="routine-step-edit-row" style="display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px solid #f4f5f7;"><span style="font-size:.75rem;font-weight:700;color:rgba(10,10,10,.35);width:20px;text-align:center;">3</span><select class="form-input" style="width:140px;font-size:.82rem;padding:7px 10px;flex-shrink:0;"><option>Cleanser</option><option>Toner</option><option selected>Serum</option><option>Moisturiser</option><option>Eye Cream</option><option>SPF</option></select><input type="text" class="form-input" style="flex:1;" placeholder="Product name or 'AI choose'…" value="Beauty of Joseon Glow Serum" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.routine-step-edit-row').remove()">🗑️</button></div>
        </div>
      </div>
      <div class="form-group"><label>Admin Notes</label><textarea class="form-input" rows="2" placeholder="Notes for this routine…" style="resize:vertical;"></textarea></div>
    </div>
    <div class="add-modal-footer"><button class="action-btn edit" onclick="document.getElementById('routineEditorOverlay').classList.remove('open')">Cancel</button><button class="action-btn tag-btn" onclick="showToast('📤','Routine sent to client!');document.getElementById('routineEditorOverlay').classList.remove('open')">📤 Save &amp; Send to Client</button><button class="action-btn primary" onclick="showToast('✅','Routine saved!');document.getElementById('routineEditorOverlay').classList.remove('open')">💾 Save Routine</button></div>
  </div>
</div>

{{-- Admin: User Routine Editor Modal --}}
<div class="modal-overlay" id="userRoutineEditorOverlay" onclick="if(event.target===this)closeUserRoutineEditor()">
  <div class="add-modal" style="max-width:700px;" onclick="event.stopPropagation()">
    <div class="add-modal-header">
      <div>
        <h2>Edit Routine Log</h2>
        <p id="ure-subtitle" style="font-size:.8rem;color:rgba(10,10,10,.45);margin-top:2px;">Select a date and adjust steps</p>
      </div>
      <button class="tag-modal-close" onclick="closeUserRoutineEditor()">✕</button>
    </div>
    <div class="add-modal-body">
      {{-- Date selector --}}
      <div class="form-group" style="margin-bottom:18px;">
        <label style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(10,10,10,.45);display:block;margin-bottom:8px;">Select Date</label>
        <div id="ure-date-grid" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
      </div>
      {{-- AM / PM tabs --}}
      <div style="display:flex;gap:8px;margin-bottom:18px;">
        <button class="action-btn primary" id="ure-am-tab" onclick="ureTab('am')" style="font-size:.82rem;">☀️ AM Routine</button>
        <button class="action-btn edit"    id="ure-pm-tab" onclick="ureTab('pm')" style="font-size:.82rem;">🌙 PM Routine</button>
      </div>
      {{-- Step checklists --}}
      <div id="ure-steps-am"></div>
      <div id="ure-steps-pm" style="display:none;"></div>
      {{-- Mark done toggles --}}
      <div style="display:flex;gap:12px;margin-top:18px;padding-top:16px;border-top:1.5px solid #f4f5f7;flex-wrap:wrap;">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;font-weight:600;">
          <input type="checkbox" id="ure-am-done" onchange="ureSync()" style="width:16px;height:16px;cursor:pointer;"> ☀️ Mark AM Complete
        </label>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;font-weight:600;">
          <input type="checkbox" id="ure-pm-done" onchange="ureSync()" style="width:16px;height:16px;cursor:pointer;"> 🌙 Mark PM Complete
        </label>
      </div>
    </div>
    <div class="add-modal-footer">
      <span id="ure-pts-preview" style="font-size:.82rem;color:rgba(10,10,10,.45);margin-right:auto;align-self:center;"></span>
      <button class="action-btn edit" onclick="closeUserRoutineEditor()">Cancel</button>
      <button class="action-btn primary" id="ure-save-btn" onclick="saveUserRoutineLog()">💾 Save Log</button>
    </div>
  </div>
</div>

{{-- Manage Subscription Box Modal --}}
<div class="modal-overlay" id="manageBoxOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" style="max-width:600px;" onclick="event.stopPropagation()">
    <div class="add-modal-header"><div><h2>Manage Subscription Box</h2><p id="manageBoxSubtitle" style="font-size:.8rem;color:rgba(10,10,10,.45);margin-top:2px;">Adaeze Okonkwo · Advanced Plan · May 2026 Box</p></div><button class="tag-modal-close" onclick="document.getElementById('manageBoxOverlay').classList.remove('open')">✕</button></div>
    <div class="add-modal-body">
      <div style="display:flex;gap:10px;margin-bottom:20px;"><button class="action-btn primary" onclick="showToast('🚚','Shipment triggered!');document.getElementById('manageBoxOverlay').classList.remove('open')">🚚 Trigger Shipment</button><button class="action-btn edit" onclick="showToast('⏭️','This box has been skipped!')">⏭️ Skip This Box</button><button class="action-btn danger" onclick="showToast('⏸️','Subscription paused.')">⏸️ Pause</button></div>
      <div class="form-group"><label>Box Contents <span style="font-weight:400;color:rgba(10,10,10,.4);font-size:.78rem;">(drag to reorder)</span></label>
        <div id="manageBoxProducts">
          <div class="box-product-row"><img class="box-product-thumb" src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=80" alt="" /><div class="box-product-name">COSRX Low pH Good Morning Gel Cleanser</div><input type="number" class="box-qty-input" value="1" min="1" max="5" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.box-product-row').remove()">🗑️</button></div>
          <div class="box-product-row"><img class="box-product-thumb" src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=80" alt="" /><div class="box-product-name">Beauty of Joseon Glow Serum</div><input type="number" class="box-qty-input" value="1" min="1" max="5" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.box-product-row').remove()">🗑️</button></div>
          <div class="box-product-row"><img class="box-product-thumb" src="https://images.unsplash.com/photo-1617897903246-719242758050?w=80" alt="" /><div class="box-product-name">Laneige Water Sleeping Mask</div><input type="number" class="box-qty-input" value="1" min="1" max="5" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('.box-product-row').remove()">🗑️</button></div>
        </div>
        <button class="action-btn tag-btn" style="width:100%;justify-content:center;margin-top:10px;" onclick="showToast('➕','Product search coming soon!')">+ Add Product to Box</button>
      </div>
      <div class="form-group"><label>Box Note to Customer</label><textarea class="form-input" rows="2" placeholder="Personal message included in the box…" style="resize:vertical;"></textarea></div>
    </div>
    <div class="add-modal-footer"><button class="action-btn edit" onclick="document.getElementById('manageBoxOverlay').classList.remove('open')">Cancel</button><button class="action-btn primary" onclick="showToast('✅','Box updated!');document.getElementById('manageBoxOverlay').classList.remove('open')">💾 Save Box Changes</button></div>
  </div>
</div>

{{-- Invite Team Member Modal --}}
<div class="modal-overlay" id="inviteTeamOverlay" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="add-modal" style="max-width:520px;" onclick="event.stopPropagation()">
    <div class="add-modal-header"><h2>Invite Team Member</h2><button class="tag-modal-close" onclick="document.getElementById('inviteTeamOverlay').classList.remove('open')">✕</button></div>
    <div class="add-modal-body">
      <div class="form-grid">
        <div class="form-group"><label>Full Name *</label><input type="text" class="form-input" placeholder="e.g. Temi Adeyemi" /></div>
        <div class="form-group"><label>Email Address *</label><input type="email" class="form-input" placeholder="team@kominhoo.ng" /></div>
      </div>
      <div class="form-group"><label>Role *</label><select class="form-input"><option value="">Select role…</option><option>👑 Super Admin</option><option>⚙️ Operations</option><option>💬 Customer Support</option><option>📣 Marketing</option><option>👤 Employee</option></select></div>
      <div class="form-group"><label>Personal Welcome Message (optional)</label><textarea class="form-input" rows="2" placeholder="Welcome to the Kominhoo team…" style="resize:vertical;"></textarea></div>
    </div>
    <div class="add-modal-footer"><button class="action-btn edit" onclick="document.getElementById('inviteTeamOverlay').classList.remove('open')">Cancel</button><button class="action-btn primary" onclick="showToast('📧','Invite sent! They will receive an email shortly.');document.getElementById('inviteTeamOverlay').classList.remove('open')">📧 Send Invite</button></div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// ── Panel switching ───────────────────────────────────
function switchAdminPanel(id, el) {
  document.querySelectorAll('.admin-panel').forEach(p => p.classList.remove('active'));
  const panel = document.getElementById('panel-' + id);
  if (panel) panel.classList.add('active');

  document.querySelectorAll('.admin-nav-item').forEach(n => n.classList.remove('active'));
  if (el) {
    el.classList.add('active');
  } else {
    const navEl = [...document.querySelectorAll('.admin-nav-item')].find(n =>
      (n.getAttribute('onclick') || '').includes(`switchAdminPanel('${id}'`)
    );
    if (navEl) navEl.classList.add('active');
  }

  // Show topbar only on overview
  const topbar = document.querySelector('.admin-topbar');
  if (topbar) topbar.style.display = id === 'overview' ? 'flex' : 'none';

  // Scroll to top when switching panels
  window.scrollTo({ top: 0, behavior: 'instant' });

  const titles = {
    'overview':         ['Overview',              '/ Dashboard'],
    'products':         ['Products',              '/ Catalog Management'],
    'bundles':          ['Bundle Kits',           '/ Curation'],
    'guides':           ['Buying Guides',         '/ Curation'],
    'routines':         ['Routine Builder',       '/ Skin OS Routines'],
    'inventory':        ['Inventory',             '/ Stock & Batch Management'],
    'orders':           ['Orders',                '/ Fulfilment'],
    'subscribers':      ['Subscribers',           '/ Subscription Management'],
    'users':            ['Users',                 '/ Customer Database'],
    'loyalty':          ['Loyalty',               '/ Points & Tiers'],
    'subscriptions':    ['Subscription Plans',    '/ Box Management'],
    'analytics':        ['Analytics',             '/ Insights'],
    'reviews':          ['Reviews',               '/ Moderation'],
    'community':        ['Community',             '/ Gallery Moderation'],
    'promotions':       ['Promotions',            '/ Discounts & Flash Sales'],
    'content':          ['Content Manager',       '/ CMS'],
    'blog':             ['Blog',                  '/ Posts & Publishing'],
    'automation':       ['Automation',            '/ Campaigns & Triggers'],
    'security-events':  ['Security Events',       '/ User Activity Monitoring'],
    'roles':            ['Roles & Permissions',   '/ Team Access'],
    'spa':              ['Spa & Clinic',          '/ Treatment Integration'],
    'skin-results':     ['Skin Results',          '/ Quiz Outcomes'],
    'quiz-config':      ['Quiz Config',           '/ Skin OS Setup'],
    'giftcards':        ['Gift Cards',            '/ Purchase & Redemption'],
    'settings':         ['Settings',              '/ Platform Configuration'],
    'wallet':           ['Wallet Management',     '/ Balances & Transactions'],
    'influencers':      ['Influencers',           '/ Partnership Applications'],
  };
  const t = titles[id] || [id.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase()), ''];
  document.getElementById('adminPageTitle').innerHTML = t[0] + (t[1] ? ' <span>' + t[1] + '</span>' : '');

  const searches = {
    'overview':        'Search products, orders, users…',
    'products':        'Search products…',
    'bundles':         'Search bundle kits…',
    'guides':          'Search buying guides…',
    'routines':        'Search routines…',
    'inventory':       'Search inventory, batches…',
    'orders':          'Search orders, customers…',
    'subscribers':     'Search subscribers…',
    'users':           'Search customers…',
    'loyalty':         'Search members, tiers…',
    'subscriptions':   'Search subscription plans…',
    'analytics':       'Search analytics…',
    'reviews':         'Search reviews…',
    'community':       'Search community posts…',
    'promotions':      'Search vouchers, codes…',
    'content':         'Search content blocks…',
    'blog':            'Search blog posts…',
    'automation':      'Search automations…',
    'security-events': 'Search security events…',
    'roles':           'Search team members…',
    'spa':             'Search appointments, services…',
    'skin-results':    'Search skin results…',
    'quiz-config':     'Search quiz questions…',
    'giftcards':       'Search gift cards…',
    'settings':        'Search settings…',
    'wallet':          'Search wallets, transactions…',
    'influencers':     'Search influencers…',
  };
  const srch = document.getElementById('adminTopSearch');
  if (srch) srch.placeholder = searches[id] || 'Search…';

  if (id === 'analytics')        loadAnalyticsRating();
  if (id === 'community')        commLoadAll();
  if (id === 'reviews')          reviewsLoad();
  if (id === 'promotions')       loadCoupons();
  if (id === 'giftcards')        gcLoadAll();
  if (id === 'routines')         loadRoutineStats();
  if (id === 'security-events')  secLoadEvents();
  if (id === 'wallet')           walletLoadOverview();
  if (id === 'influencers')      loadInfluencers();
  if (id === 'bundles')          renderAdminBundles();
}

// ── Blog (CRUD) ───────────────────────────────────────────────────────────────
function openBlogModal() {
  document.getElementById('blogPostModalTitle').textContent = 'New Blog Post';
  document.getElementById('blogPostId').value = '';
  document.getElementById('blogTitle').value = '';
  document.getElementById('blogSlug').value = '';
  document.getElementById('blogTag').value = '';
  document.getElementById('blogAuthor').value = '';
  document.getElementById('blogReadTime').value = '';
  document.getElementById('blogCoverFile').value = '';
  document.getElementById('blogCoverUrl').value = '';
  document.getElementById('blogExcerpt').value = '';
  document.getElementById('blogContent').value = '';
  document.getElementById('blogPublished').checked = false;
  document.getElementById('blogFeatured').checked = false;
  blogTogglePublished(false);
  blogCoverPreview('');
  blogSetError('');
  document.getElementById('blogPostOverlay').classList.add('open');
}

function blogCoverPreview(url) {
  const wrap = document.getElementById('blogCoverPreview');
  const img  = document.getElementById('blogCoverPreviewImg');
  if (!wrap || !img) return;
  const u = (url || '').trim();
  if (u && u.startsWith('http')) {
    img.src = u;
    wrap.style.display = '';
  } else {
    img.src = '';
    wrap.style.display = 'none';
  }
}

function blogTogglePublished(isOn) {
  const dt = document.getElementById('blogPublishedAt');
  if (!dt) return;
  dt.disabled = !isOn;
  if (!isOn) dt.value = '';
}

function blogSetError(msg) {
  const el = document.getElementById('blogPostError');
  if (!el) return;
  if (!msg) {
    el.style.display = 'none';
    el.textContent = '';
    return;
  }
  el.style.display = '';
  el.textContent = msg;
}

async function editBlogPost(id) {
  blogSetError('');
  try {
    const r = await fetch(ADMIN_URL + '/blog/posts/' + id, { credentials: 'include' });
    const d = await r.json();
    if (!d?.success || !d?.data) {
      showToast('⚠️', d?.message || 'Could not load blog post.');
      return;
    }
    const p = d.data;
    document.getElementById('blogPostModalTitle').textContent = 'Edit Blog Post';
    document.getElementById('blogPostId').value = p.id;
    document.getElementById('blogTitle').value = p.title || '';
    document.getElementById('blogSlug').value = p.slug || '';
    document.getElementById('blogTag').value = p.tag || '';
    document.getElementById('blogAuthor').value = p.author || '';
    document.getElementById('blogReadTime').value = p.reading_time || '';
    document.getElementById('blogCoverFile').value = '';
    document.getElementById('blogCoverUrl').value = (p.cover_image_path || '').startsWith('http') ? p.cover_image_path : '';
    document.getElementById('blogExcerpt').value = p.excerpt || '';
    document.getElementById('blogContent').value = p.content || '';
    document.getElementById('blogPublished').checked = !!p.is_published;
    document.getElementById('blogFeatured').checked = !!p.is_featured;
    blogTogglePublished(!!p.is_published);

    // datetime-local needs YYYY-MM-DDTHH:MM
    const dt = document.getElementById('blogPublishedAt');
    if (dt && p.published_at) {
      const iso = String(p.published_at).replace(' ', 'T').slice(0, 16);
      dt.value = iso;
    } else if (dt) {
      dt.value = '';
    }

    // preview
    if ((p.cover_image_path || '').startsWith('http')) {
      blogCoverPreview(p.cover_image_path);
    } else {
      blogCoverPreview('');
    }

    document.getElementById('blogPostOverlay').classList.add('open');
  } catch (e) {
    showToast('⚠️', 'Request failed.');
  }
}

async function saveBlogPost() {
  blogSetError('');
  const id = document.getElementById('blogPostId').value;
  const title = document.getElementById('blogTitle').value.trim();
  if (!title) {
    blogSetError('Title is required.');
    return;
  }

  const fd = new FormData();
  fd.append('title', title);
  fd.append('slug', document.getElementById('blogSlug').value.trim());
  fd.append('tag', document.getElementById('blogTag').value.trim());
  fd.append('author', document.getElementById('blogAuthor').value.trim());
  fd.append('reading_time', document.getElementById('blogReadTime').value.trim());
  fd.append('excerpt', document.getElementById('blogExcerpt').value.trim());
  fd.append('content', document.getElementById('blogContent').value);
  fd.append('is_published', document.getElementById('blogPublished').checked ? '1' : '0');
  fd.append('is_featured', document.getElementById('blogFeatured').checked ? '1' : '0');
  fd.append('published_at', document.getElementById('blogPublishedAt').value || '');
  fd.append('cover_image_url', document.getElementById('blogCoverUrl').value.trim());
  const f = document.getElementById('blogCoverFile').files?.[0];
  if (f) fd.append('cover_image', f);

  const isUpdate = !!id;
  if (isUpdate) fd.append('_method', 'PUT');

  const url = isUpdate ? (ADMIN_URL + '/blog/posts/' + id) : (ADMIN_URL + '/blog/posts');

  try {
    const r = await fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
      body: fd,
      credentials: 'include',
    });
    const d = await r.json().catch(() => ({}));

    if (!r.ok) {
      const msg =
        d?.message ||
        (d?.errors ? Object.values(d.errors).flat().slice(0, 1)[0] : null) ||
        'Save failed.';
      blogSetError(msg);
      return;
    }

    if (d?.success) {
      showToast('✅', 'Saved!');
      document.getElementById('blogPostOverlay').classList.remove('open');
      setTimeout(() => { window.location.href = ADMIN_URL + '?panel=blog'; }, 600);
    } else {
      blogSetError(d?.message || 'Save failed.');
    }
  } catch (e) {
    blogSetError('Request failed.');
  }
}

async function deleteBlogPost(id) {
  if (!confirm('Delete this blog post? This cannot be undone.')) return;
  try {
    const r = await fetch(ADMIN_URL + '/blog/posts/' + id, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
      credentials: 'include',
    });
    const d = await r.json().catch(() => ({}));
    if (r.ok && d?.success) {
      showToast('🗑️', 'Deleted.');
      setTimeout(() => { window.location.href = ADMIN_URL + '?panel=blog'; }, 600);
    } else {
      showToast('⚠️', d?.message || 'Delete failed.');
    }
  } catch (e) {
    showToast('⚠️', 'Request failed.');
  }
}

// Restore a panel after reload (e.g. /admin?panel=blog)
document.addEventListener('DOMContentLoaded', () => {
  const panel = new URLSearchParams(window.location.search).get('panel');
  if (!panel) return;
  const nav = [...document.querySelectorAll('.admin-nav-item')].find(el =>
    (el.getAttribute('onclick') || '').includes(`switchAdminPanel('${panel}'`)
  );
  switchAdminPanel(panel, nav || null);
});

// Upload a media file to the admin media upload endpoint (local storage)
function uploadCmsMedia() {
  const input = document.getElementById('cms-upload-file');
  const errorEl = document.getElementById('cms-upload-error');
  const preview = document.getElementById('cms-upload-preview');
  const progressWrap = document.getElementById('cms-upload-progress');
  const progressBar = document.getElementById('cms-upload-progress-bar');
  if (errorEl) { errorEl.style.display = 'none'; errorEl.textContent = ''; }
  if (preview) preview.innerHTML = '';
  if (progressBar) progressBar.style.width = '0%';
  if (progressWrap) progressWrap.style.display = 'none';

  if (!input || !input.files || !input.files[0]) {
    if (errorEl) { errorEl.textContent = 'No file selected'; errorEl.style.display = 'block'; }
    return;
  }

  const file = input.files[0];
  const maxMb = 5;
  if (file.size > maxMb * 1024 * 1024) {
    if (errorEl) { errorEl.textContent = `File too large (max ${maxMb} MB)`; errorEl.style.display = 'block'; }
    return;
  }

  // Do not render the uploaded image preview; show filename only to avoid automatic display
  if (preview) {
    const info = document.createElement('div');
    info.textContent = file.name + ' (' + (file.type || 'file') + ')';
    info.style.fontSize = '.86rem';
    info.style.color = 'rgba(10,10,10,.6)';
    preview.appendChild(info);
  }

  const form = new FormData();
  form.append('file', file);

  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route("admin.cms.media.upload") }}');
  if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token);
  // Ask for JSON responses and mark as AJAX
  xhr.setRequestHeader('Accept', 'application/json');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

  xhr.upload.onprogress = function(e) {
    if (e.lengthComputable) {
      if (progressWrap) progressWrap.style.display = 'block';
      if (progressBar) progressBar.style.width = Math.round((e.loaded / e.total) * 100) + '%';
    }
  };

  xhr.onload = function() {
    if (xhr.status >= 200 && xhr.status < 300) {
      try {
        const res = JSON.parse(xhr.responseText || '{}');
        if (res.success) {
          const container = document.getElementById('cms-media-library-list');
          // Insert a media row without a visible image, but then populate the URL field from server response
          const id = `uploaded-${Date.now()}`;
          const item = { id, page: 'home', slot: '', url: '', alt: '', uploadPath: res.path };
          if (container) container.insertAdjacentHTML('afterbegin', cmsMediaRow(item));

          // Find the newly inserted row and populate its URL input (but keep the img hidden)
          const newRow = document.querySelector(`.media-library-row[data-media-id="${id}"]`);
          if (newRow) {
            const urlInput = newRow.querySelector('.media-url');
            if (urlInput) {
              urlInput.value = res.url || '';
              // update preview so the image immediately fits the container responsively
              updateMediaRowPreview(urlInput);
            }
            newRow.dataset.uploadPath = res.path || '';
          }

          input.value = '';
          if (preview) preview.innerHTML = '';
          if (progressBar) progressBar.style.width = '0%';
          if (progressWrap) progressWrap.style.display = 'none';
          showToast('✅', 'Upload successful');
        } else {
          if (errorEl) { errorEl.textContent = res.message || 'Upload failed'; errorEl.style.display = 'block'; }
        }
      } catch (err) {
        if (errorEl) { errorEl.textContent = 'Upload failed: invalid server response'; errorEl.style.display = 'block'; }
      }
    } else {
      const text = xhr.responseText || ''; console.error('Upload failed', xhr.status, text);
      if (errorEl) { errorEl.textContent = `Upload failed (${xhr.status}): ${text.slice(0,200)}`; errorEl.style.display = 'block'; }
    }
  };

  xhr.onerror = function() {
    if (errorEl) { errorEl.textContent = 'Upload failed (network)'; errorEl.style.display = 'block'; }
  };

  xhr.send(form);
}

// ── Tag modal ─────────────────────────────────────────
function openTagModal(productId) {
  const p = (typeof PRODUCTS !== 'undefined') ? PRODUCTS.find(x => x.id == productId) : null;
  if (!p) return;
  document.getElementById('tagModalImg').src = p.image;
  document.getElementById('tagModalName').textContent = p.name;
  document.getElementById('tagModalBrand').textContent = p.brand + ' · ' + p.category;
  const allLabels = document.querySelectorAll('#tagModalOverlay .tag-check-label');
  allLabels.forEach(label => {
    label.classList.remove('checked');
    label.querySelector('input').checked = false;
  });
  const preTag = (vals) => {
    vals && vals.forEach(v => {
      const inp = document.querySelector('#tagModalOverlay input[value="' + v + '"]');
      if (inp) { inp.checked = true; inp.closest('.tag-check-label').classList.add('checked'); }
    });
  };
  preTag(p.skinType);
  preTag(p.concern);
  preTag([p.routineStep]);
  preTag(p.ingredients ? p.ingredients.map(i => i.toLowerCase().replace(/\s+/g,'-')) : []);
  preTag(p.climate || []);
  preTag([p.priceTier]);
  document.getElementById('tagModalOverlay').classList.add('open');
}

function closeTagModal(e) {
  if (e.target === document.getElementById('tagModalOverlay')) {
    document.getElementById('tagModalOverlay').classList.remove('open');
  }
}

function toggleTagLabel(label) {
  const inp = label.querySelector('input');
  setTimeout(() => {
    if (inp.checked) label.classList.add('checked');
    else label.classList.remove('checked');
  }, 0);
}

function saveProductTags() {
  const checked = [...document.querySelectorAll('#tagModalOverlay input[type=checkbox]:checked')];
  document.getElementById('tagModalOverlay').classList.remove('open');
  showToast('✅', 'Product tags saved to Skin OS!');
}

// ── Add product modal ─────────────────────────────────
function openAddModal() {
  const modal = document.getElementById('addModalOverlay');
  modal.querySelectorAll('.panel-tab').forEach((t,i) => t.classList.toggle('active', i===0));
  modal.querySelectorAll('.panel-tab-content').forEach((c,i) => c.classList.toggle('active', i===0));
  initAddImageGrid();
  modal.classList.add('open');
}

function closeAddModal(e) {
  if (e.target === document.getElementById('addModalOverlay')) {
    document.getElementById('addModalOverlay').classList.remove('open');
  }
}

function switchAddProductTab(el, targetId) {
  const modal = el.closest('.add-modal');
  modal.querySelectorAll('.panel-tab').forEach(t => t.classList.remove('active'));
  modal.querySelectorAll('.panel-tab-content').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  const target = document.getElementById(targetId);
  if (target) target.classList.add('active');
}

const _addImages = [];

function initAddImageGrid() {
  _addImages.length = 0;
  renderAddImageGrid();
}

function renderAddImageGrid() {
  const grid = document.getElementById('addImageGrid');
  if (!grid) return;
  grid.innerHTML = _addImages.map((img, i) => '<div style="position:relative;border-radius:10px;overflow:hidden;border:1.5px solid #e8eaed;aspect-ratio:1;"><img src="' + img.url + '" style="width:100%;height:100%;object-fit:cover;" />' + (i===0 ? '<span style="position:absolute;top:6px;left:6px;background:var(--black);color:#fff;font-size:.6rem;font-weight:700;padding:2px 7px;border-radius:20px;letter-spacing:.5px;">MAIN</span>' : '') + '<button onclick="_addImages.splice(' + i + ',1);renderAddImageGrid()" style="position:absolute;top:5px;right:5px;width:22px;height:22px;border-radius:50%;background:rgba(0,0,0,.55);border:none;color:#fff;font-size:.7rem;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button></div>').join('');
}

function handleAddImages(input) {
  const files = Array.from(input.files);
  files.slice(0, 8 - _addImages.length).forEach(file => {
    _addImages.push({ url: URL.createObjectURL(file), file });
  });
  renderAddImageGrid();
  input.value = '';
}

function previewAddImageUrl(val) {
  const wrap = document.getElementById('addImageUrlPreview');
  const img  = document.getElementById('addImageUrlThumb');
  if (val && val.startsWith('http')) {
    img.src = val; wrap.style.display = 'block';
    if (!_addImages.find(x => x.url === val)) { _addImages.unshift({ url: val, file: null }); renderAddImageGrid(); }
  } else { wrap.style.display = 'none'; }
}

function calcMargin() {
  const sell = parseFloat(document.getElementById('addPrice').value) || 0;
  const cost = parseFloat(document.getElementById('addCostPrice').value) || 0;
  const el   = document.getElementById('addMarginDisplay');
  if (!el) return;
  if (sell && cost) {
    const margin = ((sell - cost) / sell * 100).toFixed(1);
    el.textContent = margin + '% (₦' + (sell - cost).toLocaleString() + ' profit)';
    el.style.color = parseFloat(margin) >= 30 ? '#16a34a' : parseFloat(margin) >= 10 ? '#f59e0b' : 'var(--red)';
  } else { el.textContent = '—'; el.style.color = ''; }
}

function fmtDoc(cmd) {
  document.getElementById('addLongDesc')?.focus();
  document.execCommand(cmd, false, null);
}

async function addProductSave(mode) {
  const name = document.getElementById('addName')?.value?.trim();
  if (!name) {
    showToast('⚠️', 'Product name is required');
    const modal = document.getElementById('addModalOverlay');
    modal.querySelectorAll('.panel-tab').forEach((t,i) => t.classList.toggle('active', i===0));
    modal.querySelectorAll('.panel-tab-content').forEach((c,i) => c.classList.toggle('active', i===0));
    return;
  }

  const brand = document.getElementById('addBrand')?.value?.trim() || '';
  const category = document.getElementById('addCategory')?.value || '';
  const price = parseFloat(document.getElementById('addPrice')?.value || 0) || 0;
  const original_price = parseFloat(document.getElementById('addOriginalPrice')?.value || 0) || null;
  const stock = parseInt(document.getElementById('addStock')?.value || 0) || 0;
  const shortDesc = document.getElementById('addShortDesc')?.value || '';
  const longDescEl = document.getElementById('addLongDesc');
  const description = (shortDesc ? shortDesc + '\n\n' : '') + (longDescEl ? longDescEl.innerHTML : '');
  const size = document.getElementById('addVolume')?.value || '';
  const slug = document.getElementById('addSlug')?.value || '';

  // skin types
  const skinTypes = Array.from(document.querySelectorAll('input[name="addSkinType"]:checked')).map(i => i.value);

  // Build FormData — files and URLs sent together; PHP proxy handles saving files
  const fd = new FormData();
  fd.append('name', name);
  fd.append('brand', brand);
  fd.append('category', category);
  fd.append('price', price);
  if (original_price) fd.append('original_price', original_price);
  fd.append('stock', stock);
  fd.append('description', description);
  fd.append('size', size);
  if (slug) fd.append('slug', slug);
  fd.append('is_featured', document.getElementById('addFeatured')?.checked ? '1' : '0');
  fd.append('is_active', (mode === 'active' || mode === 'tag') ? '1' : '0');
  skinTypes.forEach(t => fd.append('skin_types[]', t));

  _addImages.slice(0, 8).forEach(img => {
    if (img.file) {
      fd.append('image_files[]', img.file, img.file.name);
    } else if (img.url && !img.url.startsWith('blob:')) {
      fd.append('image_urls[]', img.url);
    }
  });

  try {
    const resp = await fetch(ADMIN_PRODUCT_STORE_URL, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        // No Content-Type — browser sets multipart boundary automatically
      },
      body: fd,
    });
    const data = await resp.json();
    if (!resp.ok || !data.success) {
      console.error(data);
      showToast('⚠️', data.message || 'Failed to create product');
      return;
    }

    const product = data.data;
    // update local product lists used by the admin UI
    if (typeof CMS_PRODUCTS !== 'undefined' && Array.isArray(CMS_PRODUCTS)) CMS_PRODUCTS.unshift(product);
    if (typeof PRODUCTS !== 'undefined' && Array.isArray(PRODUCTS)) PRODUCTS.unshift(product);
    // rebuild tables
    try { buildProductsTable(); buildInventoryTable(); buildTopProducts(); } catch (e) { /* ignore */ }

    document.getElementById('addModalOverlay').classList.remove('open');
    const msgs = { draft:'💾 Product saved as draft.', active:'✅ Product published to store!', tag:'✅ Product published! Opening Tag Editor…' };
    showToast(mode === 'draft' ? '💾' : '✅', msgs[mode] || msgs.active);

    if (mode === 'tag') {
      // open tag modal for the created product
      setTimeout(() => {
        if (product && product.id) openTagModal(product.id);
      }, 600);
    }

  } catch (e) {
    console.error(e);
    showToast('⚠️', 'Network error creating product');
  }
}

// ── SERP preview live update ──────────────────────────
document.addEventListener('input', e => {
  if (e.target.id === 'addSeoTitle') { const el = document.getElementById('serpTitle'); if (el) el.textContent = e.target.value || 'Product Title — Kominhoo Beauty Nigeria'; }
  if (e.target.id === 'addSeoDesc')  { const el = document.getElementById('serpDesc');  if (el) el.textContent = e.target.value || 'Product description will appear here…'; }
  if (e.target.id === 'addName') {
    const slug = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
    const slugEl = document.getElementById('addSlug');
    if (slugEl) slugEl.value = slug;
    const serpTitle = document.getElementById('serpTitle');
    const seoTitleEl = document.getElementById('addSeoTitle');
    if (serpTitle && seoTitleEl && !seoTitleEl.value) serpTitle.textContent = (e.target.value || '') + ' — Kominhoo Beauty Nigeria';
  }
});

// ── Build products table ──────────────────────────────
function buildProductsTable() {
  const products = (typeof CMS_PRODUCTS !== 'undefined') ? CMS_PRODUCTS : [];
  const tbody = document.getElementById('adminProductsTbody');
  if (!tbody) return;
  if (!products.length) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:rgba(10,10,10,.4);">No products yet — click + Add New Product to get started.</td></tr>';
    return;
  }
  tbody.innerHTML = products.map(p => {
    const skinArr  = p.skin_types || p.skinType || [];
    const skinTags = (Array.isArray(skinArr) ? skinArr : []).map(t => '<span class="tag-chip skin">' + t + '</span>').join('');
    const isActive = p.is_active !== undefined ? !!p.is_active : (p.inStock !== undefined ? !!p.inStock : true);
    const statusClass = isActive ? 'active' : 'out-of-stock';
    const statusLabel = isActive ? 'In Stock' : 'Inactive';
    const imgArr  = p.images;
    const imgUrl  = Array.isArray(imgArr) ? (imgArr[0] || '') : (imgArr || p.image || '');
    const origPrice = p.original_price || p.originalPrice;
    return '<tr><td><div style="display:flex;align-items:center;gap:12px;"><img class="product-thumb" src="' + imgUrl + '" alt="' + p.name + '" onerror="this.style.background=\'#eee\'"><div><div class="product-name-cell">' + p.name + '</div><div class="product-brand-cell">' + p.brand + '</div></div></div></td><td>' + p.category + '</td><td>' + skinTags + '</td><td><strong>₦' + Number(p.price).toLocaleString() + '</strong>' + (origPrice ? '<br><span style="font-size:.72rem;color:rgba(10,10,10,.4);text-decoration:line-through;">₦' + Number(origPrice).toLocaleString() + '</span>' : '') + '</td><td><span class="status-badge ' + statusClass + '">' + statusLabel + '</span></td><td><div style="display:flex;gap:6px;flex-wrap:wrap;"><button class="action-btn edit" onclick="openTagModal(' + p.id + ')">🏷️ Tag</button><button class="action-btn edit" onclick="openEditModal(' + p.id + ')">✏️ Edit</button><button class="action-btn danger" onclick="deleteProduct(' + p.id + ')">🗑️</button></div></td></tr>';
  }).join('');
}

// ── Revenue chart ─────────────────────────────────────
const MONTHLY_REVENUE = @json(array_values($monthlyRevenue));
const MONTHLY_ORDERS  = @json(array_values($monthlyOrderCounts));

function buildRevenueChart() {
  const chart = document.getElementById('revenueChart');
  if (!chart) return;
  const months  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const revenue = MONTHLY_REVENUE.map(v => Math.round(v / 1000)); // in thousands
  const orders  = MONTHLY_ORDERS;
  const currentM = new Date().getMonth(); // 0-indexed
  const maxRev   = Math.max(...revenue.filter(v => v > 0), 1);
  const maxOrd   = Math.max(...orders.filter(v => v > 0), 1);
  chart.innerHTML = months.map((m, i) => {
    const past = i <= currentM;
    const revH = revenue[i] ? Math.round((revenue[i] / maxRev) * 140) : 4;
    const ordH = orders[i]  ? Math.round((orders[i]  / maxOrd) * 60)  : 2;
    const dim  = past ? '' : 'opacity:.2;';
    return '<div class="chart-bar-group">'
      + '<div class="chart-bar lime" style="height:' + revH + 'px;' + dim + '"></div>'
      + '<div class="chart-bar red"  style="height:' + ordH + 'px;' + dim + '"></div>'
      + '<div class="chart-bar-label" style="' + (past ? '' : 'opacity:.35') + '">' + m + '</div>'
      + '</div>';
  }).join('');
}

// ── Top products list ─────────────────────────────────
const TOP_PRODUCT_REVENUE = @json($topProductRevenue);

function buildTopProducts() {
  const el = document.getElementById('topProductsList');
  if (!el) return;
  const products = (typeof CMS_PRODUCTS !== 'undefined') ? CMS_PRODUCTS : [];
  if (!products.length) {
    el.innerHTML = '<div style="text-align:center;padding:24px;color:rgba(10,10,10,.35);font-size:.82rem;">No product data yet.</div>';
    return;
  }

  // Use real top products by revenue if available, otherwise fall back to first 5 products
  let ranked;
  const topRevKeys = Object.keys(TOP_PRODUCT_REVENUE);
  if (topRevKeys.length) {
    ranked = topRevKeys.slice(0, 5).map(pid => ({
      product: products.find(p => String(p.id) === String(pid)),
      revenue: TOP_PRODUCT_REVENUE[pid],
    })).filter(r => r.product);
  } else {
    ranked = products.slice(0, 5).map(p => ({ product: p, revenue: null }));
  }

  el.innerHTML = ranked.map((r, i) => {
    const p   = r.product;
    const img = Array.isArray(p.images) ? (p.images[0] || '') : (p.image || '');
    const rev = r.revenue != null ? '₦' + Number(r.revenue).toLocaleString() : '—';
    return '<div class="top-product-row">'
      + '<div class="top-product-rank">#' + (i + 1) + '</div>'
      + '<img class="top-product-thumb" src="' + img + '" alt="' + p.name + '" onerror="this.style.background=\'#eee\'">'
      + '<div class="top-product-info"><strong>' + p.name + '</strong><span>' + p.brand + '</span></div>'
      + '<div class="top-product-rev">' + rev + '</div>'
      + '</div>';
  }).join('');
}

// ── Routine Stats (admin) ─────────────────────────────
function loadRoutineStats() {
  fetch('{{ route("admin.routine.stats") }}')
    .then(r => r.json())
    .then(json => {
      if (!json.success) return;
      const d = json.data || {};
      const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
      set('rt-kpi-total',      d.total_logs      ?? '—');
      set('rt-kpi-users',      d.users_logging   ?? '—');
      set('rt-kpi-month',      d.month_logs      ?? '—');
      set('rt-kpi-completion', d.avg_completion != null ? d.avg_completion + '%' : '—');

      const tbody = document.getElementById('rt-leaderboard-body');
      if (!tbody) return;
      const rows = (d.leaderboard || []);
      if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:24px;color:rgba(10,10,10,.4)">No routine logs yet this month.</td></tr>';
        return;
      }
      tbody.innerHTML = rows.map((u, i) => `
        <tr>
          <td style="font-weight:700;color:var(--text-muted)">#${i + 1}</td>
          <td><strong>${u.name}</strong><br><span style="font-size:.75rem;color:rgba(10,10,10,.45)">${u.email}</span></td>
          <td><span style="font-weight:700">${u.days_logged}</span> days</td>
          <td><span style="font-weight:700;color:var(--black)">${u.total_pts}</span> pts</td>
          <td><button class="action-btn edit" style="padding:4px 10px;font-size:.75rem;" onclick="openUserRoutineEditor(${u.user_id},'${u.name.replace(/'/g,"\\'")}')">✏️ Edit</button></td>
        </tr>`).join('');
    })
    .catch(() => {
      const tbody = document.getElementById('rt-leaderboard-body');
      if (tbody) tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:24px;color:rgba(10,10,10,.4)">Could not load data.</td></tr>';
    });
}

// ── Admin Buying Guides ────────────────────────────────
let adminGuides = JSON.parse(localStorage.getItem('kominhoo_guides')) || (typeof GUIDES_DEFAULT !== 'undefined' ? [...GUIDES_DEFAULT] : []);

function renderAdminGuides() {
  const list  = document.getElementById('guideAdminList');
  const label = document.getElementById('guidesCountLabel');
  if (!list) return;
  if (label) label.textContent = adminGuides.length + ' guide' + (adminGuides.length !== 1 ? 's' : '') + ' live on the homepage';
  if (!adminGuides.length) {
    list.innerHTML = '<div style="padding:36px;text-align:center;color:rgba(10,10,10,.4);">No guides yet — click <strong>+ Create Guide</strong> to add your first one.</div>';
    return;
  }
  list.innerHTML = adminGuides.map(g => {
    const chips = (g.products || []).map(pid => {
      const p = (typeof PRODUCTS !== 'undefined' ? PRODUCTS : []).find(x => x.id === pid);
      return p ? `<span class="bundle-item-chip">${p.brand} — ${p.name.length > 26 ? p.name.slice(0,26)+'…' : p.name}</span>` : '';
    }).join('');
    return `<div class="bundle-row" id="guide-row-${g.id}">
      <div class="bundle-meta">
        <strong>${g.icon || '📖'} ${g.title}</strong>
        <p>${g.desc || ''}</p>
        <div class="bundle-items-list">${chips || '<span style="font-size:.75rem;color:rgba(10,10,10,.35);">No products assigned</span>'}</div>
        <div class="bundle-actions">
          <button class="action-btn edit" onclick="openGuideEditor(${g.id})">✏️ Edit</button>
          <button class="action-btn danger" onclick="deleteAdminGuide(${g.id})">🗑️ Delete</button>
          <span class="status-badge active" style="margin-left:auto;">Active</span>
        </div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:2rem;line-height:1;">${g.icon || '📖'}</div>
        <div style="font-size:.75rem;color:rgba(10,10,10,.45);margin-top:8px;font-weight:600;">${(g.products||[]).length} products</div>
      </div>
    </div>`;
  }).join('');
}

function openGuideEditor(id) {
  const overlay = document.getElementById('guideEditorOverlay');
  if (!overlay) return;
  const g = id ? adminGuides.find(x => x.id === id) : null;
  document.getElementById('guideEditorHeading').textContent = g ? 'Edit Buying Guide' : 'Create Buying Guide';
  document.getElementById('guideEditorId').value            = g ? g.id : '';
  document.getElementById('guideEditorTitleInput').value    = g ? g.title : '';
  document.getElementById('guideEditorIcon').value          = g ? (g.icon || '') : '';
  document.getElementById('guideEditorDesc').value          = g ? (g.desc  || '') : '';
  document.getElementById('guideEditorImage').value         = g ? (g.image || '') : '';

  const selectedIds = g ? (g.products || []) : [];
  const products = typeof PRODUCTS !== 'undefined' ? PRODUCTS : [];
  const countEl  = document.getElementById('guideProductCount');

  const container = document.getElementById('guideProductCheckboxes');
  container.innerHTML = products.map(p => {
    const checked = selectedIds.includes(p.id);
    return `<label style="display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:9px;cursor:pointer;border:1.5px solid ${checked ? 'var(--lime)' : '#e8eaed'};background:${checked ? '#f7ffe4' : '#fff'};font-size:.78rem;transition:all .15s;" onclick="">
      <input type="checkbox" value="${p.id}" ${checked ? 'checked' : ''} style="accent-color:#6ab04c;flex-shrink:0;"
        onchange="this.closest('label').style.borderColor=this.checked?'var(--lime)':'#e8eaed';this.closest('label').style.background=this.checked?'#f7ffe4':'#fff';document.getElementById('guideProductCount').textContent='('+document.querySelectorAll('#guideProductCheckboxes input:checked').length+' selected)'">
      <span><strong>${p.brand}</strong> ${p.name.length > 28 ? p.name.slice(0,28)+'…' : p.name}</span>
    </label>`;
  }).join('');
  if (countEl) countEl.textContent = selectedIds.length ? '(' + selectedIds.length + ' selected)' : '';

  overlay.classList.add('open');
}

function closeGuideEditor() {
  const overlay = document.getElementById('guideEditorOverlay');
  if (overlay) overlay.classList.remove('open');
}

function saveGuideEditor() {
  const id    = document.getElementById('guideEditorId').value;
  const title = document.getElementById('guideEditorTitleInput').value.trim();
  const icon  = document.getElementById('guideEditorIcon').value.trim();
  const desc  = document.getElementById('guideEditorDesc').value.trim();
  const image = document.getElementById('guideEditorImage').value.trim();
  if (!title) { if (typeof showToast !== 'undefined') showToast('⚠️', 'Guide title is required'); return; }
  const selectedProducts = [...document.querySelectorAll('#guideProductCheckboxes input[type=checkbox]:checked')].map(cb => parseInt(cb.value));
  if (id) {
    const idx = adminGuides.findIndex(g => g.id === parseInt(id));
    if (idx >= 0) adminGuides[idx] = { ...adminGuides[idx], title, icon, desc, image, products: selectedProducts };
  } else {
    const newId = adminGuides.length ? Math.max(...adminGuides.map(g => g.id)) + 1 : 1;
    adminGuides.push({ id: newId, title, icon, desc, image, products: selectedProducts });
  }
  localStorage.setItem('kominhoo_guides', JSON.stringify(adminGuides));
  closeGuideEditor();
  renderAdminGuides();
  if (typeof showToast !== 'undefined') showToast('✅', id ? 'Guide updated!' : 'New guide created!');
}

function deleteAdminGuide(id) {
  if (!confirm('Delete this buying guide? This cannot be undone.')) return;
  adminGuides = adminGuides.filter(g => g.id !== id);
  localStorage.setItem('kominhoo_guides', JSON.stringify(adminGuides));
  renderAdminGuides();
  if (typeof showToast !== 'undefined') showToast('🗑️', 'Guide deleted');
}

// ── Admin Bundle Kits ──────────────────────────────────
let adminBundles = JSON.parse(localStorage.getItem('kominhoo_bundles')) || (typeof BUNDLES_DEFAULT !== 'undefined' ? [...BUNDLES_DEFAULT] : []);

function renderAdminBundles() {
  const list  = document.getElementById('bundleAdminList');
  const label = document.getElementById('bundlesCountLabel');
  if (!list) return;
  if (label) label.textContent = adminBundles.length + ' bundle' + (adminBundles.length !== 1 ? 's' : '') + ' live in the shop';
  if (!adminBundles.length) {
    list.innerHTML = '<div style="padding:36px;text-align:center;color:rgba(10,10,10,.4);">No bundles yet — click <strong>+ Create Bundle</strong> to add your first one.</div>';
    return;
  }
  list.innerHTML = adminBundles.map(b => {
    const chips = (b.products || []).map(pid => {
      const p = (typeof PRODUCTS !== 'undefined' ? PRODUCTS : []).find(x => x.id === pid);
      return p ? `<span class="bundle-item-chip">${p.brand} ${p.name.split(' ').slice(0,3).join(' ')}</span>` : '';
    }).join('');
    const discount = b.originalPrice ? Math.round((1 - b.price / b.originalPrice) * 100) : 0;
    return `
      <div class="bundle-row">
        <div class="bundle-meta">
          <strong>${b.name}</strong>
          <p>${b.desc || ''}</p>
          <div class="bundle-items-list">${chips || '<span style="color:rgba(10,10,10,.35);font-size:.8rem;">No products assigned</span>'}</div>
          <div class="bundle-actions">
            <button class="action-btn edit" onclick="openBundleEditor(${b.id})">✏️ Edit</button>
            <button class="action-btn tag-btn" onclick="retagBundle(${b.id})">🏷️ Re-tag</button>
            <button class="action-btn danger" onclick="deleteAdminBundle(${b.id})">🗑️ Delete</button>
            <span class="status-badge active" style="margin-left:auto;">Active</span>
          </div>
        </div>
        <div class="bundle-summary">
          <div class="bundle-price">₦${b.price.toLocaleString()}</div>
          ${b.originalPrice ? `<div style="font-size:.75rem;color:rgba(10,10,10,.4);text-decoration:line-through;margin-top:2px;">₦${b.originalPrice.toLocaleString()}</div><div style="font-size:.72rem;color:#16a34a;font-weight:600;margin-top:4px;">${discount}% off</div>` : ''}
          <div style="font-size:.75rem;color:rgba(10,10,10,.45);margin-top:8px;">${(b.products||[]).length} products</div>
        </div>
      </div>`;
  }).join('');
}

function openBundleEditor(id) {
  const overlay = document.getElementById('bundleEditorOverlay');
  if (!overlay) return;
  const b = id ? adminBundles.find(x => x.id === id) : null;
  document.getElementById('bundleEditorHeading').textContent = b ? 'Edit Bundle Kit' : 'Create Bundle Kit';
  document.getElementById('bundleEditorId').value        = b ? b.id : '';
  document.getElementById('bundleEditorName').value      = b ? b.name : '';
  document.getElementById('bundleEditorTag').value       = b ? (b.tag || '') : '';
  document.getElementById('bundleEditorDesc').value      = b ? (b.desc || '') : '';
  document.getElementById('bundleEditorImage').value     = b ? (b.image || '') : '';
  document.getElementById('bundleEditorPrice').value     = b ? b.price : '';
  document.getElementById('bundleEditorOrigPrice').value = b ? (b.originalPrice || '') : '';
  const selected = b ? (b.products || []) : [];
  const allProducts = typeof PRODUCTS !== 'undefined' ? PRODUCTS : [];
  const countEl = document.getElementById('bundleProductCount');
  document.getElementById('bundleProductCheckboxes').innerHTML = allProducts.map(p => {
    const checked = selected.includes(p.id) ? 'checked' : '';
    const bg = checked ? 'background:rgba(200,230,52,.15);border-radius:8px;' : '';
    return `<label style="display:flex;align-items:center;gap:8px;padding:7px 10px;cursor:pointer;${bg}font-size:.82rem;">
      <input type="checkbox" value="${p.id}" ${checked} onchange="(function(){const c=document.querySelectorAll('#bundleProductCheckboxes input:checked').length;document.getElementById('bundleProductCount').textContent='('+c+' selected)';})()">
      <span><strong>${p.brand}</strong> — ${p.name.split(' ').slice(0,4).join(' ')}</span>
    </label>`;
  }).join('');
  if (countEl) countEl.textContent = '(' + selected.length + ' selected)';
  overlay.classList.add('open');
}

function closeBundleEditor() {
  const overlay = document.getElementById('bundleEditorOverlay');
  if (overlay) overlay.classList.remove('open');
}

function saveBundleEditor() {
  const name      = (document.getElementById('bundleEditorName').value || '').trim();
  if (!name) { alert('Please enter a bundle name.'); return; }
  const id        = document.getElementById('bundleEditorId').value;
  const tag       = (document.getElementById('bundleEditorTag').value || '').trim();
  const desc      = (document.getElementById('bundleEditorDesc').value || '').trim();
  const image     = (document.getElementById('bundleEditorImage').value || '').trim();
  const price     = parseInt(document.getElementById('bundleEditorPrice').value) || 0;
  const origPrice = parseInt(document.getElementById('bundleEditorOrigPrice').value) || null;
  const selectedProducts = [...document.querySelectorAll('#bundleProductCheckboxes input[type=checkbox]:checked')].map(cb => parseInt(cb.value));
  if (id) {
    const idx = adminBundles.findIndex(b => b.id === parseInt(id));
    if (idx >= 0) adminBundles[idx] = { ...adminBundles[idx], name, tag, desc, image, price, originalPrice: origPrice, products: selectedProducts };
  } else {
    const newId = adminBundles.length ? Math.max(...adminBundles.map(b => b.id)) + 1 : 1;
    adminBundles.push({ id: newId, name, tag, desc, image, price, originalPrice: origPrice, products: selectedProducts });
  }
  localStorage.setItem('kominhoo_bundles', JSON.stringify(adminBundles));
  closeBundleEditor();
  renderAdminBundles();
  if (typeof showToast !== 'undefined') showToast('✅', id ? 'Bundle updated!' : 'New bundle created!');
}

function deleteAdminBundle(id) {
  if (!confirm('Delete this bundle kit? This cannot be undone.')) return;
  adminBundles = adminBundles.filter(b => b.id !== id);
  localStorage.setItem('kominhoo_bundles', JSON.stringify(adminBundles));
  renderAdminBundles();
  if (typeof showToast !== 'undefined') showToast('🗑️', 'Bundle deleted');
}

function retagBundle(id) {
  const b = adminBundles.find(x => x.id === id);
  if (!b) return;
  const newTag = prompt('Enter new badge tag for "' + b.name + '":', b.tag || '');
  if (newTag === null) return;
  const idx = adminBundles.findIndex(x => x.id === id);
  if (idx >= 0) adminBundles[idx] = { ...adminBundles[idx], tag: newTag.trim() };
  localStorage.setItem('kominhoo_bundles', JSON.stringify(adminBundles));
  renderAdminBundles();
  if (typeof showToast !== 'undefined') showToast('🏷️', 'Tag updated to "' + newTag.trim() + '"');
}

// Initialise on panel open
(function() {
  const orig = window.switchAdminPanel;
  if (typeof orig === 'function') {
    window.switchAdminPanel = function(id, el) {
      orig(id, el);
      if (id === 'products') buildProductsTable();
      if (id === 'guides')   renderAdminGuides();
      if (id === 'bundles')  renderAdminBundles();
    };
  }
  // Populate tables once on load so Products panel is ready when first visited
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => { buildProductsTable(); renderAdminGuides(); renderAdminBundles(); });
  } else {
    buildProductsTable(); renderAdminGuides(); renderAdminBundles();
  }
})();

// ── Admin User Routine Editor ─────────────────────────
let _ure = {
  userId: null, userName: '', days: [],
  amSteps: [], pmSteps: [],
  selectedDate: null,
  // per-date edited state (date -> {am_steps, pm_steps, am_done, pm_done})
  edits: {},
  currentTab: 'am',
};

function openUserRoutineEditor(userId, userName) {
  _ure.userId   = userId;
  _ure.userName = userName;
  _ure.days     = [];
  _ure.edits    = {};
  _ure.selectedDate = null;

  document.getElementById('ure-subtitle').textContent = `${userName} — loading…`;
  document.getElementById('ure-date-grid').innerHTML  = '<span style="font-size:.82rem;color:rgba(10,10,10,.45)">Loading…</span>';
  document.getElementById('ure-steps-am').innerHTML   = '';
  document.getElementById('ure-steps-pm').innerHTML   = '';
  document.getElementById('ure-am-done').checked      = false;
  document.getElementById('ure-pm-done').checked      = false;
  document.getElementById('ure-pts-preview').textContent = '';
  ureTab('am');
  document.getElementById('userRoutineEditorOverlay').classList.add('open');

  fetch(`{{ route('admin.routine.user.logs', ['userId' => '__UID__']) }}`.replace('__UID__', userId), {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  })
    .then(r => r.json())
    .then(json => {
      if (!json.success) { showToast('⚠️', json.message || 'Could not load user routine.'); closeUserRoutineEditor(); return; }
      const d = json.data;
      _ure.amSteps = d.am_steps || [];
      _ure.pmSteps = d.pm_steps || [];
      _ure.days    = d.days     || [];
      document.getElementById('ure-subtitle').textContent = `${d.user.name} (${d.user.email})`;

      // Build date grid
      const grid = document.getElementById('ure-date-grid');
      grid.innerHTML = _ure.days.map(day => {
        const label = new Date(day.date + 'T00:00:00').toLocaleDateString('en-GB', { day:'numeric', month:'short' });
        const hasDone = day.am_done || day.pm_done;
        return `<button onclick="ureSelectDate('${day.date}')" data-date="${day.date}"
          style="padding:6px 10px;border-radius:8px;border:1.5px solid ${hasDone?'var(--black)':'#e8eaed'};
          background:${hasDone?'var(--black)':'#fff'};color:${hasDone?'#fff':'rgba(10,10,10,.7)'};
          font-size:.75rem;font-weight:600;cursor:pointer;transition:all .15s;">${label}${hasDone?' ✓':''}</button>`;
      }).join('');

      // Default to today or most recent
      const todayStr = new Date().toISOString().split('T')[0];
      const selectDate = _ure.days.find(d => d.date === todayStr) ? todayStr : (_ure.days[_ure.days.length-1]?.date);
      if (selectDate) ureSelectDate(selectDate);
    })
    .catch(() => { showToast('⚠️', 'Could not reach server.'); closeUserRoutineEditor(); });
}

function ureSelectDate(date) {
  _ure.selectedDate = date;

  // Highlight selected
  document.querySelectorAll('#ure-date-grid button').forEach(btn => {
    const isSelected = btn.dataset.date === date;
    btn.style.outline = isSelected ? '2px solid var(--lime)' : 'none';
    btn.style.outlineOffset = '2px';
  });

  // Load state from edits cache or original data
  const original = _ure.days.find(d => d.date === date) || { am_steps:[], pm_steps:[], am_done:false, pm_done:false };
  const state    = _ure.edits[date] || { ...original };
  _ure.edits[date] = state;

  ureRenderSteps('am', state.am_steps);
  ureRenderSteps('pm', state.pm_steps);
  document.getElementById('ure-am-done').checked = !!state.am_done;
  document.getElementById('ure-pm-done').checked = !!state.pm_done;
  ureUpdatePts();
}

function ureTab(tab) {
  _ure.currentTab = tab;
  document.getElementById('ure-steps-am').style.display = tab === 'am' ? '' : 'none';
  document.getElementById('ure-steps-pm').style.display = tab === 'pm' ? '' : 'none';
  document.getElementById('ure-am-tab').className = tab === 'am' ? 'action-btn primary' : 'action-btn edit';
  document.getElementById('ure-pm-tab').className = tab === 'pm' ? 'action-btn primary' : 'action-btn edit';
}

function ureRenderSteps(tab, checkedIds) {
  const steps     = tab === 'am' ? _ure.amSteps : _ure.pmSteps;
  const container = document.getElementById(`ure-steps-${tab}`);
  if (!container) return;
  container.innerHTML = steps.map(step => {
    const isChecked = checkedIds.includes(step.id);
    return `<label style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f4f5f7;cursor:pointer;">
      <input type="checkbox" ${isChecked?'checked':''} data-id="${step.id}" data-tab="${tab}"
        onchange="ureToggleStep(this)" style="width:16px;height:16px;cursor:pointer;flex-shrink:0;">
      <span style="font-size:.85rem;font-weight:500;flex:1">${step.label}</span>
      <span style="font-size:.75rem;color:rgba(10,10,10,.4);font-weight:600">+${step.pts} pt${step.pts>1?'s':''}</span>
    </label>`;
  }).join('');
}

function ureToggleStep(cb) {
  if (!_ure.selectedDate) return;
  const tab    = cb.dataset.tab;
  const stepId = cb.dataset.id;
  const state  = _ure.edits[_ure.selectedDate];
  const key    = tab === 'am' ? 'am_steps' : 'pm_steps';
  if (cb.checked) {
    if (!state[key].includes(stepId)) state[key].push(stepId);
  } else {
    state[key] = state[key].filter(id => id !== stepId);
  }
  ureUpdatePts();
}

function ureSync() {
  if (!_ure.selectedDate) return;
  const state    = _ure.edits[_ure.selectedDate];
  state.am_done  = document.getElementById('ure-am-done').checked;
  state.pm_done  = document.getElementById('ure-pm-done').checked;
  ureUpdatePts();
}

function ureUpdatePts() {
  if (!_ure.selectedDate) return;
  const state = _ure.edits[_ure.selectedDate];
  let pts = 0;
  (_ure.amSteps || []).forEach(s => { if ((state.am_steps||[]).includes(s.id)) pts += s.pts; });
  (_ure.pmSteps || []).forEach(s => { if ((state.pm_steps||[]).includes(s.id)) pts += s.pts; });
  if ((state.am_steps||[]).length >= (_ure.amSteps||[]).length && state.am_done) pts += 5;
  if ((state.pm_steps||[]).length >= (_ure.pmSteps||[]).length && state.pm_done) pts += 5;
  document.getElementById('ure-pts-preview').textContent = `${pts} pts for this day`;
}

function saveUserRoutineLog() {
  if (!_ure.userId || !_ure.selectedDate) return;
  const state = _ure.edits[_ure.selectedDate];
  if (!state) return;

  const btn = document.getElementById('ure-save-btn');
  if (btn) btn.disabled = true;

  const url = `{{ route('admin.routine.user.log.update', ['userId' => '__UID__', 'date' => '__DATE__']) }}`
    .replace('__UID__', _ure.userId)
    .replace('__DATE__', _ure.selectedDate);

  fetch(url, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      am_steps: state.am_steps || [],
      pm_steps: state.pm_steps || [],
      am_done:  state.am_done  || false,
      pm_done:  state.pm_done  || false,
    }),
  })
    .then(r => r.json())
    .then(json => {
      if (btn) btn.disabled = false;
      if (json.success) {
        showToast('✅', `Routine for ${_ure.selectedDate} saved!`);
        // Update the date button badge
        const datBtn = document.querySelector(`#ure-date-grid button[data-date="${_ure.selectedDate}"]`);
        if (datBtn) {
          const hasDone = state.am_done || state.pm_done;
          datBtn.style.background = hasDone ? 'var(--black)' : '#fff';
          datBtn.style.color      = hasDone ? '#fff' : 'rgba(10,10,10,.7)';
          datBtn.style.borderColor = hasDone ? 'var(--black)' : '#e8eaed';
          const label = datBtn.textContent.replace(' ✓', '');
          datBtn.textContent = label + (hasDone ? ' ✓' : '');
        }
        // Refresh leaderboard stats quietly
        loadRoutineStats();
      } else {
        showToast('⚠️', json.message || 'Could not save. Try again.');
      }
    })
    .catch(() => {
      if (btn) btn.disabled = false;
      showToast('⚠️', 'Could not reach server.');
    });
}

function closeUserRoutineEditor() {
  document.getElementById('userRoutineEditorOverlay').classList.remove('open');
}

// ── Panel Tab Switching ───────────────────────────────
function switchTab(el, targetId) {
  const panel = el.closest('.admin-panel');
  panel.querySelectorAll('.panel-tab').forEach(t => t.classList.remove('active'));
  panel.querySelectorAll('.panel-tab-content').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  const target = document.getElementById(targetId);
  if (target) target.classList.add('active');
}

// ── Edit Product Modal ────────────────────────────────
function _setSelectVal(id, val) {
  const sel = document.getElementById(id);
  if (!sel || val === undefined || val === null) return;
  const str = String(val);
  [...sel.options].forEach(o => { if (o.value === str || o.text === str) sel.value = o.value; });
}

function calcEditMargin() {
  const price = parseFloat(document.getElementById('editPrice').value) || 0;
  const cost  = parseFloat(document.getElementById('editCostPrice').value) || 0;
  const el    = document.getElementById('editMarginDisplay');
  if (price && cost) {
    const m = ((price - cost) / price * 100).toFixed(1);
    el.textContent = m + '%';
    el.style.color = m >= 40 ? '#16a34a' : m >= 20 ? '#f59e0b' : '#e63434';
  } else { el.textContent = '—'; el.style.color = ''; }
}

function previewEditImageUrl(val) {
  const preview = document.getElementById('editImageUrlPreview');
  const thumb   = document.getElementById('editImageUrlThumb');
  if (val) { thumb.src = val; preview.style.display = 'block'; }
  else { preview.style.display = 'none'; }
}

function openEditModal(productId) {
  const products = (typeof CMS_PRODUCTS !== 'undefined') ? CMS_PRODUCTS : [];
  const p = products.find(x => x.id == productId);
  if (!p) return;
  window._editProductId = productId;

  // Reset tabs to first
  const overlay = document.getElementById('editModalOverlay');
  overlay.querySelectorAll('.panel-tab').forEach((t,i) => t.classList.toggle('active', i===0));
  overlay.querySelectorAll('.panel-tab-content').forEach((c,i) => c.classList.toggle('active', i===0));

  const imgs   = p.images;
  const imgUrl = Array.isArray(imgs) ? (imgs[0] || '') : (imgs || p.image || '');
  document.getElementById('editModalImg').src = imgUrl;
  document.getElementById('editModalTitle').textContent = 'Edit Product';
  document.getElementById('editModalSub').textContent = (p.brand || '') + ' · ' + (p.category || '');

  // Header status
  _setSelectVal('editProductStatus', p.status || (p.is_active ? 'active' : 'draft'));

  // Basic Info
  document.getElementById('editName').value = p.name || '';
  document.getElementById('editBrand').value = p.brand || '';
  _setSelectVal('editOrigin', p.origin || p.country_of_origin || 'Korea');
  _setSelectVal('editCategory', p.category || '');
  _setSelectVal('editRoutineStep', p.routine_step || '');
  document.getElementById('editVolume').value = p.volume || p.size || '';
  document.getElementById('editSku').value = p.sku || '';
  _setSelectVal('editBadge', p.badge || '');
  document.getElementById('editShelfLife').value = p.shelf_life || '';
  document.getElementById('editIsNew').checked = p.badge === 'New' || !!p.is_new;
  const inStock = p.is_active !== undefined ? p.is_active : (p.inStock !== undefined ? p.inStock : true);
  document.getElementById('editInStock').checked = !!inStock;
  document.getElementById('editFeatured').checked = !!p.featured;
  document.getElementById('editVegan').checked = !!p.vegan;
  document.getElementById('editCrueltyFree').checked = p.cruelty_free !== undefined ? !!p.cruelty_free : true;

  // Media — show existing images
  const grid = document.getElementById('editImageGrid');
  grid.innerHTML = '';
  const imageList = Array.isArray(imgs) ? imgs : [imgUrl].filter(Boolean);
  imageList.forEach(url => {
    if (!url) return;
    const div = document.createElement('div');
    div.style.cssText = 'position:relative;';
    div.innerHTML = '<img src="' + url + '" style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:10px;border:1.5px solid #e8eaed;" />';
    grid.appendChild(div);
  });
  document.getElementById('editImageUrl').value = '';
  document.getElementById('editImageUrlPreview').style.display = 'none';

  // Description
  const shortDesc = p.short_description || p.shortDesc || '';
  document.getElementById('editShortDesc').value = shortDesc;
  document.getElementById('editShortDescCount').textContent = shortDesc.length;
  document.getElementById('editLongDesc').innerHTML = p.description || p.long_description || p.desc || '';
  document.getElementById('editHowToUse').value = p.how_to_use || '';
  document.getElementById('editActiveIngredients').value = p.active_ingredients || '';
  _setSelectVal('editFragrance', p.fragrance || 'Fragrance-Free');
  document.getElementById('editIngredients').value = p.ingredients || '';
  document.getElementById('editWarnings').value = p.warnings || '';

  // Pricing & Stock
  document.getElementById('editPrice').value = p.price || '';
  document.getElementById('editOriginalPrice').value = p.original_price || p.originalPrice || '';
  document.getElementById('editCostPrice').value = p.cost_price || '';
  calcEditMargin();
  document.getElementById('editStockQty').value = p.stock || p.stock_quantity || '';
  document.getElementById('editRestockLevel').value = p.restock_level || p.low_stock_threshold || 10;
  document.getElementById('editBatch').value = p.batch || p.batch_number || '';
  document.getElementById('editExpiry').value = p.expiry || p.expiry_date || '';
  document.getElementById('editWarehouse').value = p.warehouse || p.warehouse_location || '';
  _setSelectVal('editTrackInventory', p.track_inventory || 'track');
  document.getElementById('editWeight').value = p.weight || '';
  document.getElementById('editDimensions').value = p.dimensions || '';
  _setSelectVal('editColdChain', p.cold_chain || 'no');
  document.getElementById('editShipNote').value = p.shipping_note || p.ship_note || '';

  // Skin OS
  const skinTypes = p.skin_types || p.skinTypes || [];
  document.querySelectorAll('[name="editSkinType"]').forEach(cb => { cb.checked = skinTypes.includes(cb.value); });
  _setSelectVal('editRoutineTime', p.routine_time || '');
  _setSelectVal('editSensitivityScore', String(p.sensitivity_score || '2'));
  document.getElementById('editPairsWith').value = p.pairs_with || '';
  document.getElementById('editAvoidWith').value = p.avoid_with || '';

  // SEO
  const seoTitle = p.seo_title || '';
  const seoDesc  = p.seo_description || p.seo_desc || '';
  document.getElementById('editSlug').value = p.slug || '';
  document.getElementById('editSeoTitle').value = seoTitle;
  document.getElementById('editSeoTitleCount').textContent = seoTitle.length;
  document.getElementById('editSeoDesc').value = seoDesc;
  document.getElementById('editSeoDescCount').textContent = seoDesc.length;
  document.getElementById('editRelated').value = Array.isArray(p.related_products) ? p.related_products.join(', ') : (p.related_products || '');
  document.getElementById('editTags').value = Array.isArray(p.tags) ? p.tags.join(', ') : (p.tags || '');
  _setSelectVal('editAvailability', p.availability || 'Available to all customers');
  document.getElementById('editSortOrder').value = p.sort_order !== undefined ? p.sort_order : 0;

  overlay.classList.add('open');
}

async function saveEditProduct() {
  const id   = window._editProductId;
  const name = document.getElementById('editName').value.trim();
  if (!name) { showToast('⚠️', 'Product name is required'); return; }

  const products = (typeof CMS_PRODUCTS !== 'undefined') ? CMS_PRODUCTS : [];
  const pIdx     = products.findIndex(x => x.id == id);

  const existingImgs = Array.from(document.getElementById('editImageGrid').querySelectorAll('img')).map(i => i.src).filter(Boolean);
  const newUrlInput  = document.getElementById('editImageUrl').value.trim();
  const allImages    = [...existingImgs, ...(newUrlInput ? [newUrlInput] : [])];
  const skinTypes    = Array.from(document.querySelectorAll('[name="editSkinType"]:checked')).map(c => c.value);

  const payload = {
    name,
    brand:              document.getElementById('editBrand').value,
    origin:             document.getElementById('editOrigin').value,
    category:           document.getElementById('editCategory').value,
    routine_step:       document.getElementById('editRoutineStep').value,
    volume:             document.getElementById('editVolume').value,
    sku:                document.getElementById('editSku').value,
    badge:              document.getElementById('editBadge').value,
    shelf_life:         document.getElementById('editShelfLife').value,
    is_new:             document.getElementById('editIsNew').checked,
    is_active:          document.getElementById('editInStock').checked,
    featured:           document.getElementById('editFeatured').checked,
    vegan:              document.getElementById('editVegan').checked,
    cruelty_free:       document.getElementById('editCrueltyFree').checked,
    status:             document.getElementById('editProductStatus').value,
    images:             allImages,
    short_description:  document.getElementById('editShortDesc').value,
    description:        document.getElementById('editLongDesc').innerHTML,
    how_to_use:         document.getElementById('editHowToUse').value,
    active_ingredients: document.getElementById('editActiveIngredients').value,
    fragrance:          document.getElementById('editFragrance').value,
    ingredients:        document.getElementById('editIngredients').value,
    warnings:           document.getElementById('editWarnings').value,
    price:              parseFloat(document.getElementById('editPrice').value) || 0,
    original_price:     parseFloat(document.getElementById('editOriginalPrice').value) || null,
    cost_price:         parseFloat(document.getElementById('editCostPrice').value) || null,
    stock_quantity:     parseInt(document.getElementById('editStockQty').value) || 0,
    restock_level:      parseInt(document.getElementById('editRestockLevel').value) || 10,
    batch_number:       document.getElementById('editBatch').value,
    expiry_date:        document.getElementById('editExpiry').value,
    warehouse_location: document.getElementById('editWarehouse').value,
    track_inventory:    document.getElementById('editTrackInventory').value,
    weight:             parseFloat(document.getElementById('editWeight').value) || null,
    dimensions:         document.getElementById('editDimensions').value,
    cold_chain:         document.getElementById('editColdChain').value,
    shipping_note:      document.getElementById('editShipNote').value,
    skin_types:         skinTypes,
    routine_time:       document.getElementById('editRoutineTime').value,
    sensitivity_score:  parseInt(document.getElementById('editSensitivityScore').value) || 2,
    pairs_with:         document.getElementById('editPairsWith').value,
    avoid_with:         document.getElementById('editAvoidWith').value,
    slug:               document.getElementById('editSlug').value,
    seo_title:          document.getElementById('editSeoTitle').value,
    seo_description:    document.getElementById('editSeoDesc').value,
    related_products:   document.getElementById('editRelated').value.split(',').map(s => s.trim()).filter(Boolean),
    tags:               document.getElementById('editTags').value.split(',').map(s => s.trim()).filter(Boolean),
    availability:       document.getElementById('editAvailability').value,
    sort_order:         parseInt(document.getElementById('editSortOrder').value) || 0,
  };

  try {
    const resp = await fetch(ADMIN_PRODUCT_UPDATE_URL + '/' + id, {
      method: 'PUT',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify(payload),
    });
    const data = await resp.json();
    if (!resp.ok || !data.success) { showToast('⚠️', data.message || 'Failed to update product'); return; }
    if (pIdx !== -1) Object.assign(products[pIdx], data.data);
    buildProductsTable();
    document.getElementById('editModalOverlay').classList.remove('open');
    showToast('✅', 'Product updated!');
  } catch (e) { showToast('⚠️', 'Network error updating product'); }
}

async function deleteProduct(id) {
  if (!confirm('Delete this product? This cannot be undone.')) return;
  try {
    const resp = await fetch(ADMIN_PRODUCT_DELETE_URL + '/' + id, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    });
    const data = await resp.json();
    if (!resp.ok || !data.success) { showToast('⚠️', data.message || 'Failed to delete product'); return; }
    const products = (typeof CMS_PRODUCTS !== 'undefined') ? CMS_PRODUCTS : [];
    const idx = products.findIndex(x => x.id == id);
    if (idx !== -1) products.splice(idx, 1);
    buildProductsTable();
    showToast('✅', 'Product deleted.');
  } catch (e) { showToast('⚠️', 'Network error deleting product'); }
}

// ── Order Detail Modal ────────────────────────────────
const ORDER_DATA = @json($adminOrders);

function openOrderModal(orderId) {
  const o = ORDER_DATA.find(x => x.id == orderId);
  if (!o) return;
  const addr = o.shipping_address || {};
  const addrParts = [addr.street, addr.city, addr.state, addr.country].filter(Boolean);
  const customerName  = (o.user && o.user.name)  ? o.user.name  : (addr.name  || 'Customer');
  const customerEmail = (o.user && o.user.email) ? o.user.email : '—';
  const orderDate = new Date(o.created_at).toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'});

  document.getElementById('orderModalTitle').textContent = 'Order #' + o.order_number;
  document.getElementById('orderModalSub').textContent   = orderDate + ' · ' + customerName;
  document.getElementById('oCustomerName').textContent   = customerName;
  document.getElementById('oCustomerEmail').textContent  = customerEmail;
  document.getElementById('oAddress').innerHTML = addrParts.join(', ').replace(/, /g, '<br>') || '—';
  document.getElementById('oTrackingNumber').value = o.tracking_number || '';
  document.getElementById('oAdminNote').value      = o.admin_note || '';

  // Render items + totals
  const items = o.items || [];
  const itemsHtml = items.map(i => `
    <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #f4f5f7;">
      <div style="width:44px;height:44px;border-radius:8px;background:#f4f5f7;display:grid;place-items:center;font-size:1.4rem;flex-shrink:0">🧴</div>
      <div style="flex:1;"><div style="font-weight:600;font-size:.85rem;">${i.name}</div><div style="font-size:.75rem;color:rgba(10,10,10,.4);">Qty: ${i.quantity}</div></div>
      <div style="font-weight:700;">₦${Number(i.price * i.quantity).toLocaleString()}</div>
    </div>`).join('');
  const subtotal = Number(o.subtotal) || 0;
  const discount = Number(o.discount) || 0;
  const shipping = Number(o.shipping) || 0;
  document.getElementById('oItemsList').innerHTML =
    (itemsHtml || '<div style="padding:14px 16px;color:rgba(10,10,10,.4)">No items</div>') +
    `<div style="background:#fafbfc;padding:12px 16px;">
      <div style="display:flex;justify-content:space-between;font-size:.82rem;color:rgba(10,10,10,.5);margin-bottom:3px;"><span>Subtotal</span><span>₦${subtotal.toLocaleString()}</span></div>
      ${discount > 0 ? `<div style="display:flex;justify-content:space-between;font-size:.82rem;color:rgba(10,10,10,.5);margin-bottom:3px;"><span>Discount</span><span style="color:#16a34a;">-₦${discount.toLocaleString()}</span></div>` : ''}
      <div style="display:flex;justify-content:space-between;font-size:.82rem;color:rgba(10,10,10,.5);margin-bottom:3px;"><span>Delivery</span><span${shipping === 0 ? ' style="color:#16a34a;"' : ''}>${shipping === 0 ? 'Free' : '₦' + shipping.toLocaleString()}</span></div>
      <div style="display:flex;justify-content:space-between;font-size:.92rem;font-weight:700;margin-top:8px;padding-top:8px;border-top:1px solid #e8eaed;"><span>Total</span><span>₦${Number(o.total).toLocaleString()}</span></div>
    </div>`;

  // Status badge + select
  const statusClass = { pending:'pending', processing:'pending', shipped:'shipped', delivered:'active', cancelled:'cancelled' };
  const badge = document.getElementById('orderCurrentStatus');
  badge.className  = 'status-badge ' + (statusClass[o.status] || 'pending');
  badge.textContent = o.status.charAt(0).toUpperCase() + o.status.slice(1);
  const sel = document.getElementById('orderStatusSelect');
  [...sel.options].forEach(opt => { opt.selected = opt.value.toLowerCase() === o.status; });

  document.getElementById('orderModalOverlay').dataset.orderId = o.id;
  document.getElementById('orderModalOverlay').classList.add('open');
}

async function updateOrderStatus() {
  const orderId  = document.getElementById('orderModalOverlay').dataset.orderId;
  const val      = document.getElementById('orderStatusSelect').value.toLowerCase();
  const tracking   = document.getElementById('oTrackingNumber').value.trim();
  const adminNote  = document.getElementById('oAdminNote').value.trim();
  try {
    const resp = await fetch('{{ route("admin.orders.update", ["id" => ":id"]) }}'.replace(':id', orderId), {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ status: val, tracking_number: tracking || null, admin_note: adminNote || null })
    });
    const data = await resp.json();
    if (resp.ok) {
      const statusClass = { pending:'pending', processing:'pending', shipped:'shipped', delivered:'active', cancelled:'cancelled' };
      const badge = document.getElementById('orderCurrentStatus');
      badge.className  = 'status-badge ' + (statusClass[val] || 'pending');
      badge.textContent = val.charAt(0).toUpperCase() + val.slice(1);
      // Update in-memory ORDER_DATA so re-opens reflect the change
      const idx = ORDER_DATA.findIndex(x => x.id == orderId);
      if (idx !== -1) { ORDER_DATA[idx].status = val; ORDER_DATA[idx].tracking_number = tracking; ORDER_DATA[idx].admin_note = adminNote; }
      showToast('✅', 'Order updated successfully');
    } else {
      showToast('⚠️', (data && data.message) || 'Failed to update order');
    }
  } catch(e) { showToast('⚠️', 'Network error updating order'); }
}

async function saveOrderChanges() {
  await updateOrderStatus();
  document.getElementById('orderModalOverlay').classList.remove('open');
}

async function cancelOrder() {
  const sel = document.getElementById('orderStatusSelect');
  [...sel.options].forEach(opt => { opt.selected = opt.value.toLowerCase() === 'cancelled'; });
  await updateOrderStatus();
  document.getElementById('orderModalOverlay').classList.remove('open');
}

// ── User Detail Modal ─────────────────────────────────
const USER_DATA = [
  { name:'Adaeze Okonkwo',  email:'adaeze@email.com',   tier:'💎 Radiant Insider', tierColor:'var(--red)',           orders:7,  spend:'₦312K', points:'3,120', reviews:3,  skin:'Combination', joined:'Jan 2026', initials:'AO' },
  { name:'Chinyere Adaeze', email:'chinyere@email.com', tier:'👑 Luxe Luminary',   tierColor:'#4f94ea',             orders:14, spend:'₦648K', points:'6,480', reviews:8,  skin:'Dry',         joined:'Oct 2025', initials:'CA' },
  { name:'Ngozi Eze',       email:'ngozi@email.com',    tier:'✨ Glow Starter',    tierColor:'rgba(10,10,10,.5)',    orders:3,  spend:'₦87K',  points:'870',   reviews:1,  skin:'Oily',        joined:'Feb 2026', initials:'NE' },
  { name:'Fatimah Bello',   email:'fatimah@email.com',  tier:'💎 Radiant Insider', tierColor:'var(--red)',           orders:9,  spend:'₦256K', points:'2,560', reviews:4,  skin:'Sensitive',   joined:'Jul 2025', initials:'FB' },
  { name:'Amaka Obi',       email:'amaka@email.com',    tier:'👑 Luxe Luminary',   tierColor:'#4f94ea',             orders:22, spend:'₦891K', points:'8,910', reviews:12, skin:'Combination', joined:'Apr 2025', initials:'AO' },
  { name:'Ifeoma Nwosu',    email:'ifeoma@email.com',   tier:'✨ Glow Starter',    tierColor:'rgba(10,10,10,.5)',    orders:1,  spend:'₦24K',  points:'240',   reviews:0,  skin:'Normal',      joined:'Mar 2026', initials:'IN' },
];

function openUserModal(userIdx) {
  const u = USER_DATA[userIdx] || USER_DATA[0];
  document.getElementById('uAvatar').textContent     = u.initials;
  document.getElementById('uName').textContent       = u.name;
  document.getElementById('uEmail').textContent      = u.email;
  document.getElementById('uTier').textContent       = u.tier;
  document.getElementById('uTier').style.color       = u.tierColor;
  document.getElementById('uStatOrders').textContent = u.orders;
  document.getElementById('uStatSpend').textContent  = u.spend;
  document.getElementById('uStatPoints').textContent = u.points;
  document.getElementById('uStatReviews').textContent = u.reviews;
  document.getElementById('uJoined').value           = u.joined;
  const skinSel = document.getElementById('uSkinType');
  [...skinSel.options].forEach(o => { o.selected = o.text === u.skin; });
  document.getElementById('userModalOverlay').classList.add('open');
}

// ── Inventory Table ───────────────────────────────────
function buildInventoryTable() {
  const tbody    = document.getElementById('inventoryTbody');
  const products = (typeof CMS_PRODUCTS !== 'undefined') ? CMS_PRODUCTS : [];
  if (!tbody) return;
  if (!products.length) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:32px;color:rgba(10,10,10,.35);">No products in catalog yet.</td></tr>';
    return;
  }
  const now = new Date();
  tbody.innerHTML = products.map(p => {
    const stock   = p.stock ?? p.stock_quantity ?? 0;
    const maxStock = Math.max(stock, 200);
    const pct     = Math.min(100, Math.round(stock / maxStock * 100));
    const isLow   = stock > 0 && stock <= 10;
    const isOut   = stock === 0;
    const fillCls    = isOut ? 'red' : isLow ? 'amber' : 'green';
    const statusCls  = isOut ? 'out-of-stock' : isLow ? 'pending' : 'active';
    const statusText = isOut ? 'Out of Stock'  : isLow ? 'Low Stock' : 'In Stock';
    const batch  = p.batch_number || '—';
    const expiry = p.expiry_date || '';
    const expDate    = expiry ? new Date(expiry.length === 7 ? expiry + '-01' : expiry) : null;
    const monthsLeft = expDate ? Math.round((expDate - now) / (1000 * 60 * 60 * 24 * 30.4)) : 99;
    const expiryStyle = monthsLeft <= 3 ? 'color:var(--red);font-weight:700;' : monthsLeft <= 6 ? 'color:#f59e0b;font-weight:600;' : 'color:rgba(10,10,10,.55);';
    const expiryFlag  = monthsLeft <= 3 ? ' ⚠️' : '';
    const imgArr = p.images;
    const imgUrl = Array.isArray(imgArr) ? (imgArr[0] || '') : (imgArr || p.image || '');
    return '<tr><td><div style="display:flex;align-items:center;gap:10px;"><img class="product-thumb" src="' + imgUrl + '" style="width:38px;height:38px;" onerror="this.style.background=\'#eee\'" /><div><div style="font-weight:600;font-size:.83rem;">' + p.name + '</div><div style="font-size:.72rem;color:rgba(10,10,10,.4);">' + p.brand + '</div></div></div></td><td style="font-size:.82rem;">' + p.category + '</td><td><span style="font-family:monospace;font-size:.8rem;background:#f4f5f7;padding:3px 8px;border-radius:6px;">' + batch + '</span></td><td><span style="' + expiryStyle + 'font-size:.82rem;">' + (expiry || '—') + expiryFlag + '</span></td><td><input class="inv-qty-input" type="number" value="' + stock + '" min="0" onchange="updateStockQty(' + p.id + ',this.value,this)" /></td><td><div style="display:flex;align-items:center;gap:8px;"><div class="stock-bar-mini"><div class="stock-fill-mini ' + fillCls + '" style="width:' + pct + '%"></div></div><span style="font-size:.72rem;color:rgba(10,10,10,.4);">' + pct + '%</span></div></td><td><span class="status-badge ' + statusCls + '" id="inv-status-' + p.id + '">' + statusText + '</span></td></tr>';
  }).join('');
}

function invExportCsv() {
  const products = (typeof CMS_PRODUCTS !== 'undefined') ? CMS_PRODUCTS : [];
  if (!products.length) { showToast('ℹ️', 'No products to export.'); return; }
  const headers = ['Name','Brand','Category','Stock','Batch Number','Expiry Date'];
  const rows = products.map(p => [
    (p.name || '').replace(/,/g, ';'),
    (p.brand || '').replace(/,/g, ';'),
    p.category || '',
    p.stock ?? p.stock_quantity ?? 0,
    p.batch_number || '',
    p.expiry_date || '',
  ].map(v => '"' + v + '"').join(','));
  const csv  = [headers.join(','), ...rows].join('\n');
  const blob = new Blob([csv], { type: 'text/csv' });
  const url  = URL.createObjectURL(blob);
  const a    = document.createElement('a');
  a.href = url; a.download = 'inventory_' + new Date().toISOString().substring(0, 10) + '.csv';
  a.click(); URL.revokeObjectURL(url);
}

function updateStockQty(productId, newQty, inputEl) {
  const q = parseInt(newQty) || 0;
  const badge = document.getElementById('inv-status-' + productId);
  if (!badge) return;
  if (q === 0)      { badge.className = 'status-badge out-of-stock'; badge.textContent = 'Out of Stock'; }
  else if (q <= 10) { badge.className = 'status-badge pending';      badge.textContent = 'Low Stock'; }
  else              { badge.className = 'status-badge active';       badge.textContent = 'In Stock'; }
  showToast('📦', 'Stock updated to ' + q + ' units');
}

function filterInventory(query) {
  const q = query.toLowerCase();
  document.querySelectorAll('#inventoryTbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}

// ── Community Gallery Admin ────────────────────────────────────────────────
let COMM_CACHE = [];
const COMM_CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

async function commApi(url, method, body) {
  const opts = { method: method || 'GET', headers: { 'X-CSRF-TOKEN': COMM_CSRF, 'Accept': 'application/json' } };
  if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
  const r = await fetch(url, opts);
  if (!r.ok) throw new Error('HTTP ' + r.status);
  return r.json();
}

function commSwitchTab(tab, el) {
  document.querySelectorAll('#panel-community .panel-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('#panel-community .panel-tab-content').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  const tc = document.getElementById('comm-tab-' + tab);
  if (tc) tc.classList.add('active');
  if (tab === 'settings') { commLoadSettings(); return; }
  if (tab === 'activity') { commLoadActivity(); return; }
  if (COMM_CACHE.length === 0) { commLoadAll(); return; }
  if (tab === 'all')      commRenderTable(COMM_CACHE);
}

function commUpdateStats() {
  const pending       = COMM_CACHE.filter(p => p.status === 'pending');
  const totalLikes    = COMM_CACHE.reduce((s, p) => s + (p.likes || 0), 0);
  const totalComments = COMM_CACHE.reduce((s, p) => s + (p.comments || 0), 0);
  const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  set('cs-total',          COMM_CACHE.length);
  set('cs-total-likes',    totalLikes.toLocaleString());
  set('cs-total-comments', totalComments.toLocaleString());
  set('cs-pending',        pending.length);
  const navBadge = document.getElementById('comm-nav-badge');
  if (navBadge) { navBadge.textContent = pending.length; navBadge.style.display = pending.length ? '' : 'none'; }
  const actBadge = document.getElementById('comm-badge-activity');
  if (actBadge) {
    const log = (() => { try { return JSON.parse(localStorage.getItem('kominhoo_activity_log') || '[]'); } catch(_) { return []; } })();
    actBadge.textContent = log.length; actBadge.style.display = log.length ? '' : 'none';
  }
}

async function commLoadAll() {
  const refreshBtn = document.getElementById('comm-refresh-btn');
  if (refreshBtn) { refreshBtn.textContent = '↺ Loading…'; refreshBtn.disabled = true; }

  // Immediately show server-side injected data so the panel is never empty
  if (COMM_CACHE.length === 0 && typeof CMS_COMMUNITY_POSTS !== 'undefined' && CMS_COMMUNITY_POSTS.length > 0) {
    COMM_CACHE = CMS_COMMUNITY_POSTS;
    commUpdateStats();
    commRenderTable(COMM_CACHE);
  }

  // Refresh from API in background
  try {
    const data = await commApi('/admin/community/posts?status=all');
    if (data.posts) COMM_CACHE = data.posts;
  } catch(e) { /* keep existing cache */ }

  if (refreshBtn) { refreshBtn.textContent = '↺ Refresh'; refreshBtn.disabled = false; }
  commUpdateStats();
  commRenderTable(COMM_CACHE);
}

function commLoadActivity() {
  let log = [];
  try { log = JSON.parse(localStorage.getItem('kominhoo_activity_log') || '[]'); } catch(_) {}

  // Also try API (non-blocking); merge and re-render on success
  fetch(ADMIN_URL + '/community/activity', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
      const apiLog = (data.activity || []).map(a => ({
        type: a.type, post_id: a.post_id, post_caption: a.post_caption || '', post_type: a.post_type || 'photo',
        user: a.user || 'A member', text: a.text || '', id: a.id || Date.now(), time: a.time || a.created_at || new Date().toISOString()
      }));
      const merged = [...apiLog, ...log].sort((a, b) => new Date(b.time) - new Date(a.time)).slice(0, 300);
      commRenderActivity(merged);
    })
    .catch(() => {});

  commRenderActivity(log);
}

function commRenderActivity(log) {
  const el = document.getElementById('commActivityFeed');
  if (!el) return;
  if (!log.length) {
    el.innerHTML = '<div style="text-align:center;padding:60px;color:#9CA3AF;font-size:.9rem;">No activity recorded yet.<br><span style="font-size:.8rem;">Likes and comments from community members will appear here.</span></div>';
    return;
  }
  el.innerHTML = log.map(a => {
    const isLike   = a.type === 'like';
    const icon     = isLike ? '♥' : '💬';
    const iconBg   = isLike ? '#e63946' : '#3B82F6';
    const action   = isLike ? 'liked a post' : 'commented on a post';
    const timeStr  = a.time ? new Date(a.time).toLocaleString('en-NG', { month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' }) : 'Recently';
    const caption  = (a.post_caption || '').substring(0, 65) + ((a.post_caption || '').length > 65 ? '…' : '');
    const typeChip = `<span class="tag-chip" style="font-size:.6rem;padding:1px 6px;">${a.post_type || 'photo'}</span>`;
    return `<div style="display:flex;align-items:flex-start;gap:14px;padding:14px 0;border-bottom:1px solid #f4f4f4;">
      <div style="width:36px;height:36px;border-radius:50%;background:${iconBg};color:#fff;display:grid;place-items:center;font-size:.9rem;flex-shrink:0;">${icon}</div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:700;font-size:.85rem;">${a.user || 'A member'} <span style="font-weight:400;color:#9CA3AF;">${action}</span></div>
        ${a.text ? `<div style="font-size:.82rem;color:#4B5563;margin-top:3px;font-style:italic;">"${a.text}"</div>` : ''}
        ${caption ? `<div style="font-size:.75rem;color:#9CA3AF;margin-top:4px;">${caption} ${typeChip}</div>` : ''}
      </div>
      <div style="font-size:.72rem;color:#9CA3AF;flex-shrink:0;white-space:nowrap;">${timeStr}</div>
    </div>`;
  }).join('');
}

function commCard(p, mode) {
  const sc          = { approved:'active', pending:'pending', rejected:'cancelled' };
  const typeColors  = { photo:'#D4D994', before_after:'#8B5CF6', review:'#F59E0B', routine:'#0EA5E9' };
  const typeIcons   = { photo:'📸', before_after:'✨', review:'⭐', routine:'🧴' };

  let imgHtml;
  if (p.type === 'before_after' && (p.before_img || p.after_img)) {
    imgHtml = '<div style="display:grid;grid-template-columns:1fr 1fr;height:140px;gap:1px;overflow:hidden;border-radius:var(--r-lg,10px) var(--r-lg,10px) 0 0;">'
      + '<div style="position:relative;overflow:hidden;"><img src="' + (p.before_img || '') + '" style="width:100%;height:140px;object-fit:cover;" onerror="this.parentElement.style.background=\'#e5e7eb\'"><span style="position:absolute;bottom:4px;left:4px;background:rgba(0,0,0,.7);color:#fff;font-size:.55rem;font-weight:700;padding:1px 5px;border-radius:3px;letter-spacing:.05em;">BEFORE</span></div>'
      + '<div style="position:relative;overflow:hidden;"><img src="' + (p.after_img || '') + '" style="width:100%;height:140px;object-fit:cover;" onerror="this.parentElement.style.background=\'#e5e7eb\'"><span style="position:absolute;bottom:4px;right:4px;background:rgba(212,217,148,.95);color:#1C1416;font-size:.55rem;font-weight:700;padding:1px 5px;border-radius:3px;letter-spacing:.05em;">AFTER</span></div>'
      + '</div>';
  } else if (p.img) {
    imgHtml = '<img class="community-card-img" src="' + p.img + '" style="height:140px;object-fit:cover;width:100%;" onerror="this.style.background=\'#f0f2f4\';this.style.minHeight=\'140px\'" />';
  } else {
    const col  = typeColors[p.type] || '#e5e7eb';
    const icon = typeIcons[p.type]  || '📸';
    imgHtml = '<div style="height:100px;background:' + col + ';display:grid;place-items:center;font-size:2rem;opacity:.85;">' + icon + '</div>';
  }

  const stars = (p.type === 'review' && p.stars)
    ? '<div style="color:#F59E0B;font-size:.78rem;margin-bottom:4px;">' + '★'.repeat(Math.min(p.stars, 5)) + '☆'.repeat(Math.max(0, 5 - p.stars)) + '</div>'
    : '';
  const date = p.submitted_at
    ? '<span style="margin-left:auto;font-size:.65rem;color:#9CA3AF;">' + new Date(p.submitted_at).toLocaleDateString('en-NG', { month:'short', day:'numeric' }) + '</span>'
    : '';

  return '<div class="community-card" id="cc-' + p.id + '">'
    + imgHtml
    + '<div class="community-card-body">'
    + '<div class="community-card-user" style="gap:4px;flex-wrap:wrap;">'
    + '<div style="flex:1;min-width:0;">'
    + (p.user && p.user.name ? '<div style="font-weight:700;font-size:.78rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + p.user.name + '</div>' : '')
    + (p.user && p.user.handle ? '<div style="font-size:.68rem;color:#9CA3AF;">' + p.user.handle + (p.user.skin ? ' · ' + p.user.skin : '') + '</div>' : '')
    + '</div>'
    + '<span class="status-badge ' + (sc[p.status] || 'pending') + '" style="font-size:.58rem;padding:1px 7px;white-space:nowrap;">' + (p.status || '—') + '</span>'
    + '</div>'
    + stars
    + '<div class="community-card-caption" style="font-size:.78rem;line-height:1.45;margin-bottom:6px;">' + (p.caption || '').substring(0, 90) + ((p.caption || '').length > 90 ? '…' : '') + '</div>'
    + '<div style="font-size:.68rem;color:#9CA3AF;margin-bottom:8px;display:flex;gap:6px;align-items:center;flex-wrap:wrap;">'
    + '<span>♥ ' + (p.likes || 0) + '</span><span>💬 ' + (p.comments || 0) + '</span>'
    + '<span class="tag-chip" style="font-size:.58rem;padding:1px 6px;">' + (p.type || 'photo') + '</span>'
    + (p.featured ? '<span style="color:#F59E0B;">⭐</span>' : '')
    + (p.pinned   ? '<span style="color:#3B82F6;">📌</span>' : '')
    + date
    + '</div>'
    + '<div class="community-card-actions" style="flex-wrap:wrap;gap:4px;">'
    + (mode === 'pending'
        ? '<button class="action-btn primary" style="flex:1;justify-content:center;font-size:.72rem;" onclick="commApprove(\'' + p.id + '\')">✓ Approve</button>'
          + '<button class="action-btn danger" style="font-size:.72rem;" onclick="commReject(\'' + p.id + '\')">✕ Reject</button>'
        : '<button class="action-btn ' + (p.featured ? 'primary' : 'edit') + '" style="font-size:.72rem;" onclick="commFeature(\'' + p.id + '\')">' + (p.featured ? '⭐ Featured' : '★ Feature') + '</button>'
          + '<button class="action-btn ' + (p.pinned ? 'primary' : 'edit') + '" style="font-size:.72rem;" onclick="commPin(\'' + p.id + '\')">' + (p.pinned ? '📌 Pinned' : '📌 Pin') + '</button>'
      )
    + '<button class="action-btn danger" style="font-size:.72rem;" onclick="commDelete(\'' + p.id + '\')">🗑️</button>'
    + '</div></div></div>';
}

function commRenderPending(posts) {
  const g = document.getElementById('commPendingGrid');
  if (!g) return;
  g.innerHTML = posts.length ? posts.map(p => commCard(p, 'pending')).join('') : '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#9CA3AF">No pending posts 🎉</div>';
}
function commRenderApproved(posts) {
  const g = document.getElementById('commApprovedGrid');
  if (!g) return;
  g.innerHTML = posts.length ? posts.map(p => commCard(p, 'approved')).join('') : '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#9CA3AF">No approved posts yet.</div>';
}
let COMM_FILTER = 'all';
function commSetFilter(f, btn) {
  COMM_FILTER = f;
  document.querySelectorAll('#comm-tab-all .action-btn').forEach(b => { b.style.background = ''; b.style.color = ''; });
  if (btn) { btn.style.background = '#1C1416'; btn.style.color = '#fff'; }
  commRenderTable(COMM_CACHE);
}
function commRenderTable(posts) {
  const tb = document.getElementById('commAllTable');
  if (!tb) return;
  const filtered = COMM_FILTER === 'all' ? posts : posts.filter(p => p.status === COMM_FILTER);
  if (!filtered.length) {
    const msg = COMM_FILTER === 'all' ? 'No posts yet.' : 'No ' + COMM_FILTER + ' posts.';
    tb.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:24px;color:#9CA3AF">' + msg + '</td></tr>';
    return;
  }
  const sc = { approved:'active', pending:'pending', rejected:'cancelled' };
  const typeColors = { photo:'#D4D994', before_after:'#8B5CF6', review:'#F59E0B', routine:'#0EA5E9' };
  tb.innerHTML = filtered.map(p => {
    const date = p.submitted_at ? new Date(p.submitted_at).toLocaleDateString('en-NG', { month:'short', day:'numeric', year:'numeric' }) : '—';
    const userName = p.user ? (p.user.name || p.user.handle || '—') : '—';
    const hasComments = (p.comment_list || []).length > 0;
    const thumbSrc = p.type === 'before_after' ? (p.after_img || p.before_img || p.img) : p.img;
    const thumbBg  = typeColors[p.type] || '#e5e7eb';
    const thumbHtml = thumbSrc
      ? '<img src="' + thumbSrc + '" style="width:44px;height:44px;object-fit:cover;border-radius:8px;display:block;" onerror="this.style.display=\'none\';this.nextSibling.style.display=\'grid\'">'
        + '<div style="width:44px;height:44px;border-radius:8px;background:' + thumbBg + ';display:none;place-items:center;font-size:1.2rem;">📸</div>'
      : '<div style="width:44px;height:44px;border-radius:8px;background:' + thumbBg + ';display:grid;place-items:center;font-size:1.2rem;">📸</div>';

    const flagLine = (p.featured ? '<span style="color:#F59E0B;font-size:.65rem;" title="Featured">⭐</span>' : '')
                   + (p.pinned   ? '<span style="color:#3B82F6;font-size:.65rem;" title="Pinned">📌</span>'  : '');

    return '<tr id="comm-row-' + p.id + '">'
      + '<td style="padding:8px 6px;">' + thumbHtml + '</td>'
      + '<td><strong>' + userName + '</strong>' + (flagLine ? ' ' + flagLine : '') + '<br><span style="font-size:.72rem;color:#9CA3AF">' + (p.user && p.user.email ? p.user.email : (p.user && p.user.handle ? p.user.handle : '')) + '</span></td>'
      + '<td style="max-width:160px;font-size:.82rem;color:rgba(10,10,10,.6)">' + (p.caption || p.quote || '').substring(0, 55) + ((p.caption || p.quote || '').length > 55 ? '…' : '') + '</td>'
      + '<td><span class="tag-chip" style="background:' + (typeColors[p.type]||'#e5e7eb') + ';color:#1C1416;">' + (p.type || 'photo') + '</span></td>'
      + '<td style="font-size:.78rem;">' + date + '</td>'
      + '<td><span class="status-badge ' + (sc[p.status] || 'pending') + '">' + (p.status || '—') + '</span></td>'
      + '<td style="font-size:.82rem;white-space:nowrap;">'
        + '<button onclick="commShowLikers(\'' + p.id + '\',this)" style="background:none;border:none;cursor:pointer;color:#e63946;font-size:.82rem;font-weight:700;padding:2px 4px;" title="See who liked this">♥ ' + (p.likes || 0) + '</button>'
      + '</td>'
      + '<td style="font-size:.82rem;white-space:nowrap;">'
        + '<button onclick="commToggleComments(\'' + p.id + '\',this)" style="background:none;border:none;cursor:pointer;color:#3B82F6;font-size:.82rem;font-weight:700;padding:2px 4px;" title="View / hide comments">'
        + '💬 ' + (p.comments || 0) + (hasComments ? ' ▾' : '')
        + '</button>'
      + '</td>'
      + '<td><div style="display:flex;gap:4px;flex-wrap:wrap;">'
      + (p.status === 'pending'
          ? '<button class="action-btn primary" style="font-size:.7rem;padding:3px 9px;" onclick="commApprove(\'' + p.id + '\')">✓ Approve</button>'
            + '<button class="action-btn danger" style="font-size:.7rem;padding:3px 9px;" onclick="commReject(\'' + p.id + '\')">✕ Reject</button>'
          : '')
      + (p.status === 'approved'
          ? '<button class="action-btn edit" style="font-size:.7rem;padding:3px 9px;" title="' + (p.featured ? 'Unfeature' : 'Set as featured hero') + '" onclick="commFeature(\'' + p.id + '\')">' + (p.featured ? '⭐ Featured' : '★ Feature') + '</button>'
            + '<button class="action-btn edit" style="font-size:.7rem;padding:3px 9px;" title="' + (p.pinned ? 'Unpin' : 'Pin to top') + '" onclick="commPin(\'' + p.id + '\')">' + (p.pinned ? '📌' : '📌 Pin') + '</button>'
            + '<button class="action-btn danger" style="font-size:.7rem;padding:3px 9px;" onclick="commReject(\'' + p.id + '\')">🚫 Reject</button>'
          : '')
      + (p.status === 'rejected'
          ? '<button class="action-btn primary" style="font-size:.7rem;padding:3px 9px;" onclick="commApprove(\'' + p.id + '\')">↩ Restore</button>'
          : '')
      + '<button class="action-btn danger" style="font-size:.7rem;padding:3px 7px;" title="Delete permanently" onclick="commDelete(\'' + p.id + '\')">🗑️</button>'
      + '</div></td></tr>';
  }).join('');
}

// — Comment expansion (inline below the post row) ─────────────────────────
function commToggleComments(postId, btn) {
  const expandId = 'comm-comments-' + postId;
  const existing = document.getElementById(expandId);
  if (existing) { existing.remove(); btn.textContent = btn.textContent.replace('▴','▾'); return; }

  const p = COMM_CACHE.find(x => x.id === postId);
  if (!p) return;

  const comments = p.comment_list || [];
  const row = document.getElementById('comm-row-' + postId);
  if (!row) return;

  const expand = document.createElement('tr');
  expand.id = expandId;
  const colCount = row.cells.length;
  expand.innerHTML = '<td colspan="' + colCount + '" style="padding:0;background:#f9fafb;border-bottom:2px solid #e5e7eb;">'
    + '<div style="padding:16px 20px;">'
    + '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">'
    + '<strong style="font-size:.85rem;">💬 Comments (' + comments.length + ')</strong>'
    + (comments.length === 0 ? '<span style="font-size:.8rem;color:#9CA3AF">No comments yet</span>' : '')
    + '</div>'
    + (comments.length > 0
        ? comments.map(c => {
            const ts = c.created_at ? new Date(c.created_at).toLocaleString('en-NG', { month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' }) : c.time || '';
            return '<div id="comm-comment-' + c.id + '" style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid #f0f0f0;">'
              + '<div style="width:32px;height:32px;border-radius:50%;background:' + (c.color||'#D4D994') + ';color:' + (c.color==='#D4D994'||c.color==='#F59E0B' ? '#1C1416':'#fff') + ';display:grid;place-items:center;font-weight:700;font-size:.72rem;flex-shrink:0;">' + (c.av||'U') + '</div>'
              + '<div style="flex:1;min-width:0;">'
              + '<div style="font-weight:700;font-size:.82rem;">' + (c.name||'Member') + ' <span style="font-weight:400;color:#9CA3AF;font-size:.72rem;">' + ts + '</span></div>'
              + '<div style="font-size:.82rem;color:#374151;margin-top:3px;">' + (c.text||'') + '</div>'
              + '</div>'
              + '<button onclick="commDeleteComment(\'' + postId + '\',\'' + c.id + '\',this)" class="action-btn danger" style="font-size:.65rem;padding:2px 7px;flex-shrink:0;">🗑️</button>'
              + '</div>';
          }).join('')
        : '')
    + '</div></td>';

  row.insertAdjacentElement('afterend', expand);
  btn.textContent = btn.textContent.replace('▾','▴');
}

// — Delete a comment directly from the inline expansion ───────────────────
async function commDeleteComment(postId, commentId, btn) {
  if (!confirm('Delete this comment?')) return;
  if (btn) { btn.disabled = true; btn.textContent = '…'; }
  try {
    const r = await commApi('/admin/community/post/' + postId + '/comment/' + commentId, 'DELETE');
    if (!r.success) throw new Error('Server returned failure');
    // Remove from DOM
    document.getElementById('comm-comment-' + commentId)?.remove();
    // Update cache
    const p = COMM_CACHE.find(x => x.id === postId);
    if (p) {
      p.comment_list = (p.comment_list || []).filter(c => c.id !== commentId);
      p.comments = p.comment_list.length;
    }
    // Update the counter button in the main table row
    const commBtn = document.querySelector('#comm-row-' + postId + ' button[title="View / hide comments"]');
    if (commBtn) commBtn.innerHTML = '💬 ' + (p ? p.comments : 0) + (p?.comment_list?.length > 0 ? ' ▴' : '');
    // If no comments remain, update the heading inside the expanded panel
    const expandedHeading = document.querySelector('#comm-comments-' + postId + ' strong');
    if (expandedHeading) expandedHeading.textContent = '💬 Comments (' + (p ? p.comments : 0) + ')';
    showToast('🗑️', 'Comment deleted.');
  } catch(e) {
    if (btn) { btn.disabled = false; btn.textContent = '🗑️'; }
    showToast('⚠️', 'Could not delete comment. Try again.');
  }
}

// — Show who liked a post (inline below the row) ───────────────────────────
async function commShowLikers(postId, btn) {
  const expandId = 'comm-likers-' + postId;
  const existing = document.getElementById(expandId);
  if (existing) { existing.remove(); return; }

  const row = document.getElementById('comm-row-' + postId);
  if (!row) return;
  const colCount = row.cells.length;

  // Fetch likers from API
  let likers = [];
  try {
    const d = await commApi('/admin/community/post/' + postId + '/likers');
    likers = d.likers || [];
  } catch(e) {}

  const expand = document.createElement('tr');
  expand.id = expandId;
  expand.innerHTML = '<td colspan="' + colCount + '" style="padding:0;background:#fff5f5;border-bottom:2px solid #fecaca;">'
    + '<div style="padding:16px 20px;">'
    + '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">'
    + '<strong style="font-size:.85rem;color:#e63946;">♥ Likes (' + likers.length + ')</strong>'
    + '<button onclick="document.getElementById(\'' + expandId + '\').remove()" style="background:none;border:none;cursor:pointer;color:#9CA3AF;font-size:1rem;">×</button>'
    + '</div>'
    + (likers.length === 0
        ? '<span style="font-size:.8rem;color:#9CA3AF">No likes yet</span>'
        : '<div style="display:flex;flex-wrap:wrap;gap:8px;">'
          + likers.map(l => {
              const ts = l.created_at ? new Date(l.created_at).toLocaleString('en-NG', { month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' }) : '';
              return '<div style="background:#fff;border:1px solid #fecaca;border-radius:8px;padding:6px 12px;font-size:.78rem;">'
                + '<strong>' + (l.user_name || l.user_key) + '</strong>'
                + (ts ? '<br><span style="color:#9CA3AF;font-size:.68rem;">' + ts + '</span>' : '')
                + '</div>';
            }).join('')
          + '</div>'
      )
    + '</div></td>';

  row.insertAdjacentElement('afterend', expand);
}

async function commApprove(id) {
  try {
    const r = await commApi('/admin/community/post/' + id + '/approve', 'POST');
    if (r.success) { showToast('✅', 'Post approved and live!'); await commLoadAll(); }
    else showToast('⚠️', 'Approval failed. Try refreshing.');
  } catch(e) { showToast('⚠️', 'Could not approve post. Try again.'); }
}
async function commReject(id) {
  try {
    const r = await commApi('/admin/community/post/' + id + '/reject', 'POST');
    if (r.success) { showToast('🚫', 'Post rejected.'); await commLoadAll(); }
    else showToast('⚠️', 'Rejection failed. Try refreshing.');
  } catch(e) { showToast('⚠️', 'Could not reject post. Try again.'); }
}
async function commApproveAll() {
  const pending = COMM_CACHE.filter(p => p.status === 'pending');
  if (!pending.length) { showToast('ℹ️', 'No pending posts.'); return; }
  try {
    await Promise.all(pending.map(p => commApi('/admin/community/post/' + p.id + '/approve', 'POST')));
    showToast('✅', pending.length + ' posts approved!');
    await commLoadAll();
  } catch(e) { showToast('⚠️', 'Some approvals may have failed. Refreshing…'); await commLoadAll(); }
}
async function commFeature(id) {
  try {
    const r = await commApi('/admin/community/post/' + id + '/feature', 'POST');
    if (r.success) { showToast(r.featured ? '⭐' : '★', r.featured ? 'Post set as featured!' : 'Post unfeatured.'); await commLoadAll(); }
  } catch(e) { showToast('⚠️', 'Could not update featured status.'); }
}
async function commPin(id) {
  try {
    const r = await commApi('/admin/community/post/' + id + '/pin', 'POST');
    if (r.success) { showToast('📌', r.pinned ? 'Post pinned to top!' : 'Post unpinned.'); await commLoadAll(); }
  } catch(e) { showToast('⚠️', 'Could not update pin status.'); }
}
async function commDelete(id) {
  const p = COMM_CACHE.find(x => x.id === id);
  const label = p ? '"' + (p.caption || p.quote || 'this post').substring(0, 60) + '"' : 'this post';
  if (!confirm('Delete ' + label + ' permanently? This cannot be undone.')) return;
  // Optimistic: remove from DOM immediately
  document.getElementById('comm-row-' + id)?.remove();
  document.getElementById('comm-comments-' + id)?.remove();
  try {
    const r = await commApi('/admin/community/post/' + id, 'DELETE');
    if (!r.success) throw new Error('Server returned failure');
    COMM_CACHE = COMM_CACHE.filter(x => x.id !== id);
    commUpdateStats();
    showToast('🗑️', 'Post deleted.');
  } catch(e) {
    showToast('⚠️', 'Could not delete post. Refreshing…');
    await commLoadAll();
  }
}
async function commLoadSettings() {
  // Apply injected settings immediately
  const applySettings = (s) => {
    const openEl = document.getElementById('comm-setting-open');
    const autoEl = document.getElementById('comm-setting-auto');
    if (openEl) openEl.checked = s.submissions_open !== false;
    if (autoEl) autoEl.checked = s.moderation_mode === 'auto';
  };
  if (typeof CMS_COMMUNITY_SETTINGS !== 'undefined' && Object.keys(CMS_COMMUNITY_SETTINGS).length) {
    applySettings(CMS_COMMUNITY_SETTINGS);
  }
  try {
    const s = await commApi('/admin/community/settings');
    applySettings(s);
  } catch(e) {}
}
async function commSaveSetting(key, value) {
  await commApi('/admin/community/settings', 'PUT', { [key]: value });
  showToast('⚙️', 'Setting saved!');
}
function commExport() {
  if (!COMM_CACHE.length) { showToast('ℹ️', 'Load posts first.'); return; }
  const rows = [['ID','Handle','Caption','Type','Status','Likes','Comments','Submitted']];
  COMM_CACHE.forEach(p => {
    rows.push([p.id, p.user ? p.user.handle : '', (p.caption || '').replace(/,/g, ';'), p.type, p.status, p.likes || 0, p.comments || 0, p.submitted_at || '']);
  });
  const csv = rows.map(r => r.join(',')).join('\n');
  const a = document.createElement('a');
  a.href = 'data:text/csv,' + encodeURIComponent(csv);
  a.download = 'community_posts.csv';
  a.click();
}
// Legacy aliases kept for any other callers
function buildCommunityPending() { commLoadAll(); }
function approveAllCommunity()   { commApproveAll(); }

// ── Product search / filter ───────────────────────────
function filterProducts(query) {
  const q = query.toLowerCase().trim();
  document.querySelectorAll('#adminProductsTbody tr').forEach(row => {
    row.style.display = !q || row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}

// ── Reviews ───────────────────────────────────────────
let REVIEWS_ALL = [];

async function loadAnalyticsRating() {
  const el  = document.getElementById('analytics-avg-rating');
  const sub = document.getElementById('analytics-review-count');
  if (!el) return;
  // Reuse already-fetched data if available
  if (REVIEWS_ALL.length > 0) {
    const avg = (REVIEWS_ALL.reduce((s, r) => s + (r.rating || 0), 0) / REVIEWS_ALL.length).toFixed(1);
    el.textContent  = avg;
    if (sub) sub.textContent = 'from ' + REVIEWS_ALL.length.toLocaleString() + ' reviews';
    return;
  }
  try {
    const resp = await fetch('{{ route("admin.reviews.index") }}?per_page=500', {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    });
    const json = await resp.json();
    REVIEWS_ALL = json.data?.data ?? json.data ?? [];
    const total = REVIEWS_ALL.length;
    const avg   = total ? (REVIEWS_ALL.reduce((s, r) => s + (r.rating || 0), 0) / total).toFixed(1) : '—';
    el.textContent  = avg;
    if (sub) sub.textContent = total ? 'from ' + total.toLocaleString() + ' reviews' : 'no reviews yet';
  } catch (e) {
    if (el)  el.textContent  = '—';
    if (sub) sub.textContent = 'could not load';
  }
}

async function reviewsLoad() {
  const tbody = document.getElementById('rvw-table-body');
  if (tbody) tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:24px;color:#9CA3AF">Loading…</td></tr>';
  try {
    const resp = await fetch('{{ route("admin.reviews.index") }}', {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    });
    const json = await resp.json();
    REVIEWS_ALL = json.data?.data ?? json.data ?? [];
    reviewsApplyFilter();
  } catch (e) {
    if (tbody) tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--red)">Failed to load reviews.</td></tr>';
  }
}

function reviewsApplyFilter() {
  const prodId = document.getElementById('rvw-filter-product')?.value || '';
  const rating  = document.getElementById('rvw-filter-rating')?.value  || '';
  let filtered = REVIEWS_ALL;
  if (prodId) filtered = filtered.filter(r => String(r.product_id) === prodId);
  if (rating) filtered = filtered.filter(r => String(r.rating) === rating);
  reviewsRender(filtered);
}

function reviewsRender(reviews) {
  const total = REVIEWS_ALL.length;
  const avg   = total ? (REVIEWS_ALL.reduce((s, r) => s + (r.rating || 0), 0) / total).toFixed(1) : '—';
  const five  = REVIEWS_ALL.filter(r => r.rating === 5).length;
  const low   = REVIEWS_ALL.filter(r => r.rating <= 2).length;
  const setEl = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
  setEl('rvw-stat-total', total || '0');
  setEl('rvw-stat-avg',   avg);
  setEl('rvw-stat-5star', five);
  setEl('rvw-stat-low',   low);
  setEl('rvw-table-subtitle', `Showing ${reviews.length} review${reviews.length === 1 ? '' : 's'}`);

  const stars = n => '★'.repeat(Math.max(0, n)) + '☆'.repeat(Math.max(0, 5 - n));
  const tbody = document.getElementById('rvw-table-body');
  if (!tbody) return;
  if (!reviews.length) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:24px;color:#9CA3AF">No reviews found.</td></tr>';
    return;
  }
  tbody.innerHTML = reviews.map(r => {
    const product = escHtml(r.product?.name ?? `Product #${r.product_id}`);
    const date    = r.created_at ? new Date(r.created_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '—';
    const body    = escHtml((r.body ?? '').length > 80 ? r.body.slice(0, 80) + '…' : (r.body ?? ''));
    return `<tr>
      <td><strong>${product}</strong></td>
      <td>${escHtml(r.reviewer_name ?? '—')}</td>
      <td><span style="color:#f59e0b;font-size:.9rem;">${stars(r.rating ?? 0)}</span></td>
      <td style="max-width:200px;font-size:.8rem;color:rgba(10,10,10,.6);">${body}</td>
      <td style="font-size:.78rem;white-space:nowrap;">${date}</td>
      <td><button class="action-btn danger" style="font-size:.7rem;padding:3px 8px;" onclick="deleteReview(${r.id},this)">✕ Delete</button></td>
    </tr>`;
  }).join('');
}

async function deleteReview(id, btn) {
  if (!confirm('Delete this review? This cannot be undone.')) return;
  btn.disabled = true;
  try {
    const resp = await fetch(`{{ url('admin/reviews') }}/${id}`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    });
    const json = await resp.json();
    if (!resp.ok || !json.success) { showToast('⚠️', json.message || 'Failed to delete review'); btn.disabled = false; return; }
    REVIEWS_ALL = REVIEWS_ALL.filter(r => r.id !== id);
    reviewsApplyFilter();
    showToast('✅', 'Review deleted.');
  } catch (e) { showToast('⚠️', 'Network error'); btn.disabled = false; }
}

// ── Content Manager helpers ───────────────────────────
function addAnnItem() {
  const list = document.getElementById('annItemList');
  if (!list) return;
  const div = document.createElement('div');
  div.className = 'ann-item';
  div.innerHTML = '<span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" placeholder="✨" /><input type="text" class="form-input ann-text-field" placeholder="Announcement text…" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest(\'.ann-item\').remove()">🗑️</button>';
  list.appendChild(div);
  div.querySelector('.ann-text-field').focus();
}

function addFaqItem() {
  const list = document.getElementById('faqItems');
  if (!list) return;
  const div = document.createElement('div');
  div.style.cssText = 'border:1.5px solid #e8eaed;border-radius:10px;margin-bottom:10px;overflow:hidden;';
  div.innerHTML = '<div style="background:#fafbfc;padding:10px 16px;display:flex;align-items:center;gap:10px;"><input type="text" class="form-input" placeholder="Question…" style="flex:1;border:none;background:transparent;padding:0;font-weight:600;" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest(\'div\').parentElement.remove()">🗑️</button></div><textarea class="form-input" rows="2" placeholder="Answer…" style="border:none;border-top:1px solid #f0f2f4;border-radius:0;resize:vertical;"></textarea>';
  list.appendChild(div);
  div.querySelector('input').focus();
}

const CMS_CONTENT_ROUTES = {
  saveContent: @json(route('admin.cms.content.update')),
  saveQuiz: @json(route('admin.cms.quiz.update')),
};
const CMS_CONTENT_DATA = @json($cmsContent['content'] ?? []);
const CMS_QUIZ_DATA = @json($cmsContent['quiz'] ?? []);
const CMS_PRODUCTS = @json($catalogProducts ?? []);
const CMS_COMMUNITY_POSTS = @json($communityPosts ?? []);
const CMS_COMMUNITY_SETTINGS = @json($communitySettings ?? []);
const CMS_CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

function cmsProductOptions(selectedId = '') {
  return CMS_PRODUCTS.map(product => {
    const selected = String(product.id) === String(selectedId) ? ' selected' : '';
    const price = Number(product.price || 0).toLocaleString();
    return `<option value="${product.id}"${selected}>${escHtml(product.name)} (₦${price})</option>`;
  }).join('');
}

function cmsSpeedOptions(selected = 'normal') {
  return [['static','Static (no scroll)'],['normal','Normal (30s)'],['fast','Fast (20s)'],['slow','Slow (45s)']]
    .map(([value, label]) => `<option value="${value}"${selected === value ? ' selected' : ''}>${label}</option>`)
    .join('');
}

function cmsAnnouncementRow(item = {}) {
  return `<div class="ann-item"><span class="ann-drag">⠿</span><input class="form-input ann-emoji-pick" value="${escHtml(item.emoji || '')}" /><input type="text" class="form-input ann-text-field" value="${escHtml(item.text || '')}" /><input type="text" class="form-input ann-link-field" placeholder="Link (optional)" value="${escHtml(item.link || '')}" /><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>`;
}

function cmsFaqRow(item = {}) {
  return `<div style="border:1.5px solid #e8eaed;border-radius:10px;margin-bottom:10px;overflow:hidden;"><div style="background:#fafbfc;padding:10px 16px;display:flex;align-items:center;gap:10px;"><input type="text" class="form-input" value="${escHtml(item.question || '')}" style="flex:1;border:none;background:transparent;padding:0;font-weight:600;" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('div').parentElement.remove()">🗑️</button></div><textarea class="form-input" rows="2" style="border:none;border-top:1px solid #f0f2f4;border-radius:0;resize:vertical;">${escHtml(item.answer || '')}</textarea></div>`;
}

function cmsVisibilityToggle(id, label, checked) {
  return `<div class="toggle-wrap"><div class="toggle-info"><strong>${label}</strong></div><label class="toggle"><input type="checkbox" id="${id}" ${checked ? 'checked' : ''} /><span class="toggle-slider"></span></label></div>`;
}

function cmsMediaPageOptions(selected = 'home') {
  return [['home', 'Home'], ['community', 'Community'], ['shop', 'Shop'], ['results', 'Results'], ['faq', 'FAQ']]
    .map(([value, label]) => `<option value="${value}"${selected === value ? ' selected' : ''}>${label}</option>`)
    .join('');
}

function cmsMediaSlotOptions(selected = '') {
  const slots = [
    'why_1','why_2','why_3','why_4',
    'community_1','community_2','community_3','community_4','community_5','community_6','community_7',
    'hero_collage_1','hero_collage_2','hero_collage_3','hero_collage_4','hero_collage_5','hero_collage_6',
    'hero_floating_1','hero_floating_2',
    'featured_main'
  ];

  return ['<option value="">Choose slot</option>']
    .concat(slots.map(slot => `<option value="${slot}"${selected === slot ? ' selected' : ''}>${slot}</option>`))
    .join('');
}

// Normalize various media URLs so they display in the preview.
// Converts common Google Drive share links into a direct view URL.
function normalizeMediaUrl(url) {
  if (!url) return '';
  url = String(url).trim();
  try {
    // If already a direct uc link, return as-is
    if (url.includes('drive.google.com') && url.includes('uc?export')) return url;

    // Match patterns like /file/d/FILEID or /d/FILEID/view or ?id=FILEID
    const reFileD = /\/file\/d\/([a-zA-Z0-9_-]+)/i;
    const reDView = /\/d\/([a-zA-Z0-9_-]+)\//i;
    const reId = /[?&]id=([a-zA-Z0-9_-]+)/i;

    let m = url.match(reFileD) || url.match(reDView) || url.match(reId);
    if (m && m[1]) {
      return `https://drive.google.com/uc?export=view&id=${m[1]}`;
    }
  } catch (e) {
    // fall through
  }
  return url;
}

// Update the image preview for a media row when the URL input changes
function updateMediaRowPreview(urlInput) {
  try {
    const row = urlInput.closest('.media-library-row');
    if (!row) return;
    const img = row.querySelector('img');
    const val = (urlInput.value || '').trim();
    if (!img) return;
    if (!val) {
      img.style.display = 'none';
      img.removeAttribute('src');
      return;
    }

    const normalized = normalizeMediaUrl(val);
    // Set src and allow object-fit to make it responsive inside the thumb
    img.src = normalized;
    img.style.display = 'block';
    img.style.objectFit = 'cover';
    img.onload = () => {
      // ensure it fills the container responsively
      img.style.width = '100%';
      img.style.height = '100%';
    };
    img.onerror = () => {
      img.style.display = 'none';
    };
  } catch (e) {
    console.error('updateMediaRowPreview error', e);
  }
}

function cmsMediaRow(item = {}) {
  return `
    <div class="media-library-row" data-media-id="${escHtml(item.id || '')}" data-upload-path="${escHtml(item.uploadPath || '')}" style="border:1.5px solid #e8eaed;border-radius:12px;padding:12px;margin-bottom:10px;">
      <div style="display:grid;grid-template-columns:120px 130px 160px 1fr 1fr auto auto;gap:8px;align-items:center;">
        <div style="width:120px;height:78px;border-radius:8px;overflow:hidden;border:1px solid #eceef1;background:#f7f8fa;">
          <img src="${escHtml(normalizeMediaUrl(item.url || ''))}" alt="${escHtml(item.alt || 'Media preview')}" style="width:100%;height:100%;object-fit:cover;display:${item.url ? 'block' : 'none'};" onerror="this.style.display='none'" />
        </div>
        <select class="form-input media-page" style="font-size:.78rem;padding:8px 10px;">${cmsMediaPageOptions(item.page || 'home')}</select>
        <select class="form-input media-slot" style="font-size:.78rem;padding:8px 10px;">${cmsMediaSlotOptions(item.slot || '')}</select>
        <input class="form-input media-url" type="url" placeholder="https://..." value="${escHtml(item.url || '')}" oninput="updateMediaRowPreview(this)" />
        <input class="form-input media-alt" type="text" placeholder="Alt text" value="${escHtml(item.alt || '')}" />
        <label class="toggle" style="margin:0 auto;"><input class="media-enabled" type="checkbox" ${item.enabled !== false ? 'checked' : ''} /><span class="toggle-slider"></span></label>
        <button class="action-btn danger" type="button" style="padding:6px 10px;" onclick="this.closest('.media-library-row').remove()">Remove</button>
      </div>
      <div style="margin-top:8px;font-size:.73rem;color:rgba(10,10,10,.42);">Page + slot control where this media appears on the frontend.</div>
    </div>
  `;
}

function addMediaLibraryRow() {
  const container = document.getElementById('cms-media-library-list');
  if (!container) return;
  container.insertAdjacentHTML('beforeend', cmsMediaRow({ page: 'home', slot: '', url: '', alt: '', enabled: true }));
}

function initCmsEditor() {
  renderCmsEditor(CMS_CONTENT_DATA);
}

function renderCmsEditor(content) {
  const homepage = document.getElementById('ct-homepage');
  const pages = document.getElementById('ct-pages');
  const mediaPanel = document.getElementById('ct-media');
  if (!homepage || !pages || !mediaPanel) return;

  const announcement = content.announcement_bar || {};
  const hero = content.hero || {};
  const deal = content.deal_of_the_day || {};
  const visibility = content.section_visibility || {};
  const newDrops = content.new_drops || {};
  const media = content.media || {};
  const mediaLibrary = Array.isArray(media.library) ? media.library : [];
  const pageContent = content.pages || {};
  const community = pageContent.community || {};
  const shop = pageContent.shop || {};
  const loginPage = pageContent.login || {};
  const signupPage = pageContent.signup || {};
  const checkoutPage = pageContent.checkout || {};
  const resultsPage = pageContent.results || {};

  homepage.innerHTML = `
    <div class="content-block">
      <div class="content-block-header"><strong>Announcement Bar</strong><div style="display:flex;align-items:center;gap:12px;"><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Scroll speed</span><select id="cms-ann-speed" class="form-input" style="width:140px;padding:6px 10px;font-size:.78rem;">${cmsSpeedOptions(announcement.speed || 'normal')}</select><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Visible</span><label class="toggle"><input type="checkbox" id="cms-ann-visible" ${announcement.visible !== false ? 'checked' : ''} /><span class="toggle-slider"></span></label></div></div>
      <div id="annItemList">${(announcement.items || []).map(item => cmsAnnouncementRow(item)).join('')}</div>
      <button class="action-btn edit" style="margin-top:12px;" onclick="addAnnItem()">+ Add Announcement</button>
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>Hero Section</strong><div style="display:flex;align-items:center;gap:10px;"><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Visible</span><label class="toggle"><input type="checkbox" id="cms-hero-visible" ${hero.visible !== false ? 'checked' : ''} /><span class="toggle-slider"></span></label></div></div>
      <div class="hero-preview-bar"><div style="font-size:.62rem;color:rgba(255,255,255,.4);letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px;" id="heroEyebrowPreview">${escHtml(hero.eyebrow || '')}</div><div style="font-size:1.4rem;font-weight:700;color:#fff;line-height:1.2;margin-bottom:4px;"><span id="heroLine1Preview">${escHtml(hero.title_line_1 || '')}</span> <em style="font-style:italic;" id="heroLine2Preview">${escHtml(hero.title_line_2 || '')}</em> <span style="color:var(--lime);" id="heroLine3Preview">${escHtml(hero.title_line_3 || '')}</span></div><div style="font-size:.78rem;color:rgba(255,255,255,.5);" id="heroSubPreview">${escHtml(hero.description || '')}</div></div>
      <div class="form-grid">
        <div class="form-group"><label>Eyebrow Text</label><input id="cms-hero-eyebrow" type="text" class="form-input" value="${escHtml(hero.eyebrow || '')}" oninput="document.getElementById('heroEyebrowPreview').textContent=this.value" /></div>
        <div class="form-group"><label>Title Line 1</label><input id="cms-hero-line1" type="text" class="form-input" value="${escHtml(hero.title_line_1 || '')}" oninput="document.getElementById('heroLine1Preview').textContent=this.value" /></div>
        <div class="form-group"><label>Title Line 2</label><input id="cms-hero-line2" type="text" class="form-input" value="${escHtml(hero.title_line_2 || '')}" oninput="document.getElementById('heroLine2Preview').textContent=this.value" /></div>
        <div class="form-group"><label>Title Line 3</label><input id="cms-hero-line3" type="text" class="form-input" value="${escHtml(hero.title_line_3 || '')}" oninput="document.getElementById('heroLine3Preview').textContent=this.value" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Hero Description</label><textarea id="cms-hero-description" class="form-input" rows="2" oninput="document.getElementById('heroSubPreview').textContent=this.value">${escHtml(hero.description || '')}</textarea></div>
        <div class="form-group"><label>Primary CTA Text</label><input id="cms-hero-primary-text" type="text" class="form-input" value="${escHtml(hero.primary_cta_text || '')}" /></div>
        <div class="form-group"><label>Primary CTA Link</label><input id="cms-hero-primary-link" type="text" class="form-input" value="${escHtml(hero.primary_cta_link || '')}" /></div>
        <div class="form-group"><label>Secondary CTA Text</label><input id="cms-hero-secondary-text" type="text" class="form-input" value="${escHtml(hero.secondary_cta_text || '')}" /></div>
        <div class="form-group"><label>Secondary CTA Link</label><input id="cms-hero-secondary-link" type="text" class="form-input" value="${escHtml(hero.secondary_cta_link || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Hero Image URL</label><input id="cms-hero-image" type="url" class="form-input" value="${escHtml(hero.image_url || '')}" /></div>
      </div>
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>Deal of the Day</strong><div style="display:flex;align-items:center;gap:12px;"><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Show countdown</span><label class="toggle"><input type="checkbox" id="cms-deal-countdown" ${deal.show_countdown !== false ? 'checked' : ''} /><span class="toggle-slider"></span></label><span style="font-size:.78rem;color:rgba(10,10,10,.5);">Section visible</span><label class="toggle"><input type="checkbox" id="cms-deal-visible" ${deal.visible !== false ? 'checked' : ''} /><span class="toggle-slider"></span></label></div></div>
      <div class="form-grid">
        <div class="form-group"><label>Featured Product</label><select id="cms-deal-product" class="form-input"><option value="">Choose a product</option>${cmsProductOptions(deal.product_id || '')}</select></div>
        <div class="form-group"><label>Deal Price (₦)</label><input id="cms-deal-price" type="number" class="form-input" value="${escHtml(deal.deal_price || 0)}" /></div>
        <div class="form-group"><label>Original Price (₦)</label><input id="cms-deal-original-price" type="number" class="form-input" value="${escHtml(deal.original_price || 0)}" /></div>
        <div class="form-group"><label>Deal Badge Text</label><input id="cms-deal-badge" type="text" class="form-input" value="${escHtml(deal.badge || '')}" /></div>
        <div class="form-group"><label>Deal Headline</label><input id="cms-deal-headline" type="text" class="form-input" value="${escHtml(deal.headline || '')}" /></div>
        <div class="form-group"><label>Units Remaining</label><input id="cms-deal-units" type="number" class="form-input" value="${escHtml(deal.units_remaining || 0)}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Deal Description</label><textarea id="cms-deal-description" class="form-input" rows="2">${escHtml(deal.description || '')}</textarea></div>
      </div>
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>Section Visibility</strong><span>Show or hide homepage sections instantly</span></div>
      ${cmsVisibilityToggle('cms-vis-quiz', 'Quiz CTA Banner', visibility.quiz_cta_banner !== false)}
      ${cmsVisibilityToggle('cms-vis-recommended', 'Recommended For You', visibility.recommended_for_you !== false)}
      ${cmsVisibilityToggle('cms-vis-new-drops', 'New Drops Grid', visibility.new_drops_grid !== false)}
      ${cmsVisibilityToggle('cms-vis-bundles', 'Bundle Kits', visibility.bundle_kits !== false)}
      ${cmsVisibilityToggle('cms-vis-guides', 'Buying Guides', visibility.buying_guides !== false)}
      ${cmsVisibilityToggle('cms-vis-community', 'Community Gallery', visibility.community_gallery !== false)}
      ${cmsVisibilityToggle('cms-vis-subscription', 'Subscription CTA', visibility.subscription_cta !== false)}
      ${cmsVisibilityToggle('cms-vis-loyalty', 'Loyalty Tiers', visibility.loyalty_tiers !== false)}
      ${cmsVisibilityToggle('cms-vis-newsletter', 'Newsletter Section', visibility.newsletter_section !== false)}
      ${cmsVisibilityToggle('cms-vis-popup', 'Welcome Quiz Popup', visibility.welcome_quiz_popup !== false)}
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>New Drop Section — Product Slots</strong><span>Choose which 4 products appear in the New Drop grid</span></div>
      <div class="form-grid">
        <div class="form-group"><label>Slot 1</label><select id="cms-new-drop-1" class="form-input"><option value="">Choose a product</option>${cmsProductOptions((newDrops.product_ids || [])[0] || '')}</select></div>
        <div class="form-group"><label>Slot 2</label><select id="cms-new-drop-2" class="form-input"><option value="">Choose a product</option>${cmsProductOptions((newDrops.product_ids || [])[1] || '')}</select></div>
        <div class="form-group"><label>Slot 3</label><select id="cms-new-drop-3" class="form-input"><option value="">Choose a product</option>${cmsProductOptions((newDrops.product_ids || [])[2] || '')}</select></div>
        <div class="form-group"><label>Slot 4</label><select id="cms-new-drop-4" class="form-input"><option value="">Choose a product</option>${cmsProductOptions((newDrops.product_ids || [])[3] || '')}</select></div>
        <div class="form-group"><label>Section Eyebrow Label</label><input id="cms-new-drop-eyebrow" type="text" class="form-input" value="${escHtml(newDrops.eyebrow || '')}" /></div>
        <div class="form-group"><label>Section Title</label><input id="cms-new-drop-title" type="text" class="form-input" value="${escHtml(newDrops.title || '')}" /></div>
      </div>
    </div>
  `;

  pages.innerHTML = `
    <div class="content-block">
      <div class="content-block-header"><strong>FAQ Page</strong><span>Editable support content</span></div>
      <div id="faqItems">${(pageContent.faq || []).map(item => cmsFaqRow(item)).join('')}</div>
      <button class="action-btn edit" onclick="addFaqItem()" style="margin-top:4px;">+ Add FAQ</button>
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>Community Page</strong><span>Hero and share CTA</span></div>
      <div class="form-grid">
        <div class="form-group"><label>Hero Eyebrow</label><input id="cms-community-hero-eyebrow" class="form-input" value="${escHtml(community.hero_eyebrow || '')}" /></div>
        <div class="form-group"><label>Hero Title Line 1</label><input id="cms-community-hero-title-1" class="form-input" value="${escHtml(community.hero_title_line_1 || '')}" /></div>
        <div class="form-group"><label>Hero Title Line 2</label><input id="cms-community-hero-title-2" class="form-input" value="${escHtml(community.hero_title_line_2 || '')}" /></div>
        <div class="form-group"><label>Live Label</label><input id="cms-community-live-label" class="form-input" value="${escHtml(community.live_label || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Hero Description</label><textarea id="cms-community-hero-description" class="form-input" rows="3">${escHtml(community.hero_description || '')}</textarea></div>
        <div class="form-group"><label>Primary CTA Text</label><input id="cms-community-primary-cta" class="form-input" value="${escHtml(community.hero_primary_cta_text || '')}" /></div>
        <div class="form-group"><label>Secondary CTA Text</label><input id="cms-community-secondary-cta" class="form-input" value="${escHtml(community.hero_secondary_cta_text || '')}" /></div>
        <div class="form-group"><label>Stat 1 Value</label><input id="cms-community-stat-1-value" class="form-input" value="${escHtml((community.stats || [])[0]?.value || '')}" /></div>
        <div class="form-group"><label>Stat 1 Label</label><input id="cms-community-stat-1-label" class="form-input" value="${escHtml((community.stats || [])[0]?.label || '')}" /></div>
        <div class="form-group"><label>Stat 2 Value</label><input id="cms-community-stat-2-value" class="form-input" value="${escHtml((community.stats || [])[1]?.value || '')}" /></div>
        <div class="form-group"><label>Stat 2 Label</label><input id="cms-community-stat-2-label" class="form-input" value="${escHtml((community.stats || [])[1]?.label || '')}" /></div>
        <div class="form-group"><label>Stat 3 Value</label><input id="cms-community-stat-3-value" class="form-input" value="${escHtml((community.stats || [])[2]?.value || '')}" /></div>
        <div class="form-group"><label>Stat 3 Label</label><input id="cms-community-stat-3-label" class="form-input" value="${escHtml((community.stats || [])[2]?.label || '')}" /></div>
        <div class="form-group"><label>Share CTA Title</label><input id="cms-community-share-title" class="form-input" value="${escHtml(community.share_title || '')}" /></div>
        <div class="form-group"><label>Share CTA Button</label><input id="cms-community-share-button" class="form-input" value="${escHtml(community.share_button_text || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Share CTA Description</label><textarea id="cms-community-share-description" class="form-input" rows="2">${escHtml(community.share_description || '')}</textarea></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Share CTA Tags Text</label><input id="cms-community-share-tags" class="form-input" value="${escHtml(community.share_tags_text || '')}" /></div>
      </div>
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>Shop Page</strong><span>Hero, tabs and tab copy</span></div>
      <div class="form-grid">
        <div class="form-group"><label>Hero Eyebrow</label><input id="cms-shop-hero-eyebrow" class="form-input" value="${escHtml(shop.hero_eyebrow || '')}" /></div>
        <div class="form-group"><label>Hero CTA Text</label><input id="cms-shop-hero-cta" class="form-input" value="${escHtml(shop.hero_cta_text || '')}" /></div>
        <div class="form-group"><label>Hero Title Line 1</label><input id="cms-shop-hero-title-1" class="form-input" value="${escHtml(shop.hero_title_line_1 || '')}" /></div>
        <div class="form-group"><label>Hero Title Line 2</label><input id="cms-shop-hero-title-2" class="form-input" value="${escHtml(shop.hero_title_line_2 || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Hero Description</label><textarea id="cms-shop-hero-description" class="form-input" rows="2">${escHtml(shop.hero_description || '')}</textarea></div>
        <div class="form-group"><label>Tab: All</label><input id="cms-shop-tab-all" class="form-input" value="${escHtml(shop.tab_all || '')}" /></div>
        <div class="form-group"><label>Tab: Bundles</label><input id="cms-shop-tab-bundles" class="form-input" value="${escHtml(shop.tab_bundles || '')}" /></div>
        <div class="form-group"><label>Tab: Subscription</label><input id="cms-shop-tab-subscription" class="form-input" value="${escHtml(shop.tab_subscription || '')}" /></div>
        <div class="form-group"><label>Tab: New</label><input id="cms-shop-tab-new" class="form-input" value="${escHtml(shop.tab_new || '')}" /></div>
        <div class="form-group"><label>Tab: Sale</label><input id="cms-shop-tab-sale" class="form-input" value="${escHtml(shop.tab_sale || '')}" /></div>
        <div class="form-group"><label>Bundles Title</label><input id="cms-shop-bundles-title" class="form-input" value="${escHtml(shop.bundles_title || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Bundles Description</label><textarea id="cms-shop-bundles-description" class="form-input" rows="2">${escHtml(shop.bundles_description || '')}</textarea></div>
        <div class="form-group"><label>Bundles CTA Title</label><input id="cms-shop-bundles-cta-title" class="form-input" value="${escHtml(shop.bundles_cta_title || '')}" /></div>
        <div class="form-group"><label>Bundles CTA Button</label><input id="cms-shop-bundles-cta-button" class="form-input" value="${escHtml(shop.bundles_cta_button_text || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Bundles CTA Description</label><textarea id="cms-shop-bundles-cta-description" class="form-input" rows="2">${escHtml(shop.bundles_cta_description || '')}</textarea></div>
        <div class="form-group"><label>Subscription Eyebrow</label><input id="cms-shop-subscription-eyebrow" class="form-input" value="${escHtml(shop.subscription_eyebrow || '')}" /></div>
        <div class="form-group"><label>Subscription Title</label><input id="cms-shop-subscription-title" class="form-input" value="${escHtml(shop.subscription_title || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Subscription Description</label><textarea id="cms-shop-subscription-description" class="form-input" rows="2">${escHtml(shop.subscription_description || '')}</textarea></div>
        <div class="form-group"><label>New Tab Title</label><input id="cms-shop-new-title" class="form-input" value="${escHtml(shop.new_title || '')}" /></div>
        <div class="form-group"><label>Sale Tab Title</label><input id="cms-shop-sale-title" class="form-input" value="${escHtml(shop.sale_title || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>New Tab Description</label><textarea id="cms-shop-new-description" class="form-input" rows="2">${escHtml(shop.new_description || '')}</textarea></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Sale Tab Description</label><textarea id="cms-shop-sale-description" class="form-input" rows="2">${escHtml(shop.sale_description || '')}</textarea></div>
      </div>
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>Auth Pages</strong><span>Login, signup and checkout</span></div>
      <div class="form-grid">
        <div class="form-group"><label>Login Eyebrow</label><input id="cms-login-eyebrow" class="form-input" value="${escHtml(loginPage.brand_eyebrow || '')}" /></div>
        <div class="form-group"><label>Login Form Title</label><input id="cms-login-form-title" class="form-input" value="${escHtml(loginPage.form_title || '')}" /></div>
        <div class="form-group"><label>Login Hero Title Line 1</label><input id="cms-login-title-1" class="form-input" value="${escHtml(loginPage.brand_title_line_1 || '')}" /></div>
        <div class="form-group"><label>Login Hero Title Line 2</label><input id="cms-login-title-2" class="form-input" value="${escHtml(loginPage.brand_title_line_2 || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Login Description</label><textarea id="cms-login-description" class="form-input" rows="2">${escHtml(loginPage.brand_description || '')}</textarea></div>
        <div class="form-group"><label>Login Form Subtitle</label><input id="cms-login-form-subtitle" class="form-input" value="${escHtml(loginPage.form_subtitle || '')}" /></div>
        <div class="form-group"><label>Login Button Text</label><input id="cms-login-submit" class="form-input" value="${escHtml(loginPage.submit_text || '')}" /></div>
        <div class="form-group"><label>Signup Eyebrow</label><input id="cms-signup-eyebrow" class="form-input" value="${escHtml(signupPage.brand_eyebrow || '')}" /></div>
        <div class="form-group"><label>Signup Hero Title</label><input id="cms-signup-title" class="form-input" value="${escHtml(signupPage.brand_title || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Signup Description</label><textarea id="cms-signup-description" class="form-input" rows="2">${escHtml(signupPage.brand_description || '')}</textarea></div>
        <div class="form-group"><label>Signup Form Title</label><input id="cms-signup-form-title" class="form-input" value="${escHtml(signupPage.form_title || '')}" /></div>
        <div class="form-group"><label>Signup Form Subtitle</label><input id="cms-signup-form-subtitle" class="form-input" value="${escHtml(signupPage.form_subtitle || '')}" /></div>
        <div class="form-group"><label>Signup Button Text</label><input id="cms-signup-submit" class="form-input" value="${escHtml(signupPage.submit_text || '')}" /></div>
        <div class="form-group"><label>Perk 1 Title</label><input id="cms-signup-perk1-title" class="form-input" value="${escHtml(signupPage.perk_1_title || '')}" /></div>
        <div class="form-group"><label>Perk 2 Title</label><input id="cms-signup-perk2-title" class="form-input" value="${escHtml(signupPage.perk_2_title || '')}" /></div>
        <div class="form-group"><label>Perk 3 Title</label><input id="cms-signup-perk3-title" class="form-input" value="${escHtml(signupPage.perk_3_title || '')}" /></div>
        <div class="form-group"><label>Perk 4 Title</label><input id="cms-signup-perk4-title" class="form-input" value="${escHtml(signupPage.perk_4_title || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Perk 1 Description</label><textarea id="cms-signup-perk1-description" class="form-input" rows="2">${escHtml(signupPage.perk_1_description || '')}</textarea></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Perk 2 Description</label><textarea id="cms-signup-perk2-description" class="form-input" rows="2">${escHtml(signupPage.perk_2_description || '')}</textarea></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Perk 3 Description</label><textarea id="cms-signup-perk3-description" class="form-input" rows="2">${escHtml(signupPage.perk_3_description || '')}</textarea></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Perk 4 Description</label><textarea id="cms-signup-perk4-description" class="form-input" rows="2">${escHtml(signupPage.perk_4_description || '')}</textarea></div>
        <div class="form-group"><label>Checkout Page Title</label><input id="cms-checkout-title" class="form-input" value="${escHtml(checkoutPage.page_title || '')}" /></div>
        <div class="form-group"><label>Checkout Summary Title</label><input id="cms-checkout-summary-title" class="form-input" value="${escHtml(checkoutPage.summary_title || '')}" /></div>
        <div class="form-group"><label>Shipping Section Title</label><input id="cms-checkout-shipping-title" class="form-input" value="${escHtml(checkoutPage.shipping_title || '')}" /></div>
        <div class="form-group"><label>Payment Section Title</label><input id="cms-checkout-payment-title" class="form-input" value="${escHtml(checkoutPage.payment_title || '')}" /></div>
        <div class="form-group"><label>Notes Section Title</label><input id="cms-checkout-notes-title" class="form-input" value="${escHtml(checkoutPage.notes_title || '')}" /></div>
        <div class="form-group"><label>Notes Optional Label</label><input id="cms-checkout-notes-optional" class="form-input" value="${escHtml(checkoutPage.notes_optional_label || '')}" /></div>
        <div class="form-group"><label>Place Order Button</label><input id="cms-checkout-place-order" class="form-input" value="${escHtml(checkoutPage.place_order_text || '')}" /></div>
        <div class="form-group"><label>Secure Checkout Text</label><input id="cms-checkout-secure" class="form-input" value="${escHtml(checkoutPage.secure_checkout_text || '')}" /></div>
        <div class="form-group"><label>Authentic Text</label><input id="cms-checkout-authentic" class="form-input" value="${escHtml(checkoutPage.authentic_text || '')}" /></div>
      </div>
    </div>
    <div class="content-block">
      <div class="content-block-header"><strong>Results Page</strong><span>Quiz results hero and CTA copy</span></div>
      <div class="form-grid">
        <div class="form-group"><label>Hero Badge</label><input id="cms-results-hero-badge" class="form-input" value="${escHtml(resultsPage.hero_badge || '')}" /></div>
        <div class="form-group"><label>Hero Greeting Prefix</label><input id="cms-results-greeting-prefix" class="form-input" value="${escHtml(resultsPage.hero_greeting_prefix || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Hero Description</label><textarea id="cms-results-hero-description" class="form-input" rows="2">${escHtml(resultsPage.hero_description || '')}</textarea></div>
        <div class="form-group"><label>Paths Eyebrow</label><input id="cms-results-paths-eyebrow" class="form-input" value="${escHtml(resultsPage.paths_eyebrow || '')}" /></div>
        <div class="form-group"><label>Paths Title</label><input id="cms-results-paths-title" class="form-input" value="${escHtml(resultsPage.paths_title || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Paths Description</label><textarea id="cms-results-paths-description" class="form-input" rows="2">${escHtml(resultsPage.paths_description || '')}</textarea></div>
        <div class="form-group"><label>Tips Eyebrow</label><input id="cms-results-tips-eyebrow" class="form-input" value="${escHtml(resultsPage.tips_eyebrow || '')}" /></div>
        <div class="form-group"><label>Tips Title</label><input id="cms-results-tips-title" class="form-input" value="${escHtml(resultsPage.tips_title || '')}" /></div>
        <div class="form-group"><label>Sticky Bar Title</label><input id="cms-results-sticky-title" class="form-input" value="${escHtml(resultsPage.sticky_title || '')}" /></div>
        <div class="form-group"><label>Sticky Save Button</label><input id="cms-results-sticky-save" class="form-input" value="${escHtml(resultsPage.sticky_save_text || '')}" /></div>
        <div class="form-group"><label>Sticky Add Button</label><input id="cms-results-sticky-add" class="form-input" value="${escHtml(resultsPage.sticky_add_text || '')}" /></div>
        <div class="form-group" style="grid-column:1/-1;"><label>Sticky Description</label><textarea id="cms-results-sticky-description" class="form-input" rows="2">${escHtml(resultsPage.sticky_description || '')}</textarea></div>
      </div>
    </div>
    <div class="content-block"><div class="content-block-header"><strong>Shipping Policy</strong><span>Shown on frontend help pages</span></div><div class="form-group"><label>Policy Content</label><textarea id="cms-shipping-policy" class="form-input" rows="6" style="resize:vertical;">${escHtml(pageContent.shipping_policy || '')}</textarea></div></div>
    <div class="content-block"><div class="content-block-header"><strong>Returns & Exchanges</strong><span>Shown on frontend help pages</span></div><div class="form-group"><label>Policy Content</label><textarea id="cms-returns-policy" class="form-input" rows="4" style="resize:vertical;">${escHtml(pageContent.returns_policy || '')}</textarea></div></div>
  `;

  mediaPanel.innerHTML = `
    <div class="content-block">
      <div class="content-block-header">
        <strong>Media Library</strong>
        <span>${mediaLibrary.length} assets</span>
      </div>
      <div style="font-size:.78rem;color:rgba(10,10,10,.55);margin-bottom:14px;">
        Add or remove media and assign each one to a frontend page slot.
      </div>

      <div style="border:1.5px dashed #e8eaed;border-radius:12px;padding:12px;margin-bottom:12px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
          <div>
            <strong style="font-size:.95rem;">Upload Media</strong>
            <div style="font-size:.75rem;color:rgba(10,10,10,.45);">Local storage upload — max 5MB</div>
          </div>
          <div style="display:flex;gap:8px;align-items:center;">
            <input type="file" id="cms-upload-file" accept="image/*,video/*,audio/*" style="font-size:.9rem;" />
            <button class="action-btn primary" type="button" onclick="uploadCmsMedia()">Upload</button>
          </div>
        </div>
        <div id="cms-upload-preview" style="margin-top:8px;max-width:240px;"></div>
        <div id="cms-upload-progress" style="height:6px;background:#e8eaed;border-radius:6px;overflow:hidden;display:none;margin-top:8px;"><div id="cms-upload-progress-bar" style="height:100%;width:0;background:var(--lime);"></div></div>
        <div id="cms-upload-error" style="color:#c2410c;font-size:.86rem;margin-top:6px;display:none;"></div>
      </div>

      <div id="cms-media-library-list">
        ${mediaLibrary.map(item => cmsMediaRow(item)).join('')}
      </div>
      <button class="action-btn edit" type="button" onclick="addMediaLibraryRow()">+ Add Media Item</button>
      <div style="margin-top:12px;font-size:.73rem;color:rgba(10,10,10,.42);">
        Supported slots: home why/community cards, and community hero/featured images.
      </div>
    </div>
  `;
}

function collectCmsContentPayload() {
  return {
    announcement_bar: {
      visible: document.getElementById('cms-ann-visible')?.checked !== false,
      speed: document.getElementById('cms-ann-speed')?.value || 'normal',
      items: Array.from(document.querySelectorAll('#annItemList .ann-item')).map(item => ({
        emoji: item.querySelector('.ann-emoji-pick')?.value || '',
        text: item.querySelector('.ann-text-field')?.value || '',
        link: item.querySelector('.ann-link-field')?.value || '',
      })).filter(item => item.text.trim()),
    },
    hero: {
      visible: document.getElementById('cms-hero-visible')?.checked !== false,
      eyebrow: document.getElementById('cms-hero-eyebrow')?.value || '',
      title_line_1: document.getElementById('cms-hero-line1')?.value || '',
      title_line_2: document.getElementById('cms-hero-line2')?.value || '',
      title_line_3: document.getElementById('cms-hero-line3')?.value || '',
      description: document.getElementById('cms-hero-description')?.value || '',
      primary_cta_text: document.getElementById('cms-hero-primary-text')?.value || '',
      primary_cta_link: document.getElementById('cms-hero-primary-link')?.value || '',
      secondary_cta_text: document.getElementById('cms-hero-secondary-text')?.value || '',
      secondary_cta_link: document.getElementById('cms-hero-secondary-link')?.value || '',
      image_url: document.getElementById('cms-hero-image')?.value || '',
    },
    deal_of_the_day: {
      visible: document.getElementById('cms-deal-visible')?.checked !== false,
      show_countdown: document.getElementById('cms-deal-countdown')?.checked !== false,
      product_id: document.getElementById('cms-deal-product')?.value || null,
      badge: document.getElementById('cms-deal-badge')?.value || '',
      headline: document.getElementById('cms-deal-headline')?.value || '',
      description: document.getElementById('cms-deal-description')?.value || '',
      deal_price: Number(document.getElementById('cms-deal-price')?.value || 0),
      original_price: Number(document.getElementById('cms-deal-original-price')?.value || 0),
      units_remaining: Number(document.getElementById('cms-deal-units')?.value || 0),
    },
    section_visibility: {
      quiz_cta_banner: document.getElementById('cms-vis-quiz')?.checked !== false,
      recommended_for_you: document.getElementById('cms-vis-recommended')?.checked !== false,
      new_drops_grid: document.getElementById('cms-vis-new-drops')?.checked !== false,
      bundle_kits: document.getElementById('cms-vis-bundles')?.checked !== false,
      buying_guides: document.getElementById('cms-vis-guides')?.checked !== false,
      community_gallery: document.getElementById('cms-vis-community')?.checked !== false,
      subscription_cta: document.getElementById('cms-vis-subscription')?.checked !== false,
      loyalty_tiers: document.getElementById('cms-vis-loyalty')?.checked !== false,
      newsletter_section: document.getElementById('cms-vis-newsletter')?.checked !== false,
      welcome_quiz_popup: document.getElementById('cms-vis-popup')?.checked !== false,
    },
    new_drops: {
      eyebrow: document.getElementById('cms-new-drop-eyebrow')?.value || '',
      title: document.getElementById('cms-new-drop-title')?.value || '',
      product_ids: ['cms-new-drop-1', 'cms-new-drop-2', 'cms-new-drop-3', 'cms-new-drop-4'].map(id => document.getElementById(id)?.value || '').filter(Boolean),
    },
    subscription_section: {
      kicker:      document.getElementById('sub-cms-kicker')?.value      || (CMS_CONTENT_DATA.subscription_section?.kicker      || ''),
      heading:     document.getElementById('sub-cms-heading')?.value     || (CMS_CONTENT_DATA.subscription_section?.heading     || ''),
      description: document.getElementById('sub-cms-description')?.value || (CMS_CONTENT_DATA.subscription_section?.description || ''),
    },
    loyalty_section: {
      kicker:      document.getElementById('loyalty-cms-kicker')?.value      || (CMS_CONTENT_DATA.loyalty_section?.kicker      || ''),
      heading:     document.getElementById('loyalty-cms-heading')?.value     || (CMS_CONTENT_DATA.loyalty_section?.heading     || ''),
      description: document.getElementById('loyalty-cms-description')?.value || (CMS_CONTENT_DATA.loyalty_section?.description || ''),
    },
    media: {
      library: Array.from(document.querySelectorAll('#cms-media-library-list .media-library-row')).map((row, index) => ({
        id: row.dataset.mediaId || `media-${Date.now()}-${index}`,
        page: row.querySelector('.media-page')?.value || 'home',
        slot: row.querySelector('.media-slot')?.value || '',
        url: row.querySelector('.media-url')?.value || '',
        alt: row.querySelector('.media-alt')?.value || '',
        enabled: row.querySelector('.media-enabled')?.checked !== false,
      })).filter(item => item.url.trim() && item.slot.trim()),
    },
    pages: {
      faq: Array.from(document.querySelectorAll('#faqItems > div')).map(item => ({
        question: item.querySelector('input')?.value || '',
        answer: item.querySelector('textarea')?.value || '',
      })).filter(item => item.question.trim() || item.answer.trim()),
      shipping_policy: document.getElementById('cms-shipping-policy')?.value || '',
      returns_policy: document.getElementById('cms-returns-policy')?.value || '',
      community: {
        hero_eyebrow: document.getElementById('cms-community-hero-eyebrow')?.value || '',
        hero_title_line_1: document.getElementById('cms-community-hero-title-1')?.value || '',
        hero_title_line_2: document.getElementById('cms-community-hero-title-2')?.value || '',
        hero_description: document.getElementById('cms-community-hero-description')?.value || '',
        hero_primary_cta_text: document.getElementById('cms-community-primary-cta')?.value || '',
        hero_secondary_cta_text: document.getElementById('cms-community-secondary-cta')?.value || '',
        live_label: document.getElementById('cms-community-live-label')?.value || '',
        stats: [
          { value: document.getElementById('cms-community-stat-1-value')?.value || '', label: document.getElementById('cms-community-stat-1-label')?.value || '' },
          { value: document.getElementById('cms-community-stat-2-value')?.value || '', label: document.getElementById('cms-community-stat-2-label')?.value || '' },
          { value: document.getElementById('cms-community-stat-3-value')?.value || '', label: document.getElementById('cms-community-stat-3-label')?.value || '' },
        ],
        share_title: document.getElementById('cms-community-share-title')?.value || '',
        share_description: document.getElementById('cms-community-share-description')?.value || '',
        share_button_text: document.getElementById('cms-community-share-button')?.value || '',
        share_tags_text: document.getElementById('cms-community-share-tags')?.value || '',
      },
      shop: {
        hero_eyebrow: document.getElementById('cms-shop-hero-eyebrow')?.value || '',
        hero_title_line_1: document.getElementById('cms-shop-hero-title-1')?.value || '',
        hero_title_line_2: document.getElementById('cms-shop-hero-title-2')?.value || '',
        hero_description: document.getElementById('cms-shop-hero-description')?.value || '',
        hero_cta_text: document.getElementById('cms-shop-hero-cta')?.value || '',
        tab_all: document.getElementById('cms-shop-tab-all')?.value || '',
        tab_bundles: document.getElementById('cms-shop-tab-bundles')?.value || '',
        tab_subscription: document.getElementById('cms-shop-tab-subscription')?.value || '',
        tab_new: document.getElementById('cms-shop-tab-new')?.value || '',
        tab_sale: document.getElementById('cms-shop-tab-sale')?.value || '',
        bundles_title: document.getElementById('cms-shop-bundles-title')?.value || '',
        bundles_description: document.getElementById('cms-shop-bundles-description')?.value || '',
        bundles_cta_title: document.getElementById('cms-shop-bundles-cta-title')?.value || '',
        bundles_cta_description: document.getElementById('cms-shop-bundles-cta-description')?.value || '',
        bundles_cta_button_text: document.getElementById('cms-shop-bundles-cta-button')?.value || '',
        subscription_eyebrow: document.getElementById('cms-shop-subscription-eyebrow')?.value || '',
        subscription_title: document.getElementById('cms-shop-subscription-title')?.value || '',
        subscription_description: document.getElementById('cms-shop-subscription-description')?.value || '',
        new_title: document.getElementById('cms-shop-new-title')?.value || '',
        new_description: document.getElementById('cms-shop-new-description')?.value || '',
        sale_title: document.getElementById('cms-shop-sale-title')?.value || '',
        sale_description: document.getElementById('cms-shop-sale-description')?.value || '',
      },
      login: {
        brand_eyebrow: document.getElementById('cms-login-eyebrow')?.value || '',
        brand_title_line_1: document.getElementById('cms-login-title-1')?.value || '',
        brand_title_line_2: document.getElementById('cms-login-title-2')?.value || '',
        brand_description: document.getElementById('cms-login-description')?.value || '',
        form_title: document.getElementById('cms-login-form-title')?.value || '',
        form_subtitle: document.getElementById('cms-login-form-subtitle')?.value || '',
        submit_text: document.getElementById('cms-login-submit')?.value || '',
      },
      signup: {
        brand_eyebrow: document.getElementById('cms-signup-eyebrow')?.value || '',
        brand_title: document.getElementById('cms-signup-title')?.value || '',
        brand_description: document.getElementById('cms-signup-description')?.value || '',
        perk_1_title: document.getElementById('cms-signup-perk1-title')?.value || '',
        perk_1_description: document.getElementById('cms-signup-perk1-description')?.value || '',
        perk_2_title: document.getElementById('cms-signup-perk2-title')?.value || '',
        perk_2_description: document.getElementById('cms-signup-perk2-description')?.value || '',
        perk_3_title: document.getElementById('cms-signup-perk3-title')?.value || '',
        perk_3_description: document.getElementById('cms-signup-perk3-description')?.value || '',
        perk_4_title: document.getElementById('cms-signup-perk4-title')?.value || '',
        perk_4_description: document.getElementById('cms-signup-perk4-description')?.value || '',
        form_title: document.getElementById('cms-signup-form-title')?.value || '',
        form_subtitle: document.getElementById('cms-signup-form-subtitle')?.value || '',
        submit_text: document.getElementById('cms-signup-submit')?.value || '',
      },
      checkout: {
        page_title: document.getElementById('cms-checkout-title')?.value || '',
        shipping_title: document.getElementById('cms-checkout-shipping-title')?.value || '',
        payment_title: document.getElementById('cms-checkout-payment-title')?.value || '',
        notes_title: document.getElementById('cms-checkout-notes-title')?.value || '',
        notes_optional_label: document.getElementById('cms-checkout-notes-optional')?.value || '',
        summary_title: document.getElementById('cms-checkout-summary-title')?.value || '',
        place_order_text: document.getElementById('cms-checkout-place-order')?.value || '',
        secure_checkout_text: document.getElementById('cms-checkout-secure')?.value || '',
        authentic_text: document.getElementById('cms-checkout-authentic')?.value || '',
      },
      results: {
        hero_badge: document.getElementById('cms-results-hero-badge')?.value || '',
        hero_greeting_prefix: document.getElementById('cms-results-greeting-prefix')?.value || '',
        hero_description: document.getElementById('cms-results-hero-description')?.value || '',
        paths_eyebrow: document.getElementById('cms-results-paths-eyebrow')?.value || '',
        paths_title: document.getElementById('cms-results-paths-title')?.value || '',
        paths_description: document.getElementById('cms-results-paths-description')?.value || '',
        tips_eyebrow: document.getElementById('cms-results-tips-eyebrow')?.value || '',
        tips_title: document.getElementById('cms-results-tips-title')?.value || '',
        sticky_title: document.getElementById('cms-results-sticky-title')?.value || '',
        sticky_description: document.getElementById('cms-results-sticky-description')?.value || '',
        sticky_save_text: document.getElementById('cms-results-sticky-save')?.value || '',
        sticky_add_text: document.getElementById('cms-results-sticky-add')?.value || '',
      },
    },
  };
}

async function saveCmsContent() {
  try {
    const response = await fetch(CMS_CONTENT_ROUTES.saveContent, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CMS_CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ content: collectCmsContentPayload() }),
    });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`Save failed (${response.status}): ${errorText.slice(0, 180)}`);
    }
    const data = await response.json();
    Object.assign(CMS_CONTENT_DATA, data.content || {});
    showToast('🚀', 'CMS changes published to the frontend.');
  } catch (error) {
    console.error(error);
    showToast('⚠️', error.message || 'Could not save CMS changes.');
  }
}

// ── User modal tab switcher ───────────────────────────
function switchUserModalTab(el, targetId) {
  const modal = el.closest('.add-modal');
  modal.querySelectorAll('.user-modal-tab').forEach(t => t.classList.remove('active'));
  modal.querySelectorAll('.user-modal-tab-content').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  const target = document.getElementById(targetId);
  if (target) target.classList.add('active');
}

// ── Manage Box modal ──────────────────────────────────
function openManageBoxModal(customerName) {
  const sub = document.getElementById('manageBoxSubtitle');
  if (sub) sub.textContent = customerName + ' · Upcoming Box · May 2026';
  document.getElementById('manageBoxOverlay').classList.add('open');
}

// ── Routine Editor: add step ──────────────────────────
function addRoutineStep() {
  const list = document.getElementById('routineStepsList');
  if (!list) return;
  const num = list.querySelectorAll('.routine-step-edit-row').length + 1;
  const div = document.createElement('div');
  div.className = 'routine-step-edit-row';
  div.style.cssText = 'display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px solid #f4f5f7;';
  div.innerHTML = '<span style="font-size:.75rem;font-weight:700;color:rgba(10,10,10,.35);width:20px;text-align:center;">' + num + '</span><select class="form-input" style="width:140px;font-size:.82rem;padding:7px 10px;flex-shrink:0;"><option>Cleanser</option><option>Toner</option><option>Serum</option><option>Moisturiser</option><option>Eye Cream</option><option>SPF</option><option>Treatment</option><option>Mask</option></select><input type="text" class="form-input" style="flex:1;" placeholder="Product name or \'AI choose\'…" /><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest(\'.routine-step-edit-row\').remove()">🗑️</button>';
  list.appendChild(div);
  div.querySelector('input').focus();
}

// ── Loyalty Leaderboard ───────────────────────────────
// Build LOYALTY_DATA from real backend users, sorted by points descending
const LOYALTY_DATA = (function() {
  const tierMeta = {
    iconic:  { emoji: '👑', label: 'Luxe Luminary',   bg: '#d1fae5', col: '#065f46' },
    radiant: { emoji: '💎', label: 'Radiant Insider',  bg: '#dbeafe', col: '#1e40af' },
    glow:    { emoji: '✨', label: 'Glow Starter',     bg: '#fef3c7', col: '#92400e' },
    starter: { emoji: '🌱', label: 'Glow Starter',     bg: '#f0f2f4', col: 'rgba(10,10,10,.6)' },
  };
  const colors = ['#d1fae5','#dbeafe','#ede9fe','#fef3c7','#fee2e2','#f0f2f4'];
  const cols   = ['#065f46','#1e40af','#5b21b6','#92400e','#991b1b','rgba(10,10,10,.6)'];
  const users  = @json(collect($adminUsers)->sortByDesc('loyalty_points')->values()->take(10)->all());
  return users.map((u, i) => {
    const tier = (u.tier || 'starter').toLowerCase();
    const meta = tierMeta[tier] || tierMeta['starter'];
    const name = u.name || 'User';
    const words = name.trim().split(/\s+/);
    const initials = words.length >= 2 ? words[0][0] + words[words.length - 1][0] : name.slice(0, 2).toUpperCase();
    return {
      name, initials: initials.toUpperCase(),
      email: u.email || '',
      pts:   u.loyalty_points || 0,
      tier:  tier === 'radiant' ? 'insider' : (tier === 'iconic' ? 'luminary' : tier),
      tierLabel: meta.emoji + ' ' + meta.label,
      bg:    colors[i % colors.length] || '#f0f2f4',
      col:   cols[i % cols.length]   || 'rgba(10,10,10,.6)',
    };
  });
})();

function buildLoyaltyLeaderboard(data) {
  const container = document.getElementById('loyaltyLeaderboard');
  if (!container) return;
  const rankClass = ['gold','silver','bronze'];
  const rankEmoji = ['🥇','🥈','🥉'];
  container.innerHTML = (data || LOYALTY_DATA).map((u, i) =>
    '<div class="loyalty-leaderboard-row"><div class="loyalty-rank ' + (rankClass[i] || '') + '">' + (i < 3 ? rankEmoji[i] : (i+1)) + '</div><div style="width:36px;height:36px;border-radius:50%;background:' + u.bg + ';color:' + u.col + ';display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.82rem;flex-shrink:0;">' + u.initials + '</div><div class="loyalty-user-info"><strong>' + u.name + '</strong><span>' + u.email + ' &nbsp;·&nbsp; <span class="loyalty-tier-chip ' + u.tier + '">' + u.tierLabel + '</span></span></div><div class="loyalty-pts-adj"><button class="pts-adj-btn minus" title="Deduct points" onclick="adjustPoints(' + i + ',-100)">−</button><span class="loyalty-pts" id="loyalty-pts-' + i + '">' + u.pts.toLocaleString() + ' pts</span><button class="pts-adj-btn plus" title="Award points" onclick="adjustPoints(' + i + ',+100)">+</button></div></div>'
  ).join('');
}

function adjustPoints(idx, delta) {
  LOYALTY_DATA[idx].pts = Math.max(0, LOYALTY_DATA[idx].pts + delta);
  const el = document.getElementById('loyalty-pts-' + idx);
  if (el) el.textContent = LOYALTY_DATA[idx].pts.toLocaleString() + ' pts';
  showToast(delta > 0 ? '⭐' : '➖', (delta > 0 ? '+' + delta : delta) + ' pts for ' + LOYALTY_DATA[idx].name);
}

function filterLoyaltyLeaderboard(q) {
  if (typeof LOYALTY_DATA !== 'undefined') {
    const filtered = LOYALTY_DATA.filter(u => u.name.toLowerCase().includes(q.toLowerCase()) || u.email.toLowerCase().includes(q.toLowerCase()));
    buildLoyaltyLeaderboard(filtered);
  }
}

// ─── Admin: Members ────────────────────────────────────────────────────────
function filterMemberTable(q) {
  const rows = document.querySelectorAll('#member-table tbody tr[data-name]');
  const lq   = q.toLowerCase();
  rows.forEach(row => {
    const match = row.dataset.name.includes(lq) || row.dataset.email.includes(lq);
    row.style.display = match ? '' : 'none';
  });
}
function filterMemberTier(tier) {
  const rows = document.querySelectorAll('#member-table tbody tr[data-tier]');
  rows.forEach(row => { row.style.display = (!tier || row.dataset.tier === tier) ? '' : 'none'; });
}
function adminAwardPoints(userId, userName, direction) {
  document.getElementById('award-user-id').value   = userId;
  document.getElementById('award-user-name').textContent = userName;
  document.getElementById('award-pts-value').value = direction > 0 ? '100' : '-100';
  document.getElementById('award-pts-modal').classList.add('open');
}
function submitAwardPoints() {
  const userId = document.getElementById('award-user-id').value;
  const pts    = parseInt(document.getElementById('award-pts-value').value || '0');
  const note   = document.getElementById('award-pts-note').value;
  if (!pts) { showToast('⚠️', 'Enter a non-zero point value.'); return; }
  fetch(ADMIN_URL + '/loyalty/award', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify({ user_id: userId, points: pts, note }),
  }).then(r => r.json()).then(d => {
    if (d.success || d.data) {
      showToast('⭐', (pts > 0 ? '+' : '') + pts + ' pts awarded!');
      document.getElementById('award-pts-modal').classList.remove('open');
      setTimeout(() => location.reload(), 1200);
    } else { showToast('⚠️', d.message || 'Failed to award points.'); }
  }).catch(() => showToast('⚠️', 'Request failed.'));
}

// ─── Admin: Notifications ─────────────────────────────────────────────────
function adminSendNotifModal() {
  switchAdminPanel('loyalty', null);
  setTimeout(() => switchTab(document.querySelector('[onclick*="ltab-notif"]'), 'ltab-notif'), 100);
}
function toggleNotifUserSelect(val) {
  document.getElementById('notif-user-select-wrap').style.display = val === 'single' ? '' : 'none';
}
function adminUserNotif(userId, userName) {
  switchAdminPanel('loyalty', null);
  setTimeout(() => {
    switchTab(document.querySelector('[onclick*="ltab-notif"]'), 'ltab-notif');
    document.getElementById('notif-recipient-type').value = 'single';
    toggleNotifUserSelect('single');
    const sel = document.getElementById('notif-user-id');
    if (sel) sel.value = userId;
  }, 100);
}
function adminSendNotification() {
  const recipType = document.getElementById('notif-recipient-type').value;
  const userId    = recipType === 'single' ? document.getElementById('notif-user-id').value : null;
  const type      = document.getElementById('notif-type').value;
  const title     = document.getElementById('notif-title').value.trim();
  const message   = document.getElementById('notif-message').value.trim();
  if (!title || !message) { showToast('⚠️', 'Please fill in title and message.'); return; }
  const body = { type, title, message };
  if (userId) body.user_id = parseInt(userId);
  fetch(ADMIN_URL + '/notifications/send', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify(body),
  }).then(r => r.json()).then(d => {
    if (d.success) {
      showToast('📢', `Notification sent to ${d.sent_to || (userId ? '1 member' : 'all members')}!`);
      document.getElementById('notif-title').value   = '';
      document.getElementById('notif-message').value = '';
    } else { showToast('⚠️', d.message || 'Failed to send.'); }
  }).catch(() => showToast('⚠️', 'Request failed.'));
}

// ─── Admin: Tier Config Save ──────────────────────────────────────────────
function saveLoyaltyTierConfig() {
  const tiers = [];
  document.querySelectorAll('#tier-config-form .content-block').forEach(block => {
    const idx    = block.dataset.tierIdx;
    const get    = (name) => block.querySelector(`[name="tiers[${idx}][${name}]"]`)?.value;
    const getGift= (f) => block.querySelector(`[name="tiers[${idx}][gift][${f}]"]`)?.value;
    const existing = @json($loyaltyConfig['tiers'] ?? []);
    const orig   = existing[idx] || {};
    const benefitsRaw = block.querySelector(`[name="tiers[${idx}][benefits]"]`)?.value || '';
    const benefits    = benefitsRaw.split('\n').map(s => s.trim()).filter(Boolean);
    const isPopularEl = block.querySelector(`[name="tiers[${idx}][is_popular]"]`);
    const tier = { ...orig, name: get('name'), min_points: parseInt(get('min_points')||0), multiplier: parseFloat(get('multiplier')||1), color: get('color'), benefits, is_popular: isPopularEl ? isPopularEl.checked : (orig.is_popular || false) };
    if (getGift('name')) tier.gift = { name: getGift('name'), value: parseInt(getGift('value')||0), description: getGift('description') };
    tiers.push(tier);
  });
  const existing = @json($loyaltyConfig ?? []);
  const config   = { ...existing, tiers };
  fetch(ADMIN_URL + '/loyalty/config', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify({ config }),
  }).then(r => r.json()).then(d => {
    if (d.success) { showToast('✓', 'Tier configuration saved!'); }
    else { showToast('⚠️', d.message || 'Failed to save.'); }
  }).catch(() => showToast('⚠️', 'Request failed.'));
}

function deleteLoyaltyTier(btn) {
  const block = btn.closest('.content-block');
  const tierName = block.querySelector('[name$="[name]"]')?.value || 'this tier';
  const remaining = document.querySelectorAll('#tier-config-form .content-block').length;
  if (remaining <= 1) { showToast('⚠️', 'You must keep at least one tier.'); return; }
  if (!confirm(`Delete "${tierName}"? This will remove it from the loyalty program and update the home page.`)) return;
  block.remove();
  saveLoyaltyTierConfig();
}

function savePointEventsConfig() {
  const existing = @json($loyaltyConfig ?? []);
  const evts     = { ...(existing.point_events || {}) };
  document.querySelectorAll('#point-events-table input[data-event]').forEach(input => {
    const evt   = input.dataset.event;
    const field = input.dataset.field;
    if (!evts[evt]) evts[evt] = {};
    evts[evt][field] = parseInt(input.value || 0);
  });
  const config = { ...existing, point_events: evts };
  fetch(ADMIN_URL + '/loyalty/config', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify({ config }),
  }).then(r => r.json()).then(d => {
    if (d.success) { showToast('✓', 'Point events configuration saved!'); }
    else { showToast('⚠️', d.message || 'Failed to save.'); }
  }).catch(() => showToast('⚠️', 'Request failed.'));
}

// ─── Home Page Section CMS saves ──────────────────────────────────────────
async function saveSubSectionCms() {
  const payload = {
    subscription_section: {
      kicker:      document.getElementById('sub-cms-kicker')?.value      || '',
      heading:     document.getElementById('sub-cms-heading')?.value     || '',
      description: document.getElementById('sub-cms-description')?.value || '',
    },
  };
  try {
    const r = await fetch(CMS_CONTENT_ROUTES.saveContent, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CMS_CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({ content: payload }),
    });
    if (!r.ok) throw new Error('Save failed');
    showToast('✅', 'Subscription section updated on home page.');
  } catch { showToast('⚠️', 'Could not save subscription section text.'); }
}

async function saveLoyaltySectionCms() {
  const payload = {
    loyalty_section: {
      kicker:      document.getElementById('loyalty-cms-kicker')?.value      || '',
      heading:     document.getElementById('loyalty-cms-heading')?.value     || '',
      description: document.getElementById('loyalty-cms-description')?.value || '',
    },
  };
  try {
    const r = await fetch(CMS_CONTENT_ROUTES.saveContent, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CMS_CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({ content: payload }),
    });
    if (!r.ok) throw new Error('Save failed');
    showToast('✅', 'Loyalty section updated on home page.');
  } catch { showToast('⚠️', 'Could not save loyalty section text.'); }
}

// ─── Admin: Subscription Plans ────────────────────────────────────────────
function openCreatePlanModal() {
  document.getElementById('plan-modal-title').textContent = 'New Subscription Plan';
  document.getElementById('plan-modal-editing-id').value = '';
  ['pm-id','pm-name','pm-price','pm-count','pm-desc','pm-features'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
  document.getElementById('pm-cycle').value  = 'monthly';
  document.getElementById('pm-tier').value   = '';
  document.getElementById('pm-popular').checked = false;
  document.getElementById('pm-active').checked  = true;
  document.getElementById('plan-modal').classList.add('open');
}
function editPlan(planId) {
  const plans = @json($subscriptionPlans ?? []);
  const plan  = plans.find(p => p.id === planId);
  if (!plan) { showToast('⚠️', 'Plan not found.'); return; }
  document.getElementById('plan-modal-title').textContent    = 'Edit Plan';
  document.getElementById('plan-modal-editing-id').value     = planId;
  document.getElementById('pm-id').value       = plan.id;
  document.getElementById('pm-name').value     = plan.name;
  document.getElementById('pm-price').value    = plan.price;
  document.getElementById('pm-cycle').value    = plan.billing_cycle || 'monthly';
  document.getElementById('pm-count').value    = plan.products_count;
  document.getElementById('pm-tier').value     = plan.tier_required || '';
  document.getElementById('pm-desc').value     = plan.description;
  document.getElementById('pm-features').value = (plan.features || []).join('\n');
  document.getElementById('pm-popular').checked = !!plan.is_popular;
  document.getElementById('pm-active').checked  = plan.is_active !== false;
  document.getElementById('plan-modal').classList.add('open');
}
function savePlan() {
  const editingId = document.getElementById('plan-modal-editing-id').value;
  const planData  = {
    id:             document.getElementById('pm-id').value.trim(),
    name:           document.getElementById('pm-name').value.trim(),
    price:          parseInt(document.getElementById('pm-price').value || 0),
    billing_cycle:  document.getElementById('pm-cycle').value,
    frequency_label:'per ' + document.getElementById('pm-cycle').value,
    products_count: parseInt(document.getElementById('pm-count').value || 1),
    tier_required:  document.getElementById('pm-tier').value || null,
    description:    document.getElementById('pm-desc').value.trim(),
    features:       document.getElementById('pm-features').value.split('\n').map(s=>s.trim()).filter(Boolean),
    is_popular:     document.getElementById('pm-popular').checked,
    is_active:      document.getElementById('pm-active').checked,
    color:          '#6B7280',
    badge:          document.getElementById('pm-popular').checked ? 'Most Popular' : null,
  };
  if (!planData.id || !planData.name || !planData.price) { showToast('⚠️', 'Please fill in ID, name, and price.'); return; }
  const method = editingId ? 'PUT' : 'POST';
  const url    = editingId ? `/admin/subscription-plans/${editingId}` : '/admin/subscription-plans';
  fetch(url, {
    method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify(planData),
  }).then(r => r.json()).then(d => {
    if (d.success || d.data) {
      showToast('✓', editingId ? 'Plan updated!' : 'Plan created!');
      document.getElementById('plan-modal').classList.remove('open');
      setTimeout(() => location.reload(), 1200);
    } else { showToast('⚠️', d.message || 'Failed to save plan.'); }
  }).catch(() => showToast('⚠️', 'Request failed.'));
}
function deletePlan(planId) {
  fetch(ADMIN_URL + `/subscription-plans/${planId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
  }).then(r => r.json()).then(d => {
    if (d.success) { showToast('✓', 'Plan deleted.'); setTimeout(() => location.reload(), 1000); }
    else { showToast('⚠️', d.message || 'Failed to delete.'); }
  });
}
function loadAdminSubscriptions() {
  const list = document.getElementById('admin-subscriptions-list');
  if (list) list.innerHTML = '<div style="text-align:center;padding:24px;color:rgba(10,10,10,.4);">Loading…</div>';
  fetch(ADMIN_URL + '/members/subscriptions', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(data => {
      const subs = data?.data?.data || data?.data || [];
      if (!subs.length) { if (list) list.innerHTML = '<div style="text-align:center;padding:24px;color:rgba(10,10,10,.4);">No active subscriptions found.</div>'; return; }
      const rows = subs.map(s => `
        <tr>
          <td><div style="font-weight:600;">${s.user?.name||'—'}</div><div style="font-size:.75rem;color:rgba(10,10,10,.4);">${s.user?.email||''}</div></td>
          <td style="font-weight:600;">${s.plan_name||'—'}</td>
          <td>₦${(s.plan_price||0).toLocaleString()}</td>
          <td><span class="status-badge ${s.status||''}">${s.status||'—'}</span></td>
          <td style="font-size:.82rem;color:rgba(10,10,10,.4);">${s.next_billing_date||'—'}</td>
        </tr>`).join('');
      if (list) list.innerHTML = `<table class="data-table"><thead><tr><th>Member</th><th>Plan</th><th>Price</th><th>Status</th><th>Next Billing</th></tr></thead><tbody>${rows}</tbody></table>`;
    }).catch(() => { if (list) list.innerHTML = '<div style="text-align:center;padding:24px;color:rgba(10,10,10,.4);">Could not load subscriptions.</div>'; });
}

// ── Users Panel ───────────────────────────────────────
function filterUsersTable(q) {
  const rows = document.querySelectorAll('#usersTable tbody tr[data-name]');
  const lq = q.toLowerCase();
  rows.forEach(row => {
    const match = row.dataset.name.includes(lq) || row.dataset.email.includes(lq);
    row.style.display = match ? '' : 'none';
  });
}

function openUserProfileModal(u) {
  const modal = document.getElementById('userProfileModal');
  const avatarEl = document.getElementById('upModal_avatar');
  if (u.avatar) {
    avatarEl.innerHTML = `<img src="${u.avatar}" style="width:100%;height:100%;object-fit:cover">`;
  } else {
    avatarEl.textContent = (u.name || 'U').charAt(0).toUpperCase();
  }
  document.getElementById('upModal_name').textContent    = u.name || '—';
  document.getElementById('upModal_email').textContent   = u.email || '—';
  document.getElementById('upModal_tier').textContent    = u.tier || 'Bronze';
  document.getElementById('upModal_phone').textContent   = u.phone || '—';
  document.getElementById('upModal_birthday').textContent = u.birthday || '—';
  document.getElementById('upModal_skin').textContent    = u.skin_type || '—';
  document.getElementById('upModal_points').textContent  = (u.loyalty_points || 0).toLocaleString() + ' pts';
  document.getElementById('upModal_since').textContent   = u.created_at ? new Date(u.created_at).toLocaleDateString('en-GB',{month:'short',year:'numeric'}) : '—';
  const addr = [u.address_line1, u.address_line2, u.city, u.state].filter(Boolean).join(', ');
  document.getElementById('upModal_address').textContent = addr || '—';
  const prefs = u.email_prefs || {};
  const prefLabels = {routine_tips:'Routine tips',new_products:'New products',subscription:'Subscription',promotions:'Promotions',loyalty_updates:'Loyalty updates'};
  const prefText = Object.entries(prefLabels).map(([k,l]) => `${prefs[k] !== false ? '✓' : '✗'} ${l}`).join(' · ');
  document.getElementById('upModal_prefs').textContent = prefText || '—';
  modal.style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function closeUserProfileModal() {
  document.getElementById('userProfileModal').style.display = 'none';
  document.body.style.overflow = '';
}

// ── Generated Routines list ───────────────────────────
const GENERATED_ROUTINES = [
  { name:'Adaeze Okonkwo',  skin:'Combination', concern:'Hyperpigmentation', steps:['Cleanser','Toner','Vit C Serum','Moisturiser','SPF50'], initials:'AO', bg:'#fef3c7', col:'#92400e' },
  { name:'Ngozi Kalu',       skin:'Oily',        concern:'Acne & Breakouts',  steps:['Salicylic Cleanser','BHA Toner','Niacinamide','Oil-Free Moisturiser','Mattifying SPF'], initials:'NK', bg:'#dbeafe', col:'#1e40af' },
  { name:'Chidinma Eze',     skin:'Dry',         concern:'Redness',           steps:['Gentle Cleanser','Calming Toner','Ceramide Serum','Rich Moisturiser','Mineral SPF'], initials:'CE', bg:'#d1fae5', col:'#065f46' },
  { name:'Fatimah Bello',    skin:'Sensitive',   concern:'Dehydration',       steps:['Micellar Cleanser','Hyaluronic Acid','Barrier Serum','Moisturiser','SPF30'], initials:'FB', bg:'#ede9fe', col:'#5b21b6' },
  { name:'Yetunde Adeyemi',  skin:'Normal',      concern:'Anti-Aging',        steps:['Foam Cleanser','Peptide Toner','Retinol Serum','Eye Cream','Moisturiser'], initials:'YA', bg:'#fee2e2', col:'#991b1b' },
];

function buildGeneratedRoutines() {
  const container = document.getElementById('generatedRoutinesList');
  if (!container) return;
  container.innerHTML = GENERATED_ROUTINES.map(r =>
    '<div class="routine-card"><div class="routine-card-avatar" style="background:' + r.bg + ';color:' + r.col + ';">' + r.initials + '</div><div class="routine-card-body"><div class="routine-card-name">' + r.name + '</div><div class="routine-card-meta">' + r.skin + ' skin · ' + r.concern + ' · 🤖 AI-Generated</div><div class="routine-steps">' + r.steps.map((s,i) => '<span class="routine-step-chip"><span class="step-num">' + (i+1) + '</span> ' + s + '</span>').join('') + '</div><div class="routine-card-actions"><button class="action-btn edit" onclick="document.getElementById(\'routineEditorOverlay\').classList.add(\'open\')">✏️ Override</button><button class="action-btn tag-btn" onclick="showToast(\'📤\',\'Routine sent to ' + r.name + '!\')">📤 Send</button></div></div></div>'
  ).join('');
}

// ── Quiz Config Editor ────────────────────────────────
const ADMIN_DEFAULT_QUIZ_CONFIG = {
  slides:[
    {id:1,stage:1,stageLabel:'🟢 Stage 1 — Skin Type',question:'How does your skin feel 30 minutes after washing your face?',subtext:'No product, no moisturizer — just your bare skin.',type:'single',twoCol:false,nextAction:'next',options:[{val:'dry',emoji:'🏜️',label:'Tight & Dry',sub:'Feels uncomfortable, pulling sensation'},{val:'normal',emoji:'😊',label:'Normal / Comfortable',sub:'Feels balanced, no dryness or oiliness'},{val:'combination',emoji:'😐',label:'Slightly Oily in T-zone',sub:'Forehead and nose get shiny, cheeks are fine'},{val:'oily',emoji:'🧴',label:'Oily All Over',sub:'Entire face gets shiny fairly quickly'},{val:'unsure',emoji:'🤷',label:'Not Sure',sub:'It changes depending on the day or weather'}]},
    {id:2,stage:1,stageLabel:'🟢 Stage 1 — Skin Type',question:'How often do you experience shine during the day?',subtext:'Think about a typical day without blotting or touching up.',type:'single',twoCol:false,nextAction:'next',options:[{val:'rarely',emoji:'🤍',label:'Rarely',sub:'My skin stays matte most of the day'},{val:'tzone',emoji:'🌡️',label:'Only on Forehead/Nose',sub:'T-zone gets shiny by afternoon'},{val:'frequently',emoji:'☀️',label:'Frequently',sub:'Multiple times a day, especially in heat'},{val:'very_oily',emoji:'💦',label:'Very Oily Within Hours',sub:'My skin gets shiny within 2–3 hours'}]},
    {id:3,stage:1,stageLabel:'🟢 Stage 1 — Skin Type',question:'How visible are your pores?',subtext:'Look in a mirror at normal distance — what do you notice?',type:'single',twoCol:true,nextAction:'stageTransition:1',options:[{val:'barely',emoji:'🔬',label:'Barely Visible',sub:''},{val:'tzone_pores',emoji:'😑',label:'Visible in T-zone only',sub:''},{val:'large',emoji:'😮',label:'Large and Noticeable',sub:''},{val:'very_large',emoji:'🕳️',label:'Very Large & Clogged',sub:''}]},
    {id:4,stage:2,stageLabel:'🔴 Stage 2 — Skin Concerns',question:'What are your top skin concerns?',subtext:'Select up to 3 that bother you most. These drive your product recommendations.',type:'multi',twoCol:true,nextAction:'next',options:[]},
    {id:5,stage:2,stageLabel:'🔴 Stage 2 — Skin Concerns',question:'How severe are these concerns?',subtext:'This helps us determine product strength and key ingredients.',type:'single',twoCol:false,nextAction:'stageTransition:2',options:[{val:'mild',emoji:'🌱',label:'Mild',sub:'Occasional, barely noticeable — maintenance is key'},{val:'moderate',emoji:'⚠️',label:'Moderate',sub:'Regular occurrence, clearly visible — needs targeted treatment'},{val:'severe',emoji:'🚨',label:'Severe',sub:'Persistent and significant — needs strong active ingredients'}]},
    {id:6,stage:3,stageLabel:'🟡 Stage 3 — Skin Behavior',question:'How does your skin react to new products?',subtext:'Think about the last time you introduced something new to your routine.',type:'single',twoCol:false,nextAction:'next',options:[{val:'no_reaction',emoji:'✅',label:'No Reaction',sub:'I can use most things without any issues'},{val:'occasional',emoji:'😬',label:'Occasional Breakouts',sub:'New products sometimes cause a purge or pimple'},{val:'easily_irritated',emoji:'😣',label:'Easily Irritated',sub:'Redness or stinging with many products'},{val:'very_sensitive',emoji:'🚨',label:'Very Sensitive',sub:'Most products cause a reaction — I need fragrance-free, minimal ingredients'}]},
    {id:7,stage:3,stageLabel:'🟡 Stage 3 — Skin Behavior',question:'How often do you break out?',subtext:'',type:'single',twoCol:true,nextAction:'next',options:[{val:'rarely',emoji:'🌸',label:'Rarely',sub:'Barely ever'},{val:'periodic',emoji:'🌙',label:'Around Periods/Stress',sub:'Hormonal pattern'},{val:'frequently',emoji:'🤒',label:'Frequently',sub:'Multiple times a month'},{val:'constantly',emoji:'😔',label:'Constantly',sub:'Always breaking out somewhere'}]},
    {id:8,stage:3,stageLabel:'🟡 Stage 3 — Skin Behavior',question:'Do you currently use active ingredients?',subtext:'This helps us avoid recommending things that could irritate your current routine.',type:'single',twoCol:false,nextAction:'stageTransition:3',options:[{val:'no',emoji:'🚫',label:"No — I'm a beginner",sub:'Just starting my skincare journey'},{val:'yes',emoji:'🧪',label:'Yes — Retinol, AHA/BHA, Vitamin C',sub:'I already use actives in my routine'},{val:'not_sure',emoji:'🤔',label:'Not Sure',sub:"I use some products but don't know the ingredients"}]},
    {id:9,stage:4,stageLabel:'🔵 Stage 4 — Lifestyle',question:'How much water do you drink daily?',subtext:'Hydration from inside affects your skin from outside!',type:'single',twoCol:true,nextAction:'next',options:[{val:'less_1l',emoji:'🚫',label:'Less than 1L',sub:''},{val:'1_2l',emoji:'🧃',label:'1–2 Litres',sub:''},{val:'2_3l',emoji:'🍶',label:'2–3 Litres',sub:''},{val:'3l_plus',emoji:'🏆',label:'3L+',sub:''}]},
    {id:10,stage:4,stageLabel:'🔵 Stage 4 — Lifestyle',question:'How often are you exposed to the sun?',subtext:'Nigerian sun is intense — your SPF needs matter here!',type:'single',twoCol:false,nextAction:'next',options:[{val:'rarely',emoji:'🏠',label:'Rarely',sub:'Mostly indoors'},{val:'occasionally',emoji:'🤏',label:'Occasionally',sub:'Some outdoor time a few times a week'},{val:'daily',emoji:'☀️',label:'Daily',sub:'Outdoor exposure every day'}]},
    {id:11,stage:4,stageLabel:'🔵 Stage 4 — Lifestyle',question:'What best describes your environment?',subtext:'',type:'single',twoCol:false,nextAction:'stageTransition:4',options:[{val:'aircon',emoji:'❄️',label:'Air-conditioned Most of the Day',sub:'Office or home AC — skin tends to get dehydrated'},{val:'humid',emoji:'🌴',label:'Hot & Humid Climate',sub:'Lagos, PH, Warri weather — skin gets oily & sweaty'},{val:'mixed',emoji:'🌡️',label:'Mixed Environment',sub:'Move between AC and outdoor settings'}]},
    {id:12,stage:5,stageLabel:'🟠 Stage 5 — Personalization',question:"What's your budget per routine?",subtext:"We'll match products to your price range — no surprises.",type:'single',twoCol:false,nextAction:'next',options:[{val:'basic',emoji:'💰',label:'Basic — ₦50,000–₦100,000',sub:'Essentials only, quality on a budget'},{val:'mid',emoji:'💳',label:'Mid-Range — ₦100,000–₦250,000',sub:'Great products with proven actives'},{val:'premium',emoji:'💎',label:'Premium — ₦250,000+',sub:'The absolute best — results at any price'}]},
    {id:13,stage:5,stageLabel:'🟠 Stage 5 — Personalization',question:'What results are you looking for?',subtext:'',type:'single',twoCol:false,nextAction:'next',options:[{val:'quick',emoji:'⚡',label:'Quick Visible Results',sub:'I want to see changes within 2–4 weeks'},{val:'longterm',emoji:'🌳',label:'Long-Term Skin Health',sub:"I'm building a sustainable routine for years of great skin"},{val:'both',emoji:'🎯',label:'Both!',sub:"I want visible results AND I'm in it for the long haul"}]},
    {id:14,stage:5,stageLabel:'🟠 Stage 5 — Personalization',question:'Would you consider professional skin treatments?',subtext:'Like facials, chemical peels, or dermatology visits.',type:'single',twoCol:false,nextAction:'submit',options:[{val:'yes',emoji:'🏥',label:'Yes, absolutely',sub:"I'm open to professional treatments for better results"},{val:'maybe',emoji:'🤔',label:'Maybe Later',sub:"Not right now but I'm open to it"},{val:'no',emoji:'🏠',label:'No, home care only',sub:'I prefer to manage my skin with home products'}]},
  ],
  concerns:[
    {val:'acne',label:'Acne / Breakouts',emoji:'🤢'},
    {val:'dark_spots',label:'Dark Spots / Hyperpigmentation',emoji:'🔘'},
    {val:'dull',label:'Dull Skin',emoji:'😴'},
    {val:'texture',label:'Uneven Texture',emoji:'🏔️'},
    {val:'fine_lines',label:'Fine Lines / Wrinkles',emoji:'⏰'},
    {val:'sensitive',label:'Sensitive / Irritated Skin',emoji:'🌡️'},
    {val:'dehydration',label:'Dehydration',emoji:'🏜️'},
    {val:'large_pores',label:'Large Pores',emoji:'🔍'},
  ],
  stageTransitions:{
    1:{badge:'🟢 Stage 1 of 5 Complete',check:'✓',checkColor:'#b5f000',heading:'Your skin type is mapped.',message:"These questions lay the foundation of your entire routine — knowing how your skin naturally behaves lets us curate the ideal box for your concerns rather than guessing. Keep going!",nextSlide:4,doneStages:1},
    2:{badge:'🔴 Stage 2 of 5 Complete',check:'✓',checkColor:'#ff4444',heading:'Your concerns are on file.',message:"Every answer here helps us choose actives and products that target what matters most to you. Let these questions curate the ideal box for your concerns — you're almost halfway there!",nextSlide:6,doneStages:2},
    3:{badge:'🟡 Stage 3 of 5 Complete',check:'✓',checkColor:'#f5c518',heading:'Your skin behaviour is logged.',message:"Knowing how your skin reacts helps us avoid anything irritating and recommend only what's safe for you. We're using all of this to build a box that truly fits — you're more than halfway there!",nextSlide:9,doneStages:3},
    4:{badge:'🔵 Stage 4 of 5 Complete',check:'✓',checkColor:'#4488ff',heading:'Your lifestyle profile is set.',message:"Your environment, habits and hydration shape what your skin needs day-to-day. These details let us fine-tune your recommendations so your routine works with your life. One final stage — you're almost there!",nextSlide:12,doneStages:4},
  },
  loadingSteps:['🔬 Analyzing skin type profile…','🎯 Matching skin concerns to actives…','🌡️ Calculating sensitivity score…','🌍 Adjusting for Nigerian climate…','💰 Filtering by your budget…','✨ Building your personalized routine…'],
  settings:{enabled:true,maxConcernSelections:3,loadingDelayMs:3500},
  tagWeights:[
    // Q1 — Skin feel after cleansing
    {slideId:1,  answer:'Tight & Dry',                          tag:'Dry',               tagType:'skin',    weight:3},
    {slideId:1,  answer:'Normal / Comfortable',                 tag:'Normal',            tagType:'skin',    weight:2},
    {slideId:1,  answer:'Slightly Oily in T-zone',              tag:'Combination',       tagType:'skin',    weight:3},
    {slideId:1,  answer:'Oily All Over',                        tag:'Oily',              tagType:'skin',    weight:3},
    // Q2 — Shine frequency
    {slideId:2,  answer:'Only on Forehead/Nose',                tag:'Combination',       tagType:'skin',    weight:2},
    {slideId:2,  answer:'Frequently',                           tag:'Oily',              tagType:'skin',    weight:2},
    {slideId:2,  answer:'Very Oily Within Hours',               tag:'Oily',              tagType:'skin',    weight:3},
    // Q4 — Skin concerns (multi-select)
    {slideId:4,  answer:'Acne / Breakouts',                     tag:'Acne',              tagType:'concern', weight:5},
    {slideId:4,  answer:'Dark Spots / Hyperpigmentation',       tag:'Brightening',       tagType:'concern', weight:5},
    {slideId:4,  answer:'Dull Skin',                            tag:'Dull',              tagType:'concern', weight:4},
    {slideId:4,  answer:'Uneven Texture',                       tag:'Uneven Texture',    tagType:'concern', weight:4},
    {slideId:4,  answer:'Fine Lines / Wrinkles',                tag:'Anti-aging',        tagType:'concern', weight:4},
    {slideId:4,  answer:'Sensitive / Irritated Skin',           tag:'Sensitivity',       tagType:'concern', weight:4},
    {slideId:4,  answer:'Dehydration',                          tag:'Dehydration',       tagType:'concern', weight:5},
    {slideId:4,  answer:'Large Pores',                          tag:'Large Pores',       tagType:'concern', weight:4},
    // Q5 — Severity
    {slideId:5,  answer:'Severe',                               tag:'Active Ingredients',tagType:'ingred',  weight:3},
    // Q6 — Reaction to new products
    {slideId:6,  answer:'Easily Irritated',                     tag:'Sensitivity',       tagType:'concern', weight:3},
    {slideId:6,  answer:'Very Sensitive',                       tag:'Sensitivity',       tagType:'concern', weight:5},
    // Q8 — Active ingredients
    {slideId:8,  answer:"Yes — Retinol, AHA/BHA, Vitamin C",   tag:'Retinol',           tagType:'ingred',  weight:4},
    {slideId:8,  answer:"Yes — Retinol, AHA/BHA, Vitamin C",   tag:'AHA',               tagType:'ingred',  weight:3},
    {slideId:8,  answer:"Yes — Retinol, AHA/BHA, Vitamin C",   tag:'BHA / Salicylic Acid', tagType:'ingred', weight:3},
    // Q9 — Water intake
    {slideId:9,  answer:'Less than 1L',                         tag:'Dehydration',       tagType:'concern', weight:2},
    {slideId:9,  answer:'1–2 Litres',                           tag:'Dehydration',       tagType:'concern', weight:1},
    // Q10 — Sun exposure
    {slideId:10, answer:'Daily',                                tag:'Sunscreen / SPF',   tagType:'routine', weight:5},
    {slideId:10, answer:'Occasionally',                         tag:'Sunscreen / SPF',   tagType:'routine', weight:3},
    // Q11 — Environment
    {slideId:11, answer:'Hot & Humid Climate',                  tag:'Humid',             tagType:'climate', weight:3},
    {slideId:11, answer:'Air-conditioned Most of the Day',      tag:'Dehydration',       tagType:'concern', weight:2},
    // Q12 — Budget
    {slideId:12, answer:'Basic — ₦50,000–₦100,000',            tag:'Budget',            tagType:'',        weight:1},
    {slideId:12, answer:'Mid-Range — ₦100,000–₦250,000',       tag:'Mid',               tagType:'',        weight:1},
    {slideId:12, answer:'Premium — ₦250,000+',                  tag:'Premium',           tagType:'',        weight:1},
  ]
};

let adminQuizConfig;

function escHtml(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function getAdminQuizConfig() {
  if (CMS_QUIZ_DATA && Array.isArray(CMS_QUIZ_DATA.slides) && CMS_QUIZ_DATA.slides.length) return CMS_QUIZ_DATA;
  return ADMIN_DEFAULT_QUIZ_CONFIG;
}

function initQuizEditor() {
  adminQuizConfig = getAdminQuizConfig();
  buildQuizEditor(adminQuizConfig);
}

function buildQuizEditor(config) {
  document.getElementById('qz-accordion').innerHTML = config.slides.map(s => buildSlideAccordion(s)).join('');
  buildStageTransitionsTab(config.stageTransitions);
  buildConcernsEditor(config.concerns, config.settings);
  buildLoadingStepsEditor(config.loadingSteps);
  buildTagWeightsEditor(config.tagWeights || []);
  const enEl = document.getElementById('qz-enabled');
  if (enEl) enEl.checked = config.settings.enabled !== false;
  const dlEl = document.getElementById('qz-loading-delay');
  if (dlEl) dlEl.value = config.settings.loadingDelayMs || 3500;
}

function buildSlideAccordion(s) {
  const stageColors = {'1':'#b5f000','2':'#E8382E','3':'#f5c518','4':'#4488ff','5':'#FF6B35'};
  const col = stageColors[String(s.stage)] || '#999';
  const nextActionLabels = {next:'Go to Next Slide','stageTransition:1':'Show Stage 1 Transition','stageTransition:2':'Show Stage 2 Transition','stageTransition:3':'Show Stage 3 Transition','stageTransition:4':'Show Stage 4 Transition',submit:'Submit Quiz'};
  const nextActionSel = Object.entries(nextActionLabels).map(([v,l]) =>
    `<option value="${v}"${s.nextAction===v?' selected':''}>${l}</option>`).join('');
  let optSection;
  if (s.type === 'multi') {
    optSection = `<div style="background:#fafbfc;border:1.5px solid #e8eaed;border-radius:10px;padding:14px;font-size:.82rem;color:rgba(10,10,10,.5);">This slide uses the multi-select concerns list. Edit options in the <strong>Concerns</strong> tab.</div>`;
  } else {
    const optRows = s.options.map((o,oi) => `<tr data-opt-row="${oi}">
      <td><input class="form-input" type="text" value="${escHtml(o.emoji)}" data-field="emoji" style="width:52px;text-align:center;font-size:1.1rem;padding:5px;" /></td>
      <td><input class="form-input" type="text" value="${escHtml(o.val)}" data-field="val" style="width:110px;font-size:.78rem;" /></td>
      <td><input class="form-input" type="text" value="${escHtml(o.label)}" data-field="label" style="width:200px;" /></td>
      <td><input class="form-input" type="text" value="${escHtml(o.sub||'')}" data-field="sub" style="width:210px;" placeholder="(optional)" /></td>
      <td><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('tr').remove()">🗑️</button></td>
    </tr>`).join('');
    optSection = `<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
      <strong style="font-size:.82rem;">Answer Options</strong>
      <button class="action-btn edit" style="font-size:.75rem;padding:4px 10px;" onclick="addQuizOption(${s.id})">+ Add Option</button>
    </div>
    <div style="overflow-x:auto;">
      <table class="data-table" style="font-size:.8rem;min-width:620px;">
        <thead><tr><th>Emoji</th><th>Value Key</th><th>Label</th><th>Sub-label</th><th></th></tr></thead>
        <tbody id="opts-body-${s.id}">${optRows}</tbody>
      </table>
    </div>`;
  }
  return `<div class="qz-acc-item" data-slide-id="${s.id}">
    <div class="qz-acc-header" onclick="this.parentElement.classList.toggle('open')">
      <span style="flex-shrink:0;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:999px;background:${col}22;color:${col};border:1.5px solid ${col}44">${escHtml(s.stageLabel.split('—')[0].trim())}</span>
      <span style="font-size:.72rem;font-weight:700;color:rgba(10,10,10,.3);flex-shrink:0;min-width:48px">Slide ${s.id}</span>
      <span style="flex:1;font-size:.88rem;font-weight:600;color:var(--black);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${escHtml(s.question)}</span>
      <span class="qz-chevron">›</span>
    </div>
    <div class="qz-acc-body">
      <div class="form-grid" style="margin-bottom:20px;">
        <div class="form-group" style="grid-column:1/-1"><label>Question Text</label><input type="text" class="form-input" value="${escHtml(s.question)}" data-field="question" /></div>
        <div class="form-group" style="grid-column:1/-1"><label>Subtext <span style="font-weight:400;color:rgba(10,10,10,.4)">(optional)</span></label><input type="text" class="form-input" value="${escHtml(s.subtext||'')}" data-field="subtext" /></div>
        <div class="form-group"><label>Options Layout</label><select class="form-input" data-field="twoCol"><option value="false"${!s.twoCol?' selected':''}>Single Column</option><option value="true"${s.twoCol?' selected':''}>Two Column Grid</option></select></div>
        <div class="form-group"><label>Continue Button Action</label><select class="form-input" data-field="nextAction">${nextActionSel}</select></div>
      </div>
      ${optSection}
    </div>
  </div>`;
}

function addQuizOption(slideId) {
  const tbody = document.getElementById('opts-body-' + slideId);
  if (!tbody) return;
  const tr = document.createElement('tr');
  tr.setAttribute('data-opt-row', tbody.children.length);
  tr.innerHTML = `<td><input class="form-input" type="text" value="✨" data-field="emoji" style="width:52px;text-align:center;font-size:1.1rem;padding:5px;"/></td><td><input class="form-input" type="text" value="new_option" data-field="val" style="width:110px;font-size:.78rem;"/></td><td><input class="form-input" type="text" value="New Option" data-field="label" style="width:200px;"/></td><td><input class="form-input" type="text" value="" data-field="sub" style="width:210px;" placeholder="(optional)"/></td><td><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('tr').remove()">🗑️</button></td>`;
  tbody.appendChild(tr);
}

function buildStageTransitionsTab(transitions) {
  const stageNames = {1:'Skin Type',2:'Skin Concerns',3:'Skin Behavior',4:'Lifestyle'};
  const stageColors = {1:'#b5f000',2:'#E8382E',3:'#f5c518',4:'#4488ff'};
  document.getElementById('qz-stages-grid').innerHTML = Object.entries(transitions).map(([num,t]) => {
    const col = stageColors[num] || '#999';
    return `<div class="qz-stage-card" data-stage="${num}">
      <div class="qz-stage-card-header">
        <div class="qz-color-swatch" style="background:${col}"></div>
        <strong>Stage ${num} — ${stageNames[num]||''}</strong>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px;">
        <div class="form-group"><label>Badge Text</label><input type="text" class="form-input" value="${escHtml(t.badge)}" data-field="badge"/></div>
        <div class="form-group"><label>Check Colour</label>
          <div style="display:flex;gap:8px;align-items:center;">
            <input type="color" value="${t.checkColor}" style="width:40px;height:36px;padding:2px;border:1.5px solid #e8eaed;border-radius:8px;cursor:pointer;" onchange="this.nextElementSibling.value=this.value"/>
            <input type="text" class="form-input" value="${escHtml(t.checkColor)}" data-field="checkColor" oninput="this.previousElementSibling.value=this.value"/>
          </div>
        </div>
        <div class="form-group"><label>Heading</label><input type="text" class="form-input" value="${escHtml(t.heading)}" data-field="heading"/></div>
        <div class="form-group"><label>Message</label><textarea class="form-input" rows="3" data-field="message" style="resize:vertical;">${escHtml(t.message)}</textarea></div>
        <div class="form-group"><label>Continues to Slide #</label><input type="number" class="form-input" value="${t.nextSlide}" data-field="nextSlide" min="1" max="20" style="width:100px;"/></div>
      </div>
    </div>`;
  }).join('');
}

function buildConcernsEditor(concerns, settings) {
  document.getElementById('concerns-editor-body').innerHTML = concerns.map((c,i) => buildConcernRow(c,i)).join('');
  const ms = document.getElementById('qz-max-sel');
  if (ms) ms.value = settings.maxConcernSelections || 3;
}

function buildConcernRow(c, i) {
  return `<tr><td><input class="form-input" type="text" value="${escHtml(c.emoji)}" data-field="emoji" style="width:52px;text-align:center;font-size:1.1rem;padding:5px;"/></td><td><input class="form-input" type="text" value="${escHtml(c.val)}" data-field="val" style="width:130px;font-size:.78rem;"/></td><td><input class="form-input" type="text" value="${escHtml(c.label)}" data-field="label"/></td><td><button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('tr').remove()">🗑️</button></td></tr>`;
}

function addConcernRow() {
  const tbody = document.getElementById('concerns-editor-body');
  if (!tbody) return;
  const tr = document.createElement('tr');
  tr.innerHTML = buildConcernRow({emoji:'✨',val:'new_concern',label:'New Concern'}, tbody.children.length);
  tbody.appendChild(tr);
}

function buildLoadingStepsEditor(steps) {
  const container = document.getElementById('qz-loading-steps');
  if (!container) return;
  container.innerHTML = steps.map((s,i) =>
    `<div class="ann-item"><span class="ann-drag">⠿</span><input type="text" class="form-input ann-text-field" value="${escHtml(s)}" data-step-input="${i}"/><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button></div>`).join('');
}

function addLoadingStep() {
  const container = document.getElementById('qz-loading-steps');
  if (!container) return;
  const div = document.createElement('div');
  div.className = 'ann-item';
  div.innerHTML = `<span class="ann-drag">⠿</span><input type="text" class="form-input ann-text-field" value="✨ New step…" data-step-input="new"/><button class="action-btn danger" style="padding:5px 8px;" onclick="this.closest('.ann-item').remove()">🗑️</button>`;
  container.appendChild(div);
}

async function saveQuizConfig() {
  const config = {slides:[],concerns:[],stageTransitions:{},loadingSteps:[],settings:{}};
  // Slides
  document.querySelectorAll('#qz-accordion .qz-acc-item').forEach(item => {
    const slideId = parseInt(item.dataset.slideId);
    const orig = (adminQuizConfig.slides || []).find(s => s.id === slideId) || {};
    const s = Object.assign({}, orig);
    const qEl = item.querySelector('[data-field="question"]');
    const stEl = item.querySelector('[data-field="subtext"]');
    const tcEl = item.querySelector('[data-field="twoCol"]');
    const naEl = item.querySelector('[data-field="nextAction"]');
    if (qEl) s.question = qEl.value;
    if (stEl) s.subtext = stEl.value;
    if (tcEl) s.twoCol = tcEl.value === 'true';
    if (naEl) s.nextAction = naEl.value;
    if (s.type !== 'multi') {
      s.options = [];
      item.querySelectorAll('[data-opt-row]').forEach(row => {
        s.options.push({
          emoji: (row.querySelector('[data-field="emoji"]')||{}).value || '',
          val:   (row.querySelector('[data-field="val"]')||{}).value || '',
          label: (row.querySelector('[data-field="label"]')||{}).value || '',
          sub:   (row.querySelector('[data-field="sub"]')||{}).value || '',
        });
      });
    }
    config.slides.push(s);
  });
  config.slides.sort((a,b) => a.id - b.id);
  // Stage transitions
  document.querySelectorAll('#qz-stages-grid .qz-stage-card').forEach(card => {
    const num = parseInt(card.dataset.stage);
    config.stageTransitions[num] = {
      badge:      (card.querySelector('[data-field="badge"]')||{}).value || '',
      check:      '✓',
      checkColor: (card.querySelector('[data-field="checkColor"]')||{}).value || '#b5f000',
      heading:    (card.querySelector('[data-field="heading"]')||{}).value || '',
      message:    (card.querySelector('[data-field="message"]')||{}).value || '',
      nextSlide:  parseInt((card.querySelector('[data-field="nextSlide"]')||{}).value) || 1,
      doneStages: num,
    };
  });
  // Concerns
  document.querySelectorAll('#concerns-editor-body tr').forEach(row => {
    config.concerns.push({
      emoji: (row.querySelector('[data-field="emoji"]')||{}).value || '',
      val:   (row.querySelector('[data-field="val"]')||{}).value || '',
      label: (row.querySelector('[data-field="label"]')||{}).value || '',
    });
  });
  // Settings
  config.settings = {
    enabled:               (document.getElementById('qz-enabled')||{}).checked !== false,
    maxConcernSelections:  parseInt((document.getElementById('qz-max-sel')||{}).value) || 3,
    loadingDelayMs:        parseInt((document.getElementById('qz-loading-delay')||{}).value) || 3500,
  };
  // Loading steps
  document.querySelectorAll('#qz-loading-steps [data-step-input]').forEach(inp => {
    if (inp.value.trim()) config.loadingSteps.push(inp.value.trim());
  });
  // Tag weights
  config.tagWeights = [];
  document.querySelectorAll('#tag-weights-body [data-weight-row]').forEach(row => {
    const slideId = parseInt((row.querySelector('[data-field="slideId"]') || {}).value) || null;
    if (!slideId) return; // skip blank rows
    config.tagWeights.push({
      slideId,
      answer:  (row.querySelector('[data-field="answer"]')  || {}).value || '',
      tag:     (row.querySelector('[data-field="tag"]')     || {}).value || '',
      tagType: (row.querySelector('[data-field="tagType"]') || {}).value || 'skin',
      weight:  parseInt((row.querySelector('[data-field="weight"]') || {}).value) || 1,
    });
  });
  try {
    const response = await fetch(CMS_CONTENT_ROUTES.saveQuiz, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CMS_CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ quiz: config }),
    });
    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`Save failed (${response.status}): ${errorText.slice(0, 180)}`);
    }
    const data = await response.json();
    adminQuizConfig = data.quiz || config;
    showToast('💾', 'Quiz config saved! Changes are live on the quiz page.');
  } catch (error) {
    console.error(error);
    showToast('⚠️', error.message || 'Could not save quiz configuration.');
  }
}

function resetQuizConfig() {
  if (!confirm('Reset all quiz settings to defaults? This cannot be undone.')) return;
  adminQuizConfig = ADMIN_DEFAULT_QUIZ_CONFIG;
  buildQuizEditor(adminQuizConfig);
  showToast('↩', 'Quiz reset to default configuration.');
}

const TAG_TYPE_OPTS = ['skin','concern','routine','ingred','climate',''];
const TAG_TYPE_LABELS = {skin:'Skin Type',concern:'Concern',routine:'Routine Step',ingred:'Ingredient',climate:'Climate','':"Custom"};

// All known tags grouped by type — used for the tag dropdown
const TAG_OPTIONS_GROUPED = {
  'Skin Type':    ['Oily','Dry','Combination','Sensitive','Normal','All Skin Types'],
  'Concern':      ['Acne','Brightening','Dehydration','Dull','Large Pores','Anti-aging','Sensitivity','Uneven Texture'],
  'Routine Step': ['Cleanser','Toner / Essence','Serum / Ampoule','Moisturizer','Sunscreen / SPF','Eye Cream','Mask / Treatment','Exfoliant'],
  'Ingredient':   ['Niacinamide','Hyaluronic Acid','AHA','BHA / Salicylic Acid','Retinol','Vitamin C','Centella Asiatica','Ceramides','Snail Mucin','Peptides','Active Ingredients'],
  'Climate':      ['Humid','Dry Climate','Tropical','Harmattan Season','Urban / Pollution'],
  'Price Tier':   ['Budget','Mid','Premium'],
};

// Flat tag → tagType map for auto-sync when tag is chosen
const TAG_TO_TYPE = {};
const TYPE_KEY_MAP = {'Skin Type':'skin','Concern':'concern','Routine Step':'routine','Ingredient':'ingred','Climate':'climate','Price Tier':''};
for (const [group, tags] of Object.entries(TAG_OPTIONS_GROUPED)) {
  tags.forEach(t => { TAG_TO_TYPE[t] = TYPE_KEY_MAP[group]; });
}

function guessSlideIdFromQuestion(q) {
  if (!q) return '';
  const m = String(q).match(/^Q(\d+)/);
  return m ? parseInt(m[1]) : '';
}

function buildQuestionOptions(selectedSlideId) {
  const slides = (adminQuizConfig || ADMIN_DEFAULT_QUIZ_CONFIG).slides;
  return slides.map(s => {
    const short = s.question.length > 48 ? s.question.substring(0,48) + '…' : s.question;
    return `<option value="${s.id}"${s.id == selectedSlideId ? ' selected' : ''}>Q${s.id}: ${escHtml(short)}</option>`;
  }).join('');
}

function buildAnswerOptions(slideId, selectedAnswer) {
  if (!slideId) return '<option value="">— pick a question first —</option>';
  const cfg = adminQuizConfig || ADMIN_DEFAULT_QUIZ_CONFIG;
  const slide = cfg.slides.find(s => s.id == slideId);
  if (!slide) return '<option value="">— slide not found —</option>';
  const labels = slide.type === 'multi'
    ? cfg.concerns.map(c => c.label)
    : slide.options.map(o => o.label);
  return labels.map(l =>
    `<option value="${escHtml(l)}"${l === selectedAnswer ? ' selected' : ''}>${escHtml(l)}</option>`).join('');
}

function buildTagDropdownOptions(selectedTag) {
  let html = '<option value="">— Tag —</option>';
  for (const [group, tags] of Object.entries(TAG_OPTIONS_GROUPED)) {
    html += `<optgroup label="${group}">`;
    html += tags.map(t => `<option value="${t}"${t === selectedTag ? ' selected' : ''}>${t}</option>`).join('');
    html += '</optgroup>';
  }
  return html;
}

function updateAnswerDropdown(questionSel) {
  const row = questionSel.closest('tr');
  const answerSel = row.querySelector('[data-field="answer"]');
  if (answerSel) answerSel.innerHTML = buildAnswerOptions(questionSel.value, '');
}

function syncTagType(tagSel) {
  const row = tagSel.closest('tr');
  const typeSel = row.querySelector('[data-field="tagType"]');
  const detected = TAG_TO_TYPE[tagSel.value];
  if (detected !== undefined && typeSel) typeSel.value = detected;
}

function buildTagWeightsEditor(tagWeights) {
  const tbody = document.getElementById('tag-weights-body');
  if (!tbody) return;
  tbody.innerHTML = (tagWeights || []).map((w, i) => buildTagWeightRow(w, i)).join('');
}

function buildTagWeightRow(w, i) {
  const slideId = w.slideId || guessSlideIdFromQuestion(w.question);
  const typeOpts = TAG_TYPE_OPTS.map(t =>
    `<option value="${t}"${w.tagType === t ? ' selected' : ''}>${TAG_TYPE_LABELS[t]}</option>`).join('');
  return `<tr data-weight-row="${i}">
    <td>
      <select class="form-input" data-field="slideId" style="font-size:.78rem;padding:6px 8px;min-width:190px;" onchange="updateAnswerDropdown(this)">
        <option value="">— Question —</option>
        ${buildQuestionOptions(slideId)}
      </select>
    </td>
    <td>
      <select class="form-input" data-field="answer" style="font-size:.78rem;padding:6px 8px;min-width:170px;">
        ${buildAnswerOptions(slideId, w.answer)}
      </select>
    </td>
    <td>
      <select class="form-input" data-field="tag" style="font-size:.78rem;padding:6px 8px;width:160px;" onchange="syncTagType(this)">
        ${buildTagDropdownOptions(w.tag)}
      </select>
    </td>
    <td>
      <select class="form-input" data-field="tagType" style="width:120px;font-size:.78rem;padding:6px 8px;">${typeOpts}</select>
    </td>
    <td>
      <input type="number" class="form-input" value="${w.weight || 1}" data-field="weight" min="1" max="10" style="width:64px;padding:5px 8px;" />
    </td>
    <td>
      <button class="action-btn danger" style="padding:4px 8px;" onclick="this.closest('tr').remove()">🗑️</button>
    </td>
  </tr>`;
}

function addTagWeightRow() {
  const tbody = document.getElementById('tag-weights-body');
  if (!tbody) return;
  const tmp = document.createElement('table');
  tmp.innerHTML = '<tbody>' + buildTagWeightRow({slideId:'', answer:'', tag:'', tagType:'skin', weight:3}, tbody.children.length) + '</tbody>';
  tbody.appendChild(tmp.querySelector('tr'));
}

// ── Initialise panel functions ───────────────────────────────────────────────
// Overview-critical (run immediately on DOMContentLoaded)
document.addEventListener('DOMContentLoaded', () => {
  buildRevenueChart();
  buildTopProducts();
  buildInventoryTable();

  // Make topbar search active
  const topSearch = document.querySelector('.admin-topbar .admin-search-bar');
  if (topSearch) {
    topSearch.style.cursor = 'text';
    topSearch.innerHTML = '<span style="font-size:.9rem;">🔍</span> <input style="border:none;background:transparent;outline:none;font-size:.85rem;color:rgba(10,10,10,.7);width:100%;" placeholder="Search products, orders, users…" />';
  }

  // Animate tier bars on overview load
  setTimeout(() => {
    document.querySelectorAll('.tier-bar-fill').forEach(bar => {
      const w = bar.style.width;
      bar.style.width = '0';
      setTimeout(() => { bar.style.width = w; }, 100);
    });
  }, 200);

  // Non-overview panels — defer to idle so they don't block paint
  function deferredInit() {
    buildProductsTable();
    buildCommunityPending();
    buildLoyaltyLeaderboard();
    buildGeneratedRoutines();
    initCmsEditor();
    initQuizEditor();
  }
  if ('requestIdleCallback' in window) {
    requestIdleCallback(deferredInit, { timeout: 2000 });
  } else {
    setTimeout(deferredInit, 300);
  }
});

// ── Skin Results panel ────────────────────────────────────────────────────────

function filterSkinResults(q) {
  const term = q.toLowerCase().trim();
  document.querySelectorAll('#skinResultsTable tbody tr').forEach(row => {
    const name = row.dataset.name || '';
    const type = row.dataset.type || '';
    row.style.display = (!term || name.includes(term) || type.includes(term)) ? '' : 'none';
  });
}

function openSkinResultDetail(qr) {
  const user   = qr.user  || {};
  const scores = qr.skin_scores || {};
  const ans    = qr.answers || {};

  document.getElementById('srModal_name').textContent  = user.name  || 'Guest';
  document.getElementById('srModal_email').textContent = user.email || '';
  document.getElementById('srModal_type').textContent  = '🔬 ' + (qr.skin_type || '—') + ' Skin';

  const avatarEl = document.getElementById('srModal_avatar');
  avatarEl.innerHTML = user.avatar
    ? `<img src="${user.avatar}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`
    : (user.name || 'G')[0].toUpperCase();

  // Scores bars
  const metricOrder = ['Hydration','Acne Risk','Sensitivity','Oil Level','Barrier Health'];
  const metricColor = (m, v) => {
    const risky = ['Acne Risk','Sensitivity','Oil Level'].includes(m);
    return risky ? (v >= 7 ? '#e8382e' : v >= 5 ? '#f59e0b' : '#16a34a')
                 : (v >= 7 ? '#16a34a' : v >= 5 ? '#4f94ea' : '#e8382e');
  };
  document.getElementById('srModal_scores').innerHTML = metricOrder.map(m => {
    const v = scores[m] ? parseInt(scores[m]) : null;
    if (!v) return '';
    const c = metricColor(m, v);
    return `<div>
      <div style="display:flex;justify-content:space-between;font-size:.82rem;font-weight:600;margin-bottom:4px;">
        <span>${m}</span><span style="color:${c};font-weight:700">${v}/10</span>
      </div>
      <div style="height:6px;background:#f0f2f4;border-radius:3px;overflow:hidden;">
        <div style="height:100%;width:${v*10}%;background:${c};border-radius:3px;transition:width .6s ease;"></div>
      </div>
    </div>`;
  }).join('');

  // Answers grid
  const labelMap = {
    skin_feel:'Post-wash feel', shine:'Daytime shine', pores:'Pore size',
    severity:'Concern severity', reactivity:'Product reactivity', breakouts:'Breakout frequency',
    actives:'Using actives', water:'Daily water', sun:'Sun exposure',
    environment:'Environment', budget:'Budget', results_goal:'Results goal', treatments:'Open to treatments',
  };
  const concerns = Array.isArray(ans.concerns) ? ans.concerns.join(', ')
    : (typeof ans.concerns === 'string' ? ans.concerns : '—');
  const answersHtml = Object.entries(labelMap).map(([k, label]) => {
    const v = ans[k] ? String(ans[k]).replace(/_/g,' ') : '—';
    return `<div style="padding:10px 12px;background:#f8f9fa;border-radius:8px;">
      <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.4);margin-bottom:3px">${label}</div>
      <div style="font-size:.85rem;font-weight:600;text-transform:capitalize">${v}</div>
    </div>`;
  }).join('');
  document.getElementById('srModal_answers').innerHTML =
    `<div style="padding:10px 12px;background:#f8f9fa;border-radius:8px;grid-column:1/-1;">
      <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(10,10,10,.4);margin-bottom:3px">Concerns</div>
      <div style="font-size:.85rem;font-weight:600;text-transform:capitalize">${concerns || '—'}</div>
    </div>` + answersHtml;

  document.getElementById('skinResultDetailModal').style.display = 'block';
}

// ── Coupon / Voucher Management ────────────────────────────────────
let _allCoupons = [];

async function loadCoupons() {
  try {
    const res  = await fetch('{{ route("admin.coupons.index") }}', {
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (data.success) {
      _allCoupons = data.data;
      renderCoupons();
      updatePromoStats();
    }
  } catch {
    document.getElementById('admin-coupon-grid').innerHTML =
      '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#e63434;font-size:.85rem;">Failed to load coupons. Please refresh.</div>';
  }
}

function updatePromoStats() {
  const active  = _allCoupons.filter(c => c.active && !isCouponExpired(c)).length;
  const total   = _allCoupons.length;
  const uses    = _allCoupons.reduce((s, c) => s + (c.use_count || 0), 0);
  const inactive = _allCoupons.filter(c => !c.active || isCouponExpired(c)).length;
  document.getElementById('promo-stat-active').textContent  = active;
  document.getElementById('promo-stat-total').textContent   = total;
  document.getElementById('promo-stat-uses').textContent    = uses.toLocaleString();
  document.getElementById('promo-stat-expired').textContent = inactive;
}

function isCouponExpired(c) {
  if (!c.expiry_date) return false;
  return new Date().toISOString().slice(0,10) > c.expiry_date;
}

function couponStatusLabel(c) {
  if (!c.active)          return { text: 'Inactive', cls: 'cancelled' };
  if (isCouponExpired(c)) return { text: 'Expired',  cls: 'cancelled' };
  if (c.start_date && new Date().toISOString().slice(0,10) < c.start_date)
                          return { text: 'Scheduled', cls: 'pending' };
  return                         { text: 'Active',   cls: 'active' };
}

function discountLabel(c) {
  let label = '';
  switch (c.discount_type) {
    case 'percentage':    label = c.discount_value + '% off'; break;
    case 'fixed':         label = '₦' + Number(c.discount_value).toLocaleString() + ' off'; break;
    case 'free_shipping': label = 'Free shipping'; break;
    default:              label = c.discount_type;
  }
  if (c.free_shipping && c.discount_type !== 'free_shipping') label += ' + free shipping';
  return label;
}

function couponTypeInfo(type) {
  const map = {
    percentage:    { label: '% Off',         stripe: 'cs-lime', pill: 'ctp-pct',  icon: '📉' },
    fixed:         { label: '₦ Fixed',       stripe: 'cs-dark', pill: 'ctp-fix',  icon: '💰' },
    free_shipping: { label: 'Free Shipping', stripe: 'cs-blue', pill: 'ctp-ship', icon: '🚚' },
  };
  return map[type] || { label: 'Discount', stripe: 'cs-dark', pill: 'ctp-fix', icon: '🎟️' };
}

function formatExpiry(c) {
  if (!c.expiry_date) return 'No expiry date';
  const d = new Date(c.expiry_date + 'T00:00:00');
  return d.toLocaleDateString('en-NG', { day:'numeric', month:'short', year:'numeric' });
}

function restrictionLabel(r) {
  if (!r || r === 'all') return null;
  if (r === 'new_only')    return 'New customers only';
  const tier = r.replace('tier:', '');
  const names = { glow:'Glow Starter', radiant:'Radiant Insider', luxe:'Luxe Luminary' };
  return (names[tier] || tier.charAt(0).toUpperCase() + tier.slice(1)) + ' tier only';
}

function renderCoupons() {
  const filter = document.getElementById('promo-filter')?.value || 'all';
  let coupons  = _allCoupons;
  if (filter === 'active')   coupons = coupons.filter(c => c.active && !isCouponExpired(c));
  if (filter === 'inactive') coupons = coupons.filter(c => !c.active || isCouponExpired(c));

  const activeCount = _allCoupons.filter(c => c.active && !isCouponExpired(c)).length;
  document.getElementById('promo-coupon-subtext').textContent =
    activeCount + ' code' + (activeCount !== 1 ? 's' : '') + ' currently live';

  const grid = document.getElementById('admin-coupon-grid');
  if (!coupons.length) {
    grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:rgba(10,10,10,.28);">
      <div style="font-size:2.5rem;margin-bottom:12px;">🎟️</div>
      <div style="font-size:.95rem;font-weight:700;margin-bottom:6px;">No vouchers here</div>
      <div style="font-size:.8rem;">Try a different filter or create a new code</div>
    </div>`;
    return;
  }

  grid.innerHTML = coupons.map(c => {
    const status   = couponStatusLabel(c);
    const isActive = status.cls === 'active';
    const ti       = couponTypeInfo(c.discount_type);
    const dimOpacity = isActive ? '' : 'opacity:.55;';
    const usePct   = c.max_uses ? Math.min(100, Math.round(((c.use_count||0)/c.max_uses)*100)) : 0;
    const restrict = restrictionLabel(c.customer_restriction);
    const freeShipBadge = (c.free_shipping && c.discount_type !== 'free_shipping')
      ? `<div class="coupon-ship-badge">🚚 Incl. free shipping</div>` : '';
    const expiryStr = formatExpiry(c);
    const useLabel  = c.max_uses
      ? `${(c.use_count||0).toLocaleString()} / ${Number(c.max_uses).toLocaleString()} uses`
      : `${(c.use_count||0).toLocaleString()} uses (unlimited)`;
    const useBar = c.max_uses
      ? `<div class="coupon-use-bar-track"><div class="coupon-use-bar-fill" style="width:${usePct}%"></div></div>` : '';
    const toggleBtn = isActive
      ? `<button class="action-btn danger" title="Deactivate" onclick="toggleCouponStatus('${c.id}',false)" style="padding:6px 10px;">⏸</button>`
      : `<button class="action-btn edit"   title="Activate"   onclick="toggleCouponStatus('${c.id}',true)"  style="padding:6px 10px;">▶</button>`;

    return `
    <div class="coupon-card${isActive ? ' active-coupon' : ''}" style="${isActive ? '' : 'opacity:.7;'}">
      <div class="coupon-stripe ${ti.stripe}"></div>
      <div class="coupon-body">
        <div class="coupon-type-row">
          <span class="coupon-type-pill ${ti.pill}">${ti.icon} ${ti.label}</span>
          <span class="status-badge ${status.cls}">${status.text}</span>
        </div>
        <div class="coupon-code-box">
          <div class="coupon-code" style="${dimOpacity}">${c.code}</div>
          <div class="coupon-discount-big" style="${dimOpacity}">${discountLabel(c)}</div>
          ${freeShipBadge}
        </div>
        <div class="coupon-perf">
          <div class="coupon-perf-notch"></div>
          <div class="coupon-perf-line"></div>
          <div class="coupon-perf-notch"></div>
        </div>
        <div class="coupon-meta">
          <div class="coupon-meta-row">
            <span class="coupon-meta-icon">${c.expiry_date ? '📅' : '♾️'}</span>
            ${expiryStr}
          </div>
          ${c.min_order > 0 ? `<div class="coupon-meta-row"><span class="coupon-meta-icon">↪</span>Min. order ₦${Number(c.min_order).toLocaleString()}</div>` : ''}
          ${restrict ? `<div class="coupon-meta-row"><span class="coupon-meta-icon">👤</span>${restrict}</div>` : ''}
          <div class="coupon-meta-row" style="margin-top:3px;">
            <span class="coupon-meta-icon">📊</span>${useLabel}
          </div>
          ${useBar}
        </div>
      </div>
      <div class="coupon-foot">
        <button class="action-btn edit coupon-foot-edit" onclick="openEditCoupon('${c.id}')">✏️ Edit</button>
        ${toggleBtn}
        <button class="action-btn danger" title="Delete" onclick="deleteCoupon('${c.id}','${c.code}')" style="padding:6px 10px;">🗑️</button>
      </div>
    </div>`;
  }).join('') +
  `<div class="coupon-card-add" onclick="openCreateCoupon()">
    <div class="coupon-card-add-inner">
      <div class="coupon-card-add-icon">+</div>
      <div class="coupon-card-add-label">New Voucher</div>
      <div style="font-size:.72rem;margin-top:4px;color:rgba(10,10,10,.2);">Click to create</div>
    </div>
  </div>`;
}

function openCreateCoupon() {
  document.getElementById('couponModalTitle').textContent = 'Create Voucher';
  document.getElementById('coupon-save-btn').textContent = 'Create Voucher →';
  document.getElementById('coupon_edit_id').value = '';
  document.getElementById('coupon_code').value = '';
  document.getElementById('coupon_code').readOnly = false;
  document.getElementById('coupon_discount_type').value = 'percentage';
  document.getElementById('coupon_discount_value').value = '';
  document.getElementById('coupon_free_shipping').checked = false;
  document.getElementById('coupon_min_order').value = '';
  document.getElementById('coupon_max_uses').value = '';
  document.getElementById('coupon_uses_per_customer').value = '1';
  document.getElementById('coupon_description').value = '';
  document.getElementById('coupon_start_date').value = '';
  document.getElementById('coupon_expiry_date').value = '';
  document.getElementById('coupon_applicable_to').value = 'all';
  document.getElementById('coupon_customer_restriction').value = 'all';
  document.getElementById('coupon-modal-error').style.display = 'none';
  toggleDiscountValue();
  document.getElementById('couponModalOverlay').classList.add('open');
}

function openEditCoupon(id) {
  const c = _allCoupons.find(x => x.id === id);
  if (!c) return;
  document.getElementById('couponModalTitle').textContent = 'Edit Voucher';
  document.getElementById('coupon-save-btn').textContent = 'Save Changes →';
  document.getElementById('coupon_edit_id').value = c.id;
  document.getElementById('coupon_code').value = c.code;
  document.getElementById('coupon_code').readOnly = true;
  document.getElementById('coupon_discount_type').value = c.discount_type || 'percentage';
  document.getElementById('coupon_discount_value').value = c.discount_value || '';
  document.getElementById('coupon_free_shipping').checked = !!c.free_shipping;
  document.getElementById('coupon_min_order').value = c.min_order || '';
  document.getElementById('coupon_max_uses').value = c.max_uses || '';
  document.getElementById('coupon_uses_per_customer').value = c.uses_per_customer || 1;
  document.getElementById('coupon_description').value = c.description || '';
  document.getElementById('coupon_start_date').value = c.start_date || '';
  document.getElementById('coupon_expiry_date').value = c.expiry_date || '';
  document.getElementById('coupon_applicable_to').value = c.applicable_to || 'all';
  document.getElementById('coupon_customer_restriction').value = c.customer_restriction || 'all';
  document.getElementById('coupon-modal-error').style.display = 'none';
  toggleDiscountValue();
  document.getElementById('couponModalOverlay').classList.add('open');
}

function closeCouponModal() {
  document.getElementById('couponModalOverlay').classList.remove('open');
}

function toggleDiscountValue() {
  const type = document.getElementById('coupon_discount_type').value;
  const wrap  = document.getElementById('coupon_value_wrap');
  const label = document.getElementById('coupon_value_label');
  if (type === 'free_shipping') {
    wrap.style.display = 'none';
  } else {
    wrap.style.display = '';
    label.textContent = type === 'percentage' ? 'Discount Value (%)' : 'Discount Value (₦)';
  }
}

async function saveCoupon() {
  const editId = document.getElementById('coupon_edit_id').value;
  const isEdit = !!editId;
  const errEl  = document.getElementById('coupon-modal-error');
  errEl.style.display = 'none';

  const code = document.getElementById('coupon_code').value.trim().toUpperCase();
  if (!code) { errEl.textContent = 'Coupon code is required.'; errEl.style.display = 'block'; return; }

  const discountType = document.getElementById('coupon_discount_type').value;
  const discountValue = document.getElementById('coupon_discount_value').value;
  if (discountType !== 'free_shipping' && (!discountValue || parseFloat(discountValue) <= 0)) {
    errEl.textContent = 'Discount value must be greater than 0.'; errEl.style.display = 'block'; return;
  }

  const payload = {
    code,
    discount_type:        discountType,
    discount_value:       discountType !== 'free_shipping' ? parseFloat(discountValue) : 0,
    free_shipping:        document.getElementById('coupon_free_shipping').checked ? 1 : 0,
    min_order:            document.getElementById('coupon_min_order').value || 0,
    max_uses:             document.getElementById('coupon_max_uses').value || '',
    uses_per_customer:    document.getElementById('coupon_uses_per_customer').value || 1,
    description:          document.getElementById('coupon_description').value.trim(),
    start_date:           document.getElementById('coupon_start_date').value || '',
    expiry_date:          document.getElementById('coupon_expiry_date').value || '',
    applicable_to:        document.getElementById('coupon_applicable_to').value,
    customer_restriction: document.getElementById('coupon_customer_restriction').value,
  };

  const btn = document.getElementById('coupon-save-btn');
  btn.disabled = true; btn.textContent = 'Saving…';

  try {
    const url    = isEdit ? '{{ url("admin/coupons") }}/' + editId : '{{ route("admin.coupons.store") }}';
    const method = isEdit ? 'PUT' : 'POST';
    const res    = await fetch(url, {
      method,
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    if (data.success) {
      closeCouponModal();
      showToast('🎟️', isEdit ? 'Voucher updated!' : 'Voucher created and activated!');
      await loadCoupons();
    } else {
      errEl.textContent = data.message || 'Something went wrong.';
      errEl.style.display = 'block';
    }
  } catch {
    errEl.textContent = 'Network error. Please try again.';
    errEl.style.display = 'block';
  }
  btn.disabled = false;
  btn.textContent = isEdit ? 'Save Changes →' : 'Create Voucher →';
}

async function deleteCoupon(id, code) {
  if (!confirm('Delete voucher "' + code + '"? This cannot be undone.')) return;
  try {
    const res  = await fetch('{{ url("admin/coupons") }}/' + id, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
    });
    const data = await res.json();
    if (data.success) {
      showToast('🗑️', 'Voucher "' + code + '" deleted.');
      await loadCoupons();
    } else {
      showToast('❌', data.message || 'Could not delete.');
    }
  } catch {
    showToast('❌', 'Network error.');
  }
}

async function toggleCouponStatus(id, activate) {
  try {
    const res  = await fetch('{{ url("admin/coupons") }}/' + id, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify({ active: activate }),
    });
    const data = await res.json();
    if (data.success) {
      showToast(activate ? '✅' : '⏸', activate ? 'Voucher activated!' : 'Voucher deactivated.');
      await loadCoupons();
    }
  } catch {
    showToast('❌', 'Network error.');
  }
}

// ── Gift Card Admin ──────────────────────────────────────────────────────────

let GC_CACHE = [];
let GC_DENOMS = [];
let GC_DENOM_STATS = [];

async function gcLoadAll() {
  const btn = document.getElementById('gc-refresh-btn');
  if (btn) { btn.textContent = '↺ Loading…'; btn.disabled = true; }
  try {
    const res  = await fetch('{{ route("admin.gift-cards.index") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const data = await res.json();
    if (data.success) {
      GC_CACHE       = data.data || [];
      GC_DENOMS      = data.denominations || [];
      GC_DENOM_STATS = data.denomination_stats || [];
      gcRenderStats(data.stats || {});
      gcRenderDenominations();
      gcRenderTable(GC_CACHE);
    }
  } catch(e) {
    document.getElementById('gc-table-wrap').innerHTML = '<p style="color:#e8382e;padding:20px">Failed to load gift cards.</p>';
  } finally {
    if (btn) { btn.textContent = '↺ Refresh'; btn.disabled = false; }
  }
}

function gcRenderStats(s) {
  const fmtN = n => '₦' + Number(n||0).toLocaleString();
  const set  = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
  set('gcStatValue',       fmtN(s.totalValue));
  set('gcStatTotal',       s.totalSold ?? 0);
  set('gcStatRedeemed',    s.redeemed ?? 0);
  set('gcStatOutstanding', s.outstanding ?? 0);
  set('gcStatRate',        (s.rate ?? 0) + '%');
}

function gcRenderDenominations() {
  const grid = document.getElementById('gcDenomGrid');
  if (!grid) return;
  if (!GC_DENOMS.length) {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:20px;color:rgba(10,10,10,.35)">No denominations yet. Add one above.</div>';
    return;
  }
  const statsMap = {};
  GC_DENOM_STATS.forEach(s => { statsMap[s.amount] = s; });

  grid.innerHTML = GC_DENOMS.map(d => {
    const s        = statsMap[d.amount] || { sold: 0, revenue: 0 };
    const inactive = !d.is_active;
    const popular  = d.is_popular;
    return `<div class="gc-admin-denomination" style="${inactive ? 'opacity:.5;' : ''}${popular ? 'border-color:var(--rose,#893941);' : ''}">
      <div class="gc-admin-denom-vis">
        <div class="gc-admin-denom-vis-inner">
          <div class="gc-admin-denom-vis-brand">KOMINHOO.</div>
          <div class="gc-admin-denom-vis-amt">₦${Number(d.amount).toLocaleString()}</div>
          <div class="gc-admin-denom-vis-tag">Gift Card</div>
        </div>
      </div>
      <div class="gc-admin-denom-body">
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px">
          <div class="amount">₦${Number(d.amount).toLocaleString()}</div>
          ${popular ? `<span style="background:var(--rose,#893941);color:#fff;font-size:.58rem;font-weight:700;padding:2px 7px;border-radius:999px">Popular</span>` : ''}
          ${inactive ? `<span style="background:#f3f4f6;color:#6b7280;font-size:.58rem;font-weight:700;padding:2px 7px;border-radius:999px">Disabled</span>` : ''}
        </div>
        ${d.label ? `<div style="font-size:.78rem;color:rgba(10,10,10,.55);margin-bottom:4px">${d.label}</div>` : ''}
        <div class="sold">${s.sold} sold</div>
        <div class="revenue">₦${Number(s.revenue).toLocaleString()} revenue</div>
        <div style="margin-top:10px;display:flex;gap:6px;flex-wrap:wrap">
          <button class="action-btn edit" style="font-size:.7rem;padding:4px 10px" onclick="gcToggleDenom('${d.id}',${d.is_active})">${d.is_active ? 'Disable' : 'Enable'}</button>
          <button class="action-btn danger" style="font-size:.7rem;padding:4px 10px" onclick="gcDeleteDenom('${d.id}')">Remove</button>
        </div>
      </div>
    </div>`;
  }).join('');
}

function gcStatusBadge(status) {
  const map = {
    active:         '<span class="status-badge active">Active</span>',
    partially_used: '<span class="status-badge" style="background:#ede9fe;color:#5b21b6">Partial</span>',
    redeemed:       '<span class="status-badge" style="background:#fef3c7;color:#92400e">Redeemed</span>',
    expired:        '<span class="status-badge" style="background:#f3f4f6;color:#6b7280">Expired</span>',
  };
  return map[status] || `<span class="status-badge">${status}</span>`;
}

function gcRenderTable(cards) {
  const wrap = document.getElementById('gc-table-wrap');
  if (!wrap) return;
  if (!cards.length) {
    wrap.innerHTML = '<p style="color:rgba(10,10,10,.35);padding:24px 0;text-align:center">No gift cards found.</p>';
    return;
  }
  wrap.innerHTML = `<table class="data-table"><thead><tr>
    <th>Code</th><th>Value</th><th>Balance</th><th>Purchased By</th><th>Sent To</th><th>Issued</th><th>Expires</th><th>Status</th><th>Actions</th>
  </tr></thead><tbody>
  ${cards.map(c => {
    const date = c.created_at ? new Date(c.created_at).toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'}) : '—';
    return `<tr>
      <td><code style="font-size:.78rem;background:#f4f5f7;padding:2px 8px;border-radius:4px">${c.code}</code></td>
      <td style="font-weight:700">₦${Number(c.amount).toLocaleString()}</td>
      <td style="font-weight:700;color:${c.balance>0?'#15803d':'#9ca3af'}">₦${Number(c.balance||0).toLocaleString()}</td>
      <td>${c.purchaser_name||'—'}<br><span style="font-size:.72rem;color:rgba(10,10,10,.45)">${c.purchaser_email||''}</span></td>
      <td>${c.recipient_name||'—'}<br><span style="font-size:.72rem;color:rgba(10,10,10,.45)">${c.recipient_email||''}</span></td>
      <td>${date}</td>
      <td>${c.expires_at||'—'}</td>
      <td>${gcStatusBadge(c.status)}</td>
      <td><div style="display:flex;gap:4px">
        <button class="action-btn edit" style="font-size:.72rem;padding:3px 8px" onclick="gcCopyCode('${c.code}')">Copy</button>
        <button class="action-btn danger" style="font-size:.72rem;padding:3px 8px" onclick="gcDeleteCard('${c.id}')">Delete</button>
      </div></td>
    </tr>`;
  }).join('')}
  </tbody></table>`;
}

function gcFilterTable() {
  const q      = (document.getElementById('gc-search')?.value || '').toLowerCase();
  const status = document.getElementById('gc-filter-status')?.value || '';
  const filtered = GC_CACHE.filter(c => {
    const matchQ = !q || [c.code, c.purchaser_name, c.purchaser_email, c.recipient_name, c.recipient_email].some(v => (v||'').toLowerCase().includes(q));
    const matchS = !status || c.status === status;
    return matchQ && matchS;
  });
  gcRenderTable(filtered);
}

async function gcSaveDenomination() {
  const amount = parseInt(document.getElementById('gcDenomAmount')?.value);
  const errEl  = document.getElementById('gcDenomError');
  if (!amount || amount < 1000) { errEl.textContent = 'Amount must be at least ₦1,000'; errEl.style.display = 'block'; return; }
  errEl.style.display = 'none';
  const btn = document.getElementById('gcSaveDenomBtn');
  btn.disabled = true; btn.textContent = 'Saving…';
  try {
    const res  = await fetch('{{ route("admin.gift-cards.denominations.store") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({
        amount,
        label:       document.getElementById('gcDenomLabel')?.value || '',
        description: document.getElementById('gcDenomDesc')?.value  || '',
        is_popular:  document.getElementById('gc-popular-chk')?.checked || false,
      })
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('gcAddOverlay').classList.remove('open');
      document.getElementById('gcDenomAmount').value = '';
      document.getElementById('gcDenomLabel').value  = '';
      document.getElementById('gcDenomDesc').value   = '';
      document.getElementById('gc-popular-chk').checked = false;
      showToast('✓', 'Denomination added!');
      gcLoadAll();
    } else {
      errEl.textContent = data.message || 'Failed to save.'; errEl.style.display = 'block';
    }
  } catch {
    errEl.textContent = 'Network error.'; errEl.style.display = 'block';
  } finally {
    btn.disabled = false; btn.textContent = 'Save Denomination';
  }
}

async function gcToggleDenom(id, currentActive) {
  try {
    const res  = await fetch(`{{ url('admin/gift-cards/denominations') }}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ is_active: !currentActive })
    });
    const data = await res.json();
    if (data.success) { showToast('✓', currentActive ? 'Denomination disabled.' : 'Denomination enabled.'); gcLoadAll(); }
    else showToast('❌', data.message || 'Failed.');
  } catch { showToast('❌', 'Network error.'); }
}

async function gcDeleteDenom(id) {
  if (!confirm('Remove this denomination? Existing gift cards are not affected.')) return;
  try {
    const denoms = GC_DENOMS;
    const idx = denoms.findIndex(d => d.id === id);
    if (idx === -1) return;
    const updated = [...denoms.slice(0, idx), ...denoms.slice(idx + 1)];
    GC_DENOMS = updated;
    gcRenderDenominations();
    await fetch(`{{ url('admin/gift-cards/denominations') }}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ is_active: false })
    });
    showToast('✓', 'Denomination removed.');
    gcLoadAll();
  } catch { showToast('❌', 'Network error.'); }
}

async function gcIssueCard() {
  const amount   = parseInt(document.getElementById('gcIssueAmount')?.value);
  const recName  = document.getElementById('gcIssueRecipientName')?.value.trim();
  const recEmail = document.getElementById('gcIssueRecipientEmail')?.value.trim();
  const errEl    = document.getElementById('gcIssueError');
  errEl.style.display = 'none';
  if (!amount || amount < 1000) { errEl.textContent = 'Amount must be at least ₦1,000'; errEl.style.display = 'block'; return; }
  if (!recName)  { errEl.textContent = 'Recipient name is required'; errEl.style.display = 'block'; return; }
  if (!recEmail) { errEl.textContent = 'Recipient email is required'; errEl.style.display = 'block'; return; }
  const btn = document.getElementById('gcIssueSaveBtn');
  btn.disabled = true; btn.textContent = 'Issuing…';
  try {
    const res  = await fetch('{{ route("admin.gift-cards.store") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({
        amount,
        recipient_name:  recName,
        recipient_email: recEmail,
        message: document.getElementById('gcIssueMessage')?.value || '',
      })
    });
    const data = await res.json();
    if (data.success) {
      const resultEl = document.getElementById('gcIssueResult');
      document.getElementById('gcIssuedCode').textContent = data.data.code;
      document.getElementById('gcIssuedMeta').textContent = `₦${Number(data.data.amount).toLocaleString()} · Expires ${data.data.expires_at}`;
      resultEl.style.display = 'block';
      btn.textContent = 'Issue Another';
      btn.disabled = false;
      document.getElementById('gcIssueAmount').value = '';
      document.getElementById('gcIssueRecipientName').value = '';
      document.getElementById('gcIssueRecipientEmail').value = '';
      document.getElementById('gcIssueMessage').value = '';
      gcLoadAll();
    } else {
      errEl.textContent = data.message || 'Failed to issue.'; errEl.style.display = 'block';
      btn.disabled = false; btn.textContent = 'Issue Gift Card';
    }
  } catch {
    errEl.textContent = 'Network error.'; errEl.style.display = 'block';
    btn.disabled = false; btn.textContent = 'Issue Gift Card';
  }
}

async function gcDeleteCard(id) {
  if (!confirm('Delete this gift card? This cannot be undone.')) return;
  try {
    const res  = await fetch(`{{ url('admin/gift-cards') }}/${id}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    });
    const data = await res.json();
    if (data.success) { showToast('✓', 'Gift card deleted.'); gcLoadAll(); }
    else showToast('❌', data.message || 'Failed.');
  } catch { showToast('❌', 'Network error.'); }
}

function gcCopyCode(code) {
  navigator.clipboard.writeText(code).then(() => showToast('✓', `Code ${code} copied!`));
}

function gcExportCsv() {
  if (!GC_CACHE.length) { showToast('ℹ️', 'No gift cards to export.'); return; }
  const headers = ['Code','Amount','Balance','Status','Purchaser Name','Purchaser Email','Recipient Name','Recipient Email','Message','Issued','Expires'];
  const rows = GC_CACHE.map(c => [
    c.code, c.amount, c.balance, c.status,
    c.purchaser_name, c.purchaser_email, c.recipient_name, c.recipient_email,
    (c.message||'').replace(/,/g,';'),
    (c.created_at||'').substring(0,10), c.expires_at||''
  ].map(v => `"${v}"`).join(','));
  const csv  = [headers.join(','), ...rows].join('\n');
  const blob = new Blob([csv], { type: 'text/csv' });
  const url  = URL.createObjectURL(blob);
  const a    = document.createElement('a');
  a.href = url; a.download = `gift_cards_${new Date().toISOString().substring(0,10)}.csv`;
  a.click(); URL.revokeObjectURL(url);
}

// ── Influencers ───────────────────────────────────────
const INF_LIST_URL   = '{{ route("admin.influencers.index") }}';
const INF_STATUS_URL = id => `{{ url("admin/influencers") }}/${id}/status`;
const INF_DELETE_URL = id => `{{ url("admin/influencers") }}/${id}`;

let _infData = [];
let _infCurrent = null;

async function loadInfluencers() {
  try {
    const r = await fetch(INF_LIST_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'include' });
    const j = await r.json();
    _infData = Array.isArray(j.data) ? j.data : [];
    updateInfStats();
    renderInfluencerTable();
    // Show badge for pending count
    const pending = _infData.filter(a => a.status === 'pending').length;
    const badge = document.getElementById('inf-nav-badge');
    if (badge) {
      if (pending > 0) { badge.textContent = pending; badge.style.display = ''; }
      else { badge.style.display = 'none'; }
    }
  } catch (e) {
    document.getElementById('inf-tbody').innerHTML = '<tr><td colspan="8" style="text-align:center;padding:40px;color:rgba(10,10,10,.4)">Failed to load applications.</td></tr>';
  }
}

function updateInfStats() {
  document.getElementById('inf-stat-total').textContent    = _infData.length;
  document.getElementById('inf-stat-pending').textContent  = _infData.filter(a => a.status === 'pending').length;
  document.getElementById('inf-stat-approved').textContent = _infData.filter(a => a.status === 'approved').length;
  document.getElementById('inf-stat-rejected').textContent = _infData.filter(a => a.status === 'rejected').length;
}

function renderInfluencerTable() {
  const q      = (document.getElementById('inf-search')?.value || '').toLowerCase();
  const status = document.getElementById('inf-filter-status')?.value || 'all';
  const niche  = document.getElementById('inf-filter-niche')?.value  || 'all';

  const filtered = _infData.filter(a => {
    if (status !== 'all' && a.status !== status) return false;
    if (niche  !== 'all' && a.niche  !== niche)  return false;
    if (q && !`${a.name} ${a.email} ${a.instagram} ${a.tiktok}`.toLowerCase().includes(q)) return false;
    return true;
  });

  const statusStyles = {
    pending:  'background:#fff7ed;color:#ea580c;',
    approved: 'background:#f0fdf4;color:#16a34a;',
    rejected: 'background:#fff1f1;color:#e63434;',
  };

  if (!filtered.length) {
    document.getElementById('inf-tbody').innerHTML = '<tr><td colspan="8" style="text-align:center;padding:48px;color:rgba(10,10,10,.35);font-size:.88rem">No applications found.</td></tr>';
    return;
  }

  document.getElementById('inf-tbody').innerHTML = filtered.map(a => {
    const initials  = (a.name || 'U').charAt(0).toUpperCase();
    const style     = statusStyles[a.status] || 'background:#f4f5f7;color:#555;';
    const submitted = a.submitted_at ? new Date(a.submitted_at).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }) : '—';
    return `<tr>
      <td>
        <div style="display:flex;align-items:center;gap:12px">
          <div style="width:36px;height:36px;border-radius:50%;background:var(--black);color:var(--lime);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0">${initials}</div>
          <div>
            <div style="font-weight:700;font-size:.88rem">${escHtml(a.name)}</div>
            <div style="font-size:.72rem;color:rgba(10,10,10,.4)">${escHtml(a.email)}</div>
          </div>
        </div>
      </td>
      <td style="font-size:.82rem">
        <div style="font-weight:600">${escHtml(a.instagram || '—')}</div>
        ${a.tiktok ? `<div style="color:rgba(10,10,10,.45);font-size:.74rem">${escHtml(a.tiktok)}</div>` : ''}
      </td>
      <td style="font-size:.82rem;font-weight:600">${escHtml(a.followers || '—')}</td>
      <td style="font-size:.82rem">${escHtml(a.niche || '—')}</td>
      <td style="font-size:.82rem">${escHtml(a.location || '—')}</td>
      <td><span style="font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px;${style}">${a.status.charAt(0).toUpperCase() + a.status.slice(1)}</span></td>
      <td style="font-size:.78rem;color:rgba(10,10,10,.45)">${submitted}</td>
      <td><button class="action-btn edit" onclick='openInfModal(${JSON.stringify(a)})'>View</button></td>
    </tr>`;
  }).join('');
}

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function openInfModal(app) {
  _infCurrent = app;
  const statusStyles = {
    pending:  'background:#fff7ed;color:#ea580c;',
    approved: 'background:#f0fdf4;color:#16a34a;',
    rejected: 'background:#fff1f1;color:#e63434;',
  };
  document.getElementById('infM_avatar').textContent   = (app.name || 'U').charAt(0).toUpperCase();
  document.getElementById('infM_name').textContent     = app.name || '—';
  document.getElementById('infM_email').textContent    = app.email || '—';
  document.getElementById('infM_phone').textContent    = app.phone || '—';
  document.getElementById('infM_location').textContent = app.location || '—';
  document.getElementById('infM_instagram').textContent= app.instagram || '—';
  document.getElementById('infM_tiktok').textContent   = app.tiktok || '—';
  document.getElementById('infM_followers').textContent= app.followers || '—';
  document.getElementById('infM_niche').textContent    = app.niche || '—';
  document.getElementById('infM_skin').textContent     = app.skin_type || '—';
  document.getElementById('infM_message').textContent  = app.message || '—';
  document.getElementById('infM_notes').value          = app.notes || '';
  const submitted = app.submitted_at ? new Date(app.submitted_at).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '—';
  document.getElementById('infM_date').textContent     = submitted;
  const sEl = document.getElementById('infM_status_badge');
  sEl.textContent = app.status.charAt(0).toUpperCase() + app.status.slice(1);
  sEl.style.cssText = `font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:999px;display:inline-block;${statusStyles[app.status] || ''}`;
  document.getElementById('infModal').style.display = '';
}

function closeInfModal() {
  document.getElementById('infModal').style.display = 'none';
  _infCurrent = null;
}

async function updateInfStatus(status) {
  if (!_infCurrent) return;
  const notes = document.getElementById('infM_notes').value;
  try {
    const r = await fetch(INF_STATUS_URL(_infCurrent.id), {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': ADMIN_CSRF(), 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'include',
      body: JSON.stringify({ status, notes }),
    });
    if (!r.ok) throw new Error();
    _infCurrent.status = status;
    _infCurrent.notes  = notes;
    // Update local array
    const idx = _infData.findIndex(a => a.id === _infCurrent.id);
    if (idx !== -1) { _infData[idx].status = status; _infData[idx].notes = notes; }
    closeInfModal();
    updateInfStats();
    renderInfluencerTable();
    // Update sidebar badge
    const pending = _infData.filter(a => a.status === 'pending').length;
    const badge = document.getElementById('inf-nav-badge');
    if (badge) { if (pending > 0) { badge.textContent = pending; badge.style.display = ''; } else { badge.style.display = 'none'; } }
  } catch (e) { alert('Failed to update status. Please try again.'); }
}

async function deleteInfluencer() {
  if (!_infCurrent) return;
  if (!confirm(`Delete application from ${_infCurrent.name}? This cannot be undone.`)) return;
  try {
    const r = await fetch(INF_DELETE_URL(_infCurrent.id), {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': ADMIN_CSRF(), 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'include',
    });
    if (!r.ok) throw new Error();
    _infData = _infData.filter(a => a.id !== _infCurrent.id);
    closeInfModal();
    updateInfStats();
    renderInfluencerTable();
  } catch (e) { alert('Failed to delete. Please try again.'); }
}

function exportInfluencersCsv() {
  if (!_infData.length) { alert('No applications to export.'); return; }
  const headers = ['Name','Email','Phone','Instagram','TikTok','Followers','Niche','Location','Skin Type','Status','Submitted','Notes'];
  const rows = _infData.map(a => [
    a.name, a.email, a.phone, a.instagram, a.tiktok, a.followers, a.niche, a.location, a.skin_type, a.status,
    a.submitted_at ? new Date(a.submitted_at).toLocaleDateString() : '', a.notes
  ].map(v => `"${String(v || '').replace(/"/g,'""')}"`).join(','));
  const csv  = [headers.join(','), ...rows].join('\n');
  const blob = new Blob([csv], { type: 'text/csv' });
  const url  = URL.createObjectURL(blob);
  const a    = document.createElement('a');
  a.href = url; a.download = `influencer_applications_${new Date().toISOString().substring(0,10)}.csv`;
  a.click(); URL.revokeObjectURL(url);
}

// ── Security Events ───────────────────────────────────
const SEC_EVENTS_URL  = '{{ route("admin.security.events") }}';
const SEC_CLEAR_URL   = '{{ route("admin.security.events.clear") }}';
const ADMIN_CSRF      = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

const SEC_TYPE_ICONS = {
  password_change:           { icon:'🔑', color:'#dbeafe', label:'Password Changed' },
  settings_change:           { icon:'⚙️', color:'#f0fdf4', label:'Settings Changed' },
  account_deletion_request:  { icon:'⚠️', color:'#fee2e2', label:'Deletion Request' },
};

function secLoadEvents() {
  const type = document.getElementById('sec-type-filter')?.value || '';
  const url  = SEC_EVENTS_URL + (type ? '?type=' + type : '');
  fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': ADMIN_CSRF() } })
    .then(r => r.json())
    .then(d => {
      const events = d.data || [];
      const total  = d.total || 0;
      const high   = d.high_severity || 0;

      document.getElementById('sec-kpi-total').textContent = total;
      document.getElementById('sec-kpi-high').textContent  = high;

      // Count today
      const today = new Date().toISOString().substring(0,10);
      const todayCount = events.filter(e => (e.created_at || '').startsWith(today)).length;
      document.getElementById('sec-kpi-today').textContent = todayCount;

      document.getElementById('sec-event-count').textContent = total + ' event' + (total !== 1 ? 's' : '') + ' logged';

      // Admin sidebar badge
      const badge = document.getElementById('sec-events-badge');
      if (badge) { badge.textContent = high; badge.style.display = high ? '' : 'none'; }

      if (!events.length) {
        document.getElementById('sec-events-list').innerHTML = '<div style="text-align:center;padding:60px;color:#9CA3AF;font-size:.9rem">No security events recorded yet.</div>';
        return;
      }

      document.getElementById('sec-events-list').innerHTML = events.map(e => {
        const meta  = SEC_TYPE_ICONS[e.type] || { icon:'🔐', color:'#f4f5f7', label: e.type };
        const when  = e.created_at ? new Date(e.created_at).toLocaleString('en-NG', { dateStyle:'medium', timeStyle:'short' }) : '—';
        const sev   = e.severity === 'high'
          ? '<span style="background:#fee2e2;color:#991b1b;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:6px">HIGH</span>'
          : '';
        return `
        <div class="activity-item" style="padding:14px 22px;border-bottom:1px solid #f4f5f7">
          <div class="activity-dot" style="background:${meta.color};font-size:.9rem;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">${meta.icon}</div>
          <div class="activity-text" style="flex:1">
            <strong style="font-size:.85rem;font-weight:600">${meta.label}${sev}</strong>
            <p style="font-size:.78rem;color:rgba(10,10,10,.45);margin-top:2px">${e.description || '—'}</p>
            <div style="font-size:.72rem;color:rgba(10,10,10,.35);margin-top:4px;display:flex;gap:14px;flex-wrap:wrap">
              <span>👤 ${e.user_name || '—'} &lt;${e.user_email || ''}&gt;</span>
              <span>🌐 ${e.ip || '—'}</span>
            </div>
          </div>
          <div class="activity-time" style="font-size:.72rem;color:rgba(10,10,10,.35);white-space:nowrap">${when}</div>
        </div>`;
      }).join('');
    })
    .catch(() => {
      document.getElementById('sec-events-list').innerHTML = '<div style="text-align:center;padding:40px;color:#EF4444">Could not load security events.</div>';
    });
}

function secClearEvents() {
  if (!confirm('Clear all security events? This cannot be undone.')) return;
  fetch(SEC_CLEAR_URL, {
    method: 'DELETE',
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': ADMIN_CSRF() },
  })
  .then(r => r.json())
  .then(d => {
    if (d.success) { showToast('🗑️', 'Security event log cleared.'); secLoadEvents(); }
    else showToast('❌', d.message || 'Could not clear log.');
  });
}

// Load influencer pending badge on idle
(function loadInfBadge() {
  function fetchBadge() {
    fetch(INF_LIST_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'include' })
      .then(r => r.json())
      .then(d => {
        const apps    = Array.isArray(d.data) ? d.data : [];
        const pending = apps.filter(a => a.status === 'pending').length;
        const badge   = document.getElementById('inf-nav-badge');
        if (badge) { badge.textContent = pending; badge.style.display = pending ? '' : 'none'; }
      })
      .catch(() => {});
  }
  if ('requestIdleCallback' in window) {
    requestIdleCallback(fetchBadge, { timeout: 4000 });
  } else {
    setTimeout(fetchBadge, 800);
  }
})();

// Load badge count after page is idle — non-blocking
(function loadSecBadge() {
  function fetchBadge() {
    fetch(SEC_EVENTS_URL, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': ADMIN_CSRF() } })
      .then(r => r.json())
      .then(d => {
        const high  = d.high_severity || 0;
        const badge = document.getElementById('sec-events-badge');
        if (badge) { badge.textContent = high; badge.style.display = high ? '' : 'none'; }
      })
      .catch(() => {});
  }
  if ('requestIdleCallback' in window) {
    requestIdleCallback(fetchBadge, { timeout: 3000 });
  } else {
    setTimeout(fetchBadge, 500);
  }
})();

// ══════════════════════════════════════════════════════════════════════
// WALLET MANAGEMENT  (proxied through /admin/wallet/* frontend routes)
// ══════════════════════════════════════════════════════════════════════
const WALLET_CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

function walletFetch(path, opts = {}) {
  const isWrite = opts.method && opts.method !== 'GET';
  return fetch(ADMIN_URL + '/wallet' + path, {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...(isWrite ? { 'X-CSRF-TOKEN': WALLET_CSRF } : {}),
      ...opts.headers
    },
    credentials: 'include',
    ...opts
  }).then(r => r.json().catch(() => ({ success: false, message: 'Server error (' + r.status + ')' })));
}

function walletFmtAmt(n) {
  return '₦' + parseFloat(n || 0).toLocaleString('en-NG', { minimumFractionDigits: 2 });
}
function walletFmtDate(s) {
  return s ? new Date(s).toLocaleString('en-GB', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '—';
}
function walletShowMsg(el, type, text) {
  if (!el) return;
  el.style.display    = 'block';
  el.style.background = type === 'success' ? '#dcfce7' : type === 'error' ? '#fef2f2' : '#eff6ff';
  el.style.color      = type === 'success' ? '#166534' : type === 'error' ? '#991b1b' : '#1e40af';
  el.style.border     = '1px solid ' + (type === 'success' ? '#86efac' : type === 'error' ? '#fca5a5' : '#bfdbfe');
  el.textContent      = text;
}

// ── Overview (called when wallet panel first opens) ──
function walletLoadOverview() {
  walletLoadWallets();

  walletFetch('/transactions?per_page=1')
    .then(d => {
      const el = document.getElementById('wkpi-tx-count');
      if (el) el.textContent = d.success ? (d.data?.total || 0).toLocaleString() : '—';
    }).catch(() => {});

  walletFetch('/transactions?status=pending&per_page=1')
    .then(d => {
      const el = document.getElementById('wkpi-pending');
      if (el) el.textContent = d.success ? (d.data?.total ?? '0') : '—';
    }).catch(() => {});

  walletFetch('/bonus-stats')
    .then(d => {
      if (!d.success) return;
      const safe = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val ?? '—'; };
      safe('wkpi-zero-bal',         d.data?.zero_balance_wallets);
      safe('w-stat-missing-signup', d.data?.missing_signup_bonus);
      safe('w-stat-zero-bal',       d.data?.zero_balance_wallets);
    }).catch(() => {});
}

// ── Shared tx row renderer (mirrors user-side exactly) ──
const W_TX_ICONS = { deposit:'💸', purchase:'🛍️', signup_bonus:'🎁', first_deposit_bonus:'🎉', referral_bonus:'👥', admin_bonus:'⭐', campaign_bonus:'📢', refund:'↩️' };
function walletTxRows(txList) {
  if (!txList.length) return '<div style="text-align:center;padding:48px 24px;color:#9CA3AF"><div style="font-size:2rem;margin-bottom:10px">💳</div><div style="font-weight:700;margin-bottom:4px">No transactions yet</div></div>';
  return txList.map(tx => {
    const type     = tx.transaction_type || 'credit';
    const status   = tx.status || 'successful';
    const isCredit = ['credit','bonus','refund','reversal'].includes(type);
    const cat      = tx.category || type;
    const icon     = W_TX_ICONS[cat] || '💳';
    const catLabel = cat.replace(/_/g,' ');
    const iconBg   = isCredit ? 'rgba(34,197,94,.1)' : status === 'pending' ? 'rgba(107,114,128,.08)' : 'rgba(232,20,60,.08)';
    const amtClr   = status === 'failed' ? '#9CA3AF' : isCredit ? '#16A34A' : '#DC2626';
    const sBg      = status === 'successful' ? 'rgba(34,197,94,.1)' : status === 'pending' ? 'rgba(245,158,11,.1)' : 'rgba(220,38,38,.08)';
    const sClr     = status === 'successful' ? '#16A34A' : status === 'pending' ? '#D97706' : '#DC2626';
    const desc     = tx.description || catLabel.charAt(0).toUpperCase() + catLabel.slice(1);
    const date     = walletFmtDate(tx.created_at);
    const amt      = parseFloat(tx.amount || 0).toLocaleString('en-NG', { minimumFractionDigits:2 });
    return `<div class="w-tx-row" data-type="${type}"
         style="display:flex;align-items:center;gap:14px;padding:14px 24px;border-bottom:1px solid #f3f4f6;transition:.15s"
         onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
      <div style="width:42px;height:42px;border-radius:11px;display:grid;place-items:center;font-size:1.1rem;flex-shrink:0;background:${iconBg}">${icon}</div>
      <div style="flex:1;min-width:0">
        <div style="font-size:.88rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${desc}</div>
        <div style="font-size:.72rem;color:#9CA3AF;margin-top:3px;display:flex;align-items:center;gap:7px;flex-wrap:wrap">
          <span>${date}</span>
          <span style="background:#f3f4f6;padding:2px 7px;border-radius:999px;font-size:.6rem;font-weight:700;text-transform:uppercase">${catLabel}</span>
          <span style="background:${sBg};color:${sClr};padding:2px 7px;border-radius:999px;font-size:.6rem;font-weight:700">${status}</span>
        </div>
      </div>
      <div style="text-align:right;flex-shrink:0;font-size:.92rem;font-weight:700;color:${amtClr}">${isCredit?'+':'−'}₦${amt}</div>
    </div>`;
  }).join('');
}

// ── Wallets tab — card grid ──
const _wCache = {};   // walletId → wallet object, populated on load
let wWalletPage = 1;
function walletLoadWallets(page) {
  page = page || 1; wWalletPage = page;
  const status = document.getElementById('w-status-filter')?.value || '';
  const search = document.getElementById('w-wallet-search')?.value.trim() || '';
  const grid   = document.getElementById('w-wallets-grid');
  const count  = document.getElementById('w-wallet-count');
  if (grid) grid.innerHTML = '<div style="color:#9CA3AF;padding:32px;text-align:center;grid-column:1/-1">Loading…</div>';

  let qs = '?per_page=18&page=' + page;
  if (status) qs += '&status=' + status;
  if (search) qs += '&search=' + encodeURIComponent(search);

  walletFetch('/wallets' + qs)
    .then(d => {
      // Surface backend errors clearly instead of silently showing empty state
      if (!d.success && !d.data) {
        const msg = d.message || 'Backend unavailable — check that the backend is running.';
        if (grid) grid.innerHTML = `<div style="color:#e8382e;padding:32px;text-align:center;grid-column:1/-1;font-size:.88rem">
          ⚠ ${msg}<br><button class="action-btn edit" style="margin-top:12px" onclick="walletLoadWallets(${page})">↺ Retry</button></div>`;
        return;
      }

      const rows  = d.data?.data || [];
      const total = d.data?.total || 0;
      if (count) count.textContent = total + ' user' + (total !== 1 ? 's' : '');
      const kpiU = document.getElementById('wkpi-users'); if (kpiU) kpiU.textContent = total;

      // Total balance across this page (wallets only — no_wallet rows have 0)
      const totalBal = rows.reduce((s, w) => s + parseFloat(w.available_balance || 0), 0);
      const kpiB = document.getElementById('wkpi-total-bal');
      if (kpiB) kpiB.textContent = walletFmtAmt(totalBal);

      // Cache wallets that exist so the modal doesn't need to re-fetch
      rows.forEach(w => { if (w.id) _wCache[w.id] = w; });

      if (!rows.length) {
        if (grid) grid.innerHTML = '<div style="color:#9CA3AF;padding:48px;text-align:center;grid-column:1/-1">No users found.</div>';
        return;
      }

      if (grid) grid.innerHTML = rows.map(w => {
        const hasWallet = w.status !== 'no_wallet' && w.id;
        const isZero    = !hasWallet || parseFloat(w.available_balance) <= 0;
        const statusMap = {
          active:    { bg:'rgba(34,197,94,.15)',  clr:'#4ade80'  },
          frozen:    { bg:'rgba(59,130,246,.15)', clr:'#60a5fa'  },
          suspended: { bg:'rgba(245,158,11,.15)', clr:'#fbbf24'  },
          no_wallet: { bg:'rgba(107,114,128,.1)', clr:'#9CA3AF'  },
        };
        const sc      = statusMap[w.status] || { bg:'rgba(107,114,128,.1)', clr:'#6B7280' };
        const label   = hasWallet ? w.status : 'No Wallet';
        const clickFn = hasWallet
          ? `walletOpenUserModal(${w.id}, ${w.user?.id || w.user_id})`
          : `walletInitSingleUser(${w.user?.id || w.user_id})`;
        const hint    = hasWallet ? 'Tap to view details →' : 'Tap to create wallet →';

        return `<div style="background:linear-gradient(135deg,#1C1416 0%,#1c1c1c 60%,#2a2a2a 100%);border-radius:16px;padding:22px 24px;color:#fff;position:relative;overflow:hidden;cursor:pointer;${!hasWallet?'opacity:.72':'}'}"
                     onclick="${clickFn}">
          <div style="position:absolute;top:-50px;right:-50px;width:160px;height:160px;background:radial-gradient(circle,rgba(212,217,148,.12) 0%,transparent 65%);pointer-events:none"></div>
          <div style="position:relative;z-index:1">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px">
              <div>
                <div style="font-size:.82rem;font-weight:700;color:#fff">${w.user?.name || '—'}</div>
                <div style="font-size:.68rem;color:rgba(255,255,255,.4);margin-top:2px">${w.user?.email || ''}</div>
              </div>
              <span style="background:${sc.bg};color:${sc.clr};padding:3px 10px;border-radius:999px;font-size:.65rem;font-weight:700;text-transform:uppercase;flex-shrink:0">${label}</span>
            </div>
            <div style="font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.3);margin-bottom:4px">Available Balance</div>
            <div style="font-size:1.8rem;font-weight:700;line-height:1;color:${isZero?'#f87171':'#fff'}">
              <span style="font-size:.9rem;color:#D4D994;font-weight:700">₦</span>${parseFloat(w.available_balance||0).toLocaleString('en-NG',{minimumFractionDigits:2})}
            </div>
            <div style="display:flex;gap:18px;margin-top:14px">
              <div>
                <div style="font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.25)">Locked</div>
                <div style="font-size:.8rem;font-weight:700;color:rgba(255,255,255,.55);margin-top:2px">₦${parseFloat(w.locked_balance||0).toLocaleString('en-NG',{minimumFractionDigits:2})}</div>
              </div>
              <div>
                <div style="font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.25)">Tier</div>
                <div style="font-size:.8rem;font-weight:700;color:rgba(255,255,255,.55);margin-top:2px;text-transform:capitalize">${w.user?.tier || '—'}</div>
              </div>
            </div>
            <div style="margin-top:14px;font-size:.7rem;color:rgba(255,255,255,.3);text-align:right">${hint}</div>
          </div>
        </div>`;
      }).join('');

      // Pagination
      const pg   = document.getElementById('w-wallets-pagination');
      const last = d.data?.last_page || 1;
      if (pg) pg.innerHTML =
        (page > 1 ? `<button class="action-btn edit" onclick="walletLoadWallets(${page-1})">← Prev</button>` : '') +
        (last > 1 ? `<span style="font-size:.8rem;color:#6B7280;align-self:center">Page ${page} / ${last}</span>` : '') +
        (page < last ? `<button class="action-btn edit" onclick="walletLoadWallets(${page+1})">Next →</button>` : '');
    })
    .catch(err => {
      if (grid) grid.innerHTML = `<div style="color:#e8382e;padding:32px;text-align:center;grid-column:1/-1;font-size:.88rem">
        ⚠ Could not reach the backend. Check that the backend server is running.<br>
        <button class="action-btn edit" style="margin-top:12px" onclick="walletLoadWallets(${page})">↺ Retry</button></div>`;
    });
}

// ── Init wallets for all users who don't have one ──
function walletInitWallets() {
  const btn = document.getElementById('w-init-btn');
  const msg = document.getElementById('w-init-msg');
  if (!confirm('Create wallets for all users who don\'t have one yet?')) return;
  if (btn) btn.disabled = true;
  walletShowMsg(msg, 'info', 'Initialising wallets…');
  walletFetch('/init-wallets', { method: 'POST', body: '{}' })
    .then(d => {
      if (btn) btn.disabled = false;
      if (d.success) {
        const r = d.data || {};
        walletShowMsg(msg, 'success', `✓ Created ${r.created} wallet(s)${r.failed ? ' · ' + r.failed + ' failed' : ''}.`);
        walletLoadWallets(wWalletPage);
      } else {
        walletShowMsg(msg, 'error', d.message || 'Failed to initialise wallets.');
      }
    })
    .catch(() => { if (btn) btn.disabled = false; walletShowMsg(msg, 'error', 'Request failed.'); });
}

// ── Create wallet for a single user who doesn't have one yet ──
function walletInitSingleUser(userId) {
  if (!userId) return;
  if (!confirm('Create a wallet for this user?')) return;
  walletFetch('/init-wallets', { method: 'POST', body: '{}' })
    .then(d => {
      if (d.success) walletLoadWallets(wWalletPage);
    });
}

// ── User Wallet Detail Modal ──
let _wumWalletId = null, _wumUserId = null, _wumTxPage = 1;

function walletOpenUserModal(walletId, userId) {
  _wumWalletId = walletId; _wumUserId = userId; _wumTxPage = 1;

  const modal = document.getElementById('wUserModal');
  if (modal) modal.style.display = '';
  document.body.style.overflow = 'hidden';

  // Reset bonus panel
  const qb = document.getElementById('wum-quick-bonus'); if (qb) qb.style.display = 'none';
  const bm = document.getElementById('wum-bonus-msg');   if (bm) bm.style.display = 'none';

  // ── Populate wallet info from cache (instant, no network round-trip) ──
  const w = _wCache[walletId];
  if (w) {
    _wumFillModalHeader(w);
  } else {
    // Cache miss — fetch the single wallet directly
    walletFetch('/wallets/' + walletId).then(d => {
      const found = d.data?.wallet;
      if (found) { _wCache[found.id] = found; _wumFillModalHeader(found); }
    });
  }

  // ── Load transactions ──
  _wumLoadTx(1);
}

function _wumFillModalHeader(w) {
  const cap = s => s ? s.charAt(0).toUpperCase() + s.slice(1) : '—';
  const statusStyles = {
    active:    { bg:'rgba(34,197,94,.15)',  clr:'#4ade80' },
    frozen:    { bg:'rgba(59,130,246,.15)', clr:'#60a5fa' },
    suspended: { bg:'rgba(245,158,11,.15)', clr:'#fbbf24' },
  };
  const sc = statusStyles[w.status] || { bg:'rgba(107,114,128,.1)', clr:'#9CA3AF' };

  const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
  set('wum-name',    w.user?.name  || '—');
  set('wum-email',   w.user?.email || '');
  set('wum-balance', parseFloat(w.available_balance || 0).toLocaleString('en-NG', {minimumFractionDigits:2}));
  set('wum-locked',  parseFloat(w.locked_balance    || 0).toLocaleString('en-NG', {minimumFractionDigits:2}));
  set('wum-tier',    cap(w.user?.tier));

  const sb = document.getElementById('wum-status-badge');
  if (sb) { sb.textContent = cap(w.status); sb.style.background = sc.bg; sb.style.color = sc.clr; }

  const sel = document.getElementById('wum-status-sel');
  if (sel) { sel.value = w.status; sel.dataset.prev = w.status; }
}

function _wumLoadTx(page) {
  _wumTxPage = page;
  const txList = document.getElementById('wum-tx-list');

  // Guard: can't load transactions without a real wallet ID
  if (!_wumWalletId) {
    if (txList) txList.innerHTML = '<div style="text-align:center;padding:40px;color:#9CA3AF"><div style="font-size:1.5rem;margin-bottom:8px">💳</div>No wallet yet — click "Init Wallets" to create one.</div>';
    return;
  }

  if (txList) txList.innerHTML = '<div style="text-align:center;padding:40px;color:#9CA3AF">Loading transactions…</div>';

  walletFetch('/transactions?wallet_id=' + _wumWalletId + '&per_page=20&page=' + page)
    .then(d => {
      // Surface backend errors clearly
      if (!d.success && !d.data) {
        if (txList) txList.innerHTML = `<div style="text-align:center;padding:32px;color:#e8382e;font-size:.85rem">⚠ ${d.message || 'Backend unavailable'}</div>`;
        return;
      }

      const rows  = (d.data?.data)  || [];
      const total = (d.data?.total) || 0;
      const last  = (d.data?.last_page) || 1;

      const cnt = document.getElementById('wum-tx-count');
      if (cnt) cnt.textContent = total;

      // Reset filter buttons to "All"
      document.querySelectorAll('#wum-filter-btns button').forEach((b, i) => {
        if (i === 0) { b.style.background='#1C1416'; b.style.color='#fff'; b.style.borderColor='#1C1416'; }
        else         { b.style.background='#fff';    b.style.color='#374151'; b.style.borderColor='#e8eaed'; }
      });

      if (!txList) return;
      if (!rows.length) {
        txList.innerHTML = '<div style="text-align:center;padding:48px 24px;color:#9CA3AF"><div style="font-size:2rem;margin-bottom:10px">💳</div><div style="font-weight:700;margin-bottom:4px">No transactions yet</div></div>';
        return;
      }

      txList.innerHTML = walletTxRows(rows);

      // Append pagination if multi-page
      if (last > 1) {
        txList.innerHTML += `<div style="padding:12px 24px;display:flex;gap:8px;justify-content:space-between;align-items:center;border-top:1px solid #f3f4f6">
          <span style="font-size:.75rem;color:#9CA3AF">Page ${page}/${last} · ${total} total</span>
          <div style="display:flex;gap:6px">
            ${page > 1    ? `<button class="action-btn edit" onclick="_wumLoadTx(${page-1})">← Prev</button>` : ''}
            ${page < last ? `<button class="action-btn edit" onclick="_wumLoadTx(${page+1})">Next →</button>` : ''}
          </div>
        </div>`;
      }
    })
    .catch(() => {
      if (txList) txList.innerHTML = '<div style="text-align:center;padding:32px;color:#e8382e">Failed to load transactions. Please try again.</div>';
    });
}

function walletCloseUserModal() {
  const modal = document.getElementById('wUserModal');
  if (modal) modal.style.display = 'none';
  document.body.style.overflow = '';
}

function walletUMFilter(type, btn) {
  document.querySelectorAll('#wum-filter-btns button').forEach(b => {
    b.style.background = '#fff'; b.style.color = '#374151'; b.style.borderColor = '#e8eaed';
  });
  btn.style.background = '#1C1416'; btn.style.color = '#fff'; btn.style.borderColor = '#1C1416';
  document.querySelectorAll('#wum-tx-list .w-tx-row').forEach(r => {
    r.style.display = (type === 'all' || r.dataset.type === type) ? '' : 'none';
  });
}

function walletChangeStatusFromModal() {
  const sel       = document.getElementById('wum-status-sel');
  const walletId  = _wumWalletId;
  const newStatus = sel?.value;
  const prev      = sel?.dataset.prev || 'active';
  if (!walletId || !newStatus || newStatus === prev) return;
  if (!confirm('Change this wallet to "' + newStatus + '"?')) { sel.value = prev; return; }
  walletFetch('/' + walletId + '/status', { method:'PATCH', body: JSON.stringify({ status: newStatus }) })
    .then(d => {
      if (d.success) {
        sel.dataset.prev = newStatus;
        // Update cache
        if (_wCache[walletId]) _wCache[walletId].status = newStatus;
        const sc = { active:{ bg:'rgba(34,197,94,.15)', clr:'#4ade80' }, frozen:{ bg:'rgba(59,130,246,.15)', clr:'#60a5fa' }, suspended:{ bg:'rgba(245,158,11,.15)', clr:'#fbbf24' } }[newStatus] || { bg:'rgba(107,114,128,.1)', clr:'#9CA3AF' };
        const sb = document.getElementById('wum-status-badge');
        if (sb) { sb.textContent = newStatus.charAt(0).toUpperCase()+newStatus.slice(1); sb.style.background = sc.bg; sb.style.color = sc.clr; }
        walletLoadWallets(wWalletPage);
      } else { sel.value = prev; alert(d.message || 'Failed.'); }
    })
    .catch(() => { sel.value = prev; alert('Request failed.'); });
}

function walletOpenQuickBonus() {
  const qb = document.getElementById('wum-quick-bonus');
  if (qb) qb.style.display = qb.style.display === 'none' ? '' : 'none';
}

function walletSendQuickBonus() {
  const amt  = document.getElementById('wum-bonus-amt')?.value?.trim();
  const desc = document.getElementById('wum-bonus-desc')?.value?.trim();
  const msg  = document.getElementById('wum-bonus-msg');
  if (!_wumUserId || !amt || !desc) { walletShowMsg(msg, 'error', 'Fill amount and description.'); return; }
  walletShowMsg(msg, 'info', 'Granting…');
  walletFetch('/bonus', { method:'POST', body: JSON.stringify({ user_id: parseInt(_wumUserId), amount: parseFloat(amt), description: desc, category: 'admin_bonus' }) })
    .then(d => {
      if (d.success) {
        walletShowMsg(msg, 'success', '✓ ₦' + parseFloat(amt).toLocaleString() + ' added.');
        document.getElementById('wum-bonus-amt').value  = '';
        document.getElementById('wum-bonus-desc').value = '';
        // Evict cached wallet so modal re-reads fresh balance on next open
        delete _wCache[_wumWalletId];
        // Reload transactions
        _wumLoadTx(1);
        // Reload wallet grid in background
        walletLoadWallets(wWalletPage);
      } else { walletShowMsg(msg, 'error', d.message || 'Failed.'); }
    })
    .catch(() => walletShowMsg(msg, 'error', 'Request failed.'));
}

function walletChangeStatus(walletId, status, sel) {
  if (!confirm('Change wallet status to "' + status + '"?')) { sel.value = sel.dataset.prev || 'active'; return; }
  walletFetch('/' + walletId + '/status', { method: 'PATCH', body: JSON.stringify({ status }) })
    .then(d => {
      if (d.success) { sel.dataset.prev = status; walletLoadWallets(wWalletPage); }
      else { sel.value = sel.dataset.prev || 'active'; alert(d.message || 'Failed.'); }
    })
    .catch(() => { sel.value = sel.dataset.prev || 'active'; alert('Request failed.'); });
}

// ── All Transactions tab ──
let wTxPage = 1;
function walletLoadTransactions(page) {
  page = page || 1; wTxPage = page;
  const search   = document.getElementById('w-tx-search')?.value.trim() || '';
  const category = document.getElementById('w-tx-category')?.value      || '';
  const status   = document.getElementById('w-tx-status')?.value        || '';
  const dateFrom = document.getElementById('w-tx-date-from')?.value     || '';
  const dateTo   = document.getElementById('w-tx-date-to')?.value       || '';
  const list     = document.getElementById('w-tx-list');
  const count    = document.getElementById('w-tx-count');
  if (list) list.innerHTML = '<div style="text-align:center;padding:40px;color:#9CA3AF">Loading…</div>';

  let qs = '?per_page=30&page=' + page;
  if (search)   qs += '&search='    + encodeURIComponent(search);
  if (category) qs += '&category='  + category;
  if (status)   qs += '&status='    + status;
  if (dateFrom) qs += '&date_from=' + dateFrom;
  if (dateTo)   qs += '&date_to='   + dateTo;

  walletFetch('/transactions' + qs)
    .then(d => {
      // Surface backend errors (e.g. missing migrations, proxy auth failure)
      if (!d.success && !d.data) {
        if (list) list.innerHTML = `<div style="text-align:center;padding:32px;color:#e8382e;font-size:.88rem">
          ⚠ ${d.message || 'Backend unavailable'}<br>
          <button class="action-btn edit" style="margin-top:10px" onclick="walletLoadTransactions(${page})">↺ Retry</button></div>`;
        return;
      }

      const rows  = d.data?.data || [];
      const total = d.data?.total || 0;
      if (count) count.textContent = total.toLocaleString() + ' transaction' + (total !== 1 ? 's' : '');
      const kpiTx = document.getElementById('wkpi-tx-count'); if (kpiTx) kpiTx.textContent = total.toLocaleString();

      if (!rows.length) {
        if (list) list.innerHTML = '<div style="text-align:center;padding:48px;color:#9CA3AF"><div style="font-size:2rem;margin-bottom:8px">💳</div>No transactions found yet.</div>';
        document.getElementById('w-tx-page-info').textContent = '';
        document.getElementById('w-tx-page-btns').innerHTML   = '';
        return;
      }

      // Render with user name header above each user-style row
      if (list) {
        list.innerHTML = rows.map(tx => {
          const userName  = tx.wallet?.user?.name  || '—';
          const userEmail = tx.wallet?.user?.email || '';
          const userHdr   = `<div style="padding:8px 24px 0;font-size:.7rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.06em;display:flex;align-items:center;gap:8px">
            <span>${userName}</span>
            <span style="font-weight:400;color:#bbb">${userEmail}</span>
            <span style="font-family:monospace;font-size:.65rem;color:#d1d5db;margin-left:auto">ref: ${tx.reference || ''}</span>
          </div>`;
          return userHdr + walletTxRows([tx]);
        }).join('');
      }

      // Pagination
      const last = d.data?.last_page || 1;
      const info = document.getElementById('w-tx-page-info');
      const btns = document.getElementById('w-tx-page-btns');
      if (info) info.textContent = `Showing ${rows.length} of ${total.toLocaleString()} · Page ${page}/${last}`;
      if (btns) btns.innerHTML   =
        (page > 1    ? `<button class="action-btn edit" onclick="walletLoadTransactions(${page-1})">← Prev</button>` : '') +
        (page < last ? `<button class="action-btn edit" onclick="walletLoadTransactions(${page+1})">Next →</button>` : '');
    })
    .catch(() => {
      if (list) list.innerHTML = `<div style="text-align:center;padding:32px;color:#e8382e;font-size:.88rem">
        ⚠ Could not reach the backend.<br>
        <button class="action-btn edit" style="margin-top:10px" onclick="walletLoadTransactions(${page})">↺ Retry</button></div>`;
    });
}

function walletClearTxFilters() {
  ['w-tx-search','w-tx-category','w-tx-status','w-tx-date-from','w-tx-date-to']
    .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
  walletLoadTransactions(1);
}

// ── Bonus panel ──
function walletLoadBonusPanel() {
  walletLoadBonusConfig();
  walletFetch('/bonus-stats').then(d => {
    const el1 = document.getElementById('w-stat-missing-signup');
    if (el1) el1.textContent = d.data?.missing_signup_bonus ?? '—';
    const el2 = document.getElementById('w-stat-zero-bal');
    if (el2) el2.textContent = d.data?.zero_balance_wallets ?? '—';
  });
}

function walletLoadBonusConfig() {
  const form    = document.getElementById('w-cfg-form');
  const loading = document.getElementById('w-cfg-loading');
  walletFetch('/bonus-config').then(d => {
    if (!d.success && !d.data) return;
    const cfg = d.data || {};
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val ?? ''; };
    set('wcfg-signup',    cfg.signup_bonus);
    set('wcfg-fdep-pct',  cfg.first_deposit_bonus_pct);
    set('wcfg-fdep-cap',  cfg.first_deposit_bonus_cap);
    set('wcfg-referral',  cfg.referral_bonus);
    set('wcfg-zero-bal',  cfg.zero_balance_bonus);
    // Prefill the zero-balance amount input
    const za = document.getElementById('w-zero-amount');
    if (za && !za.value) za.value = cfg.zero_balance_bonus || '';
    // Update preview in the signup card
    const prev = document.getElementById('w-cfg-signup-preview');
    if (prev) prev.textContent = parseFloat(cfg.signup_bonus || 0).toLocaleString();
    if (loading) loading.style.display = 'none';
    if (form)    form.style.display    = '';
  });
}

function walletSaveBonusConfig() {
  const get = id => parseFloat(document.getElementById(id)?.value || 0);
  const data = {
    signup_bonus:            get('wcfg-signup'),
    first_deposit_bonus_pct: get('wcfg-fdep-pct'),
    first_deposit_bonus_cap: get('wcfg-fdep-cap'),
    referral_bonus:          get('wcfg-referral'),
    zero_balance_bonus:      get('wcfg-zero-bal'),
  };
  const msg = document.getElementById('w-cfg-msg');
  walletShowMsg(msg, 'info', 'Saving…');
  walletFetch('/bonus-config', { method: 'POST', body: JSON.stringify(data) })
    .then(d => {
      if (d.success) {
        walletShowMsg(msg, 'success', '✓ Bonus rules saved.');
        const prev = document.getElementById('w-cfg-signup-preview');
        if (prev) prev.textContent = parseFloat(data.signup_bonus).toLocaleString();
        const za = document.getElementById('w-zero-amount');
        if (za && !za.dataset.userEdited) za.value = data.zero_balance_bonus;
      } else {
        walletShowMsg(msg, 'error', d.message || 'Failed to save.');
      }
    })
    .catch(() => walletShowMsg(msg, 'error', 'Request failed.'));
}

function walletGrantMissingSignup() {
  const btn = document.getElementById('w-signup-bonus-btn');
  const msg = document.getElementById('w-signup-bonus-msg');
  if (!confirm('Grant the configured signup bonus to all eligible users now?')) return;
  walletShowMsg(msg, 'info', 'Granting…');
  if (btn) btn.disabled = true;
  walletFetch('/grant-signup-bonus', { method: 'POST', body: '{}' })
    .then(d => {
      if (btn) btn.disabled = false;
      if (d.success) {
        const r = d.data || {};
        walletShowMsg(msg, 'success', `✓ Granted to ${r.granted} user(s)${r.failed ? ' · ' + r.failed + ' failed' : ''}.`);
        walletFetch('/bonus-stats').then(s => {
          const el = document.getElementById('w-stat-missing-signup');
          if (el) el.textContent = s.data?.missing_signup_bonus ?? '0';
        });
      } else {
        walletShowMsg(msg, 'error', d.message || 'Failed.');
      }
    })
    .catch(() => { if (btn) btn.disabled = false; walletShowMsg(msg, 'error', 'Request failed.'); });
}

function walletGrantZeroBalance() {
  const amount   = document.getElementById('w-zero-amount')?.value?.trim();
  const campId   = document.getElementById('w-zero-campaign-id')?.value?.trim();
  const desc     = document.getElementById('w-zero-desc')?.value?.trim();
  const btn      = document.getElementById('w-zero-bonus-btn');
  const msg      = document.getElementById('w-zero-bonus-msg');
  if (!amount || !campId || !desc) { walletShowMsg(msg, 'error', 'Please fill all fields.'); return; }
  if (!confirm(`Send ₦${parseFloat(amount).toLocaleString()} to all zero-balance wallets?`)) return;
  walletShowMsg(msg, 'info', 'Sending…');
  if (btn) btn.disabled = true;
  walletFetch('/grant-zero-balance', { method: 'POST', body: JSON.stringify({ amount: parseFloat(amount), campaign_id: campId, description: desc }) })
    .then(d => {
      if (btn) btn.disabled = false;
      if (d.success) {
        const r = d.data || {};
        walletShowMsg(msg, 'success', `✓ Sent to ${r.granted} user(s)${r.failed ? ' · ' + r.failed + ' failed' : ''}.`);
        walletFetch('/bonus-stats').then(s => {
          const el = document.getElementById('w-stat-zero-bal');
          if (el) el.textContent = s.data?.zero_balance_wallets ?? '0';
        });
      } else {
        walletShowMsg(msg, 'error', d.message || 'Failed.');
      }
    })
    .catch(() => { if (btn) btn.disabled = false; walletShowMsg(msg, 'error', 'Request failed.'); });
}

// ── Manual grants ──
function walletGrantBonus() {
  const userId = document.getElementById('w-bonus-user-id')?.value?.trim();
  const amount = document.getElementById('w-bonus-amount')?.value?.trim();
  const desc   = document.getElementById('w-bonus-desc')?.value?.trim();
  const cat    = document.getElementById('w-bonus-category')?.value || 'admin_bonus';
  const msg    = document.getElementById('w-bonus-msg');
  if (!userId || !amount || !desc) { walletShowMsg(msg, 'error', 'Please fill all required fields.'); return; }
  walletFetch('/bonus', { method: 'POST', body: JSON.stringify({ user_id: parseInt(userId), amount: parseFloat(amount), description: desc, category: cat }) })
    .then(d => {
      if (d.success) {
        walletShowMsg(msg, 'success', '✓ Bonus of ' + walletFmtAmt(amount) + ' granted.');
        ['w-bonus-user-id','w-bonus-amount','w-bonus-desc'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
      } else {
        walletShowMsg(msg, 'error', d.message || 'Failed to grant bonus.');
      }
    })
    .catch(() => walletShowMsg(msg, 'error', 'Request failed.'));
}

function walletGrantBulkBonus() {
  const rawIds   = document.getElementById('w-bulk-user-ids')?.value?.trim();
  const amount   = document.getElementById('w-bulk-amount')?.value?.trim();
  const campName = document.getElementById('w-bulk-campaign-name')?.value?.trim();
  const campId   = document.getElementById('w-bulk-campaign-id')?.value?.trim();
  const msg      = document.getElementById('w-bulk-msg');
  if (!rawIds || !amount || !campName || !campId) { walletShowMsg(msg, 'error', 'Please fill all required fields.'); return; }
  const userIds = rawIds.split(',').map(s => parseInt(s.trim())).filter(Boolean);
  if (!userIds.length) { walletShowMsg(msg, 'error', 'No valid user IDs found.'); return; }
  walletShowMsg(msg, 'info', 'Distributing to ' + userIds.length + ' users…');
  walletFetch('/campaign-bonus', { method: 'POST', body: JSON.stringify({ user_ids: userIds, amount: parseFloat(amount), campaign_name: campName, campaign_id: campId }) })
    .then(d => {
      if (d.success) {
        const r = d.data || {};
        walletShowMsg(msg, 'success', `✓ Granted to ${r.granted} users${r.failed ? ' · ' + r.failed + ' failed' : ''}.`);
      } else {
        walletShowMsg(msg, 'error', d.message || 'Distribution failed.');
      }
    })
    .catch(() => walletShowMsg(msg, 'error', 'Request failed.'));
}

// ── Audit Log ──
function walletLoadAudit() {
  const tbody = document.getElementById('w-audit-tbody');
  if (tbody) tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:#9CA3AF">Loading…</td></tr>';
  walletFetch('/audit-logs?per_page=50')
    .then(d => {
      const rows = d.data?.data || [];
      if (!rows.length) { if (tbody) tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:#9CA3AF">No audit logs found.</td></tr>'; return; }
      if (tbody) tbody.innerHTML = rows.map(log => {
        const p = log.payload || {};
        return `<tr>
          <td style="font-size:.75rem;color:#6B7280;white-space:nowrap">${walletFmtDate(log.created_at)}</td>
          <td><div style="font-size:.8rem;font-weight:700">${log.wallet?.user?.name||'—'}</div><div style="font-size:.68rem;color:#9CA3AF">ID ${log.user_id}</div></td>
          <td><span style="background:#f3f4f6;padding:2px 8px;border-radius:999px;font-size:.7rem;font-weight:700">${log.action}</span></td>
          <td style="font-weight:700">${walletFmtAmt(p.amount)}</td>
          <td style="font-size:.78rem">${walletFmtAmt(p.balance_before)} → ${walletFmtAmt(p.balance_after)}</td>
          <td style="font-size:.72rem;color:#9CA3AF">${log.ip_address||'—'}</td>
          <td style="font-size:.75rem">${log.performed_by ? 'Admin #'+log.performed_by : 'System'}</td>
        </tr>`;
      }).join('');
    })
    .catch(() => { if (tbody) tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:#e8382e">Error loading audit log.</td></tr>'; });
}
</script>
@endsection
