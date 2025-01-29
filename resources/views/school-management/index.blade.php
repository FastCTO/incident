@extends('layouts.app')

@section('content')
<div class="container">
    <h1>School Management Dashboard</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h2>{{ $school->name }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Security Status:</strong></p>
            <form method="POST" action="{{ route('school.management.update') }}">
                @csrf
                @method('PUT')
                <select name="status" class="form-select">
                    <option value="Normal" {{ $school->status == 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Emergency" {{ $school->status == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="Active Shooter" {{ $school->status == 'Active Shooter' ? 'selected' : '' }}>Active Shooter</option>
                    <option value="Lockdown" {{ $school->status == 'Lockdown' ? 'selected' : '' }}>Lockdown</option>
                </select>
                <button type="submit" class="btn btn-primary mt-3">Update Status</button>
            </form>

            <!-- Invite Button -->
            <div class="mt-4">
                <a href="{{ route('invite.form') }}" class="btn btn-secondary">Invite Teachers/Staff</a>
            </div>

            <!-- Send Emergency Text to Team -->
            <div class="mt-4">
                <form method="POST" action="{{ route('emergency.sendtext') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Send Emergency Text to Team</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header"><h2>Room - Teacher Management</h2></div>
        <div class="card-body">
            @foreach ($rooms as $room)
                <h5>{{ $room->name }}</h5>
                @if($room->users->isEmpty())
                    <p>No teachers assigned.</p>
                @else
                    <ul>
                        @foreach ($room->users as $teacher)
                            @if($teacher->role == 'teacher')
                                <li>
                                    {{ $teacher->name }} ({{ $teacher->email }})
                                    <form method="POST" action="{{ route('rooms.teacher.remove', ['roomId' => $room->id, 'teacherId' => $teacher->id]) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Server Management Section -->
    <div class="card mt-4">
        <div class="card-header bg-dark text-white"><h2>Server Management</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('school.management.clearChat') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Clear Emergency Chat</button>
            </form>
        </div>
    </div>
</div>
@endsection

