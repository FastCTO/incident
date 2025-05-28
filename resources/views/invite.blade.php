@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Invite a New User</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach</ul>
        </div>
    @endif

    <!-- User Invite Form -->
    <form method="POST" action="{{ route('invite') }}" class="mb-5 p-4 bg-light border rounded">
        @csrf
        <h3 class="mb-3">Send Invite to New User</h3>
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" required class="form-control">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" required class="form-control">
        </div>

        <div class="form-group">
            <label for="cell_number">Cell Number</label>
            <input type="text" name="cell_number" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email (optional)</label>
            <input type="email" name="email" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Send User Invite</button>
    </form>

    <!-- Police Video Link Invite Form -->
    <form method="POST" action="{{ route('send.police.link') }}" class="p-4 bg-light border rounded">
        @csrf
        <h3 class="mb-3">🚓 Send Police Video Link (expires in 90 minutes)</h3>
        <div class="form-group">
            <label for="phone">Police Cell Number</label>
            <input type="text" name="phone" required class="form-control">
        </div>

        <button type="submit" class="btn btn-danger mt-3">Send Police Link</button>
    </form>
</div>
@endsection

