<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login — Kominhoo Beauty</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,600;0,6..96,700;0,6..96,900;1,6..96,400;1,6..96,700&family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --black: #1C1416;
      --lime:  #D4D994;
      --red:   #893941;
    }

    body {
      font-family: 'Jost', system-ui, sans-serif;
      background: var(--black);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      position: relative;
      overflow: hidden;
    }

    /* Decorative background blobs */
    body::before {
      content: '';
      position: absolute;
      top: -200px; right: -200px;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(212,217,148,.07) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }
    body::after {
      content: '';
      position: absolute;
      bottom: -150px; left: -150px;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(137,57,65,.05) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .login-card {
      background: #111;
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 24px;
      padding: 48px 44px;
      width: 100%;
      max-width: 440px;
      position: relative;
      z-index: 1;
    }

    /* Brand */
    .login-brand {
      text-align: center;
      margin-bottom: 36px;
    }
    .login-brand .logo {
      font-family: 'Bodoni Moda', Georgia, serif;
      font-size: 1.9rem;
      color: #fff;
      letter-spacing: .5px;
      display: block;
      margin-bottom: 8px;
    }
    .login-brand .logo span { color: var(--lime); }
    .login-brand .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(212,217,148,.1);
      border: 1px solid rgba(212,217,148,.2);
      color: var(--lime);
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 20px;
    }
    .login-brand .badge::before {
      content: '';
      width: 6px; height: 6px;
      background: var(--lime);
      border-radius: 50%;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: .5; transform: scale(.8); }
    }

    .login-heading {
      font-size: 1.35rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 4px;
      text-align: center;
    }
    .login-sub {
      font-size: .82rem;
      color: rgba(255,255,255,.35);
      text-align: center;
      margin-bottom: 32px;
    }

    /* Alert */
    .alert {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: .82rem;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-error {
      background: rgba(230,52,52,.1);
      border: 1px solid rgba(230,52,52,.25);
      color: #f87171;
    }
    .alert-success {
      background: rgba(22,163,74,.1);
      border: 1px solid rgba(22,163,74,.25);
      color: #4ade80;
    }

    /* Form */
    .form-group { margin-bottom: 18px; }
    .form-group label {
      display: block;
      font-size: .78rem;
      font-weight: 600;
      color: rgba(255,255,255,.5);
      margin-bottom: 8px;
      letter-spacing: .3px;
    }
    .form-input {
      width: 100%;
      padding: 13px 16px;
      background: rgba(255,255,255,.05);
      border: 1.5px solid rgba(255,255,255,.1);
      border-radius: 12px;
      font-family: inherit;
      font-size: .9rem;
      color: #fff;
      outline: none;
      transition: border-color .2s, background .2s;
    }
    .form-input::placeholder { color: rgba(255,255,255,.2); }
    .form-input:focus {
      border-color: var(--lime);
      background: rgba(212,217,148,.04);
    }
    .form-input.is-invalid { border-color: var(--red); }

    .field-error {
      font-size: .73rem;
      color: #f87171;
      margin-top: 5px;
    }

    /* Password toggle */
    .password-wrap { position: relative; }
    .password-toggle {
      position: absolute;
      top: 50%; right: 14px;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: rgba(255,255,255,.3);
      cursor: pointer;
      font-size: .85rem;
      padding: 4px;
      transition: color .15s;
    }
    .password-toggle:hover { color: rgba(255,255,255,.7); }

    /* Submit */
    .btn-submit {
      width: 100%;
      padding: 14px;
      background: var(--lime);
      border: none;
      border-radius: 12px;
      font-family: inherit;
      font-size: .92rem;
      font-weight: 700;
      color: var(--black);
      cursor: pointer;
      margin-top: 8px;
      transition: opacity .2s, transform .1s;
      letter-spacing: .3px;
    }
    .btn-submit:hover { opacity: .88; }
    .btn-submit:active { transform: scale(.98); }

    /* Footer links */
    .login-footer {
      margin-top: 28px;
      text-align: center;
    }
    .login-footer a {
      font-size: .8rem;
      color: rgba(255,255,255,.3);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: color .15s;
    }
    .login-footer a:hover { color: rgba(255,255,255,.65); }

    /* Divider */
    .login-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 24px 0;
    }
    .login-divider::before, .login-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: rgba(255,255,255,.07);
    }
    .login-divider span {
      font-size: .7rem;
      color: rgba(255,255,255,.2);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Security info strip */
    .security-strip {
      margin-top: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: .72rem;
      color: rgba(255,255,255,.2);
    }
  </style>
</head>
<body>

  <div class="login-card">

    <div class="login-brand">
      <span class="logo">Komin<span>hoo</span></span>
      <div class="badge">Admin Panel</div>
    </div>

    <h1 class="login-heading">Welcome back</h1>
    <p class="login-sub">Sign in to the Kominhoo admin dashboard</p>

    @if(session('error'))
      <div class="alert alert-error">⚠ {{ session('error') }}</div>
    @endif

    @if(session('success'))
      <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="alert alert-error">⚠ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf

      <div class="form-group">
        <label for="email">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
          placeholder="admin@kominhoo.com"
          value="{{ old('email') }}"
          autocomplete="email"
          required
        />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="password-wrap">
          <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
          />
          <button type="button" class="password-toggle" onclick="togglePassword()" title="Show/hide password">
            👁
          </button>
        </div>
      </div>

      <button type="submit" class="btn-submit">Sign In to Admin Panel →</button>
    </form>

    <div class="login-divider"><span>secure access</span></div>

    <div class="login-footer">
      <a href="{{ route('home') }}">← Back to Kominhoo store</a>
    </div>

    <div class="security-strip">
      🔒 Protected admin area — authorised personnel only
    </div>
  </div>

  <script>
    function togglePassword() {
      const inp = document.getElementById('password');
      const btn = inp.nextElementSibling;
      if (inp.type === 'password') {
        inp.type = 'text';
        btn.textContent = '🙈';
      } else {
        inp.type = 'password';
        btn.textContent = '👁';
      }
    }
  </script>
</body>
</html>
