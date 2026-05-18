<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeeklyReportRequest;
use App\Http\Requests\UpdateWeeklyReportRequest;
use App\Models\Cell;
use App\Models\WeeklyReport;
use App\Models\HierarchyNode;
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
            ->when($request->cell_id, fn($q) => $q->where('cell_id', $request->cell_id))
            ->when($request->sector_id, function ($q) use ($request) {
                return $q->whereHas('cell', fn($cq) => $cq->where('node_id', $request->sector_id));
            })
            ->when($request->area_id, function ($q) use ($request) {
                return $q->whereHas('cell.node', fn($nq) => $nq->where('parent_id', $request->area_id));
            })
            ->when($request->search, function ($q) use ($request) {
                $term = $request->search;
                $q->whereHas('cell', fn($cq) => $cq->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('submittedBy', fn($uq) => $uq->where('name', 'like', "%{$term}%"));
            })
            ->when($request->date_from, fn($q) => $q->whereDate('meeting_date', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('meeting_date', '<=', $request->date_to))
            ->orderByDesc('meeting_date')
            ->paginate(15)
            ->withQueryString();

        // Lista de células, setores e áreas acessíveis para os filtros da visão de Admin/Supervisor
        $accessibleCellIds = $user->accessibleCellIds();
        $cellsList = Cell::whereIn('id', $accessibleCellIds)->active()->orderBy('name')->get();
        
        $accessibleSectorIds = $cellsList->pluck('node_id')->unique()->filter()->toArray();
        $sectorsList = HierarchyNode::ofType('Sector')->whereIn('id', $accessibleSectorIds)->active()->orderBy('name')->get();
        
        $accessibleAreaIds = $sectorsList->pluck('parent_id')->unique()->filter()->toArray();
        $areasList = HierarchyNode::ofType('Area')->whereIn('id', $accessibleAreaIds)->active()->orderBy('name')->get();

        // Pré-carrega dados da célula para o líder
        if ($user->isLeader()) {
            $user->load('cell.members');
        }

        return view('reports.index', compact('reports', 'cellsList', 'sectorsList', 'areasList'));
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

    public function pdf(WeeklyReport $report)
    {
        $report->load(['cell.leader', 'submittedBy', 'conciliatedBy']);

        $memberIds = is_array($report->present_member_ids) ? $report->present_member_ids : json_decode($report->present_member_ids, true) ?? [];
        $allCellMembers = $report->cell->members()->with('user')->get();
        $visitorsList = is_array($report->visitor_names) ? $report->visitor_names : json_decode($report->visitor_names, true) ?? [];

        return view('reports.pdf', [
            'report'          => $report,
            'allCellMembers'  => $allCellMembers,
            'memberIds'       => $memberIds,
            'visitorsList'    => $visitorsList,
            'generated_at'    => now()->format('d/m/Y H:i'),
        ]);
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

    public function monthlyPdf(Request $request)
    {
        $user = $request->user();

        $reports = WeeklyReport::query()
            ->with(['cell', 'submittedBy'])
            ->when(!$user->isAdmin() && !$user->isTreasurer(), function ($q) use ($user) {
                return $q->whereIn('cell_id', $user->accessibleCellIds());
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->cell_id, fn($q) => $q->where('cell_id', $request->cell_id))
            ->when($request->sector_id, function ($q) use ($request) {
                return $q->whereHas('cell', fn($cq) => $cq->where('node_id', $request->sector_id));
            })
            ->when($request->area_id, function ($q) use ($request) {
                return $q->whereHas('cell.node', fn($nq) => $nq->where('parent_id', $request->area_id));
            })
            ->when($request->search, function ($q) use ($request) {
                $term = $request->search;
                $q->whereHas('cell', fn($cq) => $cq->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('submittedBy', fn($uq) => $uq->where('name', 'like', "%{$term}%"));
            })
            ->when($request->date_from, fn($q) => $q->whereDate('meeting_date', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('meeting_date', '<=', $request->date_to))
            ->orderBy('meeting_date')
            ->get();

        // Calcular agregados
        $totalOffer = $reports->sum('total_offer');
        $offerPix = $reports->sum('offer_pix');
        $offerCash = $reports->sum('offer_cash');
        $totalPresence = $reports->sum('total_presence');
        $presentMembers = $reports->sum('present_members');
        $visitors = $reports->sum('visitors');
        $children = $reports->sum('children');
        
        $conversions = $reports->sum('conversions');
        $reconciliations = $reports->sum('reconciliations');
        $houseOfPeace = $reports->sum('house_of_peace');
        $mdasDone = $reports->sum('mdas_done');
        $kgOfLove = $reports->sum('kg_of_love');

        $reportsCount = $reports->count();
        $averagePresence = $reportsCount > 0 ? round($totalPresence / $reportsCount, 1) : 0;

        // Tenta pegar a célula selecionada de forma segura e em conformidade com RBAC
        $selectedCell = null;
        if ($request->cell_id) {
            $selectedCell = \App\Models\Cell::with('leader')->find($request->cell_id);
            // Segurança: Se não for administrador/tesoureiro, garante que tem acesso à célula selecionada
            if (!$user->isAdmin() && !$user->isTreasurer()) {
                if ($selectedCell && !$user->accessibleCellIds()->contains($selectedCell->id)) {
                    $selectedCell = null;
                }
            }
        }

        // Se nenhuma célula foi selecionada e o usuário tem acesso a exatamente uma célula (ex: Líder), atribui ela automaticamente
        if (!$selectedCell && !$user->isAdmin() && !$user->isTreasurer()) {
            $accessibleIds = $user->accessibleCellIds();
            if ($accessibleIds->count() === 1) {
                $selectedCell = \App\Models\Cell::with('leader')->find($accessibleIds->first());
            }
        }

        return view('reports.monthly-pdf', [
            'reports' => $reports,
            'totalOffer' => $totalOffer,
            'offerPix' => $offerPix,
            'offerCash' => $offerCash,
            'totalPresence' => $totalPresence,
            'presentMembers' => $presentMembers,
            'visitors' => $visitors,
            'children' => $children,
            'conversions' => $conversions,
            'reconciliations' => $reconciliations,
            'houseOfPeace' => $houseOfPeace,
            'mdasDone' => $mdasDone,
            'kgOfLove' => $kgOfLove,
            'reportsCount' => $reportsCount,
            'averagePresence' => $averagePresence,
            'selectedCell' => $selectedCell,
            'filters' => $request->only(['status', 'date_from', 'date_to', 'search']),
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);
    }
}
