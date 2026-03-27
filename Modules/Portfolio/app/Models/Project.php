<?php

declare(strict_types=1);

namespace Modules\Portfolio\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

final class Project extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'portfolio_projects';

    protected $fillable = [
        'category_id', 'title', 'slug', 'short_description', 'description',
        'client_name', 'client_url', 'completed_at', 'technologies',
        'meta_title', 'meta_description', 'status', 'is_featured', 'sort_order',
    ];

    public array $translatable = ['title', 'short_description', 'description', 'client_name', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'completed_at' => 'date',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
