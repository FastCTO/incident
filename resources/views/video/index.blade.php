@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Video Page</h1>
    
    <div class="d-flex gap-3">
        <a href="{{ route('live.stream') }}" class="btn btn-primary">🎥 Live Stream</a>
        <a href="{{ asset('images/doorwatch1.mp4') }}" class="btn btn-secondary" target="_blank">📹 Recorded Video</a>
        <a href="{{ route('video.multistream') }}" class="btn btn-info">📡 Multi-Camera</a>
    </div>
</div>
@endsection

