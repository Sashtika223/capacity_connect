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
        Schema::create('ai_content_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('source_file_name');
            $table->string('source_file_path');
            $table->enum('status', ['drafted', 'approved', 'rejected'])->default('drafted');
            $table->json('draft_outline')->nullable();
            $table->text('draft_summary')->nullable();
            $table->json('draft_objectives')->nullable();
            $table->json('draft_mcqs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_content_drafts');
    }
};
