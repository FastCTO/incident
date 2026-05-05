<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SinchSmsService
{
    private string $servicePlanId = '0986c99adc6346249028b5a5d5543331';

    public function send(string $to, string $message): bool
    {
        $bearerToken = env('SINCH_API_SECRET');
        $fromNumber = $this->cleanPhoneNumber((string) env('SINCH_FROM_NUMBER'));
        $to = $this->cleanPhoneNumber($to);

        if (!$bearerToken || !$fromNumber || !$to) {
            Log::warning('Sinch SMS not sent because configuration or recipient is missing.', [
                'has_bearer_token' => (bool) $bearerToken,
                'from_number' => $fromNumber,
                'to' => $to,
            ]);

            return false;
        }

        $url = "https://sms.api.sinch.com/xms/v1/{$this->servicePlanId}/batches";

        try {
            $response = Http::withToken($bearerToken)
                ->acceptJson()
                ->asJson()
                ->post($url, [
                    'from' => $fromNumber,
                    'to' => [$to],
                    'body' => $message,
                ]);

            Log::info('Sinch SMS response.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Sinch SMS failed.', [
                'error' => $e->getMessage(),
                'to' => $to,
            ]);

            return false;
        }
    }

    public function sendAdminAlert(string $message): bool
    {
        $alertPhone = env('FSV_ALERT_PHONE');

        if (!$alertPhone) {
            Log::warning('FSV_ALERT_PHONE is not configured; admin SMS alert skipped.');
            return false;
        }

        return $this->send($alertPhone, $message);
    }

    private function cleanPhoneNumber(string $phone): string
    {
        $phone = trim(preg_replace('/\s+#.*$/', '', $phone));
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if ($phone && strlen($phone) === 10) {
            $phone = '1' . $phone;
        }

        return $phone;
    }
}
