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
            // Make phone column unique and nullable
            $table->string('phone')->unique()->nullable()->change();
            
            // Add new columns for phone verification
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->string('phone_verification_code', 6)->nullable()->after('phone');
            $table->timestamp('phone_verification_code_expires_at')->nullable()->after('phone_verification_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert phone column changes (cannot fully revert unique constraint)
            $table->string('phone')->nullable()->change();
            
            // Remove added columns
            $table->dropColumn([
                'phone_verified_at',
                'phone_verification_code',
                'phone_verification_code_expires_at'
            ]);
        });
    }
};
