<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return view('video.index'); // The main video page with buttons
    }

    public function recorded()
    {
        return view('video.recorded'); // Recorded video page
    }

    public function multiStream()
    {
        // Fetch HLS streams from cache (ensuring fresh URLs per user)
        $userId = auth()->id() ?? 'guest';

        $camera1 = apcu_fetch("wave_stream_sd_{$userId}") ?? env('WAVE_CAMERA_ID');
        $camera2 = apcu_fetch("wave_stream_sd_cam2_{$userId}") ?? env('WAVE_CAMERA2_ID');
        $camera3 = apcu_fetch("wave_stream_sd_cam3_{$userId}") ?? env('WAVE_CAMERA3_ID');

        return view('video.multistream', compact('camera1', 'camera2', 'camera3'));
    }
}

