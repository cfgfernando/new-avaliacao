<?php

namespace App\Http\Requests;

use App\Models\Cell;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCellRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Cell::class);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'node_id'      => ['required', 'integer', 'exists:hierarchy_nodes,id'],
            'leader_id'    => ['nullable', 'integer', 'exists:users,id'],
            'meeting_day'  => [
                'required',
                Rule::in(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']),
            ],
            'meeting_time' => ['nullable', 'date_format:H:i'],
            'address'      => ['nullable', 'string', 'max:500'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city'         => ['nullable', 'string', 'max:100'],
            'active'       => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'O nome da célula é obrigatório.',
            'node_id.required'     => 'O nó hierárquico (Setor) é obrigatório.',
            'node_id.exists'       => 'O Setor informado não existe no sistema.',
            'leader_id.exists'     => 'O líder informado não está cadastrado.',
            'meeting_day.required' => 'O dia de reunião é obrigatório.',
            'meeting_day.in'       => 'Dia inválido. Escolha um dia da semana.',
            'meeting_time.date_format' => 'Horário inválido. Use o formato HH:MM.',
        ];
    }
}
