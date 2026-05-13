@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Novo Usuário</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Preencha os dados abaixo</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Nome Completo</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="input-neo" placeholder="Ex: João da Silva">
                @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input-neo" placeholder="Ex: joao@igreja.com">
                @error('email') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Senha</label>
                    <input type="password" name="password" required class="input-neo" placeholder="Mínimo 8 caracteres">
                    @error('password') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" required class="input-neo" placeholder="Confirme a senha">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-4 px-1">Perfis de Acesso</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-3 p-4 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent" {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'checked' : '' }}>
                            <span class="font-bold text-sm text-slate-800">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles') <span class="text-xs text-rose-500 font-bold mt-2 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-sm">CRIAR USUÁRIO</button>
            </div>
        </form>
    </div>
</div>
@endsection
