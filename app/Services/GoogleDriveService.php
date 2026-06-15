<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

use App\Models\GoogleDriveToken;
use App\Models\User;
use Carbon\Carbon;

class GoogleDriveService
{
    protected ?Client $client = null;
    protected ?Drive $driveService = null;

    public function __construct() {}

    private function getClient(): Client
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $this->client = new Client();

        // 1. Try to load Admin OAuth Token (to use Admin's personal storage quota)
        $adminIds = User::where('role', 'admin')->pluck('id');
        $tokenRecord = GoogleDriveToken::whereIn('user_id', $adminIds)->latest()->first();

        if ($tokenRecord) {
            $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
            $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $this->client->setAccessType('offline');

            $tokenArray = [
                'access_token'  => $tokenRecord->access_token,
                'refresh_token' => $tokenRecord->refresh_token,
                'token_type'    => $tokenRecord->token_type,
                'expires_at'    => $tokenRecord->expires_at?->timestamp,
            ];

            $this->client->setAccessToken($tokenArray);

            // Refresh token if expired
            if ($this->client->isAccessTokenExpired()) {
                if ($tokenRecord->refresh_token) {
                    try {
                        $newToken = $this->client->fetchAccessTokenWithRefreshToken(
                            $tokenRecord->refresh_token
                        );
                        if (!isset($newToken['error'])) {
                            $tokenRecord->update([
                                'access_token' => $newToken['access_token'],
                                'expires_at'   => Carbon::now()->addSeconds($newToken['expires_in'] ?? 3600),
                            ]);
                            $this->client->setAccessToken($newToken);
                        } else {
                            Log::error('Google Drive token refresh failed: ' . $newToken['error']);
                        }
                    } catch (\Exception $ex) {
                        Log::error('Google Drive token refresh exception: ' . $ex->getMessage());
                    }
                }
            }

            if (!$this->client->isAccessTokenExpired()) {
                $this->client->addScope(Drive::DRIVE);
                return $this->client;
            }
        }

        // 2. Fallback to Service Account Credentials
        $this->client->setAuthConfig(
            base_path(env('GOOGLE_APPLICATION_CREDENTIALS'))
        );
        $this->client->addScope(Drive::DRIVE);

        return $this->client;
    }

    private function getDriveService(): Drive
    {
        if (null === $this->driveService) {
            $this->driveService = new Drive($this->getClient());
        }
        return $this->driveService;
    }

    // ─── Folder: Find ya Create ───────────────────────────────
    public function findOrCreateFolder(string $folderName, ?string $parentId = null): string
{
    // Extract ID if a full Google Drive URL is provided
    if ($parentId !== null && str_contains($parentId, 'drive.google.com')) {
        if (preg_match('/folders\/([a-zA-Z0-9-_]+)/', $parentId, $matches)) {
            $parentId = $matches[1];
        } elseif (preg_match('/id=([a-zA-Z0-9-_]+)/', $parentId, $matches)) {
            $parentId = $matches[1];
        }
    }

    // Agar parent null hai toh root mein banao
    if ($parentId === null || $parentId === '') {
        $fileMetadata = new DriveFile([
            'name'     => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        $folder = $this->getDriveService()->files->create($fileMetadata, [
            'fields'            => 'id',
            'supportsAllDrives' => true,
        ]);

        return $folder->getId();
    }

    $query = "name='{$folderName}' "
           . "and mimeType='application/vnd.google-apps.folder' "
           . "and trashed=false "
           . "and '{$parentId}' in parents";

    $results = $this->getDriveService()->files->listFiles([
        'q'                         => $query,
        'fields'                    => 'files(id, name)',
        'supportsAllDrives'         => true,
        'includeItemsFromAllDrives' => true,
    ]);

    if (count($results->getFiles()) > 0) {
        return $results->getFiles()[0]->getId();
    }

    $fileMetadata = new DriveFile([
        'name'     => $folderName,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents'  => [$parentId],
    ]);

    $folder = $this->getDriveService()->files->create($fileMetadata, [
        'fields'            => 'id',
        'supportsAllDrives' => true,
    ]);

    return $folder->getId();
}

    // ─── File Upload ──────────────────────────────────────────
    public function uploadFile(
        string $localPath,
        string $fileName,
        string $mimeType,
        string $parentFolderId
    ): array {
        $fileMetadata = new DriveFile([
            'name'    => $fileName,
            'parents' => [$parentFolderId],
        ]);

        $content = file_get_contents($localPath);

        $file = $this->getDriveService()->files->create($fileMetadata, [
            'data'              => $content,
            'mimeType'          => $mimeType,
            'uploadType'        => 'multipart',
            'fields'            => 'id, webViewLink',
            'supportsAllDrives' => true,
        ]);

        $this->makeFilePublic($file->getId());

        return [
            'file_id'  => $file->getId(),
            'file_url' => $file->getWebViewLink(),
        ];
    }

    // ─── Text File Upload (Direct Content) ───────────────────
    public function uploadTextContent(
        string $content,
        string $fileName,
        string $parentFolderId
    ): array {
        $tempPath = storage_path('app/temp_' . uniqid() . '.txt');
        file_put_contents($tempPath, $content);

        $result = $this->uploadFile($tempPath, $fileName, 'text/plain', $parentFolderId);

        @unlink($tempPath);

        return $result;
    }

    // ─── File Delete ──────────────────────────────────────────
    public function deleteFile(string $fileId): void
    {
        try {
            $this->getDriveService()->files->delete($fileId, [
                'supportsAllDrives' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Drive delete failed: ' . $e->getMessage());
        }
    }

    // ─── Make Public ──────────────────────────────────────────
    public function makeFilePublic(string $fileId): void
    {
        try {
            $permission = new \Google\Service\Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->getDriveService()->permissions->create($fileId, $permission, [
                'supportsAllDrives' => true,
            ]);
        } catch (\Exception $e) {
            Log::warning('Make public failed: ' . $e->getMessage());
        }
    }

    // ─── Get or Create Student Folder ────────────────────────
    public function getStudentFolder(int $userId, string $userName): string
    {
        $parentFolderId = env('GOOGLE_DRIVE_FOLDER_ID'); // LLU folder ID
        
        // Fetch user and force reload studentProfile to avoid cached null relation
        $user = User::find($userId);
        $profile = $user ? $user->studentProfile()->first() : null;

        if ($profile && $profile->drive_folder_id) {
            return $profile->drive_folder_id;
        }

        // Determine the student's ID (e.g. STU-2026-0001)
        $studentId = null;
        if ($profile && $profile->student_id) {
            $studentId = $profile->student_id;
        } else {
            // Fallback to generating it from userId
            $studentId = 'STU-' . date('Y') . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT);
        }

        $folderName = $studentId . ' - ' . $userName;
        $folderId = $this->findOrCreateFolder($folderName, $parentFolderId);

        // Save to profile if not saved
        if ($profile && !$profile->drive_folder_id) {
            $profile->update(['drive_folder_id' => $folderId]);
        }

        return $folderId;
    }

    public function shareFolder(string $folderId, string $email): void
{
    try {
        $permission = new \Google\Service\Drive\Permission([
            'type'         => 'user',
            'role'         => 'writer',
            'emailAddress' => $email,
        ]);

        $this->getDriveService()->permissions->create($folderId, $permission, [
            'supportsAllDrives'         => true,
            'sendNotificationEmail'     => false,
        ]);
    } catch (\Exception $e) {
        Log::error('Share folder failed: ' . $e->getMessage());
    }
    }
}