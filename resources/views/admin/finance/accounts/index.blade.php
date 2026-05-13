@extends('layouts.app')

@section('title', 'Contas e Cofres')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">CONTAS E COFRES</h2>
            <p class="text-primary-light font-medium mt-1">Gestão de contas bancárias e caixas físicos.</p>
        </div>
        <button onclick="openFinanceModal('financialAccountModal')" class="btn-neo btn-primary">
            <i class="fas fa-plus"></i>
            <span>Nova Conta</span>
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-neo p-6 border-l-4 border-primary">
            <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Total em Contas</p>
            <h3 class="text-2xl font-black text-primary-dark tracking-tighter">R$ {{ number_format($accounts->sum('balance_cache'), 2, ',', '.') }}</h3>
        </div>
        <div class="card-neo p-6 border-l-4 border-emerald-500">
            <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Contas Ativas</p>
            <h3 class="text-2xl font-black text-primary-dark tracking-tighter">{{ $accounts->where('is_active', true)->count() }}</h3>
        </div>
        <div class="card-neo p-6 border-l-4 border-blue-500">
            <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Bancos Vinculados</p>
            <h3 class="text-2xl font-black text-primary-dark tracking-tighter">{{ $accounts->where('type', 'bank')->count() }}</h3>
        </div>
    </div>

    <!-- Tabela de Contas -->
    <div class="card-neo overflow-hidden">
        <div class="p-4">
            <table id="accountsTable" class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th>Identificação</th>
                    <th>Tipo</th>
                    <th>Dados Bancários</th>
                    <th class="text-right">Saldo Atual</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($accounts as $account)
                <tr class="group" onclick="editAccount({{ $account->id }})">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-primary-dark shadow-sm border border-white group-hover:bg-accent group-hover:text-white transition-all">
                                <i class="fas {{ $account->type === 'bank' ? 'fa-building-columns' : ($account->type === 'cash' ? 'fa-wallet' : 'fa-chart-pie') }} text-sm"></i>
                            </div>
                            <span class="text-sm font-black text-primary-dark uppercase tracking-tight">{{ $account->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[9px] font-black uppercase tracking-tighter">
                            {{ $account->type === 'bank' ? 'Bancário' : ($account->type === 'cash' ? 'Cofre Físico' : 'Investimento') }}
                        </span>
                    </td>
                    <td>
                        @if($account->type !== 'cash')
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-600">{{ $account->bank_name ?? 'N/A' }}</span>
                                <span class="text-[10px] text-primary-light font-bold">AG {{ $account->agency }} / CC {{ $account->account_number }}</span>
                            </div>
                        @else
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Uso Interno</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <span class="text-sm font-money text-primary-dark tracking-tighter">
                            R$ {{ number_format($account->balance_cache, 2, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="editAccount({{ $account->id }})" class="btn-action bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white" title="Editar">
                                <i class="fas fa-pencil-alt text-[10px]"></i>
                            </button>
                            <button onclick="deleteAccount({{ $account->id }})" class="btn-action bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white" title="Excluir">
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

@push('modals')
<!-- Modal Nova Conta / Cofre -->
<div id="financialAccountModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="accountModalTitle" class="text-xl font-black text-primary-dark uppercase tracking-tight">Nova Conta ou Cofre</h3>
            <button type="button" onclick="closeFinanceModal('financialAccountModal')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="accountForm" action="{{ route('admin.finance.accounts.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="accountFormMethod" value="POST">
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Nome de Identificação</label>
                <input type="text" name="name" id="acc_name" required class="input-neo" placeholder="Ex: Caixa Matriz ou Conta Itaú">
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Tipo</label>
                    <select name="type" id="accountTypeSelect" required class="input-neo" onchange="toggleBankFields()">
                        <option value="bank">Conta Bancária</option>
                        <option value="cash">Caixa Físico (Cofre)</option>
                        <option value="investment">Investimento</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Saldo Inicial (R$)</label>
                    <input type="text" name="initial_balance" id="acc_balance" required class="input-neo mask-money" placeholder="0,00" value="0,00">
                </div>
            </div>

            <div id="bankDetailsFields" class="grid grid-cols-3 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="col-span-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Dados Bancários</label>
                </div>
                <div class="col-span-3">
                    <input type="text" name="bank_name" id="acc_bank" class="input-neo" placeholder="Nome do Banco">
                </div>
                <div>
                    <input type="text" name="agency" id="acc_agency" class="input-neo" placeholder="Agência">
                </div>
                <div class="col-span-2">
                    <input type="text" name="account_number" id="acc_number" class="input-neo" placeholder="Número da Conta">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-xs font-black tracking-widest">SALVAR CONTA/COFRE</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#accountsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
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
    function toggleBankFields() {
        const type = document.getElementById('accountTypeSelect').value;
        const bankFields = document.getElementById('bankDetailsFields');
        if (type === 'cash') {
            bankFields.classList.add('hidden');
        } else {
            bankFields.classList.remove('hidden');
        }
    }

    function editAccount(id) {
        openFinanceModal('financialAccountModal');
        $('#accountModalTitle').text('Editando Conta/Cofre #' + id);
        $('#accountForm').attr('action', `/admin/finance/accounts/${id}`);
        $('#accountFormMethod').val('PUT');
        $('button[type="submit"]').text('SALVAR ALTERAÇÕES');
    }

    function deleteAccount(id) {
        if(confirm('Atenção: A exclusão de uma conta pode afetar o histórico de transações vinculadas. Deseja continuar com a exclusão da conta #' + id + '?')) {
            alert('Enviando solicitação de exclusão para conta #' + id);
        }
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('#accountForm')[0].reset();
        $('#accountModalTitle').text('Nova Conta ou Cofre');
        $('#accountFormMethod').val('POST');
        $('button[type="submit"]').text('SALVAR CONTA/COFRE');
    }
</script>
@endpush
