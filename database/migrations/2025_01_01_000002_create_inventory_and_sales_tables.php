<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Inventory & Stock
        Schema::create('stock_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Mobile Shelf A1", "Main Warehouse"
            $table->string('code')->nullable();
            $table->timestamps();
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('stock_location_id')->constrained('stock_locations')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->decimal('average_cost', 10, 2)->default(0); // For valuation
            $table->timestamps();
        });

        // 2. Customers (Sales)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique(); // CUS-B2B-001
            $table->string('name');
            $table->enum('type', ['B2B', 'B2C'])->default('B2C');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->enum('status', ['active', 'on_hold', 'blacklisted'])->default('active');
            $table->timestamps();
        });

        // 3. Sales Orders
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // SO-2025-001
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('order_date')->useCurrent();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'card', 'digital_wallet'])->nullable();
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
        Schema::dropIfExists('sales_orders');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('stock_locations');
    }
};
