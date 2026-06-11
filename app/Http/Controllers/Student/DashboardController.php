<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassroomCourse;

class DashboardController extends Controller
{
    public function index()
    {
        $user         = Auth::user();
        $profile      = $user->studentProfile;
        $applications = $user->applications()->latest()->get();

        $stats = [
            'total'        => $applications->count(),
            'draft'        => $applications->where('status', 'draft')->count(),
            'submitted'    => $applications->where('status', 'submitted')->count(),
            'under_review' => $applications->where('status', 'under_review')->count(),
            'approved'     => $applications->where('status', 'approved')->count(),
            'rejected'     => $applications->where('status', 'rejected')->count(),
        ];

        // ─── Upcoming Meetings ────────────────────────────────
        $upcomingMeetings = Meeting::where(function($q) use ($user) {
                $q->where('for', 'all')
                  ->orWhereHas('participants', function($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            })
            ->where('status', 'scheduled')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();

        // ─── Past Meetings ────────────────────────────────────
        $pastMeetings = Meeting::where(function($q) use ($user) {
                $q->where('for', 'all')
                  ->orWhereHas('participants', function($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            })
            ->where('end_time', '<', now())
            ->orderBy('start_time', 'desc')
            ->take(3)
            ->get();

        // ─── Notifications ────────────────────────────────────
        $notifications = NotificationLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = NotificationLog::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // ─── Classroom Courses ────────────────────────────────
          $courses = ClassroomCourse::where('status', '!=', 'archived')
        ->orWhereNull('status')
        ->latest()
        ->get(); 
        
        // ─── Entry Test ───────────────────────────────────────────
        $entryTest = \App\Models\EntryTest::where('student_id', $user->id)
       ->latest()
       ->first();

        return view('student.dashboard', compact(
            'user', 'profile', 'applications', 'stats',
            'upcomingMeetings', 'pastMeetings',
            'notifications', 'unreadCount',
            'courses', 'entryTest'

        ));
        
    }

    public function markAllRead()
    {
        NotificationLog::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }
}