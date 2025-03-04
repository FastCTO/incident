<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class MultiStreamController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view the streams.');
        }

        // Camera stream file paths
        $cameraFiles = [
            'camera1' => "/home/fastcto/.camera1",
            'camera2' => "/home/fastcto/.camera2",
            'camera3' => "/home/fastcto/.camera3",
        ];

        $streams = [];

        foreach ($cameraFiles as $key => $filePath) {
            if (File::exists($filePath) && trim(File::get($filePath)) !== '') {
                $streams[$key] = trim(File::get($filePath));
                Log::info("✅ Loaded stream for $key from $filePath: " . $streams[$key]);
            } else {
                Log::warning("⚠️ Missing or empty camera stream file: $filePath");
            }
        }

        if (empty($streams)) {
            Log::error("❌ No camera streams found! Displaying error message.");
            return view('video.multistream', ['error' => 'No camera streams available.']);
        }

        Log::info("✅ MultiStreamController loaded streams for user {$user->id}: " . json_encode($streams));

        return view('video.multistream', compact('streams'));
    }
}

