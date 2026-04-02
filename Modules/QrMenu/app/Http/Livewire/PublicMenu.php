<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Settings\GeneralSettings;
use Modules\QrMenu\App\Models\Restaurant;

final class PublicMenu extends Component
{
    public Restaurant $restaurant;

    public string $search = '';

    public int $tableId;

    public function mount(Restaurant $restaurant, int $tableId): void
    {
        $this->restaurant = $restaurant;
        $this->tableId = $tableId;
    }

    public function render(GeneralSettings $settings): View
    {
        $locale = App::getLocale();

        $searchFilter = function ($query) {
            return $query->where('is_available', true)
                ->when($this->search, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('name', 'like', "%{$this->search}%")
                            ->orWhere('description', 'like', "%{$this->search}%");
                    });
                });
        };

        $categoriesQuery = $this->restaurant->categories()
            ->with([
                'items' => fn ($q) => $searchFilter($q)->orderBy('sort_order'),
            ])
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->whereHas('items', $searchFilter))
            ->orderBy('sort_order');

        $categories = $categoriesQuery->get();
        $primaryMenu = Menu::where('location', 'primary')->first();

        return view('qrmenu::livewire.public-menu', [
            'categories' => $categories,
            'allCategories' => $this->restaurant->categories()->where('is_active', true)->get(),
            'primaryMenu' => $primaryMenu,
            'settings' => $settings,
        ])->layout('qrmenu::layouts.qr-menu');
    }
}
