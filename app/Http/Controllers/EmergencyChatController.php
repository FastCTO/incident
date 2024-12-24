<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\EmergencyChatMessage;

class EmergencyChatController extends Controller
{
    public function fetchMessages()
    {
        // Fetch messages from the database if needed
        return response()->json([]); // Optional: Implement message history logic
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $message = $request->message;
        $user = auth()->user();

        broadcast(new EmergencyChatMessage($message, $user))->toOthers();

        return response()->json(['status' => 'Message Sent!']);
    }
}

