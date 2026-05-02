<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IncidentFileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| FSV Incident V1
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

    Route::post('/incidents/start', [IncidentController::class, 'start'])
        ->name('incidents.start');

    Route::resource('incidents', IncidentController::class);

    Route::post('/incidents/{incident}/files', [IncidentFileController::class, 'store'])
        ->name('incidents.files.store');

    Route::delete('/incidents/{incident}/files/{file}', [IncidentFileController::class, 'destroy'])
        ->name('incidents.files.destroy');

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
