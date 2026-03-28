<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\AgencyQuickLinks;
use App\Filament\Widgets\AgencyStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

final class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    #[\Override]
    public function getTitle(): string|Htmlable
    {
        return __('Control center');
    }

    /**
     * @return array<class-string>
     */
    #[\Override]
    public function getWidgets(): array
    {
        return [
            AgencyStatsOverview::class,
            AgencyQuickLinks::class,
        ];
    }

    /**
     * @return int|string|array<string, int|string|null>
     */
    #[\Override]
    public function getColumns(): int|string|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 3,
        ];
    }
}
