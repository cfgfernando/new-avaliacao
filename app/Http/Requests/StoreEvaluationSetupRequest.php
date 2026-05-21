<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationSetupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cycle_id' => 'required|exists:evaluation_cycles,id',
            'lotacao' => 'required|string',
            'categoria' => 'required|in:saude,guarda,educacao,geral',
            'evaluated_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $evaluated = \App\Models\User::find($value);
                    if ($evaluated) {
                        // 1. Trava de PAD
                        if ($evaluated->has_active_pad) {
                            $fail('Este servidor possui Processo Administrativo Disciplinar (PAD) ativo e sua avaliação está suspensa.');
                        }

                        // 2. Regra de Unicidade (Anti-Duplicidade) no ciclo
                        $cycleId = $this->input('cycle_id');
                        if ($cycleId) {
                            $exists = \App\Models\Evaluation::where('cycle_id', $cycleId)
                                ->where('evaluated_id', $value)
                                ->exists();
                            if ($exists) {
                                $fail('Este servidor já possui uma avaliação neste ciclo.');
                            }
                        }
                    }
                }
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cycle_id.required' => 'O ciclo avaliativo deve ser selecionado.',
            'cycle_id.exists' => 'O ciclo avaliativo selecionado é inválido.',
            'lotacao.required' => 'A secretaria/lotação deve ser selecionada.',
            'categoria.required' => 'A categoria da avaliação é obrigatória.',
            'categoria.in' => 'A categoria da avaliação selecionada é inválida.',
            'evaluated_id.required' => 'O servidor avaliado deve ser selecionado.',
            'evaluated_id.exists' => 'O servidor selecionado não foi encontrado no sistema.',
        ];
    }
}
