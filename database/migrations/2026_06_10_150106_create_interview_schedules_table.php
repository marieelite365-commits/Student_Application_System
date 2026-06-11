<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();

            // ─── Relations ────────────────────────────────────
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

            // ─── Interview Info ───────────────────────────────
            $table->string('student_roll_no')->nullable();        // Auto generated ID
            $table->string('interview_title');
            $table->dateTime('scheduled_at');                     // Interview date & time
            $table->integer('duration')->default(30);             // Minutes
            $table->enum('status', [
                'scheduled',
                'rescheduled',
                'completed',
                'cancelled',
                'no_show'
            ])->default('scheduled');

            // ─── Zoom Info ────────────────────────────────────
            $table->string('zoom_meeting_id')->nullable();
            $table->text('zoom_join_url')->nullable();
            $table->text('zoom_start_url')->nullable();
            $table->string('zoom_password')->nullable();

            // ─── Google Drive Info ────────────────────────────
            $table->string('drive_file_id')->nullable();
            $table->text('drive_file_url')->nullable();

            // ─── Interview Result ─────────────────────────────
            $table->enum('result', [
                'pending',
                'pass',
                'fail'
            ])->default('pending');
            $table->text('interviewer_notes')->nullable();

            // ─── Admin ────────────────────────────────────────
            $table->foreignId('scheduled_by')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};