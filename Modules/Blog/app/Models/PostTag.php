<?php

declare(strict_types=1);

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class PostTag extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory;
    use HasTranslations;
    use \Spatie\MediaLibrary\InteractsWithMedia;

    protected $table = 'blog_post_tags';

    protected $fillable = ['name', 'slug'];

    public array $translatable = ['name'];
}
