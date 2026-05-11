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
            'report_date' => [
                'sometimes',
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('weekly_reports', 'report_date')
                    ->where('cell_id', $report->cell_id)
                    ->ignore($report->id),
            ],
            'present_members' => ['sometimes', 'required', 'integer', 'min:0'],
            'visitors'        => ['sometimes', 'required', 'integer', 'min:0'],
            'children'        => ['sometimes', 'required', 'integer', 'min:0'],
            'mda_count'       => ['sometimes', 'required', 'integer', 'min:0'],
            'conversions'     => ['sometimes', 'required', 'integer', 'min:0'],
            'kg_social'       => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'offer_pix'       => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'offer_cash'      => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'observations'    => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }
}
