<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // ─── List All Applications ────────────────────────────────

    public function index(Request $request)
    {
        $query = Application::with(['user', 'studentProfile', 'documents']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by student name or email
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->latest()->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    // ─── Show Single Application ──────────────────────────────

    public function show(Application $application)
    {
        $application->load(['user', 'studentProfile', 'documents']);
        return view('admin.applications.show', compact('application'));
    }

    // ─── Update Status ────────────────────────────────────────

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:under_review,approved,rejected,enrolled',
            'admin_remarks' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status'        => $request->status,
            'admin_remarks' => $request->admin_remarks,
            'reviewed_at'   => now(),
        ]);

        return back()->with('success', 'Application status updated to: ' . ucfirst($request->status));
    }

    // ─── List All Students ────────────────────────────────────

    public function students()
    {
        $students = User::where('role', 'student')
            ->with('studentProfile')
            ->latest()
            ->paginate(15);

        return view('admin.students.index', compact('students'));
    }

    // ─── Show Single Student ──────────────────────────────────

    public function showStudent(User $user)
    {
        $user->load(['studentProfile', 'applications.documents']);
        return view('admin.students.show', compact('user'));
    }
}