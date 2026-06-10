@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="mb-8">
        <a href="{{ route('admin.evaluation-cycles.index') }}" class="text-xs font-bold text-primary-light hover:text-accent uppercase tracking-wider transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Voltar para Ciclos
        </a>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight mt-3">Resultados de Avaliações</h1>
        <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Consulte e analise as avaliações de desempenho submetidas</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <!-- Filtros -->
        <div class="border-b border-slate-100 pb-4 mb-4">
            <form action="{{ route('admin.evaluation-results.index') }}" method="GET" class="grid gap-4 md:grid-cols-5">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-1">Ciclo</label>
                    <select name="cycle_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none">
                        <option value="">Todos os Ciclos</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}" {{ $cycleId == $cycle->id ? 'selected' : '' }}>
                                {{ $cycle->name }} ({{ $cycle->start_date->format('m/Y') }} - {{ $cycle->end_date->format('m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-1">Grupo Funcional</label>
                    <select name="group" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none">
                        <option value="">Todos os Grupos</option>
                        @foreach($groups as $g)
                            <option value="{{ $g }}" {{ $g == $group ? 'selected' : '' }}>
                                @php
                                    $groupLabels = [
                                        'geral' => 'Quadro Geral',
                                        'saude' => 'Saúde',
                                        'guarda' => 'Guarda Municipal',
                                        'educacao' => 'Educação',
                                    ];
                                    echo $groupLabels[$g] ?? $g;
                                @endphp
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-1">Servidor</label>
                    <select name="user_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none">
                        <option value="">Todos os Servidores</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->registration_number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-1">Status</label>
                    <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none">
                        <option value="">Todos os Status</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Rascunho</option>
                        <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submetida</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-neo btn-primary px-4 py-2">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.evaluation-results.index') }}" class="ml-2 text-xs font-bold text-slate-500 hover:text-accent">
                        Limpar Filtros
                    </a>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 font-bold text-sm border border-emerald-100 flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Estatísticas -->
        <div class="mb-4">
            @php
                $totalEvals = $evaluations->total();
                $submittedEvals = $evaluations->filter(fn($e) => $e->status === 'submitted')->count();
                $draftEvals = $evaluations->filter(fn($e) => $e->status === 'draft')->count();
                $avgScore = $evaluations->avg(function($e) { return $e->final_score ?? 0; });
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                <div class="bg-slate-50 p-3 rounded-lg">
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Total</div>
                    <div class="text-2xl font-black text-slate-800">{{ $totalEvals }}</div>
                </div>
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <div class="text-[10px] font-black text-emerald-600 uppercase tracking-wider">Submetidas</div>
                    <div class="text-2xl font-black text-emerald-800">{{ $submittedEvals }}</div>
                </div>
                <div class="bg-slate-50 p-3 rounded-lg">
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Rascunhos</div>
                    <div class="text-2xl font-black text-slate-800">{{ $draftEvals }}</div>
                </div>
                <div class="bg-slate-50 p-3 rounded-lg">
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Média Geral</div>
                    <div class="text-2xl font-black text-slate-800">
                        @php echo number_format($avgScore, 2) @endphp
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Avaliações -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Servidor</th>
                        <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Ciclo</th>
                        <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Avaliador</th>
                        <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Data/Hora</th>
                        <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">Nota Final</th>
                        <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">Status</th>
                        <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($evaluations->isEmpty())
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 text-xs font-semibold">
                                Nenhuma avaliação encontrada para os filtros selecionados.
                            </td>
                        </tr>
                    @else
                        @foreach($evaluations as $evaluation)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($evaluation->evaluated?->name ?? 'XX', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $evaluation->evaluated?->name ?? 'Servidor Excluído' }}</p>
                                            <p class="text-[10px] font-semibold text-slate-400">{{ $evaluation->evaluated?->registration_number ?? '000000' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-xs text-slate-600">
                                    {{ $evaluation->cycle->name }}<br>
                                    <span class="text-[10px] font-bold">{{ $evaluation->cycle->start_date->format('m/d') }} - {{$evaluation->cycle->end_date->format('m/d') }}</span>
                                </td>
                                <td class="p-4">
                                    @if($evaluation->evaluator)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($evaluation->evaluator->name, 0, 2)) }}
                                            </div>
                                            <p class="text-[10px] font-semibold text-slate-600">{{ $evaluation->evaluator->name }}</p>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-500 italic">Sistema</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-slate-600">
                                    {{ $evaluation->submitted_at ? $evaluation->submitted_at->format('d/m/Y H:i') : ' — ' }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($evaluation->final_score !== null)
                                        <span class="text-sm font-mono font-black text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ number_format($evaluation->final_score, 2) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-bold">—</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $statusMap = [
                                            'draft' => ['bg-slate-50 text-slate-400 border-slate-200', 'RASCUNHO'],
                                            'submitted' => ['bg-emerald-50 text-emerald-600 border-emerald-200', 'SUBMETIDA'],
                                        ];
                                        [$sClass, $sLabel] = $statusMap[$evaluation->status] ?? ['bg-slate-50 text-slate-400 border-slate-200', $evaluation->status];
                                    @endphp
                                    <span class="text-[10px] font-bold uppercase px-2.5 py-0.5 rounded-full border {{ $sClass }}">
                                        {{ $sLabel }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.evaluation-results.show', $evaluation) }}" 
                                           hx-boost="false"
                                           class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-blue-500 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $evaluations->links() }}
        </div>
    </div>
</div>
@endsection
