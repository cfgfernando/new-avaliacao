@extends('layouts.app')

@section('title', 'Novo Servidor Avaliado')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.evaluated-users.index') }}" class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Novo Servidor Avaliado</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Preencha os dados cadastrais do servidor público</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <form action="{{ route('admin.evaluated-users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Nome Completo <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-neo" placeholder="Ex: João da Silva">
                    @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">E-mail Corporativo <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input-neo" placeholder="Ex: joao.silva@prefeitura.gov.br">
                    @error('email') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Número de Matrícula <span class="text-rose-500">*</span></label>
                    <input type="text" name="registration_number" value="{{ old('registration_number') }}" required class="input-neo" placeholder="Ex: 998877">
                    @error('registration_number') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Cargo Efetivo <span class="text-rose-500">*</span></label>
                    <input type="text" name="cargo" value="{{ old('cargo') }}" required class="input-neo" placeholder="Ex: Assistente Administrativo">
                    @error('cargo') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Secretaria / Lotação de Exercício <span class="text-rose-500">*</span></label>
                    <select name="office_id" required class="input-neo">
                        <option value="">Selecione uma secretaria...</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                        @endforeach
                    </select>
                    @error('office_id') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Grupo Funcional <span class="text-rose-500">*</span></label>
                    <select name="evaluation_group" required class="input-neo">
                        <option value="">Selecione um grupo...</option>
                        <option value="geral" {{ old('evaluation_group') == 'geral' ? 'selected' : '' }}>Quadro Geral</option>
                        <option value="saude" {{ old('evaluation_group') == 'saude' ? 'selected' : '' }}>Saúde</option>
                        <option value="educacao" {{ old('evaluation_group') == 'educacao' ? 'selected' : '' }}>Educação</option>
                        <option value="guarda" {{ old('evaluation_group') == 'guarda' ? 'selected' : '' }}>Guarda Municipal</option>
                    </select>
                    @error('evaluation_group') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Avaliador Responsável</label>
                <select name="evaluator_id" class="input-neo">
                    <option value="">Nenhum avaliador associado</option>
                    @foreach($evaluators as $evaluator)
                        <option value="{{ $evaluator->id }}" {{ old('evaluator_id') == $evaluator->id ? 'selected' : '' }}>{{ $evaluator->name }} ({{ $evaluator->email }})</option>
                    @endforeach
                </select>
                @error('evaluator_id') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="flex items-center gap-3 p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="checkbox" name="has_active_pad" value="1" class="w-5 h-5 rounded border-slate-300 text-rose-600 focus:ring-rose-500" {{ old('has_active_pad') ? 'checked' : '' }}>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm text-slate-800">Servidor possui PAD Ativo</span>
                        <span class="text-xs text-slate-500 mt-0.5">Se marcado, as avaliações deste servidor estarão bloqueadas administrativamente</span>
                    </div>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-6">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest font-mono">Credenciais de Acesso</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Senha de Acesso <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required class="input-neo" placeholder="Mínimo de 8 caracteres">
                        @error('password') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Confirmar Senha <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required class="input-neo" placeholder="Digite a senha novamente">
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-sm font-bold">CRIAR SERVIDOR AVALIADO</button>
            </div>
        </form>
    </div>
</div>
@endsection
