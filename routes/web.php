<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\GuestController;

// Redirect root to login page
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes (Login, Register, Password Reset)
Auth::routes();

// Routes that require user authentication
Route::middleware(['auth'])->group(function () {

    // Home/Dashboard Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Room Management Routes
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show'); // Corrected: GET route for room details
    Route::put('/rooms/{id}', [RoomController::class, 'updateOccupancy'])->name('rooms.updateOccupancy'); // PUT route for updating occupancy

    // Emergency Reporting Routes
    Route::get('/emergency/report', [EmergencyController::class, 'report'])->name('emergency.report');
    Route::post('/emergency/report', [EmergencyController::class, 'store'])->name('emergency.store');

    // Invite Management Routes
    Route::get('/invite', [InviteController::class, 'showForm'])->name('invite.form');
    Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');

    // Guest Management Route
    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
});

