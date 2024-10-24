@extends('layouts.app')

@section('content')
<div class="container">
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room Details</h1>
    <p><strong>Room Number:</strong> {{ $room->room_number }}</p>
    <p><strong>Status:</strong> {{ $room->status }}</p>
</div>
@endsection

