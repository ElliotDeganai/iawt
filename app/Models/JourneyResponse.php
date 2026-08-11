<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JourneyResponse extends Model
{
    protected $fillable = ['user_id', 'step_number', 'data', 'completed_at', 'validated_at', 'rework_reason'];

    protected $casts = [
        'data'         => 'array',
        'completed_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}