<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InviteController extends Controller
{
	   public function showForm()
    {
        return view('invite'); // Ensure this view exists in resources/views/invite.blade.php
    }
	public function sendInvite(Request $request)
{
    Log::info('sendInvite() called');  // Confirm function is called

    try {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cell_number' => 'required|string',  // Removed 'unique'
            'email' => 'nullable|email',  // Removed 'unique'
        ]);

        Log::info('Validation passed', $request->all());

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed: ' . json_encode($e->errors()));
        return back()->withErrors($e->errors());
    }

    $token = bin2hex(random_bytes(16));  // Generate a token

    Invite::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'cell_number' => $request->cell_number,
        'invited_by' => Auth::id(),
        'token' => $token,
        'expires_at' => now()->addDays(7),
    ]);

    Log::info('Invite created with token: ' . $token);

    $this->sendInviteSMS($request->cell_number, $token);

    Log::info('SMS sent to: ' . $request->cell_number);

    return back()->with('success', 'Invite sent successfully!');
}

    // Function to send SMS (Now inside the class)
    protected function sendInviteSMS($cellNumber, $token)
    {
        try {
            if (!str_starts_with($cellNumber, '1')) {
                $cellNumber = '1' . $cellNumber;
            }

            $message = "You're invited! Use this link to create your account: " .
                       url('/register?token=' . $token);

            $bearerToken = '0b75f8d79d7d40cb9f7003e5498c29f0';  // Replace with your actual API key
            $apiUrl = 'https://sms.api.sinch.com/xms/v1/0986c99adc6346249028b5a5d5543331/batches';  // Replace with endpoint

            $payload = [
                'from' => '19312230233',  // Replace with your sender number
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
}

