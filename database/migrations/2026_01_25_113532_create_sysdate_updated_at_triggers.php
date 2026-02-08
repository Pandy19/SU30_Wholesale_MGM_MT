<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'admin_users','admin_roles','supplier_profiles',
            'procurement_categories','procurement_brands','procurement_suppliers',
            'inventory_products','inventory_stock_locations','inventory_goods_receiving',
            'inventory_goods_receiving_items','inventory_stock','inventory_stock_ledger',
            'procurement_purchase_orders','procurement_purchase_order_items',
            'procurement_purchase_payments','procurement_supplier_disputes',
            'sales_customers','sales_orders','sales_order_items','sales_invoices',
            'sales_customer_payments'
        ];

        foreach ($tables as $t) {
            // Drop trigger even if table does not exist (safe)
            DB::unprepared("DROP TRIGGER IF EXISTS trg_{$t}_bu;");

            // Only create trigger if table exists
            if (Schema::hasTable($t)) {
                DB::unprepared("
                    CREATE TRIGGER trg_{$t}_bu
                    BEFORE UPDATE ON {$t}
                    FOR EACH ROW
                    SET NEW.updated_at = SYSDATE();
                ");
            }
        }

        // Special: inventory_stock last_updated
        DB::unprepared("DROP TRIGGER IF EXISTS trg_inventory_stock_last_updated_bu;");

        if (Schema::hasTable('inventory_stock')) {
            DB::unprepared("
                CREATE TRIGGER trg_inventory_stock_last_updated_bu
                BEFORE UPDATE ON inventory_stock
                FOR EACH ROW
                SET NEW.last_updated = SYSDATE();
            ");
        }
    }

    public function down(): void
    {
        $tables = [
            'admin_users','admin_roles','supplier_profiles',
            'procurement_categories','procurement_brands','procurement_suppliers',
            'inventory_products','inventory_stock_locations','inventory_goods_receiving',
            'inventory_goods_receiving_items','inventory_stock','inventory_stock_ledger',
            'procurement_purchase_orders','procurement_purchase_order_items',
            'procurement_purchase_payments','procurement_supplier_disputes',
            'sales_customers','sales_orders','sales_order_items','sales_invoices',
            'sales_customer_payments'
        ];

        foreach ($tables as $t) {
            DB::unprepared("DROP TRIGGER IF EXISTS trg_{$t}_bu;");
        }

        DB::unprepared("DROP TRIGGER IF EXISTS trg_inventory_stock_last_updated_bu;");
    }
};
