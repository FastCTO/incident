@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Summary Card -->
    <div class="card shadow-sm text-center mb-4">
        <div class="card-body">
            <h2>Indoor Map - New Hope Academy</h2>
            <p><strong>Rooms Occupied:</strong> {{ $rooms->where('current_occupancy', '>', 0)->count() }} |
               <strong>Total People Inside:</strong> {{ $rooms->sum('current_occupancy') }}</p>
            <a href="{{ route('maps.index') }}" class="btn btn-dark mt-2">⬅️ Back to Outdoor Map</a>
        </div>
    </div>

    <!-- Dynamic Room Cards -->
    <div class="row">
        @foreach ($rooms->where('current_occupancy', '>', 0) as $room)
            <div class="col-md-3 mb-4">
                <a href="{{ route('rooms.show', $room->id) }}" class="text-decoration-none">
                    <div class="card bg-warning-subtle shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Room {{ $room->room_number }}</h5>
                            <p class="card-text text-dark">👥 {{ $room->current_occupancy }} people</p>
                            <p class="card-text text-muted">📍 Status: {{ $room->room_status ?? 'Unknown' }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

