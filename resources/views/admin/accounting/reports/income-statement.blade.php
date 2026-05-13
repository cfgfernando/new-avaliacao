@extends('layouts.app')

@section('title', 'DRE - Demonstração do Resultado')

@section('content')
<div class="print-header">
    <h1 class="text-2xl font-black uppercase tracking-widest border-b-2 border-slate-100 pb-2">MDA Church - Enterprise ERP</h1>
    <h2 class="text-xl font-bold uppercase mt-4">Relatório: Demonstração do Resultado (DRE)</h2>
    <p class="text-sm uppercase font-bold text-slate-500 mt-1">Período Analítico: {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}</p>
</div>

<div class="space-y-8 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8 no-print">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Demonstração de Resultado</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Análise de Performance Operacional e Sobra Líquida</p>
        </div>
        <div class="flex gap-4">
             <button onclick="window.print()" class="btn-neo bg-white text-slate-800 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-file-pdf text-rose-500"></i>
                <span>Exportar PDF</span>
            </button>
        </div>
    </div>

    <!-- Filtros Elite V8 -->
    <div class="card-neo mb-8 no-print">
        <form action="{{ route('admin.accounting.reports.income-statement') }}" method="GET" class="flex flex-wrap gap-6 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Início do Período</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input-neo uppercase">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Fim do Período</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input-neo uppercase">
            </div>

            <button type="submit" class="btn-neo btn-primary px-10 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-sync-alt"></i>
                <span>Gerar Análise</span>
            </button>
        </form>
    </div>

    <!-- Resumo de Cards Elite V8 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Receitas -->
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-emerald-500/20 group-hover:bg-emerald-500 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Receitas Brutas (+)</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money tracking-tight">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-emerald-500 mt-2 uppercase tracking-widest">Total de Entradas</p>
                </div>
                <div class="stats-icon bg-emerald-50 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white">
                    <i class="fas fa-arrow-trend-up"></i>
                </div>
            </div>
        </div>

        <!-- Despesas -->
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-rose-500/20 group-hover:bg-rose-500 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Despesas Operacionais (-)</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money tracking-tight">R$ {{ number_format($totalExpense, 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-rose-500 mt-2 uppercase tracking-widest">Saídas e Custos</p>
                </div>
                <div class="stats-icon bg-rose-50 text-rose-500 group-hover:bg-rose-500 group-hover:text-white">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
            </div>
        </div>

        <!-- Resultado -->
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full {{ $netResult >= 0 ? 'bg-emerald-500/20' : 'bg-rose-500/20' }} group-hover:{{ $netResult >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Resultado Líquido (=)</p>
                    <h3 class="text-2xl font-black {{ $netResult >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-money tracking-tight">R$ {{ number_format($netResult, 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold {{ $netResult >= 0 ? 'text-emerald-500' : 'text-rose-500' }} mt-2 uppercase tracking-widest">{{ $netResult >= 0 ? 'Superávit Consolidado' : 'Déficit Operacional' }}</p>
                </div>
                <div class="stats-icon {{ $netResult >= 0 ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }} group-hover:{{ $netResult >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} group-hover:text-white transition-all">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Estrutura da DRE Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30 no-print">
            <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-microscope text-primary-light"></i>
                Análise Vertical e Horizontal de Resultados
            </h3>
        </div>
        <div class="p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 w-1/2">Grupo de Contas Contábeis</th>
                        <th class="px-6 py-4 text-right w-56">Valor Período (R$)</th>
                        <th class="px-6 py-4 text-right w-40">Part. (%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <!-- RECEITAS -->
                    <tr class="bg-slate-50/50">
                        <td colspan="3" class="px-6 py-3 text-[10px] font-black text-emerald-600 uppercase tracking-widest">1. RECEITAS E ENTRADAS OPERACIONAIS</td>
                    </tr>
                    @foreach($revenues as $rev)
                    <tr class="group hover:bg-slate-50/30 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <span class="text-[9px] text-slate-400 font-black font-money tracking-widest">{{ $rev->code }}</span>
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-tight">{{ $rev->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-money text-sm font-bold text-slate-800">
                            {{ number_format($rev->total, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[10px] font-black text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                {{ $totalRevenue > 0 ? number_format(($rev->total / $totalRevenue) * 100, 1) : 0 }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-emerald-50/30 border-y border-emerald-100">
                        <td class="px-6 py-5 text-[11px] font-black text-emerald-700 uppercase tracking-widest">Subtotal de Receitas Brutas</td>
                        <td class="px-6 py-5 text-right font-money text-base font-black text-emerald-700">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</td>
                        <td class="px-6 py-5 text-right text-[10px] font-black text-emerald-600 tracking-widest">100.0%</td>
                    </tr>

                    <!-- ESPAÇADOR -->
                    <tr class="h-4 bg-white"><td colspan="3"></td></tr>

                    <!-- DESPESAS -->
                    <tr class="bg-slate-50/50">
                        <td colspan="3" class="px-6 py-3 text-[10px] font-black text-rose-600 uppercase tracking-widest">2. DESPESAS E CUSTOS OPERACIONAIS</td>
                    </tr>
                    @foreach($expenses as $exp)
                    <tr class="group hover:bg-slate-50/30 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <span class="text-[9px] text-slate-400 font-black font-money tracking-widest">{{ $exp->code }}</span>
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-tight">{{ $exp->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-money text-sm font-bold text-rose-600">
                            {{ number_format($exp->total, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[10px] font-black text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                {{ $totalExpense > 0 ? number_format(($exp->total / $totalExpense) * 100, 1) : 0 }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-rose-50/30 border-y border-rose-100">
                        <td class="px-6 py-5 text-[11px] font-black text-rose-700 uppercase tracking-widest">Subtotal de Despesas Operacionais</td>
                        <td class="px-6 py-5 text-right font-money text-base font-black text-rose-700">R$ {{ number_format($totalExpense, 2, ',', '.') }}</td>
                        <td class="px-6 py-5 text-right text-[10px] font-black text-rose-600 tracking-widest">100.0%</td>
                    </tr>

                    <!-- RESULTADO FINAL -->
                    <tr class="h-8 bg-white"><td colspan="3"></td></tr>
                    <tr class="{{ $netResult >= 0 ? 'bg-emerald-50/50 border-y border-emerald-100' : 'bg-rose-50/50 border-y border-rose-100' }}">
                        <td class="px-8 py-6 text-sm font-black uppercase tracking-[0.2em] text-slate-800 border-r border-slate-100">RESULTADO LÍQUIDO DO PERÍODO</td>
                        <td class="px-8 py-6 text-right font-money text-xl font-black {{ $netResult >= 0 ? 'text-emerald-700' : 'text-rose-700' }} border-r border-slate-100">R$ {{ number_format($netResult, 2, ',', '.') }}</td>
                        <td class="px-8 py-6 text-right text-[10px] font-black uppercase tracking-widest {{ $netResult >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $netResult >= 0 ? 'SUPERÁVIT' : 'DÉFICIT' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

