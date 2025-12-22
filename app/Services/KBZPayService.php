<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KBZPayService
{
    protected $appId;
    protected $appKey;
    protected $merchantCode;
    protected $isProduction;
    protected $baseUrl;

    public function __construct()
    {
        $this->appId = Setting::get('kbzpay_app_id', config('services.kbzpay.app_id'));
        $this->appKey = Setting::get('kbzpay_app_key', config('services.kbzpay.app_key'));
        $this->merchantCode = Setting::get('kbzpay_merchant_code', config('services.kbzpay.merchant_code'));
        $this->isProduction = Setting::get('kbzpay_production', false);
        
        $this->baseUrl = $this->isProduction 
            ? 'https://api.kbzpay.com/payment/gateway/uat'
            : 'https://api.kbzpay.com/payment/gateway/uat';
    }

    /**
     * Check if KBZPay is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->appId) && !empty($this->appKey) && !empty($this->merchantCode);
    }

    /**
     * Generate a unique transaction reference
     */
    public function generateTransactionRef(): string
    {
        return 'TXN' . date('YmdHis') . strtoupper(Str::random(6));
    }

    /**
     * Create a PWA (Pay With App) payment request
     */
    public function createPayment(Order $order, string $callbackUrl = null): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'KBZPay is not configured. Please set up credentials in settings.',
            ];
        }

        $transactionRef = $this->generateTransactionRef();
        $amount = number_format($order->total, 2, '.', '');
        $timestamp = now()->format('YmdHis');

        // Build request data
        $requestData = [
            'Request' => [
                'timestamp' => $timestamp,
                'method' => 'kbz.payment.precreate',
                'notify_url' => $callbackUrl ?? route('payment.kbzpay.callback'),
                'nonce_str' => Str::random(32),
                'sign_type' => 'SHA256',
                'version' => '1.0',
                'biz_content' => [
                    'merch_order_id' => $transactionRef,
                    'merch_code' => $this->merchantCode,
                    'appid' => $this->appId,
                    'trade_type' => 'PAY_BY_QRCODE',
                    'title' => 'Order #' . $order->order_number,
                    'total_amount' => $amount,
                    'trans_currency' => 'MMK',
                    'timeout_express' => '15m',
                ],
            ],
        ];

        // Generate signature
        $requestData['Request']['sign'] = $this->generateSignature($requestData['Request']);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/precreate', $requestData);

            $result = $response->json();

            if ($response->successful() && isset($result['Response']['result']) && $result['Response']['result'] === 'SUCCESS') {
                return [
                    'success' => true,
                    'transaction_ref' => $transactionRef,
                    'qr_code' => $result['Response']['qrCode'] ?? null,
                    'prepay_id' => $result['Response']['prepay_id'] ?? null,
                    'message' => 'Payment created successfully',
                ];
            }

            return [
                'success' => false,
                'message' => $result['Response']['result_msg'] ?? 'Failed to create payment',
                'error_code' => $result['Response']['result_code'] ?? 'UNKNOWN',
            ];
        } catch (\Exception $e) {
            Log::error('KBZPay Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Query payment status
     */
    public function queryPayment(string $transactionRef): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'KBZPay is not configured',
            ];
        }

        $timestamp = now()->format('YmdHis');

        $requestData = [
            'Request' => [
                'timestamp' => $timestamp,
                'method' => 'kbz.payment.queryorder',
                'nonce_str' => Str::random(32),
                'sign_type' => 'SHA256',
                'version' => '1.0',
                'biz_content' => [
                    'merch_order_id' => $transactionRef,
                    'merch_code' => $this->merchantCode,
                    'appid' => $this->appId,
                ],
            ],
        ];

        $requestData['Request']['sign'] = $this->generateSignature($requestData['Request']);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/queryorder', $requestData);

            $result = $response->json();

            if ($response->successful()) {
                $tradeStatus = $result['Response']['trade_status'] ?? 'UNKNOWN';
                
                return [
                    'success' => true,
                    'status' => $tradeStatus,
                    'is_paid' => $tradeStatus === 'PAY_SUCCESS',
                    'transaction_id' => $result['Response']['mm_order_id'] ?? null,
                    'paid_amount' => $result['Response']['total_amount'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to query payment status',
            ];
        } catch (\Exception $e) {
            Log::error('KBZPay Query Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate signature for request
     */
    protected function generateSignature(array $data): string
    {
        // Sort and concatenate parameters
        ksort($data);
        $signString = '';
        
        foreach ($data as $key => $value) {
            if ($key !== 'sign' && !empty($value)) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }
                $signString .= $key . '=' . $value . '&';
            }
        }
        
        $signString .= 'key=' . $this->appKey;
        
        return strtoupper(hash('sha256', $signString));
    }

    /**
     * Verify callback signature
     */
    public function verifyCallback(array $data): bool
    {
        $receivedSign = $data['sign'] ?? '';
        unset($data['sign']);
        
        $calculatedSign = $this->generateSignature($data);
        
        return hash_equals($calculatedSign, $receivedSign);
    }

    /**
     * Generate static QR code data for manual payment
     * This is for when API credentials are not available
     */
    public function generateStaticQRData(Order $order): array
    {
        $phoneNumber = Setting::get('kbzpay_phone', '');
        $accountName = Setting::get('kbzpay_account_name', Setting::get('business_name', 'NexaPOS'));
        
        if (empty($phoneNumber)) {
            return [
                'success' => false,
                'message' => 'KBZPay phone number not configured',
            ];
        }

        // Generate QR code content for KBZPay
        // Format: kbzpay://pay?phone=09xxxxxxxx&amount=xxxxx&note=Order#xxxx
        $qrContent = sprintf(
            'kbzpay://pay?phone=%s&amount=%s&note=Order%%23%s',
            $phoneNumber,
            number_format($order->total, 0, '', ''),
            $order->order_number
        );

        return [
            'success' => true,
            'qr_content' => $qrContent,
            'phone' => $phoneNumber,
            'account_name' => $accountName,
            'amount' => $order->total,
            'order_number' => $order->order_number,
            'type' => 'static',
        ];
    }

    /**
     * Get all supported payment methods
     */
    public static function getSupportedMethods(): array
    {
        return [
            'kbzpay' => [
                'name' => 'KBZPay',
                'name_mm' => 'ကေဘီဇက်ပေး',
                'icon' => 'kbzpay',
                'color' => '#00A651',
            ],
            'wavepay' => [
                'name' => 'Wave Pay',
                'name_mm' => 'ဝေ့ပေး',
                'icon' => 'wavepay',
                'color' => '#FFD100',
            ],
            'cbpay' => [
                'name' => 'CB Pay',
                'name_mm' => 'စီဘီပေး',
                'icon' => 'cbpay',
                'color' => '#003366',
            ],
            'ayapay' => [
                'name' => 'AYA Pay',
                'name_mm' => 'အေရာပေး',
                'icon' => 'ayapay',
                'color' => '#E31837',
            ],
            'onepay' => [
                'name' => 'OnePay',
                'name_mm' => 'ဝမ်းပေး',
                'icon' => 'onepay',
                'color' => '#FF6B00',
            ],
        ];
    }
}
