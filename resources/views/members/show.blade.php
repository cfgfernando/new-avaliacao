@extends('layouts.app')

@section('title', $member->user->name . ' — Perfil de Membro')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- BREADCRUMB --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('members.index') }}"
           class="flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            <i class="fas fa-arrow-left"></i> Membros
        </a>
        @can('update', $member)
        <a href="{{ route('members.edit', $member) }}"
           class="btn-neo bg-white border border-slate-200 text-slate-600 text-[9px] font-black uppercase tracking-widest px-4 py-2 flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
            <i class="fas fa-pen"></i> Editar
        </a>
        @endcan
    </div>

    {{-- PROFILE HERO --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-[#1c2434] to-slate-700 p-8 flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="w-20 h-20 rounded-2xl bg-amber-400/20 border border-amber-400/30 flex items-center justify-center text-amber-300 font-black text-3xl shrink-0">
                {{ strtoupper(substr($member->user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-xl font-black text-white uppercase tracking-tight">{{ $member->user->name }}</h1>
                    @php
                        $heroStatus = match($member->status) {
                            'Active'   => 'bg-green-400/20 text-green-300 border-green-400/30',
                            'Inactive' => 'bg-gray-400/20 text-gray-300 border-gray-400/30',
                            default    => 'bg-blue-400/20 text-blue-300 border-blue-400/30',
                        };
                    @endphp
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full border {{ $heroStatus }}">
                        {{ $member->status }}
                    </span>
                    @if($member->is_baptized)
                        <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full bg-blue-400/20 text-blue-300 border border-blue-400/30">
                            <i class="fas fa-droplet text-[7px]"></i> Batizado
                        </span>
                    @endif
                    @if($member->is_tither)
                        <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30">
                            <i class="fas fa-hand-holding-heart text-[7px]"></i> Dizimista
                        </span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-5 text-white/60 text-xs font-bold">
                    <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-white/30"></i> {{ $member->user->email }}</span>
                    @if($member->user->cell)
                        <span class="flex items-center gap-1.5"><i class="fas fa-church text-white/30"></i> {{ $member->user->cell->name }}</span>
                    @endif
                    @if($member->mentor)
                        <span class="flex items-center gap-1.5"><i class="fas fa-user-tie text-white/30"></i> Mentor: {{ $member->mentor->name }}</span>
                    @endif
                    <span class="flex items-center gap-1.5"><i class="fas fa-calendar text-white/30"></i> Desde {{ $member->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- KPI STRIP --}}
        <div class="grid grid-cols-3 divide-x divide-gray-100">
            <div class="px-6 py-5 text-center">
                <p class="text-2xl font-black text-gray-800 tracking-tighter">{{ $member->disciples->count() }}</p>
                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mt-1">Discípulos</p>
            </div>
            <div class="px-6 py-5 text-center">
                <p class="text-sm font-black text-gray-800 tracking-tight">
                    {{ $member->baptism_date ? $member->baptism_date->format('d/m/Y') : '—' }}
                </p>
                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mt-1">Data Batismo</p>
            </div>
            <div class="px-6 py-5 text-center">
                <p class="text-sm font-black text-gray-800 tracking-tight">
                    {{ $member->user->cell->node->name ?? '—' }}
                </p>
                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mt-1">Setor</p>
            </div>
        </div>
    </div>

    {{-- CONTEÚDO --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- INFO ESPIRITUAL --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest pb-4 mb-4 border-b border-gray-100">
                    Informações Espirituais
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @php
                        $infoItems = [
                            ['label' => 'Batizado',     'value' => $member->is_baptized ? 'Sim' : 'Não', 'icon' => 'fa-droplet',      'highlight' => $member->is_baptized],
                            ['label' => 'Dizimista',    'value' => $member->is_tither   ? 'Sim' : 'Não', 'icon' => 'fa-hand-holding-heart', 'highlight' => $member->is_tither],
                            ['label' => 'Status',       'value' => $member->status,                      'icon' => 'fa-circle-check',  'highlight' => $member->status === 'Active'],
                            ['label' => 'Mentor',       'value' => $member->mentor->name ?? 'Não atribuído', 'icon' => 'fa-user-tie',  'highlight' => false],
                            ['label' => 'Célula',       'value' => $member->user->cell->name ?? 'Sem célula', 'icon' => 'fa-church',   'highlight' => false],
                            ['label' => 'Cadastro',     'value' => $member->created_at->format('d/m/Y'), 'icon' => 'fa-calendar',     'highlight' => false],
                        ];
                    @endphp
                    @foreach($infoItems as $item)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl {{ $item['highlight'] ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400' }} flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas {{ $item['icon'] }} text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest">{{ $item['label'] }}</p>
                            <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $item['value'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($member->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest pb-4 mb-4 border-b border-gray-100">
                    Observações
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $member->notes }}</p>
            </div>
            @endif
        </div>

        {{-- DISCÍPULOS --}}
        <div class="space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Discípulos</p>
                    <span class="bg-amber-50 text-amber-600 text-xs font-bold px-2.5 py-0.5 rounded-full border border-amber-200">
                        {{ $member->disciples->count() }}
                    </span>
                </div>
                <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                    @forelse($member->disciples as $disciple)
                    <a href="{{ route('members.show', $disciple) }}"
                       class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 font-black text-xs shrink-0">
                            {{ strtoupper(substr($disciple->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-700 truncate group-hover:text-amber-600 transition-colors">{{ $disciple->user->name }}</p>
                            <p class="text-[9px] text-gray-400">{{ $disciple->status }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-[8px] text-gray-300 group-hover:text-amber-400 transition-colors"></i>
                    </a>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <i class="fas fa-users-rays text-gray-200 text-2xl mb-2 block"></i>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sem discípulos ainda.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Ações rápidas --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Ações Rápidas</p>
                <a href="{{ route('members.edit', $member) }}"
                   class="w-full btn-neo bg-[#1c2434] text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 justify-center">
                    <i class="fas fa-pen"></i> Editar Perfil
                </a>
                @can('delete', $member)
                <form action="{{ route('members.destroy', $member) }}" method="POST"
                      onsubmit="return confirm('Remover este membro?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full btn-neo bg-red-50 border border-red-100 text-red-500 hover:bg-red-500 hover:text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 flex items-center gap-2 justify-center transition-all">
                        <i class="fas fa-trash"></i> Remover
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection
