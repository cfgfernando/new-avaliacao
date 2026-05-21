@extends('layouts.app')

@section('title', 'Inicializar Avaliação - SAD-BARS')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-3xl font-bold text-[#0f172b] tracking-tight font-sans">Inicializar Nova Avaliação Regulamentar</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium font-sans">Siga as etapas do assistente para configurar os parâmetros da avaliação e iniciar o processo.</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-all font-sans">
                <i class="fas fa-arrow-left mr-2"></i> Voltar ao Dashboard
            </a>
        </div>
    </div>

    <!-- Stepper Progress Indicator -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm max-w-4xl mx-auto">
        <div class="flex items-center justify-between max-w-xl mx-auto relative">
            <!-- Linha de progresso de fundo -->
            <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-slate-100 rounded-full z-0"></div>
            <!-- Linha de progresso ativa -->
            <div id="stepper-progress-line" class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 rounded-full z-0 transition-all duration-300 w-0"></div>

            <!-- Passo 1 -->
            <div class="z-10 text-center flex flex-col items-center step-indicator active" data-step="1">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center border-4 border-blue-50 transition-all duration-300 font-mono">1</span>
                <span class="text-[9px] font-bold text-slate-700 uppercase tracking-wider font-mono mt-1.5 bg-white px-2">Parâmetros</span>
            </div>
            <!-- Passo 2 -->
            <div class="z-10 text-center flex flex-col items-center step-indicator" data-step="2">
                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center border-4 border-white transition-all duration-300 font-mono font-medium">2</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider font-mono mt-1.5 bg-white px-2">Servidor</span>
            </div>
            <!-- Passo 3 -->
            <div class="z-10 text-center flex flex-col items-center step-indicator" data-step="3">
                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center border-4 border-white transition-all duration-300 font-mono font-medium">3</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider font-mono mt-1.5 bg-white px-2">Finalização</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid (12 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        
        <!-- Left Column: Formulário de Abertura (7/12) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm h-full flex flex-col justify-between space-y-6">
                
                <form action="{{ route('evaluations.setup.store') }}" method="POST" id="evaluation-setup-form" class="space-y-5 flex-1 flex flex-col justify-between">
                    @csrf
                    <input type="hidden" name="categoria" id="categoria" value="">
                    
                    <div class="space-y-6 flex-1">
                        
                        {{-- ==========================================
                             PASSO 1: PARÂMETROS GERAIS (CICLO / LOTAÇÃO)
                             ========================================== --}}
                        <div id="step-1-content" class="step-content space-y-5">
                            <div class="border-b border-slate-100 pb-3 mb-4">
                                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider font-sans">Passo 1: Ciclo & Lotação</h2>
                                <p class="text-xs text-slate-500 font-medium font-sans">Selecione o ciclo de avaliação vigente e sua secretaria/lotação funcional.</p>
                            </div>

                            <!-- 1. Ciclo Avaliativo -->
                            <div>
                                <label for="cycle_id" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Ciclo Avaliativo <span class="text-rose-500">*</span></label>
                                @if(auth()->user()->isAdmin())
                                    <select name="cycle_id" id="cycle_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-0 focus:bg-white outline-none">
                                        @foreach($cycles as $cycle)
                                            <option value="{{ $cycle->id }}" data-block-on-pad="{{ $cycle->block_on_pad ? '1' : '0' }}" {{ $cycle->status === 'active' ? 'selected' : '' }}>
                                                {{ $cycle->name }} {{ $cycle->status === 'active' ? '(Ciclo Vigente)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    @php $activeCycle = $cycles->first(); @endphp
                                    <input type="hidden" name="cycle_id" id="cycle_id" value="{{ $activeCycle?->id }}" data-block-on-pad="{{ $activeCycle?->block_on_pad ? '1' : '0' }}">
                                    <input type="text" value="{{ $activeCycle?->name ?? 'Nenhum ciclo ativo' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-500 cursor-not-allowed" readonly>
                                    <p class="mt-1 text-[10px] text-slate-400 font-sans">Restrito ao ciclo avaliativo vigente para a sua Chefia Imediata.</p>
                                @endif
                                @error('cycle_id')
                                    <p class="mt-1 text-xs text-rose-600 font-medium font-sans">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 2. Secretaria / Lotação -->
                            <div>
                                <label for="lotacao" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Secretaria / Lotação de Exercício <span class="text-rose-500">*</span></label>
                                @if(auth()->user()->isAdmin())
                                    <select name="lotacao" id="lotacao" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-0 focus:bg-white outline-none">
                                        <option value="">Selecione uma secretaria/lotação...</option>
                                        @foreach($lotacoes as $lot)
                                            <option value="{{ $lot }}">{{ $lot }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    @php $chefiaLotacao = $lotacoes->first(); @endphp
                                    <input type="hidden" name="lotacao" id="lotacao" value="{{ $chefiaLotacao }}">
                                    <input type="text" value="{{ $chefiaLotacao }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-500 cursor-not-allowed" readonly>
                                    <p class="mt-1 text-[10px] text-slate-400 font-sans">Limitado à sua secretaria/lotação funcional.</p>
                                @endif
                                @error('lotacao')
                                    <p class="mt-1 text-xs text-rose-600 font-medium font-sans">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ==========================================
                             PASSO 2: SELEÇÃO DE SERVIDOR
                             ========================================== --}}
                        <div id="step-2-content" class="step-content space-y-5 hidden">
                            <div class="border-b border-slate-100 pb-3 mb-4">
                                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider font-sans">Passo 2: Servidor Avaliado</h2>
                                <p class="text-xs text-slate-500 font-medium font-sans">Selecione o servidor ativo para avaliar. O painel à direita carregará as metas e incidentes funcionais do mesmo.</p>
                            </div>

                            <!-- 3. Servidor Avaliado -->
                            <div>
                                <label for="evaluated_id" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Servidor Avaliado <span class="text-rose-500">*</span></label>
                                <select name="evaluated_id" id="evaluated_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-0 focus:bg-white outline-none" disabled>
                                    <option value="">Selecione primeiro uma lotação...</option>
                                </select>
                                <p id="loading-servidores" class="mt-1.5 text-xs text-blue-600 font-medium hidden items-center gap-1.5 animate-pulse font-sans">
                                    <i class="fas fa-spinner fa-spin"></i> Carregando servidores ativos da lotação...
                                </p>
                                @error('evaluated_id')
                                    <p class="mt-1 text-xs text-rose-600 font-medium font-sans">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alertas de PAD (Preenchidos via JavaScript) -->
                            <div id="pad-warning-container" class="hidden">
                                <!-- Bloqueio Rígido -->
                                <div id="pad-block-alert" class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs font-medium space-y-2 hidden">
                                    <div class="flex items-center gap-2 text-rose-700 font-bold uppercase tracking-wider text-[10px] font-mono">
                                        <i class="fas fa-exclamation-triangle text-sm"></i> AVALIAÇÃO BLOQUEADA PELO RH
                                    </div>
                                    <p class="font-sans leading-relaxed">Este servidor responde a um Processo Administrativo Disciplinar (PAD) ativo. De acordo com as diretrizes da CAPD e a parametrização do ciclo de avaliação, o início da avaliação está temporariamente suspenso.</p>
                                </div>
                                
                                <!-- Aviso Permitido -->
                                <div id="pad-allow-alert" class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs font-medium space-y-2 hidden">
                                    <div class="flex items-center gap-2 text-amber-700 font-bold uppercase tracking-wider text-[10px] font-mono">
                                        <i class="fas fa-exclamation-circle text-sm"></i> ALERTA FUNCIONAL (PAD ATIVO)
                                    </div>
                                    <p class="font-sans leading-relaxed">Este servidor responde a um Processo Administrativo Disciplinar (PAD) ativo. A avaliação foi permitida de forma excepcional pelo RH neste ciclo, mas o registro formal do PAD permanecerá vinculado ao histórico funcional.</p>
                                </div>
                            </div>
                        </div>

                        {{-- ==========================================
                             PASSO 3: CATEGORIA & FÉ PÚBLICA (CONFIRMAR)
                             ========================================== --}}
                        <div id="step-3-content" class="step-content space-y-5 hidden">
                            <div class="border-b border-slate-100 pb-3 mb-4">
                                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider font-sans">Passo 3: Categoria Regulamentar & Confirmação</h2>
                                <p class="text-xs text-slate-500 font-medium font-sans">Selecione a categoria de escala BARS correspondente e confirme os dados finais do avaliador responsável.</p>
                            </div>

                            <!-- 4. Categoria Regulamentar (5 Cards de Rádio Customizados) -->
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-3">Categoria Regulamentar (Escala BARS correspondente) <span class="text-rose-500">*</span></label>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="radio-categoria-container">
                                    <!-- Card 1: Geral -->
                                    <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-geral">
                                        <input type="radio" name="temp_categoria" value="geral" class="sr-only radio-categoria">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                                <i class="fas fa-users text-xs"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide font-sans">Quadro Geral</h4>
                                                <p class="text-[10px] text-slate-500 mt-1 leading-normal font-sans">Carreira técnica geral, administrativa e operacional.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Card 2: Saúde -->
                                    <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-saude">
                                        <input type="radio" name="temp_categoria" value="saude" class="sr-only radio-categoria">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                                <i class="fas fa-heartbeat text-xs"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide font-sans">Saúde Pública</h4>
                                                <p class="text-[10px] text-slate-500 mt-1 leading-normal font-sans">Médicos, enfermeiros, técnicos e agentes de saúde.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Card 3: Guarda -->
                                    <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-guarda">
                                        <input type="radio" name="temp_categoria" value="guarda" class="sr-only radio-categoria">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                                <i class="fas fa-shield-alt text-xs"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide font-sans">Segurança</h4>
                                                <p class="text-[10px] text-slate-500 mt-1 leading-normal font-sans">GMs, patrulheiros, inspetores e agentes urbanos.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Card 4: Educação -->
                                    <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100" id="card-radio-educacao">
                                        <input type="radio" name="temp_categoria" value="educacao" class="sr-only radio-categoria">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                                <i class="fas fa-graduation-cap text-xs"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide font-sans">Educação Básica</h4>
                                                <p class="text-[10px] text-slate-500 mt-1 leading-normal font-sans">Professores, educadores e pedagogos escolares.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Card 5: Gestão Governamental (PEGP) -->
                                    <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none group focus-within:ring-2 focus-within:ring-blue-100 md:col-span-2" id="card-radio-gestao">
                                        <input type="radio" name="temp_categoria" value="geral_gestao" class="sr-only radio-categoria">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-200 transition-all">
                                                <i class="fas fa-briefcase text-xs"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide font-sans">Gestão e PEGP</h4>
                                                <p class="text-[10px] text-slate-500 mt-1 leading-normal font-sans">Especialistas em gestão pública, analistas e executivos municipais.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('categoria')
                                    <p class="mt-1 text-xs text-rose-600 font-medium font-sans">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Resumo das Configurações -->
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">Resumo da Avaliação</h4>
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <span class="text-slate-400 font-semibold font-sans">Servidor:</span>
                                        <span id="resumo-servidor" class="font-bold text-slate-800 block mt-0.5 truncate font-sans">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-semibold font-sans">Ciclo:</span>
                                        <span id="resumo-ciclo" class="font-bold text-slate-800 block mt-0.5 truncate font-sans">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-semibold font-sans">Lotação:</span>
                                        <span id="resumo-lotacao" class="font-bold text-slate-800 block mt-0.5 truncate font-sans">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-semibold font-sans">Categoria BARS:</span>
                                        <span id="resumo-categoria" class="font-bold text-slate-800 block mt-0.5 uppercase tracking-wide font-sans">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Avaliador Portador de Fé Pública -->
                            <div class="pt-2">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Avaliador Responsável (Fé Pública)</label>
                                <div class="flex items-center gap-3 bg-slate-50 rounded-xl border border-slate-200 p-4">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 border border-blue-200 font-bold text-xs flex items-center justify-center font-mono">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'RH', 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-slate-800 block font-sans">{{ Auth::user()->name ?? 'Departamento de RH' }}</span>
                                        <span class="text-[10px] text-slate-500 block uppercase mt-0.5 font-mono">Matrícula: {{ Auth::user()->registration_number ?? 'CAPD.2026.01' }} • Portador de Fé Pública</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Botões de Ação Dinâmicos do Wizard -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-3 bg-white mt-auto">
                        <div>
                            <button type="button" id="btn-back" class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-500 bg-white hover:bg-slate-50 transition-all font-sans hidden">
                                <i class="fas fa-chevron-left mr-1"></i> Voltar
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard') }}" id="btn-cancel" class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-500 bg-white hover:bg-slate-50 transition-all font-sans">
                                Cancelar
                            </a>
                            <button type="button" id="btn-next" class="px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all flex items-center gap-2 shadow-md shadow-blue-500/10 font-sans" disabled>
                                Avançar <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                            <button type="submit" id="btn-submit" class="px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-blue-500/10 font-sans hidden" disabled>
                                <i class="fas fa-play"></i> Iniciar Avaliação
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Painel de Apoio à Decisão (5/12) -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm h-full flex flex-col justify-between relative overflow-hidden min-h-[450px]">
                
                <!-- Estado Vazio: Nenhum servidor selecionado -->
                <div id="apoio-estado-vazio" class="flex flex-col items-center justify-center text-center my-auto py-12 space-y-4">
                    <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full border border-slate-100 flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-4xl">contact_page</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 font-sans">Apoio à Decisão (Histórico)</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-[280px] mx-auto leading-relaxed font-sans font-medium">Selecione uma secretaria e avance para selecionar um servidor. Aqui será carregado o histórico de metas e incidentes funcionais.</p>
                    </div>
                </div>

                <!-- Detalhes do Servidor (Carregados via AJAX) -->
                <div id="apoio-servidor-info" class="hidden space-y-6 flex-1 flex flex-col justify-between">
                    <!-- Top Info Card -->
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                        <img id="servidor-avatar" src="" alt="Avatar" class="w-14 h-14 rounded-full border border-slate-200 shadow-sm shrink-0">
                        <div class="min-w-0">
                            <h3 id="servidor-nome" class="text-base font-bold text-slate-900 truncate font-sans">Nome do Servidor</h3>
                            <p id="servidor-cargo" class="text-xs text-slate-500 font-semibold truncate mt-0.5 font-sans">Cargo do Servidor</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span id="servidor-lotacao" class="text-[10px] text-slate-450 font-bold uppercase truncate font-mono">Lotação</span>
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
                                    <span class="text-xs text-slate-500 block leading-tight font-medium font-sans">Elogios & Destaques</span>
                                </div>
                                <span id="incidente-positivo-count" class="w-10 h-10 rounded-full bg-emerald-500 text-white font-bold text-sm flex items-center justify-center shadow-sm font-mono">
                                    0
                                </span>
                            </div>

                            <!-- Incidentes Negativos -->
                            <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-bold text-rose-700 uppercase tracking-widest font-mono">Negativos</span>
                                    <span class="text-xs text-slate-500 block leading-tight font-medium font-sans">Falhas & Atrasos</span>
                                </div>
                                <span id="incidente-negativo-count" class="w-10 h-10 rounded-full bg-rose-500 text-white font-bold text-sm flex items-center justify-center shadow-sm font-mono">
                                    0
                                </span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 leading-relaxed font-sans font-medium">
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
    var currentStep = 1;

    // Lê block_on_pad do select (Admin) ou do input hidden (Chefia)
    function getBlockOnPad() {
        var $cycleEl = $('#cycle_id');
        if ($cycleEl.is('select')) {
            return parseInt($cycleEl.find('option:selected').data('block-on-pad')) || 0;
        } else {
            return parseInt($cycleEl.data('block-on-pad')) || 0;
        }
    }

    var blockOnPadCurrent = getBlockOnPad();

    // 1. Ouvinte para mudança de ciclo (apenas Admin tem select)
    $('#cycle_id').on('change', function() {
        blockOnPadCurrent = getBlockOnPad();
        
        // Se já houver um servidor selecionado, re-validar as travas do PAD
        if ($('#evaluated_id').val()) {
            $('#evaluated_id').trigger('change');
        }
        validateStep1();
    });

    // Disparar validação inicial do Passo 1 para habilitar botão se campos já preenchidos
    validateStep1();

    // 2. Controle de Rádio da Categoria
    $('.radio-categoria').on('change', function() {
        // Remove destaques
        $('.radio-categoria').closest('label').removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
        
        if ($(this).is(':checked')) {
            $(this).closest('label').removeClass('border-slate-200').addClass('border-blue-500 ring-2 ring-blue-100');
            
            var val = $(this).val();
            if (val === 'geral_gestao') {
                $('#categoria').val('geral');
                $('#resumo-categoria').text('Gestão e PEGP');
            } else {
                $('#categoria').val(val);
                var categoryTexts = {
                    'geral': 'Quadro Geral',
                    'saude': 'Saúde Pública',
                    'guarda': 'Segurança',
                    'educacao': 'Educação Básica'
                };
                $('#resumo-categoria').text(categoryTexts[val] || val);
            }
        }
        validateStep3();
    });

    // 3. Dropdown Dinâmico de Servidores por Lotação
    function loadServidores(lotacaoVal) {
        if (!lotacaoVal) {
            $('#evaluated_id').html('<option value="">Selecione primeiro uma lotação...</option>').prop('disabled', true);
            resetApoio();
            validateStep1();
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
                    var padBadge = servidor.has_active_pad ? ' [PAD ATIVO]' : '';
                    
                    options += '<option value="' + servidor.id + '" data-group="' + servidor.evaluation_group + '" data-pad="' + servidor.has_active_pad + '">';
                    options += servidor.name + ' (' + servidor.cargo + ')' + padBadge;
                    options += '</option>';
                });
            }
            
            $('#evaluated_id').html(options).prop('disabled', data.length === 0);
            $('#loading-servidores').addClass('hidden');
            validateStep2();
        }).fail(function() {
            $('#evaluated_id').html('<option value="">Erro ao buscar servidores ativos</option>').prop('disabled', true);
            $('#loading-servidores').addClass('hidden');
            validateStep2();
        });
    }

    $('#lotacao').on('change', function() {
        loadServidores($(this).val());
        validateStep1();
    });

    // 4. Seleção do Servidor e AJAX de Detalhes do Painel de Apoio
    $('#evaluated_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var val             = $(this).val();
        var isPad           = selectedOption.data('pad') == 1 || selectedOption.data('pad') === true;
        var group           = selectedOption.data('group'); // fallback imediato

        // Resetar Alertas de PAD
        $('#pad-warning-container').addClass('hidden');
        $('#pad-block-alert').addClass('hidden');
        $('#pad-allow-alert').addClass('hidden');

        // Servidor desmarcado → reseta tudo
        if (!val) {
            resetApoio();
            validateStep2();
            return;
        }

        // ─── PAD CHECK ───────────────────────────────────────────────────────
        if (isPad) {
            $('#pad-warning-container').removeClass('hidden');
            if (blockOnPadCurrent === 1) {
                $('#pad-block-alert').removeClass('hidden');
                $('#btn-next').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                resetApoioExcludingSelect();
                return; // bloqueia: não avança nem carrega painel
            }
            $('#pad-allow-alert').removeClass('hidden'); // apenas avisa, permite prosseguir
        }

        // ─── VALIDAÇÃO IMEDIATA (sem esperar AJAX) ───────────────────────────
        // Aplica a categoria já disponível no data-group do <option>
        if (group) {
            applyCategoria(group);
        }
        // Habilita "Avançar" imediatamente — o painel de apoio é secundário
        validateStep2();

        // ─── AJAX: Painel de Apoio (metas + incidentes + refinamento de categoria) ──
        var detailUrl = '/api/servidores/' + val + '/detalhes';
        $('#apoio-estado-vazio').hide();
        $('#apoio-servidor-info').addClass('opacity-50').removeClass('hidden').show();

        $.getJSON(detailUrl, function(data) {
            $('#servidor-nome').text(data.user.name);
            $('#servidor-cargo').text(data.user.cargo);
            $('#servidor-lotacao').text(data.user.lotacao);
            $('#servidor-avatar').attr('src', data.user.avatar);
            $('#resumo-servidor').text(data.user.name + ' (' + data.user.cargo + ')');

            // Refina a categoria com o dado confiável da API
            applyCategoria(data.user.evaluation_group);

            // Metas
            var metasHtml = '';
            if (data.goals.length === 0) {
                metasHtml = '<div class="text-xs text-slate-400 font-semibold p-3 bg-slate-50 rounded-lg border border-slate-100 text-center"><i class="fas fa-info-circle mr-1"></i> Nenhuma meta pactuada para este ciclo.</div>';
            } else {
                $.each(data.goals, function(i, goal) {
                    var target   = parseFloat(goal.target_value);
                    var achieved = parseFloat(goal.achieved_value || 0);
                    var pct      = target > 0 ? Math.min(100, Math.round((achieved / target) * 100)) : 0;
                    var barColor = pct < 50 ? 'bg-rose-500' : (pct < 80 ? 'bg-amber-500' : 'bg-emerald-500');

                    metasHtml += '<div class="space-y-1.5">';
                    metasHtml += '  <div class="flex justify-between items-center text-xs font-semibold">';
                    metasHtml += '    <span class="text-slate-700 max-w-[80%] truncate font-sans" title="' + goal.description + '">' + goal.description + '</span>';
                    metasHtml += '    <span class="text-slate-500 font-mono">' + achieved + ' / ' + target + ' ' + (goal.metric || '') + '</span>';
                    metasHtml += '  </div>';
                    metasHtml += '  <div class="relative w-full h-2 bg-slate-100 rounded-full overflow-hidden">';
                    metasHtml += '    <div class="goal-progress-bar h-full rounded-full transition-all duration-1000 w-0 ' + barColor + '" data-width="' + pct + '%"></div>';
                    metasHtml += '  </div>';
                    metasHtml += '  <div class="flex justify-between text-[10px] text-slate-400 mt-0.5">';
                    metasHtml += '    <span class="font-sans">Atingimento</span>';
                    metasHtml += '    <span class="font-bold text-slate-700 font-mono">' + pct + '%</span>';
                    metasHtml += '  </div>';
                    metasHtml += '</div>';
                });
            }
            $('#servidor-metas-container').html(metasHtml);
            setTimeout(function() {
                $('.goal-progress-bar').each(function() { $(this).css('width', $(this).data('width')); });
            }, 100);

            // Incidentes
            $('#incidente-positivo-count').text(data.incidents.positive);
            $('#incidente-negativo-count').text(data.incidents.negative);

            $('#apoio-servidor-info').removeClass('opacity-50');
        }).fail(function() {
            // Painel de apoio falhou — apenas esconde o painel, navegação não é afetada
            $('#apoio-servidor-info').hide();
            $('#apoio-estado-vazio').show().find('p').text('Histórico indisponível. A avaliação pode prosseguir normalmente.');
        });
    });

    function resetApoio() {
        resetApoioExcludingSelect();
        // Desmarcar rádio
        $('.radio-categoria').prop('checked', false).closest('label').removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
        $('#categoria').val('');
    }

    function resetApoioExcludingSelect() {
        $('#apoio-servidor-info').hide();
        $('#apoio-estado-vazio').show();
        $('#resumo-servidor').text('-');
    }

    // Aplica a categoria automaticamente com base no grupo funcional do servidor
    function applyCategoria(group) {
        if (!group) return;

        // Mapear groups para valores dos radios
        var radioValue = group;
        if (group === 'PEGP' || group === 'geral_gestao') {
            radioValue = 'geral_gestao';
        } else if (group === 'geral') {
            // 'geral' pode ser quadro geral OU gestao — priorizamos 'geral' (Quadro Geral)
            radioValue = 'geral';
        }

        var targetRadio = $('.radio-categoria[value="' + radioValue + '"]');
        // Fallback: se nao encontrou, tenta geral_gestao
        if (targetRadio.length === 0 && (group === 'geral' || group === 'PEGP')) {
            targetRadio = $('.radio-categoria[value="geral_gestao"]');
        }

        if (targetRadio.length > 0) {
            // Marcar o radio
            targetRadio.prop('checked', true);
            // Atualizar visuais dos cards
            $('.radio-categoria').closest('label').removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
            targetRadio.closest('label').removeClass('border-slate-200').addClass('border-blue-500 ring-2 ring-blue-100');
            // Atualizar o campo hidden e o resumo
            var val = targetRadio.val();
            if (val === 'geral_gestao') {
                $('#categoria').val('geral');
                $('#resumo-categoria').text('Gestão e PEGP');
            } else {
                $('#categoria').val(val);
                var categoryTexts = {
                    'geral':    'Quadro Geral',
                    'saude':    'Saúde Pública',
                    'guarda':   'Segurança',
                    'educacao': 'Educação Básica'
                };
                $('#resumo-categoria').text(categoryTexts[val] || val);
            }
        }
    }

    // Cada função só atua quando o passo correspondente está ativo,
    // evitando que callbacks AJAX de outros passos interfiram no estado do botão.
    function validateStep1() {
        if (currentStep !== 1) return;
        var cycle = $('#cycle_id').val();
        var lotacao = $('#lotacao').val();
        if (cycle && lotacao) {
            $('#btn-next').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        } else {
            $('#btn-next').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        }
    }

    function validateStep2() {
        if (currentStep !== 2) return;
        var servidor = $('#evaluated_id').val();
        var selectedOption = $('#evaluated_id option:selected');
        var isPad = selectedOption.data('pad') == 1 || selectedOption.data('pad') === true;

        if (servidor && !(isPad && blockOnPadCurrent === 1)) {
            $('#btn-next').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        } else {
            $('#btn-next').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        }
    }

    function validateStep3() {
        if (currentStep !== 3) return;
        var categoria = $('#categoria').val();
        if (categoria) {
            $('#btn-submit').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        } else {
            $('#btn-submit').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        }
    }

    // 6. Controle de Navegação do Wizard
    $('#btn-next').on('click', function() {
        if (currentStep === 1) {
            // Avança para o Passo 2
            $('#step-1-content').addClass('hidden');
            $('#step-2-content').removeClass('hidden');
            $('#btn-back').removeClass('hidden');
            
            // Atualizar Stepper UI
            $('.step-indicator[data-step="2"]').addClass('active').find('span').removeClass('bg-slate-100 text-slate-400').addClass('bg-blue-600 text-white border-blue-50');
            $('#stepper-progress-line').css('width', '50%');
            
            currentStep = 2;
            validateStep2();
        } else if (currentStep === 2) {
            // Avança para o Passo 3
            $('#step-2-content').addClass('hidden');
            $('#step-3-content').removeClass('hidden');
            $('#btn-next').addClass('hidden');
            $('#btn-submit').removeClass('hidden');

            // Injetar dados do resumo
            $('#resumo-ciclo').text(authAdminOrReadOnlyCycleName());
            $('#resumo-lotacao').text($('#lotacao').val());

            // Re-aplicar visualmente a categoria ja auto-selecionada no Passo 2
            var categoriaAtual = $('#categoria').val();
            if (categoriaAtual) {
                var radioAtual = categoriaAtual === 'geral' 
                    ? $('.radio-categoria:checked') 
                    : $('.radio-categoria[value="' + categoriaAtual + '"]');
                if (radioAtual.length > 0) {
                    $('.radio-categoria').closest('label').removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
                    radioAtual.closest('label').removeClass('border-slate-200').addClass('border-blue-500 ring-2 ring-blue-100');
                }
            }

            // Atualizar Stepper UI
            $('.step-indicator[data-step="3"]').addClass('active').find('span').removeClass('bg-slate-100 text-slate-400').addClass('bg-blue-600 text-white border-blue-50');
            $('#stepper-progress-line').css('width', '100%');

            currentStep = 3;
            validateStep3();
        }
    });

    $('#btn-back').on('click', function() {
        if (currentStep === 2) {
            // Volta para Passo 1
            $('#step-2-content').addClass('hidden');
            $('#step-1-content').removeClass('hidden');
            $('#btn-back').addClass('hidden');
            
            // Atualizar Stepper UI
            $('.step-indicator[data-step="2"]').removeClass('active').find('span').addClass('bg-slate-100 text-slate-400').removeClass('bg-blue-600 text-white border-blue-50');
            $('#stepper-progress-line').css('width', '0%');

            currentStep = 1;
            validateStep1();
        } else if (currentStep === 3) {
            // Volta para Passo 2
            $('#step-3-content').addClass('hidden');
            $('#step-2-content').removeClass('hidden');
            $('#btn-submit').addClass('hidden');
            $('#btn-next').removeClass('hidden');

            // Atualizar Stepper UI
            $('.step-indicator[data-step="3"]').removeClass('active').find('span').addClass('bg-slate-100 text-slate-400').removeClass('bg-blue-600 text-white border-blue-50');
            $('#stepper-progress-line').css('width', '50%');

            currentStep = 2;
            validateStep2();
        }
    });

    function authAdminOrReadOnlyCycleName() {
        if ($('#cycle_id').is('select')) {
            return $('#cycle_id option:selected').text().trim();
        } else {
            return "{{ $cycles->first()?->name ?? 'Ciclo Vigente' }}";
        }
    }

    // Carga inicial se a lotação já estiver definida (Ex: chefia imediata logada)
    var initialLotacao = $('#lotacao').val();
    if (initialLotacao) {
        loadServidores(initialLotacao);
        validateStep1();
    } else {
        validateStep1();
    }
});
</script>
@endpush
