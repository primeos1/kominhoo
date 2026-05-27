@extends('layouts.app')
@section('title', $title . ' — Kominhoo Beauty')

@section('content')
<section class="section" style="background:#fff;min-height:70vh">
  <div class="container" style="max-width:860px">
    <div class="section-header centered" style="margin-bottom:32px">
      <div class="section-eyebrow"><span class="dot"></span> Store Policy</div>
      <h1 class="display-sm section-title">{{ $title }}</h1>
    </div>

    <div style="background:var(--cream);border-radius:24px;padding:28px 30px;line-height:1.8;color:var(--text-secondary);white-space:pre-line">
      {{ $body }}
    </div>
  </div>
</section>
@endsection
