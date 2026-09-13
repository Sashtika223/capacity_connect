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
        Schema::create('trainee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('work_experience')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->text('interests')->nullable();
            $table->text('skills')->nullable();
            $table->text('certificates_list')->nullable(); // External certificates
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_profiles');
    }
};
