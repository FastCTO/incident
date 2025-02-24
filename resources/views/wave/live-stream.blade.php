<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Camera Stream</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
      .container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }
      .url-box {
            background: #f7f7f7;
            padding: 20px;
            border: 1px solid #ccc;
            margin: 20px auto;
            width: fit-content;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Live Camera Stream</h1>

        <video id="video" width="640" height="360" controls></video>

        <p>If the video does not load, try these URLs in a compatible player:</p>
        <div class="url-box">
            <strong>HD: {{ $streamUrlHD }}</strong><br>
            <strong>SD: {{ $streamUrlSD }}</strong> 
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const videoSrcHD = "{{ $streamUrlHD }}"; 
        const videoSrcSD = "{{ $streamUrlSD }}"; 

        function loadVideo() {
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(videoSrcHD); // Try HD first
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    video.play();
                });
                hls.on(Hls.Events.ERROR, function (event, data) {
                    if (data.fatal) {
                        console.error('HLS error:', data);
                        // If HD fails, try SD
                        if (data.type === Hls.ErrorTypes.NETWORK_ERROR) {
                            console.log('Trying SD stream...');
                            hls.loadSource(videoSrcSD); 
                        }
                    }
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = videoSrcHD; // Try HD first
                video.addEventListener('error', function() {
                    console.error('Error playing HD stream, trying SD...');
                    video.src = videoSrcSD; 
                });
                video.addEventListener('loadedmetadata', function() {
                    video.play();
                });
            }
        }

        loadVideo();
    </script>
</body>
</html>
