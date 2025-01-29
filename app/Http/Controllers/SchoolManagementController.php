<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolInfo;
use App\Models\ChatMessage; // Import ChatMessage model
use Illuminate\Support\Facades\Log;

class SchoolManagementController extends Controller
{
    /**
     * Display the School Management Dashboard.
     */
    public function index()
    {
        $school = SchoolInfo::first(); // Assuming a single school record exists
        $rooms = \App\Models\Room::with('users')->get(); // Adjust model namespace if needed

        return view('school-management.index', [
            'school' => $school,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Update the school security status.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:Normal,Emergency,Active Shooter,Lockdown',
        ]);

        $school = SchoolInfo::first();
        if ($school) {
            $school->status = $validated['status'];
            $school->save();
            Log::info('School status updated to: ' . $validated['status']);
        }

        return redirect()->route('school.management')->with('status', 'Security status updated successfully.');
    }

    /**
     * Clear all messages from Emergency Chat.
     */
    public function clearEmergencyChat()
    {
        ChatMessage::truncate(); // Delete all chat messages
        Log::info('Emergency chat has been cleared.');
        
        return redirect()->route('school.management')->with('status', 'Emergency chat cleared successfully.');
    }
}

