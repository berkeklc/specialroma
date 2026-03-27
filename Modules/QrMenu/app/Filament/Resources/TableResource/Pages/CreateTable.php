<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\TableResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\QrMenu\App\Filament\Resources\TableResource;

final class CreateTable extends CreateRecord
{
    protected static string $resource = TableResource::class;
}
