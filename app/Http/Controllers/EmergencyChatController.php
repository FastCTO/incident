<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Log;

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
        Log::info('Emergency Chat: Incoming message request', ['data' => $request->all()]);

        // Validate request to prevent empty messages
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            $message = new ChatMessage();
            $message->user_id = auth()->id(); // Log the sender's ID
            $message->body = $request->input('message');
            $message->conversation_id = 1; // 🔥 Ensure a valid conversation_id

            if ($message->save()) {
                Log::info('Emergency Chat: Message saved', [
                    'user_id' => $message->user_id,
                    'message' => $message->body
                ]);

                return response()->json([
                    'status' => 'Message sent!',
                    'user' => auth()->user()->name,
                    'message' => $message->body
                ]);
            } else {
                Log::error('Emergency Chat: Failed to save message', [
                    'user_id' => auth()->id(),
                    'message' => $request->input('message')
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send message. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Emergency Chat: Exception occurred', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending the message.'
            ], 500);
        }
    }

    // Fetch recent messages
    public function fetchMessages()
    {
        Log::info('Emergency Chat: Fetching messages');

        try {
            $messages = ChatMessage::with('user')
                ->orderBy('created_at', 'asc') // Keep messages in proper chronological order
                ->get();

            if ($messages->isEmpty()) {
                Log::warning('Emergency Chat: No messages found');
            }

            return response()->json(
                $messages->map(function ($msg) {
                    return [
                        'user' => $msg->user->name ?? 'Unknown',
                        'message' => $msg->body,
                    ];
                })
            );
        } catch (\Exception $e) {
            Log::error('Emergency Chat: Exception while fetching messages', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch messages.'
            ], 500);
        }
    }
}

