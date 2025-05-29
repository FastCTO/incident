{{-- resources/views/video/multistream-secure.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Live Camera View</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        body { margin: 0; background: #000; color: #fff; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 10px;
            padding: 10px;
        }
        video {
            width: 100%;
            height: auto;
            background: #000;
        }
    </style>
</head>
<body>
    <div class="grid">
        @foreach ($streams as $key => $url)
            <video id="video-{{ $loop->index }}" controls autoplay muted></video>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const video = document.getElementById('video-{{ $loop->index }}');
                    const streamUrl = @json($url);

                    if (Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(streamUrl);
                        hls.attachMedia(video);
                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = streamUrl;
                    } else {
                        console.error('HLS not supported on this browser');
                    }
                });
            </script>
        @endforeach
    </div>
</body>
</html>

