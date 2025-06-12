@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-5 text-center">Video Center</h1>

    <div class="row row-cols-1 row-cols-md-3 g-4">

        {{-- Live Stream --}}
        <div class="col">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 mb-2">🎥</div>
                    <h5 class="card-title">Live Stream</h5>
                    <p class="card-text">Watch your campus cameras in real-time.</p>
                    <a href="{{ route('live.stream') }}" class="btn btn-primary">Go Live</a>
                </div>
            </div>
        </div>

        {{-- Recorded Video --}}
        <div class="col">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 mb-2">📹</div>
                    <h5 class="card-title">Recorded Footage</h5>
                    <p class="card-text">Access saved clips and camera recordings.</p>
                    <a href="{{ asset('images/doorwatch1.mp4') }}" target="_blank" class="btn btn-secondary">View Clip</a>
                </div>
            </div>
        </div>

        {{-- Multi-Camera View --}}
        <div class="col">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 mb-2">📡</div>
                    <h5 class="card-title">Multi-Camera View</h5>
                    <p class="card-text">Monitor multiple feeds in a single screen layout.</p>
                    <a href="{{ route('video.multistream') }}" class="btn btn-info">Launch View</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

