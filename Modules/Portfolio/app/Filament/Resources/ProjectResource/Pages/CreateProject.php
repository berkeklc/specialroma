<?php

declare(strict_types=1);

namespace Modules\Portfolio\App\Filament\Resources\ProjectResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Portfolio\App\Filament\Resources\ProjectResource;

final class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
}
