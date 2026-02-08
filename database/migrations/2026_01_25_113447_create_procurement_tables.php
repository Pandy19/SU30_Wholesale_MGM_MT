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
        Schema::create('procurement_categories', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->string('category_code', 30)->unique();
            $table->string('category_name', 120);
            $table->string('category_slug', 160)->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
        });

        Schema::create('procurement_brands', function (Blueprint $table) {
            $table->bigIncrements('brand_id');
            $table->string('brand_name', 120);
            $table->string('brand_slug', 160)->unique();
            $table->string('logo_path', 255)->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
        });

        Schema::create('procurement_suppliers', function (Blueprint $table) {
            $table->bigIncrements('supplier_id');

            // FK columns
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');

            $table->string('supplier_code', 30)->unique();
            $table->string('company_name', 180);
            $table->string('contact_person', 120);
            $table->string('phone', 30);
            $table->string('email', 180);
            $table->string('address', 255);

            $table->enum('payment_term', ['Immediate','Net 7 Days','Net 15 Days','Net 30 Days','Net 60 Days'])->default('Immediate');
            $table->integer('lead_time_days')->default(3);
            $table->string('document_path', 255)->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');

            $table->timestamps();

            // Indexes (good for joins + FK performance)
            $table->index('brand_id', 'idx_sup_brand');
            $table->index('category_id', 'idx_sup_category');

            // Short FK names to avoid MySQL 64-char limit
            $table->foreign('brand_id', 'fk_sup_brand')
                  ->references('brand_id')->on('procurement_brands')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('category_id', 'fk_sup_category')
                  ->references('category_id')->on('procurement_categories')
                  ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_suppliers');
        Schema::dropIfExists('procurement_brands');
        Schema::dropIfExists('procurement_categories');
    }
};
