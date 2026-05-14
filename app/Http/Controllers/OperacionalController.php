<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\HierarchyNode;
use App\Models\Member;
use App\Models\Visitor;
use App\Models\WeeklyReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OperacionalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        // KPIs principais
        $totalCells    = Cell::where('active', true)->count();
        $totalMembers  = Member::where('status', 'Active')->count();
        $totalVisitors = Visitor::whereNotIn('status', ['Converted', 'Inactive'])->count();

        // Visitantes sem contato nas últimas 48h (Radar)
        $radarCount = Visitor::whereNotIn('status', ['Converted', 'Inactive'])
            ->where(function ($q) {
                $q->whereNull('last_contact_at')
                  ->orWhere('last_contact_at', '<', now()->subHours(48));
            })->count();

        // Oferta do mês corrente
        $offerThisMonth = WeeklyReport::whereMonth('report_date', now()->month)
            ->whereYear('report_date', now()->year)
            ->sum(\Illuminate\Support\Facades\DB::raw('offer_pix + offer_cash'));

        // Relatórios aguardando conciliação
        $pendingReports = WeeklyReport::with(['cell', 'submittedBy'])
            ->where('status', 'Submitted')
            ->orderByDesc('report_date')
            ->take(8)
            ->get();

        // Relatórios recentes (últimos 10)
        $recentReports = WeeklyReport::with(['cell', 'submittedBy'])
            ->orderByDesc('report_date')
            ->take(10)
            ->get();

        // Distribuição de células por nó hierárquico (top 8)
        $cellsByNode = HierarchyNode::withCount('cells')
            ->having('cells_count', '>', 0)
            ->orderByDesc('cells_count')
            ->take(8)
            ->get();

        // Células sem relatório nesta semana
        $startOfWeek = now()->startOfWeek();
        $cellsWithReportThisWeek = WeeklyReport::where('report_date', '>=', $startOfWeek)
            ->pluck('cell_id')
            ->unique();
        $cellsMissingReport = Cell::where('active', true)
            ->whereNotIn('id', $cellsWithReportThisWeek)
            ->count();

        return view('operacional.dashboard', compact(
            'totalCells',
            'totalMembers',
            'totalVisitors',
            'radarCount',
            'offerThisMonth',
            'pendingReports',
            'recentReports',
            'cellsByNode',
            'cellsMissingReport'
        ));
    }

    public function radar(): View
    {
        $critical = Visitor::with('assignedCell')
            ->whereNotIn('status', ['Converted', 'Inactive'])
            ->where(function ($q) {
                $q->whereNull('last_contact_at')
                  ->orWhere('last_contact_at', '<', now()->subHours(72));
            })
            ->orderBy('last_contact_at')
            ->get();

        $warning = Visitor::with('assignedCell')
            ->whereNotIn('status', ['Converted', 'Inactive'])
            ->where('last_contact_at', '>=', now()->subHours(72))
            ->where('last_contact_at', '<', now()->subHours(48))
            ->orderBy('last_contact_at')
            ->get();

        return view('operacional.radar', compact('critical', 'warning'));
    }

    public function registerContact(Request $request, Visitor $visitor): JsonResponse
    {
        $request->validate([
            'status' => 'required|string',
            'notes'  => 'nullable|string|max:500',
        ]);

        $visitor->update([
            'last_contact_at' => now(),
            'contacted_by'    => auth()->id(),
            'notes'           => $request->notes,
            'status'          => $request->status === 'not_interested' ? 'Inactive' : $visitor->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Contato registrado com sucesso!']);
    }
}

