<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\MenuItemResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\QrMenu\App\Filament\Resources\MenuItemResource;

final class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;
}
