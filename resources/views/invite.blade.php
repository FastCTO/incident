@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <!-- Invite New User Card -->
        <div class="col-md-6 mb-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">Send Invite to New User</div>

                <div class="card-body">
                    <form action="{{ route('invite') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="cell_number" class="form-label">Cell Number</label>
                            <input type="text" class="form-control" id="cell_number" name="cell_number" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email (optional)</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <button type="submit" class="btn btn-primary">Send Invite</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Send Police Link Card -->
        <div class="col-md-6 mb-4">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="card">
                <div class="card-header">Send Video Invite to Police</div>

                <div class="card-body">
                    <form action="{{ route('send.police.link') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="phone" class="form-label">Cell Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="10-digit number" required>
                        </div>

                        <button type="submit" class="btn btn-success">Send Police Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

