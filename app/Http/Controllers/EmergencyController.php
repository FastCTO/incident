<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    // Method to display the emergency reporting form
    public function report()
    {
        return view('emergency.report');  // Make sure this view exists
    }

    // Store the emergency report (optional for submission)
    public function store(Request $request)
    {
	     $request->validate([
            'description' => 'required|string',
        ]);

        // Handle the submission logic here
        return redirect()->route('home')->with('status', 'Emergency reported successfully!');
    }
}

