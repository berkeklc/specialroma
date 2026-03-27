<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\MenuCategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\QrMenu\App\Filament\Resources\MenuCategoryResource;

final class CreateMenuCategory extends CreateRecord
{
    protected static string $resource = MenuCategoryResource::class;
}
