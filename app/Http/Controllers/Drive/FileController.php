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

    public function createFolder(Request $request,GoogleDriveService $driveService): RedirectResponse {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_folder_id' => ['nullable', 'string'],
        ]);

        $driveService->createFolder(
            $request->input('name'),
            $request->input('parent_folder_id')
        );

        return back()->with(
            'success',
            'Folder created successfully.'
        );
    }

    public function destroy(
    string $fileId,
    GoogleDriveService $driveService
    ): RedirectResponse {

        $driveService->deleteFile($fileId);

        return back()->with(
            'success',
            'File deleted successfully.'
        );
    }

    public function rename(
    Request $request,
    string $fileId,
    GoogleDriveService $driveService
    ): RedirectResponse {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $driveService->renameFile(
            $fileId,
            $request->input('name')
        );

        return back()->with(
            'success',
            'Renamed successfully.'
        );
    }

    public function share(
    Request $request,
    string $fileId,
    GoogleDriveService $driveService
    ): RedirectResponse {

        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $driveService->shareFile(
            $fileId,
            $request->input('email')
        );

        return back()->with(
            'success',
            'File shared successfully.'
        );
    }
}
