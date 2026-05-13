<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'date',
        'description',
        'reference',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function audits()
    {
        return $this->hasMany(AccountingAudit::class, 'journal_entry_id');
    }

    public function items()
    {
        return $this->hasMany(AccountingAudit::class, 'journal_entry_id');
    }
}
