<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_mixed_mode_enabled' => 'boolean',
        'randomize_cases' => 'boolean',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'default_case_count' => 10,
            'fuzzy_match_threshold' => 65,
            'fuzzy_partial_threshold' => 50,
            'knn_k_value' => 3,
            'is_mixed_mode_enabled' => true,
            'randomize_cases' => true,
        ]);
    }
}
