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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('certificate_id')->unique()->after('id');
            $table->integer('score')->nullable()->after('course_id');

            // Prevent duplicate certificates per user per course
            $table->unique(['user_id', 'course_id']);

            $table->dropColumn('certificate_url'); // We render HTML instead
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'course_id']);
            $table->dropColumn(['certificate_id', 'score']);
            $table->string('certificate_url')->nullable();
        });
    }
};
