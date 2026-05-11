<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cell extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cells';

    protected $fillable = [
        'name',
        'node_id',
        'leader_id',
        'meeting_day',
        'meeting_time',
        'address',
        'neighborhood',
        'city',
        'active',
    ];

    protected $casts = [
        'active'       => 'boolean',
        'meeting_time' => 'datetime:H:i',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Nó hierárquico (Setor) ao qual esta célula pertence.
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(HierarchyNode::class, 'node_id');
    }

    /**
     * Líder responsável pela célula.
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Membros (usuários) que pertencem a esta célula.
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'cell_id');
    }

    /**
     * Visitantes atribuídos a esta célula.
     */
    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class, 'assigned_cell_id');
    }

    /**
     * Relatórios semanais desta célula.
     */
    public function weeklyReports(): HasMany
    {
        return $this->hasMany(WeeklyReport::class, 'cell_id');
    }

    /**
     * Último relatório semanal (atalho).
     */
    public function latestReport(): HasOne
    {
        return $this->hasOne(WeeklyReport::class, 'cell_id')->latestOfMany('report_date');
    }

    /**
     * Despesas originadas nesta célula.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'cell_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeActive($query): mixed
    {
        return $query->where('active', true);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function getMeetingDayLabelAttribute(): string
    {
        return match ($this->meeting_day) {
            'Sunday'    => 'Domingo',
            'Monday'    => 'Segunda-feira',
            'Tuesday'   => 'Terça-feira',
            'Wednesday' => 'Quarta-feira',
            'Thursday'  => 'Quinta-feira',
            'Friday'    => 'Sexta-feira',
            'Saturday'  => 'Sábado',
            default     => $this->meeting_day,
        };
    }
}
