<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Core\App\Http\Livewire\LanguageSwitcher;
use Modules\Core\App\Livewire\SiteHeader;
use Modules\Core\App\Livewire\SiteFooter;
use Modules\Core\App\Livewire\PublicPage;
use Modules\Core\App\Settings\GeneralSettings;
use Modules\Core\App\Settings\MailSettings;
use Modules\Core\App\Settings\SeoSettings;

final class CoreServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Core';

    protected string $moduleNameLower = 'core';

    public function register(): void
    {
        $this->registerSettings();
    }

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerRoutes();
        $this->registerLivewireComponents();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
    }

    private function registerSettings(): void
    {
        // Bind settings classes as singletons so they're resolved correctly.
        // Spatie's SettingsServiceProvider handles the actual binding,
        // but we ensure the classes are discoverable by registering them here.
        $this->app->singleton(GeneralSettings::class);
        $this->app->singleton(SeoSettings::class);
        $this->app->singleton(MailSettings::class);
    }

    private function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'resources/lang'), $this->moduleNameLower);
        }
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    private function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);
        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    private function registerRoutes(): void
    {
        Route::middleware('web')
            ->group(module_path($this->moduleName, 'routes/web.php'));
    }

    private function registerLivewireComponents(): void
    {
        Livewire::component('language-switcher', LanguageSwitcher::class);
        Livewire::component('core::site-header', SiteHeader::class);
        Livewire::component('core::site-footer', SiteFooter::class);
        Livewire::component('core::public-page', PublicPage::class);
    }

    /** @return list<string> */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
