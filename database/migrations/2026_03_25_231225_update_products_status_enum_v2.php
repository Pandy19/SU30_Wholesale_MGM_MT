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
        // For MySQL, use DB::statement for ENUM change
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('available', 'limited', 'unavailable', 'hidden', 'inactive') DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('available', 'limited', 'unavailable') DEFAULT 'available'");
    }
};
