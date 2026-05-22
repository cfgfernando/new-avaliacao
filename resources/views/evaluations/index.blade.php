<x-app-layout>
    @section('title', 'Avaliações (APD)')

    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Cabeçalho Principal -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Avaliações (APD)</h1>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Histórico e preenchimento de Avaliações de Desempenho de Servidores</p>
            </div>
            <a href="{{ route('evaluations.setup.create') }}" class="inline-flex items-center gap-2 bg-accent hover:bg-accent-hover text-white font-bold text-xs uppercase tracking-wider py-2.5 px-6 rounded-lg transition active:scale-95 shadow-sm">
                <span class="material-symbols-outlined text-[16px]">add</span>
                <span>Nova Avaliação</span>
            </a>
        </div>

        <!-- Alertas de Feedback -->
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl font-bold text-sm border border-emerald-100 flex items-center gap-3 shadow-sm animate-fadeIn">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 text-rose-600 p-4 rounded-xl font-bold text-sm border border-rose-100 flex items-center gap-3 shadow-sm animate-fadeIn">
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

        <!-- Dashboard de Métricas (Stats Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <!-- Card 1: Total Iniciadas -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 border border-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">assignment</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono leading-none">Total de Avaliações</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1.5 font-mono">{{ $totalEvaluations }}</h3>
                </div>
            </div>

            <!-- Card 2: Entregues -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">check_circle</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono leading-none">Entregues</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1.5 font-mono">{{ $submittedCount }}</h3>
                </div>
            </div>

            <!-- Card 3: Em Rascunho -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">edit_document</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono leading-none">Em Rascunho</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1.5 font-mono">{{ $draftCount }}</h3>
                </div>
            </div>

            <!-- Card 4: Média Geral -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 border border-purple-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">star</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-mono leading-none">Média Geral</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1.5 font-mono">
                        {{ $avgScore !== null ? number_format($avgScore, 2) : '—' }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Painel de Filtros Avançados -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between cursor-pointer select-none" id="btn-toggle-filters">
                <div class="flex items-center gap-2 text-slate-700 font-bold text-sm">
                    <span class="material-symbols-outlined text-slate-400 text-[20px]">filter_alt</span>
                    <span>Filtros Avançados</span>
                    @if($activeFiltersCount > 0)
                        <span class="inline-flex items-center justify-center bg-accent text-white text-[10px] font-bold px-2 py-0.5 rounded-full font-mono">
                            {{ $activeFiltersCount }} ativo{{ $activeFiltersCount > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200" id="chevron-filters">
                    {{ $activeFiltersCount > 0 ? 'expand_less' : 'expand_more' }}
                </span>
            </div>
            
            <form method="GET" action="{{ route('evaluations.index') }}" id="filters-container" class="space-y-4 mt-4" style="display: {{ $activeFiltersCount > 0 ? 'block' : 'none' }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                    <!-- Busca Textual -->
                    <div>
                        <label for="search" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Servidor (Nome/Matrícula)</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ $search }}" 
                                   placeholder="Buscar nome ou matrícula..." 
                                   class="input-neo !pl-10 pr-4 !py-2.5">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-[18px] text-slate-400">search</span>
                        </div>
                    </div>

                    <!-- Filtro de Ciclo -->
                    <div>
                        <label for="cycle_id" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Ciclo de Avaliação</label>
                        <select name="cycle_id" id="cycle_id" class="input-neo !py-2.5">
                            <option value="">Todos os Ciclos</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ $cycleId == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro de Lotação -->
                    <div>
                        <label for="lotacao" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Lotação do Servidor</label>
                        <select name="lotacao" id="lotacao" class="input-neo !py-2.5">
                            <option value="">Todas as Lotações</option>
                            @foreach($lotacoes as $lote)
                                <option value="{{ $lote }}" {{ $lotacao === $lote ? 'selected' : '' }}>
                                    {{ $lote }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro de Status -->
                    <div>
                        <label for="status" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Status</label>
                        <select name="status" id="status" class="input-neo !py-2.5">
                            <option value="">Todos os Status</option>
                            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Rascunho</option>
                            <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Entregue</option>
                        </select>
                    </div>

                    <!-- Filtro de Faixa de Nota / Desempenho -->
                    <div>
                        <label for="score_range" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Desempenho</label>
                        <select name="score_range" id="score_range" class="input-neo !py-2.5">
                            <option value="">Todos os Desempenhos</option>
                            <option value="above" {{ $scoreRange === 'above' ? 'selected' : '' }}>✓ Acima da Nota de Corte</option>
                            <option value="below" {{ $scoreRange === 'below' ? 'selected' : '' }}>⚠ Abaixo da Nota de Corte</option>
                        </select>
                    </div>

                    <!-- Ordenação -->
                    <div>
                        <label for="sort_by" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Ordenar Por</label>
                        <select name="sort_by" id="sort_by" class="input-neo !py-2.5">
                            <option value="recent" {{ $sortBy === 'recent' ? 'selected' : '' }}>Mais Recentes Primeiro</option>
                            <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>Mais Antigas Primeiro</option>
                            <option value="name_asc" {{ $sortBy === 'name_asc' ? 'selected' : '' }}>Nome do Avaliado (A-Z)</option>
                            <option value="name_desc" {{ $sortBy === 'name_desc' ? 'selected' : '' }}>Nome do Avaliado (Z-A)</option>
                            <option value="score_desc" {{ $sortBy === 'score_desc' ? 'selected' : '' }}>Maior Nota Final</option>
                            <option value="score_asc" {{ $sortBy === 'score_asc' ? 'selected' : '' }}>Menor Nota Final</option>
                        </select>
                    </div>
                </div>

                <!-- Botões de Ação do Filtro -->
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <a href="{{ route('evaluations.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                        <span class="material-symbols-outlined text-[16px]">clear_all</span>
                        <span>Limpar Filtros</span>
                    </a>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 bg-accent hover:bg-accent-hover text-white rounded-lg text-xs font-semibold transition shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                        <span>Filtrar</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabela de Resultados -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight font-sans">
                        Histórico de Avaliações
                    </h2>
                    <p class="text-slate-500 text-xs mt-1">
                        Lista completa de avaliações de desempenho iniciadas, salvas como rascunho ou enviadas.
                    </p>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-600 border border-blue-150 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">assignment</span>
                </div>
            </div>

            @if($evaluations->count() > 0)
                <div class="overflow-hidden border border-slate-200 rounded-lg shadow-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/60">
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Servidor</th>
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Lotação / Cargo</th>
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Ciclo</th>
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Status / Envio</th>
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Nota Final</th>
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($evaluations as $evaluation)
                                <tr class="hover:bg-gray-50/50 border-b border-gray-100 transition">
                                    <!-- Servidor -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 border border-slate-200 shrink-0">
                                                {{ strtoupper(substr($evaluation->evaluated->name ?? 'S', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm leading-tight">
                                                    {{ $evaluation->evaluated->name ?? 'Servidor Não Informado' }}
                                                </div>
                                                <div class="text-[11px] font-semibold font-mono text-slate-400 mt-0.5">
                                                    Matrícula: {{ $evaluation->evaluated->registration_number ?? '—' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Lotação / Cargo -->
                                    <td class="py-3.5 px-4">
                                        <div class="text-xs font-bold text-slate-700">
                                            {{ $evaluation->evaluated->lotacao ?? 'Sem Lotação' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $evaluation->evaluated->cargo ?? 'Sem Cargo' }}
                                        </div>
                                    </td>

                                    <!-- Ciclo -->
                                    <td class="py-3.5 px-4 text-xs font-bold text-slate-600">
                                        {{ $evaluation->cycle->name ?? 'Sem Ciclo' }}
                                    </td>

                                    <!-- Status / Envio -->
                                    <td class="py-3.5 px-4">
                                        @if($evaluation->status === 'submitted')
                                            <span class="inline-flex items-center gap-1 text-[10px] bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded border border-emerald-200 font-mono">
                                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                                ✓ ENTREGUE
                                            </span>
                                            <div class="text-[10px] text-slate-400 mt-1 font-mono font-semibold">
                                                {{ $evaluation->submitted_at ? $evaluation->submitted_at->format('d/m/Y H:i') : '—' }}
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[10px] bg-amber-50 text-amber-700 font-bold px-2 py-0.5 rounded border border-amber-200 font-mono">
                                                <span class="w-1 h-1 rounded-full bg-amber-500"></span>
                                                ✎ RASCUNHO
                                            </span>
                                            <div class="text-[10px] text-slate-400 mt-1 font-mono font-semibold">
                                                Criada em: {{ $evaluation->created_at ? $evaluation->created_at->format('d/m/Y') : '—' }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Nota Final -->
                                    <td class="py-3.5 px-4">
                                        @if($evaluation->status === 'submitted' && $evaluation->final_score !== null)
                                            <span class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg font-mono font-bold text-sm shadow-xs border {{ $evaluation->final_score >= ($evaluation->cycle->cutoff_score ?? 3.00) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-150' }}">
                                                ★ {{ number_format($evaluation->final_score, 2) }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-mono font-bold text-sm">—</span>
                                        @endif
                                    </td>

                                    <!-- Ações -->
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($evaluation->status === 'draft')
                                                <!-- Continuar Preenchendo -->
                                                <a href="{{ route('evaluations.fill', $evaluation->id) }}" 
                                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition"
                                                   title="Continuar Preenchendo">
                                                    <span class="material-symbols-outlined text-[16px]">edit_note</span>
                                                    <span>Preencher</span>
                                                </a>
                                            @else
                                                <!-- Visualizar resultados (Se for Admin) -->
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('admin.evaluation-results.show', $evaluation->id) }}" 
                                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition"
                                                       title="Visualizar Resultados">
                                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                                        <span>Resultados</span>
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-semibold cursor-not-allowed border border-slate-100" 
                                                          title="Apenas administradores podem ver o detalhamento do relatório final.">
                                                        <span class="material-symbols-outlined text-[16px]">lock</span>
                                                        <span>Concluído</span>
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if($evaluations->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $evaluations->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-slate-300">description</span>
                    </div>
                    <p class="text-slate-400 font-bold text-sm">Nenhuma avaliação encontrada.</p>
                    <p class="text-slate-300 text-xs mt-1">Refine seus filtros ou inicie uma nova avaliação.</p>
                    <a href="{{ route('evaluations.setup.create') }}" class="inline-flex items-center gap-1 mt-6 text-xs font-bold text-accent hover:text-accent-hover uppercase tracking-wider font-mono">
                        <span class="material-symbols-outlined text-[14px]">add</span>
                        <span>Criar Nova Avaliação</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        // Alternar exibição do painel de filtros
        $('#btn-toggle-filters').on('click', function() {
            var $container = $('#filters-container');
            var $chevron = $('#chevron-filters');
            
            $container.slideToggle(200, function() {
                if ($container.is(':visible')) {
                    $chevron.text('expand_less');
                } else {
                    $chevron.text('expand_more');
                }
            });
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
    </style>
    @endpush
</x-app-layout>
