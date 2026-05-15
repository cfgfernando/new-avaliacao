@extends('layouts.app')

@section('title', 'Visitantes — Funil de Atendimento')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Funil de Visitantes</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                Acompanhamento e conversão de visitantes
            </p>
        </div>
        <div class="flex gap-3">
            @if($funnel['radar'] > 0)
            <a href="{{ route('operacional.radar') }}"
               class="btn-neo bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-500 hover:text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 transition-all">
                <i class="fas fa-satellite-dish animate-pulse"></i>
                Radar <span class="bg-rose-500 text-white text-[8px] px-1.5 py-0.5 rounded-full ml-1">{{ $funnel['radar'] }}</span>
            </a>
            @endif
            <a href="{{ route('visitors.create') }}"
               class="btn-neo bg-primary text-white text-[9px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Novo Visitante
            </a>
        </div>
    </di    {{-- ===== FUNIL VISUAL ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @php
            $funnelStages = [
                ['key' => 'new',        'label' => 'Novos',       'icon' => 'fa-user-plus',      'bg' => 'bg-white',    'text' => 'text-blue-500',   'border' => 'border-slate-100',  'badge' => 'bg-blue-500',   'status' => 'New',        'glow' => 'shadow-blue-200/40'],
                ['key' => 'returning',  'label' => 'Retornou',    'icon' => 'fa-rotate-right',   'bg' => 'bg-white',   'text' => 'text-amber-600',  'border' => 'border-slate-100', 'badge' => 'bg-amber-500',  'status' => 'Returning',  'glow' => 'shadow-amber-200/40'],
                ['key' => 'interested', 'label' => 'Interessado', 'icon' => 'fa-fire',           'bg' => 'bg-white',  'text' => 'text-orange-500', 'border' => 'border-slate-100','badge' => 'bg-orange-500', 'status' => 'Interested', 'glow' => 'shadow-orange-200/40'],
                ['key' => 'converted',  'label' => 'Convertido',  'icon' => 'fa-check-circle',   'bg' => 'bg-white',   'text' => 'text-emerald-600',  'border' => 'border-slate-100', 'badge' => 'bg-emerald-500',  'status' => 'Converted',  'glow' => 'shadow-emerald-200/40'],
                ['key' => 'radar',      'label' => 'Radar 48h',   'icon' => 'fa-satellite-dish', 'bg' => 'bg-white',    'text' => 'text-rose-500',   'border' => 'border-slate-100',  'badge' => 'bg-rose-500',   'status' => null,         'glow' => 'shadow-rose-200/40'],
            ];
            $totalFunnel = max(1, $funnel['new'] + $funnel['returning'] + $funnel['interested'] + $funnel['converted']);
        @endphp

        @foreach($funnelStages as $stage)
        <a href="{{ $stage['status'] ? route('visitors.index', ['status' => $stage['status']]) : route('operacional.radar') }}"
           class="card-neo p-6 border {{ $stage['border'] }} group hover:shadow-xl hover:{{ $stage['glow'] }} hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center {{ $stage['text'] }} group-hover:scale-110 transition-transform duration-500 shadow-inner">
                    <i class="fas {{ $stage['icon'] }} text-sm {{ $stage['key'] === 'radar' ? 'animate-pulse' : '' }}"></i>
                </div>
                @if($funnel[$stage['key']] > 0)
                    <span class="{{ $stage['badge'] }} text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow-sm">
                        {{ $funnel[$stage['key']] }}
                    </span>
                @endif
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tighter leading-none group-hover:text-primary transition-colors">{{ $funnel[$stage['key']] }}</p>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2">{{ $stage['label'] }}</p>
            @if($stage['status'] && $totalFunnel > 0)
            @php $pct = round($funnel[$stage['key']] / $totalFunnel * 100); @endphp
            <div class="mt-4 h-1 bg-slate-100 rounded-full overflow-hidden">
                <div class="{{ $stage['badge'] }} h-full rounded-full transition-all duration-1000" style="width: {{ $pct }}%"></div>
            </div>
            @endif
        </a>
        @endforeach
    </div>

    {{-- ===== FILTROS ===== --}}
    <div class="card-neo p-8 border-slate-100 shadow-xl shadow-slate-200/40">
        <form method="GET" action="{{ route('visitors.index') }}" class="flex flex-wrap gap-6 items-end">
            <div class="flex-1 min-w-[240px] space-y-2">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Busca Inteligente</label>
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs group-focus-within:text-accent transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nome ou telefone..."
                           class="input-neo pl-11 py-3.5 group-hover:border-slate-300">
                </div>
            </div>
            <div class="min-w-[160px] space-y-2">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Status</label>
                <select name="status" class="input-neo py-3.5">
                    <option value="">Todos</option>
                    <option value="New"        {{ request('status') === 'New'        ? 'selected' : '' }}>Novo</option>
                    <option value="Returning"  {{ request('status') === 'Returning'  ? 'selected' : '' }}>Retornou</option>
                    <option value="Interested" {{ request('status') === 'Interested' ? 'selected' : '' }}>Interessado</option>
                    <option value="Converted"  {{ request('status') === 'Converted'  ? 'selected' : '' }}>Convertido</option>
                    <option value="Inactive"   {{ request('status') === 'Inactive'   ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="min-w-[200px] space-y-2">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Célula</label>
                <select name="cell_id" class="input-neo py-3.5">
                    <option value="">Todas as Células</option>
                    @foreach($cells as $id => $name)
                        <option value="{{ $id }}" {{ request('cell_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="btn-neo bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 flex items-center gap-2 hover:bg-slate-900 shadow-lg shadow-slate-200 transition-all">
                    <i class="fas fa-filter text-xs"></i> Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status', 'cell_id']))
                <a href="{{ route('visitors.index') }}"
                   class="btn-neo bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-widest px-6 py-4 flex items-center gap-2 hover:border-slate-400 transition-all">
                    <i class="fas fa-times text-xs"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ===== TABELA DE VISITANTES ===== --}}
    <div class="card-neo !p-0 overflow-hidden shadow-xl shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="px-8 py-5">VISITANTE</th>
                        <th class="px-8 py-5">CONTATO</th>
                        <th class="px-8 py-5">CÉLULA RESPONSÁVEL</th>
                        <th class="px-8 py-5 text-center">ÚLTIMO CONTATO</th>
                        <th class="px-8 py-5 text-center">STATUS</th>
                        <th class="px-8 py-5 text-right">AÇÕES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($visitors as $visitor)
                    @php
                        $isUrgent = $visitor->status !== 'Converted' && $visitor->status !== 'Inactive'
                            && ($visitor->last_contact_at === null || $visitor->last_contact_at->lt(now()->subHours(48)));
                        $statusMap = [
                            'New'        => ['bg-blue-50 text-blue-600 border-blue-100',   'Novo'],
                            'Returning'  => ['bg-amber-50 text-amber-600 border-amber-100', 'Retornou'],
                            'Interested' => ['bg-orange-50 text-orange-600 border-orange-100', 'Interessado'],
                            'Converted'  => ['bg-emerald-50 text-emerald-600 border-emerald-100', 'Convertido'],
                            'Inactive'   => ['bg-slate-100 text-slate-500 border-slate-200',   'Inativo'],
                        ];
                        $sc = $statusMap[$visitor->status] ?? ['bg-slate-100 text-slate-500 border-slate-200', $visitor->status];
                    @endphp
                    <tr class="group hover:bg-slate-50/80 transition-all duration-300 cursor-pointer {{ $isUrgent ? 'bg-rose-50/30' : '' }}"
                        id="visitor-row-{{ $visitor->id }}"
                        onclick="window.location='{{ route('visitors.show', $visitor) }}'">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl {{ $isUrgent ? 'bg-white shadow-rose-200 shadow-md text-rose-500 border-rose-100 animate-pulse' : 'bg-white shadow-sm text-slate-800 border-slate-100' }} border flex items-center justify-center font-black text-lg shrink-0 group-hover:scale-110 transition-transform duration-500">
                                    {{ strtoupper(substr($visitor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-primary transition-colors flex items-center gap-2">
                                        {{ $visitor->name }}
                                        @if($isUrgent)
                                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                                        @endif
                                    </p>
                                    @if($visitor->how_did_you_know)
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Origem: {{ $visitor->how_did_you_know }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($visitor->phone)
                            <a href="tel:{{ $visitor->phone }}" onclick="event.stopPropagation()"
                               class="text-[11px] font-black text-slate-600 hover:text-accent flex items-center gap-2 transition-colors uppercase tracking-widest">
                                <i class="fas fa-phone text-slate-300 text-[10px]"></i> {{ $visitor->phone }}
                            </a>
                            @endif
                            @if($visitor->email)
                            <p class="text-[9px] font-bold text-slate-400 mt-1.5 flex items-center gap-2 uppercase tracking-widest">
                                <i class="fas fa-envelope text-slate-200 text-[10px]"></i> {{ $visitor->email }}
                            </p>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            @if($visitor->assignedCell)
                                <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">{{ $visitor->assignedCell->name }}</p>
                            @else
                                <span class="text-[9px] text-slate-200 font-black uppercase tracking-[0.3em]">Não atribuído</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($visitor->last_contact_at)
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-[10px] font-black {{ $isUrgent ? 'text-rose-500 animate-pulse' : 'text-slate-700' }} uppercase tracking-widest">
                                        {{ $visitor->last_contact_at->diffForHumans() }}
                                    </span>
                                    <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ $visitor->last_contact_at->format('d/m/Y') }}</span>
                                </div>
                            @else
                                <div class="inline-flex flex-col items-center">
                                    <span class="bg-rose-50 text-rose-500 font-black text-[9px] px-3 py-1 rounded-full border border-rose-100 uppercase tracking-widest">Pendente</span>
                                    <span class="text-[8px] font-black text-rose-300 mt-2 tracking-widest uppercase">Nunca contatado</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="font-black text-[9px] px-3 py-1 rounded-full border uppercase tracking-widest {{ $sc[0] }}">
                                {{ $sc[1] }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0"
                                 onclick="event.stopPropagation()">
                                <a href="{{ route('visitors.show', $visitor) }}"
                                   class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white hover:border-slate-800 hover:-translate-y-1 transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if($visitor->status !== 'Converted')
                                <button onclick="openContactModal({{ $visitor->id }}, '{{ addslashes($visitor->name) }}', '{{ $visitor->status }}')"
                                        class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 hover:-translate-y-1 transition-all shadow-sm">
                                    <i class="fas fa-phone text-xs"></i>
                                </button>
                                @endif
                                @if($visitor->status === 'Interested')
                                <form action="{{ route('visitors.convert', $visitor) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('ATENÇÃO: Deseja converter este visitante em discípulo agora?')"
                                            class="w-9 h-9 rounded-xl bg-white border border-emerald-100 flex items-center justify-center text-emerald-500 hover:bg-emerald-500 hover:text-white hover:-translate-y-1 transition-all shadow-sm"
                                            title="Converter em Membro">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center gap-5">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 border border-slate-100">
                                    <i class="fas fa-user-slash text-4xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Sem visitantes pendentes</p>
                                    <p class="text-xs text-slate-400 mt-1">Todos os contatos estão em dia ou foram convertidos.</p>
                                </div>
                                <a href="{{ route('visitors.create') }}"
                                   class="btn-neo bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 mt-2">
                                    <i class="fas fa-user-plus"></i> Novo Visitante
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($visitors->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $visitors->links() }}
        </div>
        @endif
    </div>

</div>

{{-- MODAL REGISTRAR CONTATO --}}
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 transition-all duration-500">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up border border-slate-100">
        <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-black text-slate-800 uppercase tracking-tight text-lg">Registrar Atendimento</h3>
                <p id="contactModalName" class="text-[10px] text-accent font-black uppercase tracking-[0.2em] mt-1.5"></p>
            </div>
            <button onclick="closeContactModal()" class="text-slate-300 hover:text-slate-600 transition-all w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-white hover:shadow-sm border border-transparent hover:border-slate-100">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <div class="p-10 space-y-8">
            <input type="hidden" id="contactVisitorId">
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Evoluir Status</label>
                <select id="contactStatus" class="input-neo py-4">
                    <option value="New">Novo (Sem Alteração)</option>
                    <option value="Returning">Retornou (Veio mais de 1x)</option>
                    <option value="Interested">Interessado (Pronto para conversão)</option>
                    <option value="Converted">Convertido (Já se tornou membro)</option>
                    <option value="Inactive">Inativo (Perda de contato)</option>
                </select>
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Relatório de Visita/Contato</label>
                <textarea id="contactNotes" rows="4" class="input-neo py-4" placeholder="Descreva brevemente como foi a interação..."></textarea>
            </div>
            <button onclick="submitContact()"
                    class="btn-neo bg-slate-800 text-white w-full py-5 text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-slate-900 shadow-xl shadow-slate-200 transition-all">
                <i class="fas fa-cloud-upload-alt text-sm"></i> PERSISTIR REGISTRO
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openContactModal(id, name, currentStatus) {
        document.getElementById('contactVisitorId').value = id;
        document.getElementById('contactModalName').textContent = name;
        document.getElementById('contactStatus').value = currentStatus;
        document.getElementById('contactNotes').value = '';
        const modal = document.getElementById('contactModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeContactModal() {
        const modal = document.getElementById('contactModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function submitContact() {
        const id     = document.getElementById('contactVisitorId').value;
        const status = document.getElementById('contactStatus').value;
        const notes  = document.getElementById('contactNotes').value;

        try {
            const res = await fetch(`/visitors/${id}/contact`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status, notes }),
            });

            const data = await res.json();

            if (data.success) {
                closeContactModal();
                // Animar remoção se convertido/inativo
                if (['Converted', 'Inactive'].includes(status)) {
                    const row = document.getElementById(`visitor-row-${id}`);
                    if (row) {
                        row.style.transition = 'all 0.5s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(50px)';
                        setTimeout(() => row.remove(), 500);
                    }
                } else {
                    // Feedback visual antes de recarregar
                    window.location.reload();
                }
            }
        } catch (e) { console.error(e); }
    }
</script>
@endpushscript>
@endpush
@endsection
