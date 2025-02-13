<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authenticate Wisenet Wave</title>
</head>
<body>
    <h1>Authenticate with Wisenet Wave</h1>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('wave.auth') }}" method="POST">
        @csrf
        <button type="submit">Login to Wave</button>
    </form>
</body>
</html>
