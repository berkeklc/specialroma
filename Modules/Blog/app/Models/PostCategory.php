<?php

declare(strict_types=1);

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class PostCategory extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory;
    use HasTranslations;
    use \Spatie\MediaLibrary\InteractsWithMedia;

    protected $table = 'blog_categories';

    protected $fillable = ['name', 'slug', 'description', 'color', 'sort_order'];

    public array $translatable = ['name', 'description'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
