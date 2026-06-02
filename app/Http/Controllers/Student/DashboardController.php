<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user        = Auth::user();
        $profile     = $user->studentProfile;
        $applications = $user->applications()
            ->latest()
            ->get();

        $stats = [
            'total'       => $applications->count(),
            'draft'       => $applications->where('status', 'draft')->count(),
            'submitted'   => $applications->where('status', 'submitted')->count(),
            'under_review'=> $applications->where('status', 'under_review')->count(),
            'approved'    => $applications->where('status', 'approved')->count(),
            'rejected'    => $applications->where('status', 'rejected')->count(),
        ];

        return view('student.dashboard', compact('user', 'profile', 'applications', 'stats'));
    }
}