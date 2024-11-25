@extends('layouts.app')

@section('content')
<div class="container">
    <h1>School Rooms Dashboard</h1>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Total Rooms: {{ $totalRooms }}</h3>
            <h3>Total Registered Users:</h3>
            <ul>
                @foreach ($registeredUsers as $role => $count)
                    <li>{{ ucfirst($role) }}: {{ $count }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Total Occupied Rooms: {{ $totalOccupiedRooms }}</h3>
            <h3>Total People in Building: {{ $totalPeople }}</h3>
            <h3>Current Safety Status: {{ $schoolStatus }}</h3>
        </div>
    </div>

    <h2>Room List</h2>
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

