<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Services\GoogleDriveService;

class ApplicationController extends Controller
{
    protected $drive;

    public function __construct(GoogleDriveService $drive)
    {
        $this->drive = $drive;
    }

    // ─── Form Show ────────────────────────────────────────────
    public function create()
    {
        return view('student.application.create');
    }

    // ─── Form Submit ──────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'degree_program'     => 'required|string',
            'department'         => 'required|string',
            'semester'           => 'required|in:Fall,Spring,Summer',
            'admission_year'     => 'required|digits:4',
            'last_qualification' => 'required|string',
            'last_institution'   => 'required|string',
            'last_cgpa'          => 'nullable|numeric|min:0|max:4',
            'last_percentage'    => 'nullable|numeric|min:0|max:100',
            'passing_year'       => 'required|digits:4',
            'profile_photo'      => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'cnic_front'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'cnic_back'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'matric_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'other_document'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user    = Auth::user();
        $profile = $user->studentProfile;

        DB::beginTransaction();

        try {
            // ─── Step 1: Student Drive Folder ─────────────────
            $studentFolderId = $profile->drive_folder_id;

            if (!$studentFolderId) {
                // GoogleDriveService ki existing method use karo
                $studentFolderId = $this->drive->getStudentFolder(
                    $user->id,
                    $user->name
                );
                $profile->update(['drive_folder_id' => $studentFolderId]);
            }

            // ─── Step 2: Application Sub-Folder ───────────────
            $appFolderName = 'Application_' . now()->format('Y_m_d_His');
            $appFolderId   = $this->drive->findOrCreateFolder(
                $appFolderName,
                $studentFolderId
            );

            // ─── Step 3: Application Database Save ────────────
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
                'status'             => 'submitted',
                'submitted_at'       => now(),
                'drive_folder_id'    => $appFolderId,
            ]);

            // ─── Step 4: Documents Upload ──────────────────────
            $documents = [
                'profile_photo'      => 'profile_photo',
                'cnic_front'         => 'cnic_front',
                'cnic_back'          => 'cnic_back',
                'matric_certificate' => 'matric_certificate',
                'other_document'     => 'other',
            ];
            // DEBUG - temporarily add karo
            \Log::info('Files received: ', $request->allFiles());

            foreach ($documents as $fieldName => $docType) {
                if ($request->hasFile($fieldName)) {
                    $file      = $request->file($fieldName);
                    $fileName  = $docType . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $localPath = $file->storeAs('applications', $fileName, 'local');
                    $fullPath  = storage_path('app/' . $localPath);

                    // Google Drive pe upload
                    $driveResult = $this->drive->uploadFile(
                        $fullPath,
                        $fileName,
                        $file->getMimeType(),
                        $appFolderId
                    );

                    // Database mein save
                    ApplicationDocument::create([
                        'application_id'    => $application->id,
                        'document_type'     => $docType,
                        'original_filename' => $file->getClientOriginalName(),
                        'stored_filename'   => $fileName,
                        'file_path'         => $localPath,
                        'mime_type'         => $file->getMimeType(),
                        'file_size'         => $file->getSize(),
                        'drive_file_id'     => $driveResult['file_id'],
                        'drive_file_url'    => $driveResult['file_url'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('student.dashboard')
                ->with('success', 'Application submitted! Documents saved to Google Drive.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}