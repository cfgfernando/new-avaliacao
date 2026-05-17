<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class WeeklyReport extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table = 'weekly_reports';

    protected $fillable = [
        'cell_id',
        'meeting_date',
        'word_theme',
        'meeting_location',
        'committed_members',
        'present_members',
        'visitors',
        'children',
        'other_cell_visitors',
        'house_of_peace',
        'mdas_done',
        'kg_of_love',
        'reconciliations',
        'conversions',
        'offer_pix',
        'offer_cash',
        'status',
        'submitted_by',
        'submitted_at',
        'conciliated_by',
        'conciliated_at',
        'notes',
        'present_member_ids',
        'visitor_names',
    ];

    protected $casts = [
        'meeting_date'       => 'date',
        'submitted_at'       => 'datetime',
        'conciliated_at'     => 'datetime',
        'offer_pix'          => 'decimal:2',
        'offer_cash'         => 'decimal:2',
        'kg_of_love'         => 'decimal:2',
        'present_member_ids' => 'array',
        'visitor_names'      => 'array',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function conciliatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conciliated_by');
    }

    public function journalEntries(): MorphMany
    {
        return $this->morphMany(JournalEntry::class, 'source');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeDraft($query): mixed
    {
        return $query->where('status', 'Draft');
    }

    public function scopeSubmitted($query): mixed
    {
        return $query->where('status', 'Submitted');
    }

    public function scopeConciliated($query): mixed
    {
        return $query->where('status', 'Conciliated');
    }

    public function scopeForPeriod($query, string $start, string $end): mixed
    {
        return $query->whereBetween('meeting_date', [$start, $end]);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function getTotalOfferAttribute(): float
    {
        return (float) $this->offer_pix + (float) $this->offer_cash;
    }

    public function getTotalPresenceAttribute(): int
    {
        return (int) $this->present_members + (int) $this->visitors + (int) $this->children + (int) $this->other_cell_visitors;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Draft'       => 'Rascunho',
            'Submitted'   => 'Enviado',
            'Conciliated' => 'Conciliado',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Draft'       => 'yellow',
            'Submitted'   => 'blue',
            'Conciliated' => 'green',
            default       => 'gray',
        };
    }

    public function canBeSubmitted(): bool
    {
        return $this->status === 'Draft';
    }

    public function canBeConciliated(): bool
    {
        return $this->status === 'Submitted';
    }
}
