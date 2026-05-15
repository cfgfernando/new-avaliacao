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
    <div class="card-neo !p-0 overflow-hidden shadow-xl shadow-slate-200/40">
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-10 flex flex-col md:flex-row items-start md:items-center gap-8 relative overflow-hidden">
            {{-- Abstract background decoration --}}
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-accent/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl"></div>

            <div class="w-24 h-24 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center text-white font-black text-4xl shrink-0 shadow-2xl relative z-10 backdrop-blur-sm">
                {{ strtoupper(substr($member->user->name, 0, 1)) }}
            </div>
            <div class="flex-1 relative z-10">
                <div class="flex flex-wrap items-center gap-4 mb-3">
                    <h1 class="text-2xl font-black text-white uppercase tracking-tight">{{ $member->user->name }}</h1>
                    @php
                        $heroStatus = match($member->status) {
                            'Active'   => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                            'Inactive' => 'bg-slate-500/20 text-slate-400 border-slate-500/30',
                            default    => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                        };
                    @endphp
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full border {{ $heroStatus }}">
                        {{ $member->status }}
                    </span>
                    @if($member->baptism_date)
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            <i class="fas fa-droplet text-[8px] mr-1"></i> Batizado
                        </span>
                    @endif
                    @if($member->is_tither)
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">
                            <i class="fas fa-hand-holding-heart text-[8px] mr-1"></i> Dizimista
                        </span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-6 text-slate-300/80 text-[10px] font-black uppercase tracking-widest">
                    <span class="flex items-center gap-2"><i class="fas fa-envelope text-accent/60"></i> {{ $member->user->email }}</span>
                    @if($member->user->cell)
                        <span class="flex items-center gap-2"><i class="fas fa-church text-accent/60"></i> {{ $member->user->cell->name }}</span>
                    @endif
                    @if($member->mentor)
                        <span class="flex items-center gap-2"><i class="fas fa-user-tie text-accent/60"></i> Mentor: {{ $member->mentor->name }}</span>
                    @endif
                    <span class="flex items-center gap-2"><i class="fas fa-calendar text-accent/60"></i> Desde {{ $member->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- KPI STRIP --}}
        <div class="grid grid-cols-3 divide-x divide-slate-100 bg-white">
            <div class="px-8 py-6 text-center group hover:bg-slate-50 transition-colors">
                <p class="text-3xl font-black text-slate-800 tracking-tighter">{{ $member->disciples->count() }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 group-hover:text-accent transition-colors">Discípulos</p>
            </div>
            <div class="px-8 py-6 text-center group hover:bg-slate-50 transition-colors">
                <p class="text-sm font-black text-slate-800 tracking-widest uppercase">
                    {{ $member->baptism_date ? $member->baptism_date->format('d/m/Y') : '—' }}
                </p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 group-hover:text-blue-500 transition-colors">Data Batismo</p>
            </div>
            <div class="px-8 py-6 text-center group hover:bg-slate-50 transition-colors">
                <p class="text-sm font-black text-slate-800 tracking-widest uppercase">
                    {{ $member->user->cell->node->name ?? '—' }}
                </p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 group-hover:text-emerald-500 transition-colors">Setor</p>
            </div>
        </div>
    </div>

    {{-- CONTEÚDO --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- INFO ESPIRITUAL --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card-neo p-8">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pb-5 mb-8 border-b border-slate-50 flex items-center justify-between">
                    <span>Informações Espirituais</span>
                    <i class="fas fa-star text-accent/30 text-xs"></i>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    @php
                        $infoItems = [
                            ['label' => 'Batizado',     'value' => $member->baptism_date ? 'Sim' : 'Não', 'icon' => 'fa-droplet',      'highlight' => $member->baptism_date, 'color' => 'blue'],
                            ['label' => 'Dizimista',    'value' => $member->is_tither   ? 'Sim' : 'Não', 'icon' => 'fa-hand-holding-heart', 'highlight' => $member->is_tither, 'color' => 'amber'],
                            ['label' => 'Status',       'value' => $member->status,                      'icon' => 'fa-circle-check',  'highlight' => $member->status === 'Active', 'color' => 'emerald'],
                            ['label' => 'Mentor',       'value' => $member->mentor->name ?? 'Não atribuído', 'icon' => 'fa-user-tie',  'highlight' => false, 'color' => 'slate'],
                            ['label' => 'Célula',       'value' => $member->user->cell->name ?? 'Sem célula', 'icon' => 'fa-church',   'highlight' => false, 'color' => 'slate'],
                            ['label' => 'Cadastro',     'value' => $member->created_at->format('d/m/Y'), 'icon' => 'fa-calendar',     'highlight' => false, 'color' => 'slate'],
                        ];
                    @endphp
                    @foreach($infoItems as $item)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-2xl {{ $item['highlight'] ? 'bg-'.$item['color'].'-50 text-'.$item['color'].'-500' : 'bg-slate-50 text-slate-300' }} flex items-center justify-center shrink-0 shadow-sm border border-slate-100/50">
                            <i class="fas {{ $item['icon'] }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $item['label'] }}</p>
                            <p class="text-sm font-black text-slate-800 mt-1 uppercase tracking-tight">{{ $item['value'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($member->notes)
            <div class="card-neo p-8">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pb-5 mb-6 border-b border-slate-50">
                    Observações Internas
                </h3>
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100/50">
                    <p class="text-xs text-slate-600 leading-relaxed font-medium italic">"{{ $member->notes }}"</p>
                </div>
            </div>
            @endif
        </div>

        {{-- DISCÍPULOS --}}
        <div class="space-y-6">
            <div class="card-neo !p-0 overflow-hidden shadow-xl shadow-slate-200/30">
                <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Equipe de Discípulos</p>
                    <span class="bg-white text-slate-800 text-[10px] font-black px-3 py-1 rounded-full border border-slate-100 shadow-sm">
                        {{ $member->disciples->count() }}
                    </span>
                </div>
                <div class="divide-y divide-slate-50 max-h-[400px] overflow-y-auto custom-scrollbar">
                    @forelse($member->disciples as $disciple)
                    <a href="{{ route('members.show', $disciple) }}"
                       class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-all group">
                        <div class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-800 font-black text-xs shrink-0 group-hover:scale-110 transition-transform">
                            {{ strtoupper(substr($disciple->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-slate-800 uppercase tracking-tight truncate group-hover:text-primary transition-colors">{{ $disciple->user->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full {{ $disciple->status === 'Active' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $disciple->status }}</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[8px] text-slate-200 group-hover:text-accent group-hover:translate-x-1 transition-all"></i>
                    </a>
                    @empty
                    <div class="px-8 py-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 border border-slate-100 mx-auto mb-4">
                            <i class="fas fa-users-rays text-2xl"></i>
                        </div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Sem discípulos vinculados.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Ações rápidas --}}
            <div class="card-neo p-6 space-y-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Ações Estratégicas</p>
                <a href="{{ route('members.edit', $member) }}"
                   class="w-full btn-neo bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest px-4 py-4 flex items-center gap-3 justify-center shadow-lg shadow-slate-200 hover:bg-slate-900 transition-all">
                    <i class="fas fa-pen-nib text-xs"></i> Editar Perfil Completo
                </a>
                @can('delete', $member)
                <form action="{{ route('members.destroy', $member) }}" method="POST"
                      onsubmit="return confirm('ATENÇÃO: Deseja realmente remover este perfil de membro?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full btn-neo bg-white border border-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white text-[10px] font-black uppercase tracking-widest px-4 py-4 flex items-center gap-3 justify-center transition-all group">
                        <i class="fas fa-trash-alt text-xs group-hover:animate-bounce"></i> Remover do Sistema
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection
