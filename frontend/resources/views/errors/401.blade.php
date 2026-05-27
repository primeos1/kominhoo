@extends('errors.layout')
@section('title', '401 — Please Sign In')
@section('content')

<span class="e-watermark">401</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-blue">
    <span class="e-particle" style="--tx:14px;--ty:-18px;--dur:3.6s;--delay:0s;top:4px;right:6px;background:#3B82F6"></span>
    <span class="e-particle" style="--tx:-12px;--ty:-12px;--dur:4.4s;--delay:.7s;top:8px;left:4px;background:#3B82F6;width:5px;height:5px"></span>
    <span class="e-particle" style="--tx:10px;--ty:14px;--dur:3.8s;--delay:1.4s;bottom:6px;right:10px;background:#3B82F6;width:6px;height:6px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <circle cx="18" cy="20" r="9" stroke-width="2"/>
      <circle cx="18" cy="20" r="4" stroke-width="1.8"/>
      <line x1="25" y1="26" x2="40" y2="36" stroke-width="2.5"/>
      <line x1="31" y1="29.5" x2="31" y2="33" stroke-width="2.2"/>
      <line x1="36" y1="32.5" x2="36" y2="36" stroke-width="2.2"/>
    </svg>
  </div>
</div>

<span class="e-eyebrow"><span class="e-eyebrow-dot"></span> Error 401 <span class="e-eyebrow-dot"></span></span>

<h1 class="e-headline">Continue Your Skincare Journey</h1>
<p class="e-body">You need to be signed in to view this page. Log in to access your personalized recommendations, wishlist, and order history.</p>

<div class="e-actions">
  <a href="{{ route('login') }}" class="e-btn e-btn-primary">
    <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    Sign In
  </a>
  <a href="{{ route('register') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
    Create Free Account
  </a>
</div>

<div class="e-divider"></div>

<div style="display:flex;align-items:center;justify-content:center;gap:24px;flex-wrap:wrap">
  <a href="{{ route('home') }}" style="font-size:.8125rem;font-weight:600;color:var(--gray-500);text-decoration:none" onmouseover="this.style.color='var(--dark)'" onmouseout="this.style.color='var(--gray-500)'">← Back to Home</a>
  <a href="{{ route('quiz') }}" style="font-size:.8125rem;font-weight:600;color:var(--gray-500);text-decoration:none" onmouseover="this.style.color='var(--dark)'" onmouseout="this.style.color='var(--gray-500)'">Take the Skin Quiz →</a>
  <a href="{{ route('shop') }}" style="font-size:.8125rem;font-weight:600;color:var(--gray-500);text-decoration:none" onmouseover="this.style.color='var(--dark)'" onmouseout="this.style.color='var(--gray-500)'">Browse Shop →</a>
</div>

@endsection
