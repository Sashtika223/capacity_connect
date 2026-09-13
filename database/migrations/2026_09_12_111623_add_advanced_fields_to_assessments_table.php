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
        Schema::table('assessments', function (Blueprint $table) {
            $table->boolean('negative_marking')->default(false)->after('passing_score');
            $table->decimal('negative_mark_value', 5, 2)->default(0.25)->after('negative_marking');
            $table->integer('max_attempts')->default(0)->after('negative_mark_value'); // 0 = unlimited
            $table->boolean('randomize_questions')->default(false)->after('max_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['negative_marking', 'negative_mark_value', 'max_attempts', 'randomize_questions']);
        });
    }
};
