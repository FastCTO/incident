{{-- resources/views/send-police-link.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            🚓 Send Secure Police Video Link
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('send.police.link') }}">
                @csrf
                <div class="mb-3">
                    <label for="phone" class="form-label">📱 Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control w-50" placeholder="e.g., 2135556789" required>
                    <small class="form-text text-muted">Enter a 10-digit US/Canada number. We’ll add the country code automatically.</small>
                </div>

                <button type="submit" class="btn btn-success">
                    🚀 Send Secure Link
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

