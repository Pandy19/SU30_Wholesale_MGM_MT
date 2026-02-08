<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('sku', 60)->unique();
            $table->string('name', 200);
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');
            $table->string('image_url', 500)->nullable();
            $table->longText('specs_text')->nullable();
            $table->enum('status', ['Available','Limited','Unavailable'])->default('Available');
            $table->timestamps();

            $table->foreign('brand_id')->references('brand_id')->on('procurement_brands');
            $table->foreign('category_id')->references('category_id')->on('procurement_categories');
        });

        Schema::create('inventory_stock_locations', function (Blueprint $table) {
            $table->bigIncrements('location_id');
            $table->string('location_name', 120);
            $table->string('location_code', 60)->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('procurement_categories');
        });

        Schema::create('inventory_goods_receiving', function (Blueprint $table) {
            $table->bigIncrements('grn_id');
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('supplier_id')->nullable();

            $table->date('received_date')->nullable();
            $table->string('received_by', 100)->nullable();
            $table->enum('receiving_status', ['Pending','Accepted','Rejected'])->default('Pending');

            $table->string('approved_by', 100)->nullable();
            $table->date('approved_date')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
            // FK to PO created in procurement_purchase_orders migration (later file).
        });

        Schema::create('inventory_goods_receiving_items', function (Blueprint $table) {
            $table->bigIncrements('grn_item_id');
            $table->unsignedBigInteger('grn_id');
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');

            $table->string('sku_snapshot', 60);
            $table->string('product_name_snapshot', 200);

            $table->integer('ordered_qty');
            $table->integer('received_qty');
            $table->integer('accepted_qty');
            $table->integer('rejected_qty');

            $table->decimal('unit_cost', 12, 2);
            $table->decimal('line_total', 12, 2);

            $table->enum('item_status', ['Pending','Accepted','Rejected'])->default('Pending');
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->foreign('grn_id')->references('grn_id')->on('inventory_goods_receiving');
            $table->foreign('product_id')->references('product_id')->on('inventory_products');
            $table->foreign('supplier_id')->references('supplier_id')->on('procurement_suppliers');
        });

        Schema::create('inventory_stock', function (Blueprint $table) {
            $table->bigIncrements('stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('location_id');

            $table->integer('quantity_on_hand')->default(0);
            $table->decimal('average_cost', 12, 2)->nullable();

            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->decimal('stock_value', 12, 2)->nullable();

            $table->decimal('selling_price', 12, 2)->nullable();
            $table->decimal('profit_per_unit', 12, 2)->nullable();

            $table->enum('stock_status', ['Normal','Low Stock','Out of Stock'])->nullable();
            $table->integer('low_stock_level')->nullable();

            $table->dateTime('last_updated')->nullable();
            $table->timestamps();

            $table->unique(['product_id','location_id']);

            $table->foreign('product_id')->references('product_id')->on('inventory_products');
            $table->foreign('supplier_id')->references('supplier_id')->on('procurement_suppliers');
            $table->foreign('brand_id')->references('brand_id')->on('procurement_brands');
            $table->foreign('category_id')->references('category_id')->on('procurement_categories');
            $table->foreign('location_id')->references('location_id')->on('inventory_stock_locations');
        });

        Schema::create('inventory_stock_ledger', function (Blueprint $table) {
            $table->bigIncrements('ledger_id');
            $table->date('ledger_date')->nullable();
            $table->unsignedBigInteger('product_id');

            $table->string('sku_snapshot', 60)->nullable();
            $table->string('product_name_snapshot', 200)->nullable();

            $table->enum('action_type', ['Stock In','Stock Out','Rejected','Adjustment','Initial Stock']);
            $table->integer('quantity_in')->default(0);
            $table->integer('quantity_out')->default(0);
            $table->integer('balance_quantity')->nullable();

            $table->string('reference_number', 50)->nullable();
            $table->string('performed_by', 100)->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('reference_number');
            $table->index('ledger_date');

            $table->foreign('product_id')->references('product_id')->on('inventory_products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_ledger');
        Schema::dropIfExists('inventory_stock');
        Schema::dropIfExists('inventory_goods_receiving_items');
        Schema::dropIfExists('inventory_goods_receiving');
        Schema::dropIfExists('inventory_stock_locations');
        Schema::dropIfExists('inventory_products');
    }
};
