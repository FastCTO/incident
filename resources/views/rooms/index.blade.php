@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rooms Dashboard</h1>
    <div class="row">
        @foreach ($rooms as $room)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        Room: {{ $room->room_number }}
                    </div>
                    <div class="card-body">
                        <p><strong>Occupancy:</strong> {{ $room->current_occupancy }} people</p>
                        <p><a href="{{ route('rooms.show', $room->id) }}" class="btn btn-primary">View Details</a></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection


