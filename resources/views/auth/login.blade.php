<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BTECH — Admin Login</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;"/>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet" />
  @include('partials.iconsax')
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy:       #071b3d;
      --navy-mid:   #0b2d6b;
      --navy-deep:  #031024;
      --gold:       #c9933a;
      --gold-light: #e8b96a;
      --gold-pale:  #f6e7d0;
      --surface:    #ffffff;
      --border:     rgba(255,255,255,.12);
      --text-muted: rgba(255,255,255,.45);
    }

    html, body { height: 100%; font-family: 'DM Sans', system-ui, sans-serif; background: var(--navy-deep); overflow: hidden; }

    .bg { position: fixed; inset: 0; z-index: 0; background: radial-gradient(ellipse 80% 60% at 15% 50%, rgba(11,45,107,.58) 0%, transparent 70%), radial-gradient(ellipse 50% 80% at 90% 20%, rgba(201,147,58,.12) 0%, transparent 60%), radial-gradient(ellipse 60% 60% at 80% 90%, rgba(7,27,61,.82) 0%, transparent 70%), linear-gradient(135deg, #031024 0%, #071b3d 48%, #0b2d6b 100%); }
    .bg::before { content: ''; position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px); background-size: 60px 60px; mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 80%); }

    .orb { position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; z-index: 0; animation: drift 20s ease-in-out infinite; }
    .orb-1 { width: 500px; height: 500px; top: -100px; left: -80px; background: rgba(11,45,107,.35); animation-delay: 0s; }
    .orb-2 { width: 350px; height: 350px; bottom: -60px; right: -60px; background: rgba(201,147,58,.15); animation-delay: -8s; }
    .orb-3 { width: 250px; height: 250px; top: 40%; right: 15%; background: rgba(6,70,165,.2); animation-delay: -14s; }

    @keyframes drift { 0%,100% { transform: translate(0,0) scale(1); } 33% { transform: translate(30px,-20px) scale(1.05); } 66% { transform: translate(-20px,25px) scale(.96); } }

    .layout { position: relative; z-index: 1; display: grid; grid-template-columns: 1fr 480px; min-height: 100vh; }

    .left-panel { display: flex; flex-direction: column; justify-content: center; align-items: flex-start; padding: 60px 64px; animation: fadeUp .8s cubic-bezier(.4,0,.2,1) both; }
    .left-logo { display: flex; align-items: center; gap: 14px; margin-bottom: 64px; animation: fadeUp .7s cubic-bezier(.4,0,.2,1) .1s both; }
    .logo-mark { width: 80px; height: 85px; border-radius: 13px; background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(201,147,58,.35); overflow: hidden; }
    .logo-text { color: #fff; }
    .logo-name { display: block; font-size: 18px; font-weight: 700; letter-spacing: .2px; }
    .logo-sub  { display: block; font-size: 11px; font-weight: 500; color: var(--text-muted); letter-spacing: .8px; text-transform: uppercase; margin-top: 1px; }

    .left-hero { animation: fadeUp .8s cubic-bezier(.4,0,.2,1) .2s both; }
    .hero-eyebrow { display: inline-flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--gold); margin-bottom: 20px; }
    .hero-eyebrow::before { content: ''; width: 28px; height: 1.5px; background: var(--gold); }
    .hero-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(38px, 4vw, 58px); font-weight: 300; line-height: 1.12; color: #fff; letter-spacing: -.5px; margin-bottom: 20px; }
    .hero-title em { font-style: italic; color: var(--gold-light); }
    .hero-desc { font-size: 14.5px; line-height: 1.7; color: rgba(255,255,255,.5); max-width: 400px; margin-bottom: 48px; }

    .stats-row { display: flex; gap: 36px; animation: fadeUp .8s cubic-bezier(.4,0,.2,1) .35s both; }
    .stat-value { display: block; font-size: 28px; font-weight: 700; color: #fff; font-family: 'Cormorant Garamond', serif; letter-spacing: -.5px; }
    .stat-label { font-size: 11.5px; color: var(--text-muted); font-weight: 500; margin-top: 2px; display: block; }
    .stat-div { width: 1px; background: rgba(255,255,255,.12); align-self: stretch; }

    .right-panel { display: flex; align-items: center; justify-content: center; padding: 40px 48px; position: relative; }

    .login-card { width: 100%; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); border-radius: 24px; padding: 48px 44px; backdrop-filter: blur(24px); box-shadow: 0 32px 80px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.12); animation: slideIn .75s cubic-bezier(.4,0,.2,1) .15s both; position: relative; overflow: hidden; }
    .login-card::before { content: ''; position: absolute; top: 0; left: 0; width: 140px; height: 140px; background: radial-gradient(circle at top left, rgba(201,147,58,.18), transparent 70%); pointer-events: none; }

    @keyframes slideIn { from { opacity: 0; transform: translateX(32px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fadeUp  { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

    .card-header { margin-bottom: 36px; }
    .card-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(201,147,58,.15); border: 1px solid rgba(201,147,58,.3); color: var(--gold-light); font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; padding: 5px 12px; border-radius: 99px; margin-bottom: 18px; }
    .card-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); animation: pulse 2s ease-in-out infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

    .card-title { font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 400; color: #fff; line-height: 1.15; margin-bottom: 6px; }
    .card-sub { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 11.5px; font-weight: 700; color: rgba(255,255,255,.5); letter-spacing: .6px; text-transform: uppercase; margin-bottom: 8px; }
    .input-wrap { position: relative; }
    .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.3); pointer-events: none; display: flex; align-items: center; }
    .input-icon svg { width: 16px; height: 16px; }

    .form-input { width: 100%; padding: 13px 16px 13px 42px; background: rgba(255,255,255,.07); border: 1.5px solid rgba(255,255,255,.12); border-radius: 11px; font-size: 14px; font-family: 'DM Sans', sans-serif; color: #fff; outline: none; transition: border-color .2s, background .2s, box-shadow .2s; caret-color: var(--gold); }
    .form-input::placeholder { color: rgba(255,255,255,.25); }
    .form-input:focus { border-color: rgba(201,147,58,.6); background: rgba(255,255,255,.1); box-shadow: 0 0 0 3px rgba(201,147,58,.12); }

    .toggle-pw { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,.3); display: flex; align-items: center; transition: color .2s; padding: 2px; }
    .toggle-pw:hover { color: rgba(255,255,255,.65); }
    .toggle-pw svg { width: 16px; height: 16px; }

    .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
    .remember-label { display: flex; align-items: center; gap: 9px; cursor: pointer; font-size: 13px; color: rgba(255,255,255,.55); user-select: none; }
    .remember-check { width: 16px; height: 16px; border-radius: 4px; border: 1.5px solid rgba(255,255,255,.2); background: rgba(255,255,255,.06); cursor: pointer; accent-color: var(--gold); flex-shrink: 0; }
    .forgot-link { font-size: 13px; font-weight: 600; color: var(--gold-light); text-decoration: none; transition: color .2s; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .forgot-link:hover { color: #fff; }

    .btn-login { width: 100%; padding: 14px; background: linear-gradient(135deg, var(--gold) 0%, #b8812e 100%); border: none; border-radius: 11px; cursor: pointer; font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif; color: #fff; letter-spacing: .3px; transition: transform .18s, box-shadow .18s, filter .18s; box-shadow: 0 6px 24px rgba(201,147,58,.35); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-login:hover { transform: translateY(-1px); box-shadow: 0 10px 32px rgba(201,147,58,.45); filter: brightness(1.08); }
    .btn-login:active { transform: translateY(0); }
    .btn-login::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 50%); pointer-events: none; }

    .spinner { width: 18px; height: 18px; border-radius: 50%; border: 2.5px solid rgba(255,255,255,.3); border-top-color: #fff; animation: spin .7s linear infinite; display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .form-error { display: none; align-items: center; gap: 8px; background: rgba(220,38,38,.15); border: 1px solid rgba(220,38,38,.3); color: #fca5a5; font-size: 12.5px; font-weight: 500; padding: 10px 14px; border-radius: 9px; margin-bottom: 18px; animation: shake .4s ease; }
    .form-error.show { display: flex; }
    .form-error svg { width: 14px; height: 14px; flex-shrink: 0; }
    @keyframes shake { 0%,100%{ transform:translateX(0); } 20%{ transform:translateX(-6px); } 40%{ transform:translateX(6px); } 60%{ transform:translateX(-4px); } 80%{ transform:translateX(4px); } }

    .success-overlay { display: none; position: absolute; inset: 0; background: rgba(15,33,56,.96); border-radius: 24px; flex-direction: column; align-items: center; justify-content: center; backdrop-filter: blur(8px); z-index: 10; animation: fadeIn .3s ease; }
    .success-overlay.show { display: flex; }
    @keyframes fadeIn { from{opacity:0;} to{opacity:1;} }
    .success-check { width: 64px; height: 64px; border-radius: 50%; background: rgba(22,163,74,.2); border: 2px solid rgba(22,163,74,.5); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; animation: popIn .4s cubic-bezier(.34,1.56,.64,1) .1s both; }
    @keyframes popIn { from{transform:scale(.5);opacity:0;} to{transform:scale(1);opacity:1;} }
    .success-check svg { width: 28px; height: 28px; color: #4ade80; }
    .success-text { color: #fff; font-size: 18px; font-weight: 600; }
    .success-sub { color: rgba(255,255,255,.5); font-size: 13px; margin-top: 6px; }

    .card-footer { margin-top: 28px; text-align: center; font-size: 12px; color: rgba(255,255,255,.25); }
    .card-footer a { color: rgba(255,255,255,.4); text-decoration: none; }
    .card-footer a:hover { color: rgba(255,255,255,.7); }

    @media (max-width: 900px) {
      .layout { grid-template-columns: 1fr; }
      .left-panel { display: none; }
      .right-panel { padding: 32px 24px; align-items: flex-start; padding-top: 80px; }
      .login-card { padding: 36px 28px; }
      html, body { overflow: auto; }
    }
  </style>
</head>
<body>

<div class="bg"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="layout">

  {{-- LEFT PANEL --}}
  <div class="left-panel">
    <div class="left-logo">
      <div class="logo-mark">
        <img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH"
             style="width:100%;height:100%;border-radius:13px;object-fit:cover;"
             onerror="this.style.display='none'" />
      </div>
      <div class="logo-text">
        <span class="logo-name">Dalubhasaang Politekniko ng Lungsod ng Baliwag</span>
        <span class="logo-sub">Baliwag Polytechnic College</span>
      </div>
    </div>

    <div class="left-hero">
      <div class="hero-eyebrow">Admissions System</div>
      <h1 class="hero-title">
        Manage <em>admissions</em><br>with clarity.
      </h1>
      <p class="hero-desc">
        A streamlined portal for admissions officers to review applications,
        track enrollment status, and generate insights across all programs.
      </p>

      <div class="stats-row">
        <div class="stat-item">
          <span class="stat-value">16</span>
          <span class="stat-label">Programs Offered</span>
        </div>
        <div class="stat-div"></div>
        <div class="stat-item">
          <span class="stat-value">25+</span>
          <span class="stat-label">Active Applicants</span>
        </div>
        <div class="stat-div"></div>
        <div class="stat-item">
          <span class="stat-value">S.Y. 2026</span>
          <span class="stat-label">Current Cycle</span>
        </div>
      </div>
    </div>
  </div>

  {{-- RIGHT PANEL --}}
  <div class="right-panel">
    <div class="login-card">

      {{-- Success overlay --}}
      <div class="success-overlay" id="successOverlay" data-success="{{ session('login_success') ? '1' : '0' }}" data-redirect="{{ route('admin.dashboard') }}">
        <div class="success-check">
          <i data-iconsax="check" style="width:28px;height:28px"></i>
        </div>
        <div class="success-text">Authenticated!</div>
        <div class="success-sub" id="successSub">Redirecting to dashboard…</div>
      </div>

      <div class="card-header">
        <div class="card-badge">
          <div class="card-badge-dot"></div>
          Admin Portal
        </div>
        <div class="card-title">Welcome back,<br>Administrator</div>
        <div class="card-sub">Sign in to access the admissions dashboard</div>
      </div>

      {{-- Laravel validation errors --}}
      @if ($errors->any())
        <div class="form-error show">
          <i data-iconsax="alert-circle" style="width:16px;height:16px"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      {{-- Session error (e.g. from failed login attempt) --}}
      @if (session('error'))
        <div class="form-error show">
          <i data-iconsax="alert-circle" style="width:16px;height:16px"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      {{-- JS-driven error (for API-based login) --}}
      <div class="form-error" id="formError">
        <i data-iconsax="alert-circle" style="width:16px;height:16px"></i>
        <span id="errorText">Invalid username or password.</span>
      </div>

      {{-- Login Form --}}
      <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
        @csrf

        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <div class="input-wrap">
            <span class="input-icon">
              <i data-iconsax="mail"></i>
            </span>
            <input type="email" id="email" name="email" class="form-input"
                   placeholder="johndoe@gmail.com"
                   value="{{ old('email') }}"
                   autocomplete="email" />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">
              <i data-iconsax="lock"></i>
            </span>
            <input type="password" id="password" name="password" class="form-input"
                   placeholder="Enter your password"
                   autocomplete="current-password" />
            <button class="toggle-pw" id="togglePw" type="button" tabindex="-1">
              <i id="eyeOpen" data-iconsax="eye"></i>
              <i id="eyeClosed" data-iconsax="eye-off" style="display:none"></i>
            </button>
          </div>
        </div>

        <div class="form-row">
          <label class="remember-label">
            <input type="checkbox" name="remember" class="remember-check" id="remember"
                   {{ old('remember') ? 'checked' : '' }} />
            Remember me
          </label>
          <a href="{{ route('admin.password.request') }}" class="forgot-link">Forgot password?</a>
        </div>

        <button class="btn-login" id="loginBtn" type="submit">
          <div class="spinner" id="spinner"></div>
          <span id="btnText">Sign In to Dashboard</span>
          <i id="btnArrow" data-iconsax="arrow-right" style="width:16px;height:16px"></i>
        </button>

      </form>

      <div class="card-footer">
        © {{ date('Y') }} Baliwag Polytechnic College &nbsp;·&nbsp;
        <a href="#">Privacy Policy</a> &nbsp;·&nbsp;
        <a href="#">Help</a>
      </div>

    </div>
  </div>

</div>

<script src="{{ asset('js/api-config.js') }}?v=9"></script>
<script src="{{ asset('js/admission-api.js') }}?v=13"></script>
<script>
  /* ── TOGGLE PASSWORD VISIBILITY ── */
  document.getElementById('togglePw').addEventListener('click', () => {
    const pw     = document.getElementById('password');
    const open   = document.getElementById('eyeOpen');
    const closed = document.getElementById('eyeClosed');
    const isHidden = pw.type === 'password';
    pw.type              = isHidden ? 'text'  : 'password';
    open.style.display   = isHidden ? 'none'  : '';
    closed.style.display = isHidden ? ''      : 'none';
  });

  /* ── LOADING STATE ON SUBMIT ── */
  document.getElementById('loginForm').addEventListener('submit', () => {
    document.getElementById('spinner').style.display  = 'block';
    document.getElementById('btnText').textContent    = 'Signing in…';
    document.getElementById('btnArrow').style.display = 'none';
    document.getElementById('loginBtn').style.opacity = '.8';
    document.getElementById('loginBtn').style.pointerEvents = 'none';
  });

  /* ── SHOW SUCCESS OVERLAY IF SESSION FLAG IS SET ── */
  const successOverlay = document.getElementById('successOverlay');
  if (successOverlay && successOverlay.getAttribute('data-success') === '1') {
    successOverlay.classList.add('show');
    setTimeout(() => { window.location.replace(successOverlay.getAttribute('data-redirect')); }, 800);
  }

  /* ── HIDE JS ERROR ON INPUT ── */
  ['email', 'password'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', () => {
      document.getElementById('formError').classList.remove('show');
    });
  });

  // Initialize Iconsax Icons
  if (typeof iconsax !== 'undefined') {
    iconsax.createIcons();
  }
</script>
</body>
</html>
