<x-app-layout>
    @section('title', 'Dashboard')



    <div class="space-y-8 animate-reveal-up">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Estratégico</h2>
                <p class="text-slate-500 mt-1">Consolidado institucional do {{ $cycleName }} ({{ $startDate }} - {{ $endDate }})</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.employee-diary-incidents.index') }}" class="btn-neo bg-white text-slate-600 hover:bg-slate-50">
                    <span class="material-symbols-outlined text-[18px]">history_edu</span>
                    Diário de Bordo Funcional
                </a>
                <a href="{{ route('admin.evaluations.all.export') }}" class="btn-neo bg-white text-slate-600 hover:bg-slate-50">
                    <span class="material-symbols-outlined text-[18px]">file_download</span>
                    Exportar Dados
                </a>
                <x-button onclick="window.location.reload()" variant="primary">
                    <span class="material-symbols-outlined text-[18px]">refresh</span>
                    Sincronizar
                </x-button>
            </div>
        </div>

        <!-- KPI Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- KPI 1: Média Global BARS -->
            <x-stat-card 
                title="Média Global BARS" 
                icon="trending_up" 
                color="green" 
                value="{{ $avgBarsFormatted }}" 
                trend="{{ $avgBarsDiff }}" 
                :trendUp="$avgBars >= 4.0">
                <div class="mt-4 h-8 w-full flex items-end gap-0.5">
                    <div class="flex-1 bg-slate-100 rounded-sm h-1/2"></div>
                    <div class="flex-1 bg-slate-100 rounded-sm h-2/3"></div>
                    <div class="flex-1 bg-slate-100 rounded-sm h-1/3"></div>
                    <div class="flex-1 bg-slate-100 rounded-sm h-3/4"></div>
                    <div class="flex-1 bg-emerald-500 rounded-sm h-full shadow-[0_0_8px_rgba(16,185,129,0.3)]"></div>
                    <span class="text-[9px] font-mono text-slate-400 ml-2">Tendência</span>
                </div>
            </x-stat-card>

            <!-- KPI 2: Servidores Ativos -->
            <x-stat-card 
                title="Servidores Ativos" 
                icon="groups" 
                color="blue" 
                value="{{ number_format($totalServidores) }}" 
                subtitle="{{ number_format($engajamentoPercent, 1) }}% de engajamento no ciclo">
                <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden border border-slate-200/50">
                    <div class="bg-blue-600 h-full rounded-full" style="width: {{ $engajamentoPercent }}%"></div>
                </div>
            </x-stat-card>

            <!-- KPI 3: Tempo de Resposta -->
            <x-stat-card 
                title="Tempo de Resposta" 
                icon="avg_time" 
                color="amber" 
                value="{{ $avgDays }}" 
                trend="+0.3d" 
                :trendUp="false"
                subtitle="Média de conclusão de avaliações">
            </x-stat-card>

            <!-- KPI 4: Progresso OKR Global -->
            <x-card>
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">Progresso OKR Global</p>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                        <span class="material-symbols-outlined text-[18px]">donut_large</span>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <div class="relative w-14 h-14 shrink-0">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <path class="stroke-slate-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4"></path>
                            <path class="stroke-blue-600" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-dasharray="{{ $avgOkrPercent }}, 100" stroke-linecap="round" stroke-width="4"></path>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-[10px] font-bold font-mono text-slate-900">{{ $avgOkrPercent }}%</span>
                    </div>
                    <div>
                        <p class="text-base font-bold text-slate-900 leading-none">Atingido</p>
                        <p class="text-[10px] text-slate-400 mt-1 font-mono uppercase tracking-tighter">Projetado: 72%</p>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Main Bento Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Distribution & Top Performers -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Distribution Chart -->
                <x-card>
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Distribuição de Performance BARS</h3>
                            <p class="text-xs text-slate-400 font-semibold mt-0.5">Comparativo de scores entre os servidores do órgão</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-lg border border-blue-200/50 font-mono uppercase">Estatística Geral</span>
                        </div>
                    </div>
                    
                    <div class="h-60 flex items-end justify-between px-4 pb-2 border-b border-slate-100">
                        @foreach($levels as $lvl => $pct)
                            @php
                                $colors = [
                                    1 => ['bg' => 'bg-rose-500/30', 'hover' => 'group-hover:bg-rose-500/40', 'lbl' => 'text-rose-500'],
                                    2 => ['bg' => 'bg-amber-500/30', 'hover' => 'group-hover:bg-amber-500/40', 'lbl' => 'text-amber-500'],
                                    3 => ['bg' => 'bg-blue-600/30', 'hover' => 'group-hover:bg-blue-600/40', 'lbl' => 'text-blue-600'],
                                    4 => ['bg' => 'bg-blue-600', 'hover' => 'group-hover:bg-blue-700', 'lbl' => 'text-blue-700'],
                                    5 => ['bg' => 'bg-emerald-500/30', 'hover' => 'group-hover:bg-emerald-500/40', 'lbl' => 'text-emerald-500']
                                ];
                                $cfg = $colors[$lvl];
                            @endphp
                            <!-- Bar Level {{ $lvl }} -->
                            <div class="flex flex-col items-center gap-3 w-16 group">
                                <div class="relative w-full bg-slate-50 border-x border-t border-slate-100 rounded-t-xl transition-all duration-500 overflow-hidden" style="height: {{ max(10, $pct * 2.2) }}px">
                                    <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="absolute bottom-0 w-full {{ $cfg['bg'] }} h-full transition-colors {{ $cfg['hover'] }} {{ $lvl === 4 ? 'shadow-[0_0_12px_rgba(37,99,235,0.3)]' : '' }}"></div>
                                </div>
                                <div class="text-center">
                                    <p class="text-[9px] font-bold font-mono text-slate-400">NÍVEL {{ $lvl }}</p>
                                    <p class="text-xs font-bold text-slate-800 font-mono mt-0.5">{{ $pct }}%</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sub-competencies -->
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <h4 class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-4">Matriz de Competências Críticas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/50">
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-2">
                                    <span>Inovação / Iniciativa</span>
                                    <span class="font-mono text-blue-600">{{ number_format($compIniciativa, 1) }}</span>
                                </div>
                                <div class="w-full bg-slate-250 h-1.5 rounded-full overflow-hidden border border-slate-200/20">
                                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ ($compIniciativa/5)*100 }}%"></div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/50">
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-2">
                                    <span>Ética / Disciplina</span>
                                    <span class="font-mono text-blue-600">{{ number_format($compDisciplina, 1) }}</span>
                                </div>
                                <div class="w-full bg-slate-250 h-1.5 rounded-full overflow-hidden border border-slate-200/20">
                                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ ($compDisciplina/5)*100 }}%"></div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/50">
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-2">
                                    <span>Gestão de Recursos</span>
                                    <span class="font-mono text-blue-600">{{ number_format($compResponsabilidade, 1) }}</span>
                                </div>
                                <div class="w-full bg-slate-250 h-1.5 rounded-full overflow-hidden border border-slate-200/20">
                                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ ($compResponsabilidade/5)*100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Top Performers Table -->
                <div class="bg-white rounded-lg border border-slate-200/60 shadow-sm overflow-hidden transition-all hover:shadow-md duration-300">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Servidores em Destaque</h3>
                        <a href="{{ route('admin.evaluation-results.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-all flex items-center gap-1 uppercase tracking-wider">
                            Ver todos os resultados
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-[10px] font-mono font-bold text-slate-400 uppercase">Servidor</th>
                                    <th class="px-6 py-3 text-[10px] font-mono font-bold text-slate-400 uppercase">Lotação</th>
                                    <th class="px-6 py-3 text-[10px] font-mono font-bold text-slate-400 uppercase text-center">Score Consolidado</th>
                                    <th class="px-6 py-3 text-[10px] font-mono font-bold text-slate-400 uppercase text-right">Status do Ciclo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($performersList as $performer)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="relative shrink-0">
                                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs font-mono shadow-xs border border-blue-200/40 select-none">
                                                        {{ $performer['avatar'] }}
                                                    </div>
                                                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ $performer['name'] }}</p>
                                                    <p class="text-[9px] font-mono text-slate-400 font-bold uppercase mt-0.5">{{ $performer['id'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3.5 text-xs font-semibold text-slate-500">{{ $performer['lotacao'] }}</td>
                                        <td class="px-6 py-3.5 text-center">
                                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg font-mono text-xs font-bold border border-emerald-200/60 shadow-xs">{{ $performer['score'] }}</span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            @php
                                                $statusColor = $performer['status'] === 'Superando' ? 'green' : 'blue';
                                            @endphp
                                            <x-badge color="{{ $statusColor }}">
                                                <span class="w-1 h-1 rounded-full bg-current mr-1.5"></span>
                                                {{ $performer['status'] }}
                                            </x-badge>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Feeds & Timeline Widgets -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Recent Activity Feed (FOCUSED ON EVALUATIONS) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col min-h-[480px] transition-all hover:shadow-md duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Atividade Recente</h3>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest font-semibold">Avaliações</span>
                        </div>
                    </div>
                    
                    <div class="space-y-6 flex-1 overflow-y-auto custom-scrollbar pr-2 max-h-[360px]">
                        @forelse($evaluationActivities as $activity)
                            @php
                                $isSubmitted = $activity->status === 'submitted';
                                if ($isSubmitted) {
                                    $badge = 'Concluído';
                                    $badgeStyle = 'text-emerald-700 bg-emerald-50 border border-emerald-200';
                                    $stripeStyle = 'bg-emerald-500';
                                    $actTitle = 'Avaliação Finalizada';
                                    $actDesc = 'O avaliador ' . ($activity->evaluator?->name ?? 'Sistema') . ' concluiu a avaliação do servidor ' . ($activity->evaluated?->name ?? 'Excluído') . ' com nota consolidada ' . number_format($activity->final_score ?? 0, 2) . '.';
                                } else {
                                    $badge = 'Rascunho';
                                    $badgeStyle = 'text-amber-700 bg-amber-50 border border-amber-200';
                                    $stripeStyle = 'bg-amber-500';
                                    $actTitle = 'Rascunho Salvo';
                                    $actDesc = 'O avaliador ' . ($activity->evaluator?->name ?? 'Sistema') . ' iniciou ou alterou o rascunho de avaliação do servidor ' . ($activity->evaluated?->name ?? 'Excluído') . '.';
                                }
                                $logTime = $activity->updated_at ? $activity->updated_at->diffForHumans() : 'Recente';
                            @endphp
                            <!-- Log Item -->
                            <div class="relative pl-5 group">
                                <div class="absolute left-0 top-1 w-1 h-12 {{ $stripeStyle }} rounded-full group-hover:w-1.5 transition-all"></div>
                                <div class="flex justify-between items-start">
                                    <span class="text-[9px] font-mono font-bold uppercase {{ $badgeStyle }} px-1.5 py-0.5 rounded-md leading-none shadow-xs">{{ $badge }}</span>
                                    <span class="text-[9px] font-mono text-slate-350 font-bold uppercase">{{ $logTime }}</span>
                                </div>
                                <p class="text-xs font-bold text-slate-800 mt-2">{{ $actTitle }}</p>
                                <p class="text-[11px] text-slate-450 mt-1 leading-relaxed" title="{{ $actDesc }}">
                                    {{ $actDesc }}
                                </p>
                            </div>
                        @empty
                            <!-- Fallback Mock Activity Feed if no evaluations -->
                            <div class="relative pl-5 group">
                                <div class="absolute left-0 top-1 w-1 h-12 bg-emerald-500 rounded-full group-hover:w-1.5 transition-all"></div>
                                <div class="flex justify-between items-start">
                                    <span class="text-[9px] font-mono font-bold text-emerald-600 bg-emerald-50 border border-emerald-250 px-1.5 py-0.5 rounded-md leading-none shadow-xs">Concluído</span>
                                    <span class="text-[9px] font-mono text-slate-300 font-bold">12m</span>
                                </div>
                                <p class="text-xs font-bold text-slate-850 mt-2">Avaliação Finalizada</p>
                                <p class="text-[11px] text-slate-450 mt-1 leading-relaxed">Avaliador Ricardo Souza concluiu a avaliação do servidor Mariana Alencar.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <a href="{{ route('admin.evaluation-results.index') }}" class="mt-6 w-full py-3 bg-slate-50 border border-slate-200 text-[10px] font-extrabold text-slate-650 rounded-xl hover:bg-slate-100 transition-all uppercase tracking-widest text-center shadow-xs">
                        Gerenciar Resultados
                    </a>
                </div>

                <!-- Suggested Actions -->
                <div class="bg-[#0f172a] text-white p-6 rounded-2xl shadow-md border border-slate-850 overflow-hidden relative group transition-all hover:shadow-lg">
                    <div class="absolute -right-10 -top-10 w-36 h-36 bg-blue-600/10 rounded-full blur-2xl transition-transform group-hover:scale-150 duration-1000"></div>
                    <div class="relative z-10">
                        <h3 class="text-sm font-extrabold flex items-center gap-2 mb-4 tracking-tight">
                            <span class="material-symbols-outlined text-amber-500 text-[18px]">auto_awesome</span>
                            Ações Recomendadas
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.employee-diary-incidents.index') }}" class="block w-full text-left p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all border border-white/5 group/item">
                                <div class="flex justify-between items-start">
                                    <p class="text-xs font-bold text-white tracking-tight">Diário de Bordo Funcional</p>
                                    <span class="text-[8px] font-mono text-blue-400 font-bold tracking-wider">ATIVO</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-mono">Gestão avançada de elogios, evidências e incidentes do ciclo.</p>
                            </a>
                            <a href="{{ route('admin.evaluation-results.index') }}" class="block w-full text-left p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all border border-white/5 group/item">
                                <div class="flex justify-between items-start">
                                    <p class="text-xs font-bold text-white tracking-tight">Validar Scores Recentes</p>
                                    <span class="text-[8px] font-mono text-slate-400 font-bold tracking-wider">PENDENTE</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-mono">Aguardando homologação de notas enviadas do ciclo.</p>
                            </a>
                            <a href="{{ route('admin.evaluation-questions.index') }}" class="block w-full text-left p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all border border-white/5 group/item">
                                <div class="flex justify-between items-start">
                                    <p class="text-xs font-bold text-white tracking-tight">Revisão de Pesos BARS</p>
                                    <span class="text-[8px] font-mono text-slate-400 font-bold tracking-wider">RECOMENDADO</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 font-mono">Configurar matriz de pesos específicos para as subprefeituras.</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cycle Schedule -->
                <x-card>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-6 font-mono">Cronograma do Ciclo</h3>
                    <div class="space-y-6">
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-0.5 w-4 h-4 bg-emerald-500/20 rounded-full flex items-center justify-center border border-emerald-100">
                                <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            </div>
                            <div class="absolute left-[7px] top-4.5 w-0.5 h-6 bg-slate-100"></div>
                            <p class="text-xs font-bold text-slate-900 leading-none">Pactuação de OKRs</p>
                            <p class="text-[9px] font-mono text-slate-400 font-bold uppercase mt-1">Concluído • 12 Ago</p>
                        </div>
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-0.5 w-4 h-4 bg-blue-500/20 rounded-full flex items-center justify-center border border-blue-100">
                                <div class="w-1.5 h-1.5 bg-blue-650 rounded-full animate-pulse shadow-[0_0_6px_rgba(37,99,235,0.4)]"></div>
                            </div>
                            <div class="absolute left-[7px] top-4.5 w-0.5 h-6 bg-slate-100"></div>
                            <p class="text-xs font-bold text-slate-900 leading-none">Avaliação BARS & Aferição OKR</p>
                            <p class="text-[9px] font-mono text-blue-600 font-extrabold uppercase mt-1">Em Andamento</p>
                        </div>
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-0.5 w-4 h-4 bg-slate-100 rounded-full flex items-center justify-center border border-slate-200/60">
                                <div class="w-1.5 h-1.5 bg-slate-350 rounded-full"></div>
                            </div>
                            <p class="text-xs font-bold text-slate-400 leading-none">Devolutiva & Homologação</p>
                            <p class="text-[9px] font-mono text-slate-300 font-bold uppercase mt-1">Previsão • 05 Out</p>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- ═══════════════════════════════════════
             QUADRO FUNCIONAL DE SERVIDORES (LISTAGEM AVANÇADA)
             ═══════════════════════════════════════ -->
        <x-card>
            <div class="flex items-center justify-between border-b border-slate-150 pb-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Quadro de Servidores e Status de Avaliação</h2>
                    <p class="text-slate-500 text-xs mt-1">Gerencie membros de sua unidade, insira anotações no diário de bordo e gerencie a avaliação BARS do ciclo através da lista abaixo.</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                    <span class="material-symbols-outlined text-[20px]">person_search</span>
                </div>
            </div>

            <!-- Filtros Avançados -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Pesquisar Servidor</label>
                    <input type="text" id="search-input" placeholder="Nome ou matrícula..." class="input-neo !py-2.5">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Secretaria / Lotação</label>
                    <select id="lotacao-select" class="input-neo !py-2.5">
                        <option value="">Todas as Lotações ({{ $totalServidores }})</option>
                        @foreach($servidores->pluck('lotacao')->unique()->filter() as $lot)
                            <option value="{{ $lot }}">{{ $lot }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Cargo / Função</label>
                    <select id="cargo-select" class="input-neo !py-2.5">
                        <option value="">Todos os Cargos</option>
                        @foreach($cargos as $cg)
                            <option value="{{ $cg }}">{{ $cg }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Status da Avaliação</label>
                    <select id="status-select" class="input-neo !py-2.5">
                        <option value="">Todos os Status</option>
                        <option value="pending">Pendente</option>
                        <option value="submitted">Concluído</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Faixa de Desempenho BARS</label>
                    <select id="performance-select" class="input-neo !py-2.5">
                        <option value="">Todas as Faixas</option>
                        <option value="excelente">Excelente (Nota >= 4.50)</option>
                        <option value="apto">Apto (Nota >= {{ number_format($cutoff, 2) }})</option>
                        <option value="inadequado">Insuficiente (Nota < {{ number_format($cutoff, 2) }})</option>
                        <option value="sem_nota">Não Avaliado (Pendente)</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Exibir por Página</label>
                    <select id="per-page-select" class="input-neo !py-2.5">
                        <option value="10" selected>10 registros</option>
                        <option value="20">20 registros</option>
                        <option value="50">50 registros</option>
                        <option value="100">100 registros</option>
                        <option value="all">Mostrar Todos</option>
                    </select>
                </div>
            </div>

            <!-- Listagem em Tabela -->
            <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-xs">
                <table class="w-full text-sm text-left text-slate-650" id="servidores-table">
                    <thead class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Servidor</th>
                            <th class="px-6 py-3.5">Cargo / Função</th>
                            <th class="px-6 py-3.5">Lotação / Unidade</th>
                            <th class="px-6 py-3.5 text-center">Status / Nota BARS</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($servidores as $servidor)
                            @php
                                $evaluation = $servidor->evaluations->first();
                                $status = $evaluation ? $evaluation->status : 'pending';
                                $score = $evaluation ? (float) $evaluation->final_score : 0.00;
                                $scorePercent = round($score * 20);
                                $initials = strtoupper(substr($servidor->name, 0, 2));
                                
                                // Realçar um servidor (ex: "João Carlos da Silva" para simular foco)
                                $isFocus = str_contains(strtolower($servidor->name), 'joão carlos');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $isFocus ? 'bg-blue-50/10' : '' }}" 
                                data-name="{{ strtolower($servidor->name) }}"
                                data-registration="{{ strtolower($servidor->registration_number) }}"
                                data-lotacao="{{ $servidor->lotacao }}"
                                data-status="{{ $status }}"
                                data-cargo="{{ $servidor->cargo }}"
                                data-score="{{ $score }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center font-black text-sm shrink-0 select-none">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 leading-none">{{ $servidor->name }}</div>
                                            <div class="text-[9px] font-mono text-slate-400 font-bold uppercase mt-1">Matrícula: {{ $servidor->registration_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-600">{{ $servidor->cargo ?? 'Servidor Municipal' }}</td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $servidor->lotacao ?? 'Sem lotação informada' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($status === 'submitted')
                                        <span class="inline-flex items-center gap-1 text-[10.5px] bg-emerald-50 text-emerald-600 font-black px-2.5 py-0.5 rounded-lg border border-emerald-200 font-mono uppercase tracking-wider shadow-xs">
                                            ✓ {{ $scorePercent }}/100
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10.5px] bg-slate-50 text-slate-500 font-bold px-2.5 py-0.5 rounded-lg border border-slate-200 uppercase font-mono tracking-wider">
                                            Pendente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($status === 'submitted')
                                            <a href="{{ route('evaluation.create', $servidor->id) }}" class="px-3.5 py-2 bg-blue-600 text-white font-bold rounded-lg text-xs shadow-md shadow-blue-500/10 hover:bg-blue-700 active:scale-95 transition-all select-none">
                                                Reavaliar
                                            </a>
                                        @else
                                            <a href="{{ route('evaluation.create', $servidor->id) }}" class="px-3.5 py-2 bg-blue-600 text-white font-bold rounded-lg text-xs shadow-md shadow-blue-500/10 hover:bg-blue-700 active:scale-95 transition-all select-none">
                                                Avaliar
                                            </a>
                                        @endif
                                         <a href="{{ route('admin.employee-diary-incidents.index') }}?employee_id={{ $servidor->id }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition-all select-none">
                                            Diário
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação do Quadro de Servidores -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-6 border-t border-slate-150">
                <div class="text-xs font-semibold text-slate-500 font-mono" id="pagination-info">
                    Mostrando 0 a 0 de 0 servidores
                </div>
                <div class="flex items-center gap-1.5" id="pagination-controls">
                    <!-- Gerado dinamicamente via jQuery -->
                </div>
            </div>
        </x-card>
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        var currentPage = 1;

        function filterAndPaginateServidores() {
            var searchQuery = $('#search-input').val().toLowerCase();
            var selectedLotacao = $('#lotacao-select').val();
            var selectedStatus = $('#status-select').val();
            var selectedCargo = $('#cargo-select').val();
            var selectedPerformance = $('#performance-select').val();
            var perPageVal = $('#per-page-select').val();
            
            var matchedRows = [];

            // 1. Filtrar as linhas
            $('#servidores-table tbody tr').each(function() {
                var name = $(this).data('name').toString();
                var registration = $(this).data('registration').toString();
                var lotacao = $(this).data('lotacao') ? $(this).data('lotacao').toString() : '';
                var status = $(this).data('status').toString();
                var cargo = $(this).data('cargo') ? $(this).data('cargo').toString() : '';
                var score = parseFloat($(this).data('score')) || 0;

                var matchesSearch = name.indexOf(searchQuery) > -1 || registration.indexOf(searchQuery) > -1;
                var matchesLotacao = selectedLotacao === '' || lotacao === selectedLotacao;
                var matchesStatus = selectedStatus === '' || status === selectedStatus;
                var matchesCargo = selectedCargo === '' || cargo === selectedCargo;

                var matchesPerformance = true;
                if (selectedPerformance !== '') {
                    var cutoff = parseFloat('{{ $cutoff }}') || 3.0;
                    if (selectedPerformance === 'excelente') {
                        matchesPerformance = score >= 4.5;
                    } else if (selectedPerformance === 'apto') {
                        matchesPerformance = score >= cutoff && score < 4.5;
                    } else if (selectedPerformance === 'inadequado') {
                        matchesPerformance = score > 0 && score < cutoff;
                    } else if (selectedPerformance === 'sem_nota') {
                        matchesPerformance = score === 0;
                    }
                }

                if (matchesSearch && matchesLotacao && matchesStatus && matchesCargo && matchesPerformance) {
                    matchedRows.push($(this));
                } else {
                    $(this).hide();
                }
            });

            var totalItems = matchedRows.length;
            var perPage = perPageVal === 'all' ? totalItems : parseInt(perPageVal);
            if (perPage <= 0) perPage = 10;
            
            var totalPages = Math.ceil(totalItems / perPage);
            if (totalPages < 1) totalPages = 1;

            // Ajustar página se estiver fora dos limites
            if (currentPage > totalPages) {
                currentPage = totalPages;
            }
            if (currentPage < 1) {
                currentPage = 1;
            }

            var startIdx = (currentPage - 1) * perPage;
            var endIdx = Math.min(startIdx + perPage, totalItems);

            // Ocultar todas primeiro
            $('#servidores-table tbody tr').hide();

            // Mostrar apenas as da página atual
            for (var i = startIdx; i < endIdx; i++) {
                matchedRows[i].show();
            }

            // Atualizar texto de paginação
            var infoText = "";
            if (totalItems === 0) {
                infoText = "Nenhum servidor encontrado";
            } else {
                infoText = "Mostrando " + (startIdx + 1) + " a " + endIdx + " de " + totalItems + " servidores";
            }
            $('#pagination-info').text(infoText);

            // Renderizar botões de controle
            var controlsHtml = "";

            // Botão Anterior
            if (currentPage > 1) {
                controlsHtml += '<button class="pagination-btn px-3 py-1.5 flex items-center gap-1 text-[11px] font-bold text-slate-650 bg-slate-50 hover:bg-slate-100 rounded-full border border-slate-200 transition-all shadow-xs cursor-pointer select-none active:scale-95" data-page="' + (currentPage - 1) + '"><span class="material-symbols-outlined text-[14px]">chevron_left</span>Anterior</button>';
            } else {
                controlsHtml += '<button class="px-3 py-1.5 flex items-center gap-1 text-[11px] font-bold text-slate-350 bg-slate-50 rounded-full border border-slate-150 cursor-not-allowed select-none" disabled><span class="material-symbols-outlined text-[14px]">chevron_left</span>Anterior</button>';
            }

            // Números das páginas com lógica inteligente de reticências
            var maxButtons = 5;
            var startPage = 1;
            var endPage = totalPages;

            if (totalPages > maxButtons) {
                var maxBeforeCurrent = Math.floor(maxButtons / 2);
                var maxAfterCurrent = Math.ceil(maxButtons / 2) - 1;

                if (currentPage <= maxBeforeCurrent) {
                    endPage = maxButtons;
                } else if (currentPage + maxAfterCurrent >= totalPages) {
                    startPage = totalPages - maxButtons + 1;
                } else {
                    startPage = currentPage - maxBeforeCurrent;
                    endPage = currentPage + maxAfterCurrent;
                }
            }

            if (startPage > 1) {
                controlsHtml += '<button class="pagination-btn w-8 h-8 flex items-center justify-center text-xs font-bold text-slate-650 bg-slate-50 hover:bg-slate-100 rounded-full border border-slate-200/60 shadow-xs cursor-pointer active:scale-95" data-page="1">1</button>';
                if (startPage > 2) {
                    controlsHtml += '<span class="text-xs font-bold text-slate-400 px-1 font-mono select-none">...</span>';
                }
            }

            for (var p = startPage; p <= endPage; p++) {
                if (p === currentPage) {
                    controlsHtml += '<button class="w-8 h-8 flex items-center justify-center text-xs font-bold text-white bg-blue-600 rounded-full shadow-lg shadow-blue-500/20 border border-blue-600 select-none cursor-default" disabled>' + p + '</button>';
                } else {
                    controlsHtml += '<button class="pagination-btn w-8 h-8 flex items-center justify-center text-xs font-bold text-slate-650 bg-slate-50 hover:bg-slate-100 rounded-full border border-slate-200/60 shadow-xs cursor-pointer active:scale-95" data-page="' + p + '">' + p + '</button>';
                }
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    controlsHtml += '<span class="text-xs font-bold text-slate-400 px-1 font-mono select-none">...</span>';
                }
                controlsHtml += '<button class="pagination-btn w-8 h-8 flex items-center justify-center text-xs font-bold text-slate-650 bg-slate-50 hover:bg-slate-100 rounded-full border border-slate-200/60 shadow-xs cursor-pointer active:scale-95" data-page="' + totalPages + '">' + totalPages + '</button>';
            }

            // Botão Próximo
            if (currentPage < totalPages) {
                controlsHtml += '<button class="pagination-btn px-3 py-1.5 flex items-center gap-1 text-[11px] font-bold text-slate-650 bg-slate-50 hover:bg-slate-100 rounded-full border border-slate-200 transition-all shadow-xs cursor-pointer select-none active:scale-95" data-page="' + (currentPage + 1) + '">Próximo<span class="material-symbols-outlined text-[14px]">chevron_right</span></button>';
            } else {
                controlsHtml += '<button class="px-3 py-1.5 flex items-center gap-1 text-[11px] font-bold text-slate-350 bg-slate-50 rounded-full border border-slate-150 cursor-not-allowed select-none" disabled>Próximo<span class="material-symbols-outlined text-[14px]">chevron_right</span></button>';
            }

            $('#pagination-controls').html(controlsHtml);
        }

        // Registrar cliques nos botões de paginação (usando delegação de eventos para elementos dinâmicos)
        $(document).on('click', '.pagination-btn', function() {
            var targetPage = parseInt($(this).data('page'));
            if (targetPage && targetPage !== currentPage) {
                currentPage = targetPage;
                filterAndPaginateServidores();
                
                // Rolar suavemente até o início da listagem de servidores
                $('html, body').animate({
                    scrollTop: $("#servidores-table").offset().top - 120
                }, 200);
            }
        });

        // Reiniciar página para 1 e aplicar paginação nos filtros
        $('#search-input').on('keyup', function() {
            currentPage = 1;
            filterAndPaginateServidores();
        });

        $('#lotacao-select, #status-select, #cargo-select, #performance-select, #per-page-select').on('change', function() {
            currentPage = 1;
            filterAndPaginateServidores();
        });

        // Inicialização na primeira carga
        filterAndPaginateServidores();

        // Animação e micro-interação de clique em botões gerais do sistema
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
</x-app-layout>
