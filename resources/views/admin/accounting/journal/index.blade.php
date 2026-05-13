@extends('layouts.app')

@section('title', 'Livro Diário Contábil')

@section('content')
<div class="print-header">
    <h1 class="text-xl font-black uppercase tracking-widest">MDA Church - Enterprise ERP</h1>
    <h2 class="text-lg font-bold uppercase">Livro Diário Contábil</h2>
    <p class="text-xs uppercase font-medium">Extraído em: {{ date('d/m/Y H:i') }}</p>
</div>

<div class="space-y-8 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8 no-print">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Livro Diário</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Registro Cronológico de Operações de Partidas Dobradas</p>
        </div>
        <div class="flex gap-4">
             <button onclick="window.print()" class="btn-neo bg-white text-slate-800 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-print text-primary-light"></i>
                <span>Imprimir Relatório</span>
            </button>
        </div>
    </div>

    <!-- Tabela de Lançamentos Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30 no-print">
            <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-list-ul text-primary-light"></i>
                Listagem Analítica de Lançamentos
            </h3>
        </div>
        <div class="p-6">
            <table id="journalTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 w-40">Data & Ref</th>
                        <th class="px-6 py-4">Histórico e Composição Contábil</th>
                        <th class="px-6 py-4 text-right w-48">Débito (D)</th>
                        <th class="px-6 py-4 text-right w-48">Crédito (C)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($entries as $entry)
                    <tr class="group hover:bg-slate-50/30 transition-all cursor-pointer">
                        <!-- Data e Referência -->
                        <td class="px-6 py-6 align-top">
                            <span class="text-xs font-bold text-slate-800 block">{{ $entry->date->format('d/m/Y') }}</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1 block">{{ $entry->reference }}</span>
                        </td>

                        <!-- Histórico e Composição -->
                        <td class="px-6 py-6">
                            <span class="text-sm font-bold text-slate-800 uppercase tracking-tight block mb-4 group-hover:text-accent transition-colors">{{ $entry->description }}</span>
                            
                            <div class="space-y-2">
                                @foreach($entry->items as $item)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/50 border border-slate-100 hover:border-slate-200 transition-all">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-1 {{ $item->type === 'debit' ? 'bg-accent text-white' : 'bg-rose-100 text-rose-600' }} rounded text-[8px] font-black uppercase">
                                            {{ $item->type === 'debit' ? 'D' : 'C' }}
                                        </span>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-slate-800 uppercase tracking-tight leading-none">{{ $item->chartOfAccount->name }}</span>
                                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ $item->chartOfAccount->code }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black font-money text-slate-800">
                                            {{ number_format($item->amount, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <!-- Coluna de Débitos -->
                        <td class="px-6 py-6 text-right align-top bg-slate-50/10">
                            <span class="text-sm font-black font-money text-slate-800">
                                R$ {{ number_format($entry->items->where('type', 'debit')->sum('amount'), 2, ',', '.') }}
                            </span>
                        </td>

                        <!-- Coluna de Créditos -->
                        <td class="px-6 py-6 text-right align-top bg-rose-50/5">
                            <span class="text-sm font-black font-money text-slate-500">
                                R$ {{ number_format($entry->items->where('type', 'credit')->sum('amount'), 2, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="no-print">
                    <tr class="bg-slate-50/80 border-t border-slate-100">
                        <td colspan="2" class="px-8 py-8 text-[11px] font-black uppercase tracking-widest text-slate-800 text-right">Totais Consolidados do Período</td>
                        <td class="px-8 py-8 text-right bg-slate-100/30">
                            <div class="flex flex-col items-end">
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Total Débitos</span>
                                <span class="text-xl font-money font-black text-slate-800">R$ {{ number_format($entries->sum(fn($e) => $e->items->where('type', 'debit')->sum('amount')), 2, ',', '.') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-8 text-right bg-rose-50/20">
                            <div class="flex flex-col items-end">
                                <span class="text-[9px] text-rose-400 font-black uppercase tracking-widest mb-1">Total Créditos</span>
                                <span class="text-xl font-money font-black text-rose-900">R$ {{ number_format($entries->sum(fn($e) => $e->items->where('type', 'credit')->sum('amount')), 2, ',', '.') }}</span>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Paginação Elite V8 -->
    <div class="mt-8 flex justify-center no-print">
        {{ $entries->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Estilização da paginação Laravel para o padrão Elite V8
        $('.pagination').addClass('flex gap-2');
        $('.pagination li').addClass('inline-block');
        $('.pagination li a, .pagination li span').addClass('px-4 py-2 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-primary-light uppercase tracking-widest hover:bg-slate-50 transition-all');
        $('.pagination li.active span').addClass('!bg-accent !text-white !border-accent');
    });
</script>
@endpush

