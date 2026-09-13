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
        Schema::create('trainer_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('assessment_id')->nullable()->constrained('assessments')->onDelete('set null');
            $table->enum('status', [
                'profile_incomplete',
                'eligible',
                'assigned',
                'certification_assigned',
                'pending',
                'certification_pending',
                'certified',
                'rejected',
                'expired',
                'certification_expired',
                'renewal_required',
                'renewal_passed',
                'renewal_failed',
            ])->default('assigned');
            $table->decimal('score', 5, 2)->nullable();
            $table->integer('passing_score')->default(70);
            $table->integer('validity_years')->default(1);
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('trainer_certification_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_certification_id')->constrained('trainer_certifications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assessment_id')->nullable()->constrained('assessments')->onDelete('set null');
            $table->foreignId('assessment_attempt_id')->nullable()->constrained('assessment_attempts')->onDelete('set null');
            $table->enum('type', ['initial', 'renewal'])->default('initial');
            $table->decimal('score', 5, 2)->default(0);
            $table->integer('percentage')->default(0);
            $table->boolean('passed')->default(false);
            $table->timestamp('attempted_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_certification_attempts');
        Schema::dropIfExists('trainer_certifications');
    }
};
