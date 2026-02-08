<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('procurement_supplier_disputes', function (Blueprint $table) {
            $table->bigIncrements('dispute_id');
            $table->string('dispute_code', 30)->unique();

            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('product_id');
            $table->string('sku_snapshot', 60);
            $table->string('product_name_snapshot', 200);

            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');

            $table->integer('rejected_qty');
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('total_value', 12, 2);

            $table->text('dispute_reason');
            $table->enum('dispute_status', ['Pending','Returned','Replaced','Refunded','Closed'])->default('Pending');

            $table->text('resolution_note')->nullable();
            $table->string('resolved_by', 100)->nullable();
            $table->date('resolved_date')->nullable();

            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('procurement_suppliers');
            $table->foreign('product_id')->references('product_id')->on('inventory_products');
            $table->foreign('brand_id')->references('brand_id')->on('procurement_brands');
            $table->foreign('category_id')->references('category_id')->on('procurement_categories');
            // po_id FK added after procurement_purchase_orders exists (see below)
        });

        Schema::create('procurement_supplier_dispute_actions', function (Blueprint $table) {
            $table->bigIncrements('action_id');
            $table->unsignedBigInteger('dispute_id');

            $table->string('old_status', 30);
            $table->string('new_status', 30);
            $table->text('note')->nullable();

            $table->string('action_by', 100);
            $table->dateTime('action_date')->useCurrent();

            $table->foreign('dispute_id')->references('dispute_id')->on('procurement_supplier_disputes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_supplier_dispute_actions');
        Schema::dropIfExists('procurement_supplier_disputes');
    }
};
