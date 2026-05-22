<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'cycle_id',
        'faltas_injustificadas',
        'atrasos_minutos',
        'horas_extras',
        'mensagem',
    ];

    protected $casts = [
        'faltas_injustificadas' => 'integer',
        'atrasos_minutos' => 'integer',
        'horas_extras' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(EvaluationCycle::class, 'cycle_id');
    }
}
