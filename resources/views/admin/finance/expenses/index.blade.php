@extends('layouts.app')

@section('title', 'Controle de Despesas / Saídas')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight uppercase">Controle de Despesas</h2>
            <p class="text-primary-light font-medium mt-1">Gestão de pagamentos, contas e saídas.</p>
        </div>
        <button onclick="openFinanceModal('expenseModal')" class="btn-neo btn-primary px-8 flex items-center gap-3">
            <i class="fas fa-plus text-xs"></i>
            <span class="font-black uppercase tracking-widest text-[11px]">Nova Despesa</span>
        </button>
    </div>

    <!-- Cards Informativos (Saídas) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Pago -->
        <div class="card-neo p-6 bg-white border-l-4 border-rose-500 shadow-sm group hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pago (Mês)</p>
                    <h3 class="text-2xl font-black text-primary-dark font-money tracking-tighter">R$ {{ number_format($stats['total_paid'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-rose-500 mt-2 flex items-center gap-1 uppercase italic">
                        <i class="fas fa-caret-down"></i> {{ $stats['total_count'] }} despesas
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>

        <!-- Contas a Pagar -->
        <div class="card-neo p-6 bg-white border-l-4 border-amber-500 shadow-sm group hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Contas a Pagar</p>
                    <h3 class="text-2xl font-black text-primary-dark font-money tracking-tighter">R$ {{ number_format($stats['total_pending'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-amber-500 mt-2 uppercase italic tracking-wider">Atenção ao Vecto.</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-exclamation"></i>
                </div>
            </div>
        </div>

        <!-- Gráfico Rápido (Saídas) -->
        <div class="card-neo md:col-span-2 p-6 bg-white shadow-sm flex flex-col justify-between overflow-hidden relative">
            <div class="flex justify-between items-center mb-4 relative z-10">
                <h4 class="text-[10px] font-black text-primary-dark uppercase tracking-[0.2em] italic">Fluxo de Saídas (15 dias)</h4>
                <div class="flex gap-1">
                    <div class="w-2 h-2 rounded-full bg-rose-400"></div>
                </div>
            </div>
            <div class="h-20 w-full relative z-10">
                <canvas id="expenseChartMini"></canvas>
            </div>
            <!-- Background Decoration -->
            <div class="absolute -right-4 -bottom-4 text-slate-50 opacity-10 text-8xl pointer-events-none">
                <i class="fas fa-chart-area"></i>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card-neo p-6 mb-6 bg-slate-50/20 backdrop-blur-md border-slate-100/50 shadow-sm">
        <form action="{{ route('admin.finance.expenses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-2 italic">Pesquisar</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="input-neo !py-2.5 w-full pl-10" placeholder="Fornecedor ou descrição...">
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
                <a href="{{ route('admin.finance.expenses.index') }}" class="btn-neo bg-white text-slate-400 hover:text-rose-500 !py-3 px-4 flex items-center justify-center" title="Limpar Filtros">
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
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pago</option>
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
        </form>
    </div>

    <!-- Tabela de Despesas -->
    <div class="card-neo overflow-hidden">
        <div class="p-6 pb-2 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-xs font-black text-primary-dark uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-list-ul text-accent"></i>
                Listagem de Saídas
            </h3>
        </div>
        <div class="p-4 pt-0">
            <table id="expenseTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição / Fornecedor</th>
                        <th>Conta/Cofre</th>
                        <th class="text-right">Valor</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $transaction)
                    <tr class="group cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="editExpense({{ $transaction->id }})">
                        <td class="font-money text-xs text-slate-500">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</td>
                        <td class="font-bold text-primary-dark uppercase text-[10px]">
                            {{ $transaction->description ?: 'Sem descrição' }}
                        </td>
                        <td class="text-[10px] font-medium text-slate-600">
                            {{ $transaction->financialAccount->name }}
                        </td>
                        <td class="text-right font-money text-sm font-black text-primary-dark">
                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="px-2 py-0.5 {{ $transaction->status == 'paid' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }} rounded text-[8px] font-black uppercase">
                                {{ $transaction->status == 'paid' ? 'Pago' : 'Pendente' }}
                            </span>
                        </td>
                        <td class="text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editExpense({{ $transaction->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-accent hover:text-white" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action bg-slate-50 text-slate-500 hover:bg-slate-800 hover:text-white" title="Comprovante">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">Nenhuma despesa encontrada.</td>
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

<!-- Modal Nova Despesa (Compacto e Padronizado) -->
@include('admin.finance.expenses.modal_create')

<style>
    .modal-tab {
        padding: 12px 24px;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: 0.15em;
        color: #94a3b8;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .modal-tab.active {
        color: #f59e0b; /* accent */
        border-bottom-color: #f59e0b;
    }
</style>

<script>
    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
        $('#form-expense')[0].reset();
    }
    function switchTab(module, tab) {
        $(`#${module}Modal .modal-tab`).removeClass('active');
        $(`#${module}Modal .tab-content`).addClass('hidden');
        $(`#tab-${module}-${tab}`).addClass('active');
        $(`#content-${module}-${tab}`).removeClass('hidden');
    }
    function editExpense(id) {
        openFinanceModal('expenseModal');
        $('#expenseModal h3').text('Editando Despesa #' + id);
        $('#form-expense').attr('action', `/admin/finance/expenses/${id}`);
        if ($('#form-expense input[name="_method"]').length === 0) {
            $('#form-expense').append('<input type="hidden" name="_method" value="PUT">');
        }
    }

    // Gráfico de Saídas
    $(document).ready(function() {
        const ctxExpense = document.getElementById('expenseChartMini').getContext('2d');
        new Chart(ctxExpense, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
                datasets: [{
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244, 63, 94, 0.1)',
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
