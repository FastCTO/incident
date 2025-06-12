<?php

namespace App\Http\Controllers;

class IndoorMapController extends Controller
{
    public function index()
    {
        \Log::info("🛠 IndoorMapController triggered.");

        // Fetch all rooms with people inside
        $rooms = \DB::table('rooms')
            ->where('current_occupancy', '>', 0)
            ->select('id', 'room_number', 'room_full_name', 'current_occupancy', 'room_status')
            ->orderBy('room_number', 'asc')
            ->get();

        // Recalculate totals
        $totalPeople = $rooms->sum('current_occupancy');
        $occupiedRooms = $rooms->count();

        \Log::info("🔢 Occupied Rooms: $occupiedRooms, Total People: $totalPeople");

        return view('maps.indoor', compact('rooms', 'totalPeople', 'occupiedRooms'));
    }
}

