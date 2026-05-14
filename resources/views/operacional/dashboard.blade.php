@extends('layouts.app')

@section('title', 'Dashboard Operacional — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                Módulo Operacional
            </p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Painel de Supervisão</h1>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('reports.create') }}" class="btn-neo bg-primary text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2">
                <i class="fas fa-file-plus"></i> Novo Malote
            </a>
            <a href="{{ route('cells.index') }}" class="btn-neo bg-white text-slate-600 border border-slate-200 text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
                <i class="fas fa-sitemap"></i> Células
            </a>
            <a href="{{ route('operacional.radar') }}" class="btn-neo {{ $radarCount > 0 ? 'bg-rose-500 text-white' : 'bg-white text-slate-600 border border-slate-200' }} text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2">
                <i class="fas fa-radar {{ $radarCount > 0 ? 'animate-pulse' : '' }}"></i> 
                Radar 48h
                @if($radarCount > 0)
                    <span class="ml-1 bg-white/20 px-2 py-0.5 rounded-full">{{ $radarCount }}</span>
                @endif
            </a>
        </div>
    </div>

    {{-- ===== KPI CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Células Ativas --}}
        <div class="card-neo p-6 flex flex-col gap-3 group hover:scale-[1.02] transition-transform">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fas fa-sitemap text-sm"></i>
                </div>
                <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-full">Ativas</span>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($totalCells) }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Células Ativas</p>
            </div>
        </div>

        {{-- Membros --}}
        <div class="card-neo p-6 flex flex-col gap-3 group hover:scale-[1.02] transition-transform">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <span class="text-[8px] font-black text-blue-500 uppercase tracking-widest bg-blue-50 px-2 py-1 rounded-full">Ativos</span>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($totalMembers) }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Membros Ativos</p>
            </div>
        </div>

        {{-- Visitantes --}}
        <div class="card-neo p-6 flex flex-col gap-3 group hover:scale-[1.02] transition-transform">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                    <i class="fas fa-user-clock text-sm"></i>
                </div>
                @if($radarCount > 0)
                    <span class="text-[8px] font-black text-rose-500 uppercase tracking-widest bg-rose-50 px-2 py-1 rounded-full animate-pulse">
                        {{ $radarCount }} críticos
                    </span>
                @endif
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($totalVisitors) }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Visitantes Pendentes</p>
            </div>
        </div>

        {{-- Oferta do Mês --}}
        <div class="card-neo p-6 flex flex-col gap-3 group hover:scale-[1.02] transition-transform">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-hand-holding-heart text-sm"></i>
                </div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-full">{{ now()->format('M/Y') }}</span>
            </div>
            <div>
                <p class="text-2xl font-black text-emerald-600 tracking-tighter font-money">R$ {{ number_format($offerThisMonth, 2, ',', '.') }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Oferta no Mês</p>
            </div>
        </div>
    </div>

    {{-- ===== SECOND ROW: Alertas + Distribuição --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Radar Alert Card --}}
        @if($radarCount > 0)
        <div class="card-neo !p-0 overflow-hidden border-l-4 border-rose-400">
            <div class="bg-rose-50 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-500">
                    <i class="fas fa-circle-exclamation text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-rose-700 uppercase tracking-widest">Alerta Radar 48h</p>
                    <p class="text-[9px] text-rose-500 font-bold">Visitantes sem contato</p>
                </div>
                <span class="ml-auto text-2xl font-black text-rose-500">{{ $radarCount }}</span>
            </div>
            <div class="px-6 py-4">
                <p class="text-[10px] text-slate-500 mb-3">{{ $radarCount }} visitante(s) aguardam contato há mais de 48 horas.</p>
                <a href="{{ route('operacional.radar') }}" class="btn-neo bg-rose-500 text-white text-[9px] font-black uppercase tracking-widest px-4 py-2 flex items-center gap-2 w-full justify-center">
                    <i class="fas fa-arrow-right"></i> Ver Radar Agora
                </a>
            </div>
        </div>
        @endif

        {{-- Células sem Relatório --}}
        <div class="card-neo !p-0 overflow-hidden {{ $radarCount > 0 ? '' : 'lg:col-span-1' }}">
            <div class="bg-amber-50 px-6 py-4 flex items-center gap-3 border-b border-amber-100">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                    <i class="fas fa-calendar-times text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Sem Malote Esta Semana</p>
                    <p class="text-[9px] text-amber-500 font-bold">Células pendentes</p>
                </div>
                <span class="ml-auto text-2xl font-black text-amber-500">{{ $cellsMissingReport }}</span>
            </div>
            <div class="px-6 py-4">
                <p class="text-[10px] text-slate-500 mb-3">{{ $cellsMissingReport }} células ainda não submeteram o relatório desta semana.</p>
                <a href="{{ route('cells.index') }}" class="text-[9px] font-black text-primary-light uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                    Ver Células <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Distribuição por Nó --}}
        <div class="card-neo !p-0 overflow-hidden {{ $radarCount > 0 ? 'lg:col-span-1' : 'lg:col-span-2' }}">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Distribuição por Setor</p>
            </div>
            <div class="p-4 space-y-2">
                @foreach($cellsByNode as $node)
                @php $pct = $totalCells > 0 ? round($node->cells_count / $totalCells * 100) : 0; @endphp
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider w-28 truncate">{{ $node->name }}</span>
                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 w-8 text-right">{{ $node->cells_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== TABELAS: Relatórios ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Relatórios Aguardando Conciliação --}}
        <div class="card-neo !p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Aguardando Conciliação</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Malotes submetidos</p>
                </div>
                @if($pendingReports->count() > 0)
                    <span class="bg-amber-100 text-amber-700 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ $pendingReports->count() }} pendentes
                    </span>
                @endif
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($pendingReports as $report)
                <a href="{{ route('reports.show', $report) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/60 transition-colors group">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                        <i class="fas fa-clock text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-800 uppercase tracking-tight truncate">{{ $report->cell->name }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $report->report_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-black text-emerald-600">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</p>
                        <p class="text-[8px] text-slate-400 uppercase tracking-widest">Oferta</p>
                    </div>
                </a>
                @empty
                <div class="px-6 py-8 text-center">
                    <i class="fas fa-check-double text-emerald-400 text-2xl mb-2"></i>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tudo conciliado!</p>
                </div>
                @endforelse
            </div>
            @if($pendingReports->count() > 0)
            <div class="px-6 py-3 border-t border-slate-100">
                <a href="{{ route('reports.index', ['status' => 'Submitted']) }}" class="text-[9px] font-black text-primary-light uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                    Ver todos <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endif
        </div>

        {{-- Últimos Relatórios --}}
        <div class="card-neo !p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Atividade Recente</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Últimos malotes</p>
                </div>
                <a href="{{ route('reports.index') }}" class="text-[9px] font-black text-primary-light uppercase tracking-widest">Ver todos</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentReports as $report)
                <a href="{{ route('reports.show', $report) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/60 transition-colors group">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                        {{ $report->status === 'Conciliated' ? 'bg-emerald-50 text-emerald-500' : 
                           ($report->status === 'Submitted' ? 'bg-amber-50 text-amber-500' : 'bg-slate-50 text-slate-400') }}">
                        <i class="fas {{ $report->status === 'Conciliated' ? 'fa-check-circle' : ($report->status === 'Submitted' ? 'fa-clock' : 'fa-pen') }} text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-800 uppercase tracking-tight truncate">{{ $report->cell->name }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $report->report_date->format('d/m/Y') }}</p>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shrink-0
                        {{ $report->status === 'Conciliated' ? 'bg-emerald-50 text-emerald-600' : 
                           ($report->status === 'Submitted' ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-500') }}">
                        {{ $report->status_label }}
                    </span>
                </a>
                @empty
                <div class="px-6 py-8 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum relatório ainda.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
