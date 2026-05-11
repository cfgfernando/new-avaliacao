@extends('layouts.app')

@section('header_title', 'Gestão de Membros')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
            <h1 class="text-4xl font-black text-white leading-none tracking-tighter">MEMBROS <br><span class="text-accent">& DISCÍPULOS.</span></h1>
            <p class="text-neutral-500 font-medium mt-4">Gestão estratégica de crescimento espiritual.</p>
        </div>
        <a href="{{ route('members.create') }}" class="btn-neo">
            <i class="fas fa-user-plus"></i>
            <span>Novo Registro</span>
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card-neo p-12 bg-primary-card mb-8">
        <form action="{{ route('members.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-accent">Busca Nominal</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="NOME OU EMAIL..."
                       class="input-neo">
            </div>

            <div class="space-y-3">
                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-neutral-600">Status</label>
                <select name="status" class="input-neo">
                    <option value="">TODOS</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>ATIVO</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>INATIVO</option>
                    <option value="Visitor" {{ request('status') == 'Visitor' ? 'selected' : '' }}>VISITANTE</option>
                    <option value="Converted" {{ request('status') == 'Converted' ? 'selected' : '' }}>CONVERTIDO</option>
                </select>
            </div>

            <div class="space-y-3">
                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-neutral-600">Unidade/Célula</label>
                <select name="cell_id" class="input-neo">
                    <option value="">TODAS</option>
                    @foreach($accessibleCells as $id => $name)
                        <option value="{{ $id }}" {{ request('cell_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="btn-neo w-full">
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
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-12 py-8">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 bg-neutral-900 flex items-center justify-center text-accent font-black border border-white/5">
                                    {{ substr($member->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-white uppercase tracking-widest">{{ $member->user->name }}</div>
                                    <div class="text-[10px] font-bold text-neutral-600 tracking-widest">{{ $member->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            <div class="text-[10px] font-black text-white uppercase tracking-widest">
                                <i class="fas fa-church text-accent mr-3"></i>
                                {{ $member->user->cell->name ?? 'SEM CÉLULA' }}
                            </div>
                            <div class="text-[9px] font-bold text-neutral-600 mt-2 uppercase">
                                LÍDER: {{ $member->user->cell->leader->name ?? '-' }}
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            <span class="px-4 py-2 border text-[9px] font-black uppercase tracking-[0.2em] {{ $member->status == 'Active' ? 'border-accent text-accent' : 'border-neutral-700 text-neutral-500' }}">
                                {{ $member->status }}
                            </span>
                        </td>
                        <td class="px-12 py-8 text-right">
                            <div class="flex items-center justify-end gap-6 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('members.show', $member) }}" class="text-neutral-500 hover:text-white transition-all">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="text-neutral-500 hover:text-accent transition-all">
                                    <i class="fas fa-pen"></i>
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
