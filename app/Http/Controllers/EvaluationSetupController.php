<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationSetupRequest;
use App\Models\Evaluation;
use App\Models\EvaluationCycle;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationSetupController extends Controller
{
    /**
     * Exibe a tela de criação/setup de nova avaliação.
     */
    public function create(): View
    {
        $user = auth()->user();
        
        // Buscar ciclos de avaliação ativos
        $cycles = EvaluationCycle::where('status', 'active')->get();
        
        // Se não houver ciclo ativo, buscar o último cadastrado para evitar tela vazia em ambiente de testes/local
        if ($cycles->isEmpty()) {
            $cycles = EvaluationCycle::orderBy('id', 'desc')->take(1)->get();
        }

        // Definir a lógica de Lotações de acordo com o perfil ACL
        if ($user && $user->isAdmin()) {
            // Admin/CAPD pode selecionar qualquer lotação cadastrada
            $lotacoes = User::select('lotacao')
                ->distinct()
                ->whereNotNull('lotacao')
                ->where('lotacao', '!=', '')
                ->pluck('lotacao');
        } else {
            // Chefia Imediata (Leader, Supervisor, etc.) fica travado na sua própria lotação
            $lotacao = $user->lotacao ?? '';
            $lotacoes = collect($lotacao ? [$lotacao] : []);
        }

        return view('create-setup', compact('cycles', 'lotacoes'));
    }

    /**
     * Processa a inicialização da avaliação e cria o rascunho (draft).
     */
    public function store(StoreEvaluationSetupRequest $request): RedirectResponse
    {
        $evaluation = Evaluation::create([
            'cycle_id' => $request->cycle_id,
            'evaluator_id' => auth()->id() ?? 1,
            'evaluated_id' => $request->evaluated_id,
            'categoria' => $request->categoria,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('evaluations.fill', $evaluation->id)
            ->with('success', 'Setup de avaliação inicializado com sucesso! Continue preenchendo o formulário BARS.');
    }

    /**
     * Verificação rápida via AJAX para checar duplicidade de avaliação
     * e bloqueio por PAD no ciclo selecionado.
     */
    public function checkDuplicate(Request $request): JsonResponse
    {
        $cycleId = $request->query('cycle_id');
        $evaluatedId = $request->query('evaluated_id');

        $exists = false;
        $padBlocked = false;

        if ($cycleId && $evaluatedId) {
            $exists = Evaluation::where('cycle_id', $cycleId)
                ->where('evaluated_id', $evaluatedId)
                ->exists();

            $evaluated = User::find($evaluatedId);
            $cycle = EvaluationCycle::find($cycleId);
            if ($evaluated && $cycle) {
                $padBlocked = (bool) $cycle->block_on_pad && (bool) ($evaluated->has_active_pad ?? false);
            }
        }

        $fillUrl = null;
        $evaluation = null;
        if ($exists) {
            $evaluation = Evaluation::where('cycle_id', $cycleId)->where('evaluated_id', $evaluatedId)->first();
            if ($evaluation) {
                $fillUrl = route('evaluations.fill', $evaluation->id);
            }
        }

        return response()->json([
            'exists' => $exists,
            'pad_blocked' => $padBlocked,
            'existing_evaluation_id' => $evaluation->id ?? null,
            'fill_url' => $fillUrl,
        ]);
    }

    /**
     * Força a criação de uma nova avaliação substituindo a existente.
     * Usado via AJAX quando o usuário confirma substituir o rascunho.
     */
    public function forceCreate(Request $request): JsonResponse
    {
        $data = $request->only(['cycle_id', 'evaluated_id', 'categoria', 'lotacao']);

        $request->validate([
            'cycle_id' => 'required|exists:evaluation_cycles,id',
            'evaluated_id' => 'required|exists:users,id',
            'categoria' => 'required|in:saude,guarda,educacao,geral',
        ]);

        // Arquivar avaliação existente (se houver) em vez de deletar
        $existing = Evaluation::where('cycle_id', $data['cycle_id'])->where('evaluated_id', $data['evaluated_id'])->first();
        if ($existing) {
            $existing->update([
                'status' => 'archived',
                'archived_by' => auth()->id() ?? null,
                'archived_at' => now(),
            ]);
        }

        // Criar nova avaliação (pendente)
        $evaluation = Evaluation::create([
            'cycle_id' => $data['cycle_id'],
            'evaluator_id' => auth()->id() ?? 1,
            'evaluated_id' => $data['evaluated_id'],
            'categoria' => $data['categoria'] ?? 'geral',
            'status' => 'draft',
        ]);

        $fillUrl = $evaluation ? route('evaluations.fill', $evaluation->id) : null;

        return response()->json([
            'success' => (bool) $evaluation,
            'evaluation_id' => $evaluation->id ?? null,
            'fill_url' => $fillUrl,
        ]);
    }

    /**
     * Retorna os servidores ativos filtrados por lotação para o dropdown dinâmico.
     */
    public function getServidoresByLotacao(Request $request): JsonResponse
    {
        $lotacao = $request->query('lotacao', '');

        // Busca servidores que pertencem à lotação escolhida, exceto o próprio avaliador
        $servidores = User::where('lotacao', $lotacao)
            ->where('id', '!=', auth()->id())
            ->get(['id', 'name', 'cargo', 'registration_number', 'evaluation_group', 'has_active_pad']);

        return response()->json($servidores);
    }

    /**
     * Retorna os detalhes do servidor (metas e incidentes) para o painel de apoio.
     */
    public function getServidorDetalhes(User $user): JsonResponse
    {
        $activeCycle = EvaluationCycle::where('status', 'active')->first() 
            ?? EvaluationCycle::orderBy('id', 'desc')->first();

        // Buscar avaliação do servidor no ciclo ativo
        $evaluation = Evaluation::where('cycle_id', $activeCycle->id)
            ->where('evaluated_id', $user->id)
            ->first();

        $goals = [];
        if ($evaluation) {
            $goals = \App\Models\QuantitativeGoal::where('evaluation_id', $evaluation->id)->get();
        } else {
            // Se não houver avaliação ainda, podemos usar as metas globais do ciclo ativo
            $globalGoals = $activeCycle->global_goals ?? [];
            foreach ($globalGoals as $g) {
                $goals[] = [
                    'description' => $g['description'] ?? '',
                    'metric' => $g['metric'] ?? '',
                    'target_value' => $g['target_value'] ?? 100,
                    'achieved_value' => 0,
                    'weight' => $g['weight'] ?? 1.0,
                ];
            }
        }

        // Buscar incidentes
        $incidentsPositive = \App\Models\EmployeeDiaryIncident::where('employee_id', $user->id)
            ->where('type', 'positive')
            ->count();

        $incidentsNegative = \App\Models\EmployeeDiaryIncident::where('employee_id', $user->id)
            ->where('type', 'negative')
            ->count();

        return response()->json([
            'user' => [
                'id'               => $user->id,
                'name'             => $user->name,
                'cargo'            => $user->cargo ?? 'Não informado',
                'lotacao'          => $user->lotacao ?? 'Não informado',
                'evaluation_group' => $user->evaluation_group ?? 'geral',
                'avatar'           => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff',
            ],
            'goals' => $goals,
            'incidents' => [
                'positive' => $incidentsPositive,
                'negative' => $incidentsNegative,
            ]
        ]);
    }
}
