@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Rooms</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Current Occupancy</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            <tr>
                <td>{{ $room->room_number }}</td>
                <td>{{ $room->current_occupancy }}</td>
                <td>{{ $room->status }}</td>
                <td>
                    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

