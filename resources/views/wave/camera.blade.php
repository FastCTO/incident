<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Camera Stream</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
    <h1>Live Camera Stream</h1>

    <p><strong>Debug HLS URL:</strong> {{ $hlsUrl }}</p>

    <video id="video" width="640" height="360" controls></video>

    @if(isset($hlsUrl))
        <script>
            console.log("HLS Stream URL: {{ $hlsUrl }}");

            const video = document.getElementById('video');
            const videoSrc = "{{ $hlsUrl }}";

            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(videoSrc);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    console.log("Manifest parsed successfully");
                    video.play();
                });
                hls.on(Hls.Events.ERROR, function(event, data) {
                    console.error("HLS error:", data);
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = videoSrc;
                video.addEventListener('loadedmetadata', function() {
                    video.play();
                });
            } else {
                console.error("HLS is not supported in your browser.");
            }
        </script>
    @else
        <p style="color: red;">No camera stream available.</p>
    @endif
</body>
</html>

