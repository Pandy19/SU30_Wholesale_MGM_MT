<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goods_receiving_items', function (Blueprint $table) {
            $table->string('resolution_status')->default('Pending'); // Pending, Returned, Replaced, Refunded, Closed
            $table->text('resolution_notes')->nullable();
            $table->boolean('is_stocked')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('goods_receiving_items', function (Blueprint $table) {
            $table->dropColumn(['resolution_status', 'resolution_notes']);
        });
    }
};
