<?php

namespace App\Models;

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

    protected $appends = ['scheduled_at_formatted'];

    public function getScheduledAtFormattedAttribute(): ?string
    {
        return $this->scheduled_at?->format('d.m.Y H:i');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
