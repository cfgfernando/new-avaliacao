<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Member::class);
    }

    public function rules(): array
    {
        return [
            // Vínculo com usuário do sistema
            'user_id'        => [
                'required',
                'integer',
                'exists:users,id',
                'unique:members,user_id', // Cada usuário só pode ter um perfil de membro
            ],

            // Discipulado MDA
            'mentor_id'      => [
                'nullable',
                'integer',
                'exists:users,id',
                'different:user_id', // Não pode ser mentor de si mesmo
            ],

            // Dados espirituais
            'conversion_date' => ['nullable', 'date', 'before_or_equal:today'],
            'baptism_date'    => [
                'nullable',
                'date',
                'before_or_equal:today',
                'after_or_equal:conversion_date', // Batismo não pode ser antes da conversão
            ],

            // Dados pessoais
            'phone'          => ['nullable', 'string', 'max:20'],
            'cpf'            => [
                'nullable',
                'string',
                'size:14', // Formato: 000.000.000-00
                'unique:members,cpf',
            ],
            'birth_date'     => ['nullable', 'date', 'before:today'],
            'gender'         => ['nullable', Rule::in(['Male', 'Female'])],
            'marital_status' => ['nullable', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
            'status'         => ['sometimes', Rule::in(['Active', 'Inactive', 'Transferred', 'Deceased'])],
            'address'        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'       => 'O usuário vinculado é obrigatório.',
            'user_id.exists'         => 'O usuário informado não existe.',
            'user_id.unique'         => 'Este usuário já possui um perfil de membro.',
            'mentor_id.exists'       => 'O mentor informado não está cadastrado.',
            'mentor_id.different'    => 'O membro não pode ser seu próprio mentor.',
            'baptism_date.after_or_equal' => 'A data de batismo não pode ser anterior à data de conversão.',
            'cpf.size'               => 'O CPF deve ter 14 caracteres (formato: 000.000.000-00).',
            'cpf.unique'             => 'Este CPF já está cadastrado.',
            'birth_date.before'      => 'A data de nascimento deve ser no passado.',
            'gender.in'              => 'Gênero inválido.',
            'marital_status.in'      => 'Estado civil inválido.',
            'status.in'              => 'Status inválido.',
        ];
    }
}
