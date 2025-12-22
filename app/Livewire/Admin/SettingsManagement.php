<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SettingsManagement extends Component
{
    use WithFileUploads;

    // App Settings
    public $app_name;
    public $logo;
    public $current_logo;
    
    // Business Information
    public $business_name;
    public $business_name_mm;
    public $business_address;
    public $business_phone;
    public $business_email;
    
    // Tax & Charges
    public $default_tax_percentage;
    public $default_service_charge_percentage;
    
    // Receipt Settings
    public $receipt_header;
    public $receipt_footer;
    public $show_logo_on_receipt;
    
    // System Settings
    public $currency_symbol;
    public $date_format;
    public $time_format;
    public $timezone;
    
    // Digital Signage Settings
    public $signage_enabled = false;
    public $promotional_message = '';
    public $signage_rotation_speed = 10;
    public $signage_show_prices = true;
    public $signage_show_descriptions = true;
    public $signage_show_availability = true;
    public $signage_theme = 'dark';
    public $signage_auto_refresh = 5;
    public $signage_show_media = true;
    
    // Auto Print Settings
    public $auto_print_kitchen = true;
    public $auto_print_Bar = true;
    public $auto_print_receipt = false;
    
    // Food Court Card Settings
    public $card_system_enabled = false;
    public $card_bonus_enabled = false;
    public $card_bonus_percentage = 0;
    public $card_expiry_enabled = false;
    public $card_expiry_months = 12;
    
    // Cloud Sync Settings
    public $cloud_sync_enabled = false;
    public $cloud_sync_url = '';
    public $cloud_sync_token = '';
    public $cloud_sync_interval = 5;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // App Settings
        $this->app_name = Setting::get('app_name', config('app.name'));
        $this->current_logo = Setting::get('app_logo', null);
        
        // Business Information
        $this->business_name = Setting::get('business_name', 'My Business');
        $this->business_name_mm = Setting::get('business_name_mm', 'ကျွန်ုပ်၏လုပ်ငန်း');
        $this->business_address = Setting::get('business_address', 'Yangon, Myanmar');
        $this->business_phone = Setting::get('business_phone', '+95 9 123 456 789');
        $this->business_email = Setting::get('business_email', '');
        
        // Tax & Charges
        $this->default_tax_percentage = Setting::get('default_tax_percentage', 5);
        $this->default_service_charge_percentage = Setting::get('default_service_charge_percentage', 10);
        
        // Receipt Settings
        $this->receipt_header = Setting::get('receipt_header', '');
        $this->receipt_footer = Setting::get('receipt_footer', 'Thank you for your visit!');
        $this->show_logo_on_receipt = Setting::get('show_logo_on_receipt', false);
        
        // System Settings
        $this->currency_symbol = Setting::get('currency_symbol', 'Ks');
        $this->date_format = Setting::get('date_format', 'Y-m-d');
        $this->time_format = Setting::get('time_format', 'H:i');
        $this->timezone = Setting::get('timezone', 'Asia/Yangon');
        
        // Digital Signage Settings
        $this->signage_enabled = Setting::get('signage_enabled', true);
        $this->promotional_message = Setting::get('promotional_message', 'Welcome to our restaurant!');
        $this->signage_rotation_speed = Setting::get('signage_rotation_speed', 10);
        $this->signage_show_prices = Setting::get('signage_show_prices', true);
        $this->signage_show_descriptions = Setting::get('signage_show_descriptions', true);
        $this->signage_show_availability = Setting::get('signage_show_availability', true);
        $this->signage_theme = Setting::get('signage_theme', 'dark');
        $this->signage_auto_refresh = Setting::get('signage_auto_refresh', 5);
        $this->signage_show_media = Setting::get('signage_show_media', true);
        
        // Auto Print Settings
        $this->auto_print_kitchen = Setting::get('auto_print_kitchen', true);
        $this->auto_print_Bar = Setting::get('auto_print_Bar', true);
        $this->auto_print_receipt = Setting::get('auto_print_receipt', false);
        
        // Food Court Card Settings
        $this->card_system_enabled = Setting::get('card_system_enabled', false);
        $this->card_bonus_enabled = Setting::get('card_bonus_enabled', false);
        $this->card_bonus_percentage = Setting::get('card_bonus_percentage', 0);
        $this->card_expiry_enabled = Setting::get('card_expiry_enabled', false);
        $this->card_expiry_months = Setting::get('card_expiry_months', 12);
        
        // Cloud Sync Settings
        $this->cloud_sync_enabled = Setting::get('cloud_sync_enabled', false);
        $this->cloud_sync_url = Setting::get('cloud_sync_url', '');
        $this->cloud_sync_token = Setting::get('cloud_sync_token', '');
        $this->cloud_sync_interval = Setting::get('cloud_sync_interval', 5);
    }

    public function save()
    {
        $this->validate([
            'app_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'business_name' => 'required|string|max:255',
            'business_name_mm' => 'required|string|max:255',
            'business_address' => 'nullable|string',
            'business_phone' => 'nullable|string|max:50',
            'business_email' => 'nullable|email|max:255',
            'default_tax_percentage' => 'required|numeric|min:0|max:100',
            'default_service_charge_percentage' => 'required|numeric|min:0|max:100',
            'receipt_header' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
            'currency_symbol' => 'required|string|max:10',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'timezone' => 'required|string',
        ]);

        // Save App Settings
        Setting::set('app_name', $this->app_name);
        
        // Handle logo upload
        if ($this->logo) {
            // Delete old logo if exists
            if ($this->current_logo) {
                Storage::disk('public')->delete($this->current_logo);
            }
            
            // Store new logo
            $logoPath = $this->logo->store('logos', 'public');
            Setting::set('app_logo', $logoPath);
            $this->current_logo = $logoPath;
        }
        
        // Save Business Information
        Setting::set('business_name', $this->business_name);
        Setting::set('business_name_mm', $this->business_name_mm);
        Setting::set('business_address', $this->business_address);
        Setting::set('business_phone', $this->business_phone);
        Setting::set('business_email', $this->business_email);
        
        // Save Tax & Charges
        Setting::set('default_tax_percentage', $this->default_tax_percentage, 'float');
        Setting::set('default_service_charge_percentage', $this->default_service_charge_percentage, 'float');
        
        // Save Receipt Settings
        Setting::set('receipt_header', $this->receipt_header);
        Setting::set('receipt_footer', $this->receipt_footer);
        Setting::set('show_logo_on_receipt', (bool)$this->show_logo_on_receipt, 'boolean');
        
        // Save System Settings
        Setting::set('currency_symbol', $this->currency_symbol);
        Setting::set('date_format', $this->date_format);
        Setting::set('time_format', $this->time_format);
        Setting::set('timezone', $this->timezone);
        
        // Save Digital Signage Settings
        Setting::set('signage_enabled', $this->signage_enabled, 'boolean');
        Setting::set('promotional_message', $this->promotional_message);
        Setting::set('signage_rotation_speed', $this->signage_rotation_speed, 'integer');
        Setting::set('signage_show_prices', $this->signage_show_prices, 'boolean');
        Setting::set('signage_show_descriptions', $this->signage_show_descriptions, 'boolean');
        Setting::set('signage_show_availability', $this->signage_show_availability, 'boolean');
        Setting::set('signage_theme', $this->signage_theme);
        Setting::set('signage_auto_refresh', $this->signage_auto_refresh, 'integer');
        Setting::set('signage_show_media', $this->signage_show_media, 'boolean');
        
        // Save Auto Print Settings
        Setting::set('auto_print_kitchen', $this->auto_print_kitchen, 'boolean');
        Setting::set('auto_print_Bar', $this->auto_print_Bar, 'boolean');
        Setting::set('auto_print_receipt', $this->auto_print_receipt, 'boolean');
        
        // Save Food Court Card Settings
        Setting::set('card_system_enabled', $this->card_system_enabled, 'boolean');
        Setting::set('card_bonus_enabled', $this->card_bonus_enabled, 'boolean');
        Setting::set('card_bonus_percentage', $this->card_bonus_percentage, 'float');
        Setting::set('card_expiry_enabled', $this->card_expiry_enabled, 'boolean');
        Setting::set('card_expiry_months', $this->card_expiry_months, 'integer');
        
        // Save Cloud Sync Settings
        Setting::set('cloud_sync_enabled', $this->cloud_sync_enabled, 'boolean');
        Setting::set('cloud_sync_url', $this->cloud_sync_url);
        Setting::set('cloud_sync_token', $this->cloud_sync_token);
        Setting::set('cloud_sync_interval', $this->cloud_sync_interval, 'integer');

        session()->flash('message', 'ဆက်တင်များကို အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။');
    }

    public function deleteLogo()
    {
        if ($this->current_logo) {
            Storage::disk('public')->delete($this->current_logo);
            Setting::set('app_logo', null);
            $this->current_logo = null;
            session()->flash('message', 'လိုဂိုကို ဖျက်ပြီးပါပြီ။');
        }
    }

    public function render()
    {
        return view('livewire.admin.settings-management');
    }
}
