<x-app-layout>
    @section('title', 'Dashboard')

    @php
        $totalServidores = $servidores->count();
        
        // Contagem de incidentes críticos
        $incidentesPositivos = \App\Models\EmployeeDiaryIncident::where('type', 'positive')->count();
        $incidentesNegativos = \App\Models\EmployeeDiaryIncident::where('type', 'negative')->count();
        $totalIncidentes = $incidentesPositivos + $incidentesNegativos;

        // Contagem de metas pactuadas nas avaliações do ciclo ativo
        $totalMetas = \App\Models\QuantitativeGoal::count();

        // Progresso do ciclo (avaliados concluídos vs total)
        $avaliados = $servidores->filter(function($s) {
            return $s->evaluations->first() && $s->evaluations->first()->status === 'submitted';
        })->count();

        // Recuperar informações dinâmicas do ciclo ativo
        $activeCycle = \App\Models\EvaluationCycle::where('status', 'active')->first() 
            ?? \App\Models\EvaluationCycle::latest()->first();
        
        $cycleName = $activeCycle ? $activeCycle->name : 'Ciclo Avaliativo Consolidado do Primeiro Semestre - 2026';
        $startDate = $activeCycle ? ($activeCycle->start_date ? (\Carbon\Carbon::parse($activeCycle->start_date)->format('Y-m-d')) : '2026-01-01') : '2026-01-01';
        $endDate = $activeCycle ? ($activeCycle->end_date ? (\Carbon\Carbon::parse($activeCycle->end_date)->format('Y-m-d')) : '2026-06-30') : '2026-06-30';
        
        $weights = $activeCycle ? ($activeCycle->weights ?? []) : [];
        $okrWeight = $weights['okr'] ?? 50;
        $barsWeight = $weights['bars'] ?? 50;
    @endphp

    <div class="space-y-6 animate-reveal-up">

        {{-- =========================================================
             1. CARD DO CICLO SEMESTRAL VIGENTE (TOP CARD)
             ========================================================= --}}
        <div class="bg-gradient-to-r from-[#0b1329] via-[#0d162d] to-[#1b2544] text-white rounded-xl p-6 md:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 border border-slate-800 shadow-md relative overflow-hidden">
            <div class="space-y-3 z-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-[#131d35] text-[10px] font-bold text-blue-400 uppercase tracking-wider font-mono border border-blue-500/10">
                    Ciclo Semestral Vigente
                </span>
                <h1 class="text-xl md:text-2xl font-bold text-white tracking-tight font-sans">
                    {{ $cycleName }}
                </h1>
                <p class="text-slate-400 text-xs font-semibold">
                    Período avaliativo de <span class="text-white font-bold">{{ $startDate }}</span> até <span class="text-white font-bold">{{ $endDate }}</span>.
                </p>
                <div class="pt-2">
                    <a href="{{ route('evaluations.setup.create') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md shadow-blue-500/20 transition-all font-sans">
                        <i class="fas fa-plus-circle mr-1.5"></i> Iniciar Nova Avaliação
                    </a>
                </div>
            </div>
            
            {{-- Metas e Competências à direita (unificado e colorido conforme imagem) --}}
            <div class="bg-[#070c18]/60 border border-slate-800 rounded-xl px-6 py-4 flex items-center divide-x divide-slate-800/80 z-10 shrink-0">
                <div class="text-center pr-6 min-w-[110px]">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 font-mono">Metas OKR</p>
                    <p class="text-2xl font-bold text-blue-400 font-mono">{{ $okrWeight }}%</p>
                </div>
                <div class="text-center pl-6 min-w-[110px]">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 font-mono">Competências BARS</p>
                    <p class="text-2xl font-bold text-blue-400 font-mono">{{ $barsWeight }}%</p>
                </div>
            </div>
        </div>

        {{-- =========================================================
             2. BENTO GRID - 4 CARDS DE KPI
             ========================================================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- KPI 1: Servidores no Setor --}}
            <div class="card-neo flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Servidores no Setor</p>
                        <h3 class="text-3xl font-bold text-slate-900 mt-2 font-mono">{{ $totalServidores }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#2563eb] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">group</span>
                    </div>
                </div>
                <p class="text-[10.5px] text-slate-500 font-medium mt-4">
                    Cadastrados e geridos pela Subprefeitura / Secretarias.
                </p>
            </div>

            {{-- KPI 2: Metas Ativas Pactuadas --}}
            <div class="card-neo flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Metas Ativas Pactuadas</p>
                        <h3 class="text-3xl font-bold text-slate-900 mt-2 font-mono">{{ $totalMetas }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#2563eb] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">description</span>
                    </div>
                </div>
                <p class="text-[10.5px] text-slate-500 font-medium mt-4">
                    Metas quantitativas de entrega vinculadas aos planos de trabalho.
                </p>
            </div>

            {{-- KPI 3: Incidentes Críticos --}}
            <div class="card-neo flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Incidentes Críticos</p>
                        <h3 class="text-3xl font-bold text-slate-900 mt-2 font-mono">{{ $totalIncidentes }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#2563eb] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">book</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4 text-[11px] font-bold">
                    <span class="flex items-center gap-1 text-emerald-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ $incidentesPositivos }} Pos.
                    </span>
                    <span class="flex items-center gap-1 text-rose-600">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        {{ $incidentesNegativos }} Neg.
                    </span>
                </div>
            </div>

            {{-- KPI 4: Progresso do Ciclo --}}
            <div class="card-neo flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Progresso do Ciclo</p>
                        <h3 class="text-3xl font-bold text-emerald-600 mt-2 font-mono">{{ $avaliados }} / {{ $totalServidores }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                    </div>
                </div>
                @php
                    $percent = $totalServidores > 0 ? ($avaliados / $totalServidores) * 100 : 0;
                @endphp
                <div class="mt-4">
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================
             3. QUADRO DE SERVIDORES E STATUS DE AVALIAÇÃO
             ========================================================= --}}
        <div class="card-neo space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight font-sans">
                        Quadro de Servidores e Status de Avaliação
                    </h2>
                    <p class="text-slate-500 text-xs mt-1">
                        Gerencie indivíduos, consulte diários de incidentes ou realize a avaliação BARS.
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#2563eb] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">person_search</span>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Pesquisar Servidor</label>
                    <input type="text" id="search-input" placeholder="Nome, cargo ou email..." class="input-neo !py-2.5">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Departamento / Lotação</label>
                    <select id="lotacao-select" class="input-neo !py-2.5">
                        <option value="">Todos os Setores ({{ $totalServidores }})</option>
                        @foreach($servidores->pluck('lotacao')->unique() as $lotacao)
                            <option value="{{ $lotacao }}">{{ $lotacao }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Status de Avaliação</label>
                    <select id="status-select" class="input-neo !py-2.5">
                        <option value="">Filtrar por Status (Todos)</option>
                        <option value="pending">Pendente</option>
                        <option value="submitted">Concluído</option>
                    </select>
                </div>
            </div>

            {{-- Grid de Servidores --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" id="servidores-grid">
                @foreach($servidores as $servidor)
                    @php
                        $evaluation = $servidor->evaluations->first();
                        $status = $evaluation ? $evaluation->status : 'pending';
                        
                        // Determinar foto de avatar
                        $avatarUrl = 'https://i.pravatar.cc/150?img=11';
                        if ($servidor->name == 'Mariana Alencar Santos') {
                            $avatarUrl = 'https://i.pravatar.cc/150?img=47';
                        } elseif ($servidor->name == 'João Carlos da Silva') {
                            $avatarUrl = 'https://i.pravatar.cc/150?img=12';
                        }
                        
                        // João Carlos da Silva tem a borda realçada
                        $isFocus = $servidor->name == 'João Carlos da Silva';
                    @endphp
                    
                    <div class="bg-white rounded-xl p-5 border-2 {{ $isFocus ? 'border-[#2563eb] shadow-md shadow-blue-500/10' : 'border-slate-200' }} flex flex-col justify-between gap-5 relative transition-all duration-300 hover:shadow-lg" 
                         data-name="{{ strtolower($servidor->name) }}" 
                         data-lotacao="{{ $servidor->lotacao }}"
                         data-status="{{ $status }}">
                         
                        <div class="flex items-start gap-4">
                            <img src="{{ $avatarUrl }}" class="w-12 h-12 rounded-full object-cover border border-slate-100 shrink-0">
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate leading-none mb-1.5 font-sans">{{ $servidor->name }}</h4>
                                <p class="text-[10px] font-bold text-[#2563eb] uppercase tracking-widest font-mono truncate leading-tight mb-0.5">{{ $servidor->cargo }}</p>
                                <p class="text-[9.5px] text-slate-400 font-semibold truncate font-sans">{{ $servidor->lotacao }}</p>
                            </div>
                        </div>

                        <div class="flex items-end justify-between border-t border-slate-50 pt-4 mt-auto">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest font-mono block mb-1">Status</span>
                                @if($status === 'submitted')
                                    @php
                                        $finalScore = $evaluation->final_score ?? 0;
                                        $displayScore = number_format($finalScore * 20, 1);
                                    @endphp
                                    <span class="inline-flex items-center gap-1 text-[10px] bg-green-50 text-green-600 font-black px-2.5 py-0.5 rounded-lg border border-green-200 font-mono uppercase tracking-wider">
                                        ✓ {{ $displayScore }}/100
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] bg-slate-50 text-slate-500 font-bold px-2.5 py-0.5 rounded-lg border border-slate-200 uppercase font-mono tracking-wider">
                                        Pendente
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-1.5">
                                @if($status === 'submitted')
                                    <a href="{{ route('evaluation.create', $servidor->id) }}" class="px-4 py-2 bg-accent hover:bg-accent-hover text-white font-bold rounded-lg text-xs shadow-md shadow-blue-500/10 hover:-translate-y-0.5 transition-all duration-200">
                                        Reavaliar
                                    </a>
                                @else
                                    <a href="{{ route('evaluation.create', $servidor->id) }}" class="px-4 py-2 bg-accent hover:bg-accent-hover text-white font-bold rounded-lg text-xs shadow-md shadow-blue-500/10 hover:-translate-y-0.5 transition-all duration-200">
                                        Avaliar
                                    </a>
                                @endif
                                <a href="{{ route('admin.logs.index') }}?user={{ $servidor->id }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition-all duration-200">
                                    Diário
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        function filterServidores() {
            var searchQuery = $('#search-input').val().toLowerCase();
            var selectedLotacao = $('#lotacao-select').val();
            var selectedStatus = $('#status-select').val();

            $('#servidores-grid > div').each(function() {
                var name = $(this).data('name');
                var lotacao = $(this).data('lotacao');
                var status = $(this).data('status');

                var matchesSearch = name.indexOf(searchQuery) > -1;
                var matchesLotacao = selectedLotacao === '' || lotacao === selectedLotacao;
                var matchesStatus = selectedStatus === '' || status === selectedStatus;

                if (matchesSearch && matchesLotacao && matchesStatus) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('#search-input').on('keyup', filterServidores);
        $('#lotacao-select, #status-select').on('change', filterServidores);
    });
    </script>
    @endpush
</x-app-layout>
