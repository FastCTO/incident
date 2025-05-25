<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SMSController extends Controller
{
    public static function send($phone, $message)
    {
        try {
            // Sinch API details
            $service_plan_id = '0986c99adc6346249028b5a5d5543331';
            $bearer_token = '0b75f8d79d7d40cb9f7003e5498c29f0';
            $send_from = '12085815118';

            // Normalize phone number to ensure it’s 11 digits starting with 1 (US/Canada)
            $normalized = preg_replace('/\D/', '', $phone); // Remove non-digits
            if (strlen($normalized) === 10) {
                $normalized = '1' . $normalized;
            } elseif (strlen($normalized) === 11 && $normalized[0] !== '1') {
                $normalized = '1' . substr($normalized, 1);
            }

            // Validate final phone format
            if (!preg_match('/^1\d{10}$/', $normalized)) {
                Log::error("🚫 Invalid phone number after normalization: {$phone}");
                throw new \Exception('Invalid phone number format.');
            }

            // Prepare the JSON payload
            $payload = [
                'to' => [$normalized],
                'from' => $send_from,
                'body' => $message,
            ];

            // Send the request to Sinch API
            $response = Http::withToken($bearer_token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://us.sms.api.sinch.com/xms/v1/{$service_plan_id}/batches", $payload);

            // Log and handle response
            Log::info("✅ (Sinch) SMS sent to {$normalized}: {$message}");

            if ($response->failed()) {
                Log::error('🚫 Sinch SMS sending failed: ' . $response->body());
                throw new \Exception('Failed to send SMS: ' . $response->body());
            }

            return true;
        } catch (\Exception $e) {
            Log::error('🚫 SMS sending error: ' . $e->getMessage());
            throw $e;
        }
    }
}

