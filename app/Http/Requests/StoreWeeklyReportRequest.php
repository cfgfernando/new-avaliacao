<?php

namespace App\Http\Requests;

use App\Models\WeeklyReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWeeklyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', WeeklyReport::class);
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            // ── Identificação ────────────────────────────────────────
            'cell_id'     => [
                'required',
                'integer',
                'exists:cells,id',
                // Líder só pode criar relatório para a própria célula
                Rule::in($user->accessibleCellIds()->toArray()),
            ],
            'report_date' => [
                'required',
                'date',
                'before_or_equal:today',
                // Impede relatório duplicado para mesma célula na mesma data
                Rule::unique('weekly_reports', 'report_date')
                    ->where('cell_id', $this->input('cell_id')),
            ],

            // ── Etapa 1: Frequência ──────────────────────────────────
            'present_members' => ['required', 'integer', 'min:0', 'max:9999'],
            'visitors'        => ['required', 'integer', 'min:0', 'max:9999'],
            'children'        => ['required', 'integer', 'min:0', 'max:9999'],

            // ── Etapa 2: Espiritual / MDA ────────────────────────────
            'mda_count'       => ['required', 'integer', 'min:0', 'max:9999'],
            'conversions'     => ['required', 'integer', 'min:0', 'max:9999'],
            'kg_social'       => ['nullable', 'numeric', 'min:0', 'max:99999.99'],

            // ── Etapa 3: Financeiro ──────────────────────────────────
            'offer_pix'       => ['nullable', 'numeric', 'min:0'],
            'offer_cash'      => ['nullable', 'numeric', 'min:0'],

            // ── Observações ──────────────────────────────────────────
            'observations'    => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'cell_id.required'       => 'A célula é obrigatória.',
            'cell_id.in'             => 'Você não tem permissão para criar relatório nesta célula.',
            'report_date.required'   => 'A data do culto é obrigatória.',
            'report_date.unique'     => 'Já existe um relatório para esta célula nesta data.',
            'report_date.before_or_equal' => 'A data do relatório não pode ser futura.',
            'present_members.required' => 'Informe a quantidade de membros presentes.',
            'present_members.min'    => 'O valor não pode ser negativo.',
            'mda_count.required'     => 'Informe os participantes no ciclo MDA.',
            'conversions.required'   => 'Informe o número de conversões (pode ser 0).',
        ];
    }

    /**
     * Valores padrão para campos opcionais.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'offer_pix'  => $this->offer_pix  ?? 0,
            'offer_cash' => $this->offer_cash ?? 0,
            'kg_social'  => $this->kg_social  ?? 0,
        ]);
    }
}
