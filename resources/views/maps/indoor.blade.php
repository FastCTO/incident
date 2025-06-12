@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">

    {{-- 🏫 Header Card --}}
    <div class="card shadow-sm mb-4 mx-auto" style="max-width: 960px;">
        <div class="card-body">
            <h2 class="mb-2">Indoor Map - New Hope Academy</h2>
            <p><strong>Rooms Occupied:</strong> {{ $occupiedRooms ?? 0 }} | 
               <strong>Total People Inside:</strong> {{ $totalPeople ?? 0 }}</p>

            <a href="{{ route('maps.index') }}" class="btn btn-dark mt-2">
                ⬅️ Back to Outdoor Map
            </a>
        </div>
    </div>

    {{-- 🗺️ Indoor Map with Overlay --}}
    <div class="card shadow-sm mx-auto" style="max-width: 1000px;">
        <div class="card-body p-0 position-relative" style="overflow: hidden;">
            <img src="{{ asset('images/map-indoor-corrected.png') }}" alt="Indoor Map" class="img-fluid w-100" style="display: block;">

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
                              text-decoration: none;
                              border-radius: 5px;">
                        {{ $room->current_occupancy ?? 0 }}
                    </a>
                @endforeach
            @else
                <div class="alert alert-danger m-3">
                    🚨 No room data available! Please check the database.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

