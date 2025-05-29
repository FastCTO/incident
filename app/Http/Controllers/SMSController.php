<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class SMSController extends Controller
{
    public static function send($phone, $message)
    {
        try {
            // 📞 Ensure country code '1' is prepended if missing
            if (!str_starts_with($phone, '1')) {
                $phone = '1' . $phone;
            }

            $bearerToken = '0b75f8d79d7d40cb9f7003e5498c29f0'; // Replace with your actual API key
            $apiUrl = 'https://sms.api.sinch.com/xms/v1/0986c99adc6346249028b5a5d5543331/batches';

            $payload = [
                'from' => '19312230233', // Replace with your actual number
                'to' => [$phone],
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

            Log::info("🚓 Police SMS API Response: HTTP $httpCode - $response");

            if (curl_errno($ch)) {
                Log::error('🚫 cURL error: ' . curl_error($ch));
                throw new \Exception('cURL error: ' . curl_error($ch));
            }

            if ($httpCode >= 400) {
                Log::error('🚫 Failed to send police SMS: ' . $response);
                throw new \Exception('Failed to send police SMS: ' . $response);
            }

            curl_close($ch);

        } catch (\Exception $e) {
            Log::error('🚫 Police SMS sending failed: ' . $e->getMessage());
            throw $e;
        }
    }
}

