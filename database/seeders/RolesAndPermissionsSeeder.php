<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User & Role Management
            'manage users',
            'manage roles',
            
            // Menu & Products
            'manage menu',
            'manage products',
            'manage categories',
            'manage inventory',
            
            // Tables (Restaurant/Cafe)
            'manage tables',
            
            // Orders & Sales
            'create orders',
            'edit orders',
            'cancel orders',
            'process payments',
            'view orders',
            
            // Reports
            'view reports',
            'view sales reports',
            'view inventory reports',
            
            // Settings
            'manage settings',
            'manage expenses',
            
            // Display Screens
            'view kitchen',
            'view bar',
            'view nan_pyar',
            
            // Pharmacy specific
            'manage prescriptions',
            
            // Salon specific
            'manage appointments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ============================================
        // PLATFORM LEVEL ROLES
        // ============================================
        
        // Super Admin (SaaS Platform Owner)
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // ============================================
        // TENANT LEVEL ROLES (Business Owner/Manager)
        // ============================================
        
        // Owner (Tenant Admin - Business Owner)
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        $ownerRole->givePermissionTo(Permission::all());

        // Manager (Store/Shift Manager)
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'manage users',
            'manage menu',
            'manage products',
            'manage categories',
            'manage inventory',
            'manage tables',
            'create orders',
            'edit orders',
            'cancel orders',
            'process payments',
            'view orders',
            'view reports',
            'view sales reports',
            'view inventory reports',
            'manage expenses',
        ]);

        // ============================================
        // STAFF LEVEL ROLES (Generic)
        // ============================================
        
        // Cashier (All business types)
        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
        $cashierRole->givePermissionTo([
            'create orders',
            'edit orders',
            'process payments',
            'view orders',
            'view reports',
            'manage tables',
        ]);

        // Inventory Staff
        $inventoryRole = Role::firstOrCreate(['name' => 'inventory']);
        $inventoryRole->givePermissionTo([
            'manage inventory',
            'manage products',
            'view inventory reports',
        ]);

        // General Staff
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'create orders',
            'view orders',
        ]);

        // ============================================
        // RESTAURANT/F&B SPECIFIC ROLES
        // ============================================
        
        // Waiter
        $waiterRole = Role::firstOrCreate(['name' => 'waiter']);
        $waiterRole->givePermissionTo([
            'create orders',
            'edit orders',
            'view orders',
            'manage tables',
        ]);

        // Kitchen Staff
        $kitchenRole = Role::firstOrCreate(['name' => 'kitchen']);
        $kitchenRole->givePermissionTo(['view kitchen', 'view orders']);

        // Bar Staff
        $barRole = Role::firstOrCreate(['name' => 'bar']);
        $barRole->givePermissionTo(['view bar', 'view orders']);

        // Nan Pyar (Bread Station)
        $nanPyarRole = Role::firstOrCreate(['name' => 'nan_pyar']);
        $nanPyarRole->givePermissionTo(['view nan_pyar', 'view orders']);

        // ============================================
        // CAFE SPECIFIC ROLES
        // ============================================
        
        // Barista
        $baristaRole = Role::firstOrCreate(['name' => 'barista']);
        $baristaRole->givePermissionTo([
            'view kitchen',
            'view orders',
            'create orders',
        ]);

        // ============================================
        // RETAIL SPECIFIC ROLES
        // ============================================
        
        // Sales Staff
        $salesRole = Role::firstOrCreate(['name' => 'sales']);
        $salesRole->givePermissionTo([
            'create orders',
            'view orders',
            'manage products',
        ]);

        // ============================================
        // PHARMACY SPECIFIC ROLES
        // ============================================
        
        // Pharmacist
        $pharmacistRole = Role::firstOrCreate(['name' => 'pharmacist']);
        $pharmacistRole->givePermissionTo([
            'create orders',
            'view orders',
            'manage prescriptions',
            'manage inventory',
        ]);

        // ============================================
        // SALON SPECIFIC ROLES
        // ============================================
        
        // Stylist
        $stylistRole = Role::firstOrCreate(['name' => 'stylist']);
        $stylistRole->givePermissionTo([
            'create orders',
            'view orders',
            'manage appointments',
        ]);

        // ============================================
        // LEGACY SUPPORT - Keep 'admin' as alias for 'owner'
        // ============================================
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
