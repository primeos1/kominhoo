@extends('errors.layout')
@section('title', '405 — Method Not Allowed')
@section('content')

<span class="e-watermark">405</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-amber">
    <span class="e-particle" style="--tx:12px;--ty:-14px;--dur:3.4s;--delay:0s;top:6px;right:8px;background:var(--amber)"></span>
    <span class="e-particle" style="--tx:-10px;--ty:-10px;--dur:4s;--delay:.6s;top:10px;left:6px;background:var(--amber);width:5px;height:5px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <circle cx="24" cy="24" r="16" stroke-width="2"/>
      <line x1="14" y1="24" x2="34" y2="24" stroke-width="3.5"/>
    </svg>
  </div>
</div>

<span class="e-eyebrow"><span class="e-eyebrow-dot"></span> Error 405 <span class="e-eyebrow-dot"></span></span>

<h1 class="e-headline">That Route Isn't Available</h1>
<p class="e-body">The HTTP method used for this request isn't allowed here. This is usually a technical issue — please go back and try again.</p>

<div class="e-actions">
  <button onclick="history.back()" class="e-btn e-btn-primary">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Go Back
  </button>
  <a href="{{ route('home') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Go Home
  </a>
</div>

@endsection
