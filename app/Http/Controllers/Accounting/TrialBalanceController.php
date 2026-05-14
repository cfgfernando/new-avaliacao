<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\AccountingAudit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // 1. Buscar movimentos anteriores (Saldo de Abertura)
        $openingBalances = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.date', '<', $startDate)
            ->selectRaw('chart_of_account_id, 
                         SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debit,
                         SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        // 2. Buscar movimentos do período
        $periodBalances = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->selectRaw('chart_of_account_id, 
                         SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debit,
                         SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        // 3. Buscar todas as contas e montar o resultado
        $accounts = ChartOfAccount::orderBy('code')->get();
        
        $trialBalance = $accounts->map(function ($account) use ($openingBalances, $periodBalances) {
            $opening = $openingBalances->get($account->id);
            $period = $periodBalances->get($account->id);

            $openingDebit = $opening->total_debit ?? 0;
            $openingCredit = $opening->total_credit ?? 0;
            
            // Saldo anterior baseado no tipo da conta (Devedora ou Credora)
            $openingBalance = ($account->type === 'asset' || $account->type === 'expense') 
                ? $openingDebit - $openingCredit 
                : $openingCredit - $openingDebit;

            $periodDebit = $period->total_debit ?? 0;
            $periodCredit = $period->total_credit ?? 0;

            $closingBalance = ($account->type === 'asset' || $account->type === 'expense')
                ? ($openingBalance + $periodDebit - $periodCredit)
                : ($openingBalance + $periodCredit - $periodDebit);

            return (object) [
                'id' => $account->id,
                'parent_id' => $account->parent_id,
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'opening_balance' => $openingBalance,
                'debit' => $periodDebit,
                'credit' => $periodCredit,
                'closing_balance' => $closingBalance,
                'is_active' => $account->is_active
            ];
        });

        // 4. Agregação Hierárquica (Somar filhos nos pais)
        $trialBalance = $this->aggregateHierarchy($trialBalance);

        return view('admin.accounting.reports.trial-balance', compact('trialBalance', 'startDate', 'endDate'));
    }

    private function aggregateHierarchy($trialBalance)
    {
        // Ordenar por profundidade (contas sem filhos primeiro ou do maior código para o menor)
        // Mas o ideal é processar de baixo para cima.
        $accounts = $trialBalance->keyBy('id');
        
        // Criar um mapa de pais para filhos
        $parentMap = [];
        foreach ($accounts as $id => $account) {
            if ($account->parent_id) {
                $parentMap[$account->parent_id][] = $id;
            }
        }

        // Função recursiva para somar
        $this->sumChildren($accounts, $parentMap);

        return $accounts->values();
    }

    private function sumChildren(&$accounts, $parentMap)
    {
        foreach ($accounts as $id => $account) {
            if (isset($parentMap[$id])) {
                // Se tem filhos, somar recursivamente primeiro
                foreach ($parentMap[$id] as $childId) {
                    $child = $accounts[$childId];
                    
                    // Se o filho também tem filhos, já deve ter sido somado (ou chamamos recursivo)
                    // Para simplificar, faremos uma abordagem iterativa baseada na ordem inversa do código
                }
            }
        }

        // Abordagem mais robusta: Processar contas em ordem decrescente de profundidade de código
        $sorted = $accounts->sortByDesc(function($a) {
            return strlen($a->code);
        });

        foreach ($sorted as $account) {
            if ($account->parent_id && isset($accounts[$account->parent_id])) {
                $parent = $accounts[$account->parent_id];
                $parent->opening_balance += $account->opening_balance;
                $parent->debit += $account->debit;
                $parent->credit += $account->credit;
                $parent->closing_balance += $account->closing_balance;
            }
        }
    }
}
