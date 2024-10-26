@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rooms</h1>
    <ul>
        @foreach ($rooms as $room)
            <li><a href="{{ route('rooms.show', $room->id) }}">{{ $room->room_number }}</a></li>
        @endforeach
    </ul>
</div>
@endsection

