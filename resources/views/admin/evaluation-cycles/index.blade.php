@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Ciclos de Avaliação</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gerencie os períodos de avaliação de desempenho</p>
        </div>
        <a href="{{ route('admin.evaluation-cycles.create') }}" class="btn-neo btn-primary text-xs py-2 px-6">
            <i class="fas fa-plus mr-2"></i> NOVO CICLO
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 font-bold text-sm border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
        @if($cycles->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Nome</th>
                            <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Período</th>
                            <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Nota de Corte</th>
                            <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Avaliações</th>
                            <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Status</th>
                            <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($cycles as $cycle)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-800 text-sm">{{ $cycle->name }}</td>
                                <td class="p-4 text-xs text-slate-600">
                                    {{ $cycle->start_date->format('d/m/Y') }} — {{ $cycle->end_date->format('d/m/Y') }}
                                </td>
                                <td class="p-4">
                                    <span class="text-sm font-bold font-mono {{ $cycle->cutoff_score >= 3 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($cycle->cutoff_score, 2) }}
                                    </span>
                                </td>
                                <td class="p-4 text-xs text-slate-600 font-semibold">{{ $cycle->evaluations->count() }}</td>
                                <td class="p-4">
                                    @php
                                        $statusMap = [
                                            'active' => ['bg-emerald-50 text-emerald-600 border-emerald-200', 'ATIVO'],
                                            'inactive' => ['bg-slate-50 text-slate-400 border-slate-200', 'INATIVO'],
                                            'closed' => ['bg-rose-50 text-rose-600 border-rose-200', 'FECHADO'],
                                        ];
                                        [$sClass, $sLabel] = $statusMap[$cycle->status] ?? ['bg-slate-50 text-slate-400 border-slate-200', $cycle->status];
                                    @endphp
                                    <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border {{ $sClass }}">
                                        {{ $sLabel }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.evaluation-cycles.edit', $cycle) }}" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-blue-500 transition-colors">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>
                                        <form action="{{ route('admin.evaluation-cycles.destroy', $cycle) }}" method="POST" onsubmit="return confirm('Excluir este ciclo e todas as suas avaliações? Esta ação é irreversível.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 hover:bg-rose-50 rounded-lg text-slate-400 hover:text-rose-500 transition-colors">
                                                <i class="fas fa-trash text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-slate-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-2xl text-slate-300"></i>
                </div>
                <p class="text-slate-400 font-bold text-sm">Nenhum ciclo de avaliação cadastrado.</p>
                <p class="text-slate-300 text-xs mt-1">Crie o primeiro ciclo para iniciar as avaliações periódicas.</p>
                <a href="{{ route('admin.evaluation-cycles.create') }}" class="inline-block mt-6 text-xs font-bold text-accent hover:underline uppercase tracking-wider">
                    <i class="fas fa-plus mr-1"></i> Criar Novo Ciclo
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
