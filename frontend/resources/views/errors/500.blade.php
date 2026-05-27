@extends('errors.layout')
@section('title', '500 — Something Went Wrong')
@section('content')

<span class="e-watermark">500</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-red">
    <span class="e-particle" style="--tx:16px;--ty:-14px;--dur:2.8s;--delay:0s;top:6px;right:6px;background:var(--red)"></span>
    <span class="e-particle" style="--tx:-12px;--ty:-18px;--dur:3.4s;--delay:.5s;top:10px;left:8px;background:var(--red)"></span>
    <span class="e-particle" style="--tx:10px;--ty:16px;--dur:3s;--delay:1s;bottom:8px;right:10px;width:5px;height:5px;background:var(--red)"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <polygon points="28,4 12,26 22,26 20,44 36,22 26,22" stroke-width="1.8"/>
    </svg>
  </div>
</div>

<span class="e-status">
  <span class="e-status-dot e-status-dot-red"></span>
  System issue detected
</span>

<h1 class="e-headline">Our Formula Glitched</h1>
<p class="e-body">Something unexpected happened on our end — not yours. Our team has been notified and is already mixing up a fix. Please try again in a moment.</p>

<div class="e-actions">
  <button onclick="window.location.reload()" class="e-btn e-btn-primary">
    <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    Try Again
  </button>
  <a href="{{ route('home') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Go Home
  </a>
</div>

<div class="e-divider"></div>

<p style="font-size:.8125rem;color:var(--gray-400);line-height:1.7">
  If this keeps happening, please <a href="mailto:hello@kominhoo.com" style="color:var(--gray-600);font-weight:600;text-decoration:none;border-bottom:1px solid var(--gray-300)">email our support team</a> and we'll sort it out for you right away.
</p>

@endsection
