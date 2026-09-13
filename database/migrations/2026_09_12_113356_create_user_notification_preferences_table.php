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
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('in_app_notifications')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('new_course_alerts')->default(true);
            $table->boolean('assessment_alerts')->default(true);
            $table->boolean('certificate_alerts')->default(true);
            $table->boolean('announcement_alerts')->default(true);
            $table->boolean('skill_gap_alerts')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
