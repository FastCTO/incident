<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IndoorMapController extends Controller
{
    public function index()
    {
        Log::info("🛠 IndoorMapController triggered.");

        // Fetch room occupancy data for rooms 101-104
        $rooms = DB::table('rooms')
            ->whereIn('room_number', ['101', '102', '103', '104']) // Ensure room numbers match string format in DB
            ->select('id', 'room_number', 'room_full_name', 'current_occupancy')
            ->get();

        // Check if the query returned results
        if ($rooms->isEmpty()) {
            Log::error("🚨 No rooms found! Check database or query.");
            $rooms = collect(); // Ensure rooms is at least an empty collection
        }

        // Calculate total people inside and count of occupied rooms
        $totalPeople = $rooms->sum('current_occupancy') ?? 0;
        $occupiedRooms = $rooms->where('current_occupancy', '>', 0)->count() ?? 0;

        Log::info("🔢 Occupied Rooms: $occupiedRooms, Total People: $totalPeople");

        // ✅ Ensure the view receives the correct data
        return view('maps.indoor', compact('rooms', 'totalPeople', 'occupiedRooms'));
    }
}

