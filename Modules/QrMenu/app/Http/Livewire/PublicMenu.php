<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Http\Livewire;

use Illuminate\Support\Facades\App;
use Livewire\Component;
use Modules\QrMenu\App\Models\MenuCategory;
use Modules\QrMenu\App\Models\Restaurant;

final class PublicMenu extends Component
{
    public Restaurant $restaurant;

    public int $tableId;

    public ?int $activeCategory = null;

    public string $search = '';

    public function mount(Restaurant $restaurant, int $tableId): void
    {
        $this->restaurant = $restaurant;
        $this->tableId = $tableId;
    }

    public function selectCategory(int $categoryId): void
    {
        $this->activeCategory = $this->activeCategory === $categoryId ? null : $categoryId;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $locale = App::getLocale();

        $categoriesQuery = $this->restaurant->categories()
            ->with([
                'items' => fn ($q) => $q
                    ->where('is_available', true)
                    ->when($this->search, fn ($q) => $q->where("name->{$locale}", 'like', "%{$this->search}%"))
                    ->orderBy('sort_order'),
            ])
            ->where('is_active', true);

        if ($this->activeCategory) {
            $categoriesQuery->where('id', $this->activeCategory);
        }

        $categories = $categoriesQuery->get();

        return view('qrmenu::livewire.public-menu', [
            'categories' => $categories,
            'allCategories' => $this->restaurant->categories()->where('is_active', true)->get(),
        ])->layout('qrmenu::layouts.qr-menu');
    }
}
