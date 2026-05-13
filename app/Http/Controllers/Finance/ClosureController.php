<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClosureController extends Controller
{
    public function index()
    {
        $closures = Closure::with('lockedBy')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return view('admin.finance.closures.index', compact('closures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
            'notes' => 'nullable|string'
        ]);

        Closure::updateOrCreate(
            ['year' => $request->year, 'month' => $request->month],
            [
                'status' => 'closed',
                'locked_by_user_id' => Auth::id(),
                'locked_at' => now(),
                'notes' => $request->notes
            ]
        );

        return redirect()->route('admin.finance.closures.index')->with('success', 'Período contábil fechado com sucesso!');
    }

    public function update(Request $request, Closure $closure)
    {
        // Reabrir o período
        $closure->update([
            'status' => 'open',
            'locked_by_user_id' => null,
            'locked_at' => null
        ]);

        return redirect()->route('admin.finance.closures.index')->with('success', 'Período contábil reaberto!');
    }

    public function destroy(Closure $closure)
    {
        $closure->delete();
        return redirect()->route('admin.finance.closures.index')->with('success', 'Registro de fechamento removido.');
    }
}
