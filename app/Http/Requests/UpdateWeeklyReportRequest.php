<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWeeklyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $report = $this->route('report');
        return $this->user()->can('update', $report);
    }

    public function rules(): array
    {
        $report = $this->route('report');

        return [
            'meeting_date'        => [
                'sometimes',
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('weekly_reports', 'meeting_date')
                    ->where('cell_id', $report->cell_id)
                    ->ignore($report->id)
                    ->whereNull('deleted_at'),
            ],
            'word_theme'          => ['sometimes', 'nullable', 'string', 'max:255'],
            'meeting_location'    => ['sometimes', 'nullable', 'string', 'max:255'],
            
            // Frequência
            'present_member_ids'  => ['sometimes', 'nullable', 'array'],
            'present_members'     => ['sometimes', 'required', 'integer', 'min:0'],
            'visitors'            => ['sometimes', 'required', 'integer', 'min:0'],
            'visitor_names'       => ['sometimes', 'nullable', 'array'],
            'children'            => ['sometimes', 'required', 'integer', 'min:0'],
            'other_cell_visitors' => ['sometimes', 'required', 'integer', 'min:0'],
            'committed_members'   => ['sometimes', 'required', 'integer', 'min:0'],

            // Impacto
            'house_of_peace'      => ['sometimes', 'required', 'integer', 'min:0'],
            'mdas_done'           => ['sometimes', 'required', 'integer', 'min:0'],
            'kg_of_love'          => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'conversions'         => ['sometimes', 'required', 'integer', 'min:0'],
            'reconciliations'     => ['sometimes', 'required', 'integer', 'min:0'],

            // Financeiro
            'offer_pix'           => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'offer_cash'          => ['sometimes', 'nullable', 'numeric', 'min:0'],

            // Notas
            'notes'               => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('offer_pix')) $this->merge(['offer_pix' => $this->offer_pix ?? 0]);
        if ($this->has('offer_cash')) $this->merge(['offer_cash' => $this->offer_cash ?? 0]);
        if ($this->has('kg_of_love')) $this->merge(['kg_of_love' => $this->kg_of_love ?? 0]);
    }
}
