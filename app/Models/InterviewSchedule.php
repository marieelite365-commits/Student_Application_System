<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewSchedule extends Model
{
    protected $fillable = [
        'application_id',
        'student_id',
        'student_roll_no',
        'interview_title',
        'scheduled_at',
        'duration',
        'status',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'zoom_password',
        'drive_file_id',
        'drive_file_url',
        'result',
        'interviewer_notes',
        'scheduled_by',
        'calendar_event_id',   
        'calendar_event_url', 
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────────

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function scheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPassed(): bool
    {
        return $this->result === 'pass';
    }
}