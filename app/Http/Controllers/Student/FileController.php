use App\Jobs\UploadToGoogleDrive;

public function upload(Request $request, GoogleDriveService $drive)
{
    $request->validate([
        'file' => 'required|file|max:10240'
    ]);

    $user = auth()->user();

    $folderId = $drive->getStudentFolder($user->id, $user->name);

    $file = $request->file('file');

    // Move file temporarily
    $path = $file->store('temp');

    UploadToGoogleDrive::dispatch(
        storage_path('app/' . $path),
        $file->getClientOriginalName(),
        $file->getMimeType(),
        $folderId,
        $user->id
    );

    return response()->json([
        'message' => 'Upload started. File will be processed in background.'
    ]);
}