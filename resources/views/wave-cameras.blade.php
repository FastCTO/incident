<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Cameras</title>
</head>
<body>
    <h1>Available Cameras</h1>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if(isset($cameras) && count($cameras) > 0)
        <ul>
            @foreach($cameras as $camera)
                <li>
                    <a href="{{ route('wave.camera.view', ['id' => $camera['id']]) }}">
                        {{ $camera['name'] }} (ID: {{ $camera['id'] }})
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No cameras found.</p>
    @endif
</body>
</html>

