<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\Transaction;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\CostCenter;
use App\Models\Finance\ChartOfAccount;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $accounts = FinancialAccount::where('is_active', true)->get();
        $costCenters = CostCenter::where('is_active', true)->get();
        $chartOfAccounts = ChartOfAccount::where('is_active', true)->get();
        
        $transactions = Transaction::with(['financialAccount', 'chartOfAccount', 'costCenter'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.finance.transactions.index', compact('accounts', 'costCenters', 'chartOfAccounts', 'transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'payment_method' => 'required|in:cash,pix,transfer,credit_card,debit_card,slip',
            'amount' => 'required|string',
            'description' => 'required|string|max:255',
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'transaction_date' => 'required|date'
        ]);

        $amount = (float) str_replace(['.', ','], ['', '.'], $validated['amount']);
        $validated['amount'] = $amount;
        $validated['status'] = 'paid';

        Transaction::create($validated);

        return redirect()->back()->with('success', 'Transação registrada com sucesso!');
    }
}
