<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User; // Correct import for the User model
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetch all rooms
        return view('rooms.index', compact('rooms')); // Pass rooms to the view
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);

        // Fetch room leaders
        $roomLeaders = User::where('room_leader', true)
            ->where('home_room', $room->room_number)
            ->get();

        return view('rooms.show', compact('room', 'roomLeaders'));
    }

    public function updateOccupancy(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'current_occupancy' => 'required|integer|min:0|max:30',
        ]);

        // Find the room and update the occupancy
        $room = Room::findOrFail($id);
        $room->current_occupancy = $request->input('current_occupancy');
        $room->save();

        return redirect()->route('rooms.show', $room->id)->with('success', 'Occupancy updated successfully.');
    }
}

