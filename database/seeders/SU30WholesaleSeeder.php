<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SU30WholesaleSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        DB::table('admin_users')->insert([
            ['name'=>'Admin User','email'=>'admin@example.com','password'=>bcrypt('12345678'),'status'=>'Active'],
            ['name'=>'Staff User','email'=>'staff@example.com','password'=>bcrypt('12345678'),'status'=>'Active'],
        ]);

        DB::table('admin_roles')->insert([
            ['name'=>'admin','label'=>'Admin','description'=>'System administrator','status'=>'Active'],
            ['name'=>'staff','label'=>'Staff','description'=>'Backoffice staff','status'=>'Active'],
            ['name'=>'supplier','label'=>'Supplier','description'=>'Supplier portal role','status'=>'Active'],
        ]);

        DB::table('admin_user_roles')->insert([
            ['user_id'=>1,'role_id'=>1,'assigned_by'=>1],
            ['user_id'=>2,'role_id'=>2,'assigned_by'=>1],
        ]);

        // Procurement: categories/brands/suppliers
        DB::table('procurement_categories')->insert([
            ['category_code'=>'CAT-001','category_name'=>'Mobile Phone','category_slug'=>'mobile-phone','description'=>'Phones and accessories','status'=>'Active'],
            ['category_code'=>'CAT-002','category_name'=>'Smart TV','category_slug'=>'smart-tv','description'=>'TV and display products','status'=>'Active'],
        ]);

        DB::table('procurement_brands')->insert([
            ['brand_name'=>'Apple','brand_slug'=>'apple','logo_path'=>'/logos/apple.png','status'=>'Active'],
            ['brand_name'=>'Samsung','brand_slug'=>'samsung','logo_path'=>'/logos/samsung.png','status'=>'Active'],
        ]);

        DB::table('procurement_suppliers')->insert([
            [
                'brand_id'=>1,'category_id'=>1,'supplier_code'=>'SUP-001','company_name'=>'Apple Distributor Co.',
                'contact_person'=>'John Supplier','phone'=>'012345678','email'=>'supplier1@example.com','address'=>'Phnom Penh',
                'payment_term'=>'Net 15 Days','lead_time_days'=>5,'status'=>'Active'
            ],
            [
                'brand_id'=>2,'category_id'=>2,'supplier_code'=>'SUP-002','company_name'=>'Samsung Wholesale Co.',
                'contact_person'=>'Sok Supplier','phone'=>'098765432','email'=>'supplier2@example.com','address'=>'Phnom Penh',
                'payment_term'=>'Immediate','lead_time_days'=>3,'status'=>'Active'
            ],
        ]);

        // Products
        DB::table('inventory_products')->insert([
            ['sku'=>'IP15P-256','name'=>'iPhone 15 Pro 256GB','brand_id'=>1,'category_id'=>1,'image_url'=>null,'specs_text'=>null,'status'=>'Available'],
            ['sku'=>'SAM-TV-55','name'=>'Samsung Smart TV 55"','brand_id'=>2,'category_id'=>2,'image_url'=>null,'specs_text'=>null,'status'=>'Available'],
        ]);

        // Customer + simple sale
        DB::table('sales_customers')->insert([
            ['customer_code'=>'CUS-B2C-001','name'=>'Walk-in Customer','customer_type'=>'B2C','phone'=>'011111111','email'=>'cust@example.com','status'=>'Active'],
        ]);
    }
}
