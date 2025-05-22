@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">Multi-Camera Streaming</h1>

    <style>
        /* Ensures that rotation does not interfere with layout */
        .invert-video {
            transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
            -moz-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -o-transform: rotate(180deg);
        }
    </style>

    @if(isset($streams) && count($streams) > 0)
        <div class="row">
            @foreach ($streams as $key => $stream)
                <div class="col-md-4">
                    <h3>Camera {{ $loop->index + 1 }}</h3>
                    <video 
                        id="video-{{ $key }}" 
                        width="100%" 
                        controls 
                        muted
                        class="border">
                    </video>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-danger">🚨 No camera streams available!</p>
    @endif

    <div class="alert alert-info mt-3">
        If the videos do not load, refresh the page or try again later.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @foreach ($streams as $key => $stream)
            let video{{ $key }} = document.getElementById('video-{{ $key }}');
            let videoSrc{{ $key }} = "{{ $stream }}"; // Unique variable for each camera

            console.log("Loading video for Camera {{ $loop->index + 1 }}:", videoSrc{{ $key }});

            // Apply Rotation to Camera 2 & 3
            if ({{ $loop->index }} === 1 || {{ $loop->index }} === 2) {
                video{{ $key }}.classList.add('invert-video');
            }

            if (Hls.isSupported()) {
                let hls{{ $key }} = new Hls();
                hls{{ $key }}.loadSource(videoSrc{{ $key }});
                hls{{ $key }}.attachMedia(video{{ $key }});
                hls{{ $key }}.on(Hls.Events.MANIFEST_PARSED, function () {
                    console.log("Video {{ $loop->index + 1 }} is ready to play.");
                    video{{ $key }}.muted = true; // Required for autoplay
                    video{{ $key }}.play().catch(error => {
                        console.log("Autoplay failed for Camera {{ $loop->index + 1 }}:", error);
                    });
                });
            } else if (video{{ $key }}.canPlayType('application/vnd.apple.mpegurl')) {
                video{{ $key }}.src = videoSrc{{ $key }};
                video{{ $key }}.addEventListener('loadedmetadata', function () {
                    console.log("Native HLS supported for Camera {{ $loop->index + 1 }}.");
                    video{{ $key }}.muted = true;
                    video{{ $key }}.play().catch(error => {
                        console.log("Autoplay failed for Camera {{ $loop->index + 1 }}:", error);
                    });
                });
            } else {
                console.log("HLS not supported on this browser.");
            }
        @endforeach
    });
</script>

@endsection

