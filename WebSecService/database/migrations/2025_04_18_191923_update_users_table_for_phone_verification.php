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
        //
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
