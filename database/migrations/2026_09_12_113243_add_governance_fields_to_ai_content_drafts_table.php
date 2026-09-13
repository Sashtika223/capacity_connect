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
        Schema::table('ai_content_drafts', function (Blueprint $table) {
            $table->string('governance_status')->default('ai_generated')->after('status');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null')->after('governance_status');
            $table->timestamp('reviewed_at')->nullable()->after('reviewer_id');
            $table->text('rejection_reason')->nullable()->after('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_content_drafts', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn(['governance_status', 'reviewer_id', 'reviewed_at', 'rejection_reason']);
        });
    }
};
