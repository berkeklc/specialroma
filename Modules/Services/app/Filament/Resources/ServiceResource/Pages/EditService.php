<?php

declare(strict_types=1);

namespace Modules\Services\App\Filament\Resources\ServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Services\App\Filament\Resources\ServiceResource;

final class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
