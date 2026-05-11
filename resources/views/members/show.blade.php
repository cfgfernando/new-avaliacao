@extends('layouts.app')

@section('header_title', 'Perfil do Membro')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('members.index') }}" class="text-slate-500 hover:text-title font-black text-xs uppercase tracking-widest flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Voltar para Lista
        </a>
        <div class="flex gap-3">
            <a href="{{ route('members.edit', $member) }}" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-black/10">
                Editar Perfil
            </a>
        </div>
    </div>

    <!-- Member Profile Header -->
    <div class="card-elite p-10 flex flex-col md:flex-row items-center gap-10">
        <div class="w-32 h-32 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-300 text-5xl font-black shadow-inner">
            {{ substr($member->user->name, 0, 1) }}
        </div>
        <div class="flex-1 text-center md:text-left">
            <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mb-4">
                <h1 class="text-4xl font-black text-title tracking-tighter">{{ $member->user->name }}</h1>
                <span class="px-4 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-widest">
                    {{ $member->status }}
                </span>
            </div>
            <div class="flex flex-wrap justify-center md:justify-start items-center gap-6 text-slate-500 font-bold text-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-envelope text-accent"></i>
                    {{ $member->user->email }}
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-church text-accent"></i>
                    {{ $member->user->cell->name ?? 'Sem Célula' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="card-elite p-8 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 pb-4">Informações Espirituais</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Batizado</label>
                    <p class="font-bold text-slate-700">{{ $member->is_baptized ? 'Sim' : 'Não' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Dizimista</label>
                    <p class="font-bold text-slate-700">{{ $member->is_tither ? 'Sim' : 'Não' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Mentor</label>
                    <p class="font-bold text-slate-700">{{ $member->mentor->name ?? 'Não atribuído' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Data Cadastro</label>
                    <p class="font-bold text-slate-700">{{ $member->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="card-elite p-8 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 pb-4">Estatísticas de Discipulado</h3>
            <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Discípulos Diretos</p>
                    <p class="text-3xl font-black text-title">{{ $member->disciples->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-accent shadow-sm">
                    <i class="fas fa-users-rays text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
