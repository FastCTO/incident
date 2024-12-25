<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\EmergencyChatMessage;

class EmergencyChatController extends Controller
{
    /**
     * Show the emergency chat view.
     */
    public function index()
    {
        return view('emergency_chat');
    }

    /**
     * Handle sending a new emergency chat message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $message = $request->message;
        $user = auth()->user(); // Might be null if user not logged in, but we use 'auth' middleware so it should be valid

        // Broadcast the event (public channel)
        broadcast(new EmergencyChatMessage($message, $user))->toOthers();

        return response()->json(['status' => 'Message Sent!'], 200);
    }
}

