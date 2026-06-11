<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_test_questions', function (Blueprint $table) {
            $table->id();

            // ─── Question Info ────────────────────────────────
            $table->text('question');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_answer', ['a', 'b', 'c', 'd']);
            $table->integer('marks')->default(1);

            // ─── Category ─────────────────────────────────────
            $table->string('category')->default('general');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_test_questions');
    }
};