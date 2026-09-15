<?php

namespace App\Services\Google;


use App\Models\GoogleAccount;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use RuntimeException;


class GoogleDriveService
{

    protected Client $client;

    protected Drive $drive;

    protected GoogleAccount $googleAccount;

    public function __construct()
    {
        $user = auth()->user();

        if (!$user) {
            throw new RuntimeException('User is not authenticated.');
        }

        $this->googleAccount = $user
            ->googleAccounts()
            ->latest()
            ->firstOrFail();

        $this->client = new Client();

        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));

        $this->client->setAccessToken([
            'access_token' => $this->googleAccount->access_token,
            'expires_in' => 3600,
            'created' => now()->timestamp,
        ]);

        if ($this->client->isAccessTokenExpired()) {
            $this->refreshAccessToken();
        }

        $this->drive = new Drive($this->client);
    }

    protected function refreshAccessToken(): void
    {
        if (!$this->googleAccount->refresh_token) {
            throw new RuntimeException(
                'Google refresh token is missing. Please reconnect your Google account.'
            );
        }

        $token = $this->client->fetchAccessTokenWithRefreshToken(
            $this->googleAccount->refresh_token
        );

        if (isset($token['error'])) {
            throw new RuntimeException(
                $token['error_description'] ?? 'Unable to refresh Google token.'
            );
        }

        $this->googleAccount->update([
            'access_token' => $token['access_token'],
            'token_expires_at' => now()->addSeconds($token['expires_in'] ?? 3600),
        ]);

        $this->client->setAccessToken($token);
    }

    public function listFiles(?string $folderId = null): array
    {
        $parent = $folderId ?: 'root';

        $response = $this->drive->files->listFiles([
            'q' => sprintf(
                "'%s' in parents and trashed = false",
                $parent
            ),
            'fields' => 'files(id,name,mimeType,modifiedTime,size,webViewLink,parents)',
            'orderBy' => 'folder,name',
            'pageSize' => 100,
        ]);

        return $response->getFiles();
    }

    public function searchFiles(string $query): array
    {
        $safeQuery = str_replace("'", "\\'", $query);

        $response = $this->drive->files->listFiles([
            'q' => sprintf(
                "name contains '%s' and trashed = false",
                $safeQuery
            ),
            'fields' => 'files(id,name,mimeType,modifiedTime,size,webViewLink,parents)',
            'orderBy' => 'folder,name',
            'pageSize' => 100,
        ]);

        return $response->getFiles();
    }

    public function uploadFile(
        UploadedFile $uploadedFile,
        ?string $folderId = null
    ): DriveFile {
        $metadata = new DriveFile([
            'name' => $uploadedFile->getClientOriginalName(),
        ]);

        if ($folderId) {
            $metadata->setParents([$folderId]);
        }

        return $this->drive->files->create(
            $metadata,
            [
                'data' => file_get_contents(
                    $uploadedFile->getRealPath()
                ),
                'mimeType' => $uploadedFile->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id,name,mimeType,modifiedTime,webViewLink',
            ]
        );
    }

    public function createFolder(string $name,?string $parentFolderId = null): DriveFile {

        $metadata = new DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        if ($parentFolderId) {
            $metadata->setParents([$parentFolderId]);
        }

        return $this->drive->files->create(
            $metadata,
            [
                'fields' => 'id,name,mimeType,parents',
            ]
        );
    }  
    
    public function deleteFile(string $fileId): void
    {
        $this->drive->files->delete($fileId);
    }

    public function renameFile(
    string $fileId,
    string $newName
    ): DriveFile {

        $metadata = new DriveFile([
            'name' => $newName,
        ]);

        return $this->drive->files->update(
            $fileId,
            $metadata,
            [
                'fields' => 'id,name,mimeType,modifiedTime',
            ]
        );
    }

    public function shareFile(
    string $fileId,
    string $email
    ): void {

    $permission = new \Google\Service\Drive\Permission([
        'type' => 'user',
        'role' => 'writer',
        'emailAddress' => $email,
    ]);

    $this->drive->permissions->create(
        $fileId,
        $permission,
        [
            'sendNotificationEmail' => true,
        ]
    );
    }
    
}