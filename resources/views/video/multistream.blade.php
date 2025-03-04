@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">Multi-Camera Streaming</h1>

    <style>
        /* Video Container for Rotation */
        .video-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Rotate Camera 2 & 3 */
        .invert-video {
            transform: rotate(180deg);
            -webkit-transform: rotate(180deg); /* Safari */
            -moz-transform: rotate(180deg); /* Mozilla */
            -ms-transform: rotate(180deg); /* Microsoft */
            -o-transform: rotate(180deg); /* Opera */
        }
    </style>

    @if(isset($streams) && count($streams) > 0)
        <div class="row">
            @foreach ($streams as $key => $stream)
                <div class="col-md-4">
                    <h3>Camera {{ $loop->index + 1 }}</h3>
                    <div class="video-wrapper @if($loop->index == 1 || $loop->index == 2) invert-video @endif">
                        <video 
                            id="video-{{ $key }}" 
                            width="100%" 
                            controls 
                            muted
                            class="border">
                        </video>
                    </div>
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

