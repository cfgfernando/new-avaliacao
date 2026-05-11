<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Expense extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table = 'expenses';

    protected $fillable = [
        'account_id',
        'node_id',
        'cell_id',
        'description',
        'amount',
        'expense_date',
        'beneficiary',
        'document_number',
        'payment_method',
        'requested_by',
        'approved_by',
        'approved_at',
        'status',
        'receipt_path',
        'notes',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'expense_date'=> 'date',
        'approved_at' => 'datetime',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Conta contábil de classificação desta despesa.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Nó hierárquico (Área/Setor/Rede) de origem.
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(HierarchyNode::class, 'node_id');
    }

    /**
     * Célula de origem (quando aplicável).
     */
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id');
    }

    /**
     * Usuário que solicitou a despesa.
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Tesoureiro/Admin que aprovou a despesa.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Lançamentos contábeis gerados para esta despesa (polimórfico).
     */
    public function journalEntries(): MorphMany
    {
        return $this->morphMany(JournalEntry::class, 'source');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopePending($query): mixed
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query): mixed
    {
        return $query->where('status', 'Approved');
    }

    public function scopePaid($query): mixed
    {
        return $query->where('status', 'Paid');
    }

    public function scopeForPeriod($query, string $start, string $end): mixed
    {
        return $query->whereBetween('expense_date', [$start, $end]);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Pending'  => 'Pendente',
            'Approved' => 'Aprovado',
            'Rejected' => 'Rejeitado',
            'Paid'     => 'Pago',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Pending'  => 'yellow',
            'Approved' => 'blue',
            'Rejected' => 'red',
            'Paid'     => 'green',
            default    => 'gray',
        };
    }
}
