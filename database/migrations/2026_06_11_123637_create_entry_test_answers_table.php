<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_test_answers', function (Blueprint $table) {
            $table->id();

            // ─── Relations ────────────────────────────────────
            $table->foreignId('entry_test_id')->constrained('entry_tests')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('entry_test_questions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

            // ─── Answer ───────────────────────────────────────
            $table->enum('selected_answer', ['a', 'b', 'c', 'd'])->nullable();
            $table->boolean('is_correct')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_test_answers');
    }
};