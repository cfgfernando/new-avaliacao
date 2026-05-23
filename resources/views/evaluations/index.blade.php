<x-app-layout>
    @section('title', 'Central de Comando APD — GovPerformance')

    <!-- Estilos específicos da tela integrados localmente para evitar problemas com HTMX swap -->
    <style>
        .circular-gauge {
            position: relative;
            width: 72px;
            height: 72px;
        }
        .circular-gauge svg {
            transform: rotate(-90deg);
        }
        .circular-bg {
            fill: none;
            stroke: #F1F5F9;
            stroke-width: 6;
        }
        .circular-progress {
            fill: none;
            stroke: #10B981;
            stroke-width: 6;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.8s ease-out;
        }
        .trend-pip {
            width: 4px;
            height: 14px;
            border-radius: 99px;
            background: #E2E8F0;
            transition: all 0.2s ease;
        }
        .trend-pip.active-green {
            background: #10B981;
        }
        .trend-pip.active-red {
            background: #EF4444;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-8 animate-reveal-up">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <p class="font-mono text-[10px] text-slate-500 uppercase tracking-widest font-bold">
                        Central de Comando APD • Ultra Performance Intelligence
                    </p>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Gestão de Avaliações (APD)</h2>
                <p class="text-slate-500 text-sm mt-1">Histórico, preenchimento e monitoramento estratégico de Avaliações de Desempenho.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition shadow-xs flex items-center gap-2 uppercase tracking-wider select-none cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Painel Geral</span>
                </a>
                <a href="{{ route('evaluations.setup.create') }}" hx-boost="false" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition flex items-center gap-2 uppercase tracking-wider select-none cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Nova Avaliação</span>
                </a>
            </div>
        </div>

        <!-- Alertas de Feedback -->
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl font-bold text-sm border border-emerald-250 flex items-center gap-3 shadow-xs animate-reveal-up">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 text-rose-600 p-4 rounded-xl font-bold text-sm border border-rose-150 flex items-center gap-3 shadow-xs animate-reveal-up">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @php
            $activeFiltersCount = 0;
            if ($search) $activeFiltersCount++;
            if ($cycleId) $activeFiltersCount++;
            if ($lotacao) $activeFiltersCount++;
            if ($status) $activeFiltersCount++;
            if ($sortBy && $sortBy !== 'recent') $activeFiltersCount++;
            if ($scoreRange) $activeFiltersCount++;
        @endphp

        <!-- Top Bento Grid Dash (Fidelidade Stitch) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Saúde do Ciclo -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between h-44">
                <div class="flex justify-between items-start">
                    <p class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">Saúde do Ciclo</p>
                    <span class="text-emerald-500 font-bold text-[9px] font-mono leading-none flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[12px] font-bold">trending_up</span>
                        <span>+2.4%</span>
                    </span>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <div class="circular-gauge">
                        <svg viewBox="0 0 100 100" class="w-18 h-18">
                            <circle class="circular-bg" cx="50" cy="50" r="40"></circle>
                            <circle class="circular-progress" cx="50" cy="50" r="40" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $cycleHealth / 100) }}"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="font-mono font-bold text-xs text-slate-800">{{ $cycleHealth }}%</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-2xl font-black font-mono text-slate-900 leading-none">{{ $cycleHealth }}%</span>
                        <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-tight mt-1">{{ $cycleHealth >= 75 ? 'Excelente' : ($cycleHealth >= 40 ? 'Satisfatório' : 'Pendente') }}</p>
                    </div>
                </div>
            </div>

            <!-- Risco Institucional -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between h-44">
                <p class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-3">Risco Institucional</p>
                <div class="space-y-2.5 flex-grow flex flex-col justify-center">
                    @foreach($risks as $risk)
                        <div class="flex justify-between items-center text-[10px] font-mono leading-none">
                            <span class="text-slate-500 font-bold truncate max-w-[100px]" title="{{ $risk['name'] }}">{{ $risk['name'] }}</span>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-20 bg-slate-100 rounded-full overflow-hidden shrink-0">
                                    <div class="h-full {{ $risk['color'] }}" style="width: {{ $risk['percent'] }}%"></div>
                                </div>
                                <span class="text-slate-800 font-bold min-w-[24px] text-right">{{ $risk['percent'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Distribution Bar Chart -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between h-44 md:col-span-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-0.5">Distribuição de Score BARS</p>
                        <h3 class="font-mono text-lg font-bold text-slate-950">
                            {{ $avgScore !== null ? number_format($avgScore, 2) : '—' }}
                            <span class="text-[10px] font-semibold text-slate-450 uppercase font-sans tracking-wider ml-1">Média Geral</span>
                        </h3>
                    </div>
                    <div class="flex items-center gap-1.5 px-2 py-0.5 bg-slate-50 border border-slate-200 rounded font-mono text-[9px] text-slate-500 font-bold">
                        <span>NÍVEL 1-5</span>
                    </div>
                </div>
                
                <div class="flex items-end justify-between h-14 gap-2 mt-4">
                    <div class="w-full bg-slate-100 rounded-t-sm transition-all duration-500" style="height: {{ $scoreHeights[1] }}%" title="Nível 1 (Crítico): {{ $scoreHeights[1] }}%"></div>
                    <div class="w-full bg-slate-100 rounded-t-sm transition-all duration-500" style="height: {{ $scoreHeights[2] }}%" title="Nível 2 (Insuficiente): {{ $scoreHeights[2] }}%"></div>
                    <div class="w-full bg-slate-100 rounded-t-sm transition-all duration-500" style="height: {{ $scoreHeights[3] }}%" title="Nível 3 (Esperado): {{ $scoreHeights[3] }}%"></div>
                    <div class="w-full bg-blue-600 rounded-t-sm transition-all duration-500 shadow-sm shadow-blue-500/20" style="height: {{ $scoreHeights[4] }}%" title="Nível 4 (Destaque): {{ $scoreHeights[4] }}%"></div>
                    <div class="w-full bg-slate-100 rounded-t-sm transition-all duration-500" style="height: {{ $scoreHeights[5] }}%" title="Nível 5 (Excelente): {{ $scoreHeights[5] }}%"></div>
                </div>
            </div>

            <!-- Total Evaluations Summary -->
            <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-lg overflow-hidden relative group h-44">
                <div class="absolute -right-4 -bottom-4 opacity-5 rotate-12 group-hover:rotate-0 transition-all select-none pointer-events-none">
                    <span class="material-symbols-outlined text-[100px]">verified_user</span>
                </div>
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <p class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">Apurado Total</p>
                    <div>
                        <span class="text-3xl font-black font-mono leading-none">{{ $totalEvaluations }}</span>
                        <p class="text-[9px] text-slate-400 mt-2 uppercase tracking-wider font-semibold font-sans">Servidores Monitorados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Insights Banner (Predictive Intelligence) -->
        <div class="bg-violet-50/40 border border-violet-150 rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-50/10 via-white/5 to-transparent backdrop-blur-xs"></div>
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative z-10 w-full">
                <div class="flex items-start gap-4">
                    <div class="size-14 bg-violet-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-violet-500/20 shrink-0 select-none">
                        <span class="material-symbols-outlined text-3xl">psychology</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-violet-600 uppercase text-[10px] font-mono tracking-widest mb-1.5">Predictive Intelligence (v2.0)</h4>
                        <p class="text-slate-800 text-sm font-medium leading-relaxed">
                            @if($criticalServersCount > 0)
                                <span class="bg-rose-500 text-white text-[9px] font-extrabold px-2 py-0.5 rounded uppercase mr-2 tracking-wider shadow-sm shrink-0">Risco Alto</span>
                                Detectados <span class="font-black underline decoration-violet-500/30 font-mono text-violet-700">{{ str_pad($criticalServersCount, 2, '0', STR_PAD_LEFT) }} servidores</span> com score final crítico ou inconsistências documentadas no ciclo ativo.
                            @else
                                <span class="bg-emerald-500 text-white text-[9px] font-extrabold px-2 py-0.5 rounded uppercase mr-2 tracking-wider shadow-sm shrink-0">Risco Baixo</span>
                                Todos os servidores avaliados operam acima da nota de corte institucional. Nenhuma anomalia crítica detectada nas âncoras BARS do ciclo.
                            @endif
                        </p>
                        <div class="flex flex-wrap gap-2.5 mt-3.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-white/70 border border-slate-200 text-[9.5px] font-bold text-slate-600 uppercase shadow-xxs">
                                <span class="material-symbols-outlined text-[13px] text-violet-500">task_alt</span>
                                <span>Revisão do Time Financeiro</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-white/70 border border-slate-200 text-[9.5px] font-bold text-slate-600 uppercase shadow-xxs">
                                <span class="material-symbols-outlined text-[13px] text-violet-500">notifications_active</span>
                                <span>Notificar Gestores Logística</span>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="window.location.href='{{ route('admin.logs.index') }}'" class="bg-violet-600 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-violet-700 active:scale-95 transition-all shadow-md shadow-violet-500/20 flex items-center gap-2 shrink-0 border border-white/10 select-none cursor-pointer">
                    <span>Verificar Trilha</span>
                    <span class="material-symbols-outlined text-sm">rocket_launch</span>
                </button>
            </div>
        </div>

        <!-- Controls & Filters (Estética Stitch) -->
        <div class="bg-white border border-slate-250/60 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-4 shadow-sm">
            <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
                <!-- Caixa de Pesquisa e Filtro Retrátil -->
                <div class="relative flex-1 max-w-md">
                    <input type="text" 
                           id="search-input-main"
                           value="{{ $search }}" 
                           placeholder="Console de Busca & Descoberta..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder:text-slate-400">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">search_insights</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="btn-toggle-filters" class="flex items-center gap-1.5 bg-white border border-slate-200 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:border-slate-300 transition-all select-none cursor-pointer">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">tune</span>
                        <span>Filtros Avançados</span>
                        @if($activeFiltersCount > 0)
                            <span class="w-4 h-4 bg-blue-600 text-white rounded-full text-[9px] font-mono font-bold flex items-center justify-center select-none">{{ $activeFiltersCount }}</span>
                        @endif
                    </button>
                    @if($activeFiltersCount > 0)
                        <a href="{{ route('evaluations.index') }}" hx-boost="false" class="inline-flex items-center gap-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 transition select-none">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                            <span>Limpar Filtros</span>
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                @if(auth()->user()->isAdmin())
                    <button type="button" onclick="window.location.href='{{ route('admin.evaluation-results.index') }}'" class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:border-blue-500 hover:text-blue-600 transition-all select-none cursor-pointer">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">bar_chart</span>
                        <span>Quadro Consolidado</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Filtros Suspensos Expansíveis -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hidden" id="filters-container-box">
            <form method="GET" action="{{ route('evaluations.index') }}" id="form-filters-eval">
                <!-- Preservar busca do input principal -->
                <input type="hidden" name="search" id="search-hidden-input" value="{{ $search }}">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Filtro de Ciclo -->
                    <div>
                        <label for="cycle_id" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Ciclo de Avaliação</label>
                        <select name="cycle_id" id="cycle_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white transition-all">
                            <option value="">Todos os Ciclos</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ $cycleId == $cycle->id ? 'selected' : '' }}>{{ $cycle->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro de Lotação -->
                    <div>
                        <label for="lotacao" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Lotação (Secretaria)</label>
                        <select name="lotacao" id="lotacao" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white transition-all">
                            <option value="">Todas as Lotações</option>
                            @foreach($lotacoes as $lote)
                                <option value="{{ $lote }}" {{ $lotacao === $lote ? 'selected' : '' }}>{{ $lote }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro de Status -->
                    <div>
                        <label for="status" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Status da Avaliação</label>
                        <select name="status" id="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white transition-all">
                            <option value="">Todos os Status</option>
                            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Rascunho</option>
                            <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Concluído / Entregue</option>
                        </select>
                    </div>

                    <!-- Filtro de Faixa de Nota / Desempenho -->
                    <div>
                        <label for="score_range" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Faixa de Desempenho</label>
                        <select name="score_range" id="score_range" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white transition-all">
                            <option value="">Todos os Desempenhos</option>
                            <option value="above" {{ $scoreRange === 'above' ? 'selected' : '' }}>✓ Acima da Média de Corte (Apto)</option>
                            <option value="below" {{ $scoreRange === 'below' ? 'selected' : '' }}>⚠ Abaixo da Média de Corte (Inapto)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-100">
                    <!-- Ordenação -->
                    <div>
                        <label for="sort_by" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Ordenar Registros por</label>
                        <select name="sort_by" id="sort_by" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white transition-all">
                            <option value="recent" {{ $sortBy === 'recent' ? 'selected' : '' }}>Mais Recentes Primeiro</option>
                            <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>Mais Antigas Primeiro</option>
                            <option value="name_asc" {{ $sortBy === 'name_asc' ? 'selected' : '' }}>Nome do Avaliado (A-Z)</option>
                            <option value="name_desc" {{ $sortBy === 'name_desc' ? 'selected' : '' }}>Nome do Avaliado (Z-A)</option>
                            <option value="score_desc" {{ $sortBy === 'score_desc' ? 'selected' : '' }}>Maior Nota Final</option>
                            <option value="score_asc" {{ $sortBy === 'score_asc' ? 'selected' : '' }}>Menor Nota Final</option>
                        </select>
                    </div>

                    <div class="flex items-end justify-end gap-2">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-md shadow-blue-500/10 transition select-none cursor-pointer">
                            Aplicar Filtros
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Batch Actions Bar (Barra de Ações em Lote) -->
        <div class="bg-slate-950 text-white p-3 rounded-2xl border border-slate-800 flex items-center justify-between hidden animate-reveal-up shadow-lg" id="batch-actions-bar">
            <div class="flex items-center gap-4 px-3">
                <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">
                    <span id="selected-count" class="text-white font-bold">0</span> selecionados
                </span>
                <div class="h-4 w-px bg-white/10"></div>
                <p class="text-xs font-medium text-slate-300">Ações em lote disponíveis para servidores selecionados no quadro.</p>
            </div>
            <div class="flex gap-2">
                <button type="button" id="btn-batch-remind" class="px-4 py-1.5 rounded-lg border border-white/20 text-[10px] font-bold uppercase hover:bg-white/10 transition-all select-none cursor-pointer">Disparar Notificação</button>
                <button type="button" id="btn-batch-archive" class="px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase transition-all select-none cursor-pointer">Arquivar em Lote</button>
            </div>
        </div>

        <!-- High-Density Data Grid (Tabela Servidores) -->
        <div class="bg-white rounded-2xl border border-slate-250/60 shadow-sm overflow-hidden mt-6">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/30">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Servidores sob Monitoramento</h3>
                    <p class="text-xs text-slate-500 mt-1">Exibição de avaliações do ciclo ativo e histórico de notas APD.</p>
                </div>
                <span class="bg-slate-150 text-slate-600 px-3 py-1 rounded-full text-[9px] font-mono font-bold border border-slate-200/60 uppercase select-none">
                    {{ $evaluations->total() }} Servidores
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200/60">
                            <th class="p-4 w-12 text-center select-none">
                                <input type="checkbox" id="check-all-rows" class="rounded border-slate-300 text-blue-600 focus:ring-0 size-4 cursor-pointer">
                            </th>
                            <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest">Servidor / Identificação</th>
                            <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest">Setor / Cargo</th>
                            <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-center">Tendência</th>
                            <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-center">Score APD</th>
                            <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-center">Status / Validação</th>
                            <th class="p-4 font-mono text-[9px] font-bold text-slate-500 uppercase tracking-widest text-right w-44">Ações Avançadas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($evaluations as $evaluation)
                            @php
                                $score = $evaluation->final_score;
                                $isCritical = $evaluation->status === 'submitted' && $score < ($evaluation->cycle->cutoff_score ?? 3.00);
                                $initials = strtoupper(substr($evaluation->evaluated->name ?? 'S', 0, 2));
                                
                                // Tendência baseada na nota
                                $trend = 'stable';
                                if ($evaluation->status === 'submitted') {
                                    $trend = $score >= 4.0 ? 'up' : ($score < 3.0 ? 'down' : 'stable');
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/30 transition-all group {{ $isCritical ? 'bg-rose-500/[0.015]' : '' }}">
                                <!-- Checkbox -->
                                <td class="p-4 text-center select-none">
                                    <input type="checkbox" class="row-selector rounded border-slate-300 text-blue-600 focus:ring-0 size-4 cursor-pointer" data-id="{{ $evaluation->id }}">
                                </td>
                                
                                <!-- Servidor / Iniciais / Matrícula -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 shadow-xxs">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm leading-tight group-hover:text-blue-600 transition-colors">{{ $evaluation->evaluated->name ?? 'Servidor Não Cadastrado' }}</p>
                                            <p class="font-mono text-[10px] text-slate-450 uppercase tracking-wider mt-0.5">Matrícula: {{ $evaluation->evaluated->registration_number ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Setor / Cargo -->
                                <td class="p-4">
                                    <div class="flex flex-col justify-center">
                                        <span class="text-xs font-semibold text-slate-700 leading-none">{{ $evaluation->evaluated->lotacao ?? 'Geral' }}</span>
                                        <span class="text-[9.5px] text-slate-400 uppercase font-mono mt-1 font-bold">{{ $evaluation->evaluated->cargo ?? 'Servidor' }}</span>
                                    </div>
                                </td>

                                <!-- Tendência -->
                                <td class="p-4 text-center">
                                    <div class="inline-flex items-center justify-center gap-1.5 h-6 w-20 mx-auto">
                                        @if($trend === 'up')
                                            <div class="trend-pip active-green h-[45%]"></div>
                                            <div class="trend-pip active-green h-[70%]"></div>
                                            <div class="trend-pip active-green h-[95%]"></div>
                                            <span class="material-symbols-outlined text-emerald-500 text-xs mb-1 font-bold select-none">trending_up</span>
                                        @elseif($trend === 'down')
                                            <div class="trend-pip active-red h-[95%]"></div>
                                            <div class="trend-pip active-red h-[70%]"></div>
                                            <div class="trend-pip active-red h-[45%]"></div>
                                            <span class="material-symbols-outlined text-rose-500 text-xs mb-1 font-bold select-none">trending_down</span>
                                        @else
                                            <div class="trend-pip h-[30%]"></div>
                                            <div class="trend-pip h-[60%]"></div>
                                            <div class="trend-pip h-[60%]"></div>
                                            <span class="material-symbols-outlined text-slate-400 text-xs mb-1 font-bold select-none">trending_flat</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Score APD -->
                                <td class="p-4 text-center">
                                    @if($evaluation->status === 'submitted' && $score !== null)
                                        <span class="font-mono text-base font-black {{ $isCritical ? 'text-rose-500' : 'text-blue-600' }}">{{ number_format($score, 2) }}</span>
                                        <span class="text-[9px] text-slate-400 block font-mono uppercase tracking-widest mt-0.5">PROV_SCORE_0{{ $evaluation->id }}</span>
                                    @else
                                        <span class="text-slate-400 font-mono font-bold text-sm">—</span>
                                        <span class="text-[9px] text-slate-400 block font-mono uppercase tracking-widest mt-0.5">DRAFT_PENDING</span>
                                    @endif
                                </td>

                                <!-- Status / Validação -->
                                <td class="p-4">
                                    <div class="flex flex-col items-center justify-center gap-1">
                                        @if($evaluation->status === 'submitted')
                                            @if($isCritical)
                                                <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-600 text-[9px] font-black uppercase border border-rose-150 select-none">
                                                    Risco de Recurso
                                                </span>
                                                <span class="text-[8px] font-mono text-rose-500 font-bold uppercase tracking-tight">Insuficiente de Evidência</span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase border border-emerald-150 select-none">
                                                    Fundamentado
                                                </span>
                                                <span class="text-[8px] font-mono text-emerald-500 font-bold uppercase tracking-tight">Escore Homologado</span>
                                            @endif
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[9px] font-black uppercase border border-amber-150 select-none">
                                                Pendente / Rascunho
                                            </span>
                                            <span class="text-[8px] font-mono text-amber-500 font-bold uppercase tracking-tight">Aguardando Envio</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Ações -->
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2 select-none">
                                        @if($evaluation->status === 'draft')
                                            <!-- Preencher Rascunho -->
                                            <a href="{{ route('evaluations.fill', $evaluation->id) }}" hx-boost="false" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3.5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all shadow-xxs">
                                                <span>Preencher</span>
                                                <span class="material-symbols-outlined text-[14px]">edit_note</span>
                                            </a>
                                        @else
                                            <!-- Visualização e Ação baseada em Risco -->
                                            @if($isCritical)
                                                <a href="{{ route('admin.employee-diary-incidents.index', ['employee_id' => $evaluation->evaluated_id]) }}" hx-boost="false" class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white px-3.5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all shadow-md shadow-rose-500/10">
                                                    <span>Mitigar</span>
                                                    <span class="material-symbols-outlined text-[14px]">gavel</span>
                                                </a>
                                            @else
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('admin.evaluation-results.show', $evaluation->id) }}" hx-boost="false" class="inline-flex items-center gap-1.5 bg-slate-50 hover:bg-blue-600 hover:text-white border border-slate-250 text-slate-600 px-3.5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all shadow-xxs">
                                                        <span>Gerir APD</span>
                                                        <span class="material-symbols-outlined text-[14px]">arrow_forward_ios</span>
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-400 border border-slate-150 px-3.5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider cursor-not-allowed">
                                                        <span>Concluído</span>
                                                        <span class="material-symbols-outlined text-[14px]">lock</span>
                                                    </span>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 font-bold">
                                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 select-none">
                                        <span class="material-symbols-outlined text-slate-300 text-2xl">search_off</span>
                                    </div>
                                    <span>Nenhuma avaliação encontrada correspondente aos filtros.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação Customizada -->
            @if($evaluations->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest select-none">
                        Exibindo registros {{ $evaluations->firstItem() }} a {{ $evaluations->lastItem() }} de {{ $evaluations->total() }}
                    </p>
                    <div class="flex items-center gap-1.5 select-none">
                        <!-- Botão Anterior -->
                        @if($evaluations->onFirstPage())
                            <span class="size-8 flex items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </span>
                        @else
                            <a href="{{ $evaluations->previousPageUrl() }}" hx-boost="false" class="size-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:text-blue-600 hover:border-blue-500 transition shadow-xxs">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </a>
                        @endif

                        <!-- Paginações Numéricas -->
                        <div class="flex gap-1">
                            @foreach($evaluations->getUrlRange(max(1, $evaluations->currentPage() - 2), min($evaluations->lastPage(), $evaluations->currentPage() + 2)) as $page => $url)
                                @if($page == $evaluations->currentPage())
                                    <span class="size-8 flex items-center justify-center rounded-lg bg-slate-900 text-white text-[11px] font-mono font-bold shadow-xs">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" hx-boost="false" class="size-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-600 text-[11px] font-mono font-bold hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition shadow-xxs">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <!-- Botão Próximo -->
                        @if($evaluations->hasMorePages())
                            <a href="{{ $evaluations->nextPageUrl() }}" hx-boost="false" class="size-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:text-blue-600 hover:border-blue-500 transition shadow-xxs">
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </a>
                        @else
                            <span class="size-8 flex items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed">
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts e Microinterações locais compatíveis com swaps parciais HTMX -->
    <script>
        $(document).ready(function() {
            // 1. Mostrar/Ocultar painel de filtros avançados
            $(document).off('click', '#btn-toggle-filters').on('click', '#btn-toggle-filters', function() {
                var $container = $('#filters-container-box');
                var $chevron = $(this).find('.material-symbols-outlined');
                
                $container.slideToggle(200, function() {
                    if ($container.is(':visible')) {
                        $chevron.addClass('rotate-180');
                    } else {
                        $chevron.removeClass('rotate-180');
                    }
                });
            });

            // Sincronizar campo de busca principal com o input oculto do formulário de filtros
            $('#search-input-main').on('input', function() {
                $('#search-hidden-input').val($(this).val());
            });

            // Enviar formulário ao apertar Enter no input principal de busca
            $('#search-input-main').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#form-filters-eval').submit();
                }
            });

            // 2. Controle de seleção em massa (Checkboxes & Batch Actions)
            var $checkAll = $('#check-all-rows');
            var $rowCheckboxes = $('.row-selector');
            var $batchBar = $('#batch-actions-bar');
            var $selectedCount = $('#selected-count');

            function updateBatchActions() {
                var checkedCount = $('.row-selector:checked').length;
                $selectedCount.text(checkedCount);
                
                if (checkedCount > 0) {
                    if ($batchBar.hasClass('hidden')) {
                        $batchBar.removeClass('hidden');
                    }
                } else {
                    $batchBar.addClass('hidden');
                }
            }

            $(document).off('change', '#check-all-rows').on('change', '#check-all-rows', function() {
                var isChecked = $(this).is(':checked');
                $rowCheckboxes.prop('checked', isChecked);
                updateBatchActions();
            });

            $(document).off('change', '.row-selector').on('change', '.row-selector', function() {
                var total = $rowCheckboxes.length;
                var checked = $('.row-selector:checked').length;
                $checkAll.prop('checked', total === checked);
                updateBatchActions();
            });

            // 3. Ações em lote simuladas com notificação
            $(document).off('click', '#btn-batch-remind').on('click', '#btn-batch-remind', function() {
                var ids = [];
                $('.row-selector:checked').each(function() {
                    ids.push($(this).data('id'));
                });
                alert('Notificação de preenchimento disparada com sucesso para os servidores vinculados às avaliações selecionadas (IDs: ' + ids.join(', ') + ')!');
                // Desmarcar todos após ação
                $checkAll.prop('checked', false);
                $rowCheckboxes.prop('checked', false);
                updateBatchActions();
            });

            $(document).off('click', '#btn-batch-archive').on('click', '#btn-batch-archive', function() {
                var ids = [];
                $('.row-selector:checked').each(function() {
                    ids.push($(this).data('id'));
                });
                alert('Arquivamento administrativo solicitado para as avaliações selecionadas (IDs: ' + ids.join(', ') + ')!');
                $checkAll.prop('checked', false);
                $rowCheckboxes.prop('checked', false);
                updateBatchActions();
            });
        });
    </script>
</x-app-layout>
