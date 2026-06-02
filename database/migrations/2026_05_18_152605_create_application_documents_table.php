<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');

            // Document Info
            $table->enum('document_type', [
                'profile_photo',
                'cnic_front',
                'cnic_back',
                'matric_certificate',
                'matric_marksheet',
                'inter_certificate',
                'inter_marksheet',
                'bachelor_certificate',
                'bachelor_transcript',
                'domicile',
                'other'
            ]);

            $table->string('original_filename');      // original file ka naam
            $table->string('stored_filename');        // server pe stored naam
            $table->string('file_path');              // local storage path
            $table->string('mime_type');              // image/jpeg, application/pdf
            $table->unsignedBigInteger('file_size');  // bytes mein

            // Google Drive
            $table->string('drive_file_id')->nullable();   // Drive file ID
            $table->string('drive_file_url')->nullable();  // shareable link

            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};