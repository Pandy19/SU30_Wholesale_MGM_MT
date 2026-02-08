<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports_sales_daily_summary', function (Blueprint $table) {
            $table->date('report_date')->primary();
            $table->decimal('total_sales', 14, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('b2b_sales', 14, 2)->default(0);
            $table->decimal('b2c_sales', 14, 2)->default(0);
            $table->decimal('total_profit', 14, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('reports_profit_product_summary', function (Blueprint $table) {
            $table->date('report_date');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('product_id');

            $table->integer('total_qty_sold')->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('total_revenue', 14, 2)->default(0);
            $table->decimal('total_profit', 14, 2)->default(0);
            $table->decimal('profit_margin', 6, 2)->default(0);

            $table->timestamp('created_at')->useCurrent();

            $table->primary(['report_date', 'product_id']);

            $table->foreign('category_id')->references('category_id')->on('procurement_categories');
            $table->foreign('brand_id')->references('brand_id')->on('procurement_brands');
            $table->foreign('product_id')->references('product_id')->on('inventory_products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports_profit_product_summary');
        Schema::dropIfExists('reports_sales_daily_summary');
    }
};
