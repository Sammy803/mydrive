<?php

namespace App\Http\Controllers\Drive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriveController extends Controller
{
    public function index(): View
    {
        return view('drive.index');
    }
}
