@extends('layouts.app')

@section('title', (isset($visitor) ? 'Editar' : 'Novo') . ' Visitante — MDA')
@section('content')
<div class="space-y-8 animate-reveal-up max-w-4xl">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('visitors.index') }}"
           class="flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-[0.2em] transition-all">
            <i class="fas fa-arrow-left text-[8px]"></i> Visitantes
        </a>
        @isset($visitor)
        <span class="text-slate-200 text-[10px]">/</span>
        <span class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em] truncate max-w-[160px]">
            {{ $visitor->name }}
        </span>
        @endisset
    </div>

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">
            {{ isset($visitor) ? 'Editar Registro' : 'Novo Visitante' }}
        </h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">
            {{ isset($visitor) ? 'Atualize as informações de acompanhamento' : 'Inicie o funil de integração de novos membros' }}
        </p>
    </div>

    {{-- FORMULÁRIO --}}
    <div class="card-neo !p-0 border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="px-10 py-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Formulário de Ingresso</p>
            <i class="fas fa-user-circle text-slate-300"></i>
        </div>

        <form action="{{ isset($visitor) ? route('visitors.update', $visitor) : route('visitors.store') }}"
              method="POST" class="p-10 space-y-10">
            @csrf
            @isset($visitor) @method('PUT') @endisset

            @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 rounded-[2rem] p-6 animate-shake">
                <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i class="fas fa-circle-exclamation"></i> Pendências Encontradas
                </p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-[11px] font-bold text-rose-400 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-rose-300"></span> {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">

                {{-- Nome --}}
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">
                        Nome Completo <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $visitor->name ?? '') }}"
                           required placeholder="Ex: Gabriel Arcanjo da Silva"
                           class="input-neo py-4 @error('name') border-rose-300 bg-rose-50/10 @enderror">
                </div>

                {{-- Telefone --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Telefone Principal</label>
                    <div class="relative group">
                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs group-focus-within:text-accent transition-colors"></i>
                        <input type="text" name="phone" value="{{ old('phone', $visitor->phone ?? '') }}"
                               placeholder="(00) 00000-0000"
                               class="input-neo pl-11 py-4 @error('phone') border-rose-300 @enderror">
                    </div>
                </div>

                {{-- Email --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">E-mail para Contato</label>
                    <div class="relative group">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs group-focus-within:text-accent transition-colors"></i>
                        <input type="email" name="email" value="{{ old('email', $visitor->email ?? '') }}"
                               placeholder="visitante@igreja.com"
                               class="input-neo pl-11 py-4 @error('email') border-rose-300 @enderror">
                    </div>
                </div>

                {{-- Célula --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Célula de Acolhimento</label>
                    <select name="assigned_cell_id" class="input-neo py-4">
                        <option value="">Nenhuma Célula (Pendente)</option>
                        @foreach($cells as $cell)
                            <option value="{{ $cell->id }}" {{ old('assigned_cell_id', $visitor->assigned_cell_id ?? '') == $cell->id ? 'selected' : '' }}>
                                {{ $cell->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">
                        Estágio do Funil <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required class="input-neo py-4">
                        <option value="New"        {{ old('status', $visitor->status ?? 'New') === 'New'        ? 'selected' : '' }}>Novo Visitante</option>
                        <option value="Returning"  {{ old('status', $visitor->status ?? '') === 'Returning'     ? 'selected' : '' }}>Retornou à Igreja</option>
                        <option value="Interested" {{ old('status', $visitor->status ?? '') === 'Interested'    ? 'selected' : '' }}>Interessado em Membresia</option>
                        <option value="Converted"  {{ old('status', $visitor->status ?? '') === 'Converted'     ? 'selected' : '' }}>Convertido / Decidido</option>
                        <option value="Inactive"   {{ old('status', $visitor->status ?? '') === 'Inactive'      ? 'selected' : '' }}>Inativo / Afastado</option>
                    </select>
                </div>

                {{-- Como nos conheceu --}}
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Ponto de Contato (Origem)</label>
                    <select name="how_did_you_know" class="input-neo py-4">
                        <option value="">Selecione a origem...</option>
                        @foreach(['Convite de amigo', 'Redes sociais', 'Evento da igreja', 'Passando pela rua', 'Família', 'Outro'] as $opt)
                            <option value="{{ $opt }}" {{ old('how_did_you_know', $visitor->how_did_you_know ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Observações --}}
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Observações de Acolhimento</label>
                    <textarea name="notes" rows="4" placeholder="Descreva brevemente o perfil do visitante ou necessidades específicas..."
                               class="input-neo py-4">{{ old('notes', $visitor->notes ?? '') }}</textarea>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-50">
                <button type="submit"
                        class="btn-neo bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest px-12 py-5 flex items-center justify-center gap-3 hover:bg-slate-900 shadow-xl shadow-slate-200 transition-all">
                    <i class="fas fa-save text-sm"></i>
                    {{ isset($visitor) ? 'SALVAR ALTERAÇÕES' : 'EFETIVAR CADASTRO' }}
                </button>
                <a href="{{ isset($visitor) ? route('visitors.show', $visitor) : route('visitors.index') }}"
                   class="btn-neo bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-widest px-10 py-5 flex items-center justify-center gap-2 hover:border-slate-400 transition-all">
                    CANCELAR
                </a>
            </div>
        </form>
    </div>

</div>
@endsection  </div>

</div>
@endsection
