<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $member = $this->route('member');

        return $this->user()->can('update', $member);
    }

    public function rules(): array
    {
        $memberId = $this->route('member')?->id;

        return [
            'mentor_id'      => [
                'sometimes',
                'nullable',
                'integer',
                'exists:users,id',
                Rule::notIn([$this->route('member')?->user_id]), // Não pode ser mentor de si mesmo
            ],
            'conversion_date' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'baptism_date'    => [
                'sometimes',
                'nullable',
                'date',
                'before_or_equal:today',
                'after_or_equal:conversion_date',
            ],
            'phone'          => ['sometimes', 'nullable', 'string', 'max:20'],
            'cpf'            => [
                'sometimes',
                'nullable',
                'string',
                'size:14',
                Rule::unique('members', 'cpf')->ignore($memberId), // Ignora o próprio registro
            ],
            'birth_date'     => ['sometimes', 'nullable', 'date', 'before:today'],
            'gender'         => ['sometimes', 'nullable', Rule::in(['Male', 'Female'])],
            'marital_status' => ['sometimes', 'nullable', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
            'status'         => ['sometimes', 'required', Rule::in(['Active', 'Inactive', 'Transferred', 'Deceased'])],
            'address'        => ['sometimes', 'nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'mentor_id.not_in'       => 'O membro não pode ser seu próprio mentor.',
            'baptism_date.after_or_equal' => 'A data de batismo não pode ser anterior à conversão.',
            'cpf.unique'             => 'Este CPF já está em uso por outro membro.',
            'cpf.size'               => 'CPF deve ter 14 caracteres (formato: 000.000.000-00).',
            'status.in'              => 'Status inválido para um membro.',
        ];
    }
}
