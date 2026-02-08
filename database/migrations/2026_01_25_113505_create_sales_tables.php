<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_customers', function (Blueprint $table) {
            $table->bigIncrements('customer_id');
            $table->string('customer_code', 30)->unique();
            $table->string('name', 200);
            $table->enum('customer_type', ['B2B','B2C']);
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->decimal('outstanding_amount', 12, 2)->nullable();
            $table->enum('payment_rule', ['Credit Allowed','Full Payment Only'])->nullable();
            $table->enum('status', ['Active','On Hold','Blacklisted'])->default('Active');
            $table->timestamps();
        });

        Schema::create('sales_orders', function (Blueprint $table) {
            $table->bigIncrements('sales_order_id');
            $table->string('sales_order_code', 30)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->enum('customer_type', ['B2B','B2C']);
            $table->date('sale_date')->nullable();

            $table->enum('payment_method', ['Cash','Bank Transfer','Card','Digital Wallet'])->nullable();
            $table->enum('payment_terms', ['Immediate','Net 7 Days','Net 15 Days','Net 30 Days'])->nullable();
            $table->enum('payment_status', ['Paid','Unpaid','Partial'])->default('Unpaid');

            $table->decimal('subtotal_amount', 12, 2)->nullable();
            $table->decimal('tax_amount', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2);

            $table->text('payment_note')->nullable();
            $table->string('created_by', 100);
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('sales_customers');
        });

        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->bigIncrements('sales_order_item_id');
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('product_id');

            $table->string('sku_snapshot', 60);
            $table->string('product_name_snapshot', 200);
            $table->string('description_snapshot', 255)->nullable();

            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);

            $table->timestamps();

            $table->foreign('sales_order_id')->references('sales_order_id')->on('sales_orders');
            $table->foreign('product_id')->references('product_id')->on('inventory_products');
        });

        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->bigIncrements('sales_invoice_id');
            $table->string('sales_invoice_code', 30)->unique();

            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('customer_type', ['B2B','B2C'])->nullable();

            $table->date('invoice_date');
            $table->date('due_date')->nullable();

            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2)->default(0);

            $table->enum('payment_status', ['Paid','Partial','Unpaid','Overdue'])->default('Unpaid');
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_terms', 50)->nullable();

            $table->string('created_by', 100);
            $table->timestamps();

            $table->foreign('sales_order_id')->references('sales_order_id')->on('sales_orders');
            $table->foreign('customer_id')->references('customer_id')->on('sales_customers');
        });

        Schema::create('sales_invoice_items', function (Blueprint $table) {
            $table->bigIncrements('invoice_item_id');
            $table->unsignedBigInteger('sales_invoice_id');
            $table->unsignedBigInteger('product_id');

            $table->string('product_name', 200);
            $table->string('sku', 100);
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sales_invoice_id')->references('sales_invoice_id')->on('sales_invoices');
            $table->foreign('product_id')->references('product_id')->on('inventory_products');
        });

        Schema::create('sales_customer_payments', function (Blueprint $table) {
            $table->bigIncrements('customer_payment_id');
            $table->string('payment_code', 30)->unique();

            $table->unsignedBigInteger('sales_invoice_id');
            $table->unsignedBigInteger('customer_id');

            $table->decimal('payment_amount', 12, 2);
            $table->enum('payment_method', ['Cash','Bank Transfer','Digital Wallet','Cheque']);
            $table->date('payment_date');

            $table->string('reference_note', 255)->nullable();
            $table->string('received_by', 100);

            $table->timestamps();

            $table->foreign('sales_invoice_id')->references('sales_invoice_id')->on('sales_invoices');
            $table->foreign('customer_id')->references('customer_id')->on('sales_customers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_customer_payments');
        Schema::dropIfExists('sales_invoice_items');
        Schema::dropIfExists('sales_invoices');
        Schema::dropIfExists('sales_order_items');
        Schema::dropIfExists('sales_orders');
        Schema::dropIfExists('sales_customers');
    }
};
