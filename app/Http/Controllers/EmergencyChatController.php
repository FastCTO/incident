<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;

class EmergencyChatController extends Controller
{
    // Display the emergency chat view
    public function index()
    {
        return view('emergency_chat');
    }

    // Handle sending a message
    public function sendMessage(Request $request)
    {
        $message = new ChatMessage();
        $message->user_id = auth()->id(); // Log the sender's ID
        $message->message = $request->input('message');
        $message->save();

        return response()->json(['status' => 'Message sent!']);
    }

    // Fetch recent messages
    public function fetchMessages()
    {
        $messages = ChatMessage::with('user')
            ->latest()
            ->limit(50) // Fetch the last 50 messages
            ->get();

        return response()->json(
            $messages->map(function ($msg) {
                return [
                    'user' => $msg->user->name,
                    'message' => $msg->message,
                ];
            })
        );
    }
}

