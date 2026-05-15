<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAwarenessScore extends Model
{
    protected $guarded = [];

    protected $casts = [
        'knn_neighbors' => 'array',
        'category_scores' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(SimulationSession::class, 'session_id');
    }
}
