<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolInfo;
use Illuminate\Support\Facades\Log;

class SchoolManagementController extends Controller
{
    /**
     * Display the School Management Dashboard.
     */
    public function index()
    {
        $school = SchoolInfo::first(); // Assuming a single school record exists

        return view('school-management.index', [
            'school' => $school,
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

        $school = SchoolInfo::first(); // Assuming a single school record exists
        if ($school) {
            $school->status = $validated['status'];
            $school->save();

            // Log the change
            Log::info('School status updated to: ' . $validated['status']);
        }

        return redirect()->route('school.management')->with('status', 'Security status updated successfully.');
    }
}

