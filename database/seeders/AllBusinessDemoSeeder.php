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

class AllBusinessDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ========== 1. RETAIL STORE ==========
        $retailTenant = Tenant::where('subdomain', 'demo-retail')->first();
        
        if (!$retailTenant) {
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
        }

        // Check if users exist
        if (!User::where('email', 'admin@demo-retail.com')->exists()) {
            $retailAdmin = User::create([
                'name' => 'Retail Admin',
                'email' => 'admin@demo-retail.com',
                'password' => Hash::make('password'),
                'phone' => '09555555555',
                'tenant_id' => $retailTenant->id,
                'is_active' => true,
            ]);
            $retailAdmin->assignRole('admin');

            $retailCashier = User::create([
                'name' => 'Retail Cashier',
                'email' => 'cashier@demo-retail.com',
                'password' => Hash::make('password'),
                'phone' => '09666666666',
                'tenant_id' => $retailTenant->id,
                'is_active' => true,
            ]);
            $retailCashier->assignRole('cashier');
        }

        // Retail Categories & Items
        if (!Category::where('tenant_id', $retailTenant->id)->exists()) {
            $electronics = Category::create([
                'tenant_id' => $retailTenant->id,
                'name' => 'Electronics',
                'name_mm' => 'လျှပ်စစ်ပစ္စည်းများ',
                'printer_type' => 'receipt',
                'sort_order' => 1,
                'is_active' => true,
            ]);

            $groceries = Category::create([
                'tenant_id' => $retailTenant->id,
                'name' => 'Groceries',
                'name_mm' => 'ကုန်စုံပစ္စည်းများ',
                'printer_type' => 'receipt',
                'sort_order' => 2,
                'is_active' => true,
            ]);

            $retailItems = [
                ['category_id' => $electronics->id, 'name' => 'USB Cable', 'name_mm' => 'USB ကြိုး', 'price' => 5000],
                ['category_id' => $electronics->id, 'name' => 'Power Bank 10000mAh', 'name_mm' => 'ပါဝါဘဏ်', 'price' => 25000],
                ['category_id' => $electronics->id, 'name' => 'Earphones', 'name_mm' => 'နားကြပ်', 'price' => 8000],
                ['category_id' => $electronics->id, 'name' => 'Phone Charger', 'name_mm' => 'ဖုန်းအားသွင်းကြိုး', 'price' => 3500],
                ['category_id' => $groceries->id, 'name' => 'Rice 5kg', 'name_mm' => 'ဆန် ၅ ကီလို', 'price' => 12000],
                ['category_id' => $groceries->id, 'name' => 'Cooking Oil 1L', 'name_mm' => 'ဆီ ၁ လီတာ', 'price' => 6000],
                ['category_id' => $groceries->id, 'name' => 'Sugar 1kg', 'name_mm' => 'သကြား ၁ ကီလို', 'price' => 2500],
                ['category_id' => $groceries->id, 'name' => 'Salt 500g', 'name_mm' => 'ဆား', 'price' => 800],
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
        }

        // ========== 2. PHARMACY ==========
        $pharmacyTenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo-pharmacy'],
            [
                'name' => 'Demo Pharmacy',
                'business_type' => 'pharmacy',
                'email' => 'demo@pharmacy.com',
                'phone' => '09111222333',
                'address' => 'Yangon, Myanmar',
                'status' => 'active',
                'plan' => 'pro',
                'settings' => [
                    'currency' => 'MMK',
                    'currency_symbol' => 'Ks',
                    'tax_enabled' => false,
                    'expiry_tracking' => true,
                ],
            ]
        );

        if (!User::where('email', 'admin@demo-pharmacy.com')->exists()) {
            $pharmacyAdmin = User::create([
                'name' => 'Pharmacy Admin',
                'email' => 'admin@demo-pharmacy.com',
                'password' => Hash::make('password'),
                'phone' => '09777777777',
                'tenant_id' => $pharmacyTenant->id,
                'is_active' => true,
            ]);
            $pharmacyAdmin->assignRole('admin');

            $pharmacyCashier = User::create([
                'name' => 'Pharmacy Cashier',
                'email' => 'cashier@demo-pharmacy.com',
                'password' => Hash::make('password'),
                'phone' => '09888888888',
                'tenant_id' => $pharmacyTenant->id,
                'is_active' => true,
            ]);
            $pharmacyCashier->assignRole('cashier');
        }

        if (!Category::where('tenant_id', $pharmacyTenant->id)->exists()) {
            $medicines = Category::create([
                'tenant_id' => $pharmacyTenant->id,
                'name' => 'Medicines',
                'name_mm' => 'ဆေးဝါးများ',
                'printer_type' => 'receipt',
                'sort_order' => 1,
                'is_active' => true,
            ]);

            $supplements = Category::create([
                'tenant_id' => $pharmacyTenant->id,
                'name' => 'Supplements',
                'name_mm' => 'အားဆေးများ',
                'printer_type' => 'receipt',
                'sort_order' => 2,
                'is_active' => true,
            ]);

            $pharmacyItems = [
                ['category_id' => $medicines->id, 'name' => 'Paracetamol 500mg', 'name_mm' => 'ပါရာစီတမော', 'price' => 500],
                ['category_id' => $medicines->id, 'name' => 'Amoxicillin 500mg', 'name_mm' => 'အမောက်စီစလင်', 'price' => 1500],
                ['category_id' => $medicines->id, 'name' => 'Ibuprofen 400mg', 'name_mm' => 'အိုင်ဗူပရိုဖင်', 'price' => 800],
                ['category_id' => $medicines->id, 'name' => 'Cough Syrup', 'name_mm' => 'ချောင်းဆိုးပျောက်ဆေး', 'price' => 3500],
                ['category_id' => $supplements->id, 'name' => 'Vitamin C 1000mg', 'name_mm' => 'ဗီတာမင် စီ', 'price' => 5000],
                ['category_id' => $supplements->id, 'name' => 'Multivitamin', 'name_mm' => 'ဗီတာမင်စုံ', 'price' => 8000],
                ['category_id' => $supplements->id, 'name' => 'Calcium + D3', 'name_mm' => 'ကယ်လ်စီယမ်', 'price' => 12000],
            ];

            foreach ($pharmacyItems as $item) {
                Item::create([
                    'tenant_id' => $pharmacyTenant->id,
                    'category_id' => $item['category_id'],
                    'name' => $item['name'],
                    'name_mm' => $item['name_mm'],
                    'price' => $item['price'],
                    'is_active' => true,
                ]);
            }
        }

        // ========== 3. FAST FOOD ==========
        $fastFoodTenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo-fastfood'],
            [
                'name' => 'Demo Fast Food',
                'business_type' => 'fast_food',
                'email' => 'demo@fastfood.com',
                'phone' => '09444555666',
                'address' => 'Yangon, Myanmar',
                'status' => 'active',
                'plan' => 'pro',
                'settings' => [
                    'currency' => 'MMK',
                    'currency_symbol' => 'Ks',
                    'tax_enabled' => true,
                    'tax_percentage' => 5,
                ],
            ]
        );

        if (!User::where('email', 'admin@demo-fastfood.com')->exists()) {
            $fastFoodAdmin = User::create([
                'name' => 'Fast Food Admin',
                'email' => 'admin@demo-fastfood.com',
                'password' => Hash::make('password'),
                'phone' => '09999111222',
                'tenant_id' => $fastFoodTenant->id,
                'is_active' => true,
            ]);
            $fastFoodAdmin->assignRole('admin');

            $fastFoodCashier = User::create([
                'name' => 'Fast Food Cashier',
                'email' => 'cashier@demo-fastfood.com',
                'password' => Hash::make('password'),
                'phone' => '09999333444',
                'tenant_id' => $fastFoodTenant->id,
                'is_active' => true,
            ]);
            $fastFoodCashier->assignRole('cashier');

            $fastFoodKitchen = User::create([
                'name' => 'Fast Food Kitchen',
                'email' => 'kitchen@demo-fastfood.com',
                'password' => Hash::make('password'),
                'phone' => '09999555666',
                'tenant_id' => $fastFoodTenant->id,
                'is_active' => true,
            ]);
            $fastFoodKitchen->assignRole('kitchen');
        }

        if (!Category::where('tenant_id', $fastFoodTenant->id)->exists()) {
            $burgers = Category::create([
                'tenant_id' => $fastFoodTenant->id,
                'name' => 'Burgers',
                'name_mm' => 'ဘာဂါများ',
                'printer_type' => 'kitchen',
                'sort_order' => 1,
                'is_active' => true,
            ]);

            $sides = Category::create([
                'tenant_id' => $fastFoodTenant->id,
                'name' => 'Sides & Drinks',
                'name_mm' => 'ဘေးဟင်းနှင့် သောက်စရာ',
                'printer_type' => 'kitchen',
                'sort_order' => 2,
                'is_active' => true,
            ]);

            $fastFoodItems = [
                ['category_id' => $burgers->id, 'name' => 'Classic Burger', 'name_mm' => 'ဘာဂါ', 'price' => 4500],
                ['category_id' => $burgers->id, 'name' => 'Cheese Burger', 'name_mm' => 'ချိစ်ဘာဂါ', 'price' => 5500],
                ['category_id' => $burgers->id, 'name' => 'Double Burger', 'name_mm' => 'ဒဗယ်ဘာဂါ', 'price' => 7500],
                ['category_id' => $burgers->id, 'name' => 'Chicken Burger', 'name_mm' => 'ကြက်သားဘာဂါ', 'price' => 5000],
                ['category_id' => $sides->id, 'name' => 'French Fries', 'name_mm' => 'အာလူးကြော်', 'price' => 2500],
                ['category_id' => $sides->id, 'name' => 'Onion Rings', 'name_mm' => 'ကြက်သွန်ကြော်', 'price' => 3000],
                ['category_id' => $sides->id, 'name' => 'Coca Cola', 'name_mm' => 'ကိုကာကိုလာ', 'price' => 1500],
                ['category_id' => $sides->id, 'name' => 'Sprite', 'name_mm' => 'စပရိုက်', 'price' => 1500],
            ];

            foreach ($fastFoodItems as $item) {
                Item::create([
                    'tenant_id' => $fastFoodTenant->id,
                    'category_id' => $item['category_id'],
                    'name' => $item['name'],
                    'name_mm' => $item['name_mm'],
                    'price' => $item['price'],
                    'is_active' => true,
                ]);
            }
        }

        // ========== 4. CAFE ==========
        $cafeTenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo-cafe'],
            [
                'name' => 'Demo Coffee Shop',
                'business_type' => 'cafe',
                'email' => 'demo@cafe.com',
                'phone' => '09123123123',
                'address' => 'Yangon, Myanmar',
                'status' => 'active',
                'plan' => 'pro',
                'settings' => [
                    'currency' => 'MMK',
                    'currency_symbol' => 'Ks',
                    'service_charge_enabled' => true,
                    'service_charge_percentage' => 5,
                ],
            ]
        );

        if (!User::where('email', 'admin@demo-cafe.com')->exists()) {
            $cafeAdmin = User::create([
                'name' => 'Cafe Admin',
                'email' => 'admin@demo-cafe.com',
                'password' => Hash::make('password'),
                'phone' => '09321321321',
                'tenant_id' => $cafeTenant->id,
                'is_active' => true,
            ]);
            $cafeAdmin->assignRole('admin');

            $cafeCashier = User::create([
                'name' => 'Cafe Cashier',
                'email' => 'cashier@demo-cafe.com',
                'password' => Hash::make('password'),
                'phone' => '09321321322',
                'tenant_id' => $cafeTenant->id,
                'is_active' => true,
            ]);
            $cafeCashier->assignRole('cashier');

            $cafeWaiter = User::create([
                'name' => 'Cafe Waiter',
                'email' => 'waiter@demo-cafe.com',
                'password' => Hash::make('password'),
                'phone' => '09321321323',
                'tenant_id' => $cafeTenant->id,
                'is_active' => true,
            ]);
            $cafeWaiter->assignRole('waiter');
        }

        if (!Category::where('tenant_id', $cafeTenant->id)->exists()) {
            $coffee = Category::create([
                'tenant_id' => $cafeTenant->id,
                'name' => 'Coffee',
                'name_mm' => 'ကော်ဖီ',
                'printer_type' => 'bar',
                'sort_order' => 1,
                'is_active' => true,
            ]);

            $tea = Category::create([
                'tenant_id' => $cafeTenant->id,
                'name' => 'Tea & Others',
                'name_mm' => 'လက်ဖက်ရည်နှင့် အခြား',
                'printer_type' => 'bar',
                'sort_order' => 2,
                'is_active' => true,
            ]);

            $snacks = Category::create([
                'tenant_id' => $cafeTenant->id,
                'name' => 'Snacks',
                'name_mm' => 'မုန့်များ',
                'printer_type' => 'kitchen',
                'sort_order' => 3,
                'is_active' => true,
            ]);

            $cafeItems = [
                ['category_id' => $coffee->id, 'name' => 'Americano', 'name_mm' => 'အမေရိကန်နို', 'price' => 3500],
                ['category_id' => $coffee->id, 'name' => 'Latte', 'name_mm' => 'လာတေး', 'price' => 4500],
                ['category_id' => $coffee->id, 'name' => 'Cappuccino', 'name_mm' => 'ကပ်ပူချီနို', 'price' => 4500],
                ['category_id' => $coffee->id, 'name' => 'Espresso', 'name_mm' => 'အက်စပရက်ဆို', 'price' => 3000],
                ['category_id' => $tea->id, 'name' => 'Green Tea Latte', 'name_mm' => 'လက်ဖက်စိမ်းလာတေး', 'price' => 4000],
                ['category_id' => $tea->id, 'name' => 'Myanmar Tea', 'name_mm' => 'လက်ဖက်ရည်', 'price' => 1500],
                ['category_id' => $snacks->id, 'name' => 'Croissant', 'name_mm' => 'ခရွာဆွန့်', 'price' => 3500],
                ['category_id' => $snacks->id, 'name' => 'Chocolate Cake', 'name_mm' => 'ချောကလက်ကိတ်', 'price' => 4500],
            ];

            foreach ($cafeItems as $item) {
                Item::create([
                    'tenant_id' => $cafeTenant->id,
                    'category_id' => $item['category_id'],
                    'name' => $item['name'],
                    'name_mm' => $item['name_mm'],
                    'price' => $item['price'],
                    'is_active' => true,
                ]);
            }

            // Cafe Tables
            for ($i = 1; $i <= 8; $i++) {
                Table::create([
                    'tenant_id' => $cafeTenant->id,
                    'name' => "Table $i",
                    'capacity' => $i <= 4 ? 2 : 4,
                    'status' => 'available',
                    'sort_order' => $i,
                ]);
            }
        }

        // ========== 5. GROCERY / MINI MART ==========
        $groceryTenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo-grocery'],
            [
                'name' => 'Demo Mini Mart',
                'business_type' => 'grocery',
                'email' => 'demo@grocery.com',
                'phone' => '09456456456',
                'address' => 'Yangon, Myanmar',
                'status' => 'active',
                'plan' => 'pro',
                'settings' => [
                    'currency' => 'MMK',
                    'currency_symbol' => 'Ks',
                    'expiry_tracking' => true,
                ],
            ]
        );

        if (!User::where('email', 'admin@demo-grocery.com')->exists()) {
            $groceryAdmin = User::create([
                'name' => 'Grocery Admin',
                'email' => 'admin@demo-grocery.com',
                'password' => Hash::make('password'),
                'phone' => '09654654654',
                'tenant_id' => $groceryTenant->id,
                'is_active' => true,
            ]);
            $groceryAdmin->assignRole('admin');

            $groceryCashier = User::create([
                'name' => 'Grocery Cashier',
                'email' => 'cashier@demo-grocery.com',
                'password' => Hash::make('password'),
                'phone' => '09654654655',
                'tenant_id' => $groceryTenant->id,
                'is_active' => true,
            ]);
            $groceryCashier->assignRole('cashier');
        }

        if (!Category::where('tenant_id', $groceryTenant->id)->exists()) {
            $food = Category::create([
                'tenant_id' => $groceryTenant->id,
                'name' => 'Food & Snacks',
                'name_mm' => 'အစားအစာနှင့် မုန့်',
                'printer_type' => 'receipt',
                'sort_order' => 1,
                'is_active' => true,
            ]);

            $drinks = Category::create([
                'tenant_id' => $groceryTenant->id,
                'name' => 'Drinks',
                'name_mm' => 'သောက်စရာ',
                'printer_type' => 'receipt',
                'sort_order' => 2,
                'is_active' => true,
            ]);

            $groceryItems = [
                ['category_id' => $food->id, 'name' => 'Instant Noodles', 'name_mm' => 'ခေါက်ဆွဲခြောက်', 'price' => 500],
                ['category_id' => $food->id, 'name' => 'Potato Chips', 'name_mm' => 'အာလူးချစ်ပ်', 'price' => 1500],
                ['category_id' => $food->id, 'name' => 'Biscuits', 'name_mm' => 'ဘီစကစ်', 'price' => 1000],
                ['category_id' => $food->id, 'name' => 'Chocolate Bar', 'name_mm' => 'ချောကလက်', 'price' => 2000],
                ['category_id' => $drinks->id, 'name' => 'Mineral Water', 'name_mm' => 'သောက်ရေသန့်', 'price' => 500],
                ['category_id' => $drinks->id, 'name' => 'Coca Cola', 'name_mm' => 'ကိုကာကိုလာ', 'price' => 1000],
                ['category_id' => $drinks->id, 'name' => 'Orange Juice', 'name_mm' => 'လိမ္မော်ရည်', 'price' => 1500],
                ['category_id' => $drinks->id, 'name' => 'Energy Drink', 'name_mm' => 'အားဖြည့်ယမကာ', 'price' => 2000],
            ];

            foreach ($groceryItems as $item) {
                Item::create([
                    'tenant_id' => $groceryTenant->id,
                    'category_id' => $item['category_id'],
                    'name' => $item['name'],
                    'name_mm' => $item['name_mm'],
                    'price' => $item['price'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('');
        $this->command->info('✅ All business demo data created successfully!');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('                    LOGIN CREDENTIALS                        ');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('🍽️  RESTAURANT (Tables, Kitchen, Waiter)');
        $this->command->info('   Admin:   admin@demo-restaurant.com / password');
        $this->command->info('   Cashier: cashier@demo-restaurant.com / password');
        $this->command->info('   Waiter:  waiter@demo-restaurant.com / password');
        $this->command->info('   Kitchen: kitchen@demo-restaurant.com / password');
        $this->command->info('');
        $this->command->info('🛒 RETAIL STORE (No Tables, No Kitchen)');
        $this->command->info('   Admin:   admin@demo-retail.com / password');
        $this->command->info('   Cashier: cashier@demo-retail.com / password');
        $this->command->info('');
        $this->command->info('💊 PHARMACY (Expiry Tracking)');
        $this->command->info('   Admin:   admin@demo-pharmacy.com / password');
        $this->command->info('   Cashier: cashier@demo-pharmacy.com / password');
        $this->command->info('');
        $this->command->info('🍔 FAST FOOD (Kitchen, No Tables)');
        $this->command->info('   Admin:   admin@demo-fastfood.com / password');
        $this->command->info('   Cashier: cashier@demo-fastfood.com / password');
        $this->command->info('   Kitchen: kitchen@demo-fastfood.com / password');
        $this->command->info('');
        $this->command->info('☕ CAFE (Tables, Kitchen)');
        $this->command->info('   Admin:   admin@demo-cafe.com / password');
        $this->command->info('   Cashier: cashier@demo-cafe.com / password');
        $this->command->info('   Waiter:  waiter@demo-cafe.com / password');
        $this->command->info('');
        $this->command->info('🏪 GROCERY / MINI MART (Expiry Tracking)');
        $this->command->info('   Admin:   admin@demo-grocery.com / password');
        $this->command->info('   Cashier: cashier@demo-grocery.com / password');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
    }
}
