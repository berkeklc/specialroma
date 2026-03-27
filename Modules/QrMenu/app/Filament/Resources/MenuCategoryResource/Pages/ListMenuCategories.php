<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\MenuCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\QrMenu\App\Filament\Resources\MenuCategoryResource;

final class ListMenuCategories extends ListRecords
{
    protected static string $resource = MenuCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
