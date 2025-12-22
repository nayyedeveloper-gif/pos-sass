<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class PaymentSettings extends Component
{
    // KBZPay Settings
    public $kbzpay_enabled = false;
    public $kbzpay_phone = '';
    public $kbzpay_account_name = '';
    public $kbzpay_app_id = '';
    public $kbzpay_app_key = '';
    public $kbzpay_merchant_code = '';
    public $kbzpay_production = false;

    // Wave Pay Settings
    public $wavepay_enabled = false;
    public $wavepay_phone = '';
    public $wavepay_account_name = '';

    // CB Pay Settings
    public $cbpay_enabled = false;
    public $cbpay_phone = '';
    public $cbpay_account_name = '';

    // AYA Pay Settings
    public $ayapay_enabled = false;
    public $ayapay_phone = '';
    public $ayapay_account_name = '';

    // General Settings
    public $default_payment_method = 'cash';
    public $show_qr_on_receipt = true;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // KBZPay
        $this->kbzpay_enabled = (bool) Setting::get('kbzpay_enabled', false);
        $this->kbzpay_phone = Setting::get('kbzpay_phone', '');
        $this->kbzpay_account_name = Setting::get('kbzpay_account_name', '');
        $this->kbzpay_app_id = Setting::get('kbzpay_app_id', '');
        $this->kbzpay_app_key = Setting::get('kbzpay_app_key', '');
        $this->kbzpay_merchant_code = Setting::get('kbzpay_merchant_code', '');
        $this->kbzpay_production = (bool) Setting::get('kbzpay_production', false);

        // Wave Pay
        $this->wavepay_enabled = (bool) Setting::get('wavepay_enabled', false);
        $this->wavepay_phone = Setting::get('wavepay_phone', '');
        $this->wavepay_account_name = Setting::get('wavepay_account_name', '');

        // CB Pay
        $this->cbpay_enabled = (bool) Setting::get('cbpay_enabled', false);
        $this->cbpay_phone = Setting::get('cbpay_phone', '');
        $this->cbpay_account_name = Setting::get('cbpay_account_name', '');

        // AYA Pay
        $this->ayapay_enabled = (bool) Setting::get('ayapay_enabled', false);
        $this->ayapay_phone = Setting::get('ayapay_phone', '');
        $this->ayapay_account_name = Setting::get('ayapay_account_name', '');

        // General
        $this->default_payment_method = Setting::get('default_payment_method', 'cash');
        $this->show_qr_on_receipt = (bool) Setting::get('show_qr_on_receipt', true);
    }

    public function saveKBZPay()
    {
        $this->validate([
            'kbzpay_phone' => 'nullable|string|max:20',
            'kbzpay_account_name' => 'nullable|string|max:100',
            'kbzpay_app_id' => 'nullable|string|max:100',
            'kbzpay_app_key' => 'nullable|string|max:200',
            'kbzpay_merchant_code' => 'nullable|string|max:50',
        ]);

        Setting::set('kbzpay_enabled', $this->kbzpay_enabled);
        Setting::set('kbzpay_phone', $this->kbzpay_phone);
        Setting::set('kbzpay_account_name', $this->kbzpay_account_name);
        Setting::set('kbzpay_app_id', $this->kbzpay_app_id);
        Setting::set('kbzpay_app_key', $this->kbzpay_app_key);
        Setting::set('kbzpay_merchant_code', $this->kbzpay_merchant_code);
        Setting::set('kbzpay_production', $this->kbzpay_production);

        session()->flash('message', 'KBZPay settings saved successfully!');
    }

    public function saveWavePay()
    {
        $this->validate([
            'wavepay_phone' => 'nullable|string|max:20',
            'wavepay_account_name' => 'nullable|string|max:100',
        ]);

        Setting::set('wavepay_enabled', $this->wavepay_enabled);
        Setting::set('wavepay_phone', $this->wavepay_phone);
        Setting::set('wavepay_account_name', $this->wavepay_account_name);

        session()->flash('message', 'Wave Pay settings saved successfully!');
    }

    public function saveCBPay()
    {
        $this->validate([
            'cbpay_phone' => 'nullable|string|max:20',
            'cbpay_account_name' => 'nullable|string|max:100',
        ]);

        Setting::set('cbpay_enabled', $this->cbpay_enabled);
        Setting::set('cbpay_phone', $this->cbpay_phone);
        Setting::set('cbpay_account_name', $this->cbpay_account_name);

        session()->flash('message', 'CB Pay settings saved successfully!');
    }

    public function saveAYAPay()
    {
        $this->validate([
            'ayapay_phone' => 'nullable|string|max:20',
            'ayapay_account_name' => 'nullable|string|max:100',
        ]);

        Setting::set('ayapay_enabled', $this->ayapay_enabled);
        Setting::set('ayapay_phone', $this->ayapay_phone);
        Setting::set('ayapay_account_name', $this->ayapay_account_name);

        session()->flash('message', 'AYA Pay settings saved successfully!');
    }

    public function saveGeneralSettings()
    {
        Setting::set('default_payment_method', $this->default_payment_method);
        Setting::set('show_qr_on_receipt', $this->show_qr_on_receipt);

        session()->flash('message', 'General payment settings saved successfully!');
    }

    public function getEnabledPaymentMethods(): array
    {
        $methods = ['cash' => ['name' => 'Cash', 'name_mm' => 'ငွေသား']];

        if ($this->kbzpay_enabled) {
            $methods['kbzpay'] = ['name' => 'KBZPay', 'name_mm' => 'ကေဘီဇက်ပေး', 'color' => '#00A651'];
        }
        if ($this->wavepay_enabled) {
            $methods['wavepay'] = ['name' => 'Wave Pay', 'name_mm' => 'ဝေ့ပေး', 'color' => '#FFD100'];
        }
        if ($this->cbpay_enabled) {
            $methods['cbpay'] = ['name' => 'CB Pay', 'name_mm' => 'စီဘီပေး', 'color' => '#003366'];
        }
        if ($this->ayapay_enabled) {
            $methods['ayapay'] = ['name' => 'AYA Pay', 'name_mm' => 'အေရာပေး', 'color' => '#E31837'];
        }

        return $methods;
    }

    public function render()
    {
        return view('livewire.admin.payment-settings');
    }
}
