<?php

declare(strict_types=1);

namespace Modules\Team\App\Filament\Resources\TeamMemberResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Team\App\Filament\Resources\TeamMemberResource;

final class EditTeamMember extends EditRecord
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
