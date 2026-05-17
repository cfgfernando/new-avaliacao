<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeeklyReportRequest;
use App\Http\Requests\UpdateWeeklyReportRequest;
use App\Models\Cell;
use App\Models\WeeklyReport;
use App\Services\AccountingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WeeklyReportController extends Controller
{
    protected $accounting;

    public function __construct(AccountingService $accounting)
    {
        $this->accounting = $accounting;
        // $this->authorizeResource(WeeklyReport::class, 'report');
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $reports = WeeklyReport::query()
            ->with(['cell', 'submittedBy'])
            ->when(!$user->isAdmin() && !$user->isTreasurer(), function ($q) use ($user) {
                return $q->whereIn('cell_id', $user->accessibleCellIds());
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('meeting_date')
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    public function create(): View
    {
        $user = auth()->user();
        $cells = Cell::whereIn('id', $user->accessibleCellIds())->get();
        
        // Pré-seleciona a célula se o usuário for líder ou tiver apenas uma célula acessível
        $defaultCell = ($cells->count() === 1) ? $cells->first() : null;
        $defaultCellId = $defaultCell ? $defaultCell->id : null;
        
        return view('reports.create', compact('cells', 'defaultCellId', 'defaultCell'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cell_id'             => 'required|exists:cells,id',
            'meeting_date'        => 'required|date',
            'word_theme'          => 'nullable|string',
            'meeting_location'    => 'nullable|string',
            'committed_members'   => 'nullable|integer',
            'present_members'     => 'nullable|integer',
            'visitors'            => 'nullable|integer',
            'children'            => 'nullable|integer',
            'other_cell_visitors' => 'nullable|integer',
            'house_of_peace'      => 'nullable|integer',
            'mdas_done'           => 'nullable|integer',
            'kg_of_love'          => 'nullable|numeric',
            'reconciliations'     => 'nullable|integer',
            'conversions'         => 'nullable|integer',
            'offer_pix'           => 'nullable|numeric',
            'offer_cash'          => 'nullable|numeric',
            'notes'               => 'nullable|string',
            'present_member_ids'  => 'nullable|array',
            'visitor_names'       => 'nullable|array',
        ]);

        // Limpeza de nomes de visitantes vazios
        if (isset($data['visitor_names'])) {
            $data['visitor_names'] = array_values(array_filter($data['visitor_names']));
        }

        // Cálculo automático de membros presentes
        if (isset($data['present_member_ids'])) {
            $data['present_members'] = count($data['present_member_ids']);
        }

        $report = WeeklyReport::create($data + [
            'status'       => 'Draft',
            'submitted_by' => auth()->id(),
        ]);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Relatório salvo com sucesso.');
    }

    public function show(WeeklyReport $report): View
    {
        $report->load(['cell', 'submittedBy', 'conciliatedBy', 'journalEntries.lines.account']);
        return view('reports.show', compact('report'));
    }

    public function edit(WeeklyReport $report): View
    {
        $cells = Cell::whereIn('id', auth()->user()->accessibleCellIds())->get();
        return view('reports.edit', compact('report', 'cells'));
    }

    public function update(Request $request, WeeklyReport $report): RedirectResponse
    {
        $data = $request->validate([
            'cell_id'             => 'required|exists:cells,id',
            'meeting_date'        => 'required|date',
            'word_theme'          => 'nullable|string',
            'meeting_location'    => 'nullable|string',
            'committed_members'   => 'nullable|integer',
            'present_members'     => 'nullable|integer',
            'visitors'            => 'nullable|integer',
            'children'            => 'nullable|integer',
            'other_cell_visitors' => 'nullable|integer',
            'house_of_peace'      => 'nullable|integer',
            'mdas_done'           => 'nullable|integer',
            'kg_of_love'          => 'nullable|numeric',
            'reconciliations'     => 'nullable|integer',
            'conversions'         => 'nullable|integer',
            'offer_pix'           => 'nullable|numeric',
            'offer_cash'          => 'nullable|numeric',
            'notes'               => 'nullable|string',
            'present_member_ids'  => 'nullable|array',
            'visitor_names'       => 'nullable|array',
        ]);

        // Limpeza de nomes de visitantes vazios
        if (isset($data['visitor_names'])) {
            $data['visitor_names'] = array_values(array_filter($data['visitor_names']));
        }

        // Cálculo automático de membros presentes
        if (isset($data['present_member_ids'])) {
            $data['present_members'] = count($data['present_member_ids']);
        }

        $report->update($data);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Relatório atualizado com sucesso.');
    }

    public function submit(Request $request, WeeklyReport $report): RedirectResponse
    {
        $report->update([
            'status'       => 'Submitted',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Malote submetido com sucesso!');
    }

    public function conciliate(Request $request, WeeklyReport $report): RedirectResponse
    {
        try {
            DB::transaction(function () use ($report) {
                $report->update([
                    'status'         => 'Conciliated',
                    'conciliated_by' => auth()->id(),
                    'conciliated_at' => now(),
                ]);

                $this->accounting->createFromWeeklyReport($report, auth()->user());
            });

            return redirect()
                ->route('reports.index')
                ->with('success', 'Relatório conciliado com sucesso.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Falha na conciliação: ' . $e->getMessage());
        }
    }
}
