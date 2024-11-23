@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rooms</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Status</th>
                <th>Occupancy</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rooms as $room)
                <tr>
                    <td>{{ $room->room_number }}</td>
                    <td>{{ $room->status }}</td>
                    <td>{{ $room->current_occupancy }}</td>
                    <td>
                        <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

