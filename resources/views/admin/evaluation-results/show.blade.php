@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="mb-8">
        <a href="{{ route('admin.evaluation-results.index') }}" class="text-xs font-bold text-primary-light hover:text-accent uppercase tracking-wider transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Voltar para Resultados
        </a>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight mt-3">
            Detalhes da Avaliação
        </h1>
        <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">
            Avaliação de {{ $evaluation->evaluated->name }} ({{ $evaluation->evaluated->registration_number }})
        </p>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm shadow-sm font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm shadow-sm font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-rose-500">error</span>
            <div>
                <p>Ocorreram erros de validação:</p>
                <ul class="list-disc list-inside mt-1 font-semibold text-xs text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <!-- Cabeçalho -->
        <div class="border-b border-slate-100 pb-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Servidor Avaliado</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xl">
                                {{ strtoupper(substr($evaluation->evaluated->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-lg font-black text-slate-800">{{ $evaluation->evaluated->name }}</p>
                                <p class="text-[10px] font-semibold text-slate-400">{{ $evaluation->evaluated->registration_number }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-600">Cargo: <span class="text-slate-400">{{ $evaluation->evaluated->cargo }}</span></p>
                            <p class="text-[10px] font-bold text-slate-600">Lotação: <span class="text-slate-400">{{ $evaluation->evaluated->lotacao }}</span></p>
                            <p class="text-[10px] font-bold text-slate-600">Grupo: <span class="text-[10px] font-bold text-slate-600">{{ $evaluation->evaluated->evaluation_group }}</span></p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800">Ciclo de Avaliação</h3>
                    <div class="space-y-2">
                        <p class="text-[10px] font-bold text-slate-600">Nome: <span class="text-slate-400">{{ $evaluation->cycle->name }}</span></p>
                        <p class="text-[10px] font-bold text-slate-600">Período: <span class="text-slate-400">{{ $evaluation->cycle->start_date->format('d/m/Y') }} - {{ $evaluation->cycle->end_date->format('d/m/Y') }}</span></p>
                        <p class="text-[10px] font-bold text-slate-600">Nota de Corte: <span class="text-slate-400 font-mono">{{ number_format($evaluation->cycle->cutoff_score, 2) }}</span></p>
                        <p class="text-[10px] font-bold text-slate-600">Status: 
                            <span class="text-[10px] font-bold uppercase px-2.5 py-0.5 rounded-full 
                                {{ $evaluation->cycle->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' 
                                : $evaluation->cycle->status === 'inactive' ? 'bg-slate-50 text-slate-400 border-slate-200' 
                                : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                {{ ucfirst($evaluation->cycle->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800">Avaliador</h3>
                    @if($evaluation->evaluator)
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xl">
                                    {{ strtoupper(substr($evaluation->evaluator->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-lg font-black text-slate-800">{{ $evaluation->evaluator->name }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400">{{ $evaluation->evaluator->registration_number }}</p>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-600">Cargo: <span class="text-slate-400">{{ $evaluation->evaluator->cargo }}</span></p>
                                <p class="text-[10px] font-bold text-slate-600">Lotação: <span class="text-slate-400">{{ $evaluation->evaluator->lotacao }}</span></p>
                                <p class="text-[10px] font-bold text-slate-600">Grupo: <span class="text-[10px] font-bold text-slate-600">{{ $evaluation->evaluator->evaluation_group }}</span></p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-slash text-2xl text-slate-400"></i>
                            <p class="text-sm font-bold text-slate-600">Avaliação realizada pelo sistema</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Nota Final -->
        <div class="bg-slate-50 p-6 rounded-xl mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Nota Final</h3>
                    <p class="text-sm font-bold text-slate-600">Média ponderada das categorias</p>
                </div>
                <div class="text-5xl font-bold font-mono {{ $evaluation->final_score >= $evaluation->cycle->cutoff_score ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ number_format($evaluation->final_score, 2) }}
                </div>
            </div>
            @if($evaluation->final_score !== null)
                <div class="mt-2">
                    <div class="flex justify-between text-xs">
                        <span>Nota de Corte:</span>
                        <span class="font-mono">{{ number_format($evaluation->cycle->cutoff_score, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span>Status:</span>
                        <span class="{{ $evaluation->final_score >= $evaluation->cycle->cutoff_score ? 'text-emerald-600' : 'text-rose-600' }} font-bold font-mono">
                            {{ $evaluation->final_score >= $evaluation->cycle->cutoff_score ? 'APTO' : 'INAPTO' }}
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Metas Quantitativas (Pactuação e Aferição pelo RH) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Metas Quantitativas</h3>
                    <p class="text-xs text-slate-400 font-bold">Lançamento de valores alcançados oficiais das metas do servidor</p>
                </div>
                <div class="text-xs text-slate-400 font-bold bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200">
                    Peso de Metas: <span class="text-slate-700 font-black font-mono">{{ number_format(($evaluation->weight_goals ?? 0.50) * 100, 0) }}%</span>
                </div>
            </div>

            @if($evaluation->goals->isNotEmpty())
                <form action="{{ route('admin.evaluation-results.update-goals', $evaluation->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm">
                        <table class="w-full text-sm text-left text-slate-650">
                            <thead class="text-xs uppercase bg-slate-50 text-slate-500 font-bold border-b border-slate-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Descrição da Meta</th>
                                    <th scope="col" class="px-4 py-3 w-40">Métrica / Unidade</th>
                                    <th scope="col" class="px-4 py-3 text-right w-32">Alvo Pactuado</th>
                                    <th scope="col" class="px-4 py-3 text-right w-44">Valor Alcançado (RH)</th>
                                    <th scope="col" class="px-4 py-3 text-right w-24">Peso</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($evaluation->goals as $idx => $goal)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-3 text-slate-700 font-bold">
                                            {{ $goal->description }}
                                            <input type="hidden" name="goals[{{ $idx }}][id]" value="{{ $goal->id }}">
                                        </td>
                                        <td class="px-4 py-3 text-slate-500 font-semibold">
                                            {{ $goal->metric }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-slate-650 font-bold">
                                            {{ number_format($goal->target_value, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <input type="number" step="0.01" min="0" name="goals[{{ $idx }}][achieved_value]" value="{{ $goal->achieved_value }}" placeholder="Digitar valor alcançado" class="w-full bg-white border border-slate-250 focus:border-accent focus:bg-white text-slate-800 text-sm py-1.5 px-2.5 text-right outline-none rounded-lg transition font-mono focus:ring-2 focus:ring-accent/10">
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-slate-500 font-semibold">
                                            {{ number_format($goal->weight, 1) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-2">
                        <div class="text-[12px] text-blue-700 font-bold bg-blue-50/50 px-4 py-3 rounded-lg border border-blue-200/40 flex items-center gap-2 w-full sm:w-auto">
                            <span class="material-symbols-outlined text-[18px] text-blue-600">info</span>
                            <span>Ao salvar, a nota final mista da avaliação será recalculada de forma ponderada imediatamente.</span>
                        </div>
                        <x-button type="submit" variant="primary" class="w-full sm:w-auto flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>Salvar Valores Alcançados</span>
                        </x-button>
                    </div>
                </form>
            @else
                <div class="bg-slate-50 p-6 rounded-xl text-center text-slate-400 font-bold">
                    <span class="material-symbols-outlined text-3xl text-slate-350 mb-2">trending_up</span>
                    <p>Nenhuma meta cadastrada para esta avaliação.</p>
                </div>
            @endif
        </div>

        <!-- Gráfico de Radar das Categorias -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
            <h3 class="text-xl font-black text-slate-800 mb-4">Desempenho por Categoria</h3>
            <div class="relative">
                <!-- Container for Chart.js -->
                <canvas id="radarChart" height="200"></canvas>
            </div>
        </div>

        <!-- Incidentes Críticos -->
        @if($criticalIncidents->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xl font-black text-slate-800 mb-4">Incidentos Críticos</h3>
                <p class="text-sm font-bold text-slate-600 mb-4">
                    Notas 1, 2 ou 5 exigem justificativa e evidência conforme regulamento
                </p>
                <div class="space-y-4">
                    @foreach($criticalIncidents as $incident)
                        <div class="border-l-4 border-rose-500 pl-4 py-3 mb-3 bg-rose-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-black text-slate-800">
                                    {{ $incident->question->text }}
                                </h4>
                                <span class="text-[10px] font-bold text-rose-600">
                                    Nota {{ $incident->answer->score }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-700 leading-relaxed">
                                <strong>Justificativa:</strong> {{ $incident->criticalIncident->justification }}
                            </p>
                            @if($incident->criticalIncident->evidence_path)
                                <div class="mt-2">
                                    <span class="text-[10px] font-bold text-slate-600">Evidência:</span>
                                    <a href="{{ Storage::url($incident->criticalIncident->evidence_path) }}" 
                                       target="_blank" 
                                       class="text-sm font-semibold text-accent hover:underline">
                                        Ver Arquivo
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-slate-50 p-6 rounded-xl text-center">
                <i class="fas fa-check-circle text-2xl text-emerald-500 mb-4"></i>
                <p class="text-slate-400 font-bold">Nenhum incidente crítico registrado nesta avaliação.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('radarChart').getContext('2d');
    
    // Dados para o gráfico de radar
    const categoryLabels = @json(array_keys($categoryAverages ?? []));
    const categoryData = @json(array_values(array_column($categoryAverages ?? [], 'average')));
    const maxScore = 5; // Escala de 1 a 5
    
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Desempenho por Categoria',
                data: categoryData,
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: {
                        display: false
                    },
                    suggestedMin: 1,
                    suggestedMax: maxScore,
                    ticks: {
                        stepSize: 1,
                        backdropColor: 'rgba(255, 255, 255, 0.75)'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Nota: ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush