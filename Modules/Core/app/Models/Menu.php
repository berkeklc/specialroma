<?php

declare(strict_types=1);

namespace Modules\Core\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class Menu extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'location',
        'label',
        'items',
    ];

    public array $translatable = ['label'];

    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }
}
