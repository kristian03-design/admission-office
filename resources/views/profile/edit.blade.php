<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
</head>
<body>
  <main>
    <h1>Profile</h1>

    @if ($errors->any())
      <div>
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      @method('PATCH')
      <label>
        Name
        <input name="name" type="text" value="{{ old('name', $user->name) }}" required>
      </label>
      <label>
        Email
        <input name="email" type="email" value="{{ old('email', $user->email) }}" required>
      </label>
      <button type="submit">Save Profile</button>
    </form>

    <form method="POST" action="{{ route('profile.destroy') }}">
      @csrf
      @method('DELETE')
      <label>
        Password
        <input name="password" type="password" required>
      </label>
      <button type="submit">Delete Account</button>
    </form>
  </main>
</body>
</html>
