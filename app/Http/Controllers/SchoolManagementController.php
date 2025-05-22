<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolInfo;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class SchoolManagementController extends Controller
{
    /**
     * Display the School Management Dashboard.
     */
    public function index()
    {
        $school = SchoolInfo::first();
        $rooms = \App\Models\Room::with('users')->get();

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
        ChatMessage::truncate();
        Log::info('Emergency chat has been cleared.');
        
        return redirect()->route('school.management')->with('status', 'Emergency chat cleared successfully.');
    }

    /**
     * Simulate a database outage for 2 minutes.
     */
	public function simulateDbOutage()
{
    $currentUser = trim(shell_exec('whoami'));
    Log::info("Laravel is executing as user: $currentUser");

    Log::info('Simulating database outage... Running Python script.');

    $scriptPath = '/home/fastcto/scripts/simulate_mysql_outage.py';

    // Run script as fastcto user
    $output = shell_exec("sudo -u fastcto /usr/bin/python3 $scriptPath 2>&1");

    Log::info("Script Output: " . ($output ?: 'No output'));

    return redirect()->route('school.management')->with('status', 'Database outage simulated.');
}


}

