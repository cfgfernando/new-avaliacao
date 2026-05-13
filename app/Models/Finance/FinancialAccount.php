<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'chart_of_account_id',
        'bank_name',
        'agency',
        'account_number',
        'balance_cache',
        'is_active',
    ];

    protected $casts = [
        'balance_cache' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
