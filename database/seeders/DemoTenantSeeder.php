<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Table;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoTenantSeeder extends Seeder
{
    public function run(): void
    {
        // Create Demo Restaurant Tenant
        $restaurantTenant = Tenant::create([
            'name' => 'Demo Restaurant',
            'business_type' => 'restaurant',
            'subdomain' => 'demo-restaurant',
            'email' => 'demo@restaurant.com',
            'phone' => '09123456789',
            'address' => 'Yangon, Myanmar',
            'status' => 'active',
            'plan' => 'pro',
            'settings' => [
                'currency' => 'MMK',
                'currency_symbol' => 'Ks',
                'tax_enabled' => true,
                'tax_percentage' => 5,
                'service_charge_enabled' => true,
                'service_charge_percentage' => 10,
            ],
        ]);

        // Create Demo Retail Tenant
        $retailTenant = Tenant::create([
            'name' => 'Demo Retail Store',
            'business_type' => 'retail',
            'subdomain' => 'demo-retail',
            'email' => 'demo@retail.com',
            'phone' => '09987654321',
            'address' => 'Mandalay, Myanmar',
            'status' => 'active',
            'plan' => 'pro',
            'settings' => [
                'currency' => 'MMK',
                'currency_symbol' => 'Ks',
                'tax_enabled' => false,
            ],
        ]);

        // ========== RESTAURANT TENANT DATA ==========
        
        // Restaurant Admin
        $restaurantAdmin = User::create([
            'name' => 'Restaurant Admin',
            'email' => 'admin@demo-restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '09111111111',
            'tenant_id' => $restaurantTenant->id,
            'is_active' => true,
        ]);
        $restaurantAdmin->assignRole('admin');

        // Restaurant Cashier
        $restaurantCashier = User::create([
            'name' => 'Restaurant Cashier',
            'email' => 'cashier@demo-restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '09222222222',
            'tenant_id' => $restaurantTenant->id,
            'is_active' => true,
        ]);
        $restaurantCashier->assignRole('cashier');

        // Restaurant Waiter
        $restaurantWaiter = User::create([
            'name' => 'Restaurant Waiter',
            'email' => 'waiter@demo-restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '09333333333',
            'tenant_id' => $restaurantTenant->id,
            'is_active' => true,
        ]);
        $restaurantWaiter->assignRole('waiter');

        // Restaurant Kitchen
        $restaurantKitchen = User::create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@demo-restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '09444444444',
            'tenant_id' => $restaurantTenant->id,
            'is_active' => true,
        ]);
        $restaurantKitchen->assignRole('kitchen');

        // Restaurant Categories
        $foodCategory = Category::create([
            'tenant_id' => $restaurantTenant->id,
            'name' => 'Main Dishes',
            'name_mm' => 'အဓိကဟင်းလျာများ',
            'printer_type' => 'kitchen',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $drinkCategory = Category::create([
            'tenant_id' => $restaurantTenant->id,
            'name' => 'Beverages',
            'name_mm' => 'သောက်စရာများ',
            'printer_type' => 'bar',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $dessertCategory = Category::create([
            'tenant_id' => $restaurantTenant->id,
            'name' => 'Desserts',
            'name_mm' => 'အချိုပွဲများ',
            'printer_type' => 'kitchen',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Restaurant Items
        $restaurantItems = [
            // Main Dishes
            ['category_id' => $foodCategory->id, 'name' => 'Fried Rice', 'name_mm' => 'ထမင်းကြော်', 'price' => 3500],
            ['category_id' => $foodCategory->id, 'name' => 'Chicken Curry', 'name_mm' => 'ကြက်သားဟင်း', 'price' => 5000],
            ['category_id' => $foodCategory->id, 'name' => 'Mohinga', 'name_mm' => 'မုန့်ဟင်းခါး', 'price' => 2500],
            ['category_id' => $foodCategory->id, 'name' => 'Shan Noodles', 'name_mm' => 'ရှမ်းခေါက်ဆွဲ', 'price' => 3000],
            ['category_id' => $foodCategory->id, 'name' => 'BBQ Pork', 'name_mm' => 'ဝက်သားကင်', 'price' => 8000],
            // Beverages
            ['category_id' => $drinkCategory->id, 'name' => 'Myanmar Beer', 'name_mm' => 'မြန်မာဘီယာ', 'price' => 2500],
            ['category_id' => $drinkCategory->id, 'name' => 'Coca Cola', 'name_mm' => 'ကိုကာကိုလာ', 'price' => 1000],
            ['category_id' => $drinkCategory->id, 'name' => 'Fresh Lime Juice', 'name_mm' => 'သံပရာရည်', 'price' => 1500],
            ['category_id' => $drinkCategory->id, 'name' => 'Green Tea', 'name_mm' => 'လက်ဖက်ရည်အစိမ်း', 'price' => 800],
            // Desserts
            ['category_id' => $dessertCategory->id, 'name' => 'Shwe Yin Aye', 'name_mm' => 'ရွှေရင်အေး', 'price' => 2000],
            ['category_id' => $dessertCategory->id, 'name' => 'Mont Let Saung', 'name_mm' => 'မုန့်လက်ဆောင်း', 'price' => 1500],
        ];

        foreach ($restaurantItems as $item) {
            Item::create([
                'tenant_id' => $restaurantTenant->id,
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'name_mm' => $item['name_mm'],
                'price' => $item['price'],
                'is_active' => true,
            ]);
        }

        // Restaurant Tables
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'tenant_id' => $restaurantTenant->id,
                'name' => "Table $i",
                'capacity' => $i <= 5 ? 4 : 6,
                'status' => 'available',
                'sort_order' => $i,
            ]);
        }

        // Restaurant Customers
        $customers = [
            ['name' => 'Aung Aung', 'phone' => '09111000001', 'loyalty_points' => 500],
            ['name' => 'Mya Mya', 'phone' => '09111000002', 'loyalty_points' => 1200],
            ['name' => 'Ko Ko', 'phone' => '09111000003', 'loyalty_points' => 300],
        ];

        foreach ($customers as $customer) {
            Customer::create([
                'tenant_id' => $restaurantTenant->id,
                'name' => $customer['name'],
                'phone' => $customer['phone'],
                'loyalty_points' => $customer['loyalty_points'],
                'is_active' => true,
            ]);
        }

        // ========== RETAIL TENANT DATA ==========
        
        // Retail Admin
        $retailAdmin = User::create([
            'name' => 'Retail Admin',
            'email' => 'admin@demo-retail.com',
            'password' => Hash::make('password'),
            'phone' => '09555555555',
            'tenant_id' => $retailTenant->id,
            'is_active' => true,
        ]);
        $retailAdmin->assignRole('admin');

        // Retail Cashier
        $retailCashier = User::create([
            'name' => 'Retail Cashier',
            'email' => 'cashier@demo-retail.com',
            'password' => Hash::make('password'),
            'phone' => '09666666666',
            'tenant_id' => $retailTenant->id,
            'is_active' => true,
        ]);
        $retailCashier->assignRole('cashier');

        // Retail Categories (No kitchen printer needed)
        $electronicsCategory = Category::create([
            'tenant_id' => $retailTenant->id,
            'name' => 'Electronics',
            'name_mm' => 'လျှပ်စစ်ပစ္စည်းများ',
            'printer_type' => 'receipt',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $groceryCategory = Category::create([
            'tenant_id' => $retailTenant->id,
            'name' => 'Groceries',
            'name_mm' => 'ကုန်စုံပစ္စည်းများ',
            'printer_type' => 'receipt',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Retail Items
        $retailItems = [
            ['category_id' => $electronicsCategory->id, 'name' => 'USB Cable', 'name_mm' => 'USB ကြိုး', 'price' => 5000],
            ['category_id' => $electronicsCategory->id, 'name' => 'Power Bank 10000mAh', 'name_mm' => 'ပါဝါဘဏ်', 'price' => 25000],
            ['category_id' => $electronicsCategory->id, 'name' => 'Earphones', 'name_mm' => 'နားကြပ်', 'price' => 8000],
            ['category_id' => $groceryCategory->id, 'name' => 'Rice 5kg', 'name_mm' => 'ဆန် ၅ ကီလို', 'price' => 12000],
            ['category_id' => $groceryCategory->id, 'name' => 'Cooking Oil 1L', 'name_mm' => 'ဆီ ၁ လီတာ', 'price' => 6000],
            ['category_id' => $groceryCategory->id, 'name' => 'Sugar 1kg', 'name_mm' => 'သကြား ၁ ကီလို', 'price' => 2500],
        ];

        foreach ($retailItems as $item) {
            Item::create([
                'tenant_id' => $retailTenant->id,
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'name_mm' => $item['name_mm'],
                'price' => $item['price'],
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Demo tenants created successfully!');
        $this->command->info('');
        $this->command->info('🍽️  RESTAURANT LOGIN:');
        $this->command->info('   Admin: admin@demo-restaurant.com / password');
        $this->command->info('   Cashier: cashier@demo-restaurant.com / password');
        $this->command->info('   Waiter: waiter@demo-restaurant.com / password');
        $this->command->info('   Kitchen: kitchen@demo-restaurant.com / password');
        $this->command->info('');
        $this->command->info('🛒 RETAIL LOGIN:');
        $this->command->info('   Admin: admin@demo-retail.com / password');
        $this->command->info('   Cashier: cashier@demo-retail.com / password');
    }
}
