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
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Membro</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Célula</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Batismo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors group cursor-pointer"
                        onclick="window.location='{{ route('members.show', $member) }}'">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 font-black text-sm shrink-0">
                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 leading-tight">{{ $member->user->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $member->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($member->user->cell)
                            <p class="text-xs font-bold text-gray-700 uppercase tracking-tight">{{ $member->user->cell->name }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $member->user->cell->leader->name ?? '—' }}</p>
                            @else
                            <span class="text-[10px] text-gray-300 uppercase tracking-widest font-bold">Sem célula</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($member->baptism_date)
                                <div class="flex flex-col items-center">
                                    <span class="bg-blue-50 text-blue-600 font-medium text-xs px-2.5 py-0.5 rounded-full border border-blue-200">
                                        <i class="fas fa-droplet text-[9px]"></i> Batizado
                                    </span>
                                    <span class="text-[9px] text-gray-400 mt-1">{{ $member->baptism_date->format('d/m/Y') }}</span>
                                </div>
                            @else
                                <span class="text-[9px] text-gray-300 font-bold uppercase tracking-widest">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusConfig = match($member->status) {
                                    'Active'      => ['bg-green-50 text-green-600 border-green-200', 'Ativo'],
                                    'Inactive'    => ['bg-gray-100 text-gray-500 border-gray-200', 'Inativo'],
                                    'Transferred' => ['bg-blue-50 text-blue-600 border-blue-200', 'Transferido'],
                                    'Deceased'    => ['bg-red-50 text-red-500 border-red-200', 'Falecido'],
                                    default       => ['bg-gray-100 text-gray-500 border-gray-200', $member->status],
                                };
                            @endphp
                            <span class="font-medium text-xs px-2.5 py-0.5 rounded-full border {{ $statusConfig[0] }}">
                                {{ $statusConfig[1] }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                 onclick="event.stopPropagation()">
                                <a href="{{ route('members.show', $member) }}"
                                   class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}"
                                   class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-blue-500 hover:text-white hover:border-blue-500 transition-all">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-200">
                                    <i class="fas fa-user-slash text-3xl"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nenhum membro encontrado.</p>
                                @can('create', App\Models\Member::class)
                                <a href="{{ route('members.create') }}"
                                   class="text-[9px] font-black text-amber-500 uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                                    Cadastrar o primeiro <i class="fas fa-arrow-right"></i>
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
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
