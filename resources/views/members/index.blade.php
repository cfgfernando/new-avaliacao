@extends('layouts.app')

@section('header_title', 'Gestão de Membros')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-title tracking-tight">Membros & Discípulos</h1>
            <p class="text-slate-500 font-medium mt-1">Gerencie a base de membros e acompanhe o crescimento espiritual.</p>
        </div>
        <a href="{{ route('members.create') }}" class="btn-primary">
            <i class="fas fa-user-plus"></i>
            <span>Novo Membro</span>
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card-elite p-8 bg-white/50 backdrop-blur-sm">
        <form action="{{ route('members.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Buscar</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nome ou email..."
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status</label>
                <select name="status" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                    <option value="">Todos</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Ativo</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inativo</option>
                    <option value="Visitor" {{ request('status') == 'Visitor' ? 'selected' : '' }}>Visitante</option>
                    <option value="Converted" {{ request('status') == 'Converted' ? 'selected' : '' }}>Convertido</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Célula</label>
                <select name="cell_id" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                    <option value="">Todas</option>
                    @foreach($accessibleCells as $id => $name)
                        <option value="{{ $id }}" {{ request('cell_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end pb-1">
                <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-black/10">
                    Filtrar Resultados
                </button>
            </div>
        </form>
    </div>

    <!-- Members Table -->
    <div class="card-elite">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Membro</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Vínculo</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($members as $member)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-lg group-hover:scale-110 transition-transform">
                                    {{ substr($member->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-title">{{ $member->user->name }}</div>
                                    <div class="text-[11px] font-bold text-slate-400">{{ $member->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-xs font-bold text-slate-600">
                                <i class="fas fa-church text-accent mr-2 opacity-50"></i>
                                {{ $member->user->cell->name ?? 'Sem Célula' }}
                            </div>
                            <div class="text-[10px] font-medium text-slate-400 mt-1">
                                Líder: {{ $member->user->cell->leader->name ?? '-' }}
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusClasses = [
                                    'Active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'Inactive' => 'bg-slate-50 text-slate-600 border-slate-100',
                                    'Visitor' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'Converted' => 'bg-blue-50 text-blue-700 border-blue-100',
                                ];
                                $statusClass = $statusClasses[$member->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                {{ $member->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('members.show', $member) }}" class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-accent hover:text-white transition-all">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white transition-all">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <i class="fas fa-user-slash text-4xl"></i>
                                </div>
                                <p class="text-slate-400 font-bold">Nenhum membro encontrado com os filtros aplicados.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
        <div class="px-8 py-6 bg-slate-50/30 border-t border-slate-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
