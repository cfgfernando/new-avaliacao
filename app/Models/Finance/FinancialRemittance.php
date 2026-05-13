<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRemittance extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'remittable_type',
        'remittable_id',
        'amount',
        'type',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function batch()
    {
        return $this->belongsTo(FinancialBatch::class, 'batch_id');
    }

    public function remittable()
    {
        return $this->morphTo();
    }
}
