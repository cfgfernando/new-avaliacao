<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationCycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cutoff_score' => 'required|numeric|min:1|max:5',
            'status' => 'required|in:active,inactive,closed',
            'block_on_pad' => 'nullable|boolean',
            'global_goals' => 'nullable|array',
            'global_goals.*.description' => 'required|string|max:255',
            'global_goals.*.metric' => 'required|string|max:255',
            'global_goals.*.target_value' => 'required|numeric|min:0',
            'global_goals.*.weight' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do ciclo é obrigatório.',
            'start_date.required' => 'A data de início é obrigatória.',
            'end_date.required' => 'A data de fim é obrigatória.',
            'end_date.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
            'cutoff_score.required' => 'A nota de corte é obrigatória.',
            'cutoff_score.min' => 'A nota de corte mínima é 1.',
            'cutoff_score.max' => 'A nota de corte máxima é 5.',
            'status.required' => 'O status do ciclo é obrigatório.',
            'global_goals.*.description.required' => 'A descrição de cada meta global é obrigatória.',
            'global_goals.*.metric.required' => 'A métrica de cada meta global é obrigatória.',
            'global_goals.*.target_value.required' => 'O valor alvo de cada meta global é obrigatório.',
            'global_goals.*.target_value.numeric' => 'O valor alvo de cada meta global deve ser um número.',
            'global_goals.*.target_value.min' => 'O valor alvo de cada meta global deve ser pelo menos 0.',
            'global_goals.*.weight.required' => 'O peso de cada meta global é obrigatório.',
            'global_goals.*.weight.numeric' => 'O peso de cada meta global deve ser um número.',
            'global_goals.*.weight.min' => 'O peso de cada meta global deve ser pelo menos 0.',
            'global_goals.*.weight.max' => 'O peso de cada meta global não pode exceder 100.',
        ];
    }
}
