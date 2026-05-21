@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-3xl font-bold text-[#0f172b] tracking-tight">Parametrização BARS & Competências</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Defina os pesos metodológicos, notas de corte e cadastre competências com escala BARS assistida por IA.</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Painel Geral
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-100 flex items-center gap-3 font-semibold text-sm animate-fade-in shadow-sm">
            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                <i class="fas fa-check"></i>
            </span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl border border-rose-100 flex items-center gap-3 font-semibold text-sm animate-fade-in shadow-sm">
            <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center text-xs">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Column: Competências Banco (8/12) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Filter Bar -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                <form action="{{ route('admin.evaluation-questions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <input type="hidden" name="cycle_id" value="{{ $activeCycle->id ?? '' }}">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Grupo Funcional</label>
                        <select name="group" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-0 outline-none">
                            <option value="geral" {{ request('group') === 'geral' ? 'selected' : '' }}>Quadro Geral</option>
                            <option value="saude" {{ request('group') === 'saude' ? 'selected' : '' }}>Saúde</option>
                            <option value="guarda" {{ request('group') === 'guarda' ? 'selected' : '' }}>Guarda Municipal</option>
                            <option value="educacao" {{ request('group') === 'educacao' ? 'selected' : '' }}>Educação</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Categoria</label>
                        <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-0 outline-none">
                            <option value="">Todas</option>
                            @php
                                $cats = [
                                    'assiduidade' => 'Assiduidade',
                                    'disciplina' => 'Disciplina',
                                    'iniciativa' => 'Iniciativa',
                                    'responsabilidade' => 'Responsabilidade',
                                    'cooperacao' => 'Cooperação',
                                    'qualidade' => 'Qualidade',
                                    'desenvolvimento_rh' => 'Desenvolvimento RH',
                                    'avaliacao_usuario' => 'Avaliação pelo Usuário',
                                ];
                            @endphp
                            @foreach($cats as $key => $lbl)
                                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome da competência..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-0 outline-none">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-md shadow-blue-500/10 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        @if(request()->anyFilled(['category', 'search']) || request('group') !== 'geral')
                            <a href="{{ route('admin.evaluation-questions.index', ['cycle_id' => $activeCycle->id ?? '']) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 p-2.5 rounded-lg transition-all flex items-center justify-center" title="Limpar Filtros">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Grid de Competências BARS -->
            <div class="space-y-4">
                <div class="flex justify-between items-center px-1">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Competências Encontradas ({{ $questions->total() }})</span>
                </div>

                @if($questions->isEmpty())
                    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-folder-open text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Nenhuma competência cadastrada</h3>
                        <p class="text-xs text-slate-500 mt-1">Utilize os filtros acima ou crie uma nova competência no formulário abaixo.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($questions as $question)
                            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                                <!-- Top Bar Card -->
                                <div class="flex justify-between items-start gap-4">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap gap-1.5 items-center">
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">
                                                {{ $question->group_type === 'geral' ? 'Quadro Geral' : ucfirst($question->group_type) }}
                                            </span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-200">
                                                {{ $cats[$question->category] ?? $question->category }}
                                            </span>
                                            @if(!$question->is_active)
                                                <span class="text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 px-2 py-0.5 rounded border border-rose-200">
                                                    Inativo
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-base font-bold text-slate-900 mt-2">{{ $question->text }}</h3>
                                    </div>
                                    
                                    <div class="flex gap-1.5 shrink-0">
                                        <a href="{{ route('admin.evaluation-questions.edit', $question) }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:border-blue-500 text-slate-500 hover:text-blue-600 flex items-center justify-center transition-all bg-white" title="Editar">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.evaluation-questions.toggle', $question) }}" method="POST" class="inline" onsubmit="return confirm('Alterar status desta competência?')">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 rounded-lg border border-slate-200 hover:border-blue-500 text-slate-500 hover:text-blue-600 flex items-center justify-center transition-all bg-white" title="{{ $question->is_active ? 'Desativar' : 'Ativar' }}">
                                                <i class="fas fa-toggle-{{ $question->is_active ? 'on text-emerald-500' : 'off' }} text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.evaluation-questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta competência permanentemente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg border border-slate-200 hover:border-rose-500 text-slate-500 hover:text-rose-600 flex items-center justify-center transition-all bg-white" title="Excluir">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Escala BARS (Âncoras de Comportamento) -->
                                <div class="mt-5 pt-4 border-t border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Âncoras de Comportamento (Escala BARS 1 a 5)</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2.5">
                                        @php
                                            $anchors = $question->barsAnchors->sortBy('score');
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @php
                                                $anchor = $anchors->firstWhere('score', $i);
                                                $anchorText = $anchor ? $anchor->behavioral_description : 'Não cadastrado.';
                                                $bgClass = [
                                                    1 => 'bg-rose-50 border-rose-100 text-rose-800',
                                                    2 => 'bg-amber-50 border-amber-100 text-amber-800',
                                                    3 => 'bg-blue-50 border-blue-100 text-blue-800',
                                                    4 => 'bg-indigo-50 border-indigo-100 text-indigo-800',
                                                    5 => 'bg-emerald-50 border-emerald-100 text-emerald-800'
                                                ][$i];
                                            @endphp
                                            <div class="p-3 rounded-lg border text-[11px] leading-relaxed flex flex-col justify-between h-full min-h-[90px] transition-all hover:scale-[1.02] {{ $bgClass }}">
                                                <div class="font-bold uppercase tracking-wider text-[10px] mb-1.5 opacity-90 font-mono">Nível {{ $i }}</div>
                                                <div class="font-medium flex-1 line-clamp-4" title="{{ $anchorText }}">{{ $anchorText }}</div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $questions->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Definir Pesos/Metas (4/12) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">
                 <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-sliders-h"></i>
                    </span>
                    <div>
                                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Definir Pesos / Metas</h2>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Parâmetros de cálculo de desempenho.</p>
                                    </div>
                </div>

                @if($activeCycle)
                    @php
                        $weights = $activeCycle->weights ?? [];
                        $okrWeight = $weights['okr'] ?? 50;
                        $barsWeight = $weights['bars'] ?? 50;
                    @endphp
                    <!-- Seletor de Ciclo para Edição de Pesos -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Ciclo de Avaliação</label>
                        <select id="cycle-selector" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-0 outline-none">
                            @foreach($cycles as $c)
                                <option value="{{ $c->id }}" {{ $activeCycle->id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <form action="{{ route('admin.evaluation-cycles.update-weights', $activeCycle->id) }}" method="POST" id="form-weights" class="space-y-5">
                        @csrf
                        
                        <!-- Slider Dinâmico OKR vs BARS -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-xs font-bold">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Distribuição de Peso</span>
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-bold font-mono" id="total-soma-badge">Soma: 100%</span>
                            </div>
                            
                            <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 space-y-4">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-blue-600 font-bold">OKR (Metas): <span id="okr-val-badge" class="text-sm font-bold font-mono">{{ $okrWeight }}%</span></span>
                                    <span class="text-slate-700 font-bold">BARS (Competências): <span id="bars-val-badge" class="text-sm font-bold font-mono">{{ $barsWeight }}%</span></span>
                                </div>
                                
                                <input type="range" id="weight-range-slider" name="okr_weight" min="0" max="100" value="{{ $okrWeight }}" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600 focus:ring-0 outline-none">
                                <input type="hidden" name="bars_weight" id="bars-weight-hidden" value="{{ $barsWeight }}">
                            </div>
                        </div>

                        <!-- Nota Mínima de Corte -->
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Nota de Corte Mínima (Escala 1.00 a 5.00)</label>
                            <div class="relative">
                                <input type="number" step="0.1" min="1" max="5" name="cutoff_score" value="{{ number_format($activeCycle->cutoff_score ?? 3.0, 1, '.', '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-3 pr-10 py-2 text-sm font-bold text-slate-800 focus:border-blue-500 focus:ring-0 outline-none font-mono">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-exclamation-circle text-xs"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1.5">Servidores com nota final inferior serão enquadrados em plano de melhoria.</p>
                        </div>

                        <!-- Botão de Ação -->
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-4 rounded-lg shadow-md shadow-blue-500/10 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Salvar Parâmetros
                        </button>
                    </form>
                @else
                    <div class="text-center p-4 text-slate-400 text-xs font-semibold">
                        Nenhum ciclo cadastrado para configurar pesos.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bottom Section: Criar Competência BARS (IA Assistida) -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-4 gap-4">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-magic"></i>
                </span>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Criar Competência Assistida por IA</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Preencha o título e gere âncoras comportamentais BARS instantâneas e personalizadas.</p>
                </div>
            </div>
            <button type="button" id="btn-gerar-ia" class="self-start md:self-auto bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-lg border border-blue-200 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-sparkles"></i> Gerar com IA
            </button>
        </div>

        <form action="{{ route('admin.evaluation-questions.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Inputs Básicos -->
                <div class="md:col-span-4 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Grupo Funcional</label>
                        <select name="group_type" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-0 outline-none">
                            <option value="geral">Quadro Geral</option>
                            <option value="saude">Saúde</option>
                            <option value="guarda">Guarda Municipal</option>
                            <option value="educacao">Educação</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Categoria</label>
                        <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-0 outline-none">
                            @foreach($cats as $key => $lbl)
                                <option value="{{ $key }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Título da Competência</label>
                        <textarea name="text" id="ia-question-text" rows="4" placeholder="Ex: Comunicação Interpessoal Eficiente" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm font-medium text-slate-800 focus:border-blue-500 focus:ring-0 outline-none" required></textarea>
                    </div>
                </div>

                <!-- Inputs das Âncoras -->
                <div class="md:col-span-8 space-y-4">
                    <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono flex items-center gap-2">
                        <i class="fas fa-list-ol"></i> Escala Comportamental (Níveis 1 a 5)
                    </h3>
                    
                    <div class="space-y-3.5" id="anchors-wrapper">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $lbl = [
                                    1 => '1 — Ruim / Insatisfatório',
                                    2 => '2 — Regular / Abaixo da média',
                                    3 => '3 — Bom / Dentro do esperado',
                                    4 => '4 — Muito Bom / Supera expectativas',
                                    5 => '5 — Excepcional / Excelente referência'
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
                                <div class="absolute left-3 flex items-center gap-2 pointer-events-none">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $colorDot }}"></span>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-mono">{{ $lbl }}</span>
                                </div>
                                <input type="text" name="anchor_{{ $i }}" id="anchor-input-{{ $i }}" placeholder="Descreva o comportamento correspondente para este nível..." class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-44 pr-3 py-2.5 text-xs font-medium text-slate-700 focus:border-blue-500 focus:ring-0 outline-none" required>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Submissão do Formulário -->
            <div class="border-t border-slate-100 pt-5 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-lg shadow-md shadow-blue-500/10 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Homologar e Salvar Competência BARS
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 1. Slider de Pesos Dinâmico
    $('#weight-range-slider').on('input', function() {
        var okrVal = parseInt($(this).val());
        var barsVal = 100 - okrVal;
        
        $('#okr-val-badge').text(okrVal + '%');
        $('#bars-val-badge').text(barsVal + '%');
        $('#bars-weight-hidden').val(barsVal);
    });

    // 2. Troca de Ciclo no Dropdown recarrega a página com o ciclo_id
    $('#cycle-selector').on('change', function() {
        var cycleId = $(this).val();
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('cycle_id', cycleId);
        window.location.href = currentUrl.toString();
    });

    // 3. Geração de Âncoras via IA (Simulação Avançada)
    $('#btn-gerar-ia').on('click', function() {
        var title = $('#ia-question-text').val().trim();
        
        if (!title) {
            alert('Por favor, preencha o título da competência para que a IA possa gerar as âncoras comportamentais.');
            $('#ia-question-text').focus();
            return;
        }

        var $btn = $(this);
        var originalHtml = $btn.html();
        
        // Efeito visual de carregamento
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
                    // Preencher de forma animada/progresiva
                    var delay = 0;
                    Object.keys(response.anchors).forEach(function(key) {
                        setTimeout(function() {
                            var text = response.anchors[key];
                            var $input = $('#anchor-input-' + key);
                            $input.val('').removeClass('opacity-50');
                            
                            // Efeito simples de digitação
                            var charIndex = 0;
                            function typeWriter() {
                                if (charIndex < text.length) {
                                    $input.val($input.val() + text.charAt(charIndex));
                                    charIndex++;
                                    setTimeout(typeWriter, 5); // velocidade da digitação
                                }
                            }
                            typeWriter();
                        }, delay);
                        delay += 350; // Atraso de preenchimento entre inputs
                    });
                } else {
                    alert('Erro ao tentar simular geração de âncoras.');
                    resetAnchorInputs();
                }
            },
            error: function() {
                alert('Ocorreu um erro ao tentar se conectar com a simulação de inteligência artificial.');
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
