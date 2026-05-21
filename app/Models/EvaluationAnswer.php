<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EvaluationAnswer extends Model
{
    protected $fillable = [
        'evaluation_id',
        'question_id',
        'score',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(EvaluationQuestion::class, 'question_id');
    }

    public function criticalIncident(): HasOne
    {
        return $this->hasOne(CriticalIncident::class, 'answer_id');
    }

    public function employeeDiaryIncidents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            EmployeeDiaryIncident::class,
            'evaluation_incident_links',
            'evaluation_answer_id',
            'diary_incident_id'
        );
    }
}
