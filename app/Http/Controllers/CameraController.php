<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CameraController extends Controller
{
    public function stream()
    {
        // Get a fresh auth token
        $waveToken = trim(shell_exec('cat ~/.wave_token | cut -d "=" -f2'));

        // Fetch the HLS URL
        $deviceId = "46991f0f-9e6f-a682-d0fd-b92b2ff03785";
        $relayServer = "f69fafd7-1482-4637-add4-2b1139cd6521.relay-us-chi-2-prod-dp.vmsproxy.com";
        $hlsUrl = "https://{$relayServer}/hls/{$deviceId}.m3u8?authKey={$waveToken}&chunked&sessionID=6&hi";

        return view('camera.stream', compact('hlsUrl'));
    }
}

