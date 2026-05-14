<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\AccountingAudit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFReportController extends Controller
{
    public function trialBalance(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        $openingBalances = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.date', '<', $startDate)
            ->selectRaw('chart_of_account_id, 
                         SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debit,
                         SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $periodBalances = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->selectRaw('chart_of_account_id, 
                         SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debit,
                         SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $accounts = ChartOfAccount::orderBy('code')->get();
        
        $data = $accounts->map(function ($account) use ($openingBalances, $periodBalances) {
            $opening = $openingBalances->get($account->id);
            $period = $periodBalances->get($account->id);

            $openingDebit = $opening->total_debit ?? 0;
            $openingCredit = $opening->total_credit ?? 0;
            
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
                'closing_balance' => $closingBalance
            ];
        });

        $trialBalance = $this->aggregateHierarchy($data);

        $pdf = Pdf::loadView('admin.accounting.reports.pdf.trial-balance', [
            'trialBalance' => $trialBalance,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generated_at' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->setPaper('a4', 'landscape')->stream("Balancete_{$startDate->format('d-m-Y')}.pdf");
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        $revenues = $this->getGroupedTotals('revenue', $startDate, $endDate);
        $totalRevenue = $revenues->sum('total');

        $expenses = $this->getGroupedTotals('expense', $startDate, $endDate);
        $totalExpense = $expenses->sum('total');

        $netResult = $totalRevenue - $totalExpense;

        $pdf = Pdf::loadView('admin.accounting.reports.pdf.income-statement', [
            'revenues' => $revenues,
            'totalRevenue' => $totalRevenue,
            'expenses' => $expenses,
            'totalExpense' => $totalExpense,
            'netResult' => $netResult,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generated_at' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->setPaper('a4')->stream("DRE_{$startDate->format('d-m-Y')}.pdf");
    }

    public function balanceSheet(Request $request)
    {
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        $assets = $this->getGroupedTotals('asset', null, $endDate);
        $liabilities = $this->getGroupedTotals('liability', null, $endDate);
        $equity = $this->getGroupedTotals('equity', null, $endDate);

        $totalAsset = $assets->sum('total');
        $totalLiability = $liabilities->sum('total');
        $totalEquity = $equity->sum('total');

        $pdf = Pdf::loadView('admin.accounting.reports.pdf.balance-sheet', [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'totalAsset' => $totalAsset,
            'totalLiability' => $totalLiability,
            'totalEquity' => $totalEquity,
            'endDate' => $endDate,
            'generated_at' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->setPaper('a4')->stream("Balanco_Patrimonial_{$endDate->format('d-m-Y')}.pdf");
    }

    private function getGroupedTotals($type, $startDate, $endDate)
    {
        $query = AccountingAudit::join('journal_entries', 'accounting_audits.journal_entry_id', '=', 'journal_entries.id')
            ->join('chart_of_accounts', 'accounting_audits.chart_of_account_id', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.type', $type)
            ->where('journal_entries.date', '<=', $endDate);

        if ($startDate) {
            $query->where('journal_entries.date', '>=', $startDate);
        }

        $balances = $query->selectRaw('chart_of_account_id, 
                         SUM(CASE WHEN accounting_audits.type = "debit" THEN amount ELSE 0 END) as total_debit,
                         SUM(CASE WHEN accounting_audits.type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $accounts = ChartOfAccount::where('type', $type)->orderBy('code')->get();

        $statementData = $accounts->map(function ($account) use ($balances, $type) {
            $movement = $balances->get($account->id);
            $debits = $movement->total_debit ?? 0;
            $credits = $movement->total_credit ?? 0;
            
            $total = ($type === 'expense' || $type === 'asset') ? ($debits - $credits) : ($credits - $debits);

            return (object) [
                'id' => $account->id,
                'parent_id' => $account->parent_id,
                'code' => $account->code,
                'name' => $account->name,
                'total' => $total
            ];
        });

        return $this->aggregateTotalsHierarchy($statementData);
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
                $parent->opening_balance += $account->opening_balance;
                $parent->debit += $account->debit;
                $parent->credit += $account->credit;
                $parent->closing_balance += $account->closing_balance;
            }
        }

        return $accounts->values();
    }

    private function aggregateTotalsHierarchy($data)
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
