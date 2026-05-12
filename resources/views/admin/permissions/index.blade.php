@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-primary-dark uppercase tracking-tight">Permissões</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gestão de permissões do sistema</p>
        </div>
        <a href="{{ route('admin.permissions.create') }}" class="btn-neo btn-primary text-xs py-2 px-6">
            <i class="fas fa-plus mr-2"></i> NOVA PERMISSÃO
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 font-bold text-sm border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nome da Permissão</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data de Criação</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-primary-dark text-sm">{{ $permission->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-semibold text-slate-500">{{ $permission->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-blue-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Editar">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </a>
                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta permissão? Isso pode quebrar algumas rotas protegidas.');" class="inline-block">
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
                            <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-sm font-semibold">Nenhuma permissão cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($permissions->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
