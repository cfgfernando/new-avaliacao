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

        // 1. Buscar todas as contas
        $accounts = ChartOfAccount::orderBy('code')->get();

        // 2. Buscar todos os movimentos no período e antes dele
        $allAudits = AccountingAudit::select('chart_of_account_id', 'type', 'amount', 'created_at')
            ->get();

        $trialBalance = $accounts->map(function ($account) use ($startDate, $endDate, $allAudits) {
            // Saldo Anterior (antes de startDate)
            $openingAudits = $allAudits->where('chart_of_account_id', $account->id)
                ->where('created_at', '<', $startDate);
            
            $openingDebit = $openingAudits->where('type', 'debit')->sum('amount');
            $openingCredit = $openingAudits->where('type', 'credit')->sum('amount');
            
            $openingBalance = ($account->type === 'asset' || $account->type === 'expense') 
                ? $openingDebit - $openingCredit 
                : $openingCredit - $openingDebit;

            // Movimentação no Período
            $periodAudits = $allAudits->where('chart_of_account_id', $account->id)
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);
            
            $periodDebit = $periodAudits->where('type', 'debit')->sum('amount');
            $periodCredit = $periodAudits->where('type', 'credit')->sum('amount');

            $closingBalance = ($account->type === 'asset' || $account->type === 'expense')
                ? ($openingBalance + $periodDebit - $periodCredit)
                : ($openingBalance + $periodCredit - $periodDebit);

            return (object) [
                'id' => $account->id,
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

        // 3. TODO: Implementar agregação hierárquica (contas pai somam os filhos)
        // Por enquanto, mostraremos as contas diretas.

        return view('admin.accounting.reports.trial-balance', compact('trialBalance', 'startDate', 'endDate'));
    }
}
