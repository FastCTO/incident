<!DOCTYPE html>
<html lang="en">
<head>
    <title>Live Camera Stream</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.18.1/video.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.18.1/video-js.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Live Camera Stream</h1>
    
    <video id="camera-stream" class="video-js vjs-default-skin" controls autoplay width="640" height="360">
        <source id="stream-source" type="application/x-mpegURL">
    </video>

    <script>
        var player = videojs('camera-stream');

        function refreshStream() {
            $.get('/live-stream', function(data) {
                if (data.streamUrl) {
                    console.log("New Stream URL: " + data.streamUrl);
                    
                    // Update video source
                    $("#stream-source").attr("src", data.streamUrl);
                    player.src({ type: "application/x-mpegURL", src: data.streamUrl });
                    player.load();
                    player.play();
                } else {
                    console.error("Failed to load stream.");
                }
            });
        }

        // Load stream on page load
        refreshStream();

        // Refresh stream every 4 minutes
        setInterval(refreshStream, 240000);
    </script>
</body>
</html>

