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
        Schema::table('capacity_simulations', function (Blueprint $table) {
            $table->integer('training_duration_days')->nullable()->after('expected_improvement');
            $table->integer('training_sessions')->nullable()->after('training_duration_days');
            $table->integer('trainers_required')->nullable()->after('training_sessions');
            $table->integer('training_hours_total')->nullable()->after('trainers_required');
            $table->decimal('risk_reduction_percentage', 5, 2)->nullable()->after('training_hours_total');
        });
    }

    public function down(): void
    {
        Schema::table('capacity_simulations', function (Blueprint $table) {
            $table->dropColumn(['training_duration_days', 'training_sessions', 'trainers_required', 'training_hours_total', 'risk_reduction_percentage']);
        });
    }
};
