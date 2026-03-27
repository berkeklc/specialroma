<?php

declare(strict_types=1);

namespace Modules\Portfolio\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class ProjectCategory extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'portfolio_categories';

    protected $fillable = ['name', 'slug', 'sort_order'];

    public array $translatable = ['name'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'category_id');
    }
}
