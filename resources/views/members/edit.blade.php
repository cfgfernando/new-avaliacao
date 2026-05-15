@extends('layouts.app')

@section('title', 'Editar Membro — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up max-w-4xl">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('members.index') }}"
           class="flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            <i class="fas fa-arrow-left"></i> Membros
        </a>
        <span class="text-slate-200">/</span>
        <a href="{{ route('members.show', $member) }}"
           class="text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors truncate max-w-[160px]">
            {{ $member->user->name }}
        </a>
    </div>

    {{-- HEADER --}}
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Editar Perfil</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                Atualize as informações do discípulo no sistema
            </p>
        </div>
        <div class="flex gap-3">
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                ID: #{{ $member->id }}
            </span>
        </div>
    </div>

    {{-- FORMULÁRIO --}}
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Configurações do Membro</p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Ativo no Sistema</span>
            </div>
        </div>

        <form action="{{ route('members.update', $member) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5 mb-6">
                <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i class="fas fa-circle-exclamation"></i> Ajustes Necessários
                </p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-xs text-rose-500 font-medium">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- SEÇÃO: IDENTIFICAÇÃO --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                        <i class="fas fa-id-card text-xs"></i>
                    </div>
                    <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]">Identificação</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Usuário Vinculado (Read Only no Edit) --}}
                    <div class="md:col-span-2 bg-slate-50 rounded-[1.5rem] p-5 flex items-center gap-5 border border-slate-100/50">
                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-800 font-black text-lg">
                            {{ strtoupper(substr($member->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Usuário Vinculado</p>
                            <p class="text-sm font-bold text-slate-800">{{ $member->user->name }}</p>
                            <p class="text-[10px] font-bold text-primary-light">{{ $member->user->email }}</p>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $member->user_id }}">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf', $member->cpf) }}"
                               placeholder="000.000.000-00" class="input-neo py-3.5 mask-cpf">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Telefone Principal</label>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                               placeholder="(00) 00000-0000" class="input-neo py-3.5 mask-phone">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Data de Nascimento</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $member->birth_date ? $member->birth_date->format('Y-m-d') : '') }}"
                               class="input-neo py-3.5">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Gênero</label>
                        <select name="gender" class="input-neo py-3.5">
                            <option value="">Selecione...</option>
                            <option value="Male"   {{ old('gender', $member->gender) === 'Male'   ? 'selected' : '' }}>Masculino</option>
                            <option value="Female" {{ old('gender', $member->gender) === 'Female' ? 'selected' : '' }}>Feminino</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO: MDA E DISCIPULADO --}}
            <div class="space-y-6 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                        <i class="fas fa-users-rays text-xs"></i>
                    </div>
                    <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]">MDA & Discipulado</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Mentor (Discipulador)</label>
                        <select name="mentor_id" class="input-neo py-3.5">
                            <option value="">Selecione um mentor...</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}" {{ old('mentor_id', $member->mentor_id) == $mentor->id ? 'selected' : '' }}>
                                    {{ $mentor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Status de Membresia</label>
                        <select name="status" class="input-neo py-3.5">
                            <option value="Active"      {{ old('status', $member->status) === 'Active'      ? 'selected' : '' }}>Ativo</option>
                            <option value="Inactive"    {{ old('status', $member->status) === 'Inactive'    ? 'selected' : '' }}>Inativo</option>
                            <option value="Transferred" {{ old('status', $member->status) === 'Transferred' ? 'selected' : '' }}>Transferido</option>
                            <option value="Deceased"    {{ old('status', $member->status) === 'Deceased'    ? 'selected' : '' }}>Falecido</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Data de Conversão</label>
                        <input type="date" name="conversion_date" value="{{ old('conversion_date', $member->conversion_date ? $member->conversion_date->format('Y-m-d') : '') }}"
                               class="input-neo py-3.5">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Data de Batismo</label>
                        <input type="date" name="baptism_date" value="{{ old('baptism_date', $member->baptism_date ? $member->baptism_date->format('Y-m-d') : '') }}"
                               class="input-neo py-3.5">
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="is_tither" value="1" {{ old('is_tither', $member->is_tither) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                            <span class="ml-4 text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-slate-800 transition-colors">Dizimista Ativo</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO: ENDEREÇO --}}
            <div class="space-y-6 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-location-dot text-xs"></i>
                    </div>
                    <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]">Endereço & Localização</h3>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Endereço Completo</label>
                    <textarea name="address" rows="3" placeholder="Rua, número, bairro, cidade..."
                              class="input-neo py-4">{{ old('address', $member->address) }}</textarea>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="flex gap-4 pt-6">
                <button type="submit"
                        class="btn-neo bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest px-10 py-4 flex items-center gap-2 hover:bg-slate-900 shadow-lg shadow-slate-200 transition-all">
                    <i class="fas fa-save"></i> SALVAR ALTERAÇÕES
                </button>
                <a href="{{ route('members.show', $member) }}"
                   class="btn-neo bg-white border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-widest px-8 py-4 flex items-center gap-2 hover:border-slate-400 hover:text-slate-600 transition-all">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
