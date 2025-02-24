<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Emergency;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmergencyController extends Controller
{
    public function report()
    {
        $school = SchoolInfo::first();
        $rooms = Room::all();
	$roomLeaders = User::whereNotNull('home_room')->get();


        // Calculate building occupancy
	// Calculate building occupancy without guests table
	$buildingOccupancy = Room::sum('current_occupancy');

        // Get the logged-in user's home room
        $roomId = Auth::user()->home_room;

        // Fetch the specific room's occupancy
        $room = Room::where('room_number', $roomId)->first();
        $roomOccupancy = $room ? $room->current_occupancy : 0;

        // Reporting room
        $reportingRoom = $roomId;

        // Calculate occupied rooms count
        $roomsOccupied = Room::where('current_occupancy', '>', 0)->count();

        $userMobile = Auth::user()->mobile;

        return view('emergency.report', compact(
            'school', 'rooms', 'roomLeaders',
            'roomOccupancy', 'buildingOccupancy',
            'roomsOccupied', 'reportingRoom', 'userMobile'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'emergency_type' => 'required',
            'description' => 'required|string|max:255',
            'reporting_phone' => 'required|string',
            'room_occupancy' => 'required|integer',
        ]);

        Emergency::create(array_merge($validated, [
            'reporting_user_id' => Auth::id(),
            'school_occupancy' => Room::sum('current_occupancy'),
        ]));

        // Update room occupancy if changed
        $roomId = Auth::user()->home_room;
        $room = Room::where('room_number', $roomId)->first();

        if ($room) {
            $room->current_occupancy = $validated['room_occupancy'];
            $room->updated_at = now();
            $room->save();
        }

        // Send SMS alert
        $this->sendEmergencySMS(
            $validated['reporting_phone'],
            $validated['emergency_type'],
            $validated['description']
        );

        return redirect()->route('home')->with('status', 'Emergency reported successfully!');
    }

    protected function sendEmergencySMS($cellNumber, $emergencyType, $description)
    {
        try {
            if (!str_starts_with($cellNumber, '1')) {
                $cellNumber = '1' . $cellNumber;
            }

            $message = "Emergency Alert: $emergencyType. Description: $description.";
            $bearerToken = '0b75f8d79d7d40cb9f7003e5498c29f0'; // Replace with valid API key
            $apiUrl = 'https://sms.api.sinch.com/xms/v1/0986c99adc6346249028b5a5d5543331/batches';

            $payload = [
                'from' => '19312230233',  // Sender number
                'to' => [$cellNumber],
                'body' => $message,
            ];

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $bearerToken,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            Log::info("SMS API Response: HTTP $httpCode - $response");

            if (curl_errno($ch)) {
                Log::error('cURL error: ' . curl_error($ch));
                throw new \Exception('cURL error: ' . curl_error($ch));
            }

            if ($httpCode >= 400) {
                Log::error('Failed to send SMS: ' . $response);
                throw new \Exception('Failed to send SMS: ' . $response);
            }

            curl_close($ch);
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
        }
    }

    public function sendEmergencyText(Request $request)
    {
        // Example of sending a text to all team members:
        /*
        $teamMembers = User::where('role', 'team')->get();
        foreach ($teamMembers as $member) {
            // Implement your SMS logic here
            SmsService::send($member->phone, "Emergency text: Please take action.");
        }
        */

        return redirect()->route('school.management')->with('status', 'Emergency text sent successfully.');
    }
}

