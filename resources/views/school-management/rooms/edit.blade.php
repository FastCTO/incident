@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Room</h1>

    <form method="POST" action="{{ route('rooms.updateOccupancy', $room->id) }}"> <!-- Updated action -->
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="current_occupancy" class="form-label">Current Occupancy</label>
            <input type="number" class="form-control" name="current_occupancy" value="{{ $room->current_occupancy }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" required>
                <option value="sheltered in place" {{ $room->status == 'sheltered in place' ? 'selected' : '' }}>Sheltered in Place</option>
                <option value="need medical help" {{ $room->status == 'need medical help' ? 'selected' : '' }}>Need Medical Help</option>
                <option value="evac-safe" {{ $room->status == 'evac-safe' ? 'selected' : '' }}>Evac-Safe</option>
                <option value="empty" {{ $room->status == 'empty' ? 'selected' : '' }}>Empty</option>
                <option value="Tornado Shelter" {{ $room->status == 'Tornado Shelter' ? 'selected' : '' }}>Tornado Shelter</option>
                <option value="Evacuate Outside" {{ $room->status == 'Evacuate Outside' ? 'selected' : '' }}>Evacuate Outside</option>
                <option value="All Safe" {{ $room->status == 'All Safe' ? 'selected' : '' }}>All Safe</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
</div>
@endsection

