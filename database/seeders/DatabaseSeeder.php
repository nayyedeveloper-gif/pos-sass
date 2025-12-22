<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Printer;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles & Permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Default Users
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin123'),
            'phone' => '09000000000',
        ]);
        $superAdmin->assignRole('super-admin');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mybusiness.com',
            'password' => Hash::make('password'),
            'phone' => '09123456789',
        ]);
        $admin->assignRole('admin');

        $cashier = User::create([
            'name' => 'Cashier',
            'email' => 'cashier@mybusiness.com',
            'password' => Hash::make('password'),
            'phone' => '09111222333',
        ]);
        $cashier->assignRole('cashier');

        // 3. Default Categories
        $categories = [
            [
                'name' => 'Foods',
                'name_mm' => 'အစားအစာများ',
                'printer_type' => 'kitchen',
                'sort_order' => 1
            ],
            [
                'name' => 'Drinks',
                'name_mm' => 'သောက်စရာများ',
                'printer_type' => 'bar',
                'sort_order' => 2
            ],
            [
                'name' => 'Nan Pyar / Paratha',
                'name_mm' => 'နံပြား/ပလာတာ/အီကြာ',
                'printer_type' => 'nan_pyar',
                'sort_order' => 3
            ],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // 4. Default Printers
        $printers = [
            [
                'name' => 'Cashier Printer',
                'type' => 'receipt',
                'ip_address' => '192.168.0.200', // Placeholder
                'port' => 9100,
            ],
            [
                'name' => 'Kitchen Printer',
                'type' => 'kitchen',
                'ip_address' => '192.168.0.88',
                'port' => 9100,
            ],
            [
                'name' => 'Bar Printer',
                'type' => 'bar',
                'ip_address' => '192.168.0.77',
                'port' => 9100,
            ],
            [
                'name' => 'Nan Pyar Printer',
                'type' => 'nan_pyar',
                'ip_address' => '192.168.0.66',
                'port' => 9100,
            ],
        ];

        foreach ($printers as $printer) {
            Printer::create($printer);
        }

        // 5. Default Settings
        $settings = [
            ['key' => 'business_name', 'value' => 'My Business', 'type' => 'string'],
            ['key' => 'business_name_mm', 'value' => 'ကျွန်ုပ်၏လုပ်ငန်း', 'type' => 'string'],
            ['key' => 'business_address', 'value' => 'Yangon, Myanmar', 'type' => 'string'],
            ['key' => 'business_address_mm', 'value' => 'ရန်ကုန်မြို့၊ မြန်မာနိုင်ငံ', 'type' => 'string'],
            ['key' => 'business_phone', 'value' => '+95 9 123 456 789', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => 'Ks', 'type' => 'string'],
            ['key' => 'tax_enabled', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'service_charge_enabled', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'app_logo', 'value' => 'logos/logo.png', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
        
        // 6. Sample Items (Optional - for quick start)
        // You can uncomment or add more if needed
    }
}
