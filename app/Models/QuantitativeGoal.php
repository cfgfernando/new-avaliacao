<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuantitativeGoal extends Model
{
    protected $fillable = [
        'evaluation_id',
        'description',
        'metric',
        'target_value',
        'achieved_value',
        'weight',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'achieved_value' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }
}
