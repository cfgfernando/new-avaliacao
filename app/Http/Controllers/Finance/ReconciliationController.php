<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\Transaction;
use App\Services\Finance\OFXService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReconciliationController extends Controller
{
    protected $ofxService;

    public function __construct(OFXService $ofxService)
    {
        $this->ofxService = $ofxService;
    }

    public function index()
    {
        $accounts = FinancialAccount::where('is_active', true)->get();

        return view('admin.accounting.reconciliation.index', compact('accounts'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'ofx_file' => 'required|file'
        ]);

        $path = $request->file('ofx_file')->getRealPath();
        $bankTransactions = $this->ofxService->parse($path);
        
        $account = FinancialAccount::find($request->financial_account_id);

        $results = $bankTransactions->map(function($bankTx) use ($account) {
            $match = $this->ofxService->findMatch($bankTx, $account->id);
            
            return [
                'bank' => $bankTx,
                'match' => $match,
                'is_reconciled' => Transaction::where('bank_transaction_id', $bankTx->id)->exists()
            ];
        });

        return view('admin.accounting.reconciliation.dashboard', compact('results', 'account'));
    }

    public function reconcile(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'bank_transaction_id' => 'required|string'
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        
        // Se a transação já foi conciliada por outro item
        if ($transaction->reconciled_at) {
            return response()->json(['success' => false, 'message' => 'Esta transação já está conciliada.']);
        }

        $transaction->update([
            'reconciled_at' => now(),
            'bank_transaction_id' => $request->bank_transaction_id,
            'status' => 'paid' // Garante que esteja paga ao conciliar
        ]);

        return response()->json(['success' => true, 'message' => 'Conciliado com sucesso!']);
    }
}
