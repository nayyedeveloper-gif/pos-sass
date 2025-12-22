<?php

namespace App\Providers;

use App\Services\BusinessFeatureService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BusinessFeatureService::class, function ($app) {
            return new BusinessFeatureService();
        });

        // Register Module Service Provider
        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register @feature directive
        Blade::directive('feature', function ($feature) {
            return "<?php if(app(\App\Services\BusinessFeatureService::class)->forCurrentUser()->hasFeature({$feature})): ?>";
        });

        // Register @endfeature directive
        Blade::directive('endfeature', function () {
            return "<?php endif; ?>";
        });
    }
}
