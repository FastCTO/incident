@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <img src="{{ asset('images/school.jpg') }}" alt="School" style="width: 100%; max-width: 500px;">
        <h1>{{ $school->name }}</h1>
        <p>{{ $school->address }}</p>
    </div>

    <div class="row text-center">
        <div class="col-md-4">
            <h3>{{ $occupiedRooms }}</h3>
            <p>Occupied Rooms</p>
        </div>
        <div class="col-md-4">
            <h3>{{ $totalOccupancy }}</h3>
            <p>Total People</p>
        </div>
        <div class="col-md-4">
            <h3>{{ $emergencyStatus == 'emergency' ? 'Emergency' : 'Normal' }}</h3>
            <p>Status</p>
        </div>
    </div>

    <div class="mt-4 text-center">
        @if ($isRoomLeader)
            <a href="{{ route('rooms.show', ['id' => $userRoom]) }}" class="btn btn-primary">Go to My Room</a>
        @endif
        <a href="{{ route('emergency.report') }}" class="btn btn-danger">Report Emergency</a>
    </div>
</div>
@endsection

