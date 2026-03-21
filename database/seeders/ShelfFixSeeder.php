<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\StockLocation;
use Illuminate\Support\Facades\DB;

class ShelfFixSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StockLocation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = Category::all();
        $brands = Brand::all();

        foreach ($categories as $cat) {
            foreach ($brands as $brand) {
                for ($i = 1; $i <= 3; $i++) {
                    StockLocation::create([
                        'name' => $cat->name . ' ' . $brand->name . ' Shelf A' . $i,
                        'code' => strtoupper(substr($cat->name, 0, 1) . substr($brand->name, 0, 1)) . '-A' . $i,
                        'category_id' => $cat->id,
                        'brand_id' => $brand->id,
                        'max_capacity' => 50
                    ]);
                }
            }
        }
    }
}
