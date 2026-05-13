@extends('layouts.app')

@section('title', 'Livro Diário')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">LIVRO DIÁRIO</h2>
            <p class="text-primary-light font-medium mt-1">Registro cronológico de todas as operações financeiras.</p>
        </div>
        <div class="flex gap-4">
            <button onclick="window.print()" class="btn-neo bg-white text-slate-500 hover:text-primary-dark">
                <i class="fas fa-print"></i>
                <span>Imprimir Relatório</span>
            </button>
        </div>
    </div>

    <!-- Tabela do Livro Diário -->
    <div class="card-neo overflow-hidden">
        <div class="p-4">
            <table id="journalTable" class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="w-24">Data</th>
                    <th>Histórico / Classificação</th>
                    <th>Conta Financeira</th>
                    <th class="text-right">Débito (Saída)</th>
                    <th class="text-right">Crédito (Entrada)</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($entries as $entry)
                <tr class="group" onclick="viewJournal({{ $entry->id }})">
                    <td>
                        <span class="text-xs font-black text-primary-dark tracking-tighter">{{ \Carbon\Carbon::parse($entry->transaction_date)->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-primary-dark uppercase tracking-tight group-hover:text-accent transition-colors">{{ $entry->description }}</span>
                            <span class="text-[9px] font-bold text-primary-light uppercase tracking-widest">{{ $entry->chartOfAccount->name ?? 'Sem Categoria' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="text-xs font-bold text-slate-600">{{ $entry->financialAccount->name ?? 'N/A' }}</span>
                    </td>
                    <td class="text-right">
                        @if($entry->type === 'expense')
                            <span class="text-sm font-money text-rose-600">
                                R$ {{ number_format($entry->amount, 2, ',', '.') }}
                            </span>
                        @else
                            <span class="text-xs text-slate-200">-</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($entry->type === 'income')
                            <span class="text-sm font-money text-emerald-600">
                                R$ {{ number_format($entry->amount, 2, ',', '.') }}
                            </span>
                        @else
                            <span class="text-xs text-slate-200">-</span>
                        @endif
                    </td>
                    <td class="text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="viewJournal({{ $entry->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-slate-800 hover:text-white" title="Detalhes">
                                <i class="fas fa-file-invoice-dollar text-[10px]"></i>
                            </button>
                            <button onclick="printJournal({{ $entry->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-slate-800 hover:text-white" title="Imprimir">
                                <i class="fas fa-print text-[10px]"></i>
                            </button>
                            <button onclick="reverseJournal({{ $entry->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-rose-500 hover:text-white" title="Estornar">
                                <i class="fas fa-rotate-left text-[10px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
            dom: '<"flex justify-between items-center mb-6"f l>rt<"flex justify-between items-center mt-6"i p>',
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });
    });

    function viewJournal(id) {
        alert('Detalhes contábeis completos do lançamento #' + id);
    }

    function printJournal(id) {
        alert('Gerando PDF do comprovante #' + id);
    }

    function reverseJournal(id) {
        if(confirm('ATENÇÃO: Deseja realmente estornar este lançamento? Isso criará uma operação inversa para anulação do saldo.')) {
            alert('Lançamento #' + id + ' estornado com sucesso.');
        }
    }
</script>
@endpush
