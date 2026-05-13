@extends('layouts.app')

@section('title', 'Transações Financeiras')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Transações Financeiras</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gestão de Fluxo de Caixa, Entradas e Saídas</p>
        </div>
        <button onclick="openFinanceModal('transactionModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVA TRANSAÇÃO</span>
        </button>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Registro Cronológico de Movimentações</h3>
        </div>
        <div class="p-4">
            <table id="transactionsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 w-32">Data</th>
                        <th class="px-6 py-4">Descrição / Categoria</th>
                        <th class="px-6 py-4">Conta / C. Custo</th>
                        <th class="px-6 py-4 text-right">Valor</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($transactions as $transaction)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="editTransaction({{ $transaction->id }})">
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-800 uppercase">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 leading-tight mb-0.5 group-hover:text-accent transition-colors uppercase">{{ $transaction->description }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[8px] font-black uppercase tracking-widest">{{ $transaction->payment_method_label }}</span>
                                    <span class="text-[9px] font-black text-primary-light uppercase tracking-widest">{{ $transaction->chartOfAccount->name ?? 'Sem Categoria' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-800 uppercase tracking-widest leading-none mb-1">{{ $transaction->financialAccount->name ?? 'N/A' }}</span>
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $transaction->costCenter->name ?? 'Geral' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right bg-slate-50/30">
                            <span class="text-base font-money {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editTransaction({{ $transaction->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-blue-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Editar">
                                    <i class="fas fa-pencil-alt text-[10px]"></i>
                                </button>
                                <button onclick="deleteTransaction({{ $transaction->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-rose-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Excluir">
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
<!-- Modal Nova Transação Elite V8 -->
<div id="transactionModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[999] items-center justify-center p-6">
    <div class="bg-white w-full max-w-2xl animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2.5rem] flex flex-col max-h-[95vh]">
        
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 id="modalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Lançamento de Transação</h3>
                <p class="text-[9px] text-primary-light font-black uppercase tracking-widest mt-1">Tesouraria & Auditoria Compliance</p>
            </div>
            <button type="button" onclick="closeFinanceModal('transactionModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        <form id="transactionForm" action="{{ route('admin.finance.transactions.store') }}" method="POST" class="p-0 bg-white overflow-y-auto custom-scrollbar">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-4">Natureza da Operação</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="type" value="income" class="hidden peer" checked>
                                <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/50 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition-all hover:bg-slate-100">
                                    <i class="fas fa-arrow-up mb-2 text-xl"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest">Entrada</p>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="type" value="expense" class="hidden peer">
                                <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/50 text-center peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-600 transition-all hover:bg-slate-100">
                                    <i class="fas fa-arrow-down mb-2 text-xl"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest">Saída</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-4">Valor da Operação</label>
                        <div class="relative group/val">
                            <div class="absolute inset-y-0 left-0 w-16 flex items-center justify-center bg-slate-50 border-r border-slate-100 rounded-l-2xl text-[10px] font-black text-primary-light group-focus-within/val:bg-primary group-focus-within/val:text-white transition-all">R$</div>
                            <input type="text" name="amount" required class="input-neo !pl-20 !py-6 !text-3xl !font-black !text-slate-800 mask-money" placeholder="0,00">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Conta Financeira</label>
                        <select name="financial_account_id" required class="input-neo uppercase">
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Plano de Contas</label>
                        <select name="chart_of_account_id" required class="input-neo uppercase">
                            @foreach($chartOfAccounts as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Descrição Técnica</label>
                    <input type="text" name="description" required class="input-neo uppercase" placeholder="EX: PAGAMENTO FORNECEDOR X - NF 123">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Data do Lançamento</label>
                        <input type="date" name="transaction_date" required class="input-neo uppercase" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Meio de Movimentação</label>
                        <select name="payment_method" required class="input-neo uppercase">
                            <option value="pix">PIX Transmissão</option>
                            <option value="cash">Espécie / Dinheiro</option>
                            <option value="transfer">Transferência Eletrônica</option>
                            <option value="credit_card">Cartão Corporativo (Crédito)</option>
                            <option value="debit_card">Cartão Corporativo (Débito)</option>
                            <option value="slip">Boleto Bancário</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-8 border-t border-slate-100 flex justify-between items-center bg-slate-50/30 shrink-0">
                <button type="button" onclick="closeFinanceModal('transactionModal')" class="px-8 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-primary-light uppercase tracking-widest hover:bg-slate-50 transition-all">Descartar</button>
                <button type="submit" class="px-12 py-3 btn-neo btn-primary text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-check-circle mr-2"></i> Confirmar Lançamento
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#transactionsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            pageLength: 15,
            order: [[0, 'desc']],
            dom: '<"flex justify-between items-center mb-8 px-2"f l>rt<"flex justify-between items-center mt-8 px-2"i p>',
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
        // Reset form state
        $('#transactionForm')[0].reset();
        $('#transactionForm input, #transactionForm select, #transactionForm textarea').prop('disabled', false);
        $('button[type="submit"]').removeClass('hidden').text('Confirmar Lançamento Fiscal');
        $('#modalTitle').text('Lançamento de Transação');
        $('#formMethod').val('POST');
    }

    function editTransaction(id) {
        openFinanceModal('transactionModal');
        $('#modalTitle').text('Editar Transação: #' + id);
        $('#transactionForm').attr('action', `/admin/finance/transactions/${id}`);
        $('#formMethod').val('PUT');
        $('button[type="submit"]').text('Salvar Alterações Fiscais');
    }

    function deleteTransaction(id) {
        if(confirm('Tem certeza que deseja excluir permanentemente a transação #' + id + '? Esta ação é auditada e irreversível.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/finance/transactions/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
