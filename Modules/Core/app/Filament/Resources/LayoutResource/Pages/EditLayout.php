<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources\LayoutResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Core\App\Filament\Resources\LayoutResource;

final class EditLayout extends EditRecord
{
    protected static string $resource = LayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
