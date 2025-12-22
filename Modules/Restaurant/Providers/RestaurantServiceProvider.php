<?php

namespace Modules\Restaurant\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;

class RestaurantServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Restaurant';
    protected string $moduleNameLower = 'restaurant';

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
        // Restaurant module registration
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
                ->prefix('restaurant')
                ->name('restaurant.')
                ->group(module_path($this->moduleName, 'Routes/web.php'));
        }
    }

    protected function registerBladeDirectives(): void
    {
        // @restaurant directive - only renders content for restaurant business types
        Blade::directive('restaurant', function () {
            return "<?php if(is_module_active('Restaurant')): ?>";
        });

        Blade::directive('endrestaurant', function () {
            return "<?php endif; ?>";
        });
    }
}
