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
        // For MySQL, we need to use a raw statement to update ENUM values safely
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive', 'pending') DEFAULT 'active'");
        DB::statement("ALTER TABLE suppliers MODIFY COLUMN status ENUM('active', 'inactive', 'pending') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
        DB::statement("ALTER TABLE suppliers MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
    }
};
