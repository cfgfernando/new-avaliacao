<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    // Journalization moved to TransactionObserver

    protected $fillable = [
        'financial_account_id',
        'destination_account_id',
        'chart_of_account_id',
        'cost_center_id',
        'type',
        'payment_method',
        'amount',
        'description',
        'transaction_date',
        'status',
        'is_recurrent',
        'weekly_report_id',
        'member_id',
        'supplier_id',
        'reconciled_at',
        'bank_transaction_id',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];


    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'is_recurrent' => 'boolean',
    ];

    public function financialAccount()
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }

    public function destinationAccount()
    {
        return $this->belongsTo(FinancialAccount::class, 'destination_account_id');
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Dinheiro',
            'pix' => 'PIX',
            'transfer' => 'Transferência',
            'credit_card' => 'Cartão de Crédito',
            'debit_card' => 'Cartão de Débito',
            'slip' => 'Boleto',
            default => $this->payment_method,
        };
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'transaction_id');
    }

    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id');
    }
}
