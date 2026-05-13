<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\Bank;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.finance.settings.index', ['tab' => 'bancos']);
    }

    public function show(Bank $bank)
    {
        return response()->json($bank);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'ispb' => 'nullable|string|max:50',
            'country' => 'required|string|max:2',
            'swift' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();
            $bank = Bank::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banco cadastrado com sucesso!',
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar banco: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Bank $bank)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'ispb' => 'nullable|string|max:50',
            'country' => 'required|string|max:2',
            'swift' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();
            $bank->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banco atualizado com sucesso!',
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar banco: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Bank $bank)
    {
        try {
            $bank->delete();
            return response()->json([
                'success' => true,
                'message' => 'Banco excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir banco: ' . $e->getMessage()
            ], 500);
        }
    }
}
