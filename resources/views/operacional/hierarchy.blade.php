@extends('layouts.app')

@section('title', 'Organograma — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Estrutura Hierárquica</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                Visualização completa da árvore MDA (Redes, Distritos, Áreas e Setores)
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="expandAll()" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[9px] font-black uppercase tracking-widest px-4 py-2.5">
                Expandir Tudo
            </button>
            <button onclick="collapseAll()" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[9px] font-black uppercase tracking-widest px-4 py-2.5">
                Recolher Tudo
            </button>
        </div>
    </div>

    {{-- LEGEND --}}
    <div class="flex flex-wrap gap-4 items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mr-2">Legenda:</span>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
            <span class="text-[10px] font-bold text-slate-600 uppercase">Rede</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
            <span class="text-[10px] font-bold text-slate-600 uppercase">Distrito</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-orange-500"></div>
            <span class="text-[10px] font-bold text-slate-600 uppercase">Área</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            <span class="text-[10px] font-bold text-slate-600 uppercase">Setor</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-slate-300"></div>
            <span class="text-[10px] font-bold text-slate-600 uppercase">Célula</span>
        </div>
    </div>

    {{-- TREE VIEW --}}
    <div class="space-y-4">
        @forelse($networks as $network)
            <div class="hierarchy-root card-neo border-l-4 border-l-blue-500 overflow-hidden">
                <div class="flex items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition-colors" onclick="toggleNode('node-{{ $network->id }}')">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                            <i class="fas fa-network-wired text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $network->name }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Rede • {{ $network->children->count() }} Sub-nós</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-slate-300 transition-transform" id="icon-node-{{ $network->id }}"></i>
                </div>
                
                <div id="node-{{ $network->id }}" class="hidden bg-gray-50/30 border-t border-gray-100 p-4 space-y-4">
                    @foreach($network->children as $district)
                        <div class="ml-6 border-l-2 border-l-amber-200 pl-6 space-y-4">
                            <div class="flex items-center justify-between group cursor-pointer" onclick="toggleNode('node-{{ $district->id }}')">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500">
                                        <i class="fas fa-layer-group text-[10px]"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-700 uppercase">{{ $district->name }}</p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase">Distrito</p>
                                    </div>
                                </div>
                                <i class="fas fa-plus text-[8px] text-slate-300" id="icon-node-{{ $district->id }}"></i>
                            </div>

                            <div id="node-{{ $district->id }}" class="hidden space-y-4">
                                @foreach($district->children as $area)
                                    <div class="ml-6 border-l-2 border-l-orange-200 pl-6 space-y-4">
                                        <div class="flex items-center justify-between group cursor-pointer" onclick="toggleNode('node-{{ $area->id }}')">
                                            <div class="flex items-center gap-3">
                                                <div class="w-7 h-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                                    <i class="fas fa-vector-square text-[9px]"></i>
                                                </div>
                                                <p class="text-[11px] font-black text-slate-600 uppercase">{{ $area->name }}</p>
                                            </div>
                                            <i class="fas fa-plus text-[8px] text-slate-300" id="icon-node-{{ $area->id }}"></i>
                                        </div>

                                        <div id="node-{{ $area->id }}" class="hidden space-y-4">
                                            @foreach($area->children as $sector)
                                                <div class="ml-6 border-l-2 border-l-emerald-200 pl-6 space-y-3">
                                                    <div class="flex items-center justify-between group cursor-pointer" onclick="toggleNode('node-{{ $sector->id }}')">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-6 h-6 rounded-md bg-emerald-50 flex items-center justify-center text-emerald-500">
                                                                <i class="fas fa-bullseye text-[8px]"></i>
                                                            </div>
                                                            <p class="text-[10px] font-black text-slate-500 uppercase">{{ $sector->name }}</p>
                                                        </div>
                                                        <i class="fas fa-plus text-[7px] text-slate-300" id="icon-node-{{ $sector->id }}"></i>
                                                    </div>

                                                    <div id="node-{{ $sector->id }}" class="hidden grid grid-cols-1 md:grid-cols-2 gap-3 pb-2">
                                                        @foreach($sector->cells as $cell)
                                                            <a href="{{ route('cells.show', $cell) }}" class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-100 hover:border-amber-400 hover:shadow-sm transition-all group">
                                                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-amber-50 group-hover:text-amber-500 transition-colors">
                                                                    <i class="fas fa-church text-[10px]"></i>
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <p class="text-[10px] font-black text-slate-700 uppercase truncate">{{ $cell->name }}</p>
                                                                    <p class="text-[8px] text-slate-400 font-bold truncate">{{ $cell->leader->name ?? 'Sem Líder' }}</p>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="card-neo p-20 text-center">
                <i class="fas fa-sitemap text-slate-100 text-6xl mb-4 block"></i>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Nenhuma estrutura cadastrada.</p>
            </div>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
    function toggleNode(id) {
        const node = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        
        if (node.classList.contains('hidden')) {
            node.classList.remove('hidden');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.add('rotate-180');
            } else {
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            }
        } else {
            node.classList.add('hidden');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('rotate-180');
            } else {
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        }
    }

    function expandAll() {
        document.querySelectorAll('[id^="node-"]').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('[id^="icon-node-"]').forEach(icon => {
            if (icon.classList.contains('fa-chevron-down')) icon.classList.add('rotate-180');
            else { icon.classList.remove('fa-plus'); icon.classList.add('fa-minus'); }
        });
    }

    function collapseAll() {
        document.querySelectorAll('[id^="node-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="icon-node-"]').forEach(icon => {
            if (icon.classList.contains('fa-chevron-down')) icon.classList.remove('rotate-180');
            else { icon.classList.remove('fa-minus'); icon.classList.add('fa-plus'); }
        });
    }
</script>
@endpush
@endsection
