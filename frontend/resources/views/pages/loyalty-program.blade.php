@extends('layouts.app')
@section('title', 'Loyalty Program — Kominhoo Beauty')

@section('content')
<style>
/* ── Loyalty Hero ─────────────────────────────────────── */
.lp-hero {
  background: var(--black);
  min-height: 80vh;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
}
.lp-hero-bg {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 60% 80% at 70% 50%, rgba(107,42,48,.5) 0%, transparent 65%),
    radial-gradient(ellipse 40% 60% at 10% 80%, rgba(212,217,148,.06) 0%, transparent 60%);
}
.lp-hero-grid {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
  background-size: 80px 80px;
}
.lp-hero-inner {
  max-width: 760px;
  margin: 0 auto;
  padding: 140px 48px;
  position: relative;
  z-index: 2;
}
.lp-eyebrow {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 28px;
}
.lp-eyebrow::before {
  content: '';
  width: 32px; height: 1px;
  background: var(--lime);
}
.lp-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--lime);
}
.lp-hero-title {
  font-family: var(--font-display);
  font-size: clamp(3rem, 5.5vw, 5rem);
  line-height: 1.03;
  color: #fff;
  margin-bottom: 26px;
}
.lp-hero-title em { font-style: italic; color: var(--lime); }
.lp-hero-desc {
  font-size: 1.05rem;
  line-height: 1.78;
  color: rgba(255,255,255,.48);
  margin-bottom: 40px;
  max-width: 480px;
}
.lp-hero-actions {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
}

/* ── How It Works ───────────────────────────────────────── */
.lp-how {
  background: var(--cream);
  padding: 120px 0;
}
.lp-how-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
}
.lp-how-header {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: end;
  margin-bottom: 80px;
}
.lp-section-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}
.lp-section-eyebrow::before {
  content: '';
  width: 24px; height: 1px;
  background: var(--text-muted);
}
.lp-section-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--text-muted);
}
.lp-steps {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;
  background: var(--border);
  border: 1px solid var(--border);
  border-radius: 4px;
  overflow: hidden;
}
.lp-step {
  background: #fff;
  padding: 44px 36px;
  border-right: 1px solid var(--border);
  position: relative;
  transition: background var(--t-base);
}
.lp-step:last-child { border-right: none; }
.lp-step:hover { background: var(--cream); }
.lp-step-num {
  font-family: var(--font-display);
  font-size: 3.5rem;
  font-weight: 700;
  color: var(--lime);
  line-height: 1;
  margin-bottom: 20px;
  opacity: .5;
}
.lp-step-title {
  font-weight: 700;
  font-size: .97rem;
  color: var(--black);
  margin-bottom: 10px;
  line-height: 1.3;
}
.lp-step-desc {
  font-size: .86rem;
  line-height: 1.7;
  color: var(--text-secondary);
}

/* ── Earn ─────────────────────────────────────────────── */
.lp-earn {
  background: #fff;
  padding: 120px 0;
}
.lp-earn-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
}
.lp-earn-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2px;
  background: var(--border);
  border: 1px solid var(--border);
  border-radius: 4px;
  overflow: hidden;
  margin-top: 64px;
}
.lp-earn-cell {
  background: #fff;
  padding: 36px 32px;
  transition: background var(--t-base);
}
.lp-earn-cell:hover { background: var(--cream); }
.lp-earn-pts {
  font-family: var(--font-display);
  font-size: 1.6rem;
  font-weight: 700;
  color: var(--black);
  margin-bottom: 8px;
  line-height: 1.1;
}
.lp-earn-pts strong { color: var(--lime); }
.lp-earn-title {
  font-weight: 700;
  font-size: .88rem;
  color: var(--black);
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: .06em;
}
.lp-earn-desc {
  font-size: .84rem;
  line-height: 1.7;
  color: var(--text-secondary);
}

/* ── Tiers ─────────────────────────────────────────────── */
.lp-tiers {
  background: var(--black);
  padding: 120px 0;
  position: relative;
  overflow: hidden;
}
.lp-tiers::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    repeating-linear-gradient(
      90deg,
      transparent,
      transparent 100px,
      rgba(255,255,255,.012) 100px,
      rgba(255,255,255,.012) 101px
    );
}
.lp-tiers-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
  position: relative;
  z-index: 1;
}
.lp-tiers-header {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: end;
  margin-bottom: 72px;
}
.lp-tiers-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}
.lp-tiers-eyebrow::before {
  content: '';
  width: 24px; height: 1px;
  background: rgba(255,255,255,.25);
}
.lp-tiers-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: rgba(255,255,255,.3);
}
.lp-tiers-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
}
.lp-tier {
  background: rgba(255,255,255,.04);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 4px;
  padding: 36px 28px;
  position: relative;
  transition: background var(--t-base), border-color var(--t-base), transform var(--t-base);
}
.lp-tier:hover {
  background: rgba(255,255,255,.07);
  transform: translateY(-4px);
}
.lp-tier.featured {
  border-color: rgba(212,217,148,.3);
  background: rgba(212,217,148,.05);
}
.lp-tier-tag {
  position: absolute;
  top: -1px; left: 50%;
  transform: translateX(-50%);
  background: var(--lime);
  color: var(--black);
  font-size: .65rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  padding: 5px 14px;
  border-radius: 0 0 6px 6px;
}
.lp-tier-name {
  font-family: var(--font-display);
  font-size: .75rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--lime);
  margin-bottom: 8px;
  opacity: .7;
}
.lp-tier-title {
  font-family: var(--font-display);
  font-size: 1.6rem;
  color: #fff;
  margin-bottom: 6px;
  line-height: 1.1;
}
.lp-tier-req {
  font-size: .75rem;
  color: rgba(255,255,255,.3);
  margin-bottom: 28px;
  padding-bottom: 20px;
  border-bottom: 1px solid rgba(255,255,255,.07);
}
.lp-tier-perks {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.lp-tier-perk {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: .83rem;
  color: rgba(255,255,255,.55);
  line-height: 1.5;
}
.lp-tier-perk-dot {
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--lime);
  flex-shrink: 0;
  margin-top: 6px;
  opacity: .6;
}

/* ── Redeem ───────────────────────────────────────────── */
.lp-redeem {
  background: var(--cream);
  padding: 120px 0;
}
.lp-redeem-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
}
.lp-redeem-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 64px;
}
.lp-redeem-card {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 4px;
  padding: 40px 36px;
  transition: transform var(--t-base), box-shadow var(--t-base);
}
.lp-redeem-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--s-lg);
}
.lp-redeem-rate {
  font-family: var(--font-display);
  font-size: 2rem;
  color: var(--black);
  font-weight: 700;
  margin-bottom: 6px;
  line-height: 1;
}
.lp-redeem-rate em { font-style: italic; color: var(--lime); }
.lp-redeem-equiv {
  font-size: .78rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 24px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border);
}
.lp-redeem-title {
  font-weight: 700;
  font-size: .95rem;
  color: var(--black);
  margin-bottom: 10px;
}
.lp-redeem-desc {
  font-size: .87rem;
  line-height: 1.72;
  color: var(--text-secondary);
}

/* ── CTA ─────────────────────────────────────────────── */
.lp-cta {
  background: var(--rose-dark);
  padding: 120px 0;
  position: relative;
  overflow: hidden;
}
.lp-cta::before {
  content: '';
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    45deg,
    transparent,
    transparent 60px,
    rgba(255,255,255,.015) 60px,
    rgba(255,255,255,.015) 61px
  );
}
.lp-cta-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 60px;
  align-items: center;
  position: relative;
  z-index: 1;
}
.lp-cta-title {
  font-family: var(--font-display);
  font-size: clamp(2.2rem, 4vw, 3.4rem);
  color: #fff;
  line-height: 1.1;
  margin-bottom: 16px;
}
.lp-cta-title em { font-style: italic; color: var(--lime); }
.lp-cta-desc {
  font-size: .97rem;
  line-height: 1.72;
  color: rgba(255,255,255,.48);
  max-width: 520px;
}
.lp-cta-actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex-shrink: 0;
  align-items: flex-end;
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 1024px) {
  .lp-hero-inner { padding: 100px 24px; }
  .lp-how-inner, .lp-earn-inner, .lp-tiers-inner, .lp-redeem-inner, .lp-cta-inner { padding: 0 24px; }
  .lp-how-header, .lp-tiers-header { grid-template-columns: 1fr; gap: 16px; }
  .lp-steps { grid-template-columns: 1fr 1fr; }
  .lp-tiers-grid { grid-template-columns: 1fr 1fr; }
  .lp-cta-inner { grid-template-columns: 1fr; gap: 36px; }
  .lp-cta-actions { align-items: flex-start; flex-direction: row; flex-wrap: wrap; }
}
@media (max-width: 640px) {
  .lp-steps { grid-template-columns: 1fr; }
  .lp-earn-grid { grid-template-columns: 1fr; }
  .lp-tiers-grid { grid-template-columns: 1fr; }
  .lp-redeem-grid { grid-template-columns: 1fr; }
  .lp-step { padding: 32px 24px; }
}
</style>

<!-- Hero -->
<section class="lp-hero">
  <div class="lp-hero-bg"></div>
  <div class="lp-hero-grid"></div>
  <div class="lp-hero-inner">
    <div class="lp-eyebrow"><span>Kominhoo Rewards</span></div>
    <h1 class="lp-hero-title">Shop.<br>Earn.<br><em>Glow more.</em></h1>
    <p class="lp-hero-desc">Every purchase earns you points — our rewards currency. Redeem them for discounts, free products, and exclusive member perks. The more you invest in your skin, the more you get back.</p>
    <div class="lp-hero-actions">
      <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Join Free — Start Earning</a>
      <a href="{{ route('login') }}" class="btn btn-dark btn-lg" style="border:1px solid rgba(255,255,255,.15)">Sign In to Dashboard</a>
    </div>
  </div>
</section>

<!-- How It Works -->
<section class="lp-how">
  <div class="lp-how-inner">
    <div class="lp-how-header">
      <div>
        <div class="lp-section-eyebrow"><span>How It Works</span></div>
        <h2 class="display-md" style="color:var(--black);line-height:1.1">Four steps to<br>better rewards.</h2>
      </div>
      <p style="font-size:.97rem;line-height:1.8;color:var(--text-secondary)">Getting started takes under a minute. Your rewards compound naturally as you continue shopping and engaging with the Kominhoo community.</p>
    </div>
    <div class="lp-steps">
      <div class="lp-step">
        <div class="lp-step-num">01</div>
        <div class="lp-step-title">Create a free account</div>
        <p class="lp-step-desc">Sign up with your email in under 60 seconds and receive 100 welcome Koins instantly — before your first purchase.</p>
      </div>
      <div class="lp-step">
        <div class="lp-step-num">02</div>
        <div class="lp-step-title">Shop and earn Koins</div>
        <p class="lp-step-desc">Every ₦1,000 spent earns {{ data_get($pointEvents, 'purchase.points_per_1000', 10) }} points. Write reviews, refer friends, and post in the Gallery for bonus points on top.</p>
      </div>
      <div class="lp-step">
        <div class="lp-step-num">03</div>
        <div class="lp-step-title">Unlock higher tiers</div>
        <p class="lp-step-desc">Accumulate points to climb tiers — each level multiplies your earn rate and unlocks exclusive perks and gifts.</p>
      </div>
      <div class="lp-step">
        <div class="lp-step-num">04</div>
        <div class="lp-step-title">Redeem your rewards</div>
        <p class="lp-step-desc">Apply Koins at checkout for instant cart discounts, or exchange them for free products and shipping upgrades.</p>
      </div>
    </div>
  </div>
</section>

<!-- Ways to Earn -->
<section class="lp-earn">
  <div class="lp-earn-inner">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:end;margin-bottom:0">
      <div>
        <div class="lp-section-eyebrow"><span>Ways to Earn</span></div>
        <h2 class="display-md" style="color:var(--black);line-height:1.1">Earn Koins<br>everywhere.</h2>
      </div>
      <p style="font-size:.97rem;line-height:1.8;color:var(--text-secondary);align-self:end;padding-bottom:4px">Buying is just the start. You earn for reviewing, referring, and engaging — the programme rewards genuine participation.</p>
    </div>
    <div class="lp-earn-grid">
      @forelse($pointEvents as $evt)
      <div class="lp-earn-cell">
        <div class="lp-earn-pts">
          @if(isset($evt['points_per_1000']))
            <strong>{{ $evt['points_per_1000'] }} pts</strong> / ₦1,000
          @else
            <strong>{{ number_format($evt['points']) }} pts</strong>
          @endif
          @if($evt['one_time'] ?? false)
          <span style="font-size:.65rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);display:block;margin-top:4px">One-time</span>
          @endif
        </div>
        <div class="lp-earn-title">{{ $evt['label'] }}</div>
        <p class="lp-earn-desc">{{ $evt['description'] }}</p>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted)">Earn details coming soon.</div>
      @endforelse
    </div>
  </div>
</section>

<!-- Tiers -->
<section class="lp-tiers">
  <div class="lp-tiers-inner">
    <div class="lp-tiers-header">
      <div>
        <div class="lp-tiers-eyebrow"><span>Membership Tiers</span></div>
        <h2 class="display-md" style="color:#fff;line-height:1.1">The higher you go,<br>the more you get.</h2>
      </div>
      <p style="font-size:.97rem;line-height:1.8;color:rgba(255,255,255,.4)">Tiers are based on cumulative Koins earned. Your tier is preserved as long as your account stays active over a 12-month period.</p>
    </div>
    @php $tierCount = count($loyaltyTiers); @endphp
    <div class="lp-tiers-grid" style="grid-template-columns: repeat({{ min($tierCount, 4) }}, 1fr)">
      @forelse($loyaltyTiers as $tIdx => $tier)
      @php
        $tMin      = $tier['min_points'] ?? 0;
        $tNext     = $loyaltyTiers[$tIdx + 1] ?? null;
        $tIsLast   = $tIdx === $tierCount - 1;
        $tFeatured = $tier['is_popular'] ?? false;
        if ($tMin === 0) {
            $tReq = 'Starts free — your entry into K-beauty rewards';
        } elseif ($tIsLast) {
            $tReq = number_format($tMin) . '+ points earned';
        } else {
            $tReq = number_format($tMin) . ' – ' . number_format(($tNext['min_points'] - 1)) . ' points earned';
        }
      @endphp
      <div class="lp-tier{{ $tFeatured ? ' featured' : '' }}">
        @if($tFeatured)<div class="lp-tier-tag">Most Popular</div>@endif
        <div class="lp-tier-name">Tier {{ str_pad($tIdx + 1, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="lp-tier-title">{{ $tier['name'] }}</div>
        <div class="lp-tier-req">{{ $tReq }}</div>
        <div class="lp-tier-perks">
          @if(($tier['multiplier'] ?? 1) > 1)
          <div class="lp-tier-perk"><div class="lp-tier-perk-dot"></div>{{ $tier['multiplier'] }}× points multiplier</div>
          @endif
          @foreach($tier['benefits'] ?? [] as $benefit)
          <div class="lp-tier-perk"><div class="lp-tier-perk-dot"></div>{{ $benefit }}</div>
          @endforeach
          @if($tier['gift'] ?? null)
          <div class="lp-tier-perk"><div class="lp-tier-perk-dot"></div>{{ $tier['gift']['name'] }} gift (₦{{ number_format($tier['gift']['value']) }} value)</div>
          @endif
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:48px;color:rgba(255,255,255,.3)">Tiers coming soon.</div>
      @endforelse
    </div>
  </div>
</section>

<!-- Redeem -->
<section class="lp-redeem">
  <div class="lp-redeem-inner">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:end;">
      <div>
        <div class="lp-section-eyebrow"><span>Redeem Koins</span></div>
        <h2 class="display-md" style="color:var(--black);line-height:1.1">Spend your Koins<br>your way.</h2>
      </div>
      <p style="font-size:.97rem;line-height:1.8;color:var(--text-secondary);align-self:end;padding-bottom:4px">Koins never expire while your account is active. Redeem at checkout or browse the rewards catalogue from your dashboard.</p>
    </div>
    <div class="lp-redeem-grid">
      <div class="lp-redeem-card">
        <div class="lp-redeem-rate"><em>₦1</em> per Koin</div>
        <div class="lp-redeem-equiv">100 Koins = ₦100 off</div>
        <div class="lp-redeem-title">Cart discount</div>
        <p class="lp-redeem-desc">Apply Koins directly to your order at checkout to reduce the amount you pay immediately. No minimum order.</p>
      </div>
      <div class="lp-redeem-card">
        <div class="lp-redeem-rate">From <em>500</em></div>
        <div class="lp-redeem-equiv">Koins from rewards catalogue</div>
        <div class="lp-redeem-title">Free products</div>
        <p class="lp-redeem-desc">Exchange Koins for popular skincare products from our curated rewards catalogue. Updated monthly with new options.</p>
      </div>
      <div class="lp-redeem-card">
        <div class="lp-redeem-rate">From <em>200</em></div>
        <div class="lp-redeem-equiv">Koins per upgrade</div>
        <div class="lp-redeem-title">Shipping upgrades</div>
        <p class="lp-redeem-desc">Use Koins to upgrade to express delivery or gift free shipping to a friend anywhere in Nigeria.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="lp-cta">
  <div class="lp-cta-inner">
    <div>
      <h2 class="lp-cta-title">Start earning <em>today.</em><br>It's completely free.</h2>
      <p class="lp-cta-desc">Join thousands of Nigerians already earning Koins and building better skincare routines with every order.</p>
    </div>
    <div class="lp-cta-actions">
      <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Free Account</a>
      <a href="{{ route('shop') }}" class="btn btn-dark btn-lg" style="border:1px solid rgba(255,255,255,.15)">Browse Products</a>
    </div>
  </div>
</section>
@endsection
