<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\AccountingAudit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // 1. Receitas (Revenue)
        $revenues = $this->getGroupedTotals('revenue', $startDate, $endDate);
        $totalRevenue = $revenues->sum('total');

        // 2. Despesas (Expenses)
        $expenses = $this->getGroupedTotals('expense', $startDate, $endDate);
        $totalExpense = $expenses->sum('total');

        // 3. Resultado Líquido
        $netResult = $totalRevenue - $totalExpense;

        return view('admin.accounting.reports.income-statement', compact(
            'revenues',
            'totalRevenue',
            'expenses',
            'totalExpense',
            'netResult',
            'startDate',
            'endDate'
        ));
    }

    private function getGroupedTotals($type, $startDate, $endDate)
    {
        // 1. Buscar movimentos do período agrupados por conta
        $balances = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->join('chart_of_accounts', 'accounting_audits.chart_of_account_id', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.type', $type)
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->selectRaw('chart_of_account_id, 
                         SUM(CASE WHEN accounting_audits.type = "debit" THEN amount ELSE 0 END) as total_debit,
                         SUM(CASE WHEN accounting_audits.type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        // 2. Buscar todas as contas do tipo solicitado
        $accounts = ChartOfAccount::where('type', $type)->orderBy('code')->get();

        $statementData = $accounts->map(function ($account) use ($balances, $type) {
            $movement = $balances->get($account->id);
            $debits = $movement->total_debit ?? 0;
            $credits = $movement->total_credit ?? 0;
            
            $total = ($type === 'expense') ? ($debits - $credits) : ($credits - $debits);

            return (object) [
                'id' => $account->id,
                'parent_id' => $account->parent_id,
                'code' => $account->code,
                'name' => $account->name,
                'total' => $total
            ];
        });

        // 3. Agregação Hierárquica
        return $this->aggregateHierarchy($statementData);
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
}
