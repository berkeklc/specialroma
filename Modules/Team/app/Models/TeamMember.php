<?php

declare(strict_types=1);

namespace Modules\Team\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

final class TeamMember extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'team_members';

    protected $fillable = [
        'name', 'slug', 'position', 'bio', 'email', 'phone',
        'social_links', 'photo', 'is_active', 'sort_order',
    ];

    public array $translatable = ['name', 'position', 'bio'];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }
}
