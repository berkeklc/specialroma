<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources\LayoutResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Core\App\Filament\Resources\LayoutResource;

final class ListLayouts extends ListRecords
{
    protected static string $resource = LayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
