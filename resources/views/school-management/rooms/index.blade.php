{{-- resources/views/school-management/rooms/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>School Rooms Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <p><strong>Total Rooms:</strong> {{ $totalRooms }}</p>
            <p><strong>Total Registered Users:</strong></p>
            <ul>
                @foreach ($userCounts as $role => $count)
                    <li>{{ ucfirst($role) }}: {{ $count }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <p><strong>Total Occupied Rooms:</strong> {{ $totalOccupiedRooms }}</p>
            <p><strong>Total People in Building:</strong> {{ $totalPeople }}</p>
            <p><strong>Current Safety Status:</strong> {{ $schoolStatus }}</p>
        </div>
    </div>

    <h2>Room List</h2>
    <table class="table table-bordered">
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
                    <td>{{ $room->room_status }}</td>
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

