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
    </div>

    {{-- ===== FUNIL VISUAL ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        @php
            $funnelStages = [
                ['key' => 'new',        'label' => 'Novos',       'icon' => 'fa-user-plus',      'bg' => 'bg-blue-50',    'text' => 'text-blue-500',   'border' => 'border-blue-100',  'badge' => 'bg-blue-500',   'status' => 'New'],
                ['key' => 'returning',  'label' => 'Retornou',    'icon' => 'fa-rotate-right',   'bg' => 'bg-amber-50',   'text' => 'text-amber-600',  'border' => 'border-amber-100', 'badge' => 'bg-amber-500',  'status' => 'Returning'],
                ['key' => 'interested', 'label' => 'Interessado', 'icon' => 'fa-fire',           'bg' => 'bg-orange-50',  'text' => 'text-orange-500', 'border' => 'border-orange-100','badge' => 'bg-orange-500', 'status' => 'Interested'],
                ['key' => 'converted',  'label' => 'Convertido',  'icon' => 'fa-check-circle',   'bg' => 'bg-green-50',   'text' => 'text-green-600',  'border' => 'border-green-100', 'badge' => 'bg-green-500',  'status' => 'Converted'],
                ['key' => 'radar',      'label' => 'Radar 48h',   'icon' => 'fa-satellite-dish', 'bg' => 'bg-rose-50',    'text' => 'text-rose-500',   'border' => 'border-rose-100',  'badge' => 'bg-rose-500',   'status' => null],
            ];
            $totalFunnel = max(1, $funnel['new'] + $funnel['returning'] + $funnel['interested'] + $funnel['converted']);
        @endphp

        @foreach($funnelStages as $stage)
        <a href="{{ $stage['status'] ? route('visitors.index', ['status' => $stage['status']]) : route('operacional.radar') }}"
           class="card-neo p-5 {{ $stage['bg'] }} border {{ $stage['border'] }} group hover:shadow-md hover:scale-[1.02] transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-white/70 flex items-center justify-center {{ $stage['text'] }}">
                    <i class="fas {{ $stage['icon'] }} text-sm"></i>
                </div>
                @if($funnel[$stage['key']] > 0)
                    <span class="{{ $stage['badge'] }} text-white text-[9px] font-black px-2 py-0.5 rounded-full">
                        {{ $funnel[$stage['key']] }}
                    </span>
                @endif
            </div>
            <p class="text-2xl font-black {{ $stage['text'] }} tracking-tighter leading-none">{{ $funnel[$stage['key']] }}</p>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $stage['label'] }}</p>
            @if($stage['status'] && $totalFunnel > 0)
            @php $pct = round($funnel[$stage['key']] / $totalFunnel * 100); @endphp
            <div class="mt-3 h-1 bg-white/50 rounded-full overflow-hidden">
                <div class="{{ $stage['badge'] }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-[8px] font-black text-slate-400 mt-1">{{ $pct }}% do total</p>
            @endif
        </a>
        @endforeach
    </div>

    {{-- ===== FILTROS ===== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('visitors.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Busca</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nome ou telefone..."
                           class="input-neo pl-10 py-2.5">
                </div>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="input-neo py-2.5">
                    <option value="">Todos</option>
                    <option value="New"        {{ request('status') === 'New'        ? 'selected' : '' }}>Novo</option>
                    <option value="Returning"  {{ request('status') === 'Returning'  ? 'selected' : '' }}>Retornou</option>
                    <option value="Interested" {{ request('status') === 'Interested' ? 'selected' : '' }}>Interessado</option>
                    <option value="Converted"  {{ request('status') === 'Converted'  ? 'selected' : '' }}>Convertido</option>
                    <option value="Inactive"   {{ request('status') === 'Inactive'   ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="min-w-[180px]">
                <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Célula</label>
                <select name="cell_id" class="input-neo py-2.5">
                    <option value="">Todas</option>
                    @foreach($cells as $id => $name)
                        <option value="{{ $id }}" {{ request('cell_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="btn-neo bg-[#1c2434] text-white text-[9px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            @if(request()->anyFilled(['search', 'status', 'cell_id']))
            <a href="{{ route('visitors.index') }}"
               class="btn-neo bg-white border border-gray-200 text-gray-400 text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2">
                <i class="fas fa-times"></i> Limpar
            </a>
            @endif
        </form>
    </div>

    {{-- ===== TABELA DE VISITANTES ===== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Visitante</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Contato</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Célula Responsável</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Último Contato</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $visitor)
                    @php
                        $isUrgent = $visitor->status !== 'Converted' && $visitor->status !== 'Inactive'
                            && ($visitor->last_contact_at === null || $visitor->last_contact_at->lt(now()->subHours(48)));
                        $statusMap = [
                            'New'        => ['bg-blue-50 text-blue-600 border-blue-200',   'Novo'],
                            'Returning'  => ['bg-amber-50 text-amber-600 border-amber-200', 'Retornou'],
                            'Interested' => ['bg-orange-50 text-orange-600 border-orange-200', 'Interessado'],
                            'Converted'  => ['bg-green-50 text-green-600 border-green-200', 'Convertido'],
                            'Inactive'   => ['bg-gray-100 text-gray-500 border-gray-200',   'Inativo'],
                        ];
                        $sc = $statusMap[$visitor->status] ?? ['bg-gray-100 text-gray-500 border-gray-200', $visitor->status];
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors group {{ $isUrgent ? 'bg-rose-50/30' : '' }}"
                        id="visitor-row-{{ $visitor->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 rounded-xl {{ $isUrgent ? 'bg-rose-100 text-rose-500' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center font-black text-sm shrink-0">
                                    {{ strtoupper(substr($visitor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                        {{ $visitor->name }}
                                        @if($isUrgent)
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping inline-block"></span>
                                        @endif
                                    </p>
                                    @if($visitor->how_did_you_know)
                                        <p class="text-[9px] text-gray-400 mt-0.5">Via: {{ $visitor->how_did_you_know }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($visitor->phone)
                            <a href="tel:{{ $visitor->phone }}"
                               class="text-xs font-bold text-gray-600 hover:text-amber-600 flex items-center gap-1.5 transition-colors">
                                <i class="fas fa-phone text-gray-300"></i> {{ $visitor->phone }}
                            </a>
                            @endif
                            @if($visitor->email)
                            <p class="text-[9px] text-gray-400 mt-0.5 flex items-center gap-1">
                                <i class="fas fa-envelope text-gray-200"></i> {{ $visitor->email }}
                            </p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($visitor->assignedCell)
                                <p class="text-xs font-bold text-gray-700">{{ $visitor->assignedCell->name }}</p>
                            @else
                                <span class="text-[9px] text-gray-300 font-bold uppercase tracking-widest">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($visitor->last_contact_at)
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-bold {{ $isUrgent ? 'text-rose-500' : 'text-gray-600' }}">
                                        {{ $visitor->last_contact_at->diffForHumans() }}
                                    </span>
                                    <span class="text-[9px] text-gray-400">{{ $visitor->last_contact_at->format('d/m/Y') }}</span>
                                </div>
                            @else
                                <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest">Nunca</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-medium text-xs px-2.5 py-0.5 rounded-full border {{ $sc[0] }}">
                                {{ $sc[1] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('visitors.show', $visitor) }}"
                                   class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if($visitor->status !== 'Converted')
                                <button onclick="openContactModal({{ $visitor->id }}, '{{ addslashes($visitor->name) }}', '{{ $visitor->status }}')"
                                        class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-blue-500 hover:text-white hover:border-blue-500 transition-all">
                                    <i class="fas fa-phone text-xs"></i>
                                </button>
                                @endif
                                @if($visitor->status === 'Interested')
                                <form action="{{ route('visitors.convert', $visitor) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Marcar {{ addslashes($visitor->name) }} como convertido?')"
                                            class="w-8 h-8 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center text-green-500 hover:bg-green-500 hover:text-white transition-all"
                                            title="Converter">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-200">
                                    <i class="fas fa-user-slash text-3xl"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nenhum visitante encontrado.</p>
                                <a href="{{ route('visitors.create') }}"
                                   class="text-[9px] font-black text-amber-500 uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                                    Cadastrar o primeiro <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($visitors->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $visitors->links() }}
        </div>
        @endif
    </div>

</div>

{{-- MODAL REGISTRAR CONTATO --}}
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <h3 class="font-black text-gray-800 uppercase tracking-tight text-sm">Registrar Contato</h3>
                <p id="contactModalName" class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5"></p>
            </div>
            <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-700 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <input type="hidden" id="contactVisitorId">
            <div>
                <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Atualizar Status</label>
                <select id="contactStatus" class="input-neo py-2.5">
                    <option value="New">Novo</option>
                    <option value="Returning">Retornou</option>
                    <option value="Interested">Interessado</option>
                    <option value="Converted">Convertido</option>
                    <option value="Inactive">Inativo</option>
                </select>
            </div>
            <div>
                <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Observações do Contato</label>
                <textarea id="contactNotes" rows="3" class="input-neo py-3" placeholder="Descreva o resultado do contato..."></textarea>
            </div>
            <button onclick="submitContact()"
                    class="btn-neo bg-[#f59e0b] text-white w-full py-3 text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#d97706] transition-colors">
                <i class="fas fa-check"></i> Confirmar Registro
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
                        row.style.transition = 'opacity 0.4s, height 0.4s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 400);
                    }
                } else {
                    // Reload suave para atualizar status
                    window.location.reload();
                }
            }
        } catch (e) { console.error(e); }
    }
</script>
@endpush
@endsection
