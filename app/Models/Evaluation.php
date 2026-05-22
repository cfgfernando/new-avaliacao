<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'cycle_id',
        'evaluator_id',
        'evaluated_id',
        'categoria',
        'status',
        'archived_by',
        'archived_at',
        'final_score',
        'weight_goals',
        'weight_competencies',
        'score_goals',
        'score_competencies',
        'submitted_at',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
        'weight_goals' => 'decimal:2',
        'weight_competencies' => 'decimal:2',
        'score_goals' => 'decimal:2',
        'score_competencies' => 'decimal:2',
        'submitted_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(EvaluationCycle::class, 'cycle_id');
    }

    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EvaluationAnswer::class, 'evaluation_id');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(QuantitativeGoal::class, 'evaluation_id');
    }

    /**
     * Calcula e atualiza a nota final mista (Metas + Competências BARS) da avaliação.
     */
    public function calculateFinalScore(): float
    {
        $this->load(['answers.question', 'cycle', 'goals']);
        
        $cycle = $this->cycle;
        if (!$cycle) {
            return 0.00;
        }

        // 1. CALCULAR NOTA DAS COMPETÊNCIAS BARS
        $weights = $cycle->weights ?? [];
        $totalWeightedCompScore = 0.00;
        $totalCompWeight = 0;

        foreach ($this->answers as $answer) {
            $category = $answer->question->category;
            $weight = (int) ($weights[$category] ?? 1);
            
            $totalWeightedCompScore += ($answer->score * $weight);
            $totalCompWeight += $weight;
        }

        $scoreCompetencies = $totalCompWeight > 0 ? ($totalWeightedCompScore / $totalCompWeight) : null;

        // 2. CALCULAR NOTA DAS METAS QUANTITATIVAS
        $scoreGoals = null;
        if ($this->goals->count() > 0) {
            $totalWeightedGoalScore = 0.00;
            $totalGoalWeight = 0.00;

            foreach ($this->goals as $goal) {
                // Se a meta ainda não foi aferida pelo RH, ignora no cálculo
                if ($goal->achieved_value === null) {
                    continue;
                }

                // Cálculo de atingimento (0.00 a 1.00)
                $target = (float) $goal->target_value;
                $achieved = (float) $goal->achieved_value;
                
                if ($target > 0) {
                    $attainment = $achieved / $target;
                } else {
                    $attainment = ($achieved >= $target) ? 1.00 : 0.00;
                }
                
                // Limita o atingimento a 100% (1.00) para evitar distorções, mas permite mínimo de 0.00
                $attainment = max(0.00, min(1.00, $attainment));
                
                // Converte atingimento proporcional para escala de 1 a 5
                // 100% de atingimento = nota 5.0
                // 50% de atingimento = nota 3.0
                // 0% de atingimento = nota 1.0
                $goalScore = 1.00 + ($attainment * 4.00);
                $weight = (float) ($goal->weight ?? 1.00);

                $totalWeightedGoalScore += ($goalScore * $weight);
                $totalGoalWeight += $weight;
            }

            $scoreGoals = $totalGoalWeight > 0 ? ($totalWeightedGoalScore / $totalGoalWeight) : null;
        }

        // 3. CALCULAR NOTA FINAL MISTA PONDERADA
        $finalScore = 0.00;
        $wCompetencies = (float) ($this->weight_competencies ?? 0.50);
        $wGoals = (float) ($this->weight_goals ?? 0.50);

        if ($scoreCompetencies !== null && $scoreGoals !== null) {
            $totalWeight = $wCompetencies + $wGoals;
            $finalScore = $totalWeight > 0 
                ? (($scoreCompetencies * $wCompetencies) + ($scoreGoals * $wGoals)) / $totalWeight 
                : 0.00;
        } elseif ($scoreCompetencies !== null) {
            $finalScore = $scoreCompetencies;
        } elseif ($scoreGoals !== null) {
            $finalScore = $scoreGoals;
        }

        // Atualizar no banco
        $this->update([
            'score_competencies' => $scoreCompetencies,
            'score_goals' => $scoreGoals,
            'final_score' => $finalScore,
        ]);

        return (float) $finalScore;
    }
}
