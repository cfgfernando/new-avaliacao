@extends('layouts.app')

@section('header_title', 'Relatórios Semanais')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
            <h1 class="text-4xl font-black text-title leading-none tracking-tighter">RELATÓRIOS <br><span class="text-accent">DE CÉLULA.</span></h1>
            <p class="text-neutral-500 font-medium mt-4">Gestão e acompanhamento de mordomia financeira.</p>
        </div>
        <a href="{{ route('reports.create') }}" class="btn-neo">
            <i class="fas fa-file-circle-plus"></i>
            <span>Gerar Malote</span>
        </a>
    </div>

    <!-- Reports Grid/Table -->
    <div class="card-neo bg-primary-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/50 border-b border-slate-200">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Célula / Data</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-center">Frequência</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Oferta</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50 hover:shadow-sm transition-all duration-300 group">
                        <td class="px-12 py-8">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-accent font-black shadow-inner shadow-black/20 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-black text-primary-dark uppercase tracking-widest group-hover:text-accent transition-colors">{{ $report->cell->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 tracking-widest">{{ \Carbon\Carbon::parse($report->report_date)->format('D, d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-12 py-8 text-center">
                            <div class="flex flex-col items-center group-hover:translate-y-[-2px] transition-transform">
                                <span class="text-lg font-black text-primary-dark leading-none">{{ $report->member_count + $report->visitor_count }}</span>
                                <span class="text-[8px] font-black text-accent uppercase tracking-[0.3em] mt-2">Pessoas</span>
                            </div>
                        </td>
                        <td class="px-12 py-8 text-right">
                            <div class="text-2xl font-black text-emerald-600 leading-none group-hover:scale-105 transition-transform origin-right">
                                {{ number_format($report->offering_amount, 2, ',', '.') }}
                            </div>
                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-2">BRL CONSUR</div>
                        </td>
                        <td class="px-12 py-8">
                            <span class="px-4 py-2 border rounded-lg text-[9px] font-black uppercase tracking-[0.2em] {{ $report->status == 'Conciliated' ? 'border-emerald-500/30 text-emerald-600 bg-emerald-50' : 'border-slate-200 text-slate-500 bg-slate-50' }} shadow-sm">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="px-12 py-8 text-right">
                            <div class="flex items-center justify-end gap-6 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('reports.show', $report) }}" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-accent hover:border-accent hover:shadow-md hover:shadow-accent/10 transition-all group/btn">
                                    <i class="fas fa-eye group-hover/btn:scale-110 transition-transform"></i>
                                </a>
                                @if($report->status === 'Draft')
                                <a href="{{ route('reports.edit', $report) }}" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary hover:shadow-md transition-all group/btn">
                                    <i class="fas fa-pen group-hover/btn:scale-110 transition-transform"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <i class="fas fa-file-circle-exclamation text-4xl"></i>
                                </div>
                                <p class="text-slate-400 font-bold">Nenhum relatório encontrado para as células acessíveis.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-200/60 rounded-b-2xl">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
