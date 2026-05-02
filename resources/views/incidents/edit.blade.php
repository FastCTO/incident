@extends('layouts.app')

@section('title', 'Edit Incident - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Incident</h1>
                <p>Update incident record #{{ $incident->id }}.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('incidents.update', $incident) }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="title">Incident Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $incident->title) }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="field">
                    <label for="incident_type">Incident Type</label>
                    @php $selectedType = old('incident_type', $incident->incident_type); @endphp

                    <select name="incident_type" id="incident_type">
                        <option value="">Select type</option>
                        <option value="Theft" {{ $selectedType === 'Theft' ? 'selected' : '' }}>Theft</option>
                        <option value="Assault" {{ $selectedType === 'Assault' ? 'selected' : '' }}>Assault</option>
                        <option value="Vandalism" {{ $selectedType === 'Vandalism' ? 'selected' : '' }}>Vandalism</option>
                        <option value="Fire" {{ $selectedType === 'Fire' ? 'selected' : '' }}>Fire</option>
                        <option value="Slip and Fall" {{ $selectedType === 'Slip and Fall' ? 'selected' : '' }}>Slip and Fall</option>
                        <option value="Emergency Response" {{ $selectedType === 'Emergency Response' ? 'selected' : '' }}>Emergency Response</option>
                        <option value="Other" {{ $selectedType === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    @php $selectedStatus = old('status', $incident->status); @endphp

                    <select name="status" id="status" required>
                        <option value="open" {{ $selectedStatus === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="reviewing" {{ $selectedStatus === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="evidence_requested" {{ $selectedStatus === 'evidence_requested' ? 'selected' : '' }}>Evidence Requested</option>
                        <option value="closed" {{ $selectedStatus === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="location_name">Location Name</label>
                    <input type="text" name="location_name" id="location_name" value="{{ old('location_name', $incident->location_name) }}">
                </div>

                <div class="field">
                    <label for="incident_datetime">Incident Date/Time</label>
                    <input
                        type="datetime-local"
                        name="incident_datetime"
                        id="incident_datetime"
                        value="{{ old('incident_datetime', $incident->incident_datetime ? $incident->incident_datetime->format('Y-m-d\TH:i') : '') }}"
                    >
                </div>
            </div>

            <div class="field">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" value="{{ old('address', $incident->address) }}">
            </div>

            <div class="field">
                <label for="summary">Summary</label>
                <textarea name="summary" id="summary">{{ old('summary', $incident->summary) }}</textarea>
            </div>

            <div class="field">
                <label for="notes">Internal Notes</label>
                <textarea name="notes" id="notes">{{ old('notes', $incident->notes) }}</textarea>
            </div>

            <button type="submit" class="btn">Update Incident</button>
            <a href="{{ route('incidents.show', $incident) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
