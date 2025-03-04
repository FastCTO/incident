<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class LiveStreamController extends Controller
{
    public function getLiveStream()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view the stream.');
        }

        // ✅ Fetch stored HLS stream URLs from APCu
        $streamUrlHD = apcu_fetch("wave_stream_hd_{$user->id}");
        $streamUrlSD = apcu_fetch("wave_stream_sd_{$user->id}");

        // ✅ Log fetched values for debugging
        Log::info("🔍 Fetched HD Stream for User {$user->id}: " . ($streamUrlHD ?: '❌ Not Found'));
        Log::info("🔍 Fetched SD Stream for User {$user->id}: " . ($streamUrlSD ?: '❌ Not Found'));

        // ✅ Force refresh if URLs are missing
        if (!$streamUrlHD || !$streamUrlSD) {
            Log::warning("⚠️ HLS stream URLs missing for User {$user->id}. Redirecting to refresh...");
            return Redirect::route('force.refresh'); 
        }

        // ✅ Pass variables to Blade view
        return view('wave.live-stream', compact('streamUrlHD', 'streamUrlSD'));
    }
}

