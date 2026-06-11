<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EntryTest extends Model
{
    protected $fillable = [
        'application_id',
        'student_id',
        'interview_id',
        'total_marks',
        'obtained_marks',
        'passing_marks',
        'status',
        'result',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'completed_at' => 'datetime',
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

    public function interview(): BelongsTo
    {
        return $this->belongsTo(InterviewSchedule::class, 'interview_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EntryTestAnswer::class);
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function isPassed(): bool
    {
        return $this->result === 'pass';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }
}