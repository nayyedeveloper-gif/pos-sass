<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LicenseService
{
    /**
     * Available Business Types
     */
    public const BUSINESS_TYPES = [
        'RETAIL' => [
            'code' => 'RTL',
            'name' => 'Retail Store',
            'name_mm' => 'လက်လီဆိုင်',
            'features' => ['inventory', 'barcode', 'customers', 'reports'],
        ],
        'RESTAURANT' => [
            'code' => 'RST',
            'name' => 'Restaurant & Cafe',
            'name_mm' => 'စားသောက်ဆိုင်',
            'features' => ['tables', 'kitchen_display', 'qr_menu', 'service_charge'],
        ],
        'PHARMACY' => [
            'code' => 'PHR',
            'name' => 'Pharmacy',
            'name_mm' => 'ဆေးဆိုင်',
            'features' => ['inventory', 'expiry_tracking', 'batch_numbers', 'prescriptions'],
        ],
        'MINIMART' => [
            'code' => 'MRT',
            'name' => 'Mini Mart / Grocery',
            'name_mm' => 'ကုန်စုံဆိုင်',
            'features' => ['inventory', 'barcode', 'customers', 'multi_unit'],
        ],
        'LIQUOR' => [
            'code' => 'LQR',
            'name' => 'Liquor Store',
            'name_mm' => 'အရက်ဆိုင်',
            'features' => ['inventory', 'age_verification', 'barcode'],
        ],
        'ECOMMERCE' => [
            'code' => 'ECM',
            'name' => 'E-Commerce / Online Shop',
            'name_mm' => 'အွန်လိုင်းဆိုင်',
            'features' => ['inventory', 'shipping', 'online_orders', 'customers'],
        ],
        'UNIVERSAL' => [
            'code' => 'UNI',
            'name' => 'Universal (All Features)',
            'name_mm' => 'ဘက်စုံသုံး',
            'features' => ['all'],
        ],
    ];

    /**
     * Get Unique Machine ID based on OS
     */
    public function getMachineId()
    {
        $machineId = Cache::get('pos_machine_id');

        if (!$machineId) {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('wmic path win32_physicalmedia get SerialNumber 2>&1');
                
                if (preg_match('/[A-Z0-9]{4,}/i', str_replace('SerialNumber', '', $output), $matches)) {
                     $machineId = $matches[0];
                } else {
                     $machineId = shell_exec('wmic csproduct get uuid 2>&1');
                }
                
                $machineId = preg_replace('/\s+/', '', $machineId);
            } else {
                $machineId = shell_exec('hostname') . shell_exec('uname -a');
            }
            
            $machineId = md5(trim($machineId ?? 'UNKNOWN_DEVICE'));
            Cache::forever('pos_machine_id', $machineId);
        }

        return $machineId;
    }

    /**
     * Get all available business types
     */
    public function getBusinessTypes()
    {
        return self::BUSINESS_TYPES;
    }

    /**
     * Verify License Key
     * Format: BUSINESS_CODE-MACHINE_HASH-EXPIRY-SIGNATURE
     * Example: RTL-5d992dd1-LIFETIME-F11299A3
     */
    public function verifyLicense($licenseKey)
    {
        try {
            $parts = explode('-', $licenseKey);
            
            if (count($parts) !== 4) {
                return ['valid' => false, 'message' => 'Invalid license format.'];
            }

            list($businessCode, $machineHash, $expiry, $signature) = $parts;

            // 1. Validate Business Type
            $businessType = $this->getBusinessTypeByCode($businessCode);
            if (!$businessType) {
                return ['valid' => false, 'message' => 'Invalid business type in license.'];
            }

            // 2. Check Machine Match
            $currentMachineHash = substr($this->getMachineId(), 0, 8);
            
            if ($machineHash !== $currentMachineHash && $machineHash !== 'UNIVERSAL') {
                return ['valid' => false, 'message' => 'License key is for another computer.'];
            }

            // 3. Check Expiry
            if ($expiry !== 'LIFETIME') {
                $expiryDate = \Carbon\Carbon::createFromFormat('Ymd', $expiry);
                if ($expiryDate->isPast()) {
                    return ['valid' => false, 'message' => 'License has expired.'];
                }
            }

            // 4. Verify Signature
            $salt = 'ALLPOS_PRO_SECRET_SALT_2025'; 
            $validSignature = substr(md5($salt . $businessCode . $machineHash . $expiry), 0, 8);
            $validSignature = strtoupper($validSignature);

            if ($signature !== $validSignature) {
                return ['valid' => false, 'message' => 'Invalid license signature.'];
            }

            return [
                'valid' => true, 
                'message' => 'License verified.', 
                'license_type' => $expiry === 'LIFETIME' ? 'Lifetime' : 'Subscription',
                'business_type' => $businessType['key'],
                'business_name' => $businessType['name'],
                'business_name_mm' => $businessType['name_mm'],
                'features' => $businessType['features'],
                'expiry' => $expiry === 'LIFETIME' ? null : $expiry,
            ];

        } catch (\Exception $e) {
            return ['valid' => false, 'message' => 'Error verifying license: ' . $e->getMessage()];
        }
    }

    /**
     * Get business type by code
     */
    private function getBusinessTypeByCode($code)
    {
        foreach (self::BUSINESS_TYPES as $key => $type) {
            if ($type['code'] === $code) {
                return array_merge($type, ['key' => $key]);
            }
        }
        return null;
    }

    /**
     * Generate License Key (For Admin Use)
     * @param string $machineId - Machine ID from client
     * @param string $businessType - RETAIL, RESTAURANT, PHARMACY, etc.
     * @param string $licenseType - LIFETIME or SUBSCRIPTION
     * @param int $durationDays - Days for subscription (ignored for LIFETIME)
     */
    public function generateLicense($machineId, $businessType = 'UNIVERSAL', $licenseType = 'LIFETIME', $durationDays = 30)
    {
        $businessType = strtoupper($businessType);
        
        if (!isset(self::BUSINESS_TYPES[$businessType])) {
            throw new \InvalidArgumentException("Invalid business type: $businessType");
        }

        $businessCode = self::BUSINESS_TYPES[$businessType]['code'];
        $machineHash = substr($machineId, 0, 8);
        
        $expiry = 'LIFETIME';
        if ($licenseType !== 'LIFETIME') {
            $expiry = now()->addDays($durationDays)->format('Ymd');
        }

        $salt = 'ALLPOS_PRO_SECRET_SALT_2025';
        $signature = substr(md5($salt . $businessCode . $machineHash . $expiry), 0, 8);
        $signature = strtoupper($signature);

        return "$businessCode-$machineHash-$expiry-$signature";
    }

    /**
     * Check if a feature is available for current license
     */
    public function hasFeature($feature, $licenseData = null)
    {
        if (!$licenseData) {
            $licenseKey = \App\Models\Setting::where('key', 'license_key')->value('value');
            if (!$licenseKey) return false;
            
            $licenseData = $this->verifyLicense($licenseKey);
        }

        if (!$licenseData['valid']) return false;

        $features = $licenseData['features'] ?? [];
        
        return in_array('all', $features) || in_array($feature, $features);
    }

    /**
     * Get current license info
     */
    public function getCurrentLicense()
    {
        $licenseKey = \App\Models\Setting::where('key', 'license_key')->value('value');
        if (!$licenseKey) {
            return null;
        }

        $verification = $this->verifyLicense($licenseKey);
        if (!$verification['valid']) {
            return null;
        }

        return $verification;
    }
}
