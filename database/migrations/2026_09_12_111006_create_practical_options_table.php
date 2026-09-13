<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practical_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practical_node_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->foreignId('next_node_id')->nullable()->constrained('practical_nodes')->onDelete('set null');
            $table->text('consequence_text')->nullable();
            $table->integer('score_delta')->default(0);
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practical_options');
    }
};
