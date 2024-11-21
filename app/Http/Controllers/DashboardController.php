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

