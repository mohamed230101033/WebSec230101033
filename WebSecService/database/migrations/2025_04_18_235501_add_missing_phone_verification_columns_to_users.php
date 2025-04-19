<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if phone_verified_at column exists
            if (!Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            }
            
            // Check if phone_verification_code column exists
            if (!Schema::hasColumn('users', 'phone_verification_code')) {
                $table->string('phone_verification_code', 10)->nullable()->after('phone');
            }
            
            // Check if phone_verification_code_expires_at column exists
            if (!Schema::hasColumn('users', 'phone_verification_code_expires_at')) {
                $table->timestamp('phone_verification_code_expires_at')->nullable()->after('phone_verification_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop columns if they exist
            $columns = [];
            
            if (Schema::hasColumn('users', 'phone_verified_at')) {
                $columns[] = 'phone_verified_at';
            }
            
            if (Schema::hasColumn('users', 'phone_verification_code')) {
                $columns[] = 'phone_verification_code';
            }
            
            if (Schema::hasColumn('users', 'phone_verification_code_expires_at')) {
                $columns[] = 'phone_verification_code_expires_at';
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
