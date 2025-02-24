<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WaveAuthController;
use App\Http\Controllers\WaveCameraController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle post-login actions: authenticate with Wave API and fetch stream URLs.
     */
    protected function authenticated(Request $request, $user)
    {
        Log::info("✅ User {$user->id} logged in. Fetching Wave token & stream...");

        // ✅ Authenticate & store in cache
        app(WaveAuthController::class)->authenticate();

        // ✅ Fetch & store stream URLs in cache
        app(WaveCameraController::class)->getStreamUrls();
    }

    /**
     * Full logout: clears session, cache, and cookies.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            Log::info("🚪 User {$user->id} logging out. Clearing session & cache.");

            // ✅ Remove cached authentication & stream data
            apcu_delete("wave_token_{$user->id}");
            apcu_delete("wave_stream_{$user->id}");
        }

        // ✅ Clear Laravel session & force logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ Redirect user to login page
        return redirect('/login')->with('status', 'Logged out successfully');
    }
}

