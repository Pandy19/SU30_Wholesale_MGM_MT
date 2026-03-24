<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin',      'slug' => 'admin'],
            ['name' => 'Staff',      'slug' => 'staff'],
            ['name' => 'Inspector',  'slug' => 'inspector'],
            ['name' => 'Accountant', 'slug' => 'accountant'],
            ['name' => 'Supplier',   'slug' => 'supplier'],
            ['name' => 'Owner',      'slug' => 'owner'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
