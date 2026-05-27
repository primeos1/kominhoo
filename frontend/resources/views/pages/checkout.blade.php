@extends('layouts.app')
@section('title', 'Checkout — Kominhoo Beauty')

@section('head')
<style>
@media (max-width: 900px) {
  #checkout-body { grid-template-columns: 1fr !important; gap: 24px !important; }
  #checkout-body > div:last-child { order: -1; }
}
@media (max-width: 600px) {
  #checkout-body { padding: 0 !important; }
  [style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
  [style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
  [style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr !important; }
  [style*="grid-template-columns: 1fr 1fr 1fr"] { grid-template-columns: 1fr !important; }
  .section { padding: 24px 0 !important; }
  /* Checkout cards compact padding on mobile */
  [style*="padding:32px"] { padding: 20px !important; }
  [style*="padding: 32px"] { padding: 20px !important; }
}
@media (max-width: 400px) {
  /* Very small phones: stack payment option label and recommended badge */
  [style*="margin-left:auto"] { margin-left: 0 !important; margin-top: 4px; }
}
</style>
@endsection

@section('content')

{{-- Bank Transfer Details Modal --}}
<div id="bankModalOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:none;align-items:center;justify-content:center;padding:20px">
  <div style="background:#fff;border-radius:20px;padding:36px;max-width:440px;width:100%;text-align:center">
    <div style="font-size:2.5rem;margin-bottom:12px">🏦</div>
    <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:8px">Complete Your Payment</h2>
    <p style="font-size:.88rem;color:var(--text-muted);margin-bottom:24px">Transfer the exact amount to the account below, then send proof of payment to <strong>orders@kominhoo.com</strong></p>
    <div style="background:var(--cream);border-radius:12px;padding:20px;text-align:left;margin-bottom:20px;display:grid;gap:10px">
      <div style="display:flex;justify-content:space-between;font-size:.9rem"><span style="color:var(--text-muted)">Bank</span><strong>Guaranty Trust Bank (GTB)</strong></div>
      <div style="display:flex;justify-content:space-between;font-size:.9rem"><span style="color:var(--text-muted)">Account Name</span><strong>Kominhoo Beauty Ltd</strong></div>
      <div style="display:flex;justify-content:space-between;font-size:.9rem"><span style="color:var(--text-muted)">Account Number</span>
        <strong style="font-family:monospace;font-size:1rem;letter-spacing:.05em" id="bankAcctNum">0123456789</strong>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.9rem"><span style="color:var(--text-muted)">Amount</span><strong id="bankAmount" style="color:var(--black)">—</strong></div>
      <div style="display:flex;justify-content:space-between;font-size:.9rem"><span style="color:var(--text-muted)">Reference</span><strong id="bankReference" style="font-family:monospace">—</strong></div>
    </div>
    <button onclick="copyBankDetails()" class="btn btn-outline" style="width:100%;margin-bottom:10px">📋 Copy Account Details</button>
    <a id="bankDashboardBtn" href="{{ route('dashboard.orders') }}?order_placed=1" class="btn btn-primary" style="width:100%;display:block;text-align:center">View My Order →</a>
  </div>
</div>

<section class="section" style="background:var(--cream);min-height:80vh">
  <div class="container" style="max-width:1060px">

    <div style="display:flex;align-items:center;gap:16px;margin-bottom:40px">
      <a href="{{ url()->previous() }}" style="color:var(--text-muted);font-size:.88rem;text-decoration:none">← Back</a>
      <h1 style="font-size:1.6rem;font-weight:700;margin:0">Checkout</h1>
    </div>

    @if($isGuest)
    <div style="background:#fef3c7;border:1.5px solid #f59e0b;border-radius:12px;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
      <div style="font-size:.9rem;font-weight:600">You're checking out as a guest. <span style="font-weight:400">Log in to save your order history and earn loyalty points.</span></div>
      <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout')) }}" class="btn btn-dark btn-sm">Log In</a>
    </div>
    @endif

    <div id="empty-cart-msg" style="display:none;text-align:center;padding:64px 0">
      <div style="font-size:3.5rem;margin-bottom:16px">🛒</div>
      <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:8px">Your cart is empty</h2>
      <p style="color:var(--text-muted);margin-bottom:28px">Add some products before checking out.</p>
      <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">Shop Now →</a>
    </div>

    <div id="checkout-body" style="display:grid;grid-template-columns:1fr 380px;gap:40px;align-items:start">

      <!-- Left: Shipping + Payment -->
      <div>

        @if($isGuest)
        <!-- Guest Email (required for Paystack) -->
        <div style="background:#fff;border-radius:var(--r-xl);padding:32px;margin-bottom:24px">
          <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:6px">Your Email</h2>
          <p style="font-size:.83rem;color:var(--text-muted);margin-bottom:16px">Needed to send your order receipt.</p>
          <input type="email" id="guest_email" class="input" placeholder="you@example.com">
          <div class="field-error" id="err_email"></div>
        </div>
        @endif

        <!-- Shipping Address -->
        <div style="background:#fff;border-radius:var(--r-xl);padding:32px;margin-bottom:24px">
          <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:24px">Shipping Address</h2>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px">Full Name <span style="color:var(--red)">*</span></label>
              <input type="text" id="shipping_name" class="input" placeholder="Your full name"
                value="{{ $user['name'] ?? '' }}">
              <div class="field-error" id="err_name"></div>
            </div>
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px">Phone Number <span style="color:var(--red)">*</span></label>
              <input type="tel" id="shipping_phone" class="input" placeholder="+234 800 000 0000"
                value="{{ $user['phone'] ?? '' }}">
              <div class="field-error" id="err_phone"></div>
            </div>
          </div>
          <div style="margin-bottom:16px">
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px">Street Address <span style="color:var(--red)">*</span></label>
            <input type="text" id="shipping_street" class="input" placeholder="House number, street name"
              value="{{ $user['address_line1'] ?? '' }}">
            <div class="field-error" id="err_street"></div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px">City <span style="color:var(--red)">*</span></label>
              <input type="text" id="shipping_city" class="input" placeholder="Lagos"
                value="{{ $user['city'] ?? '' }}">
              <div class="field-error" id="err_city"></div>
            </div>
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px">State <span style="color:var(--red)">*</span></label>
              <input type="text" id="shipping_state" class="input" placeholder="Lagos State"
                value="{{ $user['state'] ?? '' }}">
              <div class="field-error" id="err_state"></div>
            </div>
            <div>
              <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px">Country</label>
              <input type="text" id="shipping_country" class="input" value="Nigeria" readonly style="background:var(--cream)">
            </div>
          </div>
        </div>

        <!-- Payment Method -->
        <div style="background:#fff;border-radius:var(--r-xl);padding:32px;margin-bottom:24px">
          <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:24px">Payment Method</h2>
          <div style="display:grid;gap:10px">

            {{-- Paystack --}}
            <div style="display:flex;align-items:center;gap:14px;padding:18px;border:2px solid var(--gray-200);border-radius:var(--r-lg);cursor:pointer" id="pm_label_paystack" onclick="selectPayment('paystack')">
              <span id="pm_dot_paystack" style="width:20px;height:20px;border-radius:50%;border:2px solid var(--gray-300);flex-shrink:0"></span>
              <div>
                <div style="font-weight:700;font-size:.92rem">Pay with Paystack</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">Card / Bank Transfer / USSD — Secure &amp; instant</div>
              </div>
              <span style="margin-left:auto;font-size:.78rem;font-weight:700;color:var(--text-muted)">Recommended</span>
            </div>

            {{-- Bank Transfer --}}
            <div style="display:flex;align-items:center;gap:14px;padding:18px;border:2px solid var(--gray-200);border-radius:var(--r-lg);cursor:pointer" id="pm_label_bank_transfer" onclick="selectPayment('bank_transfer')">
              <span id="pm_dot_bank_transfer" style="width:20px;height:20px;border-radius:50%;border:2px solid var(--gray-300);flex-shrink:0"></span>
              <div>
                <div style="font-weight:700;font-size:.92rem">Direct Bank Transfer</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">Transfer to GTB — order held for 24 hrs</div>
              </div>
            </div>

            {{-- Pay on Delivery --}}
            <div style="display:flex;align-items:center;gap:14px;padding:18px;border:2px solid var(--gray-200);border-radius:var(--r-lg);cursor:pointer" id="pm_label_pay_on_delivery" onclick="selectPayment('pay_on_delivery')">
              <span id="pm_dot_pay_on_delivery" style="width:20px;height:20px;border-radius:50%;border:2px solid var(--gray-300);flex-shrink:0"></span>
              <div>
                <div style="font-weight:700;font-size:.92rem">Pay on Delivery</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">Cash or POS — Lagos only</div>
              </div>
            </div>

            {{-- Wallet --}}
            @if(!$isGuest)
            @php $walletActive = ($walletStatus ?? '') === 'active' && ($walletBalance ?? 0) > 0; @endphp
            <div style="display:flex;align-items:center;gap:14px;padding:18px;border:2px solid var(--gray-200);border-radius:var(--r-lg);cursor:{{ $walletActive ? 'pointer' : 'default' }};opacity:{{ $walletActive ? '1' : '.55' }}"
                 id="pm_label_wallet"
                 {{ $walletActive ? 'onclick=selectPayment(\'wallet\')' : '' }}>
              <span id="pm_dot_wallet" style="width:20px;height:20px;border-radius:50%;border:2px solid var(--gray-300);flex-shrink:0"></span>
              <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;gap:8px">
                  <span style="font-weight:700;font-size:.92rem">Pay with Wallet</span>
                  <span id="co-wallet-badge" style="font-size:.68rem;font-weight:700;background:rgba(34,197,94,.12);color:#16A34A;padding:2px 8px;border-radius:999px;display:{{ $walletActive ? 'inline' : 'none' }}">Instant</span>
                </div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">
                  Balance: <strong id="co-wallet-bal" style="color:{{ $walletActive ? '#16A34A' : 'inherit' }}">₦{{ number_format($walletBalance ?? 0, 2) }}</strong>
                  <span id="co-wallet-msg" style="display:{{ $walletActive ? 'none' : 'inline' }};color:#DC2626">
                    — {{ ($walletStatus ?? '') !== 'active' ? 'Wallet inactive' : 'Insufficient funds' }}
                  </span>
                </div>
              </div>
            </div>
            @endif

          </div>
          <div class="field-error" id="err_payment" style="margin-top:10px"></div>
          {{-- Shown when wallet selected but balance is too low --}}
          <div id="wallet-balance-warn" style="display:none;margin-top:10px;background:#fef9c3;color:#854d0e;border:1px solid #fde047;border-radius:var(--r-md);padding:10px 14px;font-size:.82rem;font-weight:600">
            💳 Your wallet balance (<span id="wallet-bal-display">₦0.00</span>) is less than the order total. Please
            <a href="{{ route('dashboard.index') }}#wallet" style="color:inherit;text-decoration:underline">fund your wallet</a>
            or choose another payment method.
          </div>
        </div>

        <!-- Order Notes -->
        <div style="background:#fff;border-radius:var(--r-xl);padding:32px">
          <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:16px">Order Notes <span style="font-weight:400;font-size:.85rem;color:var(--text-muted)">(optional)</span></h2>
          <textarea id="order_notes" class="input" rows="3" placeholder="Any special instructions for your order?"></textarea>
        </div>

      </div>

      <!-- Right: Order Summary -->
      <div style="position:sticky;top:100px">
        <div style="background:#fff;border-radius:var(--r-xl);padding:28px">
          <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:20px">Order Summary</h2>

          <div id="checkout-items" style="margin-bottom:20px;display:grid;gap:12px">
            <p style="color:var(--text-muted);font-size:.87rem;text-align:center;padding:20px 0">Loading cart...</p>
          </div>

          <!-- Coupon / Voucher Widget -->
          <div class="co-voucher-wrap">

            {{-- Input state --}}
            <div id="coupon-input-zone" class="co-voucher-ticket">
              <div class="co-voucher-left">
                <span class="co-voucher-scissors">✂</span>
                <div class="co-voucher-dashes"></div>
              </div>
              <div class="co-voucher-content">
                <div class="co-voucher-label">🎟️ Got a promo or voucher code?</div>
                <div style="display:flex;gap:8px;margin-top:10px">
                  <input type="text" id="coupon_input" class="co-code-input"
                    placeholder="Enter promo code"
                    onkeydown="if(event.key==='Enter')applyCoupon()"
                    onfocus="this.style.borderColor='var(--black)'"
                    onblur="this.style.borderColor='var(--gray-200)'">
                  <button type="button" id="coupon-btn" onclick="applyCoupon()" class="co-apply-btn">Apply</button>
                </div>
                <div id="coupon-msg" style="font-size:.8rem;margin-top:8px;display:none;font-weight:500"></div>
              </div>
            </div>

            {{-- Applied state --}}
            <div id="coupon-applied-row" class="co-voucher-applied" style="display:none">
              <div class="co-voucher-applied-glow"></div>
              <div class="co-voucher-applied-icon">🎉</div>
              <div class="co-voucher-applied-info">
                <div class="co-voucher-applied-code" id="coupon-applied-code-text"></div>
                <div id="coupon-applied-label" class="co-voucher-applied-label"></div>
              </div>
              <button type="button" onclick="removeCoupon()" class="co-voucher-remove">✕ Remove</button>
            </div>

          </div>

          <!-- Gift Card Redemption -->
          <div class="co-gc-wrap">
            <div class="co-gc-card">
              <div class="co-gc-top">
                <div class="co-gc-title">🎁 Gift Card</div>
                <a href="{{ route('gift-cards.index') }}" class="co-gc-link">Buy one →</a>
              </div>
              <div class="co-gc-sub">Enter your code to apply your balance instantly.</div>
              <div id="gc-input-row" class="co-gc-row">
                <input type="text" id="gc_input" class="co-gc-input" placeholder="GC-KMH-XXXX" autocomplete="off" inputmode="text">
                <button type="button" class="co-gc-btn" onclick="applyGiftCard()" id="gc-btn">Apply</button>
              </div>
              <div id="gc-msg" class="co-gc-msg" style="display:none"></div>
              <div id="gc-applied-row" class="co-gc-applied" style="display:none">
                <div class="co-gc-applied-glow" aria-hidden="true"></div>
                <div class="co-gc-applied-label" id="gc-applied-label"></div>
                <button type="button" onclick="removeGiftCard()" class="co-gc-remove">✕ Remove</button>
              </div>
            </div>
          </div>

          <!-- Totals -->
          <div style="display:grid;gap:8px;font-size:.9rem;margin-bottom:20px">
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--text-muted)">Subtotal</span>
              <span id="summary-subtotal">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;display:none" id="discount-row">
              <span style="color:var(--text-muted)">Discount</span>
              <span id="summary-discount" style="color:#16a34a">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;display:none" id="gc-discount-row">
              <span style="color:var(--text-muted)">Gift Card</span>
              <span id="summary-gc-discount" style="color:#6d28d9">—</span>
            </div>
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--text-muted)">Shipping</span>
              <span id="summary-shipping">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:1.05rem;font-weight:700;border-top:1px solid var(--gray-200);padding-top:12px;margin-top:4px">
              <span>Total</span>
              <span id="summary-total">—</span>
            </div>
          </div>

          <div id="order-error" style="display:none;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:12px;font-size:.85rem;color:#dc2626;margin-bottom:16px"></div>

          <button type="button" id="place-order-btn" class="btn btn-primary btn-lg" style="width:100%" onclick="placeOrder()">
            Place Order →
          </button>

          <div style="display:flex;justify-content:center;gap:16px;font-size:.75rem;color:var(--text-muted);margin-top:16px">
            <span>🔒 Secure checkout</span>
            <span>✅ 100% Authentic</span>
          </div>
        </div>
      </div>

    </div><!-- /checkout-body -->
  </div>
</section>

<style>
.field-error { font-size:.78rem; color:var(--red,#E8382E); margin-top:5px; min-height:18px; }
.input-error  { border-color:var(--red,#E8382E) !important; }

/* ── Coupon / Voucher Ticket Widget ─────────────────────── */
/* Gift Card Apply Widget */
.co-gc-wrap { border-top: 1.5px solid var(--gray-200); padding-top: 18px; margin-bottom: 20px; }
.co-gc-card{
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #fdf6f7 0%, #f9eff0 100%);
  border: 1.5px solid rgba(137,57,65,.18);
  border-radius: 16px;
  padding: 16px 18px 14px;
  box-shadow: 0 10px 40px rgba(137,57,65,.06);
}
.co-gc-card::before{
  content:'';
  position:absolute;
  inset:-40px -60px auto auto;
  width:220px;height:220px;border-radius:50%;
  background: radial-gradient(circle, rgba(137,57,65,.1) 0%, transparent 62%);
  pointer-events:none;
}
.co-gc-card::after{
  content:'';
  position:absolute;left:0;right:0;top:0;height:1px;
  background: linear-gradient(90deg, transparent, rgba(137,57,65,.12), transparent);
  pointer-events:none;
}
.co-gc-top{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:6px}
.co-gc-title{font-size:.74rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--rose-dark,#6B2A30)}
.co-gc-link{font-size:.78rem;font-weight:700;color:var(--rose-dark,#6B2A30);text-decoration:none;opacity:.8}
.co-gc-link:hover{text-decoration:underline;opacity:1}
.co-gc-sub{font-size:.82rem;color:var(--text-muted);margin-bottom:10px}
.co-gc-row{display:flex;gap:8px;align-items:center}
.co-gc-input{
  flex:1;width:0;
  text-transform:uppercase;
  letter-spacing:.14em;
  font-weight:700;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
  font-size:.9rem;
  background:#fff;
  border:1.5px solid var(--gray-200);
  border-radius: 12px;
  padding: 10px 12px;
  outline:none;
  transition: border-color .2s, box-shadow .2s;
}
.co-gc-input:focus{border-color:var(--black);box-shadow:0 0 0 4px rgba(0,0,0,.05)}
.co-gc-btn{
  background: var(--black);
  color:#fff;
  border-radius: 12px;
  padding: 10px 14px;
  font-size:.82rem;
  font-weight:700;
  letter-spacing:.02em;
  border:1px solid rgba(0,0,0,.12);
  box-shadow: 0 12px 32px rgba(0,0,0,.12);
  transition: transform .15s, box-shadow .2s, background .2s;
}
.co-gc-btn:hover{transform:translateY(-1px);box-shadow:0 16px 40px rgba(0,0,0,.14);background:#151515}
.co-gc-btn:disabled{opacity:.55;transform:none;box-shadow:none;cursor:not-allowed}
.co-gc-msg{font-size:.82rem;margin-top:8px}
.co-gc-applied{
  position:relative;
  margin-top:10px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  border-radius: 14px;
  padding: 10px 12px;
  background: rgba(137,57,65,.1);
  border: 1px solid rgba(137,57,65,.25);
  overflow:hidden;
}
.co-gc-applied-glow{
  position:absolute;inset:-80px auto auto -80px;width:220px;height:220px;border-radius:50%;
  background: radial-gradient(circle, rgba(137,57,65,.2) 0%, transparent 62%);
  pointer-events:none;
}
.co-gc-applied-label{position:relative;z-index:1;font-size:.83rem;font-weight:700;color:var(--rose-dark,#6B2A30)}
.co-gc-remove{
  position:relative;z-index:1;
  background:none;border:none;
  cursor:pointer;
  font-size:.75rem;
  font-weight:700;
  color: rgba(10,10,10,.55);
  padding: 0;
  white-space: nowrap;
}
.co-gc-remove:hover{color:rgba(10,10,10,.85)}

.co-voucher-wrap {
  border-top: 1.5px solid var(--gray-200);
  padding-top: 20px;
  margin-bottom: 20px;
}

/* Ticket (input state) */
.co-voucher-ticket {
  display: flex;
  align-items: stretch;
  border: 2px dashed var(--gray-200);
  border-radius: 14px;
  overflow: hidden;
  background: #fafaf8;
  transition: border-color .2s;
}
.co-voucher-ticket:focus-within {
  border-color: var(--black);
  background: #fff;
}

/* Left scissors strip */
.co-voucher-left {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 14px 10px;
  background: linear-gradient(180deg, var(--lime) 0%, #a5c400 100%);
  width: 36px;
  flex-shrink: 0;
  gap: 4px;
}
.co-voucher-scissors {
  font-size: 1rem;
  color: rgba(0,0,0,.55);
  transform: rotate(90deg);
  flex-shrink: 0;
}
.co-voucher-dashes {
  flex: 1;
  border-left: 2px dashed rgba(0,0,0,.2);
  margin: 4px 0;
}

/* Content */
.co-voucher-content {
  flex: 1;
  padding: 16px 18px;
}
.co-voucher-label {
  font-size: .82rem;
  font-weight: 700;
  color: var(--text-secondary, #524F48);
  letter-spacing: .01em;
}

/* Code input */
.co-code-input {
  flex: 1;
  width: 0;
  text-transform: uppercase;
  letter-spacing: .1em;
  font-weight: 700;
  font-family: 'Courier New', monospace;
  font-size: .92rem;
  background: #fff;
  border: 1.5px solid var(--gray-200);
  border-radius: 10px;
  padding: 10px 14px;
  outline: none;
  transition: border-color .2s, box-shadow .2s;
  color: var(--black);
}
.co-code-input::placeholder { font-weight: 400; font-family: inherit; letter-spacing: .02em; color: var(--gray-400, #A09E95); }
.co-code-input:focus { border-color: var(--lime); box-shadow: 0 0 0 3px rgba(212,217,148,.2); }

/* Apply button */
.co-apply-btn {
  background: var(--rose-dark);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-size: .85rem;
  font-weight: 700;
  cursor: pointer;
  white-space: nowrap;
  flex-shrink: 0;
  transition: background .18s, transform .1s;
  letter-spacing: .02em;
}
.co-apply-btn:hover   { background: #2a2a2a; }
.co-apply-btn:active  { transform: scale(.97); }
.co-apply-btn:disabled { background: var(--gray-300); cursor: not-allowed; }

/* Applied state ticket */
.co-voucher-applied {
  display: flex;
  align-items: center;
  gap: 14px;
  background: linear-gradient(135deg, #f0fdf4 0%, #e8fdf0 100%);
  border: 1.5px solid #86efac;
  border-radius: 14px;
  padding: 16px 18px;
  position: relative;
  overflow: hidden;
}
.co-voucher-applied-glow {
  position: absolute;
  top: -30px; right: -30px;
  width: 100px; height: 100px;
  background: radial-gradient(circle, rgba(134,239,172,.35) 0%, transparent 70%);
  pointer-events: none;
}
.co-voucher-applied-icon {
  font-size: 1.6rem;
  flex-shrink: 0;
  animation: coTicketPop .4s ease;
}
@keyframes coTicketPop {
  0%   { transform: scale(0.5) rotate(-15deg); opacity: 0; }
  60%  { transform: scale(1.2) rotate(5deg); }
  100% { transform: scale(1) rotate(0); opacity: 1; }
}
.co-voucher-applied-info { flex: 1; min-width: 0; }
.co-voucher-applied-code {
  font-family: 'Courier New', monospace;
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: .1em;
  color: #15803d;
}
.co-voucher-applied-label {
  font-size: .78rem;
  color: #166534;
  margin-top: 2px;
  font-weight: 500;
}
.co-voucher-remove {
  background: none;
  border: 1.5px solid #86efac;
  border-radius: 8px;
  padding: 6px 12px;
  font-size: .75rem;
  font-weight: 700;
  color: #166534;
  cursor: pointer;
  white-space: nowrap;
  transition: background .15s;
  flex-shrink: 0;
}
.co-voucher-remove:hover { background: rgba(134,239,172,.2); }
</style>

@section('scripts')
{{-- Config passed via data attributes to keep the <script> block pure JS --}}
<div id="checkout-cfg"
  data-paystack-key="{{ $paystackKey ?? '' }}"
  data-user-email="{{ session('user.email') ?? '' }}"
  data-is-guest="{{ $isGuest ? '1' : '' }}"
  data-wallet-balance="{{ $walletBalance ?? 0 }}"
  data-wallet-status="{{ $walletStatus ?? 'inactive' }}"
  style="display:none"></div>

@if(!empty($paystackKey))
<script src="https://js.paystack.co/v1/inline.js"></script>
@endif

<script>
const _cfg           = document.getElementById('checkout-cfg').dataset;
const PAYSTACK_KEY   = _cfg.paystackKey;
const USER_EMAIL     = _cfg.userEmail;
const IS_GUEST       = !!_cfg.isGuest;
let WALLET_BALANCE = parseFloat(_cfg.walletBalance || 0);
let WALLET_STATUS  = _cfg.walletStatus || '';

// `cart` is declared in app.js — no redeclaration needed
let appliedDiscount     = 0;
let appliedFreeShipping = false;
let appliedCoupon       = '';
let selectedPayment     = '';
let appliedGcCode       = '';
let appliedGcDiscount   = 0;

function fmt(n) { return '₦' + Number(n).toLocaleString(); }

// ── Cart rendering ────────────────────────────────────
function renderCart() {
  cart = JSON.parse(localStorage.getItem('kominhoo_cart') || '[]');

  if (!cart.length) {
    document.getElementById('checkout-body').style.display = 'none';
    document.getElementById('empty-cart-msg').style.display = 'block';
    return;
  }

  const container = document.getElementById('checkout-items');
  container.innerHTML = cart.map(item => {
    const img = item.image
      ? `<img src="${item.image}" alt="${item.name}" style="width:48px;height:48px;border-radius:var(--r-md);object-fit:cover;flex-shrink:0">`
      : `<div style="width:48px;height:48px;border-radius:var(--r-md);background:var(--cream);display:grid;place-items:center;font-size:1.4rem;flex-shrink:0">🧴</div>`;
    return `
      <div style="display:flex;align-items:center;gap:12px">
        ${img}
        <div style="flex:1">
          <div style="font-weight:600;font-size:.88rem">${item.name}</div>
          <div style="font-size:.78rem;color:var(--text-muted)">Qty: ${item.qty}</div>
        </div>
        <div style="font-weight:700;font-size:.9rem">${fmt(item.price * item.qty)}</div>
      </div>`;
  }).join('');
  updateTotals();
}

function subtotal() {
  return cart.reduce((s, i) => s + i.price * i.qty, 0);
}

function updateTotals() {
  const sub      = subtotal();
  const shipping = (sub >= 50000 || appliedFreeShipping) ? 0 : 2500;
  const gcApplied = Math.min(appliedGcDiscount, Math.max(0, sub - appliedDiscount + shipping));
  const total    = Math.max(0, sub - appliedDiscount + shipping - gcApplied);

  document.getElementById('summary-subtotal').textContent = fmt(sub);
  document.getElementById('summary-shipping').textContent = shipping === 0 ? 'Free' : fmt(shipping);
  document.getElementById('summary-total').textContent    = fmt(total);

  const dRow = document.getElementById('discount-row');
  if (appliedDiscount > 0) {
    dRow.style.display = 'flex';
    document.getElementById('summary-discount').textContent = '-' + fmt(appliedDiscount);
  } else {
    dRow.style.display = 'none';
  }

  const gcRow = document.getElementById('gc-discount-row');
  if (gcApplied > 0) {
    gcRow.style.display = 'flex';
    document.getElementById('summary-gc-discount').textContent = '-' + fmt(gcApplied);
  } else {
    gcRow.style.display = 'none';
  }
}

// ── Coupon ────────────────────────────────────────────
async function applyCoupon() {
  const inp  = document.getElementById('coupon_input');
  const code = inp.value.trim().toUpperCase();
  if (!code) {
    inp.style.borderColor = 'var(--red)';
    setTimeout(() => inp.style.borderColor = '', 1200);
    return;
  }
  const btn = document.getElementById('coupon-btn');
  btn.disabled = true; btn.textContent = '…';
  const msg = document.getElementById('coupon-msg');
  msg.style.display = 'none';
  try {
    const res  = await fetch('{{ route("checkout.promo") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ code, order_total: subtotal() })
    });
    const data = await res.json();
    if (data.success) {
      appliedDiscount     = data.data.discount_amount ?? 0;
      appliedFreeShipping = data.data.free_shipping   ?? false;
      appliedCoupon       = data.data.coupon_code     ?? code;
      updateTotals();
      // Swap ticket → applied badge
      document.getElementById('coupon-input-zone').style.display = 'none';
      const codeEl  = document.getElementById('coupon-applied-code-text');
      const labelEl = document.getElementById('coupon-applied-label');
      if (codeEl)  codeEl.textContent  = appliedCoupon;
      if (labelEl) labelEl.textContent = data.message;
      const row = document.getElementById('coupon-applied-row');
      row.style.display = 'flex';
    } else {
      msg.style.cssText = 'display:block;color:var(--red);font-weight:500';
      msg.textContent   = '✕ ' + (data.message || 'Invalid coupon code.');
      inp.style.borderColor = 'var(--red)';
      setTimeout(() => inp.style.borderColor = '', 2000);
      btn.disabled = false; btn.textContent = 'Apply';
    }
  } catch {
    msg.style.cssText = 'display:block;color:var(--red);font-weight:500';
    msg.textContent   = '✕ Network error. Please try again.';
    btn.disabled = false; btn.textContent = 'Apply';
  }
}

function removeCoupon() {
  appliedDiscount     = 0;
  appliedFreeShipping = false;
  appliedCoupon       = '';
  const inp = document.getElementById('coupon_input');
  inp.value = ''; inp.disabled = false; inp.style.borderColor = '';
  const btn = document.getElementById('coupon-btn');
  btn.disabled = false; btn.textContent = 'Apply';
  document.getElementById('coupon-input-zone').style.display = '';
  document.getElementById('coupon-applied-row').style.display = 'none';
  document.getElementById('coupon-msg').style.display = 'none';
  updateTotals();
}

// ── Gift Card ─────────────────────────────────────────
async function applyGiftCard() {
  const code = document.getElementById('gc_input').value.trim().toUpperCase();
  if (!code) return;
  const btn = document.getElementById('gc-btn');
  btn.disabled = true; btn.textContent = '…';
  const msg = document.getElementById('gc-msg');
  msg.style.display = 'none';
  try {
    const res  = await fetch('{{ route("checkout.gift-card") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ code })
    });
    const data = await res.json();
    if (data.success) {
      appliedGcCode     = data.data.code;
      appliedGcDiscount = data.data.balance;
      updateTotals();
      document.getElementById('gc-input-row').style.display = 'none';
      msg.style.display = 'none';
      const row   = document.getElementById('gc-applied-row');
      const label = document.getElementById('gc-applied-label');
      label.textContent = '🎁 ' + appliedGcCode + ' — ₦' + Number(data.data.balance).toLocaleString() + ' balance applied';
      row.style.display = 'flex';
    } else {
      msg.style.cssText = 'display:block;color:var(--red)';
      msg.textContent   = data.message || 'Invalid gift card code.';
      btn.disabled = false; btn.textContent = 'Apply';
    }
  } catch {
    msg.style.cssText = 'display:block;color:var(--red)';
    msg.textContent   = 'Network error. Try again.';
    btn.disabled = false; btn.textContent = 'Apply';
  }
}

function removeGiftCard() {
  appliedGcCode     = '';
  appliedGcDiscount = 0;
  document.getElementById('gc_input').value = '';
  document.getElementById('gc-input-row').style.display = 'flex';
  document.getElementById('gc-applied-row').style.display = 'none';
  document.getElementById('gc-msg').style.display = 'none';
  const btn = document.getElementById('gc-btn');
  btn.disabled = false; btn.textContent = 'Apply';
  updateTotals();
}

// ── Payment selection ─────────────────────────────────
function selectPayment(val) {
  selectedPayment = val;
  document.querySelectorAll('[id^="pm_label_"]').forEach(l => l.style.borderColor = 'var(--gray-200)');
  document.querySelectorAll('[id^="pm_dot_"]').forEach(d => { d.style.background = ''; d.style.borderColor = 'var(--gray-300)'; });
  document.getElementById('pm_label_' + val).style.borderColor = 'var(--black)';
  const dot = document.getElementById('pm_dot_' + val);
  dot.style.background = 'var(--black)'; dot.style.borderColor = 'var(--black)';
  clearError('err_payment');
  const warn = document.getElementById('wallet-balance-warn');
  if (warn) warn.style.display = 'none';
}

// ── Validation helpers ────────────────────────────────
function showError(id, msg) {
  const el = document.getElementById(id);
  if (el) el.textContent = msg;
  const inputId = id.replace('err_', 'shipping_');
  const input = document.getElementById(inputId);
  if (input) input.classList.add('input-error');
}
function clearError(id) {
  const el = document.getElementById(id);
  if (el) el.textContent = '';
  const inputId = id.replace('err_', 'shipping_');
  const input = document.getElementById(inputId);
  if (input) input.classList.remove('input-error');
}
function clearAllErrors() {
  ['err_name','err_phone','err_street','err_city','err_state','err_payment','err_email'].forEach(clearError);
  document.getElementById('order-error').style.display = 'none';
}
function showOrderError(msg) {
  const el = document.getElementById('order-error');
  el.textContent = msg;
  el.style.display = 'block';
  el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function getPaystackEmail() {
  if (USER_EMAIL) return USER_EMAIL;
  const guestField = document.getElementById('guest_email');
  return guestField ? guestField.value.trim() : '';
}

function validateForm() {
  clearAllErrors();
  let valid = true;
  const name   = document.getElementById('shipping_name').value.trim();
  const phone  = document.getElementById('shipping_phone').value.trim();
  const street = document.getElementById('shipping_street').value.trim();
  const city   = document.getElementById('shipping_city').value.trim();
  const state  = document.getElementById('shipping_state').value.trim();

  if (!name)   { showError('err_name',   'Full name is required');       valid = false; }
  if (!phone)  { showError('err_phone',  'Phone number is required');    valid = false; }
  if (!street) { showError('err_street', 'Street address is required');  valid = false; }
  if (!city)   { showError('err_city',   'City is required');            valid = false; }
  if (!state)  { showError('err_state',  'State is required');           valid = false; }
  if (!selectedPayment) { showError('err_payment', 'Please select a payment method'); valid = false; }

  if (IS_GUEST && selectedPayment === 'paystack') {
    const email = getPaystackEmail();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      const emailEl = document.getElementById('err_email');
      if (emailEl) emailEl.textContent = 'A valid email is required for card payment.';
      const field = document.getElementById('guest_email');
      if (field) field.classList.add('input-error');
      valid = false;
    }
  }

  return valid;
}

function buildPayload(paymentReference = null) {
  const sub      = subtotal();
  const shipping = (sub >= 50000 || appliedFreeShipping) ? 0 : 2500;
  return {
    items: cart.map(i => ({ id: i.id, type: i.type || 'product', quantity: i.qty, price: i.price, name: i.name })),
    shipping_address: {
      name:    document.getElementById('shipping_name').value.trim(),
      phone:   document.getElementById('shipping_phone').value.trim(),
      street:  document.getElementById('shipping_street').value.trim(),
      city:    document.getElementById('shipping_city').value.trim(),
      state:   document.getElementById('shipping_state').value.trim(),
      country: document.getElementById('shipping_country').value,
    },
    payment_method:    selectedPayment,
    payment_reference: paymentReference,
    coupon_code:       appliedCoupon || null,
    gift_card_code:    appliedGcCode || null,
    gift_card_discount: appliedGcCode ? Math.min(appliedGcDiscount, Math.max(0, sub - appliedDiscount + shipping)) : 0,
    subtotal:          sub,
    discount:          appliedDiscount,
    free_shipping:     appliedFreeShipping,
    shipping:          shipping,
    total:             Math.max(0, sub - appliedDiscount + shipping - (appliedGcCode ? Math.min(appliedGcDiscount, Math.max(0, sub - appliedDiscount + shipping)) : 0)),
    notes:             document.getElementById('order_notes').value,
  };
}

// ── Submit order to backend ───────────────────────────
async function submitOrder(payload) {
  const btn = document.getElementById('place-order-btn');
  btn.disabled = true; btn.textContent = 'Placing order…';

  try {
    const res  = await fetch('{{ route("checkout.order") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();

    if (data.success) {
      localStorage.removeItem('kominhoo_cart');
      if (selectedPayment === 'bank_transfer') {
        showBankModal(data.data, payload.total);
      } else {
        window.location.href = '{{ route("dashboard.orders") }}?order_placed=1';
      }
    } else {
      showOrderError(data.message || 'Could not place order. Please try again.');
      btn.disabled = false; btn.textContent = 'Place Order →';
    }
  } catch(e) {
    showOrderError('Network error. Please check your connection and try again.');
    btn.disabled = false; btn.textContent = 'Place Order →';
  }
}

// ── Paystack flow ─────────────────────────────────────
function openPaystack(payload) {
  const btn = document.getElementById('place-order-btn');
  btn.disabled = true; btn.textContent = 'Opening payment…';

  if (!PAYSTACK_KEY) {
    showOrderError('Paystack is not configured. Please choose another payment method.');
    btn.disabled = false; btn.textContent = 'Place Order →';
    return;
  }

  const handler = PaystackPop.setup({
    key:      PAYSTACK_KEY,
    email:    getPaystackEmail(),
    amount:   Math.round(payload.total * 100),
    currency: 'NGN',
    ref:      'KMH-' + Date.now(),
    metadata: { cart_items: payload.items.length },
    onClose: function() {
      btn.disabled = false; btn.textContent = 'Place Order →';
    },
    callback: function(response) {
      payload.payment_reference = response.reference;
      submitOrder(payload);
    }
  });
  handler.openIframe();
}

// ── Bank transfer modal ───────────────────────────────
function showBankModal(order, total) {
  document.getElementById('bankAmount').textContent    = fmt(total);
  document.getElementById('bankReference').textContent = order.order_number || '—';
  const overlay = document.getElementById('bankModalOverlay');
  overlay.style.display = 'flex';
}

function copyBankDetails() {
  const ref = document.getElementById('bankReference').textContent;
  const amt = document.getElementById('bankAmount').textContent;
  const text = `Bank: GTB\nAccount Name: Kominhoo Beauty Ltd\nAccount Number: 0123456789\nAmount: ${amt}\nReference: ${ref}`;
  navigator.clipboard.writeText(text).then(() => {
    const btn = document.querySelector('#bankModalOverlay .btn-outline');
    btn.textContent = '✓ Copied!';
    setTimeout(() => { btn.textContent = '📋 Copy Account Details'; }, 2000);
  });
}

// ── Main place order handler ──────────────────────────
async function placeOrder() {
  if (!cart.length) return;
  if (!validateForm()) {
    document.querySelector('.field-error:not(:empty)')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  const payload = buildPayload();

  if (selectedPayment === 'paystack') {
    openPaystack(payload);
  } else if (selectedPayment === 'wallet') {
    if (WALLET_BALANCE < payload.total) {
      const warn  = document.getElementById('wallet-balance-warn');
      const balEl = document.getElementById('wallet-bal-display');
      if (balEl) balEl.textContent = '₦' + WALLET_BALANCE.toLocaleString('en-NG', { minimumFractionDigits: 2 });
      if (warn) { warn.style.display = ''; warn.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
      return;
    }
    submitOrder(payload);
  } else {
    submitOrder(payload);
  }
}

// ── Clear errors on input ─────────────────────────────
['shipping_name','shipping_phone','shipping_street','shipping_city','shipping_state'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('input', () => clearError('err_' + id.replace('shipping_', '')));
});
const guestEmailEl = document.getElementById('guest_email');
if (guestEmailEl) guestEmailEl.addEventListener('input', () => clearError('err_email'));

document.addEventListener('DOMContentLoaded', () => {
  renderCart();

  // Auto-fill a voucher code saved from the dashboard
  const pending = localStorage.getItem('kominhoo_pending_coupon');
  if (pending) {
    const inp = document.getElementById('coupon_input');
    if (inp && !appliedCoupon) {
      inp.value = pending;
      localStorage.removeItem('kominhoo_pending_coupon');
      setTimeout(applyCoupon, 400);
    }
  }

  // Live wallet balance — refresh from API so the display and balance check are always current
  if (!IS_GUEST) {
    fetch('{{ route('user.wallet.balance') }}', {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'include',
    })
      .then(r => r.ok ? r.json() : null)
      .then(d => {
        if (!d || !d.success) return;

        const bal  = parseFloat(d.balance || 0);
        const fmtW = n => '₦' + n.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Update JS variable used in placeOrder balance check
        WALLET_BALANCE = bal;

        // Update displayed balance in payment method row
        const balEl = document.getElementById('co-wallet-bal');
        if (balEl) { balEl.textContent = fmtW(bal); balEl.style.color = bal > 0 ? '#16A34A' : ''; }

        // Show/hide "Instant" badge and error message
        const badge = document.getElementById('co-wallet-badge');
        const msg   = document.getElementById('co-wallet-msg');
        if (badge) badge.style.display = bal > 0 ? 'inline' : 'none';
        if (msg)   msg.style.display   = bal > 0 ? 'none'   : 'inline';

        // Enable or disable the wallet payment option
        const opt = document.getElementById('pm_label_wallet');
        if (opt) {
          opt.style.opacity = bal > 0 ? '1'       : '.55';
          opt.style.cursor  = bal > 0 ? 'pointer' : 'default';
          opt.onclick       = bal > 0 ? () => selectPayment('wallet') : null;
        }

        // Update the insufficient-balance warning banner amount
        const warnBal = document.getElementById('wallet-bal-display');
        if (warnBal) warnBal.textContent = fmtW(bal);
      })
      .catch(() => {});
  }
});
</script>
@endsection
@endsection
