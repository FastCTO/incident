@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center">
        <!-- School Information -->
        <img src="{{ asset('images/school.jpg') }}" alt="School Picture" style="max-width: 100%; height: auto;">
        <h1>{{ $school->name }}</h1>
        <p>{{ $school->address }}</p>

        <!-- Occupancy and Emergency Status -->
        <div>
            <p><strong>Rooms Occupied:</strong> {{ $roomsOccupied }}</p>
            <p><strong>People in Building:</strong> {{ $peopleInBuilding }}</p>
            <p><strong>Emergency Status:</strong>
                <span style="color: {{ $emergencyStatus === 'Emergency' ? 'red' : 'green' }}">
                    {{ $emergencyStatus }}
                </span>
            </p>
        </div>

        <!-- Buttons -->
        <div style="margin-top: 20px;">
            @if ($userRoom)
                <a href="{{ route('rooms.show', $userRoom->id) }}" class="btn btn-primary">
                    Go to My Room
                </a>
            @endif
            <a href="{{ route('emergency.report') }}" class="btn btn-danger">
                Report Emergency
            </a>
        </div>
    </div>
</div>
@endsection

