<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'document' => 'required|string|unique:suppliers,document',
            'document_type' => 'required|in:cpf,cnpj,other',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'contacts' => 'nullable|array',
            'address' => 'nullable|array',
            'bank_account' => 'nullable|array',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $supplier = Supplier::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Fornecedor cadastrado com sucesso!',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar fornecedor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'document' => 'required|string|unique:suppliers,document,' . $supplier->id,
            'document_type' => 'required|in:cpf,cnpj,other',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'contacts' => 'nullable|array',
            'address' => 'nullable|array',
            'bank_account' => 'nullable|array',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $supplier->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Fornecedor atualizado com sucesso!',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar fornecedor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return response()->json([
                'success' => true,
                'message' => 'Fornecedor excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir fornecedor: ' . $e->getMessage()
            ], 500);
        }
    }
}
