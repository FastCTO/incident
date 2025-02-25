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
    Log::info('📌 Emergency Report Page Loaded');

    $school = SchoolInfo::first();
    $rooms = Room::all();
    $roomLeaders = User::whereNotNull('home_room')->get();

    $buildingOccupancy = Room::sum('current_occupancy');
    $roomId = Auth::user()->home_room;
    $room = Room::where('room_number', $roomId)->first();
    $roomOccupancy = $room ? $room->current_occupancy : 0;
    $roomsOccupied = Room::where('current_occupancy', '>', 0)->count();
    
    // ✅ Ensure this variable name matches the Blade template (`userMobile`)
    $userMobile = Auth::user()->cell_number ?? 'Unknown';  

    return view('emergency.report', compact(
        'school', 'rooms', 'roomLeaders',
        'roomOccupancy', 'buildingOccupancy',
        'roomsOccupied', 'userMobile'  // ✅ Pass as `userMobile`
    ));
}

    public function store(Request $request)
    {
        Log::info('🚨 Emergency Report Submission Started', ['data' => $request->all()]);

        try {
            $validated = $request->validate([
                'emergency_type' => 'required|string',
                'description' => 'required|string|max:255',
                'reporting_phone' => 'required|string',
                'room_occupancy' => 'required|integer',
            ]);

            Log::info('✔ Validation Passed', ['validated' => $validated]);

            // Store emergency in DB
            $emergency = Emergency::create([
                'emergency_type' => $validated['emergency_type'],
                'description' => $validated['description'],
                'reporting_phone' => $validated['reporting_phone'],
                'reporting_user_id' => Auth::id(),
                'room_occupancy' => $validated['room_occupancy'],
                'school_occupancy' => Room::sum('current_occupancy'),
            ]);

            Log::info('✅ Emergency Stored in DB', ['emergency_id' => $emergency->id]);

            // Update room occupancy
            $roomId = Auth::user()->home_room;
            $room = Room::where('room_number', $roomId)->first();

            if ($room) {
                $room->current_occupancy = $validated['room_occupancy'];
                $room->updated_at = now();
                $room->save();
                Log::info('🏠 Room Occupancy Updated', [
                    'room_id' => $roomId,
                    'occupancy' => $validated['room_occupancy']
                ]);
            } else {
                Log::warning('⚠ Room Not Found, Occupancy Not Updated', ['room_id' => $roomId]);
            }

            // Send SMS alert
            Log::info('📲 Preparing to Send Emergency SMS');
            $smsSent = $this->sendEmergencySMS(
                $validated['reporting_phone'],
                $validated['emergency_type'],
                $validated['description']
            );

            if ($smsSent) {
                Log::info('📩 Emergency SMS Sent Successfully!');
            } else {
                Log::error('🚫 Emergency SMS Failed to Send');
            }

            return redirect()->route('home')->with('status', 'Emergency reported successfully!');
        } catch (\Exception $e) {
            Log::error('❌ Error in Emergency Submission', ['error' => $e->getMessage()]);
            return redirect()->route('home')->with('error', 'Emergency report failed. Please try again.');
        }
    }

    protected function sendEmergencySMS($cellNumber, $emergencyType, $description)
    {
        try {
            Log::info('📡 Sending SMS to: ' . $cellNumber);

            if (!preg_match('/^\+?1?\d{10}$/', $cellNumber)) {
                $cellNumber = '1' . preg_replace('/\D/', '', $cellNumber);
            }

            $message = "🚨 Emergency Alert: $emergencyType. Details: $description.";
            $bearerToken = '0b75f8d79d7d40cb9f7003e5498c29f0'; 
            $apiUrl = 'https://sms.api.sinch.com/xms/v1/0986c99adc6346249028b5a5d5543331/batches';

            $payload = [
                'from' => '19312230233',  
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

            Log::info("📬 SMS API Response: HTTP $httpCode - $response");

            if (curl_errno($ch)) {
                Log::error('cURL error: ' . curl_error($ch));
                throw new \Exception('cURL error: ' . curl_error($ch));
            }

            curl_close($ch);

            return $httpCode >= 200 && $httpCode < 300; 
        } catch (\Exception $e) {
            Log::error('🚫 SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }
}

