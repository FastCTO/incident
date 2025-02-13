<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wisenet Wave Cameras</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    .container { max-width: 800px; margin: 50px auto; }
    .list-group { list-style: none; padding: 0; }
    .list-group-item { padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; }
    a { text-decoration: none; color: #333; }
    a:hover { text-decoration: underline; }
    .alert { padding: 10px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
    .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
    .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Available Cameras</h1>

    @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(isset($cameras) && count($cameras) > 0)
      <ul class="list-group">
        @foreach($cameras as $camera)
          <li class="list-group-item">
            <strong>{{ $camera['name'] }}</strong> (ID: {{ $camera['id'] }})
            <br>
            <!-- Link to view HLS stream -->
            <a href="{{ route('wave.camera.hls', ['id' => $camera['id']]) }}">View HLS Stream</a>
          </li>
        @endforeach
      </ul>
    @else
      <p>No cameras found.</p>
    @endif
  </div>
</body>
</html>

