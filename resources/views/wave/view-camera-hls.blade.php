<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Camera HLS Stream</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; }
        video { width: 80%; max-width: 800px; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Live Camera Stream</h1>
    <video id="video" controls autoplay></video>

    <script>
        const video = document.getElementById('video');
        const streamUrl = "{{ $hlsUrl }}";

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(streamUrl);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = streamUrl;
            video.addEventListener('loadedmetadata', function() {
                video.play();
            });
        } else {
            alert('Your browser does not support HLS playback.');
        }
    </script>
</body>
</html>

