@extends('layouts.app')

@section('title', 'Consolidar Visitante — ' . $visitor->name)

@section('content')
<div class="space-y-8 animate-reveal-up max-w-4xl">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('visitors.index') }}"
           class="flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            <i class="fas fa-arrow-left"></i> Visitantes
        </a>
        <span class="text-gray-200">/</span>
        <a href="{{ route('visitors.show', $visitor) }}"
           class="text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            {{ $visitor->name }}
        </a>
    </div>

    {{-- HEADER --}}
    <div class="flex items-center gap-6">
        <div class="w-16 h-16 rounded-2xl bg-green-50 border border-green-100 flex items-center justify-center text-green-500 text-2xl font-black shrink-0">
            <i class="fas fa-handshake"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Consolidação de Membro</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                Transformando o visitante em um discípulo ativo no sistema MDA
            </p>
        </div>
    </div>

    <div class="card-neo overflow-hidden border-green-100">
        <div class="px-6 py-4 bg-green-50/50 border-b border-green-100">
            <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">
                <i class="fas fa-info-circle mr-1"></i> Este processo criará uma conta de usuário e um perfil de membro vinculado.
            </p>
        </div>

        <form action="{{ route('visitors.consolidate.store', $visitor) }}" method="POST" class="p-8 space-y-8">
            @csrf

            {{-- 1. CONTA DE ACESSO --}}
            <div class="space-y-6">
                <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] flex items-center gap-2">
                    <i class="fas fa-key text-[9px]"></i> 1. Conta de Acesso (Usuário)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Nome Completo</label>
                        <input type="text" name="name" value="{{ old('name', $visitor->name) }}" required class="input-neo py-3">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">E-mail (Login)</label>
                        <input type="email" name="email" value="{{ old('email', $visitor->email) }}" required class="input-neo py-3">
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Será usado para entrar no sistema.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Senha Temporária</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                            <input type="text" name="password" value="{{ old('password', 'Mda' . date('Y') . '!') }}" required class="input-neo pl-10 py-3">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. PERFIL DE MEMBRO --}}
            <div class="space-y-6 pt-8 border-t border-gray-100">
                <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] flex items-center gap-2">
                    <i class="fas fa-id-card text-[9px]"></i> 2. Perfil de Membro (MDA)
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Célula</label>
                        <select name="cell_id" class="input-neo py-3" required>
                            <option value="">Selecione...</option>
                            @foreach($cells as $cell)
                                <option value="{{ $cell->id }}" {{ old('cell_id', $visitor->assigned_cell_id) == $cell->id ? 'selected' : '' }}>
                                    {{ $cell->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Mentor (Discipulador)</label>
                        <select name="mentor_id" class="input-neo py-3">
                            <option value="">Selecione...</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}" {{ old('mentor_id') == $mentor->id ? 'selected' : '' }}>
                                    {{ $mentor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Telefone</label>
                        <input type="text" name="phone" value="{{ old('phone', $visitor->phone) }}" class="input-neo py-3 phone-mask">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf') }}" class="input-neo py-3 cpf-mask" placeholder="000.000.000-00">
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="flex gap-4 pt-4">
                <button type="submit"
                        class="btn-neo bg-green-600 text-white text-[9px] font-black uppercase tracking-widest px-8 py-3.5 flex items-center gap-2 hover:bg-green-700 transition-colors">
                    <i class="fas fa-check-double"></i> Confirmar Consolidação
                </button>
                <a href="{{ route('visitors.show', $visitor) }}"
                   class="btn-neo bg-white border border-gray-200 text-gray-400 text-[9px] font-black uppercase tracking-widest px-6 py-3.5 flex items-center gap-2 hover:border-gray-400 transition-all">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.cpf-mask').mask('000.000.000-00');
        $('.phone-mask').mask('(00) 00000-0000');
    });
</script>
@endpush
@endsection
