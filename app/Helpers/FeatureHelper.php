<?php

namespace App\Helpers;

use App\Services\BusinessFeatureService;

class FeatureHelper
{
    /**
     * Check if current tenant has a specific feature
     */
    public static function has(string $feature): bool
    {
        return app(BusinessFeatureService::class)
            ->forCurrentUser()
            ->hasFeature($feature);
    }

    /**
     * Alias for has() method - used by Blade directive
     */
    public static function hasFeature(string $feature): bool
    {
        return self::has($feature);
    }

    /**
     * Get all enabled features for current tenant
     */
    public static function enabled(): array
    {
        return app(BusinessFeatureService::class)
            ->forCurrentUser()
            ->getEnabledFeatures();
    }

    /**
     * Check if a module is active for current tenant
     */
    public static function isModuleActive(string $module): bool
    {
        return is_module_active($module);
    }

    /**
     * Get current active module name
     */
    public static function getCurrentModule(): string
    {
        $tenant = current_tenant();
        
        if (!$tenant) {
            return 'Core';
        }

        $modules = get_active_modules();
        return collect($modules)->filter(fn($m) => $m !== 'Core')->first() ?? 'Core';
    }
}
