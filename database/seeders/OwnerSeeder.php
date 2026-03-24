<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('slug', 'owner')->first();

        User::updateOrCreate(
            ['email' => 'sopanha@gmail.com'],
            [
                'name'      => 'sopanha',
                'user_code' => 'Owner',
                'password'  => Hash::make('12345678'),
                'role'      => 'owner',
                'role_id'   => $role ? $role->id : null,
                'status'    => 'active',
            ]
        );
    }
}
