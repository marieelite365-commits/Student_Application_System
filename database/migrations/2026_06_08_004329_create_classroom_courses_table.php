<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->string('name');
            $table->string('section')->nullable();
            $table->string('description')->nullable();
            $table->string('classroom_id')->unique(); // Google Classroom ID
            $table->string('enrollment_code')->nullable();
            $table->string('alternate_link')->nullable(); // Classroom URL
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_courses');
    }
};