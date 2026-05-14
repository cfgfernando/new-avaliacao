@extends('layouts.app')

@section('title', 'Fechamento Contábil')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Fechamento Contábil</h2>
            <p class="text-primary-light font-medium mt-1">Gerencie o bloqueio de períodos para garantir a integridade dos dados.</p>
        </div>
        <button onclick="openFinanceModal('closureModal')" class="btn-neo btn-primary px-8">
            <i class="fas fa-lock"></i>
            <span>Novo Fechamento</span>
        </button>
    </div>

    <!-- Lista de Fechamentos -->
    <div class="card-neo overflow-hidden">
        <div class="p-8 pb-4 border-b border-slate-50">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-shield-halved text-accent"></i>
                Histórico de Períodos
            </h3>
        </div>
        <div class="p-4 pt-0">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="w-32">Ano</th>
                        <th class="w-32">Mês</th>
                        <th>Status</th>
                        <th>Fechado por</th>
                        <th>Data do Bloqueio</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($closures as $closure)
                    <tr class="group cursor-pointer hover:bg-slate-50/80 transition-colors" onclick="editClosure({{ $closure->id }}, {{ $closure->year }}, {{ $closure->month }})">
                        <td class="font-money text-lg text-slate-800">{{ $closure->year }}</td>
                        <td>
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-[9px] font-black text-slate-800 uppercase">
                                {{ date("F", mktime(0, 0, 0, $closure->month, 10)) }}
                            </span>
                        </td>
                        <td>
                            @if($closure->status === 'closed' || $closure->status === 'locked')
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[9px] font-black uppercase flex items-center w-fit gap-2">
                                    <i class="fas fa-lock text-[8px]"></i> BLOQUEADO
                                </span>
                            @else
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase flex items-center w-fit gap-2">
                                    <i class="fas fa-lock-open text-[8px]"></i> ABERTO
                                </span>
                            @endif
                        </td>
                        <td class="text-sm font-bold text-slate-600">
                            {{ $closure->lockedBy->name ?? 'N/A' }}
                        </td>
                        <td class="text-sm font-money text-slate-400">
                            {{ $closure->locked_at ? $closure->locked_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                @if($closure->status !== 'open')
                                <form action="{{ route('admin.finance.closures.update', $closure->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Reabrir Período">
                                        <i class="fas fa-unlock-keyhole text-[10px]"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.finance.closures.destroy', $closure->id) }}" method="POST" onsubmit="return confirm('Excluir este registro de fechamento?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Excluir">
                                        <i class="fas fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhum período bloqueado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Novo Fechamento -->
<div id="closureModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto custom-scrollbar">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md m-auto overflow-hidden animate-reveal-up border border-white/20 flex flex-col">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Novo Fechamento</h3>
            <button onclick="closeFinanceModal('closureModal')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.finance.closures.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Ano</label>
                    <select name="year" class="input-neo w-full">
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Mês</label>
                    <select name="month" class="input-neo w-full">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                {{ date("F", mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Observações / Motivo</label>
                <textarea name="notes" class="input-neo w-full h-24" placeholder="Ex: Fechamento mensal auditado pelo conselho fiscal."></textarea>
            </div>
            <div class="pt-4">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-xs font-black tracking-widest uppercase">
                    Executar Bloqueio
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
    }
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('#closureModal form')[0].reset();
    }

    function editClosure(id, year, month) {
        openFinanceModal('closureModal');
        $('#closureModal h3').text('Editando Fechamento #' + id);
        $('#closureModal select[name="year"]').val(year);
        $('#closureModal select[name="month"]').val(month);
        $('#closureModal form').attr('action', `/admin/finance/closures/${id}`);
        if ($('#closureModal form input[name="_method"]').length === 0) {
            $('#closureModal form').prepend('<input type="hidden" name="_method" value="PUT">');
        }
    }
</script>
@endpush
