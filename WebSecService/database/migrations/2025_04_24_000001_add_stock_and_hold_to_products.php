<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('photo');
            }
            if (!Schema::hasColumn('products', 'hold')) {
                $table->boolean('hold')->default(false)->after('stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
            if (Schema::hasColumn('products', 'hold')) {
                $table->dropColumn('hold');
            }
        });
    }
}; 