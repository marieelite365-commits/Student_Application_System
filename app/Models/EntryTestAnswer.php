<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntryTestAnswer extends Model
{
    protected $fillable = [
        'entry_test_id',
        'question_id',
        'student_id',
        'selected_answer',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────
    public function entryTest(): BelongsTo
    {
        return $this->belongsTo(EntryTest::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(EntryTestQuestion::class, 'question_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}