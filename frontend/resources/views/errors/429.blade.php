@extends('errors.layout')
@section('title', '429 — Too Many Requests')
@section('content')

<span class="e-watermark">429</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-red">
    <span class="e-particle" style="--tx:18px;--ty:-10px;--dur:2.4s;--delay:0s;top:8px;right:4px;background:var(--red)"></span>
    <span class="e-particle" style="--tx:-16px;--ty:-8px;--dur:2.8s;--delay:.4s;top:14px;left:4px;background:var(--red);width:5px;height:5px"></span>
    <span class="e-particle" style="--tx:14px;--ty:12px;--dur:2.6s;--delay:.8s;bottom:6px;right:8px;background:var(--red);width:4px;height:4px"></span>
    <span class="e-particle" style="--tx:-12px;--ty:10px;--dur:3s;--delay:.2s;bottom:10px;left:6px;background:var(--red);width:6px;height:6px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <path d="M12 36V20a3 3 0 0 1 6 0v-6a3 3 0 0 1 6 0v2a3 3 0 0 1 6 0v4a3 3 0 0 1 6 0v8c0 5.523-4.477 10-10 10H20a8 8 0 0 1-8-8z" stroke-width="2"/>
      <line x1="18" y1="24" x2="18" y2="30" stroke-width="1.4" opacity=".5"/>
      <line x1="24" y1="22" x2="24" y2="30" stroke-width="1.4" opacity=".5"/>
      <line x1="30" y1="24" x2="30" y2="30" stroke-width="1.4" opacity=".5"/>
    </svg>
  </div>
</div>

<span class="e-eyebrow"><span class="e-eyebrow-dot"></span> Error 429 <span class="e-eyebrow-dot"></span></span>

<h1 class="e-headline">Slow Down, Gorgeous</h1>
<p class="e-body">You've made too many requests in a short time. Take a quick beauty break — your skin (and our servers) will thank you.</p>

@php
  $retryAfter = null;
  if (!empty($exception)) {
    try { $retryAfter = method_exists($exception, 'getHeaders') ? ($exception->getHeaders()['Retry-After'] ?? null) : null; } catch (\Throwable $e) {}
  }
@endphp

@if($retryAfter)
  <div class="e-status" style="margin-bottom:32px">
    <span class="e-status-dot e-status-dot-amber"></span>
    Try again in <span id="retry-countdown">{{ $retryAfter }}</span> seconds
  </div>
@endif

<div class="e-actions">
  <button onclick="window.location.reload()" class="e-btn e-btn-primary" id="retry-btn" @if($retryAfter) disabled style="opacity:.55;cursor:not-allowed" @endif>
    <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    <span id="retry-label">Try Again</span>
  </button>
  <a href="{{ route('home') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Go Home
  </a>
</div>

<div class="e-divider"></div>

<p style="font-size:.8125rem;color:var(--gray-400);line-height:1.7;text-align:center">
  While you wait, why not <a href="{{ route('quiz') }}" style="color:var(--gray-600);font-weight:600;text-decoration:none;border-bottom:1px solid var(--gray-300)">take your skin quiz</a>
  or browse our <a href="{{ route('community') }}" style="color:var(--gray-600);font-weight:600;text-decoration:none;border-bottom:1px solid var(--gray-300)">community gallery</a>?
</p>

@endsection

@section('scripts')
@if($retryAfter ?? false)
<script>
  const btn = document.getElementById('retry-btn');
  const label = document.getElementById('retry-label');
  const cd = document.getElementById('retry-countdown');
  let secs = {{ $retryAfter }};

  const iv = setInterval(() => {
    secs--;
    if (cd) cd.textContent = secs;
    if (label) label.textContent = `Try Again (${secs}s)`;
    if (secs <= 0) {
      clearInterval(iv);
      btn.disabled = false;
      btn.style.opacity = '1';
      btn.style.cursor = 'pointer';
      if (label) label.textContent = 'Try Again';
    }
  }, 1000);
</script>
@endif
@endsection
