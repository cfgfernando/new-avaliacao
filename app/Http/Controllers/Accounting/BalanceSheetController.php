<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\AccountingAudit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date)->endOfDay() : Carbon::now()->endOfDay();

        // 1. Ativos (Assets)
        $assets = $this->getGroupedTotals('asset', $date);
        $totalAssets = $assets->sum('total');

        // 2. Passivos (Liabilities)
        $liabilities = $this->getGroupedTotals('liability', $date);
        $totalLiabilities = $liabilities->sum('total');

        // 3. Patrimônio Líquido (Equity)
        // O lucro acumulado até a data também faz parte do PL
        $equity = $this->getGroupedTotals('equity', $date);
        
        // Resultado do Exercício (Receitas - Despesas até a data)
        $revenue = $this->getRawBalanceByType('revenue', $date);
        $expense = $this->getRawBalanceByType('expense', $date);
        $netResult = $revenue - $expense;

        $totalEquity = $equity->sum('total') + $netResult;

        return view('admin.accounting.reports.balance-sheet', compact(
            'assets',
            'totalAssets',
            'liabilities',
            'totalLiabilities',
            'equity',
            'totalEquity',
            'netResult',
            'date'
        ));
    }

    private function getGroupedTotals($type, $date)
    {
        // 1. Buscar movimentos até a data
        $balances = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->join('chart_of_accounts', 'accounting_audits.chart_of_account_id', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.type', $type)
            ->where('journal_entries.date', '<=', $date)
            ->selectRaw('chart_of_account_id, 
                         SUM(CASE WHEN accounting_audits.type = "debit" THEN amount ELSE 0 END) as total_debit,
                         SUM(CASE WHEN accounting_audits.type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        // 2. Buscar todas as contas do tipo
        $accounts = ChartOfAccount::where('type', $type)->orderBy('code')->get();

        $sheetData = $accounts->map(function ($account) use ($balances, $type) {
            $movement = $balances->get($account->id);
            $debits = $movement->total_debit ?? 0;
            $credits = $movement->total_credit ?? 0;
            
            $total = ($type === 'asset' || $type === 'expense') ? ($debits - $credits) : ($credits - $debits);

            return (object) [
                'id' => $account->id,
                'parent_id' => $account->parent_id,
                'code' => $account->code,
                'name' => $account->name,
                'total' => $total
            ];
        });

        // 3. Agregação Hierárquica
        return $this->aggregateHierarchy($sheetData);
    }

    private function aggregateHierarchy($data)
    {
        $accounts = $data->keyBy('id');
        $sorted = $accounts->sortByDesc(function($a) {
            return strlen($a->code);
        });

        foreach ($sorted as $account) {
            if ($account->parent_id && isset($accounts[$account->parent_id])) {
                $parent = $accounts[$account->parent_id];
                $parent->total += $account->total;
            }
        }

        return $accounts->values()->filter(fn($item) => $item->total != 0)->sortBy('code');
    }

    private function getRawBalanceByType($type, $date)
    {
        $totals = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->join('chart_of_accounts', 'accounting_audits.chart_of_account_id', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.type', $type)
            ->where('journal_entries.date', '<=', $date)
            ->selectRaw("SUM(CASE WHEN accounting_audits.type = 'debit' THEN amount ELSE 0 END) as total_debit")
            ->selectRaw("SUM(CASE WHEN accounting_audits.type = 'credit' THEN amount ELSE 0 END) as total_credit")
            ->first();

        return ($type === 'asset' || $type === 'expense') 
            ? ($totals->total_debit - $totals->total_credit) 
            : ($totals->total_credit - $totals->total_debit);
    }
}
