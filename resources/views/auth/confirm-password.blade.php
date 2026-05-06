<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Password</title>
</head>
<body>
  <main>
    <h1>Confirm Password</h1>

    @if ($errors->any())
      <div>
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ url('/confirm-password') }}">
      @csrf
      <label>
        Password
        <input name="password" type="password" required autofocus>
      </label>
      <button type="submit">Confirm</button>
    </form>
  </main>
</body>
</html>
