@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">School Management Dashboard</h1>

    {{-- 🏫 School & Status --}}
    <div class="card shadow-sm mb-5">
        <div class="card-body text-center">
            <h2 class="mb-3">{{ $school->name }}</h2>

            @php
                $statusColors = [
                    'Normal' => 'success',
                    'Emergency' => 'danger',
                    'Active Shooter' => 'dark',
                    'Lockdown' => 'warning',
                ];
                $statusColor = $statusColors[$school->status] ?? 'secondary';
            @endphp

            <div class="mb-3">
                <span class="badge bg-{{ $statusColor }} fs-5">
                    Current Status: {{ $school->status }}
                </span>
            </div>

            <form method="POST" action="{{ route('school.management.update') }}" class="row g-2 justify-content-center">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="Normal" {{ $school->status == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Emergency" {{ $school->status == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="Active Shooter" {{ $school->status == 'Active Shooter' ? 'selected' : '' }}>Active Shooter</option>
                        <option value="Lockdown" {{ $school->status == 'Lockdown' ? 'selected' : '' }}>Lockdown</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ⚙️ Quick Action Buttons --}}
	<div class="row row-cols-1 row-cols-md-2 g-3 mb-5">
    <div class="col">
        <a href="{{ route('invite.form') }}" class="btn btn-secondary w-100 py-3 fs-5">
            👥 Invite Teachers/Staff
        </a>
    </div>
    <div class="col">
        <form method="POST" action="{{ route('emergency.sendtext') }}">
            @csrf
            <button type="submit" class="btn btn-danger w-100 py-3 fs-5">
                📢 Send Emergency Text to Team
            </button>
        </form>
    </div>
    <div class="col">
        <a href="{{ route('video.logs') }}" class="btn btn-dark text-white w-100 py-3 fs-5">
            📼 View Video Access Logs
        </a>
    </div>
    <div class="col">
        <form method="POST" action="{{ route('school.management.simulateDbOutage') }}">
            @csrf
            <button type="submit" class="btn btn-warning w-100 py-3 fs-5 text-dark">
                🧪 Simulate DB Outage
            </button>
        </form>
    </div>
</div>


    {{-- 🧑‍🏫 Room - Teacher Management --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header">
            <h3>Room - Teacher Management</h3>
        </div>
        <div class="card-body">
            @foreach ($rooms as $room)
                <div class="mb-4">
                    <h5>{{ $room->name }}</h5>
                    @if($room->users->isEmpty())
                        <p class="text-muted">No teachers assigned.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($room->users as $teacher)
                                @if($teacher->role == 'teacher')
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $teacher->name }} <span class="text-muted">({{ $teacher->email }})</span>
                                        <form method="POST" action="{{ route('rooms.teacher.remove', ['roomId' => $room->id, 'teacherId' => $teacher->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- 💻 Server Management --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h3>Server Management</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('school.management.clearChat') }}">
                @csrf
                <button type="submit" class="btn btn-danger">
                    🧹 Clear Emergency Chat
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

