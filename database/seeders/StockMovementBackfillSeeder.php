<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;

class StockMovementBackfillSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        $stocks = Stock::all();

        foreach ($stocks as $stock) {
            // Check if a movement already exists for this stock to avoid duplicates
            $exists = StockMovement::where('product_id', $stock->product_id)
                ->where('stock_location_id', $stock->stock_location_id)
                ->where('type', 'Initial Stock')
                ->exists();

            if (!$exists) {
                StockMovement::create([
                    'product_id' => $stock->product_id,
                    'stock_location_id' => $stock->stock_location_id,
                    'type' => 'Initial Stock',
                    'quantity' => $stock->quantity,
                    'balance_after' => $stock->quantity,
                    'reference' => 'SYSTEM-INIT',
                    'user_id' => $adminId,
                    'notes' => 'Backfilled from existing warehouse stock',
                    'created_at' => $stock->created_at,
                    'updated_at' => $stock->updated_at,
                ]);
            }
        }
    }
}
