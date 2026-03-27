<?php

declare(strict_types=1);

namespace Modules\Contact\App\Filament\Resources\ContactSubmissionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Contact\App\Filament\Resources\ContactSubmissionResource;
use Modules\Contact\App\Models\ContactSubmission;

final class EditContactSubmission extends EditRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var ContactSubmission $record */
        $record = $this->getRecord();

        if ($record->status === 'new' && ! $record->read_at) {
            $data['read_at'] = now();
            $data['status'] = 'read';
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
