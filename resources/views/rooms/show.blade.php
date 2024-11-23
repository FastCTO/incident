@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room: {{ $room->room_number }}</h1>
    <h3>Room Leaders</h3>
    @if ($roomLeaders->isEmpty())
        <p>No room leaders assigned yet.</p>
    @else
        <ul>
            @foreach ($roomLeaders as $leader)
                <li>{{ $leader->name }} ({{ $leader->email }})</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection

