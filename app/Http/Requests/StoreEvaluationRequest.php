<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\EvaluationCycle;
use App\Models\User;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Garante que o avaliador está autenticado
        return auth()->check();
    }

    public function rules(): array
    {
        $isDraft = $this->input('submit_type') === 'draft';

        return [
            'evaluated_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user && $user->has_active_pad) {
                        $fail('O servidor avaliado possui Processo Administrativo Disciplinar (PAD) ativo e sua avaliação está travada.');
                    }
                }
            ],
            'cycle_id' => [
                'required',
                'exists:evaluation_cycles,id',
                function ($attribute, $value, $fail) {
                    $cycle = EvaluationCycle::find($value);
                    if ($cycle && $cycle->status !== 'active') {
                        $fail('O ciclo de avaliação selecionado não está ativo para preenchimento.');
                    }
                }
            ],
            'answers' => $isDraft ? 'nullable|array' : 'required|array',
            'answers.*' => 'required|integer|between:1,5',
            'justifications' => 'array',
            'evidences' => 'array',
            
            // Metodologia Mista
            'weight_goals' => 'nullable|numeric|between:0,1',
            'weight_competencies' => 'nullable|numeric|between:0,1',
            'goals' => 'nullable|array',
            'goals.*.description' => 'required_with:goals|string|max:255',
            'goals.*.metric' => 'required_with:goals|string|max:100',
            'goals.*.target_value' => 'required_with:goals|numeric|min:0',
            'goals.*.achieved_value' => 'nullable|numeric|min:0',
            'goals.*.weight' => 'required_with:goals|numeric|min:0',
            'linked_incidents' => 'nullable|array',
            'linked_incidents.*' => 'array',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $isDraft = $this->input('submit_type') === 'draft';
            if ($isDraft) {
                return;
            }

            $answers = $this->input('answers', []);
            $justifications = $this->input('justifications', []);
            $linkedIncidents = $this->input('linked_incidents', []);

            foreach ($answers as $questionId => $score) {
                if (in_array((int)$score, [1, 2, 5])) {
                    // Se houver incidentes do diário vinculados, aceita como justificativa válida
                    $hasLinkedIncident = isset($linkedIncidents[$questionId]) && count($linkedIncidents[$questionId]) > 0;

                    if (!$hasLinkedIncident) {
                        // 1. Validar justificativa de texto obrigatória
                        $justification = $justifications[$questionId] ?? null;
                        if (empty($justification) || empty(trim($justification))) {
                            $validator->errors()->add(
                                "justifications.{$questionId}",
                                "A justificativa ou vinculação de incidente do diário é obrigatória para a nota {$score}."
                            );
                        }

                        // 2. Validar anexo obrigatório
                        $hasEvidenceFile = $this->hasFile("evidences.{$questionId}");
                        if (!$hasEvidenceFile) {
                            $validator->errors()->add(
                                "evidences.{$questionId}",
                                "O envio de evidência ou a vinculação de incidente é obrigatória para a nota {$score}."
                            );
                        } else {
                            $file = $this->file("evidences.{$questionId}");
                            if (!$file->isValid()) {
                                $validator->errors()->add(
                                    "evidences.{$questionId}",
                                    "O arquivo enviado não é válido."
                                );
                            } else {
                                $extension = strtolower($file->getClientOriginalExtension());
                                if (!in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
                                    $validator->errors()->add(
                                        "evidences.{$questionId}",
                                        "O anexo deve ser um arquivo do tipo: PDF, JPG, JPEG ou PNG."
                                    );
                                }
                                if ($file->getSize() > 5 * 1024 * 1024) { // 5MB
                                    $validator->errors()->add(
                                        "evidences.{$questionId}",
                                        "O arquivo de evidência não pode ultrapassar o limite de 5MB."
                                    );
                                }
                            }
                        }
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'evaluated_id.required' => 'O servidor avaliado deve ser informado.',
            'evaluated_id.exists' => 'O servidor informado não existe no sistema.',
            'cycle_id.required' => 'O ciclo de avaliação é obrigatório.',
            'cycle_id.exists' => 'O ciclo de avaliação informado é inválido.',
            'answers.required' => 'É obrigatório responder a todos os indicadores da avaliação.',
            'answers.*.between' => 'As notas atribuídas devem estar entre 1 (Insatisfatório) e 5 (Excelente).',
        ];
    }
}
