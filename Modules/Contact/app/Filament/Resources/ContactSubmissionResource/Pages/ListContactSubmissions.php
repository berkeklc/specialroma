<?php

declare(strict_types=1);

namespace Modules\Contact\App\Filament\Resources\ContactSubmissionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Contact\App\Filament\Resources\ContactSubmissionResource;

final class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;
}
