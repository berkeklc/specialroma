<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Team\App\Models\TeamMember;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

final class Staff extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'meeting_staff';

    protected $fillable = [
        'team_member_id', 'name', 'email', 'phone', 'photo', 'title', 'bio', 'expertise',
        'working_hours', 'meeting_duration', 'buffer_time', 'is_active',
    ];

    public array $translatable = ['title', 'bio'];

    protected function casts(): array
    {
        return [
            'expertise' => 'array',
            'working_hours' => 'array',
            'meeting_duration' => 'integer',
            'buffer_time' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'staff_id');
    }

    public function blockedSlots(): HasMany
    {
        return $this->hasMany(BlockedSlot::class, 'staff_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }
}
