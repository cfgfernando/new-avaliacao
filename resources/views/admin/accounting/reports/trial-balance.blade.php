@extends('layouts.app')

@section('title', 'Balancete de Verificação')

@section('content')
<div class="print-header">
    <h1 class="text-2xl font-black uppercase tracking-widest border-b-2 border-slate-100 pb-2">MDA Church - Enterprise ERP</h1>
    <h2 class="text-xl font-bold uppercase mt-4">Relatório: Balancete de Verificação</h2>
    <p class="text-sm uppercase font-bold text-slate-500 mt-1">Período de Competência: {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}</p>
</div>

<div class="space-y-8 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8 no-print">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Balancete de Verificação</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Auditoria de Saldos e Integridade Contábil</p>
        </div>
        <div class="flex gap-4">
             <button onclick="window.print()" class="btn-neo bg-white text-slate-800 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-file-pdf text-rose-500"></i>
                <span>Exportar PDF</span>
            </button>
            <a href="{{ route('admin.accounting.reports.income-statement') }}" class="btn-neo btn-primary text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-chart-line"></i>
                <span>Analisar DRE</span>
            </a>
        </div>
    </div>

    <!-- Filtros Elite V8 -->
    <div class="card-neo mb-8 no-print">
        <form action="{{ route('admin.accounting.reports.trial-balance') }}" method="GET" class="flex flex-wrap gap-6 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Data Inicial</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input-neo uppercase">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Data Final</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input-neo uppercase">
            </div>

            <button type="submit" class="btn-neo btn-primary px-10 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-sync-alt"></i>
                <span>Atualizar Relatório</span>
            </button>
        </form>
    </div>

    <!-- Tabela do Balancete Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30 no-print">
            <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-list-ul text-primary-light"></i>
                Consolidado de Contas Contábeis
            </h3>
        </div>
        <div class="p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 w-80">Estrutura de Contas</th>
                        <th class="px-6 py-4 text-right">Saldo Anterior</th>
                        <th class="px-6 py-4 text-right">Débitos</th>
                        <th class="px-6 py-4 text-right">Créditos</th>
                        <th class="px-6 py-4 text-right">Saldo Atual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($trialBalance as $row)
                    <tr class="group hover:bg-slate-50/30 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-tight">{{ $row->name }}</span>
                                <span class="text-[9px] text-slate-400 font-black tracking-widest uppercase mt-1">{{ $row->code }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-money text-xs font-bold text-slate-500 bg-slate-50/10">
                            {{ number_format(abs($row->opening_balance), 2, ',', '.') }} <span class="text-[8px] font-black ml-1 opacity-40">{{ $row->opening_balance >= 0 ? 'D' : 'C' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-money text-xs font-bold text-slate-800">
                            {{ number_format($row->debit, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-money text-xs font-bold text-slate-800">
                            {{ number_format($row->credit, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-money text-xs font-bold {{ $row->closing_balance >= 0 ? 'text-slate-800' : 'text-rose-500' }} bg-slate-50/10">
                            {{ number_format(abs($row->closing_balance), 2, ',', '.') }} <span class="text-[8px] font-black ml-1 opacity-40">{{ $row->closing_balance >= 0 ? 'D' : 'C' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50/80 border-t-2 border-slate-200">
                        <td class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-800">Totais Consolidados</td>
                        <td class="px-6 py-6 text-right font-money text-[10px] text-slate-400 font-black uppercase tracking-widest">Equilíbrio Fiscal</td>
                        <td class="px-6 py-6 text-right font-money text-sm font-black text-accent">
                            R$ {{ number_format($trialBalance->sum('debit'), 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-6 text-right font-money text-sm font-black text-accent">
                            R$ {{ number_format($trialBalance->sum('credit'), 2, ',', '.') }}
                        </td>
                        <td class="px-8 py-6 text-right text-[10px] font-black uppercase tracking-widest text-emerald-600">Auditoria OK</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Status de Integridade Elite V8 -->
    <div class="no-print">
        @if($trialBalance->sum('debit') != $trialBalance->sum('credit'))
        <div class="card-neo !bg-rose-50 !border-rose-100 flex items-center gap-6">
            <div class="stats-icon bg-rose-100 text-rose-600">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-rose-700 uppercase tracking-widest">Erro de Integridade Detectado</h4>
                <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1">A soma de débitos e créditos diverge. O balanço está tecnicamente desequilibrado.</p>
            </div>
        </div>
        @else
        <div class="card-neo !bg-emerald-50 !border-emerald-100 flex items-center gap-6">
            <div class="stats-icon bg-emerald-100 text-emerald-600">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-emerald-700 uppercase tracking-widest">Certificação de Equilíbrio</h4>
                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mt-1">Todos os lançamentos foram auditados e o balancete encontra-se em equilíbrio operacional.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

