<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomCourse extends Model
{
    protected $fillable = [
        'created_by',
        'name',
        'section',
        'description',
        'classroom_id',
        'enrollment_code',
        'alternate_link',
        'status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}