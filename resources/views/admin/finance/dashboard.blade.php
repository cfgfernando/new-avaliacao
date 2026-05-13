@extends('layouts.app')

@section('title', 'Dashboard Financeiro')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Dashboard Financeiro</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Visão geral do fluxo de caixa e conformidade</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.finance.transactions.index') }}" class="btn-neo btn-primary text-xs py-2.5">
                <i class="fas fa-list-ul"></i>
                <span>MOVIMENTAÇÕES</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid Elite V8 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Saldo Total -->
        <div class="card-neo group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Saldo Consolidado</p>
                    <h3 class="text-2xl font-black text-slate-800 font-money">R$ {{ number_format($totalBalance, 2, ',', '.') }}</h3>
                    <p class="text-[10px] text-primary-light mt-2 font-bold uppercase tracking-widest">Disponibilidade Total</p>
                </div>
                <div class="stats-icon bg-slate-50 text-slate-400 group-hover:bg-accent group-hover:text-white">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Entradas do Mês -->
        <div class="card-neo group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Entradas (Mês)</p>
                    <h3 class="text-2xl font-black text-emerald-600 font-money">R$ {{ number_format($monthlyIncome, 2, ',', '.') }}</h3>
                    <p class="text-[10px] text-emerald-500 mt-2 font-bold uppercase tracking-widest">Receitas do Período</p>
                </div>
                <div class="stats-icon bg-emerald-50/50 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>

        <!-- Saídas do Mês -->
        <div class="card-neo group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Saídas (Mês)</p>
                    <h3 class="text-2xl font-black text-rose-600 font-money">R$ {{ number_format($monthlyExpense, 2, ',', '.') }}</h3>
                    <p class="text-[10px] text-rose-500 mt-2 font-bold uppercase tracking-widest">Custos Operacionais</p>
                </div>
                <div class="stats-icon bg-rose-50/50 text-rose-500 group-hover:bg-rose-500 group-hover:text-white">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>

        <!-- Malotes Pendentes -->
        <div class="card-neo group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Malotes Pendentes</p>
                    <h3 class="text-2xl font-black text-amber-600 font-money">{{ $pendingBatches }}</h3>
                    <p class="text-[10px] text-amber-500 mt-2 font-bold uppercase tracking-widest">Aguardando Conferência</p>
                </div>
                <div class="stats-icon bg-amber-50/50 text-amber-500 group-hover:bg-amber-500 group-hover:text-white">
                    <i class="fas fa-archive"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Elite V8 Table -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Últimas Movimentações Técnicas</h3>
            <a href="{{ route('admin.finance.transactions.index') }}" class="px-4 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">Ver Histórico</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-slate-50">
                    @foreach($recentTransactions as $tx)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center border border-slate-100 shadow-sm {{ $tx->type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    <i class="fas {{ $tx->type === 'income' ? 'fa-plus' : 'fa-minus' }} text-xs"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800 leading-tight mb-0.5">{{ $tx->description }}</span>
                                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $tx->chartOfAccount->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $tx->financialAccount->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="text-base font-money {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                R$ {{ number_format($tx->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="inline-block px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M') }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
