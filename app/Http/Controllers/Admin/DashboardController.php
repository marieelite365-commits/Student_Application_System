<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // ── People Tiers ──────────────────────────────────────────
            // Visitors: registered users who have NOT submitted any application yet
            'visitors'        => User::where('role', 'student')
                                    ->whereDoesntHave('applications')
                                    ->count(),

            // Candidates: users who have AT LEAST one application submitted
            'candidates'      => User::where('role', 'student')
                                    ->whereHas('applications')
                                    ->count(),

            // All registered student-role users
            'total_students'  => User::where('role', 'student')->count(),

            // Finally enrolled
            'enrolled'        => Application::where('status', 'enrolled')->count(),

            // ── Application Statuses ──────────────────────────────────
            'total_apps'      => Application::count(),
            'draft'           => Application::where('status', 'draft')->count(),

            // Submitted = just submitted, admin hasn't contacted yet
            'submitted'       => Application::where('status', 'submitted')->count(),

            // Pending = admin contacted the candidate, awaiting their response
            'pending'         => Application::where('status', 'pending')->count(),

            'under_review'    => Application::where('status', 'under_review')->count(),
            'approved'        => Application::where('status', 'approved')->count(),
            'rejected'        => Application::where('status', 'rejected')->count(),
        ];

        $recentApplications = Application::with(['user'])
            ->latest()
            ->take(10)
            ->get();

        // Recent Students = those who are enrolled
        $recentStudents = User::where('role', 'student')
            ->whereHas('applications', fn($q) => $q->where('status', 'enrolled'))
            ->latest()
            ->take(5)
            ->get();

        // New candidates in last 3 days (for sidebar badge)
        $newCandidatesCount = Application::where('status', 'submitted')
            ->whereDate('created_at', '>=', now()->subDays(3))
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'recentApplications',
            'recentStudents',
            'newCandidatesCount'
        ));
    }
}