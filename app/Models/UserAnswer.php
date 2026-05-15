<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'detected_indicators' => 'array',
        'missed_indicators' => 'array',
        'is_correct' => 'boolean',
        'help_opened' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(SimulationSession::class, 'session_id');
    }

    public function cyberCase(): BelongsTo
    {
        return $this->belongsTo(CyberCase::class);
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(CaseOption::class, 'selected_option_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
