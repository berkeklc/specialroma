<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources\LayoutResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Core\App\Filament\Resources\LayoutResource;

final class CreateLayout extends CreateRecord
{
    protected static string $resource = LayoutResource::class;
}
