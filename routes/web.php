<?php


use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Drive\DriveController;
use App\Http\Controllers\Drive\FileController;
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
Route::middleware('auth')->group(function () {

    Route::get('/', [DriveController::class, 'index'])
        ->name('drive.index');

    Route::get('/drive/search', [DriveController::class, 'search'])
        ->name('drive.search');

    Route::post('/drive/files', [FileController::class, 'store'])
        ->name('drive.files.store');

});

Route::post('/drive/folders', [FileController::class, 'createFolder'])
    ->name('drive.folders.store');

Route::delete(
    '/drive/files/{fileId}',
    [FileController::class, 'destroy']
)->name('drive.files.destroy');

Route::patch(
    '/drive/files/{fileId}/rename',
    [FileController::class, 'rename']
)->name('drive.files.rename');

Route::post(
    '/drive/files/{fileId}/share',
    [FileController::class, 'share']
)->name('drive.files.share');