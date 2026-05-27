@extends('errors.layout')
@section('title', '404 — Page Not Found')
@section('content')

<span class="e-watermark">404</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-lime">
    <span class="e-particle" style="--tx:14px;--ty:-16px;--dur:3.2s;--delay:0s;top:4px;right:8px"></span>
    <span class="e-particle" style="--tx:-10px;--ty:-12px;--dur:4s;--delay:.6s;top:12px;left:4px"></span>
    <span class="e-particle" style="--tx:8px;--ty:14px;--dur:3.6s;--delay:1.2s;bottom:6px;right:12px"></span>
    <span class="e-particle" style="--tx:-14px;--ty:10px;--dur:4.4s;--delay:.3s;bottom:10px;left:6px;width:5px;height:5px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <circle cx="20" cy="20" r="11" stroke-width="2"/>
      <path d="M17.5 17.5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5c0 1.5-2.5 2.5-2.5 4" stroke-width="2"/>
      <circle cx="20" cy="24.5" r=".8" fill="currentColor" stroke="none"/>
      <line x1="28.5" y1="28.5" x2="35" y2="35" stroke-width="2.5"/>
    </svg>
  </div>
</div>

<span class="e-eyebrow"><span class="e-eyebrow-dot"></span> Error 404 <span class="e-eyebrow-dot"></span></span>

<h1 class="e-headline">Lost in the Beauty Universe</h1>
<p class="e-body">The page you're looking for has drifted away. You'll be taken home in <strong id="countdown">5</strong> seconds, or choose where to go below.</p>

<div class="e-actions">
  <a href="{{ route('shop') }}" class="e-btn e-btn-primary">
    <svg viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
    Explore the Shop
  </a>
  <a href="{{ route('home') }}" class="e-btn e-btn-outline">
    <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Go Home Now
  </a>
</div>

<div class="e-divider"></div>

<div style="display:flex;align-items:center;justify-content:center;gap:20px;flex-wrap:wrap">
  <a href="{{ route('shop', ['category' => 'serum']) }}" style="font-size:.8125rem;font-weight:600;color:var(--gray-500);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--dark)'" onmouseout="this.style.color='var(--gray-500)'">Serums →</a>
  <a href="{{ route('shop', ['category' => 'moisturizer']) }}" style="font-size:.8125rem;font-weight:600;color:var(--gray-500);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--dark)'" onmouseout="this.style.color='var(--gray-500)'">Moisturizers →</a>
  <a href="{{ route('quiz') }}" style="font-size:.8125rem;font-weight:600;color:var(--gray-500);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--dark)'" onmouseout="this.style.color='var(--gray-500)'">Take the Quiz →</a>
  <a href="{{ route('faq') }}" style="font-size:.8125rem;font-weight:600;color:var(--gray-500);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--dark)'" onmouseout="this.style.color='var(--gray-500)'">FAQ →</a>
</div>

@endsection

@section('scripts')
<script>
  // Redirect to home after 5 seconds
  const homeUrl = "{{ route('home') }}";
  const el = document.getElementById('countdown');
  let secs = 5;
  const iv = setInterval(() => {
    secs--;
    if (el) el.textContent = secs;
    if (secs <= 0) { clearInterval(iv); window.location.href = homeUrl; }
  }, 1000);
</script>
@endsection
