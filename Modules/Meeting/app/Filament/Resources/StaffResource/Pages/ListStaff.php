<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Filament\Resources\StaffResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Meeting\App\Filament\Resources\StaffResource;

final class ListStaff extends ListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
