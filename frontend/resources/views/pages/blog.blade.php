@extends('layouts.app')
@section('title', 'Blog — Kominhoo Beauty')

@section('head')
@php
  use Illuminate\Support\Str;
@endphp
<style>
.blog-page {
  font-family: 'DM Sans', system-ui, sans-serif;
  --rose:        #893941;
  --rose-dark:   #6B2A30;
  --blush:       #CB7885;
  --blush-pale:  #F6EEEF;
  --lime:        #D4D994;
  --cream:       #FAF6F3;
  --off-white:   #F5F0EC;
  --border:      #EDDCD8;
  --text:        #1C1416;
  --text-sec:    #6B5450;
}
.blog-page h1,
.blog-page h2,
.blog-page h3 { font-family: 'DM Sans', system-ui, sans-serif; }

.blog-hero {
  position: relative;
  background: radial-gradient(1200px 500px at 30% -10%, rgba(212,217,148,.25), transparent 55%),
              radial-gradient(900px 520px at 85% 10%, rgba(203,120,133,.26), transparent 55%),
              linear-gradient(180deg, #6B2A30 0%, #4B1E23 100%);
  color:#fff;
  padding: 72px clamp(20px,5vw,80px) 44px;
  overflow:hidden;
}
.blog-hero-inner { max-width: 1200px; margin: 0 auto; display:grid; grid-template-columns: 1.1fr .9fr; gap: 28px; align-items:end; }
.blog-hero-eyebrow {
  display:inline-flex; align-items:center; gap:10px;
  font-size:.78rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
  color: var(--lime);
}
.blog-hero h1 { font-size: clamp(2.1rem, 4.6vw, 3.3rem); line-height:1.08; margin:14px 0 10px; }
.blog-hero p { color: rgba(255,255,255,.72); line-height:1.7; max-width: 560px; margin:0; }

.blog-search {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.14);
  border-radius: 16px;
  padding: 14px;
  backdrop-filter: blur(8px);
}
.blog-search-row { display:flex; gap:10px; align-items:center; }
.blog-search input {
  flex:1;
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.16);
  color: #fff;
  padding: 11px 12px;
  border-radius: 12px;
  outline: none;
}
.blog-search input::placeholder { color: rgba(255,255,255,.6); }
.blog-search button {
  background: var(--lime);
  color: #1C1416;
  border: none;
  padding: 11px 14px;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 700;
}
.blog-search-meta { font-size:.78rem; margin-top:10px; color: rgba(255,255,255,.65); line-height:1.5; }

.blog-filters {
  background: var(--off-white);
  border-bottom: 1px solid var(--border);
  padding: 14px clamp(20px,5vw,80px);
}
.blog-filters-inner { max-width: 1200px; margin: 0 auto; display:flex; gap:10px; align-items:center; justify-content:space-between; flex-wrap:wrap; }
.blog-tag-row { display:flex; flex-wrap:wrap; gap:10px; }
.blog-tag {
  display:inline-flex; align-items:center; gap:8px;
  padding: 7px 16px;
  border-radius: 999px;
  border: 1.5px solid var(--border);
  background: #fff;
  color: var(--text-sec);
  font-size: .83rem;
  font-weight: 600;
  text-decoration:none;
  transition: all .18s;
}
.blog-tag:hover,
.blog-tag.active { background: var(--rose-dark); border-color: var(--rose-dark); color:#fff; }
.blog-clear { font-size:.83rem; color: var(--text-sec); text-decoration:none; font-weight:600; }
.blog-clear:hover { text-decoration:underline; }

.blog-section { background: var(--cream); padding: 44px clamp(20px,5vw,80px) 72px; }
.blog-wrap { max-width: 1200px; margin: 0 auto; }
.blog-grid { display:grid; grid-template-columns: repeat(3, 1fr); gap: 22px; margin-top: 22px; }

.blog-featured {
  display:grid; grid-template-columns: .95fr 1.05fr;
  gap:0; border-radius: 22px; overflow:hidden;
  background:#fff; border:1px solid var(--border);
  box-shadow: 0 10px 40px rgba(107,42,48,.08);
  text-decoration:none; color:inherit;
  transition: transform .18s, box-shadow .18s;
}
.blog-featured:hover { box-shadow: 0 18px 60px rgba(107,42,48,.14); transform: translateY(-2px); }
.blog-featured-media {
  height: 260px;
  background: linear-gradient(135deg, rgba(107,42,48,.18), rgba(212,217,148,.22));
  position:relative;
}
.blog-featured-media img { width:100%; height:100%; object-fit:cover; display:block; }
.blog-featured-media::after { content:''; position:absolute; inset:0; background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,.18) 100%); }
.blog-featured-body { padding: 28px 28px; display:flex; flex-direction:column; justify-content:center; }
.blog-kicker { display:inline-flex; align-items:center; gap:8px; font-size:.75rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color: var(--rose-dark); }
.blog-title { font-family:'DM Sans', system-ui, sans-serif; font-size: 1.55rem; line-height:1.2; margin:12px 0 10px; color: var(--text); }
.blog-excerpt { color: rgba(28,20,22,.68); line-height:1.7; margin:0; }
.blog-meta { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-top: 16px; font-size:.82rem; color: rgba(28,20,22,.55); }
.blog-dot { opacity:.45; }
.blog-cta { margin-top: 16px; display:inline-flex; align-items:center; gap:8px; font-weight:700; color: var(--rose-dark); }

.blog-card {
  background:#fff; border:1px solid var(--border); border-radius: 18px; overflow:hidden;
  text-decoration:none; color:inherit;
  box-shadow: 0 6px 24px rgba(107,42,48,.06);
  transition: transform .18s, box-shadow .18s;
  display:flex; flex-direction:column;
}
.blog-card:hover { transform: translateY(-2px); box-shadow: 0 14px 44px rgba(107,42,48,.11); }
.blog-card-media { height: 190px; background: linear-gradient(135deg, rgba(203,120,133,.20), rgba(212,217,148,.22)); position:relative; }
.blog-card-media img { width:100%; height:100%; object-fit:cover; display:block; }
.blog-card-body { padding: 18px 18px 20px; display:flex; flex-direction:column; gap:10px; flex:1; }
.blog-card-title { font-family:'DM Sans', system-ui, sans-serif; font-size:1.12rem; line-height:1.25; color: var(--text); }
.blog-card-excerpt { font-size:.92rem; color: rgba(28,20,22,.68); line-height:1.65; margin:0; }
.blog-card-footer { margin-top:auto; display:flex; gap:10px; align-items:center; flex-wrap:wrap; font-size:.78rem; color: rgba(28,20,22,.55); }

.blog-empty {
  background:#fff; border:1px solid var(--border); border-radius: 18px;
  padding: 22px; color: rgba(28,20,22,.72);
}

.blog-pager { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top: 26px; }
.blog-pager a {
  text-decoration:none;
  padding: 9px 14px;
  border-radius: 999px;
  border: 1.5px solid var(--border);
  background:#fff;
  color: var(--text-sec);
  font-weight:700;
  font-size:.85rem;
}
.blog-pager a:hover { border-color: var(--rose-dark); color: var(--rose-dark); }
.blog-pager .disabled { opacity:.45; pointer-events:none; }
.blog-page-num { font-size:.85rem; color: rgba(28,20,22,.65); }

.blog-newsletter {
  background: linear-gradient(135deg, rgba(203,120,133,.12), rgba(212,217,148,.16));
  border-top: 1px solid var(--border);
  padding: 48px clamp(20px,5vw,80px);
}
.blog-newsletter-inner { max-width: 900px; margin: 0 auto; text-align:center; }
.blog-newsletter h2 { margin:0 0 10px; font-size: 2rem; }
.blog-newsletter p { margin:0 auto 18px; max-width: 560px; color: rgba(28,20,22,.68); line-height:1.7; }
.blog-newsletter-form { display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }
.blog-newsletter-form input {
  padding: 12px 14px;
  border-radius: 12px;
  border: 1.5px solid var(--border);
  background:#fff;
  min-width: 260px;
  outline:none;
}
.blog-newsletter-form button {
  padding: 12px 14px;
  border-radius: 12px;
  border: 1px solid var(--rose-dark);
  background: var(--rose-dark);
  color:#fff;
  font-weight: 700;
  cursor:pointer;
}

@media (max-width: 980px) {
  .blog-hero-inner { grid-template-columns: 1fr; }
  .blog-featured { grid-template-columns: 1fr; }
  .blog-featured-media { height: 200px; }
  .blog-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .blog-grid { grid-template-columns: 1fr; }
  .blog-tag-row { overflow-x: auto; flex-wrap: nowrap; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
  .blog-tag-row::-webkit-scrollbar { display: none; }
  .blog-filters-inner { gap: 6px; }
}
@media (max-width: 480px) {
  .blog-newsletter-form { flex-direction: column; align-items: stretch; }
  .blog-newsletter-form input { min-width: 0; width: 100%; }
  .blog-newsletter-form button { width: 100%; }
  .blog-hero { padding: 48px 20px 32px; }
  .blog-newsletter { padding: 36px 20px; }
}
</style>
@endsection

@section('content')
<div class="blog-page">
  <header class="blog-hero">
    <div class="blog-hero-inner">
      <div>
        <div class="blog-hero-eyebrow">
          <span style="width:7px;height:7px;border-radius:50%;background:var(--lime);display:inline-block"></span>
          Kominhoo Journal
        </div>
        <h1>Skincare wisdom, honestly told.</h1>
        <p>Tips, ingredient deep-dives, product reviews, and K‑beauty insights tailored for Nigerian skin and climate.</p>
      </div>

      <div class="blog-search">
        <form method="GET" action="{{ route('blog') }}">
          @if($activeTag)
            <input type="hidden" name="tag" value="{{ $activeTag }}" />
          @endif
          <div class="blog-search-row">
            <input name="q" value="{{ $query }}" placeholder="Search posts…" />
            <button type="submit">Search</button>
          </div>
          <div class="blog-search-meta">
            @if($activeTag)
              Filtering by <strong>{{ $activeTag }}</strong>.
              <a class="blog-clear" href="{{ route('blog', array_filter(['q' => $query ?: null])) }}" style="color:rgba(255,255,255,.8);">Clear tag</a>
            @else
              Browse by topic, or search by title.
            @endif
          </div>
        </form>
      </div>
    </div>
  </header>

  <div class="blog-filters">
    <div class="blog-filters-inner">
      <div class="blog-tag-row">
        <a class="blog-tag {{ $activeTag === '' ? 'active' : '' }}" href="{{ route('blog', array_filter(['q' => $query ?: null])) }}">All</a>
        @foreach($tags as $tag)
          <a class="blog-tag {{ $activeTag === $tag ? 'active' : '' }}" href="{{ route('blog', array_filter(['tag' => $tag, 'q' => $query ?: null])) }}">{{ $tag }}</a>
        @endforeach
      </div>
      @if($activeTag || $query)
        <a class="blog-clear" href="{{ route('blog') }}">Reset</a>
      @endif
    </div>
  </div>

  <section class="blog-section">
    <div class="blog-wrap">
      @if($featured)
        @php
          $featuredCover = null;
          if ($featured->cover_image_path) {
            $featuredCover = Str::startsWith($featured->cover_image_path, ['http://', 'https://'])
              ? $featured->cover_image_path
              : asset('storage/' . ltrim($featured->cover_image_path, '/'));
          }
        @endphp
        <a class="blog-featured" href="{{ route('blog.show', $featured->slug) }}">
          <div class="blog-featured-media">
            @if($featuredCover)
              <img src="{{ $featuredCover }}" alt="{{ $featured->title }}">
            @endif
          </div>
          <div class="blog-featured-body">
            <div class="blog-kicker">Featured • {{ $featured->tag ?: 'Journal' }}</div>
            <div class="blog-title">{{ $featured->title }}</div>
            <p class="blog-excerpt">{{ $featured->excerpt ?: Str::limit(strip_tags($featured->content ?? ''), 140) }}</p>
            <div class="blog-meta">
              <span>{{ $featured->author ?: 'Kominhoo Team' }}</span>
              <span class="blog-dot">•</span>
              <span>{{ optional($featured->published_at)->format('M d, Y') }}</span>
              @if($featured->reading_time)
                <span class="blog-dot">•</span>
                <span>{{ $featured->reading_time }}</span>
              @endif
            </div>
            <div class="blog-cta">Read article <span aria-hidden="true">→</span></div>
          </div>
        </a>
      @endif

      @if(($posts->count() === 0) && !$featured)
        <div class="blog-empty">
          <strong>No posts found.</strong>
          <div style="margin-top:6px;line-height:1.6;">Try a different search, or reset the filters.</div>
        </div>
      @endif

      @if($posts->count() > 0)
        <div class="blog-grid">
          @foreach($posts as $post)
            @php
              $cover = null;
              if ($post->cover_image_path) {
                $cover = Str::startsWith($post->cover_image_path, ['http://', 'https://'])
                  ? $post->cover_image_path
                  : asset('storage/' . ltrim($post->cover_image_path, '/'));
              }
            @endphp
            <a class="blog-card" href="{{ route('blog.show', $post->slug) }}">
              <div class="blog-card-media">
                @if($cover)
                  <img src="{{ $cover }}" alt="{{ $post->title }}">
                @endif
              </div>
              <div class="blog-card-body">
                <div class="blog-kicker">{{ $post->tag ?: 'Journal' }}</div>
                <div class="blog-card-title">{{ $post->title }}</div>
                <p class="blog-card-excerpt">{{ $post->excerpt ?: Str::limit(strip_tags($post->content ?? ''), 120) }}</p>
                <div class="blog-card-footer">
                  <span>{{ $post->author ?: 'Kominhoo Team' }}</span>
                  <span class="blog-dot">•</span>
                  <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                  @if($post->reading_time)
                    <span class="blog-dot">•</span>
                    <span>{{ $post->reading_time }}</span>
                  @endif
                </div>
              </div>
            </a>
          @endforeach
        </div>

        <div class="blog-pager">
          <a class="{{ $posts->onFirstPage() ? 'disabled' : '' }}" href="{{ $posts->previousPageUrl() ?: '#' }}">← Prev</a>
          <div class="blog-page-num">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</div>
          <a class="{{ $posts->hasMorePages() ? '' : 'disabled' }}" href="{{ $posts->nextPageUrl() ?: '#' }}">Next →</a>
        </div>
      @endif
    </div>
  </section>

  <section class="blog-newsletter">
    <div class="blog-newsletter-inner">
      <h2>Get new posts in your inbox.</h2>
      <p>Weekly skincare tips, ingredient spotlights, and exclusive offers — no spam, ever.</p>
      <form class="blog-newsletter-form" onsubmit="return false">
        <input type="email" placeholder="Your email address" />
        <button type="submit">Subscribe</button>
      </form>
    </div>
  </section>
</div>
@endsection
