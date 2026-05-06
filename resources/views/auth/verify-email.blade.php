<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Email</title>
</head>
<body>
  <main>
    <h1>Verify Email</h1>
    <p>Please verify your email address before continuing.</p>

    @if (session('status') === 'verification-link-sent')
      <p>A new verification link has been sent to your email address.</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit">Resend Verification Email</button>
    </form>
  </main>
</body>
</html>
