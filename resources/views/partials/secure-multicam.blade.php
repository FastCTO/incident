{{-- resources/views/video/multistream-secure.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Live Camera View</title>
    <style>
        body { margin: 0; background: #000; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 10px;
            padding: 10px;
        }
        video {
            width: 100%;
            height: auto;
            background: #000;
        }
    </style>
</head>
<body>
    <div class="grid">
        @foreach ($streams as $url)
            <video controls autoplay muted>
                <source src="{{ $url }}" type="application/x-mpegURL">
                Your browser does not support the video tag.
            </video>
        @endforeach
    </div>
</body>
</html>

