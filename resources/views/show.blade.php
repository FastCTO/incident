@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room Details: {{ $room->room_number }}</h1>

    <p><strong>Status:</strong> {{ $room->status }}</p>
    <p><strong>Current Occupancy:</strong> {{ $room->current_occupancy }}</p>
    <p><strong>Room Leaders:</strong></p>
    <ul>
        @foreach ($roomLeaders as $leader)
            <li>{{ $leader->name }}</li>
        @endforeach
    </ul>

    <a href="{{ route('rooms.index') }}" class="btn btn-primary">Back to Rooms</a>
</div>
@endsection

