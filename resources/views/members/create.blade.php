@extends('layouts.app')

@section('header_title', 'Novo Membro')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('members.index') }}" class="text-slate-500 hover:text-title font-black text-xs uppercase tracking-widest flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Voltar para Lista
        </a>
    </div>

    <div class="card-elite p-10">
        <div class="mb-10">
            <h1 class="text-3xl font-black text-title tracking-tight">Cadastro de Membro</h1>
            <p class="text-slate-500 font-medium">Insira as informações básicas para registrar um novo membro no sistema.</p>
        </div>

        <form action="{{ route('members.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Nome -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Nome Completo</label>
                    <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700 placeholder:text-slate-300">
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email</label>
                    <input type="email" name="email" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700 placeholder:text-slate-300">
                </div>

                <!-- Célula -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Célula de Destino</label>
                    <select name="cell_id" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                        <option value="">Selecione uma célula...</option>
                        @foreach($cells as $cell)
                            <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mentor -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Mentor (Discipulador)</label>
                    <select name="mentor_id" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                        <option value="">Selecione um mentor...</option>
                        @foreach($mentors as $mentor)
                            <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6 border-t border-slate-100">
                <!-- Status -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status Inicial</label>
                    <select name="status" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                        <option value="Active">Ativo</option>
                        <option value="Visitor">Visitante</option>
                        <option value="Converted">Convertido</option>
                    </select>
                </div>

                <!-- Batizado -->
                <div class="flex items-center gap-4 h-full pt-6">
                    <input type="checkbox" name="is_baptized" value="1" class="w-6 h-6 rounded-lg border-slate-200 text-accent focus:ring-accent/20">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-600">Já é Batizado?</label>
                </div>

                <!-- Dizimista -->
                <div class="flex items-center gap-4 h-full pt-6">
                    <input type="checkbox" name="is_tither" value="1" class="w-6 h-6 rounded-lg border-slate-200 text-accent focus:ring-accent/20">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-600">É Dizimista?</label>
                </div>
            </div>

            <div class="pt-10">
                <button type="submit" class="w-full btn-primary py-5">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>Confirmar Cadastro do Membro</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
