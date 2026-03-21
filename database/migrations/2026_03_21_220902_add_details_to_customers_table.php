<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->text('address')->nullable()->after('email');
            $table->string('tax_number')->nullable()->after('address');
            $table->string('contact_person')->nullable()->after('name');
            $table->string('payment_terms')->nullable()->after('credit_limit');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['address', 'tax_number', 'contact_person', 'payment_terms']);
        });
    }
};
