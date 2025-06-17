@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Summary Cards Grid -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fs-1">🏫</div>
                    <h5>Total Rooms</h5>
                    <p class="fs-4">{{ $totalRooms }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fs-1">🧑‍🤝‍🧑</div>
                    <h5>Registered Users</h5>
                    <p class="fs-4">{{ $userCounts->sum() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fs-1">🏃‍♂️</div>
                    <h5>Occupied Rooms</h5>
                    <p class="fs-4">{{ $totalOccupiedRooms }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fs-1">🛡️</div>
                    <h5>Safety Status</h5>
                    <p class="fs-4">{{ $schoolStatus }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Cards -->
    <div class="row">
        @foreach ($rooms as $room)
            <div class="col-md-3 mb-4">
                <div class="card bg-warning-subtle shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Room {{ $room->room_number }}</h5>

                        <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Occupancy Input -->
                            <div class="mb-2">
                                <label for="current_occupancy_{{ $room->id }}" class="form-label">👥 People</label>
                                <input type="number" name="current_occupancy" id="current_occupancy_{{ $room->id }}"
                                       class="form-control form-control-sm text-center"
                                       min="0" max="30" value="{{ $room->current_occupancy }}">
                            </div>

                            <!-- Status Dropdown -->
                            <div class="mb-2">
                                <label for="status_{{ $room->id }}" class="form-label">📍 Status</label>
                                <select name="status" id="status_{{ $room->id }}" class="form-select form-select-sm">
                                    @php
                                        $options = [
                                            'sheltered in place',
                                            'need medical help',
                                            'evac-safe',
                                            'empty',
                                            'Tornado Shelter',
                                            'Evacuate Outside',
                                            'All Safe',
                                        ];
                                    @endphp
                                    @foreach ($options as $option)
                                        <option value="{{ $option }}" {{ $room->room_status === $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Save Button -->
                            <button type="submit" class="btn btn-success btn-sm w-100">💾 Save</button>
                        </form>

                        <!-- Optional View Button -->
                        <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-outline-primary btn-sm mt-2 w-100">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

