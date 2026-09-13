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
        // Update existing assessments table
        Schema::table('assessments', function (Blueprint $table) {
            $table->string('subject')->nullable();
            $table->text('instructions')->nullable();
            $table->integer('duration')->default(60); // minutes
            $table->integer('passing_score')->default(50); // percentage
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->dropColumn('total_marks');
        });

        // Assessment Questions
        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->integer('marks')->default(1);
            $table->timestamps();
        });

        // Assessment Options
        Schema::create('assessment_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_question_id')->constrained()->onDelete('cascade');
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // Assessment Attempts
        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->enum('status', ['in-progress', 'completed'])->default('in-progress');
            $table->integer('score')->nullable();
            $table->integer('percentage')->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamps();
        });

        // Assessment Answers
        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_attempt_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessment_question_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessment_option_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
        Schema::dropIfExists('assessment_attempts');
        Schema::dropIfExists('assessment_options');
        Schema::dropIfExists('assessment_questions');

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['subject', 'instructions', 'duration', 'passing_score', 'start_date', 'end_date', 'status']);
            $table->integer('total_marks')->nullable();
        });
    }
};
