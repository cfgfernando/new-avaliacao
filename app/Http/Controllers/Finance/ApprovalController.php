<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        // Transações pendentes de aprovação (tipicamente despesas)
        $pendingTransactions = Transaction::with(['financialAccount', 'chartOfAccount', 'costCenter', 'member', 'financialAccount.bank'])
            ->where('status', 'pending_approval')
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        return view('admin.accounting.approvals.index', compact('pendingTransactions'));
    }

    public function approve(Request $request, Transaction $transaction)
    {
        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Despesa aprovada com sucesso! O saldo da conta foi atualizado.'
        ]);
    }

    public function reject(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Despesa rejeitada.'
        ]);
    }
}
