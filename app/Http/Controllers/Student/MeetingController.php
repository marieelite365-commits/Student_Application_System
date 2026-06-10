<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    // ─── All Meetings ─────────────────────────────────────────
    public function index()
    {
        $user = Auth::user();

        $meetings = Meeting::where(function($q) use ($user) {
                $q->where('for', 'all')
                  ->orWhereHas('participants', function($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            })
            ->where('status', 'scheduled')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('student.meetings.index', compact('meetings'));
    }

    // ─── Single Meeting ───────────────────────────────────────
    public function show($id)
    {
        $user    = Auth::user();
        $meeting = Meeting::findOrFail($id);

        // Check access
        $hasAccess = $meeting->for === 'all' ||
            MeetingParticipant::where('meeting_id', $id)
                ->where('user_id', $user->id)
                ->exists();

        if (!$hasAccess) {
            return redirect()->route('student.meetings.index')
                ->with('error', 'Access denied.');
        }

        return view('student.meetings.show', compact('meeting'));
    }
    public function join($id)
{
    $user    = Auth::user();
    $meeting = Meeting::findOrFail($id);

    // ─── Time Check ───────────────────────────────────────
    $now = now();

    // Meeting start nahi hui yet

if ($now->lt($meeting->start_time)) {
    return back()->with('error',
        'You cannot join this meeting yet. It will start at ' .
        $meeting->start_time->format('h:i A') . ' on ' .
        $meeting->start_time->format('d M Y') . '.'
    );
}
// Meeting end ho gai hai
if ($now->gt($meeting->end_time)) {
    return back()->with('error',
        'You cannot join this meeting. It has already ended at ' .
        $meeting->end_time->format('h:i A') . ' on ' .
        $meeting->end_time->format('d M Y') . '.'
    );
}

    // ─── Access Check ─────────────────────────────────────
    $hasAccess = $meeting->for === 'all' ||
        MeetingParticipant::where('meeting_id', $id)
            ->where('user_id', $user->id)
            ->exists();

    if (!$hasAccess) {
        return back()->with('error', 'You are not invited to this meeting.');
    }

    // ─── Mark Attended ────────────────────────────────────
    if ($meeting->for === 'specific') {
        MeetingParticipant::where('meeting_id', $id)
            ->where('user_id', $user->id)
            ->update([
                'attended'  => true,
                'joined_at' => $now,
            ]);
    }

    // ─── Redirect to Meet ─────────────────────────────────
    return redirect()->away($meeting->meet_link);
    }
}