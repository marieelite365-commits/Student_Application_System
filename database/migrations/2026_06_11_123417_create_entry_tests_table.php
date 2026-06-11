<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_tests', function (Blueprint $table) {
            $table->id();

            // ─── Relations ────────────────────────────────────
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('interview_id')->constrained('interview_schedules')->onDelete('cascade');

            // ─── Test Info ────────────────────────────────────
            $table->integer('total_marks')->default(10);
            $table->integer('obtained_marks')->nullable();
            $table->integer('passing_marks')->default(6);

            // ─── Status ───────────────────────────────────────
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');

            // ─── Result ───────────────────────────────────────
            $table->enum('result', [
                'pending',
                'pass',
                'fail'
            ])->default('pending');

            // ─── Timing ───────────────────────────────────────
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_tests');
    }
};