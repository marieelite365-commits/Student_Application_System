<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleCalendarService;


class MeetingController extends Controller
{
    // ─── List All Meetings ───────────────────────────────────
    public function index()
    {
        $meetings = Meeting::with('creator')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('admin.meetings.index', compact('meetings'));
    }

    // ─── Create Form ─────────────────────────────────────────
    public function create()
    {
        $students = User::where('role', 'student')->get();
        return view('admin.meetings.create', compact('students'));
    }

    // ─── Store Meeting ───────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time'  => 'required|date|after:now',
            'end_time'    => 'required|date|after:start_time',
            'type'        => 'required|in:interview,orientation,class,general',
            'for'         => 'required|in:all,specific',
            'students'    => 'required_if:for,specific|array',
            'meet_link'   => 'nullable|url',
        ]);

        // ─── Google Calendar se Meet link generate karo ───────────
$meetLink      = null;
$calendarEventId = null;

try {
    $calendar = new GoogleCalendarService();
    $result   = $calendar->createEvent(
        $request->title,
        $request->description ?? '',
        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::parse($request->start_time)->format('Y-m-d H:i:s'), 'Asia/Karachi')->toRfc3339String(),
        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::parse($request->end_time)->format('Y-m-d H:i:s'), 'Asia/Karachi')->toRfc3339String(),
    );
    if ($result) {
        $meetLink        = $result['meet_link'];
        $calendarEventId = $result['event_id'];
    }
} catch (\Exception $e) {
    \Log::error('Calendar create failed: ' . $e->getMessage());
}

    // Fallback — agar Calendar fail ho toh manual link use karo
    if (!$meetLink) {
    $meetLink = $request->meet_link;
    }

    $meeting = Meeting::create([
    'created_by'      => Auth::id(),
    'title'           => $request->title,
    'description'     => $request->description,
    'meet_link'       => $meetLink,
    'google_event_id' => $calendarEventId,
    'start_time'      => $request->start_time,
    'end_time'        => $request->end_time,
    'type'            => $request->type,
    'status'          => 'scheduled',
    'for'             => $request->for,
    ]);

        // ─── Participants + Notifications ────────────────────
        if ($request->for === 'all') {
            $students = User::where('role', 'student')->get();
        } else {
            $students = User::whereIn('id', $request->students)->get();
        }

        foreach ($students as $student) {
            // Participant add karo
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'user_id'    => $student->id,
                'notified'   => true,
            ]);

            // Notification send karo
            NotificationLog::send(
                $student->id,
                'New Meeting Scheduled',
                'You have a new meeting: ' . $meeting->title . ' on ' . $meeting->start_time->format('d M Y, h:i A'),
                'info',
                route('student.meetings.show', $meeting->id)
            );
        }
        

        return redirect()->route('admin.meetings.index')
            ->with('success', 'Meeting scheduled successfully!');
    }

    // ─── Show Meeting ────────────────────────────────────────
    public function show($id)
    {
        $meeting = Meeting::with(['creator', 'participants.user'])->findOrFail($id);
        return view('admin.meetings.show', compact('meeting'));
    }

    // ─── Edit Form ───────────────────────────────────────────
    public function edit($id)
    {
        $meeting  = Meeting::findOrFail($id);
        $students = User::where('role', 'student')->get();
        return view('admin.meetings.edit', compact('meeting', 'students'));
    }

    // ─── Update Meeting ──────────────────────────────────────
    public function update(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'type'       => 'required|in:interview,orientation,class,general',
            'status'     => 'required|in:scheduled,cancelled,completed',
        ]);

        $meeting->update([
            'title'       => $request->title,
            'description' => $request->description,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'type'        => $request->type,
            'status'      => $request->status,
        ]);
        // ─── Google Calendar Sync ─────────────────────────────────
    try {
    $calendar = new GoogleCalendarService();
    if ($request->status === 'cancelled' && $meeting->google_event_id) {
        $calendar->deleteEvent($meeting->google_event_id);
        $meeting->update(['google_event_id' => null]);
    } elseif ($meeting->google_event_id) {
        $calendar->updateEvent(
            $meeting->google_event_id,
            $request->title,
            $request->description ?? '',
            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::parse($request->start_time)->format('Y-m-d H:i:s'), 'Asia/Karachi')->toRfc3339String(),
            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::parse($request->end_time)->format('Y-m-d H:i:s'), 'Asia/Karachi')->toRfc3339String(),
        );
    }
    } catch (\Exception $e) {
    \Log::error('Calendar update failed: ' . $e->getMessage());
    }
        // Agar cancel hua to notification send karo
        if ($request->status === 'cancelled') {
            foreach ($meeting->participants as $participant) {
                NotificationLog::send(
                    $participant->user_id,
                    'Meeting Cancelled',
                    'Meeting "' . $meeting->title . '" has been cancelled.',
                    'warning',
                    null
                );
            }
        }

        return redirect()->route('admin.meetings.index')
            ->with('success', 'Meeting updated successfully!');
    }

    // ─── Delete Meeting ──────────────────────────────────────
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->participants()->delete();
        $meeting->delete();

        return redirect()->route('admin.meetings.index')
            ->with('success', 'Meeting deleted.');
    }

    // ─── Generate Meet Code ──────────────────────────────────
    private function generateMeetCode(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $part1 = substr(str_shuffle($chars), 0, 3);
        $part2 = substr(str_shuffle($chars), 0, 4);
        $part3 = substr(str_shuffle($chars), 0, 3);
        return $part1 . '-' . $part2 . '-' . $part3;
    }
}