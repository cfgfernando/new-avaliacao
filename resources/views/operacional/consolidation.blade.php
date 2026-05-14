@extends('layouts.app')

@section('title', 'Consolidação Mensal — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Consolidação Mensal</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                Resumo de performance e fechamento: <span class="text-slate-800">{{ $date->translatedFormat('F / Y') }}</span>
            </p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('consolidation') }}" method="GET" class="flex items-center gap-2">
                <select name="month" class="input-neo py-2 text-[10px] font-black uppercase tracking-widest min-w-[120px]">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $date->month == $m ? 'selected' : '' }}>
                            {{ ucfirst(\Carbon\Carbon::create(null, $m)->translatedFormat('F')) }}
                        </option>
                    @endforeach
                </select>
                <select name="year" class="input-neo py-2 text-[10px] font-black uppercase tracking-widest min-w-[80px]">
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ $date->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-neo bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5">
                    Filtrar
                </button>
            </form>
        </div>
    </div>

    {{-- MAIN STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- CRESCIMENTO --}}
        <div class="card-neo p-8 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 pb-4">Crescimento MDA</h3>
            <div class="space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Novos Discípulos</p>
                        @php $growth = $prevStats['members_new'] > 0 ? (($stats['members_new'] - $prevStats['members_new']) / $prevStats['members_new'] * 100) : 0; @endphp
                        <span class="text-[9px] font-black {{ $growth >= 0 ? 'text-emerald-500' : 'text-rose-500' }} uppercase">
                            <i class="fas {{ $growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i> {{ abs(round($growth, 1)) }}%
                        </span>
                    </div>
                    <div class="flex items-end gap-3">
                        <p class="text-4xl font-black text-slate-800 tracking-tighter">{{ $stats['members_new'] }}</p>
                        <p class="text-xs font-bold text-slate-400 mb-1">este mês</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Mês Anterior</p>
                        <p class="text-lg font-black text-slate-600 tracking-tighter">{{ $prevStats['members_new'] }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Ativos</p>
                        <p class="text-lg font-black text-slate-600 tracking-tighter">{{ $stats['members_total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FINANCEIRO (OFERTAS) --}}
        <div class="card-neo p-8 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 pb-4">Financeiro Células</h3>
            <div class="space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total em Ofertas</p>
                        @php $finGrowth = $prevStats['offers_total'] > 0 ? (($stats['offers_total'] - $prevStats['offers_total']) / $prevStats['offers_total'] * 100) : 0; @endphp
                        <span class="text-[9px] font-black {{ $finGrowth >= 0 ? 'text-emerald-500' : 'text-rose-500' }} uppercase">
                            <i class="fas {{ $finGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i> {{ abs(round($finGrowth, 1)) }}%
                        </span>
                    </div>
                    <div class="flex items-end gap-2">
                        <p class="text-xs font-black text-slate-400 mb-1.5 uppercase">R$</p>
                        <p class="text-4xl font-black text-slate-800 tracking-tighter">{{ number_format($stats['offers_total'], 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-50">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Ticket Médio p/ Célula</p>
                    <p class="text-lg font-black text-slate-600 tracking-tighter">
                        R$ {{ number_format($stats['cells_total'] > 0 ? $stats['offers_total'] / $stats['cells_total'] : 0, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- EXPANSÃO --}}
        <div class="card-neo p-8 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 pb-4">Expansão de Células</h3>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Novas Células</p>
                        <p class="text-4xl font-black text-slate-800 tracking-tighter">{{ $stats['cells_new'] }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <i class="fas fa-church text-2xl"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Ativas</p>
                        <p class="text-lg font-black text-slate-600 tracking-tighter">{{ $stats['cells_total'] }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Novos Visitantes</p>
                        <p class="text-lg font-black text-slate-600 tracking-tighter">{{ $stats['visitors_new'] }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- BREAKDOWN TABLE (Exemplo: Por Rede) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-600">Breakdown por Rede</h3>
            <button class="text-[9px] font-black text-primary hover:text-primary-dark uppercase tracking-widest flex items-center gap-1">
                Exportar PDF <i class="fas fa-file-pdf"></i>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Rede</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Células</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Membros</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Ofertas</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach(['Rede Kids', 'Rede de Jovens', 'Rede da Família'] as $network)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-xs font-black text-slate-800 uppercase">{{ $network }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-xs font-bold text-slate-600">{{ rand(5, 20) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-xs font-bold text-slate-600">{{ rand(50, 150) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-xs font-bold text-slate-600">R$ {{ number_format(rand(1000, 5000), 2, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                Consolidado
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
