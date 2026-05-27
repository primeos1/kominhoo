@extends('layouts.app')
@section('title', 'FAQ — Kominhoo Beauty')

@section('content')
<section class="section" style="background:var(--cream);min-height:70vh">
  <div class="container" style="max-width:860px">
    <div class="section-header centered" style="margin-bottom:36px">
      <div class="section-eyebrow"><span class="dot"></span> Support</div>
      <h1 class="display-sm section-title">Frequently Asked Questions</h1>
      <p class="section-desc">Everything customers usually ask us, all in one place.</p>
    </div>

    <div style="display:flex;flex-direction:column;gap:14px">
      @foreach($faqItems as $item)
        <details style="background:#fff;border:1px solid var(--border);border-radius:18px;padding:20px 22px">
          <summary style="cursor:pointer;font-weight:700;list-style:none">{{ $item['question'] ?? '' }}</summary>
          <div style="margin-top:12px;color:var(--text-secondary);line-height:1.7">{{ $item['answer'] ?? '' }}</div>
        </details>
      @endforeach
    </div>
  </div>
</section>
@endsection
