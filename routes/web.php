<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EmergencyController;

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
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/api/rooms', function () {
        return \App\Models\Room::all();  // API to fetch all rooms
    });

    // Emergency Reporting Routes
    Route::get('/emergency/report', [EmergencyController::class, 'report'])->name('emergency.report');
    Route::post('/emergency/report', [EmergencyController::class, 'store'])->name('emergency.store');

    // Invite Management Routes
    Route::get('/invite', [InviteController::class, 'showForm'])->name('invite.form');
    Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');

    // Placeholder: Test Route for School Info
    Route::get('/test-school-info', function () {
        $school = \App\Models\SchoolInfo::first();
        return $school ? $school->toArray() : 'No school info found';
    });

});

