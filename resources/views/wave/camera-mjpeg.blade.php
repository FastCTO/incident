<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Camera Stream (MJPEG)</title>
</head>
<body>
    <h1>Live Camera Stream (MJPEG)</h1>

    <p><strong>Debug MJPEG URL:</strong> {{ $mjpegUrl }}</p>

    @if(isset($mjpegUrl) && !empty($mjpegUrl))
        <img id="cameraStream" src="{{ $mjpegUrl }}" width="640" height="360" alt="Live Stream">
    @else
        <p style="color: red;">No camera stream available.</p>
    @endif
</body>
</html>

