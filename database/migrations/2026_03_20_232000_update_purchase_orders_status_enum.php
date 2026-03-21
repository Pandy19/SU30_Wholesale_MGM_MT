<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL to modify ENUM is safer for existing data in MySQL
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'ordered', 'shipped', 'delivered', 'received', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'ordered', 'received', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
