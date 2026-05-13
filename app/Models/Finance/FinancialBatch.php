<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'status',
        'declared_amount',
        'confirmed_amount',
        'opened_at',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'declared_amount' => 'decimal:2',
        'confirmed_amount' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function remittances()
    {
        return $this->hasMany(FinancialRemittance::class, 'batch_id');
    }
}
