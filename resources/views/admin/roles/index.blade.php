@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-primary-dark uppercase tracking-tight">Perfis de Acesso</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gerencie os níveis de acesso (Roles)</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn-neo btn-primary text-xs py-2 px-6">
            <i class="fas fa-plus mr-2"></i> NOVO PERFIL
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 font-bold text-sm border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Arraste para ordenar a hierarquia visual</h3>
            <span class="text-[10px] text-slate-300 font-bold bg-slate-50 px-3 py-1 rounded-full uppercase tracking-widest border border-slate-100">Ordem salva automaticamente</span>
        </div>

        <ul id="rolesList" class="space-y-3">
            @forelse($roles as $role)
                <li data-id="{{ $role->id }}" class="group bg-white border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm hover:shadow-md hover:border-accent/30 transition-all duration-300 cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 group-hover:text-accent transition-colors p-1">
                            <i class="fas fa-grip-vertical"></i>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-50 text-primary-dark group-hover:bg-accent group-hover:text-white flex items-center justify-center transition-all duration-500">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <p class="font-bold text-primary-dark text-sm tracking-tight">{{ $role->name }}</p>
                            <p class="text-[10px] font-bold text-primary-light uppercase tracking-widest opacity-60">{{ $role->permissions->count() }} Permissões vinculadas</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-blue-500 transition-colors">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Excluir este perfil?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 hover:bg-rose-50 rounded-lg text-slate-400 hover:text-rose-500 transition-colors">
                                <i class="fas fa-trash text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="p-8 text-center text-slate-400 text-sm font-semibold border border-dashed border-slate-200 rounded-2xl">
                    Nenhum perfil encontrado.
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rolesList = document.getElementById('rolesList');
        
        if (rolesList && rolesList.children.length > 0 && !rolesList.querySelector('.border-dashed')) {
            new Sortable(rolesList, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'opacity-50',
                onEnd: async (evt) => {
                    const itemIds = Array.from(evt.to.children)
                                         .map(li => li.dataset.id)
                                         .filter(id => id);

                    if (!window.axios) {
                        console.error('Axios não encontrado. Verifique a instalação.');
                        return;
                    }

                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (token) {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
                    }

                    try {
                        const response = await window.axios.post('{{ route('admin.roles.order') }}', {
                            order: itemIds
                        });
                        
                        if (response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Ordem Salva!',
                                text: 'A nova hierarquia de perfis foi atualizada.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    } catch (error) {
                        console.error(error);
                        const msg = error.response?.data?.message || 'Não foi possível salvar a nova ordem.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: msg,
                            confirmButtonColor: '#0ea5e9'
                        });
                    }
                }
            });
        }
    });
</script>
@endpush
