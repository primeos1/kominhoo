@extends('layouts.app')
@section('title', 'Kominhoo Beauty — Your Personalized Korean Skincare Destination')

@section('head')
<style>
/* ================================================================
   HOME PAGE — Kominhoo Beauty
   Palette: Rose #893941 · Blush #CB7885 · Chartreuse #D4D994 · Olive #5E6623
   Fonts: Bodoni Moda (display) · Jost (body)
   ================================================================ */

/* ── Font & Color Tokens (scoped to page) ──────────────────────── */
.home-page {
  font-family: 'Jost', system-ui, sans-serif;
  /* Override global design tokens for this page */
  --black:           #1C1416;
  --dark:            #2A1E1F;
  --lime:            #D4D994;
  --lime-dark:       #5E6623;
  --lime-light:      #E6EFB5;
  --lime-pale:       #F2F5D6;
  --cream:           #FAF6F3;
  --off-white:       #F5F0EC;
  --border:          #EDDCD8;
  --text-secondary:  #6B5450;
  --text-muted:      #A08878;
  --bg-primary:      #FAF6F3;
  /* New rose tokens */
  --rose:            #893941;
  --rose-dark:       #6B2A30;
  --rose-mid:        #9E4450;
  --rose-light:      #B56070;
  --blush:           #CB7885;
  --blush-pale:      #F5ECED;
  --blush-light:     #EDD8DB;
  --chartreuse:      #D4D994;
  --olive:           #5E6623;
}

.home-page h1,
.home-page h2,
.home-page h3 { font-family: 'Bodoni Moda', Georgia, serif; }

.home-page .serif { font-family: 'Bodoni Moda', Georgia, serif; }

/* ── Animations ─────────────────────────────────────────────────── */
@keyframes shimmer-sweep {
  0%   { transform: translateX(-120%) }
  100% { transform: translateX(220%) }
}
@keyframes qp-float {
  0%   { transform: translateY(110%) rotate(0deg);   opacity: 0 }
  10%  { opacity: 1 }
  90%  { opacity: 1 }
  100% { transform: translateY(-110%) rotate(360deg); opacity: 0 }
}
@keyframes kb-zoom {
  from { transform: scale(1.0); }
  to   { transform: scale(1.09); }
}
@keyframes kb-zoom-alt {
  from { transform: scale(1.0) translateX(0); }
  to   { transform: scale(1.09) translateX(-2%); }
}
@keyframes txt-up {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes pin-in {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes pin-pulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(212,217,148,.6); }
  60%       { box-shadow: 0 0 0 9px rgba(212,217,148,0); }
}

/* ── Hero Slider ─────────────────────────────────────────────────── */
.hero-slider {
  position: relative;
  display: grid;
  grid-template-columns: 46fr 54fr;
  height: calc(100vh - var(--nav-h));
  min-height: 560px;
  max-height: 900px;
  overflow: hidden;
  background: var(--rose-dark);
}

/* ── Left: static text panel ─────────────────────────────────────── */
.hero-content-layer {
  background: var(--rose-dark);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 56px clamp(24px, 4.5vw, 60px) 80px clamp(24px, 4.5vw, 60px);
  position: relative;
  z-index: 2;
  overflow: hidden;
}
.hero-content-layer::after {
  content: '';
  position: absolute;
  right: 0; top: 10%; bottom: 10%;
  width: 1px;
  background: linear-gradient(to bottom, transparent, rgba(212,217,148,.15) 40%, rgba(212,217,148,.15) 60%, transparent);
  pointer-events: none;
}

.hero-text { max-width: 100%; }

.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 18px;
  font-family: 'Jost', sans-serif;
  font-size: .7rem;
  font-weight: 600;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: var(--chartreuse);
  animation: txt-up .7s .15s cubic-bezier(.4,0,.2,1) both;
}
.hero-eyebrow::before {
  content: '';
  width: 28px;
  height: 1.5px;
  background: var(--chartreuse);
  flex-shrink: 0;
}

.hero-title {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: clamp(2.6rem, 4.2vw, 5.6rem);
  font-weight: 700;
  line-height: 1.03;
  letter-spacing: -.02em;
  color: #fff;
  margin-bottom: 18px;
  animation: txt-up .72s .3s cubic-bezier(.4,0,.2,1) both;
}
.hero-title em { font-style: italic; color: var(--blush); }

.hero-desc {
  max-width: 42ch;
  font-family: 'Jost', sans-serif;
  font-size: .93rem;
  line-height: 1.78;
  color: rgba(255,255,255,.52);
  margin-bottom: 30px;
  font-weight: 400;
  animation: txt-up .7s .46s cubic-bezier(.4,0,.2,1) both;
}

.hero-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 40px;
  animation: txt-up .7s .58s cubic-bezier(.4,0,.2,1) both;
}
.hero-actions .btn-primary {
  background: var(--chartreuse);
  color: var(--rose-dark);
  font-family: 'Jost', sans-serif;
  font-weight: 700;
  box-shadow: 0 8px 32px rgba(212,217,148,.32);
  border: none;
}
.hero-actions .btn-primary:hover {
  background: var(--lime-light);
  transform: translateY(-2px);
  box-shadow: 0 14px 40px rgba(212,217,148,.4);
}
.hero-actions .btn-outline {
  border: 1.5px solid rgba(255,255,255,.22);
  color: rgba(255,255,255,.82);
  background: rgba(255,255,255,.06);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  font-family: 'Jost', sans-serif;
}
.hero-actions .btn-outline:hover {
  background: rgba(255,255,255,.14);
  border-color: rgba(255,255,255,.4);
  color: #fff;
  transform: translateY(-2px);
}

.hero-stats {
  display: flex;
  gap: 28px;
  padding-top: 24px;
  border-top: 1px solid rgba(255,255,255,.1);
  animation: txt-up .7s .7s cubic-bezier(.4,0,.2,1) both;
}
.hero-stat-num {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: #fff;
  line-height: 1;
  letter-spacing: -.02em;
}
.hero-stat-label {
  font-family: 'Jost', sans-serif;
  font-size: .68rem;
  font-weight: 500;
  letter-spacing: .05em;
  color: rgba(255,255,255,.34);
  margin-top: 4px;
}

/* ── Right: image pane (slides crossfade here only) ──────────────── */
.hero-img-pane {
  position: relative;
  overflow: hidden;
}
.hero-img-slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  pointer-events: none;
  transition: opacity 1.1s cubic-bezier(.4,0,.2,1);
}
.hero-img-slide.is-active {
  opacity: 1;
  pointer-events: auto;
}
.hero-slide-img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center top;
  will-change: transform;
}
.hero-img-slide.is-active .hero-slide-img {
  animation: kb-zoom 7s ease-out forwards;
}
.hero-img-slide:nth-child(even).is-active .hero-slide-img {
  animation: kb-zoom-alt 7s ease-out forwards;
}

/* ── Image pins ───────────────────────────────────────────────────── */
.hero-pin {
  position: absolute;
  display: flex;
  align-items: center;
  gap: 10px;
  z-index: 6;
  opacity: 0;
  pointer-events: none;
}
.hero-img-slide.is-active .hero-pin {
  animation: pin-in .5s .9s cubic-bezier(.4,0,.2,1) both;
  pointer-events: auto;
}
.hero-img-slide.is-active .hero-pin--b {
  animation-delay: 1.25s;
}
.hero-pin-dot {
  width: 11px;
  height: 11px;
  border-radius: 50%;
  background: var(--chartreuse);
  flex-shrink: 0;
  animation: pin-pulse 2.6s ease-in-out infinite;
  position: relative;
}
.hero-pin-dot::before {
  content: '';
  position: absolute;
  inset: -5px;
  border-radius: 50%;
  border: 1.5px solid rgba(212,217,148,.35);
}
.hero-pin-label {
  background: rgba(255,255,255,.93);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border-radius: 10px;
  padding: 8px 13px;
  min-width: 130px;
  box-shadow: 0 4px 20px rgba(28,20,22,.18);
}
.hero-pin-name {
  display: block;
  font-family: 'Jost', sans-serif;
  font-size: .76rem;
  font-weight: 700;
  color: var(--rose-dark);
  line-height: 1.25;
  margin-bottom: 2px;
}
.hero-pin-detail {
  display: block;
  font-family: 'Jost', sans-serif;
  font-size: .66rem;
  font-weight: 500;
  color: var(--text-secondary);
}

/* Navigation controls — anchored to left (text) panel */
.hero-controls {
  position: absolute;
  bottom: 32px;
  left: clamp(24px, 4.5vw, 60px);
  right: 54%;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.hero-dots {
  display: flex;
  gap: 7px;
  align-items: center;
}
.hero-dot {
  height: 3px;
  width: 24px;
  border-radius: 2px;
  background: rgba(255,255,255,.28);
  border: none;
  cursor: pointer;
  padding: 0;
  transition: all .4s ease;
}
.hero-dot.is-active {
  background: var(--chartreuse);
  width: 48px;
}
.hero-dot:hover:not(.is-active) { background: rgba(255,255,255,.52); }

.hero-arrows {
  display: flex;
  gap: 8px;
}
.hero-arrow {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  border: 1.5px solid rgba(255,255,255,.2);
  background: rgba(255,255,255,.06);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  display: grid;
  place-items: center;
  transition: all .22s ease;
  line-height: 1;
}
.hero-arrow:hover { background: var(--rose); border-color: var(--rose); }

/* Slide counter — top of left panel */
.hero-counter {
  position: absolute;
  top: 28px;
  left: clamp(24px, 4.5vw, 60px);
  z-index: 10;
  font-family: 'Jost', sans-serif;
  font-size: .7rem;
  letter-spacing: .1em;
  color: rgba(255,255,255,.3);
  display: flex;
  align-items: baseline;
  gap: 4px;
}
.hero-counter-cur {
  font-size: 1.15rem;
  font-weight: 700;
  color: rgba(255,255,255,.7);
  line-height: 1;
}


/* ── Section Shared ──────────────────────────────────────────────── */
.home-page .sec-kicker {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  font-family: 'Jost', sans-serif;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .2em;
  text-transform: uppercase;
  color: var(--rose);
  margin-bottom: 10px;
}
.home-page .sec-kicker::before {
  content: '';
  width: 20px;
  height: 1.5px;
  background: var(--rose);
  border-radius: 2px;
}
.home-page .sec-kicker-dot { display: none; }
.home-page .sec-heading {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: clamp(1.85rem, 2.7vw, 2.75rem);
  letter-spacing: -.025em;
  line-height: 1.08;
  margin-bottom: 8px;
  color: var(--black);
}
.home-page .sec-sub {
  font-family: 'Jost', sans-serif;
  font-size: .92rem;
  color: var(--text-secondary);
  line-height: 1.72;
}
.home-page .sec-row { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 40px; }

/* ── Global button overrides for home ───────────────────────────── */
.home-page .btn-primary {
  background: var(--chartreuse);
  color: var(--rose-dark);
  font-family: 'Jost', sans-serif;
  font-weight: 700;
  box-shadow: 0 6px 24px rgba(212,217,148,.28);
  border: none;
}
.home-page .btn-primary:hover {
  background: var(--lime-light);
  transform: translateY(-2px);
  box-shadow: 0 10px 32px rgba(212,217,148,.38);
}
.home-page .btn-dark {
  background: var(--rose-dark);
  color: #fff;
  font-family: 'Jost', sans-serif;
}
.home-page .btn-dark:hover {
  background: var(--rose);
  transform: translateY(-2px);
}
.home-page .btn-outline {
  border: 2px solid var(--blush-light);
  color: var(--rose);
  font-family: 'Jost', sans-serif;
  background: transparent;
}
.home-page .btn-outline:hover {
  background: var(--blush-pale);
  border-color: var(--blush);
  color: var(--rose-dark);
}

/* ── Why Kominhoo ────────────────────────────────────────────────── */
.why-section {
  padding: 96px 0 112px;
  background: var(--cream);
  border-bottom: 1px solid var(--border);
  position: relative;
  overflow: hidden;
}
.why-section::before {
  content: 'WHY';
  position: absolute;
  top: -24px;
  right: -32px;
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: clamp(9rem, 16vw, 22rem);
  font-weight: 700;
  color: rgba(137,57,65,.028);
  line-height: 1;
  pointer-events: none;
  user-select: none;
  letter-spacing: -.05em;
  z-index: 0;
}
.why-header-wrap {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 32px;
  margin-bottom: 52px;
  position: relative;
  z-index: 1;
}
.why-kicker {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  font-family: 'Jost', sans-serif;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .2em;
  text-transform: uppercase;
  color: var(--rose);
  margin-bottom: 14px;
}
.why-kicker::before {
  content: '';
  width: 20px;
  height: 1.5px;
  background: var(--rose);
  border-radius: 2px;
}
.why-heading {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: clamp(2.1rem, 3.2vw, 3.4rem);
  line-height: 1.07;
  letter-spacing: -.025em;
  max-width: 18ch;
  color: var(--black);
  margin-bottom: 0;
}
.why-lead {
  max-width: 34ch;
  font-family: 'Jost', sans-serif;
  font-size: .92rem;
  line-height: 1.8;
  color: var(--text-secondary);
}
.why-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  position: relative;
  z-index: 1;
}
.why-card {
  background: #fff;
  border-radius: 22px;
  border: 1px solid var(--border);
  overflow: hidden;
  position: relative;
  transition: transform .32s cubic-bezier(.4,0,.2,1), box-shadow .32s, border-color .32s;
  box-shadow: 0 2px 12px rgba(137,57,65,.04);
}
.why-card::before { display: none; }
.why-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 28px 64px rgba(137,57,65,.14);
  border-color: var(--blush-light);
}

/* Numbered badge floating over image */
.why-num {
  position: absolute;
  top: 14px;
  left: 14px;
  z-index: 10;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: rgba(255,255,255,.92);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  display: grid;
  place-items: center;
  font-family: 'Jost', sans-serif;
  font-size: .68rem;
  font-weight: 700;
  color: var(--rose);
  letter-spacing: .02em;
  box-shadow: 0 2px 10px rgba(28,20,22,.14);
}

/* Image */
.why-media {
  height: 240px;
  overflow: hidden;
  position: relative;
  border-radius: 0;
  border: none;
  box-shadow: none;
  margin: 0;
}
.why-media::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(18,10,12,.82) 0%, rgba(18,10,12,.1) 52%, transparent 100%);
  z-index: 1;
}
.why-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scale(1.01);
  transition: transform .65s ease;
}
.why-card:hover .why-media img { transform: scale(1.08); }

/* Title + icon overlaid on image bottom */
.why-img-text {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 14px 18px 16px;
  z-index: 2;
  display: flex;
  align-items: center;
  gap: 9px;
}
.why-icon {
  font-size: 1.1rem;
  line-height: 1;
  flex-shrink: 0;
}
.why-title {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: 1.06rem;
  font-weight: 600;
  color: #fff;
  letter-spacing: -.01em;
  line-height: 1.22;
  margin-bottom: 0;
}

/* Card body */
.why-body {
  padding: 16px 20px 22px;
  position: relative;
  border-top: 2px solid transparent;
}
.why-card:nth-child(1) .why-body,
.why-card:nth-child(3) .why-body { border-top-color: var(--rose); }
.why-card:nth-child(2) .why-body,
.why-card:nth-child(4) .why-body { border-top-color: var(--chartreuse); }

.why-tag { display: none; }
.why-desc {
  font-family: 'Jost', sans-serif;
  font-size: .85rem;
  color: var(--text-secondary);
  line-height: 1.72;
  max-width: none;
}

/* Ghost number decoration */
.why-ghost-num {
  position: absolute;
  bottom: 2px;
  right: 12px;
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: 5rem;
  font-weight: 700;
  line-height: 1;
  letter-spacing: -.05em;
  pointer-events: none;
  user-select: none;
  color: rgba(137,57,65,.055);
}
.why-card:nth-child(2) .why-ghost-num,
.why-card:nth-child(4) .why-ghost-num { color: rgba(94,102,35,.055); }

/* ── Quiz CTA Banner ─────────────────────────────────────────────── */
.quiz-banner { background: var(--rose); padding: 0; }
.quiz-banner-inner {
  max-width: var(--container);
  margin: 0 auto;
  padding: 64px var(--pad);
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 48px;
  align-items: center;
}
.quiz-banner-eyebrow {
  font-family: 'Jost', sans-serif;
  font-size: .68rem;
  font-weight: 600;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: rgba(255,255,255,.42);
  margin-bottom: 14px;
}
.quiz-banner-text h2 {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: clamp(1.9rem, 2.8vw, 2.9rem);
  color: #fff;
  line-height: 1.14;
  letter-spacing: -.025em;
  margin-bottom: 12px;
}
.quiz-banner-text p {
  font-family: 'Jost', sans-serif;
  color: rgba(255,255,255,.48);
  font-size: .92rem;
  line-height: 1.75;
  max-width: 50ch;
}
.quiz-banner-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; flex-shrink: 0; }
.quiz-banner-meta {
  font-family: 'Jost', sans-serif;
  font-size: .75rem;
  color: rgba(255,255,255,.3);
  font-weight: 500;
}
.quiz-banner .btn-primary {
  background: var(--chartreuse);
  color: var(--rose-dark);
  font-weight: 700;
  box-shadow: 0 8px 28px rgba(212,217,148,.25);
}
.quiz-banner .btn-primary:hover {
  background: var(--lime-light);
  transform: translateY(-2px);
}

/* ── Deal of the Day ─────────────────────────────────────────────── */
.deals-bg { background: var(--cream); padding: 64px 0; }
.deal-dots { display: none; }
.deal-section-label { display: none; }
.deal-inner {
  display: grid;
  grid-template-columns: 1fr 36%;
  gap: 44px;
  align-items: center;
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 28px;
  padding: 40px 40px 40px 52px;
  box-shadow: 0 6px 36px rgba(137,57,65,.08);
}
.deal-left { position: relative; z-index: 1; }
.deal-accent-line { display: none; }
.deal-badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 5px 12px;
  background: var(--rose);
  color: #fff;
  font-family: 'Jost', sans-serif;
  font-size: .66rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  border-radius: var(--r-pill);
  margin-bottom: 14px;
}
.deal-title {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: clamp(1.6rem, 2.2vw, 2.5rem);
  line-height: 1.08;
  letter-spacing: -.025em;
  color: var(--black);
  margin-bottom: 10px;
}
.deal-desc { font-family: 'Jost', sans-serif; font-size: .86rem; color: var(--text-secondary); line-height: 1.7; margin-bottom: 20px; }
.deal-countdown { display: flex; gap: 6px; align-items: center; margin-bottom: 20px; }
.count-block { display: flex; flex-direction: column; align-items: center; gap: 4px; }
.count-num {
  width: 46px;
  height: 46px;
  display: grid;
  place-items: center;
  background: var(--cream);
  border: 1.5px solid var(--border);
  border-radius: 10px;
  font-family: 'Jost', sans-serif;
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--black);
  letter-spacing: -.025em;
  font-variant-numeric: tabular-nums;
  box-shadow: 0 1px 6px rgba(137,57,65,.05);
}
.count-label { font-family: 'Jost', sans-serif; font-size: .58rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--text-muted); }
.deal-countdown-sep { font-size: 1.3rem; color: var(--blush); font-weight: 300; line-height: 1; margin-bottom: 18px; }
.deal-price-row { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; flex-wrap: wrap; }
.deal-price-new {
  font-family: 'Bodoni Moda', Georgia, serif;
  font-size: 2.1rem;
  font-weight: 700;
  color: var(--rose);
  letter-spacing: -.04em;
  line-height: 1;
}
.deal-price-old { font-family: 'Jost', sans-serif; font-size: .88rem; color: var(--text-muted); text-decoration: line-through; display: block; margin-top: 3px; }
.deal-stock-bar { margin-top: 14px; }
.deal-stock-label { display: flex; justify-content: space-between; font-family: 'Jost', sans-serif; font-size: .74rem; color: var(--text-secondary); font-weight: 600; margin-bottom: 6px; }
.deal-stock-track { height: 4px; background: var(--blush-light); border-radius: 4px; overflow: hidden; }
.deal-stock-fill { height: 100%; width: 72%; background: var(--rose); border-radius: 4px; }
.deal-visual {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  border: none;
  aspect-ratio: 1 / 1;
  max-height: 340px;
  background: var(--blush-pale);
  box-shadow: 0 12px 40px rgba(137,57,65,.13);
}
.deal-visual::before { display: none; }
.deal-visual img { width: 100%; height: 100%; object-fit: cover; }
.deal-save-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  z-index: 2;
  background: var(--rose);
  color: #fff;
  font-family: 'Jost', sans-serif;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 5px 12px;
  border-radius: 99px;
  box-shadow: 0 2px 8px rgba(137,57,65,.28);
}
.deal-price-tag { position: absolute; bottom: 10px; left: 10px; right: 10px; display: grid; grid-template-columns: 1fr 1fr; gap: 6px; background: none; border: none; box-shadow: none; backdrop-filter: none; -webkit-backdrop-filter: none; padding: 0; }
.deal-stat-chip {
  background: rgba(255,255,255,.92);
  backdrop-filter: blur(16px) saturate(180%);
  -webkit-backdrop-filter: blur(16px) saturate(180%);
  border: 1px solid rgba(255,255,255,.9);
  border-radius: 10px;
  padding: 8px 10px;
  box-shadow: 0 2px 10px rgba(137,57,65,.1);
}
.deal-chip-label { font-family: 'Jost', sans-serif; font-size: .56rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 2px; }
.deal-chip-val { font-family: 'Jost', sans-serif; font-size: .92rem; font-weight: 700; color: var(--rose); letter-spacing: -.02em; }

/* ── Carousel nav ────────────────────────────────────────────────── */
.carousel-nav { display: flex; gap: 8px; }
.carousel-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1.5px solid var(--border);
  background: #fff;
  display: grid;
  place-items: center;
  font-size: .9rem;
  cursor: pointer;
  transition: var(--t-fast);
  color: var(--black);
}
.carousel-btn:hover { background: var(--rose); color: #fff; border-color: var(--rose); }
.scroll-track { display: flex; gap: 20px; overflow-x: auto; scrollbar-width: none; padding-bottom: 4px; }
.scroll-track::-webkit-scrollbar { display: none; }

/* ── New Drop Grid ───────────────────────────────────────────────── */
.new-drop-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }


/* Section backgrounds — gives glass cards a gradient to blur over */
.new-drops-bg {
  background:
    radial-gradient(ellipse 60% 55% at 15% 30%, rgba(203,120,133,.13) 0%, transparent 65%),
    radial-gradient(ellipse 50% 45% at 82% 72%, rgba(212,217,148,.14) 0%, transparent 60%),
    var(--cream);
}
.rec-section-bg {
  background:
    radial-gradient(ellipse 55% 60% at 88% 22%, rgba(203,120,133,.14) 0%, transparent 55%),
    radial-gradient(ellipse 45% 50% at 12% 80%, rgba(212,217,148,.13) 0%, transparent 55%),
    radial-gradient(ellipse 40% 50% at 50% 50%, rgba(245,236,237,.5) 0%, transparent 70%),
    #faf6f3;
}


/* ── Buying Guides ───────────────────────────────────────────────── */
.guide-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.guide-card-img { background-size: cover; background-position: center; border-radius: 24px; aspect-ratio: 1 / 1.2; position: relative; overflow: hidden; cursor: pointer; transition: transform var(--t-base), box-shadow var(--t-base); }
.guide-card-img:hover { transform: translateY(-6px); box-shadow: 0 24px 56px rgba(137,57,65,.18); }
.guide-card-img.featured { grid-column: span 2; aspect-ratio: 2 / 1.1; }
.guide-img-inner { position: absolute; inset: 0; background: linear-gradient(160deg, rgba(28,20,22,.52) 0%, rgba(28,20,22,.18) 50%, rgba(28,20,22,.62) 100%); padding: 24px; display: flex; flex-direction: column; justify-content: flex-end; color: #fff; }

.guide-img-title { font-family: 'Bodoni Moda', Georgia, serif; font-size: 1.5rem; font-weight: 600; margin-bottom: 6px; letter-spacing: -.01em; }
.guide-card-img.featured .guide-img-title { font-size: 1.9rem; }
.guide-img-desc { font-family: 'Jost', sans-serif; font-size: .8rem; opacity: .78; margin-bottom: 14px; max-width: 80%; line-height: 1.5; }
.guide-img-footer { display: flex; justify-content: space-between; align-items: center; }
.guide-img-count { background: rgba(255,255,255,.15); backdrop-filter: blur(6px); padding: 4px 12px; border-radius: 99px; font-family: 'Jost', sans-serif; font-size: .72rem; font-weight: 600; }
.guide-img-arrow { font-family: 'Jost', sans-serif; font-weight: 700; font-size: .82rem; border-bottom: 1.5px solid var(--chartreuse); color: var(--chartreuse); }

/* ── Guide Products Modal ───────────────────────────────────────── */
.guide-modal-overlay { position: fixed; inset: 0; background: rgba(28,20,22,.86); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; visibility: hidden; transition: opacity .35s ease, visibility .35s ease; }
.guide-modal-overlay.open { opacity: 1; visibility: visible; }
.guide-modal { background: var(--cream); border-radius: 28px; width: 100%; max-width: 980px; max-height: 88vh; overflow-y: auto; position: relative; transform: translateY(24px); transition: transform .38s cubic-bezier(.34,1.56,.64,1); }
.guide-modal-overlay.open .guide-modal { transform: translateY(0); }
.guide-modal-header { padding: 32px 36px 22px; display: flex; align-items: flex-start; gap: 18px; border-bottom: 1px solid rgba(137,57,65,.12); }
.guide-modal-icon { width: 58px; height: 58px; border-radius: 16px; background: #fff; display: grid; place-items: center; font-size: 1.7rem; flex-shrink: 0; box-shadow: 0 4px 18px rgba(0,0,0,.09); }
.guide-modal-close { position: absolute; top: 18px; right: 22px; background: rgba(255,255,255,.85); border: none; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; font-size: 1rem; display: grid; place-items: center; transition: background .2s, transform .2s; }
.guide-modal-close:hover { background: #fff; transform: scale(1.08); }
.guide-modal-products { padding: 24px 36px 36px; display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px; }
@media (max-width: 640px) { .guide-modal-header { padding: 24px 20px 18px; } .guide-modal-products { padding: 16px 20px 28px; grid-template-columns: 1fr 1fr; gap: 12px; } }

/* ── Community Gallery ───────────────────────────────────────────── */
.gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: 220px 220px; gap: 12px; }
.gallery-item { display: block; border-radius: 16px; overflow: hidden; position: relative; cursor: pointer; text-decoration: none; }
.gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
.gallery-item:hover img { transform: scale(1.08); }
.gallery-item.featured { grid-column: span 2; grid-row: span 2; }
.gallery-item-overlay { position: absolute; inset: 0; background: rgba(137,57,65,0); transition: background var(--t-base); display: grid; place-items: center; }
.gallery-item:hover .gallery-item-overlay { background: rgba(137,57,65,.3); }
.gallery-item-overlay span { opacity: 0; transform: scale(.7); transition: opacity var(--t-base), transform var(--t-base); }
.gallery-item:hover .gallery-item-overlay span { opacity: 1; transform: scale(1); }

/* ── Subscription Plans ──────────────────────────────────────────── */
.sub-section { padding: 96px 0; background: var(--cream); }
.sub-plans-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 48px; }
.sub-plan-card { border-radius: 24px; border: 1.5px solid var(--border); padding: 32px; background: #fff; transition: var(--t-base); position: relative; overflow: hidden; }
.sub-plan-card:hover { border-color: rgba(137,57,65,.28); box-shadow: 0 16px 48px rgba(137,57,65,.1); transform: translateY(-4px); }
.sub-plan-card.featured { background: var(--rose); border-color: var(--rose); }
.sub-plan-card.featured::before { display: none; }
.sub-plan-tag { font-family: 'Jost', sans-serif; font-size: .66rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; padding: 4px 12px; border-radius: 99px; display: inline-block; margin-bottom: 20px; }
.sub-plan-card:not(.featured) .sub-plan-tag { background: var(--blush-pale); color: var(--rose); }
.sub-plan-card.featured .sub-plan-tag { background: rgba(212,217,148,.15); color: var(--chartreuse); }
.sub-plan-name { font-family: 'Jost', sans-serif; font-size: 1.1rem; font-weight: 700; letter-spacing: -.01em; margin-bottom: 6px; color: var(--black); }
.sub-plan-card.featured .sub-plan-name { color: #fff; }
.sub-plan-price { font-family: 'Bodoni Moda', Georgia, serif; font-size: 2.4rem; font-weight: 700; letter-spacing: -.04em; line-height: 1; margin-bottom: 4px; color: var(--rose); }
.sub-plan-card.featured .sub-plan-price { color: #fff; }
.sub-plan-period { font-family: 'Jost', sans-serif; font-size: .8rem; color: var(--text-muted); margin-bottom: 28px; }
.sub-plan-card.featured .sub-plan-period { color: rgba(255,255,255,.36); }
.sub-plan-hr { height: 1px; background: var(--border); margin-bottom: 24px; }
.sub-plan-card.featured .sub-plan-hr { background: rgba(255,255,255,.1); }
.sub-plan-features { display: flex; flex-direction: column; gap: 12px; margin-bottom: 28px; }
.sub-plan-feature { display: flex; align-items: center; gap: 10px; font-family: 'Jost', sans-serif; font-size: .88rem; font-weight: 500; color: var(--text-secondary); }
.sub-plan-card.featured .sub-plan-feature { color: rgba(255,255,255,.76); }
.sub-plan-check { width: 18px; height: 18px; border-radius: 50%; background: var(--blush-pale); display: grid; place-items: center; flex-shrink: 0; }
.sub-plan-card.featured .sub-plan-check { background: rgba(212,217,148,.14); }
.sub-plan-check-icon { color: var(--rose); font-size: .65rem; font-weight: 700; }
.sub-plan-card.featured .sub-plan-check-icon { color: var(--chartreuse); }
.sub-cta { display: none; }

/* ── Loyalty Tiers ───────────────────────────────────────────────── */
.loyalty-section { padding: 96px 0; background: #fff; }
.tier-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 48px; }
.tier-card { border-radius: 24px; border: 1.5px solid var(--border); padding: 36px 32px; background: #fff; transition: var(--t-base); position: relative; overflow: hidden; }
.tier-card:hover { border-color: rgba(137,57,65,.28); box-shadow: 0 16px 48px rgba(137,57,65,.1); transform: translateY(-4px); }
.tier-card.recommended { background: var(--rose); border-color: var(--rose); color: #fff; }
.tier-card.recommended::after { content: 'Most Popular'; position: absolute; top: 20px; right: 20px; background: var(--chartreuse); color: var(--rose-dark); font-family: 'Jost', sans-serif; font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: 4px 12px; border-radius: 99px; }
.tier-icon-wrap { width: 48px; height: 48px; border-radius: 14px; background: var(--blush-pale); display: grid; place-items: center; font-size: 1.4rem; margin-bottom: 20px; }
.tier-card.recommended .tier-icon-wrap { background: rgba(255,255,255,.1); }
.tier-icon { font-size: 1.4rem; }
.tier-name { font-family: 'Jost', sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 4px; letter-spacing: -.01em; color: var(--black); }
.tier-card.recommended .tier-name { color: #fff; }
.tier-req { font-family: 'Jost', sans-serif; font-size: .8rem; color: var(--text-muted); margin-bottom: 24px; }
.tier-card.recommended .tier-req { color: rgba(255,255,255,.38); }
.tier-divider { height: 1px; background: var(--border); margin-bottom: 20px; }
.tier-card.recommended .tier-divider { background: rgba(255,255,255,.1); }
.tier-benefits { display: flex; flex-direction: column; gap: 10px; margin-bottom: 28px; }
.tier-benefit { display: flex; align-items: flex-start; gap: 10px; font-family: 'Jost', sans-serif; font-size: .86rem; font-weight: 500; line-height: 1.4; color: var(--text-secondary); }
.tier-card.recommended .tier-benefit { color: rgba(255,255,255,.72); }
.tier-benefit::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--rose); flex-shrink: 0; margin-top: 6px; }
.tier-card.recommended .tier-benefit::before { background: var(--chartreuse); }

/* ── Newsletter ──────────────────────────────────────────────────── */
.newsletter-section { background: var(--rose-dark); padding: 96px 0; }
.newsletter-inner { text-align: center; max-width: 540px; margin: 0 auto; position: relative; z-index: 1; }
.newsletter-eyebrow { font-family: 'Jost', sans-serif; font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: var(--chartreuse); margin-bottom: 20px; display: inline-block; }
.newsletter-heading { font-family: 'Bodoni Moda', Georgia, serif; font-size: clamp(2rem, 3.5vw, 3rem); color: #fff; line-height: 1.14; letter-spacing: -.025em; margin-bottom: 14px; }
.newsletter-sub { font-family: 'Jost', sans-serif; color: rgba(255,255,255,.4); font-size: .92rem; line-height: 1.72; margin-bottom: 36px; }
.newsletter-form { display: flex; gap: 8px; padding: 6px 6px 6px 20px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12); border-radius: var(--r-pill); }
.newsletter-input { flex: 1; background: none; border: none; outline: none; font-family: 'Jost', sans-serif; color: #fff; font-size: .9rem; font-weight: 500; min-width: 0; }
.newsletter-input::placeholder { color: rgba(255,255,255,.28); }
.newsletter-btn { background: var(--chartreuse); color: var(--rose-dark); font-family: 'Jost', sans-serif; font-size: .84rem; font-weight: 700; padding: 12px 24px; border-radius: var(--r-pill); border: none; cursor: pointer; white-space: nowrap; transition: var(--t-fast); letter-spacing: .04em; }
.newsletter-btn:hover { background: var(--lime-light); }
.newsletter-note { margin-top: 14px; font-family: 'Jost', sans-serif; font-size: .74rem; color: rgba(255,255,255,.2); }

/* ── Quiz Popup ──────────────────────────────────────────────────── */
.quiz-popup-overlay { position: fixed; inset: 0; background: rgba(28,20,22,.82); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; visibility: hidden; transition: opacity .4s ease, visibility .4s ease; }
.quiz-popup-overlay.active { opacity: 1; visibility: visible; }
.quiz-popup { background: var(--rose-dark); border-radius: 28px; max-width: 440px; width: 100%; position: relative; overflow: hidden; transform: scale(.88) translateY(24px); opacity: 0; transition: transform .52s cubic-bezier(.34,1.56,.64,1), opacity .38s ease; border: 1px solid rgba(203,120,133,.18); box-shadow: 0 40px 100px rgba(28,20,22,.8), 0 0 0 1px rgba(212,217,148,.08); }
.quiz-popup-overlay.active .quiz-popup { transform: scale(1) translateY(0); opacity: 1; }
.quiz-popup::before, .quiz-popup::after { display: none; }
.quiz-popup-particles { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
.qp-particle { position: absolute; border-radius: 50%; animation: qp-float linear infinite; }
.quiz-popup-close { position: absolute; top: 14px; right: 14px; width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.42); font-size: .78rem; cursor: pointer; display: grid; place-items: center; z-index: 5; transition: var(--t-fast); line-height: 1; }
.quiz-popup-close:hover { background: rgba(255,255,255,.12); color: #fff; }
.quiz-popup-banner { background: rgba(212,217,148,.06); border-bottom: 1px solid rgba(212,217,148,.12); padding: 10px 28px; text-align: center; font-family: 'Jost', sans-serif; font-size: .72rem; font-weight: 600; color: rgba(255,255,255,.36); letter-spacing: .02em; position: relative; z-index: 1; }
.quiz-popup-banner strong { color: var(--chartreuse); font-weight: 700; }
.quiz-popup-body { padding: 32px 36px 28px; position: relative; z-index: 1; }
.quiz-popup-eyebrow { font-family: 'Jost', sans-serif; font-size: .68rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: var(--chartreuse); margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
.quiz-popup-eyebrow::before, .quiz-popup-eyebrow::after { content: ''; height: 1px; flex: 1; background: linear-gradient(to right, transparent, rgba(212,217,148,.3)); }
.quiz-popup-eyebrow::after { background: linear-gradient(to left, transparent, rgba(212,217,148,.3)); }
.quiz-popup-title { font-family: 'Bodoni Moda', Georgia, serif; font-size: 1.9rem; color: #fff; line-height: 1.18; letter-spacing: -.02em; margin-bottom: 8px; }
.quiz-popup-title em { color: var(--blush); font-style: italic; }
.quiz-popup-sub { font-family: 'Jost', sans-serif; font-size: .86rem; color: rgba(255,255,255,.38); margin-bottom: 24px; line-height: 1.6; }
.quiz-popup-perks { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; padding: 16px 18px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); border-radius: 14px; }
.quiz-popup-perk { display: flex; align-items: center; gap: 10px; font-family: 'Jost', sans-serif; font-size: .85rem; font-weight: 500; color: rgba(255,255,255,.7); }
.quiz-popup-perk-check { width: 18px; height: 18px; border-radius: 50%; background: rgba(212,217,148,.1); border: 1px solid rgba(212,217,148,.28); color: var(--chartreuse); font-size: .55rem; font-weight: 700; display: grid; place-items: center; flex-shrink: 0; }
.quiz-popup-btn { display: block; width: 100%; padding: 15px; background: var(--chartreuse); color: var(--rose-dark); font-family: 'Jost', sans-serif; font-weight: 700; font-size: .9rem; letter-spacing: .02em; text-align: center; border: none; border-radius: 12px; cursor: pointer; text-decoration: none; transition: background var(--t-fast), transform var(--t-fast), box-shadow var(--t-fast); position: relative; overflow: hidden; margin-bottom: 12px; }
.quiz-popup-btn:hover { background: var(--lime-light); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(212,217,148,.3); }
.quiz-popup-btn::after { content: ''; position: absolute; inset: 0; background: linear-gradient(105deg, transparent 25%, rgba(255,255,255,.35) 50%, transparent 75%); transform: translateX(-120%); animation: shimmer-sweep 3.8s ease-in-out infinite 2s; }
.quiz-popup-login-line { font-family: 'Jost', sans-serif; text-align: center; font-size: .76rem; color: rgba(255,255,255,.24); }
.quiz-popup-login-line a { color: rgba(255,255,255,.48); text-decoration: underline; text-underline-offset: 3px; transition: color var(--t-fast); }
.quiz-popup-login-line a:hover { color: var(--chartreuse); }

/* ── Reveal Animations ───────────────────────────────────────────── */
.reveal { opacity: 0; transform: translateY(22px); transition: opacity .68s cubic-bezier(.4,0,.2,1), transform .68s cubic-bezier(.4,0,.2,1); }
.reveal.visible { opacity: 1; transform: translateY(0); }
.reveal-left { opacity: 0; transform: translateX(-28px); transition: opacity .68s cubic-bezier(.4,0,.2,1), transform .68s cubic-bezier(.4,0,.2,1); }
.reveal-right { opacity: 0; transform: translateX(28px); transition: opacity .68s cubic-bezier(.4,0,.2,1), transform .68s cubic-bezier(.4,0,.2,1); }
.reveal-left.visible, .reveal-right.visible { opacity: 1; transform: translate(0); }
.reveal-delay-1 { transition-delay: .1s; }
.reveal-delay-2 { transition-delay: .2s; }
.reveal-delay-3 { transition-delay: .3s; }
.reveal-delay-4 { transition-delay: .45s; }

/* ── Responsive ──────────────────────────────────────────────────── */
@media (max-width: 1100px) {
  .why-grid { grid-template-columns: repeat(2,1fr); }
  .sub-plans-grid { grid-template-columns: 1fr; max-width: 420px; margin: 48px auto 0; }
  .tier-cards { grid-template-columns: 1fr; max-width: 420px; margin: 48px auto 0; }
  .hero-counter { display: none; }
  .hero-slider { grid-template-columns: 48fr 52fr; }
}
@media (max-width: 900px) {
  .hero-slider { grid-template-columns: 50fr 50fr; min-height: 460px; max-height: 680px; }
  .hero-title { font-size: clamp(2rem, 5vw, 3rem); }
  .hero-desc { font-size: .88rem; margin-bottom: 24px; }
  .hero-stats { gap: 18px; }
  .hero-controls { right: 50%; }
  .quiz-banner-inner { grid-template-columns: 1fr; }
  .quiz-banner-actions { align-items: flex-start; }
  .deal-inner { grid-template-columns: 1fr; gap: 28px; padding: 28px 24px 36px; }
  .deal-visual { max-width: 320px; margin: 0 auto; max-height: none; }

  .guide-grid { grid-template-columns: repeat(2,1fr); }
  .guide-card-img.featured { grid-column: span 2; }
  .new-drop-grid { grid-template-columns: repeat(2,1fr); }
  .gallery-grid { grid-template-columns: repeat(2,1fr); grid-template-rows: auto; }
  .gallery-item.featured { grid-column: span 2; grid-row: span 1; height: 280px; }
  .hero-pin { display: none; }
}
/* Mobile: image fills full section, text overlaid at bottom */
@media (max-width: 640px) {
  .hero-slider {
    display: block;
    position: relative;
    height: clamp(440px, 115vw, 580px);
    min-height: auto;
    max-height: none;
    overflow: hidden;
  }
  .hero-img-pane {
    position: absolute;
    inset: 0;
    height: 100%;
    max-height: none;
  }
  .hero-content-layer {
    position: absolute;
    inset: 0;
    z-index: 2;
    padding: 0 24px 72px;
    justify-content: flex-end;
    background: linear-gradient(
      to top,
      rgba(28,20,22,.95) 0%,
      rgba(28,20,22,.82) 30%,
      rgba(28,20,22,.48) 58%,
      rgba(28,20,22,.12) 78%,
      transparent 100%
    );
  }
  .hero-content-layer::after { display: none; }
  .hero-eyebrow { font-size: .76rem; margin-bottom: 12px; }
  .hero-title { font-size: clamp(2.4rem, 9.5vw, 3.2rem); margin-bottom: 12px; }
  .hero-desc { font-size: .92rem; margin-bottom: 18px; max-width: none; color: rgba(255,255,255,.75); }
  .hero-stats { gap: 16px; padding-top: 16px; flex-wrap: wrap; }
  .hero-stat-num { font-size: 1.35rem; }
  .hero-actions { gap: 10px; margin-bottom: 20px; }
  .hero-actions .btn { padding: 13px 22px; font-size: .9rem; }
  .hero-controls { right: 20px; bottom: 20px; left: 20px; z-index: 10; }
  .hero-arrow { width: 38px; height: 38px; font-size: .88rem; }
  .hero-dot { height: 2.5px; width: 20px; }
  .hero-dot.is-active { width: 36px; }
  .hero-pin { display: none; }
  .why-section::before { display: none; }
  .why-header-wrap { flex-direction: column; align-items: flex-start; gap: 12px; }
  .why-grid { grid-template-columns: 1fr; }
  .why-media { height: 200px; }
  .why-ghost-num { font-size: 3.8rem; }
  .new-drop-grid { grid-template-columns: 1fr 1fr; gap: 12px; }

  .guide-grid { grid-template-columns: 1fr; }
  .guide-card-img.featured { grid-column: span 1; aspect-ratio: 1/1.2; }
  .gallery-grid { grid-template-columns: 1fr 1fr; }
  .gallery-item.featured { grid-column: span 2; height: 220px; grid-row: span 1; }
  .sub-plans-grid { max-width: none; }
  .tier-cards { max-width: none; }
  .quiz-popup-body { padding: 28px 22px 24px; }
  .quiz-popup-title { font-size: 1.62rem; }

  .deal-visual { max-width: none; max-height: none; }
}
/* Touch device: larger tap targets */
@media (hover: none) {
  .hero-arrow { width: 46px; height: 46px; }
}

/* ── Skeleton Loading ───────────────────────────────────────────── */
@keyframes sk-shimmer {
  0%   { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

#page-skeleton {
  position: fixed;
  top: 0; left: 0; right: 0;
  height: 100vh;
  z-index: 99999;
  display: flex;
  flex-direction: column;
  pointer-events: none;
  transition: opacity .45s ease;
}
#page-skeleton.sk-out { opacity: 0; }

.sk-shimmer-layer {
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.18) 50%, transparent 100%);
  animation: sk-shimmer 1.7s ease-in-out infinite;
  pointer-events: none;
}

/* Announcement bar */
.sk-announcement {
  height: 38px;
  background: #1C1416;
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
}
.sk-announcement .sk-shimmer-layer {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.07), transparent);
}

/* Nav */
.sk-nav {
  height: 58px;
  background: rgba(250,246,243,.98);
  border-bottom: 1px solid #EDDCD8;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
}
.sk-nav .sk-shimmer-layer {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.72), transparent);
}
.sk-nav-inner {
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
.sk-logo {
  width: 120px; height: 18px;
  background: #EDDCD8;
  border-radius: 4px;
  flex-shrink: 0;
}
.sk-nav-links { display: flex; gap: 14px; align-items: center; }
.sk-nav-pill {
  height: 9px;
  background: #EDDCD8;
  border-radius: 99px;
}
.sk-nav-pill:nth-child(1) { width: 58px; }
.sk-nav-pill:nth-child(2) { width: 74px; }
.sk-nav-pill:nth-child(3) { width: 88px; }
.sk-nav-pill:nth-child(4) { width: 62px; }
.sk-nav-pill:nth-child(5) { width: 78px; }
.sk-nav-pill:nth-child(6) { width: 92px; }
.sk-nav-pill:nth-child(7) { width: 66px; }
.sk-nav-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
.sk-search { width: 110px; height: 30px; background: #EDDCD8; border-radius: 99px; }
.sk-nav-btn { height: 30px; background: #EDDCD8; border-radius: 99px; }
.sk-nav-btn:nth-of-type(1) { width: 62px; }
.sk-nav-btn:nth-of-type(2) { width: 72px; }
.sk-cart { width: 74px; height: 30px; background: #EDDCD8; border-radius: 99px; }

/* Hero */
.sk-hero {
  flex: 1;
  display: grid;
  grid-template-columns: 46fr 54fr;
  overflow: hidden;
  min-height: 0;
}

/* Left text panel */
.sk-hero-text {
  background: #6B2A30;
  padding: clamp(24px, 4.5vw, 60px);
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  overflow: hidden;
}
.sk-hero-text .sk-shimmer-layer {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.07), transparent);
}
.sk-eyebrow {
  width: 150px; height: 9px;
  background: rgba(212,217,148,.28);
  border-radius: 99px;
  margin-bottom: 24px;
}
.sk-title { border-radius: 8px; background: rgba(255,255,255,.13); margin-bottom: 14px; }
.sk-title-1 { width: 88%; height: 54px; }
.sk-title-2 { width: 70%; height: 54px; }
.sk-title-3 { width: 56%; height: 54px; margin-bottom: 22px; }
.sk-desc { height: 9px; background: rgba(255,255,255,.08); border-radius: 99px; margin-bottom: 11px; }
.sk-desc-1 { width: 88%; }
.sk-desc-2 { width: 62%; margin-bottom: 28px; }
.sk-btns { display: flex; gap: 10px; margin-bottom: 36px; }
.sk-btn-hero {
  height: 46px; border-radius: 99px;
}
.sk-btn-hero-primary { width: 160px; background: rgba(212,217,148,.28); }
.sk-btn-hero-outline { width: 140px; background: rgba(255,255,255,.09); }
.sk-stats-row {
  display: flex; gap: 28px;
  padding-top: 22px;
  border-top: 1px solid rgba(255,255,255,.1);
}
.sk-stat-block { display: flex; flex-direction: column; gap: 7px; }
.sk-stat-num { width: 52px; height: 20px; background: rgba(255,255,255,.15); border-radius: 4px; }
.sk-stat-label { width: 88px; height: 8px; background: rgba(255,255,255,.07); border-radius: 99px; }

/* Right image panel */
.sk-hero-img {
  background: #4A1F24;
  position: relative;
  overflow: hidden;
}
.sk-hero-img .sk-shimmer-layer {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.05), transparent);
  animation-delay: .4s;
}

/* Responsive */
@media (max-width: 1100px) {
  .sk-nav { height: 54px; }
  .sk-nav-pill:nth-child(n+6) { display: none; }
}
@media (max-width: 768px) {
  .sk-nav { height: 52px; }
  .sk-nav-links { display: none; }
  .sk-search { width: 80px; }
}
@media (max-width: 640px) {
  .sk-announcement { height: 36px; }
  .sk-hero {
    display: block;
    position: relative;
  }
  .sk-hero-img {
    position: absolute;
    inset: 0;
  }
  .sk-hero-text {
    position: relative;
    z-index: 1;
    height: 100%;
    background: linear-gradient(to top, rgba(28,20,22,.95) 0%, rgba(28,20,22,.6) 50%, rgba(28,20,22,.18) 80%, transparent 100%);
    justify-content: flex-end;
    padding-bottom: 72px;
  }
  .sk-title-1, .sk-title-2, .sk-title-3 { height: 42px; }
}
</style>
@endsection

@section('content')

{{-- ── Page Skeleton (nav + hero) ───────────────────────────────── --}}
<div id="page-skeleton" aria-hidden="true">

  {{-- Announcement bar --}}
  <div class="sk-announcement"><span class="sk-shimmer-layer"></span></div>

  {{-- Nav --}}
  <div class="sk-nav">
    <div class="sk-nav-inner">
      <div class="sk-logo"></div>
      <div class="sk-nav-links">
        <div class="sk-nav-pill"></div>
        <div class="sk-nav-pill"></div>
        <div class="sk-nav-pill"></div>
        <div class="sk-nav-pill"></div>
        <div class="sk-nav-pill"></div>
        <div class="sk-nav-pill"></div>
        <div class="sk-nav-pill"></div>
      </div>
      <div class="sk-nav-actions">
        <div class="sk-search"></div>
        <div class="sk-nav-btn"></div>
        <div class="sk-nav-btn"></div>
        <div class="sk-cart"></div>
      </div>
    </div>
    <span class="sk-shimmer-layer"></span>
  </div>

  {{-- Hero --}}
  <div class="sk-hero">
    <div class="sk-hero-text">
      <div class="sk-eyebrow"></div>
      <div class="sk-title sk-title-1"></div>
      <div class="sk-title sk-title-2"></div>
      <div class="sk-title sk-title-3"></div>
      <div class="sk-desc sk-desc-1"></div>
      <div class="sk-desc sk-desc-2"></div>
      <div class="sk-btns">
        <div class="sk-btn-hero sk-btn-hero-primary"></div>
        <div class="sk-btn-hero sk-btn-hero-outline"></div>
      </div>
      <div class="sk-stats-row">
        <div class="sk-stat-block"><div class="sk-stat-num"></div><div class="sk-stat-label"></div></div>
        <div class="sk-stat-block"><div class="sk-stat-num"></div><div class="sk-stat-label"></div></div>
        <div class="sk-stat-block"><div class="sk-stat-num"></div><div class="sk-stat-label"></div></div>
      </div>
      <span class="sk-shimmer-layer"></span>
    </div>
    <div class="sk-hero-img"><span class="sk-shimmer-layer"></span></div>
  </div>

</div>

<div class="home-page">

@php
  $hero        = data_get($siteContent, 'hero', []);
  $visibility  = data_get($siteContent, 'section_visibility', []);
  $deal        = data_get($siteContent, 'deal_of_the_day', []);
  $newDrops    = data_get($siteContent, 'new_drops', []);
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
  $heroSlide1 = $slotMedia('hero_slide_1',
      data_get($hero, 'image_url', 'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?auto=format&fit=crop&w=1600&q=85'),
      'Kominhoo Beauty');
  $heroSlide2 = $slotMedia('hero_slide_2',
      'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1600&q=85',
      'Korean Skincare Ritual');
  $heroSlide3 = $slotMedia('hero_slide_3',
      'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=1600&q=85',
      'Glowing Skin Results');
  $homeWhy1 = $slotMedia('why_1', 'https://images.unsplash.com/photo-1770732766528-d0e9fd0df233?auto=format&fit=crop&w=600&q=60', 'Luxury serum');
  $homeWhy2 = $slotMedia('why_2', 'https://images.unsplash.com/photo-1679394270597-e90694d70350?auto=format&fit=crop&w=600&q=60', 'K-beauty');
  $homeWhy3 = $slotMedia('why_3', 'https://images.unsplash.com/photo-1745141063798-7fa04698ea80?auto=format&fit=crop&w=600&q=60', 'Fast delivery');
  $homeWhy4 = $slotMedia('why_4', 'https://images.unsplash.com/photo-1748543669178-efd3de4e64e0?auto=format&fit=crop&w=600&q=60', 'Earn and glow');
  $whySection = data_get($siteContent, 'why_section', []);
  $quizBanner = data_get($siteContent, 'quiz_cta_banner', []);
  $communitySection = data_get($siteContent, 'community_section', []);
  $newsletterSection = data_get($siteContent, 'newsletter_section', []);
  $quizPopup = data_get($siteContent, 'quiz_popup', []);
  $quizPopupPerks = data_get($quizPopup, 'perks', [
    'Tailored to your unique skin type & concerns',
    'Expert-backed K-beauty recommendations',
    'Shop your complete routine instantly',
  ]);
  $whyCardDefaults = [
    ['icon' => '', 'title' => 'Skin-Quiz Matched',  'desc' => 'Every recommendation is personalized to your unique skin profile.'],
    ['icon' => '', 'title' => 'Authentic K-Beauty', 'desc' => 'Sourced directly from top Korean brands — 100% authentic, every time.'],
    ['icon' => '', 'title' => 'Fast Delivery',       'desc' => 'Free shipping on orders over ₦50,000. Subscribers always ship free.'],
    ['icon' => '', 'title' => 'Earn & Glow',         'desc' => 'Earn loyalty points on every order. Redeem for products and exclusive perks.'],
  ];
  $whyCards = [];
  foreach ($whyCardDefaults as $i => $default) {
    $whyCards[] = [
      'icon'  => data_get($whySection, "cards.$i.icon",  $default['icon']),
      'title' => data_get($whySection, "cards.$i.title", $default['title']),
      'desc'  => data_get($whySection, "cards.$i.desc",  $default['desc']),
    ];
  }
@endphp

{{-- ── Hero Slider ──────────────────────────────────────────────── --}}
@if(data_get($hero, 'visible', true))
<section class="hero-slider" aria-label="Hero" id="hero-slider">

  {{-- Static text panel (never changes between slides) --}}
  <div class="hero-content-layer">
    <div class="hero-text">
      <div class="hero-eyebrow">{{ data_get($hero, 'eyebrow', 'Personalized Korean Beauty') }}</div>
      <h1 class="hero-title">
        {{ data_get($hero, 'title_line_1', 'Your Skin,') }}<br>
        <em>{{ data_get($hero, 'title_line_2', 'Decoded.') }}</em><br>
        {{ data_get($hero, 'title_line_3', 'Perfected.') }}
      </h1>
      <p class="hero-desc">{{ data_get($hero, 'description', 'Authentic K-beauty formulas, matched to your unique skin profile. Delivered to your door across Nigeria.') }}</p>
      <div class="hero-actions">
        <a href="{{ data_get($hero, 'primary_cta_link', route('quiz')) }}" class="btn btn-primary btn-xl">
          {{ data_get($hero, 'primary_cta_text', ' Take the Skin Quiz') }}
        </a>
        <a href="{{ data_get($hero, 'secondary_cta_link', route('shop')) }}" class="btn btn-outline btn-xl" style="color:#fff" onmouseover="this.style.color='#D4D994'" onmouseout="this.style.color='#fff'">
          {{ data_get($hero, 'secondary_cta_text', 'Browse Products') }}
        </a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat">
          <div class="hero-stat-num">{{ data_get($hero, 'stat_1_num', '50K+') }}</div>
          <div class="hero-stat-label">{{ data_get($hero, 'stat_1_label', 'Happy Skin Lovers') }}</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-num">{{ data_get($hero, 'stat_2_num', '200+') }}</div>
          <div class="hero-stat-label">{{ data_get($hero, 'stat_2_label', 'Curated Products') }}</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-num">{{ data_get($hero, 'stat_3_num', '4.8★') }}</div>
          <div class="hero-stat-label">{{ data_get($hero, 'stat_3_label', 'Average Rating') }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Image pane: only images + pins crossfade here --}}
  <div class="hero-img-pane" id="hero-img-pane">

    {{-- Image 1 --}}
    <div class="hero-img-slide is-active" aria-hidden="false">
      <img class="hero-slide-img" src="{{ $heroSlide1['url'] }}" alt="{{ $heroSlide1['alt'] }}" fetchpriority="high">
      <div class="hero-pin" style="top:36%;left:7%">
        <div class="hero-pin-dot"></div>
        <div class="hero-pin-label">
          <span class="hero-pin-name">{{ data_get($hero, 'slide_1_pin_a_name', 'Glowing Skin Kit') }}</span>
          <span class="hero-pin-detail">{{ data_get($hero, 'slide_1_pin_a_detail', '3-step ritual · Best Value') }}</span>
        </div>
      </div>
      <div class="hero-pin hero-pin--b" style="top:63%;left:46%">
        <div class="hero-pin-dot"></div>
        <div class="hero-pin-label">
          <span class="hero-pin-name">{{ data_get($hero, 'slide_1_pin_b_name', 'Most Loved ✦') }}</span>
          <span class="hero-pin-detail">{{ data_get($hero, 'slide_1_pin_b_detail', '4.9★ · 1,200 reviews') }}</span>
        </div>
      </div>
    </div>

    {{-- Image 2 --}}
    <div class="hero-img-slide" aria-hidden="true">
      <img class="hero-slide-img" src="{{ $heroSlide2['url'] }}" alt="{{ $heroSlide2['alt'] }}" loading="lazy">
      <div class="hero-pin" style="top:27%;left:9%">
        <div class="hero-pin-dot"></div>
        <div class="hero-pin-label">
          <span class="hero-pin-name">{{ data_get($hero, 'slide_2_pin_a_name', 'COSRX Snail 96') }}</span>
          <span class="hero-pin-detail">{{ data_get($hero, 'slide_2_pin_a_detail', 'Best Seller · Free shipping') }}</span>
        </div>
      </div>
      <div class="hero-pin hero-pin--b" style="top:60%;left:50%">
        <div class="hero-pin-dot"></div>
        <div class="hero-pin-label">
          <span class="hero-pin-name">{{ data_get($hero, 'slide_2_pin_b_name', '100% Authentic') }}</span>
          <span class="hero-pin-detail">{{ data_get($hero, 'slide_2_pin_b_detail', 'Sourced from Seoul') }}</span>
        </div>
      </div>
    </div>

    {{-- Image 3 --}}
    <div class="hero-img-slide" aria-hidden="true">
      <img class="hero-slide-img" src="{{ $heroSlide3['url'] }}" alt="{{ $heroSlide3['alt'] }}" loading="lazy">
      <div class="hero-pin" style="top:30%;left:8%">
        <div class="hero-pin-dot"></div>
        <div class="hero-pin-label">
          <span class="hero-pin-name">{{ data_get($hero, 'slide_3_pin_a_name', '14-Step Skin Quiz') }}</span>
          <span class="hero-pin-detail">{{ data_get($hero, 'slide_3_pin_a_detail', 'Free · Takes 60 seconds') }}</span>
        </div>
      </div>
      <div class="hero-pin hero-pin--b" style="top:64%;left:44%">
        <div class="hero-pin-dot"></div>
        <div class="hero-pin-label">
          <span class="hero-pin-name">{{ data_get($hero, 'slide_3_pin_b_name', 'Glass Skin Goal') }}</span>
          <span class="hero-pin-detail">{{ data_get($hero, 'slide_3_pin_b_detail', '1,200+ five-star reviews') }}</span>
        </div>
      </div>
    </div>

  </div>

  {{-- Slide counter --}}


  {{-- Navigation --}}
  <div class="hero-controls" aria-label="Slider navigation">
    <div class="hero-dots" role="tablist">
      <button class="hero-dot is-active" role="tab" aria-selected="true"  aria-label="Slide 1"></button>
      <button class="hero-dot"            role="tab" aria-selected="false" aria-label="Slide 2"></button>
      <button class="hero-dot"            role="tab" aria-selected="false" aria-label="Slide 3"></button>
    </div>
    <div class="hero-arrows">
      <button class="hero-arrow hero-prev" aria-label="Previous slide">&#8592;</button>
      <button class="hero-arrow hero-next" aria-label="Next slide">&#8594;</button>
    </div>
  </div>

</section>
@endif


{{-- ── Why Kominhoo ─────────────────────────────────────────────── --}}
@if(data_get($visibility, 'why_section', true))
<section class="why-section" aria-labelledby="why-heading">
  <div class="container">
    <div class="why-header-wrap">
      <div>
        <div class="why-kicker">{{ data_get($whySection, 'kicker', 'Why Kominhoo') }}</div>
        <h2 class="why-heading reveal" id="why-heading">
          {{ data_get($whySection, 'heading_line_1', 'Luxury skincare, refined for') }}<br>{{ data_get($whySection, 'heading_line_2', 'your routine.') }}
        </h2>
      </div>
      <p class="why-lead reveal reveal-delay-1">
        {{ data_get($whySection, 'lead', 'Curated formulas, authentic sourcing, and elevated service for a more considered skincare ritual.') }}
      </p>
    </div>
    @php $whyImgs = [$homeWhy1, $homeWhy2, $homeWhy3, $homeWhy4]; $whyDelays = ['', ' reveal-delay-1', ' reveal-delay-2', ' reveal-delay-3']; @endphp
    <div class="why-grid">
      @foreach($whyCards as $wi => $wc)
      <div class="why-card reveal{{ $whyDelays[$wi] ?? '' }}">
        <div class="why-num">0{{ $wi + 1 }}</div>
        <div class="why-media">
          <img src="{{ $whyImgs[$wi]['url'] }}" alt="{{ $whyImgs[$wi]['alt'] }}" loading="lazy">
          <div class="why-img-text">
            <span class="why-icon">{{ $wc['icon'] }}</span>
            <div class="why-title">{{ $wc['title'] }}</div>
          </div>
        </div>
        <div class="why-body">
          <div class="why-desc">{{ $wc['desc'] }}</div>
          <div class="why-ghost-num">0{{ $wi + 1 }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ── Quiz CTA Banner ──────────────────────────────────────────── --}}
@if(data_get($visibility, 'quiz_cta_banner', true))
<section class="quiz-banner" aria-label="Skin quiz call to action">
  <div class="quiz-banner-inner">
    <div class="quiz-banner-text reveal-left">
      <div class="quiz-banner-eyebrow">{{ data_get($quizBanner, 'eyebrow', 'Free · 60 Seconds · No Account Needed') }}</div>
      <h2>{{ data_get($quizBanner, 'title_line_1', "Not sure where to start?") }}<br>{{ data_get($quizBanner, 'title_line_2', "We'll figure it out together.") }}</h2>
      <p>{{ data_get($quizBanner, 'description', 'Our 14-question Skin Quiz builds your perfect Korean routine in 60 seconds — personalized, science-backed, and completely free.') }}</p>
    </div>
    <div class="quiz-banner-actions reveal-right">
      <a href="{{ data_get($quizBanner, 'cta_link', route('quiz')) }}" class="btn btn-primary btn-lg">{{ data_get($quizBanner, 'cta_text', 'Start Skin Quiz — Free') }}</a>
      <div class="quiz-banner-meta">{{ data_get($quizBanner, 'meta', '60 secs · No account needed') }}</div>
    </div>
  </div>
</section>
@endif

{{-- ── Deal of the Day ──────────────────────────────────────────── --}}
@if(data_get($deal, 'visible', true))
@php
  $dealOrig    = (float) data_get($deal, 'original_price', 35000);
  $dealPrice   = (float) data_get($deal, 'deal_price', 29000);
  $dealSavePct = $dealOrig > 0 ? round((1 - $dealPrice / $dealOrig) * 100) : 0;
@endphp
<section class="deals-bg" aria-labelledby="deal-heading">
  <div class="container">
    <div class="deal-inner">
      <div class="deal-left reveal-left">
        <div class="deal-badge">⚡ {{ data_get($deal, 'badge', 'Deal of the Day') }}</div>
        <h2 class="deal-title" id="deal-heading">{{ data_get($deal, 'headline', "Today's Featured Ritual") }}</h2>
        <p class="deal-desc">{{ data_get($deal, 'description') }}</p>

        @if(data_get($deal, 'show_countdown', true))
        <div class="deal-countdown" aria-label="Countdown timer">
          <div class="count-block">
            <div class="count-num" id="count-h">23</div>
            <div class="count-label">Hours</div>
          </div>
          <div class="deal-countdown-sep" aria-hidden="true">:</div>
          <div class="count-block">
            <div class="count-num" id="count-m">59</div>
            <div class="count-label">Mins</div>
          </div>
          <div class="deal-countdown-sep" aria-hidden="true">:</div>
          <div class="count-block">
            <div class="count-num" id="count-s">00</div>
            <div class="count-label">Secs</div>
          </div>
        </div>
        @endif

        <div class="deal-price-row">
          <div>
            <div class="deal-price-new">₦{{ number_format($dealPrice) }}</div>
            <div class="deal-price-old">₦{{ number_format($dealOrig) }}</div>
          </div>
          @if($dealProduct)
          <button class="btn btn-dark btn-lg" onclick="addToCart({{ $dealProduct['id'] }})">
            Add to Cart — Save ₦{{ number_format(max(0, (float) data_get($deal, 'original_price', 0) - (float) data_get($deal, 'deal_price', 0))) }}
          </button>
          @endif
        </div>

        <div class="deal-stock-bar">
          <div class="deal-stock-label">
            <span>⚡ Stock running low</span>
            <span>{{ data_get($deal, 'units_remaining', 47) }} units left</span>
          </div>
          <div class="deal-stock-track"><div class="deal-stock-fill"></div></div>
        </div>
      </div>

      <div class="deal-visual reveal-right">
        <img src="{{ data_get($dealProduct, 'images.0', data_get($hero, 'image_url')) }}" alt="{{ data_get($dealProduct, 'name', 'Luxury skincare') }}" loading="lazy">
        @if($dealSavePct > 0)
        <div class="deal-save-badge">Save {{ $dealSavePct }}%</div>
        @endif
        <div class="deal-price-tag" aria-label="Product stats">
          <div class="deal-stat-chip">
            <div class="deal-chip-label">Rating</div>
            <div class="deal-chip-val">{{ number_format((float) data_get($dealProduct, 'rating', 4.9), 1) }} / 5</div>
          </div>
          <div class="deal-stat-chip">
            <div class="deal-chip-label">Reviews</div>
            <div class="deal-chip-val">{{ number_format((int) data_get($dealProduct, 'review_count', 0)) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

{{-- ── Recommended For You ──────────────────────────────────────── --}}
@if(data_get($visibility, 'recommended_for_you', true))
<section class="section rec-section-bg" aria-labelledby="rec-heading">
  <div class="container">
    <div class="sec-row">
      <div>
        <div class="sec-kicker">Personalized for You</div>
        <h2 class="sec-heading reveal" id="rec-heading">Recommended <em class="serif" style="font-weight:400;font-style:italic">For You</em></h2>
        <p class="sec-sub">Based on your skin profile — <a href="{{ route('quiz') }}" style="color:var(--rose);font-weight:700">take the quiz</a> for perfect matches</p>
      </div>
      <div class="carousel-nav">
        <button class="carousel-btn" onclick="scrollTrack('rec-track',-1)" aria-label="Scroll left">←</button>
        <button class="carousel-btn" onclick="scrollTrack('rec-track',1)"  aria-label="Scroll right">→</button>
      </div>
    </div>
    <div class="scroll-track" id="rec-track"></div>
  </div>
</section>
@endif

{{-- ── New Drops ────────────────────────────────────────────────── --}}
@if(data_get($visibility, 'new_drops_grid', true))
<section class="section new-drops-bg" aria-labelledby="new-drops-heading">
  <div class="container">
    <div class="sec-row">
      <div>
        <div class="sec-kicker">
          <span style="color:var(--rose);font-size:.55rem">●</span>
          {{ data_get($newDrops, 'eyebrow', 'New This Quarter') }}
        </div>
        <h2 class="sec-heading reveal" id="new-drops-heading">{{ data_get($newDrops, 'title', 'Latest Drops') }}</h2>
      </div>
      <a href="{{ route('shop') }}" class="btn btn-outline btn-sm">View All New →</a>
    </div>
    <div class="new-drop-grid">
      @foreach($newDropProducts as $product)
        <a href="{{ route('product', $product['id']) }}" class="product-card reveal">
          <div class="product-img-wrap">
            <img class="product-img" src="{{ $product['images'][0] ?? '' }}" alt="{{ $product['name'] ?? '' }}" loading="lazy">
          </div>
          <div class="product-info">
            <div class="product-brand">{{ $product['brand'] ?? '' }}</div>
            <div class="product-name">{{ $product['name'] ?? '' }}</div>
            <div class="product-price">₦{{ number_format((float) ($product['price'] ?? 0)) }}</div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ── Bundle Kits ──────────────────────────────────────────────── --}}
@if(data_get($visibility, 'bundle_kits', true))
<section class="section" style="background:#fff" aria-labelledby="bundles-heading">
  <div class="container">
    <div style="text-align:center;margin-bottom:48px">
      <div class="sec-kicker" style="justify-content:center">Curated Sets</div>
      <h2 class="sec-heading reveal" id="bundles-heading">Shop by <em class="serif" style="font-weight:400;font-style:italic">Bundle</em></h2>
      <p class="sec-sub" style="max-width:44ch;margin:0 auto">Complete routines matched to your biggest skin concern — save up to 25%</p>
    </div>
    <div class="bundle-grid" id="bundleGrid"></div>
    <div style="text-align:center;margin-top:36px">
      <a href="{{ route('shop', ['tab' => 'bundles']) }}" class="btn btn-outline" style="font-family:'Jost',sans-serif;font-weight:700;padding:13px 36px;font-size:.88rem">
        View All Bundles &rarr;
      </a>
    </div>
  </div>
</section>
@endif

{{-- ── Buying Guides ────────────────────────────────────────────── --}}
@if(data_get($visibility, 'buying_guides', true))
<section class="section" style="background:var(--cream)" aria-labelledby="guides-heading">
  <div class="container">
    <div class="sec-row" style="margin-bottom:32px">
      <div>
        <div class="sec-kicker">Expert Curation</div>
        <h2 class="sec-heading reveal" id="guides-heading">Buying <em class="serif" style="font-weight:400;font-style:italic">Guides</em></h2>
      </div>
    </div>
    <div class="guide-grid" id="guideGrid"></div>
  </div>
</section>
@endif

{{-- ── Community Gallery ────────────────────────────────────────── --}}
@if(data_get($visibility, 'community_gallery', true))
<section class="section" style="background:#fff" aria-labelledby="community-heading">
  <div class="container">
    <div style="text-align:center;margin-bottom:40px">
      <div class="sec-kicker" style="justify-content:center">{{ data_get($communitySection, 'kicker', 'Real Results') }}</div>
      <h2 class="sec-heading reveal" id="community-heading">{{ data_get($communitySection, 'heading_line_1', 'The Kominhoo') }} <em class="serif" style="font-weight:400;font-style:italic">{{ data_get($communitySection, 'heading_line_2', 'Community') }}</em></h2>
      <p class="sec-sub" style="max-width:44ch;margin:0 auto">{{ data_get($communitySection, 'description', 'Real transformations from real customers. Share your glow-up with #KominhooSkin') }}</p>
    </div>
    @php
      $communityDelay = ['', ' reveal-delay-1', ' reveal-delay-2', ' reveal-delay-1', ' reveal-delay-2', ' reveal-delay-3', ' reveal-delay-4'];
    @endphp
    <div class="gallery-grid" style="margin-bottom:32px">
      @forelse(($communityGalleryItems ?? []) as $i => $item)
        <a href="{{ route('community') }}"
           class="gallery-item {{ $i === 0 ? 'featured' : '' }} reveal{{ $communityDelay[$i] ?? '' }}">
          <img src="{{ $item['url'] }}" alt="{{ $item['alt'] ?? 'Community post' }}" loading="lazy">
          <div class="gallery-item-overlay"><span style="color:#fff;font-size:{{ $i === 0 ? '1.8rem' : '1.5rem' }}">♥</span></div>
        </a>
      @empty
        <a href="{{ route('community') }}" class="gallery-item featured reveal" style="display:grid;place-items:center;background:var(--blush-pale);border:1px solid var(--border)">
          <div style="text-align:center;padding:24px;max-width:32ch;color:var(--rose-dark)">
            <div style="font-family:'Bodoni Moda',Georgia,serif;font-size:1.4rem;margin-bottom:10px">Be the first to share</div>
            <div style="font-family:'Jost',sans-serif;font-size:.9rem;line-height:1.6;color:rgba(28,20,22,.65)">No community posts yet. Tap to post your glow-up.</div>
          </div>
        </a>
      @endforelse
    </div>
    <div style="text-align:center">
      <a href="{{ route('community') }}" class="btn btn-dark">View Community Gallery →</a>
    </div>
  </div>
</section>
@endif

{{-- ── Subscription Plans ───────────────────────────────────────── --}}
@if(data_get($visibility, 'subscription_cta', true))
@php
  $subSection = data_get($siteContent, 'subscription_section', []);
  $subHeading = data_get($subSection, 'heading', 'Your Skin Expert, On Autopilot');
  $subHeadingParts = explode(',', $subHeading, 2);
@endphp
<section class="sub-section" aria-labelledby="sub-heading">
  <div class="container">
    <div style="text-align:center">
      <div class="sec-kicker" style="justify-content:center">{{ data_get($subSection, 'kicker', 'Quarterly Subscription') }}</div>
      <h2 class="sec-heading reveal" id="sub-heading">
        @if(count($subHeadingParts) === 2)
          {{ trim($subHeadingParts[0]) }}, <em class="serif" style="font-weight:400;font-style:italic">{{ trim($subHeadingParts[1]) }}</em>
        @else
          {{ $subHeading }}
        @endif
      </h2>
      <p class="sec-sub" style="max-width:52ch;margin:0 auto">{{ data_get($subSection, 'description', 'Expert-curated routines delivered every 3 months — personalized, free shipping, easy to pause or cancel.') }}</p>
    </div>
    <div class="sub-plans-grid">
      @forelse($subscriptionPlans as $planIdx => $plan)
      @php
        $planFeatured  = $plan['is_popular'] ?? false;
        $planDelayClass = $planIdx === 0 ? '' : ($planIdx === 1 ? ' reveal-delay-2' : ' reveal-delay-4');
        $planBadge     = $planFeatured ? '⭐ Most Popular' : ($plan['badge'] ?? ucfirst($plan['billing_cycle'] ?? 'Standard'));
        $planPeriod    = $plan['frequency_label'] ?? 'per month';
        $planProducts  = $plan['products_count'] ?? '';
      @endphp
      <div class="sub-plan-card {{ $planFeatured ? 'featured' : '' }} reveal{{ $planDelayClass }}">
        <div class="sub-plan-tag">{{ $planBadge }}</div>
        <div class="sub-plan-name">{{ $plan['name'] }}</div>
        <div class="sub-plan-price">₦{{ number_format($plan['price']) }}</div>
        <div class="sub-plan-period">{{ $planPeriod }}{{ $planProducts ? ' · ' . $planProducts . ' products' : '' }}</div>
        <div class="sub-plan-hr"></div>
        <div class="sub-plan-features">
          @foreach($plan['features'] ?? [] as $feat)
          <div class="sub-plan-feature"><div class="sub-plan-check"><span class="sub-plan-check-icon">✓</span></div> {{ $feat }}</div>
          @endforeach
        </div>
        <a href="{{ route('shop', ['tab' => 'subscription']) }}" class="btn {{ $planFeatured ? 'btn-primary' : 'btn-outline' }}" style="width:100%;justify-content:center">{{ $planFeatured ? 'Subscribe Now →' : 'Get Started' }}</a>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);font-family:'Jost',sans-serif;">
        No subscription plans available yet.
      </div>
      @endforelse
    </div>
  </div>
</section>
@endif

{{-- ── Loyalty Tiers ────────────────────────────────────────────── --}}
@if(data_get($visibility, 'loyalty_tiers', true))
@php
  $loyaltySection = data_get($siteContent, 'loyalty_section', []);
  $loyaltyHeading = data_get($loyaltySection, 'heading', 'Glow More, Earn More');
  $loyaltyHeadingParts = explode(',', $loyaltyHeading, 2);
@endphp
<section class="loyalty-section" aria-labelledby="loyalty-heading">
  <div class="container">
    <div style="text-align:center;margin-bottom:0">
      <div class="sec-kicker" style="justify-content:center">{{ data_get($loyaltySection, 'kicker', 'Loyalty Program') }}</div>
      <h2 class="sec-heading reveal" id="loyalty-heading">
        @if(count($loyaltyHeadingParts) === 2)
          {{ trim($loyaltyHeadingParts[0]) }}, <em class="serif" style="font-weight:400;font-style:italic">{{ trim($loyaltyHeadingParts[1]) }}</em>
        @else
          {{ $loyaltyHeading }}
        @endif
      </h2>
      <p class="sec-sub" style="max-width:48ch;margin:0 auto">{{ data_get($loyaltySection, 'description', 'Every purchase earns points. Every point unlocks rewards. The more you shop, the more you glow.') }}</p>
    </div>
    <div class="tier-cards">
      @forelse($loyaltyTiers as $tierIdx => $tier)
      @php
        $tierRec        = $tier['is_popular'] ?? false;
        $tierDelayClass = $tierIdx === 0 ? '' : ($tierIdx === 1 ? ' reveal-delay-2' : ' reveal-delay-4');
        $tierReq        = ($tier['min_points'] ?? 0) > 0
                          ? number_format($tier['min_points']) . ' points to unlock'
                          : 'Join free upon first purchase or quiz';
        $tierCta        = $tierRec ? 'Start Earning →' : (($tier['min_points'] ?? 0) === 0 ? 'Start Your Journey' : 'Unlock ' . ($tier['name'] ?? 'Tier'));
      @endphp
      <div class="tier-card {{ $tierRec ? 'recommended' : '' }} reveal{{ $tierDelayClass }}">
        <div class="tier-icon-wrap"><div class="tier-icon">{{ $tier['icon'] ?? '✦' }}</div></div>
        <div class="tier-name">{{ $tier['name'] ?? 'Tier' }}</div>
        <div class="tier-req">{{ $tierReq }}</div>
        <div class="tier-divider"></div>
        <div class="tier-benefits">
          @foreach($tier['benefits'] ?? [] as $benefit)
          <div class="tier-benefit">{{ $benefit }}</div>
          @endforeach
        </div>
        <a href="{{ route('loyalty-program') }}" class="btn {{ $tierRec ? 'btn-primary' : 'btn-outline' }}" style="width:100%;justify-content:center">{{ $tierCta }}</a>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);font-family:'Jost',sans-serif;">
        Loyalty program coming soon.
      </div>
      @endforelse
    </div>
  </div>
</section>
@endif

{{-- ── Newsletter ───────────────────────────────────────────────── --}}
@if(data_get($visibility, 'newsletter_section', true))
<section class="newsletter-section" aria-labelledby="newsletter-heading">
  <div class="newsletter-inner">
    <div class="newsletter-eyebrow">{{ data_get($newsletterSection, 'eyebrow', 'Stay in the Know') }}</div>
    <h2 class="newsletter-heading reveal" id="newsletter-heading">
      {{ data_get($newsletterSection, 'heading_line_1', 'Get Skin Tips &') }}<br>{{ data_get($newsletterSection, 'heading_line_2', 'Exclusive Deals') }}
    </h2>
    <p class="newsletter-sub">
      {{ data_get($newsletterSection, 'subtext', 'Join 50,000+ Kominhoo skin lovers. Personalized tips, launch alerts, and subscriber-only deals — straight to your inbox.') }}
    </p>
    <div class="newsletter-form" role="form" aria-label="Newsletter signup">
      <input class="newsletter-input" type="email" placeholder="{{ data_get($newsletterSection, 'input_placeholder', 'Enter your email address…') }}" aria-label="Email address">
      <button class="newsletter-btn" onclick="showToast('📬','Subscribed! Welcome to the glow club ✨')">{{ data_get($newsletterSection, 'button_text', 'Subscribe →') }}</button>
    </div>
    <p class="newsletter-note">{{ data_get($newsletterSection, 'note', 'No spam. Unsubscribe any time.') }}</p>
  </div>
</section>
@endif

{{-- ── Welcome Quiz Popup ───────────────────────────────────────── --}}
@if(data_get($visibility, 'welcome_quiz_popup', true))
<div class="quiz-popup-overlay" id="quiz-popup-overlay" role="dialog" aria-modal="true" aria-labelledby="qp-title">
  <div class="quiz-popup" id="quiz-popup">
    <button class="quiz-popup-close" id="quiz-popup-close" aria-label="Close">✕</button>
    <div class="quiz-popup-banner">
       &nbsp;<strong>{{ data_get($quizPopup, 'banner_strong_1', 'Free') }}</strong> &nbsp;·&nbsp; {{ data_get($quizPopup, 'banner_text', 'No account needed') }} &nbsp;·&nbsp; <strong>{{ data_get($quizPopup, 'banner_strong_2', '60 seconds') }}</strong>
    </div>
    <div class="quiz-popup-body">
      <div class="quiz-popup-eyebrow">{{ data_get($quizPopup, 'eyebrow', 'Kominhoo Skin Quiz') }}</div>
      <h2 class="quiz-popup-title" id="qp-title">
        {{ data_get($quizPopup, 'title_line_1', 'Get Your') }} <em>{{ data_get($quizPopup, 'title_em', 'Personalized') }}</em><br>{{ data_get($quizPopup, 'title_line_2', 'Korean Skincare Routine') }}
      </h2>
      <p class="quiz-popup-sub">{{ data_get($quizPopup, 'subtitle', 'in 60 seconds — matched to your skin type, concerns & lifestyle') }}</p>
      <div class="quiz-popup-perks">
        @foreach($quizPopupPerks as $perk)
        <div class="quiz-popup-perk">
          <div class="quiz-popup-perk-check">✓</div>
          {{ $perk }}
        </div>
        @endforeach
      </div>
      <a href="{{ route('quiz') }}" class="quiz-popup-btn" id="quiz-popup-cta">{{ data_get($quizPopup, 'cta_text', ' Start Skin Quiz — Free') }}</a>
      <div class="quiz-popup-login-line">
        Already took the quiz? &nbsp;<a href="{{ route('login') }}" id="quiz-popup-login-link">Sign In →</a>
      </div>
    </div>
  </div>
</div>
@endif

{{-- ── Bundle Modal ──────────────────────────────────────────────── --}}
<div class="guide-modal-overlay" id="bundleModalOverlay" onclick="if(event.target===this)closeBundleModal()" role="dialog" aria-modal="true">
  <div class="guide-modal">
    <button class="guide-modal-close" onclick="closeBundleModal()" aria-label="Close">✕</button>
    <div class="guide-modal-header" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">
      <div style="flex:1;min-width:0;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
          <span id="bundleModalTag" style="font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:var(--lime);color:var(--black);padding:3px 10px;border-radius:20px;"></span>
        </div>
        <h2 id="bundleModalTitle" style="font-family:'Bodoni Moda',Georgia,serif;font-size:1.8rem;font-weight:600;margin-bottom:6px;"></h2>
        <p id="bundleModalDesc" style="font-family:'Jost',sans-serif;font-size:.9rem;color:var(--text-secondary);line-height:1.5;max-width:55ch;"></p>
      </div>
      <div style="text-align:right;flex-shrink:0;">
        <div style="font-size:1.5rem;font-weight:700;font-family:'Jost',sans-serif;color:var(--black);" id="bundleModalPrice"></div>
        <div id="bundleModalOrigPrice" style="font-size:.85rem;color:var(--text-secondary);text-decoration:line-through;margin-top:2px;"></div>
        <button id="bundleModalAddBtn" class="btn btn-primary" style="margin-top:12px;font-size:.82rem;padding:10px 20px;">Add Bundle to Cart</button>
      </div>
    </div>
    <div class="guide-modal-products bundle-modal-products"></div>
  </div>
</div>

{{-- ── Guide Products Modal ────────────────────────────────────── --}}
<div class="guide-modal-overlay" id="guideModalOverlay" onclick="if(event.target===this)closeGuideModal()" role="dialog" aria-modal="true">
  <div class="guide-modal">
    <button class="guide-modal-close" onclick="closeGuideModal()" aria-label="Close">✕</button>
    <div class="guide-modal-header">
      <div class="guide-modal-icon">📖</div>
      <div>
        <h2 id="guideModalTitle" style="font-family:'Bodoni Moda',Georgia,serif;font-size:1.8rem;font-weight:600;margin-bottom:6px;"></h2>
        <p id="guideModalDesc" style="font-family:'Jost',sans-serif;font-size:.9rem;color:var(--text-secondary);line-height:1.5;max-width:55ch;"></p>
      </div>
    </div>
    <div class="guide-modal-products"></div>
  </div>
</div>

</div>{{-- /.home-page --}}
@endsection

@section('scripts')
<script>
// ── Skeleton removal ─────────────────────────────────────────────
(function () {
  var sk = document.getElementById('page-skeleton');
  if (!sk) return;
  var done = false;
  function removeSkeleton() {
    if (done) return;
    done = true;
    sk.classList.add('sk-out');
    setTimeout(function () { sk && sk.remove(); }, 460);
  }
  var heroImg = document.querySelector('.hero-slide-img');
  if (heroImg) {
    if (heroImg.complete && heroImg.naturalWidth > 0) {
      removeSkeleton();
    } else {
      heroImg.addEventListener('load',  removeSkeleton, { once: true });
      heroImg.addEventListener('error', removeSkeleton, { once: true });
    }
  }
  setTimeout(removeSkeleton, 3000);
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

  // ── Hero Slider ──────────────────────────────────────────────
  (function () {
    const slider   = document.getElementById('hero-slider');
    if (!slider) return;
    const slides   = slider.querySelectorAll('.hero-img-slide');
    const dots     = slider.querySelectorAll('.hero-dot');
    const prevBtn  = slider.querySelector('.hero-prev');
    const nextBtn  = slider.querySelector('.hero-next');
    const counter  = slider.querySelector('.hero-counter-cur');
    const total    = slides.length;
    let cur        = 0;
    let timer;
    const DELAY    = 5800;

    function go(idx) {
      slides[cur].classList.remove('is-active');
      slides[cur].setAttribute('aria-hidden', 'true');
      dots[cur].classList.remove('is-active');
      dots[cur].setAttribute('aria-selected', 'false');
      cur = ((idx % total) + total) % total;
      slides[cur].classList.add('is-active');
      slides[cur].setAttribute('aria-hidden', 'false');
      dots[cur].classList.add('is-active');
      dots[cur].setAttribute('aria-selected', 'true');
      if (counter) counter.textContent = String(cur + 1).padStart(2, '0');
    }

    function startAuto() {
      clearInterval(timer);
      timer = setInterval(() => go(cur + 1), DELAY);
    }

    if (prevBtn) prevBtn.addEventListener('click', () => { go(cur - 1); startAuto(); });
    if (nextBtn) nextBtn.addEventListener('click', () => { go(cur + 1); startAuto(); });

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => { go(i); startAuto(); });
    });

    // Touch swipe
    let tx = 0;
    slider.addEventListener('touchstart', e => { tx = e.changedTouches[0].clientX; }, { passive: true });
    slider.addEventListener('touchend', e => {
      const dx = e.changedTouches[0].clientX - tx;
      if (Math.abs(dx) > 48) { go(cur + (dx < 0 ? 1 : -1)); startAuto(); }
    }, { passive: true });

    // Pause on hover (desktop)
    slider.addEventListener('mouseenter', () => clearInterval(timer));
    slider.addEventListener('mouseleave', () => startAuto());

    // Keyboard nav (when slider is focused)
    slider.addEventListener('keydown', e => {
      if (e.key === 'ArrowLeft')  { go(cur - 1); startAuto(); }
      if (e.key === 'ArrowRight') { go(cur + 1); startAuto(); }
    });

    startAuto();
  })();

  // ── Recommended carousel ─────────────────────────────────────
  const recTrack = document.getElementById('rec-track');
  if (recTrack) recTrack.innerHTML = PRODUCTS.slice(0, 8).map(p => buildProductCard(p, '200px')).join('');

  // ── Bundles grid ─────────────────────────────────────────────
  const bundleGrid = document.getElementById('bundleGrid');
  if (bundleGrid) bundleGrid.innerHTML = BUNDLES.slice(0, 3).map(b => `
    <div class="bundle-card reveal visible" onclick="openBundleModal(${b.id})" style="cursor:pointer">
      <img src="${b.image}" alt="${b.name}">
      <div class="bundle-overlay">
        <div class="bundle-tag"><span class="badge badge-lime">${b.tag || ''}</span></div>
        <div class="bundle-name">${b.name}</div>
        <div class="bundle-includes">${(b.products||[]).length} products · Complete routine</div>
        <div class="bundle-price">₦${b.price.toLocaleString()}${b.originalPrice ? ` <span style="font-size:.82rem;color:rgba(255,255,255,.5);text-decoration:line-through;font-weight:400">₦${b.originalPrice.toLocaleString()}</span>` : ''}</div>
        <button class="bundle-btn" onclick="event.stopPropagation();openBundleModal(${b.id})">View Bundle →</button>
      </div>
    </div>`).join('');

  window.openBundleModal = function(id) {
    const b = BUNDLES.find(x => x.id === id);
    if (!b) return;
    const overlay = document.getElementById('bundleModalOverlay');
    if (!overlay) return;
    overlay.querySelector('#bundleModalTitle').textContent = b.name;
    overlay.querySelector('#bundleModalDesc').textContent  = b.desc || '';
    overlay.querySelector('#bundleModalTag').textContent   = b.tag  || '';
    overlay.querySelector('#bundleModalPrice').textContent = '₦' + b.price.toLocaleString();
    const origEl = overlay.querySelector('#bundleModalOrigPrice');
    if (origEl) { origEl.textContent = b.originalPrice ? '₦' + b.originalPrice.toLocaleString() : ''; origEl.style.display = b.originalPrice ? '' : 'none'; }
    const products = (b.products || []).map(pid => PRODUCTS.find(p => p.id === pid)).filter(Boolean);
    const grid = overlay.querySelector('.bundle-modal-products');
    grid.innerHTML = products.length
      ? products.map(p => buildProductCard(p, '100%')).join('')
      : '<p style="color:var(--text-muted);text-align:center;padding:32px 0;grid-column:1/-1;">No products in this bundle yet.</p>';
    const addBtn = overlay.querySelector('#bundleModalAddBtn');
    if (addBtn) addBtn.onclick = function() { (b.products||[]).forEach(pid => addToCart(pid)); closeBundleModal(); };
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  window.closeBundleModal = function() {
    const overlay = document.getElementById('bundleModalOverlay');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = '';
  };
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeGuideModal(); closeBundleModal(); } });

  // ── Buying Guides ─────────────────────────────────────────────
  const guideGrid = document.getElementById('guideGrid');
  if (guideGrid) guideGrid.innerHTML = GUIDES.slice(0, 7).map((g, i) => `
    <div class="guide-card-img${i === 0 ? ' featured' : ''} reveal visible"
         style="background-image:url('${g.image}')"
         onclick="openGuideModal(${g.id})"
         role="button" tabindex="0" aria-label="${g.title}">
      <div class="guide-img-inner">
        <div class="guide-img-icon">${g.icon || '📖'}</div>
        <div class="guide-img-title">${g.title}</div>
        <div class="guide-img-desc">${g.desc}</div>
        <div class="guide-img-footer">
          <span class="guide-img-count">${(g.products||[]).length} products</span>
          <span class="guide-img-arrow">Explore →</span>
        </div>
      </div>
    </div>`).join('');

  // ── Guide modal ───────────────────────────────────────────────
  window.openGuideModal = function(id) {
    const g = GUIDES.find(x => x.id === id);
    if (!g) return;
    const overlay = document.getElementById('guideModalOverlay');
    if (!overlay) return;
    overlay.querySelector('.guide-modal-icon').textContent = g.icon || '📖';
    overlay.querySelector('#guideModalTitle').textContent = g.title;
    overlay.querySelector('#guideModalDesc').textContent = g.desc;
    const products = (g.products || []).map(pid => PRODUCTS.find(p => p.id === pid)).filter(Boolean);
    const grid = overlay.querySelector('.guide-modal-products');
    grid.innerHTML = products.length
      ? products.map(p => buildProductCard(p, '100%')).join('')
      : '<p style="color:var(--text-muted);text-align:center;padding:32px 0;grid-column:1/-1;">No products in this guide yet.</p>';
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  window.closeGuideModal = function() {
    const overlay = document.getElementById('guideModalOverlay');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = '';
  };

  // ── Welcome quiz popup ────────────────────────────────────────
  const qpOverlay = document.getElementById('quiz-popup-overlay');
  const qpClose   = document.getElementById('quiz-popup-close');
  const qpCta     = document.getElementById('quiz-popup-cta');
  const qpLogin   = document.getElementById('quiz-popup-login-link');
  if (qpOverlay && !sessionStorage.getItem('quizPopupDismissed')) {
    setTimeout(() => qpOverlay.classList.add('active'), 900);
    const dismissPopup = () => {
      qpOverlay.classList.remove('active');
      sessionStorage.setItem('quizPopupDismissed', '1');
    };
    qpClose.addEventListener('click', dismissPopup);
    qpOverlay.addEventListener('click', e => { if (e.target === qpOverlay) dismissPopup(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') dismissPopup(); });
    if (qpCta)   qpCta.addEventListener('click', dismissPopup);
    if (qpLogin) qpLogin.addEventListener('click', dismissPopup);
  }

});
</script>
@endsection
