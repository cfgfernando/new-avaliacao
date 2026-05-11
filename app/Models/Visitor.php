<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'visitors';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'assigned_cell_id',
        'status',
        'last_contact_at',
        'contacted_by',
        'notes',
        'how_did_you_know',
    ];

    protected $casts = [
        'last_contact_at' => 'datetime',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Célula responsável pelo acompanhamento do visitante.
     */
    public function assignedCell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'assigned_cell_id');
    }

    /**
     * Líder ou membro que realizou o último contato.
     */
    public function contactedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contacted_by');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeActive($query): mixed
    {
        return $query->whereNotIn('status', ['Converted', 'Inactive']);
    }

    public function scopeNeedsContact($query, int $days = 7): mixed
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('last_contact_at')
              ->orWhere('last_contact_at', '<', now()->subDays($days));
        })->whereNotIn('status', ['Converted', 'Inactive']);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'New'        => 'Novo',
            'Returning'  => 'Retornou',
            'Interested' => 'Interessado',
            'Converted'  => 'Convertido',
            'Inactive'   => 'Inativo',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'New'        => 'blue',
            'Returning'  => 'yellow',
            'Interested' => 'purple',
            'Converted'  => 'green',
            'Inactive'   => 'gray',
            default      => 'gray',
        };
    }
}
