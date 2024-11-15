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
        return view('rooms.index', compact('rooms'));
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        $roomLeaders = User::where('room_leader', true)
                            ->where('current_room', $room->room_number)
                            ->get();

        return view('rooms.show', compact('room', 'roomLeaders'));
    }

    public function updateOccupancy(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $room->current_occupancy = $request->input('current_occupancy');
        $room->save();

        return redirect()->route('rooms.show', $id)->with('success', 'Occupancy updated successfully.');
    }
}

