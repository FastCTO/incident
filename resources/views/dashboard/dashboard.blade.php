fastcto@beefcake:/var/www/html/dev.fsv.io$ cat routes/web.php | grep DashboardController
use App\Http\Controllers\DashboardController;
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
fastcto@beefcake:/var/www/html/dev.fsv.io$ cat app/Http/Controllers/DashboardController.php
<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\SchoolInfo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get logged-in user
        $school = SchoolInfo::first(); // Get school information

        $roomsOccupied = Room::where('current_occupancy', '>', 0)->count(); // Count occupied rooms
        $peopleInBuilding = Room::sum('current_occupancy'); // Total people in building
        $emergencyStatus = $school->status ?? 'Normal'; // Emergency status with default
        $userRoom = $user->home_room ? Room::where('room_number', $user->home_room)->first() : null;

        // Pass all required data to the view
        return view('dashboard.index', compact(
            'school', 'roomsOccupied', 'peopleInBuilding', 
            'emergencyStatus', 'userRoom'
        ));
    }
}

fastcto@beefcake:/var/www/html/dev.fsv.io$ find resources/views -name '*dashboard*.blade.php'
resources/views/dashboard/dashboard.blade.php
fastcto@beefcake:/var/www/html/dev.fsv.io$ cat resources/views/dashboard/dashboard.blade.php 
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <img src="{{ asset('images/school.jpg') }}" alt="School" style="width: 100%; max-width: 500px;">
        <h1>{{ $school->name }}</h1>
        <p>{{ $school->address }}</p>
    </div>

    <div class="row text-center">
        <div class="col-md-4">
            <h3>{{ $occupiedRooms }}</h3>
            <p>Occupied Rooms</p>
        </div>
        <div class="col-md-4">
            <h3>{{ $totalOccupancy }}</h3>
            <p>Total People</p>
        </div>
        <div class="col-md-4">
            <h3>{{ $emergencyStatus == 'emergency' ? 'Emergency' : 'Normal' }}</h3>
            <p>Status</p>
        </div>
    </div>

    <div class="mt-4 text-center">
        @if ($isRoomLeader)
            <a href="{{ route('rooms.show', ['id' => $userRoom]) }}" class="btn btn-primary">Go to My Room</a>
        @endif
        <a href="{{ route('emergency.report') }}" class="btn btn-danger">Report Emergency</a>
    </div>
</div>
@endsection

fastcto@beefcake:/var/www/html/dev.fsv.io$ 

