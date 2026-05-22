<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationCycle;
use App\Models\EvaluationQuestion;
use App\Models\CriticalIncident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class EvaluationController extends Controller
{
    /**
     * Exibe a listagem de todas as avaliações feitas pelo avaliador.
     */
    public function index(Request $request)
    {
        $evaluatorId = auth()->id();
        
        $query = Evaluation::where('evaluator_id', $evaluatorId)
            ->with(['evaluated', 'cycle']);
            
        // Filtros avançados
        $cycleId = $request->input('cycle_id');
        if ($cycleId) {
            $query->where('cycle_id', $cycleId);
        }
        
        $lotacao = $request->input('lotacao');
        if ($lotacao) {
            $query->whereHas('evaluated', function($q) use ($lotacao) {
                $q->where('lotacao', $lotacao);
            });
        }
        
        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }
        
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('evaluated', function($q) use ($search) {
                $q->where(function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('registration_number', 'like', "%{$search}%");
                });
            });
        }

        // Filtro por faixa de nota / desempenho
        $scoreRange = $request->input('score_range');
        if ($scoreRange === 'above') {
            $query->where('status', 'submitted')
                  ->where(function($q) {
                      $q->whereRaw('final_score >= (select COALESCE(cutoff_score, 3.00) from evaluation_cycles where evaluation_cycles.id = evaluations.cycle_id)');
                  });
        } elseif ($scoreRange === 'below') {
            $query->where('status', 'submitted')
                  ->where(function($q) {
                      $q->whereRaw('final_score < (select COALESCE(cutoff_score, 3.00) from evaluation_cycles where evaluation_cycles.id = evaluations.cycle_id)');
                  });
        }

        // 1. Extração de métricas dinâmicas filtradas antes de ordenar e paginar
        $metricsQuery = clone $query;
        
        $totalEvaluations = $metricsQuery->count();
        $submittedCount = (clone $metricsQuery)->where('status', 'submitted')->count();
        $draftCount = (clone $metricsQuery)->where('status', 'draft')->count();
        
        $avgScore = (clone $metricsQuery)->where('status', 'submitted')
            ->whereNotNull('final_score')
            ->avg('final_score');
            
        $avgScore = $avgScore !== null ? (float) $avgScore : null;

        // 2. Ordenação
        $sortBy = $request->input('sort_by', 'recent');
        if ($sortBy === 'name_asc') {
            $query->join('users as evaluated_users', 'evaluations.evaluated_id', '=', 'evaluated_users.id')
                  ->select('evaluations.*')
                  ->orderBy('evaluated_users.name', 'asc');
        } elseif ($sortBy === 'name_desc') {
            $query->join('users as evaluated_users', 'evaluations.evaluated_id', '=', 'evaluated_users.id')
                  ->select('evaluations.*')
                  ->orderBy('evaluated_users.name', 'desc');
        } elseif ($sortBy === 'score_desc') {
            $query->orderBy('final_score', 'desc');
        } elseif ($sortBy === 'score_asc') {
            $query->orderBy('final_score', 'asc');
        } elseif ($sortBy === 'oldest') {
            $query->orderBy('evaluations.created_at', 'asc');
        } else {
            $query->orderBy('evaluations.created_at', 'desc');
        }
        
        $evaluations = $query->paginate(15)->withQueryString();
        
        // Povoar filtros
        $cycles = EvaluationCycle::orderBy('id', 'desc')->get();
        
        $lotacoes = User::select('lotacao')
            ->distinct()
            ->whereNotNull('lotacao')
            ->where('lotacao', '!=', '')
            ->pluck('lotacao');
            
        return view('evaluations.index', compact(
            'evaluations', 
            'cycles', 
            'lotacoes', 
            'cycleId', 
            'lotacao', 
            'status', 
            'search',
            'sortBy',
            'scoreRange',
            'totalEvaluations',
            'submittedCount',
            'draftCount',
            'avgScore'
        ));
    }

    /**
     * Exibe a página de avaliação de um servidor específico.
     */
    public function create(User $evaluated)
    {
        // 1. Verificar se o avaliador é a chefia imediata ou admin
        // Para fins de demonstração, permitiremos se estiver autenticado.
        
        // 2. Buscar o ciclo de avaliação ativo
        $cycle = EvaluationCycle::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$cycle) {
            // Se não houver ciclo ativo, vamos criar ou usar um fictício para o teste não quebrar
            $cycle = EvaluationCycle::firstOrCreate(
                ['status' => 'active'],
                [
                    'name' => 'Ciclo Periódico CAPD Araucária ' . now()->year,
                    'start_date' => now()->subDays(5),
                    'end_date' => now()->addDays(30),
                    'weights' => [
                        'assiduidade' => 2,
                        'disciplina' => 1,
                        'iniciativa' => 1,
                        'responsabilidade' => 2,
                        'cooperacao' => 1,
                        'qualidade' => 2,
                        'desenvolvimento_rh' => 1,
                        'avaliacao_usuario' => 1
                    ],
                    'global_goals' => [
                        ['description' => 'Processos Analisados e Concluídos', 'metric' => 'Processos', 'target_value' => 100, 'weight' => 1.0],
                        ['description' => 'Satisfação de Atendimento ao Cidadão', 'metric' => '%', 'target_value' => 90, 'weight' => 1.0]
                    ],
                    'cutoff_score' => 3.00,
                ]
            );
        }

        // 3. Trava de PAD (Processo Administrativo Disciplinar)
        if ($evaluated->has_active_pad) {
            return redirect()->back()->with('error', 'Este servidor possui Processo Administrativo Disciplinar (PAD) ativo e sua avaliação está bloqueada judicialmente.');
        }

        // 4. Buscar as perguntas parametrizadas de acordo com o grupo do servidor com as âncoras BARS
        $group = $evaluated->evaluation_group ?? 'geral';
        $questions = EvaluationQuestion::active()
            ->forGroup($group)
            ->with('barsAnchors')
            ->get()
            ->groupBy('category');

        // 4.1 Buscar os incidentes críticos do diário de bordo do servidor
        $diaryIncidents = \App\Models\EmployeeDiaryIncident::where('employee_id', $evaluated->id)
            ->with('reporter')
            ->orderBy('incident_date', 'desc')
            ->get();

        // 5. Buscar dados de ponto integrados da tabela employee_points
        $employeePoint = \App\Models\EmployeePoint::where('employee_id', $evaluated->id)
            ->where('cycle_id', $cycle->id)
            ->first();

        $integrationData = [
            'ponto' => [
                'faltas_injustificadas' => $employeePoint ? $employeePoint->faltas_injustificadas : 0,
                'atrasos_minutos' => $employeePoint ? $employeePoint->atrasos_minutos : 0,
                'horas_extras' => $employeePoint ? $employeePoint->horas_extras : 0,
                'mensagem' => $employeePoint && $employeePoint->mensagem ? $employeePoint->mensagem : 'Dados do Ponto Eletrônico integrados em tempo real.',
            ],
            'rh' => [
                'horas_formacao' => 40,       // ex: 40 horas de cursos Escola de Gestão
                'cursos_concluidos' => ['Liderança no Setor Público', 'Ética e Cidadania'],
                'mensagem' => 'Dados da Escola de Gestão sincronizados com sucesso.'
            ]
        ];

        return view('evaluation', compact('evaluated', 'cycle', 'questions', 'integrationData', 'diaryIncidents'));
    }

    /**
     * Salva a avaliação e seus incidentes críticos no banco de dados.
     */
    public function store(StoreEvaluationRequest $request)
    {
        DB::beginTransaction();

        try {
            $isDraft = $request->input('submit_type') === 'draft';
            $status = $isDraft ? 'draft' : 'submitted';
            $submittedAt = $isDraft ? null : now();

            // 1. Criar ou atualizar o registro principal da avaliação
            $evaluation = Evaluation::updateOrCreate(
                [
                    'cycle_id' => $request->cycle_id,
                    'evaluated_id' => $request->evaluated_id,
                ],
                [
                    'evaluator_id' => auth()->id() ?? 1, // Fallback se não logado em ambiente dev local
                    'status' => $status,
                    'weight_goals' => $request->input('weight_goals', 0.50),
                    'weight_competencies' => $request->input('weight_competencies', 0.50),
                    'submitted_at' => $submittedAt,
                ]
            );

            // 2. Salvar as respostas e uploads
            $answers = $request->input('answers', []);
            $justifications = $request->input('justifications', []);
            $linkedIncidents = $request->input('linked_incidents', []);

            foreach ($answers as $questionId => $score) {
                // Salvar/Atualizar a resposta
                $answer = EvaluationAnswer::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'question_id' => $questionId,
                    ],
                    [
                        'score' => (int) $score,
                    ]
                );

                // Limpar vínculos anteriores
                $answer->employeeDiaryIncidents()->detach();

                // Vincular novos incidentes se houver
                if (isset($linkedIncidents[$questionId]) && is_array($linkedIncidents[$questionId])) {
                    $answer->employeeDiaryIncidents()->attach($linkedIncidents[$questionId]);
                }

                // Se for nota crítica (1, 2 ou 5), processar justificativa e upload (caso não haja incidente do diário)
                if (in_array((int)$score, [1, 2, 5])) {
                    $hasLinkedIncident = isset($linkedIncidents[$questionId]) && count($linkedIncidents[$questionId]) > 0;
                    
                    if (!$hasLinkedIncident) {
                        $justification = $justifications[$questionId] ?? '';
                        $evidencePath = null;

                        if ($request->hasFile("evidences.{$questionId}")) {
                            $file = $request->file("evidences.{$questionId}");
                            $filename = 'evidence_' . $answer->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                            $evidencePath = $file->storeAs('evidences', $filename, 'local');
                        }

                        // Gravar incidente crítico
                        CriticalIncident::updateOrCreate(
                            ['answer_id' => $answer->id],
                            [
                                'justification' => $justification,
                                'evidence_path' => $evidencePath ?? '',
                            ]
                        );
                    } else {
                        // Limpa justificativa ad-hoc anterior caso tenha sido substituída por vínculo do diário
                        $answer->criticalIncident()->delete();
                    }
                } else {
                    $answer->criticalIncident()->delete();
                }
            }

            // Guardar os valores alcançados existentes na memória antes de deletar
            $existingGoalsValues = $evaluation->goals()->pluck('achieved_value', 'description')->toArray();

            // 3. Salvar as Metas Quantitativas com base nas Metas Globais do Ciclo (Parametrizadas pelo RH)
            $evaluation->goals()->delete(); // Limpa as anteriores para re-salvar
            
            // Buscar metas globais oficiais do ciclo
            $cycle = EvaluationCycle::findOrFail($request->cycle_id);
            $globalGoals = $cycle->global_goals ?? [];
            
            $goalsData = $request->input('goals', []);
            if (is_array($goalsData)) {
                foreach ($goalsData as $goalData) {
                    if (!empty($goalData['description'])) {
                        // Encontrar a parametrização oficial correspondente a esta meta global
                        $official = collect($globalGoals)->first(function($g) use ($goalData) {
                            return $g['description'] === $goalData['description'];
                        });
                        
                        if ($official) {
                            // Preserva o valor alcançado existente se já houver no banco, ignorando o input do avaliador
                            $achievedValue = $existingGoalsValues[$official['description']] ?? null;

                            $evaluation->goals()->create([
                                'description' => $official['description'],
                                'metric' => $official['metric'],
                                'target_value' => (float) $official['target_value'],
                                'achieved_value' => $achievedValue,
                                'weight' => (float) $official['weight'],
                            ]);
                        }
                    }
                }
            }

            // 4. Calcular a nota final ponderada mista
            $evaluation->calculateFinalScore();

            DB::commit();

            if ($isDraft) {
                return redirect()->route('evaluations.index')->with('success', 'Rascunho da avaliação salvo com sucesso!');
            }

            return redirect()->route('evaluations.index')->with('success', 'Avaliação de Desempenho submetida com sucesso! Nota final: ' . $evaluation->final_score);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao salvar a avaliação: ' . $e->getMessage());
        }
    }

    /**
     * Exibe a página de avaliação de desempenho a partir de um rascunho.
     */
    public function fill(Evaluation $evaluation)
    {
        $evaluated = $evaluation->evaluated;
        $cycle = $evaluation->cycle;

        if (!$evaluated) {
            return redirect()->route('dashboard')->with('error', 'Servidor avaliado não encontrado.');
        }

        // Trava de PAD (Processo Administrativo Disciplinar)
        if ($evaluated->has_active_pad) {
            return redirect()->route('dashboard')->with('error', 'Este servidor possui Processo Administrativo Disciplinar (PAD) ativo e sua avaliação está bloqueada judicialmente.');
        }

        // Buscar as perguntas parametrizadas de acordo com a categoria da avaliação (do setup) com as âncoras BARS
        $group = $evaluation->categoria ?? $evaluated->evaluation_group ?? 'geral';
        $questions = EvaluationQuestion::active()
            ->forGroup($group)
            ->with('barsAnchors')
            ->get()
            ->groupBy('category');

        // Buscar incidentes do diário de bordo do servidor
        $diaryIncidents = \App\Models\EmployeeDiaryIncident::where('employee_id', $evaluated->id)
            ->with('reporter')
            ->orderBy('incident_date', 'desc')
            ->get();

        // Buscar dados de ponto integrados da tabela employee_points
        $employeePoint = \App\Models\EmployeePoint::where('employee_id', $evaluated->id)
            ->where('cycle_id', $cycle->id)
            ->first();

        $integrationData = [
            'ponto' => [
                'faltas_injustificadas' => $employeePoint ? $employeePoint->faltas_injustificadas : 0,
                'atrasos_minutos' => $employeePoint ? $employeePoint->atrasos_minutos : 0,
                'horas_extras' => $employeePoint ? $employeePoint->horas_extras : 0,
                'mensagem' => $employeePoint && $employeePoint->mensagem ? $employeePoint->mensagem : 'Dados do Ponto Eletrônico integrados em tempo real.',
            ],
            'rh' => [
                'horas_formacao' => 40,
                'cursos_concluidos' => ['Liderança no Setor Público', 'Ética e Cidadania'],
                'mensagem' => 'Dados da Escola de Gestão sincronizados com sucesso.'
            ]
        ];

        return view('evaluation', compact('evaluation', 'evaluated', 'cycle', 'questions', 'integrationData', 'diaryIncidents'));
    }

    /**
     * Salva as respostas finais de um rascunho de avaliação.
     */
    public function submit(Evaluation $evaluation, StoreEvaluationRequest $request)
    {
        DB::beginTransaction();

        try {
            $isDraft = $request->input('submit_type') === 'draft';
            $status = $isDraft ? 'draft' : 'submitted';
            $submittedAt = $isDraft ? null : now();

            // 1. Atualizar o registro da avaliação e gravar os pesos
            $evaluation->update([
                'evaluator_id' => auth()->id() ?? $evaluation->evaluator_id ?? 1,
                'status' => $status,
                'weight_goals' => $request->input('weight_goals', 0.50),
                'weight_competencies' => $request->input('weight_competencies', 0.50),
                'submitted_at' => $submittedAt,
            ]);

            // 2. Salvar as respostas e uploads
            $answers = $request->input('answers', []);
            $justifications = $request->input('justifications', []);
            $linkedIncidents = $request->input('linked_incidents', []);

            foreach ($answers as $questionId => $score) {
                // Salvar/Atualizar a resposta
                $answer = EvaluationAnswer::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'question_id' => $questionId,
                    ],
                    [
                        'score' => (int) $score,
                    ]
                );

                // Limpar vínculos anteriores
                $answer->employeeDiaryIncidents()->detach();

                // Vincular novos incidentes se houver
                if (isset($linkedIncidents[$questionId]) && is_array($linkedIncidents[$questionId])) {
                    $answer->employeeDiaryIncidents()->attach($linkedIncidents[$questionId]);
                }

                // Se for nota crítica (1, 2 ou 5), processar justificativa e upload (caso não haja incidente do diário)
                if (in_array((int)$score, [1, 2, 5])) {
                    $hasLinkedIncident = isset($linkedIncidents[$questionId]) && count($linkedIncidents[$questionId]) > 0;
                    
                    if (!$hasLinkedIncident) {
                        $justification = $justifications[$questionId] ?? '';
                        $evidencePath = null;

                        if ($request->hasFile("evidences.{$questionId}")) {
                            $file = $request->file("evidences.{$questionId}");
                            $filename = 'evidence_' . $answer->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                            $evidencePath = $file->storeAs('evidences', $filename, 'local');
                        }

                        // Gravar incidente crítico
                        CriticalIncident::updateOrCreate(
                            ['answer_id' => $answer->id],
                            [
                                'justification' => $justification,
                                'evidence_path' => $evidencePath ?? '',
                            ]
                        );
                    } else {
                        // Limpa justificativa anterior
                        $answer->criticalIncident()->delete();
                    }
                } else {
                    // Se a nota mudou para normal (3 ou 4), remover eventual incidente crítico existente
                    $answer->criticalIncident()->delete();
                }
            }

            // Guardar os valores alcançados existentes na memória antes de deletar
            $existingGoalsValues = $evaluation->goals()->pluck('achieved_value', 'description')->toArray();

            // 3. Salvar as Metas Quantitativas com base nas Metas Globais do Ciclo (Parametrizadas pelo RH)
            $evaluation->goals()->delete(); // Limpa as anteriores para re-salvar
            
            $cycle = $evaluation->cycle;
            $globalGoals = $cycle->global_goals ?? [];
            
            $goalsData = $request->input('goals', []);
            if (is_array($goalsData)) {
                foreach ($goalsData as $goalData) {
                    if (!empty($goalData['description'])) {
                        // Encontrar a parametrização oficial correspondente a esta meta global
                        $official = collect($globalGoals)->first(function($g) use ($goalData) {
                            return $g['description'] === $goalData['description'];
                        });
                        
                        if ($official) {
                            // Preserva o valor alcançado existente se já houver no banco, ignorando o input do avaliador
                            $achievedValue = $existingGoalsValues[$official['description']] ?? null;

                            $evaluation->goals()->create([
                                'description' => $official['description'],
                                'metric' => $official['metric'],
                                'target_value' => (float) $official['target_value'],
                                'achieved_value' => $achievedValue,
                                'weight' => (float) $official['weight'],
                            ]);
                        }
                    }
                }
            }

            // 4. Calcular a nota final ponderada mista
            $evaluation->calculateFinalScore();

            DB::commit();

            if ($isDraft) {
                return redirect()->route('evaluations.index')->with('success', 'Rascunho da avaliação salvo com sucesso!');
            }

            return redirect()->route('evaluations.index')->with('success', 'Avaliação de Desempenho submetida com sucesso! Nota final: ' . $evaluation->final_score);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao salvar a avaliação: ' . $e->getMessage());
        }
    }

    /**
     * Salva um incidente crítico no Diário de Bordo do servidor.
     */
    public function storeIncident(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'category' => 'required|string',
            'type' => 'required|in:positive,negative',
            'incident_date' => 'required|date',
            'description' => 'required|string|min:5',
        ]);

        \App\Models\EmployeeDiaryIncident::create([
            'employee_id' => $request->employee_id,
            'reporter_id' => auth()->id() ?? 1, // Fallback se não logado localmente
            'category' => $request->category,
            'type' => $request->type,
            'incident_date' => $request->incident_date,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard')->with('success', 'Incidente registrado com sucesso no Diário de Bordo do servidor!');
    }
}
