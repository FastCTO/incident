<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IncidentFileController;
use App\Http\Controllers\NvrSystemController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;

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

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/organization', [OrganizationController::class, 'edit'])
        ->name('organization.edit');

    Route::put('/organization', [OrganizationController::class, 'update'])
        ->name('organization.update');

    Route::resource('sites', SiteController::class)->except(['show']);

    Route::resource('organizations', OrganizationManagementController::class)->except(['show']);

    Route::resource('nvr-systems', NvrSystemController::class)->except(['show']);

    Route::post('/incidents/start', [IncidentController::class, 'start'])
        ->name('incidents.start');

    Route::post('/incidents/{incident}/restore', [IncidentController::class, 'restore'])
        ->name('incidents.restore');

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
