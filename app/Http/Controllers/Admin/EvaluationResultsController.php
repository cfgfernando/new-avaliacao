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

    public function show(Evaluation $evaluation)
    {
        $evaluation->load([
            'evaluator',
            'evaluated',
            'cycle',
            'answers.question',
            'answers.question.options' // if we had options
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
        foreach ($categoryScores as $category => $data) {
            if ($data['count'] > 0) {
                $categoryAverages[$category] = [
                    'average' => round($data['total'] / $data['count'], 2),
                    'weightedAverage' => $data['weightedSum'] / array_sum(array_slice($weights, 0, count($weights))),
                    'responses' => $data['count']
                ];
            }
        }
        
        // Get critical incidents
        $criticalIncidents = $evaluation->answers
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