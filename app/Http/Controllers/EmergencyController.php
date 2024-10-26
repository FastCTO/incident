<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use App\Models\Emergency;
use App\Models\SchoolInfo;
use SinchSMS;

class EmergencyController extends Controller  // Change from BaseController to Controller
{
    public function report()
    {
        $school = SchoolInfo::first();
        $rooms = Room::all();
        $roomLeaders = User::where('room_leader', true)->get();

        return view('emergency.report', compact('school', 'rooms', 'roomLeaders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'emergency_type' => 'required',
            'description' => 'required|string|max:255',
            'reporting_phone' => 'required|string',
            'reporting_user_id' => 'required|exists:users,id',
            'room_occupancy' => 'required|integer',
            'school_occupancy' => 'required|integer',
        ]);

        Emergency::create($validated);

        return redirect()->route('home')->with('status', 'Emergency reported successfully!');
    }
}

