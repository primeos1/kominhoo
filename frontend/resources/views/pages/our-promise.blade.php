@extends('layouts.app')
@section('title', 'Our Promise — Kominhoo Beauty')

@section('content')
<style>
/* ── Promise Hero ─────────────────────────────────────── */
.pr-hero {
  background: var(--rose-dark);
  padding: 130px 0 100px;
  position: relative;
  overflow: hidden;
}
.pr-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    repeating-linear-gradient(
      90deg,
      transparent,
      transparent 80px,
      rgba(255,255,255,.015) 80px,
      rgba(255,255,255,.015) 81px
    ),
    repeating-linear-gradient(
      0deg,
      transparent,
      transparent 80px,
      rgba(255,255,255,.015) 80px,
      rgba(255,255,255,.015) 81px
    );
}
.pr-hero::after {
  content: '';
  position: absolute;
  top: -80px; right: -80px;
  width: 420px; height: 420px;
  border-radius: 50%;
  border: 1px solid rgba(212,217,148,.15);
}
.pr-hero-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
  position: relative;
  z-index: 1;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
}
.pr-eyebrow {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 28px;
}
.pr-eyebrow::before {
  content: '';
  width: 32px;
  height: 1px;
  background: var(--lime);
}
.pr-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--lime);
}
.pr-hero-title {
  font-family: var(--font-display);
  font-size: clamp(2.6rem, 5vw, 4rem);
  line-height: 1.06;
  color: #fff;
  margin-bottom: 26px;
}
.pr-hero-title em { font-style: italic; color: var(--lime); }
.pr-hero-desc {
  font-size: 1.05rem;
  line-height: 1.78;
  color: rgba(255,255,255,.5);
  margin-bottom: 40px;
}
.pr-hero-visual {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.pr-commitment-card {
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  backdrop-filter: blur(12px);
  border-radius: 4px;
  padding: 24px 28px;
  display: flex;
  align-items: center;
  gap: 20px;
  transition: background var(--t-base);
}
.pr-commitment-card:hover { background: rgba(255,255,255,.1); }
.pr-cc-num {
  font-family: var(--font-display);
  font-size: 2rem;
  font-weight: 700;
  color: rgba(212,217,148,.4);
  line-height: 1;
  flex-shrink: 0;
  width: 44px;
}
.pr-cc-title {
  font-weight: 700;
  font-size: .95rem;
  color: #fff;
  margin-bottom: 4px;
}
.pr-cc-sub {
  font-size: .8rem;
  color: rgba(255,255,255,.45);
}

/* ── Pillars ──────────────────────────────────────────── */
.pr-pillars {
  background: var(--cream);
  padding: 120px 0;
}
.pr-pillars-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
}
.pr-pillars-header {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: end;
  margin-bottom: 72px;
}
.pr-section-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}
.pr-section-eyebrow::before {
  content: '';
  width: 24px; height: 1px;
  background: var(--text-muted);
}
.pr-section-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--text-muted);
}
.pr-pillars-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}
.pr-pillar {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 4px;
  padding: 44px 40px;
  position: relative;
  overflow: hidden;
  transition: transform var(--t-base), box-shadow var(--t-base);
}
.pr-pillar:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 60px rgba(0,0,0,.08);
}
.pr-pillar::after {
  content: '';
  position: absolute;
  top: 0; left: 0;
  width: 3px;
  height: 0;
  background: var(--lime);
  transition: height .4s ease;
}
.pr-pillar:hover::after { height: 100%; }
.pr-pillar-num {
  font-family: var(--font-display);
  font-size: .78rem;
  font-weight: 700;
  color: var(--lime);
  letter-spacing: .12em;
  display: block;
  margin-bottom: 20px;
}
.pr-pillar-title {
  font-family: var(--font-display);
  font-size: 1.4rem;
  font-weight: 700;
  color: var(--black);
  margin-bottom: 16px;
  line-height: 1.2;
}
.pr-pillar-desc {
  font-size: .9rem;
  line-height: 1.8;
  color: var(--text-secondary);
  margin-bottom: 24px;
}
.pr-pillar-items {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding-top: 20px;
  border-top: 1px solid var(--border);
}
.pr-pillar-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: .86rem;
  color: var(--text-secondary);
  line-height: 1.5;
}
.pr-pillar-check {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: var(--lime);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
}
.pr-pillar-check svg { display: block; }

/* ── Guarantee Strip ───────────────────────────────────── */
.pr-guarantee {
  background: var(--black);
  padding: 0;
}
.pr-guarantee-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
}
.pr-guarantee-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  border-left: 1px solid rgba(255,255,255,.07);
}
.pr-g-block {
  padding: 72px 48px;
  border-right: 1px solid rgba(255,255,255,.07);
  border-bottom: 1px solid rgba(255,255,255,.07);
  position: relative;
  transition: background var(--t-base);
}
.pr-g-block:hover { background: rgba(255,255,255,.03); }
.pr-g-num {
  font-family: var(--font-display);
  font-size: 3.5rem;
  font-weight: 700;
  color: var(--lime);
  line-height: 1;
  margin-bottom: 16px;
  opacity: .7;
}
.pr-g-title {
  font-family: var(--font-display);
  font-size: 1.2rem;
  font-weight: 700;
  color: #fff;
  margin-bottom: 12px;
}
.pr-g-desc {
  font-size: .88rem;
  line-height: 1.72;
  color: rgba(255,255,255,.42);
}
.pr-guarantee-cta {
  padding: 64px 48px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 16px;
  border-right: 1px solid rgba(255,255,255,.07);
  border-bottom: 1px solid rgba(255,255,255,.07);
  grid-column: span 3;
  border-top: 1px solid rgba(255,255,255,.07);
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
}
.pr-guarantee-cta-text {
  font-family: var(--font-display);
  font-size: clamp(1.4rem, 2.5vw, 2rem);
  color: #fff;
  line-height: 1.2;
}
.pr-guarantee-cta-text em { font-style: italic; color: var(--lime); }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 1024px) {
  .pr-hero-inner { grid-template-columns: 1fr; gap: 48px; padding: 0 24px; }
  .pr-pillars-inner { padding: 0 24px; }
  .pr-pillars-header { grid-template-columns: 1fr; gap: 16px; }
  .pr-guarantee-inner { padding: 0 24px; }
  .pr-guarantee-grid { grid-template-columns: 1fr 1fr; }
  .pr-guarantee-cta { grid-column: span 2; flex-direction: column; align-items: flex-start; }
}
@media (max-width: 640px) {
  .pr-hero { padding: 90px 0 70px; }
  .pr-pillars-grid { grid-template-columns: 1fr; }
  .pr-guarantee-grid { grid-template-columns: 1fr; }
  .pr-guarantee-cta { grid-column: span 1; }
  .pr-pillar { padding: 32px 28px; }
  .pr-g-block { padding: 44px 28px; }
}
</style>

<!-- Hero -->
<section class="pr-hero">
  <div class="pr-hero-inner">
    <div>
      <div class="pr-eyebrow"><span>Our Commitment</span></div>
      <h1 class="pr-hero-title">We promise you<br><em>real results,</em><br>real products, real care.</h1>
      <p class="pr-hero-desc">Shopping skincare online takes trust. Here's exactly what we commit to every single customer — specific, measurable, and in writing.</p>
      <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">Talk to Our Team</a>
    </div>
    <div class="pr-hero-visual">
      <div class="pr-commitment-card">
        <div class="pr-cc-num">01</div>
        <div>
          <div class="pr-cc-title">Genuine products, always</div>
          <div class="pr-cc-sub">Full supply-chain traceability on every item</div>
        </div>
      </div>
      <div class="pr-commitment-card">
        <div class="pr-cc-num">02</div>
        <div>
          <div class="pr-cc-title">Safe for melanin-rich skin</div>
          <div class="pr-cc-sub">No harmful whitening or hyperpigmentation agents</div>
        </div>
      </div>
      <div class="pr-commitment-card">
        <div class="pr-cc-num">03</div>
        <div>
          <div class="pr-cc-title">Reliable delivery, nationwide</div>
          <div class="pr-cc-sub">All 36 states, live tracking, climate-safe packaging</div>
        </div>
      </div>
      <div class="pr-commitment-card">
        <div class="pr-cc-num">04</div>
        <div>
          <div class="pr-cc-title">Human customer support</div>
          <div class="pr-cc-sub">Real skincare advisors, 4-hour response weekdays</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Pillars -->
<section class="pr-pillars">
  <div class="pr-pillars-inner">
    <div class="pr-pillars-header">
      <div>
        <div class="pr-section-eyebrow"><span>Four Pillars</span></div>
        <h2 class="display-md" style="color:var(--black);line-height:1.1">What we commit to<br>every customer.</h2>
      </div>
      <p style="font-size:.97rem;line-height:1.8;color:var(--text-secondary)">Not marketing language. These are specific, testable commitments that determine how we make decisions about product sourcing, logistics, and support.</p>
    </div>

    <div class="pr-pillars-grid">
      <div class="pr-pillar">
        <span class="pr-pillar-num">01</span>
        <div class="pr-pillar-title">Genuine products, always</div>
        <p class="pr-pillar-desc">Every item on Kominhoo is sourced from Korean brand distributors or directly from manufacturers. We maintain full supply-chain traceability.</p>
        <div class="pr-pillar-items">
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Direct partnerships with Korean brands
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Batch authenticity checks on every shipment
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Full refund if any product proves inauthentic
          </div>
        </div>
      </div>

      <div class="pr-pillar">
        <span class="pr-pillar-num">02</span>
        <div class="pr-pillar-title">Safe for melanin-rich skin</div>
        <p class="pr-pillar-desc">We actively screen every product for ingredients that can cause hyperpigmentation, irritation, or unwanted lightening in darker skin tones.</p>
        <div class="pr-pillar-items">
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            No harmful hydroquinone or mercury compounds
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Clear ingredient flagging for sensitive skin
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Melanin-safe badge on all vetted products
          </div>
        </div>
      </div>

      <div class="pr-pillar">
        <span class="pr-pillar-num">03</span>
        <div class="pr-pillar-title">Reliable delivery, nationwide</div>
        <p class="pr-pillar-desc">We ship to all 36 states. Timelines are honest, tracking is real-time, and our packaging protects your order across Nigeria's roads.</p>
        <div class="pr-pillar-items">
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Lagos same-day for orders before 12pm
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Nationwide 2–5 business day delivery
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Live order tracking via SMS & email
          </div>
        </div>
      </div>

      <div class="pr-pillar">
        <span class="pr-pillar-num">04</span>
        <div class="pr-pillar-title">Human customer support</div>
        <p class="pr-pillar-desc">Real people who know skincare answer your messages. No bots, no scripts — just genuine help when your skin needs advice or an order goes wrong.</p>
        <div class="pr-pillar-items">
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            WhatsApp & email response within 4 hours
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            Skincare advisors, not just support reps
          </div>
          <div class="pr-pillar-item">
            <div class="pr-pillar-check"><svg width="10" height="8" fill="none" stroke="var(--black)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 10 8"><path d="M1 4l2.5 2.5L9 1"/></svg></div>
            No-questions returns within 7 days
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Guarantee -->
<section class="pr-guarantee">
  <div class="pr-guarantee-inner">
    <div class="pr-guarantee-grid">
      <div class="pr-g-block">
        <div class="pr-g-num">7</div>
        <div class="pr-g-title">Days to return</div>
        <p class="pr-g-desc">Return any unopened product within 7 days of delivery for a full refund — no forms, no explanations required.</p>
      </div>
      <div class="pr-g-block">
        <div class="pr-g-num">48h</div>
        <div class="pr-g-title">Resolution guarantee</div>
        <p class="pr-g-desc">All complaints resolved within 48 hours. If we miss that window, you receive store credit automatically.</p>
      </div>
      <div class="pr-g-block">
        <div class="pr-g-num">100%</div>
        <div class="pr-g-title">Refund or replace</div>
        <p class="pr-g-desc">Your choice: refund to original payment method or a replacement shipped free — with no friction.</p>
      </div>
      <div class="pr-guarantee-cta">
        <div class="pr-guarantee-cta-text">Not happy with your order?<br><em>We'll make it right.</em></div>
        <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">Contact Support</a>
      </div>
    </div>
  </div>
</section>
@endsection
