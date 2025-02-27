<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    // Display the video page
    public function index()
    {
        return view('video');
    }
}

