<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $school = SchoolInfo::first(); // ✅ fetch school row

        $schoolStatus = $school?->status ?? 'Unknown';
        Log::info("🧪 School status on Room Dashboard: " . ($school?->status ?? 'NULL'));

        $userCounts = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('school-management.rooms.index', [
            'rooms' => $rooms,
            'school' => $school,
            'userCounts' => $userCounts,
            'schoolStatus' => $schoolStatus,
            'totalRooms' => $rooms->count(),
            'totalOccupiedRooms' => $rooms->where('current_occupancy', '>', 0)->count(),
            'totalPeople' => $rooms->sum('current_occupancy'),
        ]);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        $roomLeaders = User::whereNotNull('home_room')
            ->where('home_room', $room->room_number)
            ->get();

        return view('rooms.show', compact('room', 'roomLeaders'));
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);

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

        $request->validate([
            'current_occupancy' => 'required|integer|min:0|max:30',
            'status' => 'required|in:sheltered in place,need medical help,evac-safe,empty,Tornado Shelter,Evacuate Outside,All Safe',
        ]);

        $room = Room::find($id);

        if (!$room) {
            Log::error('Room not found: ' . $id);
            return redirect()->back()->withErrors('Room not found.');
        }

        Log::info('Room before update: ', $room->toArray());

        $room->current_occupancy = $request->input('current_occupancy');
        $room->room_status = $request->input('status');
        $room->save();

        Log::info('Room after update: ', $room->toArray());

        return redirect()->route('rooms.show', $id)->with('status', 'Room details updated successfully.');
    }

    public function maps()
    {
        $rooms = Room::all();
        $totalOccupancy = $rooms->sum('current_occupancy');

        return view('maps.index', compact('rooms', 'totalOccupancy'));
    }

    public function removeTeacher($roomId, $teacherId)
    {
        $room = Room::findOrFail($roomId);
        $teacher = User::findOrFail($teacherId);

        if ($teacher->home_room === $room->room_number) {
            $teacher->home_room = null;
            $teacher->save();
        }

        return redirect()->route('school.management')->with('status', 'Teacher removed from room successfully.');
    }

    public function create()
    {
        return view('rooms.create'); // stub
    }
}

