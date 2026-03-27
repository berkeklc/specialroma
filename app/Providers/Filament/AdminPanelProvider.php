<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Modules\Core\App\Filament\Pages\GeneralSettingsPage;
use Modules\Core\App\Filament\Pages\MailSettingsPage;
use Modules\Core\App\Filament\Pages\SeoSettingsPage;
use Modules\Core\App\Filament\Resources\LayoutResource;
use Modules\Core\App\Filament\Resources\PageResource;
use Modules\Core\App\Filament\Resources\UserResource;
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
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->brandName('AgencyStack')
            ->favicon(null)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->resources($this->getResources())
            ->pages([
                Pages\Dashboard::class,
                GeneralSettingsPage::class,
                SeoSettingsPage::class,
                MailSettingsPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            UserResource::class,
        ];

        $moduleResources = [
            'QrMenu' => [
                \Modules\QrMenu\App\Filament\Resources\MenuCategoryResource::class,
                \Modules\QrMenu\App\Filament\Resources\MenuItemResource::class,
                \Modules\QrMenu\App\Filament\Resources\TableResource::class,
            ],
            'Blog' => [
                \Modules\Blog\App\Filament\Resources\PostResource::class,
            ],
            'Services' => [
                \Modules\Services\App\Filament\Resources\ServiceResource::class,
            ],
            'Portfolio' => [
                \Modules\Portfolio\App\Filament\Resources\ProjectResource::class,
            ],
            'Team' => [
                \Modules\Team\App\Filament\Resources\TeamMemberResource::class,
            ],
            'Contact' => [
                \Modules\Contact\App\Filament\Resources\ContactSubmissionResource::class,
            ],
            'Meeting' => [
                \Modules\Meeting\App\Filament\Resources\AppointmentResource::class,
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
