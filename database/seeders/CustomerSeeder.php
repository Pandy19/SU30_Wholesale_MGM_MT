<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'customer_code' => 'CUS-B2B-001',
                'name' => 'City Wholesale Mart',
                'type' => 'B2B',
                'phone' => '012345678',
                'email' => 'contact@citywholesale.com',
                'credit_limit' => 5000.00,
                'status' => 'active',
            ],
            [
                'customer_code' => 'CUS-B2B-002',
                'name' => 'Sunrise Supermarket',
                'type' => 'B2B',
                'phone' => '098765432',
                'email' => 'sales@sunrise.com',
                'credit_limit' => 3000.00,
                'status' => 'active',
            ],
            [
                'customer_code' => 'CUS-B2C-001',
                'name' => 'John Doe',
                'type' => 'B2C',
                'phone' => '011223344',
                'email' => 'john.doe@email.com',
                'credit_limit' => 0,
                'status' => 'active',
            ],
            [
                'customer_code' => 'CUS-B2C-002',
                'name' => 'Jane Smith',
                'type' => 'B2C',
                'phone' => '055667788',
                'email' => 'jane.smith@email.com',
                'credit_limit' => 500.00,
                'status' => 'on_hold',
            ],
            [
                'customer_code' => 'CUS-B2B-003',
                'name' => 'Global Logistics Inc',
                'type' => 'B2B',
                'phone' => '077889900',
                'email' => 'info@globallogistics.com',
                'credit_limit' => 10000.00,
                'status' => 'blacklisted',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(['customer_code' => $customer['customer_code']], $customer);
        }
    }
}
