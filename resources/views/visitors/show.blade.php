@extends('layouts.app')

@section('title', $visitor->name . ' — Visitante MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- BREADCRUMB --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('visitors.index') }}"
           class="flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-[0.2em] transition-all">
            <i class="fas fa-arrow-left text-[8px]"></i> Visitantes
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('visitors.edit', $visitor) }}"
               class="btn-neo bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 hover:border-slate-400 transition-all shadow-sm">
                <i class="fas fa-pen text-[10px]"></i> EDITAR PERFIL
            </a>
        </div>
    </div>

    {{-- PROFILE HERO --}}
    @php
        $statusColors = [
            'New'        => ['from-blue-600 to-blue-800',   'bg-white/20 text-white border-white/30'],
            'Returning'  => ['from-amber-500 to-amber-700', 'bg-white/20 text-white border-white/30'],
            'Interested' => ['from-orange-500 to-orange-700','bg-white/20 text-white border-white/30'],
            'Converted'  => ['from-emerald-600 to-emerald-800', 'bg-white/20 text-white border-white/30'],
            'Inactive'   => ['from-slate-700 to-slate-900', 'bg-white/20 text-white border-white/30'],
        ];
        $sc = $statusColors[$visitor->status] ?? ['from-slate-800 to-slate-950', 'bg-white/20 text-white border-white/30'];
        $isUrgent = !in_array($visitor->status, ['Converted', 'Inactive'])
            && ($visitor->last_contact_at === null || $visitor->last_contact_at->lt(now()->subHours(48)));
    @endphp

    <div class="card-neo !p-0 border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
        <div class="bg-gradient-to-br {{ $sc[0] }} p-12 relative overflow-hidden">
            {{-- Abstract Background Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/5 rounded-full -ml-24 -mb-24 blur-2xl"></div>
            
            <div class="relative flex flex-col md:flex-row items-start md:items-center gap-8">
                <div class="w-28 h-28 rounded-[2.5rem] bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white font-black text-4xl shadow-2xl shrink-0">
                    {{ strtoupper(substr($visitor->name, 0, 1)) }}
                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">{{ $visitor->name }}</h1>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full border {{ $sc[1] }} backdrop-blur-sm shadow-sm">
                            {{ $visitor->status_label }}
                        </span>
                        @if($isUrgent)
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full bg-rose-500 text-white border border-rose-400 shadow-lg shadow-rose-500/40 animate-pulse">
                            <i class="fas fa-satellite-dish text-[10px] mr-1"></i> RADAR ATIVO
                        </span>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-x-8 gap-y-4">
                        @if($visitor->phone)
                            <a href="tel:{{ $visitor->phone }}" class="flex items-center gap-2.5 text-white/80 hover:text-white transition-all group">
                                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                                    <i class="fas fa-phone text-[10px] text-white/40 group-hover:text-white/70"></i>
                                </div>
                                <span class="text-[11px] font-black uppercase tracking-widest">{{ $visitor->phone }}</span>
                            </a>
                        @endif
                        @if($visitor->assignedCell)
                            <div class="flex items-center gap-2.5 text-white/80">
                                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center">
                                    <i class="fas fa-users text-[10px] text-white/40"></i>
                                </div>
                                <span class="text-[11px] font-black uppercase tracking-widest">{{ $visitor->assignedCell->name }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2.5 text-white/80">
                            <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-[10px] text-white/40"></i>
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-widest">INGRESSO EM {{ $visitor->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI STRIP --}}
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-50">
            <div class="px-10 py-8 group hover:bg-slate-50 transition-colors">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Última Abordagem</p>
                <p class="text-xl font-black text-slate-800 tracking-tight group-hover:text-primary transition-colors">
                    {{ $visitor->last_contact_at ? $visitor->last_contact_at->format('d/m/Y') : 'PENDENTE' }}
                </p>
            </div>
            <div class="px-10 py-8 group hover:bg-slate-50 transition-colors">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Janela de Contato</p>
                <p class="text-xl font-black {{ $isUrgent ? 'text-rose-500 animate-pulse' : 'text-slate-800' }} tracking-tight group-hover:scale-105 transition-all">
                    {{ $visitor->last_contact_at ? $visitor->last_contact_at->diffForHumans() : 'NUNCA' }}
                </p>
            </div>
            <div class="px-10 py-8 group hover:bg-slate-50 transition-colors">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Responsável Atual</p>
                <p class="text-xl font-black text-slate-800 tracking-tight">
                    {{ $visitor->contactedBy->name ?? 'A DEFINIR' }}
                </p>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- DETALHES --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="card-neo border-slate-100 p-10 shadow-xl shadow-slate-200/40">
                <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.3em] pb-6 mb-8 border-b border-slate-50 flex items-center gap-3">
                    <i class="fas fa-info-circle text-primary"></i> Dossiê do Visitante
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Estágio de Conversão</p>
                        <div class="flex items-center gap-3">
                            <span class="font-black text-[10px] px-4 py-1.5 rounded-full border uppercase tracking-widest
                                {{ match($visitor->status) {
                                    'New' => 'bg-blue-50 text-blue-600 border-blue-100 shadow-sm shadow-blue-100',
                                    'Returning' => 'bg-amber-50 text-amber-600 border-amber-100 shadow-sm shadow-amber-100',
                                    'Interested' => 'bg-orange-50 text-orange-600 border-orange-100 shadow-sm shadow-orange-100',
                                    'Converted' => 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-sm shadow-emerald-100',
                                    default => 'bg-slate-100 text-slate-500 border-slate-200',
                                } }}">
                                {{ $visitor->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Ponto de Contato</p>
                        <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $visitor->how_did_you_know ?? 'NÃO INFORMADO' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Célula de Acolhimento</p>
                        <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $visitor->assignedCell->name ?? 'PENDENTE DE ATRIBUIÇÃO' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Primeiro Contato em</p>
                        <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $visitor->created_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                </div>

                @if($visitor->notes)
                <div class="mt-12 pt-10 border-t border-slate-50">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Relatório Inicial / Observações</p>
                    <div class="bg-slate-50/80 rounded-3xl p-8 border border-slate-100 italic text-slate-600 text-sm leading-relaxed relative">
                        <i class="fas fa-quote-left absolute top-4 left-4 text-slate-200 text-2xl"></i>
                        {{ $visitor->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-8">
            {{-- Linha do Tempo do Funil --}}
            <div class="card-neo border-slate-100 p-8 shadow-xl shadow-slate-200/40">
                <p class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    <i class="fas fa-filter text-primary"></i> Estágio Atual
                </p>
                
                @php
                    $stages = ['New' => 1, 'Returning' => 2, 'Interested' => 3, 'Converted' => 4, 'Inactive' => 0];
                    $currentStage = $stages[$visitor->status] ?? 0;
                    $funnelStages = [
                        ['key' => 'New',        'label' => 'Novo',        'icon' => 'fa-user-plus'],
                        ['key' => 'Returning',  'label' => 'Retornou',    'icon' => 'fa-rotate-right'],
                        ['key' => 'Interested', 'label' => 'Interessado', 'icon' => 'fa-fire'],
                        ['key' => 'Converted',  'label' => 'Convertido',  'icon' => 'fa-check-circle'],
                    ];
                @endphp

                <div class="space-y-6 relative">
                    <div class="absolute left-[13px] top-0 bottom-0 w-px bg-slate-100"></div>
                    
                    @foreach($funnelStages as $i => $stage)
                    @php
                        $stageNum = $i + 1;
                        $isDone = $currentStage >= $stageNum;
                        $isCurrent = $currentStage === $stageNum;
                    @endphp
                    <div class="flex items-start gap-5 relative z-10">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black shrink-0 transition-all duration-500
                            {{ $isDone ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200 scale-110' : ($isCurrent ? 'bg-amber-400 text-white shadow-lg shadow-amber-200 animate-bounce' : 'bg-white border border-slate-100 text-slate-300') }}">
                            <i class="fas {{ $isDone && !$isCurrent ? 'fa-check' : $stage['icon'] }} text-[9px]"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-[11px] font-black uppercase tracking-widest {{ $isDone ? 'text-slate-800' : 'text-slate-300' }}">{{ $stage['label'] }}</span>
                            @if($isCurrent)
                                <div class="mt-1 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="bg-amber-400 h-full w-2/3 animate-pulse"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Operações Rápidas --}}
            <div class="card-neo border-slate-100 p-8 shadow-xl shadow-slate-200/40 space-y-4">
                <p class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em] mb-6">Operações Rápidas</p>

                @if(!in_array($visitor->status, ['Converted', 'Inactive']))
                <button onclick="openContactModal()"
                        class="w-full btn-neo bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest px-6 py-4 flex items-center gap-3 justify-center hover:bg-slate-900 shadow-xl shadow-slate-200 transition-all">
                    <i class="fas fa-phone-volume text-sm"></i> REGISTRAR ABORDAGEM
                </button>
                @endif

                @if($visitor->status === 'Interested' || $visitor->status === 'Returning')
                <form action="{{ route('visitors.convert', $visitor) }}" method="POST">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Deseja converter este visitante em membro agora?')"
                            class="w-full btn-neo bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest px-6 py-4 flex items-center gap-3 justify-center hover:bg-emerald-700 shadow-xl shadow-emerald-200 transition-all">
                        <i class="fas fa-user-check text-sm"></i> EFETIVAR MEMBRESIA
                    </button>
                </form>
                @endif

                <a href="{{ route('visitors.edit', $visitor) }}"
                   class="w-full btn-neo bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-widest px-6 py-4 flex items-center gap-3 justify-center hover:border-slate-400 transition-all">
                    <i class="fas fa-sliders-h"></i> CONFIGURAÇÕES
                </a>

                <div class="pt-4 border-t border-slate-50">
                    <form action="{{ route('visitors.destroy', $visitor) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('ATENÇÃO: Esta ação é irreversível. Deseja excluir este registro?')"
                                class="w-full text-[9px] font-black text-rose-300 hover:text-rose-500 uppercase tracking-[0.2em] transition-all py-2">
                            EXCLUIR REGISTRO DO SISTEMA
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL CONTATO --}}
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 transition-all duration-500">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up border border-slate-100">
        <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-black text-slate-800 uppercase tracking-tight text-lg">Registrar Acompanhamento</h3>
                <p class="text-[10px] text-accent font-black uppercase tracking-[0.2em] mt-1.5">{{ $visitor->name }}</p>
            </div>
            <button onclick="closeModal()" class="text-slate-300 hover:text-slate-600 transition-all w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-white hover:shadow-sm border border-transparent hover:border-slate-100">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="{{ route('visitors.contact', $visitor) }}" method="POST" class="p-10 space-y-8" id="contactForm">
            @csrf
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Novo Estágio</label>
                <select name="status" class="input-neo py-4">
                    <option value="New"        {{ $visitor->status === 'New' ? 'selected' : '' }}>Novo (Sem Alteração)</option>
                    <option value="Returning"  {{ $visitor->status === 'Returning' ? 'selected' : '' }}>Retornou (Veio mais de 1x)</option>
                    <option value="Interested" {{ $visitor->status === 'Interested' ? 'selected' : '' }}>Interessado (Pronto para conversão)</option>
                    <option value="Converted"  {{ $visitor->status === 'Converted' ? 'selected' : '' }}>Convertido (Já se tornou membro)</option>
                    <option value="Inactive"   {{ $visitor->status === 'Inactive' ? 'selected' : '' }}>Inativo (Perda de contato)</option>
                </select>
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Relatório da Interação</label>
                <textarea name="notes" rows="4" class="input-neo py-4" placeholder="Descreva brevemente como foi a abordagem..."></textarea>
            </div>
            <button type="submit"
                    class="btn-neo bg-slate-800 text-white w-full py-5 text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-slate-900 shadow-xl shadow-slate-200 transition-all">
                <i class="fas fa-check-circle text-sm"></i> SALVAR REGISTRO
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openContactModal() {
        const modal = document.getElementById('contactModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        const modal = document.getElementById('contactModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection
