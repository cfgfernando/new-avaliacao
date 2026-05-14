@extends('layouts.app')

@section('title', 'Controle de Ofertas / Receitas')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Controle de Receitas</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gestão de entradas, dízimos e ofertas</p>
        </div>
        <button onclick="openFinanceModal('incomeModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVA RECEITA</span>
        </button>
    </div>

    <!-- Cards Informativos Elite V8 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Recebido -->
        <div class="card-neo relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-1 h-full bg-emerald-500/20"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Total Recebido (Mês)</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money tracking-tight">R$ {{ number_format($stats['total_paid'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-black text-emerald-500 mt-2 uppercase tracking-widest flex items-center gap-1">
                        <i class="fas fa-caret-up"></i> +{{ $stats['total_count'] }} Lançamentos
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
            </div>
        </div>

        <!-- Pendente -->
        <div class="card-neo relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-1 h-full bg-amber-500/20"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Aguardando Efetivação</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money tracking-tight">R$ {{ number_format($stats['total_pending'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-black text-amber-500 mt-2 uppercase tracking-widest">Atenção Necessária</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="md:col-span-2 card-neo flex flex-col justify-between overflow-hidden relative">
            <div class="flex justify-between items-center mb-2 relative z-10">
                <h4 class="text-[9px] font-black text-primary-light uppercase tracking-widest">Evolução de Entradas</h4>
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
            </div>
            <div class="h-20 w-full relative z-10">
                <canvas id="incomeChartMini"></canvas>
            </div>
            <div class="absolute -right-2 -bottom-2 text-slate-50 opacity-10 text-6xl pointer-events-none transform -rotate-12">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Filtros Elite V8 -->
    <div class="card-neo bg-slate-50/50">
        <form action="{{ route('admin.finance.income.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Pesquisa Global</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="input-neo !pl-10" placeholder="Membro ou descrição...">
                    <div class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Data Início</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-neo">
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Data Fim</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-neo">
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Conta</label>
                <select name="financial_account_id" class="input-neo uppercase">
                    <option value="">Todas</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('financial_account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-neo btn-primary text-[10px] py-3.5">
                    <i class="fas fa-filter text-[9px]"></i> FILTRAR
                </button>
                <a href="{{ route('admin.finance.income.index') }}" class="w-12 h-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-white hover:text-accent hover:border-accent transition-all shadow-sm group">
                    <i class="fas fa-undo-alt text-[10px]"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Listagem Analítica de Entradas</h3>
        </div>
        <div class="p-4">
            <table id="incomeTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Doador / Membro</th>
                        <th class="px-6 py-4">Conta / Cofre</th>
                        <th class="px-6 py-4 text-right">Valor</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $transaction)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="editIncome({{ $transaction->id }})">
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-800 uppercase">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 leading-tight mb-0.5 group-hover:text-accent transition-colors">
                                    {{ $transaction->member->name ?? ($transaction->description ?: 'Doador Anônimo') }}
                                </span>
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $transaction->chartOfAccount->name ?? 'Sem Categoria' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-accent shadow-sm shadow-accent/50"></div>
                                <span class="text-[10px] font-black text-primary-light uppercase tracking-widest">{{ $transaction->financialAccount->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right bg-slate-50/30">
                            <span class="text-sm font-money text-slate-800">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 {{ $transaction->status == 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }} border rounded-lg text-[9px] font-black uppercase tracking-widest">
                                {{ $transaction->status == 'paid' ? 'Efetivado' : 'Pendente' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                <button onclick="editIncome({{ $transaction->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Visualizar">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </button>
                                <a href="{{ route('admin.finance.income.receipt', $transaction->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-800 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Recibo">
                                    <i class="fas fa-print text-[10px]"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center bg-slate-50/30">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-database text-4xl text-slate-200 mb-4"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum registro localizado.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($transactions->hasPages())
        <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

@push('modals')
<!-- Modal Nova Receita Elite V8 -->
<div id="incomeModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto custom-scrollbar">
    <div class="bg-white w-full max-w-xl m-auto animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2.5rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight modal-title">Nova Receita</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Lançamento de Arrecadação</p>
            </div>
            <button onclick="closeFinanceModal('incomeModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-slate-100 px-8 bg-slate-50/20 shrink-0 gap-6">
            <button onclick="switchTab('income', 'perfil')" id="tab-income-perfil" class="modal-tab-clean active">DADOS GERAIS</button>
            <button onclick="switchTab('income', 'ged')" id="tab-income-ged" class="modal-tab-clean">DOCUMENTOS</button>
            <button onclick="switchTab('income', 'controle')" id="tab-income-controle" class="modal-tab-clean">HISTÓRICO</button>
        </div>

        <!-- Scrollable Content -->
        <div class="overflow-y-auto flex-grow bg-white">
            <form action="{{ route('admin.finance.income.store') }}" method="POST" id="form-income" class="p-0" enctype="multipart/form-data">
                @csrf
                <div id="method-container"></div>
                <input type="hidden" name="status" value="paid">
                <input type="hidden" name="donor_type" id="donor_type" value="membro">
                <input type="hidden" name="member_id" id="selected_member_id">
                
                <!-- Tab: Perfil -->
                <div id="content-income-perfil" class="p-6 space-y-4 tab-content">
                    
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-8">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Origem da Arrecadação</label>
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" onclick="setDonorType('membro')" class="btn-toggle-clean active" id="btn-type-membro">MEMBRO</button>
                                <button type="button" onclick="setDonorType('visitante')" class="btn-toggle-clean" id="btn-type-visitante">VISITANTE</button>
                                <button type="button" onclick="setDonorType('anonima')" class="btn-toggle-clean" id="btn-type-anonima">ANÔNIMA</button>
                                <button type="button" onclick="setDonorType('diversas')" class="btn-toggle-clean" id="btn-type-diversas">DIVERSAS</button>
                            </div>
                        </div>
                        <div class="col-span-4">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Data de Entrada</label>
                            <input type="date" name="transaction_date" required class="input-neo !py-2.5 uppercase" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Valor do Lançamento</label>
                            <div class="relative group/val">
                                <div class="absolute inset-y-0 left-0 w-10 flex items-center justify-center bg-slate-50 border-r border-slate-100 rounded-l-xl text-[10px] font-bold text-slate-400 group-focus-within/val:bg-accent group-focus-within/val:text-white group-focus-within/val:border-accent transition-all">R$</div>
                                <input type="text" name="amount" required class="input-neo !pl-12 !py-3 !text-lg !font-black !text-slate-800 mask-money" placeholder="0,00">
                            </div>
                        </div>
                        <!-- Busca de Membro -->
                        <div id="field-membro" class="col-span-7 space-y-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest px-1">Membro Titular</label>
                            <div class="relative" id="member_search_container">
                                <input type="text" id="member_search_input" class="input-neo !pl-10 !py-3" placeholder="NOME OU CPF..." autocomplete="off">
                                <div class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                                    <i class="fas fa-search text-xs"></i>
                                </div>
                                <div id="member_results" class="hidden absolute top-full left-0 w-full bg-white mt-2 rounded-xl shadow-xl border border-slate-100 z-[110] max-h-40 overflow-y-auto py-2"></div>
                            </div>
                        </div>
                        <div id="field-visitante" class="col-span-7 hidden">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Identificação do Visitante</label>
                            <input type="text" name="visitor_name" class="input-neo !py-3 uppercase" placeholder="Nome completo...">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-2 px-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Conta Financeira</label>
                                <button type="button" onclick="togglePin('financial_account_id')" id="pin-financial_account_id" class="btn-pin-clean">
                                    <i class="fas fa-thumbtack text-[9px]"></i>
                                </button>
                            </div>
                            <select name="financial_account_id" required class="input-neo !py-2.5 uppercase">
                                <option value="">Selecione...</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-2 px-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Plano de Contas</label>
                                <button type="button" onclick="togglePin('chart_of_account_id')" id="pin-chart_of_account_id" class="btn-pin-clean">
                                    <i class="fas fa-thumbtack text-[9px]"></i>
                                </button>
                            </div>
                            <select name="chart_of_account_id" required class="input-neo !py-2.5 uppercase">
                                <option value="">Selecione...</option>
                                @foreach($chartOfAccounts as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-2 px-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Meio de Pagamento</label>
                                <button type="button" onclick="togglePin('payment_method')" id="pin-payment_method" class="btn-pin-clean">
                                    <i class="fas fa-thumbtack text-[9px]"></i>
                                </button>
                            </div>
                            <select name="payment_method" required class="input-neo !py-2.5 uppercase">
                                <option value="pix">PIX / Transferência</option>
                                <option value="cash">Dinheiro</option>
                                <option value="credit_card">Cartão Crédito</option>
                                <option value="debit_card">Cartão Débito</option>
                            </select>
                        </div>
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-2 px-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Centro de Custo</label>
                                <button type="button" onclick="togglePin('cost_center_id')" id="pin-cost_center_id" class="btn-pin-clean">
                                    <i class="fas fa-thumbtack text-[9px]"></i>
                                </button>
                            </div>
                            <select name="cost_center_id" class="input-neo !py-2.5 uppercase">
                                @foreach($costCenters as $cc)
                                <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Descrição / Observações</label>
                        <input type="text" name="description" class="input-neo !py-2.5 uppercase" placeholder="Informações adicionais...">
                    </div>
                </div>

                <!-- Tab: GED -->
                <div id="content-income-ged" class="p-12 hidden tab-content">
                    <div class="border-2 border-dashed border-slate-100 rounded-3xl p-12 text-center space-y-4 bg-slate-50/50 transition-all hover:border-accent group">
                        <div class="w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center mx-auto text-slate-400 group-hover:text-accent transition-all">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Upload de Comprovante</p>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Arraste ou clique para selecionar</p>
                        </div>
                        <input type="file" name="attachment" id="income_attachment" class="hidden" onchange="updateFileLabel(this)">
                        <label for="income_attachment" class="inline-block px-8 py-3 bg-white border border-slate-100 text-primary-light rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-slate-50 hover:text-accent hover:border-accent transition-all">
                            <span id="file-label">Escolher Arquivo</span>
                        </label>
                    </div>
                </div>

                <!-- Tab: Histórico -->
                <div id="content-income-controle" class="p-16 hidden tab-content text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-history text-4xl text-slate-100 mb-4"></i>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum histórico disponível.</p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-100 flex justify-between items-center bg-slate-50/30 shrink-0">
            <button type="button" onclick="closeFinanceModal('incomeModal')" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all">Descartar</button>
            <button type="button" onclick="$('#form-income').submit()" class="px-10 py-3 btn-neo btn-primary text-[10px] font-bold uppercase tracking-widest">
                <i class="fas fa-check-circle mr-2"></i> Registrar Receita
            </button>
        </div>
    </div>
</div>

<style>
    .modal-tab-clean { padding: 16px 20px; font-size: 10px; font-weight: 900; letter-spacing: 0.15em; color: #64748b; border-bottom: 3px solid transparent; transition: all 0.3s ease; text-transform: uppercase; }
    .modal-tab-clean.active { color: #f59e0b; border-bottom-color: #f59e0b; }
    
    .btn-toggle-clean { padding: 10px; font-size: 9px; font-weight: 800; letter-spacing: 0.1em; color: #64748b; background: #fff; border-radius: 12px; border: 1px solid #f1f5f9; transition: all 0.2s; text-transform: uppercase; }
    .btn-toggle-clean.active { background: #f59e0b; border-color: #f59e0b; color: #fff; box-shadow: 0 4px 12px -2px rgba(245, 158, 11, 0.3); }
    
    .btn-pin-clean { color: #cbd5e1; transition: all 0.2s; width: 20px; height: 20px; display: flex; items-center justify-center rounded-lg; }
    .btn-pin-clean.active { color: #fff; background: #f59e0b; transform: rotate(45deg); }
    
    .search-result-item { padding: 12px 20px; cursor: pointer; transition: all 0.2s; font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; border-bottom: 1px solid #f8fafc; }
    .search-result-item:hover { background: #f8fafc; color: #1e293b; }
</style>

<script>
    const pinnedFields = {};

    $(document).ready(function() {
        $('#member_search_input').on('keyup', function() {
            const query = $(this).val();
            if (query.length >= 3) {
                $.ajax({
                    url: "{{ route('admin.members.search') }}",
                    data: { q: query },
                    success: function(data) {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(member => {
                                html += `<div class="search-result-item" onclick="selectMember(${member.id}, '${member.name}')">${member.name}</div>`;
                            });
                        } else {
                            html = '<div class="p-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Registro não localizado</div>';
                        }
                        $('#member_results').html(html).removeClass('hidden');
                    }
                });
            } else {
                $('#member_results').addClass('hidden');
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#member_search_container').length) {
                $('#member_results').addClass('hidden');
            }
        });
    });

    function selectMember(id, name) {
        $('#selected_member_id').val(id);
        $('#member_search_input').val(name);
        $('#member_results').addClass('hidden');
    }

    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
        
        // Reset modal to "New" state if opening a generic finance modal
        if (id === 'incomeModal') {
            $('#form-income').attr('action', "{{ route('admin.finance.income.store') }}");
            $('#method-container').html('');
            $('#incomeModal .modal-title').text('Nova Receita');
            $('#incomeModal button[type="submit"]').html('<i class="fas fa-check-circle mr-2"></i> Registrar Receita');
            resetFormWithPins();
        }
        
        restorePinnedValues();
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
        resetFormWithPins();
    }

    function togglePin(fieldId) {
        const btn = $(`#pin-${fieldId}`);
        btn.toggleClass('active');
        if (btn.hasClass('active')) {
            pinnedFields[fieldId] = $(`[name="${fieldId}"]`).val();
        } else {
            delete pinnedFields[fieldId];
        }
    }

    function resetFormWithPins() {
        const form = $('#form-income')[0];
        const currentValues = {};
        Object.keys(pinnedFields).forEach(field => {
            currentValues[field] = $(`[name="${field}"]`).val();
        });
        form.reset();
        Object.keys(pinnedFields).forEach(field => {
            $(`[name="${field}"]`).val(currentValues[field]);
        });
        setDonorType('membro');
    }

    function restorePinnedValues() {
        Object.keys(pinnedFields).forEach(field => {
            if (pinnedFields[field]) {
                $(`[name="${field}"]`).val(pinnedFields[field]);
            }
        });
    }

    function updateFileLabel(input) {
        if (input.files.length > 0) {
            $('#file-label').text(input.files[0].name).addClass('text-emerald-600 font-bold');
        }
    }

    function setDonorType(type) {
        $('#donor_type').val(type);
        $('.btn-toggle-clean').removeClass('active');
        $(`#btn-type-${type}`).addClass('active');
        $('#field-membro, #field-visitante, #field-descricao').addClass('hidden');
        if (type === 'membro') { 
            $('#field-membro').removeClass('hidden'); 
        } else if (type === 'visitante') { 
            $('#field-visitante').removeClass('hidden'); 
        }
        $('#field-descricao').removeClass('hidden');
    }

    function switchTab(module, tab) {
        $(`#${module}Modal .modal-tab-clean`).removeClass('active');
        $(`#${module}Modal .tab-content`).addClass('hidden');
        $(`#tab-${module}-${tab}`).addClass('active');
        $(`#content-${module}-${tab}`).removeClass('hidden');
    }

    function editIncome(id) {
        openFinanceModal('incomeModal');
        
        // Feedback visual de carregamento
        $('#incomeModal .modal-title').text('Carregando...');
        
        $.get(`/admin/finance/income/${id}`, function(data) {
            $('#form-income').attr('action', `/admin/finance/income/${id}`);
            $('#method-container').html('<input type="hidden" name="_method" value="PUT">');
            
            // Preencher campos
            $('[name="transaction_date"]').val(data.transaction_date_formatted);
            $('[name="amount"]').val(data.amount.toString().replace('.', ',')).trigger('input');
            $('[name="financial_account_id"]').val(data.financial_account_id);
            $('[name="chart_of_account_id"]').val(data.chart_of_account_id);
            $('[name="payment_method"]').val(data.payment_method);
            $('[name="cost_center_id"]').val(data.cost_center_id);
            $('[name="description"]').val(data.description);
            $('[name="status"]').val(data.status);
            
            if (data.member_id) {
                setDonorType('membro');
                $('#selected_member_id').val(data.member_id);
                $('#member_search_input').val(data.member.name);
            } else if (data.description && data.description.includes('Visitante')) {
                setDonorType('visitante');
            } else {
                setDonorType('anonima');
            }
            
            $('#incomeModal .modal-title').text('Editar Receita #TX-' + String(data.id).padStart(6, '0'));
            $('#incomeModal button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Salvar Alterações');
        });
    }

    $(document).ready(function() {
        const ctxIncome = document.getElementById('incomeChartMini').getContext('2d');
        new Chart(ctxIncome, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
                datasets: [{
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });

        // DataTable pagination styling
        $('#incomeTable').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: true,
            columnDefs: [{ orderable: false, targets: 5 }]
        });
    });
</script>
@endpush
@endsection
