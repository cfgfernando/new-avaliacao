<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarsAnchor extends Model
{
    protected $fillable = [
        'question_id',
        'score',
        'behavioral_description',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(EvaluationQuestion::class, 'question_id');
    }
}
