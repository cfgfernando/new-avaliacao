@extends('layouts.app')

@section('title', 'Gestão de Membros — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestão de Membros</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                {{ $members->total() }} membro(s) encontrado(s)
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('operacional.dashboard') }}"
               class="btn-neo bg-white border border-slate-200 text-slate-500 text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
                <i class="fas fa-gauge-high"></i> Dashboard
            </a>
            @can('create', App\Models\Member::class)
            <a href="{{ route('members.create') }}"
               class="btn-neo bg-primary text-white text-[9px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Novo Membro
            </a>
            @endcan
        </div>
    </div>

    {{-- ===== KPI STRIP ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $statuses = [
                ['label' => 'Ativos',       'value' => $stats['active'],       'color' => 'emerald', 'icon' => 'fa-circle-check'],
                ['label' => 'Inativos',      'value' => $stats['inactive'],     'color' => 'slate',   'icon' => 'fa-circle-pause'],
                ['label' => 'Batizados',     'value' => $stats['baptized'],     'color' => 'blue',    'icon' => 'fa-droplet'],
                ['label' => 'Com Discípulos','value' => $stats['withDisciples'],'color' => 'amber',   'icon' => 'fa-users-rays'],
            ];
        @endphp
        @foreach($statuses as $stat)
        <div class="card-neo p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-2xl bg-{{ $stat['color'] }}-50 flex items-center justify-center text-{{ $stat['color'] }}-500 shrink-0">
                <i class="fas {{ $stat['icon'] }} text-sm"></i>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800 tracking-tighter leading-none">{{ number_format($stat['value']) }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $stat['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== FILTROS ===== --}}
    <div class="card-neo p-6">
        <form method="GET" action="{{ route('members.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Busca</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nome ou email..."
                           class="input-neo pl-10 py-2.5">
                </div>
            </div>
            <div class="min-w-[140px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="input-neo py-2.5">
                    <option value="">Todos</option>
                    <option value="Active"      {{ request('status') === 'Active'      ? 'selected' : '' }}>Ativo</option>
                    <option value="Inactive"    {{ request('status') === 'Inactive'    ? 'selected' : '' }}>Inativo</option>
                    <option value="Transferred" {{ request('status') === 'Transferred' ? 'selected' : '' }}>Transferido</option>
                </select>
            </div>
            <div class="min-w-[180px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Célula</label>
                <select name="cell_id" class="input-neo py-2.5">
                    <option value="">Todas</option>
                    @foreach($accessibleCells as $id => $name)
                        <option value="{{ $id }}" {{ request('cell_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2 pb-0.5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="baptized_only" value="1" {{ request('baptized_only') ? 'checked' : '' }}
                           class="rounded border-slate-300 text-primary">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Batizados</span>
                </label>
            </div>
            <button type="submit"
                    class="btn-neo bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            @if(request()->anyFilled(['search', 'status', 'cell_id', 'baptized_only']))
            <a href="{{ route('members.index') }}"
               class="btn-neo bg-white border border-slate-200 text-slate-400 text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2">
                <i class="fas fa-times"></i> Limpar
            </a>
            @endif
        </form>
    </div>

    {{-- ===== TABELA DE MEMBROS ===== --}}
    <div class="card-neo !p-0 overflow-hidden shadow-xl shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="px-8 py-5">MEMBRO</th>
                        <th class="px-8 py-5">CÉLULA / LIDERANÇA</th>
                        <th class="px-8 py-5 text-center">BATISMO</th>
                        <th class="px-8 py-5 text-center">STATUS</th>
                        <th class="px-8 py-5 text-right">AÇÕES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($members as $member)
                    <tr class="group hover:bg-slate-50/80 transition-all duration-300 cursor-pointer"
                        onclick="window.location='{{ route('members.show', $member) }}'">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-800 font-black text-lg shrink-0 group-hover:scale-110 transition-transform duration-500">
                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-800 leading-tight uppercase tracking-tight group-hover:text-primary transition-colors">{{ $member->user->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ $member->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($member->user->cell)
                                <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">{{ $member->user->cell->name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest flex items-center gap-1">
                                    <i class="fas fa-user-tie text-[8px]"></i> {{ $member->user->cell->leader->name ?? '—' }}
                                </p>
                            @else
                                <span class="text-[9px] text-slate-300 uppercase tracking-[0.2em] font-black">Sem célula atribuída</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($member->baptism_date)
                                <div class="inline-flex flex-col items-center">
                                    <span class="bg-blue-50 text-blue-600 font-black text-[9px] px-3 py-1 rounded-full border border-blue-100 uppercase tracking-widest">
                                        <i class="fas fa-droplet text-[8px] mr-1"></i> Batizado
                                    </span>
                                    <span class="text-[9px] font-black text-slate-400 mt-2 tracking-widest">{{ $member->baptism_date->format('d/m/Y') }}</span>
                                </div>
                            @else
                                <span class="text-[9px] text-slate-200 font-black uppercase tracking-[0.3em]">Não batizado</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @php
                                $statusStyle = match($member->status) {
                                    'Active'      => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Inactive'    => 'bg-slate-100 text-slate-500 border-slate-200',
                                    'Transferred' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'Deceased'    => 'bg-rose-50 text-rose-500 border-rose-100',
                                    default       => 'bg-slate-100 text-slate-500 border-slate-200',
                                };
                            @endphp
                            <span class="font-black text-[9px] px-3 py-1 rounded-full border uppercase tracking-widest {{ $statusStyle }}">
                                {{ $member->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0"
                                 onclick="event.stopPropagation()">
                                <a href="{{ route('members.show', $member) }}"
                                   class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white hover:border-slate-800 hover:-translate-y-1 transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}"
                                   class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-blue-500 hover:text-white hover:border-blue-500 hover:-translate-y-1 transition-all shadow-sm">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center gap-5">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 border border-slate-100">
                                    <i class="fas fa-user-slash text-4xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Base de dados vazia</p>
                                    <p class="text-xs text-slate-400 mt-1">Nenhum membro corresponde aos filtros aplicados.</p>
                                </div>
                                @can('create', App\Models\Member::class)
                                <a href="{{ route('members.create') }}"
                                   class="btn-neo bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 mt-2">
                                    <i class="fas fa-plus"></i> Cadastrar Novo
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
