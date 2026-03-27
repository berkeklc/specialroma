<?php

declare(strict_types=1);

namespace Modules\Team\App\Filament\Resources\TeamMemberResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Team\App\Filament\Resources\TeamMemberResource;

final class CreateTeamMember extends CreateRecord
{
    protected static string $resource = TeamMemberResource::class;
}
