<?php

namespace Tests\Feature;

use App\Models\Evaluation;
use App\Models\EvaluationCycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EvaluationSetupTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $chefia;
    protected User $servidor1;
    protected User $servidor2;
    protected User $servidorPad;
    protected EvaluationCycle $cycle;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Criar Ciclo Avaliativo Ativo
        $this->cycle = EvaluationCycle::create([
            'name' => 'Ciclo Teste 2026/1',
            'start_date' => now()->subDays(2),
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
            'status' => 'active'
        ]);

        // 2. Criar Usuários
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

        $this->servidor1 = User::create([
            'name' => 'Aline Enfermeira',
            'email' => 'aline@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Leader',
            'lotacao' => 'Secretaria de Saúde',
            'evaluation_group' => 'saude',
            'has_active_pad' => false
        ]);

        $this->servidor2 = User::create([
            'name' => 'Guarda João',
            'email' => 'joao@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Leader',
            'lotacao' => 'Secretaria de Segurança',
            'evaluation_group' => 'guarda',
            'has_active_pad' => false
        ]);

        $this->servidorPad = User::create([
            'name' => 'Servidor PAD',
            'email' => 'pad@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Leader',
            'lotacao' => 'Secretaria de Saúde',
            'evaluation_group' => 'saude',
            'has_active_pad' => true
        ]);
    }

    public function test_setup_screen_requires_authentication(): void
    {
        $response = $this->get(route('evaluations.setup.create'));
        $response->assertRedirect('/login');
    }

    public function test_setup_screen_renders_for_authenticated_users(): void
    {
        $response = $this->actingAs($this->chefia)->get(route('evaluations.setup.create'));
        $response->assertStatus(200);
        $response->assertViewHas(['cycles', 'lotacoes']);
    }

    public function test_admin_can_see_all_lotacoes_on_setup(): void
    {
        $response = $this->actingAs($this->admin)->get(route('evaluations.setup.create'));
        $response->assertStatus(200);
        
        $lotacoes = $response->viewData('lotacoes');
        
        $this->assertTrue($lotacoes->contains('Secretaria de Saúde'));
        $this->assertTrue($lotacoes->contains('Secretaria de Segurança'));
        $this->assertTrue($lotacoes->contains('CAPD Central'));
    }

    public function test_chefia_lotacao_is_locked_to_their_own(): void
    {
        $response = $this->actingAs($this->chefia)->get(route('evaluations.setup.create'));
        $response->assertStatus(200);
        
        $lotacoes = $response->viewData('lotacoes');
        
        $this->assertCount(1, $lotacoes);
        $this->assertEquals('Secretaria de Saúde', $lotacoes->first());
    }

    public function test_get_servidores_by_lotacao_returns_correct_json(): void
    {
        $response = $this->actingAs($this->chefia)->get(route('api.lotacao.servidores', ['lotacao' => 'Secretaria de Saúde']));
        
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Aline Enfermeira',
            'evaluation_group' => 'saude',
            'has_active_pad' => false
        ]);
        $response->assertJsonFragment([
            'name' => 'Servidor PAD',
            'has_active_pad' => true
        ]);
        // Não deve retornar o próprio supervisor (José)
        $response->assertJsonMissing([
            'name' => 'Supervisor José'
        ]);
    }

    public function test_setup_prevents_duplicating_evaluations_for_same_cycle(): void
    {
        // Criar uma avaliação pré-existente
        Evaluation::create([
            'cycle_id' => $this->cycle->id,
            'evaluator_id' => $this->chefia->id,
            'evaluated_id' => $this->servidor1->id,
            'categoria' => 'saude',
            'status' => 'draft'
        ]);

        // Tentar criar outra avaliação no mesmo ciclo
        $response = $this->actingAs($this->chefia)->post(route('evaluations.setup.store'), [
            'cycle_id' => $this->cycle->id,
            'lotacao' => 'Secretaria de Saúde',
            'evaluated_id' => $this->servidor1->id,
            'categoria' => 'saude'
        ]);

        $response->assertSessionHasErrors(['evaluated_id']);
        $this->assertEquals(1, Evaluation::count()); // Não deve inserir nova
    }

    public function test_setup_prevents_evaluating_servers_with_active_pad(): void
    {
        // Força block_on_pad como true
        $this->cycle->update(['block_on_pad' => true]);

        $response = $this->actingAs($this->chefia)->post(route('evaluations.setup.store'), [
            'cycle_id' => $this->cycle->id,
            'lotacao' => 'Secretaria de Saúde',
            'evaluated_id' => $this->servidorPad->id,
            'categoria' => 'saude'
        ]);

        $response->assertSessionHasErrors(['evaluated_id']);
        $this->assertEquals(0, Evaluation::count());
    }

    public function test_setup_allows_evaluating_servers_with_active_pad_when_block_on_pad_is_false(): void
    {
        // Força block_on_pad como false
        $this->cycle->update(['block_on_pad' => false]);

        $response = $this->actingAs($this->chefia)->post(route('evaluations.setup.store'), [
            'cycle_id' => $this->cycle->id,
            'lotacao' => 'Secretaria de Saúde',
            'evaluated_id' => $this->servidorPad->id,
            'categoria' => 'saude'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(1, Evaluation::count());
        
        $evaluation = Evaluation::first();
        $this->assertEquals('draft', $evaluation->status);
        $this->assertEquals('saude', $evaluation->categoria);
        
        $response->assertRedirect(route('evaluations.fill', $evaluation->id));
        $response->assertSessionHas('success');
    }


    public function test_setup_creates_draft_and_redirects_to_fill(): void
    {
        $response = $this->actingAs($this->chefia)->post(route('evaluations.setup.store'), [
            'cycle_id' => $this->cycle->id,
            'lotacao' => 'Secretaria de Saúde',
            'evaluated_id' => $this->servidor1->id,
            'categoria' => 'saude'
        ]);

        $this->assertEquals(1, Evaluation::count());
        $evaluation = Evaluation::first();
        
        $this->assertEquals('draft', $evaluation->status);
        $this->assertEquals('saude', $evaluation->categoria);
        
        $response->assertRedirect(route('evaluations.fill', $evaluation->id));
        $response->assertSessionHas('success');
    }
}
