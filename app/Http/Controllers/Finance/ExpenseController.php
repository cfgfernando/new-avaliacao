<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Transaction;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\CostCenter;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function riskZone(Request $request)
    {
        $query = Transaction::where('type', 'expense')
            ->where('status', 'pending')
            ->whereDate('transaction_date', '<', now());

        // Filtros Inteligentes
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
        if ($request->filled('q')) {
            $query->where('description', 'like', '%' . $request->q . '%');
        }

        $perPage = $request->get('per_page', 15);
        $overdueTransactions = $query->with(['financialAccount', 'chartOfAccount', 'costCenter'])
            ->orderBy('transaction_date', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Estatísticas Compactas
        $stats = [
            'total_overdue' => Transaction::where('type', 'expense')->where('status', 'pending')->whereDate('transaction_date', '<', now())->sum('amount'),
            'critical_count' => Transaction::where('type', 'expense')->where('status', 'pending')->whereDate('transaction_date', '<', now())->count(),
            'total_pending' => Transaction::where('type', 'expense')->where('status', 'pending')->sum('amount'),
        ];

        // Dados para Gráfico: Atraso por Categoria (Top 5)
        $chartData = Transaction::where('transactions.type', 'expense')
            ->where('transactions.status', 'pending')
            ->whereDate('transactions.transaction_date', '<', now())
            ->join('chart_of_accounts', 'transactions.chart_of_account_id', '=', 'chart_of_accounts.id')
            ->selectRaw('chart_of_accounts.name as category, SUM(transactions.amount) as total')
            ->groupBy('chart_of_accounts.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $accounts = FinancialAccount::where('is_active', true)->get();
        $chartOfAccounts = ChartOfAccount::where('type', 'expense')->where('is_active', true)->get();
        $costCenters = CostCenter::where('is_active', true)->get();

        return view('admin.finance.expenses.risk-zone', compact('overdueTransactions', 'stats', 'accounts', 'chartOfAccounts', 'costCenters', 'chartData'));
    }

    public function index(Request $request)
    {
        $query = Transaction::where('type', 'expense')
            ->with(['financialAccount', 'chartOfAccount', 'costCenter']);

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
            $query->where('description', 'like', "%{$q}%");
        }

        $perPage = $request->get('per_page', 20);
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate($perPage)->withQueryString();

        // Estatísticas para Cards
        $stats = [
            'total_paid' => Transaction::where('type', 'expense')->where('status', 'paid')->whereMonth('transaction_date', now()->month)->sum('amount'),
            'total_pending' => Transaction::where('type', 'expense')->where('status', 'pending')->sum('amount'),
            'total_count' => Transaction::where('type', 'expense')->whereMonth('transaction_date', now()->month)->count(),
        ];

        // Dados para Gráfico (últimos 15 dias)
        $chartData = Transaction::where('type', 'expense')
            ->where('transaction_date', '>=', now()->subDays(15))
            ->selectRaw('DATE(transaction_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $accounts = FinancialAccount::where('is_active', true)->get();
        $chartOfAccounts = ChartOfAccount::where('type', 'expense')->where('is_active', true)->get();
        $costCenters = CostCenter::where('is_active', true)->get();

        return view('admin.finance.expenses.index', compact('transactions', 'accounts', 'chartOfAccounts', 'costCenters', 'stats', 'chartData'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'cost_center_id' => 'required|exists:cost_centers,id',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,paid,cancelled',
        ]);

        $data['type'] = 'expense';

        Transaction::create($data);

        return redirect()->route('admin.finance.expenses.index')->with('success', 'Despesa registrada com sucesso!');
    }
}
