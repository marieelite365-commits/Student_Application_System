<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InterviewSchedule;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    // ─── List Interviews ──────────────────────────────────────
    public function index()
    {
        $interviews = InterviewSchedule::where('student_id', Auth::id())
            ->with(['application'])
            ->latest()
            ->get();

        return view('student.interviews.index', compact('interviews'));
    }

    // ─── Show Interview ───────────────────────────────────────
    public function show(InterviewSchedule $interview)
    {
        if ($interview->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $interview->load(['application']);

        return view('student.interviews.show', compact('interview'));
    }
}