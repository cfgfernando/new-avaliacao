<?php

namespace Tests\Unit;

use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationCycle;
use App\Models\EvaluationQuestion;
use App\Models\QuantitativeGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluationScoreTest extends TestCase
{
    use RefreshDatabase;

    protected EvaluationCycle $cycle;
    protected User $evaluator;
    protected User $evaluated;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar ciclo de avaliação com pesos para as categorias de competências e metas globais
        $this->cycle = EvaluationCycle::create([
            'name' => 'Ciclo Teste Misto 2026',
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'weights' => [
                'assiduidade' => 2,
                'disciplina' => 1,
                'iniciativa' => 1,
            ],
            'global_goals' => [
                ['description' => 'Meta 1', 'metric' => 'Unidades', 'target_value' => 100, 'weight' => 1.00],
                ['description' => 'Meta 2', 'metric' => 'Unidades', 'target_value' => 50, 'weight' => 1.00],
                ['description' => 'Meta Excedida', 'metric' => 'Unidades', 'target_value' => 100, 'weight' => 1.00],
                ['description' => 'Meta Negativa', 'metric' => 'Unidades', 'target_value' => 100, 'weight' => 1.00]
            ],
            'cutoff_score' => 3.00,
            'status' => 'active'
        ]);

        $this->evaluator = User::create([
            'name' => 'Avaliador Teste',
            'email' => 'avaliador@teste.com',
            'password' => bcrypt('senha123'),
            'role' => 'Supervisor',
        ]);

        $this->evaluated = User::create([
            'name' => 'Avaliado Teste',
            'email' => 'avaliado@teste.com',
            'password' => bcrypt('senha123'),
            'role' => 'Leader',
            'registration_number' => '12345',
            'evaluation_group' => 'geral'
        ]);
    }

    public function test_calculate_final_score_with_competencies_only(): void
    {
        $evaluation = Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->evaluator->id,
            'evaluated_id' => $this->evaluated->id,
            'status' => 'draft',
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ]);

        // Criar perguntas usando campos corretos
        $q1 = EvaluationQuestion::create(['category' => 'assiduidade', 'text' => 'Pergunta 1', 'is_active' => true, 'group_type' => 'geral']);
        $q2 = EvaluationQuestion::create(['category' => 'disciplina', 'text' => 'Pergunta 2', 'is_active' => true, 'group_type' => 'geral']);

        // Respostas:
        // q1 (assiduidade, peso 2) -> nota 4
        // q2 (disciplina, peso 1) -> nota 5
        // Média ponderada esperada BARS: ((4 * 2) + (5 * 1)) / (2 + 1) = (8 + 5) / 3 = 13 / 3 = 4.3333...
        EvaluationAnswer::create(['evaluation_id' => $evaluation->id, 'question_id' => $q1->id, 'score' => 4]);
        EvaluationAnswer::create(['evaluation_id' => $evaluation->id, 'question_id' => $q2->id, 'score' => 5]);

        $finalScore = $evaluation->calculateFinalScore();

        $this->assertEquals(4.33, round($finalScore, 2));
        $this->assertEquals(4.33, round($evaluation->score_competencies, 2));
        $this->assertNull($evaluation->score_goals);
        $this->assertEquals(4.33, round($evaluation->final_score, 2));
    }

    public function test_calculate_final_score_with_goals_only(): void
    {
        $evaluation = Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->evaluator->id,
            'evaluated_id' => $this->evaluated->id,
            'status' => 'draft',
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ]);

        // Meta 1: target = 100, achieved = 80. Atingimento = 80% (0.80) -> Nota = 1.0 + (0.8 * 4.0) = 4.2. Peso = 2.0
        // Meta 2: target = 50, achieved = 50. Atingimento = 100% (1.00) -> Nota = 1.0 + (1.0 * 4.0) = 5.0. Peso = 1.0
        // Média ponderada esperada Metas: ((4.2 * 2.0) + (5.0 * 1.0)) / (2.0 + 1.0) = (8.4 + 5.0) / 3.0 = 13.4 / 3.0 = 4.4666...
        QuantitativeGoal::create([
            'evaluation_id' => $evaluation->id,
            'description' => 'Meta 1',
            'metric' => 'Unidades',
            'target_value' => 100,
            'achieved_value' => 80,
            'weight' => 2.0,
        ]);

        QuantitativeGoal::create([
            'evaluation_id' => $evaluation->id,
            'description' => 'Meta 2',
            'metric' => 'Unidades',
            'target_value' => 50,
            'achieved_value' => 50,
            'weight' => 1.0,
        ]);

        $finalScore = $evaluation->calculateFinalScore();

        $this->assertNull($evaluation->score_competencies);
        $this->assertEquals(4.47, round($evaluation->score_goals, 2));
        $this->assertEquals(4.47, round($finalScore, 2));
    }

    public function test_calculate_final_score_mixed_with_equal_weights(): void
    {
        $evaluation = Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->evaluator->id,
            'evaluated_id' => $this->evaluated->id,
            'status' => 'draft',
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ]);

        // Competência: q1 (assiduidade, peso 2) -> nota 4. Média = 4.0
        $q1 = EvaluationQuestion::create(['category' => 'assiduidade', 'text' => 'Pergunta 1', 'is_active' => true, 'group_type' => 'geral']);
        EvaluationAnswer::create(['evaluation_id' => $evaluation->id, 'question_id' => $q1->id, 'score' => 4]);

        // Meta: target = 100, achieved = 50. Atingimento = 50% (0.50) -> Nota = 1.0 + (0.5 * 4.0) = 3.0. Peso = 1.0
        QuantitativeGoal::create([
            'evaluation_id' => $evaluation->id,
            'description' => 'Meta 1',
            'metric' => 'Unidades',
            'target_value' => 100,
            'achieved_value' => 50,
            'weight' => 1.0,
        ]);

        // Mista ponderada esperada:
        // score_competencies = 4.0
        // score_goals = 3.0
        // pesos: 50% cada.
        // final_score = (4.0 * 0.50 + 3.0 * 0.50) / (0.50 + 0.50) = 3.50
        $finalScore = $evaluation->calculateFinalScore();

        $this->assertEquals(4.00, $evaluation->score_competencies);
        $this->assertEquals(3.00, $evaluation->score_goals);
        $this->assertEquals(3.50, $finalScore);
    }

    public function test_calculate_final_score_mixed_with_custom_weights(): void
    {
        $evaluation = Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->evaluator->id,
            'evaluated_id' => $this->evaluated->id,
            'status' => 'draft',
            'weight_goals' => 0.30, // Metas valem 30%
            'weight_competencies' => 0.70, // BARS vale 70%
        ]);

        // Competência Média = 4.0
        $q1 = EvaluationQuestion::create(['category' => 'assiduidade', 'text' => 'Pergunta 1', 'is_active' => true, 'group_type' => 'geral']);
        EvaluationAnswer::create(['evaluation_id' => $evaluation->id, 'question_id' => $q1->id, 'score' => 4]);

        // Meta Nota = 3.0
        QuantitativeGoal::create([
            'evaluation_id' => $evaluation->id,
            'description' => 'Meta 1',
            'metric' => 'Unidades',
            'target_value' => 100,
            'achieved_value' => 50,
            'weight' => 1.0,
        ]);

        // Final score: (4.0 * 0.70 + 3.0 * 0.30) / (0.70 + 0.30) = (2.8 + 0.9) / 1.0 = 3.70
        $finalScore = $evaluation->calculateFinalScore();

        $this->assertEquals(3.70, round($finalScore, 2));
    }

    public function test_attainment_upper_limit_is_capped_at_one(): void
    {
        $evaluation = Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->evaluator->id,
            'evaluated_id' => $this->evaluated->id,
            'status' => 'draft',
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ]);

        // Servidor superou a meta: target = 100, achieved = 150.
        // Atingimento deve ser limitado a 1.00 (100%). Nota máxima = 5.0
        QuantitativeGoal::create([
            'evaluation_id' => $evaluation->id,
            'description' => 'Meta Excedida',
            'metric' => 'Unidades',
            'target_value' => 100,
            'achieved_value' => 150,
            'weight' => 1.0,
        ]);

        $finalScore = $evaluation->calculateFinalScore();

        $this->assertEquals(5.00, $evaluation->score_goals);
        $this->assertEquals(5.00, $finalScore);
    }

    public function test_attainment_lower_limit_is_capped_at_zero(): void
    {
        $evaluation = Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->evaluator->id,
            'evaluated_id' => $this->evaluated->id,
            'status' => 'draft',
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ]);

        // Atingimento negativo (teoricamente incomum, mas testando robustez): target = 100, achieved = -20
        // Deve limitar atingimento a 0.00. Nota mínima = 1.0
        QuantitativeGoal::create([
            'evaluation_id' => $evaluation->id,
            'description' => 'Meta Negativa',
            'metric' => 'Unidades',
            'target_value' => 100,
            'achieved_value' => -20,
            'weight' => 1.0,
        ]);

        $finalScore = $evaluation->calculateFinalScore();

        $this->assertEquals(1.00, $evaluation->score_goals);
        $this->assertEquals(1.00, $finalScore);
    }

    public function test_evaluation_store_overwrites_manipulated_goal_parameters_with_cycle_defaults(): void
    {
        // Criar uma pergunta para a avaliação passar pela validação de respostas
        $question = EvaluationQuestion::create([
            'category' => 'assiduidade',
            'text' => 'Frequência do servidor',
            'is_active' => true,
            'group_type' => 'geral'
        ]);

        // Dados da requisição com metas adulteradas (target_value alterado de 100 para 50, e weight de 1.0 para 10.0)
        $data = [
            'evaluated_id' => $this->evaluated->id,
            'cycle_id' => $this->cycle->id,
            'answers' => [
                $question->id => 3 // Nota 3 (não crítica, não exige justificativa/anexo)
            ],
            'goals' => [
                [
                    'description' => 'Meta 1',
                    'metric' => 'Unidades',
                    'target_value' => 50, // Adulterado (oficial é 100)
                    'achieved_value' => 80,
                    'weight' => 10.0, // Adulterado (oficial é 1.0)
                ]
            ],
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ];

        // Fazer a requisição POST simulando o gestor enviando o formulário
        $response = $this->actingAs($this->evaluator)->post(route('evaluation.store'), $data);

        // Deve redirecionar para as avaliações com sucesso
        $response->assertRedirect(route('evaluations.index'));
        $response->assertSessionHasNoErrors();

        // Verificar se a avaliação foi criada
        $evaluation = Evaluation::where('evaluated_id', $this->evaluated->id)
            ->where('cycle_id', $this->cycle->id)
            ->first();
        
        $this->assertNotNull($evaluation);

        // Verificar se a meta gravada no banco ignorou a adulteração e usou os valores oficiais do ciclo
        $goal = $evaluation->goals()->first();
        $this->assertNotNull($goal);
        $this->assertEquals('Meta 1', $goal->description);
        $this->assertEquals('Unidades', $goal->metric);
        $this->assertEquals(100.00, $goal->target_value); // Voltou ao oficial
        $this->assertEquals(1.00, $goal->weight); // Voltou ao oficial
        $this->assertNull($goal->achieved_value); // O valor alcançado inicial é salvo como nulo para preenchimento posterior pelo RH
    }

    public function test_evaluation_store_ignores_non_official_goals(): void
    {
        // Criar uma pergunta para a avaliação passar pela validação de respostas
        $question = EvaluationQuestion::create([
            'category' => 'assiduidade',
            'text' => 'Frequência do servidor',
            'is_active' => true,
            'group_type' => 'geral'
        ]);

        // Dados da requisição contendo uma meta oficial e uma não oficial/adulterada na descrição
        $data = [
            'evaluated_id' => $this->evaluated->id,
            'cycle_id' => $this->cycle->id,
            'answers' => [
                $question->id => 3
            ],
            'goals' => [
                [
                    'description' => 'Meta 1',
                    'metric' => 'Unidades',
                    'target_value' => 100,
                    'achieved_value' => 80,
                    'weight' => 1.0,
                ],
                [
                    'description' => 'Meta Não Cadastrada pelo RH',
                    'metric' => 'Unidades',
                    'target_value' => 50,
                    'achieved_value' => 40,
                    'weight' => 1.0,
                ]
            ],
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ];

        // Fazer requisição
        $response = $this->actingAs($this->evaluator)->post(route('evaluation.store'), $data);

        $response->assertRedirect(route('evaluations.index'));
        $response->assertSessionHasNoErrors();

        // Verificar avaliação e metas
        $evaluation = Evaluation::where('evaluated_id', $this->evaluated->id)->first();
        
        // Apenas a Meta 1 deve ter sido gravada, a meta falsa/não-oficial deve ter sido ignorada
        $this->assertEquals(1, $evaluation->goals()->count());
        $this->assertEquals('Meta 1', $evaluation->goals()->first()->description);
    }

    public function test_rh_can_update_achieved_values_and_recalculate_score(): void
    {
        $admin = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@teste.com',
            'password' => bcrypt('senha123'),
            'role' => 'Admin',
        ]);

        // Criar uma avaliação submetida
        $evaluation = Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->evaluator->id,
            'evaluated_id' => $this->evaluated->id,
            'status' => 'submitted',
            'weight_goals' => 0.50,
            'weight_competencies' => 0.50,
        ]);

        // Criar resposta (média BARS = 4.0)
        $q1 = EvaluationQuestion::create(['category' => 'assiduidade', 'text' => 'Pergunta 1', 'is_active' => true, 'group_type' => 'geral']);
        EvaluationAnswer::create(['evaluation_id' => $evaluation->id, 'question_id' => $q1->id, 'score' => 4]);

        // Criar meta com achieved_value null
        $goal = QuantitativeGoal::create([
            'evaluation_id' => $evaluation->id,
            'description' => 'Meta 1',
            'metric' => 'Unidades',
            'target_value' => 100,
            'achieved_value' => null,
            'weight' => 1.0,
        ]);

        // Calcular a nota inicial. Como o achieved_value é null, score_goals deve ser null e final_score deve ser 4.0
        $evaluation->calculateFinalScore();
        $this->assertNull($evaluation->score_goals);
        $this->assertEquals(4.00, $evaluation->final_score);

        // RH faz a atualização do valor alcançado
        $data = [
            'goals' => [
                [
                    'id' => $goal->id,
                    'achieved_value' => 50 // 50% de atingimento -> Nota = 1.0 + (0.5 * 4.0) = 3.0
                ]
            ]
        ];

        $response = $this->actingAs($admin)->post(route('admin.evaluation-results.update-goals', $evaluation->id), $data);

        // Deve redirecionar para a view de detalhes do admin com sucesso
        $response->assertRedirect(route('admin.evaluation-results.show', $evaluation->id));
        $response->assertSessionHasNoErrors();

        // Recarregar avaliação do banco
        $evaluation->refresh();

        // Verificar valores recalculados
        $this->assertEquals(50.00, $evaluation->goals()->first()->achieved_value);
        $this->assertEquals(3.00, $evaluation->score_goals);
        // final_score = (4.0 * 0.50) + (3.0 * 0.50) = 3.50
        $this->assertEquals(3.50, $evaluation->final_score);
    }
}
