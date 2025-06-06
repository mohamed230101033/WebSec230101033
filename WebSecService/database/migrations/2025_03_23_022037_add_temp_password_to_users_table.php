<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('temp_password')->nullable()->after('security_answer');
            $table->boolean('temp_password_used')->default(false)->after('temp_password');
        });
    }
    
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['temp_password', 'temp_password_used']);
        });
    }
};