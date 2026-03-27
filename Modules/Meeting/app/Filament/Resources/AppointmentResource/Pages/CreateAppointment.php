<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Filament\Resources\AppointmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Meeting\App\Filament\Resources\AppointmentResource;

final class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
}
