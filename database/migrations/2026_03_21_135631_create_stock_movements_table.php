<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('stock_location_id')->nullable()->constrained('stock_locations')->onDelete('set null');
            $table->string('type'); // "Stock In", "Stock Out", "Adjustment", etc.
            $table->integer('quantity'); // Positive for In, Negative for Out or signed
            $table->integer('balance_after');
            $table->string('reference')->nullable(); // PO-001, SO-001, etc.
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
