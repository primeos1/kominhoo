@extends('layouts.app')
@section('title', 'My Dashboard — Kominhoo Beauty')

@section('head')
<style>
/* ── Dashboard Gift Card Cards ──────────────────────────────── */
.kmh-gc-card {
  position: relative;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.14);
  transition: transform .22s cubic-bezier(.25,.8,.25,1), box-shadow .22s;
  cursor: default;
}
.kmh-gc-card:hover { transform: translateY(-3px); box-shadow: 0 16px 48px rgba(0,0,0,.2); }
.kmh-gc-layer { position: absolute; inset: 0; pointer-events: none; }
.kmh-gc-layer.gloss {
  background: linear-gradient(140deg, rgba(255,255,255,.2) 0%, rgba(255,255,255,.04) 45%, transparent 100%);
  z-index: 2;
}
.kmh-gc-layer.edge {
  background: linear-gradient(180deg, rgba(255,255,255,.06) 0%, transparent 35%, transparent 65%, rgba(0,0,0,.18) 100%);
  z-index: 3;
}
.kmh-gc-body {
  position: relative; z-index: 4;
  padding: 16px 18px; height: 100%;
  display: flex; flex-direction: column; justify-content: space-between;
  color: #fff;
}
.kmh-gc-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 10px; }
.kmh-gc-brand { font-family: var(--font-display,serif); font-size: .52rem; font-weight: 400; letter-spacing: .18em; opacity: .45; }
.kmh-gc-brand .dot { opacity: .7; }
.kmh-gc-badge {
  display: inline-flex; align-items: center; gap: 5px;
  background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
  border-radius: 999px; padding: 3px 10px;
  font-size: .62rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;
}
.kmh-gc-badge .pill {
  width: 6px; height: 6px; border-radius: 50%;
  background: rgba(255,255,255,.8); flex-shrink: 0;
  box-shadow: 0 0 5px rgba(255,255,255,.5);
}
.kmh-gc-mid { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 4px 0 8px; }
.kmh-gc-amount { font-family: var(--font-display,serif); font-size: 1.45rem; line-height: 1; margin-bottom: 5px; }
.kmh-gc-msg { font-size: .72rem; opacity: .6; line-height: 1.5; font-style: italic; }
.kmh-gc-bottom {
  display: flex; align-items: center; justify-content: space-between; gap: 8px;
  border-top: 1px solid rgba(255,255,255,.12); padding-top: 10px;
}
.kmh-gc-code {
  display: inline-flex; align-items: center; gap: 5px;
  background: rgba(0,0,0,.28); border: 1px solid rgba(255,255,255,.14);
  border-radius: 7px; padding: 5px 10px;
  color: #fff; cursor: pointer; transition: background .15s;
  font-family: 'DM Sans', system-ui, sans-serif; font-size: .72rem;
  font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
}
.kmh-gc-code:hover { background: rgba(0,0,0,.45); }
.kmh-gc-code .label { font-size: .5rem; font-weight: 700; letter-spacing: .1em; opacity: .55; text-transform: uppercase; }
.kmh-gc-details { font-size: .62rem; opacity: .5; white-space: nowrap; }
.kmh-gc-tofrom { font-size: .68rem; opacity: .65; line-height: 1.6; text-align: right; }
.kmh-gc-tofrom strong { opacity: 1; }
.kmh-gc-meta { display: flex; align-items: flex-end; }
/* Theme backgrounds */
.kmh-gc-card[data-theme="minimal"] { background: linear-gradient(148deg,#1a1a2e 0%,#16213e 55%,#0f3460 100%); }
.kmh-gc-card[data-theme="minimal"] .kmh-gc-layer.pattern { background: radial-gradient(ellipse at 80% 20%,rgba(99,102,241,.25) 0%,transparent 55%), radial-gradient(ellipse at 20% 80%,rgba(59,130,246,.2) 0%,transparent 55%); }
.kmh-gc-card[data-theme="luxe"] { background: linear-gradient(148deg,#0a0a0a 0%,#1c1c1c 55%,#0d0d0d 100%); }
.kmh-gc-card[data-theme="luxe"] .kmh-gc-layer.pattern { background: radial-gradient(ellipse at 85% 15%,rgba(212,175,55,.45) 0%,transparent 50%), radial-gradient(ellipse at 15% 85%,rgba(212,175,55,.2) 0%,transparent 45%), repeating-linear-gradient(-55deg,transparent 0,transparent 12px,rgba(212,175,55,.03) 12px,rgba(212,175,55,.03) 13px); }
.kmh-gc-card[data-theme="luxe"] .kmh-gc-amount { color: #f5d87a; }
.kmh-gc-card[data-theme="birthday"] { background: linear-gradient(135deg,#1e1b4b 0%,#3730a3 40%,#4f46e5 100%); }
.kmh-gc-card[data-theme="birthday"] .kmh-gc-layer.pattern { background: radial-gradient(ellipse at 80% 15%,rgba(167,139,250,.7) 0%,transparent 45%), radial-gradient(ellipse at 20% 85%,rgba(99,102,241,.6) 0%,transparent 45%); }
.kmh-gc-card[data-theme="celebration"] { background: linear-gradient(135deg,#1e1b4b 0%,#4c1d95 45%,#7c3aed 100%); }
.kmh-gc-card[data-theme="celebration"] .kmh-gc-layer.pattern { background: radial-gradient(ellipse at 70% 25%,rgba(251,146,60,.55) 0%,transparent 50%), radial-gradient(ellipse at 25% 75%,rgba(168,85,247,.55) 0%,transparent 50%); }
.kmh-gc-card[data-theme="romance"] { background: linear-gradient(135deg,#4c0519 0%,#881337 40%,#9f1239 100%); }
.kmh-gc-card[data-theme="romance"] .kmh-gc-layer.pattern { background: radial-gradient(ellipse at 75% 20%,rgba(251,113,133,.55) 0%,transparent 50%), radial-gradient(ellipse at 20% 80%,rgba(159,18,57,.65) 0%,transparent 50%); }
.kmh-gc-card[data-theme="festive"] { background: linear-gradient(148deg,#022c22 0%,#064e3b 50%,#065f46 100%); }
.kmh-gc-card[data-theme="festive"] .kmh-gc-layer.pattern { background: radial-gradient(ellipse at 80% 20%,rgba(52,211,153,.35) 0%,transparent 50%), radial-gradient(ellipse at 20% 80%,rgba(6,78,59,.8) 0%,transparent 50%); }
.kmh-gc-card[data-theme="tech"] { background: linear-gradient(148deg,#020617 0%,#0c1445 45%,#1e3a8a 100%); }
.kmh-gc-card[data-theme="tech"] .kmh-gc-layer.pattern { background: radial-gradient(ellipse at 80% 25%,rgba(6,182,212,.4) 0%,transparent 50%), radial-gradient(ellipse at 20% 75%,rgba(99,102,241,.35) 0%,transparent 50%); }
/* ─────────────────────────────────────────────────────────── */
.saved-card { background:#fff; border-radius:var(--r-lg); border:1.5px solid var(--border); overflow:hidden; display:flex; gap:16px; padding:16px; align-items:flex-start; transition:var(--t-base); }
.saved-card:hover { box-shadow:var(--s-md); }
.saved-card-img { width:80px; height:80px; border-radius:var(--r-md); object-fit:cover; flex-shrink:0; }
.notification-item { display:flex; gap:16px; padding:16px; border-bottom:1px solid var(--border); align-items:flex-start; }
.notification-dot { width:10px; height:10px; border-radius:50%; background:var(--lime); flex-shrink:0; margin-top:5px; }
.notification-item.read .notification-dot { background:var(--border); }
.sub-management-card { background:#fff; border-radius:var(--r-xl); padding:28px; border:1.5px solid var(--border); margin-bottom:20px; }
.sub-status { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:var(--r-pill); font-size:.75rem; font-weight:700; }
.sub-active { background:rgba(34,197,94,.1); color:#16A34A; }
.sub-paused { background:rgba(245,158,11,.1); color:#D97706; }
.points-activity { display:flex; flex-direction:column; gap:0; }
.points-row { display:flex; justify-content:space-between; align-items:center; padding:14px 0; border-bottom:1px solid var(--border); font-size:.88rem; }
.points-row:last-child { border-bottom:none; }
.points-earn { color:var(--success); font-weight:700; }
.points-spend { color:var(--red); font-weight:700; }

/* — Membership Panel Styles — */
.mem-card { background:linear-gradient(135deg,#0A0A0A 0%,#1c1c1c 60%,#2a2a2a 100%); border-radius:20px; padding:32px 36px; color:#fff; position:relative; overflow:hidden; margin-bottom:28px; }
.mem-card::before { content:''; position:absolute; top:-80px; right:-80px; width:260px; height:260px; background:radial-gradient(circle, rgba(212,217,148,.12) 0%, transparent 65%); pointer-events:none; }
.mem-card::after { content:''; position:absolute; bottom:-60px; left:-40px; width:180px; height:180px; background:radial-gradient(circle, rgba(137,57,65,.07) 0%, transparent 65%); pointer-events:none; }
.mem-card-logo { font-family:var(--font-display); font-size:1rem; color:rgba(255,255,255,.4); margin-bottom:32px; letter-spacing:.06em; }
.mem-card-logo span { color:#893941; }
.mem-card-id-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.15em; color:rgba(255,255,255,.3); margin-bottom:8px; }
.mem-card-id { font-size:1.5rem; font-weight:700; letter-spacing:.14em; color:var(--lime); font-variant-numeric:tabular-nums; }
.mem-card-footer { display:flex; justify-content:space-between; align-items:flex-end; margin-top:28px; }
.mem-card-name { font-size:1rem; font-weight:700; margin-bottom:3px; }
.mem-card-tier { font-size:.78rem; color:rgba(255,255,255,.45); }
.mem-card-meta { text-align:right; }
.mem-card-since-label { font-size:.62rem; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.25); margin-bottom:3px; }
.mem-card-since { font-size:.82rem; color:rgba(255,255,255,.5); font-weight:600; }

.selfie-zone { display:flex; flex-direction:column; align-items:center; gap:14px; padding:24px 0; }
.selfie-ring { width:110px; height:110px; border-radius:50%; border:2.5px dashed var(--border); display:flex; align-items:center; justify-content:center; background:var(--gray-50); position:relative; overflow:hidden; cursor:pointer; transition:border-color .2s; }
.selfie-ring:hover { border-color:var(--lime); }
.selfie-ring img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.selfie-ring .selfie-placeholder { font-size:2.2rem; }
.selfie-status { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:var(--r-pill); font-size:.72rem; font-weight:700; }
.selfie-none { background:rgba(160,158,149,.12); color:var(--text-muted); }
.selfie-pending { background:rgba(59,130,246,.1); color:#2563EB; }
.selfie-verified { background:rgba(34,197,94,.1); color:#16A34A; }
.selfie-instructions { font-size:.78rem; color:var(--text-muted); text-align:center; line-height:1.6; max-width:220px; }

.mem-section { background:#fff; border-radius:var(--r-xl); padding:28px 32px; border:1.5px solid var(--border); }
.mem-section-title { font-size:1rem; font-weight:700; margin-bottom:20px; }
.mem-field { display:flex; flex-direction:column; gap:7px; }
.mem-field label { font-size:.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.07em; }
.mem-field-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

/* — Community Panel Styles — */
.comm-hero { background:linear-gradient(135deg,#0A0A0A 0%,#111 55%,#1a1a1a 100%); border-radius:var(--r-xl); padding:32px 36px; color:#fff; position:relative; overflow:hidden; margin-bottom:24px; }
.comm-hero::before { content:''; position:absolute; top:-100px; right:-60px; width:340px; height:340px; background:radial-gradient(circle, rgba(212,217,148,.14) 0%, transparent 60%); pointer-events:none; }
.comm-hero::after { content:''; position:absolute; bottom:-80px; left:20px; width:200px; height:200px; background:radial-gradient(circle, rgba(137,57,65,.08) 0%, transparent 65%); pointer-events:none; }
.comm-hero-inner { position:relative; z-index:1; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:24px; }
.comm-hero-stats { display:flex; gap:32px; }
.comm-hero-stat { text-align:center; }
.comm-hero-stat-num { font-family:var(--font-display); font-size:1.6rem; color:var(--lime); font-weight:400; }
.comm-hero-stat-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:rgba(255,255,255,.35); margin-top:2px; }

.comm-composer { background:#fff; border-radius:var(--r-xl); border:1.5px solid var(--border); overflow:hidden; margin-bottom:24px; }
.comm-composer-tabs { display:flex; border-bottom:1.5px solid var(--border); }
.cc-tab { flex:1; padding:14px 8px; font-size:.82rem; font-weight:700; text-align:center; cursor:pointer; color:var(--text-muted); border:none; background:transparent; transition:all .2s; border-bottom:2.5px solid transparent; margin-bottom:-2px; }
.cc-tab.active { color:var(--black); border-bottom-color:var(--lime); background:var(--lime-pale); }
.comm-composer-body { padding:22px 24px; }
.cc-panel { display:none; }
.cc-panel.active { display:block; }

.photo-drop-zone { border:2px dashed var(--border); border-radius:var(--r-lg); padding:36px 20px; text-align:center; cursor:pointer; transition:all .2s; position:relative; overflow:hidden; background:var(--gray-50, #FAFAFA); }
.photo-drop-zone:hover, .photo-drop-zone.dragover { border-color:var(--lime); background:var(--lime-pale); }
.photo-drop-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; }
.pdz-icon { font-size:2.5rem; margin-bottom:10px; }
.pdz-title { font-size:.92rem; font-weight:700; margin-bottom:6px; }
.pdz-sub { font-size:.78rem; color:var(--text-muted); }
.photo-preview-strip { display:flex; gap:10px; flex-wrap:wrap; margin-top:16px; }
.photo-thumb { width:80px; height:80px; border-radius:var(--r-md); object-fit:cover; border:2px solid var(--border); position:relative; }
.photo-thumb-wrap { position:relative; }
.photo-thumb-remove { position:absolute; top:-6px; right:-6px; width:20px; height:20px; border-radius:50%; background:var(--red); color:#fff; font-size:.65rem; border:none; cursor:pointer; display:grid; place-items:center; font-weight:700; }

.prod-tag-input-wrap { position:relative; }
.prod-tag-dropdown { position:absolute; top:100%; left:0; right:0; background:#fff; border:1.5px solid var(--border); border-radius:var(--r-md); z-index:200; box-shadow:0 8px 24px rgba(0,0,0,.1); display:none; max-height:180px; overflow-y:auto; }
.prod-tag-dropdown.open { display:block; }
.ptd-item { padding:10px 14px; cursor:pointer; font-size:.85rem; font-weight:600; display:flex; align-items:center; gap:10px; transition:.15s; }
.ptd-item:hover { background:var(--lime-pale); }
.ptd-item img { width:32px; height:32px; border-radius:6px; object-fit:cover; }
.tagged-products-list { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; }
.tprod-chip { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; background:var(--lime-pale); border:1px solid var(--lime-dark); border-radius:var(--r-pill); font-size:.75rem; font-weight:700; color:var(--lime-dark); }
.tprod-chip button { background:none; border:none; cursor:pointer; font-size:.75rem; color:var(--text-muted); }

.star-rating { display:flex; gap:6px; cursor:pointer; }
.star-rating .star { font-size:1.6rem; color:#E5E7EB; transition:color .15s; }
.star-rating .star.active, .star-rating:hover .star:not(.past-hover) { color:#F59E0B; }

.hashtag-cloud { display:flex; flex-wrap:wrap; gap:6px; margin-top:10px; }
.htag-chip { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:var(--lime-pale); border-radius:var(--r-pill); font-size:.73rem; font-weight:700; color:var(--lime-dark); }
.htag-chip button { background:none; border:none; cursor:pointer; font-size:.72rem; }

.my-posts-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px; }
.comm-view-toggle { display:flex; background:var(--gray-100,#F3F4F6); border-radius:var(--r-pill); padding:3px; gap:2px; }
.cvt-btn { border:none; background:transparent; padding:6px 14px; border-radius:var(--r-pill); font-size:.75rem; font-weight:700; cursor:pointer; color:var(--text-muted); transition:.2s; }
.cvt-btn.active { background:#fff; color:var(--black); box-shadow:0 1px 4px rgba(0,0,0,.08); }

.my-posts-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.mpg-card { border-radius:var(--r-md); overflow:hidden; position:relative; aspect-ratio:1; cursor:pointer; }
.mpg-card img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.mpg-card:hover img { transform:scale(1.04); }
.mpg-overlay { position:absolute; inset:0; background:rgba(0,0,0,0); transition:.2s; display:flex; align-items:flex-end; padding:10px; }
.mpg-card:hover .mpg-overlay { background:rgba(0,0,0,.45); }
.mpg-stats { display:flex; gap:10px; opacity:0; transition:.2s; }
.mpg-card:hover .mpg-stats { opacity:1; }
.mpg-stat { font-size:.75rem; font-weight:700; color:#fff; display:flex; align-items:center; gap:4px; }
.mpg-type-badge { position:absolute; top:8px; left:8px; font-size:.6rem; font-weight:700; padding:3px 8px; border-radius:var(--r-pill); }
.mpg-review-card { background:var(--black); border-radius:var(--r-lg); padding:18px 20px; cursor:pointer; transition:transform .2s; }
.mpg-review-card:hover { transform:translateY(-3px); }
.mpg-rev-stars { color:var(--lime); font-size:.8rem; margin-bottom:10px; }
.mpg-rev-quote { font-family:var(--font-display); font-size:.95rem; color:#fff; line-height:1.45; font-style:italic; margin-bottom:12px; display:-webkit-box; -webkit-line-clamp:3; line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

.comm-feed { display:flex; flex-direction:column; gap:0; }
.comm-feed-item { display:flex; gap:16px; padding:18px 0; border-bottom:1px solid var(--border); }
.comm-feed-item:last-child { border-bottom:none; }
.cfi-img { width:68px; height:68px; border-radius:var(--r-md); object-fit:cover; flex-shrink:0; }
.cfi-type-icon { width:68px; height:68px; border-radius:var(--r-md); flex-shrink:0; display:grid; place-items:center; font-size:1.6rem; }
.cfi-body { flex:1; }
.cfi-meta { font-size:.75rem; color:var(--text-muted); margin-bottom:4px; }
.cfi-title { font-size:.9rem; font-weight:700; margin-bottom:5px; line-height:1.35; }
.cfi-caption { font-size:.82rem; color:var(--text-secondary); line-height:1.5; margin-bottom:8px; display:-webkit-box; -webkit-line-clamp:2; line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.cfi-actions { display:flex; gap:12px; }
.cfi-action { border:none; background:none; cursor:pointer; font-size:.78rem; font-weight:700; color:var(--text-muted); display:inline-flex; align-items:center; gap:4px; transition:.2s; padding:4px 8px; border-radius:var(--r-pill); }
.cfi-action:hover { background:var(--lime-pale); color:var(--black); }
.cfi-action.liked { color:#893941; }

.comm-discover { background:#fff; border-radius:var(--r-xl); border:1.5px solid var(--border); padding:24px; margin-top:24px; }
.comm-discover-scroll { display:flex; gap:16px; overflow-x:auto; padding-bottom:8px; scrollbar-width:thin; }
.comm-discover-scroll::-webkit-scrollbar { height:4px; }
.comm-discover-scroll::-webkit-scrollbar-track { background:transparent; }
.comm-discover-scroll::-webkit-scrollbar-thumb { background:var(--border); border-radius:10px; }
.cds-card { flex-shrink:0; width:200px; border-radius:var(--r-lg); overflow:hidden; border:1.5px solid var(--border); cursor:pointer; transition:transform .2s; }
.cds-card:hover { transform:translateY(-4px); box-shadow:0 12px 28px rgba(0,0,0,.1); }
.cds-img { height:160px; overflow:hidden; }
.cds-img img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.cds-card:hover .cds-img img { transform:scale(1.04); }
.cds-body { padding:12px; }
.cds-user { display:flex; align-items:center; gap:8px; margin-bottom:6px; }
.cds-av { width:24px; height:24px; border-radius:50%; display:grid; place-items:center; font-weight:700; font-size:.6rem; flex-shrink:0; }
.cds-like { display:flex; align-items:center; gap:5px; font-size:.75rem; font-weight:700; color:var(--text-muted); cursor:pointer; transition:.2s; }
.cds-like.liked { color:#893941; }

.comm-goto-banner { background:linear-gradient(135deg,#D4D994 0%,#5E6623 100%); border-radius:var(--r-xl); padding:28px 32px; display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; margin-top:24px; }
.cgb-title { font-family:var(--font-display); font-size:1.4rem; color:var(--black); margin-bottom:4px; }
.cgb-sub { font-size:.85rem; color:rgba(10,10,10,.6); }

/* — Referral Panel — */
.ref-hero { background:linear-gradient(135deg,#D4D994 0%,#5E6623 100%); border-radius:var(--r-xl); padding:36px; margin-bottom:24px; position:relative; overflow:hidden; }
.ref-hero::after { content:''; position:absolute; bottom:-60px; right:-60px; width:220px; height:220px; background:radial-gradient(circle, rgba(10,10,10,.08) 0%, transparent 65%); pointer-events:none; }
.ref-link-box { display:flex; gap:8px; align-items:center; background:rgba(255,255,255,.55); border-radius:var(--r-lg); padding:12px 16px; margin-top:16px; }
.ref-link-text { flex:1; font-size:.9rem; font-weight:700; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--black); }
.ref-stat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
.ref-stat-box { background:#fff; border-radius:var(--r-lg); padding:20px; text-align:center; border:1.5px solid var(--border); }
.ref-stat-num { font-family:var(--font-display); font-size:1.8rem; color:var(--black); }
.ref-stat-lbl { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:var(--text-muted); margin-top:4px; }
.ref-friend-item { display:flex; align-items:center; gap:14px; padding:14px 0; border-bottom:1px solid var(--border); }
.ref-friend-item:last-child { border-bottom:none; }
.ref-friend-av { width:40px; height:40px; border-radius:50%; display:grid; place-items:center; font-weight:700; font-size:.8rem; flex-shrink:0; color:#fff; }
.ref-friend-status { margin-left:auto; font-size:.72rem; font-weight:700; padding:3px 10px; border-radius:var(--r-pill); }
.ref-status-joined { background:rgba(34,197,94,.1); color:#16A34A; }
.ref-status-pending { background:rgba(245,158,11,.1); color:#D97706; }

/* — Vouchers Panel — */
.voucher-card { background:#fff; border-radius:var(--r-xl); border:1.5px solid var(--border); overflow:hidden; display:grid; grid-template-columns:130px 1fr; margin-bottom:16px; transition:.2s; }
.voucher-card:hover { box-shadow:var(--s-md); }
.voucher-left { background:var(--black); color:#fff; padding:20px 16px; display:flex; flex-direction:column; align-items:center; justify-content:center; position:relative; }
.voucher-left::after { content:''; position:absolute; right:-12px; top:0; bottom:0; width:24px; background:repeating-linear-gradient(to bottom, transparent, transparent 8px, #fff 8px, #fff 16px); }
.voucher-disc { font-family:var(--font-display); font-size:1.8rem; color:var(--lime); line-height:1; }
.voucher-disc-sub { font-size:.65rem; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:.1em; margin-top:4px; }
.voucher-code { font-size:.82rem; font-weight:700; letter-spacing:.14em; color:rgba(212,217,148,.7); margin-top:10px; }
.voucher-right { padding:18px 24px 18px 32px; }
.voucher-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:var(--r-pill); font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; }
.voucher-active { background:rgba(34,197,94,.1); color:#16A34A; }
.voucher-used { background:var(--gray-100,#F3F4F6); color:var(--text-muted); }
.voucher-expiry { font-size:.75rem; color:var(--text-muted); margin-top:4px; }

/* — Routine Tracker Panel — */
.routine-week { display:grid; grid-template-columns:repeat(7,1fr); gap:8px; margin-bottom:24px; }
.routine-day { border-radius:var(--r-md); border:1.5px solid var(--border); padding:12px 6px; text-align:center; cursor:pointer; transition:.2s; background:#fff; }
.routine-day.done { background:var(--lime); border-color:var(--lime-dark,#97b01e); }
.routine-day.today { border-color:var(--black); border-width:2px; }
.routine-day.missed { background:rgba(232,56,46,.05); border-color:rgba(232,56,46,.2); }
.routine-day-lbl { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted); margin-bottom:5px; }
.routine-day.done .routine-day-lbl, .routine-day.done .routine-day-num { color:var(--black); }
.routine-day-num { font-size:.88rem; font-weight:700; }
.routine-day-icon { font-size:.9rem; margin-top:4px; }
.streak-badge { display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#F59E0B,#EF4444); color:#fff; padding:8px 20px; border-radius:var(--r-pill); font-weight:700; font-size:.95rem; box-shadow:0 4px 12px rgba(239,68,68,.3); }
.rt-step-row { display:flex; align-items:center; gap:14px; padding:14px 16px; border-radius:var(--r-lg); border:1.5px solid var(--border); margin-bottom:10px; cursor:pointer; transition:.2s; background:#fff; user-select:none; }
.rt-step-row.checked { background:var(--lime-pale,#f5ffcc); border-color:var(--lime-dark,#97b01e); }
.rt-check { width:24px; height:24px; border-radius:50%; border:2px solid var(--border); display:grid; place-items:center; flex-shrink:0; font-size:.8rem; transition:.2s; }
.rt-step-row.checked .rt-check { background:var(--lime); border-color:var(--lime-dark,#97b01e); color:var(--black); }
.rt-step-label { flex:1; font-size:.9rem; font-weight:600; }
.rt-step-row.checked .rt-step-label { text-decoration:line-through; color:var(--text-muted); }
.rt-step-pts { font-size:.72rem; font-weight:700; color:var(--lime-dark,#97b01e); background:var(--lime-pale,#f5ffcc); padding:3px 8px; border-radius:var(--r-pill); }

/* — Security Panel — */
.security-section { background:#fff; border-radius:var(--r-xl); padding:28px 32px; border:1.5px solid var(--border); margin-bottom:24px; }
.security-row { display:flex; justify-content:space-between; align-items:center; padding:18px 0; border-bottom:1px solid var(--border); gap:16px; }
.security-row:last-child { border-bottom:none; }
.security-row-info h4 { font-size:.92rem; font-weight:700; margin-bottom:3px; }
.security-row-info p { font-size:.78rem; color:var(--text-muted); }
.toggle-pill { width:46px; height:26px; border-radius:13px; background:var(--border); cursor:pointer; position:relative; transition:.25s; flex-shrink:0; border:none; }
.toggle-pill.on { background:var(--lime-dark,#97b01e); }
.toggle-knob { width:22px; height:22px; border-radius:50%; background:#fff; position:absolute; top:2px; left:2px; transition:.25s; box-shadow:0 1px 4px rgba(0,0,0,.18); }
.toggle-pill.on .toggle-knob { transform:translateX(20px); }
.device-row { display:flex; align-items:center; gap:14px; padding:14px 0; border-bottom:1px solid var(--border); }
.device-row:last-child { border-bottom:none; }
.device-icon { width:40px; height:40px; border-radius:var(--r-md); background:var(--gray-100,#F3F4F6); display:grid; place-items:center; font-size:1.2rem; flex-shrink:0; }
.device-info { flex:1; }
.device-name { font-size:.88rem; font-weight:700; }
.device-meta { font-size:.74rem; color:var(--text-muted); margin-top:2px; }
.device-current { font-size:.68rem; font-weight:700; background:var(--lime-pale,#f5ffcc); color:var(--lime-dark,#97b01e); padding:2px 8px; border-radius:var(--r-pill); }
.order-filter-btn.active-filter { background:var(--rose-dark)!important; color:#fff!important; border-color:var(--rose-dark)!important; }
.plan-badge-popular { background:var(--lime); color:var(--black); }
.plan-badge-default { background:var(--rose-dark); color:#fff; }
.js-plan-card.plan-popular { border-color:var(--lime-dark,#97b01e) !important; }

@media(max-width:768px) {
  .mem-card { padding:24px 20px; }
  .mem-card-footer { flex-direction:column; gap:12px; align-items:flex-start; }
  .mem-field-row { grid-template-columns:1fr; }
  .comm-hero { padding:24px 20px; }
  .comm-hero-inner { flex-direction:column; align-items:flex-start; }
  .comm-hero-stats { gap:20px; }
  .comm-goto-banner { flex-direction:column; gap:12px; }
  .ref-stat-grid { grid-template-columns:1fr 1fr; }
  .ref-hero { padding:24px 20px; }
  .my-posts-grid { grid-template-columns:repeat(2,1fr); }
  .voucher-card { grid-template-columns:100px 1fr; }
  .voucher-right { padding:14px 14px 14px 24px; }
  .security-section { padding:20px; }
  .security-row { flex-wrap:wrap; }
  .routine-week { gap:5px; }
  .routine-day { padding:10px 4px; }
  .routine-day-lbl { font-size:.55rem; }
}
@media(max-width:480px) {
  .my-posts-grid { grid-template-columns:repeat(2,1fr); gap:8px; }
  .ref-stat-grid { grid-template-columns:1fr; }
  .voucher-card { grid-template-columns:1fr; }
  .voucher-left { padding:16px; flex-direction:row; gap:16px; }
  .voucher-left::after { display:none; }
  .routine-week { grid-template-columns:repeat(4,1fr); }
  .comm-hero-stats { flex-wrap:wrap; gap:12px; }
  .mem-section { padding:20px; }
}

/* ── Mobile Dashboard Sidebar ── */
.dash-mobile-header {
  display: none;
  position: sticky;
  top: var(--nav-h);
  z-index: 400;
  background: #fff;
  color: var(--black);
  height: 52px;
  padding: 0 16px;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  border-bottom: 1px solid var(--border);
}
.dash-mobile-menu-btn {
  width: 38px; height: 38px;
  border-radius: 10px;
  background: rgba(0,0,0,.06);
  border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; cursor: pointer; color: var(--black);
  flex-shrink: 0;
  transition: background .15s;
}
.dash-mobile-menu-btn:hover { background: rgba(0,0,0,.1); }
.dash-mobile-title {
  font-size: .92rem; font-weight: 700; color: var(--black);
  flex: 1; text-align: center;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dash-sidebar-overlay {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,.55);
  z-index: 300;
  opacity: 0; visibility: hidden;
  transition: opacity .3s ease, visibility .3s ease;
}
.dash-sidebar-overlay.open { opacity: 1; visibility: visible; }

@media (max-width: 1100px) {
  .dash-mobile-header { display: flex; }
  .dash-sidebar-overlay { display: block; }
  .dashboard-sidebar {
    display: flex !important;
    position: fixed !important;
    top: var(--nav-h);
    left: 0; bottom: 0;
    width: 280px;
    height: calc(100vh - var(--nav-h));
    z-index: 500;
    transform: translateX(-100%);
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    padding: 24px 20px;
    overflow-y: auto;
  }
  .dashboard-sidebar.open { transform: translateX(0); }
  .dashboard-main { padding: 24px 16px; }
}
@media (max-width: 480px) {
  .dashboard-sidebar { width: 100%; max-width: 300px; }
  .dashboard-main { padding: 16px 12px; }
  .dash-stat-cards { grid-template-columns: 1fr 1fr; }
}

/* ═══════════════════════════════════════════════════
   Dashboard Premium Enhancement — Kominhoo Beauty
═══════════════════════════════════════════════════ */

/* ── Stat Cards ─────────────────────────────────── */
.dash-stat-card {
  background: #fff;
  border-radius: 18px;
  padding: 20px 22px 18px;
  border: 1.5px solid var(--border);
  position: relative;
  overflow: hidden;
  transition: transform .2s cubic-bezier(.4,0,.2,1), box-shadow .2s cubic-bezier(.4,0,.2,1);
  cursor: default;
  display: flex;
  flex-direction: column;
  gap: 0;
}
.dash-stat-card::after {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: var(--_accent, var(--border));
  border-radius: 18px 18px 0 0;
}
.dash-stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 32px rgba(0,0,0,.09);
}
.dash-stat-icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  display: grid; place-items: center;
  background: var(--_icon-bg, var(--lime-pale));
  margin-bottom: 14px;
  flex-shrink: 0;
}
.dash-stat-label {
  font-size: .68rem;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .1em;
  margin-bottom: 5px;
}
.dash-stat-value {
  font-size: 2rem;
  font-weight: 700;
  line-height: 1.05;
  letter-spacing: -.025em;
  font-variant-numeric: tabular-nums;
  margin-bottom: 5px;
}
.dash-stat-change {
  font-size: .73rem;
  font-weight: 600;
  margin-top: auto;
}

/* ── Sidebar Navigation ─────────────────────────── */
.dash-nav-item {
  display: flex; align-items: center; gap: 11px;
  padding: 9px 14px;
  border-radius: 10px;
  font-size: .83rem; font-weight: 600;
  color: rgba(28,20,22,.5);
  cursor: pointer;
  transition: color .18s ease, background .18s ease;
  border-left: 2.5px solid transparent;
  margin-left: -2.5px;
}
.dash-nav-item:hover {
  color: var(--black);
  background: rgba(0,0,0,.05);
}
.dash-nav-item.active {
  color: var(--black);
  background: rgba(212,217,148,.3);
  border-left-color: var(--lime-dark);
}
.dash-nav-item.active .dash-nav-icon {
  color: var(--lime-dark);
  filter: drop-shadow(0 0 5px rgba(150,170,0,.3));
}
.dash-nav-icon { font-size: 1rem; flex-shrink: 0; width: 18px; text-align: center; }
.dash-nav-label {
  font-size: .6rem;
  font-weight: 700;
  letter-spacing: .15em;
  text-transform: uppercase;
  color: rgba(28,20,22,.35);
  padding: 12px 14px 3px;
}
.dash-nav-sep { height: 1px; background: var(--border); margin: 5px 0; }
.dash-user {
  padding: 0 8px 24px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 4px;
}
.dash-avatar {
  width: 56px; height: 56px; border-radius: 50%;
  background: linear-gradient(140deg, var(--lime) 0%, var(--lime-dark) 100%);
  color: var(--black);
  display: grid; place-items: center;
  font-size: 1.3rem; font-weight: 700; margin-bottom: 12px;
  box-shadow: 0 4px 14px rgba(212,217,148,.28);
}
.dash-user-name { font-size: .9rem; font-weight: 700; margin-bottom: 3px; color: var(--black); }
.dash-user-tier { font-size: .72rem; color: var(--lime-dark); font-weight: 700; letter-spacing: .04em; }

/* ── Welcome / Section Header ───────────────────── */
.dash-welcome {
  margin-bottom: 28px;
  padding-bottom: 22px;
  border-bottom: 1.5px solid var(--border);
}
.dash-greeting {
  font-family: var(--font-display);
  font-size: clamp(1.45rem, 2.3vw, 2rem);
  margin-bottom: 6px;
  letter-spacing: -.02em;
  color: var(--black);
}
.dash-subtext {
  font-size: .88rem;
  color: var(--text-secondary);
  line-height: 1.65;
  max-width: 540px;
}

/* ── Loyalty Bar Card ───────────────────────────── */
.loyalty-bar-card {
  background: linear-gradient(150deg, #0F0F0F 0%, #1C1416 50%, #251B1D 100%);
  border-radius: 22px;
  padding: 30px 34px;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,.05);
  box-shadow: 0 16px 48px rgba(0,0,0,.2);
}
.loyalty-bar-card::before {
  content: '';
  position: absolute; top: -100px; right: -60px;
  width: 340px; height: 340px;
  background: radial-gradient(circle, rgba(212,217,148,.11) 0%, transparent 60%);
  pointer-events: none;
}
.loyalty-bar-card::after {
  content: '';
  position: absolute; bottom: -70px; left: -40px;
  width: 200px; height: 200px;
  background: radial-gradient(circle, rgba(137,57,65,.09) 0%, transparent 65%);
  pointer-events: none;
}
.loyalty-bar-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; }
.loyalty-tier-name { font-family: var(--font-display); font-size: 1.5rem; letter-spacing: -.01em; }
.loyalty-pts {
  font-size: 2.2rem; font-weight: 700; color: var(--lime);
  font-variant-numeric: tabular-nums; letter-spacing: -.03em; line-height: 1;
}
.loyalty-pts-label {
  font-size: .67rem; font-weight: 700; color: rgba(255,255,255,.35);
  text-transform: uppercase; letter-spacing: .13em; margin-top: 4px;
}
.loyalty-progress-bar {
  height: 8px;
  background: rgba(255,255,255,.1);
  border-radius: var(--r-pill);
  overflow: hidden;
}
.loyalty-progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #7A9218 0%, var(--lime) 100%);
  border-radius: var(--r-pill);
  transition: width 1.2s cubic-bezier(.4,0,.2,1);
  box-shadow: 0 0 12px rgba(212,217,148,.4);
}
.loyalty-progress-labels { display: flex; justify-content: space-between; font-size: .72rem; color: rgba(255,255,255,.42); margin-top: 8px; }

/* ── Order Card ─────────────────────────────────── */
.order-card {
  border-radius: 16px;
  transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.order-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(0,0,0,.09);
  border-color: rgba(212,217,148,.4);
}

/* ── Panel H3 headings ──────────────────────────── */
.dashboard-panel > div h3,
.dashboard-panel h3 {
  font-size: .93rem;
  font-weight: 700;
  letter-spacing: -.01em;
}

@media(max-width:768px) {
  .loyalty-bar-card { padding: 22px 22px; }
  .dash-stat-value { font-size: 1.65rem; }
}

/* ── Dashboard Skeleton Loading ─────────────────────────────────── */
@keyframes dsk-shimmer {
  0%   { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

#dash-skeleton {
  position: fixed;
  top: 0; left: 0; right: 0;
  height: 100vh;
  z-index: 99999;
  display: flex;
  flex-direction: column;
  pointer-events: none;
  transition: opacity .45s ease;
}
#dash-skeleton.dsk-out { opacity: 0; }

.dsk-shine {
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
  animation: dsk-shimmer 1.7s ease-in-out infinite;
}

/* Announcement */
.dsk-ann {
  height: 38px;
  background: #1C1416;
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
}
.dsk-ann .dsk-shine {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.07), transparent);
}

/* Nav */
.dsk-nav {
  height: 58px;
  background: rgba(250,246,243,.98);
  border-bottom: 1px solid #EDDCD8;
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
}
.dsk-nav .dsk-shine {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.72), transparent);
}
.dsk-nav-inner {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  position: relative;
  z-index: 1;
}
.dsk-logo { width: 120px; height: 18px; background: #EDDCD8; border-radius: 4px; flex-shrink: 0; }
.dsk-nav-links { display: flex; gap: 14px; align-items: center; }
.dsk-npill { height: 9px; background: #EDDCD8; border-radius: 99px; }
.dsk-npill:nth-child(1) { width: 56px; }
.dsk-npill:nth-child(2) { width: 72px; }
.dsk-npill:nth-child(3) { width: 86px; }
.dsk-npill:nth-child(4) { width: 60px; }
.dsk-npill:nth-child(5) { width: 78px; }
.dsk-npill:nth-child(6) { width: 90px; }
.dsk-nav-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
.dsk-search { width: 110px; height: 30px; background: #EDDCD8; border-radius: 99px; }
.dsk-nbtn { height: 30px; background: #EDDCD8; border-radius: 99px; }
.dsk-nbtn:nth-of-type(1) { width: 80px; }
.dsk-nbtn:nth-of-type(2) { width: 70px; }
.dsk-ncart { width: 74px; height: 30px; background: #EDDCD8; border-radius: 99px; }

/* Dashboard body */
.dsk-body {
  flex: 1;
  display: grid;
  grid-template-columns: 260px 1fr;
  overflow: hidden;
  min-height: 0;
  background: #FAF6F3;
}

/* Sidebar */
.dsk-sidebar {
  background: #1C1416;
  padding: 32px 24px 24px;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 0;
}
.dsk-sidebar .dsk-shine {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.04), transparent);
  animation-delay: .2s;
}
.dsk-s-avatar {
  width: 60px; height: 60px;
  border-radius: 50%;
  background: rgba(212,217,148,.2);
  margin-bottom: 14px;
  flex-shrink: 0;
}
.dsk-s-name { width: 120px; height: 13px; background: rgba(255,255,255,.14); border-radius: 99px; margin-bottom: 8px; }
.dsk-s-tier { width: 80px; height: 9px; background: rgba(255,255,255,.07); border-radius: 99px; margin-bottom: 4px; }
.dsk-s-since { width: 100px; height: 8px; background: rgba(255,255,255,.05); border-radius: 99px; margin-bottom: 24px; }
.dsk-s-sep { height: 1px; background: rgba(255,255,255,.06); margin: 8px 0 16px; }
.dsk-s-label { width: 56px; height: 7px; background: rgba(255,255,255,.1); border-radius: 99px; margin-bottom: 10px; }
.dsk-s-item { height: 34px; background: rgba(255,255,255,.05); border-radius: 10px; margin-bottom: 4px; }
.dsk-s-item-active { background: rgba(212,217,148,.12); }

/* Main content */
.dsk-main {
  padding: 40px 40px 32px;
  overflow: hidden;
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 0;
}

/* Mobile header strip (hidden on desktop) */
.dsk-mobile-bar {
  display: none;
  height: 52px;
  background: #1C1416;
  border-bottom: 1px solid rgba(255,255,255,.1);
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
}
.dsk-mobile-bar .dsk-shine {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.04), transparent);
}

/* Greeting */
.dsk-greeting { width: 280px; height: 36px; background: #E8E2DE; border-radius: 8px; margin-bottom: 10px; }
.dsk-subtext { width: 360px; height: 11px; background: #E8E2DE; border-radius: 99px; margin-bottom: 32px; }

/* Skin profile banner */
.dsk-skin-banner {
  height: 82px;
  background: rgba(212,217,148,.35);
  border-radius: 20px;
  margin-bottom: 28px;
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
}
.dsk-skin-banner .dsk-shine {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
}

/* Stat cards */
.dsk-stat-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 32px;
}
.dsk-stat-card {
  background: #fff;
  border-radius: 18px;
  border: 1.5px solid #EDDCD8;
  padding: 20px 22px 18px;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 0;
}
.dsk-stat-card .dsk-shine {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.72), transparent);
  animation-delay: calc(var(--i, 0) * .12s);
}
.dsk-sc-icon { width: 36px; height: 36px; background: #F2F5D6; border-radius: 10px; margin-bottom: 14px; }
.dsk-sc-label { width: 72px; height: 7px; background: #EDDCD8; border-radius: 99px; margin-bottom: 10px; }
.dsk-sc-value { width: 88px; height: 32px; background: #E8E2DE; border-radius: 6px; margin-bottom: 8px; }
.dsk-sc-change { width: 100px; height: 8px; background: #EDDCD8; border-radius: 99px; margin-top: auto; }

/* Responsive */
@media (max-width: 1100px) {
  .dsk-nav { height: 54px; }
  .dsk-npill:nth-child(n+5) { display: none; }
  .dsk-body { grid-template-columns: 1fr; }
  .dsk-sidebar { display: none; }
  .dsk-mobile-bar { display: block; }
  .dsk-stat-grid { grid-template-columns: repeat(2, 1fr); }
  .dsk-main { padding: 24px 16px; }
}
@media (max-width: 768px) {
  .dsk-nav { height: 52px; }
  .dsk-nav-links { display: none; }
  .dsk-search { width: 80px; }
  .dsk-greeting { width: 200px; height: 28px; }
  .dsk-subtext { width: 240px; }
}
@media (max-width: 480px) {
  .dsk-stat-grid { grid-template-columns: 1fr 1fr; }
  .dsk-main { padding: 16px 12px; }
}
</style>
@endsection

@section('content')
@php
  $hour = (int) date('G');
  $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
  $firstName = explode(' ', $user['name'] ?? 'Adaeze')[0];
  $avatarLetter = strtoupper(substr($user['name'] ?? 'A', 0, 1));

  // — Loyalty — live from backend
  // Validate the tier key against known tiers; fall back to 'starter' if unknown
  $knownTiers = ['starter', 'glow', 'radiant', 'iconic'];
  $rawTierKey = $loyaltySummary['tier'] ?? ($user['tier'] ?? 'starter');
  $tierKey    = in_array($rawTierKey, $knownTiers) ? $rawTierKey : 'starter';

  $tierLabel = $loyaltySummary['tier_name'] ?? 'Starter Glow';
  $loyaltyPoints     = number_format($loyaltySummary['points'] ?? ($user['loyalty_points'] ?? 0));
  $loyaltyProgressPct= $loyaltySummary['progress_pct']  ?? 0;
  $nextTierName      = $loyaltySummary['next_tier_name'] ?? null;
  $pointsToNext      = number_format($loyaltySummary['points_to_next'] ?? 0);
  $multiplier        = $loyaltySummary['multiplier'] ?? 1.0;

  // Tier config from JSON
  $tiersConfig = $loyaltyConfig['tiers'] ?? [];
  $currentTierConfig = collect($tiersConfig)->firstWhere('id', $tierKey) ?? [];
  $tierColor  = $currentTierConfig['color']  ?? '#6B7280';
  $tierIcon   = $currentTierConfig['icon']   ?? '✦';
  $tierBenefits = $currentTierConfig['benefits'] ?? [];
  $tierGift   = $currentTierConfig['gift']   ?? null;
  $pointEventsConfig = $loyaltyConfig['point_events'] ?? [];

  // — Membership ID — from user created_at and id
  $userId    = $user['id'] ?? 0;
  $joinYear  = isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : date('Y');
  $memberSince = isset($user['created_at']) ? date('F Y', strtotime($user['created_at'])) : 'Recently';
  $memberIdCode = 'KMH-' . $joinYear . '-' . strtoupper(base_convert($userId + 100000, 10, 36));

  // — Subscription — live from backend
  $subStatus   = $subscription['status']    ?? null;
  $subPlanName = $subscription['plan_name'] ?? null;
  $subPrice    = $subscription['plan_price'] ?? 0;
  $subCycle    = $subscription['billing_cycle'] ?? 'monthly';
  $subNextDate = isset($subscription['next_billing_date']) ? date('F j, Y', strtotime($subscription['next_billing_date'])) : null;
  $subId       = $subscription['id'] ?? null;

  // — Referrals — live from backend
  $refCode         = $referralData['referral_code']   ?? ($user['referral_code'] ?? '');
  $refLink         = $referralData['referral_link']   ?? url('/ref/' . $refCode);
  $refTotal        = $referralData['total_referrals'] ?? 0;
  $refCompleted    = $referralData['completed']        ?? 0;
  $refTotalPoints  = $referralData['total_points']    ?? 0;
  $refFriends      = $referralData['referrals']       ?? [];

  // — Notifications — live from backend
  $unreadCount  = $notifData['unread_count'] ?? 0;
  $allNotifs    = $notifData['data']         ?? [];
  $notifItems   = is_array($allNotifs) ? (isset($allNotifs[0]) ? $allNotifs : ($allNotifs['data'] ?? [])) : [];

  // Notification badge: also update sidebar
  $notifBadge = $unreadCount > 0 ? $unreadCount : null;

  // — Home panel stat cards — derived from live data
  $orderCount      = count($orders ?? []);
  $deliveredCount  = count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') === 'delivered'));
  $shippingCount   = count(array_filter($orders ?? [], fn($o) => ($o['status'] ?? '') === 'shipped'));

  // Points earned this month (approximated from the loaded recent events)
  $thisMonthPrefix  = date('Y-m');
  $pointsThisMonth  = 0;
  foreach ($pointEvents ?? [] as $pe) {
      if (($pe['points'] ?? 0) > 0 && str_starts_with($pe['created_at'] ?? '', $thisMonthPrefix)) {
          $pointsThisMonth += (int) $pe['points'];
      }
  }

  // Subscription stat card display
  if ($subStatus === 'active') {
      $subShipValue = $subNextDate ? \Carbon\Carbon::parse($subscription['next_billing_date'])->diffInDays(now()) . ' days' : 'Active';
      $subShipSub   = $subPlanName ?? 'Active plan';
  } elseif ($subStatus === 'paused') {
      $subShipValue = 'Paused';
      $subShipSub   = $subPlanName ?? 'Subscription paused';
  } else {
      $subShipValue = '—';
      $subShipSub   = 'No active subscription';
  }

  // Next tier config for the home loyalty bar hint
  $nextTierConfig  = $nextTierName ? (collect($tiersConfig)->firstWhere('name', $nextTierName) ?? collect($tiersConfig)->firstWhere('id', $loyaltySummary['next_tier'] ?? '') ?? []) : [];
  $nextTierBenefit = !empty($nextTierConfig['benefits']) ? $nextTierConfig['benefits'][0] : '';
  $rawPoints       = (int) ($loyaltySummary['points'] ?? ($user['loyalty_points'] ?? 0));

  // — Security settings — from user email_prefs.security
  $secPrefs          = is_array($user['email_prefs'] ?? null) ? ($user['email_prefs']['security'] ?? []) : [];
  $sec2FA            = (bool) ($secPrefs['two_factor']          ?? false);
  $secLoginNotif     = (bool) ($secPrefs['login_notifications'] ?? true);
  $secSmsAlerts      = (bool) ($secPrefs['sms_alerts']          ?? true);
  $secSaveSessions   = (bool) ($secPrefs['save_sessions']       ?? true);

  // — Community profile — derived from user data
  $communityHandle   = '@' . strtolower(preg_replace('/\s+/', '.', trim($user['name'] ?? 'user')));
  $communitySkType   = $latestQuizResult['skin_type'] ?? ($user['skin_type'] ?? null);
  $communityVerified = !empty($user['id']); // all registered users are verified members

  // — Referral reward points — from loyalty config
  $refRewardPts = (int) ($pointEventsConfig['referral']['points'] ?? 500);

  // — Current session — derive from real User-Agent
  $ua = request()->header('User-Agent', '');
  $uaBrowser = match(true) {
      str_contains($ua, 'Edg')     => 'Edge',
      str_contains($ua, 'OPR')     => 'Opera',
      str_contains($ua, 'Firefox') => 'Firefox',
      str_contains($ua, 'Safari') && !str_contains($ua, 'Chrome') => 'Safari',
      str_contains($ua, 'Chrome')  => 'Chrome',
      default                      => 'Browser',
  };
  $uaOs = match(true) {
      str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
      str_contains($ua, 'Android') => 'Android',
      str_contains($ua, 'Windows') => 'Windows',
      str_contains($ua, 'Mac OS')  => 'macOS',
      str_contains($ua, 'Linux')   => 'Linux',
      default                      => 'Unknown OS',
  };
  $isMobile      = str_contains($ua, 'Mobile') || str_contains($ua, 'Android') || str_contains($ua, 'iPhone');
  $currentDevice = $uaBrowser . ' on ' . $uaOs;
  $currentDeviceIcon = $isMobile ? '📱' : '💻';
  $currentIp     = request()->ip();
  $sessionStart  = \Carbon\Carbon::createFromTimestamp(session()->get('_token_created_at', time()))->diffForHumans();
@endphp

{{-- ── Dashboard Skeleton ──────────────────────────────────────── --}}
<div id="dash-skeleton" aria-hidden="true">

  {{-- Announcement bar --}}
  <div class="dsk-ann"><span class="dsk-shine"></span></div>

  {{-- Nav --}}
  <div class="dsk-nav">
    <div class="dsk-nav-inner">
      <div class="dsk-logo"></div>
      <div class="dsk-nav-links">
        <div class="dsk-npill"></div>
        <div class="dsk-npill"></div>
        <div class="dsk-npill"></div>
        <div class="dsk-npill"></div>
        <div class="dsk-npill"></div>
        <div class="dsk-npill"></div>
      </div>
      <div class="dsk-nav-actions">
        <div class="dsk-search"></div>
        <div class="dsk-nbtn"></div>
        <div class="dsk-nbtn"></div>
        <div class="dsk-ncart"></div>
      </div>
    </div>
    <span class="dsk-shine"></span>
  </div>

  {{-- Mobile header bar (shown on ≤1100px) --}}
  <div class="dsk-mobile-bar"><span class="dsk-shine"></span></div>

  {{-- Dashboard body: sidebar + main --}}
  <div class="dsk-body">

    {{-- Sidebar --}}
    <div class="dsk-sidebar">
      <div class="dsk-s-avatar"></div>
      <div class="dsk-s-name"></div>
      <div class="dsk-s-tier"></div>
      <div class="dsk-s-since"></div>
      <div class="dsk-s-sep"></div>
      <div class="dsk-s-label"></div>
      <div class="dsk-s-item dsk-s-item-active"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-sep"></div>
      <div class="dsk-s-label"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-sep"></div>
      <div class="dsk-s-label"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-item"></div>
      <div class="dsk-s-item"></div>
      <span class="dsk-shine"></span>
    </div>

    {{-- Main content --}}
    <div class="dsk-main">
      <div class="dsk-greeting"></div>
      <div class="dsk-subtext"></div>
      <div class="dsk-skin-banner"><span class="dsk-shine"></span></div>
      <div class="dsk-stat-grid">
        <div class="dsk-stat-card" style="--i:0">
          <div class="dsk-sc-icon"></div>
          <div class="dsk-sc-label"></div>
          <div class="dsk-sc-value"></div>
          <div class="dsk-sc-change"></div>
          <span class="dsk-shine"></span>
        </div>
        <div class="dsk-stat-card" style="--i:1">
          <div class="dsk-sc-icon"></div>
          <div class="dsk-sc-label"></div>
          <div class="dsk-sc-value"></div>
          <div class="dsk-sc-change"></div>
          <span class="dsk-shine"></span>
        </div>
        <div class="dsk-stat-card" style="--i:2">
          <div class="dsk-sc-icon"></div>
          <div class="dsk-sc-label"></div>
          <div class="dsk-sc-value"></div>
          <div class="dsk-sc-change"></div>
          <span class="dsk-shine"></span>
        </div>
        <div class="dsk-stat-card" style="--i:3">
          <div class="dsk-sc-icon"></div>
          <div class="dsk-sc-label"></div>
          <div class="dsk-sc-value"></div>
          <div class="dsk-sc-change"></div>
          <span class="dsk-shine"></span>
        </div>
      </div>
    </div>

  </div>
</div>

@if(request()->query('order_placed'))
<div style="background:#dcfce7;color:#166534;border:1px solid #86efac;padding:14px 24px;display:flex;align-items:center;gap:12px">
  <span style="font-size:1.4rem">🎉</span>
  <div><div style="font-weight:700">Order placed successfully!</div><div style="font-size:.87rem;margin-top:2px">We've received your order and will begin processing it shortly.</div></div>
</div>
@endif

<!-- Mobile dashboard header (shown ≤1100px) -->
<div class="dash-mobile-header" id="dashMobileHeader">
  <button class="dash-mobile-menu-btn" id="dashMenuBtn" aria-label="Open menu">☰</button>
  <span class="dash-mobile-title" id="dashMobileTitle">🏠 Dashboard</span>
  <div style="width:38px;flex-shrink:0"></div>
</div>
<div class="dash-sidebar-overlay" id="dashSidebarOverlay"></div>

<div class="dashboard-layout">

  <!-- Sidebar -->
  <aside class="dashboard-sidebar" id="dashSidebar">
    <div class="dash-user">
      @if(!empty($user['avatar']))
        <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] ?? '' }}" class="dash-avatar" style="object-fit:cover;padding:0">
      @else
        <div class="dash-avatar">{{ $avatarLetter }}</div>
      @endif
      <div class="dash-user-name">{{ $user['name'] ?? 'Adaeze Okonkwo' }}</div>
      <div class="dash-user-tier">{{ $tierLabel }}</div>
      <div style="font-size:.75rem;color:rgba(28,20,22,.45);margin-top:4px">Member since {{ $memberSince }}</div>
    </div>

    <div class="dash-nav-label">Overview</div>
    <div class="dash-nav-item active" data-panel="panel-home" onclick="switchDashPanel('panel-home')"><span class="dash-nav-icon">🏠</span> Dashboard</div>
    <div class="dash-nav-item" data-panel="panel-profile" onclick="switchDashPanel('panel-profile')"><span class="dash-nav-icon">👤</span> My Profile</div>
    <div class="dash-nav-item" data-panel="panel-skin" onclick="switchDashPanel('panel-skin')"><span class="dash-nav-icon">🔬</span> Skin Profile</div>

    <div class="dash-nav-sep"></div>
    <div class="dash-nav-label">Shopping</div>
    <div class="dash-nav-item" data-panel="panel-orders" onclick="switchDashPanel('panel-orders')"><span class="dash-nav-icon">📦</span> Orders</div>
    <div class="dash-nav-item" data-panel="panel-saved" onclick="switchDashPanel('panel-saved')"><span class="dash-nav-icon">♡</span> Saved Products</div>
    <div class="dash-nav-item" data-panel="panel-vouchers" onclick="switchDashPanel('panel-vouchers')"><span class="dash-nav-icon">🏷️</span> Vouchers & Coupons <span id="voucher-nav-badge" style="background:var(--lime);color:var(--black);font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:999px;margin-left:auto;display:none"></span></div>
    <div class="dash-nav-item" data-panel="panel-giftcards" onclick="switchDashPanel('panel-giftcards')"><span class="dash-nav-icon">🎁</span> Gift Cards</div>
    <div class="dash-nav-item" data-panel="panel-wallet" onclick="switchDashPanel('panel-wallet')"><span class="dash-nav-icon">💳</span> My Wallet</div>

    <div class="dash-nav-sep"></div>
    <div class="dash-nav-label">Membership</div>
    <div class="dash-nav-item" data-panel="panel-membership" onclick="switchDashPanel('panel-membership')"><span class="dash-nav-icon">🪪</span> My Membership</div>
    <div class="dash-nav-item" data-panel="panel-loyalty" onclick="switchDashPanel('panel-loyalty')"><span class="dash-nav-icon">🌟</span> Loyalty & Points</div>
    <div class="dash-nav-item" data-panel="panel-sub" onclick="switchDashPanel('panel-sub')"><span class="dash-nav-icon">📬</span> Subscription</div>
    <div class="dash-nav-item" data-panel="panel-referral" onclick="switchDashPanel('panel-referral')"><span class="dash-nav-icon">👥</span> Referral Program</div>
    <div class="dash-nav-item" data-panel="panel-notif" onclick="switchDashPanel('panel-notif')"><span class="dash-nav-icon">🔔</span> Notifications @if($notifBadge)<span id="notif-badge-sidebar" style="background:var(--red);color:#fff;font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:999px;margin-left:auto">{{ $notifBadge }}</span>@endif</div>

    <div class="dash-nav-sep"></div>
    <div class="dash-nav-label">Community</div>
    <div class="dash-nav-item" data-panel="panel-community" onclick="switchDashPanel('panel-community')"><span class="dash-nav-icon">✨</span> My Community <span style="background:var(--lime);color:var(--black);font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:999px;margin-left:auto">New</span></div>

    <div class="dash-nav-sep"></div>
    <div class="dash-nav-label">Wellness</div>
    <div class="dash-nav-item" data-panel="panel-routine" onclick="switchDashPanel('panel-routine')"><span class="dash-nav-icon">🧴</span> Routine Tracker</div>

    <div class="dash-nav-sep"></div>
    <div class="dash-nav-label">Account</div>
    <div class="dash-nav-item" data-panel="panel-security" onclick="switchDashPanel('panel-security')"><span class="dash-nav-icon">🔐</span> Security</div>
    <div class="dash-nav-item" onclick="showToast('🚪','Logged out!')">
      <form method="POST" action="{{ route('logout') }}" style="width:100%">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:12px;width:100%;color:inherit;font:inherit;padding:0">
          <span class="dash-nav-icon">🚪</span> Sign Out
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="dashboard-main">

    <!-- HOME PANEL -->
    <div class="dashboard-panel active" id="panel-home">
      <div class="dash-welcome">
        <h1 class="dash-greeting">{{ $greeting }}, {{ $firstName }} ☀️</h1>
        <p class="dash-subtext">
          @if($unreadCount > 0)You have {{ $unreadCount }} unread notification{{ $unreadCount !== 1 ? 's' : '' }}. @endif
          @if($subStatus === 'active' && $subNextDate)Next box: {{ $subNextDate }}. @elseif(!$subStatus)Subscribe to get curated K-beauty delivered monthly. @endif
          @if($rawPoints > 0)You have {{ $loyaltyPoints }} loyalty points.@endif
        </p>
      </div>

      @php
        $ovSkinType = $latestQuizResult['skin_type'] ?? ($user['skin_type'] ?? null);
        $ovAnswers  = $latestQuizResult['answers'] ?? [];
        $ovDate     = $latestQuizResult['created_at'] ?? null;
        $ovConcerns = $ovAnswers['concerns'] ?? [];
        if (is_string($ovConcerns)) $ovConcerns = array_filter(explode(',', $ovConcerns));
        $ovConcernLabels = ['acne'=>'Acne-Prone','dark_spots'=>'Dark Spots','dehydration'=>'Dehydrated','large_pores'=>'Large Pores','sensitive'=>'Sensitive','dull'=>'Dull Skin','texture'=>'Uneven Texture','fine_lines'=>'Anti-Ageing'];
        $ovConcernParts  = array_filter(array_map(fn($c) => $ovConcernLabels[trim($c)] ?? null, (array)$ovConcerns));
        $ovSummaryLabel  = $ovSkinType ? implode(' · ', array_merge([$ovSkinType], array_slice($ovConcernParts, 0, 2))) : null;
        $ovUpdated       = $ovDate ? \Carbon\Carbon::parse($ovDate)->diffForHumans() : null;
      @endphp
      <div style="background:var(--lime);border-radius:var(--r-xl);padding:24px;margin-bottom:28px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div>
          <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--gray-700);margin-bottom:6px">Your Skin Profile</div>
          @if($ovSummaryLabel)
            <div style="font-size:1.1rem;font-weight:700;color:var(--black)">{{ $ovSummaryLabel }}</div>
            <div style="font-size:.85rem;color:var(--gray-700);margin-top:4px">Last updated: {{ $ovUpdated ?? 'recently' }}</div>
          @else
            <div style="font-size:1rem;font-weight:700;color:var(--black)">No quiz taken yet</div>
            <div style="font-size:.85rem;color:var(--gray-700);margin-top:4px">Take the quiz to get your personalised skin profile</div>
          @endif
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          @if($ovSkinType)
            <a href="{{ route('results') }}" class="btn btn-dark btn-sm">View Results</a>
          @endif
          <a href="{{ route('quiz') }}" class="btn btn-outline btn-sm">{{ $ovSkinType ? 'Retake Quiz' : 'Take Quiz →' }}</a>
        </div>
      </div>

      @php
        $walletBalance = number_format((float)(($walletData ?? [])['wallet']['available_balance'] ?? 0), 2);
        $walletStatus  = ($walletData ?? [])['wallet']['status'] ?? 'active';
      @endphp
      <div class="dash-stat-cards">

        {{-- Points --}}
        <div class="dash-stat-card" style="--_accent:var(--lime);--_icon-bg:var(--lime-pale)">
          <div class="dash-stat-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--lime-dark)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <div class="dash-stat-label">Total Points</div>
          <div class="dash-stat-value" style="color:var(--lime-dark)">{{ $loyaltyPoints }}</div>
          <div class="dash-stat-change" style="color:var(--success)">{{ $pointsThisMonth > 0 ? '+'.number_format($pointsThisMonth).' this month' : $tierLabel }}</div>
        </div>

        {{-- Orders --}}
        <div class="dash-stat-card" style="--_accent:#6366F1;--_icon-bg:#EEF2FF">
          <div class="dash-stat-icon" style="background:#EEF2FF">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
          </div>
          <div class="dash-stat-label">Orders</div>
          <div class="dash-stat-value">{{ $orderCount }}</div>
          <div class="dash-stat-change" style="color:var(--text-muted)">
            @if($orderCount){{ $deliveredCount }} delivered@if($shippingCount), {{ $shippingCount }} shipping@endif
            @else No orders yet @endif
          </div>
        </div>

        {{-- Wallet --}}
        <div class="dash-stat-card" onclick="switchDashPanel('panel-wallet')" style="cursor:pointer;--_accent:#10B981;--_icon-bg:#ECFDF5" title="Go to Wallet">
          <div class="dash-stat-icon" style="background:#ECFDF5">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
          </div>
          <div class="dash-stat-label">Wallet Balance</div>
          <div class="dash-stat-value" style="font-size:1.6rem;color:#059669">₦{{ $walletBalance }}</div>
          <div class="dash-stat-change" style="display:flex;align-items:center;gap:5px;color:var(--text-muted)">
            <span style="width:6px;height:6px;border-radius:50%;background:{{ $walletStatus === 'active' ? '#10B981' : '#f59e0b' }};flex-shrink:0;display:inline-block"></span>
            {{ ucfirst($walletStatus) }} · <span style="text-decoration:underline;cursor:pointer">Top up →</span>
          </div>
        </div>

        {{-- Referrals --}}
        <div class="dash-stat-card" style="--_accent:#F59E0B;--_icon-bg:#FFFBEB">
          <div class="dash-stat-icon" style="background:#FFFBEB">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <div class="dash-stat-label">Referrals</div>
          <div class="dash-stat-value">{{ $refTotal }}</div>
          <div class="dash-stat-change" style="color:var(--text-muted)">{{ $refCompleted }} completed · {{ number_format($refTotalPoints) }} pts</div>
        </div>

        {{-- Subscription --}}
        @php
          $subAccent = $subStatus === 'active' ? 'var(--lime)' : ($subStatus ? '#F59E0B' : 'var(--border)');
          $subIconBg = $subStatus === 'active' ? 'var(--lime-pale)' : ($subStatus ? '#FFFBEB' : '#F9FAFB');
          $subIconStroke = $subStatus === 'active' ? 'var(--lime-dark)' : ($subStatus ? '#D97706' : '#9CA3AF');
          $subValSize = strlen((string)$subShipValue) > 7 ? '1.3rem' : '2rem';
        @endphp
        <div class="dash-stat-card" style="--_accent:{{ $subAccent }};--_icon-bg:{{ $subIconBg }}">
          <div class="dash-stat-icon" style="background:{{ $subIconBg }}">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="{{ $subIconStroke }}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
          </div>
          <div class="dash-stat-label">Subscription</div>
          <div class="dash-stat-value" style="font-size:{{ $subValSize }}">{{ $subShipValue }}</div>
          <div class="dash-stat-change" style="color:var(--text-muted)">{{ $subShipSub }}</div>
        </div>

      </div>

      <div class="loyalty-bar-card">
        <div class="loyalty-bar-header">
          <div>
            <div style="font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.18em;color:rgba(255,255,255,.22);margin-bottom:8px">Loyalty Status</div>
            <div class="loyalty-tier-name js-tier-color" data-color="{{ $tierColor }}">{{ $tierIcon }} {{ $tierLabel }}</div>
            @if($multiplier > 1)
            <div style="display:inline-flex;align-items:center;gap:5px;margin-top:10px;background:rgba(212,217,148,.09);border:1px solid rgba(212,217,148,.18);border-radius:999px;padding:3px 10px">
              <span style="width:5px;height:5px;background:var(--lime);border-radius:50%;display:inline-block"></span>
              <span style="font-size:.62rem;font-weight:700;letter-spacing:.06em;color:rgba(255,255,255,.5)">{{ $multiplier }}× points multiplier</span>
            </div>
            @endif
          </div>
          <div style="text-align:right">
            <div style="font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.18em;color:rgba(255,255,255,.22);margin-bottom:8px">Balance</div>
            <div class="loyalty-pts">{{ $loyaltyPoints }}</div>
            <div class="loyalty-pts-label">points</div>
          </div>
        </div>
        <div class="loyalty-progress">
          <div class="loyalty-progress-bar"><div class="loyalty-progress-fill" data-pct="{{ $loyaltyProgressPct }}%" style="width:0%"></div></div>
          <div class="loyalty-progress-labels">
            <span>{{ $tierLabel }}</span>
            @if($nextTierName)
              <span style="color:var(--lime)">{{ $loyaltyProgressPct }}% to {{ $nextTierName }}</span>
            @else
              <span style="color:var(--lime)">Max tier reached 🎉</span>
            @endif
          </div>
        </div>
        @if($nextTierName)
        <div style="font-size:.82rem;color:rgba(255,255,255,.4);margin-top:8px">
          🔢 <strong style="color:rgba(255,255,255,.7)">{{ $pointsToNext }} pts</strong> more to unlock <strong style="color:var(--lime)">{{ $nextTierName }}</strong>{{ $nextTierBenefit ? ' — ' . $nextTierBenefit : '' }}
        </div>
        @else
        <div style="font-size:.82rem;color:rgba(255,255,255,.4);margin-top:8px">🏆 You've reached the highest tier — enjoy all benefits!</div>
        @endif
      </div>

      <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px">Recent Orders</h3>
      @if(isset($orders) && count($orders))
        @foreach(array_slice($orders, 0, 2) as $order)
        @php
          $oItemNames = array_column($order['items'] ?? [], 'name');
          $oSummary   = implode(' + ', array_slice($oItemNames, 0, 3));
          if (count($oItemNames) > 3) $oSummary .= ' + ' . (count($oItemNames) - 3) . ' more';
          if (!$oSummary) $oSummary = 'Order items';
        @endphp
        <div class="order-card">
          <div class="order-info">
            <div class="order-id">#{{ $order['order_number'] ?? 'KMH-0000' }}</div>
            <div class="order-items">{{ $oSummary }}</div>
            <div class="order-date">Ordered {{ \Carbon\Carbon::parse($order['created_at'])->format('F d, Y') }}</div>
          </div>
          <div><span class="order-status status-{{ strtolower($order['status'] ?? 'pending') }}">{{ ucfirst($order['status'] ?? 'Pending') }}</span></div>
          <div class="order-total" style="text-align:right">₦{{ number_format($order['total'] ?? 0) }}</div>
        </div>
        @endforeach
      @else
        <div style="text-align:center;padding:20px 0;color:var(--text-muted)">
          <div style="font-size:2rem;margin-bottom:8px">📦</div>
          <p style="font-size:.9rem">No orders yet. <a href="{{ route('shop') }}" style="color:var(--black);font-weight:700">Shop now →</a></p>
        </div>
      @endif
      <div style="margin-top:16px"><a class="btn btn-ghost" onclick="switchDashPanel('panel-orders')">View All Orders →</a></div>

      <h3 style="font-size:1rem;font-weight:700;margin:28px 0 16px">Recommended for You</h3>
      <div class="scroll-track" id="dash-rec-track"></div>
    </div>

    <!-- PROFILE PANEL -->
    <div class="dashboard-panel" id="panel-profile">
      <div class="dash-welcome"><h1 class="dash-greeting">My Profile</h1><p class="dash-subtext">Manage your personal information and preferences.</p></div>

      @php
        $prefs = $user['email_prefs'] ?? [];
      @endphp

      {{-- Hidden flag so JS can auto-open this panel after a save redirect --}}
      @if(session('profile_success') || session('profile_error') || session('address_success') || session('address_error') || session('prefs_success') || session('prefs_error') || session('avatar_success') || session('avatar_error'))
        <span id="dash-profile-flash" style="display:none"></span>
      @endif

      {{-- Flash messages --}}
      @if(session('profile_success') || session('address_success') || session('prefs_success') || session('avatar_success'))
        <div style="background:#dcfce7;color:#166534;border:1px solid #86efac;padding:12px 20px;border-radius:var(--r-lg);margin-bottom:20px;display:flex;align-items:center;gap:10px;font-weight:600;font-size:.88rem;">
          ✓ {{ session('profile_success') ?? session('address_success') ?? session('prefs_success') ?? session('avatar_success') }}
        </div>
      @endif
      @if(session('profile_error') || session('address_error') || session('prefs_error') || session('avatar_error'))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 20px;border-radius:var(--r-lg);margin-bottom:20px;display:flex;align-items:center;gap:10px;font-weight:600;font-size:.88rem;">
          ✕ {{ session('profile_error') ?? session('address_error') ?? session('prefs_error') ?? session('avatar_error') }}
        </div>
      @endif

      {{-- Profile picture --}}
      <div style="background:#fff;border-radius:var(--r-xl);padding:28px 32px;border:1.5px solid var(--border);margin-bottom:24px">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px">Profile Picture</h3>
        <form method="POST" action="{{ route('dashboard.profile.avatar') }}" enctype="multipart/form-data" id="avatarForm">
          @csrf
          <div style="display:flex;align-items:center;gap:28px;flex-wrap:wrap">
            <div style="position:relative;width:96px;height:96px;flex-shrink:0">
              @if(!empty($user['avatar']))
                <img id="avatarPreview" src="{{ $user['avatar'] }}" alt="Profile picture" style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--border);">
              @else
                <div id="avatarInitial" style="width:96px;height:96px;border-radius:50%;background:var(--black);color:var(--lime);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:2.2rem;font-weight:700;border:3px solid var(--border);">{{ $avatarLetter }}</div>
                <img id="avatarPreview" src="" alt="" style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--border);display:none;position:absolute;top:0;left:0">
              @endif
              <label for="avatarInput" style="position:absolute;bottom:0;right:0;width:28px;height:28px;background:var(--lime);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid #fff;font-size:.85rem;box-shadow:0 2px 6px rgba(0,0,0,.15)" title="Upload photo">📷</label>
              <input type="file" id="avatarInput" name="avatar" accept="image/jpg,image/jpeg,image/png,image/webp" style="display:none" onchange="previewAvatar(this)">
            </div>
            <div>
              <div style="font-size:.88rem;font-weight:600;margin-bottom:6px">{{ $user['name'] ?? '' }}</div>
              <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:12px">JPG, PNG or WebP · Max 2 MB</div>
              <div style="display:flex;gap:8px;flex-wrap:wrap">
                <button type="button" onclick="document.getElementById('avatarInput').click()" class="btn btn-outline btn-sm">Choose Photo</button>
                <button type="submit" id="avatarSaveBtn" class="btn btn-primary btn-sm" style="display:none">Save Photo</button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        {{-- Personal info --}}
        <div style="background:#fff;border-radius:var(--r-xl);padding:32px;border:1.5px solid var(--border)">
          <h3 style="font-size:1rem;font-weight:700;margin-bottom:24px">Personal Information</h3>
          <form method="POST" action="{{ route('dashboard.profile.update') }}">
            @csrf
            <div style="display:flex;flex-direction:column;gap:16px">
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Full Name</label>
                <input class="input" name="name" value="{{ old('name', $user['name'] ?? '') }}" required>
              </div>
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Email</label>
                <input class="input" type="email" value="{{ $user['email'] ?? '' }}" disabled style="opacity:.6;cursor:not-allowed" title="Email cannot be changed here">
              </div>
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Phone</label>
                <input class="input" name="phone" type="tel" value="{{ old('phone', $user['phone'] ?? '') }}">
              </div>
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Birthday</label>
                <input class="input" type="date" name="birthday" value="{{ old('birthday', $user['birthday'] ?? '') }}">
              </div>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>

        {{-- Delivery address --}}
        <div style="background:#fff;border-radius:var(--r-xl);padding:32px;border:1.5px solid var(--border)">
          <h3 style="font-size:1rem;font-weight:700;margin-bottom:24px">Delivery Address</h3>
          <form method="POST" action="{{ route('dashboard.profile.address') }}">
            @csrf
            <div style="display:flex;flex-direction:column;gap:16px">
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Address Line 1</label>
                <input class="input" name="address_line1" value="{{ old('address_line1', $user['address_line1'] ?? '') }}" placeholder="e.g. 14 Admiralty Way">
              </div>
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Address Line 2</label>
                <input class="input" name="address_line2" value="{{ old('address_line2', $user['address_line2'] ?? '') }}" placeholder="Estate, area">
              </div>
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">City</label>
                <input class="input" name="city" value="{{ old('city', $user['city'] ?? '') }}" placeholder="City">
              </div>
              <div>
                <label style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">State</label>
                <input class="input" name="state" value="{{ old('state', $user['state'] ?? '') }}" placeholder="State">
              </div>
              <button type="submit" class="btn btn-primary">Save Address</button>
            </div>
          </form>
        </div>
      </div>

      {{-- Email preferences --}}
      <div style="background:#fff;border-radius:var(--r-xl);padding:32px;border:1.5px solid var(--border);margin-top:24px">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px">Email Preferences</h3>
        <form method="POST" action="{{ route('dashboard.profile.email_prefs') }}">
          @csrf
          <div style="display:flex;flex-direction:column;gap:14px">
            <label class="checkbox-label"><input type="checkbox" name="routine_tips" value="1" {{ ($prefs['routine_tips'] ?? true) ? 'checked' : '' }}><span class="checkbox-box"></span>Routine updates and skincare tips</label>
            <label class="checkbox-label"><input type="checkbox" name="new_products" value="1" {{ ($prefs['new_products'] ?? true) ? 'checked' : '' }}><span class="checkbox-box"></span>New product launches &amp; restocks</label>
            <label class="checkbox-label"><input type="checkbox" name="subscription" value="1" {{ ($prefs['subscription'] ?? true) ? 'checked' : '' }}><span class="checkbox-box"></span>Subscription pre-shipment notifications</label>
            <label class="checkbox-label"><input type="checkbox" name="promotions" value="1" {{ ($prefs['promotions'] ?? false) ? 'checked' : '' }}><span class="checkbox-box"></span>Promotional emails &amp; deals</label>
            <label class="checkbox-label"><input type="checkbox" name="loyalty_updates" value="1" {{ ($prefs['loyalty_updates'] ?? true) ? 'checked' : '' }}><span class="checkbox-box"></span>Loyalty points &amp; tier updates</label>
          </div>
          <button type="submit" class="btn btn-primary" style="margin-top:20px">Update Preferences</button>
        </form>
      </div>
    </div>

    <!-- SKIN PROFILE PANEL -->
    <div class="dashboard-panel" id="panel-skin">
      @php
        $sp_type    = $latestQuizResult['skin_type'] ?? ($user['skin_type'] ?? null);
        $sp_scores  = is_array($latestQuizResult['skin_scores'] ?? null) ? $latestQuizResult['skin_scores'] : null;
        $sp_answers = $latestQuizResult['answers'] ?? [];
        $sp_date    = $latestQuizResult['created_at'] ?? null;

        $sp_concerns = $sp_answers['concerns'] ?? [];
        if (is_string($sp_concerns)) $sp_concerns = array_filter(array_map('trim', explode(',', $sp_concerns)));
        $sp_concerns = array_values((array)$sp_concerns);

        $sp_severity = $sp_answers['severity'] ?? 'moderate';

        $sp_skinLabels = ['Oily'=>'Oily & Acne-Prone','Dry'=>'Dry & Dehydrated','Combination'=>'Combination & Acne-Prone','Normal'=>'Balanced & Healthy','Sensitive'=>'Sensitive & Reactive'];
        $sp_skinIcons  = ['Oily'=>'💧','Dry'=>'🌵','Combination'=>'☯️','Normal'=>'✨','Sensitive'=>'🌸'];
        $sp_label  = $sp_skinLabels[$sp_type] ?? $sp_type ?? 'Unknown';
        $sp_icon   = $sp_skinIcons[$sp_type]  ?? '🔬';
        $sp_updated = $sp_date ? \Carbon\Carbon::parse($sp_date)->diffForHumans() : null;

        // Score color logic
        $sp_scoreColor = function(string $metric, int $val): string {
          $risky = in_array($metric, ['Acne Risk','Sensitivity','Oil Level']);
          if ($risky) return $val >= 7 ? 'var(--red)' : ($val >= 5 ? '#F59E0B' : 'var(--lime)');
          return $val >= 7 ? 'var(--lime)' : ($val >= 5 ? '#93C5FD' : 'var(--red)');
        };

        // Ingredients & avoid list per skin type
        $sp_ingredMap = [
          'Oily'        => ['Niacinamide','Salicylic Acid','BHA','Clay','Tea Tree'],
          'Dry'         => ['Hyaluronic Acid','Ceramides','Squalane','Shea Butter','Panthenol'],
          'Combination' => ['Niacinamide','Salicylic Acid','Hyaluronic Acid','Centella Asiatica','BHA'],
          'Normal'      => ['Vitamin C','Retinol','Hyaluronic Acid','SPF 50+','Niacinamide'],
          'Sensitive'   => ['Centella Asiatica','Niacinamide','Ceramides','Aloe Vera','Panthenol'],
        ];
        $sp_avoidMap = [
          'Oily'        => ['Heavy Oils','Petrolatum','Mineral Oil'],
          'Dry'         => ['Alcohol Denat.','Harsh Sulfates','Strong Retinol'],
          'Combination' => ['Alcohol Denat.','Heavy Oils','Fragrance'],
          'Normal'      => ['Petrolatum','Mineral Oil'],
          'Sensitive'   => ['Fragrance','Alcohol Denat.','High-% AHAs'],
        ];
        $sp_ingred = $sp_ingredMap[$sp_type] ?? $sp_ingredMap['Normal'];
        $sp_avoid  = $sp_avoidMap[$sp_type]  ?? $sp_avoidMap['Normal'];

        // Add concern-specific ingredients
        $sp_extraIngred = ['dark_spots'=>'Vitamin C','dehydration'=>'Hyaluronic Acid','fine_lines'=>'Retinol','dull'=>'Vitamin C','texture'=>'AHA'];
        foreach ($sp_concerns as $c) {
          if (isset($sp_extraIngred[$c]) && !in_array($sp_extraIngred[$c], $sp_ingred)) $sp_ingred[] = $sp_extraIngred[$c];
        }
        $sp_ingred = array_slice($sp_ingred, 0, 7);

        // Concern labels + emojis
        $sp_concernMeta = [
          'acne'        => ['🤕','Acne / Breakouts'],
          'dark_spots'  => ['🔘','Dark Spots'],
          'dull'        => ['😴','Dull Skin'],
          'texture'     => ['🏔️','Uneven Texture'],
          'fine_lines'  => ['⏰','Fine Lines'],
          'sensitive'   => ['🌡️','Sensitivity'],
          'dehydration' => ['🏜️','Dehydration'],
          'large_pores' => ['🕳️','Large Pores'],
        ];
        // Severity badge per concern (first = severity level, rest = mild)
        $sp_severityBadge = [
          'severe'   => ['badge badge-red','High'],
          'moderate' => ['badge','Moderate'],
          'mild'     => ['badge badge-outlined','Mild'],
        ];
        $sp_defaultMetrics = ['Oily'=>['Hydration'=>4,'Acne Risk'=>7,'Sensitivity'=>4,'Oil Level'=>8,'Barrier Health'=>4],'Dry'=>['Hydration'=>3,'Acne Risk'=>3,'Sensitivity'=>6,'Oil Level'=>2,'Barrier Health'=>3],'Combination'=>['Hydration'=>5,'Acne Risk'=>6,'Sensitivity'=>5,'Oil Level'=>6,'Barrier Health'=>5],'Normal'=>['Hydration'=>7,'Acne Risk'=>2,'Sensitivity'=>3,'Oil Level'=>4,'Barrier Health'=>8],'Sensitive'=>['Hydration'=>5,'Acne Risk'=>4,'Sensitivity'=>8,'Oil Level'=>4,'Barrier Health'=>4]];
        $sp_displayScores = $sp_scores ?: ($sp_defaultMetrics[$sp_type] ?? $sp_defaultMetrics['Normal']);
      @endphp

      <div class="dash-welcome">
        <h1 class="dash-greeting">My Skin Profile 🔬</h1>
        <p class="dash-subtext">
          @if($sp_type) Your personalized skin data — retake the quiz anytime to update.
          @else Take the skin quiz to generate your personalised skin profile.
          @endif
        </p>
      </div>

      @if($sp_type)
        {{-- Black hero card with skin type + score bars --}}
        <div style="background:var(--black);color:#fff;border-radius:var(--r-xl);padding:36px;margin-bottom:24px">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:20px;margin-bottom:28px">
            <div>
              <span class="skin-type-badge">{{ $sp_icon }} {{ $sp_type }} Skin</span>
              <h2 style="font-family:var(--font-display);font-size:1.8rem;color:#fff;margin-top:12px;line-height:1.25">
                {{ $sp_label }} Skin
                @if(!empty($sp_concerns))
                  <br><span style="font-size:1.1rem;color:rgba(255,255,255,.6)">
                    {{ implode(' · ', array_filter(array_map(fn($c) => $sp_concernMeta[trim($c)][1] ?? null, $sp_concerns))) }}
                  </span>
                @endif
              </h2>
              @if($sp_updated)
                <div style="font-size:.78rem;color:rgba(255,255,255,.35);margin-top:8px">Last updated {{ $sp_updated }}</div>
              @endif
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
              <a href="{{ route('results') }}" class="btn btn-outline-lime btn-sm">View Results</a>
              <a href="{{ route('quiz') }}" class="btn btn-primary btn-sm">Retake Quiz</a>
            </div>
          </div>
          <div class="skin-score-bars">
            @foreach($sp_displayScores as $metric => $val)
              @php $val = (int)$val; $color = $sp_scoreColor($metric, $val); @endphp
              <div class="skin-score-row">
                <div class="skin-score-label">
                  <span style="color:rgba(255,255,255,.8)">{{ $metric }}</span>
                  <span style="color:{{ $color }}">{{ $val }}/10</span>
                </div>
                <div class="skin-score-bar">
                  <div class="skin-score-fill" style="width:{{ $val * 10 }}%;background:{{ $color }}" data-target="{{ $val * 10 }}%"></div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Concerns + Ingredients grid --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
          <div style="background:#fff;border-radius:var(--r-xl);padding:28px;border:1.5px solid var(--border)">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px">Your Top Concerns</h3>
            @if(!empty($sp_concerns))
              <div style="display:flex;flex-direction:column;gap:10px">
                @foreach($sp_concerns as $i => $concern)
                  @php
                    $concern = trim($concern);
                    $meta = $sp_concernMeta[$concern] ?? ['🔬', ucfirst(str_replace('_',' ',$concern))];
                    // First concern = quiz severity level, rest = one step lighter
                    $sevKey = $i === 0 ? $sp_severity : ($sp_severity === 'severe' ? 'moderate' : 'mild');
                    [$badgeClass, $badgeLabel] = $sp_severityBadge[$sevKey] ?? $sp_severityBadge['mild'];
                    $bg = $i === 0 ? 'rgba(137,57,65,.05)' : ($i === 1 ? 'rgba(245,158,11,.05)' : 'rgba(212,217,148,.05)');
                  @endphp
                  <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:{{ $bg }};border-radius:var(--r-md)">
                    <span style="font-weight:600">{{ $meta[0] }} {{ $meta[1] }}</span>
                    <span class="{{ $badgeClass }}">{{ $badgeLabel }}</span>
                  </div>
                @endforeach
              </div>
            @else
              <p style="font-size:.88rem;color:var(--text-muted)">No concerns recorded. <a href="{{ route('quiz') }}" style="color:var(--lime-dark);font-weight:700">Retake quiz</a> to add.</p>
            @endif
          </div>
          <div style="background:#fff;border-radius:var(--r-xl);padding:28px;border:1.5px solid var(--border)">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px">Key Ingredients for You</h3>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
              @foreach($sp_ingred as $ing)
                <span class="tag active">{{ $ing }}</span>
              @endforeach
            </div>
            <div style="margin-top:20px;padding:12px;background:var(--lime-pale);border-radius:var(--r-md)">
              <div style="font-size:.78rem;font-weight:700;color:var(--gray-700);margin-bottom:6px">⚠️ Ingredients to Avoid</div>
              <div style="display:flex;flex-wrap:wrap;gap:6px">
                @foreach($sp_avoid as $av)
                  <span style="padding:4px 10px;background:rgba(137,57,65,.1);color:var(--red);border-radius:var(--r-pill);font-size:.75rem;font-weight:700">{{ $av }}</span>
                @endforeach
              </div>
            </div>
          </div>
        </div>

      @else
        {{-- No quiz taken yet --}}
        <div style="text-align:center;padding:60px 24px;background:#fff;border-radius:var(--r-xl);border:2px dashed var(--border)">
          <div style="font-size:3rem;margin-bottom:16px">🔬</div>
          <h3 style="font-size:1.2rem;font-weight:700;margin-bottom:8px">No Skin Profile Yet</h3>
          <p style="font-size:.9rem;color:var(--text-muted);max-width:360px;margin:0 auto 24px">Take the Kominhoo Skin OS quiz to get your personalised skin type, metric scores, and product recommendations.</p>
          <a href="{{ route('quiz') }}" class="btn btn-primary btn-lg">Take the Skin Quiz →</a>
        </div>
      @endif
    </div>

    <!-- ORDERS PANEL -->
    <div class="dashboard-panel" id="panel-orders">
      <div class="dash-welcome">
        <h1 class="dash-greeting">My Orders 📦</h1>
        <p class="dash-subtext">Track deliveries, reorder your favourites, and view your full purchase history.</p>
      </div>

      @if(isset($orders) && count($orders))
      @php
        $totalSpend    = array_sum(array_column($orders, 'total'));
        $activeOrders  = count(array_filter($orders, fn($o) => in_array($o['status'] ?? '', ['pending','processing','shipped'])));
        $deliveredCount= count(array_filter($orders, fn($o) => ($o['status'] ?? '') === 'delivered'));
      @endphp

      {{-- Stats bar --}}
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:28px">
        <div style="background:#fff;border-radius:var(--r-lg);padding:18px 20px;border:1.5px solid var(--border)">
          <div style="font-size:1.4rem;font-weight:700">{{ count($orders) }}</div>
          <div style="font-size:.78rem;color:var(--text-muted);margin-top:3px">Total Orders</div>
        </div>
        <div style="background:#fff;border-radius:var(--r-lg);padding:18px 20px;border:1.5px solid var(--border)">
          <div style="font-size:1.4rem;font-weight:700">₦{{ number_format($totalSpend) }}</div>
          <div style="font-size:.78rem;color:var(--text-muted);margin-top:3px">Total Spent</div>
        </div>
        <div style="background:#fff;border-radius:var(--r-lg);padding:18px 20px;border:1.5px solid var(--border)">
          <div style="font-size:1.4rem;font-weight:700;color:{{ $activeOrders ? '#f59e0b' : '#16a34a' }}">{{ $activeOrders ?: $deliveredCount }}</div>
          <div style="font-size:.78rem;color:var(--text-muted);margin-top:3px">{{ $activeOrders ? 'Active Orders' : 'Delivered' }}</div>
        </div>
      </div>

      {{-- Filter tabs --}}
      <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap" id="order-filter-tabs">
        @foreach(['all'=>'All', 'pending'=>'Pending', 'processing'=>'Processing', 'shipped'=>'Shipped', 'delivered'=>'Delivered', 'cancelled'=>'Cancelled'] as $key => $label)
        <button onclick="filterOrders('{{ $key }}')" data-filter="{{ $key }}"
          style="padding:7px 16px;border-radius:999px;font-size:.8rem;font-weight:700;border:1.5px solid var(--border);background:#fff;cursor:pointer;transition:all .15s"
          class="order-filter-btn {{ $key === 'all' ? 'active-filter' : '' }}">{{ $label }}</button>
        @endforeach
      </div>

      {{-- Order cards --}}
      <div id="orders-list" style="display:grid;gap:16px">
        @foreach($orders as $order)
        @php
          $oStatus   = $order['status'] ?? 'pending';
          $oItems    = $order['items'] ?? [];
          $oAddr     = $order['shipping_address'] ?? [];
          $oDiscount = (float)($order['discount'] ?? 0);
          $oShipping = (float)($order['shipping'] ?? 0);
          $oSubtotal = (float)($order['subtotal'] ?? 0);
          $oTotal    = (float)($order['total'] ?? 0);
          $oTracking = $order['tracking_number'] ?? null;
          $oPayment  = str_replace('_', ' ', ucfirst($order['payment_method'] ?? ''));

          $statusStep = match($oStatus) {
            'processing' => 2,
            'shipped'    => 3,
            'delivered'  => 4,
            default      => 1,
          };
          $isCancelled = $oStatus === 'cancelled';

          $statusLabel = match($oStatus) {
            'pending'    => ['Pending',    'pending'],
            'processing' => ['Processing', 'pending'],
            'shipped'    => ['Shipped',    'shipped'],
            'delivered'  => ['Delivered',  'active'],
            'cancelled'  => ['Cancelled',  'cancelled'],
            default      => ['Pending',    'pending'],
          };
        @endphp

        <div class="order-detail-card" data-status="{{ $oStatus }}"
          style="background:#fff;border-radius:var(--r-xl);border:1.5px solid var(--border);overflow:hidden">

          {{-- Card header --}}
          <div style="padding:20px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;border-bottom:1px solid var(--border)">
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
              <div>
                <div style="font-weight:700;font-size:.95rem">#{{ $order['order_number'] ?? 'KMH-0000' }}</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">
                  {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y · h:i A') }}
                </div>
              </div>
              <span class="order-status status-{{ $statusLabel[1] }}" style="font-size:.78rem">{{ $statusLabel[0] }}</span>
            </div>
            <div style="text-align:right">
              <div style="font-size:1.15rem;font-weight:700">₦{{ number_format($oTotal) }}</div>
              <div style="font-size:.75rem;color:var(--text-muted)">{{ count($oItems) }} item{{ count($oItems) !== 1 ? 's' : '' }}</div>
            </div>
          </div>

          {{-- Delivery progress (skip if cancelled) --}}
          @if(!$isCancelled)
          <div style="padding:20px 24px;border-bottom:1px solid var(--border)">
            <div style="display:flex;align-items:center;gap:0">
              @foreach([1=>'Order Placed', 2=>'Processing', 3=>'Shipped', 4=>'Delivered'] as $step => $stepLabel)
              @php $done = $statusStep >= $step; $active = $statusStep === $step; @endphp
              <div style="display:flex;align-items:center;flex:{{ $step < 4 ? '1' : '0' }}">
                <div style="display:flex;flex-direction:column;align-items:center;gap:6px;min-width:72px">
                  <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0;
                    background:{{ $done ? 'var(--black)' : '#F3F4F6' }};
                    color:{{ $done ? ($step < 4 ? 'var(--lime)' : '#fff') : '#9CA3AF' }};
                    border:2px solid {{ $done ? 'var(--black)' : '#E5E7EB' }}">
                    @if($done && $step < 4)✓@elseif($step === 4 && $done)★@else{{ $step }}@endif
                  </div>
                  <div style="font-size:.68rem;font-weight:{{ $active ? '800' : '600' }};color:{{ $done ? 'var(--black)' : 'rgba(10,10,10,.3)' }};text-align:center;white-space:nowrap">{{ $stepLabel }}</div>
                </div>
                @if($step < 4)
                <div style="flex:1;height:2px;background:{{ $statusStep > $step ? 'var(--black)' : 'var(--border)' }};margin:0 4px;margin-bottom:22px"></div>
                @endif
              </div>
              @endforeach
            </div>
            @if($oTracking || !empty($order['admin_note']))
            <div style="margin-top:12px;display:grid;gap:8px">
              @if($oTracking)
              <div style="background:var(--cream);border-radius:var(--r-md);padding:10px 14px;font-size:.82rem;display:flex;align-items:center;gap:8px">
                <span>🚚</span>
                <span>Tracking: <strong style="font-family:'DM Sans',system-ui,sans-serif">{{ $oTracking }}</strong></span>
              </div>
              @endif
              @if(!empty($order['admin_note']))
              <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:var(--r-md);padding:10px 14px;font-size:.82rem;display:flex;align-items:flex-start;gap:8px">
                <span style="flex-shrink:0">💬</span>
                <div>
                  <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#92400e;margin-bottom:2px">Message from Kominhoo</div>
                  <div style="color:#78350f;line-height:1.5">{{ $order['admin_note'] }}</div>
                </div>
              </div>
              @endif
            </div>
            @endif
          </div>
          @endif

          {{-- Items list --}}
          <div style="padding:20px 24px;border-bottom:1px solid var(--border)">
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:12px">Order Items</div>
            <div style="display:grid;gap:12px">
              @foreach($oItems as $item)
              <div style="display:flex;align-items:center;gap:12px">
                <div style="width:44px;height:44px;border-radius:var(--r-md);background:var(--cream);display:grid;place-items:center;font-size:1.3rem;flex-shrink:0">🧴</div>
                <div style="flex:1">
                  <div style="font-weight:600;font-size:.88rem">{{ $item['name'] ?? 'Item' }}</div>
                  <div style="font-size:.75rem;color:var(--text-muted)">Qty {{ $item['quantity'] ?? 1 }}</div>
                </div>
                <div style="font-weight:700;font-size:.88rem">₦{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}</div>
              </div>
              @endforeach
            </div>

            {{-- Price breakdown --}}
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);display:grid;gap:7px;font-size:.85rem">
              <div style="display:flex;justify-content:space-between;color:var(--text-muted)">
                <span>Subtotal</span><span>₦{{ number_format($oSubtotal) }}</span>
              </div>
              @if($oDiscount > 0)
              <div style="display:flex;justify-content:space-between;color:#16a34a">
                <span>Discount{{ $order['coupon_code'] ? ' (' . $order['coupon_code'] . ')' : '' }}</span>
                <span>-₦{{ number_format($oDiscount) }}</span>
              </div>
              @endif
              <div style="display:flex;justify-content:space-between;color:var(--text-muted)">
                <span>Shipping</span>
                <span>{{ $oShipping === 0.0 ? 'Free' : '₦' . number_format($oShipping) }}</span>
              </div>
              <div style="display:flex;justify-content:space-between;font-weight:700;font-size:.92rem;padding-top:7px;border-top:1px solid var(--border)">
                <span>Total</span><span>₦{{ number_format($oTotal) }}</span>
              </div>
            </div>
          </div>

          {{-- Shipping + Payment info --}}
          <div style="padding:16px 24px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:8px">Ship To</div>
              <div style="font-size:.84rem;line-height:1.6;font-weight:600">{{ $oAddr['name'] ?? '—' }}</div>
              <div style="font-size:.82rem;color:var(--text-muted);line-height:1.6">
                {{ $oAddr['street'] ?? '' }}{{ !empty($oAddr['street']) ? ', ' : '' }}{{ $oAddr['city'] ?? '' }}{{ !empty($oAddr['city']) ? ', ' : '' }}{{ $oAddr['state'] ?? '' }}
              </div>
              @if(!empty($oAddr['phone']))
              <div style="font-size:.8rem;color:var(--text-muted);margin-top:2px">{{ $oAddr['phone'] }}</div>
              @endif
            </div>
            <div>
              <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:8px">Payment</div>
              <div style="font-size:.84rem;font-weight:600;line-height:1.6">{{ $oPayment ?: '—' }}</div>
              @php $pStatus = $order['payment_status'] ?? 'pending'; @endphp
              <span style="display:inline-block;margin-top:4px;padding:2px 10px;border-radius:999px;font-size:.72rem;font-weight:700;
                background:{{ $pStatus === 'paid' ? '#dcfce7' : '#fef3c7' }};
                color:{{ $pStatus === 'paid' ? '#16a34a' : '#92400e' }}">
                {{ ucfirst($pStatus) }}
              </span>
            </div>
          </div>


          {{-- Actions --}}
          <div style="padding:14px 24px;display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            @if($oStatus === 'shipped')
            <button onclick="showToast('🚚','Tracking info will be emailed to you.')"
              class="btn btn-dark btn-sm">🚚 Track Shipment</button>
            @endif
            @if(in_array($oStatus, ['delivered', 'cancelled']))
            <button onclick="reorderItems({{ json_encode(array_column($oItems, 'name')) }})"
              class="btn btn-outline btn-sm">🔄 Reorder</button>
            @endif
            @if($oStatus === 'pending')
            <button onclick="showToast('📧','Support has been notified. We\'ll email you within 24hrs.')"
              class="btn btn-ghost btn-sm" style="font-size:.78rem;color:var(--text-muted)">Cancel Order</button>
            @endif
            @if(!empty($order['notes']))
            <span style="font-size:.78rem;color:var(--text-muted);margin-left:auto">📝 Note: {{ $order['notes'] }}</span>
            @endif
          </div>

        </div>{{-- /order-detail-card --}}
        @endforeach
      </div>{{-- /orders-list --}}

      @else
      <div style="text-align:center;padding:64px 0;color:var(--text-muted)">
        <div style="font-size:3.5rem;margin-bottom:16px">📦</div>
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:8px;color:var(--black)">No orders yet</h3>
        <p style="font-size:.9rem;margin-bottom:28px">Your order history will appear here once you've placed an order.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">Shop Now →</a>
      </div>
      @endif

    </div>

    <!-- SAVED PANEL -->
    <div class="dashboard-panel" id="panel-saved">
      <div class="dash-welcome"><h1 class="dash-greeting">Saved Products ♡</h1><p class="dash-subtext" id="saved-subtext">Your wishlist</p></div>
      <div id="saved-sale-banner" style="display:none;background:var(--lime-pale);border:1.5px solid var(--lime-dark);border-radius:var(--r-md);padding:14px 18px;margin-bottom:24px;font-size:.88rem;font-weight:600;color:var(--gray-700);justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
        <span id="saved-sale-text"></span>
        <button class="btn btn-dark btn-sm" onclick="addSaleItemsToCart()">Shop Now</button>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px" id="saved-grid"></div>
    </div>

    <!-- LOYALTY PANEL -->
    <div class="dashboard-panel" id="panel-loyalty">
      <div class="dash-welcome"><h1 class="dash-greeting">Loyalty & Points 🌟</h1><p class="dash-subtext">Track your rewards, earn points on every action, and level up your tier.</p></div>

      {{-- Tier Card --}}
      <div class="loyalty-bar-card" style="margin-bottom:24px">
        <div class="loyalty-bar-header">
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:4px">Current Tier</div>
            <div class="loyalty-tier-name js-tier-color" data-color="{{ $tierColor }}">{{ $tierIcon }} {{ $tierLabel }}</div>
          </div>
          <div style="text-align:right">
            <div class="loyalty-pts" id="loyalty-pts-display">{{ $loyaltyPoints }}</div>
            <div class="loyalty-pts-label">total points</div>
          </div>
        </div>
        <div class="loyalty-progress">
          <div class="loyalty-progress-bar"><div class="loyalty-progress-fill" data-pct="{{ $loyaltyProgressPct }}%" style="width:0%"></div></div>
          <div class="loyalty-progress-labels">
            <span>{{ $tierLabel }}</span>
            @if($nextTierName)
              <span style="color:var(--lime)">{{ $loyaltyProgressPct }}% to {{ $nextTierName }} · {{ $pointsToNext }} pts needed</span>
            @else
              <span style="color:var(--lime)">Max tier reached 🎉</span>
            @endif
          </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:24px">
          <div style="background:rgba(255,255,255,.06);border-radius:var(--r-md);padding:16px;text-align:center">
            <div style="font-size:1.3rem;font-weight:700;color:var(--lime)">{{ $multiplier }}×</div>
            <div style="font-size:.78rem;color:rgba(255,255,255,.5);margin-top:4px">Points multiplier</div>
          </div>
          <div style="background:rgba(255,255,255,.06);border-radius:var(--r-md);padding:16px;text-align:center">
            <div style="font-size:1.3rem;font-weight:700;color:var(--lime)">{{ $loyaltyPoints }}</div>
            <div style="font-size:.78rem;color:rgba(255,255,255,.5);margin-top:4px">Points balance</div>
          </div>
          <div style="background:rgba(255,255,255,.06);border-radius:var(--r-md);padding:16px;text-align:center">
            <div style="font-size:1.3rem;font-weight:700;color:var(--lime)">{{ $nextTierName ? $pointsToNext : '—' }}</div>
            <div style="font-size:.78rem;color:rgba(255,255,255,.5);margin-top:4px">{{ $nextTierName ? 'pts to next tier' : 'Max tier' }}</div>
          </div>
        </div>
        {{-- Current tier benefits --}}
        @if(!empty($tierBenefits))
        <div style="margin-top:20px;padding-top:18px;border-top:1px solid rgba(255,255,255,.08)">
          <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.3);margin-bottom:10px">Your {{ $tierLabel }} Benefits</div>
          <div style="display:flex;flex-wrap:wrap;gap:8px">
            @foreach($tierBenefits as $benefit)
            <span style="background:rgba(255,255,255,.06);border-radius:var(--r-pill);padding:4px 12px;font-size:.78rem;color:rgba(255,255,255,.65)">✓ {{ $benefit }}</span>
            @endforeach
          </div>
        </div>
        @endif
      </div>

      {{-- Tier gift if unlocked --}}
      @if($tierGift)
      <div style="background:linear-gradient(135deg,#D4D994 0%,#5E6623 100%);border-radius:var(--r-xl);padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;gap:20px;flex-wrap:wrap">
        <div style="font-size:2.5rem">🎁</div>
        <div style="flex:1">
          <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(10,10,10,.5);margin-bottom:4px">Tier Gift Unlocked</div>
          <div style="font-size:1.1rem;font-weight:700;color:var(--black);margin-bottom:3px">{{ $tierGift['name'] }}</div>
          <div style="font-size:.82rem;color:rgba(10,10,10,.6)">{{ $tierGift['description'] }}</div>
        </div>
        <button class="btn btn-dark btn-sm" onclick="claimTierGift()">Claim Gift →</button>
      </div>
      @endif

      {{-- Points Activity --}}
      <div style="background:#fff;border-radius:var(--r-xl);padding:28px;border:1.5px solid var(--border);margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
          <h3 style="font-size:1rem;font-weight:700">Points Activity</h3>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <button class="btn btn-outline btn-sm" onclick="loadMorePointEvents()">Load More</button>
            <button class="btn btn-primary btn-sm" onclick="openRedeemModal()">Redeem Points →</button>
          </div>
        </div>
        <div class="points-activity" id="points-activity-list">
          @forelse($pointEvents as $evt)
          <div class="points-row" data-id="{{ $evt['id'] ?? '' }}">
            <div>
              <div style="font-weight:700">{{ $evt['note'] ?? ucfirst(str_replace('_',' ',$evt['event_type'] ?? '')) }}</div>
              <div style="font-size:.78rem;color:var(--text-muted)">{{ isset($evt['created_at']) ? date('F j, Y', strtotime($evt['created_at'])) : '' }}</div>
            </div>
            <div class="{{ ($evt['points'] ?? 0) >= 0 ? 'points-earn' : 'points-spend' }}">
              {{ ($evt['points'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($evt['points'] ?? 0) }} pts
            </div>
          </div>
          @empty
          <div style="text-align:center;padding:32px 0;color:var(--text-muted)">
            <div style="font-size:2rem;margin-bottom:8px">🌱</div>
            <div style="font-size:.88rem">No point activity yet — start earning by shopping, reviewing, or referring friends!</div>
          </div>
          @endforelse
        </div>
      </div>

      {{-- Tier Ladder --}}
      <div style="background:#fff;border-radius:var(--r-xl);padding:28px;border:1.5px solid var(--border);margin-bottom:24px">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px">Tier Ladder</h3>
        <div style="display:flex;flex-direction:column;gap:12px">
          @foreach($tiersConfig as $t)
          @php $isActive = ($t['id'] === $tierKey); $isPast = (($loyaltySummary['points'] ?? 0) >= $t['min_points']); @endphp
          <div class="tier-row js-tier-row {{ $isActive ? 'tier-row-active' : '' }}" data-color="{{ $t['color'] }}" data-active="{{ $isActive ? '1' : '0' }}" data-past="{{ $isPast ? '1' : '0' }}" style="display:flex;align-items:center;gap:14px;padding:14px 18px;border-radius:var(--r-lg);border:2px solid var(--border);background:#fff">
            <div class="js-tier-dot" data-color="{{ $t['color'] }}" data-past="{{ $isPast ? '1' : '0' }}" data-light="{{ in_array($t['id'],['starter','glow','iconic']) ? '1' : '0' }}" style="width:36px;height:36px;border-radius:50%;background:var(--border);display:grid;place-items:center;font-size:.75rem;font-weight:700;flex-shrink:0;color:#fff">{{ $t['icon'] ?? '✦' }}</div>
            <div style="flex:1">
                <div class="js-tier-name {{ $isActive ? 'tier-name-active' : '' }}" data-color="{{ $t['color'] }}" data-active="{{ $isActive ? '1' : '0' }}" style="font-size:.9rem;font-weight:700">{{ $t['name'] }}&nbsp;@if($isActive)<span class="js-tier-badge" data-color="{{ $t['color'] }}" style="font-size:.65rem;color:#1C1416;padding:2px 8px;border-radius:999px;font-weight:700;letter-spacing:.05em">CURRENT</span>@endif</div>
              <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">{{ number_format($t['min_points']) }} pts minimum · {{ $t['multiplier'] }}× multiplier</div>
            </div>
            @if($t['gift'])
            <div style="font-size:.75rem;font-weight:700;color:var(--lime-dark);background:var(--lime-pale);padding:4px 10px;border-radius:var(--r-pill)">🎁 Gift</div>
            @endif
          </div>
          @endforeach
        </div>
      </div>

      {{-- Earn More --}}
      <div style="background:var(--lime-pale);border-radius:var(--r-xl);padding:24px;border:1.5px solid var(--lime-dark)">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px">Ways to Earn Points</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px">
          @php
          $eventIcons = ['purchase'=>'🛍️','quiz'=>'🔬','review'=>'⭐','community_post'=>'📸','before_after'=>'✨','routine_post'=>'🧴','referral'=>'👥','profile_complete'=>'👤','birthday'=>'🎂','first_order'=>'🎉','welcome'=>'🌟'];
          @endphp
          @foreach($pointEventsConfig as $key => $evt)
          <div style="background:#fff;border-radius:var(--r-md);padding:16px;display:flex;gap:12px;align-items:center">
            <span style="font-size:1.4rem">{{ $eventIcons[$key] ?? '✦' }}</span>
            <div>
              <div style="font-size:.88rem;font-weight:700">{{ $evt['label'] }}</div>
              <div style="font-size:.75rem;color:var(--text-muted)">
                @if(isset($evt['points_per_1000'])) +{{ $evt['points_per_1000'] }} pts / ₦1,000
                @elseif(isset($evt['points'])) +{{ $evt['points'] }} pts
                @endif
                @if(!empty($evt['one_time'])) <span style="color:var(--lime-dark)">(one-time)</span>@endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Redeem modal --}}
      <div id="redeem-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;display:none;align-items:center;justify-content:center">
        <div style="background:#fff;border-radius:var(--r-xl);padding:32px;max-width:420px;width:90%;position:relative">
          <button onclick="document.getElementById('redeem-modal').style.display='none'" style="position:absolute;top:16px;right:16px;background:none;border:none;cursor:pointer;font-size:1.2rem">×</button>
          <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:6px">Redeem Points</h3>
          <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:20px">You have {{ $loyaltyPoints }} points. Each 100 pts = ₦100 discount.</p>
          <div style="margin-bottom:16px"><label style="font-size:.75rem;font-weight:700;display:block;margin-bottom:6px">Points to redeem</label><input class="input" type="number" id="redeem-pts-input" min="100" max="{{ $loyaltySummary['points'] ?? 0 }}" step="100" placeholder="e.g. 500" value="100"></div>
          <button class="btn btn-primary" style="width:100%;justify-content:center" onclick="submitRedeem()">Redeem Now</button>
        </div>
      </div>
    </div>

    <!-- SUBSCRIPTION PANEL -->
    <div class="dashboard-panel" id="panel-sub">
      <div class="dash-welcome">
        <h1 class="dash-greeting">My Subscription 📬</h1>
        <p class="dash-subtext">{{ $subStatus ? 'Manage your active subscription box.' : 'Choose a subscription plan to get curated K-beauty every month.' }}</p>
      </div>

      @if($subStatus)
      {{-- Active / Paused subscription --}}
      <div class="sub-management-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;margin-bottom:20px">
          <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
              <h3 style="font-size:1.1rem;font-weight:700">{{ $subPlanName }}</h3>
              <span class="sub-status {{ $subStatus === 'active' ? 'sub-active' : 'sub-paused' }}">● {{ ucfirst($subStatus) }}</span>
            </div>
            <div style="font-size:.88rem;font-weight:700;color:var(--lime-dark);margin-bottom:4px">₦{{ number_format($subPrice) }} / {{ $subCycle }}</div>
            @if($subNextDate)<div style="font-size:.82rem;color:var(--text-secondary)">Next billing: {{ $subNextDate }}</div>@endif
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            @if($subStatus === 'active')
            <button class="btn btn-outline btn-sm js-sub-action" data-sub-id="{{ $subId }}" data-action="pause">⏸ Pause</button>
            @else
            <button class="btn btn-primary btn-sm js-sub-action" data-sub-id="{{ $subId }}" data-action="resume">▶ Resume</button>
            @endif
            <button class="btn btn-ghost btn-sm js-sub-action" data-sub-id="{{ $subId }}" data-action="cancel" data-confirm="Cancel your subscription?">Cancel</button>
          </div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <a href="{{ route('quiz') }}" class="btn btn-primary btn-sm">Retake Skin Quiz</a>
          <button class="btn btn-ghost btn-sm" onclick="switchSubTab('history')">View History</button>
        </div>
      </div>

      <div id="sub-history-section">
        <div style="background:#fff;border-radius:var(--r-xl);padding:28px;border:1.5px solid var(--border)">
          <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px">Subscription History</h3>
          @if(count($subHistory))
          <table style="width:100%;border-collapse:collapse;font-size:.88rem">
            <thead><tr style="border-bottom:1px solid var(--border)">
              <th style="padding:10px 0;text-align:left;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em">Plan</th>
              <th style="padding:10px 0;text-align:left;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em">Cycle</th>
              <th style="padding:10px 0;text-align:left;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em">Amount</th>
              <th style="padding:10px 0;text-align:left;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em">Status</th>
              <th style="padding:10px 0;text-align:left;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em">Started</th>
            </tr></thead>
            <tbody>
              @foreach($subHistory as $sh)
              <tr style="border-bottom:1px solid var(--gray-100,#F3F4F6)">
                <td style="padding:12px 0;font-weight:600">{{ $sh['plan_name'] }}</td>
                <td>{{ ucfirst($sh['billing_cycle']) }}</td>
                <td>₦{{ number_format($sh['plan_price']) }}</td>
                <td><span class="sub-status {{ $sh['status'] === 'active' ? 'sub-active' : 'sub-paused' }}" style="font-size:.7rem">{{ ucfirst($sh['status']) }}</span></td>
                <td style="font-size:.8rem;color:var(--text-muted)">{{ isset($sh['started_at']) ? date('M j, Y', strtotime($sh['started_at'])) : '—' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @else
          <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:.88rem">No subscription history yet.</div>
          @endif
        </div>
      </div>

      @else
      {{-- No subscription — show plans --}}
      <div style="background:#fff;border-radius:var(--r-xl);padding:28px;border:1.5px solid var(--border);margin-bottom:24px">
        <div style="font-size:.9rem;font-weight:700;margin-bottom:20px">Choose your plan</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px" id="sub-plans-grid">
          @foreach($subscriptionPlans as $plan)
          @if($plan['is_active'])
          @php
            $tierOrder = ['starter'=>0,'glow'=>1,'radiant'=>2,'iconic'=>3];
            $locked = !empty($plan['tier_required'])
                   && isset($tierOrder[$tierKey], $tierOrder[$plan['tier_required']])
                   && $tierOrder[$tierKey] < $tierOrder[$plan['tier_required']];
          @endphp
          <div class="js-plan-card {{ $plan['is_popular'] ? 'plan-popular' : '' }}" data-popular="{{ $plan['is_popular'] ? '1' : '0' }}" style="border:2px solid var(--border);border-radius:var(--r-xl);overflow:hidden;position:relative;{{ $locked ? 'opacity:.6' : '' }}">
            @if($plan['badge'])<div class="js-plan-badge {{ $plan['is_popular'] ? 'plan-badge-popular' : 'plan-badge-default' }}" style="font-size:.62rem;font-weight:700;letter-spacing:.1em;padding:4px 12px;text-align:center">{{ $plan['badge'] }}</div>@endif
            <div style="padding:20px">
              <div style="font-size:.95rem;font-weight:700;margin-bottom:4px">{{ $plan['name'] }}</div>
              <div style="font-size:1.5rem;font-weight:700;color:var(--black);margin-bottom:2px">₦{{ number_format($plan['price']) }}</div>
              <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:12px">{{ $plan['frequency_label'] }} · {{ $plan['products_count'] }} products/box</div>
              <div style="font-size:.8rem;color:var(--text-secondary);margin-bottom:14px;line-height:1.5">{{ $plan['description'] }}</div>
              <ul style="list-style:none;padding:0;margin:0 0 16px;display:flex;flex-direction:column;gap:5px">
                @foreach($plan['features'] as $feat)
                <li style="font-size:.78rem;display:flex;align-items:center;gap:7px;color:var(--black)"><span style="color:var(--lime-dark);font-weight:700">✓</span> {{ $feat }}</li>
                @endforeach
              </ul>
              @if($locked)
              <div style="text-align:center;font-size:.75rem;color:var(--text-muted);padding:10px;background:var(--gray-100,#F3F4F6);border-radius:var(--r-md)">🔒 Requires {{ ucfirst($plan['tier_required']) }} tier</div>
              @else
              <button class="btn btn-primary js-subscribe-btn" style="width:100%;justify-content:center" data-plan-id="{{ $plan['id'] }}" data-plan-name="{{ $plan['name'] }}" data-plan-price="{{ $plan['price'] }}" data-plan-cycle="{{ $plan['billing_cycle'] }}">Subscribe Now →</button>
              @endif
            </div>
          </div>
          @endif
          @endforeach
        </div>
      </div>
      @endif
    </div>

    <!-- MEMBERSHIP PANEL -->
    <div class="dashboard-panel" id="panel-membership">
      <div class="dash-welcome">
        <h1 class="dash-greeting">My Membership 🪪</h1>
        <p class="dash-subtext">Your membership ID, photo verification, contact info, and delivery details.</p>
      </div>
      <div class="mem-card">
        <div class="mem-card-logo">KOMIN<span>H</span>OO BEAUTY</div>
        <div class="mem-card-id-label">Membership ID</div>
        <div class="mem-card-id" id="display-member-id">{{ $memberIdCode }}</div>
        <div class="mem-card-footer">
          <div>
            <div class="mem-card-name">{{ $user['name'] ?? 'Adaeze Okonkwo' }}</div>
            <div class="mem-card-tier">{{ $tierLabel }}</div>
          </div>
          <div class="mem-card-meta">
            <div class="mem-card-since-label">Member since</div>
            <div class="mem-card-since">{{ $memberSince }}</div>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:24px;margin-bottom:24px">
        <div class="mem-section">
          <div class="mem-section-title">Photo Verification</div>
          <div class="selfie-zone">
            <div class="selfie-ring" id="selfie-ring" onclick="document.getElementById('selfie-input').click()" title="Click to upload selfie">
              <span class="selfie-placeholder" id="selfie-placeholder">🧑</span>
              <img id="selfie-preview" src="" alt="Your photo" style="display:none">
            </div>
            <span class="selfie-status selfie-none" id="selfie-status-badge">✗ Not uploaded</span>
            <p class="selfie-instructions">Take or upload a clear, well-lit selfie so we can verify your identity for secure deliveries.</p>
            <input type="file" id="selfie-input" accept="image/*" capture="user" style="display:none" onchange="handleSelfieUpload(this)">
            <button class="btn btn-dark btn-sm" onclick="document.getElementById('selfie-input').click()">📸 Upload Selfie</button>
            <div style="font-size:.72rem;color:var(--text-muted);text-align:center;line-height:1.5"><strong>Tips:</strong> Face the camera directly,<br>good lighting, no sunglasses.</div>
          </div>
        </div>
        <div class="mem-section">
          <div class="mem-section-title">Contact Information</div>
          <div style="display:flex;flex-direction:column;gap:16px">
            <div class="mem-field"><label>Primary Phone</label><input class="input" type="tel" placeholder="+234 800 000 0000" value="{{ $user['phone'] ?? '' }}"></div>
            <div class="mem-field"><label>WhatsApp Number <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label><input class="input" type="tel" placeholder="+234 800 000 0000" value="{{ $user['phone'] ?? '' }}"></div>
            <div class="mem-field"><label>Alternative Phone <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label><input class="input" type="tel" placeholder="+234 800 000 0000"></div>
            <div class="mem-field"><label>Email Address</label><input class="input" type="email" value="{{ $user['email'] ?? '' }}"></div>
            <button class="btn btn-primary" onclick="showToast('✓','Contact info saved!')">Save Contact Info</button>
          </div>
        </div>
      </div>

      <div class="mem-section">
        <div class="mem-section-title">Delivery Details</div>
        <div style="display:flex;flex-direction:column;gap:16px">
          <div class="mem-field-row">
            <div class="mem-field"><label>Recipient Full Name</label><input class="input" placeholder="Full name on delivery" value="{{ $user['name'] ?? '' }}"></div>
            <div class="mem-field"><label>Delivery Phone</label><input class="input" type="tel" placeholder="+234 800 000 0000" value="{{ $user['phone'] ?? '' }}"></div>
          </div>
          <div class="mem-field"><label>Address Line 1</label><input class="input" placeholder="House number, street name" value="{{ $user['address_line1'] ?? '' }}"></div>
          <div class="mem-field"><label>Address Line 2 <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label><input class="input" placeholder="Apartment, suite, unit, estate" value="{{ $user['address_line2'] ?? '' }}"></div>
          <div class="mem-field-row">
            <div class="mem-field"><label>City</label><input class="input" placeholder="City" value="{{ $user['city'] ?? '' }}"></div>
            <div class="mem-field"><label>State</label><input class="input" placeholder="State" value="{{ $user['state'] ?? '' }}"></div>
          </div>
          <div class="mem-field-row">
            <div class="mem-field"><label>Postal / ZIP Code <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label><input class="input" placeholder="e.g. 106104"></div>
            <div class="mem-field"><label>Nearest Landmark</label><input class="input" placeholder="e.g. Opposite Shoprite, near…"></div>
          </div>
          <div class="mem-field"><label>Delivery Instructions <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label><textarea class="input" rows="2" placeholder="Any special instructions for the courier — gate code, preferred drop-off, etc." style="resize:vertical;font-family:inherit"></textarea></div>
          <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
            <button class="btn btn-primary" onclick="showToast('✓','Delivery details saved!')">Save Delivery Details</button>
            <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;font-weight:600;cursor:pointer"><input type="checkbox" checked style="width:16px;height:16px;accent-color:var(--lime)">Set as default delivery address</label>
          </div>
        </div>
      </div>
    </div>

    <!-- NOTIFICATIONS PANEL -->
    <div class="dashboard-panel" id="panel-notif">
      <div class="dash-welcome">
        <h1 class="dash-greeting">Notifications 🔔</h1>
        <p class="dash-subtext" id="notif-subtext">
          @if($unreadCount > 0){{ $unreadCount }} unread notification{{ $unreadCount !== 1 ? 's' : '' }} — stay up to date with your Kominhoo experience.@else All caught up! No unread notifications.@endif
        </p>
      </div>
      <div style="background:#fff;border-radius:var(--r-xl);overflow:hidden;border:1.5px solid var(--border)">
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
          <span style="font-weight:700">All Notifications</span>
          <div style="display:flex;gap:8px">
            <button class="btn btn-ghost btn-sm" onclick="markAllNotifsRead()">Mark all read</button>
            <button class="btn btn-ghost btn-sm" onclick="loadNotifications()">Refresh</button>
          </div>
        </div>
        <div id="notif-list">
          @forelse($notifItems as $notif)
          @php
            $notifIcons = ['tier_upgrade'=>'🏆','order'=>'📦','subscription'=>'📬','referral'=>'👥','promotion'=>'🎁','system'=>'📢','gift'=>'🎁'];
            $notifIcon  = $notifIcons[$notif['type'] ?? 'system'] ?? '🔔';
            $isRead     = $notif['is_read'] ?? false;
            $timeAgo    = \Carbon\Carbon::parse($notif['created_at'])->diffForHumans();
          @endphp
          <div class="notification-item {{ $isRead ? 'read' : '' }}" data-id="{{ $notif['id'] }}" onclick="readNotification(this)">
            <div class="notification-dot"></div>
            <div style="flex:1">
              <div style="font-weight:700;font-size:.92rem;margin-bottom:4px">{{ $notifIcon }} {{ $notif['title'] }}</div>
              <div style="font-size:.82rem;color:var(--text-secondary)">{{ $notif['message'] }}</div>
              <div style="font-size:.75rem;color:var(--text-muted);margin-top:6px">{{ $timeAgo }}</div>
            </div>
            <button class="btn btn-ghost btn-sm" style="flex-shrink:0;font-size:.7rem;padding:3px 8px" onclick="event.stopPropagation();deleteNotification(this.closest('.notification-item'))">✕</button>
          </div>
          @empty
          <div id="notif-empty" style="text-align:center;padding:40px 20px;color:var(--text-muted)">
            <div style="font-size:2rem;margin-bottom:8px">🔔</div>
            <div style="font-size:.88rem">No notifications yet. We'll let you know when something happens!</div>
          </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- COMMUNITY PANEL -->
    <div class="dashboard-panel" id="panel-community">
      <div class="dash-welcome">
        <h1 class="dash-greeting">My Community ✨</h1>
        <p class="dash-subtext">Share your glow-up, write reviews, tag products, and inspire 50K+ skin lovers.</p>
      </div>

      <div class="comm-hero">
        <div class="comm-hero-inner">
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.35);margin-bottom:8px">Your Community Profile</div>
            <div style="font-family:var(--font-display);font-size:1.5rem;color:#fff;margin-bottom:4px">{{ $user['name'] ?? '' }}</div>
            <div style="font-size:.8rem;color:rgba(255,255,255,.45)">{{ $communityHandle }}{{ $communitySkType ? ' · ' . $communitySkType . ' Skin' : '' }}{{ $communityVerified ? ' · ✓ Verified Member' : '' }}</div>
            @php
              $communityTags = array_filter([
                $communitySkType ? '#' . str_replace(' ', '', $communitySkType) . 'Skin' : null,
                '#KominhooResults',
                '#SkincareJourney',
              ]);
            @endphp
            <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap">
              @foreach($communityTags as $tag)
              <span style="background:rgba(212,217,148,.12);color:var(--lime);padding:4px 12px;border-radius:var(--r-pill);font-size:.72rem;font-weight:700">{{ $tag }}</span>
              @endforeach
            </div>
          </div>
          <div class="comm-hero-stats">
            <div class="comm-hero-stat"><div class="comm-hero-stat-num" id="ch-posts">—</div><div class="comm-hero-stat-label">Posts</div></div>
            <div class="comm-hero-stat"><div class="comm-hero-stat-num" id="ch-likes">—</div><div class="comm-hero-stat-label">Likes</div></div>
            <div class="comm-hero-stat"><div class="comm-hero-stat-num" id="ch-reviews">—</div><div class="comm-hero-stat-label">Reviews</div></div>
            <div class="comm-hero-stat"><div class="comm-hero-stat-num" id="ch-pts">—</div><div class="comm-hero-stat-label">Pts Earned</div></div>
          </div>
        </div>
      </div>

      <div class="comm-composer">
        <div class="comm-composer-tabs">
          <button class="cc-tab active" data-cc="cc-photo" onclick="switchCC('cc-photo',this)">📸 Photo / Selfie</button>
          <button class="cc-tab" data-cc="cc-ba" onclick="switchCC('cc-ba',this)">✨ Before & After</button>
          <button class="cc-tab" data-cc="cc-review" onclick="switchCC('cc-review',this)">⭐ Product Review</button>
          <button class="cc-tab" data-cc="cc-routine" onclick="switchCC('cc-routine',this)">🧴 My Routine</button>
        </div>
        <div class="comm-composer-body">

          <div class="cc-panel active" id="cc-photo">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
              <div>
                <div class="photo-drop-zone" id="photo-drop" onclick="document.getElementById('photo-file-input').click()">
                  <input type="file" id="photo-file-input" accept="image/*" multiple onchange="handlePhotoFiles(this)">
                  <div class="pdz-icon">🖼️</div>
                  <div class="pdz-title">Drop photos here or click to browse</div>
                  <div class="pdz-sub">Up to 6 photos · JPG, PNG, HEIC</div>
                </div>
                <div class="photo-preview-strip" id="photo-preview-strip"></div>
                <div style="display:flex;gap:10px;margin-top:14px">
                  <button class="btn btn-outline btn-sm" style="flex:1" onclick="document.getElementById('photo-file-input').click()">🖼️ Add Photos</button>
                  <button class="btn btn-dark btn-sm" style="flex:1" onclick="document.getElementById('selfie-cam-input').click()">🤳 Take Selfie</button>
                  <input type="file" id="selfie-cam-input" accept="image/*" capture="user" style="display:none" onchange="handlePhotoFiles(this)">
                </div>
              </div>
              <div style="display:flex;flex-direction:column;gap:16px">
                <div>
                  <label style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);display:block;margin-bottom:8px">Caption</label>
                  <textarea class="input" rows="3" placeholder="Share your skin story — what changed, what worked, how you feel ✨" id="photo-caption" style="resize:none;font-family:inherit;font-size:.88rem"></textarea>
                </div>
                <div>
                  <label style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);display:block;margin-bottom:8px">Tag Products Used</label>
                  <div class="prod-tag-input-wrap">
                    <input class="input" placeholder="Search products…" id="prod-tag-search" oninput="filterProdTags(this.value)" onfocus="document.getElementById('prod-tag-dropdown').classList.add('open')" onblur="setTimeout(()=>document.getElementById('prod-tag-dropdown').classList.remove('open'),200)" autocomplete="off">
                    <div class="prod-tag-dropdown" id="prod-tag-dropdown"></div>
                  </div>
                  <div class="tagged-products-list" id="tagged-products"></div>
                </div>
                <div>
                  <label style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);display:block;margin-bottom:8px">Hashtags</label>
                  <div style="display:flex;gap:8px">
                    <input class="input" placeholder="#YourHashtag" id="htag-input" onkeydown="addHashtag(event)" style="flex:1">
                    <button class="btn btn-outline btn-sm" onclick="addHashtag({key:'Enter',target:document.getElementById('htag-input')})">Add</button>
                  </div>
                  <div class="hashtag-cloud" id="hashtag-cloud">
                    <span class="htag-chip">#GlassSkin <button onclick="removeHashtag(this.parentElement)">&times;</button></span>
                    <span class="htag-chip">#KominhooResults <button onclick="removeHashtag(this.parentElement)">&times;</button></span>
                  </div>
                </div>
                <div>
                  <label style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);display:block;margin-bottom:8px">Skin Type</label>
                  <select class="input" id="photo-skin-type" style="font-family:inherit">
                    @foreach(['Combination','Oily','Dry','Sensitive','Normal'] as $st)
                    <option {{ ($communitySkType === $st) ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                  </select>
                </div>
                <button class="btn btn-primary" onclick="submitCommunityPost('photo')" style="width:100%;justify-content:center">✨ Share to Community → +{{ $pointEventsConfig['community_post']['points'] ?? 30 }} pts</button>
              </div>
            </div>
          </div>

          <div class="cc-panel" id="cc-ba">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px">
              <div>
                <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Before Photo</label>
                <div class="photo-drop-zone" style="padding:24px 16px" onclick="document.getElementById('ba-before-input').click()">
                  <input type="file" id="ba-before-input" accept="image/*" onchange="handleBAPreview(this,'ba-before-preview')">
                  <img id="ba-before-preview" src="" style="display:none;width:100%;border-radius:var(--r-md);margin-bottom:8px">
                  <div class="pdz-icon" id="ba-before-icon">📷</div>
                  <div class="pdz-sub">Your before photo</div>
                </div>
              </div>
              <div>
                <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">After Photo</label>
                <div class="photo-drop-zone" style="padding:24px 16px;border-color:var(--lime)" onclick="document.getElementById('ba-after-input').click()">
                  <input type="file" id="ba-after-input" accept="image/*" onchange="handleBAPreview(this,'ba-after-preview')">
                  <img id="ba-after-preview" src="" style="display:none;width:100%;border-radius:var(--r-md);margin-bottom:8px">
                  <div class="pdz-icon" id="ba-after-icon">✨</div>
                  <div class="pdz-sub">Your glow-up result</div>
                </div>
              </div>
              <div style="display:flex;flex-direction:column;gap:14px">
                <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Time Period</label><select class="input" id="ba-period-select" style="font-family:inherit"><option>2 weeks</option><option>1 month</option><option selected>3 months</option><option>6 months</option><option>1 year</option></select></div>
                <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Main Concern Fixed</label><select class="input" style="font-family:inherit"><option>Acne / Breakouts</option><option>Hyperpigmentation</option><option>Dehydration</option><option>Oiliness</option><option>Uneven Tone</option></select></div>
                <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Your Story</label><textarea class="input" id="ba-caption" rows="4" placeholder="Tell the community what you used and what changed. Be honest — that's what makes it powerful." style="resize:none;font-family:inherit;font-size:.85rem"></textarea></div>
                <button class="btn btn-primary" onclick="submitCommunityPost('before_after')" style="width:100%;justify-content:center">📸 Post Transformation → +{{ $pointEventsConfig['before_after']['points'] ?? 50 }} pts</button>
              </div>
            </div>
          </div>

          <div class="cc-panel" id="cc-review">
            <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px">
              <div style="display:flex;flex-direction:column;gap:16px">
                <div>
                  <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Product Being Reviewed</label>
                  <div class="prod-tag-input-wrap">
                    <input class="input" placeholder="Search your purchased products…" id="rev-prod-search" oninput="filterRevProds(this.value)" onfocus="document.getElementById('rev-prod-dropdown').classList.add('open')" onblur="setTimeout(()=>document.getElementById('rev-prod-dropdown').classList.remove('open'),200)">
                    <div class="prod-tag-dropdown" id="rev-prod-dropdown"></div>
                  </div>
                  <div id="rev-selected-product" style="margin-top:10px"></div>
                </div>
                <div>
                  <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:10px">Your Rating</label>
                  <div class="star-rating" id="star-rating" onmouseleave="resetStarHover()">
                    <span class="star active" data-val="1" onmouseover="hoverStars(1)" onclick="setStars(1)">★</span>
                    <span class="star active" data-val="2" onmouseover="hoverStars(2)" onclick="setStars(2)">★</span>
                    <span class="star active" data-val="3" onmouseover="hoverStars(3)" onclick="setStars(3)">★</span>
                    <span class="star active" data-val="4" onmouseover="hoverStars(4)" onclick="setStars(4)">★</span>
                    <span class="star" data-val="5" onmouseover="hoverStars(5)" onclick="setStars(5)">★</span>
                  </div>
                  <div id="star-label" style="font-size:.78rem;color:var(--text-muted);margin-top:6px">4 out of 5 — Great!</div>
                </div>
                <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Your Review</label><textarea class="input" rows="5" id="review-text" placeholder="How long have you used it? What changed? Would you recommend it to someone with your skin type?" style="resize:none;font-family:inherit;font-size:.88rem"></textarea></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                  <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Would Repurchase?</label><select class="input" style="font-family:inherit"><option>Yes, absolutely</option><option>Probably</option><option>Not sure</option><option>No</option></select></div>
                  <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">How Long Used</label><select class="input" style="font-family:inherit"><option>1–2 weeks</option><option>1 month</option><option>3 months</option><option>6+ months</option></select></div>
                </div>
              </div>
              <div style="display:flex;flex-direction:column;gap:16px">
                <div>
                  <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Review Photo (optional)</label>
                  <div class="photo-drop-zone" style="padding:28px 16px" onclick="document.getElementById('rev-photo-input').click()">
                    <input type="file" id="rev-photo-input" accept="image/*" onchange="handleRevPhotoPreview(this)">
                    <img id="rev-photo-preview" src="" style="display:none;width:100%;border-radius:var(--r-md);margin-bottom:8px;max-height:160px;object-fit:cover">
                    <div id="rev-photo-placeholder"><div class="pdz-icon">📸</div><div class="pdz-sub">Add a photo with this product</div></div>
                  </div>
                </div>
                <div style="background:var(--lime-pale);border-radius:var(--r-md);padding:16px">
                  <div style="font-size:.8rem;font-weight:700;margin-bottom:10px">Rate these aspects:</div>
                  <div style="display:flex;flex-direction:column;gap:8px">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem"><span>Effectiveness</span><div style="display:flex;gap:4px" id="asp-effect" data-val="4" onmouseleave="this.querySelectorAll('.asp-star').forEach((s,i)=>s.classList.toggle('active',i<parseInt(this.dataset.val)))"></div></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem"><span>Texture / Feel</span><div style="display:flex;gap:4px" id="asp-texture" data-val="5" onmouseleave="this.querySelectorAll('.asp-star').forEach((s,i)=>s.classList.toggle('active',i<parseInt(this.dataset.val)))"></div></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem"><span>Value for Money</span><div style="display:flex;gap:4px" id="asp-value" data-val="3" onmouseleave="this.querySelectorAll('.asp-star').forEach((s,i)=>s.classList.toggle('active',i<parseInt(this.dataset.val)))"></div></div>
                  </div>
                </div>
                <button class="btn btn-primary" onclick="submitCommunityPost('review')" style="width:100%;justify-content:center">⭐ Submit Review → +{{ $pointEventsConfig['review']['points'] ?? 50 }} pts</button>
                <div style="font-size:.75rem;color:var(--text-muted);text-align:center;line-height:1.6">Reviews are verified against your purchase history and visible to 50K+ community members.</div>
              </div>
            </div>
          </div>

          <div class="cc-panel" id="cc-routine">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
              <div style="display:flex;flex-direction:column;gap:16px">
                <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Routine Title</label><input class="input" id="routine-title-input" placeholder="e.g. My Glass Skin AM Routine" value="{{ $communitySkType ? 'My ' . $communitySkType . ' Skin AM Routine' : 'My AM Routine' }}"></div>
                <div>
                  <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Routine Type</label>
                  <div style="display:flex;gap:8px">
                    <button class="btn btn-dark btn-sm" id="rt-am" onclick="setRoutineType('AM')">☀️ AM</button>
                    <button class="btn btn-outline btn-sm" id="rt-pm" onclick="setRoutineType('PM')">🌙 PM</button>
                    <button class="btn btn-outline btn-sm" id="rt-weekly" onclick="setRoutineType('Weekly')">📅 Weekly</button>
                  </div>
                </div>
                <div>
                  <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Steps (tag products in order)</label>
                  <div id="routine-steps" style="display:flex;flex-direction:column;gap:8px"></div>
                  <button class="btn btn-outline btn-sm" style="margin-top:10px;width:100%" onclick="addRoutineStep()">+ Add Step</button>
                </div>
                <div><label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">How has this routine helped?</label><textarea class="input" id="routine-desc" rows="3" placeholder="Describe your results and how long you've been following this routine." style="resize:none;font-family:inherit;font-size:.88rem"></textarea></div>
              </div>
              <div style="display:flex;flex-direction:column;gap:16px">
                <div>
                  <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:8px">Routine Photo / Flat Lay</label>
                  <div class="photo-drop-zone" style="padding:32px 16px" onclick="document.getElementById('routine-photo-input').click()">
                    <input type="file" id="routine-photo-input" accept="image/*" onchange="handleRoutinePhoto(this)">
                    <img id="routine-photo-preview" src="" style="display:none;width:100%;border-radius:var(--r-md);margin-bottom:8px;max-height:200px;object-fit:cover">
                    <div id="routine-photo-ph"><div class="pdz-icon">🧴</div><div class="pdz-sub">Flat lay of your products</div></div>
                  </div>
                </div>
                <div style="background:var(--lime-pale);border-radius:var(--r-md);padding:16px">
                  <div style="font-size:.82rem;font-weight:700;margin-bottom:8px">Quick skin check-ins</div>
                  <div style="display:flex;flex-direction:column;gap:8px;font-size:.82rem">
                    <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" checked style="accent-color:var(--lime);width:15px;height:15px">Reduced breakouts</label>
                    <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" checked style="accent-color:var(--lime);width:15px;height:15px">Improved hydration</label>
                    <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" style="accent-color:var(--lime);width:15px;height:15px">Faded dark spots</label>
                    <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" style="accent-color:var(--lime);width:15px;height:15px">Better texture</label>
                  </div>
                </div>
                <button class="btn btn-primary" onclick="submitCommunityPost('routine')" style="width:100%;justify-content:center">🧴 Share My Routine → +{{ $pointEventsConfig['routine_post']['points'] ?? 20 }} pts</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div style="background:#fff;border-radius:var(--r-xl);border:1.5px solid var(--border);padding:24px;margin-bottom:24px">
        <div class="my-posts-header">
          <h3 style="font-size:1rem;font-weight:700">My Posts</h3>
          <div style="display:flex;gap:10px;align-items:center">
            <div class="comm-view-toggle">
              <button class="cvt-btn active" id="view-grid-btn" onclick="setPostsView('grid')">▦ Grid</button>
              <button class="cvt-btn" id="view-feed-btn" onclick="setPostsView('feed')">☰ Feed</button>
            </div>
            <a href="{{ route('community') }}" class="btn btn-outline btn-sm">View Full Page →</a>
          </div>
        </div>
        <div id="my-posts-grid-view">
          <div class="my-posts-grid" id="my-posts-grid"></div>
          <div style="text-align:center;margin-top:18px">
            <button id="my-posts-grid-more" class="btn btn-outline btn-sm" style="display:none" onclick="loadMoreMyPostsGrid()">Load More Posts</button>
          </div>
        </div>
        <div id="my-posts-feed-view" style="display:none">
          <div class="comm-feed" id="my-posts-feed"></div>
          <div style="text-align:center;margin-top:18px">
            <button id="my-posts-feed-more" class="btn btn-outline btn-sm" style="display:none" onclick="loadMoreMyPostsFeed()">Load More Posts</button>
          </div>
        </div>
      </div>

      <div class="comm-discover">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
          <div><h3 style="font-size:1rem;font-weight:700;margin-bottom:2px">Trending in the Community</h3><div style="font-size:.78rem;color:var(--text-muted)">Skin stories inspiring your next routine</div></div>
          <a href="{{ route('community') }}" class="btn btn-dark btn-sm">Explore All →</a>
        </div>
        <div class="comm-discover-scroll" id="discover-scroll"></div>
      </div>

      <div class="comm-goto-banner">
        <div>
          <div class="cgb-title">You belong in the full community ✨</div>
          <div class="cgb-sub">50K+ skin lovers sharing real results, before & afters, and honest reviews.</div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <a href="{{ route('community') }}" class="btn btn-dark btn-lg">Explore Community →</a>
          <button class="btn btn-outline btn-sm" onclick="switchDashPanel('panel-community');switchCC('cc-photo',document.querySelector('[data-cc=cc-photo]'))">+ Create Post</button>
        </div>
      </div>
    </div>

    <!-- VOUCHERS PANEL -->
    <div class="dashboard-panel" id="panel-vouchers">
      <div class="dash-welcome">
        <h1 class="dash-greeting">Vouchers & Coupons 🏷️</h1>
        <p class="dash-subtext" id="vouchers-subtext">Loading available promotions…</p>
      </div>

      {{-- Promo code entry --}}
      <div style="background:linear-gradient(135deg,rgba(212,217,148,.12),rgba(94,102,35,.06));border:1.5px solid rgba(94,102,35,.35);border-radius:var(--r-xl);padding:22px 28px;margin-bottom:28px">
        <div style="font-size:.9rem;font-weight:700;margin-bottom:4px">🎁 Have a promo code?</div>
        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:14px">Enter your code — it will be saved and auto-applied at checkout.</div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
          <input class="input" id="dash-coupon-input"
            placeholder="Enter promo code"
            style="flex:1;max-width:300px;text-transform:uppercase;font-weight:700;letter-spacing:.06em;font-family:'DM Sans',system-ui,sans-serif;font-size:.95rem"
            onkeydown="if(event.key==='Enter')dashApplyCoupon()">
          <button class="btn btn-dark btn-sm" onclick="dashApplyCoupon()" id="dash-coupon-btn">Apply Code</button>
        </div>
        <div id="dash-coupon-msg" style="font-size:.8rem;margin-top:10px;display:none;font-weight:500"></div>
      </div>

      {{-- Live voucher list (fetched from API) --}}
      <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text-muted);margin-bottom:14px">Available Vouchers</div>
      <div id="vouchers-grid">
        <div style="text-align:center;padding:56px 20px;color:var(--text-muted)">
          <div style="font-size:2rem;margin-bottom:10px">⏳</div>
          <div style="font-size:.85rem">Loading vouchers…</div>
        </div>
      </div>
    </div>

    <!-- GIFT CARDS PANEL -->
    <div class="dashboard-panel" id="panel-giftcards">
      <div class="dash-welcome">
        <h1 class="dash-greeting">Gift Cards 🎁</h1>
        <p class="dash-subtext">Buy a gift for someone special, or manage your gift cards.</p>
      </div>
      <div style="display:flex;gap:10px;margin-bottom:28px;flex-wrap:wrap">
        <button class="btn btn-dark btn-sm" id="gct-buy" onclick="switchGcTab('buy')">Buy a Gift Card</button>
        <button class="btn btn-ghost btn-sm" id="gct-received" onclick="switchGcTab('received')">Received</button>
        <button class="btn btn-ghost btn-sm" id="gct-sent" onclick="switchGcTab('sent')">Sent by Me</button>
      </div>

      {{-- Buy tab --}}
      <div id="gc-tab-buy">
        {{-- Premium hero card --}}
        <div style="border-radius:24px;overflow:hidden;margin-bottom:16px;box-shadow:0 8px 40px rgba(0,0,0,.35)">
          <div style="background:linear-gradient(148deg,#060a18 0%,#0b1430 50%,#04060f 100%);padding:40px 32px;color:#fff;position:relative;overflow:hidden;min-height:180px">
            {{-- Ambient glows --}}
            <div style="position:absolute;top:-60px;right:-40px;width:260px;height:260px;background:radial-gradient(circle,rgba(120,130,255,.22) 0%,transparent 65%);pointer-events:none"></div>
            <div style="position:absolute;bottom:-50px;left:-20px;width:180px;height:180px;background:radial-gradient(circle,rgba(200,230,52,.07) 0%,transparent 65%);pointer-events:none"></div>
            {{-- Subtle dot grid --}}
            <div style="position:absolute;inset:0;background:radial-gradient(circle,rgba(255,255,255,.16) 0.6px,transparent 0.6px);background-size:24px 24px;opacity:.4;pointer-events:none;-webkit-mask-image:radial-gradient(ellipse 90% 90% at 80% 30%,black 0%,transparent 100%);mask-image:radial-gradient(ellipse 90% 90% at 80% 30%,black 0%,transparent 100%)"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent)"></div>

            {{-- Floating premium mini card --}}
            <div style="position:absolute;top:22px;right:26px;width:116px;aspect-ratio:1.586;border-radius:12px;background:linear-gradient(148deg,#FAF7EF,#E5D8BF);border:1.5px solid rgba(255,255,255,.85);overflow:hidden;transform:rotate(5deg);box-shadow:0 10px 28px rgba(0,0,0,.35),0 0 0 1px rgba(200,230,52,.1)">
              <div style="position:absolute;top:-18px;right:-18px;width:62px;height:62px;border-radius:50%;background:radial-gradient(circle,rgba(200,230,52,.45) 0%,transparent 65%)"></div>
              <div style="position:absolute;top:0;left:0;right:0;height:50%;background:linear-gradient(140deg,rgba(255,255,255,.38) 0%,transparent 100%);border-radius:12px 12px 0 0"></div>
              <div style="position:absolute;inset:0;padding:10px 12px;display:flex;flex-direction:column;justify-content:space-between;color:#1a1208;z-index:2">
                <div style="font-family:var(--font-display);font-size:.52rem;letter-spacing:.13em;opacity:.38">KOMINHOO.</div>
                <div style="font-family:var(--font-display);font-size:.95rem;line-height:1">₦25,000</div>
                <div style="font-size:.42rem;font-weight:700;letter-spacing:.13em;opacity:.28">GIFT CARD</div>
              </div>
            </div>

            <div style="position:relative;z-index:1;max-width:300px">
              <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.28);margin-bottom:16px">KOMINHOO BEAUTY</div>
              <div style="font-family:var(--font-display);font-size:1.85rem;color:#fff;line-height:1.15;margin-bottom:10px">Give someone the<br><em style="font-style:normal;color:#C8E634">gift of glow</em></div>
              <p style="font-size:.82rem;color:rgba(255,255,255,.45);margin-bottom:24px;line-height:1.65">₦5,000 – ₦50,000 · Instant delivery · Valid 1 year</p>
              <a href="{{ route('gift-cards.index') }}" style="display:inline-flex;align-items:center;gap:8px;background:#C8E634;color:#0a0a0a;border-radius:12px;padding:12px 22px;font-size:.85rem;font-weight:700;text-decoration:none;transition:transform .15s,box-shadow .15s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(200,230,52,.35)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                🎁 Buy a Gift Card →
              </a>
            </div>
          </div>
        </div>

        {{-- Redeem strip --}}
        <div style="background:#fff;border-radius:18px;border:1.5px solid var(--border);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;box-shadow:0 1px 4px rgba(0,0,0,.04)">
          <div>
            <div style="font-size:.88rem;font-weight:700;margin-bottom:3px">Have a gift card to redeem?</div>
            <div style="font-size:.78rem;color:var(--text-muted)">Enter your code at checkout to apply it to your order.</div>
          </div>
          <a href="{{ route('checkout') }}" style="background:var(--black);color:#fff;border-radius:10px;padding:10px 18px;font-size:.82rem;font-weight:700;text-decoration:none;white-space:nowrap;flex-shrink:0">Go to Checkout →</a>
        </div>
      </div>

      {{-- Received tab --}}
      <div id="gc-tab-received" style="display:none">
        <div id="gc-received-list">
          <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
            <div style="width:56px;height:56px;background:var(--cream);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin:0 auto 14px">🎁</div>
            <p>Loading your gift cards…</p>
          </div>
        </div>
        <div style="margin-top:14px;background:linear-gradient(135deg,#f5ffcc,#edfaaa);border:1.5px solid #c5d848;border-radius:14px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap">
          <div>
            <div style="font-size:.85rem;font-weight:700;margin-bottom:2px">Ready to redeem?</div>
            <div style="font-size:.78rem;color:#5a6a0a">Apply your code directly at checkout.</div>
          </div>
          <a href="{{ route('checkout') }}" style="background:#0A0A0A;color:#fff;border-radius:10px;padding:9px 16px;font-size:.8rem;font-weight:700;text-decoration:none;white-space:nowrap">Checkout →</a>
        </div>
      </div>

      {{-- Sent tab --}}
      <div id="gc-tab-sent" style="display:none">
        <div id="gc-sent-list">
          <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
            <div style="font-size:2.5rem;margin-bottom:12px">⏳</div>
            <p>Loading gift cards you've sent…</p>
          </div>
        </div>
        <div style="margin-top:16px;text-align:center">
          <a href="{{ route('gift-cards.index') }}" class="btn btn-outline btn-sm">🎁 Send Another Gift Card →</a>
        </div>
      </div>
    </div>

    <!-- REFERRAL PANEL -->
    <div class="dashboard-panel" id="panel-referral">
      <div class="dash-welcome">
        <h1 class="dash-greeting">Referral Program 👥</h1>
        <p class="dash-subtext">Earn {{ number_format($refRewardPts) }} loyalty points every time a friend signs up and places their first order using your code.</p>
      </div>
      <div class="ref-hero">
        <div style="position:relative;z-index:1">
          <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(10,10,10,.5);margin-bottom:10px">Your Referral Code</div>
          <div style="font-family:var(--font-display);font-size:2.2rem;color:var(--black);letter-spacing:.1em" id="ref-code-display">{{ $refCode ?: '—' }}</div>
          <div class="ref-link-box">
            <span class="ref-link-text" id="ref-link-text">{{ $refLink }}</span>
            <button class="btn btn-dark btn-sm" onclick="copyRefLink()">📋 Copy</button>
            <button class="btn btn-outline btn-sm" onclick="shareRefLink()">↗ Share</button>
          </div>
          <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap">
            <button class="btn btn-dark btn-sm" id="ref-whatsapp-btn">Share on WhatsApp</button>
            <button class="btn btn-outline btn-sm" id="ref-email-btn">Send via Email</button>
          </div>
        </div>
      </div>
      <div class="ref-stat-grid" style="margin-bottom:28px">
        <div class="ref-stat-box"><div class="ref-stat-num">{{ $refTotal }}</div><div class="ref-stat-lbl">Friends Referred</div></div>
        <div class="ref-stat-box"><div class="ref-stat-num" style="color:var(--lime-dark,#97b01e)">{{ $refCompleted }}</div><div class="ref-stat-lbl">Completed</div></div>
        <div class="ref-stat-box"><div class="ref-stat-num">{{ number_format($refTotalPoints) }}</div><div class="ref-stat-lbl">Points Earned</div></div>
      </div>
      <div style="background:#fff;border-radius:var(--r-xl);padding:28px 32px;border:1.5px solid var(--border);margin-bottom:24px">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px">Referred Friends</h3>
        @forelse($refFriends as $ref)
        @php
          $rName = $ref['referred_name'] ?? 'Unknown';
          $rInitials = strtoupper(substr($rName,0,1) . (strpos($rName,' ')!==false ? substr($rName,strpos($rName,' ')+1,1) : ''));
          $rColors = ['#D4D994','#893941','#F59E0B','#1C1416','#6366F1'];
          $rColor = $rColors[crc32($rName) % count($rColors)];
          $rLight = in_array($rColor, ['#D4D994','#F59E0B']);
          $rJoined = isset($ref['referred_joined']) ? date('F Y', strtotime($ref['referred_joined'])) : '';
        @endphp
        <div class="ref-friend-item">
          <div class="ref-friend-av js-ref-av" data-bg="{{ $rColor }}" data-light="{{ $rLight ? '1' : '0' }}">{{ $rInitials }}</div>
          <div>
            <div style="font-size:.9rem;font-weight:700">{{ $rName }}</div>
            <div style="font-size:.76rem;color:var(--text-muted)">
              {{ $rJoined ? "Joined {$rJoined}" : 'Recently joined' }} ·
              {{ $ref['status'] === 'completed' ? 'First order placed' : 'Awaiting first order' }}
            </div>
          </div>
          <span class="ref-friend-status {{ $ref['status'] === 'completed' ? 'ref-status-joined' : 'ref-status-pending' }}">
            {{ $ref['status'] === 'completed' ? '✓ Joined' : '⏳ Pending' }}
          </span>
        </div>
        @empty
        <div style="text-align:center;padding:28px 0;color:var(--text-muted)">
          <div style="font-size:2rem;margin-bottom:8px">👥</div>
          <div style="font-size:.88rem">No referrals yet. Share your link to start earning!</div>
        </div>
        @endforelse
      </div>
      <div style="background:var(--black);border-radius:var(--r-xl);padding:32px;color:#fff">
        <h3 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:24px">How it works</h3>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
          <div style="text-align:center"><div style="font-size:2rem;margin-bottom:10px">📤</div><div style="font-size:.88rem;font-weight:700;margin-bottom:4px">1. Share your link</div><div style="font-size:.78rem;color:rgba(255,255,255,.45)">Send your personal code to friends via WhatsApp, IG, or email</div></div>
          <div style="text-align:center"><div style="font-size:2rem;margin-bottom:10px">🛍️</div><div style="font-size:.88rem;font-weight:700;margin-bottom:4px">2. They shop</div><div style="font-size:.78rem;color:rgba(255,255,255,.45)">Friend signs up + places their first order using your code</div></div>
          <div style="text-align:center"><div style="font-size:2rem;margin-bottom:10px">💰</div><div style="font-size:.88rem;font-weight:700;margin-bottom:4px">3. You both win</div><div style="font-size:.78rem;color:rgba(255,255,255,.45)">You earn {{ number_format($refRewardPts) }} loyalty points. They get a welcome bonus on their first order</div></div>
        </div>
      </div>
    </div>

    <!-- ROUTINE TRACKER PANEL -->
    <div class="dashboard-panel" id="panel-routine">
      <div class="dash-welcome"><h1 class="dash-greeting">Routine Tracker 🧴</h1><p class="dash-subtext">Log your daily skincare routine and build a streak to earn bonus points.</p></div>
      <div style="background:#fff;border-radius:var(--r-xl);padding:28px 32px;border:1.5px solid var(--border);margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
          <div><h3 style="font-size:1rem;font-weight:700;margin-bottom:6px">This Week</h3><span class="streak-badge" id="streak-badge">—</span></div>
          <div style="text-align:right"><div style="font-size:1.5rem;font-weight:700" id="streak-pts-display">—</div><div style="font-size:.75rem;color:var(--text-muted)">earned this week</div></div>
        </div>
        <div class="routine-week" id="routine-week-grid"></div>
      </div>
      <div style="background:#fff;border-radius:var(--r-xl);padding:28px 32px;border:1.5px solid var(--border);margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
          <h3 style="font-size:1rem;font-weight:700">Today's Checklist</h3>
          <div style="display:flex;gap:8px">
            <button class="btn btn-dark btn-sm" id="rt-am-tab" onclick="switchRoutineTab('am')">☀️ AM</button>
            <button class="btn btn-outline btn-sm" id="rt-pm-tab" onclick="switchRoutineTab('pm')">🌙 PM</button>
          </div>
        </div>
        <div id="routine-checklist-am"></div>
        <div id="routine-checklist-pm" style="display:none"></div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;flex-wrap:wrap;gap:12px">
          <div style="font-size:.85rem;color:var(--text-muted)" id="rt-progress-text">Loading…</div>
          <button class="btn btn-primary btn-sm" id="rt-mark-done-btn" onclick="markRoutineDone()">✓ Mark Today Done</button>
        </div>
      </div>
      <div style="background:var(--black);border-radius:var(--r-xl);padding:28px 32px;color:#fff">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
          <div><h3 style="font-family:var(--font-display);font-size:1.1rem;margin-bottom:4px" id="rt-month-name">—</h3><div style="font-size:.78rem;color:rgba(255,255,255,.35)">Consistency builds glass skin</div></div>
          <div style="text-align:right"><div style="font-size:1.4rem;font-weight:700;color:var(--lime)" id="rt-days-logged">—</div><div style="font-size:.72rem;color:rgba(255,255,255,.35)">days logged</div></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:8px">
          <div style="background:rgba(255,255,255,.06);border-radius:var(--r-md);padding:16px;text-align:center"><div style="font-size:1.3rem;font-weight:700;color:var(--lime)" id="rt-completion-rate">—</div><div style="font-size:.72rem;color:rgba(255,255,255,.4);margin-top:4px">Completion rate</div></div>
          <div style="background:rgba(255,255,255,.06);border-radius:var(--r-md);padding:16px;text-align:center"><div style="font-size:1.3rem;font-weight:700;color:var(--lime)" id="rt-month-pts">—</div><div style="font-size:.72rem;color:rgba(255,255,255,.4);margin-top:4px">Earned this month</div></div>
          <div style="background:rgba(255,255,255,.06);border-radius:var(--r-md);padding:16px;text-align:center"><div style="font-size:1.3rem;font-weight:700;color:var(--lime)" id="rt-streak-stat">—</div><div style="font-size:.72rem;color:rgba(255,255,255,.4);margin-top:4px">Day streak</div></div>
        </div>
      </div>
    </div>

    <!-- SECURITY PANEL -->
    <div class="dashboard-panel" id="panel-security">
      <div class="dash-welcome"><h1 class="dash-greeting">Security 🔐</h1><p class="dash-subtext">Manage your password, two-factor authentication, active sessions, and account access.</p></div>

      {{-- Flash detection: auto-open this panel on redirect --}}
      @if(session('security_success') || session('security_error') || $errors->hasAny(['current_password','password']))
        <span id="dash-security-flash" style="display:none"></span>
      @endif

      {{-- Flash messages --}}
      @if(session('security_success'))
        <div style="background:#dcfce7;color:#166534;border:1px solid #86efac;padding:12px 20px;border-radius:var(--r-lg);margin-bottom:20px;display:flex;align-items:center;gap:10px;font-weight:600;font-size:.88rem;">
          ✓ {{ session('security_success') }}
        </div>
      @endif
      @if(session('security_error'))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 20px;border-radius:var(--r-lg);margin-bottom:20px;display:flex;align-items:center;gap:10px;font-weight:600;font-size:.88rem;">
          ✕ {{ session('security_error') }}
        </div>
      @endif
      @if($errors->hasAny(['current_password','password','password_confirmation']))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 20px;border-radius:var(--r-lg);margin-bottom:20px;font-weight:600;font-size:.88rem;">
          ✕ @foreach($errors->all() as $e) {{ $e }}. @endforeach
        </div>
      @endif

      {{-- Change Password --}}
      <div class="security-section">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:4px">Change Password</h3>
        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:20px">Choose a strong password that you haven't used before. Min. 8 characters.</p>
        <form method="POST" action="{{ route('dashboard.security.password') }}" style="display:flex;flex-direction:column;gap:14px;max-width:480px">
          @csrf
          <div>
            <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Current Password</label>
            <input class="input" type="password" name="current_password" placeholder="••••••••" required autocomplete="current-password">
          </div>
          <div>
            <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">New Password</label>
            <input class="input" type="password" name="password" placeholder="At least 8 characters" id="new-pass-input" oninput="checkPassStrength(this.value)" required autocomplete="new-password" minlength="8">
          </div>
          <div id="pass-strength-bar" style="display:none;margin-top:-8px">
            <div style="height:4px;border-radius:2px;background:var(--border);overflow:hidden"><div id="pass-strength-fill" style="height:100%;border-radius:2px;transition:width .3s,background .3s;width:0"></div></div>
            <div style="font-size:.72rem;margin-top:4px;color:var(--text-muted)" id="pass-strength-label"></div>
          </div>
          <div>
            <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Confirm New Password</label>
            <input class="input" type="password" name="password_confirmation" placeholder="Re-enter new password" required autocomplete="new-password">
          </div>
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <button type="submit" class="btn btn-primary" style="width:fit-content">Update Password</button>
            <span style="font-size:.78rem;color:var(--text-muted)">You'll stay signed in on this device after changing.</span>
          </div>
        </form>
      </div>

      {{-- Security Settings --}}
      <div class="security-section">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:4px">Security Settings</h3>
        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:20px">Changes are saved automatically when you toggle a setting.</p>
        <div class="security-row">
          <div class="security-row-info">
            <h4>Two-Factor Authentication (2FA)</h4>
            <p>Require a verification code when signing in from a new device.</p>
          </div>
          <button class="toggle-pill {{ $sec2FA ? 'on' : '' }}" id="toggle-2fa"
                  onclick="saveSecuritySetting('two_factor', this, 'Two-Factor Authentication')">
            <div class="toggle-knob"></div>
          </button>
        </div>
        <div class="security-row">
          <div class="security-row-info">
            <h4>Login Notifications</h4>
            <p>Email me whenever a new sign-in to my account is detected.</p>
          </div>
          <button class="toggle-pill {{ $secLoginNotif ? 'on' : '' }}" id="toggle-loginnotif"
                  onclick="saveSecuritySetting('login_notifications', this, 'Login notifications')">
            <div class="toggle-knob"></div>
          </button>
        </div>
        <div class="security-row">
          <div class="security-row-info">
            <h4>Order &amp; Delivery Alerts via SMS</h4>
            <p>Get SMS updates when your orders ship or are out for delivery.</p>
          </div>
          <button class="toggle-pill {{ $secSmsAlerts ? 'on' : '' }}" id="toggle-sms"
                  onclick="saveSecuritySetting('sms_alerts', this, 'SMS alerts')">
            <div class="toggle-knob"></div>
          </button>
        </div>
        <div class="security-row">
          <div class="security-row-info">
            <h4>Stay Signed In</h4>
            <p>Keep me signed in on trusted devices for up to 30 days.</p>
          </div>
          <button class="toggle-pill {{ $secSaveSessions ? 'on' : '' }}" id="toggle-sessions"
                  onclick="saveSecuritySetting('save_sessions', this, 'Stay signed in')">
            <div class="toggle-knob"></div>
          </button>
        </div>
      </div>

      {{-- Current Session --}}
      <div class="security-section">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:4px">Current Session</h3>
        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:18px">The device you are currently using to access your account.</p>
        <div class="device-row">
          <div class="device-icon">{{ $currentDeviceIcon }}</div>
          <div class="device-info">
            <div class="device-name">{{ $currentDevice }} <span class="device-current">Current</span></div>
            <div class="device-meta">IP {{ $currentIp }} · Active now</div>
          </div>
        </div>

        {{-- Account Overview Grid --}}
        <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border)">
          <div style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px">Account Details</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div style="background:var(--gray-50,#FAFAFA);border-radius:var(--r-lg);padding:14px 16px;border:1.5px solid var(--border)">
              <div style="font-size:.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px">Email</div>
              <div style="font-size:.88rem;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $user['email'] ?? '—' }}</div>
            </div>
            <div style="background:var(--gray-50,#FAFAFA);border-radius:var(--r-lg);padding:14px 16px;border:1.5px solid var(--border)">
              <div style="font-size:.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px">Member Since</div>
              <div style="font-size:.88rem;font-weight:700">{{ $memberSince }}</div>
            </div>
            <div style="background:var(--gray-50,#FAFAFA);border-radius:var(--r-lg);padding:14px 16px;border:1.5px solid var(--border)">
              <div style="font-size:.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px">Membership ID</div>
              <div style="font-size:.85rem;font-weight:700;letter-spacing:.06em">{{ $memberIdCode }}</div>
            </div>
            <div style="background:var(--gray-50,#FAFAFA);border-radius:var(--r-lg);padding:14px 16px;border:1.5px solid var(--border)">
              <div style="font-size:.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px">Loyalty Tier</div>
              <div style="font-size:.88rem;font-weight:700;color:var(--lime-dark,#97b01e)">{{ $tierIcon }} {{ $tierLabel }}</div>
            </div>
          </div>
        </div>

        @if(!empty($user['provider']))
        <div style="margin-top:14px;padding:14px 16px;background:rgba(59,130,246,.05);border-radius:var(--r-lg);border:1.5px solid rgba(59,130,246,.15);display:flex;align-items:center;gap:12px">
          <span style="font-size:1.2rem">{{ $user['provider'] === 'google' ? '🔵' : '🔷' }}</span>
          <div>
            <div style="font-size:.85rem;font-weight:700">Connected via {{ ucfirst($user['provider']) }}</div>
            <div style="font-size:.75rem;color:var(--text-muted)">Your account uses {{ ucfirst($user['provider']) }} sign-in — no separate password required.</div>
          </div>
        </div>
        @endif
      </div>

      {{-- Danger Zone --}}
      <div style="background:rgba(232,56,46,.04);border-radius:var(--r-xl);padding:24px 28px;border:1.5px solid rgba(232,56,46,.15)">
        <h3 style="font-size:.95rem;font-weight:700;color:var(--red);margin-bottom:6px">Danger Zone</h3>
        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:20px">Once your account is deleted, all data is permanently removed and cannot be recovered. We will reach out to confirm before proceeding.</p>

        <div id="delete-request-form" style="display:none;margin-bottom:16px">
          <form method="POST" action="{{ route('dashboard.security.delete-request') }}"
                onsubmit="return confirm('Submit an account deletion request? Our team will email you to confirm before anything is deleted.')">
            @csrf
            <div style="margin-bottom:12px">
              <label style="font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">Reason (optional)</label>
              <textarea name="reason" class="input" placeholder="Tell us why you'd like to delete your account…" rows="3" style="resize:vertical;min-height:80px;font-family:inherit"></textarea>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
              <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--red);border-color:rgba(232,56,46,.4)">Confirm Deletion Request</button>
              <button type="button" class="btn btn-ghost btn-sm"
                      onclick="document.getElementById('delete-request-form').style.display='none';document.getElementById('delete-btn-toggle').style.display=''">
                Cancel
              </button>
            </div>
          </form>
        </div>

        <button id="delete-btn-toggle" class="btn btn-ghost btn-sm" style="color:var(--red);border-color:rgba(232,56,46,.3)"
                onclick="this.style.display='none';document.getElementById('delete-request-form').style.display=''">
          Request Account Deletion
        </button>
      </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- WALLET PANEL                                              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="dashboard-panel" id="panel-wallet">
      @php
        $wd      = ($walletData['wallet'] ?? []);
        $wBal    = number_format((float)($wd['available_balance'] ?? 0), 2);
        $wLocked = number_format((float)($wd['locked_balance']    ?? 0), 2);
        $wStatus = $wd['status'] ?? 'active';
        $wTxList = $walletTransactions['data'] ?? [];
      @endphp

      <div class="dash-welcome">
        <h1 class="dash-greeting">My Wallet 💳</h1>
        <p class="dash-subtext">Fund your wallet, pay for orders, and track every transaction.</p>
      </div>

      {{-- Balance card --}}
      <div style="background:linear-gradient(135deg,#0A0A0A 0%,#1c1c1c 60%,#2a2a2a 100%);border-radius:20px;padding:32px 36px;color:#fff;position:relative;overflow:hidden;margin-bottom:24px">
        <div style="position:absolute;top:-80px;right:-80px;width:280px;height:280px;background:radial-gradient(circle,rgba(212,217,148,.13) 0%,transparent 65%);pointer-events:none"></div>
        <div style="position:relative;z-index:1">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:24px">
            <div>
              <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.16em;color:rgba(255,255,255,.3);margin-bottom:8px">Available Balance</div>
              <div style="font-size:2.6rem;font-weight:700;line-height:1"><span style="font-size:1.3rem;font-weight:700;color:var(--lime)">₦</span>{{ $wBal }}</div>
              <div style="font-size:.72rem;color:rgba(255,255,255,.35);margin-top:4px">Nigerian Naira · Kominhoo Wallet</div>
            </div>
            <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:999px;font-size:.68rem;font-weight:700;text-transform:uppercase;background:{{ $wStatus==='active'?'rgba(34,197,94,.15)':($wStatus==='frozen'?'rgba(59,130,246,.15)':'rgba(245,158,11,.15)') }};color:{{ $wStatus==='active'?'#4ade80':($wStatus==='frozen'?'#60a5fa':'#fbbf24') }}">
              {{ ucfirst($wStatus) }}
            </span>
          </div>
          <div style="display:flex;gap:32px">
            <div>
              <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.25)">Locked</div>
              <div style="font-size:.88rem;font-weight:700;color:rgba(255,255,255,.6);margin-top:3px">₦{{ $wLocked }}</div>
            </div>
            <div>
              <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.25)">Transactions</div>
              <div style="font-size:.88rem;font-weight:700;color:rgba(255,255,255,.6);margin-top:3px">{{ $walletTransactions['total'] ?? count($wTxList) }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Top-up form --}}
      @if($wStatus === 'active')
      <div style="background:#fff;border-radius:var(--r-xl);border:1.5px solid var(--border);padding:28px 32px;margin-bottom:24px">
        <div style="font-size:1rem;font-weight:700;margin-bottom:4px">Add Money</div>
        <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:18px">Securely top up via Paystack. Balance updates after payment confirmation.</div>

        {{-- Error flash --}}
        @if(session('wallet_error'))
        <div style="background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:var(--r-md);padding:11px 16px;margin-bottom:16px;font-size:.84rem;font-weight:600">
          ⚠ {{ session('wallet_error') }}
        </div>
        @endif

        {{-- Success state (shown after inline payment) --}}
        <div id="wallet-success-banner" style="display:none;background:#f0fdf4;color:#166534;border:1px solid #86efac;border-radius:var(--r-md);padding:14px 18px;margin-bottom:16px;font-size:.88rem;font-weight:700;text-align:center">
          ✅ Payment successful! Your wallet balance is updating — refreshing in a moment…
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px" id="wallet-presets">
          @foreach([500,1000,2500,5000,10000,20000] as $preset)
          <button onclick="walletSetAmount({{ $preset }},this)"
                  style="background:var(--gray-50,#FAFAFA);border:1.5px solid var(--border);border-radius:var(--r-md);padding:9px 16px;font-size:.85rem;font-weight:700;cursor:pointer;transition:.15s"
                  onmouseover="this.style.borderColor='var(--lime)'" onmouseout="if(!this.classList.contains('w-sel'))this.style.borderColor='var(--border)'">
            ₦{{ number_format($preset) }}
          </button>
          @endforeach
        </div>

        <div style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
          <div style="flex:1;min-width:180px">
            <label style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;display:block;margin-bottom:6px">Amount (₦)</label>
            <input type="number" id="wallet-amount-input"
                   style="border:1.5px solid var(--border);border-radius:var(--r-md);padding:12px 16px;font-size:1rem;font-weight:700;width:100%;background:#fff;transition:.15s;box-sizing:border-box"
                   placeholder="e.g. 3000" min="100" max="1000000" step="100"
                   onfocus="this.style.borderColor='var(--lime)'" onblur="this.style.borderColor='var(--border)'">
            <div id="wallet-amount-error" style="color:#DC2626;font-size:.75rem;margin-top:4px;display:none">Minimum amount is ₦100.</div>
          </div>
          <button type="button" id="wallet-submit-btn"
                  style="background:var(--black);color:var(--lime);border:none;border-radius:var(--r-md);padding:13px 28px;font-size:.88rem;font-weight:700;cursor:pointer;white-space:nowrap"
                  onclick="fundWallet()">
            Fund Wallet →
          </button>
        </div>

        <div style="font-size:.72rem;color:var(--text-muted);margin-top:12px;display:flex;align-items:center;gap:6px">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Payments secured by Paystack. Wallet credited after webhook confirmation.
        </div>
      </div>
      @else
      <div style="background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:var(--r-lg);padding:14px 20px;margin-bottom:24px;font-size:.87rem">
        ⚠ Your wallet is <strong>{{ $wStatus }}</strong>. Please contact support to reactivate.
      </div>
      @endif

      {{-- Transaction history --}}
      <div style="background:#fff;border-radius:var(--r-xl);border:1.5px solid var(--border);overflow:hidden">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:1.5px solid var(--border)">
          <div style="font-size:1rem;font-weight:700">Transaction History</div>
          <div style="display:flex;gap:6px">
            <button onclick="walletFilter('all',this)" style="border:1.5px solid var(--black);background:var(--black);color:#fff;border-radius:999px;padding:5px 14px;font-size:.75rem;font-weight:700;cursor:pointer">All</button>
            <button onclick="walletFilter('credit',this)" style="border:1.5px solid var(--border);background:#fff;border-radius:999px;padding:5px 14px;font-size:.75rem;font-weight:700;cursor:pointer">Credits</button>
            <button onclick="walletFilter('debit',this)" style="border:1.5px solid var(--border);background:#fff;border-radius:999px;padding:5px 14px;font-size:.75rem;font-weight:700;cursor:pointer">Debits</button>
            <button onclick="walletFilter('bonus',this)" style="border:1.5px solid var(--border);background:#fff;border-radius:999px;padding:5px 14px;font-size:.75rem;font-weight:700;cursor:pointer">Bonuses</button>
          </div>
        </div>

        @if(empty($wTxList))
        <div style="text-align:center;padding:48px 24px;color:var(--text-muted)">
          <div style="font-size:2rem;margin-bottom:10px">💳</div>
          <div style="font-weight:700;margin-bottom:6px">No transactions yet</div>
          <div style="font-size:.85rem">Fund your wallet above to get started.</div>
        </div>
        @else
        <div id="wallet-tx-list">
          @foreach($wTxList as $tx)
          @php
            $txType   = $tx['transaction_type'] ?? 'credit';
            $txStatus = $tx['status'] ?? 'successful';
            $txAmt    = number_format((float)($tx['amount'] ?? 0), 2);
            $txIsCredit = in_array($txType, ['credit','bonus','refund','reversal']);
            $iconMap  = ['deposit'=>'💸','purchase'=>'🛍️','signup_bonus'=>'🎁','first_deposit_bonus'=>'🎉','referral_bonus'=>'👥','admin_bonus'=>'⭐','campaign_bonus'=>'📢','refund'=>'↩️'];
            $txIcon   = $iconMap[$tx['category'] ?? ''] ?? '💳';
            $txCat    = str_replace('_', ' ', $tx['category'] ?? $txType);
            $txDate   = isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('M j, Y · g:ia') : '';
            $statusClr= $txStatus === 'successful' ? '#16A34A' : ($txStatus === 'pending' ? '#D97706' : '#DC2626');
            $statusBg = $txStatus === 'successful' ? 'rgba(34,197,94,.1)' : ($txStatus === 'pending' ? 'rgba(245,158,11,.1)' : 'rgba(220,38,38,.08)');
          @endphp
          <div class="wallet-tx-row" data-type="{{ $txType }}"
               style="display:flex;align-items:center;gap:14px;padding:14px 24px;border-bottom:1px solid var(--border);transition:.15s"
               onmouseover="this.style.background='var(--gray-50,#FAFAFA)'" onmouseout="this.style.background=''">
            <div style="width:42px;height:42px;border-radius:11px;display:grid;place-items:center;font-size:1.1rem;flex-shrink:0;background:{{ $txIsCredit ? 'rgba(34,197,94,.1)' : ($txStatus==='pending' ? 'rgba(107,114,128,.08)' : 'rgba(137,57,65,.08)') }}">
              {{ $txIcon }}
            </div>
            <div style="flex:1;min-width:0">
              <div style="font-size:.88rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $tx['description'] ?? ucfirst($txCat) }}</div>
              <div style="font-size:.72rem;color:var(--text-muted);margin-top:2px;display:flex;align-items:center;gap:8px">
                <span>{{ $txDate }}</span>
                <span style="background:var(--gray-100,#F3F4F6);padding:2px 7px;border-radius:999px;font-size:.6rem;font-weight:700;text-transform:uppercase">{{ $txCat }}</span>
                <span style="background:{{ $statusBg }};color:{{ $statusClr }};padding:2px 7px;border-radius:999px;font-size:.6rem;font-weight:700">{{ $txStatus }}</span>
              </div>
            </div>
            <div style="text-align:right;flex-shrink:0;font-size:.95rem;font-weight:700;color:{{ $txStatus==='failed'?'#9CA3AF':($txIsCredit?'#16A34A':'#DC2626') }}">
              {{ $txIsCredit ? '+' : '−' }}₦{{ $txAmt }}
            </div>
          </div>
          @endforeach
        </div>
        @endif
      </div>

    </div>
    {{-- END WALLET PANEL --}}

  </main>
</div>


<script>
function walletSetAmount(val, btn) {
  document.getElementById('wallet-amount-input').value = val;
  document.querySelectorAll('#wallet-presets button').forEach(b => {
    b.classList.remove('w-sel');
    b.style.borderColor = 'var(--border)';
    b.style.background  = 'var(--gray-50,#FAFAFA)';
  });
  btn.classList.add('w-sel');
  btn.style.borderColor = 'var(--lime)';
  btn.style.background  = 'var(--lime-pale,#f5ffe0)';
}

function walletFilter(type, btn) {
  document.querySelectorAll('#panel-wallet .tx-filter-btn, #panel-wallet [onclick^="walletFilter"]').forEach(b => {
    b.style.background  = '#fff';
    b.style.color       = '';
    b.style.borderColor = 'var(--border)';
  });
  btn.style.background  = 'var(--black)';
  btn.style.color       = '#fff';
  btn.style.borderColor = 'var(--black)';

  document.querySelectorAll('.wallet-tx-row').forEach(row => {
    row.style.display = (type === 'all' || row.dataset.type === type) ? '' : 'none';
  });
}

// Open wallet panel if URL hash is #wallet
if (window.location.hash === '#wallet') {
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof switchDashPanel === 'function') switchDashPanel('panel-wallet');
  });
}
</script>

@endsection

@section('scripts')
<script>
// ── Dashboard skeleton removal ────────────────────────────────────
(function () {
  var sk = document.getElementById('dash-skeleton');
  if (!sk) return;
  var done = false;
  function removeSkeleton() {
    if (done) return;
    done = true;
    sk.classList.add('dsk-out');
    setTimeout(function () { sk && sk.remove(); }, 460);
  }
  if (document.readyState === 'complete') {
    setTimeout(removeSkeleton, 80);
  } else {
    window.addEventListener('load', function () { setTimeout(removeSkeleton, 80); }, { once: true });
    setTimeout(removeSkeleton, 2500);
  }
})();
</script>
{{-- Paystack config — must be in the same section as the script load, identical pattern to checkout --}}
<div id="wallet-cfg"
  data-paystack-key="{{ $paystackKey ?? '' }}"
  data-user-email="{{ session('user.email') ?? '' }}"
  style="display:none"></div>
@if(!empty($paystackKey))
<script src="https://js.paystack.co/v1/inline.js"></script>
@endif
<script>
// Safe — null-guard in case element not yet in DOM
const _wCfgEl     = document.getElementById('wallet-cfg');
const WALLET_KEY   = _wCfgEl ? _wCfgEl.dataset.paystackKey  : '';
const WALLET_EMAIL = _wCfgEl ? _wCfgEl.dataset.userEmail    : '';

// ── Wallet top-up (mirrors checkout openPaystack exactly) ─────
function fundWallet() {
  const amtInput = document.getElementById('wallet-amount-input');
  const errEl    = document.getElementById('wallet-amount-error');
  const btn      = document.getElementById('wallet-submit-btn');
  const amount   = parseFloat(amtInput?.value);

  if (!amount || amount < 100) {
    amtInput.style.borderColor = '#DC2626';
    if (errEl) errEl.style.display = '';
    return;
  }
  amtInput.style.borderColor = 'var(--border)';
  if (errEl) errEl.style.display = 'none';

  const showErr = (msg) => {
    const b = document.getElementById('wallet-success-banner');
    if (b) {
      b.style.cssText = 'display:;background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:var(--r-md);padding:14px 18px;margin-bottom:16px;font-size:.88rem;font-weight:700;text-align:center';
      b.textContent = '⚠ ' + msg;
    }
    btn.disabled = false;
    btn.textContent = 'Fund Wallet →';
  };

  if (!WALLET_KEY) {
    showErr('Payment not configured. Please contact support.');
    return;
  }

  if (typeof PaystackPop === 'undefined' || typeof PaystackPop.setup !== 'function') {
    showErr('Payment gateway failed to load. Please refresh the page and try again.');
    return;
  }

  // Paystack requires a valid email — fall back to session email or a placeholder
  const paystackEmail = WALLET_EMAIL || '{{ session("user.email") ?? "noreply@kominhoo.ng" }}';

  btn.disabled    = true;
  btn.textContent = 'Opening payment…';

  try {
    const handler = PaystackPop.setup({
      key:      WALLET_KEY,
      email:    paystackEmail,
      amount:   Math.round(amount * 100),
      currency: 'NGN',
      ref:      'WLT-' + Date.now(),
      onClose: function() {
        btn.disabled    = false;
        btn.textContent = 'Fund Wallet →';
      },
      callback: function(response) {
        btn.disabled    = true;
        btn.textContent = 'Confirming…';
        _walletVerify(response.reference, btn);
      }
    });

    if (!handler || typeof handler.openIframe !== 'function') {
      throw new Error('Paystack handler not returned. Check your public key.');
    }

    handler.openIframe();
  } catch (e) {
    showErr('Could not open payment: ' + e.message);
  }
}

async function _walletVerify(reference, btn) {
  const csrf   = document.querySelector('meta[name="csrf-token"]')?.content;
  const banner = document.getElementById('wallet-success-banner');

  const showBanner = (cssText, text) => {
    if (banner) { banner.style.cssText = cssText; banner.textContent = text; }
  };

  try {
    const res  = await fetch('{{ route("dashboard.wallet.deposit.verify") }}', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body:    JSON.stringify({ reference }),
    });

    let data;
    try { data = await res.json(); } catch { data = {}; }

    if (data.success && data.data?.new_balance != null) {
      const newBal = parseFloat(data.data.new_balance);
      const fmt    = n => '₦' + n.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

      const navAmt = document.getElementById('nav-wallet-amount');
      if (navAmt) navAmt.textContent = fmt(newBal);

      showBanner(
        'display:;background:#f0fdf4;color:#166534;border:1px solid #86efac;border-radius:var(--r-md);padding:14px 18px;margin-bottom:16px;font-size:.88rem;font-weight:700;text-align:center',
        '✅ ₦' + parseFloat(data.data.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 }) + ' added! New balance: ' + fmt(newBal)
      );
      btn.textContent = 'Wallet Funded ✓';
      setTimeout(() => window.location.reload(), 2500);

    } else {
      // Show the real error from the server so we can diagnose
      const msg = data.message || ('Verify failed (HTTP ' + res.status + ')');
      showBanner(
        'display:;background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:var(--r-md);padding:14px 18px;margin-bottom:16px;font-size:.88rem;font-weight:700;text-align:center',
        '⚠ Payment was received by Paystack but wallet credit failed: ' + msg + '. Please contact support with ref: ' + reference
      );
      btn.disabled    = false;
      btn.textContent = 'Fund Wallet →';
    }
  } catch (err) {
    showBanner(
      'display:;background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:var(--r-md);padding:14px 18px;margin-bottom:16px;font-size:.88rem;font-weight:700;text-align:center',
      '⚠ Network error during confirmation: ' + err.message + '. Payment ref: ' + reference + '. Please contact support.'
    );
    btn.disabled    = false;
    btn.textContent = 'Fund Wallet →';
  }
}

// Avatar preview
function previewAvatar(input) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = function(e) {
    const preview = document.getElementById('avatarPreview');
    const initial = document.getElementById('avatarInitial');
    if (preview) { preview.src = e.target.result; preview.style.display = 'block'; }
    if (initial) initial.style.display = 'none';
    const saveBtn = document.getElementById('avatarSaveBtn');
    if (saveBtn) saveBtn.style.display = '';
  };
  reader.readAsDataURL(input.files[0]);
}

// Open the correct panel after a redirect with flash
document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('dash-profile-flash'))  switchDashPanel('panel-profile');
  if (document.getElementById('dash-security-flash')) switchDashPanel('panel-security');
  if ('{{ ((isset($active_tab) && $active_tab === "orders") || request()->query("order_placed")) ? "1" : "" }}') switchDashPanel('panel-orders');
});

// Blade-generated base URLs — correct under any subdirectory deployment
const DASH_POSTS_URL     = '{{ route("dashboard.community.posts") }}';
const COMMUNITY_POST_URL = '{{ url("/community/post") }}';
const GIFT_CARDS_URL     = '{{ route("gift-cards.index") }}';

// Community post point values from backend config (Number() coerces rendered digit-string to JS number)
const COMM_PTS = {
  photo:        Number('{{ $pointEventsConfig["community_post"]["points"] ?? 30 }}'),
  before_after: Number('{{ $pointEventsConfig["before_after"]["points"]   ?? 50 }}'),
  review:       Number('{{ $pointEventsConfig["review"]["points"]         ?? 50 }}'),
  routine:      Number('{{ $pointEventsConfig["routine_post"]["points"]   ?? 20 }}'),
};

// ── Saved / Wishlist ─────────────────────────────────────────
function renderSavedGrid() {
  const savedIds = JSON.parse(localStorage.getItem('kominhoo_saved') || '[]');
  const savedProds = (typeof PRODUCTS !== 'undefined') ? PRODUCTS.filter(p => savedIds.includes(p.id)) : [];
  const onSale = savedProds.filter(p => p.originalPrice);

  // Subtext
  const subtext = document.getElementById('saved-subtext');
  if (subtext) {
    subtext.textContent = savedProds.length === 0
      ? 'Your wishlist is empty'
      : savedProds.length + ' item' + (savedProds.length !== 1 ? 's' : '') + ' saved' + (onSale.length ? ' — ' + onSale.length + ' on sale!' : '');
  }

  // Nav badge
  const navSaved = document.querySelector('[data-panel="panel-saved"]');
  if (navSaved) {
    let badge = navSaved.querySelector('.saved-nav-badge');
    if (savedProds.length > 0) {
      if (!badge) {
        badge = document.createElement('span');
        badge.className = 'saved-nav-badge';
        badge.style.cssText = 'background:var(--lime);color:var(--black);font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:999px;margin-left:auto';
        navSaved.appendChild(badge);
      }
      badge.textContent = savedProds.length;
    } else if (badge) {
      badge.remove();
    }
  }

  // Sale banner
  const banner = document.getElementById('saved-sale-banner');
  if (banner) {
    if (onSale.length > 0) {
      document.getElementById('saved-sale-text').textContent = '🔥 ' + onSale.length + ' product' + (onSale.length !== 1 ? 's' : '') + ' in your wishlist just went on sale!';
      banner.style.display = 'flex';
    } else {
      banner.style.display = 'none';
    }
  }

  // Grid
  const savedGrid = document.getElementById('saved-grid');
  if (!savedGrid) return;

  if (savedProds.length === 0) {
    savedGrid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:64px 24px">'
      + '<div style="font-size:2.5rem;margin-bottom:12px">♡</div>'
      + '<div style="font-size:1rem;font-weight:700;margin-bottom:8px">Your wishlist is empty</div>'
      + '<p style="font-size:.875rem;color:var(--text-secondary);margin-bottom:24px">Save products you love while browsing the shop</p>'
      + '<a href="{{ route("shop") }}" class="btn btn-dark">Browse Products</a>'
      + '</div>';
    return;
  }

  savedGrid.innerHTML = savedProds.map(function(p) {
    const discount = p.originalPrice ? Math.round((1 - p.price / p.originalPrice) * 100) : 0;
    return '<div class="saved-card">'
      + '<div style="position:relative;flex-shrink:0">'
      + '<img class="saved-card-img" src="' + p.image + '" alt="' + p.name + '">'
      + (discount ? '<span style="position:absolute;top:4px;left:4px;background:var(--red);color:#fff;font-size:.6rem;font-weight:700;padding:2px 6px;border-radius:4px">-' + discount + '%</span>' : '')
      + '</div>'
      + '<div style="flex:1;min-width:0">'
      + '<div style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase">' + p.brand + '</div>'
      + '<div style="font-size:.9rem;font-weight:700;margin:4px 0;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + p.name + '</div>'
      + '<div style="display:flex;align-items:baseline;gap:6px">'
      + '<span style="font-size:.9rem;font-weight:700">₦' + p.price.toLocaleString() + '</span>'
      + (p.originalPrice ? '<span style="font-size:.78rem;color:var(--text-muted);text-decoration:line-through">₦' + p.originalPrice.toLocaleString() + '</span>' : '')
      + '</div>'
      + '<div style="display:flex;gap:8px;margin-top:10px">'
      + '<button class="btn btn-dark btn-sm" style="flex:1" onclick="addToCart(' + p.id + ')">Add to Cart</button>'
      + '<button class="btn btn-ghost btn-sm" onclick="removeFromSaved(' + p.id + ')" title="Remove from wishlist">✕</button>'
      + '</div>'
      + '</div>'
      + '</div>';
  }).join('');
}

function removeFromSaved(id) {
  const savedIds = JSON.parse(localStorage.getItem('kominhoo_saved') || '[]');
  localStorage.setItem('kominhoo_saved', JSON.stringify(savedIds.filter(s => s !== id)));
  if (typeof saved !== 'undefined') { const idx = saved.indexOf(id); if (idx > -1) saved.splice(idx, 1); }
  renderSavedGrid();
  showToast('♡', 'Removed from wishlist');
}

function addSaleItemsToCart() {
  const savedIds = JSON.parse(localStorage.getItem('kominhoo_saved') || '[]');
  if (typeof PRODUCTS === 'undefined') return;
  const onSale = PRODUCTS.filter(p => savedIds.includes(p.id) && p.originalPrice);
  onSale.forEach(p => addToCart(p.id));
}

document.addEventListener('DOMContentLoaded', () => {
  // Saved products
  renderSavedGrid();

  // Dash recommendations
  const recTrack = document.getElementById('dash-rec-track');
  if (recTrack && typeof PRODUCTS !== 'undefined' && typeof buildProductCard !== 'undefined') {
    recTrack.innerHTML = PRODUCTS.slice(0,6).map(p => buildProductCard(p,'260px')).join('');
  }

  // Loyalty bar
  setTimeout(() => {
    document.querySelectorAll('.loyalty-progress-fill').forEach(el => {
      el.style.transition = 'width 1s ease';
      el.style.width = el.dataset.pct || '62%';
    });
  }, 500);

  // Apply dynamic tier colors via data attributes (avoids CSS linter errors in Blade)
  document.querySelectorAll('.js-tier-color[data-color]').forEach(el => {
    el.style.color = el.dataset.color;
  });
  document.querySelectorAll('.js-tier-row[data-color]').forEach(el => {
    if (el.dataset.active === '1') {
      el.style.borderColor = el.dataset.color;
      el.style.background  = 'rgba(0,0,0,.02)';
    }
  });
  document.querySelectorAll('.js-tier-dot[data-color]').forEach(el => {
    if (el.dataset.past === '1') {
      el.style.background = el.dataset.color;
      el.style.color      = el.dataset.light === '1' ? '#1C1416' : '#fff';
    }
  });
  document.querySelectorAll('.js-tier-name[data-color]').forEach(el => {
    if (el.dataset.active === '1') el.style.color = el.dataset.color;
  });
  document.querySelectorAll('.js-tier-badge[data-color]').forEach(el => {
    el.style.background = el.dataset.color;
  });
  document.querySelectorAll('.js-ref-av[data-bg]').forEach(el => {
    el.style.background = el.dataset.bg;
    el.style.color      = el.dataset.light === '1' ? '#1C1416' : '#fff';
  });
  // Popular plan border highlight
  document.querySelectorAll('.js-plan-card.plan-popular').forEach(el => {
    el.style.borderColor = 'var(--lime-dark,#97b01e)';
  });

  // Subscription action buttons (delegated)
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.js-sub-action');
    if (!btn) return;
    const id     = btn.dataset.subId;
    const action = btn.dataset.action;
    const confirm_msg = btn.dataset.confirm;
    if (confirm_msg && !confirm(confirm_msg)) return;
    updateSubscription(id, action);
  });

  // Subscribe to plan buttons (delegated)
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.js-subscribe-btn');
    if (!btn) return;
    subscribeToPlan(btn.dataset.planId, btn.dataset.planName, btn.dataset.planPrice, btn.dataset.planCycle);
  });

  // Referral share buttons
  const refCode = document.getElementById('ref-code-display')?.textContent?.trim() || '';
  const refLink = document.getElementById('ref-link-text')?.textContent?.trim() || '';
  const waBtn   = document.getElementById('ref-whatsapp-btn');
  const emBtn   = document.getElementById('ref-email-btn');
  if (waBtn) waBtn.onclick = () => {
    const msg = encodeURIComponent(`Shop K-beauty in Nigeria with me! Use my code ${refCode} for a welcome bonus at Kominhoo Beauty — ${refLink}`);
    window.open(`https://wa.me/?text=${msg}`, '_blank');
  };
  if (emBtn) emBtn.onclick = () => {
    const sub  = encodeURIComponent('You\'re invited to Kominhoo Beauty!');
    const body = encodeURIComponent(`Hey!\n\nI've been loving Kominhoo Beauty — premium K-skincare in Nigeria.\n\nUse my referral code ${refCode} when you sign up: ${refLink}\n\nYou'll get a welcome bonus on your first order!`);
    window.location.href = `mailto:?subject=${sub}&body=${body}`;
  };

  // Init dropdowns
  filterProdTags('');
  filterRevProds('');
  initAspectStars();
  initRoutineSteps();
  loadMyPosts();
  renderDiscoverScroll();
  initRoutineTracker();

  // Gift card grid
  renderDashGcGrid();

  // Load member ID from localStorage if set during signup
  loadMemberId();
});

// — Composer tab switching
function switchCC(id, el) {
  document.querySelectorAll('.cc-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.cc-tab').forEach(t => t.classList.remove('active'));
  document.getElementById(id)?.classList.add('active');
  if (el) el.classList.add('active');
}

// — Photo file handling
const uploadedPhotos = [];
function handlePhotoFiles(input) {
  const files = Array.from(input.files || []);
  const strip = document.getElementById('photo-preview-strip');
  files.forEach(file => {
    if (uploadedPhotos.length >= 6) return;
    const reader = new FileReader();
    reader.onload = e => {
      uploadedPhotos.push(e.target.result);
      const wrap = document.createElement('div');
      wrap.className = 'photo-thumb-wrap';
      wrap.innerHTML = `<img class="photo-thumb" src="${e.target.result}"><button class="photo-thumb-remove" onclick="removeUploadedPhoto(this,${uploadedPhotos.length-1})">×</button>`;
      strip.appendChild(wrap);
    };
    reader.readAsDataURL(file);
  });
}
function removeUploadedPhoto(btn, idx) {
  uploadedPhotos.splice(idx, 1);
  btn.parentElement.remove();
}

function handleBAPreview(input, previewId) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById(previewId);
    const icon = document.getElementById(previewId.replace('preview','icon'));
    if (img) { img.src = e.target.result; img.style.display = 'block'; }
    if (icon) icon.style.display = 'none';
  };
  reader.readAsDataURL(file);
}

function handleRevPhotoPreview(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('rev-photo-preview');
    const ph  = document.getElementById('rev-photo-placeholder');
    if (img) { img.src = e.target.result; img.style.display = 'block'; }
    if (ph) ph.style.display = 'none';
  };
  reader.readAsDataURL(file);
}

function handleRoutinePhoto(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('routine-photo-preview');
    const ph  = document.getElementById('routine-photo-ph');
    if (img) { img.src = e.target.result; img.style.display = 'block'; }
    if (ph) ph.style.display = 'none';
  };
  reader.readAsDataURL(file);
}

// — Product tag dropdown — uses real catalog when available
const PROD_SUGGESTIONS = (typeof PRODUCTS !== 'undefined' && PRODUCTS.length)
  ? PRODUCTS.map(p => ({ name: p.name, brand: p.brand || '', img: p.image || p.img || '' }))
  : [
    { name:'COSRX Snail Mucin Essence',  brand:'COSRX',             img:'' },
    { name:'Beauty of Joseon SPF 50+',   brand:'Beauty of Joseon',  img:'' },
    { name:'The Ordinary Niacinamide',   brand:'The Ordinary',      img:'' },
    { name:'Laneige Lip Sleeping Mask',  brand:'Laneige',           img:'' },
    { name:'Innisfree Green Tea Cream',  brand:'Innisfree',         img:'' },
  ];
const taggedProds = [];
function filterProdTags(val) {
  const dd = document.getElementById('prod-tag-dropdown');
  if (!dd) return;
  const q = val.toLowerCase();
  const filtered = PROD_SUGGESTIONS.filter(p => p.name.toLowerCase().includes(q) || p.brand.toLowerCase().includes(q));
  dd.innerHTML = filtered.map(p => `<div class="ptd-item" onmousedown="addProdTag('${p.name}','${p.img}')"><img src="${p.img}" alt=""><div><div style="font-size:.82rem;font-weight:700">${p.name}</div><div style="font-size:.72rem;color:var(--text-muted)">${p.brand}</div></div></div>`).join('') || '<div class="ptd-item" style="color:var(--text-muted)">No matching products</div>';
}
function filterRevProds(val) {
  const dd = document.getElementById('rev-prod-dropdown');
  if (!dd) return;
  const q = val.toLowerCase();
  const filtered = PROD_SUGGESTIONS.filter(p => p.name.toLowerCase().includes(q));
  dd.innerHTML = filtered.map(p => `<div class="ptd-item" onmousedown="selectRevProduct('${p.name}','${p.img}')"><img src="${p.img}" alt=""><div style="font-size:.82rem;font-weight:700">${p.name}</div></div>`).join('');
}
function addProdTag(name, img) {
  if (taggedProds.includes(name)) return;
  taggedProds.push(name);
  const list = document.getElementById('tagged-products');
  const chip = document.createElement('span');
  chip.className = 'tprod-chip';
  chip.innerHTML = `<img src="${img}" style="width:18px;height:18px;border-radius:3px;object-fit:cover">${name}<button onclick="removeProdTag(this,'${name}')">×</button>`;
  list.appendChild(chip);
  document.getElementById('prod-tag-search').value = '';
}
function removeProdTag(btn, name) {
  const i = taggedProds.indexOf(name);
  if (i > -1) taggedProds.splice(i, 1);
  btn.parentElement.remove();
}
function selectRevProduct(name, img) {
  document.getElementById('rev-prod-search').value = name;
  const el = document.getElementById('rev-selected-product');
  el.innerHTML = `<div style="display:flex;align-items:center;gap:10px;background:var(--lime-pale);border-radius:var(--r-md);padding:10px 14px"><img src="${img}" style="width:36px;height:36px;border-radius:6px;object-fit:cover"><span style="font-size:.85rem;font-weight:700">${name}</span></div>`;
}

// — Aspect stars
function initAspectStars() {
  ['asp-effect','asp-texture','asp-value'].forEach(id => {
    const container = document.getElementById(id);
    if (!container) return;
    const val = parseInt(container.dataset.val) || 4;
    container.innerHTML = [1,2,3,4,5].map(n => `<span class="asp-star" data-n="${n}" style="cursor:pointer;font-size:.85rem;color:${n<=val?'#F59E0B':'#E5E7EB'}" onmouseover="hoverAsp('${id}',${n})" onclick="setAsp('${id}',${n})">★</span>`).join('');
  });
}
function hoverAsp(id, n) {
  document.querySelectorAll(`#${id} .asp-star`).forEach((s, i) => { s.style.color = i < n ? '#F59E0B' : '#E5E7EB'; });
}
function setAsp(id, n) {
  const container = document.getElementById(id);
  if (container) container.dataset.val = n;
  hoverAsp(id, n);
}

// — Star rating
let currentStars = 4;
const starLabels = ['','Terrible','Poor','Average','Great!','Outstanding!'];
function hoverStars(n) {
  document.querySelectorAll('#star-rating .star').forEach((s, i) => { s.classList.toggle('active', i < n); });
}
function resetStarHover() {
  document.querySelectorAll('#star-rating .star').forEach((s, i) => { s.classList.toggle('active', i < currentStars); });
}
function setStars(n) {
  currentStars = n;
  resetStarHover();
  const lbl = document.getElementById('star-label');
  if (lbl) lbl.textContent = `${n} out of 5 — ${starLabels[n]}`;
}

// — Hashtag management
function addHashtag(e) {
  if (e.key !== 'Enter') return;
  const input = e.target || e;
  const val = input.value.trim().replace(/^#*/, '');
  if (!val) return;
  const cloud = document.getElementById('hashtag-cloud');
  const chip = document.createElement('span');
  chip.className = 'htag-chip';
  chip.innerHTML = `#${val} <button onclick="removeHashtag(this.parentElement)">×</button>`;
  cloud.appendChild(chip);
  input.value = '';
}
function removeHashtag(chip) { chip.remove(); }

// — Routine type selector
function setRoutineType(type) {
  ['AM','PM','Weekly'].forEach(t => {
    const btn = document.getElementById('rt-' + t.toLowerCase());
    if (btn) btn.className = t === type ? 'btn btn-dark btn-sm' : 'btn btn-outline btn-sm';
  });
}

// — Routine steps
let routineStepCount = 0;
function initRoutineSteps() {
  const defaults = ['Cleanser', 'Toner / Essence', 'Serum', 'Moisturiser', 'SPF (AM only)'];
  defaults.forEach(label => addRoutineStep(label));
}
function addRoutineStep(label) {
  routineStepCount++;
  const container = document.getElementById('routine-steps');
  if (!container) return;
  const row = document.createElement('div');
  row.style.cssText = 'display:flex;align-items:center;gap:8px';
  row.innerHTML = `<span style="width:22px;height:22px;border-radius:50%;background:var(--lime);color:var(--black);font-size:.7rem;font-weight:700;display:grid;place-items:center;flex-shrink:0">${routineStepCount}</span><input class="input" placeholder="${label || 'Step ' + routineStepCount}" style="flex:1;font-size:.85rem"><button class="btn btn-ghost btn-sm" style="padding:6px 10px;color:var(--red)" onclick="this.parentElement.remove()">×</button>`;
  container.appendChild(row);
}

// — Submit post
async function submitCommunityPost(type) {
  const labels = { photo:'Photo shared', before_after:'Transformation posted', review:'Review submitted', routine:'Routine shared' };
  const pts    = COMM_PTS;

  const fd   = new FormData();
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fd.append('_token', csrf);
  fd.append('type', type);

  if (type === 'photo') {
    const caption = document.getElementById('photo-caption')?.value?.trim() || '';
    if (!caption) { showToast('⚠️', 'Please add a caption before sharing.'); return; }
    fd.append('caption',   caption);
    fd.append('skin_type', document.getElementById('photo-skin-type')?.value || 'Combination');
    const tags = Array.from(document.querySelectorAll('#hashtag-cloud .htag-chip'))
      .map(c => c.childNodes[0]?.textContent?.trim() || c.textContent.replace('×','').trim())
      .filter(Boolean).join(',');
    fd.append('tags', tags);
    if (uploadedPhotos.length > 0) {
      fd.append('img', uploadedPhotos[0]);
      if (uploadedPhotos.length > 1) fd.append('images', JSON.stringify(uploadedPhotos));
    }

  } else if (type === 'before_after') {
    const story = document.getElementById('ba-caption')?.value?.trim() || '';
    if (!story) { showToast('⚠️', 'Please write your skin story before posting.'); return; }
    const beforeSrc = document.getElementById('ba-before-preview')?.src || '';
    const afterSrc  = document.getElementById('ba-after-preview')?.src  || '';
    const validSrc  = s => s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:');
    if (!validSrc(afterSrc)) { showToast('⚠️', 'Please upload your after (glow-up) photo.'); return; }
    const period = document.getElementById('ba-period-select')?.value || '3 months';
    fd.append('caption', `${story} (${period})`);
    if (validSrc(beforeSrc)) fd.append('before_img', beforeSrc);
    fd.append('after_img', afterSrc);

  } else if (type === 'review') {
    const reviewText = document.getElementById('review-text')?.value?.trim() || '';
    if (!reviewText) { showToast('⚠️', 'Please write your review before submitting.'); return; }
    const product = document.getElementById('rev-prod-search')?.value?.trim() || '';
    fd.append('product', product);
    fd.append('caption', reviewText);
    fd.append('quote',   reviewText);
    fd.append('stars',   String(currentStars || 4));
    const revPhotoSrc = document.getElementById('rev-photo-preview')?.src || '';
    const validSrcFn  = s => s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:');
    if (validSrcFn(revPhotoSrc)) fd.append('img', revPhotoSrc);

  } else if (type === 'routine') {
    const title = document.getElementById('routine-title-input')?.value?.trim() || 'My Skincare Routine';
    const desc  = document.getElementById('routine-desc')?.value?.trim() || '';
    fd.append('caption', desc ? `${title}: ${desc}` : title);
    // Routine type from active button
    const dashRtType = document.getElementById('rt-am')?.classList.contains('btn-dark') ? 'AM'
                     : document.getElementById('rt-pm')?.classList.contains('btn-dark') ? 'PM'
                     : document.getElementById('rt-weekly')?.classList.contains('btn-dark') ? 'Weekly' : 'AM';
    fd.append('routine_type', dashRtType);
    // Steps from step inputs
    const dashSteps = Array.from(document.querySelectorAll('#routine-steps input'))
      .map(i => i.value.trim()).filter(Boolean);
    if (dashSteps.length) fd.append('steps', JSON.stringify(dashSteps));
    const rtPhotoSrc = document.getElementById('routine-photo-preview')?.src || '';
    const validSrcFn = s => s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:');
    if (validSrcFn(rtPhotoSrc)) fd.append('img', rtPhotoSrc);
  }

  showToast('⏳', 'Submitting your post…');

  try {
    const resp = await fetch('{{ route("community.post") }}', {
      method: 'POST', body: fd, headers: { 'Accept': 'application/json' },
    });
    const data = await resp.json();

    if (data.success) {
      // Reset photo state for next submission
      if (type === 'photo') {
        uploadedPhotos.length = 0;
        const strip = document.getElementById('photo-preview-strip');
        if (strip) strip.innerHTML = '';
      }

      // Prepend to My Posts
      const p = data.post || {};
      gridShown = Math.max(gridShown, GRID_PAGE);
      feedShown = Math.max(feedShown, FEED_PAGE);
      MY_POSTS.unshift({
        id:           p.id           || '',
        type,
        status:       'approved',
        img:          p.img          || '',
        before_img:   p.before_img   || '',
        after_img:    p.after_img    || '',
        images:       p.images       || null,
        routine_type: p.routine_type || null,
        steps:        p.steps        || [],
        caption:      p.caption      || '',
        quote:        p.quote        || '',
        stars:        p.stars        || currentStars || 4,
        product:      p.product      || '',
        tags:         p.tags         || [],
        likes:        0,
        comments:     0,
        time:         'Just now',
      });
      updateHeroStats();
      renderMyPosts();

      // Store in localStorage so community page can display immediately
      const stored = JSON.parse(localStorage.getItem('kominhoo_community_posts') || '[]');
      stored.unshift({
        ...p,
        beforeImg: p.before_img || '',
        afterImg:  p.after_img  || '',
        commentList: [],
        user_liked: false,
      });
      localStorage.setItem('kominhoo_community_posts', JSON.stringify(stored.slice(0, 20)));

      showToast('✨', `${labels[type]}! +${pts[type]} pts — now live on the community page.`);
    } else {
      showToast('❌', data.message || 'Submission failed. Please try again.');
    }
  } catch (e) {
    showToast('❌', 'Could not submit. Check your connection and try again.');
  }
}

// — My Posts (loaded from API)
let MY_POSTS = [];
const GRID_PAGE = 9;
const FEED_PAGE = 5;
let gridShown = GRID_PAGE;
let feedShown = FEED_PAGE;

const PT_MAP = COMM_PTS;

function updateHeroStats() {
  const nonReview = MY_POSTS.filter(p => p.type !== 'review');
  const reviews   = MY_POSTS.filter(p => p.type === 'review');
  const totalLikes = MY_POSTS.reduce((s, p) => s + (parseInt(p.likes) || 0), 0);
  const totalPts   = MY_POSTS.reduce((s, p) => s + (PT_MAP[p.type] || 30), 0);

  const fmt = n => n >= 1000 ? (n / 1000).toFixed(1).replace(/\.0$/, '') + 'k' : String(n);

  const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  set('ch-posts',   nonReview.length);
  set('ch-likes',   fmt(totalLikes));
  set('ch-reviews', reviews.length);
  set('ch-pts',     '+' + totalPts);
}

async function loadMyPosts() {
  gridShown = GRID_PAGE;
  feedShown = FEED_PAGE;
  try {
    const resp = await fetch(DASH_POSTS_URL, { headers: { 'Accept': 'application/json' } });
    if (resp.ok) {
      const data = await resp.json();
      MY_POSTS = data.posts || [];
    }
  } catch(e) { MY_POSTS = []; }
  updateHeroStats();
  renderMyPosts();
}

function renderMyPosts() { renderMyPostsGrid(); renderMyPostsFeed(); }

function loadMoreMyPostsGrid() {
  gridShown += GRID_PAGE;
  renderMyPostsGrid();
}
function loadMoreMyPostsFeed() {
  feedShown += FEED_PAGE;
  renderMyPostsFeed();
}

function renderMyPostsGrid() {
  const grid = document.getElementById('my-posts-grid');
  if (!grid) return;
  const moreBtn = document.getElementById('my-posts-grid-more');
  if (!MY_POSTS.length) {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:48px 24px;color:rgba(255,255,255,.4);font-size:.9rem;">No posts yet.<br><span style="font-size:.78rem;">Share your first glow story using the composer above ✨</span></div>';
    if (moreBtn) moreBtn.style.display = 'none';
    return;
  }
  const visible = MY_POSTS.slice(0, gridShown);
  if (moreBtn) moreBtn.style.display = MY_POSTS.length > visible.length ? '' : 'none';
  const typeIcons = { photo:'📸', before_after:'✨', review:'⭐', routine:'🧴' };
  grid.innerHTML = visible.map(p => {
    const statusBadge = p.status !== 'approved'
      ? `<span style="position:absolute;top:8px;left:8px;background:${p.status==='pending'?'#F59E0B':'#E53E3E'};color:#fff;font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:999px;z-index:2">${p.status}</span>`
      : '';
    const pid = p.id || '';
    const validSrc = s => s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:'));
    if (p.type === 'review') {
      const sv = Math.min(Math.max(parseInt(p.stars) || 4, 0), 5);
      return `
      <div class="mpg-review-card" style="grid-column:span 2;position:relative">
        ${statusBadge}
        ${validSrc(p.img) ? `<img src="${p.img}" style="width:100%;border-radius:var(--r-md);margin-bottom:10px;max-height:160px;object-fit:cover;display:block">` : ''}
        <div class="mpg-rev-stars">${'★'.repeat(sv)}${'☆'.repeat(5-sv)}</div>
        <div class="mpg-rev-quote">${p.quote||p.caption||''}</div>
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
          <span style="background:var(--lime-pale);color:var(--lime-dark);padding:3px 10px;border-radius:var(--r-pill);font-size:.7rem;font-weight:700">🧴 ${p.product||'Product Review'}</span>
          <div style="display:flex;gap:12px">
            <span class="mpg-stat" style="font-size:.75rem;font-weight:600;color:rgba(255,255,255,.5);cursor:pointer" onclick="likeMyPost(this,'${pid}')">♥ ${(p.likes||0).toLocaleString()}</span>
            <span style="font-size:.75rem;font-weight:600;color:rgba(255,255,255,.5)">💬 ${p.comments||0}</span>
          </div>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px">
          <span style="font-size:.72rem;color:rgba(255,255,255,.25)">${p.time||'Recently'}</span>
          <button onclick="deleteMyPost('${pid}',this)" style="background:none;border:none;color:#fc8181;font-size:.72rem;cursor:pointer;font-weight:700;padding:0">🗑️ Delete</button>
        </div>
      </div>`;
    }
    const img = p.type === 'before_after' ? '' : (p.img || (p.images?.[0]) || '');
    const baAfter  = p.type === 'before_after' && validSrc(p.after_img)  ? p.after_img  : '';
    const baBefore = p.type === 'before_after' && validSrc(p.before_img) ? p.before_img : '';
    const baThumb  = baAfter || baBefore;
    return `
      <div class="mpg-card" style="position:relative">
        ${p.type === 'before_after'
          ? (baBefore && baAfter
            ? `<div style="display:grid;grid-template-columns:1fr 1fr;width:100%;aspect-ratio:1;overflow:hidden;border-radius:var(--r-md) var(--r-md) 0 0">
                <div style="position:relative;overflow:hidden"><img src="${baBefore}" style="width:100%;height:100%;object-fit:cover;display:block" loading="lazy"><span style="position:absolute;bottom:4px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.6);color:#fff;font-size:.5rem;font-weight:700;padding:2px 6px;border-radius:100px;white-space:nowrap">Before</span></div>
                <div style="position:relative;overflow:hidden"><img src="${baAfter}"  style="width:100%;height:100%;object-fit:cover;display:block" loading="lazy"><span style="position:absolute;bottom:4px;left:50%;transform:translateX(-50%);background:#D4D994;color:#1C1416;font-size:.5rem;font-weight:700;padding:2px 6px;border-radius:100px;white-space:nowrap">After</span></div>
              </div>`
            : (baThumb
              ? `<img src="${baThumb}" loading="lazy" alt="" style="width:100%;aspect-ratio:1;object-fit:cover;display:block">`
              : `<div style="width:100%;aspect-ratio:1;background:var(--lime-pale);display:grid;place-items:center;font-size:2rem">✨</div>`))
          : (validSrc(img)
            ? `<img src="${img}" loading="lazy" alt="">`
            : `<div style="width:100%;aspect-ratio:1;background:var(--lime-pale);display:grid;place-items:center;font-size:2rem">${typeIcons[p.type]||'📸'}</div>`)}
        <div class="mpg-overlay">
          <div class="mpg-stats">
            <span class="mpg-stat" onclick="likeMyPost(this,'${pid}')">♥ ${(p.likes||0).toLocaleString()}</span>
            <span class="mpg-stat">💬 ${p.comments||0}</span>
          </div>
          <button onclick="deleteMyPost('${pid}',event)" style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,.65);border:none;color:#fff;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:.8rem;display:grid;place-items:center">🗑️</button>
        </div>
        ${statusBadge}
        <span class="mpg-type-badge" style="background:var(--lime);color:var(--black)">${p.type||'photo'}</span>
      </div>`;
  }).join('');
}

function renderMyPostsFeed() {
  const feed = document.getElementById('my-posts-feed');
  if (!feed) return;
  const moreBtn = document.getElementById('my-posts-feed-more');
  if (!MY_POSTS.length) {
    feed.innerHTML = '<div style="text-align:center;padding:48px 24px;color:var(--text-muted);font-size:.9rem;">No posts yet. Share your first glow story ✨</div>';
    if (moreBtn) moreBtn.style.display = 'none';
    return;
  }
  const visibleFeed = MY_POSTS.slice(0, feedShown);
  if (moreBtn) moreBtn.style.display = MY_POSTS.length > visibleFeed.length ? '' : 'none';
  const typeLabels = { photo:'📸 Photo Post', before_after:'✨ Transformation', review:'⭐ Product Review', routine:'🧴 Skincare Routine' };
  const typeIcons  = { photo:'📸', before_after:'✨', review:'⭐', routine:'🧴' };
  feed.innerHTML = visibleFeed.map(p => {
    const pid = p.id || '';
    const validSrcFeed = s => !!(s && (s.startsWith('data:') || s.startsWith('http') || s.startsWith('blob:')));
    const feedImg = p.img || (p.type === 'before_after' ? (p.after_img || p.before_img || '') : '') || (p.images?.[0] || '');
    const hasImg = validSrcFeed(feedImg);
    const statusChip = p.status !== 'approved'
      ? `<span style="display:inline-block;background:${p.status==='pending'?'#FEF3C7':'#FEE2E2'};color:${p.status==='pending'?'#92400E':'#991B1B'};padding:2px 10px;border-radius:999px;font-size:.7rem;font-weight:700;margin-bottom:8px">${p.status==='pending'?'⏳ Pending':'🚫 Rejected'}</span>`
      : '';
    return `
    <div class="comm-feed-item">
      ${hasImg ? `<img class="cfi-img" src="${feedImg}" loading="lazy" alt="">` : `<div class="cfi-type-icon" style="background:var(--lime-pale)">${typeIcons[p.type]||'📸'}</div>`}
      <div class="cfi-body">
        <div class="cfi-meta">${typeLabels[p.type]||'📸 Post'} · ${p.time||'Recently'}</div>
        <div class="cfi-title">${p.type==='review' ? (p.product||'Product')+' Review' : (p.caption||'').substring(0,60)+((p.caption||'').length>60?'…':'')}</div>
        ${p.type==='review' ? `<div class="cfi-caption" style="font-style:italic">"${p.quote||p.caption||''}"</div>` : `<div class="cfi-caption">${p.caption||''}</div>`}
        ${statusChip}
        <div class="cfi-actions">
          <button class="cfi-action" onclick="likeMyPost(this,'${pid}')">♥ ${(p.likes||0).toLocaleString()}</button>
          <button class="cfi-action">💬 ${p.comments||0}</button>
          <button class="cfi-action" onclick="showToast('✓','Link copied!')">↗ Share</button>
          <button class="cfi-action" style="color:var(--red);margin-left:auto" onclick="deleteMyPost('${pid}',this)">🗑️ Delete</button>
        </div>
      </div>
    </div>`;
  }).join('');
}

function likeMyPost(el, id) {
  const liked = el.classList.toggle('liked');
  const match = el.textContent.match(/\d[\d,]*/);
  if (match) {
    let val = parseInt(match[0].replace(/,/g,''));
    el.textContent = el.textContent.replace(match[0], liked ? (val+1).toLocaleString() : (val-1).toLocaleString());
  }
}

async function deleteMyPost(id, elOrEvent) {
  if (elOrEvent && elOrEvent.stopPropagation) elOrEvent.stopPropagation();
  if (!id) { showToast('❌', 'Cannot delete this post.'); return; }
  if (!confirm('Delete this post permanently?')) return;
  try {
    const resp = await fetch(COMMUNITY_POST_URL + '/' + id, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
    });
    const data = await resp.json();
    if (data.success) {
      MY_POSTS = MY_POSTS.filter(p => p.id !== id);
      updateHeroStats();
      renderMyPosts();
      showToast('🗑️', 'Post deleted.');
    } else {
      showToast('❌', data.message || 'Could not delete post.');
    }
  } catch(e) {
    showToast('❌', 'Could not delete. Check your connection.');
  }
}
function setPostsView(view) {
  const gridView = document.getElementById('my-posts-grid-view');
  const feedView = document.getElementById('my-posts-feed-view');
  const gridBtn  = document.getElementById('view-grid-btn');
  const feedBtn  = document.getElementById('view-feed-btn');
  if (view === 'grid') {
    gridView.style.display = ''; feedView.style.display = 'none';
    gridBtn.classList.add('active'); feedBtn.classList.remove('active');
  } else {
    gridView.style.display = 'none'; feedView.style.display = '';
    feedBtn.classList.add('active'); gridBtn.classList.remove('active');
  }
}

// — Discover scroll
const DISCOVER_POSTS = [
  { img:'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=400&h=320&fit=crop', user:'Funmi A.', av:'FA', color:'#D4D994', textColor:'#1C1416', likes:847, id:100 },
  { img:'https://images.unsplash.com/photo-1503842217505-b0a15ec3261c?w=400&h=320&fit=crop', user:'Chisom N.', av:'CN', color:'#893941', textColor:'#fff', likes:1103, id:101 },
  { img:'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=320&fit=crop', user:'Ngozi E.', av:'NE', color:'#F59E0B', textColor:'#0A0A0A', likes:519, id:102 },
  { img:'https://images.unsplash.com/photo-1545208935-9a7b23524f41?w=400&h=320&fit=crop', user:'Amaka O.', av:'AO', color:'#22C55E', textColor:'#fff', likes:734, id:103 },
  { img:'https://images.unsplash.com/photo-1555487505-8603a1a69755?w=400&h=320&fit=crop', user:'Dami O.', av:'DO', color:'#8B5CF6', textColor:'#fff', likes:445, id:104 },
  { img:'https://images.unsplash.com/photo-1557053910-d9eadeed1c58?w=400&h=320&fit=crop', user:'Blessing U.', av:'BU', color:'#0EA5E9', textColor:'#fff', likes:1456, id:105 },
];
function renderDiscoverScroll() {
  const el = document.getElementById('discover-scroll');
  if (!el) return;
  el.innerHTML = DISCOVER_POSTS.map(p => `
    <div class="cds-card">
      <div class="cds-img"><img src="${p.img}" loading="lazy" alt=""></div>
      <div class="cds-body">
        <div class="cds-user"><div class="cds-av" style="background:${p.color};color:${p.textColor}">${p.av}</div><span style="font-size:.78rem;font-weight:700">${p.user}</span></div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <span class="cds-like" id="dlike-${p.id}" onclick="likeDiscover(this,${p.id})">♥ ${p.likes.toLocaleString()}</span>
          <a href="{{ route('community') }}" style="font-size:.72rem;color:var(--lime-dark);font-weight:700;text-decoration:none">View →</a>
        </div>
      </div>
    </div>`).join('');
}
function likeDiscover(el, id) {
  const liked = el.classList.toggle('liked');
  const match = el.textContent.match(/\d[\d,]*/);
  if (match) {
    let val = parseInt(match[0].replace(/,/g,''));
    el.textContent = '♥ ' + (liked ? (val+1).toLocaleString() : (val-1).toLocaleString());
  }
}

// — Selfie upload
function handleSelfieUpload(input) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = function(e) {
    const preview     = document.getElementById('selfie-preview');
    const placeholder = document.getElementById('selfie-placeholder');
    const badge       = document.getElementById('selfie-status-badge');
    if (preview)     { preview.src = e.target.result; preview.style.display = 'block'; }
    if (placeholder) placeholder.style.display = 'none';
    if (badge)       { badge.className = 'selfie-status selfie-pending'; badge.textContent = '↻ Uploaded — Pending Review'; }
    localStorage.setItem('kominhoo_selfie_status', 'pending');
    showToast('📸', "Selfie uploaded! We'll verify it within 24 hours.");
  };
  reader.readAsDataURL(input.files[0]);
}

// — Vouchers
// ── Voucher panel — dynamic loading ─────────────────────────────
function voucherDiscLabel(c) {
  if (c.discount_type === 'percentage')    return { big: c.discount_value + '%', sub: 'OFF' };
  if (c.discount_type === 'free_shipping') return { big: '🚚',                  sub: 'FREE SHIP' };
  const v = Number(c.discount_value);
  const fmt = v >= 1000 ? '₦' + (v/1000).toFixed(v % 1000 === 0 ? 0 : 1) + 'k' : '₦' + v;
  return { big: fmt, sub: 'OFF' };
}

function voucherExpiryText(c) {
  if (!c.expiry_date) return 'No expiry date';
  const d = new Date(c.expiry_date + 'T00:00:00');
  return 'Expires ' + d.toLocaleDateString('en-NG', { day: 'numeric', month: 'long', year: 'numeric' });
}

function renderVouchers(coupons) {
  const grid    = document.getElementById('vouchers-grid');
  const subtext = document.getElementById('vouchers-subtext');
  const count   = coupons.length;

  // Update sidebar badge dynamically
  const navBadge = document.getElementById('voucher-nav-badge');
  if (navBadge) {
    if (count > 0) { navBadge.textContent = count; navBadge.style.display = ''; }
    else           { navBadge.style.display = 'none'; }
  }

  subtext.textContent = count > 0
    ? count + ' active voucher' + (count !== 1 ? 's' : '') + ' — save more on your next order'
    : 'No active promotions right now — check back soon';

  if (!count) {
    grid.innerHTML = `
      <div style="text-align:center;padding:64px 20px;background:var(--cream);border-radius:var(--r-xl);border:1.5px dashed var(--gray-200)">
        <div style="font-size:2.5rem;margin-bottom:14px">🏷️</div>
        <div style="font-size:1rem;font-weight:700;margin-bottom:6px">No vouchers available yet</div>
        <div style="font-size:.83rem;color:var(--text-muted)">New promotions will appear here when they go live.</div>
      </div>`;
    return;
  }

  grid.innerHTML = coupons.map(c => {
    const disc = voucherDiscLabel(c);
    const expText = voucherExpiryText(c);
    const minText = c.min_order > 0 ? 'Min. order ₦' + Number(c.min_order).toLocaleString() : 'All orders';
    const desc = c.description || (c.discount_type === 'free_shipping' ? 'Free shipping on your order' : disc.big + ' off your order');
    const shipBadge = (c.free_shipping && c.discount_type !== 'free_shipping')
      ? '<span style="font-size:.68rem;background:rgba(79,148,234,.1);color:#1a4f9e;padding:2px 8px;border-radius:20px;font-weight:700;margin-left:6px">+ Free shipping</span>' : '';
    return `
    <div class="voucher-card">
      <div class="voucher-left">
        <div class="voucher-disc">${disc.big}</div>
        <div class="voucher-disc-sub">${disc.sub}</div>
        <div class="voucher-code">${c.code}</div>
      </div>
      <div class="voucher-right">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap">
          <span class="voucher-badge voucher-active">● Active</span>
          <span style="font-size:.73rem;color:var(--text-muted)">${minText}</span>
          ${shipBadge}
        </div>
        <div style="font-size:.97rem;font-weight:700;margin-bottom:4px">${desc}</div>
        <div class="voucher-expiry">${expText}</div>
        <div style="display:flex;gap:10px;margin-top:14px;align-items:center;flex-wrap:wrap">
          <button class="btn btn-primary btn-sm" onclick="copyVoucherCode('${c.code}', this)">Copy Code</button>
          <a href="{{ route('checkout') }}" class="btn btn-outline btn-sm" onclick="saveVoucherForCheckout('${c.code}')">Use at Checkout →</a>
        </div>
      </div>
    </div>`;
  }).join('');
}

async function loadVouchers() {
  try {
    const res  = await fetch('{{ route("vouchers.list") }}', { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    if (data.success) renderVouchers(data.data || []);
    else renderVouchers([]);
  } catch {
    const grid = document.getElementById('vouchers-grid');
    if (grid) grid.innerHTML = '<div style="text-align:center;padding:40px;color:var(--text-muted);font-size:.85rem;">Could not load vouchers. Please refresh.</div>';
  }
}

function copyVoucherCode(code, btn) {
  navigator.clipboard?.writeText(code).catch(() => {});
  showToast('📋', 'Code ' + code + ' copied to clipboard!');
  const orig = btn.textContent;
  btn.textContent = '✓ Copied!';
  setTimeout(() => btn.textContent = orig, 2000);
}

function saveVoucherForCheckout(code) {
  localStorage.setItem('kominhoo_pending_coupon', code);
}

async function dashApplyCoupon() {
  const inp = document.getElementById('dash-coupon-input');
  const btn = document.getElementById('dash-coupon-btn');
  const msg = document.getElementById('dash-coupon-msg');
  const code = (inp?.value || '').trim().toUpperCase();
  if (!code) {
    inp.style.borderColor = 'var(--red)';
    setTimeout(() => inp.style.borderColor = '', 1500);
    return;
  }
  btn.disabled = true; btn.textContent = '…';
  msg.style.display = 'none';

  try {
    const res  = await fetch('{{ route("checkout.promo") }}', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body:    JSON.stringify({ code, order_total: 9999999 }),
    });
    const data = await res.json();
    if (data.success) {
      localStorage.setItem('kominhoo_pending_coupon', data.data.coupon_code || code);
      msg.style.cssText = 'display:block;color:#15803d;font-weight:600';
      msg.innerHTML = '✓ Code saved! It will be auto-applied when you go to checkout. <a href="{{ route("checkout") }}" style="color:#15803d;font-weight:700;text-decoration:underline">Checkout now →</a>';
      inp.value = '';
      await loadVouchers();
    } else {
      msg.style.cssText = 'display:block;color:var(--red)';
      msg.textContent = '✕ ' + (data.message || 'Invalid or expired code.');
      inp.style.borderColor = 'var(--red)';
      setTimeout(() => inp.style.borderColor = '', 2000);
    }
  } catch {
    msg.style.cssText = 'display:block;color:var(--red)';
    msg.textContent = '✕ Network error. Please try again.';
  }
  btn.disabled = false; btn.textContent = 'Apply Code';
}

// Load vouchers when the panel becomes visible
document.addEventListener('DOMContentLoaded', () => {
  const voucherPanel = document.getElementById('panel-vouchers');
  if (voucherPanel) {
    const observer = new MutationObserver(() => {
      if (voucherPanel.classList.contains('active')) loadVouchers();
    });
    observer.observe(voucherPanel, { attributes: true, attributeFilter: ['class'] });
    if (voucherPanel.classList.contains('active')) loadVouchers();
  }
});

// ─── Membership: Loyalty ────────────────────────────────────────────────────
function openRedeemModal() {
  const modal = document.getElementById('redeem-modal');
  if (modal) modal.style.display = 'flex';
}
function claimTierGift() {
  showToast('🎁', 'Your tier gift is being prepared! Our team will contact you within 24 hours.');
}
function loadMorePointEvents() {
  // AJAX load next page of events
  const list = document.getElementById('points-activity-list');
  const page = (list?.dataset.page || 1) * 1 + 1;
  fetch(`/dashboard/membership/loyalty/events?per_page=10&page=${page}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(data => {
      const events = data?.data?.data || data?.data || [];
      if (!events.length) { showToast('ℹ️', 'No more events to load.'); return; }
      if (list) {
        list.dataset.page = page;
        events.forEach(evt => {
          const pts    = evt.points || 0;
          const cls    = pts >= 0 ? 'points-earn' : 'points-spend';
          const sign   = pts >= 0 ? '+' : '';
          const date   = evt.created_at ? new Date(evt.created_at).toLocaleDateString('en-NG',{year:'numeric',month:'long',day:'numeric'}) : '';
          const row    = document.createElement('div');
          row.className = 'points-row';
          row.innerHTML = `<div><div style="font-weight:700">${evt.note || (evt.event_type||'').replace(/_/g,' ')}</div><div style="font-size:.78rem;color:var(--text-muted)">${date}</div></div><div class="${cls}">${sign}${pts.toLocaleString()} pts</div>`;
          list.appendChild(row);
        });
      }
    }).catch(() => showToast('⚠️', 'Could not load more events.'));
}
function submitRedeem() {
  const pts = parseInt(document.getElementById('redeem-pts-input')?.value || 0);
  if (!pts || pts < 100) { showToast('⚠️', 'Minimum redemption is 100 points.'); return; }
  fetch('/dashboard/membership/loyalty/redeem', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify({ points: pts }),
  }).then(r => r.json()).then(d => {
    if (d.success || d.data) {
      showToast('🌟', `${pts} points redeemed successfully!`);
      document.getElementById('redeem-modal').style.display = 'none';
      setTimeout(() => location.reload(), 1500);
    } else {
      showToast('⚠️', d.message || 'Could not redeem points.');
    }
  }).catch(() => showToast('⚠️', 'Request failed. Please try again.'));
}

// ─── Membership: Subscription ────────────────────────────────────────────────
function updateSubscription(id, action) {
  fetch(`/dashboard/membership/subscription/${id}`, {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify({ action }),
  }).then(r => r.json()).then(d => {
    if (d.success || d.data) {
      const msgs = { pause: '⏸ Subscription paused.', resume: '▶ Subscription resumed!', cancel: 'Subscription cancelled.' };
      showToast('✓', msgs[action] || 'Subscription updated.');
      setTimeout(() => location.reload(), 1500);
    } else {
      showToast('⚠️', d.message || 'Could not update subscription.');
    }
  }).catch(() => showToast('⚠️', 'Request failed.'));
}
function subscribeToPlan(planId, planName, price, cycle) {
  if (!confirm(`Subscribe to ${planName} for ₦${parseInt(price).toLocaleString()}/${cycle}?`)) return;
  fetch('/dashboard/membership/subscription', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    body: JSON.stringify({ plan_id: planId, plan_name: planName, plan_price: price, billing_cycle: cycle }),
  }).then(r => r.json()).then(d => {
    if (d.success || d.data) {
      showToast('📬', `You're subscribed to ${planName}! Welcome to the box club.`);
      setTimeout(() => location.reload(), 1800);
    } else {
      showToast('⚠️', d.message || 'Could not subscribe. Please try again.');
    }
  }).catch(() => showToast('⚠️', 'Request failed.'));
}

// ─── Membership: Notifications ───────────────────────────────────────────────
function readNotification(el) {
  if (!el || el.classList.contains('read')) return;
  el.classList.add('read');
  const dot = el.querySelector('.notification-dot');
  if (dot) dot.style.background = 'var(--border)';
  const id = el.dataset.id;
  if (id) {
    fetch(`/dashboard/membership/notifications/${id}/read`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    });
  }
  updateNotifBadge(-1);
}
function deleteNotification(el) {
  if (!el) return;
  const id  = el.dataset.id;
  const wasUnread = !el.classList.contains('read');
  el.style.opacity = '0';
  el.style.transition = 'opacity .25s';
  setTimeout(() => el.remove(), 300);
  if (wasUnread) updateNotifBadge(-1);
  if (id) {
    fetch(`/dashboard/membership/notifications/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
    });
  }
}
function markAllNotifsRead() {
  document.querySelectorAll('.notification-item:not(.read)').forEach(el => {
    el.classList.add('read');
    const dot = el.querySelector('.notification-dot');
    if (dot) dot.style.background = 'var(--border)';
  });
  fetch('/dashboard/membership/notifications/read-all', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
  });
  const badge = document.getElementById('notif-badge-sidebar');
  if (badge) badge.remove();
  const sub = document.getElementById('notif-subtext');
  if (sub) sub.textContent = 'All caught up! No unread notifications.';
}
function updateNotifBadge(delta) {
  const badge = document.getElementById('notif-badge-sidebar');
  if (!badge) return;
  const current = parseInt(badge.textContent || '0') + delta;
  if (current <= 0) { badge.remove(); }
  else { badge.textContent = current; }
}
function loadNotifications() {
  fetch('/dashboard/membership/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(data => {
      const items = data?.data?.data || data?.data || [];
      const list  = document.getElementById('notif-list');
      if (!list || !items.length) { showToast('ℹ️', 'No new notifications.'); return; }
      const typeIcons = { tier_upgrade:'🏆', order:'📦', subscription:'📬', referral:'👥', promotion:'🎁', system:'📢', gift:'🎁' };
      list.innerHTML = items.map(n => {
        const icon   = typeIcons[n.type] || '🔔';
        const isRead = n.is_read;
        const time   = n.created_at ? new Date(n.created_at).toLocaleDateString('en-NG',{month:'short',day:'numeric'}) : '';
        return `<div class="notification-item${isRead?' read':''}" data-id="${n.id}" onclick="readNotification(this)">
          <div class="notification-dot"></div>
          <div style="flex:1"><div style="font-weight:700;font-size:.92rem;margin-bottom:4px">${icon} ${n.title}</div>
          <div style="font-size:.82rem;color:var(--text-secondary)">${n.message}</div>
          <div style="font-size:.75rem;color:var(--text-muted);margin-top:6px">${time}</div></div>
          <button class="btn btn-ghost btn-sm" style="flex-shrink:0;font-size:.7rem;padding:3px 8px" onclick="event.stopPropagation();deleteNotification(this.closest('.notification-item'))">✕</button>
        </div>`;
      }).join('');
      showToast('🔔', 'Notifications refreshed.');
    }).catch(() => showToast('⚠️', 'Could not refresh notifications.'));
}

// — Referral
function copyRefLink() {
  const text = document.getElementById('ref-link-text')?.textContent || '';
  navigator.clipboard?.writeText(text).catch(() => {});
  showToast('📋', 'Referral link copied!');
}
function shareRefLink() {
  const code = document.getElementById('ref-code-display')?.textContent?.trim() || '';
  const link = document.getElementById('ref-link-text')?.textContent?.trim() || '';
  const text = `Shop K-beauty in Nigeria with me! Use my code ${code} at Kominhoo Beauty — ${link}`;
  if (navigator.share) {
    navigator.share({ title: 'Join me on Kominhoo Beauty', text }).catch(() => {});
  } else {
    navigator.clipboard?.writeText(text).catch(() => {});
    showToast('✓', 'Share text copied to clipboard!');
  }
}

// — Routine Tracker
let _rtSteps   = { am: [], pm: [] };
let _rtDone    = { am: false, pm: false };
let _rtChecked = { am: [], pm: [] };
let _rtCurrentTab = 'am';

function initRoutineTracker() {
  fetch('{{ route("dashboard.routine.data") }}')
    .then(r => r.json())
    .then(json => {
      const d = json.data || {};
      _rtSteps = { am: d.am || [], pm: d.pm || [] };

      // Restore today's checked steps from server
      if (d.today) {
        _rtChecked.am = d.today.am_steps || [];
        _rtChecked.pm = d.today.pm_steps || [];
        _rtDone.am    = !!d.today.am_done;
        _rtDone.pm    = !!d.today.pm_done;
      }

      renderRTChecklist('am');
      renderRTChecklist('pm');
      switchRoutineTab(_rtCurrentTab);

      // Week grid
      buildRoutineWeekFromData(d.week_grid || []);

      // Streak + week pts
      const streak = d.streak || 0;
      document.getElementById('streak-badge').textContent = streak > 0 ? `🔥 ${streak}-day streak` : 'No streak yet';
      document.getElementById('streak-pts-display').textContent = `+${d.week_pts || 0} pts`;

      // Monthly stats
      const m = d.month || {};
      document.getElementById('rt-month-name').textContent      = m.name        || '—';
      document.getElementById('rt-days-logged').textContent     = m.days_logged != null ? `${m.days_logged}/${m.days_elapsed}` : '—';
      document.getElementById('rt-completion-rate').textContent = m.completion_rate != null ? `${m.completion_rate}%` : '—';
      document.getElementById('rt-month-pts').textContent       = m.pts_earned != null ? `${m.pts_earned} pts` : '—';
      document.getElementById('rt-streak-stat').textContent     = streak > 0 ? `${streak} days` : '0 days';
    })
    .catch(() => {
      // If backend is down, render defaults so the UI is usable
      _rtSteps = {
        am: [
          { id:'am_1', label:'Double Cleanse (oil + foam)', pts:1 },
          { id:'am_2', label:'BHA / Exfoliant Toner',       pts:1 },
          { id:'am_3', label:'Niacinamide Serum',           pts:1 },
          { id:'am_4', label:'Hyaluronic Acid',             pts:1 },
          { id:'am_5', label:'Lightweight Moisturiser',     pts:1 },
          { id:'am_6', label:'SPF 50+ Sunscreen',           pts:2 },
        ],
        pm: [
          { id:'pm_1', label:'Oil Cleanser',              pts:1 },
          { id:'pm_2', label:'Foam Cleanser',             pts:1 },
          { id:'pm_3', label:'BHA Treatment (2–3×/week)', pts:1 },
          { id:'pm_4', label:'Snail Mucin Essence',       pts:1 },
          { id:'pm_5', label:'Night Moisturiser',         pts:1 },
          { id:'pm_6', label:'Laneige Lip Sleeping Mask', pts:1 },
        ],
      };
      renderRTChecklist('am');
      renderRTChecklist('pm');
      switchRoutineTab('am');
      buildRoutineWeekFromData([]);
    });
}

function renderRTChecklist(tab) {
  const container = document.getElementById(`routine-checklist-${tab}`);
  if (!container) return;
  const steps   = _rtSteps[tab] || [];
  const checked = _rtChecked[tab] || [];
  const isDone  = _rtDone[tab];
  container.innerHTML = steps.map(step => {
    const isChecked = checked.includes(step.id) || isDone;
    return `<div class="rt-step-row${isChecked ? ' checked' : ''}" data-id="${step.id}" data-tab="${tab}" onclick="toggleRTStep(this)">
      <div class="rt-check">${isChecked ? '✓' : ''}</div>
      <div class="rt-step-label">${step.label}</div>
      <span class="rt-step-pts">+${step.pts} pt${step.pts > 1 ? 's' : ''}</span>
    </div>`;
  }).join('');
}

function buildRoutineWeekFromData(weekGrid) {
  const grid = document.getElementById('routine-week-grid');
  if (!grid) return;
  if (!weekGrid.length) {
    // Fallback: current week without state
    const today = new Date();
    const mon   = new Date(today);
    mon.setDate(today.getDate() - ((today.getDay() + 6) % 7));
    const days  = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    grid.innerHTML = days.map((d, i) => {
      const date  = new Date(mon); date.setDate(mon.getDate() + i);
      const isTod = date.toDateString() === today.toDateString();
      const cls   = 'routine-day' + (isTod ? ' today' : '');
      return `<div class="${cls}"><div class="routine-day-lbl">${d}</div><div class="routine-day-num">${date.getDate()}</div><div class="routine-day-icon">${isTod ? '★' : ''}</div></div>`;
    }).join('');
    return;
  }
  grid.innerHTML = weekGrid.map(day => {
    let cls  = 'routine-day';
    let icon = '';
    if (day.is_done)        { cls += ' done';   icon = '✓'; }
    else if (day.is_today)  { cls += ' today';  icon = '★'; }
    else if (day.is_missed) { cls += ' missed'; icon = '✕'; }
    return `<div class="${cls}"><div class="routine-day-lbl">${day.day}</div><div class="routine-day-num">${day.num}</div><div class="routine-day-icon">${icon}</div></div>`;
  }).join('');
}

function switchRoutineTab(tab) {
  _rtCurrentTab = tab;
  document.getElementById('routine-checklist-am').style.display = tab === 'am' ? '' : 'none';
  document.getElementById('routine-checklist-pm').style.display = tab === 'pm' ? '' : 'none';
  document.getElementById('rt-am-tab').className = tab === 'am' ? 'btn btn-dark btn-sm' : 'btn btn-outline btn-sm';
  document.getElementById('rt-pm-tab').className = tab === 'pm' ? 'btn btn-dark btn-sm' : 'btn btn-outline btn-sm';
  updateRTProgress(tab);
}

function toggleRTStep(row) {
  if (_rtDone[_rtCurrentTab]) return; // already fully logged
  row.classList.toggle('checked');
  const check  = row.querySelector('.rt-check');
  const stepId = row.dataset.id;
  if (check) check.textContent = row.classList.contains('checked') ? '✓' : '';

  // Sync to in-memory checked list
  const tab     = _rtCurrentTab;
  const checked = _rtChecked[tab];
  if (row.classList.contains('checked')) {
    if (!checked.includes(stepId)) checked.push(stepId);
  } else {
    const idx = checked.indexOf(stepId);
    if (idx > -1) checked.splice(idx, 1);
  }
  updateRTProgress(tab);
}

function updateRTProgress(tab) {
  const listId = `routine-checklist-${tab}`;
  const total  = (_rtSteps[tab] || []).length;
  const done   = (_rtChecked[tab] || []).length;
  const txt    = document.getElementById('rt-progress-text');
  if (!txt) return;
  if (_rtDone[tab]) {
    txt.textContent = `✓ ${tab.toUpperCase()} routine logged for today`;
  } else {
    txt.textContent = `${done} / ${total} steps done today`;
  }
  // Update button label
  const btn = document.getElementById('rt-mark-done-btn');
  if (btn) {
    const pts = (_rtSteps[tab] || []).reduce((s, st) => (_rtChecked[tab] || []).includes(st.id) ? s + st.pts : s, 0);
    const bonus = (_rtChecked[tab] || []).length >= total ? 5 : 0;
    btn.textContent = _rtDone[tab] ? '✓ Logged' : `✓ Mark ${tab.toUpperCase()} Done → +${pts + bonus} pts`;
    btn.disabled    = _rtDone[tab];
  }
}

function markRoutineDone() {
  const tab     = _rtCurrentTab;
  const checked = _rtChecked[tab] || [];
  if (_rtDone[tab]) return;

  const btn = document.getElementById('rt-mark-done-btn');
  if (btn) btn.disabled = true;

  fetch('{{ route("dashboard.routine.log") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
    },
    body: JSON.stringify({ tab, steps: checked, mark_done: true }),
  })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        _rtDone[tab] = true;
        const pts    = (json.data?.pts_earned || 0);
        if (json.data?.already_done) {
          showToast('ℹ️', `${tab.toUpperCase()} routine already logged for today!`);
        } else {
          showToast('✓', `${tab.toUpperCase()} routine logged! +${pts} pts earned. Keep the streak going! 🔥`);
          // Mark today in week grid
          const todayEl = document.querySelector('.routine-day.today');
          if (todayEl) { todayEl.classList.add('done'); const ic = todayEl.querySelector('.routine-day-icon'); if (ic) ic.textContent = '✓'; }
          // Refresh stats silently
          setTimeout(initRoutineTracker, 800);
        }
        updateRTProgress(tab);
      } else {
        if (btn) btn.disabled = false;
        showToast('⚠️', json.message || 'Could not save routine. Try again.');
      }
    })
    .catch(() => {
      if (btn) btn.disabled = false;
      showToast('⚠️', 'Could not reach server. Check your connection.');
    });
}

// — Security
const SEC_SETTINGS_URL = '{{ route("dashboard.security.settings") }}';
const SEC_CSRF = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

function saveSecuritySetting(key, btn, label) {
  btn.classList.toggle('on');
  const isOn = btn.classList.contains('on');

  // Collect all current toggle states to send together
  const payload = new URLSearchParams();
  payload.set('_token', SEC_CSRF());
  payload.set('changed_setting', label);
  ['two_factor','login_notifications','sms_alerts','save_sessions'].forEach(k => {
    const el = document.getElementById('toggle-' + k.replace(/_/g, '').replace('twofactor','2fa').replace('loginnotifications','loginnotif').replace('smsalerts','sms').replace('savesessions','sessions'));
    if (el) payload.set(k, el.classList.contains('on') ? '1' : '0');
  });
  // Make sure the just-toggled key reflects the new state
  payload.set(key, isOn ? '1' : '0');

  fetch(SEC_SETTINGS_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json', 'X-CSRF-TOKEN': SEC_CSRF() },
    body: payload.toString(),
  })
  .then(r => r.json())
  .then(d => {
    if (d.success) showToast('🔐', `${label} ${isOn ? 'enabled' : 'disabled'}.`);
    else { btn.classList.toggle('on'); showToast('⚠️', 'Could not save setting. Try again.'); }
  })
  .catch(() => { btn.classList.toggle('on'); showToast('⚠️', 'Could not reach server.'); });
}
function checkPassStrength(val) {
  const bar  = document.getElementById('pass-strength-bar');
  const fill = document.getElementById('pass-strength-fill');
  const lbl  = document.getElementById('pass-strength-label');
  if (!bar || !fill || !lbl) return;
  if (!val) { bar.style.display = 'none'; return; }
  bar.style.display = '';
  let score = 0;
  if (val.length >= 8)          score++;
  if (/[A-Z]/.test(val))        score++;
  if (/[0-9]/.test(val))        score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const levels = [
    { w:'25%', bg:'#EF4444', t:'Weak' },
    { w:'50%', bg:'#F59E0B', t:'Fair' },
    { w:'75%', bg:'#3B82F6', t:'Good' },
    { w:'100%', bg:'#22C55E', t:'Strong ✓' },
  ];
  const lvl = levels[score - 1] || levels[0];
  fill.style.width = lvl.w; fill.style.background = lvl.bg;
  lbl.textContent = lvl.t; lbl.style.color = lvl.bg;
}

// — Gift Cards
let _gcDataLoaded = false;

function switchGcTab(tab) {
  ['buy','received','sent'].forEach(t => {
    const el  = document.getElementById('gc-tab-' + t);
    const btn = document.getElementById('gct-' + t);
    if (el)  el.style.display = t === tab ? 'block' : 'none';
    if (btn) btn.className = t === tab ? 'btn btn-dark btn-sm' : 'btn btn-ghost btn-sm';
  });
  if ((tab === 'received' || tab === 'sent') && !_gcDataLoaded) {
    _gcDataLoaded = true;
    loadDashGiftCards();
  }
}

async function loadDashGiftCards() {
  try {
    const res  = await fetch('{{ route("dashboard.gift-cards") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const data = await res.json();
    renderGcListV2('gc-received-list', data.received || [], 'received');
    renderGcListV2('gc-sent-list',     data.sent     || [], 'sent');
  } catch {
    ['gc-received-list','gc-sent-list'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.innerHTML = '<p style="color:var(--text-muted);text-align:center;padding:24px 0">Could not load gift cards.</p>';
    });
  }
}

function gcStatusTag(status, balance) {
  if (status === 'active')         return `<span style="background:rgba(34,197,94,.1);color:#16a34a;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px">Active · ₦${Number(balance).toLocaleString()} remaining</span>`;
  if (status === 'partially_used') return `<span style="background:rgba(139,92,246,.1);color:#5b21b6;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px">Partial · ₦${Number(balance).toLocaleString()} remaining</span>`;
  if (status === 'redeemed')       return `<span style="background:rgba(245,158,11,.1);color:#92400e;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px">Redeemed</span>`;
  return `<span style="background:#f3f4f6;color:#6b7280;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px">Expired</span>`;
}

const GC_THEME_POOL = ['minimal','luxe','birthday','celebration','romance','festive','tech'];

function gcThemeFromCode(code) {
  const str = String(code || '');
  let sum = 0;
  for (let i = 0; i < str.length; i++) sum += str.charCodeAt(i);
  return GC_THEME_POOL[sum % GC_THEME_POOL.length];
}

function dashCopyGc(code, btn) {
  navigator.clipboard.writeText(code).then(() => {
    if (!btn) return;
    const prev = btn.textContent;
    btn.textContent = 'Copied';
    btn.style.opacity = '.9';
    setTimeout(() => { btn.textContent = prev; btn.style.opacity = ''; }, 1400);
  });
}

function renderGcListV2(containerId, cards, type) {
  const el = document.getElementById(containerId);
  if (!el) return;

  if (!cards.length) {
    el.innerHTML = `<div style="text-align:center;padding:56px 0;color:var(--text-muted)">
      <div style="max-width:420px;margin:0 auto">
        <div class="kmh-gc-card is-compact" data-theme="minimal" style="margin:0 auto 16px;max-width:360px">
          <div class="kmh-gc-layer pattern"></div>
          <div class="kmh-gc-layer gloss"></div>
          <div class="kmh-gc-layer edge"></div>
          <div class="kmh-gc-body">
            <div class="kmh-gc-top">
              <div class="kmh-gc-brand">KOMINHOO<span class="dot">.</span></div>
              <div class="kmh-gc-badge"><span class="pill" aria-hidden="true"></span><span>Gift Card</span></div>
            </div>
            <div class="kmh-gc-mid">
              <div>
                <div class="kmh-gc-amount" style="font-size:1.6rem">₦ —</div>
                <div class="kmh-gc-msg">${type==='received'?'When someone sends you a gift card, it shows up here.':'Send a gift card in seconds — instant email delivery.'}</div>
              </div>
              <div class="kmh-gc-meta">
                <div class="kmh-gc-tofrom">To: <strong>—</strong><br><span style="opacity:.8">From: you</span></div>
              </div>
            </div>
            <div class="kmh-gc-bottom">
              <div class="kmh-gc-code"><span class="label">CODE</span><span>GC‑KMH‑••••</span></div>
              <div class="kmh-gc-details">Valid 12 months</div>
            </div>
          </div>
        </div>
        <div style="font-weight:700;margin-bottom:8px;font-size:.95rem">${type==='received'?'No gift cards received yet':'No gift cards sent yet'}</div>
        <div style="font-size:.82rem;line-height:1.6">${type==='received'?'Gift cards sent to you will appear here.':'<a href="'+GIFT_CARDS_URL+'" style="color:var(--lime-dark,#97b01e);font-weight:700">Send your first gift card →</a>'}</div>
      </div>
    </div>`;
    return;
  }

  const statusMap = {
    active:         { bg:'rgba(34,197,94,.10)',   color:'#16a34a', label:'Active' },
    partially_used: { bg:'rgba(139,92,246,.10)',  color:'#7c3aed', label:'Partial' },
    redeemed:       { bg:'rgba(245,158,11,.10)',  color:'#b45309', label:'Redeemed' },
    expired:        { bg:'rgba(148,163,184,.16)', color:'#64748b', label:'Expired' },
  };

  el.innerHTML = `<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px">
  ${cards.map((c) => {
    const theme = gcThemeFromCode(c.code || '');
    const s = statusMap[c.status] || statusMap.expired;
    const hasBalance = c.status === 'active' || c.status === 'partially_used';
    const who = type === 'received'
      ? `To: <strong>${(c.recipient_name || 'You')}</strong><br><span style="opacity:.8">From: ${(c.purchaser_name || 'Kominhoo')}</span>`
      : `To: <strong>${(c.recipient_name || '—')}</strong><br><span style="opacity:.8">From: you</span>`;
    const msg = (c.message || '').trim();
    const msgLine = msg ? `“${msg.length > 76 ? msg.substring(0, 76) + '…' : msg}”` : (type === 'received' ? 'A gift for your glow-up.' : 'Sent with love.');
    const exp = c.expires_at || '—';
    const amount = Number(c.amount || 0).toLocaleString();
    const code = String(c.code || '').toUpperCase();

    return `<div class="kmh-gc-card is-compact" data-theme="${theme}">
      <div class="kmh-gc-layer pattern"></div>
      <div class="kmh-gc-layer gloss"></div>
      <div class="kmh-gc-layer edge"></div>
      <div class="kmh-gc-body">
        <div class="kmh-gc-top">
          <div class="kmh-gc-brand">KOMINHOO<span class="dot">.</span></div>
          <div class="kmh-gc-badge" style="background:${s.bg};border-color:rgba(255,255,255,.10);color:${s.color}">
            <span class="pill" aria-hidden="true" style="background:currentColor;box-shadow:none"></span>
            <span>${s.label}${hasBalance ? ` · ₦${Number(c.balance || 0).toLocaleString()}` : ''}</span>
          </div>
        </div>
        <div class="kmh-gc-mid">
          <div>
            <div class="kmh-gc-amount">₦${amount}</div>
            <div class="kmh-gc-msg">${msgLine}</div>
          </div>
          <div class="kmh-gc-meta">
            <div class="kmh-gc-tofrom">${who}</div>
          </div>
        </div>
        <div class="kmh-gc-bottom">
          <button type="button" class="kmh-gc-code" onclick="dashCopyGc('${code}', this)" title="Copy gift card code">
            <span class="label">CODE</span><span>${code}</span>
          </button>
          <div class="kmh-gc-details">Exp ${exp}</div>
        </div>
      </div>
    </div>`;
  }).join('')}
  </div>`;
}

function renderGcList(containerId, cards, type) {
  const el = document.getElementById(containerId);
  if (!el) return;
  if (!cards.length) {
    el.innerHTML = `<div style="text-align:center;padding:56px 0;color:var(--text-muted)">
      <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(148deg,#0e2a14,#071a0c);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 18px;box-shadow:0 4px 16px rgba(0,0,0,.2);border:1px solid rgba(80,180,80,.1)">🎁</div>
      <div style="font-weight:700;margin-bottom:8px;font-size:.95rem">${type==='received'?'No gift cards received yet':'No gift cards sent yet'}</div>
      <div style="font-size:.82rem">${type==='received'?'Gift cards sent to you will appear here.':'<a href="'+GIFT_CARDS_URL+'" style="color:var(--lime-dark,#97b01e);font-weight:700">Send your first gift card →</a>'}</div>
    </div>`;
    return;
  }
  el.innerHTML = cards.map((c, i) => {
    const skin = GC_SKIN_CONFIGS[i % GC_SKIN_CONFIGS.length];
    const who  = type === 'received'
      ? `From <strong>${c.purchaser_name || 'Kominhoo'}</strong>${c.message ? ` · <em style="color:#6b7280">"${c.message.substring(0,55)}…"</em>` : ''}`
      : `To <strong>${c.recipient_name || '—'}</strong> · <span style="color:#9ca3af;font-size:.76rem">${c.recipient_email||''}</span>`;

    const statusMap = {
      active:         { bg:'rgba(34,197,94,.1)',   color:'#15803d', label:'Active' },
      partially_used: { bg:'rgba(139,92,246,.1)',  color:'#5b21b6', label:'Partial' },
      redeemed:       { bg:'rgba(245,158,11,.1)',  color:'#92400e', label:'Redeemed' },
      expired:        { bg:'rgba(107,114,128,.1)', color:'#6b7280', label:'Expired' },
    };
    const s = statusMap[c.status] || statusMap.expired;
    const hasBalance = c.status === 'active' || c.status === 'partially_used';

    return `<div style="background:#fff;border-radius:18px;border:1.5px solid var(--border,#ececec);overflow:hidden;margin-bottom:14px;transition:box-shadow .25s,transform .25s" onmouseover="this.style.boxShadow='0 6px 28px rgba(0,0,0,.09)';this.style.transform='translateY(-1px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
      <div style="display:flex;align-items:stretch">
        <div style="width:126px;flex-shrink:0;background:${skin.bg};position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center">
          <div style="position:absolute;top:-22px;right:-22px;width:72px;height:72px;border-radius:50%;background:radial-gradient(circle,${skin.orb} 0%,transparent 65%);pointer-events:none"></div>
          <div style="position:absolute;inset:0;background:radial-gradient(circle,${skin.dot} 1px,transparent 1px);background-size:13px 13px;pointer-events:none"></div>
          <div style="position:absolute;top:0;left:0;right:0;height:48%;background:linear-gradient(135deg,${skin.gloss} 0%,transparent 100%);pointer-events:none"></div>
          <div style="text-align:center;z-index:1;padding:14px 10px;color:${skin.color}">
            <div style="font-family:var(--font-display,serif);font-size:.55rem;letter-spacing:.12em;margin-bottom:8px;opacity:.38">KOMINHOO.</div>
            <div style="font-family:var(--font-display,serif);font-size:1.05rem;line-height:1;color:${skin.accent}">₦${Number(c.amount).toLocaleString()}</div>
            <div style="font-size:.42rem;font-weight:700;letter-spacing:.14em;margin-top:8px;opacity:.28">GIFT CARD</div>
          </div>
        </div>
        <div style="flex:1;padding:16px 18px;min-width:0">
          <div style="font-size:.92rem;font-weight:700;margin-bottom:4px">₦${Number(c.amount).toLocaleString()} Gift Card</div>
          <div style="font-size:.78rem;color:#6b7280;margin-bottom:10px;line-height:1.5">${who}</div>
          <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
            <span style="background:${s.bg};color:${s.color};font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:999px;letter-spacing:.04em">${s.label}${hasBalance ? ' · ₦'+Number(c.balance||0).toLocaleString() : ''}</span>
            <button onclick="navigator.clipboard.writeText('${c.code}').then(()=>{const b=this;b.textContent='✓ Copied!';b.style.color='#16a34a';b.style.background='rgba(34,197,94,.08)';setTimeout(()=>{b.textContent='${c.code}';b.style.color='';b.style.background='';},1800)})"
              style="font-family:'DM Sans',system-ui,sans-serif;font-size:.7rem;font-weight:700;color:#374151;background:#f4f6f8;border:1px solid #e4e8ec;border-radius:7px;padding:3px 9px;cursor:pointer;transition:all .15s;letter-spacing:.04em">${c.code}</button>
            <span style="font-size:.7rem;color:#9ca3af;margin-left:auto;white-space:nowrap">Exp ${c.expires_at||'—'}</span>
          </div>
        </div>
      </div>
    </div>`;
  }).join('');
}

function renderDashGcGrid() {} // no-op — buy tab redirects to /gift-cards
function loadMemberId() {
  const stored = localStorage.getItem('kominhoo_member_id');
  if (stored) {
    const el = document.getElementById('display-member-id');
    if (el) el.textContent = stored;
  }
}

// ── Order filter tabs ───────────────────────────────
function filterOrders(status) {
  document.querySelectorAll('.order-filter-btn').forEach(btn => {
    const isActive = btn.dataset.filter === status;
    btn.style.background    = isActive ? 'var(--black)' : '#fff';
    btn.style.color         = isActive ? '#fff' : '';
    btn.style.borderColor   = isActive ? 'var(--black)' : 'var(--border)';
  });
  document.querySelectorAll('.order-detail-card').forEach(card => {
    card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
  });
  const list = document.getElementById('orders-list');
  const visible = list && [...list.querySelectorAll('.order-detail-card')].filter(c => c.style.display !== 'none');
  let empty = document.getElementById('orders-empty-filter');
  if (visible && visible.length === 0) {
    if (!empty) {
      empty = document.createElement('div');
      empty.id = 'orders-empty-filter';
      empty.style.cssText = 'text-align:center;padding:40px 0;color:var(--text-muted);font-size:.9rem';
      empty.innerHTML = '<div style="font-size:2rem;margin-bottom:10px">🔍</div><p>No ' + status + ' orders found.</p>';
      list.appendChild(empty);
    }
    empty.style.display = '';
  } else if (empty) {
    empty.style.display = 'none';
  }
}

// ── Reorder ─────────────────────────────────────────
function reorderItems(itemNames) {
  showToast('🛒', 'Added ' + itemNames.length + ' item' + (itemNames.length !== 1 ? 's' : '') + ' to cart');
}

// ── Mobile Sidebar Toggle ─────────────────────────────────────────
(function() {
  const menuBtn  = document.getElementById('dashMenuBtn');
  const sidebar  = document.getElementById('dashSidebar');
  const overlay  = document.getElementById('dashSidebarOverlay');
  const titleEl  = document.getElementById('dashMobileTitle');
  if (!menuBtn || !sidebar || !overlay) return;

  const panelTitles = {
    'panel-home':       '🏠 Dashboard',
    'panel-profile':    '👤 My Profile',
    'panel-skin':       '🔬 Skin Profile',
    'panel-orders':     '📦 Orders',
    'panel-saved':      '♡ Saved Products',
    'panel-vouchers':   '🏷️ Vouchers & Coupons',
    'panel-giftcards':  '🎁 Gift Cards',
    'panel-wallet':     '💳 My Wallet',
    'panel-membership': '🪪 My Membership',
    'panel-loyalty':    '🌟 Loyalty & Points',
    'panel-sub':        '📬 Subscription',
    'panel-referral':   '👥 Referral Program',
    'panel-notif':      '🔔 Notifications',
    'panel-community':  '✨ My Community',
    'panel-routine':    '🧴 Routine Tracker',
    'panel-security':   '🔐 Security',
  };

  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('open');
    menuBtn.textContent = '✕';
    menuBtn.setAttribute('aria-label', 'Close menu');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
    menuBtn.textContent = '☰';
    menuBtn.setAttribute('aria-label', 'Open menu');
    document.body.style.overflow = '';
  }

  menuBtn.addEventListener('click', () =>
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar()
  );
  overlay.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

  // On nav item click: update mobile title + close sidebar
  document.querySelectorAll('.dash-nav-item[data-panel]').forEach(item => {
    item.addEventListener('click', () => {
      if (titleEl) titleEl.textContent = panelTitles[item.dataset.panel] || '🏠 Dashboard';
      if (window.innerWidth <= 1100) closeSidebar();
    });
  });

  // Reset on resize to desktop
  window.addEventListener('resize', () => {
    if (window.innerWidth > 1100) {
      closeSidebar();
      document.body.style.overflow = '';
    }
  });
})();
</script>
@endsection
