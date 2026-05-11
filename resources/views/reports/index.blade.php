@extends('layouts.app')

@section('header_title', 'Relatórios Semanais')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-title tracking-tight">Relatórios de Célula</h1>
            <p class="text-slate-500 font-medium mt-1">Acompanhe a frequência, ofertas e o crescimento das células.</p>
        </div>
        <a href="{{ route('reports.create') }}" class="btn-primary">
            <i class="fas fa-file-circle-plus"></i>
            <span>Novo Relatório</span>
        </a>
    </div>

    <!-- Reports Grid/Table -->
    <div class="card-elite">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Célula / Data</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-center">Frequência</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Oferta</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-accent flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-black text-title">{{ $report->cell->name }}</div>
                                    <div class="text-[11px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-black text-title">{{ $report->member_count + $report->visitor_count }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pessoas</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="text-sm font-black text-emerald-600">
                                R$ {{ number_format($report->offering_amount, 2, ',', '.') }}
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Oferta Cell</div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusClasses = [
                                    'Draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'Submitted' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'Conciliated' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                ];
                                $statusClass = $statusClasses[$report->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('reports.show', $report) }}" class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-accent hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if($report->status === 'Draft')
                                <a href="{{ route('reports.edit', $report) }}" class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-pen text-xs"></i>
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
        <div class="px-8 py-6 bg-slate-50/30 border-t border-slate-100">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
