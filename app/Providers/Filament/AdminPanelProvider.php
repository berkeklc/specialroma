<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Modules\Blog\App\Filament\Resources\PostResource;
use Modules\Contact\App\Filament\Resources\ContactSubmissionResource;
use Modules\Core\App\Filament\Pages\GeneralSettingsPage;
use Modules\Core\App\Filament\Pages\MailSettingsPage;
use Modules\Core\App\Filament\Pages\SeoSettingsPage;
use Modules\Core\App\Filament\Resources\LayoutResource;
use Modules\Core\App\Filament\Resources\MenuResource;
use Modules\Core\App\Filament\Resources\PageResource;
use Modules\Core\App\Filament\Resources\UserResource;
use Modules\Meeting\App\Filament\Resources\AppointmentResource;
use Modules\Meeting\App\Filament\Resources\StaffResource;
use Modules\Portfolio\App\Filament\Resources\ProjectResource;
use Modules\QrMenu\App\Filament\Resources\MenuCategoryResource;
use Modules\QrMenu\App\Filament\Resources\MenuItemResource;
use Modules\QrMenu\App\Filament\Resources\RestaurantResource;
use Modules\QrMenu\App\Filament\Resources\TableResource;
use Modules\Services\App\Filament\Resources\ServiceResource;
use Modules\Team\App\Filament\Resources\TeamMemberResource;
use Nwidart\Modules\Facades\Module;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa()
            ->colors([
                'primary' => Color::Violet,
                'gray' => Color::Slate,
            ])
            ->font('DM Sans', 'https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap')
            ->brandName('AgencyStack')
            ->favicon(null)
            ->homeUrl(url('/'))
            ->sidebarWidth('17rem')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->resources($this->getResources())
            ->pages([
                GeneralSettingsPage::class,
                SeoSettingsPage::class,
                MailSettingsPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->userMenuItems([
                'general-settings' => MenuItem::make()
                    ->label(__('General settings'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (): string => GeneralSettingsPage::getUrl())
                    ->sort(24),
                'seo-settings' => MenuItem::make()
                    ->label(__('SEO & Analytics'))
                    ->icon('heroicon-o-magnifying-glass-circle')
                    ->url(fn (): string => SeoSettingsPage::getUrl())
                    ->sort(25),
                'mail-settings' => MenuItem::make()
                    ->label(__('Mail / SMTP'))
                    ->icon('heroicon-o-envelope')
                    ->url(fn (): string => MailSettingsPage::getUrl())
                    ->sort(26),
            ])
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_BEFORE, fn () => view('filament.hooks.admin-topbar-settings'))
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.hooks.admin-panel-styles'))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /** @return list<class-string> */
    private function getResources(): array
    {
        $resources = [
            PageResource::class,
            LayoutResource::class,
            MenuResource::class,
            UserResource::class,
        ];

        $moduleResources = [
            'QrMenu' => [
                RestaurantResource::class,
                MenuCategoryResource::class,
                MenuItemResource::class,
                TableResource::class,
            ],
            'Blog' => [
                PostResource::class,
            ],
            'Services' => [
                ServiceResource::class,
            ],
            'Portfolio' => [
                ProjectResource::class,
            ],
            'Team' => [
                TeamMemberResource::class,
            ],
            'Contact' => [
                ContactSubmissionResource::class,
            ],
            'Meeting' => [
                StaffResource::class,
                AppointmentResource::class,
            ],
        ];

        foreach ($moduleResources as $moduleName => $classes) {
            if (Module::isEnabled($moduleName)) {
                array_push($resources, ...$classes);
            }
        }

        return $resources;
    }
}
