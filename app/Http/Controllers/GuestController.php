<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'working_room' => 'required|string',
        ]);

        DB::table('guests')->insert([
            'guest_name' => $validated['guest_name'],
            'substitute' => 1,
            'freq_visitor' => 1,
            'mobile' => $validated['mobile'],
            'working_room' => $validated['working_room'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Substitute added successfully.');
    }
}

