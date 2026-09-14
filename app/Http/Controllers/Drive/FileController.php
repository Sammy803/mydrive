<?php

namespace App\Http\Controllers\Drive;

use App\Http\Controllers\Controller;
use App\Services\Google\GoogleDriveService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(
        Request $request,
        GoogleDriveService $driveService
    ): RedirectResponse {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:51200',
            ],
            'folder_id' => [
                'nullable',
                'string',
            ],
        ]);

        $driveService->uploadFile(
            $request->file('file'),
            $request->input('folder_id')
        );

        return back()->with(
            'success',
            'File uploaded successfully.'
        );
    }
}
