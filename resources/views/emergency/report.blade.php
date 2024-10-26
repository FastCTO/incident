@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Report Emergency</h1>

    <h2>School Details</h2>
    <ul>
        <li>Name: {{ $school->name }}</li>
        <li>Address: {{ $school->address }}</li>
        <li>Total Floors: {{ $school->total_floors }}</li>
        <li>Max Capacity: {{ $school->max_capacity }}</li>
    </ul>

    <h2>Room Leaders</h2>
    <ul>
        @foreach ($roomLeaders as $leader)
            <li>{{ $leader->name }} ({{ $leader->email }})</li>
        @endforeach
    </ul>

    <h2>Report an Emergency</h2>
    <form action="{{ route('emergency.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

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
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>

    <input type="hidden" name="reporting_user_id" value="{{ auth()->id() }}">

    <div class="mb-3">
        <label for="reporting_phone" class="form-label">Reporting Phone</label>
        <input type="text" class="form-control" id="reporting_phone" name="reporting_phone" required>
    </div>

    <div class="mb-3">
        <label for="room_occupancy" class="form-label">Room Occupancy</label>
        <input type="number" class="form-control" id="room_occupancy" name="room_occupancy" required>
    </div>

    <div class="mb-3">
        <label for="school_occupancy" class="form-label">School Occupancy</label>
        <input type="number" class="form-control" id="school_occupancy" name="school_occupancy" required>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

