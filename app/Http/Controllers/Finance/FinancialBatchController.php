<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\FinancialBatch;
use Illuminate\Support\Facades\Auth;

class FinancialBatchController extends Controller
{
    public function index()
    {
        $batches = FinancialBatch::orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.finance.batches.index', compact('batches'));
    }

    public function show($id)
    {
        $batch = FinancialBatch::findOrFail($id);
        return response()->json($batch);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:financial_batches',
            'status' => 'required|in:pending,confirmed,divergent',
            'declared_amount' => 'required|string',
        ]);

        $amount = (float) str_replace(['.', ','], ['', '.'], $validated['declared_amount']);
        $validated['declared_amount'] = $amount;
        $validated['opened_at'] = now();

        FinancialBatch::create($validated);

        return redirect()->back()->with('success', 'Malote aberto com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $batch = FinancialBatch::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|unique:financial_batches,code,' . $id,
            'status' => 'required|in:pending,confirmed,divergent',
            'declared_amount' => 'required|string',
        ]);

        $amount = (float) str_replace(['.', ','], ['', '.'], $validated['declared_amount']);
        $validated['declared_amount'] = $amount;

        $batch->update($validated);

        return redirect()->back()->with('success', 'Malote atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $batch = FinancialBatch::findOrFail($id);
        $batch->delete();

        return response()->json(['success' => true, 'message' => 'Malote excluído com sucesso!']);
    }
}
