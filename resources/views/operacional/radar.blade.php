@extends('layouts.app')

@section('title', 'Radar 48h — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping inline-block"></span>
                Módulo Operacional · Alerta
            </p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Radar 48 Horas</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Visitantes aguardando contato</p>
        </div>
        <a href="{{ route('operacional.dashboard') }}" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[9px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2 hover:border-primary hover:text-primary transition-all self-start">
            <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="card-neo p-5 flex flex-col gap-2">
            <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500">
                <i class="fas fa-triangle-exclamation text-sm"></i>
            </div>
            <p class="text-2xl font-black text-rose-500 tracking-tighter">{{ $critical->count() }}</p>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Críticos (+72h)</p>
        </div>
        <div class="card-neo p-5 flex flex-col gap-2">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="fas fa-clock text-sm"></i>
            </div>
            <p class="text-2xl font-black text-amber-500 tracking-tighter">{{ $warning->count() }}</p>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Atenção (48–72h)</p>
        </div>
        <div class="card-neo p-5 flex flex-col gap-2">
            <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                <i class="fas fa-users text-sm"></i>
            </div>
            <p class="text-2xl font-black text-slate-700 tracking-tighter">{{ $critical->count() + $warning->count() }}</p>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total no Radar</p>
        </div>
    </div>

    {{-- LISTA CRÍTICOS --}}
    @if($critical->count() > 0)
    <div class="card-neo !p-0 overflow-hidden border-l-4 border-rose-400">
        <div class="px-6 py-4 border-b border-rose-100 bg-rose-50/50 flex items-center gap-3">
            <i class="fas fa-triangle-exclamation text-rose-500"></i>
            <p class="text-[10px] font-black text-rose-700 uppercase tracking-widest">Críticos — Mais de 72 horas sem contato</p>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($critical as $visitor)
            <div class="flex items-center gap-4 px-6 py-5 group" id="radar-row-{{ $visitor->id }}">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 font-black shrink-0">
                    {{ strtoupper(substr($visitor->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $visitor->name }}</p>
                    <div class="flex flex-wrap gap-3 mt-1">
                        @if($visitor->phone)
                        <a href="tel:{{ $visitor->phone }}" class="text-[9px] font-bold text-slate-400 hover:text-primary flex items-center gap-1">
                            <i class="fas fa-phone"></i> {{ $visitor->phone }}
                        </a>
                        @endif
                        @if($visitor->assignedCell)
                        <span class="text-[9px] font-bold text-slate-400 flex items-center gap-1">
                            <i class="fas fa-sitemap"></i> {{ $visitor->assignedCell->name }}
                        </span>
                        @endif
                        <span class="text-[9px] font-black text-rose-500 flex items-center gap-1">
                            <i class="fas fa-clock"></i> 
                            {{ $visitor->last_contact_at ? 'Último contato ' . $visitor->last_contact_at->diffForHumans() : 'Nunca contactado' }}
                        </span>
                    </div>
                </div>
                <button onclick="openContactModal({{ $visitor->id }}, '{{ addslashes($visitor->name) }}')"
                    class="btn-neo bg-rose-50 border border-rose-100 text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 text-[9px] font-black uppercase tracking-widest px-4 py-2 flex items-center gap-2 transition-all shrink-0">
                    <i class="fas fa-phone-volume"></i> Registrar Contato
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- LISTA ATENÇÃO --}}
    @if($warning->count() > 0)
    <div class="card-neo !p-0 overflow-hidden border-l-4 border-amber-400">
        <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/50 flex items-center gap-3">
            <i class="fas fa-clock text-amber-500"></i>
            <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Atenção — Entre 48 e 72 horas</p>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($warning as $visitor)
            <div class="flex items-center gap-4 px-6 py-4 group" id="radar-row-{{ $visitor->id }}">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 font-black shrink-0">
                    {{ strtoupper(substr($visitor->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ $visitor->name }}</p>
                    <div class="flex flex-wrap gap-3 mt-1">
                        @if($visitor->phone)
                        <a href="tel:{{ $visitor->phone }}" class="text-[9px] font-bold text-slate-400 hover:text-primary flex items-center gap-1">
                            <i class="fas fa-phone"></i> {{ $visitor->phone }}
                        </a>
                        @endif
                        <span class="text-[9px] font-black text-amber-500 flex items-center gap-1">
                            <i class="fas fa-clock"></i> 
                            {{ $visitor->last_contact_at ? $visitor->last_contact_at->diffForHumans() : 'Nunca contactado' }}
                        </span>
                    </div>
                </div>
                <button onclick="openContactModal({{ $visitor->id }}, '{{ addslashes($visitor->name) }}')"
                    class="btn-neo bg-white border border-slate-200 text-slate-500 hover:border-amber-400 hover:text-amber-600 text-[9px] font-black uppercase tracking-widest px-4 py-2 flex items-center gap-2 transition-all shrink-0">
                    <i class="fas fa-phone"></i> Contato
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($critical->count() === 0 && $warning->count() === 0)
    <div class="card-neo p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-double text-emerald-500 text-2xl"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Todos os visitantes estão em dia!</p>
        <p class="text-xs text-slate-400 mt-1">Nenhum visitante aguarda contato há mais de 48 horas.</p>
    </div>
    @endif

</div>

{{-- MODAL REGISTRAR CONTATO --}}
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden animate-reveal-up">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-black text-slate-800 uppercase tracking-tight text-sm">Registrar Contato</h3>
                <p id="contactModalName" class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5"></p>
            </div>
            <button onclick="closeContactModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <input type="hidden" id="contactVisitorId">
            <div>
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Status do Contato</label>
                <select id="contactStatus" class="input-neo py-2.5">
                    <option value="contacted">Contactado com sucesso</option>
                    <option value="no_answer">Não atendeu</option>
                    <option value="will_return">Prometeu retornar</option>
                    <option value="not_interested">Não tem interesse</option>
                </select>
            </div>
            <div>
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Observações</label>
                <textarea id="contactNotes" rows="3" class="input-neo py-3" placeholder="Registre detalhes do contato..."></textarea>
            </div>
            <button onclick="submitContact()" class="btn-neo bg-primary text-white w-full py-3 text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-check"></i> Confirmar Registro
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openContactModal(id, name) {
        document.getElementById('contactVisitorId').value = id;
        document.getElementById('contactModalName').textContent = name;
        document.getElementById('contactNotes').value = '';
        document.getElementById('contactModal').classList.remove('hidden');
        document.getElementById('contactModal').classList.add('flex');
    }

    function closeContactModal() {
        document.getElementById('contactModal').classList.add('hidden');
        document.getElementById('contactModal').classList.remove('flex');
    }

    async function submitContact() {
        const id     = document.getElementById('contactVisitorId').value;
        const status = document.getElementById('contactStatus').value;
        const notes  = document.getElementById('contactNotes').value;

        try {
            const response = await fetch(`/radar/${id}/contact`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status, notes })
            });

            const data = await response.json();

            if (data.success) {
                closeContactModal();
                const row = document.getElementById(`radar-row-${id}`);
                if (row) {
                    row.style.transition = 'all 0.4s ease';
                    row.style.opacity = '0';
                    row.style.height = '0';
                    setTimeout(() => row.remove(), 400);
                }
                // Toast
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Contato registrado!', showConfirmButton: false, timer: 3000 });
                }
            }
        } catch (e) {
            console.error(e);
        }
    }
</script>
@endpush
@endsection
