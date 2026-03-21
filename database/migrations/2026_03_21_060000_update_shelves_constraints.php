<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_locations', function (Blueprint $table) {
            $table->integer('max_capacity')->default(50); // Limit to 50 units
            $table->unsignedBigInteger('current_product_id')->nullable(); // Track which product is on this shelf
            
            $table->foreign('current_product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('stock_locations', function (Blueprint $table) {
            $table->dropForeign(['current_product_id']);
            $table->dropColumn(['max_capacity', 'current_product_id']);
        });
    }
};
