@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Room: {{ $room->room_number }}</h1>

    <form method="POST" action="{{ route('rooms.update', $room->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                @foreach ($statusOptions as $status)
                    <option value="{{ $status }}" {{ $room->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="current_occupancy" class="form-label">Current Occupancy</label>
            <select class="form-select" id="current_occupancy" name="current_occupancy" required>
                @for ($i = 0; $i <= 30; $i++)
                    <option value="{{ $i }}" {{ $room->current_occupancy == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
</div>
@endsection

