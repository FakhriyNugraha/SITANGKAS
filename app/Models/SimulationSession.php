<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SimulationSession extends Model
{
    protected $guarded = [];

    protected $casts = [
        'case_order' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class, 'session_id');
    }

    public function awarenessScore(): HasOne
    {
        return $this->hasOne(UserAwarenessScore::class, 'session_id');
    }
}
