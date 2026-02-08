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
        Schema::table('procurement_brands', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('brand_slug');
            // optional foreign key (only if your category_id is BIGINT)
            // $table->foreign('category_id')->references('category_id')->on('procurement_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurement_brands', function (Blueprint $table) {
            // $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
