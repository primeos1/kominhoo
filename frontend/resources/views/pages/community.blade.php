@extends('layouts.app')
@section('title', 'Kominhoo Community — Real Skin Stories & Glow Gallery')

@section('head')
<style>
/* ── Community-page overrides ─────────────────────────────────── */
:root {
  --lime-pale: rgba(212,217,148,0.12);
  --r-pill: 100px;
  --r-xl: 28px;
  --t-fast: all 0.2s ease;
  --nav-h: 76px;
}

/* Hero */
.c-hero {
  position: relative;
  min-height: 620px;
  background: var(--rose-dark);
  overflow: hidden;
  display: flex;
  align-items: center;
}
.c-hero-collage {
  position: absolute;
  inset: 0;
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 2px;
  opacity: 0.22;
}
.c-hero-collage img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(0.2); }
.c-hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(125deg, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.7) 45%, rgba(0,0,0,0.5) 75%, rgba(0,0,0,0.85) 100%);
}
.c-hero-photo {
  position: absolute;
  border-radius: 24px;
  overflow: hidden;
  border: 2px solid rgba(255,255,255,0.2);
  box-shadow: 0 30px 50px rgba(0,0,0,0.5);
  z-index: 2;
  animation: hero-float var(--dur, 7s) ease-in-out infinite var(--delay, 0s);
}
@keyframes hero-float {
  0%,100% { transform: translateY(0) rotate(var(--rot,0deg)); }
  50% { transform: translateY(-14px) rotate(var(--rot,0deg)); }
}
.c-hero-content {
  position: relative; z-index: 3;
  max-width: 1280px; margin: 0 auto;
  padding: 80px 24px;
  width: 100%;
}
.c-hero-eyebrow {
  display: inline-flex; align-items: center; gap: 12px;
  font-size: 0.7rem; font-weight: 700;
  letter-spacing: 0.2em; text-transform: uppercase;
  color: var(--lime); margin-bottom: 22px;
}
.c-hero-eyebrow::before { content: ''; width: 32px; height: 2px; background: var(--lime); }
.c-hero-title {
  font-family: var(--font-display);
  font-size: clamp(3rem, 7vw, 6rem);
  color: #fff; line-height: 1.02;
  margin-bottom: 24px;
}
.c-hero-title em { color: var(--lime); font-style: italic; }
.c-hero-desc { font-size: 1.05rem; color: rgba(255,255,255,0.55); max-width: 480px; margin-bottom: 42px; line-height: 1.7; }
.c-hero-stats { display: flex; gap: 48px; flex-wrap: wrap; margin-bottom: 42px; }
.c-hero-stat-num { font-family: var(--font-display); font-size: 2.2rem; font-weight: 400; color: #fff; }
.c-hero-stat-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.4); }
.live-dot {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: 0.75rem; font-weight: 600;
  color: rgba(255,255,255,0.6);
}
.live-dot::before {
  content: ''; width: 8px; height: 8px; border-radius: 50%;
  background: var(--lime);
  box-shadow: 0 0 0 0 rgba(212,217,148,0.6);
  animation: pulse-live 2s ease-out infinite;
}
@keyframes pulse-live {
  0% { box-shadow: 0 0 0 0 rgba(212,217,148,0.6); }
  70% { box-shadow: 0 0 0 8px rgba(212,217,148,0); }
  100% { box-shadow: 0 0 0 0 rgba(212,217,148,0); }
}

/* Filters bar */
.c-filters {
  position: sticky;
  top: var(--nav-h);
  z-index: 500;
  background: rgba(250,246,243,0.96);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid #EFF0F2;
}
.c-filters-inner {
  max-width: 1280px; margin: 0 auto;
  padding: 14px 24px;
  display: flex; align-items: center;
  justify-content: space-between; gap: 16px; flex-wrap: nowrap;
}
.filter-pills { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
.filter-pills::-webkit-scrollbar { display: none; }
.fpill {
  padding: 7px 18px; border-radius: var(--r-pill);
  font-size: 0.78rem; font-weight: 600;
  border: 1.5px solid #EFF0F2;
  background: #fff; color: #4B5563;
  cursor: pointer; transition: var(--t-fast);
}
.fpill.active { background: var(--rose-dark); color: #fff; border-color: var(--rose-dark); }
.c-post-count { font-size: 0.78rem; font-weight: 500; color: #6B7280; }
.c-sort {
  padding: 7px 32px 7px 14px; border-radius: var(--r-pill);
  border: 1.5px solid #EFF0F2; font-weight: 600; background: #fff;
  cursor: pointer;
}

/* Featured post */
.featured-wrap { max-width: 1280px; margin: 0 auto; padding: 48px 24px 0; }
.featured-card {
  background: var(--rose-dark); border-radius: var(--r-xl);
  overflow: hidden; display: grid; grid-template-columns: 1fr 1fr;
  min-height: 460px; box-shadow: 0 32px 64px rgba(0,0,0,0.2);
}
.featured-img-side { position: relative; overflow: hidden; }
.featured-img-side img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
.featured-card:hover .featured-img-side img { transform: scale(1.05); }
.featured-pill {
  position: absolute; top: 20px; left: 20px;
  background: var(--lime); color: var(--black);
  font-size: 0.65rem; font-weight: 700; padding: 5px 14px;
  border-radius: var(--r-pill);
}
.featured-body { padding: 48px 40px; display: flex; flex-direction: column; justify-content: center; }
.featured-ey { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.16em; color: var(--lime); margin-bottom: 20px; }
.featured-quote { font-family: var(--font-display); font-size: 1.6rem; color: #fff; line-height: 1.3; font-style: italic; margin-bottom: 24px; }
.tag-row, .prod-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
.ptag { font-size: 0.7rem; font-weight: 600; background: rgba(255,255,255,0.08); padding: 4px 12px; border-radius: var(--r-pill); color: rgba(255,255,255,0.7); cursor: pointer; }
.ptag.lime { color: var(--lime); background: rgba(212,217,148,0.12); }
.prod-pill { font-size: 0.7rem; background: rgba(255,255,255,0.05); padding: 5px 12px; border-radius: var(--r-pill); color: #ccc; cursor: pointer; }
.featured-user { display: flex; align-items: center; gap: 14px; margin-bottom: 26px; }
.uavatar { width: 48px; height: 48px; border-radius: 50%; display: grid; place-items: center; font-weight: 700; }
.paction { display: inline-flex; align-items: center; gap: 8px; margin-right: 20px; cursor: pointer; color: rgba(255,255,255,0.5); transition: 0.2s; background: none; border: none; font-size: 0.88rem; font-weight: 700; }
.paction.liked { color: #ff5c7c; }

/* Main layout */
.c-main { max-width: 1280px; margin: 40px auto; padding: 0 24px 80px; display: grid; grid-template-columns: 1fr 300px; gap: 40px; }

/* Masonry */
.masonry { columns: 3; column-gap: 22px; }
.mcard, .review-mcard { break-inside: avoid; margin-bottom: 22px; border-radius: 28px; background: #fff; box-shadow: 0 6px 14px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.05); transition: all 0.25s ease; overflow: hidden; cursor: pointer; }
.mcard:hover { transform: translateY(-6px); box-shadow: 0 24px 48px rgba(0,0,0,0.12); }
.mc-img { position: relative; overflow: hidden; }
.mc-img img { width: 100%; display: block; transition: transform 0.5s; }
.mcard:hover .mc-img img { transform: scale(1.03); }
.mc-overlay {
  position: absolute; inset: 0; background: rgba(0,0,0,0);
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.mcard:hover .mc-overlay { background: rgba(0,0,0,0.3); }
.mc-hover-btns { display: flex; gap: 12px; opacity: 0; transform: translateY(8px); transition: 0.2s; }
.mcard:hover .mc-hover-btns { opacity: 1; transform: translateY(0); }
.mc-btn { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.9); backdrop-filter: blur(6px); border: none; font-size: 1rem; cursor: pointer; display: grid; place-items: center; }
.mc-badge {
  position: absolute; top: 12px; left: 12px; font-size: 0.6rem; font-weight: 700; padding: 4px 12px; border-radius: 100px;
}
.mc-badge-transform { background: var(--lime); color: black; }
.mc-badge.featured { background: var(--lime); color: #0A0A0A; }
.mc-body { padding: 16px; }
.mc-user { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.mc-avatar { width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; font-weight: 700; font-size: 0.7rem; flex-shrink: 0; }
.mc-uname { font-weight: 700; font-size: .82rem; }
.mc-skin  { font-size: .7rem; color: #6B7280; }
.mc-time  { font-size: .7rem; color: #6B7280; margin-left: auto; }
.mc-caption { font-size: 0.82rem; color: #374151; line-height: 1.5; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 3; }
.mc-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 12px; }
.mc-tag { font-size: 0.68rem; font-weight: 600; color: var(--lime-dark); background: var(--lime-pale); padding: 2px 8px; border-radius: 20px; }
.mc-footer { display: flex; gap: 16px; padding-top: 8px; border-top: 1px solid #f0f0f0; }
.mc-stat { font-size: 0.75rem; font-weight: 600; color: #6c757d; cursor: pointer; display: flex; align-items: center; gap: 5px; background: none; border: none; }
.mc-stat.liked { color: #e63946; }

/* Before/After */
.ba-wrap { display: grid; grid-template-columns: 1fr 1fr; position: relative; }
.ba-side { position: relative; }
.ba-side img { width: 100%; height: 200px; object-fit: cover; }
.ba-label { position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.7); color: white; font-size: 0.6rem; padding: 2px 8px; border-radius: 20px; white-space: nowrap; }
.ba-rule { position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; background: white; transform: translateX(-50%); z-index: 2; }

/* Review cards */
.review-mcard { background: #0A0A0A; padding: 24px; border-radius: 28px; }
.rm-stars { color: var(--lime); font-size: 0.8rem; margin-bottom: 14px; }
.rm-quote { font-family: var(--font-display); font-size: 1rem; color: #fff; line-height: 1.45; font-style: italic; margin-bottom: 16px; }
.rm-prod { display: inline-block; background: rgba(212,217,148,0.12); color: var(--lime); padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; margin-top: 12px; }

/* Sidebar */
.c-sidebar { position: sticky; top: calc(var(--nav-h) + 40px); display: flex; flex-direction: column; gap: 24px; }
.scard { background: white; border-radius: 28px; padding: 24px; border: 1px solid #EFF0F2; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
.scard-title { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #6B7280; margin-bottom: 20px; display: flex; gap: 8px; align-items: center; }
.glower-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; cursor: pointer; }
.g-rank { font-weight: 700; font-size: 0.8rem; width: 24px; }
.g-rank.gold { color: #F5B042; }
.g-rank.silver { color: #9CA3AF; }
.g-rank.bronze { color: #CD7F32; }
.g-av { width: 38px; height: 38px; border-radius: 50%; display: grid; place-items: center; font-weight: 700; }
.g-pts { background: var(--lime-pale); padding: 2px 8px; border-radius: 40px; font-size: 0.7rem; font-weight: 700; color: var(--lime-dark); }
.tag-cloud { display: flex; flex-wrap: wrap; gap: 8px; }
.ttag { background: #F3F4F6; padding: 5px 14px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: 0.2s; }
.ttag:hover { background: var(--lime-pale); color: black; }
.sdist-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.sdist-lbl { font-size: .78rem; font-weight: 600; color: #4B5563; min-width: 90px; }
.sdist-bar-bg { flex: 1; height: 6px; background: #e9eef2; border-radius: 10px; overflow: hidden; }
.sdist-bar { height: 100%; background: var(--lime); border-radius: 10px; }

/* Share CTA */
.share-cta { background: var(--lime); padding: 64px 24px; }
.share-cta-inner { max-width: 1280px; margin: 0 auto; display: flex; justify-content: space-between; gap: 40px; flex-wrap: wrap; align-items: center; }
.share-cta h2 { font-family: var(--font-display); font-size: 2.4rem; color: var(--black); }

/* Post modal tabs */
.pm-tab { padding: 12px 18px; font-size: .82rem; font-weight: 700; color: #6B7280; border: none; background: transparent; cursor: pointer; border-bottom: 2.5px solid transparent; margin-bottom: -1px; transition: .2s; }
.pm-tab.active { color: var(--black); border-bottom-color: var(--lime); }

/* Scrollbars in modals */
#post-modal::-webkit-scrollbar, #c-lightbox-content::-webkit-scrollbar { width: 5px; }
#post-modal::-webkit-scrollbar-thumb, #c-lightbox-content::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }

/* Responsive */
@media (max-width: 1024px) { .masonry { columns: 2; } .c-main { grid-template-columns: 1fr; } .c-sidebar { position: static; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); } }
@media (max-width: 768px) {
  .featured-card { grid-template-columns: 1fr; }
  .c-hero-photo { display: none; }
  .masonry { columns: 1; }
  .c-hero { min-height: 460px; }
  .c-hero-content { padding: 48px 20px; }
  .c-hero-stats { gap: 24px; }
  .c-hero-stat-num { font-size: 1.6rem; }
  .c-hero-title { font-size: clamp(2rem, 8vw, 3.5rem); }
  .c-hero-desc { font-size: .92rem; max-width: 100%; }
}
@media (max-width: 540px) {
  .c-filters-inner { flex-direction: column; align-items: stretch; }
  .c-hero-stats { gap: 16px; }
  .c-hero-collage { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 680px) {
  #c-lightbox-content { grid-template-columns: 1fr !important; max-height: 100vh; border-radius: 20px !important; }
  #lb-media { min-height: 260px !important; border-radius: 20px 20px 0 0 !important; }
}
@media (max-width: 640px) {
  /* Post creation modal — collapse all multi-column panel grids */
  #post-modal [style*="grid-template-columns:1fr 1fr"],
  #post-modal [style*="grid-template-columns: 1fr 1fr"],
  #post-modal [style*="grid-template-columns:1fr 1fr 1fr"],
  #post-modal [style*="grid-template-columns: 1fr 1fr 1fr"],
  #post-modal [style*="grid-template-columns:1.2fr 1fr"],
  #post-modal [style*="grid-template-columns: 1.2fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
  #post-modal > div > div[style*="padding:22px 28px 0"] { padding: 16px 16px 0 !important; }
  #post-modal > div > div[style*="padding:22px 28px 28px"],
  #post-modal > div > div[style*="padding: 22px 28px 28px"] { padding: 16px !important; }
  #post-modal > div > div[style*="padding:0 28px"] { padding: 0 16px !important; }
  .featured-body { padding: 24px 20px !important; }
  .share-cta { padding: 40px 20px; }
  .share-cta-inner { flex-direction: column; align-items: stretch; text-align: center; }
  .share-cta h2 { font-size: 1.9rem; }
}
@media (max-width: 400px) {
  #post-modal > div { border-radius: 20px; }
  .c-hero-title { font-size: clamp(1.8rem, 9vw, 2.4rem); }
  .c-hero-stats { gap: 12px; }
  .c-hero-stat-num { font-size: 1.4rem; }
}
.lb-ba-grid { display: grid; height: 100%; gap: 3px; }
.lb-ba-side { position: relative; overflow: hidden; min-height: 200px; }
.lb-ba-side img { width: 100%; height: 100%; object-fit: cover; display: block; }
.lb-ba-label { position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); padding: 4px 16px; border-radius: 100px; font-size: .72rem; font-weight: 700; white-space: nowrap; }

/* Multi-image carousel in lightbox */
.lb-carousel { position:relative; width:100%; height:100%; overflow:hidden; }
.lb-carousel-track { display:flex; height:100%; transition:transform .35s cubic-bezier(.4,0,.2,1); will-change:transform; }
.lb-carousel-slide { min-width:100%; height:100%; flex-shrink:0; }
.lb-carousel-slide img { width:100%; height:100%; object-fit:cover; display:block; user-select:none; }
.lb-carousel-btn { position:absolute; top:50%; transform:translateY(-50%); background:rgba(0,0,0,.5); border:none; color:#fff; width:38px; height:38px; border-radius:50%; cursor:pointer; font-size:1.2rem; font-weight:700; display:grid; place-items:center; transition:.2s; z-index:5; line-height:1; }
.lb-carousel-btn:hover { background:rgba(0,0,0,.75); }
.lb-carousel-btn.lb-prev { left:12px; }
.lb-carousel-btn.lb-next { right:12px; }
.lb-carousel-dots { position:absolute; bottom:14px; left:50%; transform:translateX(-50%); display:flex; gap:5px; z-index:5; }
.lb-dot { width:7px; height:7px; border-radius:50%; background:rgba(255,255,255,.45); border:none; padding:0; cursor:pointer; transition:.2s; flex-shrink:0; }
.lb-dot.active { background:#fff; transform:scale(1.25); }
.lb-counter { position:absolute; top:12px; right:12px; background:rgba(0,0,0,.52); color:#fff; font-size:.7rem; font-weight:700; padding:3px 10px; border-radius:100px; z-index:5; letter-spacing:.04em; }
.mc-multi-badge { position:absolute; top:8px; right:8px; background:rgba(0,0,0,.55); color:#fff; font-size:.65rem; font-weight:700; padding:3px 7px; border-radius:6px; z-index:2; display:flex; align-items:center; gap:3px; }

/* Post modal — rich composer */
.pm-drop-zone { border: 2px dashed #E5E7EB; border-radius: 20px; padding: 36px 20px; text-align: center; cursor: pointer; transition: .2s; background: #FAFAFA; }
.pm-drop-zone:hover, .pm-drop-zone.drag-over { border-color: var(--lime); background: rgba(212,217,148,.06); }
.pm-prod-wrap { position: relative; }
.pm-prod-dropdown { position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #fff; border: 1.5px solid #EFF0F2; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,.1); z-index: 300; max-height: 180px; overflow-y: auto; display: none; }
.pm-prod-dropdown.open { display: block; }
.pm-prod-item { display: flex; align-items: center; gap: 10px; padding: 9px 14px; cursor: pointer; transition: .15s; font-size: .82rem; }
.pm-prod-item:hover { background: #F9FAFB; }
.pm-prod-item img { width: 32px; height: 32px; border-radius: 6px; object-fit: cover; flex-shrink: 0; }
.pm-tagged-prods { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; min-height: 4px; }
.pm-prod-chip { display: inline-flex; align-items: center; gap: 5px; background: rgba(212,217,148,.14); color: var(--lime-dark); padding: 4px 10px; border-radius: 100px; font-size: .74rem; font-weight: 700; }
.pm-prod-chip img { width: 16px; height: 16px; border-radius: 3px; object-fit: cover; }
.pm-prod-chip button { background: none; border: none; cursor: pointer; color: var(--lime-dark); font-size: .85rem; padding: 0 0 0 2px; line-height: 1; }
.pm-htag-chip { display: inline-flex; align-items: center; gap: 4px; background: rgba(212,217,148,.15); color: var(--lime-dark); padding: 3px 10px; border-radius: 100px; font-size: .74rem; font-weight: 700; }
.pm-htag-chip button { background: none; border: none; cursor: pointer; color: var(--lime-dark); font-size: .85rem; padding: 0 0 0 2px; line-height: 1; }
.pm-asp-box { background: rgba(212,217,148,.1); border-radius: 14px; padding: 14px 16px; }
.pm-checkins { background: rgba(212,217,148,.1); border-radius: 14px; padding: 14px 16px; }
.pm-field-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #6B7280; display: block; margin-bottom: 6px; }
.pm-input { width: 100%; padding: 10px 14px; border: 1.5px solid #EFF0F2; border-radius: 100px; font-size: .85rem; font-family: inherit; outline: none; transition: border-color .2s; }
.pm-input:focus { border-color: var(--lime); }
.pm-textarea { width: 100%; padding: 11px 14px; border: 1.5px solid #EFF0F2; border-radius: 14px; font-size: .86rem; font-family: inherit; resize: none; outline: none; transition: border-color .2s; }
.pm-textarea:focus { border-color: var(--lime); }
.pm-select { width: 100%; padding: 10px 14px; border: 1.5px solid #EFF0F2; border-radius: 100px; font-size: .85rem; font-family: inherit; outline: none; cursor: pointer; }
</style>
@endsection

@section('content')

@php
  $communityCms = data_get($siteContent, 'pages.community', []);
  $mediaLibrary = collect(data_get($siteContent, 'media.library', []))
      ->filter(fn ($item) => !empty($item['enabled']) && !empty($item['slot']) && !empty($item['url']));
  $mediaBySlot = $mediaLibrary->keyBy('slot');
  $slotMedia = function (string $slot, string $fallbackUrl, string $fallbackAlt = 'Media') use ($mediaBySlot): array {
      $item = $mediaBySlot->get($slot, []);

      return [
          'url' => data_get($item, 'url', $fallbackUrl),
          'alt' => data_get($item, 'alt', $fallbackAlt),
      ];
  };
  $collage1 = $slotMedia('hero_collage_1', 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=500&fit=crop', 'Community collage 1');
  $collage2 = $slotMedia('hero_collage_2', 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=400&h=500&fit=crop', 'Community collage 2');
  $collage3 = $slotMedia('hero_collage_3', 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=400&h=500&fit=crop', 'Community collage 3');
  $collage4 = $slotMedia('hero_collage_4', 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400&h=500&fit=crop', 'Community collage 4');
  $collage5 = $slotMedia('hero_collage_5', 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=500&fit=crop', 'Community collage 5');
  $collage6 = $slotMedia('hero_collage_6', 'https://images.unsplash.com/photo-1545208935-9a7b23524f41?w=400&h=500&fit=crop', 'Community collage 6');
  $floating1 = $slotMedia('hero_floating_1', 'https://images.unsplash.com/photo-1557053910-d9eadeed1c58?w=350&h=450&fit=crop', 'Community floating image 1');
  $floating2 = $slotMedia('hero_floating_2', 'https://images.unsplash.com/photo-1555487505-8603a1a69755?w=260&h=340&fit=crop', 'Community floating image 2');
  $featuredMain = $slotMedia('featured_main', 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=800&h=550&fit=crop', 'Featured transformation');

  // ── Featured post helpers ───────────────────────────────────────────────
  $fp          = $featuredPost ?? null;
  $fpType      = $fp['type']    ?? 'photo';
  $fpCaption   = $fp ? ((!empty($fp['quote']) ? $fp['quote'] : null) ?? $fp['caption'] ?? '') : '3 months on the Kominhoo Glass Skin routine and I literally cannot believe this is my face. The COSRX snail serum changed everything.';
  $fpTags      = $fp['tags']     ?? ['#GlassSkin', '#KominhooResults', '#SkincareJourney'];
  $fpProducts  = $fp['products'] ?? ['COSRX Snail Mucin', 'Laneige Water Mask'];
  $fpUser      = $fp['user']     ?? [];
  $fpLikes     = number_format((int) ($fp['likes']    ?? 1247));
  $fpComments  = (int) ($fp['comments'] ?? 89);
  $fpAvColor      = $fpUser['color']     ?? 'var(--lime)';
  $fpAvTextColor  = $fpUser['textColor'] ?? 'var(--black)';
  $fpAv           = $fpUser['av']   ?? 'AO';
  $fpName         = $fpUser['name'] ?? 'Kominhoo Member';
  $fpSkin         = $fpUser['skin'] ?? 'Combination';
  $fpBadge    = match($fpType) { 'review'=>'⭐ Product Review', 'routine'=>'🧴 Routine Share', 'before_after'=>'✨ Transformation', default=>'⭐ Post of the Week' };
  $fpTypeLbl  = match($fpType) { 'review'=>'Featured Review', 'routine'=>'Featured Routine', 'before_after'=>'Featured Transformation', default=>'Featured Post' };
  // Image: prefer post img, then after/before for B&A, then CMS media slot
  $fpImgUrl = '';
  if ($fp) {
    if (!empty($fp['img']))                                           $fpImgUrl = $fp['img'];
    elseif ($fpType === 'before_after' && !empty($fp['after_img']))  $fpImgUrl = $fp['after_img'];
    elseif ($fpType === 'before_after' && !empty($fp['before_img'])) $fpImgUrl = $fp['before_img'];
  }
  if (empty($fpImgUrl)) $fpImgUrl = $featuredMain['url'];
@endphp

{{-- Hero --}}
<section class="c-hero">
  <div class="c-hero-collage">
    <img src="{{ $collage1['url'] }}" alt="{{ $collage1['alt'] }}">
    <img src="{{ $collage2['url'] }}" alt="{{ $collage2['alt'] }}">
    <img src="{{ $collage3['url'] }}" alt="{{ $collage3['alt'] }}">
    <img src="{{ $collage4['url'] }}" alt="{{ $collage4['alt'] }}">
    <img src="{{ $collage5['url'] }}" alt="{{ $collage5['alt'] }}">
    <img src="{{ $collage6['url'] }}" alt="{{ $collage6['alt'] }}">
  </div>
  <div class="c-hero-overlay"></div>
  <div class="c-hero-photo" style="width:170px;height:220px;right:8%;top:70px;--rot:-4deg;--dur:8s">
    <img src="{{ $floating1['url'] }}" alt="{{ $floating1['alt'] }}">
  </div>
  <div class="c-hero-photo" style="width:130px;height:170px;right:24%;bottom:50px;--rot:3deg;--dur:10s;--delay:1s">
    <img src="{{ $floating2['url'] }}" alt="{{ $floating2['alt'] }}">
  </div>
  <div class="c-hero-content">
    <div class="c-hero-eyebrow">Real Skin · Real People · Real Glow</div>
    <h1 class="c-hero-title">{{ data_get($communityCms, 'hero_title_line_1', 'The Kominhoo') }}<br><em>{{ data_get($communityCms, 'hero_title_line_2', 'Community') }}</em></h1>
    <p class="c-hero-desc">{{ data_get($communityCms, 'hero_description', '50,000+ skin lovers sharing honest results, routines, and transformations. Your next favourite product is one post away.') }}</p>
    <div class="c-hero-stats">
      @foreach(data_get($communityCms, 'stats', [['value'=>'50K+','label'=>'Members'],['value'=>'18K+','label'=>'Posts'],['value'=>'4.8★','label'=>'Avg Rating']]) as $stat)
      <div class="c-hero-stat">
        <div class="c-hero-stat-num">{{ $stat['value'] }}</div>
        <div class="c-hero-stat-label">{{ $stat['label'] }}</div>
      </div>
      @endforeach
    </div>
    <div class="c-hero-actions" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
      <button class="btn btn-primary btn-lg" onclick="openPostModal()"> Share Your Glow</button>
      <a href="{{ route('quiz') }}" class="btn btn-outline-lime btn-lg">Take Skin Quiz →</a>
      <span class="live-dot">{{ data_get($communityCms, 'live_label', '247 sharing now') }}</span>
    </div>
  </div>
</section>

{{-- Filters --}}
<div class="c-filters">
  <div class="c-filters-inner">
    <div class="filter-pills" id="filterContainer">
      <button class="fpill active" data-filter="all">All Posts</button>
      <button class="fpill" data-filter="transformation"> Transformations</button>
      <button class="fpill" data-filter="review">⭐ Reviews</button>
      <button class="fpill" data-filter="routine"> Routines</button>
      <button class="fpill" data-filter="oily">Oily Skin</button>
      <button class="fpill" data-filter="dry">Dry Skin</button>
      <button class="fpill" data-filter="combination">Combination</button>
      <button class="fpill" data-filter="sensitive">Sensitive</button>
    </div>
    <div class="c-filters-right" style="display:flex;align-items:center;gap:16px">
      <span class="c-post-count" id="post-count">Loading posts…</span>
      <select class="c-sort" id="c-sort">
        <option value="trending">🔥 Trending</option>
        <option value="latest">🕐 Latest</option>
        <option value="loved">❤️ Most Loved</option>
      </select>
    </div>
  </div>
</div>

{{-- Featured Post --}}
<div class="featured-wrap">
  <div class="featured-card">
    <div class="featured-img-side" style="cursor:pointer" onclick="openFeaturedPost()">
      <img src="{{ $fpImgUrl }}" alt="{{ $featuredMain['alt'] }}">
      <div class="featured-pill">{{ $fpBadge }}</div>
    </div>
    <div class="featured-body" style="cursor:pointer" onclick="openFeaturedPost()">
      <div class="featured-ey">{{ $fpTypeLbl }}</div>
      <div class="featured-quote">{{ $fpCaption }}</div>
      @if(count($fpTags))
      <div class="tag-row">
        @foreach($fpTags as $fpTag)
          <span class="ptag{{ $loop->first ? ' lime' : '' }}">{{ $fpTag }}</span>
        @endforeach
      </div>
      @endif
      @if(count($fpProducts))
      <div class="prod-tags">
        @foreach($fpProducts as $fpProd)
          <span class="prod-pill">{{ $fpProd }}</span>
        @endforeach
      </div>
      @endif
      <div class="featured-user">
        <div class="uavatar" style="background:{{ $fpAvColor }};color:{{ $fpAvTextColor }}">{{ $fpAv }}</div>
        <div>
          <div style="color:white;font-weight:700">{{ $fpName }}</div>
          <div><span style="color:var(--lime);font-size:.8rem">{{ $fpSkin }}</span><span style="color:gray;font-size:.8rem"> · Verified</span></div>
        </div>
      </div>
      <div class="post-actions" onclick="event.stopPropagation()">
        <button class="paction" id="feat-like">♥ {{ $fpLikes }}</button>
        <button class="paction" id="feat-comment">💬 {{ $fpComments }} {{ $fpComments === 1 ? 'comment' : 'comments' }}</button>
        <button class="paction" id="feat-share">↗ Share</button>
      </div>
    </div>
  </div>
</div>

{{-- Main Content + Sidebar --}}
<div class="c-main">
  <div>
    <div class="masonry" id="masonry-grid"></div>
    <div style="text-align:center;margin-top:32px;">
      <button class="btn btn-outline btn-lg" id="load-more-btn">Load More Posts</button>
    </div>
  </div>
  <div class="c-sidebar">
    <div class="scard">
      <div class="scard-title">
        <span style="background:var(--lime);width:6px;height:6px;border-radius:50%;display:inline-block;"></span>
        Top Glowers This Month
      </div>
      <div id="top-glowers"></div>
    </div>
    <div class="scard">
      <div class="scard-title">🔥 Trending Hashtags</div>
      <div class="tag-cloud" id="trendingTags"></div>
    </div>
    <div class="scard">
      <div class="scard-title">👩‍🦱 Community Skin Types</div>
      <div class="sdist">
        @foreach($skinDistribution as $dist)
        <div class="sdist-row">
          <span class="sdist-lbl">{{ $dist['name'] }}</span>
          <div class="sdist-bar-bg"><div class="sdist-bar" style="width:{{ $dist['pct'] }}%"></div></div>
          <span>{{ $dist['pct'] }}%</span>
        </div>
        @endforeach
      </div>
    </div>
    <div class="scard" style="background:#0A0A0A;">
      <div style="font-size:1.6rem;">✨</div>
      <div style="font-family:var(--font-display);color:white;font-size:1.2rem;">Share your glow-up</div>
      <div style="color:#aaa;font-size:0.8rem;margin:8px 0 16px;">Post your skin journey and inspire 50K+ lovers.</div>
      <button class="btn btn-primary" onclick="openPostModal()" style="width:100%;justify-content:center;">Post Your Glow →</button>
    </div>
  </div>
</div>

{{-- Share CTA --}}
<div class="share-cta">
  <div class="share-cta-inner">
    <div>
      <h2>{{ data_get($communityCms, 'share_title', 'Your Skin Story Deserves to Be Heard') }}</h2>
      <p>{{ data_get($communityCms, 'share_description', 'Join thousands sharing honest Korean skincare journeys — before & afters, routine breakdowns, and real results.') }}</p>
    </div>
    <div>
      <button class="btn btn-dark btn-lg" onclick="openPostModal()" style="background:#0A0A0A;color:#fff;">{{ data_get($communityCms, 'share_button_text', '📸 Share My Story') }}</button>
      <div style="margin-top:12px;font-size:.85rem;">{{ data_get($communityCms, 'share_tags_text', '#KominhooSkin · #KominhooResults') }}</div>
    </div>
  </div>
</div>

{{-- Toast --}}
<div id="c-toast" style="position:fixed;bottom:32px;left:50%;transform:translateX(-50%) translateY(20px);background:#0A0A0A;color:#fff;padding:14px 24px;border-radius:100px;font-size:.88rem;font-weight:600;z-index:99999;opacity:0;transition:all .3s ease;pointer-events:none;display:flex;align-items:center;gap:10px;white-space:nowrap;box-shadow:0 8px 32px rgba(0,0,0,.35)"></div>

{{-- Community Search Overlay (separate from global layout search) --}}
<div id="c-search-overlay" style="position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,.7);backdrop-filter:blur(8px);display:none;align-items:flex-start;justify-content:center;padding-top:80px">
  <div style="background:#fff;border-radius:28px;width:100%;max-width:640px;padding:28px;margin:0 16px">
    <div style="display:flex;gap:12px;align-items:center;margin-bottom:20px">
      <input id="c-search-input" style="flex:1;background:#F3F4F6;border:1.5px solid transparent;outline:none;padding:14px 20px;border-radius:100px;font-size:1rem;width:100%" placeholder="Search posts, users, hashtags…" oninput="liveSearch(this.value)">
      <button onclick="closeCommunitySearch()" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#6B7280">✕</button>
    </div>
    <div id="c-search-results" style="display:flex;flex-direction:column;gap:10px;max-height:400px;overflow-y:auto"></div>
    <div id="c-search-empty" style="text-align:center;padding:32px;color:#6B7280;font-size:.9rem">Start typing to search posts…</div>
  </div>
</div>

{{-- Post Creation Modal --}}
<div id="post-modal" style="position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,.75);backdrop-filter:blur(8px);display:none;align-items:center;justify-content:center;padding:20px">
  <div style="background:#fff;border-radius:28px;width:100%;max-width:820px;max-height:92vh;overflow-y:auto">
    <div style="padding:22px 28px 0;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:#fff;z-index:10;border-bottom:1px solid #F0F0F0;padding-bottom:0">
      <div style="font-size:1.05rem;font-weight:700">Share Your Glow </div>
      <button onclick="closePostModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:#6B7280;width:36px;height:36px;border-radius:50%;display:grid;place-items:center">✕</button>
    </div>
    <div style="display:flex;border-bottom:1px solid #F0F0F0;padding:0 28px;overflow-x:auto">
      <button class="pm-tab active" data-panel="pm-photo" onclick="switchPostTab('pm-photo',this)">📸 Photo / Selfie</button>
      <button class="pm-tab" data-panel="pm-ba" onclick="switchPostTab('pm-ba',this)"> Before & After</button>
      <button class="pm-tab" data-panel="pm-review" onclick="switchPostTab('pm-review',this)">⭐ Product Review</button>
      <button class="pm-tab" data-panel="pm-routine" onclick="switchPostTab('pm-routine',this)">🧴 My Routine</button>
    </div>
    <div style="padding:22px 28px 28px">

      {{-- ── Photo panel ── --}}
      <div class="pm-panel" id="pm-photo">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div>
            <div class="pm-drop-zone" id="pm-drop-zone" onclick="document.getElementById('pm-file-input').click()" ondragover="pmDragOver(event)" ondragleave="pmDragLeave(event)" ondrop="pmDrop(event)">
              <input type="file" id="pm-file-input" accept="image/*" multiple style="display:none" onchange="pmHandleFiles(this.files)">
              <div style="font-size:2.4rem;margin-bottom:8px">🖼️</div>
              <div style="font-weight:700;margin-bottom:4px;font-size:.92rem">Drop photos here or click to browse</div>
              <div style="font-size:.76rem;color:#6B7280">Up to 6 photos · JPG, PNG, HEIC</div>
            </div>
            <div id="pm-preview-strip" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px"></div>
            <div style="display:flex;gap:8px;margin-top:10px">
              <button class="btn btn-outline btn-sm" style="flex:1;justify-content:center" onclick="document.getElementById('pm-file-input').click()">🖼️ Add Photos</button>
              <button class="btn btn-dark btn-sm" style="flex:1;justify-content:center" onclick="document.getElementById('pm-selfie-input').click()">🤳 Take Selfie</button>
            </div>
            <input type="file" id="pm-selfie-input" accept="image/*" capture="user" style="display:none" onchange="pmHandleFiles(this.files)">
          </div>
          <div style="display:flex;flex-direction:column;gap:14px">
            <div>
              <label class="pm-field-label">Caption</label>
              <textarea id="pm-caption" class="pm-textarea" rows="3" placeholder="Share your skin story — what changed, what worked "></textarea>
            </div>
            <div>
              <label class="pm-field-label">Tag Products Used</label>
              <div class="pm-prod-wrap">
                <input class="pm-input" placeholder="Search products…" id="pm-prod-search" oninput="pmFilterProds(this.value)" onfocus="document.getElementById('pm-prod-dropdown').classList.add('open')" onblur="setTimeout(()=>document.getElementById('pm-prod-dropdown').classList.remove('open'),200)" autocomplete="off">
                <div class="pm-prod-dropdown" id="pm-prod-dropdown"></div>
              </div>
              <div class="pm-tagged-prods" id="pm-tagged-prods"></div>
            </div>
            <div>
              <label class="pm-field-label">Hashtags <span style="font-weight:400;text-transform:none">(press Enter or click Add)</span></label>
              <div style="display:flex;gap:8px">
                <input class="pm-input" placeholder="#GlassSkin" id="pm-htag-input" onkeydown="pmAddHashtag(event)" style="flex:1">
                <button class="btn btn-outline btn-sm" onclick="pmAddHashtag({key:'Enter',target:document.getElementById('pm-htag-input')})">Add</button>
              </div>
              <div id="pm-hashtag-cloud" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px">
                <span class="pm-htag-chip">#KominhooResults <button onclick="this.parentElement.remove()">×</button></span>
              </div>
            </div>
            <div>
              <label class="pm-field-label">Skin Type</label>
              <select id="pm-skintype" class="pm-select"><option>Combination</option><option>Oily</option><option>Dry</option><option>Sensitive</option><option>Normal</option><option>Acne-Prone</option></select>
            </div>
            <button class="btn btn-primary" style="width:100%;justify-content:center" onclick="submitPost('photo')">✨ Share to Community → +30 pts</button>
          </div>
        </div>
      </div>

      {{-- ── Before & After panel ── --}}
      <div class="pm-panel" id="pm-ba" style="display:none">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
          <div>
            <label class="pm-field-label">Before Photo</label>
            <div class="pm-drop-zone" style="padding:28px 12px;min-height:180px;display:flex;flex-direction:column;align-items:center;justify-content:center" onclick="document.getElementById('pm-before-input').click()" id="pm-before-zone">
              <input type="file" id="pm-before-input" accept="image/*" style="display:none" onchange="pmBAPreview(this,'pm-before-preview','pm-before-ph')">
              <img id="pm-before-preview" style="display:none;width:100%;border-radius:12px;max-height:160px;object-fit:cover;margin-bottom:6px">
              <span id="pm-before-ph"><div style="font-size:2rem">📷</div><div style="font-size:.75rem;color:#6B7280;margin-top:4px">Your before photo</div></span>
            </div>
          </div>
          <div>
            <label class="pm-field-label">After Photo</label>
            <div class="pm-drop-zone" style="padding:28px 12px;min-height:180px;display:flex;flex-direction:column;align-items:center;justify-content:center;border-color:var(--lime);background:rgba(212,217,148,.04)" onclick="document.getElementById('pm-after-input').click()" id="pm-after-zone">
              <input type="file" id="pm-after-input" accept="image/*" style="display:none" onchange="pmBAPreview(this,'pm-after-preview','pm-after-ph')">
              <img id="pm-after-preview" style="display:none;width:100%;border-radius:12px;max-height:160px;object-fit:cover;margin-bottom:6px">
              <span id="pm-after-ph"><div style="font-size:2rem">✨</div><div style="font-size:.75rem;color:#6B7280;margin-top:4px">Your glow-up result</div></span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:12px">
            <div>
              <label class="pm-field-label">Time Period</label>
              <select id="pm-ba-period" class="pm-select"><option>2 weeks</option><option>1 month</option><option selected>3 months</option><option>6 months</option><option>1 year+</option></select>
            </div>
            <div>
              <label class="pm-field-label">Main Concern Fixed</label>
              <select class="pm-select"><option>Acne / Breakouts</option><option>Hyperpigmentation</option><option>Dehydration</option><option>Oiliness</option><option>Uneven Tone</option></select>
            </div>
            <div>
              <label class="pm-field-label">Your Story</label>
              <textarea id="pm-ba-caption" class="pm-textarea" rows="5" placeholder="Tell the community what you used and what changed. Be honest — that's what makes it powerful."></textarea>
            </div>
            <button class="btn btn-primary" style="width:100%;justify-content:center" onclick="submitPost('before_after')">📸 Post Transformation → +50 pts</button>
          </div>
        </div>
      </div>

      {{-- ── Review panel ── --}}
      <div class="pm-panel" id="pm-review" style="display:none">
        <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:20px">
          <div style="display:flex;flex-direction:column;gap:14px">
            <div>
              <label class="pm-field-label">Product Being Reviewed</label>
              <div class="pm-prod-wrap">
                <input class="pm-input" placeholder="Search or type a product name…" id="pm-rev-product" oninput="pmFilterRevProds(this.value)" onfocus="document.getElementById('pm-rev-prod-dropdown').classList.add('open')" onblur="setTimeout(()=>document.getElementById('pm-rev-prod-dropdown').classList.remove('open'),200)">
                <div class="pm-prod-dropdown" id="pm-rev-prod-dropdown"></div>
              </div>
              <div id="pm-rev-selected" style="margin-top:8px"></div>
            </div>
            <div>
              <label class="pm-field-label">Your Rating</label>
              <div id="pm-stars" style="display:flex;gap:8px;cursor:pointer" onmouseleave="pmResetStars()">
                <span class="pm-star" data-v="1" onmouseover="pmHoverStar(1)" onclick="pmSetStar(1)" style="font-size:2rem;color:#F59E0B;transition:.15s">★</span>
                <span class="pm-star" data-v="2" onmouseover="pmHoverStar(2)" onclick="pmSetStar(2)" style="font-size:2rem;color:#F59E0B;transition:.15s">★</span>
                <span class="pm-star" data-v="3" onmouseover="pmHoverStar(3)" onclick="pmSetStar(3)" style="font-size:2rem;color:#F59E0B;transition:.15s">★</span>
                <span class="pm-star" data-v="4" onmouseover="pmHoverStar(4)" onclick="pmSetStar(4)" style="font-size:2rem;color:#F59E0B;transition:.15s">★</span>
                <span class="pm-star" data-v="5" onmouseover="pmHoverStar(5)" onclick="pmSetStar(5)" style="font-size:2rem;color:#E5E7EB;transition:.15s">★</span>
              </div>
              <div id="pm-star-label" style="font-size:.78rem;color:#6B7280;margin-top:4px">4 out of 5 — Great!</div>
            </div>
            <div>
              <label class="pm-field-label">Your Review</label>
              <textarea id="pm-rev-text" class="pm-textarea" rows="5" placeholder="How long have you used it? What changed? Would you recommend it to someone with your skin type?"></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
              <div>
                <label class="pm-field-label">Would Repurchase?</label>
                <select class="pm-select"><option>Yes, absolutely</option><option>Probably</option><option>Not sure</option><option>No</option></select>
              </div>
              <div>
                <label class="pm-field-label">How Long Used</label>
                <select class="pm-select"><option>1–2 weeks</option><option>1 month</option><option>3 months</option><option>6+ months</option></select>
              </div>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:14px">
            <div>
              <label class="pm-field-label">Review Photo (optional)</label>
              <div class="pm-drop-zone" style="padding:28px 16px" onclick="document.getElementById('pm-rev-photo').click()">
                <input type="file" id="pm-rev-photo" accept="image/*" style="display:none" onchange="pmRevPhotoPreview(this)">
                <img id="pm-rev-ph-preview" style="display:none;width:100%;border-radius:12px;max-height:150px;object-fit:cover;margin-bottom:6px">
                <div id="pm-rev-ph-ph"><div style="font-size:2rem;margin-bottom:4px">📸</div><div style="font-size:.75rem;color:#6B7280">Add a product photo</div></div>
              </div>
            </div>
            <div class="pm-asp-box">
              <div style="font-size:.8rem;font-weight:700;margin-bottom:10px">Rate these aspects:</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem"><span>Effectiveness</span><div id="pm-asp-effect" data-val="4" onmouseleave="this.querySelectorAll('.pm-asp-star').forEach((s,i)=>s.style.color=i<parseInt(this.dataset.val)?'#F59E0B':'#E5E7EB')"></div></div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem"><span>Texture / Feel</span><div id="pm-asp-texture" data-val="5" onmouseleave="this.querySelectorAll('.pm-asp-star').forEach((s,i)=>s.style.color=i<parseInt(this.dataset.val)?'#F59E0B':'#E5E7EB')"></div></div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem"><span>Value for Money</span><div id="pm-asp-value" data-val="3" onmouseleave="this.querySelectorAll('.pm-asp-star').forEach((s,i)=>s.style.color=i<parseInt(this.dataset.val)?'#F59E0B':'#E5E7EB')"></div></div>
              </div>
            </div>
            <button class="btn btn-primary" style="width:100%;justify-content:center" onclick="submitPost('review')">⭐ Submit Review → +10 pts</button>
            <div style="font-size:.74rem;color:#9CA3AF;text-align:center;line-height:1.6">Reviews are shown to 50K+ community members.</div>
          </div>
        </div>
      </div>

      {{-- ── Routine panel ── --}}
      <div class="pm-panel" id="pm-routine" style="display:none">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div style="display:flex;flex-direction:column;gap:14px">
            <div>
              <label class="pm-field-label">Routine Title</label>
              <input id="pm-rt-title" class="pm-input" placeholder="e.g. My Glass Skin AM Routine" value="My AM Skincare Routine">
            </div>
            <div>
              <label class="pm-field-label">Routine Type</label>
              <div style="display:flex;gap:8px">
                <button class="btn btn-dark btn-sm" id="pm-rt-am" onclick="pmSetRoutineType('AM')">☀️ AM</button>
                <button class="btn btn-outline btn-sm" id="pm-rt-pm" onclick="pmSetRoutineType('PM')">🌙 PM</button>
                <button class="btn btn-outline btn-sm" id="pm-rt-weekly" onclick="pmSetRoutineType('Weekly')">📅 Weekly</button>
              </div>
            </div>
            <div>
              <label class="pm-field-label">Steps (in order)</label>
              <div id="pm-steps" style="display:flex;flex-direction:column;gap:6px"></div>
              <button class="btn btn-outline btn-sm" style="width:100%;margin-top:8px;justify-content:center" onclick="pmAddStep()">+ Add Step</button>
            </div>
            <div>
              <label class="pm-field-label">How has this routine helped?</label>
              <textarea id="pm-rt-desc" class="pm-textarea" rows="3" placeholder="Describe your results and how long you've been following this routine."></textarea>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:14px">
            <div>
              <label class="pm-field-label">Routine Photo / Flat Lay</label>
              <div class="pm-drop-zone" style="padding:32px 16px" onclick="document.getElementById('pm-rt-photo').click()">
                <input type="file" id="pm-rt-photo" accept="image/*" style="display:none" onchange="pmRtPhotoPreview(this)">
                <img id="pm-rt-ph-preview" style="display:none;width:100%;border-radius:12px;max-height:200px;object-fit:cover;margin-bottom:6px">
                <div id="pm-rt-ph-ph"><div style="font-size:2rem;margin-bottom:6px">🧴</div><div style="font-size:.78rem;color:#6B7280">Flat lay of your products</div></div>
              </div>
            </div>
            <div class="pm-checkins">
              <div style="font-size:.82rem;font-weight:700;margin-bottom:8px">Quick skin check-ins</div>
              <div style="display:flex;flex-direction:column;gap:8px;font-size:.82rem">
                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" checked style="accent-color:#D4D994;width:15px;height:15px"> Reduced breakouts</label>
                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" checked style="accent-color:#D4D994;width:15px;height:15px"> Improved hydration</label>
                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" style="accent-color:#D4D994;width:15px;height:15px"> Faded dark spots</label>
                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" style="accent-color:#D4D994;width:15px;height:15px"> Better texture</label>
              </div>
            </div>
            <button class="btn btn-primary" style="width:100%;justify-content:center" onclick="submitPost('routine')">🧴 Share My Routine → +20 pts</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Post Lightbox --}}
<div id="c-lightbox" style="position:fixed;inset:0;z-index:9500;background:rgba(0,0,0,.88);backdrop-filter:blur(12px);display:none;align-items:center;justify-content:center;padding:20px">
  <button onclick="closeLightbox()" style="position:absolute;top:20px;right:24px;background:rgba(255,255,255,.1);border:none;color:#fff;font-size:1.2rem;width:44px;height:44px;border-radius:50%;cursor:pointer;display:grid;place-items:center;transition:.2s" onmouseover="this.style.background='rgba(255,255,255,.2)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">✕</button>
  <div id="c-lightbox-content" style="background:#fff;border-radius:28px;width:100%;max-width:900px;max-height:90vh;overflow-y:auto;display:grid;grid-template-columns:1fr 1fr">
    <div id="lb-media" style="background:#0A0A0A;border-radius:28px 0 0 28px;overflow:hidden;min-height:480px;display:flex;align-items:center;justify-content:center"></div>
    <div style="padding:28px;display:flex;flex-direction:column;gap:0;max-height:90vh;overflow-y:auto">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
        <div id="lb-avatar" style="width:44px;height:44px;border-radius:50%;display:grid;place-items:center;font-weight:700;flex-shrink:0"></div>
        <div style="flex:1"><div id="lb-name" style="font-weight:700;font-size:.95rem"></div><div id="lb-handle" style="font-size:.78rem;color:#6B7280"></div></div>
        <span id="lb-time" style="font-size:.75rem;color:#9CA3AF"></span>
      </div>
      <div id="lb-caption" style="font-size:.92rem;color:#374151;line-height:1.6;margin-bottom:14px"></div>
      <div id="lb-tags" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px"></div>
      <div id="lb-products" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:20px"></div>
      <div style="display:flex;gap:12px;padding:14px 0;border-top:1px solid #F0F0F0;border-bottom:1px solid #F0F0F0;margin-bottom:16px">
        <button id="lb-like-btn" style="background:none;border:none;cursor:pointer;font-weight:700;font-size:.88rem;padding:8px 16px;border-radius:100px;transition:.2s;color:#6B7280" onmouseover="this.style.background='#FFF0F3'" onmouseout="!this.classList.contains('liked')&&(this.style.background='')">♥ <span id="lb-like-count"></span></button>
        <button style="background:none;border:none;cursor:pointer;font-weight:700;font-size:.88rem;padding:8px 16px;border-radius:100px;transition:.2s;color:#6B7280" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background=''" onclick="document.getElementById('lb-comment-input').focus()">💬 Comment</button>
        <button style="background:none;border:none;cursor:pointer;font-weight:700;font-size:.88rem;padding:8px 16px;border-radius:100px;transition:.2s;color:#6B7280;margin-left:auto" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background=''" id="lb-share-btn">↗ Share</button>
      </div>
      <div style="font-size:.78rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px">Comments</div>
      <div id="lb-comments" style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;flex:1"></div>
      @if(session('api_token'))
      <div style="display:flex;gap:10px;align-items:center;padding-top:12px;border-top:1px solid #F0F0F0">
        @php $lbAvatar = session('user.avatar'); $lbInitials = strtoupper(substr(session('user.name','?'), 0, 2)); @endphp
        @if($lbAvatar)
          <img src="{{ $lbAvatar }}" alt="" style="width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1.5px solid #E5E7EB">
        @else
          <div style="width:34px;height:34px;border-radius:50%;background:var(--lime);color:var(--black);display:grid;place-items:center;font-weight:700;font-size:.72rem;flex-shrink:0">{{ $lbInitials }}</div>
        @endif
        <input id="lb-comment-input" placeholder="Add a comment…" style="flex:1;border:1.5px solid #EFF0F2;border-radius:100px;padding:9px 16px;font-size:.85rem;font-family:inherit;outline:none" onfocus="this.style.borderColor='#D4D994'" onblur="this.style.borderColor='#EFF0F2'" onkeydown="lbAddComment(event)">
        <button onclick="lbAddComment({key:'Enter',target:document.getElementById('lb-comment-input')})" style="background:#D4D994;border:none;border-radius:100px;padding:9px 18px;font-weight:700;font-size:.82rem;cursor:pointer;transition:.2s" onmouseover="this.style.background='#5E6623'" onmouseout="this.style.background='#D4D994'">Post</button>
      </div>
      @else
      <div style="padding-top:12px;border-top:1px solid #F0F0F0;text-align:center">
        <p style="font-size:.85rem;color:#6B7280;margin-bottom:10px">Sign in to like and comment on posts</p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="display:inline-flex">Sign In</a>
        <a href="{{ route('register') }}" class="btn btn-outline btn-sm" style="display:inline-flex;margin-left:8px">Join Free</a>
      </div>
      @endif
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
window._kominhoo = <?php echo json_encode([
    'loggedIn' => (bool) session('api_token'),
    'loginUrl' => route('login'),
]); ?>;
</script>
<script>
// ── DATA (loaded from API) ────────────────────────────────────────────────────
@php
$fbPost = $featuredPost ?? null;
$fbUser = array_diff_key($fbPost['user'] ?? [], ['email' => 1]);
$fallbackFeaturedData = [
    'id'          => $fbPost['id']       ?? 'seed_11',
    'type'        => $fbPost['type']     ?? 'photo',
    'caption'     => $fbPost ? ((!empty($fbPost['quote']) ? $fbPost['quote'] : null) ?? $fbPost['caption'] ?? '') : '3 months on the Kominhoo Glass Skin routine and I literally cannot believe this is my face. The COSRX snail serum changed everything.',
    'tags'        => $fbPost['tags']     ?? ['#GlassSkin', '#KominhooResults', '#SkincareJourney'],
    'products'    => $fbPost['products'] ?? ['COSRX Snail Mucin', 'Laneige Water Mask'],
    'likes'       => (int) ($fbPost['likes']    ?? 1247),
    'comments'    => (int) ($fbPost['comments'] ?? 89),
    'time'        => $fbPost['time'] ?? '2d ago',
    'user'        => $fbUser ?: ['name'=>'Adaeze Okonkwo','handle'=>'@adaeze_glows','skin'=>'Combination','av'=>'AO','color'=>'#D4D994','textColor'=>'#1C1416'],
    'commentList' => $fbPost['comment_list'] ?? [
        ['av'=>'CN','color'=>'#893941','name'=>'Chisom N.','time'=>'1d ago','text'=>'This is literally my goal! What cleanser were you using?'],
        ['av'=>'KA','color'=>'#8B5CF6','name'=>'Kemi A.','time'=>'18h ago','text'=>'The COSRX snail serum is a complete game changer 🙌'],
    ],
];
@endphp
let FEATURED_POST_ID = {!! json_encode($fallbackFeaturedData['id']) !!};
let GALLERY_OPEN       = true;
let COMMUNITY_POSTS    = [];

// Fallback featured post — seeded from server-side data; img getter reads the DOM so it stays in sync.
const _fbData = {!! json_encode($fallbackFeaturedData) !!};
const FALLBACK_FEATURED = {
  id:          _fbData.id,
  type:        _fbData.type,
  get img()    { return document.querySelector('.featured-img-side img')?.src || ''; },
  caption:     _fbData.caption,
  tags:        _fbData.tags,
  products:    _fbData.products,
  likes:       _fbData.likes,
  comments:    _fbData.comments,
  time:        _fbData.time,
  user:        _fbData.user,
  commentList: _fbData.commentList,
};
const CSRF           = document.querySelector('meta[name="csrf-token"]')?.content || '';
const COMMUNITY_API  = '{{ url("/community/post") }}';
const IS_LOGGED_IN   = window._kominhoo?.loggedIn ?? false;
const LOGIN_URL      = window._kominhoo?.loginUrl ?? '/login';

const TOP_GLOWERS = {!! json_encode($topGlowers) !!};
const TRENDING_TAGS = {!! json_encode($trendingTagsJs) !!};

// Normalise API snake_case → camelCase used by card builders
function normalisePost(p) {
  // Build the images array: use explicit images[] if set, else fall back to single img
  const images = (p.images && p.images.length > 0)
    ? p.images
    : (p.img ? [p.img] : []);
  return {
    ...p,
    images,
    routineType: p.routine_type || p.routineType || null,
    steps:       Array.isArray(p.steps) ? p.steps : [],
    commentList: p.comment_list || p.commentList || [],
    beforeImg:   p.before_img   || p.beforeImg   || '',
    afterImg:    p.after_img    || p.afterImg     || '',
  };
}

// ── ACTIVITY LOGGING ────────────────────────────────────────────────────────
function _logActivity(entry) {
  try {
    const log = JSON.parse(localStorage.getItem('kominhoo_activity_log') || '[]');
    log.unshift({ ...entry, id: Date.now(), time: new Date().toISOString() });
    localStorage.setItem('kominhoo_activity_log', JSON.stringify(log.slice(0, 300)));
  } catch(_) {}
}

function openFeaturedPost() {
  openLightbox(FEATURED_POST_ID);
}

// ── TOAST ───────────────────────────────────────────────────────────────────
let toastTimer;
function showToast(icon, msg) {
  const t = document.getElementById('c-toast');
  if (!t) return;
  t.innerHTML = `<span>${icon}</span> ${msg}`;
  t.style.opacity = '1';
  t.style.transform = 'translateX(-50%) translateY(0)';
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    t.style.opacity = '0';
    t.style.transform = 'translateX(-50%) translateY(20px)';
  }, 3000);
}

// ── SEARCH ──────────────────────────────────────────────────────────────────
function openCommunitySearch() {
  document.getElementById('c-search-overlay').style.display = 'flex';
  setTimeout(() => document.getElementById('c-search-input')?.focus(), 50);
  document.body.style.overflow = 'hidden';
}
function closeCommunitySearch() {
  document.getElementById('c-search-overlay').style.display = 'none';
  document.body.style.overflow = '';
}
document.getElementById('c-search-overlay')?.addEventListener('click', e => {
  if (e.target === document.getElementById('c-search-overlay')) closeCommunitySearch();
});

function liveSearch(q) {
  const resultsEl = document.getElementById('c-search-results');
  const emptyEl = document.getElementById('c-search-empty');
  if (!q.trim()) { resultsEl.innerHTML = ''; emptyEl.style.display = 'block'; return; }
  emptyEl.style.display = 'none';
  const term = q.toLowerCase();
  const matches = COMMUNITY_POSTS.filter(p =>
    p.caption?.toLowerCase().includes(term) ||
    p.quote?.toLowerCase().includes(term) ||
    p.user.name.toLowerCase().includes(term) ||
    p.user.handle.toLowerCase().includes(term) ||
    p.tags?.some(t => t.toLowerCase().includes(term)) ||
    p.products?.some(pr => pr.toLowerCase().includes(term)) ||
    p.product?.toLowerCase().includes(term)
  );
  if (!matches.length) { resultsEl.innerHTML = `<div style="padding:16px;text-align:center;color:#6B7280;font-size:.88rem">No posts found for "${q}"</div>`; return; }
  resultsEl.innerHTML = matches.map(p => `
    <div onclick="closeCommunitySearch();openLightbox(${p.id})" style="display:flex;gap:12px;align-items:center;padding:12px;border-radius:14px;cursor:pointer;transition:.2s" onmouseover="this.style.background='#F9F9F9'" onmouseout="this.style.background=''">
      <div style="width:44px;height:44px;border-radius:50%;background:${p.user.color};color:${p.user.textColor||'#fff'};display:grid;place-items:center;font-weight:700;font-size:.75rem;flex-shrink:0">${p.user.av}</div>
      <div style="flex:1">
        <div style="font-weight:700;font-size:.88rem">${p.user.name} <span style="font-weight:400;color:#9CA3AF">· ${p.type}</span></div>
        <div style="font-size:.78rem;color:#4B5563;margin-top:2px">${(p.caption||p.quote||'').substring(0,80)}…</div>
      </div>
      ${p.img ? `<img src="${p.img}&w=60&h=60" style="width:44px;height:44px;border-radius:10px;object-fit:cover">` : ''}
    </div>`).join('');
}

// ── CARD BUILDERS ───────────────────────────────────────────────────────────
function buildPhotoCard(p) {
  const id = p.id;
  const liked = likedPosts.has(id);
  const multiImg = p.images && p.images.length > 1;
  return `<div class="mcard" data-id="${id}" data-type="photo" onclick="openLightbox('${id}')">
    <div class="mc-img" style="position:relative">
      <img src="${p.images?.[0] || p.img}" loading="lazy" alt="">
      <div class="mc-overlay">
        <div class="mc-hover-btns">
          <button class="mc-btn like-btn" data-id="${id}" onclick="event.stopPropagation();toggleLike('${id}',this)" style="${liked?'color:#e63946':''}">♥</button>
          <button class="mc-btn" onclick="event.stopPropagation();sharePost('${id}')">↗</button>
        </div>
      </div>
      ${multiImg ? `<span class="mc-multi-badge">⊞ ${p.images.length}</span>` : ''}
      ${p.badge ? `<span class="mc-badge ${p.badge}">Transformation</span>` : ''}
    </div>
    <div class="mc-body">
      <div class="mc-user">
        <div class="mc-avatar" style="background:${p.user.color};color:${p.user.textColor||'#0A0A0A'}">${p.user.av}</div>
        <div><div class="mc-uname">${p.user.name}</div><div class="mc-skin">${p.user.skin}</div></div>
        <span class="mc-time">${p.time}</span>
      </div>
      <div class="mc-caption">${p.caption}</div>
      <div class="mc-tags">${p.tags.map(t=>`<span class="mc-tag" onclick="event.stopPropagation();filterByTag('${t}')">${t}</span>`).join('')}</div>
      <div class="mc-footer">
        <button class="mc-stat like-stat${liked?' liked':''}" data-id="${id}" onclick="event.stopPropagation();toggleLikeStat('${id}',this)">♥ ${p.likes.toLocaleString()}</button>
        <button class="mc-stat comment-stat" data-id="${id}" onclick="event.stopPropagation();openLightbox('${id}')">💬 ${p.comments}</button>
        <button class="mc-stat" onclick="event.stopPropagation();sharePost('${id}')" style="margin-left:auto">↗</button>
      </div>
    </div>
  </div>`;
}
function buildBACard(p) {
  const id = p.id;
  const liked = likedPosts.has(id);
  const validSrc = s => s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:'));
  const bBefore = validSrc(p.beforeImg);
  const bAfter  = validSrc(p.afterImg);
  return `<div class="mcard" data-id="${id}" data-type="before_after" onclick="openLightbox('${id}')">
    <div class="mc-img">
      <div class="ba-wrap">
        ${bBefore ? `<div class="ba-side"><img src="${p.beforeImg}" loading="lazy"><div class="ba-label">Before</div></div>` : ''}
        ${bAfter  ? `<div class="ba-side"><img src="${p.afterImg}"  loading="lazy"><div class="ba-label">After</div></div>`  : ''}
        ${!bBefore && !bAfter ? `<div class="ba-side" style="display:grid;place-items:center;background:#111;font-size:2rem">✨</div>` : ''}
        ${bBefore && bAfter ? '<div class="ba-rule"></div>' : ''}
      </div>
      <div class="mc-overlay">
        <div class="mc-hover-btns">
          <button class="mc-btn like-btn" data-id="${id}" onclick="event.stopPropagation();toggleLike('${id}',this)" style="${liked?'color:#e63946':''}">♥</button>
          <button class="mc-btn" onclick="event.stopPropagation();sharePost('${id}')">↗</button>
        </div>
      </div>
      <span class="mc-badge mc-badge-transform"> Transformation</span>
    </div>
    <div class="mc-body">
      <div class="mc-user">
        <div class="mc-avatar" style="background:${p.user.color};color:${p.user.textColor||'#0A0A0A'}">${p.user.av}</div>
        <div><div class="mc-uname">${p.user.name}</div><div class="mc-skin">${p.user.skin}</div></div>
        <span class="mc-time">${p.time}</span>
      </div>
      <div class="mc-caption">${p.caption}</div>
      <div class="mc-tags">${p.tags.map(t=>`<span class="mc-tag" onclick="event.stopPropagation();filterByTag('${t}')">${t}</span>`).join('')}</div>
      <div class="mc-footer">
        <button class="mc-stat like-stat${liked?' liked':''}" data-id="${id}" onclick="event.stopPropagation();toggleLikeStat('${id}',this)">♥ ${p.likes.toLocaleString()}</button>
        <button class="mc-stat comment-stat" data-id="${id}" onclick="event.stopPropagation();openLightbox('${id}')">💬 ${p.comments}</button>
        <button class="mc-stat" onclick="event.stopPropagation();sharePost('${id}')" style="margin-left:auto">↗</button>
      </div>
    </div>
  </div>`;
}
function buildReviewCard(p) {
  const id = p.id;
  const liked = likedPosts.has(id);
  const sv = Math.min(Math.max(parseInt(p.stars) || 4, 0), 5);
  const stars = '★'.repeat(sv) + '☆'.repeat(5 - sv);
  const validSrc = s => s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:'));
  const hasPhoto = validSrc(p.img);
  return `<div class="review-mcard" data-id="${id}" onclick="openLightbox('${id}')" style="cursor:pointer">
    ${hasPhoto ? `<img src="${p.img}" loading="lazy" alt="Product photo" style="width:100%;border-radius:18px;margin-bottom:14px;max-height:220px;object-fit:cover;display:block">` : ''}
    <div class="rm-stars">${stars}</div>
    <div class="rm-quote">${p.quote || p.caption || ''}</div>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
      <div class="mc-avatar" style="background:${p.user.color};color:${p.user.textColor||'#fff'};width:36px;height:36px">${p.user.av}</div>
      <div><div style="color:rgba(255,255,255,.9);font-weight:700;font-size:.85rem">${p.user.name}</div><div style="font-size:.75rem;color:rgba(255,255,255,.45)">${p.user.skin}</div></div>
      <span style="margin-left:auto;font-size:.72rem;color:rgba(255,255,255,.3)">${p.time}</span>
    </div>
    <span class="rm-prod">🧴 ${p.product || 'Product Review'}</span>
    <div style="margin-top:14px;display:flex;gap:12px;align-items:center">
      <button class="mc-stat like-stat${liked?' liked':''}" data-id="${id}" onclick="event.stopPropagation();toggleLikeStat('${id}',this)" style="color:${liked?'#e63946':'rgba(255,255,255,.5)'};cursor:pointer;transition:.2s;background:none;border:none">♥ ${p.likes.toLocaleString()}</button>
      <button class="comment-stat" data-id="${id}" style="color:rgba(255,255,255,.35);font-size:.75rem;font-weight:600;cursor:pointer;background:none;border:none" onclick="event.stopPropagation();openLightbox('${id}')">💬 ${p.comments}</button>
      <button style="color:rgba(255,255,255,.35);font-size:.75rem;font-weight:600;cursor:pointer;margin-left:auto;background:none;border:none" onclick="event.stopPropagation();sharePost('${id}')">↗ Share</button>
    </div>
  </div>`;
}

function buildRoutineCard(p) {
  const id = p.id;
  const liked = likedPosts.has(id);
  const validSrc = s => s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:'));
  const hasImg = validSrc(p.img);

  // Parse title + description from caption
  const cap = p.caption || '';
  const colon = cap.indexOf(':');
  const rtTitle = (colon > -1 ? cap.substring(0, colon) : cap).trim();
  const rtDesc  = (colon > -1 ? cap.substring(colon + 1) : '').trim();

  // Use stored routine_type, fall back to keyword detection
  const rtType = p.routineType
    || (cap.toLowerCase().includes('am') || cap.toLowerCase().includes('morning') ? 'AM'
    : cap.toLowerCase().includes('pm') || cap.toLowerCase().includes('night') ? 'PM'
    : cap.toLowerCase().includes('weekly') ? 'Weekly' : 'AM');
  const rtBadge = rtType === 'AM' ? '☀️ AM Routine'
                : rtType === 'PM' ? '🌙 PM Routine'
                : rtType === 'Weekly' ? '📅 Weekly Routine'
                : '🧴 Skincare Routine';
  const rtAccent = rtType === 'PM' ? '#8B5CF6' : rtType === 'Weekly' ? '#0EA5E9' : '#D4D994';
  const rtAccentText = rtType === 'PM' ? '#fff' : '#0A0A0A';
  const stepPreview = (p.steps || []).slice(0, 3);

  const prods = (p.products || []).slice(0, 4);
  const prodChips = prods.length
    ? prods.map(pr => `<span style="background:rgba(212,217,148,.14);color:var(--lime-dark);padding:2px 9px;border-radius:100px;font-size:.63rem;font-weight:700">🧴 ${pr}</span>`).join('')
    : '';

  return `<div class="mcard" data-id="${id}" data-type="routine" onclick="openLightbox('${id}')">
    <div class="mc-img" style="position:relative">
      ${hasImg
        ? `<img src="${p.img}" loading="lazy" alt="">
           <div style="position:absolute;bottom:0;left:0;right:0;padding:14px 16px;background:linear-gradient(transparent,rgba(0,0,0,.88))">
             <span style="background:${rtAccent};color:${rtAccentText};font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:2px 9px;border-radius:100px;display:inline-block;margin-bottom:5px">${rtBadge}</span>
             <div style="color:#fff;font-size:.86rem;font-weight:700;line-height:1.25">${rtTitle}</div>
           </div>`
        : `<div style="background:#0A0A0A;min-height:190px;display:flex;flex-direction:column;align-items:flex-start;justify-content:center;gap:10px;padding:22px 20px;text-align:left">
             <span style="background:${rtAccent};color:${rtAccentText};font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:3px 10px;border-radius:100px">${rtBadge}</span>
             <div style="color:#fff;font-size:.92rem;font-weight:700;line-height:1.3">${rtTitle}</div>
             ${stepPreview.length ? `<div style="display:flex;flex-direction:column;gap:5px;width:100%">${stepPreview.map((s,i)=>`<div style="display:flex;align-items:center;gap:7px"><span style="width:18px;height:18px;border-radius:50%;background:${rtAccent};color:${rtAccentText};font-size:.6rem;font-weight:700;display:grid;place-items:center;flex-shrink:0">${i+1}</span><span style="color:rgba(255,255,255,.7);font-size:.73rem">${s}</span></div>`).join('')}${p.steps.length>3?`<span style="color:rgba(255,255,255,.3);font-size:.7rem;margin-left:25px">+${p.steps.length-3} more steps</span>`:''}</div>` : (rtDesc ? `<div style="color:rgba(255,255,255,.55);font-size:.76rem;line-height:1.45">${rtDesc.substring(0,90)}${rtDesc.length>90?'…':''}</div>` : '')}
             ${prodChips ? `<div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:2px">${prodChips}</div>` : ''}
           </div>`}
      <div class="mc-overlay">
        <div class="mc-hover-btns">
          <button class="mc-btn like-btn" data-id="${id}" onclick="event.stopPropagation();toggleLike('${id}',this)" style="${liked?'color:#e63946':''}">♥</button>
          <button class="mc-btn" onclick="event.stopPropagation();sharePost('${id}')">↗</button>
        </div>
      </div>
    </div>
    <div class="mc-body">
      <div class="mc-user">
        <div class="mc-avatar" style="background:${p.user.color};color:${p.user.textColor||'#0A0A0A'}">${p.user.av}</div>
        <div><div class="mc-uname">${p.user.name}</div><div class="mc-skin">${p.user.skin}</div></div>
        <span class="mc-time">${p.time}</span>
      </div>
      ${hasImg ? `<div style="font-size:.75rem;font-weight:700;color:#374151;margin-bottom:4px">${rtBadge} · ${rtTitle}</div>` : ''}
      ${rtDesc ? `<div class="mc-caption">${rtDesc}</div>` : ''}
      ${prodChips && hasImg ? `<div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px">${prodChips}</div>` : ''}
      <div class="mc-tags">${(p.tags||[]).map(t=>`<span class="mc-tag" onclick="event.stopPropagation();filterByTag('${t}')">${t}</span>`).join('')}</div>
      <div class="mc-footer">
        <button class="mc-stat like-stat${liked?' liked':''}" data-id="${id}" onclick="event.stopPropagation();toggleLikeStat('${id}',this)">♥ ${p.likes.toLocaleString()}</button>
        <button class="mc-stat comment-stat" data-id="${id}" onclick="event.stopPropagation();openLightbox('${id}')">💬 ${p.comments}</button>
        <button class="mc-stat" onclick="event.stopPropagation();sharePost('${id}')" style="margin-left:auto">↗</button>
      </div>
    </div>
  </div>`;
}

// ── LIKE STATE ──────────────────────────────────────────────────────────────
const likedPosts = new Set();
function _persistLike(id) {
  fetch(`${COMMUNITY_API}/${id}/like`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
  }).catch(() => {});
  const post = COMMUNITY_POSTS.find(p => p.id === id);
  if (likedPosts.has(id)) {
    _logActivity({ type: 'like', post_id: id, post_caption: post?.caption || post?.quote || '', post_type: post?.type || 'photo', user: 'A member' });
  }
}
function _requireLogin(action) {
  if (IS_LOGGED_IN) return true;
  showToast('🔒', `<a href="${LOGIN_URL}" style="color:#D4D994;font-weight:700">Sign in</a> to ${action}`);
  return false;
}
function toggleLike(id, btn) {
  if (!_requireLogin('like posts')) return;
  if (likedPosts.has(id)) { showToast('♥', "You've already liked this post!"); return; }
  likedPosts.add(id);
  btn.style.color = '#e63946';
  const stat = document.querySelector(`.like-stat[data-id="${id}"]`);
  if (stat) { stat.classList.add('liked'); }
  updateLikeDisplay(id, 1);
  showToast('♥', 'Post liked!');
  _persistLike(id);
}
function toggleLikeStat(id, el) {
  if (!_requireLogin('like posts')) return;
  if (likedPosts.has(id)) { showToast('♥', "You've already liked this post!"); return; }
  likedPosts.add(id);
  el.classList.add('liked');
  el.style.color = '#e63946';
  document.querySelectorAll(`.like-btn[data-id="${id}"]`).forEach(b => b.style.color = '#e63946');
  updateLikeDisplay(id, 1);
  showToast('♥', 'Post liked!');
  _persistLike(id);
}
function updateLikeDisplay(id, delta) {
  document.querySelectorAll(`.like-stat[data-id="${id}"]`).forEach(el => {
    const num = parseInt(el.textContent.replace(/[^0-9]/g,'')) + delta;
    el.textContent = `♥ ${num.toLocaleString()}`;
  });
  const post = COMMUNITY_POSTS.find(p => p.id === id);
  if (post) post.likes += delta;
  const lbCount = document.getElementById('lb-like-count');
  if (lbCount && parseInt(document.getElementById('lb-like-btn')?.dataset.postId) === id) {
    lbCount.textContent = post.likes.toLocaleString();
  }
}

// ── SHARE ───────────────────────────────────────────────────────────────────
function sharePost(id) {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(window.location.href + '#post-' + id).then(() => showToast('↗', 'Link copied to clipboard!'));
  } else {
    showToast('↗', 'Link copied to clipboard!');
  }
}

// ── FILTER & SORT ────────────────────────────────────────────────────────────
let activeFilter = 'all';
function applyFilter(filter) {
  activeFilter = filter;
  let filtered = [...COMMUNITY_POSTS];
  if (filter !== 'all') {
    filtered = COMMUNITY_POSTS.filter(p =>
      p.user.skin?.toLowerCase().includes(filter) ||
      (filter === 'transformation' && p.type === 'before_after') ||
      (filter === 'review' && p.type === 'review') ||
      (filter === 'routine' && p.type === 'routine') ||
      p.tags?.some(t => t.toLowerCase().includes(filter))
    );
  }
  const sortVal = document.getElementById('c-sort')?.value || 'trending';
  filtered = sortPosts(filtered, sortVal);
  renderMasonry(filtered);
}
function sortPosts(posts, val) {
  const timeOrder = {'1h ago':1,'3h ago':2,'5h ago':3,'6h ago':4,'8h ago':5,'10h ago':6,'12h ago':7,'14h ago':8,'16h ago':9,'1d ago':10};
  if (val === 'latest') return [...posts].sort((a,b) => (timeOrder[a.time]||99) - (timeOrder[b.time]||99));
  if (val === 'loved') return [...posts].sort((a,b) => b.likes - a.likes);
  return [...posts].sort((a,b) => (b.likes * 0.6 + b.comments * 0.4) - (a.likes * 0.6 + a.comments * 0.4));
}
function filterByTag(tag) {
  const clean = tag.replace('#','').toLowerCase();
  document.querySelectorAll('.fpill').forEach(b => b.classList.remove('active'));
  document.querySelector('.fpill[data-filter="all"]')?.classList.add('active');
  const filtered = COMMUNITY_POSTS.filter(p => p.tags?.some(t => t.toLowerCase().includes(clean)));
  renderMasonry(filtered);
  document.getElementById('masonry-grid')?.scrollIntoView({ behavior:'smooth', block:'start' });
  showToast('🔍', `Filtered by ${tag}`);
}
function filterByUser(name) {
  const filtered = COMMUNITY_POSTS.filter(p => p.user.name === name);
  renderMasonry(filtered);
  document.getElementById('masonry-grid')?.scrollIntoView({ behavior:'smooth', block:'start' });
  showToast('👤', `Viewing posts by ${name}`);
}

document.querySelectorAll('.fpill').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.fpill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilter(btn.dataset.filter);
  });
});
document.getElementById('c-sort')?.addEventListener('change', () => applyFilter(activeFilter));

// ── RENDER ───────────────────────────────────────────────────────────────────
function renderMasonry(posts) {
  const grid = document.getElementById('masonry-grid');
  if (!grid) return;
  const countEl = document.getElementById('post-count');
  if (countEl) countEl.textContent = `Showing ${posts.length.toLocaleString()} posts`;
  if (!posts.length) {
    grid.innerHTML = '<div style="text-align:center;padding:40px;color:#6B7280;font-size:.95rem">No posts match this filter yet.<br><button class="btn btn-outline btn-sm" onclick="applyFilter(\'all\')" style="margin-top:12px">View all posts</button></div>';
    return;
  }
  grid.innerHTML = posts.map(p => {
    if (p.type === 'before_after') return buildBACard(p);
    if (p.type === 'review')       return buildReviewCard(p);
    if (p.type === 'routine')      return buildRoutineCard(p);
    return buildPhotoCard(p);
  }).join('');
}

// Load More
let loadCount = 0;
document.getElementById('load-more-btn')?.addEventListener('click', () => {
  loadCount++;
  const btn = document.getElementById('load-more-btn');
  btn.textContent = 'Loading…';
  btn.disabled = true;
  setTimeout(() => {
    const extra = [...COMMUNITY_POSTS].sort(() => Math.random() - 0.5).slice(0, 4);
    const grid = document.getElementById('masonry-grid');
    extra.forEach(p => {
      const div = document.createElement('div');
      const newP = { ...p, id: p.id + loadCount * 100, time: 'Just now', likes: Math.floor(p.likes * 0.3) };
      div.innerHTML = p.type === 'before_after' ? buildBACard(newP) : p.type === 'review' ? buildReviewCard(newP) : p.type === 'routine' ? buildRoutineCard(newP) : buildPhotoCard(newP);
      grid.appendChild(div.firstElementChild);
    });
    btn.textContent = 'Load More Posts';
    btn.disabled = false;
    if (loadCount >= 3) { btn.textContent = "You've seen it all ✨"; btn.disabled = true; }
  }, 600);
});

// ── TOP GLOWERS ──────────────────────────────────────────────────────────────
function renderTopGlowers() {
  const el = document.getElementById('top-glowers');
  if (!el) return;
  el.innerHTML = TOP_GLOWERS.map(g => `
    <div class="glower-row" onclick="filterByUser('${g.name}')" title="View ${g.name}'s posts">
      <span class="g-rank ${g.rankClass||''}">${g.rank}</span>
      <div class="g-av" style="background:${g.color};color:${g.textColor||'#fff'}">${g.av}</div>
      <div style="flex:1">
        <div style="font-weight:700;font-size:.85rem">${g.name}</div>
        <div style="font-size:.72rem;color:#9CA3AF">${g.posts} posts · ${g.skin}</div>
      </div>
      <span class="g-pts">${g.pts} pts</span>
    </div>`).join('');
}

// ── TRENDING TAGS ────────────────────────────────────────────────────────────
function renderTrending() {
  const el = document.getElementById('trendingTags');
  if (!el) return;
  el.innerHTML = TRENDING_TAGS.map(t => {
    const tag = t.split(' ')[0];
    return `<span class="ttag" onclick="filterByTag('${tag}')">${t}</span>`;
  }).join('');
}

// ── FEATURED POST BUTTONS ────────────────────────────────────────────────────
document.getElementById('feat-like')?.addEventListener('click', function() {
  if (!_requireLogin('like posts')) return;
  if (likedPosts.has(FEATURED_POST_ID)) { showToast('♥', "You've already liked this post!"); return; }
  likedPosts.add(FEATURED_POST_ID);
  this.classList.add('liked');
  this.style.color = '#e63946';
  const post = COMMUNITY_POSTS.find(p => p.id === FEATURED_POST_ID) || FALLBACK_FEATURED;
  post.likes = (post.likes || 0) + 1;
  this.innerHTML = `♥ ${post.likes.toLocaleString()}`;
  _persistLike(FEATURED_POST_ID);
  showToast('♥', 'Post liked!');
});
document.getElementById('feat-comment')?.addEventListener('click', () => openFeaturedPost());
document.getElementById('feat-share')?.addEventListener('click', () => sharePost(FEATURED_POST_ID));

document.querySelectorAll('.prod-pill').forEach(el => {
  el.addEventListener('click', () => {
    const term = el.textContent.trim();
    const filtered = COMMUNITY_POSTS.filter(p => p.products?.some(pr => pr.includes(term)));
    renderMasonry(filtered.length ? filtered : COMMUNITY_POSTS);
    showToast('🧴', `Showing posts with ${term}`);
    document.getElementById('masonry-grid')?.scrollIntoView({ behavior:'smooth' });
  });
});
document.querySelectorAll('.ptag').forEach(el => {
  el.addEventListener('click', () => filterByTag(el.textContent.trim()));
});

// ── LIGHTBOX ─────────────────────────────────────────────────────────────────
let lbImages = [];
let lbSlideIdx = 0;

function lbGoTo(idx) {
  if (idx < 0 || idx >= lbImages.length) return;
  lbSlideIdx = idx;
  const track = document.getElementById('lb-track');
  if (track) track.style.transform = `translateX(-${idx * 100}%)`;
  document.querySelectorAll('.lb-dot').forEach((d, i) => d.classList.toggle('active', i === idx));
  const counter = document.getElementById('lb-counter');
  if (counter) counter.textContent = `${idx + 1} / ${lbImages.length}`;
  const prev = document.querySelector('.lb-prev');
  const next = document.querySelector('.lb-next');
  if (prev) { prev.style.opacity = idx === 0 ? '0' : '1'; prev.style.pointerEvents = idx === 0 ? 'none' : ''; }
  if (next) { next.style.opacity = idx === lbImages.length - 1 ? '0' : '1'; next.style.pointerEvents = idx === lbImages.length - 1 ? 'none' : ''; }
}

function _renderLBCarousel(imgs) {
  lbImages = imgs;
  lbSlideIdx = 0;
  const single = imgs.length === 1;
  if (single) {
    return `<img src="${imgs[0]}" style="width:100%;height:100%;object-fit:cover;display:block" alt="">`;
  }
  return `
    <div class="lb-carousel">
      <div class="lb-carousel-track" id="lb-track">
        ${imgs.map(src => `<div class="lb-carousel-slide"><img src="${src}" alt=""></div>`).join('')}
      </div>
      <button class="lb-carousel-btn lb-prev" onclick="lbGoTo(lbSlideIdx-1)" style="opacity:0;pointer-events:none">‹</button>
      <button class="lb-carousel-btn lb-next" onclick="lbGoTo(lbSlideIdx+1)">›</button>
      <div class="lb-carousel-dots">${imgs.map((_,i) => `<button class="lb-dot${i===0?' active':''}" onclick="lbGoTo(${i})"></button>`).join('')}</div>
      <div class="lb-counter" id="lb-counter">1 / ${imgs.length}</div>
    </div>`;
}

function _attachCarouselTouch(media) {
  let sx = 0, sy = 0;
  media.addEventListener('touchstart', e => { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, { passive: true });
  media.addEventListener('touchend', e => {
    const dx = sx - e.changedTouches[0].clientX;
    const dy = sy - e.changedTouches[0].clientY;
    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) lbGoTo(lbSlideIdx + (dx > 0 ? 1 : -1));
  });
}

function openLightbox(id) {
  const p = COMMUNITY_POSTS.find(pp => pp.id === id);
  if (!p) return;

  lbImages = [];
  const media = document.getElementById('lb-media');

  if (p.type === 'photo') {
    const imgs = (p.images && p.images.length > 0) ? p.images : (p.img ? [p.img] : []);
    media.innerHTML = _renderLBCarousel(imgs);
    if (imgs.length > 1) _attachCarouselTouch(media);
  } else if (p.type === 'before_after') {
    const hasSrc = s => s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:'));
    const hasBefore = hasSrc(p.beforeImg);
    const hasAfter  = hasSrc(p.afterImg);
    const cols = hasBefore && hasAfter ? '1fr 1fr' : '1fr';
    media.innerHTML = `
      <div class="lb-ba-grid" style="grid-template-columns:${cols}">
        ${hasBefore ? `<div class="lb-ba-side"><img src="${p.beforeImg}" alt="Before"><span class="lb-ba-label" style="background:rgba(0,0,0,.62);color:#fff">Before</span></div>` : ''}
        ${hasAfter  ? `<div class="lb-ba-side"><img src="${p.afterImg}"  alt="After"><span class="lb-ba-label" style="background:#D4D994;color:#1C1416">After ✨</span></div>` : ''}
        ${!hasBefore && !hasAfter ? `<div style="display:grid;place-items:center;height:100%;color:rgba(255,255,255,.4);font-size:3rem">✨</div>` : ''}
      </div>`;
  } else if (p.type === 'review') {
    const hasSrc = s => s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:'));
    if (hasSrc(p.img)) {
      media.innerHTML = `<img src="${p.img}" style="width:100%;height:100%;object-fit:cover;display:block" alt="">`;
    } else {
      const sv = Math.min(Math.max(parseInt(p.stars) || 4, 0), 5);
      media.innerHTML = `<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:16px;padding:28px">
        <div style="font-size:2.8rem;color:#F59E0B">${'★'.repeat(sv)}${'☆'.repeat(5-sv)}</div>
        <div style="font-family:var(--font-display);font-size:1.3rem;color:#fff;text-align:center;font-style:italic;line-height:1.5">"${p.quote || p.caption || ''}"</div>
        ${p.product ? `<span style="background:rgba(212,217,148,.15);color:#D4D994;padding:6px 18px;border-radius:100px;font-size:.78rem;font-weight:700">🧴 ${p.product}</span>` : ''}
      </div>`;
    }
  } else {
    // Routine lightbox — rich step-by-step display
    const hasSrc = s => s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:'));
    const rtType = p.routineType || 'AM';
    const rtMeta = {
      AM:     { emoji:'☀️', label:'AM Routine',     bg:'linear-gradient(160deg,#1a1000 0%,#0A0A0A 100%)', accent:'#F59E0B', text:'#0A0A0A' },
      PM:     { emoji:'🌙', label:'PM Routine',     bg:'linear-gradient(160deg,#0d0a1a 0%,#0A0A0A 100%)', accent:'#8B5CF6', text:'#fff' },
      Weekly: { emoji:'📅', label:'Weekly Routine', bg:'linear-gradient(160deg,#000f1a 0%,#0A0A0A 100%)', accent:'#0EA5E9', text:'#0A0A0A' },
    };
    const m = rtMeta[rtType] || rtMeta.AM;

    const cap = p.caption || '';
    const colon = cap.indexOf(':');
    const rtTitle = (colon > -1 ? cap.substring(0, colon) : cap).trim();
    const rtDesc  = (colon > -1 ? cap.substring(colon + 1) : '').trim();

    const stepsHtml = p.steps && p.steps.length
      ? `<div style="margin-top:20px">
          <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.3);margin-bottom:12px">Routine Steps</div>
          <div style="display:flex;flex-direction:column;gap:10px">
            ${p.steps.map((s,i) => `
              <div style="display:flex;align-items:center;gap:12px">
                <span style="width:26px;height:26px;border-radius:50%;background:${m.accent};color:${m.text};font-size:.7rem;font-weight:700;display:grid;place-items:center;flex-shrink:0">${i+1}</span>
                <span style="color:rgba(255,255,255,.85);font-size:.86rem;font-weight:500">${s}</span>
              </div>`).join('')}
          </div>
        </div>`
      : '';

    const descHtml = rtDesc
      ? `<p style="color:rgba(255,255,255,.5);font-size:.82rem;line-height:1.6;margin-top:16px">${rtDesc}</p>`
      : '';

    const flatLayHtml = hasSrc(p.img)
      ? `<img src="${p.img}" style="width:100%;border-radius:14px;margin-top:20px;max-height:190px;object-fit:cover;flex-shrink:0" alt="Flat lay">`
      : '';

    media.innerHTML = `
      <div style="height:100%;display:flex;flex-direction:column;overflow-y:auto;padding:28px;background:${m.bg}">
        <div>
          <span style="background:${m.accent};color:${m.text};font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:4px 12px;border-radius:100px;display:inline-flex;align-items:center;gap:5px">${m.emoji} ${m.label}</span>
          <div style="font-family:var(--font-display);font-size:1.45rem;color:#fff;margin-top:12px;line-height:1.3">${rtTitle || 'My Skincare Routine'}</div>
        </div>
        ${stepsHtml}
        ${descHtml}
        ${flatLayHtml}
      </div>`;
  }

  document.getElementById('lb-avatar').style.cssText = `background:${p.user.color};color:${p.user.textColor||'#fff'};width:44px;height:44px;border-radius:50%;display:grid;place-items:center;font-weight:700;font-size:.75rem;flex-shrink:0`;
  document.getElementById('lb-avatar').textContent = p.user.av;
  document.getElementById('lb-name').textContent = p.user.name;
  document.getElementById('lb-handle').textContent = `${p.user.handle} · ${p.user.skin}`;
  document.getElementById('lb-time').textContent = p.time;
  document.getElementById('lb-caption').textContent = p.caption || p.quote || '';
  document.getElementById('lb-tags').innerHTML = (p.tags||[]).map(t => `<span class="mc-tag" style="cursor:pointer" onclick="closeLightbox();filterByTag('${t}')">${t}</span>`).join('');
  document.getElementById('lb-products').innerHTML = (p.products||[]).map(pr => `<span style="background:#F3F4F6;padding:4px 12px;border-radius:100px;font-size:.72rem;font-weight:600;cursor:pointer" onclick="showToast('🧴','${pr}')">🧴 ${pr}</span>`).join('');

  const likeBtn = document.getElementById('lb-like-btn');
  likeBtn.dataset.postId = id;
  const alreadyLiked = likedPosts.has(id);
  likeBtn.classList.toggle('liked', alreadyLiked);
  likeBtn.style.color = alreadyLiked ? '#e63946' : '#6B7280';
  document.getElementById('lb-like-count').textContent = p.likes.toLocaleString();
  likeBtn.onclick = () => {
    if (!_requireLogin('like posts')) return;
    if (likedPosts.has(id)) { showToast('♥', "You've already liked this post!"); return; }
    likedPosts.add(id); p.likes++;
    likeBtn.style.color = '#e63946'; likeBtn.classList.add('liked');
    document.getElementById('lb-like-count').textContent = p.likes.toLocaleString();
    document.querySelectorAll(`.like-stat[data-id="${id}"]`).forEach(el => { el.textContent = `♥ ${p.likes.toLocaleString()}`; });
    showToast('♥', 'Post liked!');
    _persistLike(id);
  };

  document.getElementById('lb-share-btn').onclick = () => sharePost(id);
  renderLBComments(p.commentList || [], id);

  const lb = document.getElementById('c-lightbox');
  lb.style.display = 'flex';
  lb._postId = id;
  document.body.style.overflow = 'hidden';
}
function renderLBComments(comments, id) {
  const el = document.getElementById('lb-comments');
  if (!el) return;
  el.innerHTML = comments.map(c => `
    <div style="display:flex;gap:10px;align-items:flex-start">
      <div style="width:30px;height:30px;border-radius:50%;background:${c.color};color:#0A0A0A;display:grid;place-items:center;font-weight:700;font-size:.6rem;flex-shrink:0">${c.av}</div>
      <div style="background:#F9F9F9;border-radius:14px;padding:10px 14px;flex:1">
        <div style="font-weight:700;font-size:.8rem;margin-bottom:3px">${c.name} <span style="font-weight:400;color:#9CA3AF;font-size:.72rem">· ${c.time}</span></div>
        <div style="font-size:.85rem;color:#374151">${c.text}</div>
      </div>
    </div>`).join('');
}
async function lbAddComment(e) {
  if (e.key !== 'Enter') return;
  if (!_requireLogin('comment on posts')) return;
  const input = e.target;
  const text  = input.value.trim();
  if (!text) return;

  const lb   = document.getElementById('c-lightbox');
  const post = COMMUNITY_POSTS.find(p => p.id === lb._postId);
  if (!post) return;

  input.value    = '';
  input.disabled = true;

  try {
    const resp = await fetch(`${COMMUNITY_API}/${post.id}/comment`, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
      body: JSON.stringify({ text }),
    });
    const data = await resp.json();

    if (data.success && data.comment) {
      if (!post.commentList) post.commentList = [];
      post.commentList.push(data.comment);
      post.comments++;
      renderLBComments(post.commentList, post.id);
      document.querySelectorAll(`.comment-stat[data-id="${post.id}"]`).forEach(el => {
        el.textContent = `💬 ${post.comments}`;
      });
      const commentsEl = document.getElementById('lb-comments');
      if (commentsEl) commentsEl.scrollTop = commentsEl.scrollHeight;
      showToast('💬', 'Comment posted!');
      _logActivity({ type: 'comment', post_id: post.id, post_caption: post.caption || post.quote || '', post_type: post.type || 'photo', user: 'A member', text });
    } else {
      showToast('❌', 'Failed to post comment.');
    }
  } catch (err) {
    showToast('❌', 'Could not post comment.');
  } finally {
    input.disabled = false;
    input.focus();
  }
}
function closeLightbox() {
  document.getElementById('c-lightbox').style.display = 'none';
  document.body.style.overflow = '';
}
document.getElementById('c-lightbox')?.addEventListener('click', e => {
  if (e.target === document.getElementById('c-lightbox')) closeLightbox();
});

// ── POST CREATION MODAL ───────────────────────────────────────────────────────
function openPostModal() {
  if (!_requireLogin('post to the community')) return;
  // Reset photo state
  pmPhotoImgs.length = 0;
  const strip = document.getElementById('pm-preview-strip');
  if (strip) strip.innerHTML = '';
  // Reset star rating
  pmStarVal = 4; pmResetStars();
  const starLbl = document.getElementById('pm-star-label');
  if (starLbl) starLbl.textContent = '4 out of 5 — Great! 😊';
  // Clear review photo preview
  const rvPrev = document.getElementById('pm-rev-ph-preview');
  if (rvPrev) { rvPrev.src = ''; rvPrev.style.display = 'none'; }
  const rvPh = document.getElementById('pm-rev-ph-ph');
  if (rvPh) rvPh.style.display = '';
  // Clear routine photo preview
  const rtPrev = document.getElementById('pm-rt-ph-preview');
  if (rtPrev) { rtPrev.src = ''; rtPrev.style.display = 'none'; }
  const rtPh = document.getElementById('pm-rt-ph-ph');
  if (rtPh) rtPh.style.display = '';
  // Clear BA previews
  ['pm-before-preview','pm-after-preview'].forEach(id => {
    const el = document.getElementById(id);
    if (el) { el.src = ''; el.style.display = 'none'; }
  });
  const bfPh = document.getElementById('pm-before-ph');
  if (bfPh) bfPh.style.display = '';
  const afPh = document.getElementById('pm-after-ph');
  if (afPh) afPh.style.display = '';
  document.querySelectorAll('.pm-panel').forEach(p => p.style.display = 'none');
  document.querySelectorAll('.pm-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('pm-photo').style.display = '';
  document.querySelector('.pm-tab[data-panel="pm-photo"]')?.classList.add('active');
  document.getElementById('post-modal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
  // Reset routine steps + type
  const stepsEl = document.getElementById('pm-steps');
  if (stepsEl) stepsEl.innerHTML = '';
  pmStepCount = 0;
  pmSetRoutineType('AM');
  initPmSteps();
  pmInitAspectStars();
  pmFilterProds('');
  pmFilterRevProds('');
}
function closePostModal() {
  document.getElementById('post-modal').style.display = 'none';
  document.body.style.overflow = '';
}
document.getElementById('post-modal')?.addEventListener('click', e => {
  if (e.target === document.getElementById('post-modal')) closePostModal();
});
function switchPostTab(id, btn) {
  document.querySelectorAll('.pm-panel').forEach(p => p.style.display = 'none');
  document.querySelectorAll('.pm-tab').forEach(t => t.classList.remove('active'));
  document.getElementById(id).style.display = '';
  btn.classList.add('active');
}
async function submitPost(type) {
  if (!GALLERY_OPEN) { showToast('🔒', 'Submissions are currently closed.'); return; }

  const pts    = { photo:30, before_after:50, review:10, routine:20 };
  const labels = { photo:'Photo posted', before_after:'Transformation shared', review:'Review published', routine:'Routine shared' };

  const fd = new FormData();
  fd.append('_token', CSRF);
  fd.append('type',   type);

  if (type === 'photo') {
    const caption = document.getElementById('pm-caption')?.value?.trim() || '';
    if (!caption) { showToast('⚠️', 'Please add a caption before sharing.'); return; }
    fd.append('caption',   caption);
    fd.append('skin_type', document.getElementById('pm-skintype')?.value || 'Combination');
    const tags = Array.from(document.querySelectorAll('#pm-hashtag-cloud .pm-htag-chip'))
      .map(c => c.childNodes[0]?.textContent?.trim() || c.textContent.replace('×','').trim())
      .filter(Boolean).join(',');
    fd.append('tags', tags);
    if (pmPhotoImgs.length > 0) {
      fd.append('img', pmPhotoImgs[0]);
      if (pmPhotoImgs.length > 1) fd.append('images', JSON.stringify(pmPhotoImgs));
    }

  } else if (type === 'before_after') {
    const story = document.getElementById('pm-ba-caption')?.value?.trim() || '';
    if (!story) { showToast('⚠️', 'Please write your transformation story.'); return; }
    const beforeSrc = document.getElementById('pm-before-preview')?.src || '';
    const afterSrc  = document.getElementById('pm-after-preview')?.src  || '';
    const validSrc  = s => s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:');
    if (!validSrc(afterSrc)) { showToast('⚠️', 'Please upload your after (glow-up) photo.'); return; }
    const period = document.getElementById('pm-ba-period')?.value || '3 months';
    fd.append('caption', `${story} (${period})`);
    if (validSrc(beforeSrc)) fd.append('before_img', beforeSrc);
    fd.append('after_img', afterSrc);

  } else if (type === 'review') {
    const reviewText = document.getElementById('pm-rev-text')?.value?.trim() || '';
    if (!reviewText) { showToast('⚠️', 'Please write your review before submitting.'); return; }
    const product = document.getElementById('pm-rev-product')?.value?.trim() || '';
    fd.append('product', product);
    fd.append('caption', reviewText);
    fd.append('quote',   reviewText);
    fd.append('stars',   String(pmStarVal || 4));
    const revPhotoSrc = document.getElementById('pm-rev-ph-preview')?.src || '';
    const validSrc = s => s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:');
    if (validSrc(revPhotoSrc)) fd.append('img', revPhotoSrc);

  } else if (type === 'routine') {
    const title = document.getElementById('pm-rt-title')?.value?.trim() || 'My Skincare Routine';
    const desc  = document.getElementById('pm-rt-desc')?.value?.trim() || '';
    fd.append('caption', desc ? `${title}: ${desc}` : title);
    // Routine type from active button
    const rtType = document.getElementById('pm-rt-am')?.classList.contains('btn-dark') ? 'AM'
                 : document.getElementById('pm-rt-pm')?.classList.contains('btn-dark') ? 'PM'
                 : document.getElementById('pm-rt-weekly')?.classList.contains('btn-dark') ? 'Weekly' : 'AM';
    fd.append('routine_type', rtType);
    // Steps from step inputs
    const steps = Array.from(document.querySelectorAll('#pm-steps input'))
      .map(i => i.value.trim()).filter(Boolean);
    if (steps.length) fd.append('steps', JSON.stringify(steps));
    const rtPhotoSrc = document.getElementById('pm-rt-ph-preview')?.src || '';
    const validSrc = s => s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:');
    if (validSrc(rtPhotoSrc)) fd.append('img', rtPhotoSrc);
  }

  closePostModal();
  showToast('⏳', 'Submitting…');

  try {
    const resp = await fetch('{{ route("community.post") }}', { method:'POST', body: fd });
    const data = await resp.json();

    if (data.success) {
      if (data.status === 'approved' && data.post) {
        const p = normalisePost(data.post);
        COMMUNITY_POSTS.unshift(p);
        renderMasonry(COMMUNITY_POSTS);
        document.getElementById('masonry-grid')?.scrollIntoView({ behavior:'smooth' });
        showToast('✨', `${labels[type]}! +${pts[type]} pts 🎉`);
      } else {
        showToast('⏳', 'Post submitted! It will appear after admin approval.');
      }
    } else {
      showToast('❌', data.message || 'Submission failed. Please try again.');
    }
  } catch (e) {
    showToast('❌', 'Could not submit. Check your connection and try again.');
  }
}

// Photo upload — explicit array so submitPost always has the correct list
const pmPhotoImgs = [];
function pmHandleFiles(files) {
  const strip = document.getElementById('pm-preview-strip');
  if (!strip) return;
  Array.from(files).slice(0, 6 - pmPhotoImgs.length).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const src = e.target.result;
      const idx = pmPhotoImgs.push(src) - 1;
      const wrap = document.createElement('div');
      wrap.style.cssText = 'position:relative;width:70px;height:70px';
      wrap.dataset.idx = idx;
      wrap.innerHTML = `<img src="${src}" style="width:70px;height:70px;border-radius:10px;object-fit:cover;border:2px solid #EFF0F2"><button onclick="pmRemovePhoto(this)" style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;border-radius:50%;background:#893941;color:#fff;border:none;font-size:.6rem;cursor:pointer;display:grid;place-items:center;font-weight:700">×</button>`;
      strip.appendChild(wrap);
      document.getElementById('pm-drop-zone')?.classList.add('drag-over');
      setTimeout(() => document.getElementById('pm-drop-zone')?.classList.remove('drag-over'), 300);
    };
    reader.readAsDataURL(file);
  });
}
function pmRemovePhoto(btn) {
  const wrap = btn.parentElement;
  const src = wrap.querySelector('img')?.src;
  const i = pmPhotoImgs.indexOf(src);
  if (i > -1) pmPhotoImgs.splice(i, 1);
  wrap.remove();
}
function pmDragOver(e) { e.preventDefault(); document.getElementById('pm-drop-zone').classList.add('drag-over'); }
function pmDragLeave() { document.getElementById('pm-drop-zone').classList.remove('drag-over'); }
function pmDrop(e) { e.preventDefault(); pmDragLeave(); pmHandleFiles(e.dataTransfer.files); }

function pmBAPreview(input, previewId, phId) {
  const file = input.files[0]; if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById(previewId);
    const ph  = document.getElementById(phId);
    if (img) { img.src = e.target.result; img.style.display = 'block'; }
    if (ph)  ph.style.display = 'none';
  };
  reader.readAsDataURL(file);
}
function pmRevPhotoPreview(input) {
  const file = input.files[0]; if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('pm-rev-ph-preview').src = e.target.result;
    document.getElementById('pm-rev-ph-preview').style.display = 'block';
    document.getElementById('pm-rev-ph-ph').style.display = 'none';
  };
  reader.readAsDataURL(file);
}
function pmRtPhotoPreview(input) {
  const file = input.files[0]; if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('pm-rt-ph-preview').src = e.target.result;
    document.getElementById('pm-rt-ph-preview').style.display = 'block';
    document.getElementById('pm-rt-ph-ph').style.display = 'none';
  };
  reader.readAsDataURL(file);
}

// Star rating
let pmStarVal = 4;
const pmStarLabels = ['','Terrible 😞','Poor 😕','Average 😐','Great! 😊','Outstanding! 🤩'];
function pmHoverStar(n) { document.querySelectorAll('.pm-star').forEach((s,i) => s.style.color = i < n ? '#F59E0B' : '#E5E7EB'); }
function pmResetStars() { document.querySelectorAll('.pm-star').forEach((s,i) => s.style.color = i < pmStarVal ? '#F59E0B' : '#E5E7EB'); }
function pmSetStar(n) { pmStarVal = n; pmResetStars(); const lbl=document.getElementById('pm-star-label'); if(lbl) lbl.textContent = `${n} out of 5 — ${pmStarLabels[n]}`; }

// Routine type toggle
function pmSetRoutineType(type) {
  ['AM','PM','Weekly'].forEach(t => {
    const btn = document.getElementById('pm-rt-' + t.toLowerCase());
    if (btn) btn.className = t === type ? 'btn btn-dark btn-sm' : 'btn btn-outline btn-sm';
  });
}

// Routine steps
let pmStepCount = 0;
function initPmSteps() {
  const container = document.getElementById('pm-steps');
  if (!container || container.children.length) return;
  ['Cleanser','Toner / Essence','Serum','Moisturiser','SPF'].forEach(label => pmAddStep(label));
}
function pmAddStep(label) {
  pmStepCount++;
  const container = document.getElementById('pm-steps');
  if (!container) return;
  const row = document.createElement('div');
  row.style.cssText = 'display:flex;align-items:center;gap:8px';
  row.innerHTML = `<span style="width:22px;height:22px;border-radius:50%;background:#D4D994;color:#1C1416;font-size:.7rem;font-weight:700;display:grid;place-items:center;flex-shrink:0">${pmStepCount}</span><input placeholder="${label||'Step '+pmStepCount}" style="flex:1;padding:8px 14px;border:1.5px solid #EFF0F2;border-radius:100px;font-size:.82rem;font-family:inherit;outline:none" onfocus="this.style.borderColor='#D4D994'" onblur="this.style.borderColor='#EFF0F2'"><button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#893941;font-size:1rem;padding:4px 8px">×</button>`;
  container.appendChild(row);
}

// Hashtag chips
function pmAddHashtag(e) {
  if (e.key !== 'Enter') return;
  const input = e.target;
  let val = input.value.trim().replace(/^#*/,'');
  if (!val) return;
  const cloud = document.getElementById('pm-hashtag-cloud');
  if (!cloud) return;
  const chip = document.createElement('span');
  chip.className = 'pm-htag-chip';
  chip.innerHTML = `#${val} <button onclick="this.parentElement.remove()">×</button>`;
  cloud.appendChild(chip);
  input.value = '';
}

// Product tag search (photo panel)
const PM_PROD_LIST = [
  { name:'COSRX Snail Mucin Essence', brand:'COSRX', img:'https://images.unsplash.com/photo-1620916566396-4c7aa9a87879?w=64&h=64&fit=crop' },
  { name:'Beauty of Joseon Relief Sun SPF 50+', brand:'Beauty of Joseon', img:'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=64&h=64&fit=crop' },
  { name:'The Ordinary Niacinamide 10%', brand:'The Ordinary', img:'https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=64&h=64&fit=crop' },
  { name:'Laneige Water Sleeping Mask', brand:'Laneige', img:'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=64&h=64&fit=crop' },
  { name:'Innisfree Green Tea Cream', brand:'Innisfree', img:'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=64&h=64&fit=crop' },
  { name:'Some By Mi AHA BHA PHA Toner', brand:'Some By Mi', img:'https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?w=64&h=64&fit=crop' },
  { name:'Medicube Age-R Booster Pro', brand:'Medicube', img:'https://images.unsplash.com/photo-1620916566396-4c7aa9a87879?w=64&h=64&fit=crop' },
];
const pmTaggedProds = [];
function pmFilterProds(val) {
  const dd = document.getElementById('pm-prod-dropdown');
  if (!dd) return;
  const q = val.toLowerCase();
  const filtered = PM_PROD_LIST.filter(p => p.name.toLowerCase().includes(q) || p.brand.toLowerCase().includes(q));
  dd.innerHTML = filtered.map(p =>
    `<div class="pm-prod-item" onmousedown="pmAddProdTag('${p.name}','${p.img}')"><img src="${p.img}" alt=""><div><div style="font-weight:700">${p.name}</div><div style="font-size:.72rem;color:#9CA3AF">${p.brand}</div></div></div>`
  ).join('') || '<div class="pm-prod-item" style="color:#9CA3AF">No matching products</div>';
}
function pmAddProdTag(name, img) {
  if (pmTaggedProds.includes(name)) return;
  pmTaggedProds.push(name);
  const list = document.getElementById('pm-tagged-prods');
  if (!list) return;
  const chip = document.createElement('span');
  chip.className = 'pm-prod-chip';
  chip.innerHTML = `<img src="${img}" alt="">${name}<button onclick="pmRemoveProdTag(this,'${name}')">×</button>`;
  list.appendChild(chip);
  const input = document.getElementById('pm-prod-search');
  if (input) input.value = '';
}
function pmRemoveProdTag(btn, name) {
  const i = pmTaggedProds.indexOf(name);
  if (i > -1) pmTaggedProds.splice(i, 1);
  btn.parentElement.remove();
}

// Product search (review panel)
function pmFilterRevProds(val) {
  const dd = document.getElementById('pm-rev-prod-dropdown');
  if (!dd) return;
  const q = val.toLowerCase();
  const filtered = PM_PROD_LIST.filter(p => p.name.toLowerCase().includes(q) || p.brand.toLowerCase().includes(q));
  dd.innerHTML = filtered.map(p =>
    `<div class="pm-prod-item" onmousedown="pmSelectRevProduct('${p.name}','${p.img}')"><img src="${p.img}" alt=""><div style="font-weight:700">${p.name}</div></div>`
  ).join('');
}
function pmSelectRevProduct(name, img) {
  const input = document.getElementById('pm-rev-product');
  if (input) input.value = name;
  const el = document.getElementById('pm-rev-selected');
  if (el) el.innerHTML = `<div style="display:flex;align-items:center;gap:10px;background:rgba(212,217,148,.12);border-radius:12px;padding:8px 12px"><img src="${img}" style="width:32px;height:32px;border-radius:6px;object-fit:cover"><span style="font-size:.84rem;font-weight:700">${name}</span></div>`;
}

// Aspect star ratings (review panel)
function pmInitAspectStars() {
  ['pm-asp-effect','pm-asp-texture','pm-asp-value'].forEach(id => {
    const c = document.getElementById(id);
    if (!c || c.children.length) return;
    const val = parseInt(c.dataset.val) || 4;
    c.innerHTML = [1,2,3,4,5].map(n =>
      `<span class="pm-asp-star" style="cursor:pointer;font-size:.9rem;color:${n<=val?'#F59E0B':'#E5E7EB'}" onmouseover="pmHoverAsp('${id}',${n})" onclick="pmSetAsp('${id}',${n})">★</span>`
    ).join('');
  });
}
function pmHoverAsp(id, n) {
  document.querySelectorAll(`#${id} .pm-asp-star`).forEach((s,i) => s.style.color = i < n ? '#F59E0B' : '#E5E7EB');
}
function pmSetAsp(id, n) {
  const c = document.getElementById(id);
  if (c) c.dataset.val = n;
  pmHoverAsp(id, n);
}

// Escape closes modals
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeLightbox(); closePostModal(); closeCommunitySearch(); }
  if (e.key === 'ArrowLeft'  && lbImages.length > 1 && document.getElementById('c-lightbox')?.style.display !== 'none') lbGoTo(lbSlideIdx - 1);
  if (e.key === 'ArrowRight' && lbImages.length > 1 && document.getElementById('c-lightbox')?.style.display !== 'none') lbGoTo(lbSlideIdx + 1);
});

// ── INIT ─────────────────────────────────────────────────────────────────────
async function initCommunity() {
  // Show skeleton while loading
  const grid = document.getElementById('masonry-grid');
  if (grid) grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px 0;color:#9CA3AF;font-size:.9rem">Loading community posts…</div>';

  try {
    const resp = await fetch('{{ route("community.posts") }}', { headers: { 'Accept': 'application/json' } });
    const data = await resp.json();

    COMMUNITY_POSTS     = (data.posts || []).map(normalisePost);
    FEATURED_POST_ID    = data.featured_post_id ?? 11;
    GALLERY_OPEN        = data.submissions_open !== false;

    // Pre-populate liked state from API response
    COMMUNITY_POSTS.forEach(p => { if (p.user_liked) likedPosts.add(p.id); });

    // Prepend locally-submitted posts not yet in API (from dashboard); clean up old/approved ones
    try {
      const stored  = JSON.parse(localStorage.getItem('kominhoo_community_posts') || '[]');
      if (stored.length) {
        const apiIds = new Set(COMMUNITY_POSTS.map(p => p.id));
        const now    = Date.now();
        const fresh  = stored.filter(p => {
          if (!p.id || apiIds.has(p.id)) return false; // already live in API — drop from local
          const age = now - new Date(p.submitted_at || 0).getTime();
          return age < 3600000; // drop posts older than 1h (likely rejected or expired)
        });
        localStorage.setItem('kominhoo_community_posts', JSON.stringify(fresh));
        if (fresh.length) COMMUNITY_POSTS = [...fresh.map(normalisePost), ...COMMUNITY_POSTS];
      }
    } catch (_) {}
  } catch (err) {
    console.warn('Community: failed to load posts from API', err);
    if (grid) grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px 0;color:#9CA3AF;font-size:.9rem">Could not load posts. Please refresh.</div>';
    COMMUNITY_POSTS = [{ ...FALLBACK_FEATURED }];
  }

  // Ensure the featured post (id=11 fallback or API-specified) is always in the array
  // so the lightbox can open it regardless of API state.
  if (!COMMUNITY_POSTS.find(p => p.id === FEATURED_POST_ID)) {
    COMMUNITY_POSTS.unshift({ ...FALLBACK_FEATURED, id: FEATURED_POST_ID });
  }

  renderMasonry(COMMUNITY_POSTS);
  renderTopGlowers();
  renderTrending();

  // Sync featured card counts + liked state from API data
  const featPost = COMMUNITY_POSTS.find(p => p.id === FEATURED_POST_ID);
  if (featPost) {
    const featLikeBtn = document.getElementById('feat-like');
    const featCommentBtn = document.getElementById('feat-comment');
    if (featLikeBtn) {
      featLikeBtn.innerHTML = `♥ ${featPost.likes.toLocaleString()}`;
      if (likedPosts.has(FEATURED_POST_ID)) {
        featLikeBtn.classList.add('liked');
        featLikeBtn.style.color = '#e63946';
      }
    }
    if (featCommentBtn) {
      featCommentBtn.textContent = `💬 ${featPost.comments} comment${featPost.comments !== 1 ? 's' : ''}`;
    }
  }
}
initCommunity();
</script>
@endsection
