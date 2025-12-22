<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'business_name', 'value' => 'My Business', 'type' => 'string'],
            ['key' => 'business_name_mm', 'value' => 'ကျွန်ုပ်၏လုပ်ငန်း', 'type' => 'string'],
            ['key' => 'business_address', 'value' => 'Yangon, Myanmar', 'type' => 'string'],
            ['key' => 'business_address_mm', 'value' => 'ရန်ကုန်မြို့၊ မြန်မာနိုင်ငံ', 'type' => 'string'],
            ['key' => 'business_phone', 'value' => '+95 9 123 456 789', 'type' => 'string'],
            ['key' => 'business_email', 'value' => 'info@mybusiness.com', 'type' => 'string'],
            ['key' => 'tax_enabled', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'tax_percentage', 'value' => '0', 'type' => 'decimal'],
            ['key' => 'service_charge_enabled', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'service_charge_amount', 'value' => '0', 'type' => 'decimal'],
            ['key' => 'currency', 'value' => 'MMK', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => 'Ks', 'type' => 'string'],
            ['key' => 'auto_print_kitchen', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'auto_print_Bar', 'value' => '1', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
