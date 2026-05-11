<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class JournalEntry extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'journal_entries';

    protected $fillable = [
        'reference',
        'entry_date',
        'description',
        'document_number',
        'source_type',
        'source_id',
        'created_by',
        'is_reversed',
        'reversal_of',
    ];

    protected $casts = [
        'entry_date'  => 'date',
        'is_reversed' => 'boolean',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Linhas do lançamento (Débitos e Créditos em Partida Dobrada).
     */
    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class, 'journal_entry_id');
    }

    /**
     * Usuário que criou o lançamento.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Origem polimórfica do lançamento (WeeklyReport, Expense, etc.).
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Lançamento original que este estorna (se aplicável).
     */
    public function reversalOf(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'reversal_of');
    }

    /**
     * Estorno gerado a partir deste lançamento.
     */
    public function reversal(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'reversal_of');
    }

    // =========================================================================
    // HELPERS - Validação de Partida Dobrada
    // =========================================================================

    /**
     * Verifica se os débitos batem com os créditos (regra de ouro da contabilidade).
     */
    public function isBalanced(): bool
    {
        $totals = $this->lines
            ->groupBy('type')
            ->map(fn ($lines) => $lines->sum('amount'));

        $debit  = $totals->get('Debit', 0);
        $credit = $totals->get('Credit', 0);

        return bccomp((string) $debit, (string) $credit, 2) === 0;
    }

    public function getTotalDebitAttribute(): float
    {
        return (float) $this->lines->where('type', 'Debit')->sum('amount');
    }

    public function getTotalCreditAttribute(): float
    {
        return (float) $this->lines->where('type', 'Credit')->sum('amount');
    }
}
