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
        Schema::create('practical_attempt_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practical_attempt_id')->constrained()->onDelete('cascade');
            $table->foreignId('node_id')->constrained('practical_nodes')->onDelete('cascade');
            $table->foreignId('option_id')->nullable()->constrained('practical_options')->onDelete('cascade');
            $table->integer('score_delta')->default(0);
            $table->text('consequence_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practical_attempt_logs');
    }
};
