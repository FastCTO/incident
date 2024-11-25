<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $totalRooms = $rooms->count();
        $registeredUsers = User::all()->groupBy('role')->map->count();
        $totalOccupiedRooms = $rooms->where('current_occupancy', '>', 0)->count();
        $totalPeople = $rooms->sum('current_occupancy');
        $schoolStatus = config('school.safety_status', 'Safe');

        return view('rooms.index', compact('rooms', 'totalRooms', 'registeredUsers', 'totalOccupiedRooms', 'totalPeople', 'schoolStatus'));
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        $roomLeaders = User::where('room_leader', true)->where('home_room', $room->room_number)->get();

        return view('rooms.show', compact('room', 'roomLeaders'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'current_occupancy' => 'required|integer|min:0|max:30',
            'status' => 'required|in:sheltered in place,need medical help,evac-safe,empty,Tornado Shelter,Evacuate Outside,All Safe',
        ]);

        $room = Room::findOrFail($id);
        $room->update([
            'current_occupancy' => $request->input('current_occupancy'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('rooms.index')->with('status', 'Room updated successfully.');
    }

    public function maps()
    {
        $rooms = Room::all();
        $totalOccupancy = $rooms->sum('current_occupancy');

        return view('maps.index', compact('rooms', 'totalOccupancy'));
    }
}

