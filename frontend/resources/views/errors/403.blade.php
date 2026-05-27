@extends('errors.layout')
@section('title', '403 — Access Restricted')
@section('content')

<span class="e-watermark">403</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-dark">
    <span class="e-particle" style="--tx:12px;--ty:-14px;--dur:4s;--delay:0s;top:8px;right:8px;background:var(--gray-400)"></span>
    <span class="e-particle" style="--tx:-10px;--ty:-10px;--dur:4.8s;--delay:.9s;top:12px;left:6px;background:var(--gray-400);width:5px;height:5px"></span>
    <span class="e-particle" style="--tx:8px;--ty:12px;--dur:4.2s;--delay:1.6s;bottom:8px;right:12px;background:var(--gray-400);width:5px;height:5px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <path d="M16 22V18a8 8 0 0 1 16 0v4" stroke-width="2"/>
      <rect x="10" y="22" width="28" height="20" rx="4" stroke-width="2"/>
      <circle cx="24" cy="32" r="3" stroke-width="1.8"/>
      <line x1="24" y1="35" x2="24" y2="39" stroke-width="2"/>
    </svg>
  </div>
</div>

<span class="e-eyebrow"><span class="e-eyebrow-dot"></span> Error 403 <span class="e-eyebrow-dot"></span></span>

<h1 class="e-headline">This Area Is Members Only</h1>
<p class="e-body">You don't have permission to view this page. If you think this is a mistake, please sign in or contact our support team.</p>

<div class="e-actions">
  <a href="{{ route('login') }}" class="e-btn e-btn-dark">
    <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    Sign In
  </a>
  <a href="{{ route('home') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Go Home
  </a>
</div>

<div class="e-divider"></div>

<p style="font-size:.8125rem;color:var(--gray-400);line-height:1.7;text-align:center">
  Don't have an account?
  <a href="{{ route('register') }}" style="color:var(--lime-dark);font-weight:700;text-decoration:none;border-bottom:1px solid var(--lime)">Create one free →</a>
</p>

@endsection
