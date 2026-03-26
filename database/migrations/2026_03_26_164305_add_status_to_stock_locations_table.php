<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_locations', function (Blueprint $blueprint) {
            $blueprint->enum('status', ['active', 'inactive'])->default('active')->after('max_capacity');
        });
    }

    public function down(): void
    {
        Schema::table('stock_locations', function (Blueprint $blueprint) {
            $blueprint->dropColumn('status');
        });
    }
};
