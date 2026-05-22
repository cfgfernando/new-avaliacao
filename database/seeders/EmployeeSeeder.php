<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\User;
use App\Models\EmployeePoint;
use App\Models\EvaluationCycle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Populando Banco de Servidores Avaliados...');

        // 1. Obter as Secretarias oficiais cadastradas (IDs 1, 2, 3)
        $offices = [
            'geral' => Office::find(1),
            'guarda' => Office::find(2),
            'saude' => Office::find(3),
        ];

        // Validar se todas as secretarias existem
        foreach ($offices as $key => $office) {
            if (!$office) {
                throw new \Exception("A secretaria oficial correspondente a '{$key}' (IDs de 1 a 3) não foi encontrada no banco de dados.");
            }
        }

        // 2. Definir arrays de dados para popular
        $cargos = [
            'geral' => ['Assistente Administrativo', 'Auxiliar de Serviços Gerais', 'Analista de Gestão', 'Técnico de Informática'],
            'saude' => ['Enfermeiro de Saúde da Família', 'Técnico em Enfermagem', 'Fisioterapeuta', 'Cirurgião Dentista'],
            'guarda' => ['Guarda Municipal 3ª Classe', 'Guarda Municipal 2ª Classe', 'Guarda Municipal Classe Especial', 'Inspetor da Guarda'],
        ];

        $nomes = [
            'Masculinos' => [
                'Alexandre', 'Bernardo', 'Carlos', 'Daniel', 'Eduardo', 'Felipe', 'Gabriel', 'Heitor', 'Igor', 'João',
                'Kauan', 'Leonardo', 'Marcos', 'Nicolas', 'Otávio', 'Pedro', 'Rafael', 'Samuel', 'Thiago', 'Victor',
                'Adriano', 'Bruno', 'Douglas', 'Fabrício', 'Guilherme', 'Hugo', 'Jonas', 'Lucas', 'Murilo', 'Rodrigo'
            ],
            'Femininos' => [
                'Alice', 'Beatriz', 'Camila', 'Diana', 'Elisa', 'Fernanda', 'Gabriela', 'Helena', 'Isabela', 'Júlia',
                'Karina', 'Larissa', 'Mariana', 'Natália', 'Olívia', 'Patrícia', 'Raquel', 'Sofia', 'Tatiana', 'Vanessa',
                'Amanda', 'Bruna', 'Carolina', 'Débora', 'Giovanna', 'Isadora', 'Letícia', 'Manuela', 'Priscila', 'Vitória'
            ],
            'Sobrenomes' => [
                'Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira', 'Alves', 'Pereira', 'Lima', 'Gomes',
                'Costa', 'Ribeiro', 'Martins', 'Carvalho', 'Almeida', 'Lopes', 'Soares', 'Dias', 'Vieira', 'Barbosa',
                'Rocha', 'Nascimento', 'Cardoso', 'Teixeira', 'Moreira', 'Mendes', 'Nunes', 'Melo', 'Assis', 'Castilho'
            ]
        ];

        $categorias = ['geral', 'saude', 'guarda'];
        $matriculaBase = [
            'geral' => 1000,
            'saude' => 2000,
            'guarda' => 3000,
        ];

        // 3. Obter ou Criar ciclo ativo de avaliação
        $cycle = EvaluationCycle::where('status', 'active')->first();
        if (!$cycle) {
            $cycle = EvaluationCycle::create([
                'name' => 'Ciclo de Avaliação de Desempenho 2026',
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(30),
                'status' => 'active',
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
                'global_goals' => [],
                'cutoff_score' => 3.00
            ]);
        }

        // 4. Loop para criar 20 servidores por categoria
        $totalCreated = 0;
        foreach ($categorias as $cat) {
            $office = $offices[$cat];
            $listaCargos = $cargos[$cat];
            $baseMatricula = $matriculaBase[$cat];

            for ($i = 0; $i < 20; $i++) {
                $matricula = $baseMatricula + $i;
                
                // Gerar nome semi-aleatório
                $genero = ($i % 2 === 0) ? 'Masculinos' : 'Femininos';
                $pNome = $nomes[$genero][array_rand($nomes[$genero])];
                $sobrenome1 = $nomes['Sobrenomes'][array_rand($nomes['Sobrenomes'])];
                $sobrenome2 = $nomes['Sobrenomes'][array_rand($nomes['Sobrenomes'])];
                
                // Evitar sobrenomes idênticos
                while ($sobrenome1 === $sobrenome2) {
                    $sobrenome2 = $nomes['Sobrenomes'][array_rand($nomes['Sobrenomes'])];
                }
                
                $nomeCompleto = "{$pNome} {$sobrenome1} {$sobrenome2}";
                $email = strtolower($pNome . '.' . strtolower(str_replace(' ', '', $sobrenome2)) . '.' . $matricula . '@araucaria.pr.gov.br');
                $cargo = $listaCargos[$i % count($listaCargos)];

                // Criar ou atualizar o servidor
                $user = User::updateOrCreate(
                    ['registration_number' => (string) $matricula],
                    [
                        'name' => $nomeCompleto,
                        'email' => $email,
                        'password' => Hash::make('servidor123'),
                        'role' => 'Servidor',
                        'cargo' => $cargo,
                        'lotacao' => $office->name,
                        'office_id' => $office->id,
                        'evaluation_group' => $cat,
                        'has_active_pad' => ($i === 7) ? true : false, // Inserir 1 servidor com PAD em cada grupo para fins de demonstração
                    ]
                );

                // Criar registro de ponto eletrônico simulado
                EmployeePoint::updateOrCreate(
                    [
                        'employee_id' => $user->id,
                        'cycle_id' => $cycle->id,
                    ],
                    [
                        'faltas_injustificadas' => rand(0, 3) === 3 ? rand(1, 4) : 0, // A maioria tem 0 faltas
                        'atrasos_minutos' => rand(0, 2) === 2 ? rand(10, 120) : 0,
                        'horas_extras' => rand(0, 5) * 4,
                        'mensagem' => 'Frequência apurada com sucesso.'
                    ]
                );

                $totalCreated++;
            }
        }

        $this->command->info("Seeding completo! {$totalCreated} servidores e seus registros de frequência correspondentes foram povoados com sucesso.");
    }
}
