<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningMaterial extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function views(): HasMany
    {
        return $this->hasMany(MaterialView::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
