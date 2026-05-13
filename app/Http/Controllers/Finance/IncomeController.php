<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Transaction;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\CostCenter;
use App\Models\Member;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('type', 'income')
            ->with(['financialAccount', 'chartOfAccount', 'costCenter', 'member']);

        // Filtros
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        if ($request->filled('financial_account_id')) {
            $query->where('financial_account_id', $request->financial_account_id);
        }

        if ($request->filled('chart_of_account_id')) {
            $query->where('chart_of_account_id', $request->chart_of_account_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhereHas('member', function($m) use ($q) {
                        $m->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $perPage = $request->get('per_page', 20);
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate($perPage)->withQueryString();

        // Estatísticas para Cards
        $stats = [
            'total_paid' => Transaction::where('type', 'income')->where('status', 'paid')->whereMonth('transaction_date', now()->month)->sum('amount'),
            'total_pending' => Transaction::where('type', 'income')->where('status', 'pending')->sum('amount'),
            'total_count' => Transaction::where('type', 'income')->whereMonth('transaction_date', now()->month)->count(),
        ];

        // Dados para Gráfico (últimos 15 dias)
        $chartData = Transaction::where('type', 'income')
            ->where('transaction_date', '>=', now()->subDays(15))
            ->selectRaw('DATE(transaction_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $accounts = FinancialAccount::where('is_active', true)->get();
        $chartOfAccounts = ChartOfAccount::where('type', 'revenue')->where('is_active', true)->get();
        $costCenters = CostCenter::where('is_active', true)->get();
        $members = Member::with('user')->limit(50)->get();
        $cells = \App\Models\Cell::orderBy('name')->get();

        return view('admin.finance.income.index', compact('transactions', 'accounts', 'chartOfAccounts', 'costCenters', 'members', 'cells', 'stats', 'chartData'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'cost_center_id' => 'required|exists:cost_centers,id',
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,paid,cancelled',
        ]);

        $data['type'] = 'income';

        Transaction::create($data);

        return redirect()->route('admin.finance.income.index')->with('success', 'Receita registrada com sucesso!');
    }
}
