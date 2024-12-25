<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SchoolManagementController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmergencyChatController;

// If app is in maintenance mode, show maintenance
if (app()->isDownForMaintenance()) {
    Route::view('/', 'maintenance');
}

// Redirect root to home
Route::get('/', function () {
    return redirect()->route('home');
});

// Authentication routes
Auth::routes();

// Auth-protected routes
Route::middleware(['auth'])->group(function () {

    // Home / Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rooms
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{roomId}/teacher/{teacherId}', [RoomController::class, 'removeTeacher'])
        ->name('rooms.teacher.remove');

    // Emergency
    Route::get('/emergency/report', [EmergencyController::class, 'report'])->name('emergency.report');
    Route::post('/emergency/report', [EmergencyController::class, 'store'])->name('emergency.store');
    Route::post('/emergency/send-text', [EmergencyController::class, 'sendEmergencyText'])->name('emergency.sendtext');

    // Invite / Guests
    Route::get('/invite', [InviteController::class, 'showForm'])->name('invite.form');
    Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');
    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');

    // School Management
    Route::get('/school-management', [SchoolManagementController::class, 'index'])->name('school.management');
    Route::put('/school-management/update', [SchoolManagementController::class, 'update'])
        ->name('school.management.update');

    // Map
    Route::get('/maps', [RoomController::class, 'maps'])->name('maps.index');

    // Chatbot (OpenAI-based)
    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');

    // Emergency Chat (Real-time)
	Route::middleware(['auth'])->group(function () {
    Route::get('/emergency-chat', [EmergencyChatController::class, 'index'])->name('emergency.chat');
    Route::post('/emergency-chat/send', [EmergencyChatController::class, 'sendMessage'])->name('emergency.chat.send');
    Route::get('/emergency-chat/fetch', [EmergencyChatController::class, 'fetchMessages'])->name('emergency.chat.fetch');
    });
    // Logout
    Route::post('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect('/');
    })->name('logout');
});

