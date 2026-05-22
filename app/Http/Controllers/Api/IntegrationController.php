<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use App\Models\EmployeePoint;
use App\Models\EvaluationCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IntegrationController extends Controller
{
    /**
     * Importa ou atualiza servidores do RH.
     */
    public function importServidores(Request $request)
    {
        $input = $request->all();
        // Permite receber o array direto na raiz ou envolvido na chave 'servidores'
        if (!isset($input['servidores']) && is_array($input) && (empty($input) || isset($input[0]))) {
            $input = ['servidores' => $input];
        }

        $validator = Validator::make($input, [
            'servidores' => 'required|array',
            'servidores.*.nome' => 'required|string|max:255',
            'servidores.*.matricula' => 'required|string|max:50',
            'servidores.*.cargo' => 'required|string|max:255',
            'servidores.*.lotacao' => 'required|string|max:255',
            'servidores.*.grupo' => 'required|string|max:255',
            'servidores.*.email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação nos dados enviados.',
                'errors' => $validator->errors()
            ], 422);
        }

        $createdCount = 0;
        $updatedCount = 0;
        $errors = [];

        foreach ($input['servidores'] as $index => $item) {
            try {
                // 1. Criar ou obter a Secretaria (Office) correspondente
                $office = Office::firstOrCreate(
                    ['name' => $item['lotacao']],
                    [
                        'sigla' => strtoupper(substr(str_replace(' ', '', $item['lotacao']), 0, 10)),
                        'is_active' => true
                    ]
                );

                // 2. Localizar usuário pela matrícula
                $user = User::where('registration_number', $item['matricula'])->first();

                $email = $item['email'] ?? ($user ? $user->email : $item['matricula'] . '@araucaria.pr.gov.br');

                $userData = [
                    'name' => $item['nome'],
                    'cargo' => $item['cargo'],
                    'lotacao' => $office->name,
                    'office_id' => $office->id,
                    'evaluation_group' => $item['grupo'],
                    'role' => 'Servidor',
                    'email' => $email,
                ];

                if (!$user) {
                    $userData['password'] = Hash::make(Str::random(32));
                    $userData['registration_number'] = $item['matricula'];
                    User::create($userData);
                    $createdCount++;
                } else {
                    $user->update($userData);
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'matricula' => $item['matricula'] ?? 'desconhecida',
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Importação de servidores concluída.',
            'stats' => [
                'criados' => $createdCount,
                'atualizados' => $updatedCount,
                'erros' => count($errors)
            ],
            'errors' => $errors
        ], 200);
    }

    /**
     * Importa ou atualiza registros de ponto consolidados.
     */
    public function importPonto(Request $request)
    {
        $input = $request->all();
        // Permite receber o array direto na raiz ou envolvido na chave 'registros'
        if (!isset($input['registros']) && is_array($input) && (empty($input) || isset($input[0]))) {
            $input = ['registros' => $input];
        }

        $validator = Validator::make($input, [
            'registros' => 'required|array',
            'registros.*.matricula' => 'required|string|max:50',
            'registros.*.cycle_id' => 'nullable|integer',
            'registros.*.faltas_injustificadas' => 'required|integer|min:0',
            'registros.*.atrasos_minutos' => 'required|integer|min:0',
            'registros.*.horas_extras' => 'required|integer|min:0',
            'registros.*.mensagem' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação nos dados enviados.',
                'errors' => $validator->errors()
            ], 422);
        }

        $successCount = 0;
        $errors = [];

        foreach ($input['registros'] as $index => $item) {
            try {
                // 1. Localizar servidor pela matrícula
                $user = User::where('registration_number', $item['matricula'])->first();

                if (!$user) {
                    $errors[] = [
                        'index' => $index,
                        'matricula' => $item['matricula'],
                        'error' => 'Servidor não encontrado com a matrícula fornecida.'
                    ];
                    continue;
                }

                // 2. Determinar o ciclo de avaliação
                $cycleId = $item['cycle_id'] ?? null;
                if (!$cycleId) {
                    $cycle = EvaluationCycle::where('status', 'active')
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if (!$cycle) {
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
                                    'produtividade' => 2,
                                    'relacionamento' => 1,
                                ],
                                'global_goals' => [],
                                'cutoff_score' => 7.0,
                                'block_on_pad' => true,
                            ]
                        );
                    }
                    $cycleId = $cycle->id;
                } else {
                    $cycleExists = EvaluationCycle::where('id', $cycleId)->exists();
                    if (!$cycleExists) {
                        $errors[] = [
                            'index' => $index,
                            'matricula' => $item['matricula'],
                            'error' => "Ciclo de avaliação com ID {$cycleId} não encontrado."
                        ];
                        continue;
                    }
                }

                // 3. Fazer upsert dos registros de ponto consolidados
                EmployeePoint::updateOrCreate(
                    [
                        'employee_id' => $user->id,
                        'cycle_id' => $cycleId,
                    ],
                    [
                        'faltas_injustificadas' => $item['faltas_injustificadas'],
                        'atrasos_minutos' => $item['atrasos_minutos'],
                        'horas_extras' => $item['horas_extras'],
                        'mensagem' => $item['mensagem'] ?? null,
                    ]
                );

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'matricula' => $item['matricula'] ?? 'desconhecida',
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Importação de registros de ponto concluída.',
            'stats' => [
                'sucessos' => $successCount,
                'erros' => count($errors)
            ],
            'errors' => $errors
        ], 200);
    }
}
