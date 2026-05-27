@extends('layouts.app')
@section('title', 'Terms & Conditions — Kominhoo Beauty')

@section('content')
<style>
/* ── Legal Hero ─────────────────────────────────────────── */
.legal-hero {
  background: var(--black);
  padding: 110px 0 80px;
  position: relative;
  overflow: hidden;
}
.legal-hero::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
}
.legal-hero-inner {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 48px;
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 60px;
  align-items: end;
}
.legal-eyebrow {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 24px;
}
.legal-eyebrow::before {
  content: '';
  width: 32px; height: 1px;
  background: var(--lime);
}
.legal-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--lime);
}
.legal-hero-title {
  font-family: var(--font-display);
  font-size: clamp(2.4rem, 5vw, 3.6rem);
  line-height: 1.06;
  color: #fff;
  margin-bottom: 16px;
}
.legal-hero-meta {
  font-size: .82rem;
  color: rgba(255,255,255,.28);
  letter-spacing: .04em;
}
.legal-hero-toc {
  background: rgba(255,255,255,.04);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 4px;
  padding: 28px 24px;
  min-width: 220px;
}
.legal-toc-title {
  font-size: .65rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: rgba(255,255,255,.25);
  margin-bottom: 16px;
}
.legal-toc-link {
  display: block;
  padding: 7px 0;
  border-bottom: 1px solid rgba(255,255,255,.05);
  font-size: .82rem;
  color: rgba(255,255,255,.45);
  text-decoration: none;
  transition: color var(--t-fast);
}
.legal-toc-link:last-child { border-bottom: none; }
.legal-toc-link:hover { color: var(--lime); }

/* ── Legal Body ─────────────────────────────────────────── */
.legal-body {
  background: var(--cream);
  padding: 80px 0 100px;
}
.legal-body-inner {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 48px;
  display: grid;
  grid-template-columns: 220px 1fr;
  gap: 48px;
  align-items: start;
}
.legal-sticky-nav {
  position: sticky;
  top: calc(var(--nav-h) + 24px);
}
.legal-sticky-title {
  font-size: .65rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 16px;
}
.legal-sticky-link {
  display: block;
  padding: 8px 0;
  font-size: .83rem;
  color: var(--text-muted);
  text-decoration: none;
  border-bottom: 1px solid var(--border);
  transition: color var(--t-fast);
}
.legal-sticky-link:last-child { border-bottom: none; }
.legal-sticky-link:hover { color: var(--black); }
.legal-summary {
  background: var(--lime);
  border-radius: 4px;
  padding: 22px 24px;
  margin-bottom: 32px;
}
.legal-summary-label {
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--black);
  margin-bottom: 8px;
  opacity: .6;
}
.legal-summary-text {
  font-size: .88rem;
  line-height: 1.7;
  color: var(--black);
  font-weight: 500;
}
.legal-section {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 4px;
  padding: 40px 44px;
  margin-bottom: 16px;
  scroll-margin-top: calc(var(--nav-h) + 24px);
}
.legal-section-header {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  margin-bottom: 24px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border);
}
.legal-section-num {
  font-family: var(--font-display);
  font-size: .75rem;
  font-weight: 700;
  color: var(--lime);
  letter-spacing: .1em;
  padding-top: 6px;
  flex-shrink: 0;
}
.legal-section-title {
  font-family: var(--font-display);
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--black);
  line-height: 1.2;
}
.legal-section p {
  font-size: .92rem;
  line-height: 1.85;
  color: var(--text-secondary);
  margin-bottom: 16px;
}
.legal-section p:last-child { margin-bottom: 0; }
.legal-section p strong { color: var(--black); font-weight: 700; }
.legal-section a { color: var(--black); text-decoration: underline; text-underline-offset: 3px; }
.legal-list {
  list-style: none;
  padding: 0;
  margin: 4px 0 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.legal-list li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: .9rem;
  line-height: 1.65;
  color: var(--text-secondary);
}
.legal-list-dot {
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--border);
  flex-shrink: 0;
  margin-top: 8px;
}
.legal-contact-cta {
  background: var(--black);
  border-radius: 4px;
  padding: 40px 44px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
}
.legal-contact-cta-text {
  font-family: var(--font-display);
  font-size: 1.2rem;
  color: #fff;
  line-height: 1.3;
}
.legal-contact-cta-sub {
  font-size: .85rem;
  color: rgba(255,255,255,.4);
  margin-top: 6px;
}

@media (max-width: 1024px) {
  .legal-hero-inner { grid-template-columns: 1fr; gap: 32px; padding: 0 24px; }
  .legal-hero-toc { display: none; }
  .legal-body-inner { grid-template-columns: 1fr; padding: 0 24px; }
  .legal-sticky-nav { display: none; }
}
@media (max-width: 600px) {
  .legal-section { padding: 28px 24px; }
  .legal-contact-cta { flex-direction: column; align-items: flex-start; padding: 28px 24px; }
}
</style>

<!-- Hero -->
<section class="legal-hero">
  <div class="legal-hero-inner">
    <div>
      <div class="legal-eyebrow"><span>Legal</span></div>
      <h1 class="legal-hero-title">Terms &amp; Conditions</h1>
      <p class="legal-hero-meta">Last updated May 2026 &nbsp;·&nbsp; Effective immediately</p>
    </div>
    <div class="legal-hero-toc">
      <div class="legal-toc-title">In this document</div>
      <a href="#t-acceptance" class="legal-toc-link">Acceptance of terms</a>
      <a href="#t-account" class="legal-toc-link">Account registration</a>
      <a href="#t-products" class="legal-toc-link">Products &amp; pricing</a>
      <a href="#t-orders" class="legal-toc-link">Orders &amp; payment</a>
      <a href="#t-shipping" class="legal-toc-link">Shipping &amp; delivery</a>
      <a href="#t-returns" class="legal-toc-link">Returns &amp; refunds</a>
      <a href="#t-loyalty" class="legal-toc-link">Loyalty programme</a>
      <a href="#t-ugc" class="legal-toc-link">User content</a>
      <a href="#t-ip" class="legal-toc-link">Intellectual property</a>
      <a href="#t-liability" class="legal-toc-link">Limitation of liability</a>
      <a href="#t-governing" class="legal-toc-link">Governing law</a>
    </div>
  </div>
</section>

<!-- Body -->
<section class="legal-body">
  <div class="legal-body-inner">

    <nav class="legal-sticky-nav">
      <div class="legal-sticky-title">Sections</div>
      <a href="#t-acceptance" class="legal-sticky-link">Acceptance of terms</a>
      <a href="#t-account" class="legal-sticky-link">Account registration</a>
      <a href="#t-products" class="legal-sticky-link">Products &amp; pricing</a>
      <a href="#t-orders" class="legal-sticky-link">Orders &amp; payment</a>
      <a href="#t-shipping" class="legal-sticky-link">Shipping &amp; delivery</a>
      <a href="#t-returns" class="legal-sticky-link">Returns &amp; refunds</a>
      <a href="#t-loyalty" class="legal-sticky-link">Loyalty programme</a>
      <a href="#t-ugc" class="legal-sticky-link">User content</a>
      <a href="#t-ip" class="legal-sticky-link">Intellectual property</a>
      <a href="#t-liability" class="legal-sticky-link">Limitation of liability</a>
      <a href="#t-governing" class="legal-sticky-link">Governing law</a>
    </nav>

    <div>
      <div class="legal-summary">
        <div class="legal-summary-label">Key points</div>
        <p class="legal-summary-text">By using Kominhoo, you agree to these terms. They cover your account, how we handle orders and payments, our returns policy, the Koins loyalty programme, and your rights as a customer in Nigeria. We've written them plainly — the full detail follows below.</p>
      </div>

      <div class="legal-section" id="t-acceptance">
        <div class="legal-section-header">
          <span class="legal-section-num">01</span>
          <div class="legal-section-title">Acceptance of terms</div>
        </div>
        <p>These Terms &amp; Conditions ("Terms") govern your use of the Kominhoo Beauty website (kominhoo.ng) and any related services (collectively, the "Platform"). By accessing the Platform or placing an order, you agree to these Terms and our <a href="{{ route('privacy.policy') }}">Privacy Policy</a>.</p>
        <p>Kominhoo Beauty Nigeria ("we," "us," "our") reserves the right to update these Terms at any time. Material changes will be communicated via email or an in-app notice before they take effect.</p>
      </div>

      <div class="legal-section" id="t-account">
        <div class="legal-section-header">
          <span class="legal-section-num">02</span>
          <div class="legal-section-title">Account registration</div>
        </div>
        <p>To place orders and access loyalty features, you must create an account. You agree to:</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Provide accurate and current registration information</li>
          <li><span class="legal-list-dot"></span>Maintain the security of your password and account</li>
          <li><span class="legal-list-dot"></span>Notify us immediately of any unauthorised access to your account</li>
          <li><span class="legal-list-dot"></span>Be at least 16 years old</li>
          <li><span class="legal-list-dot"></span>Take responsibility for all activity under your account</li>
        </ul>
        <p>We reserve the right to suspend or terminate accounts that violate these Terms, engage in fraudulent activity, or abuse our systems.</p>
      </div>

      <div class="legal-section" id="t-products">
        <div class="legal-section-header">
          <span class="legal-section-num">03</span>
          <div class="legal-section-title">Products &amp; pricing</div>
        </div>
        <p>All products listed on Kominhoo are offered subject to availability. We reserve the right to modify descriptions, images, or prices without prior notice; discontinue any product at any time; and limit quantities available per customer.</p>
        <p>Prices are displayed in Nigerian Naira (₦) and include VAT where applicable. If a pricing error occurs, we will notify you before processing your order. Product images are representative — actual colours may vary slightly due to screen calibration.</p>
      </div>

      <div class="legal-section" id="t-orders">
        <div class="legal-section-header">
          <span class="legal-section-num">04</span>
          <div class="legal-section-title">Orders &amp; payment</div>
        </div>
        <p>Placing an order constitutes an offer to purchase. An order is confirmed only when you receive a confirmation email from us. We accept payment via:</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Debit and credit cards (Visa, Mastercard) via Paystack</li>
          <li><span class="legal-list-dot"></span>Bank transfer</li>
          <li><span class="legal-list-dot"></span>USSD payment</li>
          <li><span class="legal-list-dot"></span>Kominhoo Wallet balance</li>
          <li><span class="legal-list-dot"></span>Gift Cards and approved discount codes</li>
        </ul>
        <p>We reserve the right to cancel orders suspected of fraud or where payment cannot be verified. Full refunds are issued for cancelled orders.</p>
      </div>

      <div class="legal-section" id="t-shipping">
        <div class="legal-section-header">
          <span class="legal-section-num">05</span>
          <div class="legal-section-title">Shipping &amp; delivery</div>
        </div>
        <p>Delivery timelines begin from order confirmation. We are not liable for delays caused by factors outside our control, including courier delays, public holidays, or extreme weather. Risk of loss passes to you upon delivery to the provided address.</p>
        <p>Please ensure your delivery details are accurate — we are not responsible for failed deliveries due to incorrect information. Full details are in our <a href="{{ route('shipping.policy') }}">Shipping Policy</a>.</p>
      </div>

      <div class="legal-section" id="t-returns">
        <div class="legal-section-header">
          <span class="legal-section-num">06</span>
          <div class="legal-section-title">Returns &amp; refunds</div>
        </div>
        <p>We accept returns within 7 days of delivery for unopened, unused products in original packaging. Exceptions apply for damaged, defective, counterfeit, or incorrect items — these are eligible for full replacement or refund regardless of condition.</p>
        <p>Full details are in our <a href="{{ route('returns.policy') }}">Returns &amp; Exchanges Policy</a>. To initiate a return, contact us via our <a href="{{ route('contact') }}">Contact page</a>.</p>
      </div>

      <div class="legal-section" id="t-loyalty">
        <div class="legal-section-header">
          <span class="legal-section-num">07</span>
          <div class="legal-section-title">Loyalty programme</div>
        </div>
        <p>The Kominhoo Rewards (Koins) programme is subject to additional rules published on the <a href="{{ route('loyalty-program') }}">Loyalty Programme page</a>.</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Koins have no cash value and cannot be transferred between accounts</li>
          <li><span class="legal-list-dot"></span>Koins expire after 12 months of account inactivity</li>
          <li><span class="legal-list-dot"></span>We may modify or discontinue the programme with 30 days' notice</li>
          <li><span class="legal-list-dot"></span>Fraudulently obtained Koins will be forfeited and the account suspended</li>
          <li><span class="legal-list-dot"></span>Tier status is reassessed annually based on cumulative Koins earned</li>
        </ul>
      </div>

      <div class="legal-section" id="t-ugc">
        <div class="legal-section-header">
          <span class="legal-section-num">08</span>
          <div class="legal-section-title">User-generated content</div>
        </div>
        <p>By submitting reviews, community posts, or other content to Kominhoo, you grant us a non-exclusive, royalty-free, worldwide licence to display, reproduce, and distribute that content on our Platform and marketing channels. You agree not to submit content that is defamatory, misleading, violates third-party rights, or promotes competing products.</p>
        <p>We reserve the right to remove any content that violates these guidelines without notice.</p>
      </div>

      <div class="legal-section" id="t-ip">
        <div class="legal-section-header">
          <span class="legal-section-num">09</span>
          <div class="legal-section-title">Intellectual property</div>
        </div>
        <p>All content on the Kominhoo Platform — text, graphics, logos, product images, UI design, and software — is the property of Kominhoo Beauty Nigeria or our licensors and is protected by Nigerian and international copyright and trademark laws. You may not reproduce, distribute, or create derivative works from our content without prior written permission.</p>
      </div>

      <div class="legal-section" id="t-liability">
        <div class="legal-section-header">
          <span class="legal-section-num">10</span>
          <div class="legal-section-title">Limitation of liability</div>
        </div>
        <p>To the fullest extent permitted by Nigerian law, Kominhoo Beauty Nigeria shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the Platform or products. Our total liability for any claim shall not exceed the amount you paid for the relevant product(s).</p>
        <p>Product descriptions are for informational purposes and do not constitute medical advice. We recommend a patch test before using any new skincare product. Consult a dermatologist for specific skin conditions.</p>
      </div>

      <div class="legal-section" id="t-governing">
        <div class="legal-section-header">
          <span class="legal-section-num">11</span>
          <div class="legal-section-title">Governing law</div>
        </div>
        <p>These Terms are governed by the laws of the Federal Republic of Nigeria. Any disputes are subject to the exclusive jurisdiction of the courts of Lagos State. We encourage direct contact first — most issues are resolved quickly through conversation.</p>
      </div>

      <div class="legal-contact-cta">
        <div>
          <div class="legal-contact-cta-text">Questions about these terms?</div>
          <div class="legal-contact-cta-sub">Our team is happy to clarify anything in plain language.</div>
        </div>
        <a href="{{ route('contact') }}" class="btn btn-primary">Get in Touch</a>
      </div>
    </div>

  </div>
</section>
@endsection
