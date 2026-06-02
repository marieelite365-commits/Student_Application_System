<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');

            // Academic Info
            $table->string('degree_program');   // BS, MS, PhD
            $table->string('department');
            $table->enum('semester', ['Fall', 'Spring', 'Summer']);
            $table->year('admission_year');

            // Academic Background
            $table->string('last_qualification');     // Matric, Inter, Bachelor
            $table->string('last_institution');
            $table->decimal('last_cgpa', 4, 2)->nullable();
            $table->decimal('last_percentage', 5, 2)->nullable();
            $table->year('passing_year');

            // Application Status
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'approved',
                'rejected',
                'enrolled'
            ])->default('draft');

            $table->text('admin_remarks')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            // Drive
            $table->string('drive_folder_id')->nullable(); // application ka folder Drive mein

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};