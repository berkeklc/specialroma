<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Filament\Resources\StaffResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Meeting\App\Filament\Resources\StaffResource;

final class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'working_hours' => StaffResource::defaultWorkingHours(),
            'meeting_duration' => 30,
            'buffer_time' => 0,
            'is_active' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['working_hours'] = array_replace_recursive(
            StaffResource::defaultWorkingHours(),
            is_array($data['working_hours'] ?? null) ? $data['working_hours'] : []
        );

        return $data;
    }
}
