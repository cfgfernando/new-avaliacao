@extends('layouts.app')

@section('title', 'Gestão de Células — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestão de Células</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $cells->total() }} célula(s) encontrada(s)</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('operacional.dashboard') }}" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-5 py-3 flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
                <i class="fas fa-gauge-high"></i> Dashboard
            </a>
            @can('create', App\Models\Cell::class)
            <a href="{{ route('cells.create') }}" class="btn-neo bg-primary text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2">
                <i class="fas fa-plus"></i> Nova Célula
            </a>
            @endcan
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card-neo p-6">
        <form method="GET" action="{{ route('cells.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Buscar</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou cidade..." class="input-neo pl-10 py-2.5">
                </div>
            </div>
            <div class="min-w-[160px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Dia de Reunião</label>
                <select name="meeting_day" class="input-neo py-2.5">
                    <option value="">Qualquer dia</option>
                    @foreach(['Sunday'=>'Domingo','Monday'=>'Segunda','Tuesday'=>'Terça','Wednesday'=>'Quarta','Thursday'=>'Quinta','Friday'=>'Sexta','Saturday'=>'Sábado'] as $val => $label)
                        <option value="{{ $val }}" {{ request('meeting_day') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <label class="flex items-center gap-2 cursor-pointer pb-0.5">
                    <input type="checkbox" name="active_only" value="1" {{ request('active_only', '1') ? 'checked' : '' }} class="rounded border-slate-300 text-primary">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Apenas Ativas</span>
                </label>
            </div>
            <button type="submit" class="btn-neo bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </form>
    </div>

    {{-- GRID DE CÉLULAS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($cells as $cell)
        <a href="{{ route('cells.show', $cell) }}" class="card-neo !p-0 overflow-hidden group hover:shadow-lg hover:scale-[1.01] transition-all duration-300">
            {{-- Card Header --}}
            <div class="bg-gradient-to-br from-slate-50 to-white px-6 pt-6 pb-4 border-b border-slate-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-lg group-hover:bg-primary group-hover:text-white transition-all">
                        {{ strtoupper(substr($cell->name, 0, 2)) }}
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full {{ $cell->active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-500 border border-rose-100' }}">
                        {{ $cell->active ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>
                <h3 class="font-black text-slate-800 uppercase tracking-tight text-sm group-hover:text-primary transition-colors">{{ $cell->name }}</h3>
                @if($cell->neighborhood || $cell->city)
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                    {{ implode(', ', array_filter([$cell->neighborhood, $cell->city])) }}
                </p>
                @endif
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-4 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Líder</p>
                        <p class="text-xs font-black text-slate-700">{{ $cell->leader->name ?? '— Sem líder —' }}</p>
                    </div>
                </div>

                @if($cell->node)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="fas fa-sitemap text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $cell->node->type_label }}</p>
                        <p class="text-xs font-black text-slate-700">{{ $cell->node->name }}</p>
                    </div>
                </div>
                @endif

                @if($cell->meeting_day)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="fas fa-calendar text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Reunião</p>
                        <p class="text-xs font-black text-slate-700">{{ $cell->meeting_day_label }}
                            @if($cell->meeting_time) · {{ \Carbon\Carbon::parse($cell->meeting_time)->format('H:i') }}@endif
                        </p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1">
                    <i class="fas fa-arrow-right text-primary opacity-0 group-hover:opacity-100 transition-opacity -translate-x-1 group-hover:translate-x-0 duration-300"></i>
                    Ver detalhes
                </span>
                <span class="text-[8px] font-black text-primary-light uppercase tracking-widest">
                    #{{ $cell->id }}
                </span>
            </div>
        </a>
        @empty
        <div class="md:col-span-2 xl:col-span-3">
            <div class="card-neo p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-200">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhuma célula encontrada.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- PAGINAÇÃO --}}
    @if($cells->hasPages())
    <div class="flex justify-center">
        {{ $cells->links() }}
    </div>
    @endif

</div>
@endsection
