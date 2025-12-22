<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin123'),
            'phone' => '+95 9 000 000 000',
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super-admin');

        // Admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mybusiness.com',
            'password' => Hash::make('password'),
            'phone' => '+95 9 111 111 111',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Cashier user
        $cashier = User::create([
            'name' => 'Cashier',
            'email' => 'cashier@mybusiness.com',
            'password' => Hash::make('password'),
            'phone' => '+95 9 222 222 222',
            'is_active' => true,
        ]);
        $cashier->assignRole('cashier');

        // Staff user
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@mybusiness.com',
            'password' => Hash::make('password'),
            'phone' => '+95 9 333 333 333',
            'is_active' => true,
        ]);
        $staff->assignRole('waiter');
    }
}
