@extends('layouts.app')
@section('title', 'Gift Cards — Kominhoo Beauty')

@section('head')
<style>
/* ═══════════════════════════════════════════════════
   Gift Cards v2 — Light Catalog + Panel
   ═══════════════════════════════════════════════════ */

@keyframes gcShine   { 0%{transform:translateX(-130%) skewX(-15deg)} 50%,100%{transform:translateX(230%) skewX(-15deg)} }
@keyframes gcModalIn { from{opacity:0;transform:scale(.88) translateY(20px)} to{opacity:1;transform:none} }
@keyframes confettiDrop { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:translateY(50px)} }
@keyframes gcPulse   { 0%,100%{opacity:.6} 50%{opacity:1} }
@keyframes gcFloat   { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }

/* ── Page shell ── */
.gcv2 {
  background: var(--cream, #FAF6F3);
  min-height: calc(100vh - 64px);
  padding: 0 0 80px;
  font-family: var(--font-body, 'Cormorant Garamond', Georgia, serif);
}
.gcv2-inner { padding: 60px 0 0; }

/* ── Page header ── */
.gcv2-head {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
  margin-bottom: 44px;
  padding-bottom: 40px;
  border-bottom: 1px solid var(--border, #EDDCD8);
}
.gcv2-title {
  font-family: var(--font-display);
  font-size: clamp(2.8rem, 5.5vw, 5rem);
  font-weight: 300;
  line-height: 1.0;
  margin: 0 0 10px;
  background: linear-gradient(135deg, #893941 0%, #CB7885 55%, #B56070 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.gcv2-sub {
  font-size: 1.05rem;
  font-style: italic;
  color: var(--text-muted, #A08878);
  margin: 0;
}
.gcv2-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--rose-dark, #6B2A30);
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 12px 24px;
  font-size: .85rem;
  font-weight: 600;
  font-family: inherit;
  letter-spacing: .06em;
  text-transform: uppercase;
  cursor: pointer;
  white-space: nowrap;
  flex-shrink: 0;
  transition: background .2s, box-shadow .2s;
}
.gcv2-create-btn:hover {
  background: #521f23;
  box-shadow: 0 6px 24px rgba(137,57,65,.35);
}

/* ── Filter tabs ── */
.gcv2-filters {
  display: flex;
  gap: 0;
  flex-wrap: nowrap;
  overflow-x: auto;
  margin-bottom: 36px;
  padding-bottom: 0;
  border-bottom: 1.5px solid var(--border, #EDDCD8);
  scrollbar-width: none;
}
.gcv2-filters::-webkit-scrollbar { display: none; }
.gcf-tab {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 10px 18px;
  border-radius: 0;
  border: none;
  border-bottom: 2px solid transparent;
  background: none;
  color: var(--text-muted, #A08878);
  font-size: .72rem;
  font-weight: 600;
  font-family: inherit;
  letter-spacing: .08em;
  text-transform: uppercase;
  cursor: pointer;
  transition: color .18s, border-color .18s;
  white-space: nowrap;
  margin-bottom: -1.5px;
}
.gcf-tab:hover {
  background: none;
  color: var(--black, #1C1416);
  border-bottom-color: rgba(137,57,65,.3);
}
.gcf-tab.active {
  background: none;
  border-bottom-color: var(--rose, #893941);
  color: var(--rose-dark, #6B2A30);
  font-weight: 700;
}

/* ── Main two-column layout ── */
.gcv2-layout {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 32px;
  align-items: start;
}

/* ══════════════════════════════════
   CARD GRID (left column)
   ══════════════════════════════════ */
.gcv2-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}

/* ── Card thumbnail ── */
.gc-thumb {
  cursor: pointer;
  border-radius: 10px;
  overflow: hidden;
  border: 1.5px solid var(--border, #EDDCD8);
  transition: border-color .2s, box-shadow .2s;
  background: #fff;
}
.gc-thumb:hover { border-color: rgba(137,57,65,.4); box-shadow: 0 4px 24px rgba(137,57,65,.12); }
.gc-thumb.gc-selected {
  border-color: var(--rose, #893941);
  box-shadow: 0 0 0 1px rgba(137,57,65,.3), 0 6px 28px rgba(137,57,65,.18);
}
.gc-thumb[data-hidden] { display: none; }

/* ── Thumbnail visual area ── */
.gc-thumb-vis {
  position: relative;
  aspect-ratio: 4 / 3;
  overflow: hidden;
}

/* ── Card visual themes ── */
.gc-vis-birthday {
  background:
    radial-gradient(ellipse at 80% 15%, rgba(232,180,188,.6) 0%, transparent 45%),
    radial-gradient(ellipse at 20% 85%, rgba(137,57,65,.65) 0%, transparent 45%),
    radial-gradient(ellipse at 50% 50%, rgba(158,68,80,.3) 0%, transparent 70%),
    linear-gradient(135deg, #2a1215 0%, #6B2A30 40%, #893941 100%);
}
.gc-vis-birthday::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='280' height='210'%3E%3Ccircle cx='40' cy='80' r='28' fill='rgba(232,180,188,0.2)'/%3E%3Ccircle cx='80' cy='30' r='18' fill='rgba(203,120,133,0.18)'/%3E%3Ccircle cx='200' cy='55' r='32' fill='rgba(181,96,112,0.16)'/%3E%3Ccircle cx='170' cy='150' r='22' fill='rgba(232,180,188,0.14)'/%3E%3Ccircle cx='55' cy='170' r='26' fill='rgba(137,57,65,0.18)'/%3E%3Ccircle cx='245' cy='130' r='18' fill='rgba(158,68,80,0.18)'/%3E%3Crect x='120' y='20' width='5' height='35' rx='2.5' fill='rgba(255,255,255,0.12)' transform='rotate(20 120 20)'/%3E%3Crect x='230' y='60' width='4' height='28' rx='2' fill='rgba(255,255,255,0.1)' transform='rotate(-25 230 60)'/%3E%3Crect x='30' y='130' width='4' height='22' rx='2' fill='rgba(255,255,255,0.1)' transform='rotate(10 30 130)'/%3E%3Ccircle cx='140' cy='110' r='4' fill='rgba(255,255,255,0.25)'/%3E%3Ccircle cx='90' cy='130' r='3' fill='rgba(255,255,255,0.2)'/%3E%3Ccircle cx='210' cy='30' r='3.5' fill='rgba(255,255,255,0.2)'/%3E%3C/svg%3E");
  background-size: cover;
  pointer-events: none;
  z-index: 1;
}

.gc-vis-luxury {
  background: linear-gradient(148deg, #0a0a0a 0%, #1c1c1c 50%, #0d0d0d 100%);
}
.gc-vis-luxury::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse at 90% 10%, rgba(212,175,55,.45) 0%, transparent 50%),
    radial-gradient(ellipse at 10% 90%, rgba(212,175,55,.2) 0%, transparent 40%),
    repeating-linear-gradient(-55deg, transparent 0, transparent 12px, rgba(212,175,55,.04) 12px, rgba(212,175,55,.04) 13px);
  pointer-events: none;
  z-index: 1;
}
.gc-vis-luxury::after {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 45%;
  background: linear-gradient(140deg, rgba(255,255,255,.06) 0%, transparent 100%);
  pointer-events: none;
  z-index: 1;
}

.gc-vis-love {
  background:
    radial-gradient(ellipse at 75% 20%, rgba(251,113,133,.55) 0%, transparent 50%),
    radial-gradient(ellipse at 20% 80%, rgba(159,18,57,.65) 0%, transparent 50%),
    linear-gradient(135deg, #4c0519 0%, #881337 40%, #9f1239 100%);
}
.gc-vis-love::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='280' height='210'%3E%3Cpath d='M55 70 C55 61 44 52 33 61 C22 70 33 84 55 95 C77 84 88 70 77 61 C66 52 55 61 55 70Z' fill='rgba(251,113,133,0.2)'/%3E%3Cpath d='M195 42 C195 36 187 28 179 36 C171 44 179 55 195 63 C211 55 219 44 211 36 C203 28 195 36 195 42Z' fill='rgba(251,113,133,0.18)'/%3E%3Cpath d='M130 150 C130 146 125 141 120 146 C115 151 120 158 130 163 C140 158 145 151 140 146 C135 141 130 146 130 150Z' fill='rgba(251,113,133,0.22)'/%3E%3Ccircle cx='80' cy='160' r='5' fill='rgba(255,255,255,0.15)'/%3E%3Ccircle cx='220' cy='110' r='4' fill='rgba(255,255,255,0.12)'/%3E%3C/svg%3E");
  background-size: cover;
  pointer-events: none;
  z-index: 1;
}

.gc-vis-festive {
  background:
    radial-gradient(ellipse at 80% 20%, rgba(52,211,153,.35) 0%, transparent 50%),
    radial-gradient(ellipse at 20% 80%, rgba(6,78,59,.8) 0%, transparent 50%),
    linear-gradient(148deg, #022c22 0%, #064e3b 50%, #065f46 100%);
}
.gc-vis-festive::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='280' height='210'%3E%3Ccircle cx='60' cy='50' r='16' fill='none' stroke='rgba(52,211,153,0.25)' stroke-width='2'/%3E%3Ccircle cx='200' cy='90' r='22' fill='none' stroke='rgba(52,211,153,0.2)' stroke-width='2'/%3E%3Ccircle cx='100' cy='160' r='14' fill='none' stroke='rgba(52,211,153,0.22)' stroke-width='2'/%3E%3Ccircle cx='60' cy='50' r='6' fill='rgba(239,68,68,0.35)'/%3E%3Ccircle cx='200' cy='90' r='8' fill='rgba(251,191,36,0.3)'/%3E%3Ccircle cx='100' cy='160' r='5' fill='rgba(239,68,68,0.3)'/%3E%3Ccircle cx='240' cy='30' r='10' fill='rgba(239,68,68,0.25)'/%3E%3Ccircle cx='35' cy='140' r='8' fill='rgba(251,191,36,0.25)'/%3E%3Cline x1='60' y1='34' x2='60' y2='50' stroke='rgba(251,191,36,0.4)' stroke-width='2'/%3E%3Cline x1='200' y1='74' x2='200' y2='90' stroke='rgba(251,191,36,0.4)' stroke-width='2'/%3E%3Cline x1='100' y1='146' x2='100' y2='160' stroke='rgba(251,191,36,0.4)' stroke-width='2'/%3E%3C/svg%3E");
  background-size: cover;
  pointer-events: none;
  z-index: 1;
}

.gc-vis-celebration {
  background:
    radial-gradient(ellipse at 72% 22%, rgba(212,175,55,.55) 0%, transparent 50%),
    radial-gradient(ellipse at 25% 78%, rgba(94,102,35,.7) 0%, transparent 50%),
    linear-gradient(135deg, #1a1c05 0%, #3a4010 45%, #5e6623 100%);
}
.gc-vis-celebration::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='280' height='210'%3E%3Crect x='40' y='20' width='7' height='28' rx='3.5' fill='rgba(212,175,55,0.4)' transform='rotate(25 40 20)'/%3E%3Crect x='180' y='35' width='6' height='24' rx='3' fill='rgba(212,175,55,0.35)' transform='rotate(-30 180 35)'/%3E%3Crect x='100' y='100' width='5' height='20' rx='2.5' fill='rgba(200,230,52,0.3)' transform='rotate(15 100 100)'/%3E%3Crect x='230' y='120' width='7' height='26' rx='3.5' fill='rgba(212,175,55,0.3)' transform='rotate(-20 230 120)'/%3E%3Crect x='60' y='150' width='6' height='22' rx='3' fill='rgba(200,230,52,0.28)' transform='rotate(35 60 150)'/%3E%3Ccircle cx='70' cy='80' r='5' fill='rgba(212,175,55,0.45)'/%3E%3Ccircle cx='210' cy='25' r='4' fill='rgba(200,230,52,0.4)'/%3E%3Ccircle cx='155' cy='165' r='4.5' fill='rgba(212,175,55,0.38)'/%3E%3Ccircle cx='250' cy='75' r='3.5' fill='rgba(200,230,52,0.35)'/%3E%3C/svg%3E");
  background-size: cover;
  pointer-events: none;
  z-index: 1;
}

.gc-vis-custom {
  background:
    radial-gradient(ellipse at 78% 18%, rgba(180,96,112,.5) 0%, transparent 50%),
    radial-gradient(ellipse at 22% 82%, rgba(107,42,48,.6) 0%, transparent 50%),
    radial-gradient(ellipse at 50% 50%, rgba(137,57,65,.2) 0%, transparent 65%),
    linear-gradient(135deg, #2a1215 0%, #5a2228 45%, #7b3340 100%);
}
.gc-vis-custom::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='280' height='210'%3E%3Cline x1='35' y1='88' x2='245' y2='88' stroke='rgba(255,255,255,.07)' stroke-width='1' stroke-dasharray='4,5'/%3E%3Cline x1='35' y1='108' x2='210' y2='108' stroke='rgba(255,255,255,.07)' stroke-width='1' stroke-dasharray='4,5'/%3E%3Cline x1='35' y1='128' x2='230' y2='128' stroke='rgba(255,255,255,.06)' stroke-width='1' stroke-dasharray='4,5'/%3E%3Ccircle cx='218' cy='52' r='34' fill='none' stroke='rgba(203,120,133,.15)' stroke-width='1.5'/%3E%3Ccircle cx='218' cy='52' r='20' fill='rgba(180,96,112,.1)'/%3E%3Cpath d='M212 42 L224 42 L228 54 L208 54 Z' fill='rgba(255,255,255,.12)'/%3E%3Cline x1='213' y1='58' x2='227' y2='58' stroke='rgba(255,255,255,.18)' stroke-width='1.5' stroke-linecap='round'/%3E%3Ccircle cx='60' cy='160' r='10' fill='rgba(180,96,112,.15)'/%3E%3Ccircle cx='38' cy='62' r='16' fill='rgba(137,57,65,.1)'/%3E%3C/svg%3E");
  background-size: cover;
  pointer-events: none;
  z-index: 1;
}

/* ── Common card vis gloss ── */
.gc-thumb-vis::after {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 42%;
  background: linear-gradient(140deg, rgba(255,255,255,.14) 0%, rgba(255,255,255,.03) 55%, transparent 100%);
  pointer-events: none;
  z-index: 3;
}

/* Shimmer on hover */
.gc-thumb-vis::before { z-index: 1; } /* theme pattern is z:1 */
.gc-thumb-shimmer {
  position: absolute;
  inset: 0;
  background: linear-gradient(105deg, transparent 28%, rgba(255,255,255,.38) 50%, transparent 72%);
  transform: translateX(-130%) skewX(-15deg);
  pointer-events: none;
  z-index: 4;
  opacity: 0;
}
.gc-thumb:hover .gc-thumb-shimmer { opacity: 1; animation: gcShine .7s ease-out forwards; }

/* Text content on card */
.gc-thumb-txt {
  position: absolute;
  inset: 0;
  padding: 13px 14px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  z-index: 5;
  pointer-events: none;
}
.gc-thumb-logo {
  font-size: .52rem;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: rgba(255,255,255,.35);
}
.gc-thumb-headline {
  font-family: var(--font-display);
  font-size: 1rem;
  font-weight: 700;
  color: #fff;
  line-height: 1.2;
  text-shadow: 0 2px 10px rgba(0,0,0,.55);
}
.gc-vis-luxury .gc-thumb-headline {
  background: linear-gradient(90deg, #c8a94a, #f5d87a, #c8a94a);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: none;
}
.gc-thumb-amt-badge {
  display: inline-flex;
  align-items: center;
  background: rgba(0,0,0,.5);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,.15);
  border-radius: 10px;
  padding: 5px 12px;
  font-family: var(--font-display);
  font-size: 1.05rem;
  color: #fff;
  letter-spacing: -.01em;
  align-self: flex-start;
}

/* Check indicator */
.gc-thumb-check {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,.35);
  background: rgba(255,255,255,.08);
  backdrop-filter: blur(4px);
  z-index: 6;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all .2s;
}
.gc-thumb.gc-selected .gc-thumb-check {
  background: #893941;
  border-color: #893941;
  box-shadow: 0 0 0 2px rgba(137,57,65,.4);
}
.gc-thumb-check-icon {
  display: none;
  color: #fff;
  font-size: .6rem;
  font-weight: 700;
  line-height: 1;
}
.gc-thumb.gc-selected .gc-thumb-check-icon { display: block; }

/* ── Card info (below visual) ── */
.gc-thumb-info {
  background: #fff;
  padding: 12px 14px 14px;
  border-top: 1px solid var(--border, #EDDCD8);
}
.gc-thumb-name {
  font-family: var(--font-display);
  font-size: 1.05rem;
  font-weight: 400;
  font-style: italic;
  color: var(--black, #1C1416);
  margin-bottom: 6px;
  line-height: 1.2;
}
.gc-thumb-tagline { display: none; }
.gc-thumb-tag {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  font-size: .6rem;
  font-weight: 600;
  font-family: inherit;
  padding: 2px 8px;
  border-radius: 2px;
  letter-spacing: .06em;
  text-transform: uppercase;
}

/* Tag colors per category */
.gc-tag-birthday    { background: rgba(137,57,65,.1);  color: var(--rose-dark, #6B2A30); border: 1px solid rgba(137,57,65,.25); }
.gc-tag-luxury      { background: rgba(212,175,55,.1);  color: #7a5c10; border: 1px solid rgba(212,175,55,.3); }
.gc-tag-love        { background: rgba(181,96,112,.1);  color: var(--rose-dark, #6B2A30); border: 1px solid rgba(181,96,112,.25); }
.gc-tag-festive     { background: rgba(16,185,129,.1);  color: #065f46; border: 1px solid rgba(16,185,129,.25); }
.gc-tag-celebration { background: rgba(94,102,35,.1);   color: #3a4010; border: 1px solid rgba(94,102,35,.25); }
.gc-tag-custom      { background: rgba(137,57,65,.1);   color: var(--rose-dark, #6B2A30); border: 1px solid rgba(137,57,65,.25); }

/* ═══════════════════════════════════════
   UNIVERSAL CARD CTA
   ═══════════════════════════════════════ */
.gc-universal {
  margin-top: 28px;
  background: var(--blush-pale, #F5ECED);
  border: none;
  border-top: 1px solid var(--border, #EDDCD8);
  border-bottom: 1px solid var(--border, #EDDCD8);
  border-radius: 0;
  padding: 22px 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
}
.gc-universal-icon {
  font-size: 2rem;
  width: 52px;
  height: 52px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(137,57,65,.1);
  border: 1px solid rgba(137,57,65,.2);
  border-radius: 8px;
}
.gc-universal-body { flex: 1; min-width: 160px; }
.gc-universal-title {
  font-family: var(--font-display);
  font-size: 1.2rem;
  font-weight: 400;
  font-style: italic;
  color: var(--black, #1C1416);
  margin-bottom: 4px;
}
.gc-universal-desc  { font-size: .85rem; color: var(--text-muted, #A08878); line-height: 1.55; }
.gc-universal-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: var(--rose-dark, #6B2A30);
  border: none;
  border-radius: 4px;
  color: #fff;
  padding: 10px 20px;
  font-size: .72rem;
  font-weight: 600;
  font-family: inherit;
  letter-spacing: .07em;
  text-transform: uppercase;
  text-decoration: none;
  white-space: nowrap;
  flex-shrink: 0;
  cursor: pointer;
  transition: background .18s;
}
.gc-universal-btn:hover { background: #521f23; }

/* ═══════════════════════════════════════
   PREVIEW PANEL (right column)
   ═══════════════════════════════════════ */
.gcv2-panel {
  background: #fff;
  border-radius: 10px;
  border: 1px solid var(--border, #EDDCD8);
  overflow: hidden;
  position: sticky;
  top: 80px;
  box-shadow: 0 4px 24px rgba(0,0,0,.06);
}

/* Panel header */
.gcp-hdr {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid var(--border, #EDDCD8);
}
.gcp-hdr-lbl {
  font-family: var(--font-display);
  font-size: 1rem;
  font-weight: 400;
  font-style: italic;
  color: var(--text-muted, #A08878);
  letter-spacing: 0;
  text-transform: none;
}
.gcp-nav {
  display: flex;
  align-items: center;
  gap: 8px;
}
.gcp-nav-btn {
  width: 26px;
  height: 26px;
  border-radius: 3px;
  background: var(--cream, #FAF6F3);
  border: 1px solid var(--border, #EDDCD8);
  color: var(--text-muted, #A08878);
  font-size: .9rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .15s, color .15s, border-color .15s;
  padding: 0;
  line-height: 1;
}
.gcp-nav-btn:hover { background: var(--blush-pale, #F5ECED); color: var(--rose-dark, #6B2A30); border-color: rgba(137,57,65,.3); }
.gcp-nav-lbl {
  font-size: .68rem;
  letter-spacing: .06em;
  color: var(--text-muted, #A08878);
  min-width: 30px;
  text-align: center;
}

/* Large card preview in panel */
.gcp-preview {
  margin: 18px 20px;
  border-radius: 8px;
  overflow: hidden;
  aspect-ratio: 4 / 3;
  position: relative;
  box-shadow: 0 12px 40px rgba(0,0,0,.35);
}
/* panel preview text */
.gcp-preview .gc-thumb-txt { padding: 20px 22px; }
.gcp-preview .gc-thumb-headline { font-size: 1.5rem; }
.gcp-preview .gc-thumb-logo { font-size: .6rem; }
.gcp-preview .gc-thumb-amt-badge {
  font-size: 1.3rem;
  padding: 7px 16px;
}

/* Mode toggle */
.gcp-mode {
  display: flex;
  gap: 0;
  padding: 0 20px 16px;
  border-bottom: 1px solid var(--border, #EDDCD8);
}
.gcp-mode-btn {
  flex: 1;
  padding: 8px 10px;
  border-radius: 0;
  font-size: .68rem;
  font-weight: 600;
  font-family: inherit;
  letter-spacing: .07em;
  text-transform: uppercase;
  cursor: pointer;
  border: none;
  border-bottom: 2px solid transparent;
  background: transparent;
  color: var(--text-muted, #A08878);
  text-align: center;
  transition: all .18s;
  margin-bottom: -1px;
}
.gcp-mode-btn.active {
  border-bottom-color: var(--rose, #893941);
  color: var(--rose-dark, #6B2A30);
  font-weight: 700;
}
.gcp-mode-btn:hover:not(.active) {
  color: var(--black, #1C1416);
  border-bottom-color: rgba(137,57,65,.2);
}

/* Details list */
.gcp-details {
  padding: 4px 20px 0;
}
.gcp-row {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 11px 0;
  border-bottom: 1px solid rgba(0,0,0,.05);
}
.gcp-row:last-child { border-bottom: none; }
.gcp-row-icon { display: none; }
.gcp-row-label {
  font-size: .62rem;
  font-weight: 600;
  font-family: inherit;
  color: var(--text-muted, #A08878);
  min-width: 72px;
  flex-shrink: 0;
  padding-top: 4px;
  letter-spacing: .1em;
  text-transform: uppercase;
}
.gcp-row-val {
  font-size: .95rem;
  color: var(--black, #1C1416);
  flex: 1;
  line-height: 1.45;
}
.gcp-row-val.dim { color: var(--text-muted, #A08878); font-style: italic; }

/* Inline inputs in panel rows */
.gcp-inp {
  width: 100%;
  background: transparent;
  border: none;
  border-bottom: 1px solid var(--border, #EDDCD8);
  border-radius: 0;
  color: var(--black, #1C1416);
  font-size: .95rem;
  font-family: inherit;
  padding: 5px 0 6px;
  outline: none;
  transition: border-color .2s;
  margin-bottom: 8px;
  display: block;
}
.gcp-inp:last-child { margin-bottom: 0; }
.gcp-inp:focus { border-bottom-color: var(--rose, #893941); }
.gcp-inp::placeholder { color: rgba(160,136,120,.5); }
textarea.gcp-inp { resize: none; min-height: 52px; line-height: 1.6; }
.gcp-inp.err { border-bottom-color: rgba(239,68,68,.7); }

/* Amount pills */
.gcp-amt-pills { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px; }
.gcp-amt-pill {
  padding: 5px 12px;
  border-radius: 3px;
  border: 1px solid var(--border, #EDDCD8);
  background: #fff;
  color: var(--text-muted, #A08878);
  font-size: .78rem;
  font-weight: 600;
  font-family: inherit;
  letter-spacing: .02em;
  cursor: pointer;
  transition: all .15s;
  white-space: nowrap;
}
.gcp-amt-pill:hover { background: var(--blush-pale, #F5ECED); border-color: rgba(137,57,65,.35); color: var(--rose-dark, #6B2A30); }
.gcp-amt-pill.active { background: var(--rose-dark, #6B2A30); border-color: var(--rose-dark); color: #fff; }

/* Code box */
.gcp-code-box {
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--cream, #FAF6F3);
  border: 1px solid var(--border, #EDDCD8);
  border-radius: 4px;
  padding: 10px 14px;
  width: 100%;
}
.gcp-code-val {
  font-family: 'Courier New', monospace;
  font-size: .8rem;
  font-weight: 700;
  letter-spacing: .12em;
  color: var(--black, #1C1416);
  flex: 1;
}
.gcp-copy-btn {
  width: 28px; height: 28px;
  border-radius: 3px;
  background: #fff;
  border: 1px solid var(--border, #EDDCD8);
  color: var(--text-muted, #A08878);
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem;
  transition: all .15s;
  flex-shrink: 0;
}
.gcp-copy-btn:hover { background: var(--rose-dark, #6B2A30); border-color: var(--rose-dark); color: #fff; }

/* Self-note */
.gcp-self-note {
  background: rgba(16,185,129,.08);
  border: 1px solid rgba(16,185,129,.2);
  border-radius: 10px;
  padding: 10px 12px;
  font-size: .76rem;
  color: #065f46;
  line-height: 1.5;
  margin-top: 2px;
  display: none;
}

/* Error */
.gcp-error {
  background: rgba(239,68,68,.08);
  border: 1px solid rgba(239,68,68,.2);
  border-radius: 10px;
  padding: 10px 14px;
  font-size: .8rem;
  color: #B91C1C;
  margin: 4px 18px 8px;
  display: none;
}

/* Add to Cart button */
.gcp-buy-btn {
  margin: 16px 20px 20px;
  width: calc(100% - 40px);
  background: var(--rose-dark, #6B2A30);
  border: none;
  border-radius: 4px;
  color: #fff;
  padding: 15px;
  font-size: .8rem;
  font-weight: 600;
  font-family: inherit;
  letter-spacing: .1em;
  text-transform: uppercase;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background .2s, box-shadow .2s;
  box-shadow: 0 4px 20px rgba(137,57,65,.3);
}
.gcp-buy-btn:hover { background: #521f23; box-shadow: 0 6px 28px rgba(137,57,65,.45); }
.gcp-buy-btn:active { box-shadow: none; }
.gcp-buy-btn:disabled { opacity: .5; cursor: not-allowed; box-shadow: none; }

/* ═══════════════════════════════════════
   HOW IT WORKS
   ═══════════════════════════════════════ */
.gcv2-hiw { margin-top: 80px; padding-top: 60px; border-top: 1px solid var(--border, #EDDCD8); }
.gcv2-hiw-hdr { text-align: center; margin-bottom: 52px; }
.gcv2-hiw-lbl {
  font-size: .65rem;
  font-weight: 600;
  letter-spacing: .2em;
  text-transform: uppercase;
  color: var(--blush, #CB7885);
  margin-bottom: 12px;
}
.gcv2-hiw-title {
  font-family: var(--font-display);
  font-size: clamp(1.8rem, 3vw, 2.8rem);
  font-weight: 300;
  font-style: italic;
  color: var(--black, #1C1416);
  margin: 0;
}
.gcv2-hiw-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
}
.gcv2-hiw-step { text-align: center; position: relative; }
.gcv2-hiw-icon {
  width: 56px; height: 56px;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem;
  margin: 0 auto 20px;
  position: relative; overflow: hidden;
  background: var(--blush-pale, #F5ECED);
  border: 1px solid var(--border, #EDDCD8);
}
.gcv2-hiw-icon::before { display: none; }
.gcv2-hiw-icon-0, .gcv2-hiw-icon-1, .gcv2-hiw-icon-2 { background: var(--blush-pale, #F5ECED); border-color: var(--border, #EDDCD8); }
.gcv2-hiw-step-num {
  font-size: .6rem;
  font-weight: 600;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--blush, #CB7885);
  margin-bottom: 10px;
}
.gcv2-hiw-step-title {
  font-family: var(--font-display);
  font-size: 1.15rem;
  font-weight: 400;
  font-style: italic;
  color: var(--black, #1C1416);
  margin-bottom: 10px;
}
.gcv2-hiw-step-desc  { font-size: .88rem; color: var(--text-muted, #A08878); line-height: 1.7; }
.gcv2-hiw-connector {
  position: absolute;
  top: 28px;
  left: calc(50% + 38px);
  right: calc(-50% + 38px);
  height: 1px;
  background: var(--border, #EDDCD8);
}

/* ── Trust bar ── */
.gcv2-trust {
  background: var(--cream, #FAF6F3);
  border-top: 1px solid var(--border, #EDDCD8);
  padding: 28px 0;
  margin-top: 80px;
}
.gcv2-trust-inner {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  gap: 0;
}
.gcv2-trust-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: .68rem;
  font-weight: 600;
  letter-spacing: .07em;
  text-transform: uppercase;
  color: var(--text-muted, #A08878);
  padding: 0 20px;
}
.gcv2-trust-item + .gcv2-trust-item {
  border-left: 1px solid var(--border, #EDDCD8);
}
.gcv2-trust-dot { display: none; }

/* ── Success modal legacy styles ── */
.gc-code-box {
  background: #f4f6f8; border: 1.5px solid #e4e7eb; border-radius: 14px;
  padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;
  gap: 12px; margin-bottom: 16px;
}
.gc-code-val { font-family: 'Courier New', monospace; font-size: 1.2rem; font-weight: 700; letter-spacing: .14em; color: #0a0a0a; }
.gc-copy-btn-modal {
  background: #0a0a0a; color: #fff; border: none; border-radius: 10px;
  padding: 9px 16px; font-size: .8rem; font-weight: 700; cursor: pointer;
  white-space: nowrap; flex-shrink: 0; transition: background .15s;
}
.gc-copy-btn-modal:hover { background: #2a2a2a; }

/* ── Responsive ── */
@media (max-width: 960px) {
  .gcv2-layout { grid-template-columns: 1fr; }
  .gcv2-panel { position: static; margin-top: 16px; }
}
@media (max-width: 680px) {
  .gcv2-inner { padding-top: 36px; }
  .gcv2-head { margin-bottom: 28px; padding-bottom: 24px; }
  .gcv2-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
  .gcv2-hiw { display: none; }
  .gcv2-trust-item { padding: 6px 12px; }
}
@media (max-width: 400px) {
  .gcv2-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════
     SUCCESS MODAL
     ══════════════════════════════════════════════════ --}}
<div id="gcSuccessOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(8px)">
  <div style="background:#fff;border-radius:24px;padding:36px 32px;max-width:460px;width:100%;position:relative;animation:gcModalIn .4s cubic-bezier(.34,1.56,.64,1) both">

    {{-- Confetti --}}
    <div style="position:absolute;top:0;left:0;right:0;height:80px;overflow:hidden;border-radius:24px 24px 0 0;pointer-events:none" aria-hidden="true">
      <div style="position:absolute;top:12px;left:15%;width:8px;height:8px;background:#893941;border-radius:50%;animation:confettiDrop 1.2s .1s both"></div>
      <div style="position:absolute;top:8px;left:30%;width:6px;height:6px;background:#f43f5e;border-radius:2px;animation:confettiDrop 1s .2s both;transform:rotate(30deg)"></div>
      <div style="position:absolute;top:15px;left:55%;width:7px;height:7px;background:#CB7885;border-radius:50%;animation:confettiDrop 1.1s .15s both"></div>
      <div style="position:absolute;top:6px;left:70%;width:5px;height:5px;background:#6B2A30;border-radius:2px;animation:confettiDrop .9s .3s both;transform:rotate(-20deg)"></div>
      <div style="position:absolute;top:18px;left:85%;width:8px;height:8px;background:#f43f5e;border-radius:50%;animation:confettiDrop 1.3s .05s both"></div>
      <div style="position:absolute;top:10px;left:45%;width:6px;height:6px;background:#fbbf24;border-radius:2px;animation:confettiDrop 1s .25s both;transform:rotate(45deg)"></div>
    </div>

    <div style="text-align:center;margin-bottom:20px;margin-top:8px">
      <div style="font-size:2.8rem;margin-bottom:10px;animation:gcModalIn .5s .1s both">🎁</div>
      <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:6px;color:#0a0a0a" id="gcSuccessHeading">Gift Card Sent!</h2>
      <p style="font-size:.88rem;color:#6b7280" id="gcSuccessSubline">Delivered to <strong id="gcSuccessRecipient" style="color:#0a0a0a"></strong></p>
      <p style="font-size:.82rem;color:#9ca3af" id="gcSuccessEmail"></p>
    </div>

    <div class="gc-code-box">
      <div>
        <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;margin-bottom:4px">Gift Card Code</div>
        <div class="gc-code-val" id="gcSuccessCode">—</div>
      </div>
      <button class="gc-copy-btn-modal" onclick="copySuccessCode()" id="gcCopyBtn">Copy</button>
    </div>

    <div style="display:flex;gap:10px">
      <button onclick="document.getElementById('gcSuccessOverlay').style.display='none'" style="flex:1;background:#f4f5f7;border:none;border-radius:12px;padding:13px;font-size:.88rem;font-weight:700;cursor:pointer" onmouseover="this.style.background='#e8eaed'" onmouseout="this.style.background='#f4f5f7'">Close</button>
      <a href="{{ route('shop') }}" style="flex:1;background:#893941;color:#fff;border:none;border-radius:12px;padding:13px;font-size:.88rem;font-weight:700;cursor:pointer;text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center">Shop Now →</a>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════
     PAGE BODY
     ══════════════════════════════════════════════════ --}}
<div class="gcv2">

  @if(!session('api_token'))
  <div style="background:var(--blush-pale,#F5ECED);border-bottom:1px solid var(--border,#EDDCD8);padding:11px 0">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
      <span style="font-size:.82rem;font-style:italic;color:var(--text-muted,#A08878)">Sign in to purchase and track your gift cards.</span>
      <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" style="background:var(--rose-dark,#6B2A30);color:#fff;border-radius:3px;padding:7px 18px;font-size:.68rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;text-decoration:none;white-space:nowrap">Sign In</a>
    </div>
  </div>
  @endif

  <div class="gcv2-inner">
  <div class="container">

    {{-- Page header --}}
    <div class="gcv2-head">
      <div>
        <h1 class="gcv2-title">Gift Cards</h1>
        <p class="gcv2-sub">Send joy. Share moments. Make it unforgettable.</p>
      </div>
      <button class="gcv2-create-btn" onclick="openCustomCard()">
        <span>✏️</span> Create Custom Card
      </button>
    </div>

    {{-- Filter tabs --}}
    <div class="gcv2-filters" role="tablist" aria-label="Gift card categories">
      <button class="gcf-tab active" data-filter="all"         onclick="filterCards('all',this)"         role="tab">🎁 All Cards</button>
      <button class="gcf-tab"        data-filter="birthday"    onclick="filterCards('birthday',this)"    role="tab">🎂 Birthday</button>
      <button class="gcf-tab"        data-filter="celebration" onclick="filterCards('celebration',this)" role="tab">🎉 Celebration</button>
      <button class="gcf-tab"        data-filter="love"        onclick="filterCards('love',this)"        role="tab">❤️ Love</button>
      <button class="gcf-tab"        data-filter="festive"     onclick="filterCards('festive',this)"     role="tab">🎄 Festive</button>
      <button class="gcf-tab"        data-filter="custom"      onclick="filterCards('custom',this)"      role="tab">✏️ Custom</button>
      <button class="gcf-tab"        data-filter="luxury"      onclick="filterCards('luxury',this)"      role="tab">💎 Luxury</button>
    </div>

    {{-- Main layout --}}
    <div class="gcv2-layout">

      {{-- ──────── LEFT: Card grid ──────── --}}
      <div>
        @php
        $gcTemplates = [
          [
            'id'       => 'birthday',
            'name'     => 'Birthday Bash',
            'tagline'  => 'Make their day extra special',
            'headline' => 'Happy Birthday!',
            'category' => 'birthday',
            'vis'      => 'birthday',
            'tag_cls'  => 'gc-tag-birthday',
            'tag_txt'  => '🎂 Birthday',
            'amount'   => 10000,
            'occasion' => 'Birthday',
          ],
          [
            'id'       => 'luxury',
            'name'     => 'Luxury Gold',
            'tagline'  => 'Timeless. Elegant. Premium.',
            'headline' => 'A Gift of Excellence',
            'category' => 'luxury',
            'vis'      => 'luxury',
            'tag_cls'  => 'gc-tag-luxury',
            'tag_txt'  => '💎 Luxury',
            'amount'   => 50000,
            'occasion' => 'Any Occasion',
          ],
          [
            'id'       => 'love',
            'name'     => 'Love & Hearts',
            'tagline'  => 'For someone truly special',
            'headline' => 'With Love, Always',
            'category' => 'love',
            'vis'      => 'love',
            'tag_cls'  => 'gc-tag-love',
            'tag_txt'  => '❤️ Love',
            'amount'   => 25000,
            'occasion' => 'Love',
          ],
          [
            'id'       => 'festive',
            'name'     => "Season's Greetings",
            'tagline'  => 'Spread joy this season',
            'headline' => "Season's Greetings",
            'category' => 'festive',
            'vis'      => 'festive',
            'tag_cls'  => 'gc-tag-festive',
            'tag_txt'  => '🎄 Festive',
            'amount'   => 5000,
            'occasion' => 'Festive',
          ],
          [
            'id'       => 'celebration',
            'name'     => 'Celebration Vibes',
            'tagline'  => 'Celebrate every moment',
            'headline' => "Let's Celebrate!",
            'category' => 'celebration',
            'vis'      => 'celebration',
            'tag_cls'  => 'gc-tag-celebration',
            'tag_txt'  => '🎉 Celebration',
            'amount'   => 10000,
            'occasion' => 'Celebration',
          ],
          [
            'id'       => 'custom',
            'name'     => 'Your Custom Card',
            'tagline'  => 'Write your personal message',
            'headline' => 'From the Heart',
            'category' => 'custom',
            'vis'      => 'custom',
            'tag_cls'  => 'gc-tag-custom',
            'tag_txt'  => '✏️ Custom',
            'amount'   => 10000,
            'occasion' => 'Special',
          ],
        ];
        @endphp

        <div class="gcv2-grid" id="gcGrid">
          @foreach($gcTemplates as $idx => $tpl)
          <div class="gc-thumb{{ $idx === 0 ? ' gc-selected' : '' }}"
               data-idx="{{ $idx }}"
               data-category="{{ $tpl['category'] }}"
               data-amount="{{ $tpl['amount'] }}"
               data-template="{{ $tpl['id'] }}"
               data-headline="{{ $tpl['headline'] }}"
               data-occasion="{{ $tpl['occasion'] }}"
               onclick="selectCard({{ $idx }})"
               role="button"
               tabindex="0"
               aria-label="Select {{ $tpl['name'] }} gift card"
               onkeydown="if(event.key==='Enter'||event.key===' ')selectCard({{ $idx }})">

            {{-- Visual --}}
            <div class="gc-thumb-vis gc-vis-{{ $tpl['vis'] }}">
              <div class="gc-thumb-shimmer"></div>
              {{-- Check indicator --}}
              <div class="gc-thumb-check" aria-hidden="true">
                <span class="gc-thumb-check-icon">✓</span>
              </div>
              {{-- Card text --}}
              <div class="gc-thumb-txt">
                <div class="gc-thumb-logo">Kominhoo.</div>
                <div class="gc-thumb-headline">{{ $tpl['headline'] }}</div>
                <div class="gc-thumb-amt-badge">₦{{ number_format($tpl['amount'], 0, '.', ',') }}</div>
              </div>
            </div>

            {{-- Info --}}
            <div class="gc-thumb-info">
              <div class="gc-thumb-name">{{ $tpl['name'] }}</div>
              <div class="gc-thumb-tagline">{{ $tpl['tagline'] }}</div>
              <span class="gc-thumb-tag {{ $tpl['tag_cls'] }}">{{ $tpl['tag_txt'] }}</span>
            </div>
          </div>
          @endforeach
        </div>

        {{-- Universal Card CTA --}}
        <div class="gc-universal">
          <div class="gc-universal-icon">🎁</div>
          <div class="gc-universal-body">
            <div class="gc-universal-title">Not sure which card to choose?</div>
            <div class="gc-universal-desc">Let them choose their favourite with a Kominhoo Universal Gift Card — any amount, any design.</div>
          </div>
          <button class="gc-universal-btn" onclick="openUniversalCard()">
            Send Universal Card →
          </button>
        </div>

        {{-- How it works --}}
        <div class="gcv2-hiw">
          <div class="gcv2-hiw-hdr">
            <div class="gcv2-hiw-lbl">Simple & instant</div>
            <h2 class="gcv2-hiw-title">How Gift Cards Work</h2>
          </div>
          <div class="gcv2-hiw-grid">
            @foreach([
              ['💳','Choose & Pay',      'Pick a design, personalise your message, and pay securely via Paystack.'],
              ['📧','Instant Delivery',  'The recipient gets their unique code straight to their inbox.'],
              ['✨','Shop & Glow',        'They enter the code at checkout to use on any Kominhoo product.'],
            ] as $k => [$ico,$title,$desc])
            <div class="gcv2-hiw-step">
              @if($k < 2)<div class="gcv2-hiw-connector"></div>@endif
              <div class="gcv2-hiw-icon gcv2-hiw-icon-{{ $k }}">{{ $ico }}</div>
              <div class="gcv2-hiw-step-num">0{{ $k+1 }}</div>
              <div class="gcv2-hiw-step-title">{{ $title }}</div>
              <div class="gcv2-hiw-step-desc">{{ $desc }}</div>
            </div>
            @endforeach
          </div>
        </div>

      </div>{{-- /left --}}

      {{-- ──────── RIGHT: Preview panel ──────── --}}
      <div class="gcv2-panel" id="gcPanel">

        {{-- Panel header --}}
        <div class="gcp-hdr">
          <span class="gcp-hdr-lbl">Preview</span>
          <div class="gcp-nav">
            <button class="gcp-nav-btn" onclick="navCard(-1)" aria-label="Previous card">‹</button>
            <span class="gcp-nav-lbl" id="gcNavLbl">1 / 6</span>
            <button class="gcp-nav-btn" onclick="navCard(1)"  aria-label="Next card">›</button>
          </div>
        </div>

        {{-- Large card preview --}}
        <div class="gcp-preview gc-vis-birthday" id="gcPanelVis">
          <div class="gc-thumb-shimmer" style="animation:gcShine 7s ease-in-out infinite;opacity:.6"></div>
          <div class="gc-thumb-txt" style="padding:18px 20px">
            <div class="gc-thumb-logo" style="font-size:.6rem">Kominhoo.</div>
            <div class="gc-thumb-headline" style="font-size:1.4rem" id="gcPanelHeadline">Happy Birthday!</div>
            <div class="gc-thumb-amt-badge" style="font-size:1.3rem;padding:7px 16px" id="gcPanelAmtBadge">₦10,000</div>
          </div>
        </div>

        {{-- Mode toggle --}}
        <div class="gcp-mode">
          <button class="gcp-mode-btn active" id="gcModeSomeone" onclick="setMode(false)">🎁 For Someone</button>
          <button class="gcp-mode-btn"        id="gcModeMyself"  onclick="setMode(true)">🙋 For Myself</button>
        </div>

        {{-- Details form --}}
        <div class="gcp-details">

          {{-- Amount --}}
          <div class="gcp-row">
            <span class="gcp-row-icon">✦</span>
            <span class="gcp-row-label">Amount</span>
            <div class="gcp-row-val" style="flex:1">
              <div class="gcp-amt-pills" id="gcAmtPills">
                @foreach($denominations as $d)
                <button class="gcp-amt-pill{{ $d['amount'] === 10000 ? ' active' : '' }}"
                        data-amount="{{ $d['amount'] }}"
                        onclick="selectAmt({{ $d['amount'] }}, this)">
                  ₦{{ number_format($d['amount'], 0, '.', ',') }}
                  @if($d['is_popular'] ?? false)<span style="font-size:.6rem;opacity:.7"> ★</span>@endif
                </button>
                @endforeach
              </div>
              <input type="number" id="gcCustomAmt" class="gcp-inp" style="margin-top:6px"
                     placeholder="Custom: min ₦1,000" min="1000" step="500"
                     oninput="selectCustomAmt(this.value)">
              <div id="gcAmtErr" style="font-size:.72rem;color:#B91C1C;margin-top:3px;min-height:14px"></div>
            </div>
          </div>

          {{-- From --}}
          <div class="gcp-row">
            <span class="gcp-row-icon">👤</span>
            <span class="gcp-row-label">From</span>
            <div class="gcp-row-val" style="flex:1">
              <input type="text" id="gcFromName" class="gcp-inp"
                     placeholder="Your name"
                     value="{{ session('user.name') ?? '' }}"
                     oninput="syncPreview()">
              <input type="email" id="gcFromEmail" class="gcp-inp"
                     placeholder="your@email.com"
                     value="{{ session('user.email') ?? '' }}"
                     oninput="syncPreview()">
              <div id="gcFromErr" style="font-size:.72rem;color:#B91C1C;margin-top:3px;min-height:14px"></div>
            </div>
          </div>

          {{-- To --}}
          <div id="gcToRow" class="gcp-row">
            <span class="gcp-row-icon">🎯</span>
            <span class="gcp-row-label">To</span>
            <div class="gcp-row-val" style="flex:1">
              <input type="text"  id="gcToName"  class="gcp-inp" placeholder="Recipient's name"  oninput="syncPreview()">
              <input type="email" id="gcToEmail" class="gcp-inp" placeholder="recipient@email.com" oninput="syncPreview()">
              <div id="gcToErr" style="font-size:.72rem;color:#B91C1C;margin-top:3px;min-height:14px"></div>
            </div>
          </div>

          {{-- Self note --}}
          <div id="gcSelfNote" class="gcp-row" style="display:none">
            <span class="gcp-row-icon">🙋</span>
            <span class="gcp-row-label">To</span>
            <div class="gcp-row-val" style="flex:1">
              <div class="gcp-self-note" style="display:block">
                Gift card will be delivered to <strong id="gcSelfEmail">your email</strong>. Usable immediately at checkout.
              </div>
            </div>
          </div>

          {{-- Message --}}
          <div class="gcp-row">
            <span class="gcp-row-icon">💬</span>
            <span class="gcp-row-label">Message</span>
            <div class="gcp-row-val" style="flex:1">
              <textarea id="gcMessage" class="gcp-inp" rows="2"
                placeholder="'Happy Birthday! Treat yourself to a full glow-up 🌟'"
                oninput="syncPreview()"></textarea>
            </div>
          </div>

          {{-- Card Title (custom card only) --}}
          <div id="gcCustomTitleRow" class="gcp-row" style="display:none">
            <span class="gcp-row-icon">✏️</span>
            <span class="gcp-row-label">Card Title</span>
            <div class="gcp-row-val" style="flex:1">
              <input type="text" id="gcCardTitle" class="gcp-inp"
                     placeholder="e.g. Congratulations! Happy Birthday!"
                     maxlength="40"
                     oninput="updateCustomHeadline(this.value)">
              <div style="font-size:.68rem;color:var(--text-muted,#A08878);margin-top:3px">Appears on the card · max 40 chars</div>
            </div>
          </div>

          {{-- Occasion --}}
          <div class="gcp-row">
            <span class="gcp-row-icon">📅</span>
            <span class="gcp-row-label">Occasion</span>
            <span class="gcp-row-val" id="gcOccasion">Birthday</span>
          </div>

          {{-- Redemption Code --}}
          <div class="gcp-row">
            <span class="gcp-row-icon">🔒</span>
            <span class="gcp-row-label">Code</span>
            <div class="gcp-row-val" style="flex:1">
              <div class="gcp-code-box" id="gcCodeBox">
                <span class="gcp-code-val" id="gcCodeVal">Generated on purchase</span>
                <button class="gcp-copy-btn" id="gcPanelCopyBtn" onclick="copyPanelCode()" style="display:none" title="Copy code">⧉</button>
              </div>
            </div>
          </div>

          {{-- Expiry --}}
          <div class="gcp-row">
            <span class="gcp-row-icon">📆</span>
            <span class="gcp-row-label">Expires On</span>
            <span class="gcp-row-val dim" id="gcExpiry">12 months from purchase</span>
          </div>

          {{-- Terms --}}
          <div class="gcp-row">
            <span class="gcp-row-icon">📋</span>
            <span class="gcp-row-label">Terms</span>
            <span class="gcp-row-val dim" style="font-size:.73rem">Valid for all Kominhoo services. Non-refundable.</span>
          </div>

        </div>{{-- /gcp-details --}}

        <div class="gcp-error" id="gcPanelError"></div>

        <button class="gcp-buy-btn" id="gcBuyBtn" onclick="buyGiftCard()">
          <span id="gcBuyIcon">🛒</span>
          <span id="gcBuyText">Add to Cart</span>
        </button>

      </div>{{-- /panel --}}

    </div>{{-- /gcv2-layout --}}

  </div>{{-- /container --}}
  </div>{{-- /gcv2-inner --}}

  {{-- Trust bar --}}
  <div class="gcv2-trust">
    <div class="container">
      <div class="gcv2-trust-inner">
        <div class="gcv2-trust-item"><div class="gcv2-trust-dot"></div>Secured by Paystack</div>
        <div class="gcv2-trust-item"><div class="gcv2-trust-dot"></div>Instant email delivery</div>
        <div class="gcv2-trust-item"><div class="gcv2-trust-dot"></div>Valid for 1 full year</div>
        <div class="gcv2-trust-item"><div class="gcv2-trust-dot"></div>Any Kominhoo product</div>
        <div class="gcv2-trust-item"><div class="gcv2-trust-dot"></div>Zero expiry fees</div>
      </div>
    </div>
  </div>

</div>{{-- /gcv2 --}}

@section('scripts')
<div id="gc-cfg" data-paystack-key="{{ $paystackKey ?? '' }}" style="display:none"></div>
@if(!empty($paystackKey))
<script src="https://js.paystack.co/v1/inline.js"></script>
@endif

<script>
// ── State ─────────────────────────────────────────────────────
const PAYSTACK_KEY = document.getElementById('gc-cfg').dataset.paystackKey;

const GC_TEMPLATES = @json(array_values($gcTemplates));
const GC_DENOM     = @json($denominations);

let activeIdx      = 0;
let selectedAmount = GC_TEMPLATES[0].amount;
let gcForSelf      = false;
let lastCard       = null;

// ── Helpers ───────────────────────────────────────────────────
const fmt = n => '₦' + Number(n).toLocaleString();
const el  = id => document.getElementById(id);
const txt = (id, v) => { const e = el(id); if (e) e.textContent = v; };

// ── Card selection ────────────────────────────────────────────
function selectCard(idx) {
  activeIdx = ((idx % GC_TEMPLATES.length) + GC_TEMPLATES.length) % GC_TEMPLATES.length;
  const tpl = GC_TEMPLATES[activeIdx];

  // Update grid selection state
  document.querySelectorAll('.gc-thumb').forEach((t, i) => {
    t.classList.toggle('gc-selected', i === activeIdx);
  });

  // Update nav label
  txt('gcNavLbl', (activeIdx + 1) + ' / ' + GC_TEMPLATES.length);

  // Update panel visual: swap CSS class
  const vis = el('gcPanelVis');
  vis.className = vis.className.replace(/gc-vis-\w+/, '');
  vis.classList.add('gcp-preview', 'gc-vis-' + tpl.vis);

  // Show/hide custom title input
  const isCustom = tpl.id === 'custom';
  const ctRow = el('gcCustomTitleRow');
  if (ctRow) ctRow.style.display = isCustom ? '' : 'none';

  // Update headline & occasion
  const customTitle = isCustom ? (el('gcCardTitle')?.value.trim() || '') : '';
  txt('gcPanelHeadline', customTitle || tpl.headline);
  txt('gcOccasion',      tpl.occasion);

  // Set default amount for this template
  const defaultAmt = tpl.amount;
  const pill = document.querySelector(`.gcp-amt-pill[data-amount="${defaultAmt}"]`);
  if (pill) {
    selectAmt(defaultAmt, pill);
    el('gcCustomAmt').value = '';
  }

  syncPreview();
}

function navCard(dir) { selectCard(activeIdx + dir); }

// ── Filter ────────────────────────────────────────────────────
function filterCards(cat, btn) {
  document.querySelectorAll('.gcf-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  let firstVisible = -1;
  document.querySelectorAll('.gc-thumb').forEach((t, i) => {
    const show = cat === 'all' || t.dataset.category === cat;
    t.toggleAttribute('data-hidden', !show);
    if (show && firstVisible === -1) firstVisible = i;
  });
  if (firstVisible >= 0) selectCard(firstVisible);
}

// ── Amount ────────────────────────────────────────────────────
function selectAmt(amount, btn) {
  document.querySelectorAll('.gcp-amt-pill').forEach(p => p.classList.remove('active'));
  if (btn) btn.classList.add('active');
  selectedAmount = amount;
  txt('gcPanelAmtBadge', fmt(amount));
  clearErr('gcAmtErr');
}

function selectCustomAmt(val) {
  document.querySelectorAll('.gcp-amt-pill').forEach(p => p.classList.remove('active'));
  selectedAmount = parseInt(val) || 0;
  txt('gcPanelAmtBadge', selectedAmount >= 1000 ? fmt(selectedAmount) : '₦ —');
  if (selectedAmount >= 1000) clearErr('gcAmtErr');
}

// ── Mode toggle ───────────────────────────────────────────────
function setMode(forSelf) {
  gcForSelf = forSelf;
  el('gcModeSomeone').classList.toggle('active', !forSelf);
  el('gcModeMyself').classList.toggle('active',   forSelf);
  el('gcToRow').style.display    = forSelf ? 'none' : '';
  el('gcSelfNote').style.display = forSelf ? ''     : 'none';
  el('gcBuyText').textContent = forSelf ? 'Buy for Myself' : 'Add to Cart';
  if (forSelf) {
    const email = el('gcFromEmail').value.trim();
    txt('gcSelfEmail', email || 'your email');
  }
  clearAllErrors();
}

// ── Sync preview badge ────────────────────────────────────────
function syncPreview() {
  txt('gcPanelAmtBadge', selectedAmount >= 1000 ? fmt(selectedAmount) : '₦ —');
  if (gcForSelf) {
    const email = el('gcFromEmail').value.trim();
    txt('gcSelfEmail', email || 'your email');
  }
}

// ── Validation ────────────────────────────────────────────────
function setErr(id, msg) { const e = el(id); if (e) e.textContent = msg; }
function clearErr(id)    { const e = el(id); if (e) e.textContent = ''; }
function clearAllErrors() {
  ['gcAmtErr','gcFromErr','gcToErr'].forEach(clearErr);
  const pe = el('gcPanelError'); if (pe) pe.style.display = 'none';
}

function validate() {
  clearAllErrors();
  let ok = true;
  if (!selectedAmount || selectedAmount < 1000) { setErr('gcAmtErr', 'Select or enter an amount (min ₦1,000)'); ok = false; }
  if (!el('gcFromName').value.trim())  { setErr('gcFromErr', 'Your name is required'); ok = false; }
  if (!el('gcFromEmail').value.trim()) { setErr('gcFromErr', 'Your email is required'); ok = false; }
  if (!gcForSelf) {
    if (!el('gcToName').value.trim()  || !el('gcToEmail').value.trim()) {
      setErr('gcToErr', 'Recipient name and email are required'); ok = false;
    }
  }
  return ok;
}

// ── Purchase ──────────────────────────────────────────────────
function setBuyLoading(on) {
  el('gcBuyBtn').disabled = on;
  el('gcBuyIcon').textContent = on ? '⏳' : '🛒';
  el('gcBuyText').textContent = on ? 'Processing…' : (gcForSelf ? 'Buy for Myself' : 'Add to Cart');
}

function showPanelError(msg) {
  const pe = el('gcPanelError');
  pe.textContent = msg; pe.style.display = 'block';
}

async function createGiftCard(paymentRef) {
  setBuyLoading(true);
  try {
    const fromName  = el('gcFromName').value.trim();
    const fromEmail = el('gcFromEmail').value.trim();
    const toName    = gcForSelf ? fromName  : el('gcToName').value.trim();
    const toEmail   = gcForSelf ? fromEmail : el('gcToEmail').value.trim();
    const tpl       = GC_TEMPLATES[activeIdx];

    const res = await fetch('{{ route("gift-cards.purchase") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({
        amount:           selectedAmount,
        purchaser_name:   fromName,
        purchaser_email:  fromEmail,
        recipient_name:   toName,
        recipient_email:  toEmail,
        message:          el('gcMessage').value.trim(),
        theme:            tpl.id,
        payment_ref:      paymentRef,
        for_self:         gcForSelf,
      })
    });
    if (res.status === 401 || res.status === 403) {
      window.location.href = '{{ route("login") }}?redirect={{ urlencode(url()->current()) }}'; return;
    }
    const data = await res.json();
    if (data.success) {
      lastCard = data.data;
      showSuccessModal(data.data);
      showPurchasedCode(data.data.code, data.data.expires_at);
    } else {
      showPanelError(data.message || 'Could not process. Please try again.');
    }
  } catch {
    showPanelError('Network error. Please try again.');
  } finally {
    setBuyLoading(false);
  }
}

function buyGiftCard() {
  if (!validate()) {
    el('gcPanel').scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }
  if (PAYSTACK_KEY) {
    setBuyLoading(true);
    const handler = PaystackPop.setup({
      key:      PAYSTACK_KEY,
      email:    el('gcFromEmail').value.trim() || 'guest@kominhoo.com',
      amount:   selectedAmount * 100,
      currency: 'NGN',
      ref:      'GC-KMH-' + Date.now(),
      metadata: {
        type: 'gift_card',
        recipient: gcForSelf ? el('gcFromEmail').value.trim() : el('gcToEmail').value.trim(),
        theme: GC_TEMPLATES[activeIdx].id,
      },
      onClose:  () => setBuyLoading(false),
      callback: (res) => createGiftCard(res.reference),
    });
    handler.openIframe();
  } else {
    createGiftCard('direct');
  }
}

// ── After purchase: show code in panel ───────────────────────
function showPurchasedCode(code, expiry) {
  el('gcCodeVal').textContent = code;
  el('gcPanelCopyBtn').style.display = 'flex';
  el('gcExpiry').textContent = expiry;
  el('gcExpiry').classList.remove('dim');
}

// ── Copy code (panel) ─────────────────────────────────────────
function copyPanelCode() {
  if (!lastCard) return;
  navigator.clipboard.writeText(lastCard.code).then(() => {
    const btn = el('gcPanelCopyBtn');
    btn.textContent = '✓'; btn.style.color = '#6ee7b7';
    setTimeout(() => { btn.textContent = '⧉'; btn.style.color = ''; }, 2000);
  });
}

// ── Success modal ─────────────────────────────────────────────
function showSuccessModal(card) {
  const heading = el('gcSuccessHeading');
  if (gcForSelf) { heading.textContent = 'Gift Card Ready!'; }
  else           { heading.textContent = 'Gift Card Sent!'; }
  el('gcSuccessRecipient').textContent = card.recipient_name;
  el('gcSuccessEmail').textContent     = card.recipient_email;
  el('gcSuccessCode').textContent      = card.code;
  el('gcSuccessOverlay').style.display = 'flex';
}

function copySuccessCode() {
  if (!lastCard) return;
  navigator.clipboard.writeText(lastCard.code).then(() => {
    const btn = el('gcCopyBtn');
    btn.textContent = '✓ Copied!'; btn.style.background = '#16a34a';
    setTimeout(() => { btn.textContent = 'Copy'; btn.style.background = ''; }, 2200);
  });
}

// ── Custom card headline ──────────────────────────────────────
function updateCustomHeadline(val) {
  if (GC_TEMPLATES[activeIdx]?.id === 'custom') {
    txt('gcPanelHeadline', val.trim() || 'From the Heart');
  }
}

// ── Open custom card ──────────────────────────────────────────
function openCustomCard() {
  const idx = GC_TEMPLATES.findIndex(t => t.id === 'custom');
  if (idx < 0) return;
  filterCards('all', document.querySelector('.gcf-tab[data-filter="all"]'));
  selectCard(idx);
  el('gcPanel').scrollIntoView({ behavior: 'smooth', block: 'start' });
  setTimeout(() => el('gcCardTitle')?.focus(), 420);
}

// ── Open universal card (same flow as custom card) ────────────
function openUniversalCard() { openCustomCard(); }

// ── Scroll helper ─────────────────────────────────────────────
function scrollToPanel() { el('gcPanel').scrollIntoView({ behavior: 'smooth', block: 'start' }); }

// ── Filter card visibility ────────────────────────────────────
// (filterCards is already defined above)

// ── Init ──────────────────────────────────────────────────────
(function init() {
  selectCard(0);
  const seed = el('gcFromName')?.value.trim();
  // Keyboard nav
  el('gcPanel')?.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft')  navCard(-1);
    if (e.key === 'ArrowRight') navCard(1);
  });
  // Close success modal on overlay click
  el('gcSuccessOverlay')?.addEventListener('click', e => {
    if (e.target === el('gcSuccessOverlay')) el('gcSuccessOverlay').style.display = 'none';
  });
})();
</script>
@endsection
@endsection
