<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HierarchyNode;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.finance.settings.index', ['tab' => 'unidades']);
    }

    public function show($id)
    {
        $unit = HierarchyNode::findOrFail($id);
        return response()->json($unit);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:hierarchy_nodes,id'
        ]);

        try {
            DB::beginTransaction();
            $unit = HierarchyNode::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Unidade criada com sucesso!',
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar unidade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $unit = HierarchyNode::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:hierarchy_nodes,id'
        ]);

        try {
            DB::beginTransaction();
            $unit->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Unidade atualizada com sucesso!',
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar unidade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $unit = HierarchyNode::findOrFail($id);
        try {
            $unit->delete();
            return response()->json([
                'success' => true,
                'message' => 'Unidade excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir unidade: ' . $e->getMessage()
            ], 500);
        }
    }
}
