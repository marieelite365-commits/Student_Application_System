<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meet_link')->nullable();
            $table->string('google_event_id')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('type', ['interview', 'orientation', 'class', 'general'])->default('general');
            $table->enum('status', ['scheduled', 'cancelled', 'completed'])->default('scheduled');
            $table->enum('for', ['all', 'specific'])->default('all');
            $table->timestamps();
        });

        // Meeting participants table
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });

        // Notifications table
        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info'); // info, success, warning
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_participants');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('notifications_log');
    }
};