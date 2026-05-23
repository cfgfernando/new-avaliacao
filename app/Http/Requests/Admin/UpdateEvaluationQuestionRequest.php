<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_type' => 'required|in:geral,saude,guarda,educacao',
            'category' => 'required|in:assiduidade,disciplina,iniciativa,responsabilidade,cooperacao,qualidade,desenvolvimento_rh,avaliacao_usuario',
            'text' => 'required|string|max:500',
            'is_active' => 'boolean',
            'anchor_1' => 'nullable|string|max:1000',
            'anchor_2' => 'nullable|string|max:1000',
            'anchor_3' => 'nullable|string|max:1000',
            'anchor_4' => 'nullable|string|max:1000',
            'anchor_5' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'group_type.required' => 'O grupo funcional é obrigatório.',
            'group_type.in' => 'O grupo funcional selecionado é inválido.',
            'category.required' => 'A categoria é obrigatória.',
            'category.in' => 'A categoria selecionada é inválida.',
            'text.required' => 'O texto da pergunta é obrigatória.',
            'text.max' => 'O texto da pergunta não pode exceder 500 caracteres.',
        ];
    }
}