<?php

namespace App\Services;

use App\Models\Tenant;

class BusinessFeatureService
{
    /**
     * Feature definitions for each business type
     * true = enabled, false = disabled
     */
    public const FEATURES = [
        'restaurant' => [
            'tables' => true,
            'kitchen_display' => true,
            'bar_display' => true,
            'reservations' => true,
            'waiter_ordering' => true,
            'dine_in' => true,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => true,
            'bar_printer' => true,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => true,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => true,
            'service_charge' => true,
            'tips' => true,
        ],
        'retail' => [
            'tables' => false,
            'kitchen_display' => false,
            'bar_display' => false,
            'reservations' => false,
            'waiter_ordering' => false,
            'dine_in' => false,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => false,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => true,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => false,
            'service_charge' => false,
            'tips' => false,
        ],
        'pharmacy' => [
            'tables' => false,
            'kitchen_display' => false,
            'bar_display' => false,
            'reservations' => false,
            'waiter_ordering' => false,
            'dine_in' => false,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => false,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => true,
            'prescriptions' => true,
            'barcode_scanner' => true,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => false,
            'service_charge' => false,
            'tips' => false,
        ],
        'grocery' => [
            'tables' => false,
            'kitchen_display' => false,
            'bar_display' => false,
            'reservations' => false,
            'waiter_ordering' => false,
            'dine_in' => false,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => false,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => true,
            'prescriptions' => false,
            'barcode_scanner' => true,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => false,
            'service_charge' => false,
            'tips' => false,
        ],
        'cafe' => [
            'tables' => true,
            'kitchen_display' => true,
            'bar_display' => true,
            'reservations' => true,
            'waiter_ordering' => true,
            'dine_in' => true,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => true,
            'bar_printer' => true,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => false,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => true,
            'service_charge' => true,
            'tips' => true,
        ],
        'salon' => [
            'tables' => false,
            'kitchen_display' => false,
            'bar_display' => false,
            'reservations' => true,
            'waiter_ordering' => false,
            'dine_in' => false,
            'takeaway' => false,
            'delivery' => false,
            'kitchen_printer' => false,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => false,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => false,
            'service_charge' => true,
            'tips' => true,
        ],
        'liquor_store' => [
            'tables' => false,
            'kitchen_display' => false,
            'bar_display' => false,
            'reservations' => false,
            'waiter_ordering' => false,
            'dine_in' => false,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => false,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => true,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => false,
            'service_charge' => false,
            'tips' => false,
        ],
        'fast_food' => [
            'tables' => false,
            'kitchen_display' => true,
            'bar_display' => false,
            'reservations' => false,
            'waiter_ordering' => false,
            'dine_in' => true,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => true,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => false,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => false,
            'service_charge' => false,
            'tips' => false,
        ],
        'ecommerce' => [
            'tables' => false,
            'kitchen_display' => false,
            'bar_display' => false,
            'reservations' => false,
            'waiter_ordering' => false,
            'dine_in' => false,
            'takeaway' => false,
            'delivery' => true,
            'kitchen_printer' => false,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => true,
            'loyalty_program' => true,
            'customer_display' => false,
            'split_bill' => false,
            'service_charge' => false,
            'tips' => false,
        ],
        'general' => [
            'tables' => false,
            'kitchen_display' => false,
            'bar_display' => false,
            'reservations' => false,
            'waiter_ordering' => false,
            'dine_in' => false,
            'takeaway' => true,
            'delivery' => true,
            'kitchen_printer' => false,
            'bar_printer' => false,
            'inventory' => true,
            'expiry_tracking' => false,
            'prescriptions' => false,
            'barcode_scanner' => true,
            'loyalty_program' => true,
            'customer_display' => true,
            'split_bill' => false,
            'service_charge' => false,
            'tips' => false,
        ],
    ];

    /**
     * Feature labels in Myanmar and English
     */
    public const FEATURE_LABELS = [
        'tables' => ['en' => 'Table Management', 'mm' => 'စားပွဲစီမံခန့်ခွဲမှု'],
        'kitchen_display' => ['en' => 'Kitchen Display (KDS)', 'mm' => 'မီးဖိုချောင် မျက်နှာပြင်'],
        'bar_display' => ['en' => 'Bar Display', 'mm' => 'ဘား မျက်နှာပြင်'],
        'reservations' => ['en' => 'Reservations', 'mm' => 'ကြိုတင်စာရင်းသွင်းမှု'],
        'waiter_ordering' => ['en' => 'Waiter Ordering', 'mm' => 'စားပွဲထိုး အော်ဒါ'],
        'dine_in' => ['en' => 'Dine In', 'mm' => 'ဆိုင်တွင်းစား'],
        'takeaway' => ['en' => 'Takeaway', 'mm' => 'ထုတ်ယူ'],
        'delivery' => ['en' => 'Delivery', 'mm' => 'ပို့ဆောင်မှု'],
        'kitchen_printer' => ['en' => 'Kitchen Printer', 'mm' => 'မီးဖိုချောင် ပရင်တာ'],
        'bar_printer' => ['en' => 'Bar Printer', 'mm' => 'ဘား ပရင်တာ'],
        'inventory' => ['en' => 'Inventory Management', 'mm' => 'ကုန်ပစ္စည်းစီမံခန့်ခွဲမှု'],
        'expiry_tracking' => ['en' => 'Expiry Date Tracking', 'mm' => 'သက်တမ်းကုန်ရက် ခြေရာခံမှု'],
        'prescriptions' => ['en' => 'Prescriptions', 'mm' => 'ဆေးညွှန်းစာ'],
        'barcode_scanner' => ['en' => 'Barcode Scanner', 'mm' => 'ဘားကုဒ် စကင်နာ'],
        'loyalty_program' => ['en' => 'Loyalty Program', 'mm' => 'အမှတ်စနစ်'],
        'customer_display' => ['en' => 'Customer Display', 'mm' => 'ဖောက်သည် မျက်နှာပြင်'],
        'split_bill' => ['en' => 'Split Bill', 'mm' => 'ဘေလ်ခွဲ'],
        'service_charge' => ['en' => 'Service Charge', 'mm' => 'ဝန်ဆောင်ခ'],
        'tips' => ['en' => 'Tips', 'mm' => 'အပိုဆု'],
    ];

    protected ?Tenant $tenant = null;

    public function __construct(?Tenant $tenant = null)
    {
        $this->tenant = $tenant;
    }

    /**
     * Set the tenant context
     */
    public function setTenant(Tenant $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }

    /**
     * Get tenant from current user
     */
    public function forCurrentUser(): self
    {
        if (auth()->check() && auth()->user()->tenant_id) {
            $this->tenant = auth()->user()->tenant;
        }
        return $this;
    }

    /**
     * Check if a feature is enabled for the current tenant
     */
    public function hasFeature(string $feature): bool
    {
        if (!$this->tenant) {
            return false;
        }

        $businessType = $this->tenant->business_type ?? 'general';
        
        // Check tenant-specific override first
        $overrides = $this->tenant->getSetting('feature_overrides', []);
        if (isset($overrides[$feature])) {
            return (bool) $overrides[$feature];
        }

        // Fall back to business type defaults
        return self::FEATURES[$businessType][$feature] ?? false;
    }

    /**
     * Get all features for the current tenant
     */
    public function getFeatures(): array
    {
        if (!$this->tenant) {
            return [];
        }

        $businessType = $this->tenant->business_type ?? 'general';
        $defaults = self::FEATURES[$businessType] ?? self::FEATURES['general'];
        $overrides = $this->tenant->getSetting('feature_overrides', []);

        return array_merge($defaults, $overrides);
    }

    /**
     * Get enabled features only
     */
    public function getEnabledFeatures(): array
    {
        return array_keys(array_filter($this->getFeatures()));
    }

    /**
     * Get feature label
     */
    public static function getFeatureLabel(string $feature, string $lang = 'en'): string
    {
        return self::FEATURE_LABELS[$feature][$lang] ?? $feature;
    }

    /**
     * Get all available business types
     */
    public static function getBusinessTypes(): array
    {
        return array_keys(self::FEATURES);
    }
}
