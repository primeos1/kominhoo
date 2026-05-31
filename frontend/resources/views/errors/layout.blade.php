<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Oops') — Kominhoo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,300;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{
  --lime:#D4D994;--lime-dark:#5E6623;--lime-light:#E6EFB5;--lime-pale:#F2F5D6;
  --red:#893941;--cream:#FAF6F3;--off-white:#F5F0EC;
  --gray-100:#F5ECED;--gray-200:#EDD8DB;--gray-300:#D7C3C6;
  --gray-400:#A08878;--gray-500:#6B5450;--gray-600:#4A3A38;--gray-700:#2A1E1F;
  --black:#1C1416;--dark:#2A1E1F;--amber:#D97706;
}
html{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
body{
  font-family:'DM Sans',system-ui,-apple-system,sans-serif;
  background:var(--cream);color:var(--dark);
  min-height:100vh;display:flex;flex-direction:column;
}

/* ── NAV ── */
.e-nav{
  position:fixed;top:0;left:0;right:0;height:58px;
  background:rgba(250,246,243,.9);
  backdrop-filter:blur(20px) saturate(180%);
  -webkit-backdrop-filter:blur(20px) saturate(180%);
  border-bottom:1px solid var(--gray-200);
  display:flex;align-items:center;padding:0 32px;z-index:100;
}
.e-nav-wrap{max-width:1400px;margin:0 auto;width:100%;display:flex;align-items:center;justify-content:space-between}
.e-logo{
  font-size:1.125rem;font-weight:700;color:var(--dark);
  text-decoration:none;letter-spacing:-.5px;font-family:'DM Sans',system-ui,sans-serif;
}
.e-logo em{font-style:normal;color:var(--red)}
.e-nav-links{display:flex;align-items:center;gap:8px}
.e-nav-links a{
  font-size:.8125rem;font-weight:600;color:var(--gray-500);
  text-decoration:none;padding:8px 14px;border-radius:999px;
  transition:color .2s,background .2s;
}
.e-nav-links a:hover{color:var(--dark);background:var(--gray-100)}
.e-nav-home{background:var(--dark);color:#fff !important}
.e-nav-home:hover{background:var(--gray-700) !important}

/* ── MAIN ── */
.e-main{
  flex:1;display:flex;align-items:center;justify-content:center;
  padding:80px 24px 80px;position:relative;overflow:hidden;
  min-height:calc(100vh - 58px - 62px);
}
.e-main::before{
  content:'';position:absolute;inset:0;
  background-image:radial-gradient(circle,var(--gray-300) 1px,transparent 1px);
  background-size:28px 28px;opacity:.3;pointer-events:none;
}

/* Blobs */
.e-blob{position:absolute;border-radius:50%;filter:blur(90px);pointer-events:none;z-index:0}
.e-blob-a{width:700px;height:700px;background:var(--lime);opacity:.16;top:-250px;right:-200px;animation:bfl 14s ease-in-out infinite}
.e-blob-b{width:500px;height:500px;background:var(--red);opacity:.08;bottom:-200px;left:-150px;animation:bfl 18s ease-in-out infinite reverse}
@keyframes bfl{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(-50px) scale(1.1)}}

/* ── CARD ── */
.e-card{
  position:relative;z-index:1;max-width:580px;width:100%;
  background:rgba(255,255,255,.8);
  backdrop-filter:blur(32px) saturate(160%);
  -webkit-backdrop-filter:blur(32px) saturate(160%);
  border:1px solid rgba(255,255,255,.95);
  border-radius:36px;padding:72px 52px 60px;
  box-shadow:0 2px 0 rgba(255,255,255,.9) inset,0 12px 60px rgba(0,0,0,.09),0 1px 3px rgba(0,0,0,.04);
  text-align:center;
  animation:cardIn .7s cubic-bezier(.34,1.4,.64,1) both;
}
@keyframes cardIn{from{opacity:0;transform:translateY(40px) scale(.96)}to{opacity:1;transform:none}}

/* Error code watermark behind card */
.e-watermark{
  position:absolute;
  top:-48px;left:50%;transform:translateX(-50%);
  font-family:'DM Sans',system-ui,sans-serif;
  font-size:clamp(7rem,18vw,14rem);
  line-height:1;color:transparent;
  -webkit-text-stroke:2px var(--gray-200);
  white-space:nowrap;pointer-events:none;user-select:none;z-index:0;
  letter-spacing:-.02em;
}

/* ── ILLUSTRATION ── */
.e-illo-wrap{position:relative;width:112px;height:112px;margin:0 auto 36px}
.e-illo{
  width:112px;height:112px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  position:relative;
}
.e-illo::after{
  content:'';position:absolute;inset:-8px;border-radius:50%;
  border:1.5px dashed currentColor;opacity:.2;
  animation:illo-spin 25s linear infinite;
}
@keyframes illo-spin{to{transform:rotate(360deg)}}

.e-illo svg{width:48px;height:48px;stroke-width:1.6;fill:none;stroke-linecap:round;stroke-linejoin:round}

/* Colour variants */
.e-illo-lime{background:var(--lime-pale);color:var(--lime-dark)}
.e-illo-lime svg{stroke:var(--lime-dark)}
.e-illo-red{background:#FFF0F3;color:var(--red)}
.e-illo-red svg{stroke:var(--red)}
.e-illo-dark{background:var(--gray-100);color:var(--gray-700)}
.e-illo-dark svg{stroke:var(--gray-700)}
.e-illo-amber{background:#FFFBEB;color:var(--amber)}
.e-illo-amber svg{stroke:var(--amber)}
.e-illo-blue{background:#EFF6FF;color:#2563EB}
.e-illo-blue svg{stroke:#2563EB}

/* Floating particles */
.e-particle{
  position:absolute;width:8px;height:8px;border-radius:50%;
  background:currentColor;opacity:.2;
  animation:pfloat var(--dur,3s) ease-in-out infinite var(--delay,0s);
}
@keyframes pfloat{
  0%,100%{transform:translate(0,0) scale(1)}
  50%{transform:translate(var(--tx,6px),var(--ty,-10px)) scale(.8)}
}

/* ── TYPOGRAPHY ── */
.e-eyebrow{
  display:inline-flex;align-items:center;gap:7px;
  font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
  color:var(--gray-400);margin-bottom:16px;
}
.e-eyebrow-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block}

.e-headline{
  font-family:'DM Sans',system-ui,sans-serif;
  font-size:clamp(1.65rem,3.5vw,2.5rem);line-height:1.2;
  color:var(--dark);margin-bottom:16px;
  animation:cardIn .7s cubic-bezier(.34,1.4,.64,1) .1s both;
}

.e-body{
  font-size:.9375rem;line-height:1.8;color:var(--gray-500);
  max-width:400px;margin:0 auto 40px;
  animation:cardIn .7s cubic-bezier(.34,1.4,.64,1) .18s both;
}

/* ── ACTIONS ── */
.e-actions{display:flex;align-items:center;justify-content:center;gap:10px;flex-wrap:wrap}
.e-btn{
  display:inline-flex;align-items:center;gap:8px;
  padding:13px 28px;border-radius:999px;
  font-family:'DM Sans',system-ui,sans-serif;font-size:.9375rem;font-weight:700;
  text-decoration:none;cursor:pointer;border:none;
  transition:all .28s cubic-bezier(.4,0,.2,1);white-space:nowrap;
}
.e-btn svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
.e-btn-primary{background:var(--lime);color:var(--dark);box-shadow:0 4px 20px rgba(212,217,148,.35)}
.e-btn-primary:hover{background:var(--lime-dark);transform:translateY(-2px);box-shadow:0 8px 32px rgba(212,217,148,.5)}
.e-btn-dark{background:var(--dark);color:#fff}
.e-btn-dark:hover{background:var(--gray-700);transform:translateY(-2px)}
.e-btn-outline{background:transparent;color:var(--dark);border:1.5px solid var(--gray-300)}
.e-btn-outline:hover{border-color:var(--dark);transform:translateY(-2px);background:var(--gray-100)}
.e-btn-red{background:var(--red);color:#fff;box-shadow:0 4px 20px rgba(232,20,60,.25)}
.e-btn-red:hover{background:#C5102F;transform:translateY(-2px)}

/* ── STATUS PILL ── */
.e-status{
  display:inline-flex;align-items:center;gap:8px;
  background:var(--gray-100);border:1px solid var(--gray-200);
  border-radius:999px;padding:6px 14px;margin-bottom:28px;
  font-size:.8125rem;font-weight:600;color:var(--gray-500);
}
.e-status-dot{width:7px;height:7px;border-radius:50%;background:#22C55E;flex-shrink:0;animation:sdot 2s ease-in-out infinite}
.e-status-dot-amber{background:var(--amber)}
.e-status-dot-red{background:var(--red)}
@keyframes sdot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.7)}}

/* ── DIVIDER ── */
.e-divider{height:1px;background:var(--gray-200);margin:32px 0}

/* ── FOOTER ── */
.e-footer{
  padding:16px 32px;border-top:1px solid var(--gray-200);
  background:rgba(255,255,255,.6);backdrop-filter:blur(10px);
  text-align:center;
}
.e-footer p{font-size:.8125rem;color:var(--gray-400);line-height:1.9}
.e-footer a{
  color:var(--gray-600);text-decoration:none;font-weight:600;
  border-bottom:1px solid var(--gray-300);
  transition:color .2s,border-color .2s;
}
.e-footer a:hover{color:var(--dark);border-color:var(--dark)}

/* ── RESPONSIVE ── */
@media(max-width:640px){
  .e-card{padding:52px 28px 48px;border-radius:28px}
  .e-watermark{font-size:clamp(5rem,24vw,9rem);top:-28px}
  .e-nav{padding:0 16px}
  .e-nav-links .e-nav-shop{display:none}
}
@media(max-width:420px){
  .e-card{padding:44px 20px 40px}
  .e-btn{padding:11px 20px;font-size:.875rem}
  .e-actions{flex-direction:column;align-items:stretch}
  .e-btn{justify-content:center}
}
</style>
</head>
<body>

<nav class="e-nav">
  <div class="e-nav-wrap">
    <a href="{{ route('home') }}" class="e-logo">KOMIN<em>H</em>OO</a>
    <div class="e-nav-links">
      <a href="{{ route('shop') }}" class="e-nav-shop">Browse Shop</a>
      <a href="{{ route('home') }}" class="e-nav-home">← Home</a>
    </div>
  </div>
</nav>

<main class="e-main">
  <div class="e-blob e-blob-a"></div>
  <div class="e-blob e-blob-b"></div>

  <div class="e-card">
    @yield('content')
  </div>
</main>

<footer class="e-footer">
  <p>
    Need help? <a href="{{ route('faq') }}">Visit our FAQ</a> &nbsp;·&nbsp;
    <a href="mailto:hello@kominhoo.com">Email support</a> &nbsp;·&nbsp;
    <a href="{{ route('community') }}">Community</a>
  </p>
</footer>

@yield('scripts')
</body>
</html>
