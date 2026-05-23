<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEvaluationQuestionRequest;
use App\Http\Requests\Admin\UpdateEvaluationQuestionRequest;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationCycle;
use App\Services\AuditService;
use Illuminate\Http\Request;

class EvaluationQuestionController extends Controller
{
    public function index()
    {
        $group = request('group', 'geral');
        $category = request('category', '');
        $search = request('search', '');
        
        $query = EvaluationQuestion::with('barsAnchors')->where('group_type', $group);
        
        if ($category) {
            $query->where('category', $category);
        }
        
        if ($search) {
            $query->where('text', 'like', "%{$search}%");
        }
        
        $questions = $query->orderBy('category')->orderBy('text')->paginate(20);
        
        $categories = EvaluationQuestion::where('group_type', $group)
            ->distinct()
            ->pluck('category')
            ->toArray();

        $cycles = EvaluationCycle::orderBy('start_date', 'desc')->get();
        
        $cycleId = request('cycle_id');
        $activeCycle = null;
        if ($cycleId) {
            $activeCycle = EvaluationCycle::find($cycleId);
        }
        if (!$activeCycle) {
            $activeCycle = EvaluationCycle::where('status', 'active')->first()
                ?? EvaluationCycle::where('status', 'ativo')->first()
                ?? EvaluationCycle::orderBy('start_date', 'desc')->first();
        }
        
        $cats = [
            'assiduidade' => 'Assiduidade',
            'disciplina' => 'Disciplina',
            'iniciativa' => 'Iniciativa',
            'responsabilidade' => 'Responsabilidade',
            'cooperacao' => 'Cooperação',
            'qualidade' => 'Qualidade',
            'desenvolvimento_rh' => 'Desenvolvimento de RH',
            'avaliacao_usuario' => 'Avaliação do Usuário'
        ];
        
        return view('admin.evaluation-questions.index', compact(
            'questions', 'group', 'category', 'search', 'categories', 'cycles', 'activeCycle', 'cats'
        ));
    }

    public function create()
    {
        return view('admin.evaluation-questions.create');
    }

    public function store(StoreEvaluationQuestionRequest $request)
    {
        $data = $request->validated();
        $question = EvaluationQuestion::create($data);

        for ($i = 1; $i <= 5; $i++) {
            $anchorText = $request->input("anchor_{$i}");
            if ($anchorText) {
                $question->barsAnchors()->create([
                    'score' => $i,
                    'behavioral_description' => $anchorText
                ]);
            }
        }

        AuditService::log('CREATE_EVALUATION_QUESTION', [
            'question_id' => $question->id,
            'group' => $question->group_type,
            'category' => $question->category,
            'text' => substr($question->text, 0, 100),
        ]);

        return redirect()->route('admin.evaluation-questions.index', [
            'group' => $request->group_type ?? 'geral'
        ])->with('success', 'Pergunta criada com sucesso.');
    }

    public function show(EvaluationQuestion $evaluationQuestion)
    {
        return view('admin.evaluation-questions.show', ['question' => $evaluationQuestion]);
    }

    public function edit(EvaluationQuestion $evaluationQuestion)
    {
        return view('admin.evaluation-questions.edit', ['question' => $evaluationQuestion]);
    }

    public function update(UpdateEvaluationQuestionRequest $request, EvaluationQuestion $evaluationQuestion)
    {
        $data = $request->validated();
        $evaluationQuestion->update($data);

        // Salvar ou atualizar as âncoras de 1 a 5 se enviadas no request
        for ($i = 1; $i <= 5; $i++) {
            $anchorText = $request->input("anchor_{$i}");
            if ($anchorText !== null) {
                $evaluationQuestion->barsAnchors()->updateOrCreate(
                    ['score' => $i],
                    ['behavioral_description' => $anchorText]
                );
            }
        }

        AuditService::log('UPDATE_EVALUATION_QUESTION', [
            'question_id' => $evaluationQuestion->id,
            'group' => $evaluationQuestion->group_type,
            'category' => $evaluationQuestion->category,
            'text' => substr($evaluationQuestion->text, 0, 100),
        ]);

        return redirect()->route('admin.evaluation-questions.index', [
            'group' => $request->group_type ?? $evaluationQuestion->group_type
        ])->with('success', 'Pergunta atualizada com sucesso.');
    }

    public function destroy(EvaluationQuestion $evaluationQuestion)
    {
        // Check if question has been used in evaluations
        if ($evaluationQuestion->answers()->exists()) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir esta pergunta pois já foi utilizada em avaliações.');
        }

        $evaluationQuestion->delete();

        AuditService::log('DELETE_EVALUATION_QUESTION', [
            'question_id' => $evaluationQuestion->id,
            'group' => $evaluationQuestion->group_type,
            'category' => $evaluationQuestion->category,
        ]);

        return redirect()->route('admin.evaluation-questions.index', [
            'group' => $evaluationQuestion->group_type
        ])->with('success', 'Pergunta excluída com sucesso.');
    }

    public function toggle(EvaluationQuestion $evaluationQuestion)
    {
        $evaluationQuestion->update([
            'is_active' => !$evaluationQuestion->is_active
        ]);

        $action = $evaluationQuestion->is_active ? 'ACTIVATE_QUESTION' : 'DEACTIVATE_QUESTION';

        AuditService::log($action, [
            'question_id' => $evaluationQuestion->id,
            'group' => $evaluationQuestion->group_type,
            'category' => $evaluationQuestion->category,
        ]);

        return redirect()->back()->with('success', 
            $evaluationQuestion->is_active 
                ? 'Pergunta ativada com sucesso.' 
                : 'Pergunta desativada com sucesso.');
    }

    public function generateAnchors(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $title = $request->input('title');
        $desc = $request->input('description') ?? '';

        $anchors = [
            1 => "Demonstra comportamento insatisfatório em relação a {$title}, agindo de forma passiva, cometendo erros frequentes ou descumprindo os padrões básicos esperados.",
            2 => "Apresenta desempenho básico ou inconsistente em {$title}; cumpre os requisitos mínimos apenas sob supervisão direta e demonstra pouca proatividade.",
            3 => "Cumpre satisfatoriamente as expectativas para {$title}; realiza as tarefas padrão com autonomia, demonstrando domínio das rotinas do setor.",
            4 => "Destaca-se positivamente na competência {$title}; antecipa problemas, auxilia colegas de equipe e propõe melhorias práticas no fluxo de trabalho.",
            5 => "Exerce liderança inspiradora e referência absoluta em {$title}; desenvolve novas soluções estratégicas de alto impacto e capacita ativamente a equipe."
        ];

        return response()->json([
            'success' => true,
            'anchors' => $anchors
        ]);
    }
}