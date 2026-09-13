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
        Schema::table('ai_content_drafts', function (Blueprint $table) {
            $table->string('draft_title')->nullable()->after('status');
            $table->text('draft_description')->nullable()->after('draft_title');
            $table->json('draft_modules')->nullable()->after('draft_outline');
            $table->json('draft_notes')->nullable()->after('draft_modules');
            $table->json('draft_practice_questions')->nullable()->after('draft_mcqs');
            $table->text('source_pasted_content')->nullable()->after('source_file_path');
            $table->enum('draft_difficulty', ['easy', 'medium', 'hard'])->default('medium')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_content_drafts', function (Blueprint $table) {
            //
        });
    }
};
