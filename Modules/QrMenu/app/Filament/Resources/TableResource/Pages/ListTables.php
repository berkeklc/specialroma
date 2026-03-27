<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\TableResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\QrMenu\App\Filament\Resources\TableResource;

final class ListTables extends ListRecords
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
