<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');

        // Start session for memory
        session_start();
        if (!isset($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }

        // Store user's message in session memory
        $_SESSION['chat_history'][] = ['role' => 'user', 'content' => $message];

        // Generate response
        $response = $this->generateResponse($message);

        return response()->json([
            'message' => $message,
            'response' => $response,
        ]);
    }

    private function generateResponse($message)
    {
        $client = new Client();
        $currentUser = Auth::user();
        $currentRoute = request()->path();

        // Single disclaimer to start responses
        $demoDisclaimer = "🚨 **This is a demo system.** If this were a real emergency, please dial 911 immediately.";

        // Emergency response keywords
        $lockdownKeywords = ['gun', 'shooter', 'active shooter', 'gunfire', 'gunshots', 'shooting', 'gun', 'lockdown'];
        $injuryKeywords = ['wounded', 'hurt', 'injured', 'bleeding', 'shot', 'first aid'];
        $commsKeywords = ['chat', 'communicate', 'emergency chat', 'help', 'assistance'];

        // Determine response category
        $responseType = 'default';
        foreach ($lockdownKeywords as $word) {
            if (stripos($message, $word) !== false) {
                $responseType = 'lockdown';
                break;
            }
        }
        foreach ($injuryKeywords as $word) {
            if (stripos($message, $word) !== false) {
                $responseType = 'injury';
                break;
            }
        }
        foreach ($commsKeywords as $word) {
            if (stripos($message, $word) !== false) {
                $responseType = 'comms';
                break;
            }
        }

        // Generate structured responses
        switch ($responseType) {
            case 'lockdown':
                $aiResponse = "Please **lock all doors, cover windows, and move to the hard corner.** ";
                $aiResponse .= "If you have a first aid kit, place it on the teacher's desk. Use the Report Emergency button above in Red. **Report your lockdown status, room number, and occupancy in Emergency Chat.** ";
                break;
            case 'injury':
                $aiResponse = "If someone is wounded, click the Yellow Critical Care above for wound treatment videos: **1. Apply pressure to stop bleeding. 2. Use available first aid supplies. 3. Report the number of wounded in Emergency Chat. Use the Red Button above to Text 911 directly.** ";
                $aiResponse .= "For step-by-step guidance, visit Critical Care via the button above";
                break;
            case 'comms':
                $aiResponse = "For secure communication, use the **Emergency Chat** feature. Click the light blue button above to coordinate with staff. Use the Red Button to Text 911 directly";
                break;
            default:
                $aiResponse = "I'm here to assist. Please use the Red Button to Text 911 Directly, The Light Blue Button to securely chat with school staff and first responders. The Yellow Button will take you to First Aid videos in case of wounded.";
                break;
        }

        // Store AI's response in session memory
        $_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $aiResponse];

        return "$demoDisclaimer\n\n$aiResponse";
    }
}

