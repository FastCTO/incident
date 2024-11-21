<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\GuestController;


if (App::isDownForMaintenance()) {
    Route::view('/', 'maintenance');
}

// Redirect users with room assignments to the dashboard
Route::get('/', function () {
    return redirect()->route('home');
});

// Routes that require user authentication
Route::middleware(['auth'])->group(function () {
    // Home/Dashboard Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Room Management Routes
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::put('/rooms/{id}', [RoomController::class, 'updateOccupancy'])->name('rooms.updateOccupancy');

    // Emergency Reporting Routes
    Route::get('/emergency/report', [EmergencyController::class, 'report'])->name('emergency.report');
    Route::post('/emergency/report', [EmergencyController::class, 'store'])->name('emergency.store');

    // Invite Management Routes
    Route::get('/invite', [InviteController::class, 'showForm'])->name('invite.form');
    Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');

    // Guest Management Route
    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
    // Map Route
Route::get('/maps', [RoomController::class, 'maps'])->name('maps.index');

// Logout Route
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/'); // Redirect to the homepage after logout
})->name('logout');


});

// Authentication Routes
Auth::routes();

