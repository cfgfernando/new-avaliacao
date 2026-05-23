@extends('layouts.app')

@section('title', 'Central de Parametrização: BARS & Competências')

@section('content')
@php
    $cats = $cats ?? [
        'assiduidade' => 'Assiduidade',
        'disciplina' => 'Disciplina',
        'iniciativa' => 'Iniciativa',
        'responsabilidade' => 'Responsabilidade',
        'cooperacao' => 'Cooperação',
        'qualidade' => 'Qualidade',
        'desenvolvimento_rh' => 'Desenvolvimento de RH',
        'avaliacao_usuario' => 'Avaliação do Usuário'
    ];
@endphp
<style>
    .tech-pip {
        width: 6px;
        height: 14px;
        border-radius: 2px;
        background: #E2E8F0;
        transition: all 0.2s ease;
    }
    .tech-pip.active {
        background: #2563EB;
    }
    .tech-pip.active.warning {
        background: #F59E0B;
    }
    .matrix-cell {
        transition: all 0.2s ease;
    }
    .matrix-cell:hover {
        transform: scale(1.15);
        z-index: 10;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
    }
    #bars-editor input:focus, 
    #bars-editor textarea:focus {
        background-color: #1e293b !important;
        color: #f8fafc !important; /* text-slate-50 */
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
    }
</style>

<div class="max-w-7xl mx-auto space-y-8 animate-reveal-up">
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-6 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <p class="font-mono text-[10px] text-slate-500 uppercase tracking-widest font-bold">
                    Ciclo Ativo: {{ $activeCycle->name ?? 'Q3 2026' }} • ID Instância: <span class="text-slate-900 font-bold">CMD-BARS-PRM-022</span>
                </p>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Central de Parametrização: BARS & Competências</h2>
            <p class="text-slate-500 text-sm mt-1">Gestão estratégica e parametrização avançada do motor de avaliação comportamental e legal.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition shadow-xs flex items-center gap-2 uppercase tracking-wider select-none cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Painel Geral</span>
            </a>
            <button onclick="openCreateModal()" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition flex items-center gap-2 uppercase tracking-wider select-none cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Nova Competência</span>
            </button>
        </div>
    </div>

    <!-- Alertas de Feedback -->
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-100 flex items-center gap-3 font-semibold text-sm shadow-sm animate-fadeIn">
            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                <i class="fas fa-check"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl border border-rose-100 flex items-center gap-3 font-semibold text-sm shadow-sm animate-fadeIn">
            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center text-xs">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Simulation & Metrics Bento Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <!-- Advanced Simulation Card -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden relative">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-blue-600">analytics</span>
                        Simulação de Notas & Impacto IG
                    </h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase mt-0.5">Motor de análise preditiva "What-If" para o ciclo vigente</p>
                </div>
                <span class="font-mono text-[9px] bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-slate-500 font-bold uppercase">PREDICTIVE_ENGINE_V4</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-[10px] font-bold text-slate-600 uppercase tracking-widest font-mono">Delta Score Médio BARS</label>
                            <span class="font-mono text-blue-600 font-bold text-xs" id="delta-value-badge">-0.5 pts</span>
                        </div>
                        <input class="w-full h-1.5 bg-slate-100 border border-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600 outline-none" id="delta-range-slider" max="2" min="-2" step="0.1" type="range" value="-0.5"/>
                        <div class="flex justify-between mt-1.5 text-[9px] font-mono text-slate-400 font-bold">
                            <span>-2.0</span>
                            <span>NEUTRO (0.0)</span>
                            <span>+2.0</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 shadow-xxs">
                        <p class="text-[9px] font-mono text-slate-400 font-bold uppercase mb-1">Projeção de Resultado</p>
                        <div class="flex items-center gap-4">
                            <div class="text-2xl font-mono font-bold text-rose-500" id="delta-impact-badge">-4.2%</div>
                            <div class="h-8 w-px bg-slate-250"></div>
                            <p class="text-[11px] leading-relaxed text-slate-500 font-medium italic">Impacto direto estimado no Índice de Governança (IG) Municipal caso a média de pontuações caia.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-900 rounded-xl p-5 text-white shadow-md relative overflow-hidden">
                    <span class="font-mono text-[9px] text-slate-400 font-bold block mb-4 uppercase tracking-wider">CORRELAÇÃO OKR VS BARS</span>
                    <div class="flex items-end gap-1.5 h-32 mb-4">
                        <div class="flex-1 bg-blue-500 rounded-t-xs opacity-40 h-[40%]"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-xs opacity-60 h-[65%]"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-xs opacity-80 h-[85%]"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-xs h-[100%] shadow-lg shadow-blue-500/20"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-xs opacity-50 h-[55%]"></div>
                        <div class="flex-1 bg-blue-500 rounded-t-xs opacity-30 h-[30%]"></div>
                    </div>
                    <div class="flex justify-between text-[9px] font-mono text-slate-400 border-t border-slate-800 pt-3">
                        <span>Q1</span>
                        <span>Q2</span>
                        <span class="text-white font-bold">Q3 (PROJ)</span>
                        <span>Q4</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Version Hub & Quick Parameters -->
        <div class="lg:col-span-4 flex flex-col justify-between gap-6">
            <!-- Nota de Corte Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex-1 flex flex-col justify-center">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Nota de Corte Ciclo</span>
                    <span class="font-mono text-xs font-bold text-slate-900 px-2.5 py-1 bg-slate-50 rounded border border-slate-200 font-mono shadow-xxs">
                        {{ number_format($activeCycle->cutoff_score ?? 3.0, 1) }}
                    </span>
                </div>
                <div class="flex gap-1.5">
                    @php
                        $cutoff = $activeCycle->cutoff_score ?? 3.0;
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <div class="h-1.5 flex-1 rounded-full {{ $i <= $cutoff ? 'bg-blue-600' : 'bg-slate-100 border border-slate-200/50' }}"></div>
                    @endfor
                </div>
            </div>
            
            <!-- Histórico de Parâmetros -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex-1 flex flex-col justify-between">
                <h4 class="text-[10px] font-bold text-slate-900 mb-4 uppercase tracking-widest font-mono flex items-center gap-2 border-b border-slate-100 pb-2">
                    <span class="material-symbols-outlined text-slate-450 text-[18px]">history</span>
                    Histórico de Parâmetros
                </h4>
                <div class="space-y-4 flex-1 overflow-y-auto pr-1">
                    <div class="pl-3.5 border-l-2 border-blue-600 relative">
                        <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-blue-600"></div>
                        <p class="text-[11px] font-bold text-slate-900 leading-none mb-1">Versão 2.4.1 (Atual)</p>
                        <p class="text-[10px] text-slate-500">Ajuste de pesos OKR/BARS ({{ $activeCycle->weights['okr'] ?? 50 }}/{{ $activeCycle->weights['bars'] ?? 50 }})</p>
                        <p class="text-[9px] font-mono text-slate-400 mt-1 uppercase font-bold">12 OUT 2026 • ADMIN</p>
                    </div>
                    <div class="pl-3.5 border-l-2 border-slate-200 relative">
                        <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-slate-350"></div>
                        <p class="text-[11px] font-bold text-slate-600 leading-none mb-1">Versão 2.4.0</p>
                        <p class="text-[10px] text-slate-500">Importação de âncoras Setoriais</p>
                        <p class="text-[9px] font-mono text-slate-400 mt-1 uppercase font-bold">05 SET 2026 • IA</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategic Matrix & Methodology -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Correlation Matrix -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-blue-600">grid_on</span>
                        Correlação: Competências vs OKRs
                    </h3>
                    <p class="text-[11px] text-slate-450 font-bold uppercase mt-0.5">Peso de impacto das competências BARS nos objetivos institucionais.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-6 gap-2">
                <div class="col-span-2"></div>
                <div class="text-[9px] font-mono font-bold text-slate-450 uppercase text-center rotate-12 mb-3">OKR_01</div>
                <div class="text-[9px] font-mono font-bold text-slate-450 uppercase text-center rotate-12 mb-3">OKR_02</div>
                <div class="text-[9px] font-mono font-bold text-slate-450 uppercase text-center rotate-12 mb-3">OKR_03</div>
                <div class="text-[9px] font-mono font-bold text-slate-450 uppercase text-center rotate-12 mb-3">OKR_04</div>
                
                <div class="col-span-2 text-[10px] font-bold text-slate-700 py-2 border-b border-slate-100">Liderança</div>
                <div class="matrix-cell bg-blue-600/90 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/40 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/20 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/70 rounded border border-blue-500/25 h-8"></div>
                
                <div class="col-span-2 text-[10px] font-bold text-slate-700 py-2 border-b border-slate-100">Resiliência</div>
                <div class="matrix-cell bg-blue-600/20 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/80 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/50 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/10 rounded border border-blue-500/25 h-8"></div>
                
                <div class="col-span-2 text-[10px] font-bold text-slate-700 py-2 border-b border-slate-100">Técnica Legis.</div>
                <div class="matrix-cell bg-blue-600/10 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/10 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/90 rounded border border-blue-500/25 h-8"></div>
                <div class="matrix-cell bg-blue-600/40 rounded border border-blue-500/25 h-8"></div>
            </div>
            <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                <span class="text-[9px] font-mono font-bold text-slate-400 uppercase">Intensidade do Impacto:</span>
                <div class="flex gap-1 items-center">
                    <div class="w-2.5 h-2.5 rounded-xs bg-blue-600/10 border border-blue-500/10"></div>
                    <div class="w-2.5 h-2.5 rounded-xs bg-blue-600/40 border border-blue-500/15"></div>
                    <div class="w-2.5 h-2.5 rounded-xs bg-blue-600/70 border border-blue-500/20"></div>
                    <div class="w-2.5 h-2.5 rounded-xs bg-blue-600 shadow-xxs"></div>
                </div>
            </div>
        </section>

        <!-- Methodology Section -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-2">
                    <span class="material-symbols-outlined text-slate-400">functions</span>
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-widest font-mono">Metodologia de Cálculo (Score Final)</h3>
                </div>
                <div class="bg-slate-900 rounded-xl p-5 font-mono text-[12px] leading-relaxed text-slate-350 shadow-md">
                    <div class="mb-4">
                        <span class="text-blue-450 font-semibold">// Algoritmo de Ponderação GovSense v2.4</span><br/>
                        <span class="text-emerald-500 font-bold">SCORE_FINAL</span> = ( <span class="text-white">Σ(BARS_i * W_b)</span> + <span class="text-white">Σ(OKR_j * W_o)</span> ) / <span class="text-white">K_norm</span>
                    </div>
                    <div class="space-y-1 text-[10.5px]">
                        <p><span class="text-slate-500">W_b (Peso BARS):</span> <span class="text-white font-bold">{{ number_format($activeCycle->weights['bars'] ?? 50) }}%</span></p>
                        <p><span class="text-slate-500">W_o (Peso OKRs):</span> <span class="text-white font-bold">{{ number_format($activeCycle->weights['okr'] ?? 50) }}%</span></p>
                        <p><span class="text-slate-500">K_norm:</span> Coeficiente de Normalização Institucional</p>
                    </div>
                </div>
            </div>
            <p class="mt-4 text-[10px] text-slate-400 italic font-medium leading-relaxed">As fórmulas calculadas são auditadas de forma eletrônica e seguem a Lei Complementar 174/2026 de Transparência de Dados Públicos.</p>
        </section>
    </div>

    <!-- Enhanced Competency Management Table -->
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mt-8">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Gestão de Competências Ativas</h3>
                <p class="text-xs text-slate-500 mt-1">Mapeamento granular, âncoras registradas e filtros do quadro de servidores.</p>
            </div>
            
            <!-- Barra de Filtros Integrada -->
            <form action="{{ route('admin.evaluation-questions.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <input type="hidden" name="cycle_id" value="{{ $activeCycle->id ?? '' }}">
                <select name="group" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-700 outline-none focus:ring-0">
                    <option value="geral" {{ request('group') === 'geral' ? 'selected' : '' }}>Quadro Geral</option>
                    <option value="saude" {{ request('group') === 'saude' ? 'selected' : '' }}>Saúde</option>
                    <option value="guarda" {{ request('group') === 'guarda' ? 'selected' : '' }}>Guarda Municipal</option>
                    <option value="educacao" {{ request('group') === 'educacao' ? 'selected' : '' }}>Educação</option>
                </select>
                <select name="category" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-700 outline-none focus:ring-0">
                    <option value="">Todas Categorias</option>
                    @foreach($cats as $key => $lbl)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <div class="relative w-full sm:w-48">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..." class="w-full bg-white border border-slate-200 rounded-lg pl-8 pr-3 py-1.5 text-xs font-medium text-slate-700 focus:ring-0 outline-none">
                    <span class="material-symbols-outlined absolute left-2.5 top-2 text-[14px] text-slate-400">search</span>
                </div>
                <button type="submit" class="hidden"></button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 border-b border-slate-250/60">
                        <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest">Identificador / Nome</th>
                        <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-center w-36">Tendência (3 Ciclos)</th>
                        <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-center w-36">Foundation</th>
                        <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-center w-48">BARS Ready</th>
                        <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-right w-44">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($questions as $question)
                        @php
                            $anchors = $question->barsAnchors;
                            $hasAnchor1 = $anchors->contains('score', 1);
                            $hasAnchor2 = $anchors->contains('score', 2);
                            $hasAnchor3 = $anchors->contains('score', 3);
                            $hasAnchor4 = $anchors->contains('score', 4);
                            $hasAnchor5 = $anchors->contains('score', 5);
                            $filledCount = $anchors->count();
                            $isReady = $filledCount === 5;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="p-4">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0 shadow-xxs">
                                        <span class="material-symbols-outlined text-[20px]">psychology</span>
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="font-bold text-slate-800 text-sm leading-tight">{{ $question->text }}</p>
                                            <span class="px-2 py-0.5 bg-slate-900 font-mono text-[9px] text-white font-bold rounded">
                                                ID: COMP_{{ strtoupper(substr($question->category, 0, 2)) }}_{{ str_pad($question->id, 3, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-400 font-semibold mt-1">
                                            {{ $question->group_type === 'geral' ? 'Quadro Geral' : ucfirst($question->group_type) }} • {{ $cats[$question->category] ?? $question->category }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <!-- Tendência Estética baseada no ID -->
                            <td class="p-4">
                                <div class="flex items-end justify-center gap-1.5 h-6 w-20 mx-auto">
                                    <div class="w-2.5 bg-emerald-500/20 h-[30%] rounded-xs"></div>
                                    <div class="w-2.5 bg-emerald-500/40 h-[60%] rounded-xs"></div>
                                    <div class="w-2.5 bg-emerald-500 h-[100%] rounded-xs"></div>
                                </div>
                                <div class="text-[9px] font-mono text-emerald-600 font-bold text-center mt-1">+12% crescimento</div>
                            </td>
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg shadow-xxs mx-auto">
                                    <span class="font-mono text-[10px] font-bold text-slate-650">05 NÍVEIS</span>
                                    <span class="material-symbols-outlined text-xs text-slate-400">gavel</span>
                                </div>
                            </td>
                            <!-- Indicador de Âncoras Preenchidas -->
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-1 mb-1">
                                    <div class="tech-pip {{ $hasAnchor1 ? 'active' : '' }}"></div>
                                    <div class="tech-pip {{ $hasAnchor2 ? 'active' : '' }}"></div>
                                    <div class="tech-pip {{ $hasAnchor3 ? 'active' : '' }}"></div>
                                    <div class="tech-pip {{ $hasAnchor4 ? 'active' : '' }}"></div>
                                    <div class="tech-pip {{ $hasAnchor5 ? 'active' : '' }}"></div>
                                </div>
                                @if($isReady)
                                    <span class="text-[9px] font-mono text-emerald-600 uppercase font-bold tracking-tight">Sync Ready</span>
                                @else
                                    <span class="text-[9px] font-mono text-amber-500 uppercase font-bold tracking-tight">Rascunho ({{ $filledCount }}/5)</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="px-3 py-1.5 text-blue-600 hover:text-white border border-blue-200 hover:bg-blue-600 rounded-lg text-xs font-bold transition-all btn-config-bars" 
                                            data-id="{{ $question->id }}"
                                            data-text="{{ $question->text }}"
                                            data-group="{{ $question->group_type }}"
                                            data-category="{{ $question->category }}"
                                            data-anchor1="{{ $anchors->firstWhere('score', 1)->behavioral_description ?? '' }}"
                                            data-anchor2="{{ $anchors->firstWhere('score', 2)->behavioral_description ?? '' }}"
                                            data-anchor3="{{ $anchors->firstWhere('score', 3)->behavioral_description ?? '' }}"
                                            data-anchor4="{{ $anchors->firstWhere('score', 4)->behavioral_description ?? '' }}"
                                            data-anchor5="{{ $anchors->firstWhere('score', 5)->behavioral_description ?? '' }}">
                                        Configurar BARS
                                    </button>
                                    
                                    <form action="{{ route('admin.evaluation-questions.toggle', $question) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg border border-slate-200 hover:border-blue-500 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-all bg-white" title="{{ $question->is_active ? 'Desativar' : 'Ativar' }}">
                                            <span class="material-symbols-outlined text-[18px] {{ $question->is_active ? 'text-emerald-500 font-bold' : '' }}">toggle_{{ $question->is_active ? 'on' : 'off' }}</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.evaluation-questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta competência permanentemente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg border border-slate-200 hover:border-rose-500 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-all bg-white" title="Excluir">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 font-bold">Nenhuma competência encontrada para este grupo ou filtro.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $questions->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </section>

    <!-- BARS Editor Section (Hidden by default, populates via jQuery) -->
    <section class="hidden transition-all duration-300 opacity-0 transform translate-y-4 mt-8" id="bars-editor">
        <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl p-8 relative overflow-hidden text-white">
            <div class="absolute top-0 right-0 p-8 opacity-5 select-none pointer-events-none">
                <span class="material-symbols-outlined text-[140px]">psychology</span>
            </div>
            
            <form id="form-edit-bars" method="POST" action="" hx-boost="false">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="group_type" id="edit-question-group">
                <input type="hidden" name="category" id="edit-question-category">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 relative z-10">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-xl font-bold tracking-tight flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-500">settings_suggest</span>
                                <span>Refinamento de Âncoras BARS</span>
                            </h3>
                            <span class="px-2.5 py-0.5 bg-blue-600 rounded font-mono text-[9px] font-bold uppercase tracking-wider">Modo Edição Ativo</span>
                        </div>
                        <p class="text-slate-400 text-xs">Defina de forma objetiva os comportamentos esperados do servidor para cada faixa da nota.</p>
                    </div>
                </div>

                <div class="space-y-6 relative">
                    <!-- Título Editável da Competência -->
                    <div class="mb-4">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono block mb-2">Descrição / Texto da Competência</label>
                        <textarea name="text" id="edit-question-text-input" rows="2" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-3 text-sm text-slate-200 outline-none focus:border-blue-500 transition-all font-semibold leading-relaxed" required></textarea>
                    </div>

                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono block border-b border-slate-800 pb-2">Comportamentos por Nível</h4>

                    <!-- Escalas de 1 a 5 -->
                    @for($i = 1; $i <= 5; $i++)
                        @php
                            $colorDot = [
                                1 => 'bg-rose-500 shadow-rose-500/20',
                                2 => 'bg-amber-500 shadow-amber-500/20',
                                3 => 'bg-blue-500 shadow-blue-500/20',
                                4 => 'bg-indigo-500 shadow-indigo-500/20',
                                5 => 'bg-emerald-500 shadow-emerald-500/20'
                            ][$i];
                            $lblText = [
                                1 => 'Nível 1 — Comportamento Crítico / Insatisfatório',
                                2 => 'Nível 2 — Abaixo do Esperado',
                                3 => 'Nível 3 — Atitude Padrão / Esperada (Baseline)',
                                4 => 'Nível 4 — Supera as Expectativas',
                                5 => 'Nível 5 — Excelente Referência / Mentor Técnico'
                            ][$i];
                        @endphp
                        <div class="flex gap-4 items-start">
                            <div class="flex flex-col items-center gap-2 pt-2 shrink-0">
                                <div class="w-10 h-10 rounded-xl {{ $colorDot }} text-white font-mono font-bold text-base flex items-center justify-center shadow-md">
                                    {{ $i }}
                                </div>
                            </div>
                            <div class="flex-grow">
                                <label class="text-[9.5px] font-mono font-bold text-slate-450 uppercase mb-2 block">{{ $lblText }}</label>
                                <textarea name="anchor_{{ $i }}" id="edit-anchor-{{ $i }}" rows="2" class="w-full bg-slate-800/60 border border-slate-700 rounded-xl p-3 text-xs text-slate-200 outline-none focus:border-blue-500 transition-all leading-relaxed" placeholder="Preencha a âncora comportamental para esta pontuação..."></textarea>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mt-8 pt-5 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-[10px] font-mono text-slate-500 flex items-center gap-2 uppercase tracking-widest font-bold">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Modificações sincronizadas com o APD
                    </div>
                    <div class="flex gap-3 w-full sm:w-auto justify-end">
                        <button type="button" class="px-5 py-2 text-slate-400 font-bold hover:text-white transition" onclick="toggleBarsEditor()">Descartar</button>
                        <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-md shadow-blue-500/10 transition active:scale-95">Salvar Alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- ═══════════════════════════════════════
     MODAL: NOVA COMPETÊNCIA (IA ASSISTIDA)
     ═══════════════════════════════════════ -->
<div id="create-modal" class="fixed inset-0 z-50 hidden bg-slate-900/65 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-3xl w-full overflow-hidden animate-reveal-up max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-slate-150 flex items-center justify-between bg-slate-50/50 shrink-0">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2 select-none">
                <span class="material-symbols-outlined text-blue-600 text-[20px]">magic_button</span>
                Criar Competência Assistida por IA
            </h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-700 transition cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        
        <form action="{{ route('admin.evaluation-questions.store') }}" method="POST" class="flex-grow overflow-y-auto p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Configurações -->
                <div class="md:col-span-4 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Grupo Funcional</label>
                        <select name="group_type" class="input-neo !py-2.5">
                            <option value="geral">Quadro Geral</option>
                            <option value="saude">Saúde</option>
                            <option value="guarda">Guarda Municipal</option>
                            <option value="educacao">Educação</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Categoria</label>
                        <select name="category" class="input-neo !py-2.5">
                            @foreach($cats as $key => $lbl)
                                <option value="{{ $key }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Título da Competência</label>
                        <textarea name="text" id="ia-question-text" rows="4" placeholder="Ex: Comunicação Interpessoal" class="input-neo !py-2" required></textarea>
                    </div>
                    
                    <button type="button" id="btn-gerar-ia" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs uppercase tracking-wider py-2.5 rounded-lg border border-blue-200 transition-all flex items-center justify-center gap-2 select-none cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">auto_awesome</span>
                        <span>Gerar Âncoras com IA</span>
                    </button>
                </div>

                <!-- Âncoras geradas -->
                <div class="md:col-span-8 space-y-4">
                    <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono flex items-center gap-1.5 border-b border-slate-100 pb-2">
                        <span class="material-symbols-outlined text-xs">list_alt</span>
                        <span>Escala Comportamental BARS (Níveis 1 a 5)</span>
                    </h4>
                    
                    <div class="space-y-3.5" id="anchors-wrapper">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $lbl = [
                                    1 => 'Nível 1 — Crítico',
                                    2 => 'Nível 2 — Insuficiente',
                                    3 => 'Nível 3 — Esperado',
                                    4 => 'Nível 4 — Destaque',
                                    5 => 'Nível 5 — Excelente'
                                ][$i];
                                $colorDot = [
                                    1 => 'bg-rose-500',
                                    2 => 'bg-amber-500',
                                    3 => 'bg-blue-500',
                                    4 => 'bg-indigo-500',
                                    5 => 'bg-emerald-500'
                                ][$i];
                            @endphp
                            <div class="relative flex items-center">
                                <div class="absolute left-3.5 flex items-center gap-1.5 pointer-events-none select-none">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $colorDot }}"></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase font-mono">{{ $lbl }}</span>
                                </div>
                                <input type="text" name="anchor_{{ $i }}" id="anchor-input-{{ $i }}" placeholder="Descrição comportamental..." class="input-neo !pl-36 !py-2.5 !text-xs font-medium text-slate-700" required>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Footer Modal Buttons -->
            <div class="border-t border-slate-150 pt-5 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition select-none cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-md shadow-blue-500/10 transition select-none cursor-pointer">
                    Salvar e Homologar BARS
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Alertas (jQuery) -->
<div id="modal-alert" class="fixed inset-0 z-[110] flex items-center justify-center hidden p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" onclick="closeAlertModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-md z-10 overflow-hidden modal-alert-inner opacity-0 transition-all duration-300">
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between select-none">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-amber-500 text-[20px]">info</span>
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Alerta</span>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-650 transition cursor-pointer" onclick="closeAlertModal()">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>
        <div class="p-6">
            <p class="text-xs text-slate-600 font-bold leading-relaxed" id="modal-alert-message"></p>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-150 flex items-center justify-end shrink-0">
            <button type="button" onclick="closeAlertModal()" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs px-4 py-2 rounded-lg transition cursor-pointer">
                Confirmar
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openCreateModal() {
    $('#create-modal').removeClass('hidden').addClass('flex');
}

function closeCreateModal() {
    $('#create-modal').removeClass('flex').addClass('hidden');
}

function openAlertModal(message) {
    $('#modal-alert-message').text(message);
    let modal = $('#modal-alert');
    modal.removeClass('hidden');
    setTimeout(function() {
        modal.find('.modal-alert-inner').removeClass('opacity-0').addClass('opacity-100');
    }, 50);
}

function closeAlertModal() {
    let modal = $('#modal-alert');
    modal.find('.modal-alert-inner').addClass('opacity-0').removeClass('opacity-100');
    setTimeout(function() {
        modal.addClass('hidden');
    }, 200);
}

function toggleBarsEditor() {
    const editor = document.getElementById('bars-editor');
    if (editor.classList.contains('hidden')) {
        editor.classList.remove('hidden');
        setTimeout(() => {
            editor.classList.remove('opacity-0', 'translate-y-4');
            editor.classList.add('opacity-100', 'translate-y-0');
            editor.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 10);
    } else {
        editor.classList.remove('opacity-100', 'translate-y-0');
        editor.classList.add('opacity-0', 'translate-y-4');
        setTimeout(() => {
            editor.classList.add('hidden');
        }, 300);
    }
}

$(document).ready(function() {
    // 1. Simulação Dinâmica "What-If" no Frontend
    $('#delta-range-slider').on('input', function() {
        var val = parseFloat($(this).val());
        
        // Formatar valor do badge de delta
        var valText = (val >= 0 ? '+' : '') + val.toFixed(1) + ' pts';
        $('#delta-value-badge').text(valText);
        
        // Calcular impacto projetado do IG correspondente (simulado)
        // delta de -2.0 dá -16.8% e delta de +2.0 dá +16.8%
        var impact = val * 8.4;
        var impactText = (impact >= 0 ? '+' : '') + impact.toFixed(1) + '%';
        
        var $badge = $('#delta-impact-badge');
        $badge.text(impactText);
        
        if (impact < 0) {
            $badge.removeClass('text-emerald-500').addClass('text-rose-500');
        } else {
            $badge.removeClass('text-rose-500').addClass('text-emerald-500');
        }
    });

    // 2. Evento Click no "Configurar BARS" para carregar dados reais no Editor
    $(document).on('click', '.btn-config-bars', function() {
        var $btn = $(this);
        var id = $btn.data('id');
        var text = $btn.data('text');
        var group = $btn.data('group');
        var category = $btn.data('category');
        
        // Atualizar inputs da pergunta
        $('#edit-question-group').val(group);
        $('#edit-question-category').val(category);
        $('#edit-question-text-input').val(text);
        
        // Preencher âncoras
        for(var i=1; i<=5; i++) {
            $('#edit-anchor-'+i).val($btn.data('anchor'+i));
        }
        
        // Setar action dinâmico do formulário
        var updateUrl = "{{ route('admin.evaluation-questions.update', ':id') }}".replace(':id', id);
        $('#form-edit-bars').attr('action', updateUrl);
        
        // Abrir seção do editor se estiver oculta
        if ($('#bars-editor').hasClass('hidden')) {
            toggleBarsEditor();
        } else {
            document.getElementById('bars-editor').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    // 3. Assistente de IA para Geração de Âncoras no Modal
    $('#btn-gerar-ia').on('click', function() {
        var title = $('#ia-question-text').val().trim();
        
        if (!title) {
            openAlertModal('Por favor, preencha o título da competência para que a IA possa gerar as âncoras comportamentais.');
            $('#ia-question-text').focus();
            return;
        }

        var $btn = $(this);
        var originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Gerando com IA...');
        
        // Esmaecer as caixas de âncoras durante a geração
        for(var i=1; i<=5; i++) {
            $('#anchor-input-'+i).val('Gerando âncora comportamental...').addClass('opacity-50');
        }

        $.ajax({
            url: "{{ route('admin.bars.generate-anchors') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                title: title,
                description: "Geração de âncora para a competência"
            },
            success: function(response) {
                if (response.success) {
                    var delay = 0;
                    Object.keys(response.anchors).forEach(function(key) {
                        setTimeout(function() {
                            var text = response.anchors[key];
                            var $input = $('#anchor-input-' + key);
                            $input.val('').removeClass('opacity-50');
                            
                            var charIndex = 0;
                            function typeWriter() {
                                if (charIndex < text.length) {
                                    $input.val($input.val() + text.charAt(charIndex));
                                    charIndex++;
                                    setTimeout(typeWriter, 4);
                                }
                            }
                            typeWriter();
                        }, delay);
                        delay += 350;
                    });
                } else {
                    openAlertModal('Erro ao tentar gerar âncoras.');
                    resetAnchorInputs();
                }
            },
            error: function() {
                openAlertModal('Ocorreu um erro de conexão ao simular a Inteligência Artificial.');
                resetAnchorInputs();
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    function resetAnchorInputs() {
        for(var i=1; i<=5; i++) {
            $('#anchor-input-'+i).val('').removeClass('opacity-50');
        }
    }
});
</script>
@endpush
