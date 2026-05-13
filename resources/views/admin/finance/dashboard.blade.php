@extends('layouts.app')

@section('title', 'Dashboard Financeiro')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">DASHBOARD FINANCEIRO</h2>
            <p class="text-primary-light font-medium mt-1">Visão geral do Módulo de Compliance e Malotes.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Saldo Total -->
        <div class="card-neo p-6 border-l-4 border-primary">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Saldo em Contas</p>
                    <h3 class="text-2xl font-black text-primary-dark tracking-tighter">R$ {{ number_format($totalBalance, 2, ',', '.') }}</h3>
                </div>
                <div class="p-2 bg-primary/5 rounded-xl text-primary">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Entradas do Mês -->
        <div class="card-neo p-6 border-l-4 border-emerald-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Entradas (Mês)</p>
                    <h3 class="text-2xl font-black text-emerald-600 tracking-tighter">R$ {{ number_format($monthlyIncome, 2, ',', '.') }}</h3>
                </div>
                <div class="p-2 bg-emerald-50 rounded-xl text-emerald-500">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Saídas do Mês -->
        <div class="card-neo p-6 border-l-4 border-rose-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Saídas (Mês)</p>
                    <h3 class="text-2xl font-black text-rose-600 tracking-tighter">R$ {{ number_format($monthlyExpense, 2, ',', '.') }}</h3>
                </div>
                <div class="p-2 bg-rose-50 rounded-xl text-rose-500">
                    <i class="fas fa-arrow-down text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Malotes Pendentes -->
        <div class="card-neo p-6 border-l-4 border-amber-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-1">Malotes Pendentes</p>
                    <h3 class="text-2xl font-black text-amber-600 tracking-tighter">{{ $pendingBatches }}</h3>
                </div>
                <div class="p-2 bg-amber-50 rounded-xl text-amber-500">
                    <i class="fas fa-archive text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 gap-6">
        <div class="card-neo overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                <h3 class="text-xs font-black text-primary-dark uppercase tracking-widest">Últimas Movimentações</h3>
                <a href="{{ route('admin.finance.transactions.index') }}" class="text-[10px] font-black text-primary hover:underline uppercase">Ver Todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentTransactions as $tx)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $tx->type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        <i class="fas {{ $tx->type === 'income' ? 'fa-plus' : 'fa-minus' }} text-[10px]"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-primary-dark uppercase tracking-tight">{{ $tx->description }}</span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase">{{ $tx->chartOfAccount->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $tx->financialAccount->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs font-black {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    R$ {{ number_format($tx->amount, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
