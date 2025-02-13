<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaveCameraController extends Controller
{
    /**
     * Load the HLS stream for a single camera.
     */
    public function viewSingleCameraHLS()
    {
        $cameraId = '46991f0f-9e6f-a682-d0fd-b92b2ff03785';
        $serverUrl = "https://f69fafd7-1482-4637-add4-2b1139cd6521.relay-us-chi-2-prod-dp.vmsproxy.com";

        // Authenticate and get a token
        $token = $this->getWaveToken();
        if (!$token) {
            return response()->json(['error' => 'Failed to authenticate with Wave API'], 500);
        }

        // Fetch the HLS stream URL
        $hlsUrl = "{$serverUrl}/hls/{$cameraId}.m3u?authKey={$token}&chunked&sessionID=6&hi";

        Log::info("Generated HLS URL: $hlsUrl");

        return view('wave.view-camera-hls', compact('hlsUrl'));
    }

    /**
     * Authenticate with the Wave API and return the access token.
     */
    private function getWaveToken()
    {
        $url = "https://sync.wavevms.com/cdb/oauth2/token";

        $response = Http::asJson()->post($url, [
            'grant_type'    => 'password',
            'response_type' => 'token',
            'client_id'     => '3rdParty',
            'scope'         => 'cloudSystemId=f69fafd7-1482-4637-add4-2b1139cd6521',
            'username'      => 'vic@fsv.io',
            'password'      => 'H0tpussy'
        ]);

        if ($response->failed()) {
            Log::error('Wave API Authentication Failed: ' . $response->body());
            return null;
        }

        $data = $response->json();
        return $data['access_token'] ?? null;
    }
}

