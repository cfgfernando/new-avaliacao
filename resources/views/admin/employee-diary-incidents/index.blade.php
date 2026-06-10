<x-app-layout>
    @section('title', 'Diário de Bordo Funcional')

    <div class="space-y-8 animate-reveal-up">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Diário de Bordo Funcional</h1>
                <p class="text-slate-500 mt-1">Gestão de elogios, atitudes positivas e incidentes do servidor</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all flex items-center gap-2 shadow-xs uppercase tracking-wider select-none cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">file_download</span>
                    Exportar Relatório
                </button>
                <button onclick="openCreateModal()" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-2 uppercase tracking-wider select-none cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Registrar no Diário de Bordo
                </button>
            </div>
        </div>

        <!-- Bento Grid Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Stat Card 1: Total de Incidentes -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs transition-all hover:shadow-sm duration-300">
                <p class="text-slate-500 font-mono text-[10px] uppercase tracking-widest mb-1">Total de Registros</p>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-3xl font-mono font-bold text-slate-900">{{ $totalIncidents }}</h3>
                    <span class="text-emerald-600 text-xs font-mono font-bold flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[12px] font-bold">trending_up</span>
                        Ativos
                    </span>
                </div>
                <div class="mt-4 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200/20">
                    @php
                        $positivePct = $totalIncidents > 0 ? ($totalPositive / $totalIncidents) * 100 : 0;
                    @endphp
                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $positivePct }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 font-mono mt-2 uppercase tracking-tight">
                    {{ $totalPositive }} Positivos • {{ $totalNegative }} Negativos
                </p>
            </div>

            <!-- Stat Card 2: Impacto Médio -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs transition-all hover:shadow-sm duration-300">
                <p class="text-slate-500 font-mono text-[10px] uppercase tracking-widest mb-1">Média por Servidor</p>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-3xl font-mono font-bold text-slate-900">{{ $averageImpact }}</h3>
                    <span class="text-slate-400 text-xs font-mono">ocorrências</span>
                </div>
                <div class="mt-4 flex gap-1">
                    <div class="h-1.5 flex-1 bg-emerald-500 rounded-full"></div>
                    <div class="h-1.5 flex-1 bg-blue-600 rounded-full"></div>
                    <div class="h-1.5 flex-1 bg-rose-500 rounded-full"></div>
                </div>
                <p class="text-[10px] text-slate-400 font-mono mt-2 uppercase tracking-tight">
                    Média de diário consolidado do ciclo
                </p>
            </div>

            <!-- Stat Card 3: Sentiment Score -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs transition-all hover:shadow-sm duration-300 col-span-1 md:col-span-2 flex justify-between items-center">
                <div class="flex-1">
                    <p class="text-slate-500 font-mono text-[10px] uppercase tracking-widest mb-1">Performance Sentiment Score</p>
                    <div class="flex items-baseline gap-3">
                        <h3 class="text-3xl font-mono font-bold text-slate-900">{{ $sentimentScore }}/100</h3>
                        <span class="{{ $sentimentScore >= 60 ? 'text-emerald-600' : 'text-rose-600' }} text-xs font-mono font-bold flex items-center">
                            {{ $sentimentScore >= 60 ? 'Tendência Positiva' : 'Requer Atenção' }}
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-mono mt-2 uppercase tracking-tight">
                        Razão de incidentes positivos vs negativos registrados
                    </p>
                </div>
                <div class="w-32 h-16 shrink-0">
                    <svg class="w-full h-full" viewBox="0 0 100 40">
                        @php
                            // Desenha um gráfico coerente com o score
                            $yVal = 40 - ($sentimentScore * 0.35);
                        @endphp
                        <path d="M0 35 Q 25 25, 50 {{ $yVal + 5 }} T 100 {{ $yVal }}" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round"></path>
                        <path d="M0 35 Q 25 25, 50 {{ $yVal + 5 }} T 100 {{ $yVal }} L 100 40 L 0 40 Z" fill="#2563eb" fill-opacity="0.08"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Timeline and Listing -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Filtros e Pesquisa -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <form method="GET" action="{{ route('admin.employee-diary-incidents.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Pesquisar Servidor</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, matrícula ou termo..." class="input-neo !py-2">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Tipo de Registro</label>
                            <select name="type" class="input-neo !py-2" onchange="this.form.submit()">
                                <option value="">Todos</option>
                                <option value="positive" {{ request('type') === 'positive' ? 'selected' : '' }}>Positivo (Sucesso)</option>
                                <option value="negative" {{ request('type') === 'negative' ? 'selected' : '' }}>Negativo (Atenção)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Categoria</label>
                            <select name="category" class="input-neo !py-2" onchange="this.form.submit()">
                                <option value="">Todas</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Timeline de Incidentes</h2>
                    @if(request()->anyFilled(['search', 'type', 'category', 'employee_id']))
                        <a href="{{ route('admin.employee-diary-incidents.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 font-mono uppercase">
                            <span class="material-symbols-outlined text-[16px]">close</span> Limpar Filtros
                        </a>
                    @endif
                </div>

                <!-- Timeline List -->
                <div class="relative pl-8 space-y-6 before:content-[''] before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-200">
                    @forelse($incidents as $incident)
                        @php
                            $isPositive = $incident->type === 'positive';
                            $dotColor = $isPositive ? 'bg-emerald-500 ring-emerald-100' : 'bg-rose-500 ring-rose-100';
                            $badgeStyle = $isPositive 
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                                : 'bg-rose-50 text-rose-700 border-rose-150';
                        @endphp
                        <!-- Incident Item -->
                        <div class="group relative bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:shadow-sm transition-all duration-300">
                            <!-- Bullet indicator on the line -->
                            <div class="absolute -left-[29px] top-6 size-3 rounded-full {{ $dotColor }} ring-4 z-10"></div>
                            
                            <div class="flex justify-between items-start gap-4 mb-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded border {{ $badgeStyle }} uppercase">
                                        {{ $isPositive ? 'Positivo' : 'Negativo' }}
                                    </span>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 bg-slate-50 text-slate-600 rounded border border-slate-200 uppercase">
                                        {{ $incident->category }}
                                    </span>
                                    <span class="text-xs font-mono text-slate-400 font-semibold">
                                        {{ $incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('d/m/Y') : $incident->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest font-semibold">Registrado Por</p>
                                    <p class="text-xs font-bold text-slate-900">{{ $incident->reporter?->name ?? 'Sistema' }}</p>
                                </div>
                            </div>

                            <!-- Subject/Servidor -->
                            <div class="mb-3 p-3 bg-slate-50 rounded-xl border border-slate-200/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($incident->employee?->name ?? 'S', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800 leading-none">{{ $incident->employee?->name ?? 'Servidor Excluído' }}</p>
                                            <p class="text-[9px] font-mono text-slate-400 font-bold uppercase mt-1">Matrícula: {{ $incident->employee?->registration_number ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($incident->employee && $incident->employee->lotacao)
                                    <span class="text-[10.5px] font-bold text-slate-500 font-mono uppercase bg-white px-2 py-0.5 rounded border border-slate-200/50 shadow-xxs">
                                        {{ $incident->employee->lotacao }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-slate-600 leading-relaxed font-normal mb-4">
                                {{ $incident->description }}
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <div class="flex items-center gap-2">
                                    @if($incident->evidence_file)
                                        <a href="{{ asset('storage/' . $incident->evidence_file) }}" target="_blank" class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold rounded-lg text-[10.5px] transition-all flex items-center gap-1 border border-blue-200 select-none cursor-pointer">
                                            <span class="material-symbols-outlined text-[14px]">attachment</span>
                                            Evidência
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-mono italic">Sem arquivo de evidência</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="openEditModal({{ json_encode($incident) }})" class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 font-bold rounded-lg text-[10.5px] border border-slate-200 transition-all cursor-pointer">
                                        Editar
                                    </button>
                                    <form method="POST" action="{{ route('admin.employee-diary-incidents.destroy', $incident->id) }}" onsubmit="return confirm('Deseja realmente excluir este incidente do diário de bordo?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 font-bold rounded-lg text-[10.5px] border border-rose-150 transition-all cursor-pointer">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-xs">
                            <span class="material-symbols-outlined text-slate-350 text-[48px] mb-3">history_edu</span>
                            <p class="text-sm font-bold text-slate-700">Nenhum incidente crítico registrado</p>
                            <p class="text-xs text-slate-400 mt-1">Utilize o botão superior para registrar as ocorrências observadas do ciclo.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Paginação Laravel -->
                <div class="mt-6">
                    {{ $incidents->links() }}
                </div>
            </div>

            <!-- Right Side: Sidebar Insights -->
            <div class="lg:col-span-4 space-y-6">
                <!-- AI Insights Widget -->
                <div class="bg-[#0f172a] text-white p-6 rounded-2xl shadow-md border border-slate-850 overflow-hidden relative group">
                    <div class="absolute -right-12 -top-12 size-36 bg-blue-600/10 rounded-full blur-2xl transition-transform group-hover:scale-150 duration-1000"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-blue-500 text-[18px]">bolt</span>
                            <h3 class="text-xs font-bold uppercase tracking-widest font-mono text-blue-400">Insight GovSense AI</h3>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed mb-6">
                            Detectamos que servidores com mais de <span class="text-white font-bold">2 incidentes negativos</span> cadastrados no ciclo ativo possuem uma queda projetada de <span class="text-rose-400 font-bold">14.5%</span> no score final consolidado BARS.
                        </p>
                        <div class="space-y-4">
                            <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                                <p class="text-[10px] font-mono text-slate-400 uppercase mb-2">Recomendação Estratégica</p>
                                <p class="text-xs text-white leading-snug">Sugerimos realizar alinhamentos preventivos de feedback (PDI) com os servidores sob alertas negativos frequentes antes do fechamento do ciclo.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evolution of Cycle -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 tracking-tight">Resumo de Registros</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Incidentes Positivos</span>
                            <span class="text-xs font-mono font-bold text-emerald-600">{{ $totalPositive }} ({{ $totalIncidents > 0 ? round(($totalPositive / $totalIncidents) * 100) : 0 }}%)</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200/20">
                            <div class="h-full bg-emerald-500" style="width: {{ $totalIncidents > 0 ? ($totalPositive / $totalIncidents) * 100 : 0 }}%"></div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Incidentes Negativos</span>
                            <span class="text-xs font-mono font-bold text-rose-600">{{ $totalNegative }} ({{ $totalIncidents > 0 ? round(($totalNegative / $totalIncidents) * 100) : 0 }}%)</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200/20">
                            <div class="h-full bg-rose-500" style="width: {{ $totalIncidents > 0 ? ($totalNegative / $totalIncidents) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- FAQ BARS -->
                <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-slate-400 mb-2" style="font-size: 32px;">help_center</span>
                    <p class="text-xs font-bold text-slate-700">Dúvidas sobre o Diário BARS?</p>
                    <p class="text-[10px] text-slate-500 mt-1 leading-snug">Incidentes críticos servem como justificativa legal e formal para escores extremos (níveis 1, 2 e 5).</p>
                    <a href="{{ route('admin.evaluation-questions.index') }}" class="text-[10px] font-bold text-blue-600 mt-3 hover:underline">Configurar Competências BARS</a>
                </div>
            </div>
        </div>
    </div>

    <!-- CREATE MODAL (Dinâmico com jQuery) -->
    <div id="create-modal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-lg w-full overflow-hidden animate-reveal-up">
            <div class="px-6 py-4 border-b border-slate-150 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-[20px]">history_edu</span>
                    Registrar no Diário de Bordo
                </h3>
                <button onclick="closeCreateModal()" class="text-slate-450 hover:text-slate-700 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form id="create-form" method="POST" action="{{ route('admin.employee-diary-incidents.store') }}" enctype="multipart/form-data" hx-boost="false" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Servidor Envolvido *</label>
                    <select name="employee_id" id="create-employee-select" class="input-neo !py-2.5">
                        <option value="">Selecione o Servidor</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} (Matrícula: {{ $emp->registration_number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Tipo de Registro *</label>
                        <select name="type" required class="input-neo !py-2.5">
                            <option value="positive">Positivo (Sucesso)</option>
                            <option value="negative">Negativo (Atenção)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Data do Ocorrido *</label>
                        <input type="date" name="incident_date" required value="{{ date('Y-m-d') }}" class="input-neo !py-2">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Categoria / Competência *</label>
                    <input type="text" name="category" required placeholder="Ex: Iniciativa, Disciplina, Ética..." list="category-options" class="input-neo !py-2.5">
                    <datalist id="category-options">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"></option>
                        @endforeach
                        <option value="Iniciativa"></option>
                        <option value="Disciplina"></option>
                        <option value="Ética"></option>
                        <option value="Responsabilidade"></option>
                        <option value="Trabalho em Equipe"></option>
                        <option value="Pontualidade"></option>
                    </datalist>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Descrição Detalhada do Comportamento *</label>
                    <textarea name="description" required rows="4" placeholder="Descreva de forma clara e objetiva o comportamento observado, contendo datas, referências de processos ou ações práticas..." class="input-neo !py-2"></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Arquivo de Evidência (Opcional - Máx: 5MB)</label>
                    <input type="file" name="evidence_file" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-150">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-all cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-md shadow-blue-500/10 hover:bg-blue-700 transition-all cursor-pointer">
                        Salvar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL (Dinâmico com jQuery) -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-lg w-full overflow-hidden animate-reveal-up">
            <div class="px-6 py-4 border-b border-slate-150 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-[20px]">edit_note</span>
                    Editar Registro do Diário
                </h3>
                <button onclick="closeEditModal()" class="text-slate-450 hover:text-slate-700 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form id="edit-form" method="POST" enctype="multipart/form-data" hx-boost="false" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Servidor Envolvido *</label>
                    <select name="employee_id" id="edit-employee-id" class="input-neo !py-2.5">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} (Matrícula: {{ $emp->registration_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Tipo de Registro *</label>
                        <select name="type" id="edit-type" required class="input-neo !py-2.5">
                            <option value="positive">Positivo (Sucesso)</option>
                            <option value="negative">Negativo (Atenção)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Data do Ocorrido *</label>
                        <input type="date" name="incident_date" id="edit-incident-date" required class="input-neo !py-2">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Categoria / Competência *</label>
                    <input type="text" name="category" id="edit-category" required placeholder="Ex: Iniciativa, Disciplina, Ética..." list="category-options" class="input-neo !py-2.5">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Descrição Detalhada do Comportamento *</label>
                    <textarea name="description" id="edit-description" required rows="4" placeholder="Descreva o comportamento..." class="input-neo !py-2"></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 block">Arquivo de Evidência (Enviar novo substitui o anterior)</label>
                    <input type="file" name="evidence_file" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 font-mono mt-1" id="edit-evidence-status"></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-150">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-all cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-md shadow-blue-500/10 hover:bg-blue-700 transition-all cursor-pointer">
                        Atualizar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Select2 (Local Assets) -->
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    
    <style>
        /* Customização Estética do Select2 de acordo com o Design System */
        .select2-container--default .select2-selection--single {
            background-color: rgba(248, 250, 252, 0.5) !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            height: 46px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            padding-left: 16px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 12px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15) !important;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
            background-color: #ffffff !important;
            z-index: 9999 !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            outline: none !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15) !important;
        }
        .select2-results__option {
            font-size: 13px !important;
            font-weight: 500 !important;
            padding: 8px 16px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }
    </style>

    <script>
        function openCreateModal() {
            $('#create-modal').removeClass('hidden').addClass('flex');
            
            // Destruir instância anterior do Select2 se já existir para evitar bugs de reinicialização
            if ($('#create-employee-select').hasClass("select2-hidden-accessible")) {
                $('#create-employee-select').select2('destroy');
            }

            // Recalcular largura e foco ao abrir
            $('#create-employee-select').select2({
                dropdownParent: $('#create-modal'),
                width: '100%',
                minimumInputLength: 3,
                placeholder: 'Digite nome ou matrícula...',
                language: {
                    inputTooShort: function() {
                        return "Digite pelo menos 3 caracteres do nome ou matrícula";
                    },
                    noResults: function() {
                        return "Nenhum servidor encontrado";
                    }
                }
            });
        }

        function closeCreateModal() {
            $('#create-modal').removeClass('flex').addClass('hidden');
        }

        function openEditModal(incident) {
            var actionUrl = "{{ route('admin.employee-diary-incidents.update', ':id') }}".replace(':id', incident.id);
            $('#edit-form').attr('action', actionUrl);

            // Destruir instância anterior do Select2 se já existir para evitar bugs de reinicialização
            if ($('#edit-employee-id').hasClass("select2-hidden-accessible")) {
                $('#edit-employee-id').select2('destroy');
            }

            // Inicializar/Recalcular Select2 para Edição antes de setar o valor
            $('#edit-employee-id').select2({
                dropdownParent: $('#edit-modal'),
                width: '100%',
                minimumInputLength: 3,
                placeholder: 'Digite nome ou matrícula...',
                language: {
                    inputTooShort: function() {
                        return "Digite pelo menos 3 caracteres do nome ou matrícula";
                    },
                    noResults: function() {
                        return "Nenhum servidor encontrado";
                    }
                }
            });

            $('#edit-employee-id').val(incident.employee_id).trigger('change');
            $('#edit-type').val(incident.type);
            
            // Tratar formato da data
            var dateVal = incident.incident_date ? incident.incident_date.substring(0, 10) : '';
            $('#edit-incident-date').val(dateVal);
            
            $('#edit-category').val(incident.category);
            $('#edit-description').val(incident.description);

            if (incident.evidence_file) {
                $('#edit-evidence-status').text("Já possui arquivo de evidência vinculado.");
            } else {
                $('#edit-evidence-status').text("Nenhum arquivo de evidência vinculado.");
            }

            $('#edit-modal').removeClass('hidden').addClass('flex');
        }

        function closeEditModal() {
            $('#edit-modal').removeClass('flex').addClass('hidden');
        }

        $(document).ready(function() {
            // Foco automático do campo de busca do Select2 ao ser aberto
            $(document).on('select2:open', function() {
                setTimeout(function() {
                    var searchField = document.querySelector('.select2-search__field');
                    if (searchField) searchField.focus();
                }, 50);
            });

            // Validação customizada no envio do formulário de criação
            $('#create-form').on('submit', function(e) {
                var employeeSelect = $('#create-employee-select');
                if (!employeeSelect.val()) {
                    e.preventDefault();
                    
                    // Destacar a borda do Select2 em vermelho
                    var select2Container = employeeSelect.next('.select2-container');
                    select2Container.find('.select2-selection').css('border-color', '#ef4444');
                    
                    // Abrir o Select2 para digitação imediata
                    employeeSelect.select2('open');
                    return false;
                }
            });

            // Limpar destaque vermelho ao selecionar um valor na criação
            $('#create-employee-select').on('change', function() {
                if ($(this).val()) {
                    var select2Container = $(this).next('.select2-container');
                    select2Container.find('.select2-selection').css('border-color', '');
                }
            });

            // Validação customizada no envio do formulário de edição
            $('#edit-form').on('submit', function(e) {
                var employeeSelect = $('#edit-employee-id');
                if (!employeeSelect.val()) {
                    e.preventDefault();
                    
                    // Destacar a borda do Select2 em vermelho
                    var select2Container = employeeSelect.next('.select2-container');
                    select2Container.find('.select2-selection').css('border-color', '#ef4444');
                    
                    employeeSelect.select2('open');
                    return false;
                }
            });

            // Limpar destaque vermelho na edição ao selecionar um valor
            $('#edit-employee-id').on('change', function() {
                if ($(this).val()) {
                    var select2Container = $(this).next('.select2-container');
                    select2Container.find('.select2-selection').css('border-color', '');
                }
            });

            // Micro-interações de botões
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
</x-app-layout>
