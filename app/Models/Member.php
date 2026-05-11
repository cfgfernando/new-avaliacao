<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Member extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $table = 'members';

    protected $fillable = [
        'user_id',
        'mentor_id',
        'conversion_date',
        'baptism_date',
        'phone',
        'cpf',
        'birth_date',
        'gender',
        'marital_status',
        'status',
        'address',
    ];

    protected $casts = [
        'conversion_date' => 'date',
        'baptism_date'    => 'date',
        'birth_date'      => 'date',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Usuário do sistema vinculado a este membro.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mentor/Discipulador deste membro no modelo MDA.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Atalho para a célula deste membro (via user).
     */
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id')->through('user');
        // Nota: use $this->user->cell em vez de um relacionamento hasManyThrough
        // para evitar complexidade desnecessária aqui.
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeActive($query): mixed
    {
        return $query->where('status', 'Active');
    }

    public function scopeBaptized($query): mixed
    {
        return $query->whereNotNull('baptism_date');
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Active'      => 'Ativo',
            'Inactive'    => 'Inativo',
            'Transferred' => 'Transferido',
            'Deceased'    => 'Falecido',
            default       => $this->status,
        };
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }
}
