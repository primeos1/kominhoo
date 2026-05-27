@extends('layouts.app')
@section('title', 'Contact Us — Kominhoo Beauty')

@section('content')
<style>
/* ── Contact Hero ─────────────────────────────────────── */
.ct-hero {
  background: var(--black);
  padding: 110px 0 80px;
  position: relative;
  overflow: hidden;
}
.ct-hero::before {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(212,217,148,.2), transparent);
}
.ct-hero-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: end;
}
.ct-eyebrow {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 28px;
}
.ct-eyebrow::before {
  content: '';
  width: 32px; height: 1px;
  background: var(--lime);
}
.ct-eyebrow span {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--lime);
}
.ct-hero-title {
  font-family: var(--font-display);
  font-size: clamp(2.6rem, 5vw, 4rem);
  line-height: 1.06;
  color: #fff;
  margin-bottom: 22px;
}
.ct-hero-title em { font-style: italic; color: var(--lime); }
.ct-hero-desc {
  font-size: 1rem;
  line-height: 1.78;
  color: rgba(255,255,255,.48);
}
.ct-response-chips {
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-self: center;
}
.ct-chip {
  display: flex;
  align-items: center;
  gap: 16px;
  background: rgba(255,255,255,.04);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 4px;
  padding: 18px 22px;
  transition: background var(--t-base);
}
.ct-chip:hover { background: rgba(255,255,255,.07); }
.ct-chip-label {
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: rgba(255,255,255,.3);
  min-width: 90px;
}
.ct-chip-value {
  font-size: .92rem;
  color: rgba(255,255,255,.8);
  font-weight: 500;
}
.ct-chip-badge {
  margin-left: auto;
  background: rgba(212,217,148,.15);
  border: 1px solid rgba(212,217,148,.2);
  color: var(--lime);
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 4px 10px;
  border-radius: 999px;
  white-space: nowrap;
}

/* ── Main ─────────────────────────────────────────────── */
.ct-main {
  background: var(--cream);
  padding: 100px 0;
}
.ct-main-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 48px;
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 60px;
  align-items: start;
}

/* Form */
.ct-form-wrap {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 4px;
  overflow: hidden;
}
.ct-form-header {
  padding: 36px 40px 28px;
  border-bottom: 1px solid var(--border);
}
.ct-form-title {
  font-family: var(--font-display);
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--black);
  margin-bottom: 6px;
}
.ct-form-sub {
  font-size: .88rem;
  color: var(--text-muted);
}
.ct-form-body {
  padding: 36px 40px;
}
.ct-field {
  margin-bottom: 22px;
}
.ct-label {
  display: block;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--black);
  margin-bottom: 8px;
}
.ct-input,
.ct-select,
.ct-textarea {
  width: 100%;
  padding: 13px 16px;
  border: 1.5px solid var(--border);
  border-radius: 4px;
  font-size: .93rem;
  font-family: inherit;
  color: var(--black);
  background: #fff;
  transition: border-color var(--t-fast), box-shadow var(--t-fast);
  outline: none;
  box-sizing: border-box;
  -webkit-appearance: none;
  appearance: none;
}
.ct-input:focus,
.ct-select:focus,
.ct-textarea:focus {
  border-color: var(--black);
  box-shadow: 0 0 0 3px rgba(28,20,22,.06);
}
.ct-textarea {
  resize: vertical;
  min-height: 140px;
  line-height: 1.6;
}
.ct-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
.ct-submit {
  width: 100%;
  padding: 15px;
  background: var(--black);
  color: #fff;
  font-size: .9rem;
  font-weight: 700;
  letter-spacing: .04em;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background var(--t-base), transform var(--t-fast);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-family: inherit;
  margin-top: 4px;
}
.ct-submit:hover { background: var(--dark); transform: translateY(-1px); }
.ct-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.ct-success {
  display: none;
  text-align: center;
  padding: 48px 40px;
}
.ct-success-line {
  width: 48px;
  height: 2px;
  background: var(--lime);
  margin: 0 auto 24px;
}
.ct-success-title {
  font-family: var(--font-display);
  font-size: 1.5rem;
  color: var(--black);
  margin-bottom: 12px;
}
.ct-success-desc {
  font-size: .9rem;
  line-height: 1.72;
  color: var(--text-secondary);
}

/* Sidebar */
.ct-sidebar {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.ct-info-block {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 4px;
  overflow: hidden;
}
.ct-info-block-header {
  padding: 18px 24px;
  border-bottom: 1px solid var(--border);
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--text-muted);
}
.ct-info-row {
  display: grid;
  grid-template-columns: 80px 1fr;
  gap: 12px;
  padding: 16px 24px;
  border-bottom: 1px solid var(--border);
  align-items: start;
}
.ct-info-row:last-child { border-bottom: none; }
.ct-info-key {
  font-size: .72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--text-muted);
  padding-top: 2px;
}
.ct-info-val {
  font-size: .88rem;
  color: var(--black);
  font-weight: 500;
  line-height: 1.5;
}
.ct-info-note {
  font-size: .78rem;
  color: var(--text-muted);
  margin-top: 3px;
}
.ct-dark-block {
  background: var(--black);
  border-radius: 4px;
  padding: 28px 24px;
}
.ct-dark-block-title {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: rgba(255,255,255,.3);
  margin-bottom: 16px;
}
.ct-quick-link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid rgba(255,255,255,.06);
  color: rgba(255,255,255,.55);
  font-size: .87rem;
  text-decoration: none;
  transition: color var(--t-fast);
}
.ct-quick-link:last-child { border-bottom: none; }
.ct-quick-link:hover { color: var(--lime); }
.ct-quick-link svg { opacity: .4; transition: opacity var(--t-fast), transform var(--t-fast); }
.ct-quick-link:hover svg { opacity: 1; transform: translateX(3px); }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 1024px) {
  .ct-hero-inner { grid-template-columns: 1fr; gap: 40px; padding: 0 24px; }
  .ct-main-inner { grid-template-columns: 1fr; padding: 0 24px; }
  .ct-sidebar { order: -1; }
}
@media (max-width: 600px) {
  .ct-hero { padding: 80px 0 60px; }
  .ct-row { grid-template-columns: 1fr; }
  .ct-form-header { padding: 28px 24px 20px; }
  .ct-form-body { padding: 24px; }
}
</style>

<!-- Hero -->
<section class="ct-hero">
  <div class="ct-hero-inner">
    <div>
      <div class="ct-eyebrow"><span>Get in Touch</span></div>
      <h1 class="ct-hero-title">We're here<br>to <em>help you.</em></h1>
      <p class="ct-hero-desc">Questions about your order, skincare advice, or a return? Our team of skincare advisors typically replies within 4 hours on weekdays.</p>
    </div>
    <div class="ct-response-chips">
      <div class="ct-chip">
        <span class="ct-chip-label">WhatsApp</span>
        <span class="ct-chip-value">+234 800 KOMINHOO</span>
        <span class="ct-chip-badge">Mon–Sat</span>
      </div>
      <div class="ct-chip">
        <span class="ct-chip-label">Email</span>
        <span class="ct-chip-value">hello@kominhoo.ng</span>
        <span class="ct-chip-badge">4h reply</span>
      </div>
      <div class="ct-chip">
        <span class="ct-chip-label">Instagram</span>
        <span class="ct-chip-value">@kominhoobeauty</span>
        <span class="ct-chip-badge">DM open</span>
      </div>
    </div>
  </div>
</section>

<!-- Main -->
<section class="ct-main">
  <div class="ct-main-inner">
    <!-- Form -->
    <div class="ct-form-wrap">
      <div id="ct-form-area">
        <div class="ct-form-header">
          <div class="ct-form-title">Send us a message</div>
          <div class="ct-form-sub">We'll respond within 4 hours during business hours, Mon–Sat.</div>
        </div>
        <div class="ct-form-body">
          <form id="ct-form" onsubmit="handleCtSubmit(event)">
            @csrf
            <div class="ct-row">
              <div class="ct-field">
                <label class="ct-label">First name</label>
                <input type="text" class="ct-input" placeholder="Adaeze" required>
              </div>
              <div class="ct-field">
                <label class="ct-label">Last name</label>
                <input type="text" class="ct-input" placeholder="Obi" required>
              </div>
            </div>
            <div class="ct-field">
              <label class="ct-label">Email address</label>
              <input type="email" class="ct-input" placeholder="adaeze@email.com" required>
            </div>
            <div class="ct-field">
              <label class="ct-label">Subject</label>
              <select class="ct-select" required>
                <option value="" disabled selected>Select a topic</option>
                <option value="order">Order or delivery issue</option>
                <option value="product">Product question</option>
                <option value="return">Return or refund</option>
                <option value="skincare">Skincare advice</option>
                <option value="account">Account or membership</option>
                <option value="wholesale">Wholesale enquiry</option>
                <option value="press">Press or partnerships</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="ct-field">
              <label class="ct-label">Order number <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--text-muted)">(optional)</span></label>
              <input type="text" class="ct-input" placeholder="KMH-12345">
            </div>
            <div class="ct-field">
              <label class="ct-label">Message</label>
              <textarea class="ct-textarea" placeholder="Tell us how we can help you…" required></textarea>
            </div>
            <button type="submit" class="ct-submit" id="ct-submit-btn">
              Send Message
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
          </form>
        </div>
      </div>
      <div class="ct-success" id="ct-success-area">
        <div class="ct-success-line"></div>
        <div class="ct-success-title">Message sent.</div>
        <p class="ct-success-desc">Thank you for reaching out. Our team will reply to your email within 4 hours on weekdays. In the meantime, check our FAQ for instant answers.</p>
        <a href="{{ route('faq') }}" class="btn btn-dark btn-lg" style="margin-top:24px">Browse FAQ</a>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="ct-sidebar">
      <div class="ct-info-block">
        <div class="ct-info-block-header">Contact Details</div>
        <div class="ct-info-row">
          <span class="ct-info-key">WhatsApp</span>
          <div>
            <div class="ct-info-val">+234 800 KOMINHOO</div>
            <div class="ct-info-note">Mon–Sat, 9am–6pm WAT</div>
          </div>
        </div>
        <div class="ct-info-row">
          <span class="ct-info-key">Email</span>
          <div>
            <div class="ct-info-val">hello@kominhoo.ng</div>
            <div class="ct-info-note">Response within 4 hours weekdays</div>
          </div>
        </div>
        <div class="ct-info-row">
          <span class="ct-info-key">Office</span>
          <div>
            <div class="ct-info-val">Lagos Island, Lagos</div>
            <div class="ct-info-note">By appointment only</div>
          </div>
        </div>
      </div>

      <div class="ct-info-block">
        <div class="ct-info-block-header">Business Hours</div>
        <div class="ct-info-row">
          <span class="ct-info-key">Weekdays</span>
          <div class="ct-info-val">9:00am – 6:00pm WAT</div>
        </div>
        <div class="ct-info-row">
          <span class="ct-info-key">Saturday</span>
          <div class="ct-info-val">10:00am – 3:00pm WAT</div>
        </div>
        <div class="ct-info-row">
          <span class="ct-info-key">Sunday</span>
          <div>
            <div class="ct-info-val">Closed</div>
            <div class="ct-info-note">Messages answered next business day</div>
          </div>
        </div>
      </div>

      <div class="ct-dark-block">
        <div class="ct-dark-block-title">Quick Help Links</div>
        <a href="{{ route('faq') }}" class="ct-quick-link">
          Frequently Asked Questions
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('shipping.policy') }}" class="ct-quick-link">
          Shipping Policy
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('returns.policy') }}" class="ct-quick-link">
          Returns &amp; Exchanges
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('our-promise') }}" class="ct-quick-link">
          Our Promise
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('quiz') }}" class="ct-quick-link">
          Take the Skin Quiz
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<script>
function handleCtSubmit(e) {
  e.preventDefault();
  const btn = document.getElementById('ct-submit-btn');
  btn.textContent = 'Sending…';
  btn.disabled = true;
  setTimeout(() => {
    document.getElementById('ct-form-area').style.display = 'none';
    document.getElementById('ct-success-area').style.display = 'block';
  }, 900);
}
</script>
@endsection
