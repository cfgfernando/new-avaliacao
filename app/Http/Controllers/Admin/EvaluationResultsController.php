<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationCycle;
use App\Models\EvaluationQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationResultsController extends Controller
{
    public function index()
    {
        // Get filter parameters
        $cycleId = request('cycle_id');
        $group = request('group', '');
        $userId = request('user_id', '');
        $status = request('status', '');
        
        // Base query
        $query = Evaluation::with(['evaluator', 'evaluated', 'cycle', 'answers.question'])
            ->orderBy('submitted_at', 'desc');
        
        // Apply filters
        if ($cycleId) {
            $query->where('cycle_id', $cycleId);
        }
        
        if ($group) {
            $query->whereHas('evaluated', function($q) use ($group) {
                $q->where('evaluation_group', $group);
            });
        }
        
        if ($userId) {
            $query->where('evaluated_id', $userId);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $evaluations = $query->paginate(15);
        
        // Get data for filters
        $cycles = EvaluationCycle::orderBy('start_date', 'desc')->get();
        $groups = User::whereNotNull('evaluation_group')
            ->distinct()
            ->pluck('evaluation_group')
            ->toArray();
        $users = User::whereNotNull('registration_number')
            ->orderBy('name')
            ->get();
        
        return view('admin.evaluation-results.index', compact(
            'evaluations', 'cycles', 'groups', 'users',
            'cycleId', 'group', 'userId', 'status'
        ));
    }

    /**
     * Advanced listing of completed evaluations with rich filters.
     */
    public function all(Request $request)
    {
        $cycleId = $request->get('cycle_id');
        $lotacao = $request->get('lotacao');
        $evaluatedName = $request->get('evaluated_name');
        $evaluatorName = $request->get('evaluator_name');
        $submittedFrom = $request->get('submitted_from');
        $submittedTo = $request->get('submitted_to');
        $scoreMin = $request->get('score_min');
        $scoreMax = $request->get('score_max');
        $categoria = $request->get('categoria');
        $status = $request->get('status');

        $query = Evaluation::with(['evaluator', 'evaluated', 'cycle'])
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'desc');

        if ($cycleId) {
            $query->where('cycle_id', $cycleId);
        }

        if ($lotacao) {
            $query->whereHas('evaluated', function ($q) use ($lotacao) {
                $q->where('lotacao', $lotacao);
            });
        }

        if ($evaluatedName) {
            $query->whereHas('evaluated', function ($q) use ($evaluatedName) {
                $q->where('name', 'like', '%' . $evaluatedName . '%');
            });
        }

        if ($evaluatorName) {
            $query->whereHas('evaluator', function ($q) use ($evaluatorName) {
                $q->where('name', 'like', '%' . $evaluatorName . '%');
            });
        }

        if ($submittedFrom) {
            $query->whereDate('submitted_at', '>=', $submittedFrom);
        }

        if ($submittedTo) {
            $query->whereDate('submitted_at', '<=', $submittedTo);
        }

        if ($scoreMin) {
            $query->where('final_score', '>=', (float) $scoreMin);
        }

        if ($scoreMax) {
            $query->where('final_score', '<=', (float) $scoreMax);
        }

        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $evaluations = $query->paginate(25)->appends($request->query());

        $cycles = EvaluationCycle::orderBy('start_date', 'desc')->get();
        $lotacoes = \App\Models\User::whereNotNull('lotacao')->distinct()->pluck('lotacao');

        return view('admin.evaluations.all_index', compact(
            'evaluations', 'cycles', 'lotacoes',
            'cycleId', 'lotacao', 'evaluatedName', 'evaluatorName',
            'submittedFrom', 'submittedTo', 'scoreMin', 'scoreMax', 'categoria', 'status'
        ));
    }

    /**
     * Export filtered results as CSV (or XLS via csv mime).
     */
    public function export(Request $request)
    {
        // Reuse the same filters as `all`
        $query = Evaluation::with(['evaluator', 'evaluated', 'cycle'])
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'desc');

        if ($request->get('cycle_id')) {
            $query->where('cycle_id', $request->get('cycle_id'));
        }

        if ($request->get('lotacao')) {
            $lotacao = $request->get('lotacao');
            $query->whereHas('evaluated', function ($q) use ($lotacao) {
                $q->where('lotacao', $lotacao);
            });
        }

        if ($request->get('evaluated_name')) {
            $name = $request->get('evaluated_name');
            $query->whereHas('evaluated', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($request->get('evaluator_name')) {
            $name = $request->get('evaluator_name');
            $query->whereHas('evaluator', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($request->get('submitted_from')) {
            $query->whereDate('submitted_at', '>=', $request->get('submitted_from'));
        }

        if ($request->get('submitted_to')) {
            $query->whereDate('submitted_at', '<=', $request->get('submitted_to'));
        }

        if ($request->get('score_min')) {
            $query->where('final_score', '>=', (float) $request->get('score_min'));
        }

        if ($request->get('score_max')) {
            $query->where('final_score', '<=', (float) $request->get('score_max'));
        }

        if ($request->get('categoria')) {
            $query->where('categoria', $request->get('categoria'));
        }

        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $rows = $query->get();

        $filename = 'avaliacoes_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            // BOM for UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, [
                'ID', 'Servidor', 'Matrícula', 'Avaliador', 'Ciclo', 'Lotação', 'Categoria', 'Nota Final', 'Status', 'Enviada Em'
            ]);

            foreach ($rows as $ev) {
                fputcsv($out, [
                    $ev->id,
                    $ev->evaluated?->name,
                    $ev->evaluated?->registration_number,
                    $ev->evaluator?->name,
                    $ev->cycle?->name,
                    $ev->evaluated?->lotacao,
                    $ev->categoria,
                    $ev->final_score,
                    $ev->status,
                    $ev->submitted_at ? $ev->submitted_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load([
            'evaluator',
            'evaluated',
            'cycle',
            'answers.question'
        ]);
        
        // Calculate category scores for radar chart
        $categoryScores = [];
        $weights = $evaluation->cycle->weights ?? [];
        $totalWeight = array_sum($weights);
        
        foreach ($evaluation->answers as $answer) {
            $category = $answer->question->category;
            if (!isset($categoryScores[$category])) {
                $categoryScores[$category] = [
                    'total' => 0,
                    'count' => 0,
                    'weight' => $weights[$category] ?? 1,
                    'weightedSum' => 0
                ];
            }
            
            $categoryScores[$category]['total'] += $answer->score;
            $categoryScores[$category]['count'] += 1;
            $categoryScores[$category]['weightedSum'] += ($answer->score * ($weights[$category] ?? 1));
        }
        
        // Calculate average scores per category
        $categoryAverages = [];
        $weightsSum = array_sum(array_slice($weights, 0, count($weights)));
        foreach ($categoryScores as $category => $data) {
            if ($data['count'] > 0) {
                $categoryAverages[$category] = [
                    'average' => round($data['total'] / $data['count'], 2),
                    'weightedAverage' => $weightsSum > 0 ? ($data['weightedSum'] / $weightsSum) : 0,
                    'responses' => $data['count']
                ];
            }
        }
        
        // Get critical incidents
        $criticalIncidents = $evaluation->answers()
            ->whereIn('score', [1, 2, 5])
            ->with(['question', 'criticalIncident'])
            ->get();
        
        return view('admin.evaluation-results.show', compact(
            'evaluation', 'categoryAverages', 'criticalIncidents'
        ));
    }

    public function updateGoals(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'goals' => 'required|array',
            'goals.*.id' => 'required|exists:quantitative_goals,id',
            'goals.*.achieved_value' => 'nullable|numeric|min:0',
        ], [
            'goals.*.achieved_value.numeric' => 'O valor alcançado deve ser um número.',
            'goals.*.achieved_value.min' => 'O valor alcançado não pode ser negativo.',
        ]);

        foreach ($request->input('goals', []) as $goalData) {
            $goal = $evaluation->goals()->where('id', $goalData['id'])->first();
            if ($goal) {
                $goal->update([
                    'achieved_value' => ($goalData['achieved_value'] !== null && $goalData['achieved_value'] !== '') 
                        ? (float) $goalData['achieved_value'] 
                        : null
                ]);
            }
        }

        // Recalcular a nota final
        $evaluation->calculateFinalScore();

        return redirect()->route('admin.evaluation-results.show', $evaluation->id)
            ->with('success', 'Valores alcançados das metas quantitativas atualizados e nota final recalculada com sucesso!');
    }
}