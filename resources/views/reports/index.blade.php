@extends('layouts.app')

@section('title', 'Relatórios Semanais — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Relatórios Semanais</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Gestão de Malotes por Célula</p>
        </div>
        <a href="{{ route('reports.create') }}" hx-boost="false" class="btn-neo bg-primary text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 self-start md:self-auto">
            <i class="fas fa-file-circle-plus"></i> Gerar Malote
        </a>
    </div>

    {{-- FILTROS --}}
    <div class="card-neo p-6">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="input-neo py-2.5">
                    <option value="">Todos os Status</option>
                    <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Rascunho</option>
                    <option value="Submitted" {{ request('status') === 'Submitted' ? 'selected' : '' }}>Submetido</option>
                    <option value="Conciliated" {{ request('status') === 'Conciliated' ? 'selected' : '' }}>Conciliado</option>
                </select>
            </div>
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Data Início</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-neo py-2.5">
            </div>
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Data Fim</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-neo py-2.5">
            </div>
            <button type="submit" class="btn-neo bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            @if(request()->anyFilled(['status', 'date_from', 'date_to']))
            <a href="{{ route('reports.index') }}" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                <i class="fas fa-times"></i> Limpar
            </a>
            @endif
        </form>
    </div>

    {{-- TABELA --}}
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
            <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">
                {{ $reports->total() }} malote(s) encontrado(s)
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-slate-50/30">
                        <th class="px-6 py-4 text-left">Célula / Data</th>
                        <th class="px-6 py-4 text-center">Frequência</th>
                        <th class="px-6 py-4 text-right">Oferta Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fas fa-calendar-week text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ $report->cell->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                                        {{ $report->meeting_date?->format('D, d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-lg font-black text-slate-800 tracking-tighter">
                                    {{ $report->total_presence }}
                                </span>
                                <span class="text-[8px] font-black text-primary-light uppercase tracking-widest">
                                    {{ $report->present_members }}m + {{ $report->visitors }}v + {{ $report->children }}c + {{ $report->other_cell_visitors }}o
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div>
                                <p class="text-sm font-black text-emerald-600 tracking-tighter">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</p>
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">
                                    PIX: {{ number_format($report->offer_pix, 2, ',', '.') }} | Cash: {{ number_format($report->offer_cash, 2, ',', '.') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl
                                {{ $report->status === 'Conciliated' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 
                                   ($report->status === 'Submitted' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 
                                   'bg-slate-50 text-slate-600 border border-slate-200') }}">
                                {{ $report->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 transition-all">
                                {{-- Ver Detalhes --}}
                                <a href="{{ route('reports.show', $report) }}" hx-boost="false" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-primary hover:border-primary hover:bg-white transition-all shadow-sm" title="Ver Detalhes">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Editar (Apenas Draft) --}}
                                @if($report->status === 'Draft')
                                <a href="{{ route('reports.edit', $report) }}" hx-boost="false" class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Editar">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </a>

                                {{-- Enviar Malote (Draft -> Submitted) --}}
                                <form action="{{ route('reports.submit', $report) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm" title="Enviar Malote">
                                        <i class="fas fa-paper-plane text-[10px]"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Conciliar (Apenas Submitted e Admin/Tesoureiro) --}}
                                @if($report->status === 'Submitted' && (auth()->user()->isAdmin() || auth()->user()->isTreasurer()))
                                <form action="{{ route('reports.conciliate', $report) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Conciliar">
                                        <i class="fas fa-check-double text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <i class="fas fa-file-circle-exclamation text-3xl"></i>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum relatório encontrado.</p>
                                <a href="{{ route('reports.create') }}" class="text-[9px] font-black text-primary-light uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                                    Criar o primeiro <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $reports->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
