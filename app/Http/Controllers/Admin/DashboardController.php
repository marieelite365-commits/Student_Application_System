<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use App\Models\StudentProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'  => User::where('role', 'student')->count(),
            'total_apps'      => Application::count(),
            'draft'           => Application::where('status', 'draft')->count(),
            'submitted'       => Application::where('status', 'submitted')->count(),
            'under_review'    => Application::where('status', 'under_review')->count(),
            'approved'        => Application::where('status', 'approved')->count(),
            'rejected'        => Application::where('status', 'rejected')->count(),
            'enrolled'        => Application::where('status', 'enrolled')->count(),
        ];

        $recentApplications = Application::with(['user', 'studentProfile'])
            ->latest()
            ->take(10)
            ->get();

        $recentStudents = User::where('role', 'student')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentApplications', 'recentStudents'));
    }
}
