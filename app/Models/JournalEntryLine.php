<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryLine extends Model
{
    use HasFactory;

    protected $table = 'journal_entry_lines';

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'type',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Lançamento contábil pai.
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    /**
     * Conta do Plano de Contas movimentada.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function isDebit(): bool
    {
        return $this->type === 'Debit';
    }

    public function isCredit(): bool
    {
        return $this->type === 'Credit';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'Debit' ? 'Débito' : 'Crédito';
    }
}
