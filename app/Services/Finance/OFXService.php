<?php

namespace App\Services\Finance;

use OfxParser\Parser;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OFXService
{
    public function parse($filePath): Collection
    {
        $parser = new Parser();
        $ofx = $parser->loadFromFile($filePath);

        $bankAccount = $ofx->BankAccounts[0]; // Pegamos a primeira conta por padrão
        $statement = $bankAccount->Statement;

        return collect($statement->transactions)->map(function($transaction) {
            return (object) [
                'id' => $transaction->uniqueId, // FITID

                'date' => Carbon::parse($transaction->date),
                'amount' => $transaction->amount,
                'description' => $transaction->memo ?: $transaction->name,
                'type' => $transaction->amount > 0 ? 'income' : 'expense'
            ];
        });
    }

    /**
     * Tenta encontrar uma transação no sistema que coincida com a do banco.
     */
    public function findMatch($bankTransaction, $financialAccountId)
    {
        return \App\Models\Finance\Transaction::where('financial_account_id', $financialAccountId)
            ->where('amount', abs($bankTransaction->amount))
            ->where('type', $bankTransaction->type)
            ->whereNull('reconciled_at')
            ->whereBetween('transaction_date', [
                $bankTransaction->date->copy()->subDays(3), 
                $bankTransaction->date->copy()->addDays(3)
            ])
            ->first();
    }
}
