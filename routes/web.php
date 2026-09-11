<?php


use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Drive\DriveController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');

Route::post('/logout', [GoogleAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Route::get('/', [DriveController::class, 'index'])
//     ->name('drive.index');
Route::get('/', [DriveController::class, 'index'])
    ->middleware('auth')
    ->name('drive.index');