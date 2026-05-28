<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Kominhoo Beauty — Personalized Korean Skincare Nigeria')</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="alternate icon" href="{{ asset('favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<style>
/* ─── Mobile App Chrome (hidden on desktop, shown ≤459px) ─── */
.mob-sb,.mob-app-nav,.mob-tab-bar{display:none}
@media(max-width:459px){
  .announcement-bar,.nav,.mobile-nav,.mobile-nav-overlay,footer,.floating-quiz{display:none!important}
  body{background:#F2F2F7;padding-bottom:76px}
  .mob-sb{display:flex;align-items:center;justify-content:space-between;height:44px;padding:10px 20px 0;background:#F2F2F7;position:sticky;top:0;z-index:300}
  .mob-sb-time{font-size:15px;font-weight:600;letter-spacing:-.3px;font-family:'Jost',system-ui,sans-serif}
  .mob-sb-icons{display:flex;align-items:center;gap:6px}
  .mob-sb-signal{display:flex;align-items:flex-end;gap:1.5px}
  .mob-sb-bar{width:3px;border-radius:1px;background:currentColor}
  .mob-sb-bar:nth-child(1){height:5px}.mob-sb-bar:nth-child(2){height:7px}.mob-sb-bar:nth-child(3){height:9px}.mob-sb-bar:nth-child(4){height:11px}
  .mob-sb-wifi{width:15px;height:11px}
  .mob-sb-battery{width:25px;height:12px;border:1.5px solid currentColor;border-radius:3px;padding:2px;position:relative;display:flex;align-items:center}
  .mob-sb-battery::after{content:'';position:absolute;right:-5px;top:50%;transform:translateY(-50%);width:3px;height:5px;background:currentColor;border-radius:0 2px 2px 0}
  .mob-sb-battery-fill{height:100%;width:75%;background:currentColor;border-radius:1px}
  .mob-app-nav{display:block;background:#F2F2F7;position:sticky;top:44px;z-index:299}
  .mob-app-nav-inner{display:flex;align-items:center;justify-content:space-between;padding:0 16px;height:44px}
  .mob-nav-logo{font-family:'Jost',sans-serif;font-size:20px;font-weight:800;letter-spacing:1px;text-decoration:none;color:#1C1416}
  .mob-nav-logo span{color:#893941}
  .mob-nav-btn{width:36px;height:36px;border-radius:50%;background:rgba(120,120,128,.12);display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;color:#1C1416;position:relative;flex-shrink:0}
  .mob-nav-btn svg{width:18px;height:18px}
  .mob-cart-dot{position:absolute;top:5px;right:5px;width:8px;height:8px;border-radius:50%;background:#893941;border:1.5px solid #F2F2F7}
  .mob-nav-actions{display:flex;gap:8px}
  .mob-tab-bar{display:flex;position:fixed;bottom:0;left:0;right:0;height:72px;background:rgba(249,249,249,.95);backdrop-filter:blur(20px) saturate(180%);-webkit-backdrop-filter:blur(20px) saturate(180%);border-top:.5px solid rgba(60,60,67,.25);z-index:299;padding:8px 0 16px;align-items:flex-start;justify-content:space-around}
  .mob-tab{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1;color:rgba(60,60,67,.4);font-size:10px;font-weight:500;letter-spacing:.2px;text-decoration:none;cursor:pointer;transition:color .15s;position:relative;font-family:'Jost',sans-serif}
  .mob-tab.active{color:#893941}
  .mob-tab svg{width:24px;height:24px}
  .mob-tab-badge{position:absolute;top:-2px;left:calc(50% + 6px);background:#FF3B30;color:#fff;font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:999px;padding:0 5px;display:flex;align-items:center;justify-content:center;border:2px solid rgba(249,249,249,.95)}
}
</style>
@yield('head')
</head>
<body>

{{-- Mobile: Status Bar --}}
<div class="mob-sb" aria-hidden="true">
  <span id="mob-sb-time" class="mob-sb-time">9:41</span>
  <div class="mob-sb-icons">
    <div class="mob-sb-signal">
      <div class="mob-sb-bar"></div><div class="mob-sb-bar"></div><div class="mob-sb-bar"></div><div class="mob-sb-bar"></div>
    </div>
    <svg class="mob-sb-wifi" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M7.5 2.75C9.43 2.75 11.17 3.52 12.45 4.77L13.84 3.38C12.2 1.78 9.96.75 7.5.75s-4.7 1.03-6.34 2.63L2.55 4.77C3.83 3.52 5.57 2.75 7.5 2.75z" fill="currentColor"/>
      <path d="M7.5 5.75c1.24 0 2.36.48 3.19 1.27L12.08 5.63C10.89 4.48 9.28 3.75 7.5 3.75S4.11 4.48 2.92 5.63l1.39 1.39A4.47 4.47 0 017.5 5.75z" fill="currentColor"/>
      <path d="M7.5 8.75c.55 0 1.05.21 1.43.55L10.32 7.91A3.73 3.73 0 007.5 6.75c-1.04 0-1.97.42-2.66 1.1l1.33 1.45c.38-.34.88-.55 1.33-.55z" fill="currentColor"/>
      <circle cx="7.5" cy="10.25" r="1" fill="currentColor"/>
    </svg>
    <div class="mob-sb-battery"><div class="mob-sb-battery-fill"></div></div>
  </div>
</div>

{{-- Mobile: App Top Nav --}}
<nav class="mob-app-nav" aria-label="App navigation">
  <div class="mob-app-nav-inner">
    <div class="mob-nav-actions">
      <button class="mob-nav-btn" aria-label="Search" onclick="document.getElementById('search-overlay').classList.add('open');setTimeout(function(){var i=document.querySelector('.search-input');if(i)i.focus();},100)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </button>
    </div>
    <a href="{{ route('home') }}" class="mob-nav-logo">KOMIN<span>H</span>OO</a>
    <div class="mob-nav-actions">
      <button class="mob-nav-btn" id="mob-cart-btn" aria-label="Cart" onclick="document.getElementById('cart-drawer').classList.add('open');document.getElementById('cart-overlay').classList.add('open')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span class="mob-cart-dot" id="mob-cart-dot" style="display:none"></span>
      </button>
    </div>
  </div>
</nav>

<!-- Announcement Bar -->
@if(data_get($siteContent, 'announcement_bar.visible', true))
  @php
    $announcement = data_get($siteContent, 'announcement_bar', []);
    $speed = $announcement['speed'] ?? 'normal';
    $animStyle = '';
    if($speed === 'static'){
      $animStyle = 'animation: none;';
    } elseif($speed === 'slow'){
      $animStyle = 'animation: marquee-scroll 60s linear infinite;';
    } elseif($speed === 'fast'){
      $animStyle = 'animation: marquee-scroll 18s linear infinite;';
    } else {
      $animStyle = 'animation: marquee-scroll 38s linear infinite;';
    }
  @endphp
  <div class="announcement-bar">
    <div class="announcement-track" style="{{ $animStyle }}">
      <div class="announcement-items">
        @foreach(data_get($siteContent, 'announcement_bar.items', []) as $item)
          <div class="announcement-item">
            <span>{{ $item['emoji'] ?? '' }}</span>
            {{ $item['text'] ?? '' }}
            @if(!empty($item['link']))
              &nbsp;<a href="{{ $item['link'] }}">Open →</a>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endif

<!-- Navigation -->
@php
  $_isShop = request()->routeIs('shop');
  $_cat    = request()->query('category', '');
  $_tab    = request()->query('tab', '');
  $_navActive = fn($cond) => $cond ? ' active' : '';
@endphp
<nav class="nav">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-logo">KOMIN<span>H</span>OO</a>
    <div class="nav-links">
      <a href="{{ route('shop') }}" class="nav-link{{ $_navActive($_isShop && !$_cat && !$_tab) }}">Shop All</a>
      <a href="{{ route('shop', ['category' => 'cleanser']) }}" class="nav-link{{ $_navActive($_isShop && $_cat === 'cleanser') }}">Cleansers</a>
      <a href="{{ route('shop', ['category' => 'moisturizer']) }}" class="nav-link{{ $_navActive($_isShop && $_cat === 'moisturizer') }}">Moisturizers</a>
      <a href="{{ route('shop', ['category' => 'makeup']) }}" class="nav-link{{ $_navActive($_isShop && $_cat === 'makeup') }}">Makeup</a>
      <a href="{{ route('shop', ['category' => 'haircare']) }}" class="nav-link{{ $_navActive($_isShop && $_cat === 'haircare') }}">Haircare</a>
      <a href="{{ route('shop', ['tab' => 'bundles']) }}" class="nav-link{{ $_navActive($_isShop && $_tab === 'bundles') }}">Bundles</a>
      <a href="{{ route('shop', ['tab' => 'guides']) }}" class="nav-link{{ $_navActive($_isShop && $_tab === 'guides') }}">Buying Guides</a>
      <a href="{{ route('community') }}" class="nav-link{{ $_navActive(request()->routeIs('community')) }}">Gallery</a>
      <a href="{{ route('blog') }}" class="nav-link{{ $_navActive(request()->routeIs('blog*')) }}">Blog</a>
      <a href="{{ route('shop', ['tab' => 'subscription']) }}" class="nav-link{{ $_navActive($_isShop && $_tab === 'subscription') }}" style="color:var(--red);font-weight:700">Subscribe <span class="new-dot"></span></a>
    </div>
    <div class="nav-actions">
      <div class="nav-search" data-open-search>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="11" cy="11" r="7.5"/><line x1="16.5" y1="16.5" x2="21" y2="21"/><line x1="8" y1="10" x2="14" y2="10"/><line x1="8" y1="13" x2="12" y2="13"/></svg>
        <span>Search…</span>
      </div>
      @if(session('api_token'))
        @php $navAvatar = session('user.avatar'); $navInitial = strtoupper(substr(session('user.name','?'), 0, 1)); @endphp
        {{-- Wallet balance chip with show/hide toggle --}}
        <div class="nav-wallet-wrap" style="display:inline-flex;align-items:center;gap:0;background:var(--black);border-radius:999px;border:1.5px solid rgba(212,217,148,.25)">
          <a href="{{ route('dashboard.index') }}#wallet" id="nav-wallet-chip"
             style="display:inline-flex;align-items:center;gap:6px;color:var(--lime);padding:5px 8px 5px 10px;font-size:.75rem;font-weight:700;text-decoration:none"
             title="My Wallet">
            <span style="font-size:.85rem">💳</span>
            <span id="nav-wallet-amount">₦••••</span>
          </a>
          <button id="nav-wallet-toggle" onclick="toggleWalletBalance()"
                  style="background:none;border:none;color:rgba(212,217,148,.6);padding:5px 10px 5px 4px;cursor:pointer;font-size:.75rem;line-height:1;display:flex;align-items:center"
                  title="Show/hide balance" aria-label="Toggle wallet balance">
            <svg id="nav-wallet-eye" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-outline btn-sm" style="display:inline-flex;align-items:center;gap:8px;padding-left:6px">
          @if($navAvatar)
            <img src="{{ $navAvatar }}" alt="" style="width:26px;height:26px;border-radius:50%;object-fit:cover;flex-shrink:0">
          @else
            <span style="width:26px;height:26px;border-radius:50%;background:var(--lime);color:var(--black);display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.65rem;flex-shrink:0">{{ $navInitial }}</span>
          @endif
          {{ explode(' ', session('user.name', 'My Account'))[0] }}
        </a>
        <form method="POST" action="{{ route('logout') }}" class="nav-signout" style="display:inline">
          @csrf
          <button type="submit" class="btn btn-outline btn-sm">Sign Out</button>
        </form>
        <script>
        (function() {
          const _fmtBal = n => '₦' + parseFloat(n || 0).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          const _amtEl  = document.getElementById('nav-wallet-amount');
          const _HIDDEN = '₦••••';
          const _STORE  = 'kmh_wallet_visible';

          // Fetch the real balance but only display it if the user has chosen to show it
          window._navWalletBal = null;
          fetch('{{ route('user.wallet.balance') }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'include' })
            .then(r => r.ok ? r.json() : null)
            .then(d => {
              if (!_amtEl) return;
              window._navWalletBal = (d && d.success) ? _fmtBal(d.balance) : null;
              const visible = localStorage.getItem(_STORE) === '1';
              _amtEl.textContent = (visible && window._navWalletBal) ? window._navWalletBal : _HIDDEN;
              _updateEyeIcon(visible);
            })
            .catch(() => {});

          function _updateEyeIcon(visible) {
            const eye = document.getElementById('nav-wallet-eye');
            if (!eye) return;
            eye.innerHTML = visible
              ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
              : '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
          }

          window.toggleWalletBalance = function() {
            const visible = localStorage.getItem(_STORE) !== '1';
            localStorage.setItem(_STORE, visible ? '1' : '0');
            if (_amtEl) _amtEl.textContent = (visible && window._navWalletBal) ? window._navWalletBal : _HIDDEN;
            _updateEyeIcon(visible);
          };
        })();
        </script>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Sign In</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Join Free</a>
      @endif
      <button class="cart-btn" data-open-cart>
        🛒 <span class="cart-count-label">Cart</span> <span class="cart-count" style="display:none">0</span>
      </button>
      <button class="btn-icon nav-toggle" id="nav-toggle">☰</button>
    </div>
  </div>
</nav>

<!-- Mobile Nav Drawer -->
<div class="mobile-nav" id="mobile-nav" aria-hidden="true">
  <div class="mobile-nav-inner">
    <a href="{{ route('shop') }}" class="mobile-nav-link{{ $_navActive($_isShop && !$_cat && !$_tab) }}">Shop All</a>
    <a href="{{ route('shop', ['category' => 'cleanser']) }}" class="mobile-nav-link{{ $_navActive($_isShop && $_cat === 'cleanser') }}">Cleansers</a>
    <a href="{{ route('shop', ['category' => 'moisturizer']) }}" class="mobile-nav-link{{ $_navActive($_isShop && $_cat === 'moisturizer') }}">Moisturizers</a>
    <a href="{{ route('shop', ['category' => 'makeup']) }}" class="mobile-nav-link{{ $_navActive($_isShop && $_cat === 'makeup') }}">Makeup</a>
    <a href="{{ route('shop', ['category' => 'haircare']) }}" class="mobile-nav-link{{ $_navActive($_isShop && $_cat === 'haircare') }}">Haircare</a>
    <a href="{{ route('shop', ['tab' => 'bundles']) }}" class="mobile-nav-link{{ $_navActive($_isShop && $_tab === 'bundles') }}">Bundle Kits</a>
    <a href="{{ route('shop', ['tab' => 'guides']) }}" class="mobile-nav-link{{ $_navActive($_isShop && $_tab === 'guides') }}">Buying Guides</a>
    <a href="{{ route('community') }}" class="mobile-nav-link{{ $_navActive(request()->routeIs('community')) }}">Gallery</a>
    <a href="{{ route('blog') }}" class="mobile-nav-link{{ $_navActive(request()->routeIs('blog*')) }}">Blog</a>
    <a href="{{ route('shop', ['tab' => 'subscription']) }}" class="mobile-nav-link{{ $_navActive($_isShop && $_tab === 'subscription') }}" style="color:var(--red);font-weight:700">Subscribe ✨</a>
    <div class="mobile-nav-divider"></div>
    <a href="{{ route('quiz') }}" class="mobile-nav-link" style="font-weight:700">✨ Take Skin Quiz</a>
    @if(session('api_token'))
      <a href="{{ route('dashboard.index') }}" class="mobile-nav-link">My Account</a>
      <form method="POST" action="{{ route('logout') }}">@csrf
        <button type="submit" class="mobile-nav-link" style="width:100%;text-align:left;background:none;border:none;font-family:inherit;cursor:pointer;color:var(--red)">Sign Out</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="mobile-nav-link">Sign In</a>
      <a href="{{ route('register') }}" class="mobile-nav-link" style="color:var(--lime-dark);font-weight:700">Join Free →</a>
    @endif
  </div>
</div>
<div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>

<!-- Flash toasts -->
@if(session('success') || session('error') || session('warning') || session('info'))
<style>
.toast-stack{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none}
.toast{
  display:flex;align-items:flex-start;gap:12px;
  background:#fff;border:1px solid rgba(0,0,0,.06);
  border-radius:16px;padding:14px 18px;
  box-shadow:0 8px 40px rgba(0,0,0,.12),0 2px 8px rgba(0,0,0,.06);
  font-family:var(--font-body);
  font-size:.9rem;font-weight:500;color:#1A1A1A;
  max-width:360px;min-width:260px;pointer-events:all;
  animation:toastin .45s cubic-bezier(.34,1.56,.64,1) both;
  position:relative;overflow:hidden;
}
.toast::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
  background:currentColor;opacity:.35;
  animation:toastbar 5s linear forwards;
}
@keyframes toastin{from{opacity:0;transform:translateX(80px) scale(.92)}to{opacity:1;transform:none}}
@keyframes toastbar{from{width:100%}to{width:0}}
.toast-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.toast-icon svg{width:18px;height:18px;stroke-width:2.2;fill:none;stroke-linecap:round;stroke-linejoin:round}
.toast-body{flex:1;min-width:0}
.toast-title{font-weight:700;font-size:.875rem;margin-bottom:2px}
.toast-msg{font-size:.8125rem;color:#737068;line-height:1.55}
.toast-close{background:none;border:none;cursor:pointer;padding:2px;color:#A09E95;border-radius:6px;flex-shrink:0;transition:color .15s;margin-top:1px}
.toast-close:hover{color:#1A1A1A}
.toast-close svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round}
/* Variants */
.toast-success{color:#15803D}
.toast-success .toast-icon{background:#F0FDF4}
.toast-success .toast-icon svg{stroke:#15803D}
.toast-error{color:#C5102F}
.toast-error .toast-icon{background:#FFF0F3}
.toast-error .toast-icon svg{stroke:#C5102F}
.toast-warning{color:#B45309}
.toast-warning .toast-icon{background:#FFFBEB}
.toast-warning .toast-icon svg{stroke:#B45309}
.toast-info{color:#1D4ED8}
.toast-info .toast-icon{background:#EFF6FF}
.toast-info .toast-icon svg{stroke:#1D4ED8}
@media(max-width:480px){.toast-stack{bottom:16px;right:16px;left:16px}.toast{max-width:none}}
</style>
<div class="toast-stack" id="toast-stack">
  @if(session('success'))
  <div class="toast toast-success" role="alert" aria-live="polite">
    <div class="toast-icon">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="toast-body">
      <div class="toast-title">Success</div>
      <div class="toast-msg">{{ session('success') }}</div>
    </div>
    <button class="toast-close" onclick="this.closest('.toast').remove()" aria-label="Dismiss">
      <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  @endif
  @if(session('error'))
  <div class="toast toast-error" role="alert" aria-live="assertive">
    <div class="toast-icon">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div class="toast-body">
      <div class="toast-title">Error</div>
      <div class="toast-msg">{{ session('error') }}</div>
    </div>
    <button class="toast-close" onclick="this.closest('.toast').remove()" aria-label="Dismiss">
      <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  @endif
  @if(session('warning'))
  <div class="toast toast-warning" role="alert" aria-live="polite">
    <div class="toast-icon">
      <svg viewBox="0 0 24 24"><path d="m10.29 3.86-8.03 13.9C1.64 19.05 2.78 21 4.58 21h14.85c1.8 0 2.94-1.95 2.32-3.24L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </div>
    <div class="toast-body">
      <div class="toast-title">Heads up</div>
      <div class="toast-msg">{{ session('warning') }}</div>
    </div>
    <button class="toast-close" onclick="this.closest('.toast').remove()" aria-label="Dismiss">
      <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  @endif
  @if(session('info'))
  <div class="toast toast-info" role="alert" aria-live="polite">
    <div class="toast-icon">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    </div>
    <div class="toast-body">
      <div class="toast-title">Info</div>
      <div class="toast-msg">{{ session('info') }}</div>
    </div>
    <button class="toast-close" onclick="this.closest('.toast').remove()" aria-label="Dismiss">
      <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  @endif
</div>
<script>
  // Auto-dismiss toasts after 5 seconds
  document.querySelectorAll('.toast').forEach((t, i) => {
    setTimeout(() => { t.style.transition='opacity .4s,transform .4s'; t.style.opacity='0'; t.style.transform='translateX(80px)'; setTimeout(()=>t.remove(),400); }, 5000 + i * 300);
  });
</script>
@endif

<!-- Page content -->
@yield('content')

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-logo">KOMIN<span>H</span>OO</div>
        <p class="footer-brand-desc">Nigeria's premier personalized Korean skincare destination. We match you to the products your skin actually needs — guided by science, delivered to your door.</p>
        <div style="display:flex;gap:8px;margin-top:24px">
          <div class="social-btn">𝕏</div>
          <div class="social-btn">📸</div>
          <div class="social-btn">in</div>
          <div class="social-btn">📌</div>
        </div>
        <a href="{{ route('influencer.show') }}" style="display:inline-flex;align-items:center;gap:8px;margin-top:22px;background:var(--lime);color:var(--black);font-size:.78rem;font-weight:700;padding:9px 18px;border-radius:999px;text-decoration:none;letter-spacing:.02em;transition:opacity .18s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
           Be an Ambassador
        </a>
      </div>
      <div>
        <div class="footer-col-title">Shop</div>
        <div class="footer-links">
          <a href="{{ route('shop') }}" class="footer-link">All Products</a>
          <a href="{{ route('shop', ['category' => 'cleanser']) }}" class="footer-link">Cleansers</a>
          <a href="{{ route('shop', ['category' => 'serum']) }}" class="footer-link">Serums & Essences</a>
          <a href="{{ route('shop', ['category' => 'moisturizer']) }}" class="footer-link">Moisturizers</a>
          <a href="{{ route('shop', ['bundles' => 1]) }}" class="footer-link">Bundle Kits</a>
          <a href="{{ route('shop', ['tab' => 'subscription']) }}" class="footer-link">Subscriptions</a>
          <a href="{{ route('gift-cards.index') }}" class="footer-link">Gift Cards 🎁</a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Help</div>
        <div class="footer-links">
          <a href="{{ route('quiz') }}" class="footer-link">Skin Quiz</a>
          <a href="{{ route('shop', ['tab' => 'guides']) }}" class="footer-link">Buying Guides</a>
          <a href="{{ route('shipping.policy') }}" class="footer-link">Shipping Policy</a>
          <a href="{{ route('returns.policy') }}" class="footer-link">Returns & Exchanges</a>
          <a href="{{ route('faq') }}" class="footer-link">FAQ</a>
          <a href="{{ route('contact') }}" class="footer-link">Contact Us</a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Company</div>
        <div class="footer-links">
          <a href="{{ route('about') }}" class="footer-link">About Kominhoo</a>
          <a href="{{ route('our-promise') }}" class="footer-link">Our Promise</a>
          <a href="{{ route('loyalty-program') }}" class="footer-link">Loyalty Program</a>
          <a href="{{ route('influencer.show') }}" class="footer-link">Become an Ambassador</a>
          <a href="{{ route('blog') }}" class="footer-link">Blog</a>
          <a href="#" class="footer-link" style="color:rgba(255,255,255,.3)">Admin →</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copyright">© {{ date('Y') }} Kominhoo Beauty Nigeria. All rights reserved. | <a href="{{ route('privacy.policy') }}" style="color:rgba(255,255,255,.3)">Privacy</a> · <a href="{{ route('terms') }}" style="color:rgba(255,255,255,.3)">Terms</a></div>
      <div style="display:flex;gap:12px;align-items:center">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Visa_Logo.png/640px-Visa_Logo.png" alt="Visa" style="height:20px;filter:brightness(0) invert(.4)">
        <span style="color:rgba(255,255,255,.2)">·</span>
        <span style="color:rgba(255,255,255,.4);font-size:.82rem">Mastercard · Bank Transfer · USSD</span>
      </div>
    </div>
  </div>
</footer>

<!-- Floating Quiz Button -->
<a href="{{ route('quiz') }}" class="floating-quiz" id="floating-quiz" style="display:none">
  <div class="pulse-dot"></div> Take the Skin Quiz
</a>

<!-- Cart Drawer -->
<div class="modal-overlay" id="cart-overlay" style="background:rgba(0,0,0,.4);backdrop-filter:none;z-index:8999;display:block;position:fixed;inset:0;opacity:0;visibility:hidden;transition:var(--t-base)"></div>
<div class="cart-drawer" id="cart-drawer">
  <div class="cart-header">
    <div class="cart-title">Your Cart 🛒</div>
    <button id="close-cart" style="font-size:1.3rem;color:var(--text-muted)">✕</button>
  </div>
  <div class="cart-items"></div>
  <div class="cart-footer">
    <div class="cart-footer-totals"></div>
    <button class="cart-checkout-btn" onclick="window.location='{{ route('checkout') }}'">Proceed to Checkout →</button>
    <div style="text-align:center;margin-top:12px;font-size:.78rem;color:var(--text-muted)">🔒 Secure checkout · Free returns</div>
  </div>
</div>

<!-- Search Overlay -->
<div class="search-overlay" id="search-overlay">
  <div class="search-box">
    <span style="font-size:1.2rem;color:var(--text-muted)">🔍</span>
    <input class="search-input" placeholder="Search serums, cleansers, brands…" type="text">
    <span class="search-close" id="close-search">✕</span>
  </div>
  <div class="search-results" id="search-results"></div>
</div>

<!-- Quick View Modal -->
<div class="modal-overlay" id="quick-view-modal">
  <div class="modal" style="max-width:720px;width:100%;position:relative">
    <span class="modal-close" onclick="closeModal('quick-view-modal')">✕</span>
    <div id="qv-content"></div>
  </div>
</div>

<script>const BASE_URL = '{{ rtrim(url(''), '/') }}';</script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
(function() {
  const toggle  = document.getElementById('nav-toggle');
  const nav     = document.getElementById('mobile-nav');
  const overlay = document.getElementById('mobile-nav-overlay');
  if (!toggle || !nav) return;
  function openNav()  { nav.classList.add('open'); overlay.classList.add('open'); toggle.textContent = '✕'; nav.setAttribute('aria-hidden','false'); }
  function closeNav() { nav.classList.remove('open'); overlay.classList.remove('open'); toggle.textContent = '☰'; nav.setAttribute('aria-hidden','true'); }
  toggle.addEventListener('click', () => nav.classList.contains('open') ? closeNav() : openNav());
  overlay.addEventListener('click', closeNav);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNav(); });
})();
</script>
@yield('scripts')

{{-- Mobile: Bottom Tab Bar --}}
<nav class="mob-tab-bar" aria-label="Bottom navigation">
  <a href="{{ route('home') }}" class="mob-tab {{ request()->routeIs('home') ? 'active' : '' }}" aria-label="Home">
    <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
    </svg>
    Home
  </a>
  <a href="{{ route('shop') }}" class="mob-tab {{ request()->routeIs('shop*') ? 'active' : '' }}" aria-label="Shop">
    <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('shop*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
    </svg>
    Shop
  </a>
  <a href="{{ route('community') }}" class="mob-tab {{ request()->routeIs('community*') ? 'active' : '' }}" aria-label="Explore">
    <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('community*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/>
    </svg>
    Explore
  </a>
  <button class="mob-tab {{ request()->routeIs('cart*') ? 'active' : '' }}" aria-label="Cart" onclick="document.getElementById('cart-drawer').classList.add('open');document.getElementById('cart-overlay').classList.add('open')" style="background:none;border:none;padding:0">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:relative">
      <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
    </svg>
    <span class="mob-tab-badge" id="mob-tab-cart-badge" style="display:none">0</span>
    Cart
  </button>
  <a href="{{ session('api_token') ? route('dashboard.index') : route('login') }}" class="mob-tab {{ request()->routeIs('dashboard*','login','register') ? 'active' : '' }}" aria-label="Profile">
    <svg viewBox="0 0 24 24" fill="{{ request()->routeIs('dashboard*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
    </svg>
    Profile
  </a>
</nav>
<script>
(function(){
  // Status bar clock
  function updateClock(){
    var el=document.getElementById('mob-sb-time');
    if(!el)return;
    var d=new Date(),h=d.getHours(),m=d.getMinutes();
    var ampm=h>=12?'PM':'AM';h=h%12||12;
    el.textContent=h+':'+(m<10?'0'+m:m)+' '+ampm;
  }
  updateClock();
  setInterval(updateClock,30000);

  // Cart badge sync — patch after app.js updateCartUI
  var _origUpdateCartUI=window.updateCartUI;
  window.updateCartUI=function(count){
    if(_origUpdateCartUI)_origUpdateCartUI.apply(this,arguments);
    var dot=document.getElementById('mob-cart-dot');
    var badge=document.getElementById('mob-tab-cart-badge');
    var n=parseInt(count)||0;
    if(dot){dot.style.display=n>0?'':'none';}
    if(badge){badge.style.display=n>0?'flex':'none';badge.textContent=n>9?'9+':n;}
  };
  // Run once on load to sync from existing cart state
  if(window.cart&&window.cart.items){
    var total=window.cart.items.reduce(function(s,i){return s+(i.qty||1);},0);
    window.updateCartUI(total);
  }
})();
</script>
</body>
</html>
