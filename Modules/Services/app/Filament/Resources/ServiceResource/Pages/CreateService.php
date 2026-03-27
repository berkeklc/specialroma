<?php

declare(strict_types=1);

namespace Modules\Services\App\Filament\Resources\ServiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Services\App\Filament\Resources\ServiceResource;

final class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;
}
