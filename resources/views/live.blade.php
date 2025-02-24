@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Live Camera Stream</h2>

    @if(isset($error))
        <p style="color: red;">{{ $error }}</p>
    @else
        <video id="live-stream" controls width="640" height="360"></video>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    function loadStream() {
        fetch('/live-stream')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById("live-stream").outerHTML = "<p style='color: red;'>No stream available.</p>";
                    return;
                }
                var video = document.getElementById("live-stream");
                if (Hls.isSupported()) {
                    var hls = new Hls();
                    hls.loadSource(data.hlsUrlHD);
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, function() {
                        video.play();
                    });
                }
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadStream();
        setInterval(loadStream, 300000); // 🔥 Refresh every 5 minutes
    });
</script>
@endsection

