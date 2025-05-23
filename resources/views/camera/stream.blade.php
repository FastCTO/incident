<!DOCTYPE html>
<html lang="en">
<head>
    <title>Live Camera Stream</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.18.1/video.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.18.1/video-js.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
        }
        .form-container {
            margin-bottom: 2rem;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            width: 100%;
            max-width: 500px;
        }
        .form-container input {
            padding: 0.5rem;
            width: 70%;
            margin-right: 10px;
        }
        .form-container button {
            padding: 0.5rem 1rem;
        }
        .status-message {
            margin-top: 1rem;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
    <h1>Live Camera Stream</h1>

    <!-- Secure Police Link Sender Form -->
    <div class="form-container">
        <form method="POST" action="/send-invite-police-link">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <label for="phone">Send Secure Link to Officer:</label><br><br>
            <input type="text" name="phone" placeholder="Enter phone #" required>
            <button type="submit">Send Link</button>
        </form>

        @if(session('status'))
            <div class="status-message">
                ✅ {{ session('status') }}
            </div>
        @endif
    </div>

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

        refreshStream(); // Initial load
        setInterval(refreshStream, 240000); // Refresh every 4 min
    </script>
</body>
</html>

