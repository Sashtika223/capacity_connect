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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('course_code')->unique()->after('title');
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->onDelete('set null')->after('id');
            $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('set null')->after('category_id');
            $table->string('thumbnail')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('duration')->nullable(); // e.g. "4 weeks", "10 hours"
            $table->text('learning_objectives')->nullable();
            $table->text('prerequisites')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Note: `status` enum already exists from Phase 3 (active, inactive).
            // We will keep using active/inactive or add 'draft', 'published'
            // The prompt requests 'draft' / 'published', so let's modify the enum if possible, or just add a new field.
            // Since modifying enum in SQLite/MySQL can be tricky, let's add `publish_status`
            $table->enum('publish_status', ['draft', 'published'])->default('draft');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'course_code', 'category_id', 'trainer_id', 'thumbnail',
                'difficulty', 'duration', 'learning_objectives', 'prerequisites',
                'is_featured', 'start_date', 'end_date', 'publish_status',
            ]);
        });
    }
};
