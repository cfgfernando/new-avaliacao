<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Finance\Transaction;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\FinancialBatch;
use Carbon\Carbon;

class FinanceDashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Métricas principais
        $totalBalance = FinancialAccount::where('is_active', true)->sum('balance_cache');
        
        $monthlyIncome = Transaction::where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
            
        $monthlyExpense = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $pendingBatches = FinancialBatch::where('status', 'pending')->count();

        // Transações Recentes
        $recentTransactions = Transaction::with(['financialAccount', 'chartOfAccount'])
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        return view('admin.finance.dashboard', compact(
            'totalBalance', 
            'monthlyIncome', 
            'monthlyExpense', 
            'pendingBatches',
            'recentTransactions'
        ));
    }
}
