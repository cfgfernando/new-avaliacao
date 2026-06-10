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
        <input type="hidden" name="submit_type" id="submit_type" value="submit">
        <input type="hidden" name="evaluated_id" value="{{ $evaluated->id }}">
        <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">

        <!-- ═══════════════════════════════════════
             SEÇÃO 1: PACTUAÇÃO DE METAS QUANTITATIVAS (Inputs Ocultos)
             ═══════════════════════════════════════ -->
        <input type="hidden" name="weight_goals" id="weight_goals" value="{{ old('weight_goals', $evaluation->weight_goals ?? 0.50) }}">
        <input type="hidden" name="weight_competencies" id="weight_competencies" value="{{ old('weight_competencies', $evaluation->weight_competencies ?? 0.50) }}">

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
        @foreach($goals as $idx => $goal)
            @php
                $desc = is_array($goal) ? ($goal['description'] ?? '') : $goal->description;
                $metric = is_array($goal) ? ($goal['metric'] ?? '') : $goal->metric;
                $target = is_array($goal) ? ($goal['target_value'] ?? '') : $goal->target_value;
                $achieved = is_array($goal) ? ($goal['achieved_value'] ?? '') : $goal->achieved_value;
                $weight = is_array($goal) ? ($goal['weight'] ?? 1.0) : $goal->weight;
            @endphp
            <input type="hidden" name="goals[{{ $idx }}][description]" value="{{ $desc }}">
            <input type="hidden" name="goals[{{ $idx }}][metric]" value="{{ $metric }}">
            <input type="hidden" name="goals[{{ $idx }}][target_value]" value="{{ $target }}">
            <input type="hidden" name="goals[{{ $idx }}][achieved_value]" value="{{ $achieved }}">
            <input type="hidden" name="goals[{{ $idx }}][weight]" value="{{ $weight }}">
        @endforeach

        <!-- ═══════════════════════════════════════
             SEÇÃO 2: AVALIAÇÃO COMPORTAMENTAL BARS
             ═══════════════════════════════════════ -->
        <div class="space-y-8">
            <div class="flex items-center justify-between pb-2">
                <h3 class="text-md font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600">checklist</span>
                    <span>2. Avaliação Comportamental (BARS)</span>
                </h3>
                <button type="button" id="btn-toggle-all" onclick="toggleAllAccordions()" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-slate-500 hover:text-white hover:bg-blue-600 hover:border-blue-600 transition bg-white border border-slate-200 px-3 py-1.5 rounded-lg">
                    <span class="material-symbols-outlined text-[14px]">unfold_more</span>
                    <span>Expandir Todos</span>
                </button>
            </div>
            @if(count($questions) > 0)

                @foreach ($questions as $category => $categoryQuestions)
                    @php
                        $answeredCount = 0;
                        $categoryHasError = false;
                        foreach ($categoryQuestions as $q) {
                            $scoreValue = old("answers.{$q->id}") ?? (isset($evaluation) ? $evaluation->answers->where('question_id', $q->id)->first()?->score : null);
                            if ($scoreValue !== null) {
                                $answeredCount++;
                            }
                            if ($errors->has("answers.{$q->id}") || $errors->has("justifications.{$q->id}") || $errors->has("evidences.{$q->id}")) {
                                $categoryHasError = true;
                            }
                        }
                        $totalCount = count($categoryQuestions);
                        $categoryIsOpen = ($answeredCount > 0) || $categoryHasError;
                        
                        if ($categoryHasError) {
                            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                        } elseif ($answeredCount === $totalCount) {
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        } elseif ($answeredCount > 0) {
                            $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                        } else {
                            $badgeClass = 'bg-slate-100 text-slate-500 border-slate-200';
                        }

                        // Filtragem e mapeamento de incidentes do Diário de Bordo para esta categoria
                        $normalizeCategoryPhp = function($str) {
                            if (!$str) return "";
                            $str = mb_strtolower($str, 'UTF-8');
                            $str = preg_replace('/[áàâãä]/u', 'a', $str);
                            $str = preg_replace('/[éèêë]/u', 'e', $str);
                            $str = preg_replace('/[íìîï]/u', 'i', $str);
                            $str = preg_replace('/[óòôõö]/u', 'o', $str);
                            $str = preg_replace('/[úùûü]/u', 'u', $str);
                            $str = preg_replace('/[ç]/u', 'c', $str);
                            $normalized = preg_replace('/[^a-z0-9]/', '', $str);
                            
                            $aliases = [
                                'pontualidade' => 'assiduidade',
                                'trabalhoemequipe' => 'cooperacao',
                                'etica' => 'disciplina'
                            ];
                            return $aliases[$normalized] ?? $normalized;
                        };

                        $normalizedCategory = $normalizeCategoryPhp($category);
                        $categoryIncidents = $diaryIncidents->filter(function($incident) use ($normalizeCategoryPhp, $normalizedCategory) {
                            return $normalizeCategoryPhp($incident->category) === $normalizedCategory;
                        });

                        $hasNegative = $categoryIncidents->contains('type', 'negative');
                        $hasPositive = $categoryIncidents->contains('type', 'positive');
                        $incidentCount = $categoryIncidents->count();
                        $negativesCount = $categoryIncidents->where('type', 'negative')->count();
                        $positivesCount = $categoryIncidents->where('type', 'positive')->count();
                    @endphp

                    <!-- Card da Categoria como Accordion -->
                    <x-card class="p-0 overflow-hidden border border-slate-200 shadow-sm category-card" data-category="{{ $category }}">
                        <!-- Accordion Header -->
                        <div class="category-accordion-header flex items-center justify-between p-6 cursor-pointer select-none bg-slate-50/50 hover:bg-slate-50 transition-colors {{ !$categoryIsOpen ? 'rounded-b-xl' : 'border-b border-slate-100' }}" data-category="{{ $category }}">
                            <div class="flex items-center gap-3">
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
                            
                            <div class="flex items-center gap-2 shrink-0">
                                @if($incidentCount > 0)
                                    @if($hasNegative)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[9.5px] font-extrabold uppercase rounded-md border bg-rose-50 text-rose-700 border-rose-200">
                                            <span class="material-symbols-outlined text-[12px] font-bold" style="font-variation-settings: 'FILL' 1">warning</span>
                                            Diário: {{ $negativesCount }} Alerta(s)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[9.5px] font-extrabold uppercase rounded-md border bg-emerald-50 text-emerald-700 border-emerald-250/60">
                                            <span class="material-symbols-outlined text-[12px] font-bold" style="font-variation-settings: 'FILL' 1">thumb_up</span>
                                            Diário: {{ $positivesCount }} Elogio(s)
                                        </span>
                                    @endif
                                @endif
                                <span class="category-progress-badge px-2.5 py-1 text-[10px] font-bold uppercase rounded-md border {{ $badgeClass }}">
                                    {{ $answeredCount }} de {{ $totalCount }} respondidas
                                </span>
                                <span class="material-symbols-outlined text-[20px] text-slate-400 transition-transform duration-200 category-accordion-chevron {{ $categoryIsOpen ? 'rotate-180' : '' }}">
                                    expand_more
                                </span>
                            </div>
                        </div>

                        <!-- Accordion Content -->
                        <div class="category-accordion-content p-6 space-y-6 {{ !$categoryIsOpen ? 'hidden' : '' }}" {!! !$categoryIsOpen ? 'style="display: none;"' : '' !!}>
                            @foreach ($categoryQuestions as $index => $question)
                                @php
                                    $answer = isset($evaluation) ? $evaluation->answers->where('question_id', $question->id)->first() : null;
                                    $scoreValue = old("answers.{$question->id}") ?? ($answer ? $answer->score : null);
                                    $justificationValue = old("justifications.{$question->id}") ?? ($answer && $answer->criticalIncident ? $answer->criticalIncident->justification : '');
                                    $evidencePath = $answer && $answer->criticalIncident ? $answer->criticalIncident->evidence_path : '';
                                    $hasError = $errors->has("answers.{$question->id}") || $errors->has("justifications.{$question->id}") || $errors->has("evidences.{$question->id}");
                                @endphp
                                
                                <!-- Container da Pergunta (Sem Accordion) -->
                                <div class="question-row border-b border-slate-100 last:border-b-0 pb-6 mb-6 last:pb-0 last:mb-0 transition-all duration-200 question-container {{ $hasError ? 'bg-rose-50/30 p-4 rounded-xl border border-rose-300/80' : '' }}" 
                                     data-question-id="{{ $question->id }}" 
                                     data-category="{{ $category }}"
                                     @foreach($question->barsAnchors as $anchor)
                                         data-anchor-{{ $anchor->score }}="{{ $anchor->behavioral_description }}"
                                     @endforeach
                                >
                                    <!-- Cabeçalho da Pergunta Linear -->
                                    <div class="flex items-center gap-3 mb-4">
                                        <span class="material-symbols-outlined text-[20px] text-slate-400 shrink-0 select-none">help_outline</span>
                                        <p class="text-sm font-bold text-slate-700 leading-relaxed text-left">
                                            {{ $index + 1 }}. {{ $question->text }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-6">
                                        <!-- Texto da Pergunta e Badges -->
                                        <div class="flex-1 space-y-2">
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
                                            <div class="evidence-badge {{ ($scoreValue !== null && in_array((int)$scoreValue, [1, 2, 5])) ? '' : 'hidden' }}">
                                                <x-badge variant="success" class="gap-1.5 px-3 py-1 font-bold shadow-sm rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-250/50">
                                                    <span class="material-symbols-outlined text-[14px]">task_alt</span>
                                                    <span class="evidence-status-text">
                                                        @if($scoreValue !== null && in_array((int)$scoreValue, [1, 2, 5]))
                                                            @if(isset($answer) && $answer->employeeDiaryIncidents->count() > 0)
                                                                Evidência do Diário de Bordo vinculada
                                                            @else
                                                                Justificativa e documento anexados
                                                            @endif
                                                        @else
                                                            Evidência registrada com sucesso
                                                        @endif
                                                    </span>
                                                </x-badge>
                                            </div>
                                        </div>

                                        <!-- Escala Segmentada BARS Horizontal (1 a 5) -->
                                        <div class="shrink-0 flex flex-col items-end gap-2 w-full sm:w-auto font-mono">
                                            <div class="flex items-center w-full sm:w-auto bg-slate-100 p-1 rounded-xl border border-slate-200/50 select-none">
                                                @php
                                                    $barsLabels = [
                                                        1 => 'Insuficiente',
                                                        2 => 'Abaixo do esperado',
                                                        3 => 'Dentro do esperado',
                                                        4 => 'Acima do esperado',
                                                        5 => 'Excelente',
                                                    ];
                                                @endphp
                                                @for ($score = 1; $score <= 5; $score++)
                                                @php
                                                    $isActive = ($scoreValue == $score);
                                                    $btnActiveClasses = '';
                                                    if ($isActive) {
                                                    $activeStyles = [
                                                        1 => 'bg-rose-600 text-white border-rose-700 shadow-sm scale-105 font-bold',
                                                        2 => 'bg-amber-500 text-gray-900 border-amber-600 shadow-sm scale-105 font-bold',
                                                        3 => 'bg-blue-600 text-white border-blue-700 shadow-sm scale-105 font-bold',
                                                        4 => 'bg-emerald-500 text-gray-900 border-emerald-600 shadow-sm scale-105 font-bold',
                                                        5 => 'bg-emerald-700 text-white border-emerald-800 shadow-sm scale-105 font-bold'
                                                    ];
                                                        $btnActiveClasses = $activeStyles[$score];
                                                    } else {
                                                        $btnActiveClasses = 'text-slate-500 hover:text-slate-800 bg-transparent border-transparent';
                                                    }
                                                @endphp
                                                    <label class="flex-1 sm:flex-none relative flex flex-col items-center justify-center px-4 py-2 border rounded-lg cursor-pointer text-xs transition-all select-none segment-label font-mono {{ $btnActiveClasses }}" data-score="{{ $score }}">
                                                         <input type="radio" 
                                                                name="answers[{{ $question->id }}]" 
                                                                value="{{ $score }}" 
                                                                class="sr-only score-radio"
                                                                {{ $isActive ? 'checked' : '' }}>
                                                         <span class="text-sm font-bold font-mono">{{ $score }}</span>
                                                         <span class="text-[9px] uppercase tracking-wider mt-0.5 hidden md:inline opacity-60">{{ $barsLabels[$score] }}</span>
                                                    </label>
                                                @endfor
                                            </div>

                                            <!-- Botão de vincular do diário de bordo -->
                                            @php
                                                $btnClass = 'text-slate-500 hover:text-white hover:bg-blue-600 hover:border-blue-600 bg-white border-slate-200';
                                                $btnIcon = 'bookmark';
                                                if ($incidentCount > 0) {
                                                    if ($hasNegative) {
                                                        $btnClass = 'bg-rose-50 border-rose-250 text-rose-700 hover:bg-rose-100 hover:border-rose-350 hover:text-rose-800';
                                                        $btnIcon = 'warning';
                                                    } else {
                                                        $btnClass = 'bg-emerald-50 border-emerald-250 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-350 hover:text-emerald-800';
                                                        $btnIcon = 'thumb_up';
                                                    }
                                                }
                                            @endphp
                                            <button type="button" class="btn-open-diary inline-flex items-center gap-1.5 text-[11px] font-bold transition border px-3 py-1.5 rounded-lg mt-1 {{ $btnClass }}" data-question-id="{{ $question->id }}" data-category="{{ $category }}">
                                                <span class="material-symbols-outlined text-[14px]">{{ $btnIcon }}</span>
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
                                        <p class="text-xs text-slate-650 leading-relaxed font-semibold" id="bars-behavior-text-{{ $question->id }}">
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
                              id="btn-draft-evaluation"
                              variant="secondary"
                              class="font-bold border border-slate-300 hover:bg-slate-200">
                        <span class="material-symbols-outlined text-[18px]">draft</span>
                        <span>Salvar como Rascunho</span>
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
                            </div>
                            <div class="flex items-center justify-between text-[11px] mb-2">
                                <span class="text-slate-450">Registrado por: <strong class="text-slate-600">{{ $incident->reporter?->name ?? 'Chefia' }}</strong></span>
                                <span class="text-slate-400"><i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($incident->incident_date)->format('d/m/Y') }}</span>
                            </div>
                            
                            <p class="text-xs text-slate-700 leading-relaxed font-semibold mb-3">
                                {{ $incident->description }}
                            </p>
                            
                            <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 text-[10px]">
                                <span class="text-slate-450">Registrado por: <strong class="text-slate-600">{{ $incident->reporter?->name ?? 'Chefia' }}</strong></span>
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

<!-- ═══════════════════════════════════════
     MODAL DE ALERTA GENÉRICO
     ═══════════════════════════════════════ -->
<div id="modal-alert" class="fixed inset-0 z-[110] flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeAlertModal()"></div>
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md mx-4 z-10 overflow-hidden modal-alert-inner opacity-0 transition-all duration-300">
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between select-none">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-amber-500" style="font-variation-settings:'FILL' 1">info</span>
                <span class="text-sm font-bold text-slate-900">Atenção</span>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-600 transition" onclick="closeAlertModal()">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6">
            <div class="text-sm text-slate-700 leading-relaxed" id="modal-alert-message"></div>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-150 flex items-center justify-end shrink-0">
            <button type="button" onclick="closeAlertModal()" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs px-4 py-2 rounded-lg transition">
                OK
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════
     BOTÃO VOLTAR AO TOPO
     ═══════════════════════════════════════ -->
<button id="scroll-to-top" type="button" class="fixed bottom-6 right-6 z-50 w-10 h-10 bg-white border border-slate-200 rounded-full shadow-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition-all duration-300 opacity-0 invisible">
    <span class="material-symbols-outlined text-[20px]">arrow_upward</span>
</button>

<script>
function openAlertModal(message) {
    $('#modal-alert-message').html(message);
    let modal = $('#modal-alert');
    modal.removeClass('hidden');
    setTimeout(function() {
        modal.find('.modal-alert-inner').removeClass('opacity-0').addClass('opacity-100');
    }, 50);
}

function closeAlertModal() {
    if (window.redirectTimeout) {
        clearTimeout(window.redirectTimeout);
        window.redirectTimeout = null;
    }
    let modal = $('#modal-alert');
    modal.find('.modal-alert-inner').addClass('opacity-0').removeClass('opacity-100');
    setTimeout(function() {
        modal.addClass('hidden');
    }, 250);
}

function toggleAllAccordions() {
    let btn = $('#btn-toggle-all');
    let expand = btn.text().includes('Expandir');

    $('.category-card').each(function() {
        let card = $(this);
        let header = card.find('.category-accordion-header');
        let content = card.find('.category-accordion-content');
        let chevron = card.find('.category-accordion-chevron');
        let isHidden = content.hasClass('hidden') || content.css('display') === 'none';

        if (expand && isHidden) {
            content.removeClass('hidden').hide().slideDown(250, function() {
                header.addClass('border-b border-slate-100').removeClass('rounded-b-xl');
                chevron.addClass('rotate-180');
            });
        } else if (!expand && !isHidden) {
            content.slideUp(250, function() {
                header.removeClass('border-b border-slate-100').addClass('rounded-b-xl');
                chevron.removeClass('rotate-180');
            });
        }
    });

    btn.find('span:last').text(expand ? 'Recolher Todos' : 'Expandir Todos');
    btn.find('.material-symbols-outlined').text(expand ? 'unfold_less' : 'unfold_more');
}

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

    // ─── ACCORDIONS: SINCRO DO BADGE DE PROGRESSE DE CATEGORIA ───
    function updateCategoryProgress(category) {
        let card = $(`.category-card[data-category="${category}"]`);
        let questions = card.find('.question-container');
        let total = questions.length;
        let answered = 0;

        questions.each(function() {
            let q = $(this);
            let score = q.find('.score-radio:checked').val();
            if (score !== undefined && score !== null && score !== '') {
                answered++;
            }
        });

        let errorsInCat = card.find('.question-row.border-rose-300').length > 0 || 
                          card.find('.justification-textarea.border-rose-500').length > 0 || 
                          card.find('.file-upload-box.border-rose-500').length > 0;

        let badge = card.find('.category-progress-badge');
        badge.text(`${answered} de ${total} respondidas`);

        // Atualiza a classe de cor do badge
        badge.removeClass('bg-rose-50 text-rose-700 border-rose-200 bg-emerald-50 text-emerald-700 border-emerald-200 bg-blue-50 text-blue-700 border-blue-200 bg-slate-100 text-slate-500 border-slate-200');

        if (errorsInCat) {
            badge.addClass('bg-rose-50 text-rose-700 border-rose-200');
        } else if (answered === total) {
            badge.addClass('bg-emerald-50 text-emerald-700 border-emerald-200');
        } else if (answered > 0) {
            badge.addClass('bg-blue-50 text-blue-700 border-blue-200');
        } else {
            badge.addClass('bg-slate-100 text-slate-500 border-slate-200');
        }
    }

    function handleQuestionScoreChange(questionContainer, score) {
        // Remove estilo de erro caso o usuário preencha a nota
        if (score !== null && score !== undefined && !isNaN(score)) {
            questionContainer.removeClass('bg-rose-50/30 p-4 rounded-xl border border-rose-300/80');
        }
        
        // Marcação lateral esquerda com a cor da nota
        questionContainer.removeClass('border-l-4 border-l-rose-500 border-l-amber-500 border-l-blue-500 border-l-emerald-500 border-l-emerald-700');
        if (score !== null && score !== undefined && !isNaN(score) && score >= 1 && score <= 5) {
            const borderColors = {
                1: 'border-l-rose-500',
                2: 'border-l-amber-500',
                3: 'border-l-blue-500',
                4: 'border-l-emerald-500',
                5: 'border-l-emerald-700'
            };
            questionContainer.addClass('border-l-4 ' + borderColors[score]);
        }
        
        let category = questionContainer.data('category');
        updateCategoryProgress(category);
    }

    // ─── ACCORDIONS: EVENTO DE CLIQUE NO HEADER DA CATEGORIA ───
    $(document).on('click', '.category-accordion-header', function(e) {
        // Só bloqueia se o clique foi diretamente num input ou button
        let tag = $(e.target).prop('tagName').toLowerCase();
        if (tag === 'input' || tag === 'button' || tag === 'a') {
            return;
        }

        let header = $(this);
        let categoryName = header.data('category');

        // Localiza o card pelo data-category (mais robusto que closest)
        let card = $('.category-card[data-category="' + categoryName + '"]');
        let content = card.find('.category-accordion-content').first();
        let chevron = header.find('.category-accordion-chevron').first();

        // Detecta se está fechado (hidden do Tailwind OU display:none do inline style)
        let isHidden = content.hasClass('hidden') || content.css('display') === 'none';

        if (isHidden) {
            content.removeClass('hidden').hide().slideDown(250, function() {
                header.addClass('border-b border-slate-100').removeClass('rounded-b-xl');
                chevron.addClass('rotate-180');
            });
        } else {
            content.slideUp(250, function() {
                header.removeClass('border-b border-slate-100').addClass('rounded-b-xl');
                chevron.removeClass('rotate-180');
            });
        }
    });

    // ─── BOTÃO VOLTAR AO TOPO ───
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 400) {
            $('#scroll-to-top').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
        } else {
            $('#scroll-to-top').addClass('opacity-0 invisible').removeClass('opacity-100 visible');
        }
    });

    $('#scroll-to-top').on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 400);
    });

    // ─── EXPANDIR/RECOLHER TODOS OS ACCORDIONS ───
    // (handler via onclick + função global abaixo)

    // ─── INICIALIZAÇÃO DE ESTADOS VISUAIS BARS ───
    $('.score-radio:checked').each(function() {
        let radio = $(this);
        let score = parseInt(radio.val());
        let label = radio.closest('.segment-label');
        let questionContainer = radio.closest('.question-container');

        // Destaca a nota ativa
        highlightSegmentLabel(label, score);
        questionContainer.data('prev-checked', score);

        // Exibe descrição correspondente
        showBarsDescription(questionContainer, score);
        
        // Verifica a validação de nota crítica
        checkCriticalValidation(questionContainer, score);

        // Sincroniza o progresso
        handleQuestionScoreChange(questionContainer, score);
    });

    function highlightSegmentLabel(activeLabel, score) {
        let container = activeLabel.parent();
        
        container.find('.segment-label').each(function() {
            $(this).removeClass('bg-rose-600 bg-amber-500 bg-blue-600 bg-emerald-500 bg-emerald-700 text-white text-gray-900 border-rose-700 border-amber-600 border-blue-700 border-emerald-600 border-emerald-800 shadow-sm scale-105 font-bold')
                   .addClass('text-slate-500 hover:text-slate-800 border-transparent bg-transparent');
        });
        
        let targetLabel = container.find(`.segment-label[data-score="${score}"]`);
        const activeClasses = {
            1: 'bg-rose-600 text-white border-rose-700 shadow-sm scale-105 font-bold',
            2: 'bg-amber-500 text-gray-900 border-amber-600 shadow-sm scale-105 font-bold',
            3: 'bg-blue-600 text-white border-blue-700 shadow-sm scale-105 font-bold',
            4: 'bg-emerald-500 text-gray-900 border-emerald-600 shadow-sm scale-105 font-bold',
            5: 'bg-emerald-700 text-white border-emerald-800 shadow-sm scale-105 font-bold'
        };
        
        targetLabel.addClass(activeClasses[score])
                   .removeClass('text-slate-500 hover:text-slate-800 border-transparent bg-transparent');
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

        let labels = { 1: 'Insuficiente', 2: 'Abaixo do esperado', 3: 'Dentro do esperado', 4: 'Acima do esperado', 5: 'Excelente' };
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
        handleQuestionScoreChange(questionContainer, score);

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
                handleQuestionScoreChange(questionContainer, score);
            } else {
                // Cancelou: Reverte a nota para a anterior
                questionContainer.find('.score-radio').prop('checked', false);
                
                if (currentOriginalScore) {
                    let originalRadio = questionContainer.find(`.score-radio[value="${currentOriginalScore}"]`);
                    originalRadio.prop('checked', true);
                    highlightSegmentLabel(originalRadio.closest('.segment-label'), currentOriginalScore);
                    showBarsDescription(questionContainer, currentOriginalScore);
                    questionContainer.data('prev-checked', currentOriginalScore);
                    handleQuestionScoreChange(questionContainer, currentOriginalScore);
                } else {
                    questionContainer.data('prev-checked', null);
                    questionContainer.find('.segment-label').removeClass('bg-rose-600 bg-amber-500 bg-blue-600 bg-emerald-500 bg-emerald-700 text-white text-gray-900 border-rose-700 border-amber-600 border-blue-700 border-emerald-600 border-emerald-800 shadow-sm scale-105 font-bold')
                                                            .addClass('text-slate-500 hover:text-slate-800 border-transparent bg-transparent');
                    $(`#bars-description-box-${currentQuestionId}`).slideUp(200);
                    handleQuestionScoreChange(questionContainer, null);
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
            openAlertModal('Para prosseguir com esta nota, preencha a justificativa (mínimo 15 caracteres) e anexe um arquivo comprobatório.');
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

    // Helper para normalizar strings de categoria no frontend (case-insensitive, sem acentos, sem espaços)
    function normalizeStr(str) {
        if (!str) return "";
        let val = str.toString()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .replace(/[^a-z0-9]/g, "")
            .trim();
        
        let aliases = {
            'pontualidade': 'assiduidade',
            'trabalhoemequipe': 'cooperacao',
            'etica': 'disciplina'
        };
        return aliases[val] || val;
    }

    // Abre o diário focado em uma pergunta específica
    $(document).on('click', '.btn-open-diary', function() {
        let button = $(this);
        targetQuestionIdForDiary = button.data('question-id');
        let category = button.data('category');

        // Seleciona a opção no select de forma case-insensitive e normalizada
        let normalizedCat = normalizeStr(category);
        let found = false;
        $('#diary-category-filter option').each(function() {
            if (normalizeStr($(this).val()) === normalizedCat) {
                $('#diary-category-filter').val($(this).val());
                found = true;
                return false; // quebra o loop
            }
        });
        if (!found) {
            $('#diary-category-filter').val('all');
        }
        $('#diary-category-filter').trigger('change');
        
        openDiaryDrawer();
    });

    // Filtro de Categorias no Diário
    $('#diary-category-filter').on('change', function() {
        let val = $(this).val();
        if (val === 'all') {
            $('.incident-item-card').slideDown(200);
        } else {
            let normalizedVal = normalizeStr(val);
            $('.incident-item-card').each(function() {
                let card = $(this);
                if (normalizeStr(card.data('category')) === normalizedVal) {
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
            openAlertModal('Clique no botão "Buscar no Diário de Bordo" no card da competência que você deseja justificar.');
            closeDiaryDrawer();
            return;
        }

        let questionContainer = $(`.question-container[data-question-id="${targetQuestionIdForDiary}"]`);
        
        // Evita duplicidade
        if (questionContainer.find(`.linked-incident-input[value="${incidentId}"]`).length > 0) {
            openAlertModal('Este incidente já está vinculado a este indicador.');
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

        let category = questionContainer.data('category');
        updateCategoryProgress(category);
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
        
        let category = container.data('category');
        updateCategoryProgress(category);
        updateProgress();
    });

    // ─── PROGRESSO DO FORMULÁRIO ───
    function updateProgress() {
        let totalQuestions = $('.question-container').length;
        let answeredQuestions = $('.score-radio:checked').length;
        
        $('#form-progress-text').text(`${answeredQuestions} de ${totalQuestions} competências respondidas`);
    }
    updateProgress();

    // Identificar qual botão disparou o submit
    $('#btn-draft-evaluation').on('click', function() {
        $('#submit_type').val('draft');
    });
    
    $('#btn-submit-evaluation').on('click', function() {
        $('#submit_type').val('submit');
    });

    // ─── VALIDATION ON FORM SUBMIT ───
    $('#evaluation-form').on('submit', function(e) {
        let isDraft = $('#submit_type').val() === 'draft';
        let unanswered = [];
        
        if (!isDraft) {
            $('.question-container').each(function() {
                let container = $(this);
                let questionId = container.data('question-id');
                let score = container.find('.score-radio:checked').val();
                
                if (score === undefined || score === null || score === '') {
                    unanswered.push(container);
                } else {
                    // If it is a critical score (1, 2, 5), check if it has a diary incident or ad-hoc justification + evidence file
                    let scoreInt = parseInt(score);
                    if ([1, 2, 5].includes(scoreInt)) {
                        let hasDiary = container.find('.linked-incident-input').length > 0;
                        let hasText = container.find('.justification-textarea').val().trim().length >= 15;
                        let hasFile = container.find('.evidence-file-input').val() !== '' || 
                                      (container.find('.file-name-label').text() !== 'Nenhum arquivo selecionado' && 
                                       container.find('.file-name-label').text().trim() !== '');
                        if (!hasDiary && (!hasText || !hasFile)) {
                            unanswered.push(container);
                        }
                    }
                }
            });
            
            if (unanswered.length > 0) {
                e.preventDefault();
                
                // Destacar as perguntas sem resposta/com erro e abrir seus respectivos accordions de categoria
                unanswered.forEach(function(container) {
                    // Destacar a subpergunta com estilo de erro
                    container.addClass('bg-rose-50/30 p-4 rounded-xl border border-rose-300/80');
                    
                    // Obter a categoria e o card
                    let categoryCard = container.closest('.category-card');
                    let header = categoryCard.find('.category-accordion-header');
                    let content = categoryCard.find('.category-accordion-content');
                    let chevron = categoryCard.find('.category-accordion-chevron');
                    
                    if (!content.is(':visible')) {
                        header.removeClass('rounded-b-xl border-b-0').addClass('border-b border-slate-100');
                        chevron.addClass('rotate-180');
                        content.slideDown(200, function() {
                            let category = categoryCard.data('category');
                            updateCategoryProgress(category);
                        });
                    } else {
                        let category = categoryCard.data('category');
                        updateCategoryProgress(category);
                    }
                });
                
                // Scroll até a primeira pergunta com erro
                $('html, body').animate({
                    scrollTop: unanswered[0].offset().top - 100
                }, 500);
                
                openAlertModal('Responda a todas as competências e preencha as justificativas e evidências obrigatórias para as notas críticas (1, 2 ou 5).');
                return false;
            }
        }
        
        // ─── ENVIO VIA AJAX PARA CAPTURAR ERROS DO SERVIDOR ───
        e.preventDefault();
        
        let form = this;
        let submitBtn = isDraft ? $('#btn-draft-evaluation') : $('#btn-submit-evaluation');
        let originalHtml = submitBtn.html();
        
        // Desabilitar botões
        $('#btn-draft-evaluation, #btn-submit-evaluation').prop('disabled', true);
        
        if (isDraft) {
            submitBtn.html('<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> <span>Salvando rascunho...</span>');
        } else {
            submitBtn.html('<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> <span>Enviando...</span>');
        }
        
        $.ajax({
            url: form.action,
            method: form.method,
            data: new FormData(form),
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function() {
                $('#btn-draft-evaluation, #btn-submit-evaluation').prop('disabled', false);
                submitBtn.html(originalHtml);
                
                let successTitle = isDraft ? 'Rascunho salvo com sucesso!' : 'Avaliação submetida com sucesso!';
                openAlertModal('<div class="flex flex-col items-center text-center py-2"><span class="material-symbols-outlined text-emerald-500 text-4xl mb-2" style="font-variation-settings:\'FILL\' 1">check_circle</span><div class="font-bold text-slate-800 text-sm">' + successTitle + '</div><div class="text-xs text-slate-500 mt-1">Redirecionando para a listagem...</div></div>');
                
                window.redirectTimeout = setTimeout(function() {
                    window.location.href = '{{ route("evaluations.index") }}';
                }, 2500);
            },
            error: function(xhr) {
                $('#btn-draft-evaluation, #btn-submit-evaluation').prop('disabled', false);
                submitBtn.html(originalHtml);
                
                if (xhr.status === 419) {
                    openAlertModal('Sua sessão expirou. <a href="' + window.location.href + '" class="text-blue-600 underline font-bold">Recarregue a página</a> e tente novamente.');
                } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    let items = [];
                    $.each(errors, function(field, msgs) {
                        $.each(msgs, function(i, msg) {
                            items.push('<li class="ml-4 list-disc text-rose-600 font-medium">' + $('<span>').text(msg).html() + '</li>');
                        });
                    });
                    openAlertModal('<div class="font-bold text-slate-800 mb-2">Erros de validação:</div><ul class="space-y-1">' + items.join('') + '</ul>');
                } else if (xhr.status === 422 && xhr.responseJSON) {
                    openAlertModal('Erro de validação: ' + $('<span>').text(xhr.responseJSON.message || 'Dados inválidos.').html());
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    openAlertModal($('<span>').text(xhr.responseJSON.message).html());
                } else {
                    openAlertModal('Ocorreu um erro inesperado (código ' + xhr.status + '). O formulário foi preservado — tente novamente.');
                }
            }
        });
    });

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
        let category = container.data('category');
        updateCategoryProgress(category);
        updateProgress();
    });

    $(document).on('keyup', '.justification-textarea', function() {
        let container = $(this).closest('.question-container');
        let score = container.find('.score-radio:checked').val();
        if (score) {
            checkCriticalValidation(container, parseInt(score));
        }
        let category = container.data('category');
        updateCategoryProgress(category);
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
