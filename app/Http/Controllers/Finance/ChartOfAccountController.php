<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\ChartOfAccount;
use Illuminate\Support\Facades\DB;

class ChartOfAccountController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.finance.settings.index', ['tab' => 'plano-contas']);
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        return response()->json($chartOfAccount);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:chart_of_accounts,code',
            'name' => 'required|string|max:100',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            $coa = ChartOfAccount::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta contábil criada com sucesso!',
                'data' => $coa
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar conta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'name' => 'required|string|max:100',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            $chartOfAccount->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta contábil atualizada com sucesso!',
                'data' => $chartOfAccount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar conta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        try {
            $chartOfAccount->delete();
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
