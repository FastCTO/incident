<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EmergencyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "web" middleware group. Make something great!
|--------------------------------------------------------------------------
*/

// Default Route - Welcome Page
/*Route::get('/', function () {
    return view('welcome');
});
 */

Route::get('/', function () {
    return redirect('/login');
});


// Authentication Routes (Login, Register, Password Reset, etc.)
Auth::routes();

// Dashboard Route - After Login (Home Page)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // tessting route
      Route::get('/test-school-info', function () {
    $school = \App\Models\SchoolInfo::first();
    return $school ? $school->toArray() : 'No school info found';
});

    // Room Routes

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/api/rooms', function () {
    return App\Models\Room::all();
    });

    // Emergency Reporting Routes

//Route::get('/emergency/report', [EmergencyController::class, 'report'])->name('emergency.report');
//Route::post('/emergency/report', [EmergencyController::class, 'submitReport']);
//Route::post('/emergency/store', [EmergencyController::class, 'store'])->name('emergency.store');
    Route::middleware(['auth'])->group(function () {
    Route::get('/emergency/report', [EmergencyController::class, 'report'])->name('emergency.report');
    Route::post('/emergency/report', [EmergencyController::class, 'store'])->name('emergency.store');
});

// Route to handle form submission (POST request)
Route::post('/emergency/report', [EmergencyController::class, 'store'])->name('emergency.store');
    // Invite Routes
Route::get('/invite', [InviteController::class, 'showForm'])->name('invite.form');
Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');
    Route::get('/invite', function () {
        return view('invite'); // Invite Form
    })->name('invite.form');
    Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');
});

