<?php

declare(strict_types=1);

namespace Modules\Portfolio\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class PortfolioServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Portfolio';

    protected string $moduleNameLower = 'portfolio';

    public function register(): void {}

    public function boot(): void
    {
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
        $this->loadViewsFrom(module_path($this->moduleName, 'resources/views'), $this->moduleNameLower);
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        Route::middleware('web')->group(module_path($this->moduleName, 'routes/web.php'));
    }
}
