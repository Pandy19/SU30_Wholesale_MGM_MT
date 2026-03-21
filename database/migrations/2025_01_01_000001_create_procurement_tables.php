<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // SUP-001
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('payment_term')->default('Net 30 Days');
            $table->integer('lead_time_days')->default(0);
            $table->string('document')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('brand_id')->nullable()->constrained('brands'); // Per view, supplier linked to brand?
            $table->timestamps();
        });

        // 2. Supplier Product Prices (For "Supplier Offers" view)
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->integer('available_qty')->default(0); // From view "Available: 40"
            $table->timestamps();
        });

        // 3. Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // PO-2025-001
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->date('order_date');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'ordered', 'shipped', 'delivered', 'received', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('line_total', 12, 2);
            $table->integer('received_qty')->default(0); // For GRN tracking
            $table->timestamps();
        });

        // 4. Goods Receiving Notes (GRN)
        Schema::create('goods_receivings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders');
            $table->date('received_date')->useCurrent();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('remarks')->nullable(); // Rejection reason
            $table->timestamps();
        });

        Schema::create('goods_receiving_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receiving_id')->constrained('goods_receivings')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('ordered_qty');
            $table->integer('received_qty');
            $table->integer('accepted_qty')->default(0);
            $table->integer('rejected_qty')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receiving_items');
        Schema::dropIfExists('goods_receivings');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('supplier_products');
        Schema::dropIfExists('suppliers');
    }
};
