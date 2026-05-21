<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CriticalIncident extends Model
{
    protected $fillable = [
        'answer_id',
        'justification',
        'evidence_path',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(EvaluationAnswer::class, 'answer_id');
    }

    /**
     * Retorna a URL pública para o anexo da evidência.
     */
    public function getEvidenceUrlAttribute(): ?string
    {
        return $this->evidence_path ? Storage::url($this->evidence_path) : null;
    }
}
