<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoodsReceivingSeeder extends Seeder
{
    public function run(): void
    {
        // Get some existing data
        $po = DB::table('purchase_orders')->where('po_number', 'PO-2025-001')->first();
        if (!$po) return;

        $poItems = DB::table('purchase_order_items')->where('purchase_order_id', $po->id)->get();
        $admin = DB::table('users')->where('role', 'admin')->first();

        // 1. Pending Goods Receiving
        $gr1Id = DB::table('goods_receivings')->insertGetId([
            'purchase_order_id' => $po->id,
            'received_date' => now()->subDays(2),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($poItems as $item) {
            DB::table('goods_receiving_items')->insert([
                'goods_receiving_id' => $gr1Id,
                'product_id' => $item->product_id,
                'ordered_qty' => $item->quantity,
                'received_qty' => $item->quantity,
                'accepted_qty' => 0,
                'rejected_qty' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Accepted Goods Receiving
        $gr2Id = DB::table('goods_receivings')->insertGetId([
            'purchase_order_id' => $po->id,
            'received_date' => now()->subDays(5),
            'approved_by' => $admin->id,
            'status' => 'accepted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($poItems as $item) {
            DB::table('goods_receiving_items')->insert([
                'goods_receiving_id' => $gr2Id,
                'product_id' => $item->product_id,
                'ordered_qty' => $item->quantity,
                'received_qty' => $item->quantity,
                'accepted_qty' => $item->quantity,
                'rejected_qty' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
