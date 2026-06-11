<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            $table->string('calendar_event_id')->nullable()->after('drive_file_url');
            $table->string('calendar_event_url')->nullable()->after('calendar_event_id');
        });
    }

    public function down(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            $table->dropColumn(['calendar_event_id', 'calendar_event_url']);
        });
    }
};