<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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
use App\Http\Controllers\WaveAuthController;
use App\Http\Controllers\WaveCameraController;
use App\Http\Controllers\LiveStreamController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\IndoorMapController;
use App\Http\Controllers\MultiStreamController;
use App\Http\Controllers\InvitePoliceController;

Route::get('/', fn () => redirect()->route('home'));
Auth::routes();


// 🚓 Public: Secure Police Video Link


Route::get('/invite-police/{token}', [InvitePoliceController::class, 'show'])->name('invite.police.show');

Route::post('/send-invite-police-link', function (Request $request) {
    $request->validate(['phone' => 'required']);

    $link = InvitePoliceController::generateSecureLink();

    app('App\Http\Controllers\SMSController')->send(
        $request->phone,
        "Secure Police Link: $link\nThis link will expire in 90 minutes."
    );

    return back()->with('status', 'Link sent!');
})->name('send.invite.police.link');

	Route::get('/secure-multistream', [MultiStreamController::class, 'secure'])->name('secure.multistream');
    Route::get('/multi-stream', [MultiStreamController::class, 'index'])->name('video.multistream');

// 🛡️ Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard & Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Video
    Route::get('/video', [VideoController::class, 'index'])->name('video.page');
    Route::get('/recorded-video', [VideoController::class, 'recorded'])->name('video.recorded');
    Route::get('/live-stream', [LiveStreamController::class, 'getLiveStream'])->name('live.stream');
    Route::get('/get-camera-stream', [WaveCameraController::class, 'getStreamUrls'])->name('camera.stream');

    // Emergency Reporting
    Route::prefix('emergency')->group(function () {
        Route::get('/report', [EmergencyController::class, 'report'])->name('emergency.report');
        Route::post('/report', [EmergencyController::class, 'store'])->name('emergency.store');
        Route::post('/send-text', [EmergencyController::class, 'sendEmergencyText'])->name('emergency.sendtext');
    });

    // Rooms
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/{id}', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/{id}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/{roomId}/teacher/{teacherId}', [RoomController::class, 'removeTeacher'])->name('rooms.teacher.remove');
    });

    // Maps
    Route::get('/maps', [RoomController::class, 'maps'])->name('maps.index');
    Route::get('/maps/indoor', [IndoorMapController::class, 'index'])->name('maps.indoor');

    // Invite System
    Route::prefix('invite')->group(function () {
        Route::get('/', [InviteController::class, 'showForm'])->name('invite.form');
        Route::post('/', [InviteController::class, 'sendInvite'])->name('invite');
    });

    // Guests
    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');

    // School Management
    Route::prefix('school-management')->group(function () {
        Route::get('/', [SchoolManagementController::class, 'index'])->name('school.management');
        Route::put('/update', [SchoolManagementController::class, 'update'])->name('school.management.update');
        Route::post('/clear-chat', [SchoolManagementController::class, 'clearEmergencyChat'])->name('school.management.clearChat');
        Route::post('/simulate-db-outage', [SchoolManagementController::class, 'simulateDbOutage'])->name('school.management.simulateDbOutage');
    });

    // Chat Interfaces
    Route::prefix('chat')->group(function () {
        Route::get('/', fn () => view('chat'))->name('chat');
        Route::post('/', [ChatController::class, 'sendMessage'])->name('chat.send');
    });

    Route::prefix('emergency-chat')->group(function () {
        Route::get('/', [EmergencyChatController::class, 'index'])->name('emergency.chat');
        Route::post('/send', [EmergencyChatController::class, 'sendMessage'])->name('emergency.chat.send');
        Route::get('/fetch', [EmergencyChatController::class, 'fetchMessages'])->name('emergency.chat.fetch');
    });

    // Critical Care
    Route::get('/critical-care', [CriticalCareController::class, 'index'])->name('critical-care');

    // Wave Auth
    Route::post('/wave-auth', [WaveAuthController::class, 'authenticate'])->name('wave.auth');

    // Force Refresh
    Route::match(['get', 'post'], '/force-refresh', function () {
        app(WaveAuthController::class)->authenticate();
        app(WaveCameraController::class)->getStreamUrls();
        return redirect()->route('live.stream');
    })->name('force.refresh');

    // Logout
    Route::post('/logout', function (Request $request) {
        $user = Auth::user();
        if ($user) {
            Log::info("🚪 User {$user->id} logging out. Clearing cache.");
            apcu_delete("wave_token_{$user->id}");
            apcu_delete("wave_stream_hd_{$user->id}");
            apcu_delete("wave_stream_sd_{$user->id}");
        }

        Session::flush();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Logged out successfully');
    })->name('logout');
});

