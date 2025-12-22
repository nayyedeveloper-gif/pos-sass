<?php

if (!function_exists('module_path')) {
    /**
     * Get the path to a module.
     *
     * @param string $module
     * @param string $path
     * @return string
     */
    function module_path(string $module, string $path = ''): string
    {
        $modulePath = base_path('Modules/' . $module);
        
        return $path ? $modulePath . '/' . $path : $modulePath;
    }
}

if (!function_exists('module_exists')) {
    /**
     * Check if a module exists.
     *
     * @param string $module
     * @return bool
     */
    function module_exists(string $module): bool
    {
        return is_dir(module_path($module));
    }
}

if (!function_exists('get_active_modules')) {
    /**
     * Get list of active modules for current tenant.
     *
     * @return array
     */
    function get_active_modules(): array
    {
        $tenant = current_tenant();
        
        if (!$tenant) {
            return ['Core'];
        }

        $businessType = $tenant->business_type;
        
        $moduleMap = [
            'restaurant' => ['Core', 'Restaurant'],
            'cafe' => ['Core', 'Restaurant'],
            'fast_food' => ['Core', 'Restaurant'],
            'retail' => ['Core', 'Retail'],
            'grocery' => ['Core', 'Retail'],
            'mini_market' => ['Core', 'Retail'],
            'pharmacy' => ['Core', 'Pharmacy'],
        ];

        return $moduleMap[$businessType] ?? ['Core'];
    }
}

if (!function_exists('current_tenant')) {
    /**
     * Get the current tenant.
     *
     * @return \App\Models\Tenant|null
     */
    function current_tenant(): ?\App\Models\Tenant
    {
        if (auth()->check() && auth()->user()->tenant) {
            return auth()->user()->tenant;
        }
        
        return null;
    }
}

if (!function_exists('is_module_active')) {
    /**
     * Check if a module is active for current tenant.
     *
     * @param string $module
     * @return bool
     */
    function is_module_active(string $module): bool
    {
        return in_array($module, get_active_modules());
    }
}
