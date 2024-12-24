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

if (App::isDownForMaintenance()) {
    Route::view('/', 'maintenance');
}

// Redirect users with room assignments to the dashboard
Route::get('/', function () {
    return redirect()->route('home');
});

// Authentication Routes
Auth::routes();

// Routes that require user authentication
Route::middleware(['auth'])->group(function () {
    // Home/Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Room Management
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');

    // Emergency Reporting
    Route::get('/emergency/report', [EmergencyController::class, 'report'])->name('emergency.report');
    Route::post('/emergency/report', [EmergencyController::class, 'store'])->name('emergency.store');
    Route::post('/emergency/send-text', [EmergencyController::class, 'sendEmergencyText'])->name('emergency.sendtext');

    // Invite Management
    Route::get('/invite', [InviteController::class, 'showForm'])->name('invite.form');
    Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');

    // Guest Management
    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');

    // Map Route
    Route::get('/maps', [RoomController::class, 'maps'])->name('maps.index');

    // School Management
    Route::get('/school-management', [SchoolManagementController::class, 'index'])->name('school.management');
    Route::put('/school-management/update', [SchoolManagementController::class, 'update'])->name('school.management.update');

    // Teacher removal
    Route::delete('/rooms/{roomId}/teacher/{teacherId}', [RoomController::class, 'removeTeacher'])
        ->name('rooms.teacher.remove');

    // Chatbot Routes
    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');

    // Emergency Chat Routes
    Route::get('/emergency-chat', function () {
        return view('emergency_chat');
    })->name('emergency.chat');

    Route::get('/emergency-chat/messages', [EmergencyChatController::class, 'fetchMessages'])->name('emergency.chat.fetch');
    Route::post('/emergency-chat/messages', [EmergencyChatController::class, 'sendMessage'])->name('emergency.chat.send');

    // Logout
    Route::post('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect('/');
    })->name('logout');
});

