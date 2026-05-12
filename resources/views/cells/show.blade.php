@extends('layouts.app')

@section('header_title', 'Detalhes da Célula')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('cells.index') }}" class="text-slate-500 hover:text-title font-black text-xs uppercase tracking-widest flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Voltar para Lista
        </a>
        <a href="{{ route('cells.edit', $cell) }}" class="px-6 py-3 bg-slate-900 text-title rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-black/10">
            Editar Célula
        </a>
    </div>

    <!-- Cell Header Card -->
    <div class="card-elite p-10 flex flex-col md:flex-row items-center gap-10">
        <div class="w-32 h-32 rounded-3xl gold-gradient flex items-center justify-center text-title text-5xl font-black shadow-lg shadow-orange-500/20">
            <i class="fas fa-church"></i>
        </div>
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl font-black text-title tracking-tighter mb-2">{{ $cell->name }}</h1>
            <div class="flex flex-wrap justify-center md:justify-start items-center gap-6 text-slate-500 font-bold text-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-tie text-accent"></i>
                    Líder: {{ $cell->leader->name ?? 'Não definido' }}
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-location-dot text-accent"></i>
                    {{ $cell->city ?? 'Local não definido' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Cell Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Info Column -->
        <div class="md:col-span-1 space-y-8">
            <div class="card-elite p-8 space-y-6">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 pb-4">Informações Gerais</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Dia de Reunião</label>
                        <p class="font-bold text-slate-700">{{ $cell->meeting_day ?? 'Não definido' }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Setor / Nó</label>
                        <p class="font-bold text-slate-700">{{ $cell->node->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Status</label>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $cell->active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                            {{ $cell->active ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Column -->
        <div class="md:col-span-2 space-y-8">
            <div class="card-elite">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-title uppercase tracking-widest">Membros da Célula</h3>
                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-500">{{ $cell->members->count() }} Total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Nome</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Papel</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($cell->members as $member)
                            <tr>
                                <td class="px-8 py-4">
                                    <div class="text-sm font-bold text-slate-700">{{ $member->name }}</div>
                                </td>
                                <td class="px-8 py-4 text-xs font-medium text-slate-500">
                                    {{ $member->role }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-8 py-10 text-center text-slate-400 font-bold">Nenhum membro vinculado.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
