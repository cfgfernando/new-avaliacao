<?php

namespace App\Observers;

use App\Models\Finance\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->updateBalance($transaction, 'add');
        
        // Só contabiliza se estiver aprovado ou pago
        if (in_array($transaction->status, ['paid', 'approved'])) {
            app(\App\Services\Finance\AccountingService::class)->journalize($transaction);
        }
    }


    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Se houver alteração em valores críticos, precisamos estornar e reaplicar
        if ($transaction->isDirty(['amount', 'financial_account_id', 'destination_account_id', 'type', 'status'])) {
            // Criar uma instância temporária com os valores originais para o estorno de saldo
            $original = $transaction->replicate();
            foreach ($transaction->getOriginal() as $key => $value) {
                $original->{$key} = $value;
            }
            
            $this->updateBalance($original, 'subtract');
            $this->updateBalance($transaction, 'add');

            // Gestão Contábil Dinâmica
            if (in_array($transaction->status, ['paid', 'approved'])) {
                // Se a transação está ativa (paga ou aprovada), garantimos que o lançamento exista e esteja atualizado
                $transaction->journalEntries()->delete();
                app(\App\Services\Finance\AccountingService::class)->journalize($transaction);
            } else {
                // Se a transação voltou para pendente ou foi cancelada/rejeitada, removemos da contabilidade
                $transaction->journalEntries()->delete();
            }
        }
    }


    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->updateBalance($transaction, 'subtract');
        // TODO: Implementar estorno contábil se necessário
    }

    /**
     * Atualiza o saldo das contas envolvidas.
     */
    private function updateBalance(Transaction $transaction, string $action): void
    {
        // Regra de Governança: Apenas transações 'paid' ou 'approved' afetam o caixa.
        // Entradas (income) costumam ser 'paid' imediatamente.
        // Saídas (expense) podem ficar em 'pending_approval'.
        if (!in_array($transaction->status, ['paid', 'approved'])) {
            return;
        }

        $multiplier = ($action === 'add') ? 1 : -1;
        $amount = $transaction->amount * $multiplier;

        // Conta Principal (Origem)
        $account = $transaction->financialAccount;
        if ($account) {
            if ($transaction->type === 'income') {
                $account->increment('balance_cache', $amount);
            } else {
                // expense ou transfer saem da conta principal
                $account->decrement('balance_cache', $amount);
            }
        }

        // Conta de Destino (apenas para Transferências)
        if ($transaction->type === 'transfer' && $transaction->destination_account_id) {
            $destAccount = \App\Models\Finance\FinancialAccount::find($transaction->destination_account_id);
            if ($destAccount) {
                $destAccount->increment('balance_cache', $amount);
            }
        }
    }


    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
