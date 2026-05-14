@extends('layouts.app')

@section('title', (isset($visitor) ? 'Editar' : 'Novo') . ' Visitante — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up max-w-3xl">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('visitors.index') }}"
           class="flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            <i class="fas fa-arrow-left"></i> Visitantes
        </a>
        @isset($visitor)
        <span class="text-gray-200">/</span>
        <a href="{{ route('visitors.show', $visitor) }}"
           class="text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors truncate max-w-[160px]">
            {{ $visitor->name }}
        </a>
        @endisset
    </div>

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">
            {{ isset($visitor) ? 'Editar Visitante' : 'Novo Visitante' }}
        </h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
            {{ isset($visitor) ? 'Atualize os dados do visitante' : 'Cadastre um novo visitante no sistema' }}
        </p>
    </div>

    {{-- FORMULÁRIO --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Dados do Visitante</p>
        </div>

        <form action="{{ isset($visitor) ? route('visitors.update', $visitor) : route('visitors.store') }}"
              method="POST" class="p-6 space-y-6">
            @csrf
            @isset($visitor) @method('PUT') @endisset

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-2">
                    <i class="fas fa-triangle-exclamation mr-1"></i> Corrija os erros abaixo:
                </p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-xs text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nome --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">
                        Nome Completo <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $visitor->name ?? '') }}"
                           required placeholder="Digite o nome completo"
                           class="input-neo py-3 @error('name') border-red-300 @enderror">
                </div>

                {{-- Telefone --}}
                <div class="space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Telefone</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                        <input type="text" name="phone" value="{{ old('phone', $visitor->phone ?? '') }}"
                               placeholder="(00) 00000-0000"
                               class="input-neo pl-10 py-3 @error('phone') border-red-300 @enderror">
                    </div>
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">E-mail</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                        <input type="email" name="email" value="{{ old('email', $visitor->email ?? '') }}"
                               placeholder="visitante@email.com"
                               class="input-neo pl-10 py-3 @error('email') border-red-300 @enderror">
                    </div>
                </div>

                {{-- Célula --}}
                <div class="space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Célula Responsável</label>
                    <select name="assigned_cell_id" class="input-neo py-3 @error('assigned_cell_id') border-red-300 @enderror">
                        <option value="">Selecione uma célula...</option>
                        @foreach($cells as $cell)
                            <option value="{{ $cell->id }}" {{ old('assigned_cell_id', $visitor->assigned_cell_id ?? '') == $cell->id ? 'selected' : '' }}>
                                {{ $cell->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">
                        Status no Funil <span class="text-red-400">*</span>
                    </label>
                    <select name="status" required class="input-neo py-3 @error('status') border-red-300 @enderror">
                        <option value="New"        {{ old('status', $visitor->status ?? 'New') === 'New'        ? 'selected' : '' }}>Novo</option>
                        <option value="Returning"  {{ old('status', $visitor->status ?? '') === 'Returning'     ? 'selected' : '' }}>Retornou</option>
                        <option value="Interested" {{ old('status', $visitor->status ?? '') === 'Interested'    ? 'selected' : '' }}>Interessado</option>
                        <option value="Converted"  {{ old('status', $visitor->status ?? '') === 'Converted'     ? 'selected' : '' }}>Convertido</option>
                        <option value="Inactive"   {{ old('status', $visitor->status ?? '') === 'Inactive'      ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                {{-- Como nos conheceu --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Como nos conheceu</label>
                    <select name="how_did_you_know" class="input-neo py-3">
                        <option value="">Selecione...</option>
                        @foreach(['Convite de amigo', 'Redes sociais', 'Evento da igreja', 'Passando pela rua', 'Família', 'Outro'] as $opt)
                            <option value="{{ $opt }}" {{ old('how_did_you_know', $visitor->how_did_you_know ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Observações --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Observações</label>
                    <textarea name="notes" rows="3" placeholder="Informações adicionais sobre o visitante..."
                              class="input-neo py-3 @error('notes') border-red-300 @enderror">{{ old('notes', $visitor->notes ?? '') }}</textarea>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="flex gap-4 pt-2">
                <button type="submit"
                        class="btn-neo bg-[#f59e0b] text-white text-[9px] font-black uppercase tracking-widest px-8 py-3 flex items-center gap-2 hover:bg-[#d97706] transition-colors">
                    <i class="fas fa-check"></i>
                    {{ isset($visitor) ? 'Salvar Alterações' : 'Cadastrar Visitante' }}
                </button>
                <a href="{{ isset($visitor) ? route('visitors.show', $visitor) : route('visitors.index') }}"
                   class="btn-neo bg-white border border-gray-200 text-gray-500 text-[9px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 hover:border-gray-400 transition-all">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
