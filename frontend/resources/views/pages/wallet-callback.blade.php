@extends('layouts.app')
@section('title', 'Payment Processing — Kominhoo Beauty')

@section('content')
<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;padding:40px 24px">
  <div style="text-align:center;max-width:440px">

    <div id="state-verifying">
      <div style="font-size:3rem;margin-bottom:20px;animation:spin 1.5s linear infinite;display:inline-block">⏳</div>
      <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:8px">Verifying your payment…</h2>
      <p style="color:var(--text-muted);font-size:.9rem;line-height:1.6">
        Please wait while we confirm your deposit with Paystack.<br>
        This usually takes a few seconds.
      </p>
      <div style="margin-top:24px">
        <a href="{{ url('/dashboard/wallet') }}" id="wallet-link"
           style="background:var(--black);color:var(--lime);padding:12px 28px;border-radius:var(--r-md);font-weight:700;font-size:.88rem;text-decoration:none;display:inline-block">
          View My Wallet →
        </a>
      </div>
      <p style="font-size:.75rem;color:var(--text-muted);margin-top:16px">
        Your balance will update automatically once the payment is confirmed.<br>
        <strong>Do not refresh or close this page.</strong>
      </p>
    </div>

  </div>
</div>

<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
</style>

<script>
// Auto-redirect to wallet after 4s — webhook will have processed by then in most cases
setTimeout(() => {
  window.location.href = '{{ url('/dashboard/wallet') }}?ref={{ $reference ?? "" }}';
}, 4000);
</script>
@endsection
