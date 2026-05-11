<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCellRequest extends FormRequest
{
    public function authorize(): bool
    {
        $cell = $this->route('cell');

        return $this->user()->can('update', $cell);
    }

    public function rules(): array
    {
        return [
            'name'         => ['sometimes', 'required', 'string', 'max:150'],
            'node_id'      => ['sometimes', 'required', 'integer', 'exists:hierarchy_nodes,id'],
            'leader_id'    => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'meeting_day'  => [
                'sometimes',
                'required',
                Rule::in(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']),
            ],
            'meeting_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'address'      => ['sometimes', 'nullable', 'string', 'max:500'],
            'neighborhood' => ['sometimes', 'nullable', 'string', 'max:100'],
            'city'         => ['sometimes', 'nullable', 'string', 'max:100'],
            'active'       => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'O nome da célula é obrigatório.',
            'node_id.exists'       => 'O Setor informado não existe.',
            'leader_id.exists'     => 'O líder informado não está cadastrado.',
            'meeting_day.in'       => 'Dia inválido.',
            'meeting_time.date_format' => 'Horário inválido. Use o formato HH:MM.',
        ];
    }
}
