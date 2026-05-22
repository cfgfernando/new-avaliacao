@extends('layouts.app')

@section('title', 'Servidores Avaliados')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Servidores Avaliados</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Gerenciamento do quadro de servidores públicos sob avaliação</p>
        </div>
        <a href="{{ route('admin.evaluated-users.create') }}" class="btn-neo btn-primary text-xs py-2 px-6 shrink-0">
            <i class="fas fa-plus mr-2"></i> NOVO SERVIDOR
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 font-bold text-sm border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 text-rose-600 p-4 rounded-xl mb-6 font-bold text-sm border border-rose-100 flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Filtros de Busca -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.evaluated-users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Busca por Nome, Matrícula ou E-mail</label>
                <input type="text" name="search" value="{{ $search }}" class="input-neo py-2.5" placeholder="Digite para buscar...">
            </div>
            
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Secretaria / Lotação</label>
                <select name="office_id" class="input-neo py-2.5">
                    <option value="">Todas as Secretarias</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}" {{ $officeId == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <div class="flex-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Grupo Funcional</label>
                    <select name="evaluation_group" class="input-neo py-2.5">
                        <option value="">Todos</option>
                        <option value="geral" {{ $group == 'geral' ? 'selected' : '' }}>Quadro Geral</option>
                        <option value="saude" {{ $group == 'saude' ? 'selected' : '' }}>Saúde</option>
                        <option value="educacao" {{ $group == 'educacao' ? 'selected' : '' }}>Educação</option>
                        <option value="guarda" {{ $group == 'guarda' ? 'selected' : '' }}>Guarda Municipal</option>
                    </select>
                </div>
                <button type="submit" class="btn-neo btn-primary py-2.5 px-4 text-xs font-bold self-end shrink-0" title="Filtrar">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Registros -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Servidor</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Matrícula</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cargo e Lotação</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Grupo Funcional</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Avaliador Associado</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-200 flex items-center justify-center font-bold text-xs text-blue-600 font-sans">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                            {{ $user->name }}
                                            @if($user->has_active_pad)
                                                <span class="text-[9px] bg-rose-50 text-rose-700 font-bold px-2 py-0.5 rounded border border-rose-200 animate-pulse font-mono" title="O servidor possui PAD ativo. Avaliação travada administrativamente.">
                                                    PAD ATIVO
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 font-semibold mt-0.5">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200">
                                    {{ $user->registration_number }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-slate-750">{{ $user->cargo }}</div>
                                <div class="text-[10px] text-slate-450 mt-0.5 uppercase tracking-wide font-semibold">{{ $user->office?->name ?? ($user->lotacao ?? 'Sem secretaria') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $groupLabels = [
                                        'geral' => 'Quadro Geral',
                                        'saude' => 'Saúde',
                                        'educacao' => 'Educação',
                                        'guarda' => 'Guarda Municipal'
                                    ];
                                    $groupClasses = [
                                        'geral' => 'bg-slate-50 text-slate-700 border-slate-200',
                                        'saude' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'educacao' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'guarda' => 'bg-amber-50 text-amber-700 border-amber-200'
                                    ];
                                @endphp
                                <span class="{{ $groupClasses[$user->evaluation_group] ?? 'bg-slate-50 text-slate-700 border-slate-200' }} text-[9px] font-black px-2 py-1 rounded border uppercase tracking-widest font-mono">
                                    {{ $groupLabels[$user->evaluation_group] ?? $user->evaluation_group }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->evaluator_id)
                                    <div class="text-xs font-bold text-slate-800">{{ $user->evaluator?->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $user->evaluator?->email }}</div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Não associado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.evaluated-users.edit', $user->id) }}" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-blue-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Editar">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </a>
                                    <form action="{{ route('admin.evaluated-users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este servidor avaliado?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-rose-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Excluir">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-sm font-semibold">Nenhum servidor avaliado encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
