<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('purchases')) {
            Schema::create('purchases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('price', 10, 2);
                $table->decimal('total_price', 10, 2);
                $table->timestamp('purchase_date')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
}; 