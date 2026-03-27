<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Appointment extends Model
{
    use HasFactory;

    protected $table = 'meeting_appointments';

    protected $fillable = [
        'staff_id', 'client_name', 'client_email', 'client_phone', 'notes',
        'starts_at', 'ends_at', 'timezone', 'status', 'meeting_type',
        'meeting_link', 'meeting_id', 'meeting_password',
        'reminder_sent_at', 'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
