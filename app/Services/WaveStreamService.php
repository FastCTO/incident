<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class WaveStreamService
{
    protected $relayServer;
    protected $streamId;

    public function __construct()
    {
        $this->relayServer = env('WAVE_RELAY_SERVER'); // Ensure this is set in .env
    }

    public function getStreamUrl($userId)
    {
        // Check if the stream URL is cached
        $cacheKey = "wave_stream_url_{$userId}";
        $cachedStream = Cache::get($cacheKey);

        if ($cachedStream) {
            Log::info("✅ Using cached HLS stream for user $userId");
            return $cachedStream;
        }

        Log::info("⏳ Fetching new HLS stream URL for user $userId...");

        // Fetch the new HLS stream URL
        $streamUrl = $this->fetchStreamUrl();

        if ($streamUrl) {
            Cache::put($cacheKey, $streamUrl, now()->addMinutes(9)); // Cache for 9 minutes
            Log::info("✅ Cached new HLS stream for user $userId");
        } else {
            Log::error("❌ Failed to fetch HLS stream URL for user $userId");
        }

        return $streamUrl;
    }

    private function fetchStreamUrl()
    {
        $waveToken = Cache::get('wave_token');

        if (!$waveToken) {
            Log::error("❌ No valid Wave authentication token found!");
            return null;
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $waveToken",
            'Accept' => 'application/json',
        ])->get("https://{$this->relayServer}/hls/{$this->streamId}.m3u8");

        if ($response->successful()) {
            return $response->body();
        }

        Log::error("Wave API request failed: " . $response->body());
        return null;
    }
}

