<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meeting_participants', function (Blueprint $table) {
            $table->boolean('attended')->default(false)->after('notified');
            $table->timestamp('joined_at')->nullable()->after('attended');
        });

        // Meetings table mein auto-end track karne ke liye
        Schema::table('meetings', function (Blueprint $table) {
            $table->boolean('auto_ended')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('meeting_participants', function (Blueprint $table) {
            $table->dropColumn(['attended', 'joined_at']);
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('auto_ended');
        });
    }
};