<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCycle extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'weights',
        'global_goals',
        'cutoff_score',
        'status',
        'block_on_pad',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'weights' => 'array',
        'global_goals' => 'array',
        'cutoff_score' => 'decimal:2',
        'block_on_pad' => 'boolean',
    ];

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'cycle_id');
    }
}
