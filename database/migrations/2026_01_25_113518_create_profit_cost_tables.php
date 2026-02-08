<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_stock_batches', function (Blueprint $table) {
            $table->bigIncrements('stock_batch_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('purchase_order_id');

            $table->decimal('unit_cost', 12, 2);
            $table->integer('quantity_received');
            $table->integer('quantity_remaining');
            $table->date('received_date');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('product_id')->references('product_id')->on('inventory_products');
            $table->foreign('supplier_id')->references('supplier_id')->on('procurement_suppliers');
            // purchase_order_id FK added after procurement_purchase_orders exists (see below)
        });

        Schema::create('sales_stock_cost_mapping', function (Blueprint $table) {
            $table->bigIncrements('sales_stock_map_id');
            $table->unsignedBigInteger('sales_invoice_item_id');
            $table->unsignedBigInteger('stock_batch_id');

            $table->integer('quantity_used');
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('cost_total', 14, 2);

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sales_invoice_item_id')->references('invoice_item_id')->on('sales_invoice_items');
            $table->foreign('stock_batch_id')->references('stock_batch_id')->on('inventory_stock_batches');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_stock_cost_mapping');
        Schema::dropIfExists('inventory_stock_batches');
    }
};
