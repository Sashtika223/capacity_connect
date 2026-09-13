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
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Digital Capacity Building & Emergency Response Portal');
            $table->text('hero_description')->default('Institutional platform for workforce capacity assessment, disaster response drills, competency risk management, and verifiable professional certifications.');
            $table->text('important_notice')->nullable();
            $table->json('stat_highlights')->nullable();
            $table->json('featured_course_ids')->nullable();
            $table->boolean('show_ai_section')->default(true);
            $table->boolean('show_disaster_section')->default(true);
            $table->boolean('show_offline_section')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};
