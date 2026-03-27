<?php

declare(strict_types=1);

namespace Modules\Core\App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Core\App\Models\Layout;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Enums\LayoutType;
use Modules\Core\App\Settings\GeneralSettings;

final class SiteHeader extends Component
{
    #[Computed]
    public function layout(): ?Layout
    {
        return Layout::where('type', LayoutType::Header->value)
            ->where('is_active', true)
            ->first();
    }

    #[Computed]
    public function settings(): GeneralSettings
    {
        return app(GeneralSettings::class);
    }

    #[Computed]
    public function primaryMenu(): ?Menu
    {
        return Menu::where('location', 'primary')->first();
    }

    public function render(): \Illuminate\View\View
    {
        return view('core::livewire.site-header');
    }
}
