<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication routes (login, register, password reset, etc.)
Auth::routes();

// Route to the home page after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Invite Routes
Route::get('/invite', function () {
    return view('invite'); // Display invite form
})->name('invite.form');

Route::post('/invite', [InviteController::class, 'sendInvite'])->name('invite');
