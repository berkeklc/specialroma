<?php

declare(strict_types=1);

namespace Modules\Contact\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Contact\App\Livewire\ContactForm;

final class ContactServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Contact';

    protected string $moduleNameLower = 'contact';

    public function register(): void {}

    public function boot(): void
    {
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
        $this->loadViewsFrom(module_path($this->moduleName, 'resources/views'), $this->moduleNameLower);
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        Route::middleware('web')->group(module_path($this->moduleName, 'routes/web.php'));
        Livewire::component('contact::contact-form', ContactForm::class);
    }
}
