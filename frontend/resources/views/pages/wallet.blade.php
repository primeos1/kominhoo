@extends('layouts.app')
@section('title', 'My Wallet — Kominhoo Beauty')

@section('head')
<style>
/* ── Wallet page layout ── */
.wallet-layout { display:grid; grid-template-columns:260px 1fr; gap:28px; max-width:1100px; margin:0 auto; padding:40px 24px; }

/* ── Sidebar ── (same pattern as dashboard) */
.wallet-sidebar { background:var(--rose-dark); border-radius:20px; padding:24px 16px; height:fit-content; position:sticky; top:24px; color:#fff; }
.ws-avatar { width:70px; height:70px; border-radius:50%; background:var(--lime); color:var(--black); font-size:1.6rem; font-weight:700; display:grid; place-items:center; margin:0 auto 12px; overflow:hidden; }
.ws-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.ws-name { text-align:center; font-size:.92rem; font-weight:700; color:#fff; }
.ws-tier { text-align:center; font-size:.72rem; color:rgba(255,255,255,.35); margin-top:3px; margin-bottom:20px; }
.ws-nav-sep { height:1px; background:rgba(255,255,255,.07); margin:12px 4px; }
.ws-nav-label { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.14em; color:rgba(255,255,255,.22); padding:0 12px; margin-bottom:6px; }
.ws-nav-item { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; font-size:.82rem; font-weight:600; color:rgba(255,255,255,.5); cursor:pointer; transition:.15s; text-decoration:none; }
.ws-nav-item:hover { background:rgba(255,255,255,.05); color:#fff; }
.ws-nav-item.active { background:rgba(212,217,148,.12); color:var(--lime); }

/* ── Balance card ── */
.wallet-card { background:linear-gradient(135deg,var(--black) 0%,#111 55%,#1c1c1c 100%); border-radius:20px; padding:32px 36px; color:#fff; position:relative; overflow:hidden; margin-bottom:24px; }
.wallet-card::before { content:''; position:absolute; top:-80px; right:-80px; width:280px; height:280px; background:radial-gradient(circle,rgba(212,217,148,.13) 0%,transparent 65%); pointer-events:none; }
.wallet-card::after { content:''; position:absolute; bottom:-60px; left:-40px; width:180px; height:180px; background:radial-gradient(circle,rgba(137,57,65,.07) 0%,transparent 65%); pointer-events:none; }
.wallet-card-inner { position:relative; z-index:1; }
.wc-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.16em; color:rgba(255,255,255,.3); margin-bottom:8px; }
.wc-balance { font-size:2.8rem; font-weight:700; color:#fff; font-variant-numeric:tabular-nums; line-height:1; margin-bottom:4px; }
.wc-balance span { font-size:1.4rem; font-weight:700; color:var(--lime); margin-right:4px; }
.wc-currency { font-size:.72rem; color:rgba(255,255,255,.35); margin-bottom:28px; }
.wc-stats { display:flex; gap:32px; }
.wc-stat { display:flex; flex-direction:column; gap:4px; }
.wc-stat-label { font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.25); }
.wc-stat-value { font-size:.88rem; font-weight:700; color:rgba(255,255,255,.6); }
.wc-status { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:999px; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin-left:auto; }
.wc-status.active { background:rgba(34,197,94,.15); color:#4ade80; }
.wc-status.suspended { background:rgba(245,158,11,.15); color:#fbbf24; }
.wc-status.frozen { background:rgba(59,130,246,.15); color:#60a5fa; }
.wc-header-row { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px; }

/* ── Top-up form ── */
.topup-card { background:#fff; border-radius:var(--r-xl); border:1.5px solid var(--border); padding:28px 32px; margin-bottom:24px; }
.topup-title { font-size:1rem; font-weight:700; margin-bottom:4px; }
.topup-sub { font-size:.82rem; color:var(--text-muted); margin-bottom:20px; }
.topup-amounts { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px; }
.topup-preset { background:var(--gray-50,#FAFAFA); border:1.5px solid var(--border); border-radius:var(--r-md); padding:10px 18px; font-size:.88rem; font-weight:700; cursor:pointer; transition:.15s; }
.topup-preset:hover { border-color:var(--lime); background:var(--lime-pale); }
.topup-preset.selected { border-color:var(--lime); background:var(--lime-pale); color:var(--black); }
.topup-input-row { display:flex; gap:12px; align-items:flex-end; }
.topup-input-wrap { flex:1; display:flex; flex-direction:column; gap:6px; }
.topup-input-wrap label { font-size:.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.07em; }
.topup-input { border:1.5px solid var(--border); border-radius:var(--r-md); padding:12px 16px; font-size:1rem; font-weight:700; width:100%; background:#fff; transition:.15s; }
.topup-input:focus { outline:none; border-color:var(--lime); }
.topup-btn { background:var(--rose-dark); color:var(--lime); border:none; border-radius:var(--r-md); padding:13px 28px; font-size:.88rem; font-weight:700; cursor:pointer; transition:.15s; white-space:nowrap; }
.topup-btn:hover { background:var(--rose); }
.topup-note { font-size:.72rem; color:var(--text-muted); margin-top:12px; display:flex; align-items:center; gap:6px; }

/* ── Transaction list ── */
.tx-section { background:#fff; border-radius:var(--r-xl); border:1.5px solid var(--border); overflow:hidden; }
.tx-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1.5px solid var(--border); }
.tx-title { font-size:1rem; font-weight:700; }
.tx-filter { display:flex; gap:6px; }
.tx-filter-btn { border:1.5px solid var(--border); background:#fff; border-radius:var(--r-pill); padding:5px 14px; font-size:.75rem; font-weight:700; cursor:pointer; transition:.15s; }
.tx-filter-btn.active, .tx-filter-btn:hover { border-color:var(--rose-dark); background:var(--rose-dark); color:#fff; }
.tx-empty { text-align:center; padding:48px 24px; color:var(--text-muted); font-size:.88rem; }
.tx-item { display:flex; align-items:center; gap:16px; padding:16px 24px; border-bottom:1px solid var(--border); transition:.15s; }
.tx-item:last-child { border-bottom:none; }
.tx-item:hover { background:var(--gray-50,#FAFAFA); }
.tx-icon { width:44px; height:44px; border-radius:12px; display:grid; place-items:center; font-size:1.2rem; flex-shrink:0; }
.tx-icon.credit { background:rgba(34,197,94,.1); }
.tx-icon.debit { background:rgba(137,57,65,.08); }
.tx-icon.bonus { background:rgba(212,217,148,.15); }
.tx-icon.pending { background:rgba(107,114,128,.08); }
.tx-body { flex:1; min-width:0; }
.tx-desc { font-size:.88rem; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.tx-meta { font-size:.72rem; color:var(--text-muted); margin-top:2px; display:flex; align-items:center; gap:8px; }
.tx-cat { display:inline-block; padding:2px 8px; border-radius:999px; font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; background:var(--gray-100,#F3F4F6); color:var(--text-muted); }
.tx-amount { font-size:.95rem; font-weight:700; text-align:right; flex-shrink:0; }
.tx-amount.credit, .tx-amount.bonus, .tx-amount.refund { color:#16A34A; }
.tx-amount.debit, .tx-amount.withdrawal { color:#DC2626; }
.tx-amount.pending { color:var(--text-muted); }
.tx-status-badge { font-size:.6rem; font-weight:700; padding:2px 7px; border-radius:999px; display:inline-block; margin-top:2px; }
.tx-status-successful { background:rgba(34,197,94,.1); color:#16A34A; }
.tx-status-pending { background:rgba(107,114,128,.08); color:#6B7280; }
.tx-status-failed { background:rgba(220,38,38,.08); color:#DC2626; }

/* ── Alert messages ── */
.wallet-alert { border-radius:var(--r-lg); padding:14px 20px; display:flex; align-items:flex-start; gap:12px; margin-bottom:20px; font-size:.87rem; }
.wallet-alert.success { background:#dcfce7; color:#166534; border:1px solid #86efac; }
.wallet-alert.error { background:#fef2f2; color:#991b1b; border:1px solid #fca5a5; }

@media(max-width:760px) {
  .wallet-layout { grid-template-columns:1fr; }
  .wallet-sidebar { position:static; }
  .wc-balance { font-size:2rem; }
  .wc-stats { gap:16px; }
  .topup-input-row { flex-direction:column; }
}
@media(max-width:480px) {
  .wc-balance { font-size:1.6rem; }
  .wc-stats { flex-wrap:wrap; }
}
</style>
@endsection

@section('content')
@php
  $user         = $user ?? session('user') ?? [];
  $walletData   = $wallet['wallet'] ?? [];
  $balance      = number_format((float)($walletData['available_balance'] ?? 0), 2);
  $locked       = number_format((float)($walletData['locked_balance'] ?? 0), 2);
  $walletStatus = $walletData['status'] ?? 'active';
  $txList       = $transactions['data'] ?? [];

  $avatarLetter = strtoupper(substr($user['name'] ?? 'A', 0, 1));
  $tierLabel    = $user['tier'] ?? 'starter';
  $unreadCount  = $notifData['unread_count'] ?? 0;
@endphp

<div class="wallet-layout">

  <!-- ── Sidebar ── -->
  <aside class="wallet-sidebar">
    <div class="ws-avatar">
      @if(!empty($user['avatar']))
        <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] ?? '' }}">
      @else
        {{ $avatarLetter }}
      @endif
    </div>
    <div class="ws-name">{{ $user['name'] ?? 'My Account' }}</div>
    <div class="ws-tier">{{ ucfirst($tierLabel) }}</div>

    <div class="ws-nav-label">Overview</div>
    <a href="/dashboard" class="ws-nav-item"><span>🏠</span> Dashboard</a>
    <a href="/dashboard/orders" class="ws-nav-item"><span>📦</span> Orders</a>

    <div class="ws-nav-sep"></div>
    <div class="ws-nav-label">Wallet</div>
    <a href="/dashboard/wallet" class="ws-nav-item active"><span>💳</span> My Wallet</a>

    <div class="ws-nav-sep"></div>
    <div class="ws-nav-label">Membership</div>
    <a href="/dashboard" class="ws-nav-item"><span>🌟</span> Loyalty & Points</a>
    <a href="/dashboard" class="ws-nav-item">
      <span>🔔</span> Notifications
      @if($unreadCount > 0)
        <span style="background:var(--red);color:#fff;font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:999px;margin-left:auto">{{ $unreadCount }}</span>
      @endif
    </a>

    <div class="ws-nav-sep"></div>
    <a href="/shop" class="ws-nav-item"><span>🛍️</span> Shop</a>
    <form method="POST" action="/logout" style="margin:0">
      @csrf
      <button type="submit" class="ws-nav-item" style="width:100%;border:none;background:none;text-align:left;color:rgba(255,255,255,.5);cursor:pointer">
        <span>🚪</span> Sign Out
      </button>
    </form>
  </aside>

  <!-- ── Main content ── -->
  <main>

    @if(session('wallet_success'))
    <div class="wallet-alert success">
      <span style="font-size:1.2rem">✓</span>
      <span>{{ session('wallet_success') }}</span>
    </div>
    @endif

    @if(session('wallet_error'))
    <div class="wallet-alert error">
      <span style="font-size:1.2rem">⚠</span>
      <span>{{ session('wallet_error') }}</span>
    </div>
    @endif

    <!-- Balance card -->
    <div class="wallet-card">
      <div class="wallet-card-inner">
        <div class="wc-header-row">
          <div>
            <div class="wc-label">Available Balance</div>
            <div class="wc-balance"><span>₦</span>{{ $balance }}</div>
            <div class="wc-currency">Nigerian Naira · Kominhoo Wallet</div>
          </div>
          <div class="wc-status {{ $walletStatus }}">{{ ucfirst($walletStatus) }}</div>
        </div>
        <div class="wc-stats">
          <div class="wc-stat">
            <div class="wc-stat-label">Locked</div>
            <div class="wc-stat-value">₦{{ $locked }}</div>
          </div>
          <div class="wc-stat">
            <div class="wc-stat-label">Currency</div>
            <div class="wc-stat-value">NGN</div>
          </div>
          <div class="wc-stat">
            <div class="wc-stat-label">Total Transactions</div>
            <div class="wc-stat-value">{{ $transactions['total'] ?? count($txList) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top-up form -->
    @if($walletStatus === 'active')
    <div class="topup-card">
      <div class="topup-title">Add Money to Wallet</div>
      <div class="topup-sub">Securely fund your wallet via Paystack. Your balance updates immediately after payment confirmation.</div>

      <div class="topup-amounts">
        <button class="topup-preset" onclick="setAmount(500)">₦500</button>
        <button class="topup-preset" onclick="setAmount(1000)">₦1,000</button>
        <button class="topup-preset" onclick="setAmount(2500)">₦2,500</button>
        <button class="topup-preset" onclick="setAmount(5000)">₦5,000</button>
        <button class="topup-preset" onclick="setAmount(10000)">₦10,000</button>
        <button class="topup-preset" onclick="setAmount(20000)">₦20,000</button>
      </div>

      <form method="POST" action="{{ route('dashboard.wallet.deposit') }}" id="topup-form">
        @csrf
        <div class="topup-input-row">
          <div class="topup-input-wrap">
            <label for="amount">Custom amount (₦)</label>
            <input type="number" name="amount" id="amount-input" class="topup-input"
                   placeholder="e.g. 3000" min="100" max="1000000" step="100" required>
          </div>
          <button type="submit" class="topup-btn" id="topup-submit-btn">
            Fund Wallet →
          </button>
        </div>
        @error('amount')
          <div style="color:#DC2626;font-size:.78rem;margin-top:8px">{{ $message }}</div>
        @enderror
      </form>

      <div class="topup-note">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Payments secured by Paystack. Wallet credited after webhook verification — not on redirect.
      </div>
    </div>
    @else
    <div class="wallet-alert error">
      <span>⚠</span>
      <span>Your wallet is <strong>{{ $walletStatus }}</strong>. Please contact support to reactivate.</span>
    </div>
    @endif

    <!-- Transaction history -->
    <div class="tx-section">
      <div class="tx-header">
        <div class="tx-title">Transaction History</div>
        <div class="tx-filter">
          <button class="tx-filter-btn active" onclick="filterTx('all', this)">All</button>
          <button class="tx-filter-btn" onclick="filterTx('credit', this)">Credits</button>
          <button class="tx-filter-btn" onclick="filterTx('debit', this)">Debits</button>
          <button class="tx-filter-btn" onclick="filterTx('bonus', this)">Bonuses</button>
        </div>
      </div>

      @if(empty($txList))
      <div class="tx-empty">
        <div style="font-size:2rem;margin-bottom:10px">💳</div>
        <div style="font-weight:700;margin-bottom:6px">No transactions yet</div>
        <div>Fund your wallet above to get started.</div>
      </div>
      @else
      <div id="tx-list">
        @foreach($txList as $tx)
        @php
          $type    = $tx['transaction_type'] ?? 'credit';
          $status  = $tx['status'] ?? 'successful';
          $amount  = number_format((float)($tx['amount'] ?? 0), 2);
          $prefix  = in_array($type, ['credit','bonus','refund','reversal']) ? '+' : '−';
          $iconMap = [
            'deposit'            => '💸',
            'purchase'           => '🛍️',
            'signup_bonus'       => '🎁',
            'first_deposit_bonus'=> '🎉',
            'referral_bonus'     => '👥',
            'admin_bonus'        => '⭐',
            'campaign_bonus'     => '📢',
            'refund'             => '↩️',
          ];
          $icon    = $iconMap[$tx['category'] ?? ''] ?? '💳';
          $catLabel= str_replace('_', ' ', $tx['category'] ?? $type);
          $date    = isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('M j, Y · g:ia') : '';
        @endphp
        <div class="tx-item" data-type="{{ $type }}" data-status="{{ $status }}">
          <div class="tx-icon {{ in_array($type, ['credit','bonus','refund']) ? 'credit' : ($status === 'pending' ? 'pending' : 'debit') }}">
            {{ $icon }}
          </div>
          <div class="tx-body">
            <div class="tx-desc">{{ $tx['description'] ?? ucfirst($catLabel) }}</div>
            <div class="tx-meta">
              <span>{{ $date }}</span>
              <span class="tx-cat">{{ $catLabel }}</span>
              <span class="tx-status-badge tx-status-{{ $status }}">{{ $status }}</span>
            </div>
          </div>
          <div class="tx-amount {{ in_array($status, ['pending','failed']) ? $status : $type }}">
            @if($status === 'pending') <span style="font-size:.7rem;display:block;text-align:right;margin-bottom:2px">Pending</span> @endif
            {{ in_array($type, ['credit','bonus','refund','reversal']) ? '+' : '−' }}₦{{ $amount }}
          </div>
        </div>
        @endforeach
      </div>

      @if(($transactions['last_page'] ?? 1) > 1)
      <div style="padding:16px 24px;border-top:1px solid var(--border);text-align:center">
        <a href="/dashboard/wallet?page={{ ($transactions['current_page'] ?? 1) + 1 }}"
           style="font-size:.82rem;font-weight:700;color:var(--black);text-decoration:none">
          Load more transactions →
        </a>
      </div>
      @endif
      @endif
    </div>

  </main>
</div>

<script>
function setAmount(val) {
  document.getElementById('amount-input').value = val;
  document.querySelectorAll('.topup-preset').forEach(b => b.classList.remove('selected'));
  event.target.classList.add('selected');
}

document.getElementById('amount-input')?.addEventListener('input', function() {
  document.querySelectorAll('.topup-preset').forEach(b => b.classList.remove('selected'));
});

document.getElementById('topup-form')?.addEventListener('submit', function() {
  const btn = document.getElementById('topup-submit-btn');
  if (btn) { btn.textContent = 'Redirecting to Paystack…'; btn.disabled = true; }
});

function filterTx(type, btn) {
  document.querySelectorAll('.tx-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.tx-item').forEach(item => {
    if (type === 'all') {
      item.style.display = '';
    } else {
      item.style.display = item.dataset.type === type ? '' : 'none';
    }
  });
}
</script>
@endsection
