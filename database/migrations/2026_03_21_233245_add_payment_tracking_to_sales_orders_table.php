<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('order_date');
            $table->decimal('paid_amount', 15, 2)->default(0)->after('total_amount');
            $table->text('payment_note')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'paid_amount', 'payment_note']);
        });
    }
};
