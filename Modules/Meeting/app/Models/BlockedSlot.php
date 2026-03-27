<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BlockedSlot extends Model
{
    use HasFactory;

    protected $table = 'meeting_blocked_slots';

    protected $fillable = ['staff_id', 'starts_at', 'ends_at', 'reason', 'is_recurring'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_recurring' => 'boolean',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
