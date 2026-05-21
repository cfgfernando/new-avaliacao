<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EvaluationQuestion extends Model
{
    protected $fillable = [
        'category',
        'group_type',
        'text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForGroup(Builder $query, string $groupType): Builder
    {
        return $query->where('group_type', $groupType);
    }

    public function barsAnchors(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BarsAnchor::class, 'question_id');
    }
}
