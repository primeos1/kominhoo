@extends('layouts.app')
@section('title', 'About Kominhoo — Korean Skincare for Nigerian Skin')

@section('content')
<style>
/* ── About Hero ─────────────────────────────────────────── */
.ab-hero {
  background: var(--black);
  min-height: 92vh;
  display: grid;
  grid-template-columns: 1fr 1fr;
  position: relative;
  overflow: hidden;
}
.ab-hero-left {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 120px 64px 120px 80px;
  position: relative;
  z-index: 2;
}
.ab-hero-right {
  position: relative;
  overflow: hidden;
}
.ab-hero-img-fill {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--rose-dark) 0%, #3a1820 60%, var(--black) 100%);
}
.ab-hero-img-fill::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 60% 80% at 60% 40%, rgba(212,217,148,.12) 0%, transparent 70%),
    repeating-linear-gradient(
      45deg,
      transparent,
      transparent 60px,
      rgba(255,255,255,.018) 60px,
      rgba(255,255,255,.018) 61px
    );
}
.ab-hero-text-bg {
  position: absolute;
  bottom: 0; right: 0;
  font-family: var(--font-display);
  font-size: 28vw;
  font-weight: 700;
  color: rgba(255,255,255,.03);
  line-height: 1;
  pointer-events: none;
  user-select: none;
}
.ab-eyebrow {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 32px;
}
.ab-eyebrow-line {
  width: 32px;
  height: 1px;
  background: var(--lime);
}
.ab-eyebrow-text {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--lime);
}
.ab-hero-title {
  font-family: var(--font-display);
  font-size: clamp(2.8rem, 5vw, 4.4rem);
  line-height: 1.06;
  color: #fff;
  margin-bottom: 28px;
}
.ab-hero-title em {
  font-style: italic;
  color: var(--lime);
}
.ab-hero-desc {
  font-size: 1.05rem;
  line-height: 1.78;
  color: rgba(255,255,255,.5);
  max-width: 480px;
  margin-bottom: 48px;
}
.ab-hero-actions {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
}
.ab-stats-bar {
  margin-top: 72px;
  padding-top: 40px;
  border-top: 1px solid rgba(255,255,255,.08);
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;
}
.ab-stat {
  padding-right: 24px;
  border-right: 1px solid rgba(255,255,255,.07);
}
.ab-stat:last-child { border-right: none; padding-right: 0; }
.ab-stat-num {
  font-family: var(--font-display);
  font-size: 2.2rem;
  font-weight: 700;
  color: var(--lime);
  line-height: 1;
  margin-bottom: 6px;
}
.ab-stat-label {
  font-size: .72rem;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: rgba(255,255,255,.35);
}

/* ── Story ─────────────────────────────────────────────── */
.ab-story {
  background: var(--cream);
  padding: 120px 0;
  overflow: hidden;
  position: relative;
}
.ab-story-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
}
.ab-story-visual {
  position: relative;
}
.ab-story-panel {
  aspect-ratio: 4/5;
  background: var(--rose-dark);
  border-radius: 4px;
  overflow: hidden;
  position: relative;
}
.ab-story-panel::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    linear-gradient(135deg, rgba(212,217,148,.1) 0%, transparent 50%),
    repeating-linear-gradient(
      -45deg,
      transparent,
      transparent 40px,
      rgba(255,255,255,.025) 40px,
      rgba(255,255,255,.025) 41px
    );
}
.ab-story-panel-text {
  position: absolute;
  bottom: 36px;
  left: 36px;
  right: 36px;
}
.ab-story-panel-label {
  font-family: var(--font-display);
  font-size: 1.8rem;
  font-style: italic;
  color: rgba(255,255,255,.9);
  line-height: 1.3;
}
.ab-story-panel-sub {
  font-size: .75rem;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--lime);
  margin-top: 8px;
}
.ab-story-accent {
  position: absolute;
  top: -24px;
  right: -24px;
  width: 140px;
  height: 140px;
  border: 1px solid var(--lime);
  border-radius: 50%;
  opacity: .35;
}
.ab-story-accent::before {
  content: '';
  position: absolute;
  inset: 16px;
  border: 1px solid var(--lime);
  border-radius: 50%;
  opacity: .6;
}
.ab-story-content {
  padding-left: 20px;
}
.ab-story-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 24px;
}
.ab-story-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--rose-dark);
}
.ab-story-eyebrow::before {
  content: '';
  width: 28px;
  height: 1px;
  background: var(--rose-dark);
  flex-shrink: 0;
}
.ab-story-title {
  font-family: var(--font-display);
  font-size: clamp(2rem, 3.5vw, 2.8rem);
  line-height: 1.12;
  color: var(--black);
  margin-bottom: 28px;
}
.ab-story-body {
  font-size: .97rem;
  line-height: 1.85;
  color: var(--text-secondary);
}
.ab-story-body p + p { margin-top: 18px; }

/* ── Values ─────────────────────────────────────────────── */
.ab-values {
  background: #fff;
  padding: 120px 0;
}
.ab-values-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
}
.ab-section-header {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: end;
  margin-bottom: 72px;
}
.ab-section-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}
.ab-section-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--text-muted);
}
.ab-section-eyebrow::before {
  content: '';
  width: 24px;
  height: 1px;
  background: var(--text-muted);
  flex-shrink: 0;
}
.ab-values-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2px;
  background: var(--border);
  border: 1px solid var(--border);
  border-radius: 4px;
  overflow: hidden;
}
.ab-value-cell {
  background: #fff;
  padding: 40px 36px;
  transition: background var(--t-base);
  position: relative;
}
.ab-value-cell:hover { background: var(--cream); }
.ab-value-num {
  font-family: var(--font-display);
  font-size: .8rem;
  font-weight: 700;
  color: var(--lime);
  letter-spacing: .1em;
  margin-bottom: 20px;
  display: block;
}
.ab-value-title {
  font-family: var(--font-display);
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--black);
  margin-bottom: 14px;
  line-height: 1.3;
}
.ab-value-desc {
  font-size: .88rem;
  line-height: 1.75;
  color: var(--text-secondary);
}
.ab-value-arrow {
  position: absolute;
  bottom: 28px;
  right: 28px;
  width: 32px;
  height: 32px;
  border: 1px solid var(--border);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity var(--t-base), transform var(--t-base);
}
.ab-value-cell:hover .ab-value-arrow {
  opacity: 1;
  transform: translate(2px, -2px);
}

/* ── CTA ─────────────────────────────────────────────────── */
.ab-cta {
  background: var(--black);
  padding: 120px 0;
  position: relative;
  overflow: hidden;
}
.ab-cta::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background:
    radial-gradient(ellipse 40% 60% at 80% 50%, rgba(212,217,148,.08) 0%, transparent 70%),
    radial-gradient(ellipse 30% 50% at 20% 50%, rgba(107,42,48,.4) 0%, transparent 70%);
  pointer-events: none;
}
.ab-cta-inner {
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
.ab-cta-title {
  font-family: var(--font-display);
  font-size: clamp(2.2rem, 4vw, 3.4rem);
  line-height: 1.1;
  color: #fff;
  margin-bottom: 16px;
}
.ab-cta-title em { font-style: italic; color: var(--lime); }
.ab-cta-desc {
  font-size: .97rem;
  line-height: 1.72;
  color: rgba(255,255,255,.45);
  max-width: 520px;
}
.ab-cta-actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: flex-end;
  flex-shrink: 0;
}

/* ── Hero product cards ─────────────────────────────────── */
.ab-hero-products {
  position: absolute;
  inset: 0;
  z-index: 1;
}
.ab-hero-prod-card {
  position: absolute;
  width: 148px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,.5), 0 0 0 1px rgba(255,255,255,.08);
  background: #1a0e10;
}
.ab-hero-prod-card--lg { width: 188px; }
.ab-hero-prod-card img {
  width: 100%;
  aspect-ratio: 3/4;
  object-fit: cover;
  display: block;
}
.ab-hero-prod-badge {
  padding: 8px 12px 10px;
  background: rgba(0,0,0,.5);
  backdrop-filter: blur(6px);
  border-top: 1px solid rgba(255,255,255,.07);
}
.ab-hero-prod-badge span {
  font-size: .62rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--lime);
}

/* ── Story panel image ──────────────────────────────────── */
.ab-story-panel-img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: .45;
}

/* ── Gallery section ────────────────────────────────────── */
.ab-gallery {
  background: #0d0809;
  overflow: hidden;
}
.ab-gallery-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 100px 48px;
}
.ab-gallery-head {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 40px;
  gap: 24px;
}
.ab-gallery-head-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 14px;
}
.ab-gallery-head-eyebrow::before {
  content: '';
  width: 24px;
  height: 1px;
  background: rgba(255,255,255,.25);
  flex-shrink: 0;
}
.ab-gallery-head-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: rgba(255,255,255,.35);
}
.ab-gallery-head-title {
  font-family: var(--font-display);
  font-size: clamp(1.8rem, 2.8vw, 2.4rem);
  line-height: 1.1;
  color: #fff;
}
.ab-gallery-head-title em { font-style: italic; color: var(--lime); }
.ab-gallery-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1.2fr;
  grid-template-rows: 270px 270px;
  gap: 6px;
}
.ab-gallery-cell {
  position: relative;
  overflow: hidden;
  border-radius: 3px;
  background: var(--rose-dark);
}
.ab-gallery-cell--tall { grid-row: span 2; }
.ab-gallery-cell img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform .6s cubic-bezier(.25,.46,.45,.94);
  filter: brightness(.8) saturate(.85);
}
.ab-gallery-cell:hover img {
  transform: scale(1.05);
  filter: brightness(.9) saturate(1.05);
}
.ab-gallery-label {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  padding: 40px 18px 16px;
  background: linear-gradient(transparent, rgba(0,0,0,.7));
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: rgba(255,255,255,.75);
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 1024px) {
  .ab-hero { grid-template-columns: 1fr; min-height: auto; }
  .ab-hero-right { display: none; }
  .ab-hero-left { padding: 100px 32px 80px; }
  .ab-stats-bar { grid-template-columns: repeat(2, 1fr); gap: 24px; }
  .ab-story-grid { grid-template-columns: 1fr; gap: 48px; padding: 0 24px; }
  .ab-story-visual { max-width: 420px; }
  .ab-values-inner { padding: 0 24px; }
  .ab-section-header { grid-template-columns: 1fr; gap: 20px; }
  .ab-values-grid { grid-template-columns: 1fr 1fr; }
  .ab-cta-inner { grid-template-columns: 1fr; gap: 36px; padding: 0 24px; }
  .ab-cta-actions { align-items: flex-start; flex-direction: row; flex-wrap: wrap; }
  .ab-gallery-inner { padding: 64px 24px; }
  .ab-gallery-grid { grid-template-columns: 1fr 1fr; grid-template-rows: repeat(3, 200px); }
  .ab-gallery-cell--tall { grid-row: span 1; }
  .ab-gallery-head { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 600px) {
  .ab-hero-left { padding: 80px 24px 60px; }
  .ab-stats-bar { grid-template-columns: 1fr 1fr; }
  .ab-story-content { padding-left: 0; }
  .ab-values-grid { grid-template-columns: 1fr; }
  .ab-cta::before { display: none; }
  .ab-gallery-grid { grid-template-columns: 1fr; grid-template-rows: repeat(5, 220px); }
}
</style>

<!-- Hero -->
<section class="ab-hero">
  <div class="ab-hero-left">
    <div class="ab-eyebrow">
      <div class="ab-eyebrow-line"></div>
      <span class="ab-eyebrow-text">Our Story</span>
    </div>
    <h1 class="ab-hero-title">
      Built for<br>
      <em>Nigerian skin.</em><br>
      Powered by Korean science.
    </h1>
    <p class="ab-hero-desc">Kominhoo was born from a simple frustration — K-beauty works, but global brands don't design for our skin, our climate, or our melanin. We changed that.</p>
    <div class="ab-hero-actions">
      <a href="{{ route('quiz') }}" class="btn btn-primary btn-lg">Take the Skin Quiz</a>
      <a href="{{ route('shop') }}" class="btn btn-outline-lime btn-lg" style="color:#fff;border-color:rgba(255,255,255,.2)">Browse Products</a>
    </div>
    <div class="ab-stats-bar">
      <div class="ab-stat">
        <div class="ab-stat-num">10K+</div>
        <div class="ab-stat-label">Happy customers</div>
      </div>
      <div class="ab-stat">
        <div class="ab-stat-num">200+</div>
        <div class="ab-stat-label">Curated products</div>
      </div>
      <div class="ab-stat">
        <div class="ab-stat-num">36</div>
        <div class="ab-stat-label">States served</div>
      </div>
      <div class="ab-stat">
        <div class="ab-stat-num">98%</div>
        <div class="ab-stat-label">Verified authentic</div>
      </div>
    </div>
  </div>
  <div class="ab-hero-right">
    <div class="ab-hero-img-fill"></div>
    <div class="ab-hero-products">
      <div class="ab-hero-prod-card" style="left:9%;top:13%;transform:rotate(-6deg)">
        <img src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400&auto=format&fit=crop&q=80" alt="Korean serum">
        <div class="ab-hero-prod-badge"><span>Melanin tested</span></div>
      </div>
      <div class="ab-hero-prod-card ab-hero-prod-card--lg" style="left:38%;top:29%;transform:rotate(3deg);z-index:2">
        <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400&auto=format&fit=crop&q=80" alt="K-beauty face cream">
        <div class="ab-hero-prod-badge"><span>100% authentic</span></div>
      </div>
      <div class="ab-hero-prod-card" style="left:14%;top:58%;transform:rotate(5deg)">
        <img src="https://images.unsplash.com/photo-1612817288484-6f916006741a?w=400&auto=format&fit=crop&q=80" alt="Korean toner">
        <div class="ab-hero-prod-badge"><span>Climate-fit</span></div>
      </div>
    </div>
    <div class="ab-hero-text-bg">K</div>
  </div>
</section>

<!-- Origin Story -->
<section class="ab-story">
  <div class="ab-story-grid">
    <div class="ab-story-visual">
      <div class="ab-story-panel">
        <img src="https://images.unsplash.com/photo-1596755389378-c31d21fd1273?w=600&auto=format&fit=crop&q=80" alt="Korean skincare routine" class="ab-story-panel-img" loading="lazy">
        <div class="ab-story-panel-text">
          <div class="ab-story-panel-label">"After much thought" — the meaning behind our name.</div>
          <div class="ab-story-panel-sub">Lagos, 2023</div>
        </div>
      </div>
      <div class="ab-story-accent"></div>
    </div>
    <div class="ab-story-content">
      <div class="ab-story-eyebrow"><span>Our Beginning</span></div>
      <h2 class="ab-story-title">Why we started Kominhoo</h2>
      <div class="ab-story-body">
        <p>In 2023, after years of watching Korean skincare transform routines globally — yet consistently fail Nigerian skin types due to ill-matched formulas and harmful whitening agents — our founders decided to build something better.</p>
        <p>The name "Kominhoo" fuses the Korean word <em>고민후</em>, meaning "after much thought," with our deep love for K-beauty innovation. Every product here exists because we thought hard about whether it truly works for melanin-rich skin in Nigeria's climate.</p>
        <p>We don't just import and sell. We test, curate, and match — using our Skin Quiz to connect each customer with formulas designed for their specific skin concerns.</p>
      </div>
    </div>
  </div>
</section>

<!-- Visual Gallery -->
<section class="ab-gallery">
  <div class="ab-gallery-inner">
    <div class="ab-gallery-head">
      <div>
        <div class="ab-gallery-head-eyebrow"><span>Behind the brand</span></div>
        <h2 class="ab-gallery-head-title">Real skin.<br><em>Real results.</em></h2>
      </div>
      <a href="{{ route('shop') }}" class="btn btn-outline-lime" style="color:#fff;border-color:rgba(255,255,255,.2);flex-shrink:0;align-self:flex-end">Browse all products</a>
    </div>
    <div class="ab-gallery-grid">
      <div class="ab-gallery-cell ab-gallery-cell--tall">
        <img src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=800&auto=format&fit=crop&q=80" alt="Korean skincare curated for Nigeria" loading="lazy">
        <div class="ab-gallery-label">Curated in Seoul, loved in Lagos</div>
      </div>
      <div class="ab-gallery-cell">
        <img src="https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=600&auto=format&fit=crop&q=80" alt="Ingredient transparency" loading="lazy">
        <div class="ab-gallery-label">Ingredient transparency</div>
      </div>
      <div class="ab-gallery-cell">
        <img src="https://images.unsplash.com/photo-1523264939339-c89f9dadde2e?w=600&auto=format&fit=crop&q=80" alt="Made for melanin-rich skin" loading="lazy">
        <div class="ab-gallery-label">Made for melanin</div>
      </div>
      <div class="ab-gallery-cell">
        <img src="https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=600&auto=format&fit=crop&q=80" alt="Skincare community" loading="lazy">
        <div class="ab-gallery-label">Our community</div>
      </div>
      <div class="ab-gallery-cell">
        <img src="https://images.unsplash.com/photo-1543178454-5d3ca9bf03b0?w=600&auto=format&fit=crop&q=80" alt="200+ verified K-beauty products" loading="lazy">
        <div class="ab-gallery-label">200+ verified products</div>
      </div>
    </div>
  </div>
</section>

<!-- Core Values -->
<section class="ab-values">
  <div class="ab-values-inner">
    <div class="ab-section-header">
      <div>
        <div class="ab-section-eyebrow"><span>What We Stand For</span></div>
        <h2 class="display-md" style="color:var(--black);line-height:1.1">Six principles that<br>guide every decision.</h2>
      </div>
      <p style="font-size:.97rem;line-height:1.8;color:var(--text-secondary);padding-bottom:6px">These aren't aspirational values on a wall — they're the criteria we use when choosing what to stock, how to price it, and how to treat each customer.</p>
    </div>

    <div class="ab-values-grid">
      <div class="ab-value-cell">
        <span class="ab-value-num">01</span>
        <div class="ab-value-title">Science-led curation</div>
        <p class="ab-value-desc">Every product passes a rigorous review: ingredient safety for melanin-rich skin, efficacy evidence, and suitability for Nigerian humidity and harmattan seasons.</p>
        <div class="ab-value-arrow">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17L17 7M7 7h10v10"/></svg>
        </div>
      </div>
      <div class="ab-value-cell">
        <span class="ab-value-num">02</span>
        <div class="ab-value-title">100% authenticity</div>
        <p class="ab-value-desc">We source directly from Korean brands and authorised distributors. No grey-market imports. Every product comes with verifiable authenticity.</p>
        <div class="ab-value-arrow">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17L17 7M7 7h10v10"/></svg>
        </div>
      </div>
      <div class="ab-value-cell">
        <span class="ab-value-num">03</span>
        <div class="ab-value-title">Melanin-first thinking</div>
        <p class="ab-value-desc">We actively exclude products with harmful whitening agents. Our recommendations celebrate your skin tone rather than attempting to change it.</p>
        <div class="ab-value-arrow">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17L17 7M7 7h10v10"/></svg>
        </div>
      </div>
      <div class="ab-value-cell">
        <span class="ab-value-num">04</span>
        <div class="ab-value-title">Climate-aware selection</div>
        <p class="ab-value-desc">Lagos heat. Abuja dust. Port Harcourt humidity. We tag products by Nigerian climate suitability so your routine survives the weather.</p>
        <div class="ab-value-arrow">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17L17 7M7 7h10v10"/></svg>
        </div>
      </div>
      <div class="ab-value-cell">
        <span class="ab-value-num">05</span>
        <div class="ab-value-title">Fair, honest pricing</div>
        <p class="ab-value-desc">K-beauty shouldn't be a luxury. Our pricing, loyalty rewards, and subscription plans are designed to make consistent skincare accessible.</p>
        <div class="ab-value-arrow">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17L17 7M7 7h10v10"/></svg>
        </div>
      </div>
      <div class="ab-value-cell">
        <span class="ab-value-num">06</span>
        <div class="ab-value-title">Community above all</div>
        <p class="ab-value-desc">Our Gallery, blog, and influencer programme exist to build real knowledge-sharing between Nigerian skincare lovers — not just to sell products.</p>
        <div class="ab-value-arrow">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17L17 7M7 7h10v10"/></svg>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="ab-cta">
  <div class="ab-cta-inner">
    <div>
      <h2 class="ab-cta-title">Ready to find your<br><em>perfect routine?</em></h2>
      <p class="ab-cta-desc">Take our 2-minute Skin Quiz and we'll personalise your K-beauty routine based on your skin type, concerns, and environment.</p>
    </div>
    <div class="ab-cta-actions">
      <a href="{{ route('quiz') }}" class="btn btn-primary btn-lg">Take the Skin Quiz</a>
      <a href="{{ route('contact') }}" class="btn btn-dark btn-lg" style="border:1px solid rgba(255,255,255,.15)">Get in Touch</a>
    </div>
  </div>
</section>
@endsection
