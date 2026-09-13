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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('expert_verified')->default(false)->after('role');
            $table->string('expert_role')->nullable()->after('expert_verified');
            $table->boolean('in_knowledge_repo')->default(false)->after('expert_role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['expert_verified', 'expert_role', 'in_knowledge_repo']);
        });
    }
};
