<?php

namespace Modules\Retail\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;

class RetailServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Retail';
    protected string $moduleNameLower = 'retail';

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
                ->prefix('retail')
                ->name('retail.')
                ->group(module_path($this->moduleName, 'Routes/web.php'));
        }
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('retail', function () {
            return "<?php if(is_module_active('Retail')): ?>";
        });

        Blade::directive('endretail', function () {
            return "<?php endif; ?>";
        });
    }
}
