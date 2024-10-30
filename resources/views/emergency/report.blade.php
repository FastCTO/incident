@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Report Emergency</h1>

    <h2>School Details</h2>
    <ul>
        <li><strong>Name:</strong> {{ $school->name }}</li>
        <li><strong>Address:</strong> {{ $school->address }}</li>
        <li><strong>Total Floors:</strong> {{ $school->total_floors }}</li>
        <li><strong>Reporting User:</strong> {{ Auth::user()->name }}</li>
        <li><strong>User's Cell:</strong> {{ $userMobile }}</li>
        <li><strong>Current Building Occupancy:</strong> {{ $buildingOccupancy }}</li>
        <li><strong>Rooms Occupied:</strong> {{ $roomsOccupied }}</li>
        <li><strong>Reporting Room:</strong> {{ $reportingRoom }}</li>
    </ul>

    <h2>Room Leaders</h2>
    <ul>
        @foreach ($roomLeaders as $leader)
            <li>{{ $leader->name }} ({{ $leader->email }})</li>
        @endforeach
    </ul>

    <!-- Emergency Report Form -->
    <form action="{{ route('emergency.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="emergency_type" class="form-label">Emergency Type</label>
            <select id="emergency_type" name="emergency_type" class="form-control" required>
                <option value="Active Shooter">Active Shooter</option>
                <option value="Tornado">Tornado</option>
                <option value="Medical">Medical</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="reporting_phone" class="form-label">Reporting Phone</label>
            <input type="text" class="form-control" id="reporting_phone"
                   name="reporting_phone" value="{{ $userMobile }}" required>
        </div>

        <div class="mb-3">
            <label for="room_occupancy" class="form-label">Room Occupancy</label>
            <input type="number" class="form-control" id="room_occupancy"
                   name="room_occupancy" value="{{ $roomOccupancy }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Notes</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>

        <button type="submit" class="btn btn-danger btn-lg">Submit Emergency</button>
    </form>
</div>
@endsection

