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
        return view('video.recorded'); // Placeholder for recorded videos
    }
}

