@extends('layouts.app')

@section('content')
@php
    $categoryDetails = [
        'assiduidade' => [
            'title' => 'Assiduidade',
            'desc' => 'Regularidade da jornada, cumprimento de escalas e pontualidade geral.',
        ],
        'disciplina' => [
            'title' => 'Disciplina',
            'desc' => 'Respeito à hierarquia, conduta ética e cumprimento das normas e regulamentos.',
        ],
        'iniciativa' => [
            'title' => 'Iniciativa',
            'desc' => 'Postura ativa na busca de soluções autônomas e simplificação de processos.',
        ],
        'responsabilidade' => [
            'title' => 'Responsabilidade',
            'desc' => 'Comprometimento com prazos, zelo pelo patrimônio público e conformidade legal.',
        ],
        'cooperacao' => [
            'title' => 'Cooperação',
            'desc' => 'Trabalho em equipe, relacionamento interpessoal e espírito colaborativo.',
        ],
        'qualidade' => [
            'title' => 'Qualidade do Trabalho',
            'desc' => 'Precisão técnica, organização, clareza nos documentos e atendimento excelente.',
        ],
        'desenvolvimento_rh' => [
            'title' => 'Desenvolvimento de RH',
            'desc' => 'Participação em capacitações, reciclagem e aplicação prática dos aprendizados.',
        ],
        'avaliacao_usuario' => [
            'title' => 'Avaliação pelo Usuário',
            'desc' => 'Resolutividade e polidez no atendimento direto ao cidadão ou cliente interno.',
        ],
    ];
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <!-- Links de Voltar -->
    <div class="mb-4">
        <a href="{{ route('admin.evaluation-results.index') }}" class="text-xs font-bold text-slate-400 hover:text-blue-600 uppercase tracking-wider transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">arrow_back</span>
            Voltar para Resultados
        </a>
    </div>

    <!-- Feedback Mensagens -->
    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm shadow-sm font-bold flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm shadow-sm font-bold flex items-center gap-2 mb-6">
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

    <!-- Profile Header Section -->
    <section class="mb-8 flex flex-col md:flex-row items-center justify-between bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center font-black text-3xl shadow-sm select-none">
                {{ strtoupper(substr($evaluation->evaluated->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $evaluation->evaluated->name }}</h2>
                <div class="flex flex-wrap items-center gap-3 mt-1.5">
                    <span class="font-mono text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded border border-slate-200/60 font-bold">Matrícula: {{ $evaluation->evaluated->registration_number }}</span>
                    <span class="text-sm font-semibold text-slate-500">{{ $evaluation->evaluated->cargo ?? 'Analista Público Pleno' }} | {{ $evaluation->cycle->name }}</span>
                </div>
            </div>
        </div>
        <div class="mt-6 md:mt-0 flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-100 transition-colors shadow-xs">
                <span class="material-symbols-outlined text-[18px]">print</span>
                Imprimir PDF
            </button>
            <a href="{{ route('admin.evaluations.all.export', ['cycle_id' => $evaluation->cycle_id, 'evaluated_name' => $evaluation->evaluated->name]) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-blue-700 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Exportar Dados
            </a>
        </div>
    </section>

    <!-- Bento Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Coluna Esquerda: Consolidado e Gráfico -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Pontuação Consolidada -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm flex flex-col justify-center items-center">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 text-center font-mono">Nota Final Consolidada</p>
                <div class="flex flex-col items-center">
                    <span class="font-mono text-6xl text-emerald-600 font-black tracking-tighter">{{ number_format($evaluation->final_score, 2) }}</span>
                    <span class="font-mono text-sm text-slate-400 mt-1">/ 5.00</span>
                </div>
                
                @php
                    $score = (float) $evaluation->final_score;
                    $cutoff = (float) $evaluation->cycle->cutoff_score;
                    
                    if ($score >= 4.5) {
                        $badgeText = 'DESEMPENHO EXCEPCIONAL';
                        $badgeColor = 'bg-emerald-50 text-emerald-600 border border-emerald-200';
                        $badgeDot = 'bg-emerald-500';
                    } elseif ($score >= $cutoff) {
                        $badgeText = 'APTO / SATISFATÓRIO';
                        $badgeColor = 'bg-blue-50 text-blue-600 border border-blue-200';
                        $badgeDot = 'bg-blue-500';
                    } else {
                        $badgeText = 'INAPTO / REQUER MELHORIAS';
                        $badgeColor = 'bg-rose-50 text-rose-600 border border-rose-150';
                        $badgeDot = 'bg-rose-500';
                    }
                @endphp
                
                <div class="mt-6 px-4 py-1.5 {{ $badgeColor }} rounded-full flex items-center gap-2 text-xs font-bold tracking-wider font-mono">
                    <div class="w-2 h-2 {{ $badgeDot }} rounded-full"></div>
                    <span>{{ $badgeText }}</span>
                </div>
            </div>

            <!-- Gráfico de Radar -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 font-mono">Desempenho por Categoria</h3>
                <div class="relative h-64">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: BARS Competency Analysis -->
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2 tracking-tight">
                <span class="material-symbols-outlined text-blue-650">verified_user</span>
                Análise de Competências (BARS)
            </h3>
            
            <div class="space-y-4">
                @foreach($categoryAverages as $category => $data)
                    @php
                        $title = $categoryDetails[$category]['title'] ?? ucfirst($category);
                        $desc = $categoryDetails[$category]['desc'] ?? 'Média da categoria obtida nas avaliações do ciclo.';
                        $average = $data['average'];
                        $percent = ($average / 5) * 100;
                        
                        $roundedScore = (int) round($average);
                        $roundedScore = max(1, min(5, $roundedScore));
                        
                        // Buscar as respostas desta categoria
                        $categoryAnswers = $evaluation->answers->filter(function($ans) use ($category) {
                            return $ans->question->category === $category;
                        });
                        
                        // Resumo BARS da Categoria
                        $categoryAnswer = $categoryAnswers->first();
                        $anchorDescription = 'Desempenho registrado correspondente ao nível de comportamento ' . $roundedScore . '.';
                        if ($categoryAnswer && $categoryAnswer->question) {
                            $anchor = \App\Models\BarsAnchor::where('question_id', $categoryAnswer->question_id)
                                ->where('score', $roundedScore)
                                ->first();
                            if ($anchor) {
                                $anchorDescription = $anchor->behavioral_description;
                            }
                        }
                    @endphp

                    <!-- Item do Acordeão (Inicia Fechado) -->
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs hover:border-slate-350 transition-colors">
                        <!-- Header do Acordeão -->
                        <div class="accordion-header flex items-center justify-between p-4 cursor-pointer select-none bg-slate-50/50 hover:bg-slate-50 transition-colors" data-category="{{ $category }}">
                            <div class="flex items-center gap-3">
                                @php
                                    $icon = 'star';
                                    $bg = 'bg-slate-100 text-slate-650';
                                    if($category === 'assiduidade') { $icon = 'schedule'; $bg = 'bg-blue-50 text-blue-500'; }
                                    elseif($category === 'disciplina') { $icon = 'gavel'; $bg = 'bg-rose-50 text-rose-500'; }
                                    elseif($category === 'iniciativa') { $icon = 'lightbulb'; $bg = 'bg-amber-50 text-amber-500'; }
                                    elseif($category === 'responsabilidade') { $icon = 'verified_user'; $bg = 'bg-emerald-50 text-emerald-500'; }
                                    elseif($category === 'cooperacao') { $icon = 'groups'; $bg = 'bg-indigo-50 text-indigo-500'; }
                                    elseif($category === 'qualidade') { $icon = 'workspace_premium'; $bg = 'bg-violet-50 text-violet-500'; }
                                    elseif($category === 'desenvolvimento_rh') { $icon = 'school'; $bg = 'bg-purple-50 text-purple-500'; }
                                    elseif($category === 'avaliacao_usuario') { $icon = 'sentiment_satisfied'; $bg = 'bg-teal-50 text-teal-500'; }
                                @endphp
                                <div class="w-9 h-9 rounded-lg {{ $bg }} flex items-center justify-center shadow-xs">
                                    <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-850 text-sm leading-tight uppercase tracking-wider">{{ $title }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">Média: {{ number_format($average, 2) }} • {{ $categoryAnswers->count() }} pergunta(s)</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="font-mono text-xs font-black text-blue-650 bg-blue-50 border border-blue-100 rounded-md px-2 py-0.5 shadow-xxs">★ {{ number_format($average, 2) }}</span>
                                <span class="material-symbols-outlined text-[20px] text-slate-400 transition-transform duration-200 accordion-chevron">
                                    expand_more
                                </span>
                            </div>
                        </div>

                        <!-- Conteúdo do Acordeão (Oculto por Padrão) -->
                        <div class="accordion-content p-5 space-y-5 border-t border-slate-100 hidden" style="display: none;">
                            <!-- Definição da Competência -->
                            <div>
                                <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 font-mono">Definição do Indicador</h5>
                                <p class="text-xs text-slate-600 leading-relaxed font-semibold">{{ $desc }}</p>
                            </div>

                            <!-- Resumo Comportamental Consolidado -->
                            <div class="p-3.5 bg-slate-50 border-l-4 border-blue-600 rounded-r-lg shadow-xxs">
                                <h5 class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1 font-mono">Consolidado da Categoria</h5>
                                <p class="text-xs italic text-slate-700 leading-relaxed font-semibold">
                                    "Nível {{ $roundedScore }}: {{ $anchorDescription }}"
                                </p>
                            </div>

                            <!-- Detalhamento de cada Pergunta -->
                            <div class="space-y-4 pt-3 border-t border-slate-100">
                                <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 font-mono">Perguntas e Pontuações Individuais</h5>
                                @foreach($categoryAnswers as $idx => $categoryAns)
                                    @php
                                        $questionObj = $categoryAns->question;
                                        $scoreValue = $categoryAns->score;
                                        
                                        // Buscar a âncora específica da resposta
                                        $qAnchor = \App\Models\BarsAnchor::where('question_id', $questionObj->id)
                                            ->where('score', $scoreValue)
                                            ->first();
                                        $qAnchorDescription = $qAnchor ? $qAnchor->behavioral_description : '—';
                                    @endphp
                                    <div class="bg-white border border-slate-150 rounded-lg p-4 shadow-xxs hover:shadow-xs transition-shadow">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex items-start gap-2.5">
                                                <span class="material-symbols-outlined text-slate-350 text-[18px] mt-0.5">help_outline</span>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-750 leading-relaxed">{{ $idx + 1 }}. {{ $questionObj->text }}</p>
                                                    <div class="mt-2 text-xs text-slate-650 italic leading-relaxed font-semibold bg-slate-50/50 p-2.5 rounded border border-slate-100 border-l-2 border-l-slate-400">
                                                        <span class="font-bold text-[9px] uppercase font-mono text-slate-400 tracking-wide block mb-0.5">Comportamento Demonstrado (Âncora BARS):</span>
                                                        "{{ $qAnchorDescription }}"
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center justify-center font-mono font-bold text-xs bg-slate-50 text-slate-700 border border-slate-200/60 px-2.5 py-1 rounded-md shrink-0 shadow-xxs">
                                                Nota: {{ $scoreValue }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Metas Quantitativas (OKRs) -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm mt-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 tracking-tight">
                    <span class="material-symbols-outlined text-blue-650">track_changes</span>
                    Objetivos Estratégicos & OKRs
                </h3>
                <p class="text-xs text-slate-400 font-semibold mt-1">Lançamento e aferição dos valores alcançados oficiais das metas do servidor pelo RH.</p>
            </div>
            <div class="text-xs text-slate-450 font-bold bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200 font-mono">
                Peso de Metas: <span class="text-slate-700 font-black">{{ number_format(($evaluation->weight_goals ?? 0.50) * 100, 0) }}%</span>
            </div>
        </div>

        @if($evaluation->goals->isNotEmpty())
            <form action="{{ route('admin.evaluation-results.update-goals', $evaluation->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-xs">
                    <table class="w-full text-sm text-left text-slate-650">
                        <thead class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-4 py-3">Meta / Indicador de Sucesso</th>
                                <th scope="col" class="px-4 py-3 text-right w-36">Alvo Pactuado</th>
                                <th scope="col" class="px-4 py-3 text-right w-44">Valor Alcançado (RH)</th>
                                <th scope="col" class="px-4 py-3 text-right w-24">Peso</th>
                                <th scope="col" class="px-4 py-3 text-center w-36">Progresso</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($evaluation->goals as $idx => $goal)
                                @php
                                    $target = (float) $goal->target_value;
                                    $achieved = $goal->achieved_value !== null ? (float) $goal->achieved_value : null;
                                    
                                    if ($achieved !== null) {
                                        $progressPercent = $target > 0 ? min(100, max(0, ($achieved / $target) * 100)) : 0;
                                        if ($progressPercent >= 100) {
                                            $barColor = 'bg-emerald-500';
                                        } elseif ($progressPercent >= 50) {
                                            $barColor = 'bg-amber-500';
                                        } else {
                                            $barColor = 'bg-rose-500';
                                        }
                                    } else {
                                        $progressPercent = 0;
                                        $barColor = 'bg-slate-300';
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-slate-800">{{ $goal->description }}</div>
                                        <div class="text-xs text-slate-400 font-medium mt-0.5">{{ $goal->metric }}</div>
                                        <input type="hidden" name="goals[{{ $idx }}][id]" value="{{ $goal->id }}">
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono text-slate-650 font-bold">
                                        {{ number_format($goal->target_value, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <input type="number" step="0.01" min="0" name="goals[{{ $idx }}][achieved_value]" value="{{ $goal->achieved_value }}" placeholder="Aferir valor" class="w-full bg-white border border-slate-200 focus:border-blue-600 focus:bg-white text-slate-800 text-sm py-1.5 px-3 text-right outline-none rounded-lg transition font-mono focus:ring-2 focus:ring-blue-600/10">
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono text-slate-500 font-bold">
                                        {{ number_format($goal->weight, 1) }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-20 h-2 bg-slate-100 rounded-full overflow-hidden border border-slate-200/50">
                                                <div class="h-full {{ $barColor }} rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                            <span class="font-mono text-xs font-bold {{ $achieved !== null ? ($progressPercent >= 100 ? 'text-emerald-600' : ($progressPercent >= 50 ? 'text-amber-600' : 'text-rose-600')) : 'text-slate-450' }}">
                                                {{ $achieved !== null ? number_format($progressPercent, 0) . '%' : 'N/D' }}
                                            </span>
                                        </div>
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
                    <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-blue-700 active:scale-95 transition-all shadow-sm cursor-pointer select-none">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Salvar Valores Alcançados</span>
                    </button>
                </div>
            </form>
        @else
            <div class="bg-slate-50 p-6 rounded-xl border border-slate-200/60 text-center text-slate-400 font-bold">
                <span class="material-symbols-outlined text-3xl text-slate-350 mb-2">trending_up</span>
                <p>Nenhuma meta quantitativa cadastrada para esta avaliação.</p>
            </div>
        @endif
    </div>

    <!-- Histórico de Incidentes Críticos -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm mt-6">
        <h3 class="text-lg font-bold text-slate-900 mb-2 flex items-center gap-2 tracking-tight">
            <span class="material-symbols-outlined text-blue-650">history_edu</span>
            Registro de Incidentes Críticos
        </h3>
        <p class="text-xs text-slate-450 font-semibold mb-6">Notas com nível comportamental crítico (1, 2 ou 5) exigem justificativa e anexo de comprovação conforme regulamento municipal.</p>
        
        @if($criticalIncidents->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($criticalIncidents as $incident)
                    @php
                        $score = $incident->score;
                        $isPositive = $score == 5;
                        
                        if ($isPositive) {
                            $cardBg = 'bg-emerald-50/20 border-emerald-100';
                            $iconBg = 'bg-emerald-50 text-emerald-600 border border-emerald-200';
                            $iconName = 'add_circle';
                            $badgeText = 'Incidente Positivo';
                            $badgeClass = 'bg-emerald-50 text-emerald-600 border border-emerald-200';
                        } else {
                            $cardBg = 'bg-rose-50/20 border-rose-100';
                            $iconBg = 'bg-rose-50 text-rose-600 border border-rose-150';
                            $iconName = 'priority_high';
                            $badgeText = 'Ponto de Atenção';
                            $badgeClass = 'bg-rose-50 text-rose-600 border border-rose-150';
                        }
                    @endphp
                    
                    <div class="p-5 border {{ $cardBg }} rounded-xl flex gap-4 transition-all hover:shadow-xs">
                        <div class="w-10 h-10 rounded-full {{ $iconBg }} flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-[20px]">{{ $iconName }}</span>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-center gap-2 flex-wrap">
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }} px-2 py-0.5 rounded font-mono">{{ $badgeText }}</span>
                                <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $incident->created_at->format('d/m/Y') }}</span>
                            </div>
                            <h4 class="font-bold text-slate-900 mt-2 text-sm leading-tight">{{ $incident->question->text }}</h4>
                            <div class="bg-white/80 p-3 rounded-lg border border-slate-200/40 mt-3">
                                <p class="text-xs text-slate-650 leading-relaxed"><strong class="text-slate-800">Justificativa:</strong> {{ $incident->criticalIncident->justification }}</p>
                            </div>
                            
                            @if($incident->criticalIncident->evidence_path)
                                <div class="mt-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400">attachment</span>
                                    <a href="{{ Storage::url($incident->criticalIncident->evidence_path) }}" 
                                       target="_blank" 
                                       class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                                        Ver Anexo de Evidência
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 p-6 rounded-xl border border-slate-200/60 text-center">
                <span class="material-symbols-outlined text-3xl text-emerald-500 mb-2">check_circle</span>
                <p class="text-slate-400 font-bold text-sm">Nenhum incidente crítico registrado nesta avaliação.</p>
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
            labels: categoryLabels.map(cat => {
                const map = {
                    'assiduidade': 'Assiduidade',
                    'disciplina': 'Disciplina',
                    'iniciativa': 'Iniciativa',
                    'responsabilidade': 'Responsabilidade',
                    'cooperacao': 'Cooperação',
                    'qualidade': 'Qualidade',
                    'desenvolvimento_rh': 'Desenv. RH',
                    'avaliacao_usuario': 'Av. Usuário'
                };
                return map[cat] || cat;
            }),
            datasets: [{
                label: 'Média BARS',
                data: categoryData,
                fill: true,
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderColor: 'rgba(37, 99, 235, 0.8)',
                pointBackgroundColor: 'rgba(37, 99, 235, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(37, 99, 235, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: {
                        color: 'rgba(226, 232, 240, 0.8)'
                    },
                    suggestedMin: 1,
                    suggestedMax: maxScore,
                    ticks: {
                        stepSize: 1,
                        backdropColor: 'transparent',
                        color: '#64748b',
                        font: {
                            family: 'JetBrains Mono',
                            size: 10
                        }
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.8)'
                    },
                    pointLabels: {
                        color: '#334155',
                        font: {
                            family: 'Inter',
                            weight: '600',
                            size: 11
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Nota BARS: ' + context.parsed.r;
                        }
                    }
                }
            }
        }
    });

    // Alternar exibição do acordeão de competências na tela de resultados
    $('.accordion-header').on('click', function() {
        var $content = $(this).next('.accordion-content');
        var $chevron = $(this).find('.accordion-chevron');
        
        $content.slideToggle(200, function() {
            if ($content.is(':visible')) {
                $chevron.addClass('rotate-180');
            } else {
                $chevron.removeClass('rotate-180');
            }
        });
    });

    // Animação e micro-interação de clique em botões
    document.querySelectorAll('button, a.btn-action').forEach(button => {
        button.addEventListener('mousedown', () => {
            button.classList.add('scale-95');
        });
        button.addEventListener('mouseup', () => {
            button.classList.remove('scale-95');
        });
        button.addEventListener('mouseleave', () => {
            button.classList.remove('scale-95');
        });
    });
});
</script>
@endpush