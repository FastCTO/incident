@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Report Emergency</h1>

    <!-- Emergency Button at the Top -->
    <div class="text-center mb-4">
        <button type="submit" form="emergencyForm" style="border: none; background: none; padding: 0;">
            <img src="{{ asset('images/911-button.webp') }}" alt="Submit Emergency" style="width: 250px; max-width: 90%; height: auto;" />
        </button>
    </div>

    <!-- School Details -->
    <div class="mb-3">
        <strong>Name:</strong> {{ $school->name ?? 'N/A' }}<br>
        <strong>Address:</strong> {{ $school->address ?? 'N/A' }}<br>
        <strong>Total Floors:</strong> {{ $school->floors ?? 'N/A' }}<br>
        <strong>Current Building Occupancy:</strong> {{ $buildingOccupancy ?? 0 }}<br>
        <strong>Rooms Occupied:</strong> {{ $roomsOccupied ?? 0 }}<br>
        <strong>User's Cell:</strong> {{ auth()->user()->cell_number ?? 'N/A' }}<br>
        <strong>User Name:</strong> {{ auth()->user()->name ?? 'N/A' }}
    </div>

    <!-- Emergency Report Form -->
    <form id="emergencyForm" action="{{ route('emergency.store') }}" method="POST">
        @csrf

        <!-- Hidden context data -->
        <input type="hidden" name="reporting_phone" value="{{ auth()->user()->cell_number ?? 'Unknown' }}">
        <input type="hidden" name="reporting_user" value="{{ auth()->user()->name ?? 'Anonymous' }}">
        <input type="hidden" name="room_occupancy" value="{{ $roomOccupancy ?? 0 }}">
        <input type="hidden" name="school_name" value="{{ $school->name ?? 'N/A' }}">
        <input type="hidden" name="school_address" value="{{ $school->address ?? 'N/A' }}">
        <input type="hidden" name="school_occupancy" value="{{ $buildingOccupancy ?? 0 }}">
        <input type="hidden" name="rooms_occupied" value="{{ $roomsOccupied ?? 0 }}">

        <div class="mb-3">
            <label for="emergency_type" class="form-label">Emergency Type</label>
            <select id="emergency_type" name="emergency_type" class="form-control" required>
                <option value="Active Shooter" selected>Active Shooter</option>
                <option value="Tornado">Tornado</option>
                <option value="Medical">Medical</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Optional Notes</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Add any important details (optional)"></textarea>
        </div>

        <!-- Fallback Submit Button -->
        <div class="text-center">
            <button type="submit" class="btn btn-danger btn-lg mt-3">Submit Emergency</button>
        </div>
    </form>
</div>
@endsection

