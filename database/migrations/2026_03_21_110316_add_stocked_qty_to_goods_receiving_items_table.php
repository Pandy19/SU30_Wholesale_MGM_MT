<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goods_receiving_items', function (Blueprint $table) {
            $table->integer('stocked_qty')->default(0)->after('accepted_qty');
        });
    }

    public function down(): void
    {
        Schema::table('goods_receiving_items', function (Blueprint $table) {
            $table->dropColumn('stocked_qty');
        });
    }
};
