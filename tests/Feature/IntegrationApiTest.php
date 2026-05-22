<?php

namespace Tests\Feature;

use App\Models\Office;
use App\Models\User;
use App\Models\EmployeePoint;
use App\Models\EvaluationCycle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IntegrationApiTest extends TestCase
{
    use RefreshDatabase;

    protected string $validToken = 'test_integration_token_123';

    /**
     * Test a request with a missing token returns unauthorized.
     */
    public function test_unauthorized_if_token_is_missing(): void
    {
        $response = $this->postJson('/api/v1/integration/rh/servidores', []);
        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Unauthorized. Invalid or missing integration token.'
                 ]);
    }

    /**
     * Test a request with an invalid token returns unauthorized.
     */
    public function test_unauthorized_if_token_is_invalid(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer wrong_token_456'
        ])->postJson('/api/v1/integration/rh/servidores', []);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Unauthorized. Invalid or missing integration token.'
                 ]);
    }

    /**
     * Test the RH import endpoint creates users and offices correctly.
     */
    public function test_can_import_employees_from_rh(): void
    {
        $payload = [
            'servidores' => [
                [
                    'nome' => 'José da Silva',
                    'matricula' => '102030',
                    'cargo' => 'Auxiliar Administrativo',
                    'lotacao' => 'Secretaria de Finanças',
                    'grupo' => 'Geral',
                    'email' => 'jose.silva@araucaria.pr.gov.br'
                ],
                [
                    'nome' => 'Maria Oliveira',
                    'matricula' => '405060',
                    'cargo' => 'Engenheira Civil',
                    'lotacao' => 'Secretaria de Obras',
                    'grupo' => 'Engenharia'
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->validToken
        ])->postJson('/api/v1/integration/rh/servidores', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'stats' => [
                         'criados' => 2,
                         'atualizados' => 0,
                         'erros' => 0
                     ]
                 ]);

        // Verificar se os Offices foram criados
        $this->assertTrue(Office::where('name', 'Secretaria de Finanças')->exists());
        $this->assertTrue(Office::where('name', 'Secretaria de Obras')->exists());

        // Verificar se os usuários foram criados corretamente
        $jose = User::where('registration_number', '102030')->first();
        $this->assertNotNull($jose);
        $this->assertEquals('José da Silva', $jose->name);
        $this->assertEquals('Auxiliar Administrativo', $jose->cargo);
        $this->assertEquals('Servidor', $jose->role); // Deve receber a role Servidor
        $this->assertEquals('jose.silva@araucaria.pr.gov.br', $jose->email);

        $maria = User::where('registration_number', '405060')->first();
        $this->assertNotNull($maria);
        $this->assertEquals('Maria Oliveira', $maria->name);
        $this->assertEquals('405060@araucaria.pr.gov.br', $maria->email); // Email fallback gerado
        $this->assertEquals('Servidor', $maria->role);
    }

    /**
     * Test the RH import endpoint updates existing users correctly.
     */
    public function test_can_update_existing_employees_from_rh(): void
    {
        // 1. Criar um usuário previamente
        $office = Office::create([
            'name' => 'Secretaria Antiga',
            'sigla' => 'SA',
            'is_active' => true
        ]);

        $user = User::create([
            'name' => 'Servidor Antigo Name',
            'email' => 'antigo@araucaria.pr.gov.br',
            'password' => Hash::make('secret'),
            'role' => 'Servidor',
            'registration_number' => '102030',
            'cargo' => 'Cargo Antigo',
            'lotacao' => 'Secretaria Antiga',
            'office_id' => $office->id,
            'evaluation_group' => 'Geral'
        ]);

        $payload = [
            'servidores' => [
                [
                    'nome' => 'José da Silva Atualizado',
                    'matricula' => '102030',
                    'cargo' => 'Analista de Sistemas',
                    'lotacao' => 'Secretaria de Finanças',
                    'grupo' => 'TI',
                    'email' => 'novo.jose@araucaria.pr.gov.br'
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->validToken
        ])->postJson('/api/v1/integration/rh/servidores', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'stats' => [
                         'criados' => 0,
                         'atualizados' => 1,
                         'erros' => 0
                     ]
                 ]);

        $user->refresh();
        $this->assertEquals('José da Silva Atualizado', $user->name);
        $this->assertEquals('Analista de Sistemas', $user->cargo);
        $this->assertEquals('Secretaria de Finanças', $user->lotacao);
        $this->assertEquals('novo.jose@araucaria.pr.gov.br', $user->email);
    }

    /**
     * Test point electronic records import under normal conditions.
     */
    public function test_can_import_point_records(): void
    {
        // Criar ciclo de avaliação ativo
        $cycle = EvaluationCycle::create([
            'name' => 'Ciclo Teste',
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'status' => 'active',
            'weights' => [],
            'global_goals' => [],
            'cutoff_score' => 7.0
        ]);

        // Criar usuário servidor
        $user = User::create([
            'name' => 'Servidor Teste',
            'email' => 'teste@araucaria.pr.gov.br',
            'password' => Hash::make('secret'),
            'role' => 'Servidor',
            'registration_number' => '998877',
            'cargo' => 'Auxiliar',
            'lotacao' => 'Geral',
            'evaluation_group' => 'Geral'
        ]);

        $payload = [
            'registros' => [
                [
                    'matricula' => '998877',
                    'cycle_id' => $cycle->id,
                    'faltas_injustificadas' => 2,
                    'atrasos_minutos' => 45,
                    'horas_extras' => 10,
                    'mensagem' => 'Ponto apurado com sucesso.'
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->validToken
        ])->postJson('/api/v1/integration/ponto/registros', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'stats' => [
                         'sucessos' => 1,
                         'erros' => 0
                     ]
                 ]);

        // Verificar registro no banco
        $point = EmployeePoint::where('employee_id', $user->id)
                              ->where('cycle_id', $cycle->id)
                              ->first();
        $this->assertNotNull($point);
        $this->assertEquals(2, $point->faltas_injustificadas);
        $this->assertEquals(45, $point->atrasos_minutos);
        $this->assertEquals(10, $point->horas_extras);
        $this->assertEquals('Ponto apurado com sucesso.', $point->mensagem);
    }

    /**
     * Test point electronic records import auto creates active cycle if none provided.
     */
    public function test_can_import_point_records_without_cycle_id(): void
    {
        // Criar usuário servidor
        $user = User::create([
            'name' => 'Servidor Teste',
            'email' => 'teste@araucaria.pr.gov.br',
            'password' => Hash::make('secret'),
            'role' => 'Servidor',
            'registration_number' => '998877',
            'cargo' => 'Auxiliar',
            'lotacao' => 'Geral',
            'evaluation_group' => 'Geral'
        ]);

        $payload = [
            'registros' => [
                [
                    'matricula' => '998877',
                    'faltas_injustificadas' => 1,
                    'atrasos_minutos' => 10,
                    'horas_extras' => 0,
                    'mensagem' => 'Ciclo automático.'
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->validToken
        ])->postJson('/api/v1/integration/ponto/registros', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'stats' => [
                         'sucessos' => 1,
                         'erros' => 0
                     ]
                 ]);

        // Um ciclo de avaliação ativo deve ter sido criado/obtido
        $cycle = EvaluationCycle::where('status', 'active')->first();
        $this->assertNotNull($cycle);

        // Verificar registro no banco
        $point = EmployeePoint::where('employee_id', $user->id)
                              ->where('cycle_id', $cycle->id)
                              ->first();
        $this->assertNotNull($point);
        $this->assertEquals(1, $point->faltas_injustificadas);
        $this->assertEquals(10, $point->atrasos_minutos);
    }

    /**
     * Test point elektronics cannot import if employee registration doesn't exist.
     */
    public function test_cannot_import_point_for_non_existent_employee(): void
    {
        $payload = [
            'registros' => [
                [
                    'matricula' => '000000', // Matrícula inexistente
                    'faltas_injustificadas' => 0,
                    'atrasos_minutos' => 0,
                    'horas_extras' => 0
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->validToken
        ])->postJson('/api/v1/integration/ponto/registros', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'stats' => [
                         'sucessos' => 0,
                         'erros' => 1
                     ]
                 ]);

        $this->assertCount(1, $response->json('errors'));
        $this->assertEquals('Servidor não encontrado com a matrícula fornecida.', $response->json('errors.0.error'));
    }

    /**
     * Test point electronics cannot import for non existent cycle id.
     */
    public function test_cannot_import_point_for_non_existent_cycle(): void
    {
        // Criar usuário servidor
        User::create([
            'name' => 'Servidor Teste',
            'email' => 'teste@araucaria.pr.gov.br',
            'password' => Hash::make('secret'),
            'role' => 'Servidor',
            'registration_number' => '998877',
            'cargo' => 'Auxiliar',
            'lotacao' => 'Geral',
            'evaluation_group' => 'Geral'
        ]);

        $payload = [
            'registros' => [
                [
                    'matricula' => '998877',
                    'cycle_id' => 9999, // ID inexistente
                    'faltas_injustificadas' => 0,
                    'atrasos_minutos' => 0,
                    'horas_extras' => 0
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->validToken
        ])->postJson('/api/v1/integration/ponto/registros', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'stats' => [
                         'sucessos' => 0,
                         'erros' => 1
                     ]
                 ]);

        $this->assertCount(1, $response->json('errors'));
        $this->assertEquals('Ciclo de avaliação com ID 9999 não encontrado.', $response->json('errors.0.error'));
    }
}
