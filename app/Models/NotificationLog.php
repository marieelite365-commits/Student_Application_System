<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationLog extends Model
{
    use HasFactory;

    protected $table = 'notifications_log';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ─────────────────────────────────────────────

    public static function send(int $userId, string $title, string $message, string $type = 'info', ?string $link = null): void
    {
        static::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'link'    => $link,
            'is_read' => false,
        ]);
    }
}
