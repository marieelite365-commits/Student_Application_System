<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApplicationController extends Controller
{
    use AuthorizesRequests;
    protected GoogleDriveService $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    // ─── List Applications ────────────────────────────────────

    public function index()
    {
        $applications = Auth::user()->applications()->latest()->get();
        return view('student.applications.index', compact('applications'));
    }

    // ─── Create Application ───────────────────────────────────

    public function create()
    {
        $user    = Auth::user();
        $profile = $user->studentProfile;

        if (!$profile) {
            return redirect()->route('student.profile.create')
                ->with('warning', 'Please complete your profile first.');
        }

        return view('student.applications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'degree_program'     => 'required|string|max:100',
            'department'         => 'required|string|max:100',
            'semester'           => 'required|in:Fall,Spring,Summer',
            'admission_year'     => 'required|digits:4|integer|min:2020|max:2030',
            'last_qualification' => 'required|string|max:100',
            'last_institution'   => 'required|string|max:200',
            'last_cgpa'          => 'nullable|numeric|min:0|max:4',
            'last_percentage'    => 'nullable|numeric|min:0|max:100',
            'passing_year'       => 'required|digits:4|integer|min:2000|max:2030',

            // Documents
            'profile_photo'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cnic_front'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cnic_back'           => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'matric_certificate'  => 'required|mimes:jpeg,png,jpg,pdf|max:5120',
            'matric_marksheet'    => 'required|mimes:jpeg,png,jpg,pdf|max:5120',
            'inter_certificate'   => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'inter_marksheet'     => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'domicile'            => 'required|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $user    = Auth::user();
        $profile = $user->studentProfile;

        // ─── Create Application ───────────────────────────
        $application = Application::create([
            'user_id'            => $user->id,
            'student_profile_id' => $profile->id,
            'degree_program'     => $request->degree_program,
            'department'         => $request->department,
            'semester'           => $request->semester,
            'admission_year'     => $request->admission_year,
            'last_qualification' => $request->last_qualification,
            'last_institution'   => $request->last_institution,
            'last_cgpa'          => $request->last_cgpa,
            'last_percentage'    => $request->last_percentage,
            'passing_year'       => $request->passing_year,
            'status'             => 'draft',
        ]);

        // ─── Upload Documents ─────────────────────────────
        $documentFields = [
            'profile_photo', 'cnic_front', 'cnic_back',
            'matric_certificate', 'matric_marksheet',
            'inter_certificate', 'inter_marksheet', 'domicile',
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file     = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs('applications/' . $application->id, $filename, 'local');

                $driveFileId  = null;
                $driveFileUrl = null;

                if ($application->drive_folder_id) {
                    try {
                        $driveResult  = $this->driveService->uploadFile(
                            storage_path('app/' . $path),
                            $field . '_' . $filename,
                            $file->getMimeType(),
                            $application->drive_folder_id
                        );
                        $driveFileId  = $driveResult['file_id'];
                        $driveFileUrl = $driveResult['file_url'];
                    } catch (\Exception $e) {
                        \Log::error('Drive upload failed for ' . $field . ': ' . $e->getMessage());
                    }
                }

                ApplicationDocument::create([
                    'application_id'    => $application->id,
                    'document_type'     => $field,
                    'original_filename' => $file->getClientOriginalName(),
                    'stored_filename'   => $filename,
                    'file_path'         => $path,
                    'mime_type'         => $file->getMimeType(),
                    'file_size'         => $file->getSize(),
                    'drive_file_id'     => $driveFileId,
                    'drive_file_url'    => $driveFileUrl,
                ]);
            }
        }

        // ─── Google Drive Upload (Queue mein) ─────────────
        // dispatch(new UploadApplicationToDrive($application));

        // ─── Google Drive: User ka folder find ya create karo ───
        try {
            $userFolderName = 'Student_' . $user->id . '_' . str_replace(' ', '_', $user->name);
            $userFolderId   = $this->driveService->findOrCreateFolder($userFolderName);

            $application->update(['drive_folder_id' => $userFolderId]);

            $infoText  = "APPLICATION DETAILS\n";
            $infoText .= "===================\n";
            $infoText .= "Application ID  : {$application->id}\n";
            $infoText .= "Student Name    : {$user->name}\n";
            $infoText .= "Email           : {$user->email}\n";
            $infoText .= "Degree Program  : {$application->degree_program}\n";
            $infoText .= "Department      : {$application->department}\n";
            $infoText .= "Semester        : {$application->semester} {$application->admission_year}\n";
            $infoText .= "Qualification   : {$application->last_qualification}\n";
            $infoText .= "Institution     : {$application->last_institution}\n";
            $infoText .= "Percentage      : {$application->last_percentage}%\n";
            $infoText .= "Passing Year    : {$application->passing_year}\n";
            $infoText .= "Status          : {$application->status}\n";
            $infoText .= "Created At      : {$application->created_at}\n";

            $tempPath = storage_path('app/temp_app_' . $application->id . '.txt');
            file_put_contents($tempPath, $infoText);
            $this->driveService->uploadFile($tempPath, 'application_info.txt', 'text/plain', $userFolderId);
            @unlink($tempPath);

        } catch (\Exception $e) {
            \Log::error('Drive folder creation failed: ' . $e->getMessage());
        }

        return redirect()->route('student.applications.show', $application->id)
            ->with('success', 'Application saved as draft!');
    }

    // ─── Show Application ─────────────────────────────────────

    public function show(Application $application)
    {
    if ($application->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $application->load('documents', 'studentProfile');

    return view('student.applications.show', compact('application'));
    }

    // ─── Submit Application ───────────────────────────────────

    public function submit(Application $application)
    {
    if ($application->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    if (!$application->isDraft()) {
        return back()->with('error', 'Application already submitted.');
    }

    $application->update([
        'status'       => 'submitted',
        'submitted_at' => now(),
    ]);

    return redirect()->route('student.applications.show', $application->id)
        ->with('success', 'Application submitted successfully!');
    }
}