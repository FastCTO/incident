@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">Send Invites</h1>

    {{-- ✅ Flash success message --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 align-items-stretch">

        {{-- 🚓 Police Video Invite --}}
        <div class="col-md-6">
            <div class="card h-100 border-primary shadow-sm">
                <div class="card-header bg-primary text-white fs-5">
                    🚓 Send Video Invite to Police – Expires in 90 Minutes
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('send.police.link') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="police_phone" class="form-label">Phone Number</label>
                            <input type="text" id="police_phone" name="phone" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fs-5">Send Police Video Link</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 📨 Standard User Invite --}}
        <div class="col-md-6">
            <div class="card h-100 border-success shadow-sm">
                <div class="card-header bg-success text-white fs-5">
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
                        <button type="submit" class="btn btn-success w-100 fs-5">Send Invite</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

