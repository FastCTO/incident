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
use App\Http\Controllers\CriticalCareController;
use App\Http\Controllers\WaveCameraController;

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
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/{id}', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/{id}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/{roomId}/teacher/{teacherId}', [RoomController::class, 'removeTeacher'])
            ->name('rooms.teacher.remove');
    });

    // Emergency
    Route::prefix('emergency')->group(function () {
        Route::get('/report', [EmergencyController::class, 'report'])->name('emergency.report');
        Route::post('/report', [EmergencyController::class, 'store'])->name('emergency.store');
        Route::post('/send-text', [EmergencyController::class, 'sendEmergencyText'])->name('emergency.sendtext');
    });

    // Invite / Guests
    Route::prefix('invite')->group(function () {
        Route::get('/', [InviteController::class, 'showForm'])->name('invite.form');
        Route::post('/', [InviteController::class, 'sendInvite'])->name('invite');
    });

    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');

    // School Management
    Route::prefix('school-management')->group(function () {
        Route::get('/', [SchoolManagementController::class, 'index'])->name('school.management');
        Route::put('/update', [SchoolManagementController::class, 'update'])->name('school.management.update');
        Route::post('/clear-chat', [SchoolManagementController::class, 'clearEmergencyChat'])
            ->name('school.management.clearChat');
    });

    // Maps
    Route::get('/maps', [RoomController::class, 'maps'])->name('maps.index');

    // Chatbot (OpenAI-based)
    Route::prefix('chat')->group(function () {
        Route::get('/', function () {
            return view('chat');
        })->name('chat');
        Route::post('/', [ChatController::class, 'sendMessage'])->name('chat.send');
    });

    // Emergency Chat (Real-time)
    Route::prefix('emergency-chat')->group(function () {
        Route::get('/', [EmergencyChatController::class, 'index'])->name('emergency.chat');
        Route::post('/send', [EmergencyChatController::class, 'sendMessage'])->name('emergency.chat.send');
        Route::get('/fetch', [EmergencyChatController::class, 'fetchMessages'])->name('emergency.chat.fetch');
    });

    // Critical Care Section
    Route::get('/critical-care', [CriticalCareController::class, 'index'])->name('critical-care');

    // Wave Camera Streaming (HLS)
    Route::get('/wave/camera', [WaveCameraController::class, 'viewSingleCameraHLS'])->name('wave.camera.hls');

    // Logout
    Route::post('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect('/');
    })->name('logout');
});

