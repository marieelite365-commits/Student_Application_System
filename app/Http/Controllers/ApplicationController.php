<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Application; 
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Storage;


class ApplicationController extends Controller 
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
    $this->googleDriveService = $googleDriveService;
    }
    // 👇 ADD THIS FUNCTION
    public function index()
    {
        $applications = Application::where('user_id', Auth::id())->get();

        return view('applications.index', compact('applications'));
    }

   public function admin(Request $request)
{
    $query = Application::query();

    // FILTER BY STATUS
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // SEARCH FEATURE
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('username', 'like', '%'.$request->search.'%')
              ->orWhere('cnic', 'like', '%'.$request->search.'%');
        });
    }

    $applications = $query->get();

    return view('admin.index', compact('applications'));
}

public function approve($id)
{
    $application = Application::findOrFail($id);
    $application->status = 'Approved';
    $application->save();

    return back()->with('success', 'Application Approved Successfully');
}

public function reject($id)
{
    $application = Application::findOrFail($id);
    $application->status = 'Rejected';
    $application->save();

    return back()->with('success', 'Application Rejected Successfully');
}

public function myApplication()
{
    $application = Application::where('user_id', auth()->id())->first();

    return view('profile.edit', [
        'application' => $application,
        'user' => auth()->user()
    ]);
}
public function update(Request $request, $id)
{
    $application = Application::findOrFail($id);

    $data = [
        'cnic'    => $request->cnic,
        'degree'  => $request->degree,
        'age'     => $request->age,
        'phone'   => $request->phone,
        'program' => $request->program,
    ];

    // ─── Get or create Drive folder ──────────────────────
    $folderId = $this->googleDriveService->findOrCreateFolder(
        $application->cnic ?? $request->cnic
    );

    // ─── Image Update ─────────────────────────────────────
    if ($request->hasFile('image')) {
        // Local storage
        $image = Storage::disk('public')->putFile('images', $request->file('image'));
        $data['image'] = $image;

        // Drive: old delete + new upload
        if ($application->drive_image) {
            $this->googleDriveService->deleteFile($application->drive_image);
        }

        $driveImage = $this->googleDriveService->uploadFile(
            $request->file('image')->getPathname(),
            $request->file('image')->getClientOriginalName(),
            $request->file('image')->getMimeType(),
            $folderId
        );
        $data['drive_image'] = $driveImage['file_id'];
    }

    // ─── Document Update ──────────────────────────────────
    if ($request->hasFile('document')) {
        // Local storage
        $document = Storage::disk('public')->putFile('documents', $request->file('document'));
        $data['document'] = $document;

        // Drive: old delete + new upload
        if ($application->drive_document) {
            $this->googleDriveService->deleteFile($application->drive_document);
        }

        $driveDocument = $this->googleDriveService->uploadFile(
            $request->file('document')->getPathname(),
            $request->file('document')->getClientOriginalName(),
            $request->file('document')->getMimeType(),
            $folderId
        );
        $data['drive_document'] = $driveDocument['file_id'];
    }

    $application->update($data);

    return back()->with('success', 'Application updated successfully!');
}

public function store(Request $request)
{
    // ✅ 1. VALIDATION (FIRST - IMPORTANT)
    $request->validate([
        'cnic' => 'required',
        'degree' => 'required',
        'age' => 'required',
        'phone' => 'required',
        'program' => 'required',
        'image' => 'required|image',
        'document' => 'required|file',
    ]);

    $user = Auth::user();

    // ✅ 2. CHECK GOOGLE CONNECTED (SAFE)
    if (!$user->google_token) {
        return redirect('/google/redirect')->with('error', 'Please connect Google first');
    }

    // ✅ 3. LOCAL STORAGE (BACKUP - SAFE)
    $image = Storage::disk('public')->putFile('images', $request->file('image'));
    $document = Storage::disk('public')->putFile('documents', $request->file('document'));

    // update profile image (your existing logic)
    $user->profile_photo_path = $image;
    $user->save();

    // ✅ 4. CREATE GOOGLE DRIVE FOLDER (CNIC BASED)
    $folderName = $request->cnic;
    $folderId = $this->googleDriveService->findOrCreateFolder($request->cnic);

    // ✅ 5. UPLOAD FILES TO GOOGLE DRIVE
    $driveImage = $this->googleDriveService->upload(
        $request->file('image')->getPathname(),
        $request->file('image')->getClientOriginalName(),
        $folderId
    );

    $driveDocument = $this->googleDriveService->upload(
        $request->file('document')->getPathname(),
        $request->file('document')->getClientOriginalName(),
        $folderId
    );

    // ✅ 6. SAVE TO DATABASE (NO CHANGE)
    Application::create([
        'user_id' => $user->id,
        'username' => $request->username,
        'cnic' => $request->cnic,
        'degree' => $request->degree,
        'age' => $request->age,
        'phone' => $request->phone,
        'program' => $request->program,
        'image' => $image,
        'document' => $document,
        'drive_image' => $driveImage,
        'drive_document' => $driveDocument,
        'status' => 'Pending',
    ]);

    return redirect()->route('dashboard')->with('success', 'Application submitted successfully');
}
}
