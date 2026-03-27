<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Core\App\Filament\Resources\PageResource;

final class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
}
