@extends('layouts.app')

@section('title', 'Zona de Risco - Contas a Pagar Vencidas')

@section('content')
<div class="space-y-6 animate-reveal-up pb-20">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-rose-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
                    <i class="fas fa-biohazard text-sm"></i>
                </span>
                Zona de Risco
            </h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Alertas Ativos & Gestão de Crise</p>
        </div>
        <button onclick="openFinanceModal('expenseModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVA DESPESA</span>
        </button>
    </div>

    <!-- Stats & Chart Row (Compact) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Stats Column -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Crítico -->
            <div class="card-neo p-5 bg-white relative group transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Qtd. Vencidos</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $stats['critical_count'] }}</h3>
                        <p class="text-[9px] font-bold text-rose-500 mt-2 uppercase italic tracking-wider flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-rose-500 animate-ping"></span> Crítico
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl group-hover:bg-rose-500 group-hover:text-white transition-all">
                        <i class="fas fa-skull-crossbones"></i>
                    </div>
                </div>
            </div>

            <!-- Total em Atraso -->
            <div class="card-neo p-5 bg-white relative group transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Total Vencido</p>
                        <h3 class="text-2xl font-black text-rose-600 font-money tracking-tighter italic">R$ {{ number_format($stats['total_overdue'], 2, ',', '.') }}</h3>
                        <p class="text-[9px] font-bold text-slate-500 mt-2 uppercase italic">Impacto Imediato</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl group-hover:text-rose-500 transition-all">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Column -->
        <div class="lg:col-span-3 card-neo p-6 bg-white flex flex-col justify-between">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em] italic">Top Categorias em Atraso</h4>
                <div class="text-[9px] font-bold text-slate-400 uppercase italic">Concentração de Risco</div>
            </div>
            <div class="flex-grow h-40">
                <canvas id="riskChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filtros Inteligentes (Elite V8 Standard) -->
    <div class="card-neo p-5 bg-slate-50/20 backdrop-blur-md border-slate-100/50">
        <form action="{{ route('admin.finance.expenses.risk-zone') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-1.5 px-1 italic">Pesquisar Descrição</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="input-neo !py-2 w-full pl-9 text-xs" placeholder="Ex: Copel, Fornecedor...">
                    <div class="absolute inset-y-0 left-3.5 flex items-center text-slate-300 pointer-events-none">
                        <i class="fas fa-search text-[10px]"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-1.5 px-1 italic">Conta</label>
                <select name="financial_account_id" class="input-neo !py-2 w-full text-xs font-bold italic">
                    <option value="">Todas</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('financial_account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-1.5 px-1 italic">Categoria</label>
                <select name="chart_of_account_id" class="input-neo !py-2 w-full text-xs font-bold italic">
                    <option value="">Todas</option>
                    @foreach($chartOfAccounts as $coa)
                    <option value="{{ $coa->id }}" {{ request('chart_of_account_id') == $coa->id ? 'selected' : '' }}>{{ $coa->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-1.5 px-1 italic">Vecto. De</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-neo !py-2 w-full text-xs font-bold">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-neo btn-primary flex-1 !py-2 flex items-center justify-center gap-2">
                    <i class="fas fa-filter text-[9px]"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Filtrar</span>
                </button>
                <a href="{{ route('admin.finance.expenses.risk-zone') }}" class="btn-neo bg-white text-slate-400 hover:text-rose-500 !py-2 px-3 flex items-center justify-center border-slate-200" title="Limpar">
                    <i class="fas fa-undo-alt text-[9px]"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Alerta Máximo (Compact) -->
    <div class="card-neo overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] flex items-center gap-3 italic">
                <i class="fas fa-shield-virus text-rose-500"></i>
                Listagem de Débitos em Atraso
            </h3>
            <div class="flex items-center gap-4">
                 <select name="per_page" class="input-neo !py-1.5 !px-3 text-[9px] font-black uppercase tracking-widest border-slate-200" onchange="window.location.href='{{ route('admin.finance.expenses.risk-zone') }}?per_page=' + this.value">
                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 itens</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 itens</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 itens</option>
                 </select>
            </div>
        </div>
        
        <div class="p-4 pt-0">
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Vecto.</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Descrição / Fornecedor</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Dias Atraso</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-right">Valor</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($overdueTransactions as $transaction)
                    <tr class="group hover:bg-rose-50/30 transition-all duration-300 cursor-pointer">
                        <td class="p-5">
                            <span class="font-money text-rose-600 font-black text-xs">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</span>
                        </td>
                        <td class="p-5">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-800 uppercase text-[10px] tracking-tight">{{ $transaction->description ?: 'Fornecedor não identificado' }}</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-0.5 italic tracking-widest">{{ $transaction->chartOfAccount->name }}</span>
                            </div>
                        </td>
                        <td class="p-5 text-center">
                            @php 
                                $days = \Carbon\Carbon::parse($transaction->transaction_date)->diffInDays(now());
                            @endphp
                            <span class="px-2 py-1 bg-rose-50 text-rose-600 rounded-lg text-[8px] font-black uppercase tracking-widest border border-rose-100">
                                {{ $days }} DIAS
                            </span>
                        </td>
                        <td class="p-5 text-right">
                            <span class="font-money text-xs font-black text-slate-800">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</span>
                        </td>
                        <td class="p-5">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                <button class="btn-action !w-8 !h-8 bg-slate-50 text-slate-400 hover:bg-rose-600 hover:text-white transition-all">
                                    <i class="fas fa-dollar-sign text-[10px]"></i>
                                </button>
                                <button class="btn-action !w-8 !h-8 bg-slate-50 text-slate-400 hover:bg-accent-dark hover:text-white transition-all">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center">
                            <div class="flex flex-col items-center gap-3 opacity-20">
                                <i class="fas fa-shield-check text-5xl text-emerald-500"></i>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em]">Nenhuma conta em atraso.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação (Standardized) -->
        <div class="px-8 py-4 bg-slate-50/30 border-t border-slate-50">
            {{ $overdueTransactions->links() }}
        </div>
    </div>
</div>

<!-- Modal Nova Despesa -->
@include('admin.finance.expenses.modal_create')

<style>
    .font-money { font-family: 'JetBrains Mono', monospace !important; }
    .animate-reveal-up { animation: revealUp 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes revealUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
    }
    function switchTab(module, tab) {
        $(`#${module}Modal .modal-tab`).removeClass('active');
        $(`#${module}Modal .tab-content`).addClass('hidden');
        $(`#tab-${module}-${tab}`).addClass('active');
        $(`#content-${module}-${tab}`).removeClass('hidden');
    }

    // Gráfico de Risco (Top Categorias)
    $(document).ready(function() {
        const ctxRisk = document.getElementById('riskChart').getContext('2d');
        new Chart(ctxRisk, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData->pluck('category')->map(fn($c) => strtoupper(substr($c, 0, 15)))) !!},
                datasets: [{
                    label: 'Total em Atraso (R$)',
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    backgroundColor: 'rgba(244, 63, 94, 0.8)',
                    borderColor: '#f43f5e',
                    borderWidth: 0,
                    borderRadius: 8,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { size: 10, weight: 'bold' },
                        bodyFont: { size: 10 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 9, weight: '900' }, color: '#94a3b8' }
                    },
                    y: { 
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 8, weight: '700' }, color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endsection
