<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\IncidentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| FSV Incident V1 - Safe Mode
|
| Dashboard/Home are temporarily redirected to Incidents while we clean
| old Guardian Cloud controller/view code.
|
*/

Route::get('/', function () {
    return redirect()->route('incidents.index');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return redirect()->route('incidents.index');
    })->name('home');

    Route::get('/dashboard', function () {
        return redirect()->route('incidents.index');
    })->name('dashboard');

    Route::resource('incidents', IncidentController::class);

    Route::post('/logout', function (Request $request) {
        $user = Auth::user();

        if ($user) {
            Log::info("User {$user->id} logging out of FSV Incident.");
        }

        Session::flush();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Logged out successfully.');
    })->name('logout');
});
