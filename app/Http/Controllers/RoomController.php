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
	$roomLeaders = User::whereNotNull('home_room')->where('home_room', $room->room_number)->get();


        return view('rooms.show', compact('room', 'roomLeaders'));
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);

        // Enum options for room status
        $statusOptions = [
            'sheltered in place',
            'need medical help',
            'evac-safe',
            'empty',
            'Tornado Shelter',
            'Evacuate Outside',
            'All Safe',
        ];

        return view('rooms.edit', compact('room', 'statusOptions'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request data: ', $request->all());

        // Validate the request
        $request->validate([
            'current_occupancy' => 'required|integer|min:0|max:30',
            'status' => 'required|in:sheltered in place,need medical help,evac-safe,empty,Tornado Shelter,Evacuate Outside,All Safe',
        ]);

        $room = Room::find($id);

        if (!$room) {
            Log::error('Room not found: ' . $id);
            return redirect()->back()->withErrors('Room not found.');
        }

        // Log the room data before update
        Log::info('Room before update: ', $room->toArray());

        // Update the room
        $room->current_occupancy = $request->input('current_occupancy');
	$room->room_status = $request->input('status');
        $room->save();

        // Log the room data after update
        Log::info('Room after update: ', $room->toArray());

        return redirect()->route('rooms.show', $id)->with('status', 'Room details updated successfully.');
    }

    public function maps()
    {
        $rooms = Room::all();
        $totalOccupancy = $rooms->sum('current_occupancy');

        return view('maps.index', compact('rooms', 'totalOccupancy'));
    }

    /**
     * Remove a teacher from a room by clearing their home_room field.
     */
    public function removeTeacher($roomId, $teacherId)
    {
        $room = Room::findOrFail($roomId);
        $teacher = User::findOrFail($teacherId);

        // Only remove if the teacher is currently assigned to this room
        if ($teacher->home_room === $room->room_number) {
            $teacher->home_room = null;
            $teacher->save();
        }

        return redirect()->route('school.management')->with('status', 'Teacher removed from room successfully.');
    }
}

