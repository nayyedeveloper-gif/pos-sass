<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Available modules and their service providers
     */
    protected array $modules = [
        'Core' => \Modules\Core\Providers\CoreServiceProvider::class,
        'Restaurant' => \Modules\Restaurant\Providers\RestaurantServiceProvider::class,
        'Retail' => \Modules\Retail\Providers\RetailServiceProvider::class,
        'Pharmacy' => \Modules\Pharmacy\Providers\PharmacyServiceProvider::class,
    ];

    /**
     * Module mapping by business type
     */
    protected array $businessTypeModules = [
        'restaurant' => ['Core', 'Restaurant'],
        'cafe' => ['Core', 'Restaurant'],
        'fast_food' => ['Core', 'Restaurant'],
        'bar' => ['Core', 'Restaurant'],
        'retail' => ['Core', 'Retail'],
        'grocery' => ['Core', 'Retail'],
        'mini_market' => ['Core', 'Retail'],
        'convenience_store' => ['Core', 'Retail'],
        'pharmacy' => ['Core', 'Pharmacy'],
        'drug_store' => ['Core', 'Pharmacy'],
        'clinic' => ['Core', 'Pharmacy'],
    ];

    public function register(): void
    {
        // Register all modules initially (they will check if they should be active)
        foreach ($this->modules as $module => $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    public function boot(): void
    {
        $this->registerBladeDirectives();
        $this->registerViewComposers();
    }

    protected function registerBladeDirectives(): void
    {
        // @module('Restaurant') ... @endmodule
        Blade::directive('module', function ($module) {
            return "<?php if(is_module_active({$module})): ?>";
        });

        Blade::directive('endmodule', function () {
            return "<?php endif; ?>";
        });

        // @feature('tables') ... @endfeature (improved version)
        Blade::directive('feature', function ($feature) {
            return "<?php if(\App\Helpers\FeatureHelper::hasFeature({$feature})): ?>";
        });

        Blade::directive('endfeature', function () {
            return "<?php endif; ?>";
        });
    }

    protected function registerViewComposers(): void
    {
        // Share active modules with all views
        view()->composer('*', function ($view) {
            $view->with('activeModules', get_active_modules());
            $view->with('currentModule', $this->getCurrentModule());
        });
    }

    protected function getCurrentModule(): string
    {
        $tenant = current_tenant();
        
        if (!$tenant) {
            return 'Core';
        }

        $businessType = $tenant->business_type;
        $modules = $this->businessTypeModules[$businessType] ?? ['Core'];
        
        // Return the main business module (not Core)
        return collect($modules)->filter(fn($m) => $m !== 'Core')->first() ?? 'Core';
    }

    /**
     * Get modules for a specific business type
     */
    public static function getModulesForBusinessType(string $businessType): array
    {
        $mapping = [
            'restaurant' => ['Core', 'Restaurant'],
            'cafe' => ['Core', 'Restaurant'],
            'fast_food' => ['Core', 'Restaurant'],
            'bar' => ['Core', 'Restaurant'],
            'retail' => ['Core', 'Retail'],
            'grocery' => ['Core', 'Retail'],
            'mini_market' => ['Core', 'Retail'],
            'convenience_store' => ['Core', 'Retail'],
            'pharmacy' => ['Core', 'Pharmacy'],
            'drug_store' => ['Core', 'Pharmacy'],
            'clinic' => ['Core', 'Pharmacy'],
        ];

        return $mapping[$businessType] ?? ['Core'];
    }
}
