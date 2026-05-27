@extends('layouts.app')
@section('title', 'Be an Influencer — Kominhoo Beauty')

@section('content')
<style>
  /* ── Hero ──────────────────────────────────────────────────── */
  .inf-hero {
    background: var(--rose-dark);
    position: relative;
    overflow: hidden;
    padding: 100px 0 80px;
  }
  .inf-hero::before {
    content: '';
    position: absolute;
    top: -120px; right: -160px;
    width: 520px; height: 520px;
    background: radial-gradient(circle, rgba(212,217,148,.18) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
  }
  .inf-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -100px;
    width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(212,217,148,.10) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
  }
  .inf-hero-inner {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .inf-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--lime);
    margin-bottom: 20px;
  }
  .inf-eyebrow-dot {
    width: 6px; height: 6px;
    background: var(--lime);
    border-radius: 50%;
    flex-shrink: 0;
  }
  .inf-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.4rem, 5vw, 3.6rem);
    line-height: 1.08;
    color: #fff;
    margin: 0 0 22px;
  }
  .inf-hero-title em {
    font-style: italic;
    color: var(--lime);
  }
  .inf-hero-desc {
    font-size: 1.03rem;
    line-height: 1.72;
    color: rgba(255,255,255,.58);
    margin-bottom: 36px;
    max-width: 480px;
  }
  .inf-hero-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--lime);
    color: var(--black);
    font-weight: 700;
    font-size: .92rem;
    padding: 14px 28px;
    border-radius: 999px;
    text-decoration: none;
    transition: transform .18s, box-shadow .18s;
    box-shadow: 0 4px 20px rgba(212,217,148,.35);
  }
  .inf-hero-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(212,217,148,.45);
  }
  .inf-hero-stats {
    display: flex;
    gap: 32px;
    margin-top: 40px;
    padding-top: 32px;
    border-top: 1px solid rgba(255,255,255,.1);
  }
  .inf-stat-val {
    font-family: var(--font-display);
    font-size: 2rem;
    color: #fff;
    line-height: 1;
  }
  .inf-stat-label {
    font-size: .73rem;
    color: rgba(255,255,255,.38);
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: .06em;
  }
  /* hero visual side */
  .inf-hero-visual {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .inf-glow-ring {
    width: 340px; height: 340px;
    border-radius: 50%;
    border: 1.5px solid rgba(212,217,148,.22);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }
  .inf-glow-ring::before {
    content: '';
    position: absolute;
    inset: 20px;
    border-radius: 50%;
    border: 1px dashed rgba(212,217,148,.14);
  }
  .inf-center-badge {
    width: 200px; height: 200px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e1e1e, #2a2a2a);
    border: 2px solid rgba(212,217,148,.3);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: 0 0 60px rgba(212,217,148,.15), inset 0 0 40px rgba(212,217,148,.05);
  }
  .inf-center-badge-icon { font-size: 2.8rem; line-height: 1; margin-bottom: 8px; }
  .inf-center-badge-text {
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.5);
  }
  .inf-orbit-item {
    position: absolute;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    backdrop-filter: blur(8px);
    border-radius: 14px;
    padding: 10px 14px;
    font-size: .72rem;
    font-weight: 600;
    color: rgba(255,255,255,.75);
    white-space: nowrap;
  }
  .inf-orbit-item:nth-child(2) { top: 10px;  left: 50%; transform: translateX(-50%); }
  .inf-orbit-item:nth-child(3) { bottom: 10px; left: 50%; transform: translateX(-50%); }
  .inf-orbit-item:nth-child(4) { left: -20px; top: 50%; transform: translateY(-50%); }
  .inf-orbit-item:nth-child(5) { right: -20px; top: 50%; transform: translateY(-50%); }

  /* ── Perks ──────────────────────────────────────────────────── */
  .inf-perks {
    background: var(--cream);
    padding: 80px 0;
  }
  .inf-perks-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 48px;
  }
  .inf-perk-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 20px;
    padding: 32px 28px;
    transition: transform .2s, box-shadow .2s;
  }
  .inf-perk-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(0,0,0,.08);
  }
  .inf-perk-icon {
    width: 52px; height: 52px;
    background: var(--rose-dark);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 20px;
  }
  .inf-perk-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--black);
    margin-bottom: 8px;
  }
  .inf-perk-desc {
    font-size: .84rem;
    line-height: 1.65;
    color: var(--text-secondary);
  }

  /* ── Who We're Looking For ──────────────────────────────────── */
  .inf-who {
    background: #fff;
    padding: 80px 0;
  }
  .inf-who-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    margin-top: 0;
  }
  .inf-who-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-top: 32px;
  }
  .inf-who-list li {
    display: flex;
    gap: 14px;
    align-items: flex-start;
  }
  .inf-who-check {
    width: 26px; height: 26px;
    background: var(--rose-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
    color: var(--lime);
    font-size: .75rem;
    font-weight: 700;
  }
  .inf-who-text strong {
    display: block;
    font-size: .92rem;
    font-weight: 700;
    color: var(--black);
    margin-bottom: 2px;
  }
  .inf-who-text span {
    font-size: .82rem;
    color: var(--text-secondary);
    line-height: 1.6;
  }
  .inf-tiers {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }
  .inf-tier-card {
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 18px;
  }
  .inf-tier-badge {
    min-width: 90px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    flex-shrink: 0;
  }
  .inf-tier-nano  { background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0; }
  .inf-tier-micro { background: #eff6ff; color: #2563eb; border: 1.5px solid #bfdbfe; }
  .inf-tier-mid   { background: #fdf4ff; color: #9333ea; border: 1.5px solid #e9d5ff; }
  .inf-tier-macro { background: #fff7ed; color: #ea580c; border: 1.5px solid #fed7aa; }
  .inf-tier-info strong { display: block; font-size: .88rem; font-weight: 700; color: var(--black); margin-bottom: 3px; }
  .inf-tier-info span   { font-size: .78rem; color: var(--text-secondary); }

  /* ── Application Form ─────────────────────────────────────────── */
  .inf-form-section {
    background: var(--cream);
    padding: 80px 0 100px;
  }
  .inf-form-wrap {
    max-width: 760px;
    margin: 0 auto;
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 24px;
    padding: 48px 52px;
    box-shadow: 0 8px 40px rgba(0,0,0,.06);
  }
  .inf-form-title {
    font-family: var(--font-display);
    font-size: 1.85rem;
    color: var(--black);
    margin: 0 0 6px;
  }
  .inf-form-subtitle {
    font-size: .88rem;
    color: var(--text-secondary);
    margin-bottom: 36px;
  }
  .inf-form-group {
    margin-bottom: 22px;
  }
  .inf-form-label {
    display: block;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(10,10,10,.5);
    margin-bottom: 7px;
  }
  .inf-form-label span { color: #e63434; margin-left: 2px; }
  .inf-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }
  .inf-form-input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    font-size: .9rem;
    font-family: inherit;
    color: var(--black);
    background: #fff;
    transition: border-color .18s, box-shadow .18s;
    outline: none;
    box-sizing: border-box;
  }
  .inf-form-input:focus {
    border-color: var(--black);
    box-shadow: 0 0 0 3px rgba(10,10,10,.07);
  }
  .inf-form-input.error {
    border-color: #e63434;
    box-shadow: 0 0 0 3px rgba(230,52,52,.08);
  }
  .inf-form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23888' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 10px;
    padding-right: 36px;
    cursor: pointer;
  }
  .inf-form-textarea {
    resize: vertical;
    min-height: 130px;
    line-height: 1.6;
  }
  .inf-form-hint {
    font-size: .72rem;
    color: rgba(10,10,10,.4);
    margin-top: 5px;
  }
  .inf-error-msg {
    font-size: .76rem;
    color: #e63434;
    margin-top: 5px;
    font-weight: 600;
  }
  .inf-form-divider {
    border: none;
    border-top: 1.5px solid #f4f5f7;
    margin: 28px 0;
  }
  .inf-form-section-label {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(10,10,10,.3);
    margin-bottom: 18px;
  }
  .inf-submit-btn {
    width: 100%;
    padding: 15px;
    background: var(--rose-dark);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-size: .95rem;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: background .18s, transform .18s, box-shadow .18s;
    margin-top: 8px;
    letter-spacing: .01em;
  }
  .inf-submit-btn:hover {
    background: var(--rose);
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(0,0,0,.18);
  }
  .inf-submit-btn:active { transform: none; }
  .inf-terms-note {
    font-size: .75rem;
    color: rgba(10,10,10,.38);
    text-align: center;
    margin-top: 14px;
    line-height: 1.6;
  }

  /* ── Success banner ───────────────────────────────────────────── */
  .inf-success-banner {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1.5px solid #bbf7d0;
    border-radius: 16px;
    padding: 24px 28px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 28px;
  }
  .inf-success-icon {
    width: 40px; height: 40px;
    background: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 700;
  }
  .inf-success-title { font-weight: 700; font-size: .95rem; color: #14532d; margin-bottom: 3px; }
  .inf-success-text  { font-size: .84rem; color: #166534; line-height: 1.6; }

  /* ── Responsive ──────────────────────────────────────────────── */
  @media (max-width: 900px) {
    .inf-hero-inner  { grid-template-columns: 1fr; gap: 40px; }
    .inf-hero-visual { display: none; }
    .inf-perks-grid  { grid-template-columns: 1fr 1fr; }
    .inf-who-grid    { grid-template-columns: 1fr; gap: 40px; }
    .inf-form-wrap   { padding: 32px 28px; }
    .inf-form-row    { grid-template-columns: 1fr; gap: 0; }
  }
  @media (max-width: 600px) {
    .inf-hero { padding: 70px 0 60px; }
    .inf-perks-grid { grid-template-columns: 1fr; }
    .inf-hero-stats { gap: 20px; }
  }
</style>

<!-- ── Hero ──────────────────────────────────────────────────────── -->
<section class="inf-hero">
  <div class="inf-hero-inner">
    <div>
      <div class="inf-eyebrow">
        <span class="inf-eyebrow-dot"></span>
        Influencer Partnership Programme
      </div>
      <h1 class="inf-hero-title">
        Share K-Beauty.<br>
        <em>Earn Beautifully.</em>
      </h1>
      <p class="inf-hero-desc">
        Join Nigeria's most exciting Korean skincare brand as a partner. Showcase real products, earn commissions, and build your platform while helping your audience discover skincare that actually works for melanin-rich skin.
      </p>
      <a href="#apply" class="inf-hero-cta">
        Apply Now — It's Free
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <div class="inf-hero-stats">
        <div>
          <div class="inf-stat-val">₦250k+</div>
          <div class="inf-stat-label">Earned by partners</div>
        </div>
        <div>
          <div class="inf-stat-val">40%</div>
          <div class="inf-stat-label">Commission rate</div>
        </div>
        <div>
          <div class="inf-stat-val">500+</div>
          <div class="inf-stat-label">Products to promote</div>
        </div>
      </div>
    </div>

    <div class="inf-hero-visual">
      <div class="inf-glow-ring">
        <div class="inf-center-badge">
          <div class="inf-center-badge-icon"></div>
          <div class="inf-center-badge-text" style="color:var(--lime);font-size:.75rem;font-weight:700;margin-top:4px;">Kominhoo</div>
          <div class="inf-center-badge-text">Influencer</div>
        </div>
        <div class="inf-orbit-item"> Instagram</div>
        <div class="inf-orbit-item"> TikTok</div>
        <div class="inf-orbit-item"> Up to 40%</div>
        <div class="inf-orbit-item"> Free Products</div>
      </div>
    </div>
  </div>
</section>

<!-- ── Perks ─────────────────────────────────────────────────────── -->
<section class="inf-perks">
  <div class="container">
    <div class="section-header centered">
      <div class="section-eyebrow"><span class="dot"></span> Why Partner With Us</div>
      <h2 class="display-sm section-title">Everything you need to <em>thrive</em></h2>
      <p class="section-desc" style="max-width:520px;margin:0 auto">We treat our influencers like business partners — not just promoters.</p>
    </div>

    <div class="inf-perks-grid">
      <div class="inf-perk-card">
        <div class="inf-perk-icon">💰</div>
        <div class="inf-perk-title">Generous Commissions</div>
        <div class="inf-perk-desc">Earn 15–40% on every sale you drive. Commissions are paid out weekly in naira directly to your bank account. No caps, no hidden fees.</div>
      </div>
      <div class="inf-perk-card">
        <div class="inf-perk-icon">🎁</div>
        <div class="inf-perk-title">Free Product Gifting</div>
        <div class="inf-perk-desc">Receive curated product packages matched to your skin type — before they launch. Create authentic content with products you've actually tested.</div>
      </div>
      <div class="inf-perk-card">
        <div class="inf-perk-icon">🔗</div>
        <div class="inf-perk-title">Your Own Discount Code</div>
        <div class="inf-perk-desc">Get a personalised promo code your audience can use for 10% off. Drives conversions and builds loyalty to your personal brand.</div>
      </div>
      <div class="inf-perk-card">
        <div class="inf-perk-icon">📊</div>
        <div class="inf-perk-title">Real-Time Dashboard</div>
        <div class="inf-perk-desc">Track clicks, conversions, and earnings in your personal dashboard. Know exactly what's working and when you'll be paid.</div>
      </div>
      <div class="inf-perk-card">
        <div class="inf-perk-icon">🌟</div>
        <div class="inf-perk-title">Early Access & Exclusives</div>
        <div class="inf-perk-desc">Be the first to know about new arrivals, limited-edition sets, and flash sales. Give your audience content nobody else has yet.</div>
      </div>
      <div class="inf-perk-card">
        <div class="inf-perk-icon">🤝</div>
        <div class="inf-perk-title">Dedicated Support</div>
        <div class="inf-perk-desc">A real human from our team responds to every partner. Creative briefs, product guides, and content tips are all included.</div>
      </div>
    </div>
  </div>
</section>

<!-- ── Who We're Looking For ─────────────────────────────────────── -->
<section class="inf-who">
  <div class="container">
    <div class="inf-who-grid">
      <div>
        <div class="section-eyebrow"><span class="dot"></span> Who Qualifies</div>
        <h2 class="display-sm section-title" style="margin-top:12px">You don't need<br>millions of followers</h2>
        <ul class="inf-who-list">
          <li>
            <div class="inf-who-check">✓</div>
            <div class="inf-who-text">
              <strong>Any Nigerian-based creator</strong>
              <span>Instagram, TikTok, YouTube, Twitter — all platforms welcome.</span>
            </div>
          </li>
          <li>
            <div class="inf-who-check">✓</div>
            <div class="inf-who-text">
              <strong>At least 500 engaged followers</strong>
              <span>We value authentic engagement over vanity metrics. Nano-influencers often convert better.</span>
            </div>
          </li>
          <li>
            <div class="inf-who-check">✓</div>
            <div class="inf-who-text">
              <strong>Genuine interest in skincare or beauty</strong>
              <span>You don't have to be a beauty guru — curiosity and authenticity matter most.</span>
            </div>
          </li>
          <li>
            <div class="inf-who-check">✓</div>
            <div class="inf-who-text">
              <strong>Consistent content creator</strong>
              <span>Posting at least twice a week shows your audience you're active and trustworthy.</span>
            </div>
          </li>
        </ul>
      </div>

      <div>
        <div class="section-eyebrow" style="margin-bottom:20px"><span class="dot"></span> Partner Tiers</div>
        <div class="inf-tiers">
          <div class="inf-tier-card">
            <div class="inf-tier-badge inf-tier-nano">Nano</div>
            <div class="inf-tier-info">
              <strong>500 – 10K followers</strong>
              <span>15% commission · Free starter kit · Your promo code</span>
            </div>
          </div>
          <div class="inf-tier-card">
            <div class="inf-tier-badge inf-tier-micro">Micro</div>
            <div class="inf-tier-info">
              <strong>10K – 50K followers</strong>
              <span>22% commission · Monthly gifting · Priority support</span>
            </div>
          </div>
          <div class="inf-tier-card">
            <div class="inf-tier-badge inf-tier-mid">Mid-Tier</div>
            <div class="inf-tier-info">
              <strong>50K – 250K followers</strong>
              <span>30% commission · Paid collab fees · Co-branded content</span>
            </div>
          </div>
          <div class="inf-tier-card">
            <div class="inf-tier-badge inf-tier-macro">Macro</div>
            <div class="inf-tier-info">
              <strong>250K+ followers</strong>
              <span>40% commission · Custom deals · Brand ambassador title</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── Application Form ──────────────────────────────────────────── -->
<section class="inf-form-section" id="apply">
  <div class="container">
    <div class="section-header centered" style="margin-bottom:40px">
      <div class="section-eyebrow"><span class="dot"></span> Apply Now</div>
      <h2 class="display-sm section-title">Ready to grow together?</h2>
      <p class="section-desc">Fill in the form below. We review every application within 3–5 business days.</p>
    </div>

    <div class="inf-form-wrap">

      @if(session('success'))
        <div class="inf-success-banner">
          <div class="inf-success-icon">✓</div>
          <div>
            <div class="inf-success-title">Application submitted!</div>
            <div class="inf-success-text">{{ session('success') }}</div>
          </div>
        </div>
      @endif

      @if($errors->any())
        <div style="background:#fff1f1;border:1.5px solid #fca5a5;border-radius:14px;padding:18px 22px;margin-bottom:24px;">
          <div style="font-size:.82rem;font-weight:700;color:#991b1b;margin-bottom:6px">Please fix the following:</div>
          @foreach($errors->all() as $error)
            <div style="font-size:.8rem;color:#dc2626;margin-top:4px">• {{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('influencer.submit') }}" novalidate>
        @csrf

        <div class="inf-form-section-label">Personal Information</div>

        <div class="inf-form-row">
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_name">Full Name <span>*</span></label>
            <input type="text" id="inf_name" name="name" class="inf-form-input {{ $errors->has('name') ? 'error' : '' }}"
              placeholder="Adaeze Okonkwo" value="{{ old('name') }}" required>
            @error('name')<div class="inf-error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_email">Email Address <span>*</span></label>
            <input type="email" id="inf_email" name="email" class="inf-form-input {{ $errors->has('email') ? 'error' : '' }}"
              placeholder="adaeze@gmail.com" value="{{ old('email') }}" required>
            @error('email')<div class="inf-error-msg">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="inf-form-row">
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_phone">Phone Number <span>*</span></label>
            <input type="tel" id="inf_phone" name="phone" class="inf-form-input {{ $errors->has('phone') ? 'error' : '' }}"
              placeholder="+234 801 234 5678" value="{{ old('phone') }}" required>
            @error('phone')<div class="inf-error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_location">City / State <span>*</span></label>
            <input type="text" id="inf_location" name="location" class="inf-form-input {{ $errors->has('location') ? 'error' : '' }}"
              placeholder="Lagos, Nigeria" value="{{ old('location') }}" required>
            @error('location')<div class="inf-error-msg">{{ $message }}</div>@enderror
          </div>
        </div>

        <hr class="inf-form-divider">
        <div class="inf-form-section-label">Social Media Presence</div>

        <div class="inf-form-row">
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_instagram">Instagram Handle <span>*</span></label>
            <input type="text" id="inf_instagram" name="instagram" class="inf-form-input {{ $errors->has('instagram') ? 'error' : '' }}"
              placeholder="@yourusername" value="{{ old('instagram') }}" required>
            @error('instagram')<div class="inf-error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_tiktok">TikTok Handle</label>
            <input type="text" id="inf_tiktok" name="tiktok" class="inf-form-input"
              placeholder="@yourusername" value="{{ old('tiktok') }}">
            <div class="inf-form-hint">Optional — include if you create TikTok content</div>
          </div>
        </div>

        <div class="inf-form-row">
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_followers">Total Follower Count <span>*</span></label>
            <select id="inf_followers" name="followers" class="inf-form-input inf-form-select {{ $errors->has('followers') ? 'error' : '' }}" required>
              <option value="" disabled {{ old('followers') ? '' : 'selected' }}>Select a range…</option>
              <option value="500-1K"    {{ old('followers') === '500-1K'    ? 'selected' : '' }}>500 – 1,000</option>
              <option value="1K-5K"    {{ old('followers') === '1K-5K'    ? 'selected' : '' }}>1,000 – 5,000</option>
              <option value="5K-10K"   {{ old('followers') === '5K-10K'   ? 'selected' : '' }}>5,000 – 10,000</option>
              <option value="10K-50K"  {{ old('followers') === '10K-50K'  ? 'selected' : '' }}>10,000 – 50,000</option>
              <option value="50K-250K" {{ old('followers') === '50K-250K' ? 'selected' : '' }}>50,000 – 250,000</option>
              <option value="250K+"    {{ old('followers') === '250K+'    ? 'selected' : '' }}>250,000+</option>
            </select>
            @error('followers')<div class="inf-error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="inf-form-group">
            <label class="inf-form-label" for="inf_niche">Content Niche <span>*</span></label>
            <select id="inf_niche" name="niche" class="inf-form-input inf-form-select {{ $errors->has('niche') ? 'error' : '' }}" required>
              <option value="" disabled {{ old('niche') ? '' : 'selected' }}>Select your niche…</option>
              <option value="Skincare"    {{ old('niche') === 'Skincare'    ? 'selected' : '' }}>Skincare</option>
              <option value="Beauty"      {{ old('niche') === 'Beauty'      ? 'selected' : '' }}>Beauty & Makeup</option>
              <option value="Lifestyle"   {{ old('niche') === 'Lifestyle'   ? 'selected' : '' }}>Lifestyle</option>
              <option value="Fashion"     {{ old('niche') === 'Fashion'     ? 'selected' : '' }}>Fashion & Style</option>
              <option value="Wellness"    {{ old('niche') === 'Wellness'    ? 'selected' : '' }}>Wellness & Health</option>
              <option value="Haircare"    {{ old('niche') === 'Haircare'    ? 'selected' : '' }}>Haircare</option>
              <option value="Foodie"      {{ old('niche') === 'Foodie'      ? 'selected' : '' }}>Food & Lifestyle</option>
              <option value="Tech"        {{ old('niche') === 'Tech'        ? 'selected' : '' }}>Tech & Reviews</option>
              <option value="Other"       {{ old('niche') === 'Other'       ? 'selected' : '' }}>Other</option>
            </select>
            @error('niche')<div class="inf-error-msg">{{ $message }}</div>@enderror
          </div>
        </div>

        <hr class="inf-form-divider">
        <div class="inf-form-section-label">About You</div>

        <div class="inf-form-group">
          <label class="inf-form-label" for="inf_skin_type">Your Skin Type</label>
          <select id="inf_skin_type" name="skin_type" class="inf-form-input inf-form-select">
            <option value="">Prefer not to say</option>
            <option value="Oily"        {{ old('skin_type') === 'Oily'        ? 'selected' : '' }}>Oily</option>
            <option value="Dry"         {{ old('skin_type') === 'Dry'         ? 'selected' : '' }}>Dry</option>
            <option value="Combination" {{ old('skin_type') === 'Combination' ? 'selected' : '' }}>Combination</option>
            <option value="Normal"      {{ old('skin_type') === 'Normal'      ? 'selected' : '' }}>Normal</option>
            <option value="Sensitive"   {{ old('skin_type') === 'Sensitive'   ? 'selected' : '' }}>Sensitive</option>
          </select>
          <div class="inf-form-hint">Helps us send you products that actually work for your skin</div>
        </div>

        <div class="inf-form-group">
          <label class="inf-form-label" for="inf_message">Why do you want to partner with Kominhoo? <span>*</span></label>
          <textarea id="inf_message" name="message" class="inf-form-input inf-form-textarea {{ $errors->has('message') ? 'error' : '' }}"
            placeholder="Tell us about yourself, your audience, and why you'd be a great Kominhoo partner. Mention any skincare journey or Korean beauty experience you have…"
            required>{{ old('message') }}</textarea>
          @error('message')<div class="inf-error-msg">{{ $message }}</div>@enderror
          <div class="inf-form-hint">Minimum 20 characters. Be genuine — we read every application personally.</div>
        </div>

        <button type="submit" class="inf-submit-btn">
          Submit Application →
        </button>
        <div class="inf-terms-note">
          By submitting, you agree to our <a href="#" style="color:var(--black);text-decoration:underline;">Partner Terms</a> and confirm you are based in Nigeria. We'll contact you at the email provided.
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
