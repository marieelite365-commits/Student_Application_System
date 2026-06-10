<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'description',
        'meet_link',
        'google_event_id',
        'start_time',
        'end_time',
        'type',
        'status',
        'for',
        'auto_ended',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'auto_ended' => 'boolean',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    // ─── Helpers ─────────────────────────────────────────────

    public function isUpcoming(): bool
    {
        return $this->start_time->isFuture() && $this->status === 'scheduled';
    }

    public function isPast(): bool
    {
        return $this->end_time->isPast();
    }

    public function isLive(): bool
    {
        return now()->between($this->start_time, $this->end_time)
            && $this->status === 'scheduled';
    }

    public function isForAll(): bool
    {
        return $this->for === 'all';
    }

    public function attendanceCount(): int
    {
        return $this->participants()->where('attended', true)->count();
    }
}