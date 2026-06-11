<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EntryTestQuestion extends Model
{
    protected $fillable = [
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'marks',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────
    public function answers(): HasMany
    {
        return $this->hasMany(EntryTestAnswer::class, 'question_id');
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function isCorrect(string $answer): bool
    {
        return strtolower($answer) === strtolower($this->correct_answer);
    }
}