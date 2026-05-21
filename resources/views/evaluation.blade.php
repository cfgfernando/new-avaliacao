@extends('layouts.app')

@section('title', 'Avaliação de Desempenho Mista')

@section('content')
<div class="space-y-8 select-none">
    <!-- Cabeçalho Principal (Breadcrumb e Status do Servidor) -->
    <x-card class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-xl shadow-sm border border-blue-100 font-mono">
                {{ strtoupper(substr($evaluated->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 leading-tight">{{ $evaluated->name }}</h2>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400 font-bold uppercase tracking-widest font-mono mt-1">
                    <span>Matrícula: <strong class="text-slate-600 font-bold font-mono">{{ $evaluated->registration_number ?? '124.582' }}</strong></span>
                    <span>•</span>
                    <span>Cargo: <strong class="text-slate-600 font-bold">{{ $evaluated->cargo ?? 'Não Definido' }}</strong></span>
                    <span>•</span>
                    <span>Lotação: <strong class="text-slate-600 font-bold">{{ $evaluated->lotacao ?? 'Geral' }}</strong></span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-badge variant="success" class="font-bold uppercase tracking-widest px-3 py-1.5 bg-emerald-50 text-emerald-700 border-emerald-200">
                Metodologia Mista
            </x-badge>
            <div class="text-xs text-slate-400 font-bold bg-slate-100 px-2.5 py-1.5 rounded-lg border border-slate-200 font-mono">
                Fim do Ciclo: {{ $cycle->end_date->format('d/m/Y') }}
            </div>
        </div>
    </x-card>

    <!-- Erros de Validação do Laravel -->
    @if ($errors->any())
        <div class="p-5 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-2">
                <span class="material-symbols-outlined text-rose-500">error</span>
                <span>Existem erros no preenchimento do formulário:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Cards de Dados Integrados (Ponto e RH/Escola de Gestão) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Ponto Eletrônico -->
        <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow duration-300">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">watch_later</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Integração Ponto Eletrônico</h4>
                            <p class="text-[10px] text-slate-400 font-medium">Sincronizado via API em tempo real</p>
                        </div>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse" title="Sincronizado"></span>
                    <div class="grid grid-cols-3 gap-4 py-6">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                        <p class="text-2xl font-bold text-rose-600 font-mono">{{ $integrationData['ponto']['faltas_injustificadas'] }}</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest font-mono mt-1">Faltas Injustificadas</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                        <p class="text-2xl font-bold text-amber-600 font-mono">{{ $integrationData['ponto']['atrasos_minutos'] }}m</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest font-mono mt-1">Atrasos Acumulados</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                        <p class="text-2xl font-bold text-emerald-500 font-mono">+{{ $integrationData['ponto']['horas_extras'] }}h</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest font-mono mt-1">Horas Extras</p>
                    </div>
                </div>             </div>
            </div>
            <p class="text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-100 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-emerald-500">check_circle</span>
                {{ $integrationData['ponto']['mensagem'] }}
            </p>
        </x-card>

        <!-- Escola de Gestão / RH -->
        <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow duration-300">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">school</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Escola de Gestão (RH)</h4>
                            <p class="text-[10px] text-slate-400 font-medium">Créditos de formação validados no ciclo</p>
                        </div>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse" title="Sincronizado"></span>
                </div>
                <div class="grid grid-cols-3 gap-4 py-6">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-center col-span-1 flex flex-col justify-center">
                        <p class="text-2xl font-bold text-purple-600 font-mono">{{ $integrationData['rh']['horas_formacao'] }}h</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest font-mono mt-1">Carga Horária</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-left col-span-2 flex flex-col justify-center">
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest font-mono">Cursos Concluídos:</p>
                        <ul class="text-[11px] text-slate-700 font-semibold list-disc list-inside mt-1 space-y-0.5">
                            @foreach ($integrationData['rh']['cursos_concluidos'] as $curso)
                                <li>{{ $curso }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <p class="text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-100 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-emerald-500">check_circle</span>
                {{ $integrationData['rh']['mensagem'] }}
            </p>
        </x-card>
    </div>

    <!-- Formulário Principal da Avaliação -->
    @isset($evaluation)
        <form action="{{ route('evaluations.submit', $evaluation->id) }}" method="POST" enctype="multipart/form-data" id="evaluation-form" class="space-y-8">
    @else
        <form action="{{ route('evaluation.store') }}" method="POST" enctype="multipart/form-data" id="evaluation-form" class="space-y-8">
    @endisset
        @csrf
        <input type="hidden" name="evaluated_id" value="{{ $evaluated->id }}">
        <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">

        <!-- ═══════════════════════════════════════
             SEÇÃO 1: PACTUAÇÃO DE METAS QUANTITATIVAS
             ═══════════════════════════════════════ -->
        <x-card class="md:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">trending_up</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">1. Pactuação de Metas Quantitativas</h3>
                        <p class="text-xs text-slate-400">Acompanhamento e mensuração de objectives físicos do servidor</p>
                    </div>
                </div>
                
                <!-- Controle de Pesos Mistos -->
                <div class="flex items-center gap-4 bg-slate-50 p-2.5 rounded-xl border border-slate-200/50 text-xs">
                    <div class="flex items-center gap-2">
                        <label for="weight_goals" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Peso Metas:</label>
                        <select name="weight_goals" id="weight_goals" class="bg-white border border-slate-200 rounded-lg p-1.5 font-bold text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none">
                            <option value="0.30" {{ (old('weight_goals', $evaluation->weight_goals ?? 0.50) == 0.30) ? 'selected' : '' }}>30% (Secundário)</option>
                            <option value="0.40" {{ (old('weight_goals', $evaluation->weight_goals ?? 0.50) == 0.40) ? 'selected' : '' }}>40%</option>
                            <option value="0.50" {{ (old('weight_goals', $evaluation->weight_goals ?? 0.50) == 0.50) ? 'selected' : '' }}>50% (Equilibrado)</option>
                            <option value="0.60" {{ (old('weight_goals', $evaluation->weight_goals ?? 0.50) == 0.60) ? 'selected' : '' }}>60%</option>
                            <option value="0.70" {{ (old('weight_goals', $evaluation->weight_goals ?? 0.50) == 0.70) ? 'selected' : '' }}>70% (Predominante)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 border-l border-slate-200 pl-3">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Peso BARS:</span>
                        <span id="weight_competencies_badge" class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md font-bold font-mono border border-blue-150">50%</span>
                        <input type="hidden" name="weight_competencies" id="weight_competencies" value="{{ old('weight_competencies', $evaluation->weight_competencies ?? 0.50) }}">
                    </div>
                </div>
            </div>

            <!-- Tabela de Metas -->
            <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm">
                <table class="w-full text-sm text-left text-slate-600" id="goals-table">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 font-bold border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-4 py-3">Descrição da Meta</th>
                            <th scope="col" class="px-4 py-3 w-40">Métrica / Unidade</th>
                            <th scope="col" class="px-4 py-3 text-right w-32">Alvo Pactuado</th>
                            <th scope="col" class="px-4 py-3 text-right w-32">Valor Alcançado</th>
                            <th scope="col" class="px-4 py-3 text-right w-24">Peso</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white" id="goals-tbody">
                        @php
                            $goals = old('goals');
                            if (!$goals) {
                                if (isset($evaluation) && $evaluation->goals->isNotEmpty()) {
                                    $goals = $evaluation->goals;
                                } else {
                                    $goals = $cycle->global_goals ?? [];
                                }
                            }
                        @endphp
                        @forelse($goals as $idx => $goal)
                            @php
                                $desc = is_array($goal) ? ($goal['description'] ?? '') : $goal->description;
                                $metric = is_array($goal) ? ($goal['metric'] ?? '') : $goal->metric;
                                $target = is_array($goal) ? ($goal['target_value'] ?? '') : $goal->target_value;
                                $achieved = is_array($goal) ? ($goal['achieved_value'] ?? '') : $goal->achieved_value;
                                $weight = is_array($goal) ? ($goal['weight'] ?? 1.0) : $goal->weight;
                            @endphp
                            <tr class="goal-row hover:bg-slate-50/50 transition-colors" data-index="{{ $idx }}">
                                <td class="px-4 py-3">
                                    <input type="text" name="goals[{{ $idx }}][description]" value="{{ $desc }}" readonly class="w-full bg-slate-50/70 border border-transparent text-slate-500 text-sm py-1.5 px-2.5 outline-none rounded-lg cursor-not-allowed select-none" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="goals[{{ $idx }}][metric]" value="{{ $metric }}" readonly class="w-full bg-slate-50/70 border border-transparent text-slate-500 text-sm py-1.5 px-2.5 outline-none rounded-lg cursor-not-allowed select-none" required>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <input type="number" step="0.01" min="0" name="goals[{{ $idx }}][target_value]" value="{{ $target }}" readonly class="w-full bg-slate-50/70 border border-transparent text-slate-500 text-sm py-1.5 px-2.5 text-right outline-none rounded-lg cursor-not-allowed select-none font-mono" required>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <input type="number" step="0.01" min="0" name="goals[{{ $idx }}][achieved_value]" value="{{ $achieved }}" placeholder="Lançado pelo RH" class="w-full bg-slate-50/70 border border-transparent text-slate-500 text-sm py-1.5 px-2.5 text-right outline-none rounded-lg cursor-not-allowed select-none font-mono" readonly>
                                </td>
                                <td class="px-4 py-3 text-right font-mono">
                                    <input type="number" step="0.1" min="0" name="goals[{{ $idx }}][weight]" value="{{ $weight }}" readonly class="w-full bg-slate-50/70 border border-transparent text-slate-500 text-sm py-1.5 px-2.5 text-right outline-none rounded-lg cursor-not-allowed select-none" required>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-slate-400 font-medium">Nenhuma meta quantitativa cadastrada para este ciclo pelo RH.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Rodapé e Botões da Seção de Metas -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-2">
                <div class="text-[12px] text-blue-700 font-bold bg-blue-50/50 px-4 py-3 rounded-lg border border-blue-150 flex items-center gap-2 w-full">
                    <span class="material-symbols-outlined text-[18px] text-blue-600">info</span>
                    <span>As metas quantitativas acima são parametrizadas de forma global pelo RH e os valores alcançados serão lançados exclusivamente pelo RH ao final do ciclo.</span>
                </div>
            </div>
        </x-card>

        <!-- ═══════════════════════════════════════
             SEÇÃO 2: AVALIAÇÃO COMPORTAMENTAL BARS
             ═══════════════════════════════════════ -->
        <div class="space-y-8">
            <div class="flex items-center justify-between pb-2">
                <h3 class="text-md font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600">checklist</span>
                    <span>2. Avaliação Comportamental (BARS)</span>
                </h3>
                <x-button variant="secondary" type="button" class="!py-2 !px-4 flex items-center gap-1.5 !text-xs font-bold rounded-lg transition" onclick="openDiaryDrawer()">
                    <span class="material-symbols-outlined text-[16px] text-blue-600" style="font-variation-settings:'FILL' 1">book</span>
                    <span>Ver Diário Completo</span>
                </x-button>
            </div>

            @if(count($questions) > 0)
                @foreach ($questions as $category => $categoryQuestions)
                    <!-- Seção da Categoria -->
                    <x-card class="md:p-8 space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            @php
                                $icon = 'star';
                                $bg = 'bg-slate-100 text-slate-600';
                                if($category === 'assiduidade') { $icon = 'schedule'; $bg = 'bg-blue-50 text-blue-500'; }
                                elseif($category === 'disciplina') { $icon = 'gavel'; $bg = 'bg-rose-50 text-rose-500'; }
                                elseif($category === 'iniciativa') { $icon = 'lightbulb'; $bg = 'bg-amber-50 text-amber-500'; }
                                elseif($category === 'responsabilidade') { $icon = 'verified_user'; $bg = 'bg-emerald-50 text-emerald-500'; }
                                elseif($category === 'cooperacao') { $icon = 'groups'; $bg = 'bg-indigo-50 text-indigo-500'; }
                                elseif($category === 'qualidade') { $icon = 'workspace_premium'; $bg = 'bg-violet-50 text-violet-500'; }
                                elseif($category === 'desenvolvimento_rh') { $icon = 'school'; $bg = 'bg-purple-50 text-purple-500'; }
                                elseif($category === 'avaliacao_usuario') { $icon = 'sentiment_satisfied'; $bg = 'bg-teal-50 text-teal-500'; }
                            @endphp
                            <div class="w-10 h-10 rounded-xl {{ $bg }} flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-800 uppercase tracking-wider">{{ str_replace('_', ' ', $category) }}</h3>
                                <p class="text-xs text-slate-400">Peso do grupo indicador: <strong class="text-slate-600 font-bold">{{ $cycle->weights[$category] ?? 1 }}</strong></p>
                            </div>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach ($categoryQuestions as $index => $question)
                                @php
                                    $answer = isset($evaluation) ? $evaluation->answers->where('question_id', $question->id)->first() : null;
                                    $scoreValue = old("answers.{$question->id}") ?? ($answer ? $answer->score : null);
                                    $justificationValue = old("justifications.{$question->id}") ?? ($answer && $answer->criticalIncident ? $answer->criticalIncident->justification : '');
                                    $evidencePath = $answer && $answer->criticalIncident ? $answer->criticalIncident->evidence_path : '';
                                @endphp
                                <!-- Container da Pergunta -->
                                <div class="py-6 first:pt-0 last:pb-0 question-container" 
                                     data-question-id="{{ $question->id }}" 
                                     data-category="{{ $category }}"
                                     @foreach($question->barsAnchors as $anchor)
                                         data-anchor-{{ $anchor->score }}="{{ $anchor->behavioral_description }}"
                                     @endforeach
                                >
                                    <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-6">
                                        <!-- Texto da Pergunta e Badges -->
                                        <div class="flex-1 space-y-2">
                                            <p class="text-sm font-bold text-slate-700 leading-relaxed">
                                                {{ $index + 1 }}. {{ $question->text }}
                                            </p>
                                            
                                            <!-- Elemento de Incidente Vinculado (Badge) -->
                                            <div class="linked-incidents-container space-y-1.5">
                                                @if(isset($answer) && $answer->employeeDiaryIncidents->count() > 0)
                                                    @foreach($answer->employeeDiaryIncidents as $linkedIncident)
                                                        <div class="flex items-center justify-between bg-emerald-50 border border-emerald-100 rounded-lg p-2.5 text-xs text-emerald-800" data-incident-id="{{ $linkedIncident->id }}">
                                                            <div class="flex items-center gap-2">
                                                                <span class="material-symbols-outlined text-emerald-600 text-[16px] shrink-0">bookmark_added</span>
                                                                <span class="leading-relaxed font-semibold">
                                                                    <strong>[Diário Bordo - {{ \Carbon\Carbon::parse($linkedIncident->incident_date)->format('d/m/Y') }}]</strong> 
                                                                    {{ Str::limit($linkedIncident->description, 70) }}
                                                                </span>
                                                            </div>
                                                            <button type="button" class="text-rose-500 hover:text-rose-700 btn-remove-link p-1 rounded-md" data-question-id="{{ $question->id }}" data-incident-id="{{ $linkedIncident->id }}">
                                                                <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                                                            </button>
                                                            <input type="hidden" name="linked_incidents[{{ $question->id }}][]" value="{{ $linkedIncident->id }}" class="linked-incident-input">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            
                                            <!-- Feedback Visual de Evidência Preenchida -->
                                            <div class="evidence-badge hidden">
                                                <x-badge variant="success" class="gap-1.5 px-3 py-1 font-bold shadow-sm rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-250/50">
                                                    <span class="material-symbols-outlined text-[14px]">task_alt</span>
                                                    <span class="evidence-status-text">Evidência registrada com sucesso</span>
                                                </x-badge>
                                            </div>
                                        </div>

                                        <!-- Escala Segmentada BARS Horizontal (1 a 5) -->
                                        <div class="shrink-0 flex flex-col items-end gap-2 w-full sm:w-auto">
                                            <div class="flex items-center w-full sm:w-auto bg-slate-100 p-1 rounded-xl border border-slate-200/50 select-none">
                                                @php
                                                    $barsLabels = [
                                                        1 => 'Insatisfatório',
                                                        2 => 'Regular',
                                                        3 => 'Bom',
                                                        4 => 'Ótimo',
                                                        5 => 'Excelente',
                                                    ];
                                                @endphp
                                                @for ($score = 1; $score <= 5; $score++)
                                                    <label class="flex-1 sm:flex-none relative flex flex-col items-center justify-center px-4 py-2 border border-transparent rounded-lg cursor-pointer text-xs font-bold text-slate-500 hover:text-slate-800 transition-all select-none segment-label font-mono" data-score="{{ $score }}">
                                                        <input type="radio" 
                                                               name="answers[{{ $question->id }}]" 
                                                               value="{{ $score }}" 
                                                               class="sr-only score-radio"
                                                               {{ $scoreValue == $score ? 'checked' : '' }}>
                                                        <span class="text-sm font-bold font-mono">{{ $score }}</span>
                                                        <span class="text-[9px] uppercase tracking-wider text-slate-400 mt-0.5 hidden md:inline">{{ $barsLabels[$score] }}</span>
                                                    </label>
                                                @endfor
                                            </div>

                                            <!-- Botão de vincular do diário de bordo -->
                                            <button type="button" class="btn-open-diary inline-flex items-center gap-1.5 text-[11px] font-bold text-slate-500 hover:text-white hover:bg-blue-600 hover:border-blue-600 transition bg-white border border-slate-200 px-3 py-1.5 rounded-lg mt-1" data-question-id="{{ $question->id }}" data-category="{{ $category }}">
                                                <span class="material-symbols-outlined text-[14px]">bookmark</span>
                                                <span>Buscar no Diário de Bordo</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Bloco Dinâmico de Âncora BARS (Revela dinamicamente a âncora comportamental) -->
                                    <div class="mt-4 p-4 bg-slate-50 border border-slate-100 rounded-lg hidden transition-all duration-200 bars-description-box" id="bars-description-box-{{ $question->id }}">
                                        <div class="flex items-center justify-between border-b border-slate-200/50 pb-2 mb-2">
                                            <span class="text-[10px] font-bold uppercase text-slate-400 tracking-widest font-mono">Comportamento Ancorado de Referência (BARS)</span>
                                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded text-white bg-slate-400 bars-level-badge font-mono" id="bars-level-badge-{{ $question->id }}">Nível X</span>
                                        </div>
                                        <p class="text-xs text-slate-600 leading-relaxed font-semibold" id="bars-behavior-text-{{ $question->id }}">
                                            (Carregando descrição...)
                                        </p>
                                    </div>

                                    <!-- Div Secreta (Inputs Reais) de Incidente Crítico Ad-Hoc -->
                                    <div class="hidden-incident-inputs hidden" id="incident-inputs-{{ $question->id }}">
                                        <div class="space-y-4 py-2 incident-fields-wrapper">
                                            <div class="flex items-start gap-2.5 p-3.5 bg-blue-50 border border-blue-200/60 rounded-xl text-xs text-blue-800 leading-relaxed font-semibold">
                                                <span class="material-symbols-outlined text-[18px] text-blue-600 shrink-0">warning</span>
                                                <span>Notas críticas (1, 2 ou 5) exigem justificativa escrita e anexo comprobatório <strong>OU</strong> a vinculação de um incidente do Diário de Bordo.</span>
                                            </div>

                                            <div>
                                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Justificativa da Nota (Mín. 15 caracteres) <span class="text-rose-500">*</span></label>
                                                <textarea name="justifications[{{ $question->id }}]" 
                                                          rows="3" 
                                                          placeholder="Descreva de forma detalhada o incidente de conduta ou justificativa técnica de desempenho..." 
                                                          class="w-full text-slate-800 text-sm border border-slate-250 focus:border-blue-500 focus:ring-blue-500 p-3 outline-none rounded-lg justification-textarea">{{ $justificationValue }}</textarea>
                                            </div>

                                            <div>
                                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Documento Comprobatório (PDF/JPG/PNG max 5MB) <span class="text-rose-500">*</span></label>
                                                <div class="flex items-center justify-between p-3.5 border border-dashed border-slate-300 rounded-lg hover:bg-slate-50 transition cursor-pointer relative bg-white file-upload-box">
                                                    <div class="flex items-center gap-2">
                                                        <span class="material-symbols-outlined text-slate-400">upload_file</span>
                                                        <span class="text-xs text-slate-500 font-semibold file-name-label font-mono">
                                                            {{ $evidencePath ? basename($evidencePath) : 'Nenhum arquivo selecionado' }}
                                                        </span>
                                                    </div>
                                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 rounded-lg border border-slate-200 shrink-0">Buscar</span>
                                                    <input type="file" 
                                                           name="evidences[{{ $question->id }}]" 
                                                           accept=".pdf,.jpg,.jpeg,.png"
                                                           class="absolute inset-0 opacity-0 cursor-pointer evidence-file-input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                @endforeach
            @else
                <div class="bg-white p-8 rounded-xl border border-slate-100 text-center text-slate-500 shadow-sm">
                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">folder_open</span>
                    <p class="font-bold">Nenhuma competência cadastrada para o grupo funcional deste servidor.</p>
                </div>
            @endif

            <!-- Rodapé de Ações do Formulário -->
            <x-card class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm border border-slate-200">
                <div class="flex items-center gap-2 text-sm text-slate-500 font-semibold">
                    <span class="material-symbols-outlined text-blue-600">checklist</span>
                    <span id="form-progress-text" class="font-mono text-xs text-slate-500 ml-1">0 de 0 perguntas respondidas</span>
                </div>
                <div class="flex items-center gap-3">
                    <x-button variant="secondary" href="{{ route('dashboard') }}" class="font-bold">
                        Cancelar
                    </x-button>
                    <x-button type="submit" 
                              id="btn-submit-evaluation"
                              variant="primary"
                              class="disabled:opacity-50 disabled:pointer-events-none font-bold shadow-md shadow-blue-500/10">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        <span>Enviar Avaliação</span>
                    </x-button>
                </div>
            </x-card>
        </div>
    </form>
</div>

<!-- ═══════════════════════════════════════
     DRAWER DO DIÁRIO DE BORDO (LATERAL SLIDE)
     ═══════════════════════════════════════ -->
<div id="drawer-diary" class="fixed inset-0 z-[80] overflow-hidden hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 overflow-hidden">
        <!-- Backdrop translúcido -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300 drawer-backdrop" onclick="closeDiaryDrawer()"></div>

        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
            <!-- Painel deslizante -->
            <div class="pointer-events-auto w-screen max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-white shadow-2xl border-l border-slate-200 flex flex-col h-full">
                <!-- Cabeçalho -->
                <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-slate-200 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-blue-600" style="font-variation-settings:'FILL' 1">book</span>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900" id="slide-over-title">Diário de Bordo</h2>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">Vincular Incidente do Servidor</p>
                        </div>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-650 transition p-1" onclick="closeDiaryDrawer()">
                        <span class="material-symbols-outlined text-[22px]">close</span>
                    </button>
                </div>

                <!-- Filtros e Info -->
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col shrink-0">
                    <label for="diary-category-filter" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-1">Filtrar por Categoria:</label>
                    <select id="diary-category-filter" class="text-xs bg-white border border-slate-200 rounded-lg p-1.5 font-bold text-slate-700 outline-none focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">Ver Todas</option>
                        <option value="assiduidade">Assiduidade</option>
                        <option value="disciplina">Disciplina</option>
                        <option value="iniciativa">Iniciativa</option>
                        <option value="responsabilidade">Responsabilidade</option>
                        <option value="cooperacao">Cooperação</option>
                        <option value="qualidade">Qualidade</option>
                        <option value="desenvolvimento_rh">Desenvolvimento RH</option>
                        <option value="avaliacao_usuario">Avaliação pelo Usuário</option>
                    </select>
                </div>

                <!-- Conteúdo (Incidentes) -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar" id="diary-incidents-container">
                    @forelse($diaryIncidents as $incident)
                        @php
                            $isPos = $incident->type === 'positive';
                            $border = $isPos ? 'border-l-4 border-l-emerald-500' : 'border-l-4 border-l-rose-500';
                            $badgeBg = $isPos ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200';
                            $icon = $isPos ? 'thumb_up' : 'thumb_down';
                        @endphp
                        <div class="bg-white rounded-xl border border-slate-150 p-4 shadow-sm {{ $border }} hover:shadow-md transition duration-200 incident-item-card" data-category="{{ $incident->category }}" data-id="{{ $incident->id }}" data-desc="{{ $incident->description }}" data-date="{{ \Carbon\Carbon::parse($incident->incident_date)->format('d/m/Y') }}">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full border {{ $badgeBg }} flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[10px]">{{ $icon }}</span>
                                        {{ $incident->type === 'positive' ? 'Positivo' : 'Negativo' }}
                                    </span>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase bg-slate-100 text-slate-650 rounded-full border border-slate-200 font-mono">
                                        {{ str_replace('_', ' ', $incident->category) }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold font-mono">{{ \Carbon\Carbon::parse($incident->incident_date)->format('d/m/Y') }}</span>
                            </div>
                            
                            <p class="text-xs text-slate-700 leading-relaxed font-semibold mb-3">
                                {{ $incident->description }}
                            </p>
                            
                            <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 text-[10px]">
                                <span class="text-slate-450">Registrado por: <strong class="text-slate-600">{{ $incident->reporter->name ?? 'Chefia' }}</strong></span>
                                <x-button type="button" variant="primary" class="btn-link-incident-action !py-1.5 !px-3 !text-[10px] rounded-lg" data-id="{{ $incident->id }}">
                                    <span class="material-symbols-outlined text-[12px] font-bold">link</span>
                                    <span>Vincular</span>
                                </x-button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 text-slate-400">
                            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">history_edu</span>
                            <p class="font-bold text-sm">Diário Vazio</p>
                            <p class="text-xs text-slate-400 mt-1">Nenhum incidente cadastrado no diário de bordo deste servidor.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════
     MODAL DE INCIDENTE CRÍTICO (AD-HOC)
     ═══════════════════════════════════════ -->
<div id="modal-critical-incident" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <!-- Backdrop com blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm modal-backdrop" onclick="closeCriticalIncidentModal(false)"></div>
    
    <!-- Modal Card -->
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-lg mx-4 z-10 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]">
        <!-- Cabeçalho -->
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between select-none">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings:'FILL' 1">warning</span>
                <span class="text-sm font-bold text-slate-900">Atenção: Justificativa Exigida</span>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-650 transition close-modal-btn">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Conteúdo -->
        <div class="p-6 overflow-y-auto flex-1 space-y-4" id="modal-fields-container">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-150">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest font-mono block mb-1" id="modal-indicator-label">Indicador</span>
                <p class="text-xs font-bold text-slate-800" id="modal-question-text"></p>
            </div>
            
            <div id="modal-inputs-placeholder">
                <!-- Div com os inputs reais será movida para cá via jQuery -->
            </div>
        </div>

        <!-- Rodapé -->
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-150 flex items-center justify-end gap-3 shrink-0">
            <x-button type="button" 
                      variant="secondary" 
                      class="cancel-modal-btn text-xs px-4 py-2 font-bold">
                Cancelar e Trocar Nota
            </x-button>
            <x-button type="button" 
                      variant="primary"
                      class="confirm-modal-btn text-xs px-4 py-2 font-bold shadow-md shadow-blue-500/10">
                Gravar Evidência
            </x-button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentQuestionId = null;
    let currentOriginalScore = null;
    let targetQuestionIdForDiary = null;

    // ─── PESOS MISTOS: AJUSTE AUTOMÁTICO DINÂMICO ───
    function adjustWeights() {
        let weightGoalsVal = parseFloat($('#weight_goals').val());
        let weightCompetenciesVal = (1.00 - weightGoalsVal).toFixed(2);
        
        // Atualiza campos e Badges
        $('#weight_competencies').val(weightCompetenciesVal);
        $('#weight_competencies_badge').text(Math.round(weightCompetenciesVal * 100) + '%');
    }
    $('#weight_goals').on('change', adjustWeights);
    adjustWeights(); // Inicializar



    // ─── INICIALIZAÇÃO DE ESTADOS VISUAIS BARS ───
    $('.score-radio:checked').each(function() {
        let radio = $(this);
        let score = parseInt(radio.val());
        let label = radio.closest('.segment-label');
        let questionContainer = radio.closest('.question-container');
        let questionId = questionContainer.data('question-id');

        // Destaca a nota ativa
        highlightSegmentLabel(label, score);
        questionContainer.data('prev-checked', score);

        // Exibe descrição correspondente
        showBarsDescription(questionContainer, score);
        
        // Verifica a validação de nota crítica
        checkCriticalValidation(questionContainer, score);
    });

    function highlightSegmentLabel(activeLabel, score) {
        let container = activeLabel.closest('.select-none');
        container.find('.segment-label').removeClass('bg-blue-50 text-blue-700 border-blue-200 font-bold shadow-sm scale-105');
        
        // Destaca o novo botão clicado
        let targetLabel = container.find(`.segment-label[data-score="${score}"]`);
        targetLabel.addClass('bg-blue-50 text-blue-700 border-blue-200 font-bold shadow-sm scale-105')
                   .removeClass('text-slate-500 bg-transparent');
    }

    function showBarsDescription(container, score) {
        let questionId = container.data('question-id');
        let desc = container.data('anchor-' + score);
        
        // Fallback para descrição genérica caso não haja no banco
        if (!desc) {
            let fallbacks = {
                1: 'Comportamento reativo, prejudicial ou omisso. Fere normas e necessita de intervenção corretiva imediata.',
                2: 'Comportamento inconstante. Necessita de supervisão e cobrança constantes para entregar o mínimo.',
                3: 'Atende plenamente ao esperado. Executa a atribuição com autonomia e qualidade regular.',
                4: 'Acima do esperado. Demonstra proatividade e entrega com qualidade superior e eficiência.',
                5: 'Excelente. Atua como modelo para a equipe, inova, lidera e antecipa riscos operacionais.'
            };
            desc = fallbacks[score];
        }

        let descBox = $(`#bars-description-box-${questionId}`);
        let levelBadge = $(`#bars-level-badge-${questionId}`);
        let behaviorText = $(`#bars-behavior-text-${questionId}`);

        let labels = { 1: 'Insatisfatório', 2: 'Regular', 3: 'Bom', 4: 'Ótimo', 5: 'Excelente' };
        let badgeColors = {
            1: 'bg-rose-500',
            2: 'bg-amber-500',
            3: 'bg-blue-500',
            4: 'bg-emerald-500',
            5: 'bg-emerald-500'
        };

        levelBadge.removeClass('bg-slate-400 bg-rose-500 bg-amber-500 bg-blue-500 bg-emerald-500')
                  .addClass(badgeColors[score])
                  .text(`Nível ${score} - ${labels[score]}`);
                  
        behaviorText.text(desc);
        descBox.slideDown(200);
    }

    function checkCriticalValidation(container, score) {
        let questionId = container.data('question-id');
        let isCritical = [1, 2, 5].includes(score);
        let badge = container.find('.evidence-badge');

        if (isCritical) {
            let hasDiaryIncident = container.find('.linked-incident-input').length > 0;
            let hasAdHocJustification = container.find('.justification-textarea').val().trim().length >= 15;
            let hasAdHocFile = container.find('.evidence-file-input').val() !== '' || 
                               (container.find('.file-name-label').text() !== 'Nenhum arquivo selecionado' && 
                                container.find('.file-name-label').text().trim() !== '');

            if (hasDiaryIncident) {
                badge.find('.evidence-status-text').text('Evidência do Diário de Bordo vinculada');
                badge.removeClass('hidden');
            } else if (hasAdHocJustification && hasAdHocFile) {
                badge.find('.evidence-status-text').text('Justificativa e documento anexados');
                badge.removeClass('hidden');
            } else {
                badge.addClass('hidden');
            }
        } else {
            badge.addClass('hidden');
        }
    }

    // ─── CLIQUE NAS NOTAS DA ESCALA ───
    $('.score-radio').on('click', function() {
        let radio = $(this);
        let questionContainer = radio.closest('.question-container');
        let questionId = questionContainer.data('question-id');
        let score = parseInt(radio.val());
        let prevChecked = questionContainer.data('prev-checked');
        
        highlightSegmentLabel(radio.closest('.segment-label'), score);
        showBarsDescription(questionContainer, score);

        let hasDiaryIncident = questionContainer.find('.linked-incident-input').length > 0;

        if ([1, 2, 5].includes(score)) {
            // Nota crítica
            if (hasDiaryIncident) {
                // Se já tem incidente do diário vinculado, não precisa do modal ad-hoc
                questionContainer.data('prev-checked', score);
                checkCriticalValidation(questionContainer, score);
                updateProgress();
            } else {
                // Abre o modal para justificativa ad-hoc
                currentQuestionId = questionId;
                currentOriginalScore = prevChecked || null;
                openCriticalIncidentModal(questionContainer, score);
            }
        } else {
            // Nota normal (3 ou 4) - Oculta qualquer exigência/badges
            questionContainer.find('.evidence-badge').addClass('hidden');
            questionContainer.data('prev-checked', score);
            updateProgress();
        }
    });

    // ─── AÇÕES DO MODAL AD-HOC ───
    function openCriticalIncidentModal(container, score) {
        let questionId = container.data('question-id');
        let questionText = container.find('.text-sm').text();
        let category = container.data('category');

        $('#modal-question-text').text(questionText.replace(/^\d+\.\s*/, ''));
        $('#modal-indicator-label').text(`Indicador: ${category.replace('_', ' ').toUpperCase()}`);

        let realInputsWrapper = $(`#incident-inputs-${questionId}`);
        realInputsWrapper.removeClass('hidden');
        $('#modal-inputs-placeholder').append(realInputsWrapper);

        let modal = $('#modal-critical-incident');
        modal.removeClass('hidden');
        setTimeout(function() {
            modal.find('.bg-white').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 50);
    }

    function closeCriticalIncidentModal(save) {
        let modal = $('#modal-critical-incident');
        let realInputsWrapper = $(`#incident-inputs-${currentQuestionId}`);
        let questionContainer = $(`.question-container[data-question-id="${currentQuestionId}"]`);
        
        modal.find('.bg-white').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        
        setTimeout(function() {
            modal.addClass('hidden');
            realInputsWrapper.addClass('hidden');
            questionContainer.append(realInputsWrapper);

            let score = questionContainer.find('.score-radio:checked').val();

            if (save) {
                questionContainer.data('prev-checked', score);
                checkCriticalValidation(questionContainer, score);
            } else {
                // Cancelou: Reverte a nota para a anterior
                questionContainer.find('.score-radio').prop('checked', false);
                
                if (currentOriginalScore) {
                    let originalRadio = questionContainer.find(`.score-radio[value="${currentOriginalScore}"]`);
                    originalRadio.prop('checked', true);
                    highlightSegmentLabel(originalRadio.closest('.segment-label'), currentOriginalScore);
                    showBarsDescription(questionContainer, currentOriginalScore);
                    questionContainer.data('prev-checked', currentOriginalScore);
                } else {
                    questionContainer.data('prev-checked', null);
                    questionContainer.find('.segment-label').removeClass('bg-blue-50 text-blue-700 border-blue-200 font-bold shadow-sm scale-105')
                                                            .addClass('text-slate-500 bg-transparent');
                    $(`#bars-description-box-${currentQuestionId}`).slideUp(200);
                }
                
                checkCriticalValidation(questionContainer, currentOriginalScore);
            }

            currentQuestionId = null;
            currentOriginalScore = null;
            updateProgress();
        }, 250);
    }

    $('.confirm-modal-btn').on('click', function() {
        let wrapper = $('#modal-inputs-placeholder');
        let text = wrapper.find('.justification-textarea').val();
        let file = wrapper.find('.evidence-file-input').val();
        let hasExistingFile = wrapper.find('.file-name-label').text() !== 'Nenhum arquivo selecionado' && 
                              wrapper.find('.file-name-label').text().trim() !== '';
        
        let errors = false;

        wrapper.find('.justification-textarea').removeClass('border-rose-500 focus:border-rose-500');
        wrapper.find('.file-upload-box').removeClass('border-rose-500');

        if (!text || text.trim().length < 15) {
            wrapper.find('.justification-textarea').addClass('border-rose-500 focus:border-rose-500');
            errors = true;
        }

        if (!file && !hasExistingFile) {
            wrapper.find('.file-upload-box').addClass('border-rose-500');
            errors = true;
        }

        if (errors) {
            alert('Atenção: Para prosseguir com esta nota, preencha a justificativa (min. 15 caracteres) e anexe um arquivo comprobatório.');
            return;
        }

        closeCriticalIncidentModal(true);
    });

    $('.cancel-modal-btn, .close-modal-btn').on('click', function() {
        closeCriticalIncidentModal(false);
    });

    // ─── DRAWER DO DIÁRIO DE BORDO ───
    window.openDiaryDrawer = function() {
        let drawer = $('#drawer-diary');
        drawer.removeClass('hidden');
        setTimeout(function() {
            drawer.find('.drawer-backdrop').removeClass('opacity-0').addClass('opacity-100');
            drawer.find('.translate-x-full').removeClass('translate-x-full');
        }, 50);
    };

    window.closeDiaryDrawer = function() {
        let drawer = $('#drawer-diary');
        drawer.find('.drawer-backdrop').removeClass('opacity-100').addClass('opacity-0');
        drawer.find('.pointer-events-auto').addClass('translate-x-full');
        setTimeout(function() {
            drawer.addClass('hidden');
            targetQuestionIdForDiary = null;
        }, 300);
    };

    // Abre o diário focado em uma pergunta específica
    $(document).on('click', '.btn-open-diary', function() {
        let button = $(this);
        targetQuestionIdForDiary = button.data('question-id');
        let category = button.data('category');

        // Filtra no select
        $('#diary-category-filter').val(category).trigger('change');
        
        openDiaryDrawer();
    });

    // Filtro de Categorias no Diário
    $('#diary-category-filter').on('change', function() {
        let val = $(this).val();
        if (val === 'all') {
            $('.incident-item-card').slideDown(200);
        } else {
            $('.incident-item-card').each(function() {
                let card = $(this);
                if (card.data('category') === val) {
                    card.slideDown(200);
                } else {
                    card.slideUp(200);
                }
            });
        }
    });

    // Ação de Vincular Incidente
    $(document).on('click', '.btn-link-incident-action', function() {
        let button = $(this);
        let card = button.closest('.incident-item-card');
        let incidentId = card.data('id');
        let date = card.data('date');
        let desc = card.data('desc');

        if (!targetQuestionIdForDiary) {
            // Se abriu o diário genérico, pergunta a qual indicador vincular
            alert('Por favor, clique no botão "Buscar no Diário de Bordo" no card da competência que você deseja justificar.');
            closeDiaryDrawer();
            return;
        }

        let questionContainer = $(`.question-container[data-question-id="${targetQuestionIdForDiary}"]`);
        
        // Evita duplicidade
        if (questionContainer.find(`.linked-incident-input[value="${incidentId}"]`).length > 0) {
            alert('Este incidente já está vinculado a este indicador.');
            return;
        }

        // Gera o bloco html de vínculo
        let linkHtml = `
            <div class="flex items-center justify-between bg-emerald-50 border border-emerald-100 rounded-lg p-2.5 text-xs text-emerald-800 transition animate-fadeIn" data-incident-id="${incidentId}">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 text-[16px] shrink-0">bookmark_added</span>
                    <span class="leading-relaxed font-semibold">
                        <strong>[Diário Bordo - ${date}]</strong> 
                        ${desc.substring(0, 70)}${desc.length > 70 ? '...' : ''}
                    </span>
                </div>
                <button type="button" class="text-rose-500 hover:text-rose-700 btn-remove-link p-1 rounded-md" data-question-id="${targetQuestionIdForDiary}" data-incident-id="${incidentId}">
                    <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                </button>
                <input type="hidden" name="linked_incidents[${targetQuestionIdForDiary}][]" value="${incidentId}" class="linked-incident-input">
            </div>
        `;

        questionContainer.find('.linked-incidents-container').append(linkHtml);
        
        // Verifica a validação de nota crítica
        let currentScore = questionContainer.find('.score-radio:checked').val();
        if (currentScore) {
            checkCriticalValidation(questionContainer, parseInt(currentScore));
        }

        closeDiaryDrawer();
    });

    // Ação de Desvincular
    $(document).on('click', '.btn-remove-link', function() {
        let button = $(this);
        let questionId = button.data('question-id');
        let incidentId = button.data('incident-id');
        let container = button.closest('.question-container');

        button.closest('[data-incident-id]').remove();

        // Reavalia validação de nota crítica
        let currentScore = container.find('.score-radio:checked').val();
        if (currentScore) {
            checkCriticalValidation(container, parseInt(currentScore));
        }
        updateProgress();
    });

    // ─── PROGRESSO DO FORMULÁRIO ───
    function updateProgress() {
        let totalQuestions = $('.question-container').length;
        let answeredQuestions = $('.score-radio:checked').length;
        
        $('#form-progress-text').text(`${answeredQuestions} de ${totalQuestions} competências respondidas`);
        
        if (answeredQuestions === totalQuestions) {
            $('#btn-submit-evaluation').prop('disabled', false);
        } else {
            $('#btn-submit-evaluation').prop('disabled', true);
        }
    }
    updateProgress();

    // ─── NOME DE ARQUIVOS NOS UPLOADS AD-HOC ───
    $(document).on('change', '.evidence-file-input', function(e) {
        let input = $(this);
        let fileName = e.target.files[0] ? e.target.files[0].name : 'Nenhum arquivo selecionado';
        input.closest('.file-upload-box').find('.file-name-label').text(fileName);
        
        let container = input.closest('.question-container');
        let score = container.find('.score-radio:checked').val();
        if (score) {
            checkCriticalValidation(container, parseInt(score));
        }
        updateProgress();
    });

    $(document).on('keyup', '.justification-textarea', function() {
        let container = $(this).closest('.question-container');
        let score = container.find('.score-radio:checked').val();
        if (score) {
            checkCriticalValidation(container, parseInt(score));
        }
        updateProgress();
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.2s ease-out forwards;
}
.segment-label {
    border-radius: 8px;
    margin: 1px;
}
</style>
@endsection
