@extends('layouts.bare')
@section('title', 'Join Free — Kominhoo Beauty')

@section('head')
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--font-body);background:var(--lime);min-height:100vh;display:grid;grid-template-columns:1fr 1fr}
.auth-brand{background:var(--lime);display:flex;flex-direction:column;padding:48px 56px;position:relative;overflow:hidden;min-height:100vh}
.auth-brand::before{content:'';position:absolute;top:-100px;right:-60px;width:400px;height:400px;background:radial-gradient(circle,rgba(0,0,0,.06) 0%,transparent 65%);pointer-events:none}
.auth-logo{font-family:var(--font-display);font-size:1.8rem;font-weight:400;color:var(--black);text-decoration:none;display:inline-flex;align-items:center;z-index:1}
.auth-logo span{color:var(--red)}
.auth-brand-body{display:flex;flex-direction:column;gap:28px;flex:1;justify-content:center;z-index:1;padding:48px 0 40px}
.auth-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.75rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(0,0,0,.5)}
.auth-eyebrow::before{content:'';width:28px;height:2px;background:var(--black)}
.auth-headline{font-family:var(--font-display);font-size:clamp(2rem,3.2vw,3rem);line-height:1.1;color:var(--black)}
.auth-sub{font-size:.95rem;color:rgba(0,0,0,.6);line-height:1.7;max-width:360px}
.perk{display:flex;align-items:flex-start;gap:12px}
.perk-icon{width:38px;height:38px;flex-shrink:0;background:rgba(0,0,0,.1);border-radius:10px;display:grid;place-items:center;font-size:1.1rem}
.perk-title{font-size:.9rem;font-weight:700;color:var(--black);margin-bottom:2px}
.perk-desc{font-size:.78rem;color:rgba(0,0,0,.55);line-height:1.4}
.brand-card{background:rgba(0,0,0,.1);border-radius:16px;padding:20px 24px;display:flex;align-items:center;gap:16px}
.auth-form-panel{background:var(--bg-primary);display:flex;align-items:flex-start;justify-content:center;padding:48px 56px;min-height:100vh;overflow-y:auto;position:relative}
.auth-back{position:absolute;top:32px;left:32px;display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;color:var(--text-muted);text-decoration:none;transition:color .15s}
.auth-back:hover{color:var(--black)}
.auth-inner{width:100%;max-width:420px;padding-top:64px;padding-bottom:48px}
.auth-form-title{font-family:var(--font-display);font-size:2rem;color:var(--black);margin-bottom:6px}
.auth-form-sub{font-size:.9rem;color:var(--text-muted);margin-bottom:28px}
.social-btns{display:flex;gap:12px;margin-bottom:20px}
.social-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 16px;border-radius:999px;font-size:.85rem;font-weight:600;cursor:pointer;transition:all .2s;border:1.5px solid var(--border);background:#fff;color:var(--black);text-decoration:none;white-space:nowrap;font-family:var(--font-body)}
.social-btn:hover{border-color:var(--black);box-shadow:0 4px 14px rgba(0,0,0,.08);transform:translateY(-1px)}
.social-btn.dark{background:var(--black);color:#fff;border-color:var(--black)}
.social-btn.dark:hover{background:var(--dark);box-shadow:0 4px 14px rgba(0,0,0,.2)}
.auth-divider{display:flex;align-items:center;gap:16px;margin-bottom:20px}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:var(--border)}
.auth-divider span{font-size:.78rem;font-weight:600;color:var(--text-muted);white-space:nowrap}
.auth-input{width:100%;padding:13px 18px;border:1.5px solid var(--border);border-radius:12px;font-size:.92rem;color:var(--black);background:#fff;transition:border-color .15s,box-shadow .15s;outline:none;font-family:var(--font-body)}
.auth-input:focus{border-color:var(--lime);box-shadow:0 0 0 3px rgba(212,217,148,.2)}
.auth-input::placeholder{color:var(--gray-300)}
.pw-wrap{position:relative}
.pw-wrap .auth-input{padding-right:50px}
.pw-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);padding:4px;transition:color .15s}
.pw-toggle:hover{color:var(--black)}
.pw-strength-bar{display:flex;gap:4px;margin-top:8px;margin-bottom:4px}
.ps{flex:1;height:3px;border-radius:99px;background:var(--border);transition:background .3s}
.pw-label{font-size:.72rem;font-weight:600;color:var(--text-muted)}
.skin-opts{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}
.skin-opt{display:flex;align-items:center;gap:6px;padding:8px 14px;border:1.5px solid var(--border);border-radius:999px;font-size:.8rem;font-weight:600;cursor:pointer;transition:all .15s;background:#fff;color:var(--text-secondary);white-space:nowrap}
.skin-opt:hover{border-color:var(--lime);background:var(--lime-pale)}
.skin-opt.selected{border-color:var(--lime);background:var(--lime);color:var(--black)}
.auth-terms{display:flex;align-items:flex-start;gap:10px;cursor:pointer;user-select:none}
.terms-box{width:18px;height:18px;min-width:18px;border:2px solid var(--border);border-radius:5px;display:grid;place-items:center;transition:all .15s;background:#fff;margin-top:2px}
.auth-terms-text{font-size:.82rem;color:var(--text-secondary);line-height:1.5}
.auth-terms-text a{color:var(--black);font-weight:700;text-decoration:none}
.auth-submit{width:100%;padding:15px 28px;background:var(--rose-dark);color:#fff;border:none;border-radius:999px;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .25s;font-family:var(--font-body);letter-spacing:.02em;margin-top:4px}
.auth-submit:hover{background:var(--rose);transform:translateY(-2px);box-shadow:0 8px 24px rgba(137,57,65,.3)}
.auth-submit:disabled{opacity:.7;transform:none;cursor:not-allowed}
.auth-error{background:#FEE2E2;border:1.5px solid var(--red);border-radius:12px;padding:12px 16px;font-size:.88rem;font-weight:600;color:var(--red);margin-bottom:16px}
.auth-footer{text-align:center;margin-top:24px;font-size:.88rem;color:var(--text-muted)}
.auth-footer a{color:var(--black);font-weight:700;text-decoration:none}
.auth-footer a:hover{color:var(--lime-dark)}
@media(max-width:900px){body{grid-template-columns:1fr}.auth-brand{display:none}.auth-form-panel{padding:48px 24px;align-items:center}.auth-inner{padding-top:24px}}
@media(max-width:480px){.auth-form-panel{padding:48px 20px}.social-btns{flex-direction:column}[style*="grid-template-columns:1fr 1fr"],[style*="grid-template-columns: 1fr 1fr"]{grid-template-columns:1fr!important}}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
@endsection

@section('content')
@php($signupCms = data_get($siteContent, 'pages.signup', []))
{{-- Brand Panel --}}
<div class="auth-brand">
  <a href="{{ route('home') }}" class="auth-logo">KOMIN<span>H</span>OO</a>
  <div class="auth-brand-body">
    <div>
      <div class="auth-eyebrow">{{ data_get($signupCms, 'brand_eyebrow', 'Join the glow club') }}</div>
      <h1 class="auth-headline">{{ data_get($signupCms, 'brand_title', 'Your perfect routine awaits.') }}</h1>
      <p class="auth-sub" style="margin-top:14px">{{ data_get($signupCms, 'brand_description', 'Create your free account and unlock a personalized Korean skincare routine in 60 seconds.') }}</p>
    </div>
    <div style="display:flex;flex-direction:column;gap:14px">
      <div class="perk"><div class="perk-icon">🧬</div><div><div class="perk-title">{{ data_get($signupCms, 'perk_1_title', 'Personalized Skin Quiz') }}</div><div class="perk-desc">{{ data_get($signupCms, 'perk_1_description', 'Get matched to products that actually work for your skin type.') }}</div></div></div>
      <div class="perk"><div class="perk-icon">🎁</div><div><div class="perk-title">{{ data_get($signupCms, 'perk_2_title', 'Earn Loyalty Points') }}</div><div class="perk-desc">{{ data_get($signupCms, 'perk_2_description', 'Every purchase earns Glow Points. Redeem for products and perks.') }}</div></div></div>
      <div class="perk"><div class="perk-icon">🚚</div><div><div class="perk-title">Free Shipping at ₦50K+</div><div class="perk-desc">Subscribers always ship free, no minimum required.</div></div></div>
      <div class="perk"><div class="perk-icon">💌</div><div><div class="perk-title">{{ data_get($signupCms, 'perk_4_title', 'Exclusive Member Deals') }}</div><div class="perk-desc">{{ data_get($signupCms, 'perk_4_description', 'Early access to launches, flash sales, and seasonal edits.') }}</div></div></div>
    </div>
    <div class="brand-card">
      <div style="font-size:2rem;flex-shrink:0">✨</div>
      <div>
        <div style="font-size:.88rem;font-weight:600;color:var(--black)">Free to join — no credit card needed</div>
        <div style="font-size:.75rem;color:rgba(0,0,0,.5);margin-top:3px">50,000+ members already glowing up</div>
      </div>
    </div>
  </div>
</div>

{{-- Form Panel --}}
<div class="auth-form-panel">
  <a href="{{ route('home') }}" class="auth-back">← Back to shop</a>
  <div class="auth-inner">
    <h2 class="auth-form-title">{{ data_get($signupCms, 'form_title', 'Create your account') }}</h2>
    <p class="auth-form-sub">{{ data_get($signupCms, 'form_subtitle', 'Join free and start your glow journey today') }}</p>

    @if($errors->any())
      <div class="auth-error">
        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
      </div>
    @endif

    {{-- Social --}}
    <div class="social-btns">
      <a href="{{ route('social.redirect', 'google') }}" class="social-btn">
        <svg width="17" height="17" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
        Google
      </a>
      <a href="{{ route('social.redirect', 'facebook') }}" class="social-btn dark">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073C24 5.406 18.627 0 12 0S0 5.406 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.513c-1.491 0-1.956.93-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
        Facebook
      </a>
    </div>

    <div class="auth-divider"><span>or sign up with email</span></div>

    <form method="POST" action="{{ route('register.submit') }}" id="signup-form">
      @csrf
      <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Name + Phone --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
          <div>
            <label style="font-size:.82rem;font-weight:700;color:#3A3830;display:block;margin-bottom:7px">Full Name</label>
            <input type="text" name="name" class="auth-input" placeholder="Adaeze Okafor" required autocomplete="name" value="{{ old('name') }}">
          </div>
          <div>
            <label style="font-size:.82rem;font-weight:700;color:var(--gray-700);display:block;margin-bottom:7px">Phone <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label>
            <input type="tel" name="phone" class="auth-input" placeholder="+234..." autocomplete="tel" value="{{ old('phone') }}">
          </div>
        </div>

        {{-- Email --}}
        <div>
          <label style="font-size:.82rem;font-weight:700;color:#3A3830;display:block;margin-bottom:7px">Email address</label>
          <input type="email" name="email" class="auth-input" placeholder="you@example.com" required autocomplete="email" value="{{ old('email') }}">
        </div>

        {{-- Password --}}
        <div>
          <label style="font-size:.82rem;font-weight:700;color:#3A3830;display:block;margin-bottom:7px">Password</label>
          <div class="pw-wrap">
            <input type="password" name="password" id="su-pw" class="auth-input" placeholder="Min. 8 characters" required autocomplete="new-password" oninput="updateStrength(this.value)">
            <button type="button" class="pw-toggle" onclick="togglePw('su-pw',this)">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="pw-strength-bar">
            <div class="ps" id="ps1"></div><div class="ps" id="ps2"></div><div class="ps" id="ps3"></div><div class="ps" id="ps4"></div>
          </div>
          <div class="pw-label" id="ps-label">Enter a password</div>
        </div>

        {{-- Confirm Password --}}
        <div>
          <label style="font-size:.82rem;font-weight:700;color:#3A3830;display:block;margin-bottom:7px">Confirm password</label>
          <div class="pw-wrap">
            <input type="password" name="password_confirmation" id="su-pw2" class="auth-input" placeholder="Repeat your password" required autocomplete="new-password">
            <button type="button" class="pw-toggle" onclick="togglePw('su-pw2',this)">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        {{-- Skin type picker --}}
        <div>
          <div style="font-size:.82rem;font-weight:700;color:#3A3830;margin-bottom:4px">
            Skin type <span style="font-weight:400;color:var(--text-muted)">(optional)</span>
          </div>
          <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:8px">Helps us personalise your recommendations right away</div>
          <input type="hidden" name="skin_type" id="skin-type-val" value="{{ old('skin_type') }}">
          <div class="skin-opts">
            @foreach(['Oily','Dry','Combination','Normal','Sensitive','Not sure'] as $type)
              <button type="button" class="skin-opt{{ old('skin_type') === $type ? ' selected' : '' }}" onclick="selectSkin(this,'{{ $type }}')">
                {{ ['Oily'=>'🫧','Dry'=>'💧','Combination'=>'⚖️','Normal'=>'✨','Sensitive'=>'🌸','Not sure'=>'❓'][$type] }} {{ $type }}
              </button>
            @endforeach
          </div>
        </div>

        {{-- Terms --}}
        <div class="auth-terms" id="terms-label">
          <input type="checkbox" name="terms" id="terms-cb" style="display:none">
          <span class="terms-box" id="terms-box" onclick="toggleTerms()"></span>
          <span class="auth-terms-text">
            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
            I'm happy to receive skin tips and deals by email — unsubscribe anytime.
          </span>
        </div>

        <button type="submit" class="auth-submit" id="signup-submit">Create Free Account →</button>
      </div>
    </form>

    <div class="auth-footer">
      Already have an account? <a href="{{ route('login') }}">Sign in →</a>
    </div>
  </div>
</div>

<script>
function togglePw(id, btn) {
  const inp = document.getElementById(id);
  inp.type = inp.type === 'text' ? 'password' : 'text';
  btn.style.color = inp.type === 'password' ? '#A08878' : '#1C1416';
}

function selectSkin(btn, type) {
  document.querySelectorAll('.skin-opt').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  document.getElementById('skin-type-val').value = type;
}

function toggleTerms() {
  const cb  = document.getElementById('terms-cb');
  const box = document.getElementById('terms-box');
  cb.checked = !cb.checked;
  box.style.background   = cb.checked ? '#D4D994' : '#fff';
  box.style.borderColor  = cb.checked ? '#D4D994' : '#EDDCD8';
  box.innerHTML = cb.checked ? '<span style="font-size:.68rem;font-weight:700;color:#1C1416">✓</span>' : '';
}

function updateStrength(val) {
  const segs = [1,2,3,4].map(i => document.getElementById('ps'+i));
  const label = document.getElementById('ps-label');
  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const colors = ['','#893941','#F59E0B','#D4D994','#22C55E'];
  const labels = ['Enter a password','Too weak','Could be stronger','Good password','Strong password'];
  segs.forEach((s,i) => s.style.background = i < score ? colors[score] : '#EDDCD8');
  label.textContent = val.length ? labels[score] : 'Enter a password';
  label.style.color = val.length ? colors[score] : '#A08878';
}

document.getElementById('signup-form').addEventListener('submit', function(e) {
  const pw  = document.getElementById('su-pw').value;
  const pw2 = document.getElementById('su-pw2').value;
  const cb  = document.getElementById('terms-cb');

  if (pw !== pw2) {
    e.preventDefault();
    document.getElementById('su-pw2').style.borderColor = '#893941';
    document.getElementById('su-pw2').focus();
    return;
  }

  if (!cb.checked) {
    e.preventDefault();
    const box = document.getElementById('terms-box');
    box.style.borderColor = '#893941';
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }
  const btn = document.getElementById('signup-submit');
  btn.innerHTML = '<span style="width:16px;height:16px;border:2px solid rgba(255,255,255,.5);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:inline-block;vertical-align:middle;margin-right:6px"></span>Creating account…';
  btn.disabled = true;
});
</script>
@endsection

