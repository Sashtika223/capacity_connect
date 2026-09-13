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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('certificate_name')->nullable()->after('certificate_id');
            $table->string('issuing_organization')->nullable()->after('certificate_name');
            $table->date('expiry_date')->nullable()->after('issue_date');
            $table->enum('cert_status', ['valid', 'expiring_soon', 'expired', 'renewal_required'])->default('valid')->after('expiry_date');
            $table->string('renewal_file_path')->nullable()->after('cert_status');
            $table->boolean('renewal_verified')->default(false)->after('renewal_file_path');
            $table->dateTime('last_notified_at')->nullable()->after('renewal_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            //
        });
    }
};
