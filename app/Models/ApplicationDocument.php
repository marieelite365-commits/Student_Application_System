<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'document_type',
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
        'drive_file_id',
        'drive_file_url',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'file_size'   => 'integer',
    ];

    // ─── Relationships ───────────────────────────────────────
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // ─── Helper Methods ──────────────────────────────────────
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 2) . ' KB';
    }

    public function isOnDrive(): bool
    {
        return !is_null($this->drive_file_id);
    }

    public function getDocumentLabelAttribute(): string
    {
        return match($this->document_type) {
            'profile_photo'       => 'Profile Photo',
            'cnic_front'          => 'CNIC Front',
            'cnic_back'           => 'CNIC Back',
            'matric_certificate'  => 'Matric Certificate',
            'matric_marksheet'    => 'Matric Marksheet',
            'inter_certificate'   => 'Inter Certificate',
            'inter_marksheet'     => 'Inter Marksheet',
            'bachelor_certificate'=> 'Bachelor Certificate',
            'bachelor_transcript' => 'Bachelor Transcript',
            'domicile'            => 'Domicile Certificate',
            'other'               => 'Other Document',
            default               => 'Document',
        };
    }
}