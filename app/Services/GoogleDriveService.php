<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected ?Client $client = null;
    protected ?Drive $driveService = null;

    public function __construct() {}

    private function getClient(): Client
    {
        if (null === $this->client) {
            $this->client = new Client();
            $this->client->setAuthConfig(
                base_path(env('GOOGLE_APPLICATION_CREDENTIALS'))
            );
            $this->client->addScope(Drive::DRIVE);
        }
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
        $folderName = 'Student_' . $userId . '_' . str_replace(' ', '_', $userName);
        return $this->findOrCreateFolder($folderName);
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