@extends('layouts.app')

@section('title', 'Inicializar Avaliação - SAD-BARS')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-3xl font-bold text-[#0f172b] tracking-tight">Inicializar Nova Avaliação Regulamentar</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Configure os parâmetros do processo avaliativo. O painel de apoio à direita carregará o histórico funcional do servidor.</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Voltar ao Dashboard
            </a>
        </div>
    </div>

    <!-- Main Content Grid (12 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        
        <!-- Left Column: Formulário de Abertura (7/12) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm h-full flex flex-col justify-between space-y-6">
                
                <form action="{{ route('evaluations.setup.store') }}" method="POST" id="evaluation-setup-form" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- 1. Ciclo Avaliativo -->
                        <div>
                            <label for="cycle_id" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Ciclo Avaliativo <span class="text-rose-500">*</span></label>
                            @if(auth()->user()->isAdmin())
                                <select name="cycle_id" id="cycle_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-0 outline-none">
                                    @foreach($cycles as $cycle)
                                        <option value="{{ $cycle->id }}" {{ $cycle->status === 'active' ? 'selected' : '' }}>
                                            {{ $cycle->name }} {{ $cycle->status === 'active' ? '(Ciclo Vigente)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                @php $activeCycle = $cycles->first(); @endphp
                                <input type="hidden" name="cycle_id" id="cycle_id" value="{{ $activeCycle?->id }}">
                                <input type="text" value="{{ $activeCycle?->name ?? 'Nenhum ciclo ativo' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-500 cursor-not-allowed" readonly>
                                <p class="mt-1 text-[10px] text-slate-400">Restrito ao ciclo avaliativo vigente para a sua Chefia Imediata.</p>
                            @endif
                            @error('cycle_id')
                                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 2. Secretaria / Lotação -->
                        <div>
                            <label for="lotacao" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Secretaria / Lotação de Exercício <span class="text-rose-500">*</span></label>
                            @if(auth()->user()->isAdmin())
                                <select name="lotacao" id="lotacao" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-0 outline-none">
                                    <option value="">Selecione uma secretaria/lotação...</option>
                                    @foreach($lotacoes as $lot)
                                        <option value="{{ $lot }}">{{ $lot }}</option>
                                    @endforeach
                                </select>
                            @else
                                @php $chefiaLotacao = $lotacoes->first(); @endphp
                                <input type="hidden" name="lotacao" id="lotacao" value="{{ $chefiaLotacao }}">
                                <input type="text" value="{{ $chefiaLotacao }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-500 cursor-not-allowed" readonly>
                                <p class="mt-1 text-[10px] text-slate-400">Limitado à sua secretaria/lotação funcional.</p>
                            @endif
                            @error('lotacao')
                                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 3. Servidor Avaliado -->
                        <div>
                            <label for="evaluated_id" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Servidor Avaliado <span class="text-rose-500">*</span></label>
                            <select name="evaluated_id" id="evaluated_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-0 outline-none" disabled>
                                <option value="">Selecione primeiro uma lotação...</option>
                            </select>
                            <p id="loading-servidores" class="mt-1.5 text-xs text-blue-600 font-medium hidden items-center gap-1.5 animate-pulse">
                                <i class="fas fa-spinner fa-spin"></i> Carregando servidores ativos da lotação...
                            </p>
                            @error('evaluated_id')
                                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 4. Categoria Regulamentar (5 Cards de Rádio Customizados) -->
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-3">Categoria Regulamentar (Escala BARS correspondente) <span class="text-rose-500">*</span></label>
                            <input type="hidden" name="categoria" id="categoria" value="">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="radio-categoria-container">
                                <!-- Card 1: Geral -->
                                <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-geral">
                                    <input type="radio" name="temp_categoria" value="geral" class="sr-only radio-categoria">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                            <i class="fas fa-users text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Quadro Geral</h4>
                                            <p class="text-[10px] text-slate-500 mt-1 leading-normal">Carreira técnica geral, administrativa e operacional.</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Card 2: Saúde -->
                                <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-saude">
                                    <input type="radio" name="temp_categoria" value="saude" class="sr-only radio-categoria">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                            <i class="fas fa-heartbeat text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Saúde Pública</h4>
                                            <p class="text-[10px] text-slate-500 mt-1 leading-normal">Médicos, enfermeiros, técnicos e agentes de saúde.</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Card 3: Guarda -->
                                <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-guarda">
                                    <input type="radio" name="temp_categoria" value="guarda" class="sr-only radio-categoria">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                            <i class="fas fa-shield-alt text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Segurança</h4>
                                            <p class="text-[10px] text-slate-500 mt-1 leading-normal">GMs, patrulheiros, inspetores e agentes urbanos.</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Card 4: Educação -->
                                <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-educacao">
                                    <input type="radio" name="temp_categoria" value="educacao" class="sr-only radio-categoria">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                            <i class="fas fa-graduation-cap text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Educação Básica</h4>
                                            <p class="text-[10px] text-slate-500 mt-1 leading-normal">Professores, educadores e pedagogos escolares.</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Card 5: Gestão Governamental (Mapeado como 'geral') -->
                                <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-gestao">
                                    <input type="radio" name="temp_categoria" value="geral_gestao" class="sr-only radio-categoria">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                            <i class="fas fa-briefcase text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Gestão e PEGP</h4>
                                            <p class="text-[10px] text-slate-500 mt-1 leading-normal">Especialistas em gestão pública e executivos.</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('categoria')
                                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 5. Avaliador Portador de Fé Pública -->
                        <div class="pt-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Avaliador Responsável (Fé Pública)</label>
                            <div class="flex items-center gap-3 bg-slate-50 rounded-xl border border-slate-200 p-4">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 border border-blue-200 font-bold text-xs flex items-center justify-center font-mono">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'RH', 0, 2)) }}
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">{{ Auth::user()->name ?? 'Departamento de RH' }}</span>
                                    <span class="text-[10px] text-slate-500 block uppercase mt-0.5 font-mono">Matrícula: {{ Auth::user()->registration_number ?? 'CAPD.2026.01' }} • Status: Portador de Fé Pública</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-500 bg-white hover:bg-slate-50 transition-all">
                            Cancelar
                        </a>
                        <button type="submit" id="btn-submit" class="px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-blue-500/10" disabled>
                            <i class="fas fa-play"></i> Iniciar Avaliação
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Painel de Apoio à Decisão (5/12) -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm h-full flex flex-col justify-between relative overflow-hidden min-h-[450px]">
                
                <!-- Estado Vazio: Nenhum servidor selecionado -->
                <div id="apoio-estado-vazio" class="flex flex-col items-center justify-center text-center my-auto py-12 space-y-4">
                    <div class="w-20 h-20 bg-slate-50 text-slate-350 rounded-full border border-slate-150 flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl">contact_page</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Apoio à Decisão (Histórico)</h3>
                        <p class="text-xs text-slate-450 mt-1 max-w-[280px] mx-auto leading-relaxed">Selecione uma secretaria e um servidor à esquerda para carregar o histórico de metas e incidentes funcionais.</p>
                    </div>
                </div>

                <!-- Detalhes do Servidor (Carregados via AJAX) -->
                <div id="apoio-servidor-info" class="hidden space-y-6 flex-1 flex flex-col justify-between">
                    <!-- Top Info Card -->
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                        <img id="servidor-avatar" src="" alt="Avatar" class="w-14 h-14 rounded-full border border-slate-200 shadow-sm shrink-0">
                        <div class="min-w-0">
                            <h3 id="servidor-nome" class="text-base font-bold text-slate-900 truncate">Nome do Servidor</h3>
                            <p id="servidor-cargo" class="text-xs text-slate-500 font-semibold truncate mt-0.5">Cargo do Servidor</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span id="servidor-lotacao" class="text-[10px] text-slate-400 font-bold uppercase truncate">Lotação</span>
                            </div>
                        </div>
                    </div>

                    <!-- Seção de Metas Pactuadas (OKR) -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono flex items-center gap-2">
                            <i class="fas fa-bullseye text-blue-500"></i> Metas Pactuadas e Atingimento (Ciclo)
                        </h4>
                        
                        <div id="servidor-metas-container" class="space-y-3">
                            <!-- Injetado via jQuery -->
                        </div>
                    </div>

                    <!-- Seção de Incidentes Críticos (Diário Funcional) -->
                    <div class="space-y-3 pt-2">
                        <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono flex items-center gap-2">
                            <i class="fas fa-book-open text-blue-500"></i> Incidentes Críticos (Diário Funcional)
                        </h4>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Incidentes Positivos -->
                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest font-mono">Positivos</span>
                                    <span class="text-xs text-slate-500 block leading-tight font-medium">Elogios & Destaques</span>
                                </div>
                                <span id="incidente-positivo-count" class="w-10 h-10 rounded-full bg-emerald-500 text-white font-bold text-sm flex items-center justify-center shadow-sm font-mono">
                                    0
                                </span>
                            </div>

                            <!-- Incidentes Negativos -->
                            <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-bold text-rose-700 uppercase tracking-widest font-mono">Negativos</span>
                                    <span class="text-xs text-slate-500 block leading-tight font-medium">Falhas & Atrasos</span>
                                </div>
                                <span id="incidente-negativo-count" class="w-10 h-10 rounded-full bg-rose-500 text-white font-bold text-sm flex items-center justify-center shadow-sm font-mono">
                                    0
                                </span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i> Os incidentes críticos registrados servem como subsídio legal direto para justificar a pontuação nas âncoras BARS de comportamento.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // 1. Controle dos Cards de Rádio da Categoria
    $('.radio-categoria').on('change', function() {
        // Remove destaques de todos os labels do grupo
        $('.radio-categoria').closest('label').removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
        
        // Adiciona destaque ao label ativo
        if ($(this).is(':checked')) {
            $(this).closest('label').removeClass('border-slate-200').addClass('border-blue-500 ring-2 ring-blue-100');
            
            // Tratamento do valor especial de gestão (que no backend mapeia para 'geral')
            var val = $(this).val();
            if (val === 'geral_gestao') {
                $('#categoria').val('geral');
            } else {
                $('#categoria').val(val);
            }
        }
        checkFormValidity();
    });

    // 2. Validador do Formulário
    function checkFormValidity() {
        var cycle = $('#cycle_id').val();
        var lotacao = $('#lotacao').val();
        var servidor = $('#evaluated_id').val();
        var categoria = $('#categoria').val();
        
        if (cycle && lotacao && servidor && categoria) {
            $('#btn-submit').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        } else {
            $('#btn-submit').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        }
    }

    // 3. Dropdown Dinâmico de Servidores por Lotação
    function loadServidores(lotacaoVal) {
        if (!lotacaoVal) {
            $('#evaluated_id').html('<option value="">Selecione primeiro uma lotação...</option>').prop('disabled', true);
            resetApoio();
            checkFormValidity();
            return;
        }

        $('#loading-servidores').removeClass('hidden');
        $('#evaluated_id').html('<option value="">Carregando servidores...</option>').prop('disabled', true);
        
        var url = "{{ parse_url(route('api.lotacao.servidores'), PHP_URL_PATH) }}?lotacao=" + encodeURIComponent(lotacaoVal);

        $.getJSON(url, function(data) {
            var options = '<option value="">Selecione um servidor...</option>';
            
            if(data.length === 0) {
                options = '<option value="">Nenhum servidor ativo encontrado nesta lotação</option>';
            } else {
                $.each(data, function(index, servidor) {
                    var padBadge = servidor.has_active_pad ? ' [PAD ATIVO — AVALIAÇÃO BLOQUEADA]' : '';
                    var isDisabled = servidor.has_active_pad ? 'disabled class="text-rose-500 bg-rose-50/50"' : '';
                    
                    options += '<option value="' + servidor.id + '" data-group="' + servidor.evaluation_group + '" data-pad="' + servidor.has_active_pad + '" ' + isDisabled + '>';
                    options += servidor.name + ' (' + servidor.cargo + ')' + padBadge;
                    options += '</option>';
                });
            }
            
            $('#evaluated_id').html(options).prop('disabled', data.length === 0);
            $('#loading-servidores').addClass('hidden');
            checkFormValidity();
        }).fail(function() {
            $('#evaluated_id').html('<option value="">Erro ao buscar servidores ativos</option>').prop('disabled', true);
            $('#loading-servidores').addClass('hidden');
            checkFormValidity();
        });
    }

    // Evento change da lotação
    $('#lotacao').on('change', function() {
        loadServidores($(this).val());
    });

    // 4. Seleção de Servidor e Requisição AJAX de Detalhes (Painel de Apoio)
    $('#evaluated_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var val = $(this).val();
        var isPad = selectedOption.data('pad');
        var group = selectedOption.data('group');

        if (isPad == 1 || isPad === true) {
            alert('Atenção: Este servidor possui Processo Administrativo Disciplinar (PAD) ativo. Por determinação legal da CAPD, o processo avaliativo está temporariamente suspenso.');
            $(this).val('');
            resetApoio();
            checkFormValidity();
            return;
        }

        if (!val) {
            resetApoio();
            checkFormValidity();
            return;
        }

        // Fazer requisição AJAX para carregar detalhes do servidor no painel lateral
        var detailUrl = "/api/servidores/" + val + "/detalhes";
        
        // Mostrar transição de loading sutil no painel
        $('#apoio-estado-vazio').hide();
        $('#apoio-servidor-info').addClass('opacity-50').removeClass('hidden').show();

        $.getJSON(detailUrl, function(data) {
            // Atualizar cabeçalho do servidor
            $('#servidor-nome').text(data.user.name);
            $('#servidor-cargo').text(data.user.cargo);
            $('#servidor-lotacao').text(data.user.lotacao);
            $('#servidor-avatar').attr('src', data.user.avatar);

            // Atualizar Metas (Barras de progresso)
            var metasHtml = '';
            if (data.goals.length === 0) {
                metasHtml = '<div class="text-xs text-slate-400 font-semibold p-3 bg-slate-50 rounded-lg border border-slate-100 text-center"><i class="fas fa-info-circle mr-1"></i> Nenhuma meta pactuada para este ciclo.</div>';
            } else {
                $.each(data.goals, function(index, goal) {
                    var target = parseFloat(goal.target_value);
                    var achieved = parseFloat(goal.achieved_value || 0);
                    var pct = target > 0 ? Math.min(100, Math.round((achieved / target) * 100)) : 0;
                    
                    var barColor = 'bg-blue-500';
                    if (pct < 50) barColor = 'bg-rose-500';
                    else if (pct < 80) barColor = 'bg-amber-500';
                    else barColor = 'bg-emerald-500';

                    metasHtml += '<div class="space-y-1.5">';
                    metasHtml += '  <div class="flex justify-between items-center text-xs font-semibold">';
                    metasHtml += '      <span class="text-slate-700 max-w-[80%] truncate" title="' + goal.description + '">' + goal.description + '</span>';
                    metasHtml += '      <span class="text-slate-500 font-mono">' + achieved + ' / ' + target + ' ' + (goal.metric || '') + '</span>';
                    metasHtml += '  </div>';
                    metasHtml += '  <div class="relative w-full h-2.5 bg-slate-100 rounded-full border border-slate-150 overflow-hidden">';
                    metasHtml += '      <div class="goal-progress-bar h-full rounded-full transition-all duration-1000 w-0 ' + barColor + '" data-width="' + pct + '%"></div>';
                    metasHtml += '  </div>';
                    metasHtml += '  <div class="flex justify-between items-center text-[10px] text-slate-400 mt-0.5">';
                    metasHtml += '      <span>Atingimento</span>';
                    metasHtml += '      <span class="font-bold text-slate-700 font-mono">' + pct + '%</span>';
                    metasHtml += '  </div>';
                    metasHtml += '</div>';
                });
            }
            $('#servidor-metas-container').html(metasHtml);

            // Iniciar animação das barras de progresso
            setTimeout(function() {
                $('.goal-progress-bar').each(function() {
                    var finalWidth = $(this).data('width');
                    $(this).css('width', finalWidth);
                });
            }, 100);

            // Atualizar contagem de Incidentes Críticos
            $('#incidente-positivo-count').text(data.incidents.positive);
            $('#incidente-negativo-count').text(data.incidents.negative);

            // Seletor inteligente da categoria correspondente ao grupo funcional do servidor
            if (group) {
                var targetRadio = $('.radio-categoria[value="' + group + '"]');
                if (targetRadio.length === 0 && (group === 'geral' || group === 'PEGP')) {
                    // Trata também a categoria regulamentar de gestão
                    targetRadio = $('.radio-categoria[value="geral_gestao"]');
                }
                
                if (targetRadio.length > 0) {
                    targetRadio.prop('checked', true).trigger('change');
                }
            }

            // Exibir painel com efeito fade
            $('#apoio-servidor-info').removeClass('opacity-50');
            checkFormValidity();
        }).fail(function() {
            alert('Falha ao tentar obter histórico e apoio à decisão do servidor.');
            resetApoio();
            checkFormValidity();
        });
    });

    function resetApoio() {
        $('#apoio-servidor-info').hide();
        $('#apoio-estado-vazio').show();
        
        // Desmarcar rádio da categoria para forçar nova escolha
        $('.radio-categoria').prop('checked', false).closest('label').removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
        $('#categoria').val('');
    }

    // Carga inicial se a lotação já estiver definida (Ex: supervisor logado)
    var initialLotacao = $('#lotacao').val();
    if (initialLotacao) {
        loadServidores(initialLotacao);
    }
});
</script>
@endpush
