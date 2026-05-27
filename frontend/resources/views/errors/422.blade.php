@extends('errors.layout')
@section('title', '422 — Validation Error')
@section('content')

<span class="e-watermark">422</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-amber">
    <span class="e-particle" style="--tx:14px;--ty:-16px;--dur:3s;--delay:0s;top:6px;right:6px;background:var(--amber)"></span>
    <span class="e-particle" style="--tx:-12px;--ty:-10px;--dur:3.8s;--delay:.7s;top:10px;left:4px;background:var(--amber);width:5px;height:5px"></span>
    <span class="e-particle" style="--tx:8px;--ty:14px;--dur:4s;--delay:1.2s;bottom:6px;right:10px;background:var(--amber);width:4px;height:4px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <rect x="8" y="8" width="32" height="32" rx="4" stroke-width="2"/>
      <line x1="16" y1="16" x2="32" y2="32" stroke-width="2.5"/>
      <line x1="32" y1="16" x2="16" y2="32" stroke-width="2.5"/>
    </svg>
  </div>
</div>

<span class="e-eyebrow"><span class="e-eyebrow-dot"></span> Error 422 <span class="e-eyebrow-dot"></span></span>

<h1 class="e-headline">Something Doesn't Look Right</h1>
<p class="e-body">The information you submitted couldn't be processed. Please go back and double-check your entries, then try again.</p>

<div class="e-actions">
  <button onclick="history.back()" class="e-btn e-btn-primary">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Fix My Entries
  </button>
  <a href="{{ route('home') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Go Home
  </a>
</div>

@endsection
