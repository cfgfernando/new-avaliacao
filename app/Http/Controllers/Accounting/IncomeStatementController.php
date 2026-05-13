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
        return ChartOfAccount::where('type', $type)
            ->whereHas('audits', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with(['audits' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($account) use ($type) {
                $debits = $account->audits->where('type', 'debit')->sum('amount');
                $credits = $account->audits->where('type', 'credit')->sum('amount');
                
                $total = ($type === 'expense') ? ($debits - $credits) : ($credits - $debits);
                
                return (object) [
                    'code' => $account->code,
                    'name' => $account->name,
                    'total' => $total
                ];
            })
            ->filter(fn($item) => $item->total != 0)
            ->values();
    }
}
