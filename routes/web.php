<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SchoolManagementController;
use App\Http\Controllers\ChatController; // Make sure this is imported
use App\Http\Controllers\EmergencyChatController;
use App\Http\Controllers\CriticalCareController;
use App\Http\Controllers\WaveAuthController;
use App\Http\Controllers\WaveCameraController;
use App\Http\Controllers\LiveStreamController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\IndoorMapController;
use App\Http\Controllers\MultiStreamController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/video', [VideoController::class, 'index'])->name('video.page');
    Route::get('/live-stream', [LiveStreamController::class, 'getLiveStream'])->name('live.stream');
    Route::get('/recorded-video', [VideoController::class, 'recorded'])->name('video.recorded'); // Placeholder
    Route::get('/maps/indoor', [IndoorMapController::class, 'index'])->name('maps.indoor');
   // Route::get('/multi-stream', [VideoController::class, 'multiStream'])->name('video.multistream');
       Route::get('/multi-stream', [MultiStreamController::class, 'index'])->name('video.multistream');
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/{id}', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/{id}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/{roomId}/teacher/{teacherId}', [RoomController::class, 'removeTeacher'])->name('rooms.teacher.remove');
    });

    Route::prefix('emergency')->group(function () {
        Route::get('/report', [EmergencyController::class, 'report'])->name('emergency.report');
        Route::post('/report', [EmergencyController::class, 'store'])->name('emergency.store');
        Route::post('/send-text', [EmergencyController::class, 'sendEmergencyText'])->name('emergency.sendtext');
    });

    Route::prefix('invite')->group(function () {
        Route::get('/', [InviteController::class, 'showForm'])->name('invite.form');
        Route::post('/', [InviteController::class, 'sendInvite'])->name('invite');
    });

    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');

    Route::prefix('school-management')->group(function () {
        Route::get('/', [SchoolManagementController::class, 'index'])->name('school.management');
        Route::put('/update', [SchoolManagementController::class, 'update'])->name('school.management.update');
        Route::post('/clear-chat', [SchoolManagementController::class, 'clearEmergencyChat'])->name('school.management.clearChat');
    });

    Route::get('/maps', [RoomController::class, 'maps'])->name('maps.index');
    Route::get('/maps/indoor', function () {
    return view('maps.indoor');
	})->name('maps.indoor');


    // Define the 'chat' route
    // 🤖 Chatbot (OpenAI-based)
Route::prefix('chat')->group(function () {
    Route::get('/', function () {
        return view('chat');
    })->name('chat');
    Route::post('/', [ChatController::class, 'sendMessage'])->name('chat.send');
});


    Route::prefix('emergency-chat')->group(function () {
        Route::get('/', [EmergencyChatController::class, 'index'])->name('emergency.chat');
        Route::post('/send', [EmergencyChatController::class, 'sendMessage'])->name('emergency.chat.send');
        Route::get('/fetch', [EmergencyChatController::class, 'fetchMessages'])->name('emergency.chat.fetch');
    });

    Route::get('/critical-care', [CriticalCareController::class, 'index'])->name('critical-care');

    Route::post('/wave-auth', [WaveAuthController::class, 'authenticate'])->name('wave.auth');

    Route::get('/live-stream', [LiveStreamController::class, 'getLiveStream'])->name('live.stream');

    Route::get('/get-camera-stream', [WaveCameraController::class, 'getStreamUrls'])->name('camera.stream');

	Route::match(['get', 'post'], '/force-refresh', function () {
    app(WaveAuthController::class)->authenticate();
    app(WaveCameraController::class)->getStreamUrls();
    return redirect()->route('live.stream');
})->name('force.refresh');

    Route::post('/logout', function (Request $request) { // Inject Request
        $user = Auth::user();
        if ($user) {
            Log::info("🚪 User {$user->id} logging out. Clearing cache.");
            apcu_delete("wave_token_{$user->id}");
            apcu_delete("wave_stream_hd_{$user->id}");
            apcu_delete("wave_stream_sd_{$user->id}");
        }

        Session::flush();
        Auth::logout();

        // Invalidate and regenerate the session token
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect('/login')->with('status', 'Logged out successfully');
    })->name('logout');
});
