<?php

declare(strict_types=1);

namespace Modules\Services\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

final class Service extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'short_description', 'description', 'icon',
        'featured_image', 'meta_title', 'meta_description', 'features',
        'price_from', 'status', 'is_featured', 'sort_order',
    ];

    public array $translatable = ['title', 'short_description', 'description', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'price_from' => 'decimal:2',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
