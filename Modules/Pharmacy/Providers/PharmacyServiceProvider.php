<?php

namespace Modules\Pharmacy\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;

class PharmacyServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Pharmacy';
    protected string $moduleNameLower = 'pharmacy';

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerMigrations();
        $this->registerRoutes();
        $this->registerBladeDirectives();
    }

    public function register(): void
    {
        //
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    protected function registerViews(): void
    {
        $sourcePath = module_path($this->moduleName, 'Views');
        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    protected function registerRoutes(): void
    {
        if (file_exists(module_path($this->moduleName, 'Routes/web.php'))) {
            Route::middleware(['web', 'auth'])
                ->prefix('pharmacy')
                ->name('pharmacy.')
                ->group(module_path($this->moduleName, 'Routes/web.php'));
        }
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('pharmacy', function () {
            return "<?php if(is_module_active('Pharmacy')): ?>";
        });

        Blade::directive('endpharmacy', function () {
            return "<?php endif; ?>";
        });
    }
}
