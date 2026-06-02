<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Info
            $table->string('student_id')->unique(); // e.g. STU-2026-0001
            $table->string('cnic', 15)->unique();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            
            // Google Drive
            $table->string('drive_folder_id')->nullable(); // student ka personal Drive folder ID
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};