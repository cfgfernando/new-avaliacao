@extends('layouts.app')

@section('title', 'Controle de Ofertas / Receitas')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight uppercase">Controle de Receitas</h2>
            <p class="text-primary-light font-medium mt-1">Gestão de entradas, dízimos e ofertas.</p>
        </div>
        <button onclick="openFinanceModal('incomeModal')" class="btn-neo btn-primary px-8 flex items-center gap-3">
            <i class="fas fa-plus text-xs"></i>
            <span class="font-black uppercase tracking-widest text-[11px]">Nova Receita</span>
        </button>
    </div>

    <!-- Cards Informativos -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Recebido -->
        <div class="card-neo p-6 bg-white border-l-4 border-emerald-500 shadow-sm group hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Recebido (Mês)</p>
                    <h3 class="text-2xl font-black text-primary-dark font-money tracking-tighter">R$ {{ number_format($stats['total_paid'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-emerald-500 mt-2 flex items-center gap-1 uppercase italic">
                        <i class="fas fa-caret-up"></i> +{{ $stats['total_count'] }} lançamentos
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
            </div>
        </div>

        <!-- Pendente -->
        <div class="card-neo p-6 bg-white border-l-4 border-amber-500 shadow-sm group hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Aguardando Efetivação</p>
                    <h3 class="text-2xl font-black text-primary-dark font-money tracking-tighter">R$ {{ number_format($stats['total_pending'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-amber-500 mt-2 uppercase italic tracking-wider">Atenção Necessária</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- Gráfico Rápido (Mesclado) -->
        <div class="card-neo md:col-span-2 p-6 bg-white shadow-sm flex flex-col justify-between overflow-hidden relative">
            <div class="flex justify-between items-center mb-4 relative z-10">
                <h4 class="text-[10px] font-black text-primary-dark uppercase tracking-[0.2em] italic">Evolução de Entradas (15 dias)</h4>
                <div class="flex gap-1">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                </div>
            </div>
            <div class="h-20 w-full relative z-10">
                <canvas id="incomeChartMini"></canvas>
            </div>
            <!-- Background Decoration -->
            <div class="absolute -right-4 -bottom-4 text-slate-50 opacity-10 text-8xl pointer-events-none">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card-neo p-6 mb-6 bg-slate-50/20 backdrop-blur-md border-slate-100/50 shadow-sm">
        <form action="{{ route('admin.finance.income.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Pesquisar</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="input-neo !py-2.5 w-full pl-10" placeholder="Membro ou descrição...">
                    <div class="absolute inset-y-0 left-4 flex items-center text-slate-300 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Início</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-neo !py-2.5 w-full font-bold">
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Fim</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-neo !py-2.5 w-full font-bold">
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Conta</label>
                <select name="financial_account_id" class="input-neo !py-2.5 w-full font-bold italic">
                    <option value="">Todas</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('financial_account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-neo btn-primary flex-1 !py-3 flex items-center justify-center gap-2">
                    <i class="fas fa-filter text-[10px]"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Filtrar</span>
                </button>
                <a href="{{ route('admin.finance.income.index') }}" class="btn-neo bg-white text-slate-400 hover:text-rose-500 !py-3 px-4 flex items-center justify-center" title="Limpar Filtros">
                    <i class="fas fa-undo-alt text-[10px]"></i>
                </a>
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Categoria</label>
                <select name="chart_of_account_id" class="input-neo !py-2.5 w-full font-bold italic">
                    <option value="">Todas</option>
                    @foreach($chartOfAccounts as $coa)
                    <option value="{{ $coa->id }}" {{ request('chart_of_account_id') == $coa->id ? 'selected' : '' }}>{{ $coa->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Status</label>
                <select name="status" class="input-neo !py-2.5 w-full font-bold italic">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Efetivado</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Meio Pagto</label>
                <select name="payment_method" class="input-neo !py-2.5 w-full font-bold italic">
                    <option value="">Todos</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Dinheiro</option>
                    <option value="pix" {{ request('payment_method') == 'pix' ? 'selected' : '' }}>PIX</option>
                    <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Cartão</option>
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Ver</label>
                <select name="per_page" class="input-neo !py-2.5 w-full font-bold italic" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 itens</option>
                    <option value="20" {{ request('per_page') == 20 || !request('per_page') ? 'selected' : '' }}>20 itens</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 itens</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 itens</option>
                </select>
            </div>
            </div>
        </form>
    </div>
    <div class="card-neo overflow-hidden">
        <div class="p-6 pb-2 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-xs font-black text-primary-dark uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-list-ul text-accent"></i>
                Listagem de Entradas
            </h3>
        </div>
        <div class="p-4 pt-0">
            <table id="incomeTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Doador/Membro</th>
                        <th>Conta/Cofre</th>
                        <th class="text-right">Valor</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $transaction)
                    <tr class="group cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="editIncome({{ $transaction->id }})">
                        <td class="font-money text-xs text-slate-500">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</td>
                        <td class="font-bold text-primary-dark uppercase text-[10px]">
                            {{ $transaction->member->name ?? ($transaction->description ?: 'Doador Anônimo') }}
                        </td>
                        <td class="text-[10px] font-medium text-slate-600">
                            {{ $transaction->financialAccount->name }}
                        </td>
                        <td class="text-right font-money text-sm font-black text-primary-dark">
                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="px-2 py-0.5 {{ $transaction->status == 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} rounded text-[8px] font-black uppercase">
                                {{ $transaction->status == 'paid' ? 'Efetivado' : 'Pendente' }}
                            </span>
                        </td>
                        <td class="text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editIncome({{ $transaction->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-accent hover:text-white" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action bg-slate-50 text-slate-500 hover:bg-slate-800 hover:text-white" title="Recibo">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">Nenhuma receita encontrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-50">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<!-- Modal Nova Receita -->
<div id="incomeModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-xl overflow-hidden animate-reveal-up flex flex-col max-h-[95vh]">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent shadow-sm border border-accent/20">
                    <i class="fas fa-arrow-trend-up text-sm"></i>
                </div>
                <h3 class="text-lg font-black text-primary-dark uppercase tracking-tight">Nova Receita</h3>
            </div>
            <button onclick="closeFinanceModal('incomeModal')" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-rose-50 text-slate-300 hover:text-rose-500 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-slate-50 px-6 bg-white shrink-0">
            <button onclick="switchTab('income', 'perfil')" id="tab-income-perfil" class="modal-tab active">PERFIL</button>
            <button onclick="switchTab('income', 'ged')" id="tab-income-ged" class="modal-tab">GED</button>
            <button onclick="switchTab('income', 'controle')" id="tab-income-controle" class="modal-tab">HISTÓRICO</button>
        </div>

        <!-- Scrollable Content -->
        <div class="overflow-y-auto flex-grow">
            <form action="{{ route('admin.finance.income.store') }}" method="POST" id="form-income" class="p-0" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" value="paid">
                <input type="hidden" name="donor_type" id="donor_type" value="membro">
                <input type="hidden" name="member_id" id="selected_member_id">
                
                <!-- Tab: Perfil -->
                <div id="content-income-perfil" class="p-6 space-y-4 tab-content">
                    
                    <!-- Valor e Data -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Valor Recebido (R$)</label>
                            <input type="text" name="amount" required class="input-neo !py-2.5 w-full font-money text-left font-black text-lg" placeholder="0,00">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Data Entrada</label>
                            <input type="date" name="transaction_date" required class="input-neo !py-2.5 w-full font-black text-primary-dark" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <!-- Toggle Type -->
                    <div class="grid grid-cols-4 gap-2 pt-2">
                        <button type="button" onclick="setDonorType('membro')" class="btn-toggle active" id="btn-type-membro">MEMBRO</button>
                        <button type="button" onclick="setDonorType('visitante')" class="btn-toggle" id="btn-type-visitante">VISITANTE</button>
                        <button type="button" onclick="setDonorType('anonima')" class="btn-toggle" id="btn-type-anonima">ANÔNIMA</button>
                        <button type="button" onclick="setDonorType('diversas')" class="btn-toggle" id="btn-type-diversas">DIVERSAS</button>
                    </div>

                    <!-- Busca de Membro Estilo Imagem -->
                    <div id="field-membro">
                        <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Membro Associado</label>
                        <div class="relative" id="member_search_container">
                            <input type="text" id="member_search_input" class="input-neo !py-3 w-full pl-12 font-black italic text-primary-dark" placeholder="Pesquisar..." autocomplete="off">
                            <div class="absolute inset-y-0 left-5 flex items-center text-slate-300 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </div>
                            <!-- Resultados Flutuantes -->
                            <div id="member_results" class="hidden absolute top-full left-0 w-full bg-white mt-2 rounded-2xl shadow-xl border border-slate-100 z-[110] max-h-48 overflow-y-auto py-2">
                                <!-- Ajax results here -->
                            </div>
                        </div>
                    </div>

                    <div id="field-visitante" class="hidden">
                        <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Identificação Doador</label>
                        <input type="text" name="visitor_name" class="input-neo !py-2.5 w-full italic font-black text-primary-dark" placeholder="Nome do doador...">
                    </div>

                    <!-- Conta e Plano com PIN -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-1.5 px-1">
                                <label class="text-[9px] font-black text-primary-light uppercase tracking-[0.2em] italic">Conta / Cofre</label>
                                <button type="button" onclick="togglePin('financial_account_id')" id="pin-financial_account_id" class="btn-pin">
                                    <i class="fas fa-thumbtack text-[8px]"></i>
                                </button>
                            </div>
                            <select name="financial_account_id" required class="input-neo !py-2.5 w-full font-black italic pinable">
                                <option value="">Selecione...</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-1.5 px-1">
                                <label class="text-[9px] font-black text-primary-light uppercase tracking-[0.2em] italic">Plano de Contas</label>
                                <button type="button" onclick="togglePin('chart_of_account_id')" id="pin-chart_of_account_id" class="btn-pin">
                                    <i class="fas fa-thumbtack text-[8px]"></i>
                                </button>
                            </div>
                            <select name="chart_of_account_id" required class="input-neo !py-2.5 w-full font-black italic pinable">
                                <option value="">Selecione...</option>
                                @foreach($chartOfAccounts as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Forma e Centro de Custo -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-1.5 px-1">
                                <label class="text-[9px] font-black text-primary-light uppercase tracking-[0.2em] italic">Forma Pagto.</label>
                                <button type="button" onclick="togglePin('payment_method')" id="pin-payment_method" class="btn-pin">
                                    <i class="fas fa-thumbtack text-[8px]"></i>
                                </button>
                            </div>
                            <select name="payment_method" required class="input-neo !py-2.5 w-full font-black italic pinable">
                                <option value="cash">Dinheiro</option>
                                <option value="pix">PIX / Transferência</option>
                                <option value="credit_card">Cartão de Crédito</option>
                            </select>
                        </div>
                        <div class="relative group/pin">
                            <div class="flex justify-between items-center mb-1.5 px-1">
                                <label class="text-[9px] font-black text-primary-light uppercase tracking-[0.2em] italic">Centro de Custo</label>
                                <button type="button" onclick="togglePin('cost_center_id')" id="pin-cost_center_id" class="btn-pin">
                                    <i class="fas fa-thumbtack text-[8px]"></i>
                                </button>
                            </div>
                            <select name="cost_center_id" class="input-neo !py-2.5 w-full font-black italic pinable">
                                @foreach($costCenters as $cc)
                                <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Célula -->
                    <div>
                        <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Célula / Grupo (Se houver)</label>
                        <select name="cell_id" class="input-neo !py-2.5 w-full font-black italic">
                            <option value="">Nenhuma Selecionada</option>
                            @foreach($cells as $cell)
                            <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="field-descricao">
                        <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Descrição da Receita (Opcional)</label>
                        <input type="text" name="description" class="input-neo !py-2.5 w-full font-black italic text-primary-dark" placeholder="Observações extras...">
                    </div>
                </div>

                <!-- Tab: GED -->
                <div id="content-income-ged" class="p-8 hidden tab-content">
                    <div class="border-2 border-dashed border-slate-100 rounded-3xl p-10 text-center space-y-4 bg-slate-50/30">
                        <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mx-auto text-slate-300">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                        </div>
                        <input type="file" name="attachment" id="income_attachment" class="hidden" onchange="updateFileLabel(this)">
                        <label for="income_attachment" class="btn-neo btn-primary !py-2.5 !px-8 !text-[9px] cursor-pointer inline-block">
                            <span id="file-label">Selecionar Arquivo</span>
                        </label>
                    </div>
                </div>

                <!-- Tab: Histórico -->
                <div id="content-income-controle" class="p-8 hidden tab-content text-center py-20">
                    <i class="fas fa-history text-4xl text-slate-100 mb-4"></i>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lançamentos recentes</p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-50 flex justify-between items-center bg-white shrink-0">
            <button type="button" onclick="closeFinanceModal('incomeModal')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500">CANCELAR</button>
            <button type="submit" form="form-income" class="btn-neo btn-primary px-10 py-3.5 !rounded-xl flex items-center gap-2 group transition-all">
                <span class="text-[10px] font-black uppercase tracking-widest">REGISTRAR RECEITA</span>
                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </div>
</div>

<style>
    .modal-tab { padding: 12px 24px; font-size: 9px; font-weight: 900; letter-spacing: 0.15em; color: #94a3b8; border-bottom: 2px solid transparent; transition: all 0.3s ease; }
    .modal-tab.active { color: #f59e0b; border-bottom-color: #f59e0b; }
    .btn-toggle { padding: 10px; font-size: 8px; font-weight: 900; letter-spacing: 0.1em; color: #64748b; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; transition: all 0.2s; }
    .btn-toggle.active { background: #fffbeb; border-color: #f59e0b; color: #b45309; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.1); }
    .btn-pin { color: #cbd5e1; transition: all 0.2s; padding: 2px; }
    .btn-pin.active { color: #f59e0b; transform: rotate(45deg); }
    .search-result-item { padding: 12px 20px; cursor: pointer; transition: all 0.2s; font-size: 11px; font-weight: 800; color: #1e293b; text-transform: uppercase; font-style: italic; border-bottom: 1px solid #f8fafc; }
    .search-result-item:hover { background: #fffbeb; color: #b45309; }
</style>

<script>
    const pinnedFields = {};

    $(document).ready(function() {
        // Lógica de Busca Customizada (Estilo Imagem)
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
                            html = '<div class="p-4 text-center text-[10px] font-bold text-slate-400 uppercase italic">Nenhum membro encontrado</div>';
                        }
                        $('#member_results').html(html).removeClass('hidden');
                    }
                });
            } else {
                $('#member_results').addClass('hidden');
            }
        });

        // Fechar resultados ao clicar fora
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
            $('#file-label').text(input.files[0].name).addClass('text-emerald-500 font-black');
        }
    }

    function setDonorType(type) {
        $('#donor_type').val(type);
        $('.btn-toggle').removeClass('active');
        $(`#btn-type-${type}`).addClass('active');
        $('#field-membro, #field-visitante, #field-descricao').addClass('hidden');
        
        if (type === 'membro') { 
            $('#field-membro').removeClass('hidden'); 
            $('#field-descricao').removeClass('hidden'); 
        } else if (type === 'visitante') { 
            $('#field-visitante').removeClass('hidden'); 
            $('#field-descricao').removeClass('hidden'); 
        } else if (type === 'anonima' || type === 'diversas') { 
            $('#field-descricao').removeClass('hidden'); 
        }
    }

    function switchTab(module, tab) {
        $(`#${module}Modal .modal-tab`).removeClass('active');
        $(`#${module}Modal .tab-content`).addClass('hidden');
        $(`#tab-${module}-${tab}`).addClass('active');
        $(`#content-${module}-${tab}`).removeClass('hidden');
    }

    function editIncome(id) {
        openFinanceModal('incomeModal');
        // Logic for edit can be added here
    }

    // Gráfico de Entradas
    $(document).ready(function() {
        const ctxIncome = document.getElementById('incomeChartMini').getContext('2d');
        new Chart(ctxIncome, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
                datasets: [{
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
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
    });
</script>
@endsection
