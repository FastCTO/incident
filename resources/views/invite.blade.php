{{-- resources/views/invite.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 h3 text-center">Send Invites</h1>

    {{-- ✅ Flash success message for both forms --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Box 1: Send Video Invite to Police -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    🚓 Send Video Invite to Police - Expires in 90 Minutes
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('send.police.link') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="police_phone" class="form-label">Phone Number</label>
                            <input type="text" id="police_phone" name="phone" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Police Video Link</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Box 2: Send Invite to New User -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    📨 Send Invite to New User
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invite') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="cell_number" class="form-label">Cell Number</label>
                            <input type="text" id="cell_number" name="cell_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (optional)</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Send Invite</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

