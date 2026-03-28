<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;
use Modules\Contact\App\Models\ContactSubmission;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Models\Page;
use Modules\Meeting\App\Models\Appointment;
use Nwidart\Modules\Facades\Module;

final class AgencyStatsOverview extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected function getHeading(): ?string
    {
        return __('Site overview');
    }

    protected function getDescription(): ?string
    {
        return __('Content and activity at a glance.');
    }

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $stats = [];

        if (Schema::hasTable('pages')) {
            $stats[] = Stat::make(__('Published pages'), (string) Page::query()->where('status', 'published')->count())
                ->description(__('Total in CMS'))
                ->icon('heroicon-m-document-text')
                ->color('success');
        }

        if (Schema::hasTable('menus')) {
            $stats[] = Stat::make(__('Menus'), (string) Menu::query()->count())
                ->description(__('Navigation locations'))
                ->icon('heroicon-m-squares-2x2')
                ->color('info');
        }

        if (Schema::hasTable('users')) {
            $stats[] = Stat::make(__('Admin users'), (string) User::query()->count())
                ->description(__('With panel access'))
                ->icon('heroicon-m-users')
                ->color('gray');
        }

        if (Module::isEnabled('Meeting') && Schema::hasTable('meeting_appointments')) {
            $pending = Appointment::query()->where('status', 'pending')->count();
            $stats[] = Stat::make(__('Pending bookings'), (string) $pending)
                ->description(__('Awaiting confirmation'))
                ->icon('heroicon-m-calendar-days')
                ->color($pending > 0 ? 'warning' : 'success');
        }

        if (Module::isEnabled('Contact') && Schema::hasTable('contact_submissions')) {
            $unread = ContactSubmission::query()->unread()->count();
            $stats[] = Stat::make(__('Unread messages'), (string) $unread)
                ->description(__('Contact inbox'))
                ->icon('heroicon-m-envelope')
                ->color($unread > 0 ? 'danger' : 'success');
        }

        if ($stats === []) {
            $stats[] = Stat::make(__('AgencyStack'), '—')
                ->description(__('Run migrations to see live stats.'))
                ->color('gray');
        }

        return $stats;
    }
}
