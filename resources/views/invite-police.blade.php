@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="alert alert-primary text-center">
        🚔 <strong>Secure Police Video Link</strong><br>
        ⚠️ This link will expire in <strong>90 minutes</strong><br>
        Multiple views allowed within that time window
    </div>

    <div class="row">
        <!-- Recorded Video -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">📼 Recorded Clip</div>
                <div class="card-body p-0">
                    <video controls autoplay muted style="width:100%;">
                        <source src="{{ asset('storage/demo-recorded.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>

        <!-- Multicam Live View -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">📡 Live Multicam View</div>
                <div class="card-body p-0">
                    @include('partials.multicam') <!-- reuse existing multicam view -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

