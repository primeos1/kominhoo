@extends('layouts.bare')
@section('title', 'Sign In — Kominhoo Beauty')

@section('head')
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--font-body);background:var(--black);min-height:100vh;display:grid;grid-template-columns:1fr 1fr}
.auth-brand{background:var(--rose-dark);color:#fff;display:flex;flex-direction:column;padding:48px 56px;position:relative;overflow:hidden;min-height:100vh}
.auth-brand::before{content:'';position:absolute;top:-120px;right:-80px;width:500px;height:500px;background:radial-gradient(circle,rgba(212,217,148,.12) 0%,transparent 65%);pointer-events:none}
.auth-brand::after{content:'';position:absolute;bottom:-80px;left:-60px;width:380px;height:380px;background:radial-gradient(circle,rgba(137,57,65,.08) 0%,transparent 65%);pointer-events:none}
.auth-logo{font-family:var(--font-display);font-size:1.8rem;font-weight:400;color:#fff;text-decoration:none;display:inline-flex;align-items:center;margin-bottom:auto;z-index:1}
.auth-logo span{color:var(--red)}
.auth-brand-body{display:flex;flex-direction:column;gap:32px;flex:1;justify-content:center;z-index:1;padding:60px 0 40px}
.auth-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.75rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.4)}
.auth-eyebrow::before{content:'';width:28px;height:2px;background:var(--lime)}
.auth-headline{font-family:var(--font-display);font-size:clamp(2rem,3.5vw,3.2rem);line-height:1.1;color:#fff}
.auth-headline em{color:var(--lime);font-style:italic}
.auth-sub{font-size:.95rem;color:rgba(255,255,255,.55);line-height:1.7;max-width:380px}
.auth-img-card{border-radius:20px;overflow:hidden;position:relative;aspect-ratio:16/9;max-width:440px}
.auth-img-card img{width:100%;height:100%;object-fit:cover;display:block}
.auth-testimonial{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:20px 24px;max-width:400px;backdrop-filter:blur(12px)}
.auth-testimonial-text{font-size:.9rem;color:rgba(255,255,255,.8);line-height:1.6;margin-bottom:14px;font-style:italic}
.auth-testimonial-author{display:flex;align-items:center;gap:10px}
.auth-tav{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#D4D994,#5E6623);display:grid;place-items:center;font-size:.85rem;font-weight:700;color:var(--black);flex-shrink:0}
.auth-tname{font-size:.82rem;font-weight:700;color:rgba(255,255,255,.9)}
.auth-tloc{font-size:.72rem;color:rgba(255,255,255,.4)}
.auth-stats{display:flex;gap:24px;flex-wrap:wrap;z-index:1;padding-top:8px}
.auth-stat-num{font-size:1.4rem;font-weight:700;color:var(--lime)}
.auth-stat-label{font-size:.72rem;color:rgba(255,255,255,.4);font-weight:500;text-transform:uppercase;letter-spacing:.06em}
.auth-form-panel{background:var(--bg-primary);display:flex;align-items:center;justify-content:center;padding:48px 56px;min-height:100vh;position:relative}
.auth-back{position:absolute;top:32px;left:32px;display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;color:var(--text-muted);text-decoration:none;transition:color .15s}
.auth-back:hover{color:var(--black)}
.auth-inner{width:100%;max-width:400px}
.auth-form-title{font-family:var(--font-display);font-size:2rem;color:var(--black);margin-bottom:6px}
.auth-form-sub{font-size:.9rem;color:var(--text-muted);margin-bottom:32px}
.social-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:12px;padding:13px 20px;border-radius:999px;font-size:.9rem;font-weight:600;cursor:pointer;transition:all .2s;border:1.5px solid var(--border);background:#fff;color:var(--black);text-decoration:none;font-family:var(--font-body);margin-bottom:12px}
.social-btn:hover{border-color:var(--black);box-shadow:0 4px 14px rgba(0,0,0,.08);transform:translateY(-1px)}
.social-btn.dark{background:var(--black);color:#fff;border-color:var(--black)}
.social-btn.dark:hover{background:var(--dark);box-shadow:0 4px 14px rgba(0,0,0,.2)}
.auth-divider{display:flex;align-items:center;gap:16px;margin:8px 0 24px}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:var(--border)}
.auth-divider span{font-size:.78rem;font-weight:600;color:var(--text-muted);white-space:nowrap}
.auth-input{width:100%;padding:13px 18px;border:1.5px solid var(--border);border-radius:12px;font-size:.92rem;color:var(--black);background:#fff;transition:border-color .15s,box-shadow .15s;outline:none;font-family:var(--font-body)}
.auth-input:focus{border-color:var(--lime);box-shadow:0 0 0 3px rgba(212,217,148,.2)}
.auth-input::placeholder{color:var(--gray-300)}
.pw-wrap{position:relative}
.pw-wrap .auth-input{padding-right:50px}
.pw-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);padding:4px;transition:color .15s}
.pw-toggle:hover{color:var(--black)}
.auth-submit{width:100%;padding:15px 28px;background:var(--rose-dark);color:#fff;border:none;border-radius:999px;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .25s;font-family:var(--font-body);letter-spacing:.02em;margin-top:4px}
.auth-submit:hover{background:var(--rose);transform:translateY(-2px);box-shadow:0 8px 24px rgba(137,57,65,.3)}
.auth-submit:disabled{opacity:.7;transform:none;cursor:not-allowed}
.auth-error{background:#FEE2E2;border:1.5px solid var(--red);border-radius:12px;padding:12px 16px;font-size:.88rem;font-weight:600;color:var(--red);margin-bottom:16px}
.auth-success{background:#F0FDF4;border:1.5px solid #22C55E;border-radius:12px;padding:12px 16px;font-size:.88rem;font-weight:600;color:#16A34A;margin-bottom:16px}
.auth-footer{text-align:center;margin-top:28px;font-size:.88rem;color:var(--text-muted)}
.auth-footer a{color:var(--black);font-weight:700;text-decoration:none}
.auth-footer a:hover{color:var(--lime-dark)}
@media(max-width:900px){body{grid-template-columns:1fr}.auth-brand{display:none}.auth-form-panel{padding:48px 24px}}
@media(max-width:480px){.auth-form-panel{padding:48px 20px}}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
@endsection

@section('content')
@php($loginCms = data_get($siteContent, 'pages.login', []))
{{-- Brand Panel --}}
<div class="auth-brand">
  <a href="{{ route('home') }}" class="auth-logo">KOMIN<span>H</span>OO</a>
  <div class="auth-brand-body">
    <div>
      <div class="auth-eyebrow">{{ data_get($loginCms, 'brand_eyebrow', 'Korean Beauty, Personalized') }}</div>
      <h1 class="auth-headline">{{ data_get($loginCms, 'brand_title_line_1', 'Glow up starts') }}<br><em>{{ data_get($loginCms, 'brand_title_line_2', 'right here.') }}</em></h1>
      <p class="auth-sub" style="margin-top:16px">{{ data_get($loginCms, 'brand_description', 'Sign in to access your personalized skin routine, loyalty points, and exclusive member deals.') }}</p>
    </div>
    <div class="auth-img-card">
      <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&h=450&fit=crop&q=80" alt="Skincare">
    </div>
    <div class="auth-testimonial">
      <div class="auth-testimonial-text">"The skin quiz completely changed my routine. My skin has never looked this good — and I've been trying K-beauty for years!"</div>
      <div class="auth-testimonial-author">
        <div class="auth-tav">A</div>
        <div>
          <div class="auth-tname">Adaeze O.</div>
          <div class="auth-tloc">Lagos, Nigeria · Radiant Insider Member</div>
        </div>
      </div>
    </div>
  </div>
  <div class="auth-stats">
    <div><div class="auth-stat-num">50K+</div><div class="auth-stat-label">Members</div></div>
    <div><div class="auth-stat-num">4.8★</div><div class="auth-stat-label">Avg Rating</div></div>
    <div><div class="auth-stat-num">200+</div><div class="auth-stat-label">Products</div></div>
    <div><div class="auth-stat-num">Free</div><div class="auth-stat-label">Skin Quiz</div></div>
  </div>
</div>

{{-- Form Panel --}}
<div class="auth-form-panel">
  <a href="{{ route('home') }}" class="auth-back">← Back to shop</a>
  <div class="auth-inner">
    <h2 class="auth-form-title">{{ data_get($loginCms, 'form_title', 'Welcome back') }}</h2>
    <p class="auth-form-sub">{{ data_get($loginCms, 'form_subtitle', 'Sign in to your Kominhoo account') }}</p>

    @if($errors->any())
      <div class="auth-error">{{ $errors->first() }}</div>
    @endif
    @if(session('success'))
      <div class="auth-success">{{ session('success') }}</div>
    @endif

    {{-- Social --}}
    <a href="{{ route('social.redirect', 'google') }}" class="social-btn">
      <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
      Continue with Google
    </a>
    <a href="{{ route('social.redirect', 'facebook') }}" class="social-btn dark">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073C24 5.406 18.627 0 12 0S0 5.406 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.513c-1.491 0-1.956.93-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
      Continue with Facebook
    </a>

    <div class="auth-divider"><span>or sign in with email</span></div>

    <form method="POST" action="{{ route('login.submit') }}" id="login-form">
      @csrf
      <div style="display:flex;flex-direction:column;gap:18px">
        <div>
          <label style="font-size:.82rem;font-weight:700;color:#3A3830;display:block;margin-bottom:7px">Email address</label>
          <input type="email" name="email" class="auth-input" placeholder="you@example.com" required autocomplete="email" value="{{ old('email') }}">
        </div>
        <div>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:7px">
            <label style="font-size:.82rem;font-weight:700;color:#3A3830">Password</label>
            <a href="#" style="font-size:.78rem;font-weight:600;color:var(--text-muted);text-decoration:none;transition:color .15s" onmouseover="this.style.color='#1C1416'" onmouseout="this.style.color='#A08878'">Forgot password?</a>
          </div>
          <div class="pw-wrap">
            <input type="password" name="password" id="login-pw" class="auth-input" placeholder="Enter your password" required autocomplete="current-password">
            <button type="button" class="pw-toggle" onclick="togglePw('login-pw',this)" title="Show/hide">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none">
          <input type="checkbox" name="remember" id="remember-me" style="display:none">
          <span id="cb-box" style="width:18px;height:18px;border:2px solid #E2E1DC;border-radius:5px;display:grid;place-items:center;flex-shrink:0;background:#fff;transition:all .15s;cursor:pointer" onclick="toggleRemember()"></span>
          <span style="font-size:.85rem;color:#524F48">Keep me signed in</span>
        </label>
        <button type="submit" class="auth-submit" id="login-submit">Sign In →</button>
      </div>
    </form>

    <div class="auth-footer">
      Don't have an account? <a href="{{ route('register') }}">Create one free →</a>
    </div>
  </div>
</div>

<script>
function togglePw(id, btn) {
  const inp = document.getElementById(id);
  inp.type = inp.type === 'text' ? 'password' : 'text';
  btn.style.color = inp.type === 'password' ? '#A08878' : '#1C1416';
}
function toggleRemember() {
  const cb = document.getElementById('remember-me');
  const box = document.getElementById('cb-box');
  cb.checked = !cb.checked;
  box.style.background = cb.checked ? '#D4D994' : '#fff';
  box.style.borderColor = cb.checked ? '#D4D994' : '#EDDCD8';
  box.textContent = cb.checked ? '✓' : '';
  box.style.fontSize = '.68rem';
  box.style.fontWeight = '800';
  box.style.color = '#1C1416';
}
document.getElementById('login-form').addEventListener('submit', function() {
  const btn = document.getElementById('login-submit');
  btn.innerHTML = '<span style="width:16px;height:16px;border:2px solid rgba(255,255,255,.5);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:inline-block;vertical-align:middle;margin-right:6px"></span>Signing in…';
  btn.disabled = true;
});
</script>
@endsection

