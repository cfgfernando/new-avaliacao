@extends('layouts.app')

@section('title', (isset($member) ? 'Editar' : 'Novo') . ' Membro — MDA')

@section('content')
<div class="space-y-8 animate-reveal-up max-w-4xl">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('members.index') }}"
           class="flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors">
            <i class="fas fa-arrow-left"></i> Membros
        </a>
        @isset($member)
        <span class="text-gray-200">/</span>
        <a href="{{ route('members.show', $member) }}"
           class="text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors truncate max-w-[160px]">
            {{ $member->user->name }}
        </a>
        @endisset
    </div>

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">
            {{ isset($member) ? 'Editar Membro' : 'Novo Membro' }}
        </h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
            {{ isset($member) ? 'Atualize o perfil do discípulo' : 'Registre um novo discípulo no sistema MDA' }}
        </p>
    </div>

    {{-- FORMULÁRIO --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Informações Cadastrais</p>
            @isset($member)
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID: #{{ $member->id }}</span>
            @endisset
        </div>

        <form action="{{ isset($member) ? route('members.update', $member) : route('members.store') }}"
              method="POST" class="p-8 space-y-8">
            @csrf
            @isset($member) @method('PUT') @endisset

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-2">
                    <i class="fas fa-triangle-exclamation mr-1"></i> Verifique os campos abaixo:
                </p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-xs text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- SEÇÃO: DADOS PESSOAIS --}}
            <div class="space-y-6">
                <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] flex items-center gap-2">
                    <i class="fas fa-user text-[9px]"></i> Dados Pessoais
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!isset($member))
                    {{-- Seleção de Usuário (apenas no Create se necessário, ou criar User junto) --}}
                    {{-- Nota: O StoreMemberRequest espera user_id. Se for novo, talvez precise de uma lógica de criação de User. --}}
                    {{-- O Controller original parece esperar que o User já exista ou seja criado em outro lugar. --}}
                    {{-- Vamos seguir o padrão do formulário anterior que pedia Name/Email e o Controller lidava (ou não). --}}
                    {{-- Verificando MemberController@store... ele apenas faz Member::create($request->validated()). --}}
                    {{-- Se o request pede user_id, precisamos de um seletor de usuários que ainda não são membros. --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Vincular Usuário Existente</label>
                        <select name="user_id" class="input-neo py-3 select2-users" required>
                            <option value="">Pesquise por nome ou email...</option>
                        </select>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                            <i class="fas fa-info-circle mr-1"></i> O membro deve ter uma conta de usuário ativa.
                        </p>
                    </div>
                    @else
                    <div class="md:col-span-2 bg-slate-50 rounded-xl p-4 flex items-center gap-4 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 font-black">
                            {{ strtoupper(substr($member->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Usuário Vinculado</p>
                            <p class="text-sm font-bold text-slate-700">{{ $member->user->name }} ({{ $member->user->email }})</p>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $member->user_id }}">
                    </div>
                    @endif

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf', $member->cpf ?? '') }}"
                               placeholder="000.000.000-00" class="input-neo py-3 cpf-mask">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Telefone</label>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone ?? '') }}"
                               placeholder="(00) 00000-0000" class="input-neo py-3 phone-mask">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Data de Nascimento</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', isset($member->birth_date) ? $member->birth_date->format('Y-m-d') : '') }}"
                               class="input-neo py-3">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Gênero</label>
                        <select name="gender" class="input-neo py-3">
                            <option value="">Selecione...</option>
                            <option value="Male"   {{ old('gender', $member->gender ?? '') === 'Male'   ? 'selected' : '' }}>Masculino</option>
                            <option value="Female" {{ old('gender', $member->gender ?? '') === 'Female' ? 'selected' : '' }}>Feminino</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO: MDA E DISCIPULADO --}}
            <div class="space-y-6 pt-8 border-t border-gray-100">
                <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] flex items-center gap-2">
                    <i class="fas fa-church text-[9px]"></i> Discipulado e MDA
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Mentor (Discipulador)</label>
                        <select name="mentor_id" class="input-neo py-3">
                            <option value="">Selecione um mentor...</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}" {{ old('mentor_id', $member->mentor_id ?? '') == $mentor->id ? 'selected' : '' }}>
                                    {{ $mentor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Status de Membresia</label>
                        <select name="status" class="input-neo py-3">
                            <option value="Active"      {{ old('status', $member->status ?? 'Active') === 'Active'      ? 'selected' : '' }}>Ativo</option>
                            <option value="Inactive"    {{ old('status', $member->status ?? '') === 'Inactive'    ? 'selected' : '' }}>Inativo</option>
                            <option value="Transferred" {{ old('status', $member->status ?? '') === 'Transferred' ? 'selected' : '' }}>Transferido</option>
                            <option value="Deceased"    {{ old('status', $member->status ?? '') === 'Deceased'    ? 'selected' : '' }}>Falecido</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Data de Conversão</label>
                        <input type="date" name="conversion_date" value="{{ old('conversion_date', isset($member->conversion_date) ? $member->conversion_date->format('Y-m-d') : '') }}"
                               class="input-neo py-3">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Data de Batismo</label>
                        <input type="date" name="baptism_date" value="{{ old('baptism_date', isset($member->baptism_date) ? $member->baptism_date->format('Y-m-d') : '') }}"
                               class="input-neo py-3">
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_tither" value="1" {{ old('is_tither', $member->is_tither ?? false) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            <span class="ml-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Dizimista Ativo</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO: ENDEREÇO --}}
            <div class="space-y-6 pt-8 border-t border-gray-100">
                <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] flex items-center gap-2">
                    <i class="fas fa-location-dot text-[9px]"></i> Endereço
                </h3>
                <div class="space-y-2">
                    <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Endereço Completo</label>
                    <textarea name="address" rows="2" placeholder="Rua, número, bairro, cidade..."
                              class="input-neo py-3">{{ old('address', $member->address ?? '') }}</textarea>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="flex gap-4 pt-4">
                <button type="submit"
                        class="btn-neo bg-[#f59e0b] text-white text-[9px] font-black uppercase tracking-widest px-8 py-3.5 flex items-center gap-2 hover:bg-[#d97706] transition-colors">
                    <i class="fas fa-check"></i>
                    {{ isset($member) ? 'Salvar Alterações' : 'Concluir Cadastro' }}
                </button>
                <a href="{{ isset($member) ? route('members.show', $member) : route('members.index') }}"
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
        // Máscaras
        $('.cpf-mask').mask('000.000.000-00');
        $('.phone-mask').mask('(00) 00000-0000');

        // Select2 para usuários (apenas se for Create)
        if ($('.select2-users').length > 0) {
            $('.select2-users').select2({
                ajax: {
                    url: '/members/search-users', // Precisamos criar esta rota
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data };
                    }
                },
                minimumInputLength: 3,
                theme: 'bootstrap-5'
            });
        }
    });
</script>
@endpush
@endsection
