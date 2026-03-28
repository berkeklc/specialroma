<?php

declare(strict_types=1);

namespace Modules\Meeting\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Meeting\App\Livewire\BookingPage;

final class MeetingServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Meeting';

    protected string $moduleNameLower = 'meeting';

    public function register(): void {}

    public function boot(): void
    {
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
        $this->loadViewsFrom(module_path($this->moduleName, 'resources/views'), $this->moduleNameLower);
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        Route::middleware('web')
            ->group(module_path($this->moduleName, 'routes/web.php'));

        Livewire::component('meeting::booking-page', BookingPage::class);
    }
}
