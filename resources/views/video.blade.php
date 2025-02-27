@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">Live & Recorded Video</h1>

    <div class="row justify-content-center">
        <!-- Live Stream Section -->
        <div class="col-md-5">
            <h3>Live Stream</h3>
            <div class="video-box">
                <img src="{{ asset('images/live-stream-thumbnail.jpg') }}" alt="Live Stream" class="img-fluid rounded shadow-sm">
            </div>
            <a href="{{ route('live.stream') }}" class="btn btn-primary btn-lg mt-3">Watch Live</a>
        </div>

        <!-- Last 5 Minutes Section -->
        <div class="col-md-5">
            <h3>Last 5 Minutes</h3>
            <div class="video-box">
                <img src="{{ asset('images/recorded-thumbnail.jpg') }}" alt="Last 5 Min" class="img-fluid rounded shadow-sm">
            </div>
            <a href="#" class="btn btn-warning btn-lg mt-3">Get Last 5 Minutes</a>
        </div>
    </div>
</div>
@endsection

