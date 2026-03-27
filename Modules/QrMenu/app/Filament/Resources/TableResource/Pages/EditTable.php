<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\TableResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\QrMenu\App\Filament\Resources\TableResource;

final class EditTable extends EditRecord
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
