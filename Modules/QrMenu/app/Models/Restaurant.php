<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

final class Restaurant extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'qr_menu_restaurants';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'logo',
        'working_hours',
        'currency',
        'primary_color',
        'is_active',
    ];

    public array $translatable = ['name', 'description'];

    protected function casts(): array
    {
        return [
            'working_hours' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function tables(): HasMany
    {
        return $this->hasMany(MenuTable::class, 'restaurant_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(MenuCategory::class, 'restaurant_id')->orderBy('sort_order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'restaurant_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('banner')->singleFile();
    }
}
