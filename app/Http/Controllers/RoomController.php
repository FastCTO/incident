<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetch all rooms
        return view('rooms.index', compact('rooms'));
    }

    public function show($id)
    {
        $room = Room::findOrFail($id); // Fetch the room by ID
        $roomLeaders = User::where('room_leader', true)
            ->where('home_room', $room->room_number)
            ->get();

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

        Log::info('Room updated: ' . $room->id);

        return redirect()->route('rooms.index')->with('status', 'Room updated successfully.');
    }

    public function maps()
    {
        $rooms = Room::all(); // Fetch all rooms
        $totalOccupancy = $rooms->sum('current_occupancy'); // Calculate total occupancy

        return view('maps.index', compact('rooms', 'totalOccupancy'));
    }
}

