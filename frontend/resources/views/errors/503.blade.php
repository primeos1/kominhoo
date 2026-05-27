@extends('errors.layout')
@section('title', 'We\'ll Be Right Back')
@section('content')

<span class="e-watermark" style="-webkit-text-stroke-color:var(--gray-200)">503</span>

<div class="e-illo-wrap">
  <div class="e-illo e-illo-lime">
    <span class="e-particle" style="--tx:18px;--ty:-20px;--dur:3s;--delay:0s;top:0;right:4px;width:6px;height:6px"></span>
    <span class="e-particle" style="--tx:-14px;--ty:-14px;--dur:4s;--delay:.8s;top:8px;left:2px;width:4px;height:4px"></span>
    <span class="e-particle" style="--tx:12px;--ty:18px;--dur:3.5s;--delay:.4s;bottom:4px;right:8px;width:5px;height:5px"></span>
    <span class="e-particle" style="--tx:-18px;--ty:12px;--dur:4.5s;--delay:1.2s;bottom:2px;left:4px;width:4px;height:4px"></span>
    <svg viewBox="0 0 48 48" aria-hidden="true">
      <line x1="32" y1="32" x2="14" y2="14" stroke-width="2.5"/>
      <rect x="30" y="28" width="6" height="6" rx="1" transform="rotate(45 33 31)" stroke-width="1.8"/>
      <path d="M12 8l1.2 2.8L16 12l-2.8 1.2L12 16l-1.2-2.8L8 12l2.8-1.2z" stroke-width="1.4"/>
      <path d="M36 8l.8 1.8L38.6 10l-1.8.8L36 12.6l-.8-1.8L33.4 10l1.8-.8z" stroke-width="1.2"/>
      <path d="M10 34l.7 1.6 1.6.7-1.6.7-.7 1.6-.7-1.6-1.6-.7 1.6-.7z" stroke-width="1.2"/>
    </svg>
  </div>
</div>

<span class="e-status">
  <span class="e-status-dot e-status-dot-amber"></span>
  Scheduled maintenance in progress
</span>

<h1 class="e-headline">We're Getting a Glow-Up</h1>

@if(!empty($exception) && $exception->getMessage())
  <p class="e-body">{{ $exception->getMessage() }}</p>
@else
  <p class="e-body">Kominhoo is down for a quick refresh — like a great sheet mask, some things just take a little time. We'll be back better than ever soon.</p>
@endif

<div class="e-actions">
  <a href="{{ route('home') }}" class="e-btn e-btn-primary">
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    Check Back Soon
  </a>
</div>

<div class="e-divider"></div>

<p style="font-size:.8125rem;color:var(--gray-400);line-height:1.7;text-align:center">
  Questions? <a href="mailto:hello@kominhoo.com" style="color:var(--gray-600);font-weight:600;text-decoration:none;border-bottom:1px solid var(--gray-300)">hello@kominhoo.com</a>
</p>

@endsection

@section('scripts')
<script>
  // Auto-refresh every 60 seconds on maintenance page
  setTimeout(() => window.location.reload(), 60000);
</script>
@endsection
