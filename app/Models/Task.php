<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'description',
        'location',
        'scheduled_at',
        'status',
        'completion_notes',
        'assigned_to',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $appends = [
        'scheduled_at_formatted',
        'scheduled_at_for_input',
    ];

    protected function scheduledAtForInput(): Attribute
    {
        return Attribute::get(
            fn() => $this->scheduled_at
                ? $this->scheduled_at->format('Y-m-d\TH:i')
                : null
        );
    }

    public function getScheduledAtFormattedAttribute(): ?string
    {
        return $this->scheduled_at?->format('d.m.Y H:i');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
