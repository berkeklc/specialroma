<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\MenuCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\QrMenu\App\Filament\Resources\MenuCategoryResource;

final class EditMenuCategory extends EditRecord
{
    protected static string $resource = MenuCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
