@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.permissions.index') }}" class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Nova Permissão</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Configure o nível de restrição</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono mb-2 px-1">Nome da Permissão</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="input-neo" placeholder="Ex: manage_users, edit_posts">
                <span class="text-[10px] text-slate-400 mt-1 px-1">Dica: Use um padrão como 'verbo_recurso', tudo minúsculo.</span>
                @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6 flex justify-between items-center">
                <button type="submit" class="btn-neo btn-primary py-4 px-8 text-sm w-full md:w-auto">CRIAR PERMISSÃO</button>
            </div>
        </form>
    </div>
</div>
@endsection
