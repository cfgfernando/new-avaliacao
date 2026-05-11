<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $fillable = [
        'code',
        'name',
        'type',
        'nature',
        'parent_id',
        'is_analytical',
        'current_balance',
        'active',
    ];

    protected $casts = [
        'is_analytical'   => 'boolean',
        'active'          => 'boolean',
        'current_balance' => 'decimal:2',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Conta pai (sintética) na árvore do Plano de Contas.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    /**
     * Contas filhas (analíticas ou sintéticas).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    /**
     * Árvore completa de descendentes.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Linhas de lançamento contábil que movimentam esta conta.
     */
    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class, 'account_id');
    }

    /**
     * Despesas classificadas nesta conta.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'account_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeAnalytical($query): mixed
    {
        return $query->where('is_analytical', true);
    }

    public function scopeActive($query): mixed
    {
        return $query->where('active', true);
    }

    public function scopeOfType($query, string $type): mixed
    {
        return $query->where('type', $type);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'Asset'     => 'Ativo',
            'Liability' => 'Passivo',
            'Equity'    => 'Patrimônio Líquido',
            'Revenue'   => 'Receita',
            'Expense'   => 'Despesa',
            default     => $this->type,
        };
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }
}
