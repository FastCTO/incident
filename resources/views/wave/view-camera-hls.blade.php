<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Camera HLS Stream</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    .container { max-width: 800px; margin: 50px auto; text-align: center; }
    .url-box { background: #f7f7f7; padding: 20px; border: 1px solid #ccc; margin: 20px auto; width: fit-content; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Live Camera HLS Stream</h1>
    <video id="video" width="640" height="360" controls></video>
    <p>If the video does not load, copy this URL into a compatible player:</p>
    <div class="url-box">
      <strong>{{ $hlsUrl }}</strong>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
  <script>
    const video = document.getElementById('video');
    const videoSrc = "{{ $hlsUrl }}";

    function loadVideo() {
      if (Hls.isSupported()) {
        const hls = new Hls();
        hls.loadSource(videoSrc);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function() {
          video.play();
        });

        hls.on(Hls.Events.ERROR, function(event, data) {
          console.error("HLS Error:", data);
          if (data.type === Hls.ErrorTypes.NETWORK_ERROR) {
            console.warn("🔄 Network error, reloading stream...");
            window.location.reload();
          }
        });

        hls.on(Hls.Events.BUFFER_EMPTY, function() {
          console.warn("⏳ Stream buffer empty, reloading...");
          window.location.reload();
        });

      } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = videoSrc;
        video.addEventListener('loadedmetadata', function() {
          video.play();
        });
      }
    }

    loadVideo();
  </script>
</body>
</html>

