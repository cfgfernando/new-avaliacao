<?php

namespace Tests\Feature;

use App\Models\EvaluationCycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EvaluationCycleAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $chefia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Araucária',
            'email' => 'admin@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Admin',
            'lotacao' => 'CAPD Central',
            'evaluation_group' => 'geral'
        ]);

        $this->chefia = User::create([
            'name' => 'Supervisor José',
            'email' => 'jose@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Supervisor',
            'lotacao' => 'Secretaria de Saúde',
            'evaluation_group' => 'saude'
        ]);
    }

    public function test_non_admin_cannot_access_cycle_management(): void
    {
        $response = $this->actingAs($this->chefia)->get(route('admin.evaluation-cycles.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->chefia)->get(route('admin.evaluation-cycles.create'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_cycle_management_index_and_create_views(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.evaluation-cycles.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('admin.evaluation-cycles.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_cycle_with_global_goals(): void
    {
        $cycleData = [
            'name' => 'Novo Ciclo RH 2026',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(60)->format('Y-m-d'),
            'cutoff_score' => 3.50,
            'status' => 'active',
            'weights' => [
                'assiduidade' => 2,
                'disciplina' => 1
            ],
            'global_goals' => [
                [
                    'description' => 'Processos Respondidos',
                    'metric' => 'processos',
                    'target_value' => '150',
                    'weight' => '2.5'
                ],
                [
                    'description' => 'Satisfação dos Clientes',
                    'metric' => 'percentual',
                    'target_value' => '95',
                    'weight' => '1.5'
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.evaluation-cycles.store'), $cycleData);

        $response->assertRedirect(route('admin.evaluation-cycles.index'));
        $response->assertSessionHas('success', 'Ciclo de avaliação criado com sucesso.');

        $this->assertEquals(1, EvaluationCycle::count());
        $cycle = EvaluationCycle::first();
        
        $this->assertEquals('Novo Ciclo RH 2026', $cycle->name);
        $this->assertCount(2, $cycle->global_goals);
        
        // Verificar tipos convertidos
        $firstGoal = $cycle->global_goals[0];
        $this->assertEquals('Processos Respondidos', $firstGoal['description']);
        $this->assertEquals('processos', $firstGoal['metric']);
        $this->assertEquals(150.0, $firstGoal['target_value']);
        $this->assertEquals(2.5, $firstGoal['weight']);
    }

    public function test_admin_can_update_cycle_with_global_goals(): void
    {
        $cycle = EvaluationCycle::create([
            'name' => 'Ciclo Provisório',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
            'cutoff_score' => 3.00,
            'status' => 'active',
            'weights' => [],
            'global_goals' => [
                [
                    'description' => 'Meta Antiga',
                    'metric' => 'unidades',
                    'target_value' => 50,
                    'weight' => 1.0
                ]
            ]
        ]);

        $updateData = [
            'name' => 'Ciclo Provisório Atualizado',
            'start_date' => $cycle->start_date->format('Y-m-d'),
            'end_date' => $cycle->end_date->format('Y-m-d'),
            'cutoff_score' => 4.00,
            'status' => 'active',
            'weights' => [],
            'global_goals' => [
                [
                    'description' => 'Meta Atualizada',
                    'metric' => 'percentual',
                    'target_value' => '85',
                    'weight' => '2.0'
                ],
                [
                    'description' => 'Nova Meta Adicionada',
                    'metric' => 'atendimentos',
                    'target_value' => '200',
                    'weight' => '3.0'
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.evaluation-cycles.update', $cycle), $updateData);

        $response->assertRedirect(route('admin.evaluation-cycles.index'));
        $response->assertSessionHas('success', 'Ciclo de avaliação atualizado com sucesso.');

        $cycle->refresh();
        $this->assertEquals('Ciclo Provisório Atualizado', $cycle->name);
        $this->assertEquals(4.00, $cycle->cutoff_score);
        $this->assertCount(2, $cycle->global_goals);

        $firstGoal = $cycle->global_goals[0];
        $this->assertEquals('Meta Atualizada', $firstGoal['description']);
        $this->assertEquals(85.0, $firstGoal['target_value']);
        $this->assertEquals(2.0, $firstGoal['weight']);
    }

    public function test_admin_cannot_store_cycle_with_invalid_goals(): void
    {
        $cycleData = [
            'name' => 'Ciclo Inválido',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(60)->format('Y-m-d'),
            'cutoff_score' => 3.00,
            'status' => 'active',
            'global_goals' => [
                [
                    'description' => '', // Inválido (vazio)
                    'metric' => 'processos',
                    'target_value' => '100',
                    'weight' => '1.0'
                ],
                [
                    'description' => 'Meta 2',
                    'metric' => '', // Inválido (vazio)
                    'target_value' => '100',
                    'weight' => '1.0'
                ],
                [
                    'description' => 'Meta 3',
                    'metric' => 'processos',
                    'target_value' => 'texto', // Inválido (não numérico)
                    'weight' => '1.0'
                ],
                [
                    'description' => 'Meta 4',
                    'metric' => 'processos',
                    'target_value' => '100',
                    'weight' => 'texto' // Inválido (não numérico)
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.evaluation-cycles.store'), $cycleData);

        $response->assertSessionHasErrors([
            'global_goals.0.description',
            'global_goals.1.metric',
            'global_goals.2.target_value',
            'global_goals.3.weight',
        ]);
        
        $this->assertEquals(0, EvaluationCycle::count());
    }
}
