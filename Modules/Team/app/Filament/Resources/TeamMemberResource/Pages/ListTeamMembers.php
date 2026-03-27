<?php

declare(strict_types=1);

namespace Modules\Team\App\Filament\Resources\TeamMemberResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Team\App\Filament\Resources\TeamMemberResource;

final class ListTeamMembers extends ListRecords
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
