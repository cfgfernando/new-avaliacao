<?php

namespace App\Http\Requests;

use App\Models\WeeklyReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWeeklyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware handles it, but true is safer for now if permissions are being synced
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            // ── Identificação ────────────────────────────────────────
            'cell_id'             => [
                'required',
                'integer',
                'exists:cells,id',
                Rule::in($user->accessibleCellIds()->toArray()),
            ],
            'meeting_date'        => [
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('weekly_reports', 'meeting_date')
                    ->where('cell_id', $this->input('cell_id'))
                    ->whereNull('deleted_at'),
            ],
            'word_theme'          => ['nullable', 'string', 'max:255'],
            'meeting_location'    => ['nullable', 'string', 'max:255'],

            // ── Frequência ──────────────────────────────────────────
            'present_member_ids'  => ['nullable', 'array'],
            'present_member_ids.*'=> ['integer', 'exists:members,id'],
            'present_members'     => ['required', 'integer', 'min:0'],
            'visitors'            => ['required', 'integer', 'min:0'],
            'visitor_names'       => ['nullable', 'array'],
            'children'            => ['required', 'integer', 'min:0'],
            'other_cell_visitors' => ['required', 'integer', 'min:0'],
            'committed_members'   => ['required', 'integer', 'min:0'],

            // ── Impacto Pastoral ────────────────────────────────────
            'house_of_peace'      => ['required', 'integer', 'min:0'],
            'mdas_done'           => ['required', 'integer', 'min:0'],
            'kg_of_love'          => ['nullable', 'numeric', 'min:0'],
            'conversions'         => ['required', 'integer', 'min:0'],
            'reconciliations'     => ['required', 'integer', 'min:0'],

            // ── Financeiro ──────────────────────────────────────────
            'offer_pix'           => ['nullable', 'numeric', 'min:0'],
            'offer_cash'          => ['nullable', 'numeric', 'min:0'],

            // ── Notas ───────────────────────────────────────────────
            'notes'               => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'cell_id.required'           => 'A célula é obrigatória.',
            'cell_id.in'                 => 'Você não tem permissão para esta célula.',
            'meeting_date.required'      => 'A data é obrigatória.',
            'meeting_date.unique'        => 'Já existe um relatório para esta célula nesta data.',
            'meeting_date.before_or_equal'=> 'A data não pode ser futura.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'offer_pix'  => $this->offer_pix  ?? 0,
            'offer_cash' => $this->offer_cash ?? 0,
            'kg_of_love' => $this->kg_of_love ?? 0,
            'present_member_ids' => $this->present_member_ids ?? [],
            'visitor_names'      => $this->visitor_names ?? [],
        ]);
    }
}
