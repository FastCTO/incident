<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $response = $this->generateResponse($message);

        return response()->json([
            'message' => $message,
            'response' => $response,
        ]);
    }


    private function generateResponse($message)
{
    $client = new Client();

    // Fetch dynamic context
    $currentUser = auth()->user();
    $currentRoute = request()->path();
    $contextMessage = "You are assisting {$currentUser->name} (role: {$currentUser->role}). They are currently on the '{$currentRoute}' page.";

    try {
        $response = $client->post(config('apis.openai.endpoint'), [
            'headers' => [
                'Authorization' => 'Bearer ' . config('apis.openai.api_key'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => config('apis.openai.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $contextMessage],
                    ['role' => 'user', 'content' => $message],
                ],
                'max_tokens' => config('apis.openai.max_tokens'),
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        return trim($data['choices'][0]['message']['content']) ?? 'Sorry, I couldn\'t process that.';
    } catch (\Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
}

}

