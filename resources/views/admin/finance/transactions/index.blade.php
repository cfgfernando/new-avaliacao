@extends('layouts.app')

@section('title', 'Transações Financeiras')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">TRANSAÇÕES</h2>
            <p class="text-primary-light font-medium mt-1">Gestão de entradas, saídas e transferências.</p>
        </div>
        <button onclick="openFinanceModal('transactionModal')" class="btn-neo btn-primary">
            <i class="fas fa-plus"></i>
            <span>Nova Transação</span>
        </button>
    </div>

    <!-- Tabela de Transações -->
    <div class="card-neo overflow-hidden">
        <div class="p-8 pb-4">
            <h3 class="text-sm font-black text-primary-dark uppercase tracking-widest">Registro de Movimentações</h3>
        </div>
        <div class="p-4 pt-0">
            <table id="transactionsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição / Categoria</th>
                        <th>Conta / C. Custo</th>
                        <th class="text-right">Valor</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($transactions as $transaction)
                    <tr class="group" onclick="editTransaction({{ $transaction->id }})">
                        <td>
                            <span class="text-xs font-black text-primary-dark tracking-tighter">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-primary-dark uppercase tracking-tight group-hover:text-accent transition-colors">{{ $transaction->description }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[8px] font-black uppercase">{{ $transaction->payment_method_label }}</span>
                                    <span class="text-[9px] font-bold text-primary-light uppercase tracking-widest">{{ $transaction->chartOfAccount->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-600">{{ $transaction->financialAccount->name ?? 'N/A' }}</span>
                                <span class="text-[10px] text-primary-light font-bold">{{ $transaction->costCenter->name ?? 'Geral' }}</span>
                            </div>
                        </td>
                        <td class="text-right">
                            <span class="text-sm font-money {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewTransaction({{ $transaction->id }})" class="btn-action bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white" title="Visualizar">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </button>
                                <button onclick="editTransaction({{ $transaction->id }})" class="btn-action bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white" title="Editar">
                                    <i class="fas fa-pencil-alt text-[10px]"></i>
                                </button>
                                <button onclick="deleteTransaction({{ $transaction->id }})" class="btn-action bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white" title="Excluir">
                                    <i class="fas fa-trash text-[10px]"></i>
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

@push('modals')
<!-- Modal de Transação -->
<div id="transactionModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-reveal-up">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="modalTitle" class="text-xl font-black text-primary-dark uppercase tracking-tight">Lançar Transação</h3>
            <button type="button" onclick="closeFinanceModal('transactionModal')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="transactionForm" action="{{ route('admin.finance.transactions.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-3 px-1">Tipo de Lançamento</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="income" class="hidden peer" checked>
                            <div class="p-4 rounded-2xl border-2 border-slate-100 bg-slate-50 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all group-hover:border-slate-200">
                                <i class="fas fa-arrow-up text-slate-300 peer-checked:text-emerald-500 mb-2"></i>
                                <p class="text-[10px] font-black uppercase text-slate-400 peer-checked:text-emerald-600">Entrada</p>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="expense" class="hidden peer">
                            <div class="p-4 rounded-2xl border-2 border-slate-100 bg-slate-50 text-center peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-all group-hover:border-slate-200">
                                <i class="fas fa-arrow-down text-slate-300 peer-checked:text-rose-500 mb-2"></i>
                                <p class="text-[10px] font-black uppercase text-slate-400 peer-checked:text-rose-600">Saída</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-3 px-1">Valor da Operação</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">R$</span>
                        <input type="text" name="amount" required class="input-neo pl-12 text-xl font-black text-primary-dark mask-money" placeholder="0,00">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Conta Financeira</label>
                    <select name="financial_account_id" required class="input-neo">
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Categoria (Plano de Contas)</label>
                    <select name="chart_of_account_id" required class="input-neo">
                        @foreach($chartOfAccounts as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Descrição</label>
                <input type="text" name="description" required class="input-neo" placeholder="Ex: Pagamento de Energia Elétrica - Maio/2024">
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Data da Transação</label>
                    <input type="date" name="transaction_date" required class="input-neo" value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Método de Pagamento</label>
                    <select name="payment_method" required class="input-neo">
                        <option value="pix">PIX</option>
                        <option value="cash">Dinheiro</option>
                        <option value="transfer">Transferência Bancária</option>
                        <option value="credit_card">Cartão de Crédito</option>
                        <option value="debit_card">Cartão de Débito</option>
                        <option value="slip">Boleto</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeFinanceModal('transactionModal')" class="btn-neo flex-1 py-4 text-xs font-black uppercase text-slate-400 hover:bg-slate-50">CANCELAR</button>
                <button type="submit" class="btn-neo btn-primary flex-[2] py-4 text-xs font-black tracking-widest shadow-xl shadow-accent/20">CONFIRMAR LANÇAMENTO</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#transactionsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            pageLength: 15,
            order: [[0, 'desc']],
            dom: '<"flex justify-between items-center mb-6"f l>rt<"flex justify-between items-center mt-6"i p>',
            columnDefs: [
                { orderable: false, targets: 4 }
            ]
        });
    });

    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
    }

    function editTransaction(id) {
        // Em um cenário real, aqui buscaríamos os dados via AJAX.
        // Por enquanto, vamos preparar o modal para edição.
        openFinanceModal('transactionModal');
        $('#modalTitle').text('Editar Transação #' + id);
        $('#transactionForm').attr('action', `/admin/finance/transactions/${id}`);
        $('#formMethod').val('PUT');
        
        // Simulação de preenchimento (UX)
        $('button[type="submit"]').text('SALVAR ALTERAÇÕES');
    }

    function viewTransaction(id) {
        // Abrir modal em modo de leitura
        editTransaction(id);
        $('#modalTitle').text('Detalhes da Transação #' + id);
        $('#transactionForm input, #transactionForm select, #transactionForm textarea').prop('disabled', true);
        $('button[type="submit"]').addClass('hidden');
    }

    function deleteTransaction(id) {
        if(confirm('Deseja realmente excluir permanentemente a transação #' + id + '? Esta ação não pode ser desfeita.')) {
            // Aqui dispararíamos o formulário de exclusão
            alert('Comando de exclusão enviado para o servidor para o ID: ' + id);
        }
    }

    // Resetar modal ao fechar para não quebrar o "Novo Lançamento"
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('#transactionForm input, #transactionForm select, #transactionForm textarea').prop('disabled', false);
        $('button[type="submit"]').removeClass('hidden').text('CONFIRMAR LANÇAMENTO');
    }
</script>
@endpush
