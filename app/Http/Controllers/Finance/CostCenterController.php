<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\CostCenter;
use Illuminate\Support\Facades\DB;

class CostCenterController extends Controller
{
    public function show(CostCenter $costCenter)
    {
        return response()->json($costCenter);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:cost_centers,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            $costCenter = CostCenter::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Centro de custo cadastrado com sucesso!',
                'data' => $costCenter
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar centro de custo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, CostCenter $costCenter)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:cost_centers,code,' . $costCenter->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            $costCenter->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Centro de custo atualizado com sucesso!',
                'data' => $costCenter
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar centro de custo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(CostCenter $costCenter)
    {
        try {
            $costCenter->delete();
            return response()->json([
                'success' => true,
                'message' => 'Centro de custo excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir centro de custo: ' . $e->getMessage()
            ], 500);
        }
    }
}
