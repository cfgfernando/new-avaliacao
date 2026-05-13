@extends('layouts.app')

@section('title', 'Contas e Cofres')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8 no-print">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Contas e Cofres</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gestão de disponibilidades, bancos e caixas operacionais</p>
        </div>
        <button onclick="openFinanceModal('financialAccountModal')" class="btn-neo btn-primary text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>NOVA CONTA / COFRE</span>
        </button>
    </div>

    <!-- Stats Grid Elite V8 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-primary-light/20 group-hover:bg-accent transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total em Contas</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money tracking-tight">R$ {{ number_format($accounts->sum('balance_cache'), 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-primary-light mt-2 uppercase tracking-widest">Disponibilidade Consolidada</p>
                </div>
                <div class="stats-icon bg-slate-50 text-slate-400 group-hover:bg-accent group-hover:text-white">
                    <i class="fas fa-vault"></i>
                </div>
            </div>
        </div>
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-emerald-500/20 group-hover:bg-emerald-500 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Contas Ativas</p>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $accounts->where('is_active', true)->count() }}</h3>
                    <p class="text-[9px] font-bold text-emerald-500 mt-2 uppercase tracking-widest">Operacionalizando</p>
                </div>
                <div class="stats-icon bg-emerald-50/50 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-accent/20 group-hover:bg-accent transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Bancos Vinculados</p>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $accounts->where('type', 'bank')->count() }}</h3>
                    <p class="text-[9px] font-bold text-accent mt-2 uppercase tracking-widest">Instituições Ativas</p>
                </div>
                <div class="stats-icon bg-accent/10 text-accent group-hover:bg-accent group-hover:text-white">
                    <i class="fas fa-building-columns"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-file-invoice-dollar text-primary-light"></i>
                Gerenciamento de Custódia & Saldo
            </h3>
        </div>
        <div class="p-6">
            <table id="accountsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Identificação da Conta</th>
                        <th class="px-6 py-4">Tipo / Natureza</th>
                        <th class="px-6 py-4">Dados Bancários</th>
                        <th class="px-6 py-4 text-right">Saldo Atual (R$)</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($accounts as $account)
                    <tr class="group hover:bg-slate-50/30 transition-all cursor-pointer" onclick="editAccount({{ $account->id }})">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                                    <i class="fas {{ $account->type === 'bank' ? 'fa-building-columns' : ($account->type === 'cash' ? 'fa-wallet' : 'fa-chart-pie') }}"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight">{{ $account->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                {{ $account->type === 'bank' ? 'Bancário' : ($account->type === 'cash' ? 'Cofre Físico' : 'Investimento') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($account->type !== 'cash')
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-800 uppercase tracking-widest leading-none mb-1">{{ $account->bank_name ?? 'N/A' }}</span>
                                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">AG {{ $account->agency }} / CC {{ $account->account_number }}</span>
                                </div>
                            @else
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Disponibilidade Imediata</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right bg-slate-50/10">
                            <span class="text-sm font-black font-money text-slate-800">
                                R$ {{ number_format($account->balance_cache, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="editAccount({{ $account->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-accent transition-all shadow-sm border border-transparent hover:border-slate-100" title="Editar">
                                    <i class="fas fa-pencil-alt text-[10px]"></i>
                                </button>
                                <button onclick="deleteAccount({{ $account->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-rose-500 transition-all shadow-sm border border-transparent hover:border-slate-100" title="Excluir">
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
<!-- Modal Elite V8 -->
<div id="financialAccountModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[999] items-center justify-center p-6">
    <div class="bg-white w-full max-w-lg animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 id="accountModalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Nova Conta ou Cofre</h3>
                <p class="text-[9px] text-primary-light font-black uppercase tracking-widest mt-1">Gestão de Tesouraria</p>
            </div>
            <button type="button" onclick="closeFinanceModal('financialAccountModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="accountForm" action="{{ route('admin.finance.accounts.store') }}" method="POST" class="p-8 space-y-8 bg-white">
            @csrf
            <input type="hidden" name="_method" id="accountFormMethod" value="POST">
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Nome / Identificação</label>
                <input type="text" name="name" id="acc_name" required class="input-neo uppercase" placeholder="Ex: ITAÚ CORPORATIVO ou CAIXA MATRIZ">
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Tipo de Custódia</label>
                    <select name="type" id="accountTypeSelect" required class="input-neo uppercase" onchange="toggleBankFields()">
                        <option value="bank">Conta Bancária</option>
                        <option value="cash">Caixa Físico (Cofre)</option>
                        <option value="investment">Aplicação / Investimento</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Saldo de Abertura (R$)</label>
                    <input type="text" name="initial_balance" id="acc_balance" required class="input-neo text-lg font-money mask-money" placeholder="0,00" value="0,00">
                </div>
            </div>

            <div id="bankDetailsFields" class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                <p class="text-[9px] font-black text-primary-light uppercase tracking-widest px-1 flex items-center gap-2">
                    <i class="fas fa-building-columns"></i> Dados Bancários Complementares
                </p>
                <input type="text" name="bank_name" id="acc_bank" class="input-neo !bg-white uppercase" placeholder="Instituição Financeira">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="agency" id="acc_agency" class="input-neo !bg-white uppercase" placeholder="Agência">
                    <input type="text" name="account_number" id="acc_number" class="input-neo !bg-white uppercase" placeholder="Conta Corrente">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeFinanceModal('financialAccountModal')" class="flex-1 px-6 py-3 border border-slate-100 rounded-xl text-[10px] font-black text-primary-light uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" class="flex-[2] btn-neo btn-primary text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-save mr-2"></i> Confirmar Cadastro
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#accountsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            dom: '<"flex justify-between items-center mb-8 px-2"f l>rt<"flex justify-between items-center mt-8 px-2"i p>',
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
            drawCallback: function() {
                $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 bg-white border border-slate-100 rounded-lg text-[10px] font-black text-slate-600 uppercase tracking-widest mx-1 hover:bg-slate-50 transition-all');
            }
        });
    });

    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
        $('#accountForm')[0].reset();
        $('#accountModalTitle').text('Nova Conta ou Cofre');
        $('#accountFormMethod').val('POST');
        $('button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Confirmar Cadastro');
        toggleBankFields();
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
        $('#accountModalTitle').text('Editar Conta/Cofre: #' + id);
        $('#accountForm').attr('action', `/admin/finance/accounts/${id}`);
        $('#accountFormMethod').val('PUT');
        $('button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Salvar Alterações');
    }

    function deleteAccount(id) {
        if(confirm('A exclusão de uma conta pode causar inconsistências históricas. Deseja realmente excluir a conta?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/finance/accounts/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection

