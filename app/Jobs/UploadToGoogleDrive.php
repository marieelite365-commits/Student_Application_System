namespace App\Jobs;

use App\Services\GoogleDriveService;
use App\Models\StudentFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadToGoogleDrive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;
    public $fileName;
    public $mimeType;
    public $folderId;
    public $userId;

    public function __construct($filePath, $fileName, $mimeType, $folderId, $userId)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
        $this->folderId = $folderId;
        $this->userId = $userId;
    }

    public function handle(GoogleDriveService $drive)
    {
        $result = $drive->uploadFile(
            $this->filePath,
            $this->fileName,
            $this->mimeType,
            $this->folderId
        );

        StudentFile::create([
            'user_id'       => $this->userId,
            'file_name'     => $this->fileName,
            'drive_file_id' => $result['file_id'],
            'drive_url'     => $result['file_url'],
            'folder_id'     => $this->folderId,
        ]);
    }
}