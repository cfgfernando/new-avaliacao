<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EmployeeDiaryIncident extends Model
{
    protected $fillable = [
        'employee_id',
        'reporter_id',
        'category',
        'description',
        'type',
        'incident_date',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function evaluationAnswers(): BelongsToMany
    {
        return $this->belongsToMany(
            EvaluationAnswer::class,
            'evaluation_incident_links',
            'diary_incident_id',
            'evaluation_answer_id'
        );
    }
}
