<?php

namespace App\Http\Controllers\Drive;

use App\Http\Controllers\Controller;
use App\Services\Google\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriveController extends Controller
{
   public function index(
        Request $request,
        GoogleDriveService $driveService
    ): View {
        $folderId = $request->query('folder');

        $files = $driveService->listFiles($folderId);

        return view('drive.index', [
            'files' => $files,
            'folderId' => $folderId,
        ]);
    }

    public function search(
        Request $request,
        GoogleDriveService $driveService
    ): View {
        $request->validate([
            'q' => ['required', 'string', 'max:255'],
        ]);

        $files = $driveService->searchFiles(
            $request->string('q')->toString()
        );

        return view('drive.index', [
            'files' => $files,
            'folderId' => null,
            'searchQuery' => $request->string('q')->toString(),
        ]);
    }
}
