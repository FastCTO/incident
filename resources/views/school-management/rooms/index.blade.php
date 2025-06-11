@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">School Rooms Dashboard</h1>

    {{-- Dashboard Summary Cards --}}
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-2 fs-1">🏫</div>
                    <h5 class="card-title">Total Rooms</h5>
                    <p class="card-text fs-4">{{ $totalRooms }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-2 fs-1">👥</div>
                    <h5 class="card-title">Registered Users</h5>
                    <p class="card-text fs-4">{{ $userCounts->sum() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-2 fs-1">🏃</div>
                    <h5 class="card-title">Occupied Rooms</h5>
                    <p class="card-text fs-4">{{ $totalOccupiedRooms }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-2 fs-1">🛡️</div>
                    <h5 class="card-title">Safety Status</h5>
                    <p class="card-text fs-5">{{ $schoolStatus }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- User Role Breakdown --}}
    <div class="mb-5">
        <h4>User Roles</h4>
        <ul>
            @foreach ($userCounts as $role => $count)
                <li><strong>{{ ucfirst($role) }}</strong>: {{ $count }}</li>
            @endforeach
        </ul>
    </div>

    {{-- Success Message --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- Room Table --}}
    <h4>Room List</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Room</th>
                    <th>Status</th>
                    <th>Occupancy</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        <form method="POST" action="{{ route('rooms.update', $room->id) }}">
                            @csrf
                            @method('PUT')

                            <td>{{ $room->room_number }}</td>

                            <td>
                                <select name="status" class="form-select form-select-sm">
                                    @php
                                        $statuses = ['sheltered in place', 'need medical help', 'evac-safe', 'empty', 'Tornado Shelter', 'Evacuate Outside', 'All Safe'];
                                    @endphp
                                    @foreach ($statuses as $statusOption)
                                        <option value="{{ $statusOption }}" @selected($room->room_status === $statusOption)>
                                            {{ $statusOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="current_occupancy" class="form-control form-control-sm"
                                       value="{{ $room->current_occupancy }}" min="0" max="30">
                            </td>

                            <td class="d-flex gap-2">
                                <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-sm btn-secondary">View</a>
                                <button type="submit" class="btn btn-sm btn-success">✓</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

