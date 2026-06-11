<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_profile_id',
        'degree_program',
        'department',
        'semester',
        'admission_year',
        'last_qualification',
        'last_institution',
        'last_cgpa',
        'last_percentage',
        'passing_year',
        'status',
        'admin_remarks',
        'submitted_at',
        'reviewed_at',
        'drive_folder_id',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function documents()
    {
         return $this->hasMany(ApplicationDocument::class);
    }

    public function interviewSchedule()
    {
    return $this->hasOne(InterviewSchedule::class);
    }

    // ── Helper Methods ─────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'submitted';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    // Status badge color helper
    public function statusColor(): string
    {
    return match($this->status) {
        'draft'                => 'gray',
        'submitted'            => 'blue',
        'under_review'         => 'yellow',
        'interview_scheduled'  => 'indigo',
        'interview_passed'     => 'teal',
        'interview_failed'     => 'red',
        'approved'             => 'green',
        'rejected'             => 'red',
        'enrolled'             => 'purple',
        default                => 'gray',
       };
    }
}