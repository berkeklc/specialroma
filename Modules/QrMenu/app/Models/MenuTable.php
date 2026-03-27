<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MenuTable extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory;
    use \Spatie\MediaLibrary\InteractsWithMedia;

    protected $table = 'qr_menu_tables';

    protected $fillable = [
        'restaurant_id',
        'name',
        'qr_code_url',
        'qr_code_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('qr_code')->singleFile();
    }
}
