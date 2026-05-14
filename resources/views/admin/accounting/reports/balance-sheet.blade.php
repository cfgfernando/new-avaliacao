@extends('layouts.app')

@section('title', 'Balanço Patrimonial')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Balanço Patrimonial</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Demonstração da Posição Financeira e Patrimonial</p>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.accounting.reports.balance-sheet') }}" method="GET" class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="input-neo !py-2 !px-4 !text-[10px] !w-40 uppercase">
                <button type="submit" class="btn-neo btn-primary !py-2 !px-6 text-[10px]">
                    <i class="fas fa-filter mr-2"></i> FILTRAR
                </button>
            </form>
            <a href="{{ route('admin.accounting.reports.pdf.balance-sheet', request()->all()) }}" target="_blank" class="btn-neo bg-white text-slate-800 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-file-pdf text-rose-500"></i>
                <span>GERAR PDF</span>
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 print:grid-cols-2">
        <!-- Coluna de Ativos -->
        <div class="space-y-6">
            <div class="card-neo !p-0 overflow-hidden border-emerald-100">
                <div class="px-8 py-4 bg-emerald-50/50 border-b border-emerald-100 flex justify-between items-center">
                    <h3 class="text-xs font-black text-emerald-800 uppercase tracking-widest">1. ATIVOS (Aplicações)</h3>
                    <span class="text-xs font-black text-emerald-700">TOTAL: R$ {{ number_format($totalAssets, 2, ',', '.') }}</span>
                </div>
                <div class="p-6">
                    <table class="w-full text-left border-collapse">
                        <tbody class="divide-y divide-slate-50">
                            @foreach($assets as $asset)
                            @php
                                $isParent = \App\Models\Finance\ChartOfAccount::where('parent_id', $asset->id)->exists();
                                $depth = substr_count($asset->code, '.');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24">{{ $asset->code }}</td>
                                <td class="py-3 text-[11px] {{ $isParent ? 'font-black text-slate-900' : 'font-bold text-slate-700' }} uppercase" style="padding-left: {{ $depth * 1 }}rem">{{ $asset->name }}</td>
                                <td class="py-3 text-right text-[11px] font-money {{ $isParent ? 'font-black text-slate-900' : 'text-emerald-600' }}">R$ {{ number_format($asset->total, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            @if($assets->isEmpty())
                            <tr>
                                <td colspan="3" class="py-10 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhuma conta de ativo com saldo</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-emerald-100">
                                <td colspan="2" class="py-4 text-[10px] font-black text-emerald-800 uppercase tracking-widest">TOTAL DOS ATIVOS</td>
                                <td class="py-4 text-right text-sm font-black text-emerald-800 font-money">R$ {{ number_format($totalAssets, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Coluna de Passivos e PL -->
        <div class="space-y-6">
            <!-- Passivos -->
            <div class="card-neo !p-0 overflow-hidden border-rose-100">
                <div class="px-8 py-4 bg-rose-50/50 border-b border-rose-100 flex justify-between items-center">
                    <h3 class="text-xs font-black text-rose-800 uppercase tracking-widest">2. PASSIVOS (Obrigações)</h3>
                    <span class="text-xs font-black text-rose-700">TOTAL: R$ {{ number_format($totalLiabilities, 2, ',', '.') }}</span>
                </div>
                <div class="p-6">
                    <table class="w-full text-left border-collapse">
                        <tbody class="divide-y divide-slate-50">
                            @foreach($liabilities as $liability)
                            @php
                                $isParent = \App\Models\Finance\ChartOfAccount::where('parent_id', $liability->id)->exists();
                                $depth = substr_count($liability->code, '.');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24">{{ $liability->code }}</td>
                                <td class="py-3 text-[11px] {{ $isParent ? 'font-black text-slate-900' : 'font-bold text-slate-700' }} uppercase" style="padding-left: {{ $depth * 1 }}rem">{{ $liability->name }}</td>
                                <td class="py-3 text-right text-[11px] font-money {{ $isParent ? 'font-black text-slate-900' : 'text-rose-600' }}">R$ {{ number_format($liability->total, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            @if($liabilities->isEmpty())
                            <tr>
                                <td colspan="3" class="py-10 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhuma conta de passivo com saldo</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-rose-100">
                                <td colspan="2" class="py-4 text-[10px] font-black text-rose-800 uppercase tracking-widest">TOTAL DOS PASSIVOS</td>
                                <td class="py-4 text-right text-sm font-black text-rose-800 font-money">R$ {{ number_format($totalLiabilities, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Patrimônio Líquido -->
            <div class="card-neo !p-0 overflow-hidden border-primary-100">
                <div class="px-8 py-4 bg-primary-50/50 border-b border-primary-100 flex justify-between items-center">
                    <h3 class="text-xs font-black text-primary-800 uppercase tracking-widest">3. PATRIMÔNIO LÍQUIDO</h3>
                    <span class="text-xs font-black text-primary-700">TOTAL: R$ {{ number_format($totalEquity, 2, ',', '.') }}</span>
                </div>
                <div class="p-6">
                    <table class="w-full text-left border-collapse">
                        <tbody class="divide-y divide-slate-50">
                            @foreach($equity as $e)
                            @php
                                $isParent = \App\Models\Finance\ChartOfAccount::where('parent_id', $e->id)->exists();
                                $depth = substr_count($e->code, '.');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24">{{ $e->code }}</td>
                                <td class="py-3 text-[11px] {{ $isParent ? 'font-black text-slate-900' : 'font-bold text-slate-700' }} uppercase" style="padding-left: {{ $depth * 1 }}rem">{{ $e->name }}</td>
                                <td class="py-3 text-right text-[11px] font-money {{ $isParent ? 'font-black text-slate-900' : 'text-primary-600' }}">R$ {{ number_format($e->total, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24">RES</td>
                                <td class="py-3 text-[11px] font-bold text-slate-700 uppercase italic">Resultado do Exercício (Até a Data)</td>
                                <td class="py-3 text-right text-[11px] font-money {{ $netResult >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    R$ {{ number_format($netResult, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-primary-100">
                                <td colspan="2" class="py-4 text-[10px] font-black text-primary-800 uppercase tracking-widest">TOTAL DO PL</td>
                                <td class="py-4 text-right text-sm font-black text-primary-800 font-money">R$ {{ number_format($totalEquity, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Verificação de Equilíbrio -->
    <div class="card-neo bg-slate-800 border-none mt-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-700 flex items-center justify-center text-white text-xl">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Equação Patrimonial</h4>
                    <p class="text-sm font-bold text-white uppercase tracking-tight">Ativo = Passivo + Patrimônio Líquido</p>
                </div>
            </div>
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Lado A (Ativos)</p>
                    <p class="text-xl font-black text-emerald-400 font-money">R$ {{ number_format($totalAssets, 2, ',', '.') }}</p>
                </div>
                <div class="text-slate-600 text-2xl font-black">=</div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Lado B (P + PL)</p>
                    <p class="text-xl font-black text-rose-400 font-money">R$ {{ number_format($totalLiabilities + $totalEquity, 2, ',', '.') }}</p>
                </div>
            </div>
            @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
            <div class="px-6 py-2 bg-emerald-500/20 text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-500/30">
                <i class="fas fa-check-circle mr-2"></i> EQUILIBRADO
            </div>
            @else
            <div class="px-6 py-2 bg-rose-500/20 text-rose-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-rose-500/30">
                <i class="fas fa-exclamation-triangle mr-2"></i> DESEQUILÍBRIO: R$ {{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity)), 2, ',', '.') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
