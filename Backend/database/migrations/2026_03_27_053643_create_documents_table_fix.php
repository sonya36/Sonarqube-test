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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->index();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->foreignId('application_id')->constrained('applications')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->index();
            $table->integer('version')->default(1);
            $table->integer('view_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug', 'application_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
