<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BTECH — Reset Password</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}"/>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet" />
  @include('partials.iconsax')
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root { --navy: #1b3557; --navy-mid: #254d82; --navy-deep: #0f2138; --gold: #c9933a; --gold-light: #e8b96a; --border: rgba(255,255,255,.12); --text-muted: rgba(255,255,255,.45); }
    html, body { height: 100%; font-family: 'DM Sans', system-ui, sans-serif; background: var(--navy-deep); overflow: hidden; }
    .bg { position: fixed; inset: 0; z-index: 0; background: radial-gradient(ellipse 80% 60% at 15% 50%, rgba(37,77,130,.55) 0%, transparent 70%), radial-gradient(ellipse 50% 80% at 90% 20%, rgba(201,147,58,.12) 0%, transparent 60%), radial-gradient(ellipse 60% 60% at 80% 90%, rgba(27,53,87,.8) 0%, transparent 70%), #0f2138; }
    .bg::before { content: ''; position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px); background-size: 60px 60px; mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 80%); }
    .orb { position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; z-index: 0; animation: drift 20s ease-in-out infinite; }
    .orb-1 { width: 500px; height: 500px; top: -100px; left: -80px; background: rgba(37,77,130,.35); }
    .orb-2 { width: 350px; height: 350px; bottom: -60px; right: -60px; background: rgba(201,147,58,.15); animation-delay: -8s; }
    .orb-3 { width: 250px; height: 250px; top: 40%; right: 15%; background: rgba(58,109,173,.2); animation-delay: -14s; }
    @keyframes drift { 0%,100%{ transform:translate(0,0) scale(1); } 33%{ transform:translate(30px,-20px) scale(1.05); } 66%{ transform:translate(-20px,25px) scale(.96); } }

    .layout { position: relative; z-index: 1; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 40px 24px; }
    .card { width: 100%; max-width: 440px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); border-radius: 24px; padding: 48px 44px; backdrop-filter: blur(24px); box-shadow: 0 32px 80px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.12); animation: slideIn .75s cubic-bezier(.4,0,.2,1) both; position: relative; overflow: hidden; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; width: 140px; height: 140px; background: radial-gradient(circle at top left, rgba(201,147,58,.18), transparent 70%); pointer-events: none; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

    .icon-circle { width: 64px; height: 64px; border-radius: 50%; background: rgba(201,147,58,.15); border: 1.5px solid rgba(201,147,58,.35); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; }
    .icon-circle svg { width: 28px; height: 28px; color: var(--gold-light); }

    .card-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(201,147,58,.15); border: 1px solid rgba(201,147,58,.3); color: var(--gold-light); font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; padding: 5px 12px; border-radius: 99px; margin-bottom: 18px; }
    .card-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); animation: pulse 2s ease-in-out infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }
    .card-title { font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 400; color: #fff; line-height: 1.15; margin-bottom: 6px; }
    .card-sub { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 32px; }

    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 11.5px; font-weight: 700; color: rgba(255,255,255,.5); letter-spacing: .6px; text-transform: uppercase; margin-bottom: 8px; }
    .input-wrap { position: relative; }
    .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.3); pointer-events: none; display: flex; align-items: center; }
    .input-icon svg { width: 16px; height: 16px; }
    .form-input { width: 100%; padding: 13px 44px 13px 42px; background: rgba(255,255,255,.07); border: 1.5px solid rgba(255,255,255,.12); border-radius: 11px; font-size: 14px; font-family: 'DM Sans', sans-serif; color: #fff; outline: none; transition: border-color .2s, background .2s, box-shadow .2s; caret-color: var(--gold); }
    .form-input::placeholder { color: rgba(255,255,255,.25); }
    .form-input:focus { border-color: rgba(201,147,58,.6); background: rgba(255,255,255,.1); box-shadow: 0 0 0 3px rgba(201,147,58,.12); }
    .toggle-pw { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,.3); display: flex; align-items: center; transition: color .2s; padding: 2px; }
    .toggle-pw:hover { color: rgba(255,255,255,.65); }
    .toggle-pw svg { width: 16px; height: 16px; }

    .btn-primary { width: 100%; padding: 14px; background: linear-gradient(135deg, var(--gold) 0%, #b8812e 100%); border: none; border-radius: 11px; cursor: pointer; font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif; color: #fff; letter-spacing: .3px; transition: transform .18s, box-shadow .18s, filter .18s; box-shadow: 0 6px 24px rgba(201,147,58,.35); display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 8px; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 32px rgba(201,147,58,.45); filter: brightness(1.08); }

    .form-error { display: none; align-items: center; gap: 8px; background: rgba(220,38,38,.15); border: 1px solid rgba(220,38,38,.3); color: #fca5a5; font-size: 12.5px; font-weight: 500; padding: 10px 14px; border-radius: 9px; margin-bottom: 18px; }
    .form-error.show { display: flex; }
    .form-error svg { width: 14px; height: 14px; flex-shrink: 0; }

    @media (max-width: 500px) { .card { padding: 36px 24px; } }
  </style>
</head>
<body>
<div class="bg"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="layout">
  <div class="card">

    <div class="icon-circle">
      <i data-iconsax="shield-check"></i>
    </div>

    <div class="card-badge">
      <div class="card-badge-dot"></div>
      Set New Password
    </div>
    <div class="card-title">Create a new<br>password</div>
    <div class="card-sub">Choose a strong password for your admin account.</div>

    @if ($errors->any())
      <div class="form-error show">
        <i data-iconsax="alert-circle" style="width:14px;height:14px"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}" />

      <div class="form-group">
        <label class="form-label" for="email">Email Address</label>
        <div class="input-wrap">
          <span class="input-icon">
            <i data-iconsax="mail"></i>
          </span>
          <input type="email" id="email" name="email" class="form-input"
                 placeholder="admin@admission.edu"
                 value="{{ old('email', $request->email) }}"
                 autocomplete="email" required />
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">New Password</label>
        <div class="input-wrap">
          <span class="input-icon">
            <i data-iconsax="lock"></i>
          </span>
          <input type="password" id="password" name="password" class="form-input"
                 placeholder="Enter new password"
                 autocomplete="new-password" required />
          <button class="toggle-pw" id="togglePw1" type="button" tabindex="-1">
            <i id="eye1Open" data-iconsax="eye"></i>
            <i id="eye1Closed" data-iconsax="eye-off" style="display:none"></i>
          </button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="password_confirmation">Confirm Password</label>
        <div class="input-wrap">
          <span class="input-icon">
            <i data-iconsax="lock"></i>
          </span>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                 placeholder="Re-enter new password"
                 autocomplete="new-password" required />
          <button class="toggle-pw" id="togglePw2" type="button" tabindex="-1">
            <i id="eye2Open" data-iconsax="eye"></i>
            <i id="eye2Closed" data-iconsax="eye-off" style="display:none"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-primary">
        <i data-iconsax="check-circle" style="width:16px;height:16px"></i>
        Reset Password
      </button>
    </form>

  </div>
</div>

<script>
  [[1], [2]].forEach(([n]) => {
    const btn   = document.getElementById(`togglePw${n}`);
    const input = document.getElementById(n === 1 ? 'password' : 'password_confirmation');
    const open  = document.getElementById(`eye${n}Open`);
    const close = document.getElementById(`eye${n}Closed`);
    btn.addEventListener('click', () => {
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      open.style.display  = isHidden ? 'none' : '';
      close.style.display = isHidden ? '' : 'none';
    });
  });
</script>
<script>
  if (typeof iconsax !== 'undefined') {
    iconsax.createIcons();
  }
</script>
</body>
</html>
