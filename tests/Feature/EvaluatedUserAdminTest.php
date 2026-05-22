<?php

namespace Tests\Feature;

use App\Models\Office;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EvaluatedUserAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $chefia;
    protected Office $office;

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

        $this->office = Office::create([
            'name' => 'Secretaria Municipal de Saúde',
            'sigla' => 'SMS',
            'is_active' => true,
        ]);
    }

    public function test_non_admin_cannot_access_evaluated_user_management(): void
    {
        $response = $this->actingAs($this->chefia)->get(route('admin.evaluated-users.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->chefia)->get(route('admin.evaluated-users.create'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_evaluated_user_management_index_and_create_views(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.evaluated-users.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('admin.evaluated-users.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_evaluated_user(): void
    {
        $userData = [
            'name' => 'Ana Souza',
            'email' => 'ana.souza@araucaria.pr.gov.br',
            'registration_number' => '554433',
            'cargo' => 'Enfermeira',
            'office_id' => $this->office->id,
            'evaluation_group' => 'saude',
            'evaluator_id' => $this->chefia->id,
            'has_active_pad' => '1',
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.evaluated-users.store'), $userData);

        $response->assertRedirect(route('admin.evaluated-users.index'));
        $response->assertSessionHas('success', 'Servidor avaliado cadastrado com sucesso.');

        // O total de usuários agora deve ser 3 (admin, chefia, e o novo servidor)
        $this->assertEquals(3, User::count());
        
        $user = User::where('email', 'ana.souza@araucaria.pr.gov.br')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Ana Souza', $user->name);
        $this->assertEquals('554433', $user->registration_number);
        $this->assertEquals('Enfermeira', $user->cargo);
        $this->assertEquals($this->office->id, $user->office_id);
        $this->assertEquals('Secretaria Municipal de Saúde', $user->lotacao); // Verificação do booted observer
        $this->assertEquals('saude', $user->evaluation_group);
        $this->assertEquals($this->chefia->id, $user->evaluator_id);
        $this->assertTrue($user->has_active_pad);
        $this->assertEquals('Servidor', $user->role); // Papel padrão atribuído
    }

    public function test_admin_can_update_evaluated_user(): void
    {
        $user = User::create([
            'name' => 'Servidor Provisório',
            'email' => 'prov@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Servidor',
            'office_id' => $this->office->id,
            'registration_number' => '999999',
            'cargo' => 'Auxiliar',
            'evaluation_group' => 'geral',
        ]);

        $newOffice = Office::create([
            'name' => 'Secretaria Municipal de Educação',
            'sigla' => 'SMED',
            'is_active' => true,
        ]);

        $updateData = [
            'name' => 'Servidor Atualizado',
            'email' => 'prov.atualizado@araucaria.pr.gov.br',
            'registration_number' => '888888',
            'cargo' => 'Auxiliar de Serviços Gerais',
            'office_id' => $newOffice->id,
            'evaluation_group' => 'educacao',
            'evaluator_id' => '',
            // has_active_pad não enviado -> será falso
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.evaluated-users.update', $user->id), $updateData);

        $response->assertRedirect(route('admin.evaluated-users.index'));
        $response->assertSessionHas('success', 'Servidor avaliado atualizado com sucesso.');

        $user->refresh();
        $this->assertEquals('Servidor Atualizado', $user->name);
        $this->assertEquals('prov.atualizado@araucaria.pr.gov.br', $user->email);
        $this->assertEquals('888888', $user->registration_number);
        $this->assertEquals('Auxiliar de Serviços Gerais', $user->cargo);
        $this->assertEquals($newOffice->id, $user->office_id);
        $this->assertEquals('Secretaria Municipal de Educação', $user->lotacao); // booted observer atualizou!
        $this->assertEquals('educacao', $user->evaluation_group);
        $this->assertNull($user->evaluator_id);
        $this->assertFalse($user->has_active_pad);
    }

    public function test_admin_cannot_delete_user_with_linked_evaluations(): void
    {
        $user = User::create([
            'name' => 'Servidor com Avaliação',
            'email' => 'com.aval@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Servidor',
            'office_id' => $this->office->id,
            'registration_number' => '111111',
            'cargo' => 'Auxiliar',
            'evaluation_group' => 'geral',
        ]);

        // Criar um Ciclo de Avaliação
        $cycle = \App\Models\EvaluationCycle::create([
            'name' => 'Ciclo 2026',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
            'cutoff_score' => 3.00,
            'status' => 'active',
            'weights' => [],
            'global_goals' => [],
        ]);

        // Mock de avaliação
        $evaluation = Evaluation::create([
            'evaluated_id' => $user->id,
            'evaluator_id' => $this->chefia->id,
            'cycle_id' => $cycle->id,
            'status' => 'draft',
            'score' => 0,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.evaluated-users.destroy', $user->id));

        $response->assertRedirect(route('admin.evaluated-users.index'));
        $response->assertSessionHas('error', 'Não é possível excluir este servidor pois existem avaliações associadas a ele.');
        
        $this->assertNull($user->fresh()->deleted_at); // Não foi excluído por soft delete
    }

    public function test_admin_can_delete_user_without_evaluations(): void
    {
        $user = User::create([
            'name' => 'Servidor sem Avaliação',
            'email' => 'sem.aval@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Servidor',
            'office_id' => $this->office->id,
            'registration_number' => '222222',
            'cargo' => 'Auxiliar',
            'evaluation_group' => 'geral',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.evaluated-users.destroy', $user->id));

        $response->assertRedirect(route('admin.evaluated-users.index'));
        $response->assertSessionHas('success', 'Servidor avaliado excluído com sucesso.');
        
        $this->assertNotNull($user->fresh()->deleted_at); // Excluído por soft delete
    }
}
