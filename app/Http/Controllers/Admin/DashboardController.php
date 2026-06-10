<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Meeting;
use App\Models\NotificationLog;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'visitors'       => User::where('role', 'student')
                                    ->whereDoesntHave('applications')
                                    ->count(),
            'candidates'     => User::where('role', 'student')
                                    ->whereHas('applications')
                                    ->count(),
            'total_students' => User::where('role', 'student')->count(),
            'enrolled'       => Application::where('status', 'enrolled')->count(),
            'total_apps'     => Application::count(),
            'draft'          => Application::where('status', 'draft')->count(),
            'submitted'      => Application::where('status', 'submitted')->count(),
            'pending'        => Application::where('status', 'pending')->count(),
            'under_review'   => Application::where('status', 'under_review')->count(),
            'approved'       => Application::where('status', 'approved')->count(),
            'rejected'       => Application::where('status', 'rejected')->count(),
        ];

        $recentApplications = Application::with(['user'])
            ->latest()
            ->take(10)
            ->get();

        $recentStudents = User::where('role', 'student')
            ->whereHas('applications', fn($q) => $q->where('status', 'enrolled'))
            ->latest()
            ->take(5)
            ->get();

        $newCandidatesCount = Application::where('status', 'submitted')
            ->whereDate('created_at', '>=', now()->subDays(3))
            ->count();

        // ─── Upcoming Meetings ────────────────────────────────
        $upcomingMeetings = Meeting::with('creator')
            ->where('status', 'scheduled')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        // ─── Past Meetings with Attendance ───────────────────
        $pastMeetings = Meeting::with(['creator', 'participants'])
            ->where('end_time', '<', now())
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();

        // ─── Admin Notifications ──────────────────────────────
        $adminUser = auth()->user();
        $notifications = NotificationLog::where('user_id', $adminUser->id)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = NotificationLog::where('user_id', $adminUser->id)
            ->where('is_read', false)
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'recentApplications',
            'recentStudents',
            'newCandidatesCount',
            'upcomingMeetings',
            'pastMeetings',
            'notifications',
            'unreadCount'
        ));
    }

    // ─── Mark All Notifications Read ─────────────────────────
    public function markAllRead()
    {
        NotificationLog::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }
}