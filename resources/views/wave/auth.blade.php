<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Authenticate with Wisenet Wave</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    .container { max-width: 500px; margin: 50px auto; }
    .alert { padding: 10px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
    .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Authenticate with Wisenet Wave</h1>

    @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    <form action="{{ route('wave.auth') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="admin" class="form-control" required>
      </div>
      <br>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" value="H33d-bang" class="form-control" required>
      </div>
      <br>
      <button type="submit" class="btn btn-primary">Login to Wave</button>
    </form>
  </div>
</body>
</html>

