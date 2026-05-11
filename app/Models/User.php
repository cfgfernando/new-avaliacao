<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cell_id',
        'node_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id');
    }

    /**
     * Nó hierárquico gerenciado pelo Supervisor.
     */
    public function supervisedNode(): BelongsTo
    {
        return $this->belongsTo(HierarchyNode::class, 'node_id');
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'user_id');
    }

    public function disciples(): HasMany
    {
        return $this->hasMany(Member::class, 'mentor_id');
    }

    public function ledCells(): HasMany
    {
        return $this->hasMany(Cell::class, 'leader_id');
    }

    public function submittedReports(): HasMany
    {
        return $this->hasMany(WeeklyReport::class, 'submitted_by');
    }

    public function conciliatedReports(): HasMany
    {
        return $this->hasMany(WeeklyReport::class, 'conciliated_by');
    }

    public function requestedExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'requested_by');
    }

    public function approvedExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'approved_by');
    }

    public function contactedVisitors(): HasMany
    {
        return $this->hasMany(Visitor::class, 'contacted_by');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'created_by');
    }

    // =========================================================================
    // HELPERS RBAC
    // =========================================================================

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isTreasurer(): bool
    {
        return $this->role === 'Treasurer';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'Supervisor';
    }

    public function isLeader(): bool
    {
        return $this->role === 'Leader';
    }

    public function hasFinancialAccess(): bool
    {
        return in_array($this->role, ['Admin', 'Treasurer']);
    }

    /**
     * Retorna os IDs das células que este usuário pode gerenciar,
     * respeitando a hierarquia RBAC.
     *
     * @return \Illuminate\Support\Collection<int>
     */
    public function accessibleCellIds(): \Illuminate\Support\Collection
    {
        return match ($this->role) {
            'Admin', 'Treasurer' => Cell::pluck('id'),
            'Supervisor'         => Cell::whereHas('node', fn ($q) =>
                                        $q->where('id', $this->node_id)
                                          ->orWhere('parent_id', $this->node_id)
                                    )->pluck('id'),
            'Leader'             => collect([$this->cell_id])->filter(),
            default              => collect(),
        };
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'Admin'      => 'Administrador',
            'Treasurer'  => 'Tesoureiro',
            'Supervisor' => 'Supervisor',
            'Leader'     => 'Líder de Célula',
            default      => $this->role,
        };
    }
}
