@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room: {{ $room->room_number }}</h1>

    <h3>Status: {{ ucfirst($room->status) }}</h3>
    <h3>Current Occupancy: {{ $room->current_occupancy }}</h3>
    <h3>Last Updated: {{ $room->updated_at->format('Y-m-d H:i:s') }}</h3>

    <h3>Room Leaders</h3>
    @if ($roomLeaders->isEmpty())
        <p>No room leaders assigned yet.</p>
    @else
        <ul>
            @foreach ($roomLeaders as $leader)
                <li>{{ $leader->name }} ({{ $leader->email }})</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary">Edit Room Details</a>
</div>
@endsection

