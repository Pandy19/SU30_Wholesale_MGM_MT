<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SU30WholesaleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        DB::table('users')->insert([
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => Hash::make('12345678'), 'role' => 'admin', 'status' => 'active', 'created_at' => now()],
            ['name' => 'Staff User', 'email' => 'staff@example.com', 'password' => Hash::make('12345678'), 'role' => 'staff', 'status' => 'active', 'created_at' => now()],
        ]);

        // 2. Categories
        DB::table('categories')->insert([
            ['code' => 'CAT-001', 'name' => 'Mobile Phone', 'description' => 'Phones and accessories', 'status' => 'active', 'created_at' => now()],
            ['code' => 'CAT-002', 'name' => 'Smart TV', 'description' => 'TV and display products', 'status' => 'active', 'created_at' => now()],
            ['code' => 'CAT-003', 'name' => 'Laptops', 'description' => 'Computing devices', 'status' => 'active', 'created_at' => now()],
        ]);

        // 3. Brands
        DB::table('brands')->insert([
            ['name' => 'Apple', 'category_id' => 1, 'logo' => null, 'status' => 'active', 'created_at' => now()],
            ['name' => 'Samsung', 'category_id' => 1, 'logo' => null, 'status' => 'active', 'created_at' => now()],
            ['name' => 'Sony', 'category_id' => 2, 'logo' => null, 'status' => 'active', 'created_at' => now()],
        ]);

        // 4. Products
        DB::table('products')->insert([
            [
                'sku' => 'IP15P-256', 
                'name' => 'iPhone 15 Pro 256GB', 
                'category_id' => 1, 
                'brand_id' => 1, 
                'image' => 'https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1', 
                'specs' => '6.1" OLED, A17 Pro Chip', 
                'status' => 'available', 
                'created_at' => now()
            ],
            [
                'sku' => 'SAM-TV-55', 
                'name' => 'Samsung Smart TV 55"', 
                'category_id' => 2, 
                'brand_id' => 2, 
                'image' => 'https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png', 
                'specs' => '4K UHD, HDR10+', 
                'status' => 'available', 
                'created_at' => now()
            ],
        ]);

        // 5. Suppliers
        DB::table('suppliers')->insert([
            [
                'code' => 'SUP-001', 
                'company_name' => 'Apple Distributor Co.', 
                'contact_person' => 'John Supplier', 
                'phone' => '012345678', 
                'email' => 'supplier1@example.com', 
                'address' => 'Phnom Penh', 
                'payment_term' => 'Net 15 Days', 
                'lead_time_days' => 5, 
                'status' => 'active', 
                'brand_id' => 1, 
                'created_at' => now()
            ],
            [
                'code' => 'SUP-002', 
                'company_name' => 'Samsung Wholesale Co.', 
                'contact_person' => 'Sok Supplier', 
                'phone' => '098765432', 
                'email' => 'supplier2@example.com', 
                'address' => 'Phnom Penh', 
                'payment_term' => 'Immediate', 
                'lead_time_days' => 3, 
                'status' => 'active', 
                'brand_id' => 2, 
                'created_at' => now()
            ],
        ]);

        // 6. Supplier Product Offers
        DB::table('supplier_products')->insert([
            ['supplier_id' => 1, 'product_id' => 1, 'price' => 950.00, 'available_qty' => 40, 'created_at' => now()],
            ['supplier_id' => 2, 'product_id' => 2, 'price' => 720.00, 'available_qty' => 20, 'created_at' => now()],
        ]);

        // 7. Stock Locations
        DB::table('stock_locations')->insert([
            ['name' => 'Mobile Shelf A1', 'code' => 'MS-A1', 'created_at' => now()],
            ['name' => 'TV Shelf T1', 'code' => 'TV-T1', 'created_at' => now()],
        ]);

        // 8. Stocks
        DB::table('stocks')->insert([
            ['product_id' => 1, 'stock_location_id' => 1, 'quantity' => 10, 'average_cost' => 950.00, 'created_at' => now()],
            ['product_id' => 2, 'stock_location_id' => 2, 'quantity' => 5, 'average_cost' => 720.00, 'created_at' => now()],
        ]);

        // 9. Customers
        DB::table('customers')->insert([
            ['customer_code' => 'CUS-B2C-001', 'name' => 'Walk-in Customer', 'type' => 'B2C', 'phone' => '011111111', 'email' => 'cust@example.com', 'credit_limit' => null, 'status' => 'active', 'created_at' => now()],
            ['customer_code' => 'CUS-B2B-001', 'name' => 'Phnom Penh Electronics', 'type' => 'B2B', 'phone' => '012999888', 'email' => 'ppe@example.com', 'credit_limit' => 5000.00, 'status' => 'active', 'created_at' => now()],
        ]);

        // 10. Purchase Orders (To populate price history and history views)
        DB::table('purchase_orders')->insert([
            ['po_number' => 'PO-2025-001', 'supplier_id' => 1, 'order_date' => '2025-01-10', 'total_amount' => 9500.00, 'status' => 'completed', 'payment_status' => 'paid', 'created_at' => '2025-01-10 10:00:00'],
        ]);

        DB::table('purchase_order_items')->insert([
            ['purchase_order_id' => 1, 'product_id' => 1, 'quantity' => 10, 'unit_cost' => 950.00, 'line_total' => 9500.00, 'received_qty' => 10, 'created_at' => '2025-01-10 10:00:00'],
        ]);

        // 11. Sales Orders
        DB::table('sales_orders')->insert([
            [
                'order_number' => 'SO-2025-001', 
                'customer_id' => 1, 
                'order_date' => now(), 
                'total_amount' => 1200.00, 
                'status' => 'completed', 
                'payment_method' => 'cash', 
                'payment_status' => 'paid', 
                'created_by' => 1, 
                'created_at' => now()
            ],
        ]);

        DB::table('sales_order_items')->insert([
            ['sales_order_id' => 1, 'product_id' => 1, 'quantity' => 1, 'unit_price' => 1200.00, 'line_total' => 1200.00, 'created_at' => now()],
        ]);
    }
}
