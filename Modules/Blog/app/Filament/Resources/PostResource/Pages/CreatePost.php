<?php

declare(strict_types=1);

namespace Modules\Blog\App\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Blog\App\Filament\Resources\PostResource;

final class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
