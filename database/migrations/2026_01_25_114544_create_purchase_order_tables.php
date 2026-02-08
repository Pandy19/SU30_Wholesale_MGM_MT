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
        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT PURCHASE ORDERS
        |--------------------------------------------------------------------------
        */
        Schema::create('procurement_purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('po_id');
            $table->string('po_number', 30)->unique();
            $table->unsignedBigInteger('supplier_id');

            $table->date('order_date');
            $table->date('invoice_date')->nullable();

            $table->decimal('subtotal_amount', 12, 2)->nullable();
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            $table->enum('payment_status', ['Paid','Unpaid','Partial','N/A'])->default('Unpaid');
            $table->string('created_by', 100)->nullable();

            $table->enum('receiving_status', ['Pending','Accepted','Rejected'])->default('Pending');
            $table->enum('stock_status', ['Added to Stock','Not Added'])->default('Not Added');

            $table->timestamps();

            // Index for FK
            $table->index('supplier_id', 'idx_po_supplier');

            // IMPORTANT: procurement_suppliers primary key is supplier_id
            $table->foreign('supplier_id', 'fk_po_supplier')
                  ->references('supplier_id')->on('procurement_suppliers')
                  ->onUpdate('cascade')->onDelete('restrict');
        });

        /*
        |--------------------------------------------------------------------------
        | PURCHASE ORDER ITEMS
        |--------------------------------------------------------------------------
        */
        Schema::create('procurement_purchase_order_items', function (Blueprint $table) {
            $table->bigIncrements('po_item_id');
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id')->nullable();

            $table->string('sku_snapshot', 60)->nullable();
            $table->string('product_name_snapshot', 200)->nullable();

            $table->decimal('unit_cost', 12, 2);
            $table->integer('quantity');
            $table->decimal('line_total', 12, 2);

            $table->timestamps();

            // Indexes for FK
            $table->index('po_id', 'idx_poi_po');
            $table->index('product_id', 'idx_poi_product');
            $table->index('supplier_id', 'idx_poi_supplier');

            $table->foreign('po_id', 'fk_poi_po')
                  ->references('po_id')->on('procurement_purchase_orders')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('product_id', 'fk_poi_product')
                  ->references('product_id')->on('inventory_products')
                  ->onUpdate('cascade')->onDelete('restrict');

            // IMPORTANT: procurement_suppliers primary key is supplier_id
            $table->foreign('supplier_id', 'fk_poi_supplier')
                  ->references('supplier_id')->on('procurement_suppliers')
                  ->onUpdate('cascade')->onDelete('restrict');
        });

        /*
        |--------------------------------------------------------------------------
        | PURCHASE PAYMENTS
        |--------------------------------------------------------------------------
        */
        Schema::create('procurement_purchase_payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('po_id');

            $table->date('payment_date');
            $table->enum('payment_method', ['Cash','Bank','Transfer','Digital Wallet','Cheque']);
            $table->decimal('paid_amount', 12, 2);
            $table->enum('payment_status', ['Paid','Partial','Unpaid'])->default('Paid');

            $table->timestamps();

            // Index for FK
            $table->index('po_id', 'idx_pop_po');

            $table->foreign('po_id', 'fk_pop_po')
                  ->references('po_id')->on('procurement_purchase_orders')
                  ->onUpdate('cascade')->onDelete('cascade');
        });

        /*
        |--------------------------------------------------------------------------
        | SUPPLIER SWITCH LOGS
        |--------------------------------------------------------------------------
        */
        Schema::create('procurement_purchase_order_supplier_switch_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('product_id');

            $table->unsignedBigInteger('old_supplier_id');
            $table->unsignedBigInteger('new_supplier_id');

            $table->decimal('old_unit_cost', 12, 2);
            $table->decimal('new_unit_cost', 12, 2);

            $table->unsignedBigInteger('switched_by')->nullable();
            $table->timestamp('switched_at')->useCurrent();

            // Indexes for FK
            $table->index('po_id', 'idx_posl_po');
            $table->index('product_id', 'idx_posl_product');
            $table->index('old_supplier_id', 'idx_posl_old_supplier');
            $table->index('new_supplier_id', 'idx_posl_new_supplier');
            $table->index('switched_by', 'idx_posl_switched_by');

            $table->foreign('po_id', 'fk_posl_po')
                  ->references('po_id')->on('procurement_purchase_orders')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('product_id', 'fk_posl_product')
                  ->references('product_id')->on('inventory_products')
                  ->onUpdate('cascade')->onDelete('restrict');

            // IMPORTANT: procurement_suppliers primary key is supplier_id
            $table->foreign('old_supplier_id', 'fk_posl_old_supplier')
                  ->references('supplier_id')->on('procurement_suppliers')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('new_supplier_id', 'fk_posl_new_supplier')
                  ->references('supplier_id')->on('procurement_suppliers')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('switched_by', 'fk_posl_user')
                  ->references('id')->on('admin_users')
                  ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_purchase_order_supplier_switch_logs');
        Schema::dropIfExists('procurement_purchase_payments');
        Schema::dropIfExists('procurement_purchase_order_items');
        Schema::dropIfExists('procurement_purchase_orders');
    }
};
