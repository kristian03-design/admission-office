<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
</head>
<body>
  <main>
    <h1>Create Account</h1>

    @if ($errors->any())
      <div>
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <label>
        Name
        <input name="name" type="text" value="{{ old('name') }}" required autofocus>
      </label>
      <label>
        Email
        <input name="email" type="email" value="{{ old('email') }}" required>
      </label>
      <label>
        Password
        <input name="password" type="password" required>
      </label>
      <label>
        Confirm Password
        <input name="password_confirmation" type="password" required>
      </label>
      <button type="submit">Register</button>
    </form>
  </main>
</body>
</html>
