<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Modules\Core\App\Filament\Resources\LayoutResource;
use Modules\Core\App\Filament\Resources\MenuResource;
use Modules\Core\App\Filament\Resources\PageResource;
use Modules\Meeting\App\Filament\Resources\StaffResource;
use Nwidart\Modules\Facades\Module;

final class AgencyQuickLinks extends Widget
{
    protected static bool $isDiscovered = false;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.widgets.agency-quick-links';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $links = [
            [
                'label' => __('Pages'),
                'description' => __('Edit site pages & blocks'),
                'icon' => 'heroicon-o-document-text',
                'url' => PageResource::getUrl(),
            ],
            [
                'label' => __('Menus'),
                'description' => __('Header, footer & links'),
                'icon' => 'heroicon-o-squares-2x2',
                'url' => MenuResource::getUrl(),
            ],
            [
                'label' => __('Header / Footer'),
                'description' => __('Layout builder'),
                'icon' => 'heroicon-o-rectangle-group',
                'url' => LayoutResource::getUrl(),
            ],
        ];

        if (Module::isEnabled('Meeting')) {
            $links[] = [
                'label' => __('Bookings'),
                'description' => __('Staff & appointments'),
                'icon' => 'heroicon-o-calendar-days',
                'url' => StaffResource::getUrl(),
            ];
        }

        $links[] = [
            'label' => __('View website'),
            'description' => __('Open public site'),
            'icon' => 'heroicon-o-arrow-top-right-on-square',
            'url' => url('/'),
            'open' => true,
        ];

        return [
            'links' => $links,
        ];
    }
}
