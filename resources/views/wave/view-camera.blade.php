<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Camera Stream</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .container { max-width: 800px; margin: 50px auto; text-align: center; }
        .stream-container { margin-top: 20px; }
        .stream-container img { width: 100%; border: 1px solid #ccc; }
        .alert { padding: 10px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Live Camera Stream</h1>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="stream-container">
            <!-- The MJPEG stream is loaded via an <img> tag -->
            <img src="{{ $stream_url }}" alt="Camera Stream">
        </div>
    </div>
</body>
</html>

