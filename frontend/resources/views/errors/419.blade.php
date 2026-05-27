@extends('errors.layout')
@section('title', '419 — Session Expired')
@section('content')

<span class="e-watermark">419</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-amber">
    <span class="e-particle" style="--tx:12px;--ty:-16px;--dur:3s;--delay:0s;top:6px;right:8px;background:var(--amber)"></span>
    <span class="e-particle" style="--tx:-10px;--ty:-12px;--dur:3.8s;--delay:.5s;top:10px;left:6px;background:var(--amber);width:5px;height:5px"></span>
    <span class="e-particle" style="--tx:8px;--ty:12px;--dur:4s;--delay:1s;bottom:8px;right:10px;background:var(--amber);width:4px;height:4px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <path d="M12 8h24l-12 14z" stroke-width="2"/>
      <path d="M12 40h24L24 26z" stroke-width="2"/>
      <rect x="10" y="6" width="28" height="4" rx="2" stroke-width="1.8"/>
      <rect x="10" y="38" width="28" height="4" rx="2" stroke-width="1.8"/>
      <circle cx="24" cy="25" r="1.5" fill="currentColor" stroke="none"/>
    </svg>
  </div>
</div>

<span class="e-status">
  <span class="e-status-dot e-status-dot-amber"></span>
  Session token expired
</span>

<h1 class="e-headline">Your Session Took a Break</h1>
<p class="e-body">Your security token has expired — this usually happens after a long time away. Refreshing the page in <strong id="countdown">5</strong> seconds…</p>

<div class="e-actions">
  <button onclick="window.location.reload()" class="e-btn e-btn-primary" id="refresh-btn">
    <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    <span id="refresh-label">Refresh Page</span>
  </button>
  <a href="{{ route('home') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Go Home
  </a>
</div>

<div class="e-divider"></div>

<p style="font-size:.8125rem;color:var(--gray-400);line-height:1.7;text-align:center">
  If refreshing doesn't work, try <a href="{{ route('login') }}" style="color:var(--gray-600);font-weight:600;text-decoration:none;border-bottom:1px solid var(--gray-300)">signing in again</a>.
  Your cart and wishlist are safely saved.
</p>

@endsection

@section('scripts')
<script>
  let secs = 5;
  const label = document.getElementById('refresh-label');
  const cd = document.getElementById('countdown');
  const iv = setInterval(() => {
    secs--;
    if (label) label.textContent = `Refresh Page (${secs}s)`;
    if (cd) cd.textContent = secs;
    if (secs <= 0) { clearInterval(iv); window.location.reload(); }
  }, 1000);
</script>
@endsection
