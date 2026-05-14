@extends('layouts.app')

@section('title', 'Livro Diário')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8 no-print">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Livro Diário</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Registro Cronológico de Operações Financeiras</p>
        </div>
        <div class="flex gap-4">
             <button onclick="window.print()" class="btn-neo bg-white text-slate-800 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-print text-primary-light"></i>
                <span>Imprimir Relatório</span>
            </button>
        </div>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Listagem Geral de Lançamentos Auditados</h3>
        </div>
        <div class="p-6">
            <table id="journalTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 w-32">Data Operação</th>
                        <th class="px-6 py-4">Histórico / Descrição</th>
                        <th class="px-6 py-4">Origem Financeira</th>
                        <th class="px-6 py-4 text-right">Saída (-)</th>
                        <th class="px-6 py-4 text-right">Entrada (+)</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($entries as $entry)
                    <tr class="group hover:bg-slate-50/30 transition-all cursor-pointer" onclick="viewJournal({{ $entry->id }})">
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold text-slate-800 tracking-tight">{{ \Carbon\Carbon::parse($entry->transaction_date)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight group-hover:text-accent transition-colors">{{ $entry->description }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $entry->chartOfAccount->name ?? 'Sem Categoria' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-light/40 group-hover:bg-accent transition-colors"></div>
                                <span class="text-[10px] font-bold text-primary-light uppercase tracking-widest">{{ $entry->financialAccount->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right bg-rose-50/5">
                            @if($entry->type === 'expense')
                                <span class="text-xs font-black font-money text-rose-500">
                                    R$ {{ number_format($entry->amount, 2, ',', '.') }}
                                </span>
                            @else
                                <span class="text-[9px] text-slate-200 font-black">---</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right bg-emerald-50/5">
                            @if($entry->type === 'income')
                                <span class="text-xs font-black font-money text-emerald-500">
                                    R$ {{ number_format($entry->amount, 2, ',', '.') }}
                                </span>
                            @else
                                <span class="text-[9px] text-slate-200 font-black">---</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                <button onclick="viewJournal({{ $entry->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Detalhes">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </button>
                                <button onclick="printJournal({{ $entry->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-800 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Imprimir">
                                    <i class="fas fa-print text-[10px]"></i>
                                </button>
                                <button onclick="reverseJournal({{ $entry->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Estornar">
                                    <i class="fas fa-undo-alt text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#journalTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            pageLength: 25,
            order: [[0, 'desc']],
            dom: '<"flex justify-between items-center mb-8 px-2"f l>rt<"flex justify-between items-center mt-8 px-2"i p>',
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
            drawCallback: function() {
                $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 bg-white border border-slate-100 rounded-lg text-[10px] font-black text-slate-600 uppercase tracking-widest mx-1 hover:bg-slate-50 transition-all');
            }
        });
    });

    function viewJournal(id) {
        alert('Acesso ao Dossiê Contábil do Lançamento #' + id);
    }

    function printJournal(id) {
        alert('Gerando Comprovante de Operação #' + id);
    }

    function reverseJournal(id) {
        if(confirm('ALERTA DE AUDITORIA: Deseja realmente estornar este lançamento? Esta ação criará uma contrapartida inversa automática e não poderá ser desfeita.')) {
            alert('Operação #' + id + ' estornada com sucesso no Livro Diário.');
        }
    }
</script>
@endpush

