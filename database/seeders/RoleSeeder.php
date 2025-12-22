<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Order permissions
            'view orders',
            'create orders',
            'update orders',
            'delete orders',
            'complete orders',
            
            // Item permissions
            'view items',
            'create items',
            'update items',
            'delete items',
            
            // Table permissions
            'view tables',
            'create tables',
            'update tables',
            'delete tables',
            
            // User permissions
            'view users',
            'create users',
            'update users',
            'delete users',
            
            // Report permissions
            'view reports',
            'view dashboard',
            
            // Settings permissions
            'view settings',
            'update settings',
            
            // Printer permissions
            'view printers',
            'update printers',
            
            // Receipt permissions
            'print receipts',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Cashier role
        $cashierRole = Role::create(['name' => 'cashier']);
        $cashierRole->givePermissionTo([
            'view orders',
            'create orders',
            'update orders',
            'complete orders',
            'view items',
            'view tables',
            'print receipts',
        ]);

        // Waiter role
        $waiterRole = Role::create(['name' => 'waiter']);
        $waiterRole->givePermissionTo([
            'view orders',
            'create orders',
            'update orders',
            'delete orders',
            'view items',
            'view tables',
        ]);

        // Kitchen role
        $kitchenRole = Role::create(['name' => 'kitchen']);
        $kitchenRole->givePermissionTo([
            'view orders',
        ]);

        // Bar role
        $BarRole = Role::create(['name' => 'Bar']);
        $BarRole->givePermissionTo([
            'view orders',
        ]);
    }
}
