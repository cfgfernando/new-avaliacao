<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Finance\AccountingAudit;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingDashboardController extends Controller
{
    public function index()
    {
        // 1. Calcular Ativo Total
        $assets = $this->calculateBalanceByType('asset');
        
        // 2. Calcular Passivo Total
        $liabilities = $this->calculateBalanceByType('liability');
        
        // 3. Calcular Receitas e Despesas (Resultado)
        $revenue = $this->calculateBalanceByType('revenue');
        $expense = $this->calculateBalanceByType('expense');
        $netIncome = $revenue - $expense;

        // 4. Patrimônio Líquido (PL)
        $equity = $this->calculateBalanceByType('equity') + $netIncome;

        // 5. Últimos Lançamentos Contábeis
        $recentEntries = JournalEntry::with(['items.chartOfAccount'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('admin.accounting.dashboard', compact(
            'assets',
            'liabilities',
            'equity',
            'netIncome',
            'recentEntries'
        ));
    }

    private function calculateBalanceByType($type)
    {
        $coaIds = ChartOfAccount::where('type', $type)->pluck('id');
        
        if ($coaIds->isEmpty()) return 0;

        $totals = AccountingAudit::whereIn('chart_of_account_id', $coaIds)
            ->selectRaw("SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) as total_debit")
            ->selectRaw("SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) as total_credit")
            ->first();

        if ($type === 'asset' || $type === 'expense') {
            return $totals->total_debit - $totals->total_credit;
        } else {
            return $totals->total_credit - $totals->total_debit;
        }
    }
}
