<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Iniciando Seeding do Boilerplate Core...');

        // 1. Popular as Permissões
        $this->call(PermissionSeeder::class);

        // 2. Criar ou Obter o Usuário Administrador Master Padrão
        $admin = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Administrador Master',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
                'active' => true,
            ]
        );

        // 3. Rodar Perfil Administrador e Vincular Permissões
        $this->call(AdminRoleSeeder::class);

        // 4. Popular os Menus e Categorias
        $this->call(MenuSeeder::class);

        // 5. Popular as Perguntas Parametrizadas de Avaliação
        $this->call(EvaluationQuestionsSeeder::class);

        // 5.1 Popular as Âncoras BARS das Perguntas
        $this->call(BarsAnchorsSeeder::class);

        // 6. Criar Servidores Públicos de Teste para o CAPD Araucária
        $servidores = [
            [
                'name' => 'Carlos Eduardo Costa',
                'email' => 'carlos.costa@araucaria.pr.gov.br',
                'password' => Hash::make('servidor123'),
                'role' => 'Leader',
                'registration_number' => '147.234',
                'cargo' => 'Analista de Atendimento ao Cidadão',
                'lotacao' => 'Subprefeitura Centro-Sul',
                'evaluation_group' => 'geral',
                'has_active_pad' => false,
            ],
            [
                'name' => 'Mariana Alencar Santos',
                'email' => 'mariana.santos@araucaria.pr.gov.br',
                'password' => Hash::make('servidor123'),
                'role' => 'Leader',
                'registration_number' => '159.357',
                'cargo' => 'Supervisora de Operações e Gestão Escolar',
                'lotacao' => 'Secretaria de Educação',
                'evaluation_group' => 'educacao',
                'has_active_pad' => false,
            ],
            [
                'name' => 'João Carlos da Silva',
                'email' => 'joao.silva@araucaria.pr.gov.br',
                'password' => Hash::make('servidor123'),
                'role' => 'Leader',
                'registration_number' => '124.582',
                'cargo' => 'Assessor Técnico de Políticas de Saúde Pública',
                'lotacao' => 'Secretaria de Saúde',
                'evaluation_group' => 'saude',
                'has_active_pad' => false,
            ],
        ];

        foreach ($servidores as $s) {
            User::firstOrCreate(
                ['email' => $s['email']],
                $s
            );
        }

        // 7. Criar Ciclo de Avaliação Ativo com Metas Globais do RH
        $cycle = \App\Models\EvaluationCycle::firstOrCreate(
            ['status' => 'active'],
            [
                'name' => 'Ciclo Geral de Desempenho 2026',
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
                    [
                        'description' => 'Processos Analisados e Concluídos',
                        'metric' => 'Processos',
                        'target_value' => 100,
                        'weight' => 1.0
                    ],
                    [
                        'description' => 'Satisfação de Atendimento ao Cidadão',
                        'metric' => '%',
                        'target_value' => 90,
                        'weight' => 1.0
                    ]
                ],
                'cutoff_score' => 3.00,
            ]
        );

        // 8. Criar Avaliações, Metas e Incidentes Críticos para os Servidores de Teste conforme Mockup
        $joao = User::where('email', 'joao.silva@araucaria.pr.gov.br')->first();
        $carlos = User::where('email', 'carlos.costa@araucaria.pr.gov.br')->first();
        $mariana = User::where('email', 'mariana.santos@araucaria.pr.gov.br')->first();

        if ($cycle) {
            // Criar Avaliação Concluída para o João
            if ($joao) {
                $evalJoao = \App\Models\Evaluation::updateOrCreate(
                    [
                        'cycle_id' => $cycle->id,
                        'evaluated_id' => $joao->id,
                    ],
                    [
                        'evaluator_id' => $admin->id,
                        'categoria' => 'saude',
                        'status' => 'submitted',
                        'weight_goals' => 0.50,
                        'weight_competencies' => 0.50,
                        'score_goals' => 3.50,
                        'score_competencies' => 4.34,
                        'final_score' => 3.92, // 3.92 * 20 = 78.4
                        'submitted_at' => now(),
                    ]
                );

                // Criar Metas para o João (2 metas)
                \App\Models\QuantitativeGoal::updateOrCreate(
                    ['evaluation_id' => $evalJoao->id, 'description' => 'Processos Analisados e Concluídos'],
                    ['metric' => 'Processos', 'target_value' => 100, 'achieved_value' => 90, 'weight' => 1.0]
                );
                \App\Models\QuantitativeGoal::updateOrCreate(
                    ['evaluation_id' => $evalJoao->id, 'description' => 'Satisfação de Atendimento ao Cidadão'],
                    ['metric' => '%', 'target_value' => 90, 'achieved_value' => 85, 'weight' => 1.0]
                );

                // Criar incidentes para o João
                \App\Models\EmployeeDiaryIncident::updateOrCreate(
                    ['employee_id' => $joao->id, 'description' => 'Atendimento exemplar na UBS Centro durante plantão.'],
                    ['reporter_id' => $admin->id, 'category' => 'qualidade', 'type' => 'positive', 'incident_date' => now()->subDays(2)]
                );
                \App\Models\EmployeeDiaryIncident::updateOrCreate(
                    ['employee_id' => $joao->id, 'description' => 'Apoio na implantação do sistema de triagem rápida.'],
                    ['reporter_id' => $admin->id, 'category' => 'iniciativa', 'type' => 'positive', 'incident_date' => now()->subDays(3)]
                );
            }

            // Criar Avaliação Pendente para o Carlos
            if ($carlos) {
                $evalCarlos = \App\Models\Evaluation::updateOrCreate(
                    [
                        'cycle_id' => $cycle->id,
                        'evaluated_id' => $carlos->id,
                    ],
                    [
                        'evaluator_id' => $admin->id,
                        'categoria' => 'geral',
                        'status' => 'pending',
                        'weight_goals' => 0.50,
                        'weight_competencies' => 0.50,
                    ]
                );

                // Criar Metas para o Carlos (2 metas)
                \App\Models\QuantitativeGoal::updateOrCreate(
                    ['evaluation_id' => $evalCarlos->id, 'description' => 'Processos Analisados e Concluídos'],
                    ['metric' => 'Processos', 'target_value' => 100, 'weight' => 1.0]
                );
                \App\Models\QuantitativeGoal::updateOrCreate(
                    ['evaluation_id' => $evalCarlos->id, 'description' => 'Satisfação de Atendimento ao Cidadão'],
                    ['metric' => '%', 'target_value' => 90, 'weight' => 1.0]
                );

                // Criar incidentes para o Carlos
                \App\Models\EmployeeDiaryIncident::updateOrCreate(
                    ['employee_id' => $carlos->id, 'description' => 'Organização ágil do guichê de atendimento social.'],
                    ['reporter_id' => $admin->id, 'category' => 'qualidade', 'type' => 'positive', 'incident_date' => now()->subDays(1)]
                );
                \App\Models\EmployeeDiaryIncident::updateOrCreate(
                    ['employee_id' => $carlos->id, 'description' => 'Atraso não justificado no início do expediente.'],
                    ['reporter_id' => $admin->id, 'category' => 'assiduidade', 'type' => 'negative', 'incident_date' => now()->subDays(4)]
                );
            }

            // Criar Avaliação Pendente para a Mariana
            if ($mariana) {
                $evalMariana = \App\Models\Evaluation::updateOrCreate(
                    [
                        'cycle_id' => $cycle->id,
                        'evaluated_id' => $mariana->id,
                    ],
                    [
                        'evaluator_id' => $admin->id,
                        'categoria' => 'educacao',
                        'status' => 'pending',
                        'weight_goals' => 0.50,
                        'weight_competencies' => 0.50,
                    ]
                );

                // Criar Metas para a Mariana (2 metas)
                \App\Models\QuantitativeGoal::updateOrCreate(
                    ['evaluation_id' => $evalMariana->id, 'description' => 'Processos Analisados e Concluídos'],
                    ['metric' => 'Processos', 'target_value' => 100, 'weight' => 1.0]
                );
                \App\Models\QuantitativeGoal::updateOrCreate(
                    ['evaluation_id' => $evalMariana->id, 'description' => 'Satisfação de Atendimento ao Cidadão'],
                    ['metric' => '%', 'target_value' => 90, 'weight' => 1.0]
                );

                // Criar incidentes para a Mariana
                \App\Models\EmployeeDiaryIncident::updateOrCreate(
                    ['employee_id' => $mariana->id, 'description' => 'Supervisão excelente da distribuição da merenda escolar.'],
                    ['reporter_id' => $admin->id, 'category' => 'qualidade', 'type' => 'positive', 'incident_date' => now()->subDays(2)]
                );
                \App\Models\EmployeeDiaryIncident::updateOrCreate(
                    ['employee_id' => $mariana->id, 'description' => 'Divergência de inventário no depósito de materiais escolares.'],
                    ['reporter_id' => $admin->id, 'category' => 'responsabilidade', 'type' => 'negative', 'incident_date' => now()->subDays(5)]
                );
            }
        }

        $this->command->info('Seeding do Boilerplate Core concluído com sucesso!');
    }
}
