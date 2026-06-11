<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InterviewSchedule;
use App\Models\User;
use App\Services\ZoomService;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleCalendarService;

class InterviewController extends Controller
{
    protected ZoomService $zoomService;
    protected GoogleDriveService $driveService;
    protected GoogleCalendarService $calendarService;

    public function __construct(
    ZoomService $zoomService,
    GoogleDriveService $driveService,
    GoogleCalendarService $calendarService     
    ) {
    $this->zoomService     = $zoomService;
    $this->driveService    = $driveService;
    $this->calendarService = $calendarService;
    }

    // ─── List All Interviews ──────────────────────────────────
    public function index()
    {
        $interviews = InterviewSchedule::with(['application', 'student', 'scheduledBy'])
            ->latest()
            ->paginate(15);

        return view('admin.interviews.index', compact('interviews'));
    }

    // ─── Schedule Interview Form ──────────────────────────────
    public function create(Request $request)
    {
        // Sirf submitted applications jin ka interview nahi hua
        $applications = Application::with(['user', 'studentProfile'])
            ->where('status', 'submitted')
            ->whereDoesntHave('interviewSchedule')
            ->get();

        $selectedApplication = null;
        if ($request->has('application_id')) {
            $selectedApplication = Application::with(['user', 'studentProfile'])
                ->find($request->application_id);
        }

        return view('admin.interviews.create', compact('applications', 'selectedApplication'));
    }

    // ─── Store Interview ──────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
            'duration'       => 'required|integer|min:15|max:60',
        ]);

        $application = Application::with(['user', 'studentProfile'])->findOrFail($request->application_id);
        $student     = $application->user;

        // ─── Generate Roll No ──────────────────────────────
        $rollNo = 'LLU-' . date('Y') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);

        // ─── Zoom Meeting Create ───────────────────────────
        try {
            $zoomMeeting = $this->zoomService->createMeeting(
                topic:     'Interview - ' . $student->name . ' (' . $rollNo . ')',
                startTime: \Carbon\Carbon::parse($request->scheduled_at, 'Asia/Karachi')->format('Y-m-d\TH:i:s'),
                duration:  $request->duration,
                agenda:    'Admission interview for ' . $application->degree_program
            );
        } catch (\Exception $e) {
            Log::error('Zoom meeting create failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create Zoom meeting. Please try again.');
        }

        // ─── Save Interview ────────────────────────────────
        $interview = InterviewSchedule::create([
            'application_id'  => $application->id,
            'student_id'      => $student->id,
            'student_roll_no' => $rollNo,
            'interview_title' => 'Admission Interview - ' . $application->degree_program,
            'scheduled_at'    => $request->scheduled_at,
            'duration'        => $request->duration,
            'status'          => 'scheduled',
            'zoom_meeting_id' => $zoomMeeting['meeting_id'],
            'zoom_join_url'   => $zoomMeeting['join_url'],
            'zoom_start_url'  => $zoomMeeting['start_url'],
            'zoom_password'   => $zoomMeeting['password'],
            'scheduled_by'    => Auth::id(),
        ]);

        // ─── Google Drive: Interview Info Save ────────────
        try {
            $folderId = $this->driveService->getStudentFolder($student->id, $student->name);

            $infoText  = "INTERVIEW SCHEDULE\n";
            $infoText .= "==================\n";
            $infoText .= "Roll No         : {$rollNo}\n";
            $infoText .= "Student Name    : {$student->name}\n";
            $infoText .= "Email           : {$student->email}\n";
            $infoText .= "Degree Program  : {$application->degree_program}\n";
            $infoText .= "Department      : {$application->department}\n";
            $infoText .= "Interview Date  : " . date('d M Y, h:i A', strtotime($request->scheduled_at)) . "\n";
            $infoText .= "Duration        : {$request->duration} minutes\n";
            $infoText .= "Zoom Meeting ID : {$zoomMeeting['meeting_id']}\n";
            $infoText .= "Join URL        : {$zoomMeeting['join_url']}\n";
            $infoText .= "Password        : {$zoomMeeting['password']}\n";
            $infoText .= "Status          : Scheduled\n";

            $tempPath = storage_path('app/temp_interview_' . $interview->id . '.txt');
            file_put_contents($tempPath, $infoText);

            $driveResult = $this->driveService->uploadFile(
                $tempPath,
                'interview_schedule_' . $rollNo . '.txt',
                'text/plain',
                $folderId
            );

            @unlink($tempPath);

            $interview->update([
                'drive_file_id'  => $driveResult['file_id'],
                'drive_file_url' => $driveResult['file_url'],
            ]);

        } catch (\Exception $e) {
            Log::error('Drive interview save failed: ' . $e->getMessage());
        }

        // ─── Google Calendar: Event Create ────────────────────
        try {
        $calendarResult = $this->calendarService->createInterviewEvent(
          studentName:   $student->name,
          studentEmail:  $student->email,
          rollNo:        $rollNo,
          degreeProgram: $application->degree_program,
          department:    $application->department,
          scheduledAt:   $request->scheduled_at,
          duration:      $request->duration,
          zoomJoinUrl:   $zoomMeeting['join_url'],
          zoomMeetingId: $zoomMeeting['meeting_id'],
          zoomPassword:  $zoomMeeting['password']
         );

        if ($calendarResult['success']) {
        $interview->update([
            'calendar_event_id'  => $calendarResult['event_id'],
            'calendar_event_url' => $calendarResult['event_url'],
        ]);
        }
  
       } catch (\Exception $e) {
          Log::error('Calendar event create failed: ' . $e->getMessage());
       }

        // ─── Application Status Update ─────────────────────
        $application->update(['status' => 'interview_scheduled']);

        return redirect()->route('admin.interviews.index')
            ->with('success', 'Interview schedule ho gaya! Roll No: ' . $rollNo);
    }

    // ─── Show Interview ───────────────────────────────────────
    public function show(InterviewSchedule $interview)
    {
        $interview->load(['application', 'student', 'scheduledBy']);
        return view('admin.interviews.show', compact('interview'));
    }

    // ─── Mark Result ──────────────────────────────────────────
    public function markResult(Request $request, InterviewSchedule $interview)
    {
        $request->validate([
            'result'            => 'required|in:pass,fail',
            'interviewer_notes' => 'nullable|string|max:1000',
        ]);

        $interview->update([
            'result'            => $request->result,
            'status'            => 'completed',
            'interviewer_notes' => $request->interviewer_notes,
        ]);

        // Application status update
       if ($request->result === 'pass') {
        $interview->application->update(['status' => 'interview_passed']);

        // ─── Entry Test Auto Create ────────────────────────
        \App\Models\EntryTest::create([
        'application_id' => $interview->application_id,
        'student_id'     => $interview->student_id,
        'interview_id'   => $interview->id,
        'total_marks'    => 10,
        'passing_marks'  => 6,
        'status'         => 'pending',
        'result'         => 'pending',
        ]);

       } else {
        $interview->application->update(['status' => 'interview_failed']);
       }

       return redirect()->route('admin.interviews.show', $interview->id)
       ->with('success', 'Interview result saved successfully!');
    }

    // ─── Cancel Interview ─────────────────────────────────────
    public function cancel(InterviewSchedule $interview)
    {
        try {
            $this->zoomService->deleteMeeting($interview->zoom_meeting_id);
        } catch (\Exception $e) {
            Log::error('Zoom delete failed: ' . $e->getMessage());
        }

         try {
            if ($interview->calendar_event_id) {
                $this->calendarService->deleteEvent($interview->calendar_event_id);
            }
        } catch (\Exception $e) {
            Log::error('Calendar delete failed: ' . $e->getMessage());
        }

        $interview->update(['status' => 'cancelled']);
        $interview->application->update(['status' => 'submitted']);

        return back()->with('success', 'Interview cancel ho gaya.');
    }
}