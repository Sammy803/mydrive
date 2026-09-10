<?php

use App\Http\Controllers\Drive\DriveController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DriveController::class, 'index'])
    ->name('drive.index');