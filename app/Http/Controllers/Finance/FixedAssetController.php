<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\FixedAsset;

class FixedAssetController extends Controller
{
    public function index()
    {
        $assets = FixedAsset::orderBy('purchase_date', 'desc')->get();
        return view('admin.finance.fixed-assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'purchase_date' => 'required|date',
            'purchase_value' => 'required|string',
            'depreciation_rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string'
        ]);

        // Convert money format
        $value = str_replace(['.', ','], ['', '.'], $validated['purchase_value']);
        $validated['purchase_value'] = (float) $value;
        $validated['current_value'] = $validated['purchase_value'];
        $validated['status'] = 'active';

        FixedAsset::create($validated);

        return redirect()->back()->with('success', 'Patrimônio imobilizado registrado com sucesso!');
    }
}
