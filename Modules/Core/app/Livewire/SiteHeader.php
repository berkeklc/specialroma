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
use Modules\QrMenu\App\Models\Restaurant;
use Nwidart\Modules\Facades\Module;

final class SiteHeader extends Component
{
    public function render(): View
    {
        // Always fetch fresh — no Livewire computed caching so admin changes reflect immediately.
        $layout = Layout::where('type', LayoutType::Header->value)->where('is_active', true)->first();
        $settings = app(GeneralSettings::class);
        $primaryMenu = Menu::where('location', 'primary')->first();

        // Logo path lives in Layout Builder JSON (FileUpload), not MediaLibrary.
        $logoRow = collect($layout?->rows ?? [])->firstWhere('type', 'logo');
        $logoPath = is_array($logoRow) ? ($logoRow['data']['image'] ?? null) : null;
        $logoUrl = ! empty($logoPath) ? Storage::url($logoPath) : null;
        $logoAlt = $logoUrl
            ? ((is_array($logoRow) ? ($logoRow['data']['alt'] ?? null) : null) ?: $settings->site_name)
            : null;

        $ctaRow = collect($layout?->rows ?? [])->firstWhere('type', 'cta_button');

        $qrMenuUrl = null;
        if (Module::isEnabled('QrMenu')) {
            $restaurant = Restaurant::query()
                ->where('is_active', true)
                ->where('slug', 'special-roma')
                ->first();
            $table = $restaurant?->tables()->where('is_active', true)->first();
            if ($restaurant && $table) {
                $qrMenuUrl = route('qr-menu.public', [
                    'restaurant' => $restaurant->slug,
                    'table' => $table->id,
                ]);
            }
        }

        return view('core::livewire.site-header', compact(
            'layout',
            'settings',
            'primaryMenu',
            'logoUrl',
            'logoAlt',
            'ctaRow',
            'qrMenuUrl',
        ));
    }
}
