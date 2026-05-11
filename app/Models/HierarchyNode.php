<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HierarchyNode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hierarchy_nodes';

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Nó pai na hierarquia (ex: Setor → Área).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(HierarchyNode::class, 'parent_id');
    }

    /**
     * Nós filhos diretos (ex: todos os Setores de uma Área).
     */
    public function children(): HasMany
    {
        return $this->hasMany(HierarchyNode::class, 'parent_id');
    }

    /**
     * Todos os descendentes de forma recursiva.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Células vinculadas a este nó hierárquico.
     */
    public function cells(): HasMany
    {
        return $this->hasMany(Cell::class, 'node_id');
    }

    /**
     * Despesas lançadas neste nó hierárquico.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'node_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeOfType($query, string $type): mixed
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query): mixed
    {
        return $query->where('active', true);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    /**
     * Retorna o label em português do tipo de nó.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'Network'  => 'Rede',
            'District' => 'Distrito',
            'Area'     => 'Área',
            'Sector'   => 'Setor',
            default    => $this->type,
        };
    }
}
