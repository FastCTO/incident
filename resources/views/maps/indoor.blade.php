@extends('layouts.app')

@section('content')

<div class="container text-center">

    <!-- Header Information -->
    <h1>Indoor Map - New Hope Academy</h1>
    <p><strong>Rooms Occupied:</strong> {{ $occupiedRooms ?? 0 }} | <strong>Total People Inside:</strong> {{ $totalPeople ?? 0 }}</p>

    <a href="{{ route('maps.index') }}" class="btn btn-dark">Back to Outdoor Map</a>

    <!-- Indoor Map with Clickable Rooms -->
    <div class="mt-3" style="position: relative; display: inline-block;">
        <img src="{{ asset('images/map-indoor-corrected.png') }}" alt="Indoor Map" class="img-fluid">

        @if(isset($rooms) && $rooms->count() > 0)
            @foreach ($rooms as $index => $room)
                <a href="{{ url('/rooms/' . $room->id) }}"
                   style="position: absolute;
                          left: {{ 950 + ($index % 2) * 100 }}px;
                          top: {{ 320 + (intdiv($index, 2) * 80) }}px;
                          width: 50px;
                          height: 50px;
                          background: rgba(255, 255, 0, 0.5);
                          text-align: center;
                          line-height: 50px;
                          font-weight: bold;
                          color: black;
                          text-decoration: none;">
                    {{ $room->current_occupancy ?? 0 }}
                </a>
            @endforeach
        @else
            <p class="text-danger">🚨 No room data available! Please check the database.</p>
        @endif
    </div>

</div>

@endsection

