<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('original_name');
            $table->string('file_path', 500);
            $table->string('disk')->default('local'); // Refinement: specify storage disk
            $table->string('file_type', 50);
            $table->string('mime_type', 100);
            $table->bigInteger('file_size');
            $table->timestamp('created_at')->useCurrent();

            $table->index('document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_attachments');
    }
};
