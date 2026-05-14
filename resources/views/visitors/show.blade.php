@extends('layouts.app')

@section('title', $visitor->name . ' — Visitante MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- BREADCRUMB --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('visitors.index') }}"
           class="flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            <i class="fas fa-arrow-left"></i> Visitantes
        </a>
        <a href="{{ route('visitors.edit', $visitor) }}"
           class="btn-neo bg-white border border-slate-200 text-slate-600 text-[9px] font-black uppercase tracking-widest px-4 py-2 flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
            <i class="fas fa-pen"></i> Editar
        </a>
    </div>

    {{-- PROFILE HERO --}}
    @php
        $statusColors = [
            'New'        => ['from-blue-800 to-blue-700',   'bg-blue-400/20 text-blue-300 border-blue-400/30'],
            'Returning'  => ['from-amber-800 to-amber-700', 'bg-amber-400/20 text-amber-300 border-amber-400/30'],
            'Interested' => ['from-orange-800 to-orange-700','bg-orange-400/20 text-orange-300 border-orange-400/30'],
            'Converted'  => ['from-green-800 to-green-700', 'bg-green-400/20 text-green-300 border-green-400/30'],
            'Inactive'   => ['from-slate-700 to-slate-600', 'bg-slate-400/20 text-slate-300 border-slate-400/30'],
        ];
        $sc = $statusColors[$visitor->status] ?? ['from-slate-800 to-slate-700', 'bg-slate-400/20 text-slate-300 border-slate-400/30'];
        $isUrgent = !in_array($visitor->status, ['Converted', 'Inactive'])
            && ($visitor->last_contact_at === null || $visitor->last_contact_at->lt(now()->subHours(48)));
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r {{ $sc[0] }} p-8 flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white font-black text-3xl shrink-0">
                {{ strtoupper(substr($visitor->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-xl font-black text-white uppercase tracking-tight">{{ $visitor->name }}</h1>
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full border {{ $sc[1] }}">
                        {{ $visitor->status_label }}
                    </span>
                    @if($isUrgent)
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full bg-rose-400/20 text-rose-300 border border-rose-400/30 animate-pulse">
                        <i class="fas fa-triangle-exclamation text-[7px]"></i> Radar 48h
                    </span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-5 text-white/60 text-xs font-bold">
                    @if($visitor->phone)
                        <a href="tel:{{ $visitor->phone }}" class="flex items-center gap-1.5 hover:text-white/90 transition-colors">
                            <i class="fas fa-phone text-white/30"></i> {{ $visitor->phone }}
                        </a>
                    @endif
                    @if($visitor->email)
                        <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-white/30"></i> {{ $visitor->email }}</span>
                    @endif
                    @if($visitor->assignedCell)
                        <span class="flex items-center gap-1.5"><i class="fas fa-church text-white/30"></i> {{ $visitor->assignedCell->name }}</span>
                    @endif
                    <span class="flex items-center gap-1.5"><i class="fas fa-calendar text-white/30"></i> Cadastrado {{ $visitor->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- KPI strip --}}
        <div class="grid grid-cols-3 divide-x divide-gray-100">
            <div class="px-6 py-5 text-center">
                <p class="text-sm font-black text-gray-800 tracking-tight">
                    {{ $visitor->last_contact_at ? $visitor->last_contact_at->format('d/m/Y') : '—' }}
                </p>
                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mt-1">Último Contato</p>
            </div>
            <div class="px-6 py-5 text-center">
                <p class="text-sm font-black {{ $isUrgent ? 'text-rose-500' : 'text-gray-800' }} tracking-tight">
                    {{ $visitor->last_contact_at ? $visitor->last_contact_at->diffForHumans() : 'Nunca' }}
                </p>
                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mt-1">Há quanto tempo</p>
            </div>
            <div class="px-6 py-5 text-center">
                <p class="text-sm font-black text-gray-800 tracking-tight">
                    {{ $visitor->contactedBy->name ?? '—' }}
                </p>
                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mt-1">Contactado por</p>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- DETALHES --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest pb-4 mb-4 border-b border-gray-100">
                    Informações do Visitante
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mb-1">Status Atual</p>
                        <span class="font-medium text-xs px-2.5 py-1 rounded-full border
                            {{ match($visitor->status) {
                                'New' => 'bg-blue-50 text-blue-600 border-blue-200',
                                'Returning' => 'bg-amber-50 text-amber-600 border-amber-200',
                                'Interested' => 'bg-orange-50 text-orange-600 border-orange-200',
                                'Converted' => 'bg-green-50 text-green-600 border-green-200',
                                default => 'bg-gray-100 text-gray-500 border-gray-200',
                            } }}">
                            {{ $visitor->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mb-1">Como nos conheceu</p>
                        <p class="text-sm font-bold text-gray-700">{{ $visitor->how_did_you_know ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mb-1">Célula Responsável</p>
                        <p class="text-sm font-bold text-gray-700">{{ $visitor->assignedCell->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mb-1">Cadastrado em</p>
                        <p class="text-sm font-bold text-gray-700">{{ $visitor->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($visitor->notes)
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Observações</p>
                    <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-xl p-4">{{ $visitor->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- AÇÕES --}}
        <div class="space-y-4">
            {{-- Progresso do Funil --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Progresso no Funil</p>
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
                <div class="space-y-2">
                    @foreach($funnelStages as $i => $stage)
                    @php
                        $stageNum = $i + 1;
                        $isDone = $currentStage >= $stageNum;
                        $isCurrent = $currentStage === $stageNum;
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black shrink-0
                            {{ $isDone ? 'bg-green-500 text-white' : ($isCurrent ? 'bg-amber-400 text-white' : 'bg-gray-100 text-gray-400') }}">
                            <i class="fas {{ $isDone && !$isCurrent ? 'fa-check' : $stage['icon'] }} text-[9px]"></i>
                        </div>
                        <span class="text-xs font-bold {{ $isDone ? 'text-gray-700' : 'text-gray-400' }}">{{ $stage['label'] }}</span>
                        @if($isCurrent)
                            <span class="ml-auto text-[8px] font-black text-amber-500 uppercase tracking-widest">Atual</span>
                        @endif
                    </div>
                    @if(!$loop->last)
                        <div class="ml-3.5 h-3 w-px bg-gray-100"></div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Ações --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Ações</p>

                @if(!in_array($visitor->status, ['Converted', 'Inactive']))
                <button onclick="openContactModal()"
                        class="w-full btn-neo bg-[#f59e0b] text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 justify-center hover:bg-[#d97706] transition-colors">
                    <i class="fas fa-phone-volume"></i> Registrar Contato
                </button>
                @endif

                @if($visitor->status === 'Interested' || $visitor->status === 'Returning')
                <a href="{{ route('visitors.consolidate', $visitor) }}"
                   class="w-full btn-neo bg-green-500 text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 justify-center hover:bg-green-600 transition-colors">
                    <i class="fas fa-handshake"></i> Consolidar como Membro
                </a>
                @endif

                <a href="{{ route('visitors.edit', $visitor) }}"
                   class="w-full btn-neo bg-white border border-gray-200 text-gray-600 text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 justify-center hover:border-gray-400 transition-all">
                    <i class="fas fa-pen"></i> Editar Dados
                </a>

                <form action="{{ route('visitors.destroy', $visitor) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Remover este visitante?')"
                            class="w-full btn-neo bg-red-50 border border-red-100 text-red-400 hover:bg-red-500 hover:text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 justify-center transition-all">
                        <i class="fas fa-trash"></i> Remover
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- MODAL CONTATO --}}
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-gray-800 uppercase tracking-tight text-sm">Registrar Contato</h3>
                <p class="text-[10px] text-gray-400 font-semibold mt-0.5">{{ $visitor->name }}</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="{{ route('visitors.contact', $visitor) }}" method="POST" class="p-6 space-y-5" id="contactForm">
            @csrf
            <div>
                <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Atualizar Status</label>
                <select name="status" class="input-neo py-2.5">
                    <option value="New"        {{ $visitor->status === 'New' ? 'selected' : '' }}>Novo</option>
                    <option value="Returning"  {{ $visitor->status === 'Returning' ? 'selected' : '' }}>Retornou</option>
                    <option value="Interested" {{ $visitor->status === 'Interested' ? 'selected' : '' }}>Interessado</option>
                    <option value="Converted"  {{ $visitor->status === 'Converted' ? 'selected' : '' }}>Convertido</option>
                    <option value="Inactive"   {{ $visitor->status === 'Inactive' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div>
                <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Observações</label>
                <textarea name="notes" rows="3" class="input-neo py-3" placeholder="Resultado do contato..."></textarea>
            </div>
            <button type="submit"
                    class="w-full btn-neo bg-[#f59e0b] text-white py-3 text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#d97706] transition-colors">
                <i class="fas fa-check"></i> Salvar Contato
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
