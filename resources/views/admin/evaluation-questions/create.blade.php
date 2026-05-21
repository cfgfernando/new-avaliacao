@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="mb-8">
        <a href="{{ route('admin.evaluation-questions.index', ['group' => old('group_type', 'geral')]) }}" 
           class="text-xs font-bold text-primary-light hover:text-accent uppercase tracking-wider transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Voltar para Perguntas
        </a>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight mt-3">Nova Pergunta</h1>
        <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Cadastre uma nova pergunta parametrizável</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <form action="{{ route('admin.evaluation-questions.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Grupo Funcional</label>
                    <select name="group_type" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none @error('group_type') border-rose-300 @enderror">
                        <option value="geral" {{ old('group_type') === 'geral' ? 'selected' : '' }}>Quadro Geral</option>
                        <option value="saude" {{ old('group_type') === 'saude' ? 'selected' : '' }}>Saúde</option>
                        <option value="guarda" {{ old('group_type') === 'guarda' ? 'selected' : '' }}>Guarda Municipal</option>
                        <option value="educacao" {{ old('group_type') === 'educacao' ? 'selected' : '' }}>Educação</option>
                    </select>
                    @error('group_type') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Categoria</label>
                    <select name="category" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none @error('category') border-rose-300 @enderror">
                        <option value="assiduidade" {{ old('category') === 'assiduidade' ? 'selected' : '' }}>Assiduidade e Pontualidade</option>
                        <option value="disciplina" {{ old('category') === 'disciplina' ? 'selected' : '' }}>Disciplina</option>
                        <option value="iniciativa" {{ old('category') === 'iniciativa' ? 'selected' : '' }}>Iniciativa</option>
                        <option value="responsabilidade" {{ old('category') === 'responsabilidade' ? 'selected' : '' }}>Responsabilidade</option>
                        <option value="cooperacao" {{ old('category') === 'cooperacao' ? 'selected' : '' }}>Cooperação</option>
                        <option value="qualidade" {{ old('category') === 'qualidade' ? 'selected' : '' }}>Qualidade do Trabalho</option>
                        <option value="desenvolvimento_rh" {{ old('category') === 'desenvolvimento_rh' ? 'selected' : '' }}>Desenvolvimento RH</option>
                        <option value="avaliacao_usuario" {{ old('category') === 'avaliacao_usuario' ? 'selected' : '' }}>Avaliação pelo Usuário</option>
                    </select>
                    @error('category') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Texto da Pergunta</label>
                    <textarea name="text" rows="4" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none @error('text') border-rose-300 @enderror">{{ old('text') }}</textarea>
                    @error('text') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-accent focus:border-accent border-gray-300 rounded">
                    </div>
                    <span class="ml-2 text-[11px] font-black text-slate-600">Pergunta Ativa</span>
                </div>

                <div class="border-t border-slate-100 pt-6 flex items-center justify-end">
                    <a href="{{ route('admin.evaluation-questions.index', ['group' => old('group_type', 'geral')]) }}" 
                       class="px-4 py-2 text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-bold text-white bg-accent hover:bg-accent-hover rounded-lg shadow-md shadow-blue-500/10 transition flex items-center gap-2">
                        <i class="fas fa-save text-[14px]"></i>
                        Salvar Pergunta
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection