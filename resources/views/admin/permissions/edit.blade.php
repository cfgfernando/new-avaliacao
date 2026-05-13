@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.permissions.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Editar Permissão</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">{{ $permission->name }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Nome da Permissão</label>
                <input type="text" name="name" value="{{ old('name', $permission->name) }}" required class="input-neo" placeholder="Ex: manage_users, edit_posts">
                <span class="text-[10px] text-slate-400 mt-1 px-1">Atenção: alterar o nome pode quebrar o código que a utiliza.</span>
                @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6 flex justify-between items-center">
                <button type="submit" class="btn-neo btn-primary py-4 px-8 text-sm w-full md:w-auto">SALVAR ALTERAÇÕES</button>
            </div>
        </form>
    </div>
</div>
@endsection
