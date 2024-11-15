@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room: {{ $room->room_number }}</h1>

    <!-- Room Leaders Section -->
    <h3>Room Leaders</h3>
    @if($roomLeaders->isEmpty())
        <p>No room leaders assigned yet.</p>
    @else
        <ul>
            @foreach($roomLeaders as $leader)
                <li>{{ $leader->name }} ({{ $leader->email }})</li>
            @endforeach
        </ul>
    @endif

    <!-- Current Occupancy Section -->
    <h3>Current Occupancy</h3>
    <form action="{{ route('rooms.updateOccupancy', $room->id) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="current_occupancy" class="form-control mb-3">
            @for ($i = 0; $i <= 30; $i++)
                <option value="{{ $i }}" {{ $i == $room->current_occupancy ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        <button type="submit" class="btn btn-primary">Update Occupancy</button>
    </form>
</div>
@endsection

