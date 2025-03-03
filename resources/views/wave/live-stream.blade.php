@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">Live Camera Streaming</h1>

    <video id="video" width="100%" controls class="border"></video>

    <div class="alert alert-info mt-3">
        If the video does not load, refresh the page or try again later.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const video = document.getElementById('video');
        const videoSrcHD = "{{ $streamUrlHD }}";
        const videoSrcSD = "{{ $streamUrlSD }}";

        function loadVideo() {
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(videoSrcHD);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function () {
                    video.play();
                });
                hls.on(Hls.Events.ERROR, function (event, data) {
                    if (data.fatal && data.type === Hls.ErrorTypes.NETWORK_ERROR) {
                        console.log('HD stream failed, switching to SD...');
                        hls.loadSource(videoSrcSD);
                    }
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = videoSrcHD;
                video.addEventListener('error', function () {
                    console.log('HD failed, switching to SD...');
                    video.src = videoSrcSD;
                });
                video.addEventListener('loadedmetadata', function () {
                    video.play();
                });
            }
        }

        loadVideo();
    });
</script>
@endsection

