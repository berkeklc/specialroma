<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Filament\Resources\StaffResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Meeting\App\Filament\Resources\StaffResource;

final class EditStaff extends EditRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $defaults = StaffResource::defaultWorkingHours();
        $incoming = is_array($data['working_hours'] ?? null) ? $data['working_hours'] : [];
        $data['working_hours'] = array_replace_recursive($defaults, $incoming);

        return $data;
    }
}
