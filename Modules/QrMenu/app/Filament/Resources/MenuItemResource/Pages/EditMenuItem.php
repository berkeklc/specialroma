<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\MenuItemResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\QrMenu\App\Filament\Resources\MenuItemResource;

final class EditMenuItem extends EditRecord
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
