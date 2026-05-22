<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ArchivedEvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Evaluation::with(['evaluated', 'evaluator', 'cycle'])
            ->where('status', 'archived')
            ->orderBy('archived_at', 'desc');

        $evaluations = $query->paginate(30);

        return view('admin.evaluations.archived_index', compact('evaluations'));
    }

    public function restore(Request $request, Evaluation $evaluation): RedirectResponse
    {
        if ($evaluation->status !== 'archived') {
            return back()->with('error', 'Avaliação não está arquivada.');
        }

        $evaluation->status = 'draft';
        $evaluation->archived_by = null;
        $evaluation->archived_at = null;
        $evaluation->save();

        return back()->with('success', 'Avaliação restaurada com sucesso.');
    }
}
