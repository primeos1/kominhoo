@extends('layouts.app')
@section('title', ($post->title ?? 'Blog') . ' — Kominhoo Beauty')

@section('head')
@php
  use Illuminate\Support\Str;
  $cover = null;
  if (!empty($post->cover_image_path)) {
    $cover = Str::startsWith($post->cover_image_path, ['http://', 'https://'])
      ? $post->cover_image_path
      : asset('storage/' . ltrim($post->cover_image_path, '/'));
  }
@endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,300;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700&display=swap" rel="stylesheet">
<style>
.post-page{
  font-family:'DM Sans',system-ui,sans-serif;
  --rose-dark:#6B2A30;
  --cream:#FAF6F3;
  --border:#EDDCD8;
  --text:#1C1416;
}
.post-page h1,.post-page h2,.post-page h3{font-family:'DM Sans',system-ui,sans-serif}
.post-hero{
  background: #231013;
  color:#fff;
  position:relative;
  overflow:hidden;
}
.post-hero-media{
  position:absolute; inset:0;
  background: radial-gradient(1200px 500px at 25% -10%, rgba(212,217,148,.22), transparent 55%),
              radial-gradient(900px 520px at 85% 10%, rgba(203,120,133,.22), transparent 55%),
              linear-gradient(180deg, rgba(107,42,48,.80) 0%, rgba(35,16,19,.92) 100%);
}
.post-hero-media img{width:100%;height:100%;object-fit:cover;opacity:.35;filter:saturate(1.05) contrast(1.05)}
.post-hero-inner{position:relative;max-width:980px;margin:0 auto;padding:70px 20px 38px}
.post-back{color:rgba(255,255,255,.78);text-decoration:none;font-weight:700;font-size:.9rem}
.post-back:hover{text-decoration:underline}
.post-kicker{margin-top:18px;font-size:.78rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.78);font-weight:700}
.post-title{font-size:clamp(2.1rem,4.7vw,3.1rem);line-height:1.08;margin:12px 0 10px}
.post-meta{display:flex;gap:10px;flex-wrap:wrap;align-items:center;color:rgba(255,255,255,.75);font-size:.9rem}
.post-dot{opacity:.5}

.post-body-wrap{background:var(--cream);padding:34px 20px 70px}
.post-body{max-width:820px;margin:0 auto;background:#fff;border:1px solid var(--border);border-radius:18px;box-shadow:0 10px 40px rgba(0,0,0,.05);padding:30px 26px;overflow:hidden}
.post-body .prose{color:rgba(28,20,22,.82);line-height:1.85;font-size:1.02rem;overflow-wrap:break-word;word-break:break-word}
.post-body .prose img,.post-body .prose video,.post-body .prose iframe,.post-body .prose table{max-width:100%;height:auto}
.post-body .prose h2{margin:26px 0 10px;font-size:1.5rem;color:var(--text)}
.post-body .prose h3{margin:22px 0 8px;font-size:1.25rem;color:var(--text)}
.post-body .prose p{margin:0 0 14px}
.post-body .prose ul{margin:0 0 14px 18px}
.post-body .prose a{color:var(--rose-dark);font-weight:700}
.post-body .prose blockquote{margin:18px 0;padding:12px 14px;border-left:4px solid var(--rose-dark);background:rgba(107,42,48,.06);border-radius:10px}
.post-body .empty{color:rgba(28,20,22,.65)}

.related{max-width:980px;margin:28px auto 0}
.related h2{font-size:1.6rem;margin:0 0 12px}
.rel-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.rel-card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;text-decoration:none;color:inherit;box-shadow:0 6px 22px rgba(0,0,0,.04);transition:transform .18s, box-shadow .18s}
.rel-card:hover{transform:translateY(-2px);box-shadow:0 14px 42px rgba(0,0,0,.08)}
.rel-media{height:140px;background:linear-gradient(135deg, rgba(203,120,133,.20), rgba(212,217,148,.22))}
.rel-media img{width:100%;height:100%;object-fit:cover;display:block}
.rel-body{padding:14px 14px 16px}
.rel-tag{font-size:.72rem;letter-spacing:.11em;text-transform:uppercase;font-weight:700;color:var(--rose-dark)}
.rel-title{margin-top:8px;font-family:'DM Sans',system-ui,sans-serif;font-size:1.06rem;line-height:1.25}
.rel-meta{margin-top:10px;font-size:.8rem;color:rgba(28,20,22,.58)}
@media(max-width:980px){.rel-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.rel-grid{grid-template-columns:1fr}.post-body{padding:22px 18px}.post-hero-inner{padding:48px 16px 28px}.related{padding:0 16px}.post-body-wrap{padding:24px 16px 48px}}
</style>
@endsection

@section('content')
<div class="post-page">
  <header class="post-hero">
    <div class="post-hero-media">
      @if($cover)
        <img src="{{ $cover }}" alt="{{ $post->title }}">
      @endif
    </div>
    <div class="post-hero-inner">
      <a class="post-back" href="{{ route('blog') }}">← Back to Blog</a>
      <div class="post-kicker">{{ $post->tag ?: 'Journal' }}</div>
      <h1 class="post-title">{{ $post->title }}</h1>
      <div class="post-meta">
        <span>{{ $post->author ?: 'Kominhoo Team' }}</span>
        <span class="post-dot">•</span>
        <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
        @if($post->reading_time)
          <span class="post-dot">•</span>
          <span>{{ $post->reading_time }}</span>
        @endif
      </div>
    </div>
  </header>

  <main class="post-body-wrap">
    <div class="post-body">
      <div class="prose">
        @if(!empty($post->content))
          {!! $post->content !!}
        @elseif(!empty($post->excerpt))
          <p>{{ $post->excerpt }}</p>
        @else
          <p class="empty">This post doesn’t have content yet.</p>
        @endif
      </div>
    </div>

    @if(isset($related) && $related->count() > 0)
      <div class="related">
        <h2>Related posts</h2>
        <div class="rel-grid">
          @foreach($related as $rp)
            @php
              $rpCover = null;
              if (!empty($rp->cover_image_path)) {
                $rpCover = Str::startsWith($rp->cover_image_path, ['http://', 'https://'])
                  ? $rp->cover_image_path
                  : asset('storage/' . ltrim($rp->cover_image_path, '/'));
              }
            @endphp
            <a class="rel-card" href="{{ route('blog.show', $rp->slug) }}">
              <div class="rel-media">
                @if($rpCover)
                  <img src="{{ $rpCover }}" alt="{{ $rp->title }}">
                @endif
              </div>
              <div class="rel-body">
                <div class="rel-tag">{{ $rp->tag ?: 'Journal' }}</div>
                <div class="rel-title">{{ $rp->title }}</div>
                <div class="rel-meta">{{ optional($rp->published_at)->format('M d, Y') }}</div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif
  </main>
</div>
@endsection
