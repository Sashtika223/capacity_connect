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
        Schema::create('capacity_simulations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('competency_id')->constrained()->cascadeOnDelete();
            $table->integer('current_employees');
            $table->integer('current_capacity');
            $table->integer('required_capacity');
            $table->integer('employees_to_train');
            $table->decimal('expected_improvement', 5, 2)->default(1.00);
            $table->integer('projected_capacity');
            $table->integer('remaining_gap');
            $table->decimal('improvement_percentage', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacity_simulations');
    }
};
