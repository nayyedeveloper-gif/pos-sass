<?php

namespace App\Livewire\Components;

use App\Models\Order;
use App\Models\Setting;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentQrCode extends Component
{
    public ?Order $order = null;
    public string $selectedMethod = 'kbzpay';
    public array $enabledMethods = [];
    public string $qrCodeSvg = '';
    public array $paymentInfo = [];

    public function mount(?Order $order = null)
    {
        $this->order = $order;
        $this->loadEnabledMethods();
        
        if (!empty($this->enabledMethods)) {
            $this->selectedMethod = array_key_first($this->enabledMethods);
            $this->generateQrCode();
        }
    }

    public function loadEnabledMethods()
    {
        $methods = [];

        if (Setting::get('kbzpay_enabled', false) && Setting::get('kbzpay_phone')) {
            $methods['kbzpay'] = [
                'name' => 'KBZPay',
                'name_mm' => 'ကေဘီဇက်ပေး',
                'color' => '#00A651',
                'phone' => Setting::get('kbzpay_phone'),
                'account_name' => Setting::get('kbzpay_account_name'),
            ];
        }

        if (Setting::get('wavepay_enabled', false) && Setting::get('wavepay_phone')) {
            $methods['wavepay'] = [
                'name' => 'Wave Pay',
                'name_mm' => 'ဝေ့ပေး',
                'color' => '#FFD100',
                'phone' => Setting::get('wavepay_phone'),
                'account_name' => Setting::get('wavepay_account_name'),
            ];
        }

        if (Setting::get('cbpay_enabled', false) && Setting::get('cbpay_phone')) {
            $methods['cbpay'] = [
                'name' => 'CB Pay',
                'name_mm' => 'စီဘီပေး',
                'color' => '#003366',
                'phone' => Setting::get('cbpay_phone'),
                'account_name' => Setting::get('cbpay_account_name'),
            ];
        }

        if (Setting::get('ayapay_enabled', false) && Setting::get('ayapay_phone')) {
            $methods['ayapay'] = [
                'name' => 'AYA Pay',
                'name_mm' => 'အေရာပေး',
                'color' => '#E31837',
                'phone' => Setting::get('ayapay_phone'),
                'account_name' => Setting::get('ayapay_account_name'),
            ];
        }

        $this->enabledMethods = $methods;
    }

    public function selectMethod(string $method)
    {
        if (isset($this->enabledMethods[$method])) {
            $this->selectedMethod = $method;
            $this->generateQrCode();
        }
    }

    public function generateQrCode()
    {
        if (!isset($this->enabledMethods[$this->selectedMethod])) {
            $this->qrCodeSvg = '';
            $this->paymentInfo = [];
            return;
        }

        $method = $this->enabledMethods[$this->selectedMethod];
        $phone = $method['phone'];
        $amount = $this->order ? number_format($this->order->total, 0, '', '') : '0';
        $orderNumber = $this->order ? $this->order->order_number : 'N/A';

        // Generate QR content based on payment method
        $qrContent = $this->generateQrContent($this->selectedMethod, $phone, $amount, $orderNumber);

        // Generate QR code SVG
        $this->qrCodeSvg = QrCode::size(200)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->margin(1)
            ->generate($qrContent);

        $this->paymentInfo = [
            'method' => $method['name'],
            'method_mm' => $method['name_mm'],
            'phone' => $phone,
            'account_name' => $method['account_name'],
            'amount' => $this->order ? $this->order->total : 0,
            'order_number' => $orderNumber,
            'color' => $method['color'],
        ];
    }

    protected function generateQrContent(string $method, string $phone, string $amount, string $orderNumber): string
    {
        // Different payment apps may have different QR formats
        // These are simplified formats - actual formats may vary
        switch ($method) {
            case 'kbzpay':
                // KBZPay deep link format
                return "kbzpay://pay?phone={$phone}&amount={$amount}&note=Order%23{$orderNumber}";
            
            case 'wavepay':
                // Wave Pay format
                return "wavepay://transfer?phone={$phone}&amount={$amount}&note=Order%23{$orderNumber}";
            
            case 'cbpay':
                // CB Pay format
                return "cbpay://pay?phone={$phone}&amount={$amount}&ref=Order{$orderNumber}";
            
            case 'ayapay':
                // AYA Pay format
                return "ayapay://transfer?phone={$phone}&amount={$amount}&memo=Order{$orderNumber}";
            
            default:
                // Generic format with phone number
                return "tel:{$phone}";
        }
    }

    public function render()
    {
        return view('livewire.components.payment-qr-code');
    }
}
