<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WaveAuthController extends Controller
{
    public function authenticate()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $cachedToken = apcu_fetch("wave_token_{$user->id}"); // User-specific key
        if ($cachedToken) {
            Log::info("✅ Using cached Wave API auth token for user {$user->id}");
            return response()->json(['message' => 'Using cached token', 'access_token' => $cachedToken]);
        }

        Log::info("🔄 Requesting new Wave API auth token...");
        $response = Http::post('https://sync.wavevms.com/cdb/oauth2/token', [
            'grant_type' => 'password',
            'response_type' => 'token',
            'client_id' => '3rdParty',
            'scope' => env('WAVE_SCOPE'),
            'username' => env('WAVE_USERNAME'),
            'password' => env('WAVE_PASSWORD')
        ]);

        if (!$response->successful()) {
            Log::error("❌ Wave API Auth Error: " . $response->body());
            return response()->json(['error' => 'Failed to authenticate with Wave API'], 500);
        }

        $data = $response->json();
        $accessToken = $data['access_token'] ?? null;

        if (!$accessToken) {
            return response()->json(['error' => 'Authentication token missing'], 500);
        }

        apcu_store("wave_token_{$user->id}", $accessToken, 540); // User-specific key, 9 min expiration

        Log::info("✅ Cached new Wave API token for user {$user->id}");

        return response()->json([
            'message' => 'Authentication successful',
            'access_token' => $accessToken,
        ]);
    }
}
