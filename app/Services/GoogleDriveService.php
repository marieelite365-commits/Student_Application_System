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

    // ✅ BILKUL KHALI CONSTRUCTOR
    public function __construct()
    {
        // kuch nahi
    }

    // ✅ Client sirf tab bane jab zarurat ho
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

    // ✅ Drive service sirf tab bane jab zarurat ho
    private function getDriveService(): Drive
    {
        if (null === $this->driveService) {
            $this->driveService = new Drive($this->getClient());
        }
        return $this->driveService;
    }

    // ─── Folder: Find ya Create ──────────────────────────────

    public function findOrCreateFolder(string $folderName, ?string $parentId = null): string
    {
        $parentId = $parentId ?? env('GOOGLE_DRIVE_FOLDER_ID');

        $query = "name='{$folderName}' "
               . "and mimeType='application/vnd.google-apps.folder' "
               . "and trashed=false "
               . "and '{$parentId}' in parents";

        $results = $this->getDriveService()->files->listFiles([
            'q'      => $query,
            'fields' => 'files(id, name)',
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
            'fields' => 'id',
        ]);

        return $folder->getId();
    }

    // ─── File Upload ─────────────────────────────────────────

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
            'data'       => $content,
            'mimeType'   => $mimeType,
            'uploadType' => 'multipart',
            'fields'     => 'id, webViewLink',
        ]);

        $this->makeFilePublic($file->getId());

        return [
            'file_id'  => $file->getId(),
            'file_url' => $file->getWebViewLink(),
        ];
    }

    // ─── File Delete ─────────────────────────────────────────

    public function deleteFile(string $fileId): void
    {
        try {
            $this->getDriveService()->files->delete($fileId);
        } catch (\Exception $e) {
            Log::error('Drive delete failed: ' . $e->getMessage());
        }
    }

    // ─── Make Public ─────────────────────────────────────────

    public function makeFilePublic(string $fileId): void
    {
        try {
            $permission = new \Google\Service\Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->getDriveService()->permissions->create($fileId, $permission);
        } catch (\Exception $e) {
            Log::warning('Make public failed: ' . $e->getMessage());
        }
    }
}