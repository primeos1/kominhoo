@extends('layouts.app')
@section('title', 'Privacy Policy — Kominhoo Beauty')

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
      <h1 class="legal-hero-title">Privacy Policy</h1>
      <p class="legal-hero-meta">Last updated May 2026 &nbsp;·&nbsp; Effective immediately</p>
    </div>
    <div class="legal-hero-toc">
      <div class="legal-toc-title">In this document</div>
      <a href="#s-collect" class="legal-toc-link">Information we collect</a>
      <a href="#s-use" class="legal-toc-link">How we use it</a>
      <a href="#s-share" class="legal-toc-link">Sharing your data</a>
      <a href="#s-cookies" class="legal-toc-link">Cookies</a>
      <a href="#s-security" class="legal-toc-link">Security</a>
      <a href="#s-rights" class="legal-toc-link">Your rights</a>
      <a href="#s-retention" class="legal-toc-link">Retention</a>
      <a href="#s-children" class="legal-toc-link">Children</a>
      <a href="#s-changes" class="legal-toc-link">Policy changes</a>
    </div>
  </div>
</section>

<!-- Body -->
<section class="legal-body">
  <div class="legal-body-inner">

    <!-- Sticky nav (desktop) -->
    <nav class="legal-sticky-nav">
      <div class="legal-sticky-title">Sections</div>
      <a href="#s-collect" class="legal-sticky-link">Information we collect</a>
      <a href="#s-use" class="legal-sticky-link">How we use it</a>
      <a href="#s-share" class="legal-sticky-link">Sharing your data</a>
      <a href="#s-cookies" class="legal-sticky-link">Cookies</a>
      <a href="#s-security" class="legal-sticky-link">Security</a>
      <a href="#s-rights" class="legal-sticky-link">Your rights</a>
      <a href="#s-retention" class="legal-sticky-link">Retention</a>
      <a href="#s-children" class="legal-sticky-link">Children</a>
      <a href="#s-changes" class="legal-sticky-link">Policy changes</a>
    </nav>

    <div>
      <div class="legal-summary">
        <div class="legal-summary-label">Plain-language summary</div>
        <p class="legal-summary-text">We collect only what we need to run Kominhoo and improve your experience. We don't sell your personal data to third parties. You can access, correct, or delete your data by contacting us at any time.</p>
      </div>

      <div class="legal-section" id="s-collect">
        <div class="legal-section-header">
          <span class="legal-section-num">01</span>
          <div class="legal-section-title">Information we collect</div>
        </div>
        <p>We collect information you provide directly, information generated when you use our services, and limited data from third parties.</p>
        <p><strong>Information you provide:</strong></p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Account details: name, email address, phone number, password</li>
          <li><span class="legal-list-dot"></span>Delivery information: shipping address, state, postcode</li>
          <li><span class="legal-list-dot"></span>Payment information: processed securely by Paystack — we never store card numbers</li>
          <li><span class="legal-list-dot"></span>Skin Quiz responses: skin type, concerns, goals, lifestyle inputs</li>
          <li><span class="legal-list-dot"></span>Communications: messages sent via contact forms, email, or WhatsApp</li>
          <li><span class="legal-list-dot"></span>Reviews and community posts you submit on our platform</li>
        </ul>
        <p><strong>Collected automatically:</strong></p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Device, browser type, operating system, IP address</li>
          <li><span class="legal-list-dot"></span>Pages viewed, time on site, links clicked, cart activity</li>
          <li><span class="legal-list-dot"></span>Referral source (how you arrived at our site)</li>
          <li><span class="legal-list-dot"></span>Cookie identifiers and session tokens</li>
        </ul>
      </div>

      <div class="legal-section" id="s-use">
        <div class="legal-section-header">
          <span class="legal-section-num">02</span>
          <div class="legal-section-title">How we use your information</div>
        </div>
        <p>We use the information we collect to:</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Process and fulfil your orders, including delivery notifications</li>
          <li><span class="legal-list-dot"></span>Personalise your Skin Quiz results and product recommendations</li>
          <li><span class="legal-list-dot"></span>Operate your loyalty account and track Koins earnings</li>
          <li><span class="legal-list-dot"></span>Send order confirmations, receipts, and shipping updates</li>
          <li><span class="legal-list-dot"></span>Send marketing emails and promotions — only with your opt-in consent</li>
          <li><span class="legal-list-dot"></span>Respond to your customer service enquiries</li>
          <li><span class="legal-list-dot"></span>Improve our platform, detect fraud, and maintain security</li>
          <li><span class="legal-list-dot"></span>Comply with legal obligations under Nigerian law (NDPR)</li>
        </ul>
        <p>We will never use your Skin Quiz data to target you with advertisements outside of Kominhoo.</p>
      </div>

      <div class="legal-section" id="s-share">
        <div class="legal-section-header">
          <span class="legal-section-num">03</span>
          <div class="legal-section-title">Sharing your information</div>
        </div>
        <p>We do not sell, rent, or trade your personal information. We share data only in these limited circumstances:</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span><strong>Delivery partners:</strong> your name, address, and phone number are shared with our logistics partners to fulfil your order</li>
          <li><span class="legal-list-dot"></span><strong>Payment processors:</strong> Paystack receives your payment details; their privacy policy governs their handling</li>
          <li><span class="legal-list-dot"></span><strong>Service providers:</strong> email, analytics, and hosting providers who are contractually bound to protect your data</li>
          <li><span class="legal-list-dot"></span><strong>Legal compliance:</strong> if required by Nigerian law, a court order, or a regulatory authority</li>
          <li><span class="legal-list-dot"></span><strong>Business transfers:</strong> in the event of a merger or acquisition, with prior notice to you</li>
        </ul>
      </div>

      <div class="legal-section" id="s-cookies">
        <div class="legal-section-header">
          <span class="legal-section-num">04</span>
          <div class="legal-section-title">Cookies &amp; tracking</div>
        </div>
        <p>We use cookies and similar technologies to keep you logged in, remember your cart, and understand how you use our site.</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span><strong>Essential:</strong> required for login, checkout, and security — cannot be disabled</li>
          <li><span class="legal-list-dot"></span><strong>Functional:</strong> remember your preferences such as notification settings</li>
          <li><span class="legal-list-dot"></span><strong>Analytics:</strong> help us understand site usage to improve the experience</li>
          <li><span class="legal-list-dot"></span><strong>Marketing:</strong> used only if you consent, to show relevant promotions</li>
        </ul>
        <p>You can manage cookies in your browser settings at any time. Disabling essential cookies may affect core site functionality.</p>
      </div>

      <div class="legal-section" id="s-security">
        <div class="legal-section-header">
          <span class="legal-section-num">05</span>
          <div class="legal-section-title">Data security</div>
        </div>
        <p>We take reasonable technical and organisational measures to protect your information against unauthorised access, loss, or alteration:</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>HTTPS encryption on all data transmission</li>
          <li><span class="legal-list-dot"></span>Hashed and salted password storage</li>
          <li><span class="legal-list-dot"></span>Restricted internal access on a need-to-know basis</li>
          <li><span class="legal-list-dot"></span>Regular security audits and monitoring</li>
        </ul>
        <p>No transmission over the internet is 100% secure. If you believe your account has been compromised, contact us immediately at <strong>security@kominhoo.ng</strong>.</p>
      </div>

      <div class="legal-section" id="s-rights">
        <div class="legal-section-header">
          <span class="legal-section-num">06</span>
          <div class="legal-section-title">Your rights</div>
        </div>
        <p>Under Nigeria's data protection regulations (NDPR), you have the right to:</p>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Access the personal data we hold about you</li>
          <li><span class="legal-list-dot"></span>Correct inaccurate or incomplete data</li>
          <li><span class="legal-list-dot"></span>Request deletion of your account and associated data</li>
          <li><span class="legal-list-dot"></span>Withdraw consent for marketing communications at any time</li>
          <li><span class="legal-list-dot"></span>Object to certain types of data processing</li>
          <li><span class="legal-list-dot"></span>Receive a portable copy of your data</li>
        </ul>
        <p>To exercise any of these rights, email us at <strong>privacy@kominhoo.ng</strong> or use the account deletion option in your dashboard under Settings. We respond within 14 business days.</p>
      </div>

      <div class="legal-section" id="s-retention">
        <div class="legal-section-header">
          <span class="legal-section-num">07</span>
          <div class="legal-section-title">Data retention</div>
        </div>
        <ul class="legal-list">
          <li><span class="legal-list-dot"></span>Order data: retained for 7 years for tax and accounting compliance</li>
          <li><span class="legal-list-dot"></span>Account profile: deleted within 30 days of a verified deletion request</li>
          <li><span class="legal-list-dot"></span>Marketing consent records: retained for 3 years to demonstrate compliance</li>
          <li><span class="legal-list-dot"></span>Anonymised analytics: may be retained indefinitely</li>
        </ul>
      </div>

      <div class="legal-section" id="s-children">
        <div class="legal-section-header">
          <span class="legal-section-num">08</span>
          <div class="legal-section-title">Children's privacy</div>
        </div>
        <p>Kominhoo is not directed at children under 16. We do not knowingly collect personal information from anyone under 16. If we learn we have collected such information, we will delete it promptly. Contact <strong>privacy@kominhoo.ng</strong> if you believe a child has created an account.</p>
      </div>

      <div class="legal-section" id="s-changes">
        <div class="legal-section-header">
          <span class="legal-section-num">09</span>
          <div class="legal-section-title">Changes to this policy</div>
        </div>
        <p>We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. When we do, we will update the date at the top and, where changes are material, notify registered users by email. Continued use of Kominhoo after changes are posted constitutes acceptance of the updated policy.</p>
      </div>

      <div class="legal-contact-cta">
        <div>
          <div class="legal-contact-cta-text">Questions about your privacy?</div>
          <div class="legal-contact-cta-sub">Our team responds to all privacy enquiries within 14 business days.</div>
        </div>
        <a href="{{ route('contact') }}" class="btn btn-primary">Contact Our Team</a>
      </div>
    </div>

  </div>
</section>
@endsection
