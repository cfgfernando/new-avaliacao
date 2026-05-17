@extends('layouts.app')

@section('title', $cell->name . ' — Célula MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- BREADCRUMB --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('cells.index') }}" class="flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            <i class="fas fa-arrow-left"></i> Células
        </a>
        <a href="{{ route('cells.edit', $cell) }}" class="btn-neo bg-white border border-slate-200 text-slate-600 text-[9px] font-black uppercase tracking-widest px-4 py-2 flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>

    {{-- CELL HERO --}}
    <div class="card-neo !p-0 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 p-8 flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white font-black text-2xl shrink-0">
                {{ strtoupper(substr($cell->name, 0, 2)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-xl font-black text-white uppercase tracking-tight">{{ $cell->name }}</h1>
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full {{ $cell->active ? 'bg-emerald-400/20 text-emerald-300 border border-emerald-400/30' : 'bg-rose-400/20 text-rose-300 border border-rose-400/30' }}">
                        {{ $cell->active ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-5 text-white/60 text-xs font-bold mt-2">
                    @if($cell->leader)
                        <span class="flex items-center gap-1.5"><i class="fas fa-user-tie text-white/40"></i> {{ $cell->leader->name }}</span>
                    @endif
                    @if($cell->node)
                        <span class="flex items-center gap-1.5"><i class="fas fa-sitemap text-white/40"></i> {{ $cell->node->name }}</span>
                    @endif
                    @if($cell->meeting_day)
                        <span class="flex items-center gap-1.5"><i class="fas fa-calendar text-white/40"></i> {{ $cell->meeting_day_label }}
                            @if($cell->meeting_time) · {{ \Carbon\Carbon::parse($cell->meeting_time)->format('H:i') }}@endif
                        </span>
                    @endif
                    @if($cell->city)
                        <span class="flex items-center gap-1.5"><i class="fas fa-location-dot text-white/40"></i> {{ implode(', ', array_filter([$cell->neighborhood, $cell->city])) }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- KPI Strip --}}
        <div class="grid grid-cols-3 divide-x divide-slate-100">
            <div class="px-6 py-5 text-center">
                <p class="text-2xl font-black text-slate-800 tracking-tighter">{{ $cell->members->count() }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Membros</p>
            </div>
            <div class="px-6 py-5 text-center">
                <p class="text-2xl font-black text-slate-800 tracking-tighter">{{ $cell->visitors->count() }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Visitantes</p>
            </div>
            <div class="px-6 py-5 text-center">
                @if($cell->latestReport)
                    <p class="text-2xl font-black text-emerald-600 tracking-tighter">R$ {{ number_format($cell->latestReport->total_offer, 2, ',', '.') }}</p>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Último Malote</p>
                @else
                    <p class="text-2xl font-black text-slate-300 tracking-tighter">—</p>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Sem Malotes</p>
                @endif
            </div>
        </div>
    </div>

    {{-- CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- MEMBROS --}}
        <div class="lg:col-span-2 card-neo !p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Membros</p>
                <span class="text-[9px] font-black text-slate-400 bg-slate-100 px-3 py-1 rounded-full">{{ $cell->members->count() }}</span>
            </div>
            <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                @forelse($cell->members as $member)
                <div class="flex items-center gap-4 px-6 py-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-xs shrink-0">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-800 truncate">{{ $member->name }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $member->role }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum membro vinculado.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- VISITANTES + ÚLTIMO RELATÓRIO --}}
        <div class="space-y-5">
            {{-- Visitantes ativos --}}
            <div class="card-neo !p-0 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Visitantes</p>
                    <span class="text-[9px] font-black text-amber-600 bg-amber-50 px-3 py-1 rounded-full">{{ $cell->visitors->count() }}</span>
                </div>
                <div class="divide-y divide-slate-50 max-h-48 overflow-y-auto">
                    @forelse($cell->visitors as $visitor)
                    <div class="flex items-center gap-3 px-6 py-3">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500 text-xs shrink-0">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-slate-700 truncate">{{ $visitor->name }}</p>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $visitor->status_label }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-6 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sem visitantes.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Último relatório --}}
            @if($cell->latestReport)
            <div class="card-neo !p-0 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Último Malote</p>
                    <p class="text-[9px] text-slate-400 font-bold mt-0.5">{{ $cell->latestReport->meeting_date->format('d/m/Y') }}</p>
                </div>
                <div class="px-6 py-4 space-y-2">
                    <div class="flex justify-between text-[9px] font-black uppercase tracking-widest">
                        <span class="text-slate-400">Membros</span>
                        <span class="text-slate-700">{{ $cell->latestReport->present_members }}</span>
                    </div>
                    <div class="flex justify-between text-[9px] font-black uppercase tracking-widest">
                        <span class="text-slate-400">Visitantes</span>
                        <span class="text-slate-700">{{ $cell->latestReport->visitors }}</span>
                    </div>
                    <div class="flex justify-between text-[9px] font-black uppercase tracking-widest">
                        <span class="text-slate-400">Crianças</span>
                        <span class="text-slate-700">{{ $cell->latestReport->children }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-2 mt-2 flex justify-between text-[9px] font-black uppercase tracking-widest">
                        <span class="text-slate-600">Oferta Total</span>
                        <span class="text-emerald-600">R$ {{ number_format($cell->latestReport->total_offer, 2, ',', '.') }}</span>
                    </div>
                </div>
                <div class="px-6 py-3 border-t border-slate-100">
                    <a href="{{ route('reports.show', $cell->latestReport) }}" class="text-[9px] font-black text-primary-light uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                        Ver malote <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- AÇÃO RÁPIDA --}}
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('reports.create') }}?cell_id={{ $cell->id }}" class="btn-neo bg-primary text-white text-[9px] font-black uppercase tracking-widest px-5 py-3 flex items-center gap-2">
            <i class="fas fa-file-plus"></i> Criar Malote para esta Célula
        </a>
    </div>

</div>
@endsection
