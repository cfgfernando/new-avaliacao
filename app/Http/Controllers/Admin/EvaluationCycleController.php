<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEvaluationCycleRequest;
use App\Http\Requests\Admin\UpdateEvaluationCycleRequest;
use App\Models\EvaluationCycle;
use App\Services\AuditService;
use Illuminate\Http\Request;

class EvaluationCycleController extends Controller
{
    public function index()
    {
        $cycles = EvaluationCycle::orderBy('start_date', 'desc')->get();
        return view('admin.evaluation-cycles.index', compact('cycles'));
    }

    public function create()
    {
        return view('admin.evaluation-cycles.create');
    }

    public function store(StoreEvaluationCycleRequest $request)
    {
        $data = $request->validated();
        $data['weights'] = $request->input('weights', []);

        $globalGoals = $request->input('global_goals', []);
        $sanitizedGoals = [];
        if (is_array($globalGoals)) {
            foreach ($globalGoals as $goal) {
                if (!empty($goal['description'])) {
                    $sanitizedGoals[] = [
                        'description' => (string) $goal['description'],
                        'metric' => (string) $goal['metric'],
                        'target_value' => (float) $goal['target_value'],
                        'weight' => (float) $goal['weight'],
                    ];
                }
            }
        }
        $data['global_goals'] = $sanitizedGoals;

        $cycle = EvaluationCycle::create($data);

        AuditService::log('CREATE_EVALUATION_CYCLE', [
            'cycle_id' => $cycle->id,
            'name' => $cycle->name,
        ]);

        return redirect()->route('admin.evaluation-cycles.index')
            ->with('success', 'Ciclo de avaliação criado com sucesso.');
    }

    public function edit(EvaluationCycle $evaluationCycle)
    {
        return view('admin.evaluation-cycles.edit', ['cycle' => $evaluationCycle]);
    }

    public function update(UpdateEvaluationCycleRequest $request, EvaluationCycle $evaluationCycle)
    {
        $data = $request->validated();
        $data['weights'] = $request->input('weights', []);

        $globalGoals = $request->input('global_goals', []);
        $sanitizedGoals = [];
        if (is_array($globalGoals)) {
            foreach ($globalGoals as $goal) {
                if (!empty($goal['description'])) {
                    $sanitizedGoals[] = [
                        'description' => (string) $goal['description'],
                        'metric' => (string) $goal['metric'],
                        'target_value' => (float) $goal['target_value'],
                        'weight' => (float) $goal['weight'],
                    ];
                }
            }
        }
        $data['global_goals'] = $sanitizedGoals;

        $evaluationCycle->update($data);

        AuditService::log('UPDATE_EVALUATION_CYCLE', [
            'cycle_id' => $evaluationCycle->id,
            'name' => $evaluationCycle->name,
        ]);

        return redirect()->route('admin.evaluation-cycles.index')
            ->with('success', 'Ciclo de avaliação atualizado com sucesso.');
    }

    public function destroy(EvaluationCycle $evaluationCycle)
    {
        $evaluationCycle->evaluations()->delete();
        $evaluationCycle->delete();

        AuditService::log('DELETE_EVALUATION_CYCLE', [
            'cycle_id' => $evaluationCycle->id,
        ]);

        return redirect()->route('admin.evaluation-cycles.index')
            ->with('success', 'Ciclo de avaliação excluído com sucesso.');
    }

    public function updateWeights(Request $request, EvaluationCycle $evaluationCycle)
    {
        $request->validate([
            'okr_weight' => 'required|numeric|min:0|max:100',
            'bars_weight' => 'required|numeric|min:0|max:100',
            'cutoff_score' => 'required|numeric|min:1|max:5',
        ]);

        $okr = (float) $request->input('okr_weight');
        $bars = (float) $request->input('bars_weight');

        $evaluationCycle->update([
            'weights' => [
                'okr' => $okr,
                'bars' => $bars,
            ],
            'cutoff_score' => (float) $request->input('cutoff_score'),
        ]);

        AuditService::log('UPDATE_EVALUATION_CYCLE_WEIGHTS', [
            'cycle_id' => $evaluationCycle->id,
            'weights' => ['okr' => $okr, 'bars' => $bars],
            'cutoff_score' => $request->input('cutoff_score'),
        ]);

        return redirect()->back()->with('success', 'Pesos e nota de corte atualizados com sucesso.');
    }
}
