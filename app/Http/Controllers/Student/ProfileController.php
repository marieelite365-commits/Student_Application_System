<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ─── Show Profile ─────────────────────────────────────────

    public function show()
    {
        $user    = Auth::user();
        $profile = $user->studentProfile;

        return view('student.profile.show', compact('user', 'profile'));
    }

    // ─── Create Profile ───────────────────────────────────────

    public function create()
    {
        $user = Auth::user();

        if ($user->studentProfile) {
            return redirect()->route('student.profile.show')
                ->with('info', 'Profile already exists.');
        }

        return view('student.profile.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cnic'          => 'required|string|size:13|unique:student_profiles,cnic',
            'phone'         => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender'        => 'required|in:male,female,other',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'province'      => 'required|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Profile photo upload (local)
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')
                ->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);
        }

        // Generate student ID
        $studentId = StudentProfile::generateStudentId();

        // Create profile in DB
        $profile = StudentProfile::create([
            'user_id'       => $user->id,
            'student_id'    => $studentId,
            'cnic'          => $request->cnic,
            'phone'         => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender'        => $request->gender,
            'address'       => $request->address,
            'city'          => $request->city,
            'province'      => $request->province,
        ]);

        // ─── Google Drive: Student folder banao aur Photo upload karo ──────────────
        try {
            $drive      = new GoogleDriveService();
            $folderId   = $drive->getStudentFolder($user->id, $user->name);

            // Photo upload karo Drive pe agar upload ki gayi hai
            if (isset($path) && $request->hasFile('profile_photo')) {
                $drive->uploadFile(
                    Storage::disk('public')->path($path),
                    'profile-photo-' . $profile->student_id . '.jpg',
                    $request->file('profile_photo')->getMimeType(),
                    $folderId
                );
            }
        } catch (\Exception $e) {
            Log::error('Drive folder creation/photo upload failed: ' . $e->getMessage());
            // Drive fail ho to bhi profile save rahegi
        }

        return redirect()->route('student.dashboard')
            ->with('success', 'Profile created! Your Student ID is: ' . $studentId);
    }

    // ─── Edit Profile ─────────────────────────────────────────

    public function edit()
    {
        $user    = Auth::user();
        $profile = $user->studentProfile;

        if (!$profile) {
            return redirect()->route('student.profile.create');
        }

        return view('student.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->studentProfile;

        $request->validate([
            'phone'         => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender'        => 'required|in:male,female,other',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'province'      => 'required|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Profile photo update (local)
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')
                ->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);

            // ─── Google Drive: Photo upload karo ─────────────
            try {
                $drive    = new GoogleDriveService();

                // Student ka folder lo ya banao
                $folderId = $profile->drive_folder_id
                    ?? $drive->getStudentFolder($user->id, $user->name);

                // Photo upload karo Drive pe
                $drive->uploadFile(
                    Storage::disk('public')->path($path),
                    'profile-photo-' . $profile->student_id . '.jpg',
                    $request->file('profile_photo')->getMimeType(),
                    $folderId
                );

            } catch (\Exception $e) {
                Log::error('Drive photo upload failed: ' . $e->getMessage());
                // Drive fail ho to bhi profile update rahegi
            }
        }

        // DB update
        $profile->update([
            'phone'         => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender'        => $request->gender,
            'address'       => $request->address,
            'city'          => $request->city,
            'province'      => $request->province,
        ]);

        return redirect()->route('student.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}