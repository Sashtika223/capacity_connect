<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practical_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practical_assessment_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('situation_text');
            $table->boolean('is_start')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practical_nodes');
    }
};
