<?php

declare(strict_types=1);

namespace Modules\Contact\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ContactSubmission extends Model
{
    use HasFactory;

    protected $table = 'contact_submissions';

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message',
        'form_key', 'ip_address', 'status', 'read_at', 'replied_at', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
        ];
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now(), 'status' => 'read']);
    }

    public function scopeUnread(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeNew(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'new');
    }
}
