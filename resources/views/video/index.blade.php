@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Video Page</h1>
    
    <div class="d-flex gap-3">
        <a href="{{ route('live.stream') }}" class="btn btn-primary">🎥 Live Stream</a>
        <a href="{{ route('video.recorded') }}" class="btn btn-secondary">📹 Recorded Video</a>
    </div>
</div>
@endsection

