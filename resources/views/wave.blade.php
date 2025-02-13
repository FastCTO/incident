
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisenet Wave Cameras</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Wisenet Wave Camera Feeds</h1>

        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        @if(isset($cameras) && count($cameras) > 0)
            <ul>
                @foreach($cameras as $camera)
                    <li>
                        {{ $camera['name'] }} (ID: {{ $camera['id'] }})
                        <br>
                        <a href="{{ route('wave.camera.view', ['id' => $camera['id']]) }}" target="_blank">
                            View Stream
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No cameras found or API failed to retrieve data.</p>
        @endif

        <form method="POST" action="{{ route('wave.auth') }}">
            @csrf
            <button type="submit">Login to Wave</button>
        </form>
    </div>
</body>
</html>

