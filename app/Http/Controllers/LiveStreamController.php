<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LiveStreamController extends Controller
{
    public function getLiveStream()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view the stream.');
        }

        $streamUrlHD = apcu_fetch("wave_stream_hd_{$user->id}");
        $streamUrlSD = apcu_fetch("wave_stream_sd_{$user->id}");

        if (!$streamUrlHD ||!$streamUrlSD) {
            Log::warning("⚠️ HLS stream URLs not found in cache. Redirecting to force refresh...");
            return redirect()->route('force.refresh'); 
        }

        return view('wave.live-stream', [
            'streamUrlHD' => $streamUrlHD, 
            'streamUrlSD' => $streamUrlSD 
        ]);
    }
}
