<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassroomCourse;
use App\Services\GoogleClassroomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    // ─── List All Courses ─────────────────────────────────────────
    public function index()
    {
        $courses = ClassroomCourse::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.classroom.index', compact('courses'));
    }

    // ─── Create Form ──────────────────────────────────────────────
    public function create()
    {
        return view('admin.classroom.create');
    }

    // ─── Store Course ─────────────────────────────────────────────
    public function store(Request $request)
    {
    $request->validate([
        'name'            => 'required|string|max:255',
        'section'         => 'nullable|string|max:255',
        'description'     => 'nullable|string',
        'alternate_link'  => 'required|url',
        'enrollment_code' => 'nullable|string|max:50',
    ]);

    ClassroomCourse::create([
        'created_by'      => Auth::id(),
        'name'            => $request->name,
        'section'         => $request->section,
        'description'     => $request->description,
        'classroom_id'    => 'manual-' . time(),
        'enrollment_code' => $request->enrollment_code,
        'alternate_link'  => $request->alternate_link,
    ]);

    return redirect()->route('admin.admin.classroom.index')
        ->with('success', 'Course added successfully!');
    }
    // ─── Show Course ──────────────────────────────────────────────
    public function show($id)
    {
        $course = ClassroomCourse::findOrFail($id);
        return view('admin.classroom.show', compact('course'));
    }

    // ─── Post Announcement ────────────────────────────────────────
    public function announce(Request $request, $id)
    {
        $request->validate([
            'announcement' => 'required|string',
        ]);

        $course    = ClassroomCourse::findOrFail($id);
        $classroom = new GoogleClassroomService();

        $success = $classroom->postAnnouncement(
            $course->classroom_id,
            $request->announcement
        );

        if ($success) {
            return back()->with('success', 'Announcement posted successfully!');
        }

        return back()->with('error', 'Failed to post announcement.');
    }

    // ─── Enroll Student ───────────────────────────────────────────
    public function enroll(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $course    = ClassroomCourse::findOrFail($id);
        $classroom = new GoogleClassroomService();

        $success = $classroom->enrollStudent(
            $course->classroom_id,
            $request->email
        );

        if ($success) {
            return back()->with('success', 'Student enrolled successfully!');
        }

        return back()->with('error', 'Failed to enroll student.');
    }

    // ─── Archive Course ───────────────────────────────────────────
    public function archive($id)
    {
        $course    = ClassroomCourse::findOrFail($id);
        $classroom = new GoogleClassroomService();

        $success = $classroom->archiveCourse($course->classroom_id);

        if ($success) {
            $course->update(['status' => 'archived']);
            return back()->with('success', 'Course archived successfully!');
        }

        return back()->with('error', 'Failed to archive course.');
    }
}