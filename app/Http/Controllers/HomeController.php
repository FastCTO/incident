<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->home_room) {
            return redirect()->route('dashboard'); // Redirect if user has a room
        }

        return view('home', [
            'user' => $user,
        ]);
    }
}

