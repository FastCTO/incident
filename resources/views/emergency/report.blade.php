@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Report Emergency</h1>

    <form id="emergencyForm" action="{{ route('emergency.store') }}" method="POST">
        @csrf

        {{-- 🔒 Hidden Metadata --}}
        <input type="hidden" name="reporting_phone" value="{{ auth()->user()->cell_number ?? 'Unknown' }}">
        <input type="hidden" name="reporting_user" value="{{ auth()->user()->name ?? 'Anonymous' }}">
        <input type="hidden" name="room_occupancy" value="{{ $roomOccupancy ?? 0 }}">
        <input type="hidden" name="school_name" value="{{ $school->name ?? 'N/A' }}">
        <input type="hidden" name="school_address" value="{{ $school->address ?? 'N/A' }}">
        <input type="hidden" name="school_occupancy" value="{{ $buildingOccupancy ?? 0 }}">
        <input type="hidden" name="rooms_occupied" value="{{ $roomsOccupied ?? 0 }}">

        {{-- 📐 Grid Row: 1/3 + 1/3 + 1/3 --}}
        <div class="row g-4 align-items-center mb-4 text-center text-md-start">

            {{-- Left: School Info --}}
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">School & User Info</h5>
                        <ul class="list-unstyled">
                            <li><strong>School:</strong> {{ $school->name ?? 'N/A' }}</li>
                            <li><strong>Address:</strong> {{ $school->address ?? 'N/A' }}</li>
                            <li><strong>Floors:</strong> {{ $school->floors ?? 'N/A' }}</li>
                            <li><strong>Building Occupancy:</strong> {{ $buildingOccupancy ?? 0 }}</li>
                            <li><strong>Rooms Occupied:</strong> {{ $roomsOccupied ?? 0 }}</li>
                            <li><strong>User Cell:</strong> {{ auth()->user()->cell_number ?? 'N/A' }}</li>
                            <li><strong>User Name:</strong> {{ auth()->user()->name ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Center: 911 Button (Clickable Submit) --}}
            <div class="col-md-4 text-center">
                <button type="submit" style="border: none; background: none; padding: 0;">
                    <img src="{{ asset('images/911-button.webp') }}" alt="Submit Emergency" style="width: 180px;" />
                </button>
                <p class="text-muted mt-2">Click the 911 button to send the emergency report</p>
            </div>

            {{-- Right: Emergency Form --}}
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Emergency Details</h5>

                        {{-- Emergency Type --}}
                        <div class="form-check mb-2 text-start">
                            <input class="form-check-input" type="radio" name="emergency_type" id="typeShooter" value="Active Shooter" checked>
                            <label class="form-check-label" for="typeShooter">Active Shooter</label>
                        </div>
                        <div class="form-check mb-2 text-start">
                            <input class="form-check-input" type="radio" name="emergency_type" id="typeTornado" value="Tornado">
                            <label class="form-check-label" for="typeTornado">Tornado</label>
                        </div>
                        <div class="form-check mb-2 text-start">
                            <input class="form-check-input" type="radio" name="emergency_type" id="typeMedical" value="Medical">
                            <label class="form-check-label" for="typeMedical">Medical</label>
                        </div>
                        <div class="form-check mb-3 text-start">
                            <input class="form-check-input" type="radio" name="emergency_type" id="typeOther" value="Other">
                            <label class="form-check-label" for="typeOther">Other</label>
                        </div>

                        {{-- Optional Notes --}}
                        <label for="description" class="form-label">Additional Notes</label>
                        <textarea id="description" name="description" rows="3" class="form-control" placeholder="Optional comments for first responders..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

