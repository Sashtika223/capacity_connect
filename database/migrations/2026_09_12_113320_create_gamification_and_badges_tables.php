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
        // Add gamification columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('status');
            $table->integer('level')->default(1)->after('points');
            $table->integer('learning_streak')->default(1)->after('level');
            $table->timestamp('last_active_at')->nullable()->after('learning_streak');
        });

        // Badges Table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->default('bi-award');
            $table->integer('points_required')->default(0);
            $table->timestamps();
        });

        // User Badges Pivot Table
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamp('awarded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'level', 'learning_streak', 'last_active_at']);
        });
    }
};
