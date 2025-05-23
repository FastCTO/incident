<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class MultiStreamController extends Controller
{
    // Standard logged-in multicam view
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view the streams.');
        }

        $streams = $this->loadCameraStreams();

        if (empty($streams)) {
            Log::error("❌ No camera streams found! Displaying error message.");
            return view('video.multistream', ['error' => 'No camera streams available.']);
        }

        Log::info("✅ MultiStreamController loaded streams for user {$user->id}: " . json_encode($streams));

        return view('video.multistream', compact('streams'));
    }

    // Secure public version via signed token
    public function secure(Request $request)
    {
        if (!URL::hasValidSignature($request)) {
            Log::warning("🔒 Invalid or expired signature on secure multicam request.");
            abort(403, 'Invalid or expired link.');
        }

        $streams = $this->loadCameraStreams();

        if (empty($streams)) {
            Log::error("❌ No camera streams found for secure link!");
            return response('No camera streams available.', 503);
        }

        Log::info("✅ Secure police link loaded with streams: " . json_encode($streams));

        return view('video.multistream-secure', compact('streams'));
    }

    // Reusable camera stream loader
    private function loadCameraStreams(): array
    {
        $cameraFiles = [
            'camera1' => "/home/fastcto/.camera1",
            'camera2' => "/home/fastcto/.camera2",
            'camera3' => "/home/fastcto/.camera3",
        ];

        $streams = [];

        foreach ($cameraFiles as $key => $filePath) {
            if (File::exists($filePath) && trim(File::get($filePath)) !== '') {
                $streams[$key] = trim(File::get($filePath));
                Log::info("📹 Loaded stream for $key: {$streams[$key]}");
            } else {
                Log::warning("⚠️ Missing or empty stream file: $filePath");
            }
        }

        return $streams;
    }
}

