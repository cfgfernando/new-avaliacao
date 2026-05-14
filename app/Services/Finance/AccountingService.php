<?php

namespace App\Services\Finance;

use App\Models\Finance\Transaction;
use App\Models\Finance\JournalEntry;
use App\Models\Finance\AccountingAudit;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Faz a contabilização de uma transação financeira usando partidas dobradas.
     */
    public function journalize(Transaction $transaction)
    {
        if (\App\Models\Finance\Closure::isPeriodClosed($transaction->transaction_date)) {
            // Se o período estiver fechado, não permitimos a movimentação contábil
            return null;
        }

        return DB::transaction(function () use ($transaction) {
            // 1. Criar o cabeçalho do lançamento (JournalEntry)
            $journalEntry = JournalEntry::create([
                'transaction_id' => $transaction->id,
                'date' => $transaction->transaction_date,
                'description' => $transaction->description,
                'reference' => 'TX-' . $transaction->id,
            ]);

            $financialCoaId = $transaction->financialAccount->chart_of_account_id;
            $transactionCoaId = $transaction->chart_of_account_id;

            if (!$financialCoaId) {
                // Se a conta financeira não tiver mapeamento, não podemos contabilizar corretamente
                // TODO: Logar erro ou lançar exceção dependendo da política de rigidez
                return null;
            }

            if ($transaction->type === 'income') {
                // RECEITA:
                // DÉBITO: Conta de Ativo (Banco/Caixa)
                $this->createItem($journalEntry, $financialCoaId, 'debit', $transaction->amount);
                
                // CRÉDITO: Conta de Receita (Plano de Contas)
                $this->createItem($journalEntry, $transactionCoaId, 'credit', $transaction->amount);
            } elseif ($transaction->type === 'expense') {
                // DESPESA:
                // DÉBITO: Conta de Despesa (Plano de Contas)
                $this->createItem($journalEntry, $transactionCoaId, 'debit', $transaction->amount);
                
                // CRÉDITO: Conta de Ativo (Banco/Caixa)
                $this->createItem($journalEntry, $financialCoaId, 'credit', $transaction->amount);
            } elseif ($transaction->type === 'transfer') {
                // TRANSFERÊNCIA:
                // DÉBITO: Conta de Ativo Destino (Banco/Caixa)
                $destCoaId = $transaction->destinationAccount?->chart_of_account_id;
                if ($destCoaId) {
                    $this->createItem($journalEntry, $destCoaId, 'debit', $transaction->amount);
                    
                    // CRÉDITO: Conta de Ativo Origem (Banco/Caixa)
                    $this->createItem($journalEntry, $financialCoaId, 'credit', $transaction->amount);
                }
            }

            return $journalEntry;
        });
    }

    private function createItem(JournalEntry $entry, $coaId, $type, $amount)
    {
        return AccountingAudit::create([
            'journal_entry_id' => $entry->id,
            'chart_of_account_id' => $coaId,
            'type' => $type,
            'amount' => $amount,
        ]);
    }
}
