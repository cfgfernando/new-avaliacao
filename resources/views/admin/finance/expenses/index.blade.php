@extends('layouts.app')

@section('title', 'Controle de Despesas / Saídas')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Controle de Despesas</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gestão de Pagamentos e Contas a Pagar</p>
        </div>
        <button onclick="openFinanceModal('expenseModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVA DESPESA</span>
        </button>
    </div>

    <!-- Cards Informativos Elite V8 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Pago -->
        <div class="card-neo relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-1 h-full bg-rose-500/20"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Total Pago (Mês)</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money tracking-tight">R$ {{ number_format($stats['total_paid'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-black text-rose-500 mt-2 uppercase tracking-widest flex items-center gap-1">
                        <i class="fas fa-caret-down"></i> {{ $stats['total_count'] }} Despesas
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>

        <!-- Contas a Pagar -->
        <div class="card-neo relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-1 h-full bg-amber-500/20"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Contas a Pagar</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money tracking-tight">R$ {{ number_format($stats['total_pending'], 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-black text-amber-500 mt-2 uppercase tracking-widest">Atenção ao Vencimento</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                    <i class="fas fa-calendar-exclamation"></i>
                </div>
            </div>
        </div>

        <!-- Gráfico Elite V8 -->
        <div class="md:col-span-2 card-neo flex flex-col justify-between overflow-hidden relative">
            <div class="flex justify-between items-center mb-2 relative z-10">
                <h4 class="text-[9px] font-black text-primary-light uppercase tracking-widest">Fluxo de Saídas (15 dias)</h4>
                <div class="w-2 h-2 rounded-full bg-rose-500"></div>
            </div>
            <div class="h-20 w-full relative z-10">
                <canvas id="expenseChartMini"></canvas>
            </div>
            <div class="absolute -right-2 -bottom-2 text-slate-50 opacity-10 text-6xl pointer-events-none transform -rotate-12">
                <i class="fas fa-chart-line text-rose-500"></i>
            </div>
        </div>
    </div>

    <!-- Filtros Elite V8 -->
    <div class="card-neo bg-slate-50/50">
        <form action="{{ route('admin.finance.expenses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Busca Geral</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="input-neo !pl-10" placeholder="Fornecedor ou descrição...">
                    <div class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Período De</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-neo">
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Período Até</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-neo">
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Conta</label>
                <select name="financial_account_id" class="input-neo uppercase">
                    <option value="">Todas as Contas</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('financial_account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-neo btn-primary text-[10px] py-3.5">
                    <i class="fas fa-filter text-[9px]"></i> FILTRAR
                </button>
                <a href="{{ route('admin.finance.expenses.index') }}" class="w-12 h-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-white hover:text-accent hover:border-accent transition-all shadow-sm group">
                    <i class="fas fa-undo-alt text-[10px]"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Relatório Analítico de Saídas</h3>
        </div>
        <div class="p-4">
            <table id="expenseTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Descrição / Fornecedor</th>
                        <th class="px-6 py-4">Conta / Cofre</th>
                        <th class="px-6 py-4 text-right">Valor</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $transaction)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="editExpense({{ $transaction->id }})">
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-800 uppercase">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 leading-tight mb-0.5 group-hover:text-accent transition-colors">
                                    {{ $transaction->description ?: 'Sem descrição técnica' }}
                                </span>
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $transaction->chartOfAccount->name ?? 'Sem Categoria' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-rose-400 shadow-sm shadow-rose-400/50"></div>
                                <span class="text-[10px] font-black text-primary-light uppercase tracking-widest">{{ $transaction->financialAccount->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right bg-slate-50/30">
                            <span class="text-sm font-money text-slate-800">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClasses = match($transaction->status) {
                                    'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'approved' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'pending_approval' => 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse',
                                    'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100'
                                };
                                $statusLabel = match($transaction->status) {
                                    'paid' => 'Pago',
                                    'approved' => 'Aprovado',
                                    'pending_approval' => 'Aguardando Aprovação',
                                    'rejected' => 'Rejeitado',
                                    'pending' => 'Pendente',
                                    default => $transaction->status
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $statusClasses }}">
                                {{ $statusLabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                <button onclick="editExpense({{ $transaction->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Visualizar">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-800 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Recibo">
                                    <i class="fas fa-print text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center bg-slate-50/30">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-database text-4xl text-slate-200 mb-4"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhuma despesa registrada.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação Elite V8 -->
        @if($transactions->hasPages())
        <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Nova Despesa -->
@include('admin.finance.expenses.modal_create')
@endpush

@push('scripts')
<script>
    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
        $('#form-expense')[0].reset();
        $('#expenseModal h3').text('Nova Despesa');
        $('#form-expense').attr('action', `{{ route('admin.finance.expenses.store') }}`);
        $('#method-container-exp').html('');
    }
    function switchTab(module, tab) {
        $(`#${module}Modal .modal-tab-clean`).removeClass('active');
        $(`#${module}Modal .tab-content`).addClass('hidden');
        $(`#tab-${module}-${tab}`).addClass('active');
        $(`#content-${module}-${tab}`).removeClass('hidden');
    }
    function editExpense(id) {
        openFinanceModal('expenseModal');
        
        // Feedback visual de carregamento
        $('#expenseModal h3').text('Carregando...');
        
        $.get(`/admin/finance/expenses/${id}`, function(data) {
            $('#form-expense').attr('action', `/admin/finance/expenses/${id}`);
            $('#method-container-exp').html('<input type="hidden" name="_method" value="PUT">');
            
            // Preencher campos
            $('[name="transaction_date"]').val(data.transaction_date_formatted);
            $('[name="amount"]').val(data.amount.toString().replace('.', ',')).trigger('input');
            $('[name="financial_account_id"]').val(data.financial_account_id);
            $('[name="chart_of_account_id"]').val(data.chart_of_account_id);
            $('[name="payment_method"]').val(data.payment_method);
            $('[name="cost_center_id"]').val(data.cost_center_id);
            $('[name="description"]').val(data.description);
            $('[name="status"]').val(data.status);
            
            $('#expenseModal h3').text('Editar Despesa #TX-' + String(data.id).padStart(6, '0'));
            $('#expenseModal button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Salvar Alterações');
        });
    }

    $(document).ready(function() {
        const ctxExpense = document.getElementById('expenseChartMini').getContext('2d');
        new Chart(ctxExpense, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
                datasets: [{
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244, 63, 94, 0.05)',
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
        $('#expenseTable').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: true,
            columnDefs: [{ orderable: false, targets: 5 }]
        });
    });
</script>
@endpush
