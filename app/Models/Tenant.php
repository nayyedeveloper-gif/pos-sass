<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'business_type',
        'subdomain',
        'domain',
        'email',
        'phone',
        'address',
        'logo',
        'status',
        'plan',
        'trial_ends_at',
        'subscription_ends_at',
        'settings',
        'enabled_roles',
        'custom_fields',
    ];

    /**
     * Business types with their default roles
     */
    public const BUSINESS_TYPES = [
        'restaurant' => [
            'name' => 'Restaurant / F&B',
            'name_mm' => 'စားသောက်ဆိုင်',
            'roles' => ['owner', 'manager', 'cashier', 'waiter', 'kitchen', 'bar'],
            'icon' => 'utensils',
        ],
        'retail' => [
            'name' => 'Retail Store',
            'name_mm' => 'လက်လီဆိုင်',
            'roles' => ['owner', 'manager', 'cashier', 'sales', 'inventory'],
            'icon' => 'shopping-bag',
        ],
        'pharmacy' => [
            'name' => 'Pharmacy',
            'name_mm' => 'ဆေးဆိုင်',
            'roles' => ['owner', 'manager', 'cashier', 'pharmacist', 'inventory'],
            'icon' => 'pill',
        ],
        'grocery' => [
            'name' => 'Grocery / Mini Mart',
            'name_mm' => 'ကုန်စုံဆိုင် / မီနီမတ်',
            'roles' => ['owner', 'manager', 'cashier', 'inventory'],
            'icon' => 'shopping-cart',
        ],
        'cafe' => [
            'name' => 'Cafe / Coffee Shop',
            'name_mm' => 'ကော်ဖီဆိုင်',
            'roles' => ['owner', 'manager', 'cashier', 'barista', 'kitchen'],
            'icon' => 'coffee',
        ],
        'fast_food' => [
            'name' => 'Fast Food',
            'name_mm' => 'အမြန်စာ',
            'roles' => ['owner', 'manager', 'cashier', 'kitchen'],
            'icon' => 'hamburger',
        ],
        'liquor_store' => [
            'name' => 'Liquor Store / Bar',
            'name_mm' => 'အရက်ဆိုင် / ဘား',
            'roles' => ['owner', 'manager', 'cashier', 'bartender', 'inventory'],
            'icon' => 'wine',
        ],
        'salon' => [
            'name' => 'Salon / Spa',
            'name_mm' => 'ဆံပင်ညှပ်ဆိုင် / စပါ',
            'roles' => ['owner', 'manager', 'cashier', 'stylist'],
            'icon' => 'scissors',
        ],
        'ecommerce' => [
            'name' => 'E-Commerce / Online Shop',
            'name_mm' => 'အွန်လိုင်းဆိုင်',
            'roles' => ['owner', 'manager', 'order_processor', 'inventory'],
            'icon' => 'globe',
        ],
        'general' => [
            'name' => 'General Business',
            'name_mm' => 'အထွေထွေ',
            'roles' => ['owner', 'manager', 'cashier', 'staff'],
            'icon' => 'building',
        ],
    ];

    protected $casts = [
        'settings' => 'array',
        'enabled_roles' => 'array',
        'custom_fields' => 'array',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    /**
     * Get available roles for this tenant based on business type
     */
    public function getAvailableRoles(): array
    {
        // If custom enabled_roles is set, use that
        if ($this->enabled_roles) {
            return $this->enabled_roles;
        }

        // Otherwise, use default roles for business type
        $businessType = $this->business_type ?? 'general';
        return self::BUSINESS_TYPES[$businessType]['roles'] ?? self::BUSINESS_TYPES['general']['roles'];
    }

    /**
     * Get business type info
     */
    public function getBusinessTypeInfo(): array
    {
        return self::BUSINESS_TYPES[$this->business_type] ?? self::BUSINESS_TYPES['general'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' || $this->isOnTrial();
    }

    public function isOnTrial(): bool
    {
        return $this->status === 'trial' && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySubdomain($query, string $subdomain)
    {
        return $query->where('subdomain', $subdomain);
    }

    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
        $this->save();
    }
}
