<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\Bank;
use Illuminate\Support\Facades\DB;

class FinancialAccountController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.finance.settings.index', ['tab' => 'contas']);
    }

    public function show(FinancialAccount $account)
    {
        return response()->json($account);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:bank,cash,investment',
            'bank_id' => 'nullable|exists:banks,id',
            'agency' => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:50',
            'initial_balance' => 'required',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            
            // Handle bank_name if bank_id is provided
            if ($request->bank_id) {
                $bank = Bank::find($request->bank_id);
                $validated['bank_name'] = $bank->name;
            }
            unset($validated['bank_id']);
            $balance = $validated['initial_balance'];
            if (is_string($balance)) {
                $balance = str_replace(['.', ','], ['', '.'], $balance);
            }
            
            $validated['balance_cache'] = (float) $balance;
            unset($validated['initial_balance']);

            $account = FinancialAccount::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta criada com sucesso!',
                'data' => $account
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar conta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, FinancialAccount $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:bank,cash,investment',
            'bank_id' => 'nullable|exists:banks,id',
            'agency' => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Handle bank_name if bank_id is provided
            if ($request->bank_id) {
                $bank = Bank::find($request->bank_id);
                $validated['bank_name'] = $bank->name;
            }
            unset($validated['bank_id']);
            $account->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta atualizada com sucesso!',
                'data' => $account
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar conta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(FinancialAccount $account)
    {
        try {
            $account->delete();
            return response()->json([
                'success' => true,
                'message' => 'Conta excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir conta: ' . $e->getMessage()
            ], 500);
        }
    }
}
