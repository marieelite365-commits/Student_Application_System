<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleClassroomToken extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'token_type',
        'expires_at',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}