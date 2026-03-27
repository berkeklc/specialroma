<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\QrMenu\App\Models\MenuTable;
use Modules\QrMenu\App\Models\Restaurant;

final class QrMenuController
{
    public function __invoke(Restaurant $restaurant, int $table): View|Response
    {
        abort_unless($restaurant->is_active, 404);

        $tableRecord = MenuTable::where('restaurant_id', $restaurant->id)
            ->where('id', $table)
            ->where('is_active', true)
            ->firstOrFail();

        return view('qrmenu::livewire.public-menu-page', [
            'restaurant' => $restaurant,
            'tableId' => $tableRecord->id,
        ]);
    }
}
