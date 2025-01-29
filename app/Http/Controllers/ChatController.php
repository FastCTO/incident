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

        // Demo disclaimer at the start of every response
        $demoDisclaimer = "🚨 **This is a demo system.** If this were a real emergency, please dial 911 immediately.";

        // Detect test/demo messages
        $isTest = stripos($message, 'test') !== false || stripos($message, 'demo') !== false;

        $systemPrompt = "**AI Safety Help - Guardian Cloud**\n" .
            "You are an AI assistant for Guardian Cloud, providing emergency guidance.\n" .
            "\n" .
            "**Handling Tests & Demos:**\n" .
            "- If the user mentions 'test' or 'demo', do NOT assume it's a real emergency.\n" .
            "- Always prepend responses with: '🚨 This is a demo system. If this were a real emergency, please dial 911 immediately.'\n" .
            "\n" .
            "**Handling Real Emergencies:**\n" .
            "- Guide users step-by-step for medical and active shooter emergencies.\n" .
            "- Always ask: 'Have you pressed the **Report Emergency** button above to notify 911?'\n" .
            "- Provide links where applicable:\n" .
            "  - **Medical Emergencies:** Use the **Critical Care** button above.\n" .
            "  - **Communication Help:** Use the **Emergency Chat** button above.\n" .
            "\n" .
            "**You do not contact emergency services. The user must take action manually.**";

        $contextMessage = "User: {$currentUser->name} (Role: {$currentUser->role}) is on '{$currentRoute}'.";

        try {
            $aiMessages = [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'system', 'content' => $contextMessage],
                ['role' => 'user', 'content' => $message],
            ];

            // Return a controlled response in test mode
            if ($isTest) {
                return $demoDisclaimer . "\n\nPlease use the action buttons above to proceed.";
            }

            $response = $client->post(config('apis.openai.endpoint'), [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('apis.openai.api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => config('apis.openai.model'),
                    'messages' => $aiMessages,
                    'max_tokens' => config('apis.openai.max_tokens'),
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $aiResponse = trim($data['choices'][0]['message']['content']) ?? 'Sorry, I couldn\'t process that.';

            return $demoDisclaimer . "\n\n" . $aiResponse . "\n\nUse the action buttons above to take further steps.";
        } catch (\Exception $e) {
            Log::error("ChatController Error: " . $e->getMessage());
            return 'An error occurred while processing your request. Please try again.';
        }
    }
}

