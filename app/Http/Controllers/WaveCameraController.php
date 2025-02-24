<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WaveCameraController extends Controller
{
    public function getStreamUrls()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // ✅ Check Cache First
        $cachedHD = apcu_fetch("wave_stream_hd_{$user->id}");
        $cachedSD = apcu_fetch("wave_stream_sd_{$user->id}");
        $cachedExpiry = apcu_fetch("wave_stream_expires_{$user->id}");

        if ($cachedHD && $cachedSD && $cachedExpiry && Carbon::now()->lessThan($cachedExpiry)) {
            Log::info("✅ Using cached HLS stream URLs for user {$user->id}");
            return response()->json([
                'stream_url_hd' => $cachedHD,
                'stream_url_sd' => $cachedSD,
                'expires_at' => $cachedExpiry
            ]);
        }

        Log::info("⏳ Requesting new HLS stream URLs...");
        $authToken = apcu_fetch("wave_token_{$user->id}");

        if (!$authToken) {
            Log::warning("⚠️ No valid Wave API token found. Fetching new one...");
            app(WaveAuthController::class)->authenticate();
            $authToken = apcu_fetch("wave_token_{$user->id}");

            if (!$authToken) {
                Log::error("❌ Still no valid Wave API token after authentication.");
                return response()->json(['error' => 'Failed to retrieve Wave API token.'], 500);
            }
        }

        $cameraId = env('WAVE_CAMERA_ID');
        $serverUrl = env('WAVE_PROXY_URL');

        $streamResponse = Http::withHeaders([
            'Authorization' => "Bearer {$authToken}"
        ])->get("{$serverUrl}/hls/{$cameraId}.m3u8");

        if (!$streamResponse->successful()) {
            Log::error("❌ Wave API Error: Failed to get HLS stream URLs. Status Code: " . $streamResponse->status());
            return response()->json(['error' => 'Failed to retrieve stream URLs.'], $streamResponse->status());
        }

        $hlsContent = $streamResponse->body();
        Log::debug("📄 HLS Response: " . substr($hlsContent, 0, 500)); // Log first 500 chars for debugging

        // ✅ Extract HD and SD URLs (Fixed)
        $hdUrl = null;
        $sdUrl = null;

        $lines = explode("\n", trim($hlsContent));
        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);

            if (str_starts_with($line, '#EXT-X-STREAM-INF')) {
                $bandwidth = null;
                if (preg_match('/BANDWIDTH=(\d+)/', $line, $matches)) {
                    $bandwidth = (int) $matches[1]; // Extract bandwidth value
                }

                if (isset($lines[$i + 1]) && filter_var(trim($lines[$i + 1]), FILTER_VALIDATE_URL)) {
                    $url = trim($lines[$i + 1]); // Extract actual URL

                    if ($bandwidth && $bandwidth > 1000000) {
                        $hdUrl = $url;
                    } else {
                        $sdUrl = $url;
                    }
                }
            }
        }

        if (!$hdUrl || !$sdUrl) {
            Log::error("❌ Could not parse HD or SD URL from HLS response.");
            return response()->json(['error' => 'Could not parse stream URLs.'], 500);
        }

        // ✅ Store URLs and expiration time in cache
        $expiresAt = Carbon::now()->addMinutes(9);
        apcu_store("wave_stream_hd_{$user->id}", $hdUrl, 540);
        apcu_store("wave_stream_sd_{$user->id}", $sdUrl, 540);
        apcu_store("wave_stream_expires_{$user->id}", $expiresAt, 540);

        Log::info("✅ Cached new HLS stream URLs for user {$user->id}");

        return response()->json([
            'stream_url_hd' => $hdUrl,
            'stream_url_sd' => $sdUrl,
            'expires_at' => $expiresAt
        ]);
    }
}

