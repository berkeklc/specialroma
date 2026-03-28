<?php

declare(strict_types=1);

namespace Modules\Core\App\Livewire;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Modules\Core\App\Enums\LayoutType;
use Modules\Core\App\Models\Layout;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Settings\GeneralSettings;

final class SiteFooter extends Component
{
    public function render(): View
    {
        $layout = Layout::where('type', LayoutType::Footer->value)->where('is_active', true)->first();
        $settings = app(GeneralSettings::class);
        $footerMenu = Menu::where('location', 'footer')->first();

        $logoRow = collect($layout?->rows ?? [])->firstWhere('type', 'logo');
        $logoPath = is_array($logoRow) ? ($logoRow['data']['image'] ?? null) : null;
        $logoUrl = ! empty($logoPath) ? Storage::url($logoPath) : null;
        $textRow = collect($layout?->rows ?? [])->firstWhere('type', 'text_block');
        $socialLinks = $settings->social_links ?? [];

        return view('core::livewire.site-footer', compact(
            'layout',
            'settings',
            'footerMenu',
            'logoUrl',
            'textRow',
            'socialLinks',
        ));
    }
}
