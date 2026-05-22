@extends('layouts.app')

@section('title', 'Nova Secretaria / Lotação')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.offices.index') }}" class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Nova Secretaria / Lotação</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Preencha os dados abaixo</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <form action="{{ route('admin.offices.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Nome Completo <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="input-neo" placeholder="Ex: Secretaria Municipal de Obras Públicas">
                @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Sigla</label>
                <input type="text" name="sigla" value="{{ old('sigla') }}" class="input-neo" placeholder="Ex: SMOP">
                @error('sigla') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="flex items-center gap-3 p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm text-slate-800">Lotação Ativa</span>
                        <span class="text-xs text-slate-500 mt-0.5">Define se a lotação pode ser selecionada em novos servidores e avaliações</span>
                    </div>
                </label>
            </div>

            <div class="pt-6">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-sm font-bold">CRIAR LOTAÇÃO</button>
            </div>
        </form>
    </div>
</div>
@endsection
