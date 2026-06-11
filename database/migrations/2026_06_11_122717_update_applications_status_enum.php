<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'draft',
            'submitted',
            'under_review',
            'interview_scheduled',
            'interview_passed',
            'interview_failed',
            'approved',
            'rejected',
            'enrolled'
        ) NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'draft',
            'submitted',
            'under_review',
            'approved',
            'rejected',
            'enrolled'
        ) NOT NULL DEFAULT 'draft'");
    }
};