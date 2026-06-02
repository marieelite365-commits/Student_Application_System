<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'cnic',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'province',
        'drive_folder_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // ─── Relationships ───────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // ─── Helper Methods ──────────────────────────────────────
    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address,
            $this->city,
            $this->province,
        ]));
    }

    public function hasDriveFolder(): bool
    {
        return !is_null($this->drive_folder_id);
    }

    // Auto-generate student ID
    public static function generateStudentId(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->latest()->first();
        $number = $last ? (intval(substr($last->student_id, -4)) + 1) : 1;
        return 'STU-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
