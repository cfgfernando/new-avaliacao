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
        $this->authorizeResource(WeeklyReport::class, 'report');
    }

    /**
     * Listagem com escopo RBAC.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $reports = WeeklyReport::query()
            ->with(['cell', 'submittedBy'])
            ->when(!$user->isAdmin() && !$user->isTreasurer(), function ($q) use ($user) {
                return $q->whereIn('cell_id', $user->accessibleCellIds());
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('report_date')
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    public function create(): View
    {
        $user = auth()->user();
        $cells = Cell::whereIn('id', $user->accessibleCellIds())->get();
        
        return view('reports.create', compact('cells'));
    }

    /**
     * Salva o relatório como Rascunho (Draft).
     */
    public function store(StoreWeeklyReportRequest $request): RedirectResponse
    {
        $report = WeeklyReport::create($request->validated() + [
            'status' => 'Draft'
        ]);

        return redirect()
            ->route('reports.edit', $report)
            ->with('success', 'Relatório salvo como rascunho.');
    }

    public function show(WeeklyReport $report): View
    {
        $report->load(['cell', 'submittedBy', 'conciliatedBy', 'journalEntries.lines.account']);
        return view('reports.show', compact('report'));
    }

    public function edit(WeeklyReport $report): View
    {
        // A Policy já garante que só edita se for Draft
        $cells = Cell::whereIn('id', auth()->user()->accessibleCellIds())->get();
        return view('reports.edit', compact('report', 'cells'));
    }

    public function update(UpdateWeeklyReportRequest $request, WeeklyReport $report): RedirectResponse
    {
        $report->update($request->validated());

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Relatório atualizado com sucesso.');
    }

    /**
     * SUBMISSÃO DO MALOTE (Lock)
     * Altera de Draft -> Submitted. 
     */
    public function submit(Request $request, WeeklyReport $report): RedirectResponse
    {
        $this->authorize('submit', $report);

        $report->update([
            'status'       => 'Submitted',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Malote submetido com sucesso! O relatório agora está bloqueado para edição.');
    }

    /**
     * CONCILIAÇÃO (Transação Atômica)
     * Altera de Submitted -> Conciliated e gera contabilidade.
     */
    public function conciliate(Request $request, WeeklyReport $report): RedirectResponse
    {
        $this->authorize('conciliate', $report);

        try {
            // [TRANSACAO ATOMICA] Tudo ou nada
            DB::transaction(function () use ($report) {
                // 1. Atualiza status do relatório
                $report->update([
                    'status'         => 'Conciliated',
                    'conciliated_by' => auth()->id(),
                    'conciliated_at' => now(),
                ]);

                // 2. Gera lançamento contábil via Service
                $this->accounting->createFromWeeklyReport($report, auth()->user());
            });

            return redirect()
                ->route('reports.show', $report)
                ->with('success', 'Relatório conciliado e integrado à contabilidade com sucesso.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Falha na conciliação: ' . $e->getMessage());
        }
    }
}
