@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-24">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <i class="fas fa-shield-alt text-lg"></i>
            </div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Editar Perfil e Permissões</h1>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn-neo bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs py-2 px-6 rounded-lg font-semibold flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" id="roleForm">
        @csrf
        @method('PUT')

        <!-- Cabeçalho do Formulário -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nome do Perfil <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Ex: Administrador">
                    @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Descrição</label>
                    <input type="text" name="description" value="{{ old('description', $role->description) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Ex: Acesso total ao sistema">
                </div>
            </div>
        </div>

        <!-- Matriz de Permissões -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i class="fas fa-th text-slate-400"></i>
                    <h2 class="text-sm font-bold text-slate-800">Matriz de Permissões</h2>
                </div>
                <div class="text-[10px] text-slate-400 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i> Selecione as ações permitidas para este perfil
                </div>
            </div>

            @php
                // Agrupar permissões existentes
                $modules = [];
                foreach($permissions as $p) {
                    $parts = explode('.', $p->name);
                    $module = count($parts) > 1 ? ucfirst($parts[0]) : 'Geral';
                    $modules[$module][] = $p;
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($modules as $moduleName => $modulePerms)
                <div class="border border-slate-100 rounded-xl overflow-hidden module-group">
                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700">{{ $moduleName }}</span>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" class="sr-only toggle-all">
                                <div class="w-8 h-4 bg-slate-200 rounded-full shadow-inner transition-colors toggle-bg"></div>
                                <div class="absolute left-0.5 top-0.5 w-3 h-3 bg-white rounded-full shadow transform transition-transform toggle-dot"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">Tudo</span>
                        </label>
                    </div>
                    <div class="p-4 space-y-3 bg-white">
                        @if(count($modulePerms) > 0)
                            @foreach($modulePerms as $perm)
                            @php
                                $permName = explode('.', $perm->name)[1] ?? $perm->name;
                                $isSuper = str_contains($permName, 'Super Usuário');
                            @endphp
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="w-4 h-4 rounded border-slate-300 {{ $isSuper ? 'text-blue-500 focus:ring-blue-500' : 'text-blue-500 focus:ring-blue-500' }} perm-checkbox" {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                <span class="text-xs transition-colors flex items-center gap-2 {{ $isSuper ? 'text-blue-500 font-bold' : 'text-slate-600 group-hover:text-slate-800' }}">
                                    {{ $permName }}
                                    @if($isSuper)
                                        <i class="fas fa-shield-alt text-amber-400"></i>
                                    @endif
                                </span>
                            </label>
                            @endforeach
                        @else
                            <div class="text-xs text-slate-400 italic">Nenhuma permissão cadastrada neste módulo.</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @error('permissions') <span class="text-xs text-rose-500 font-bold mt-2 block">{{ $message }}</span> @enderror
        </div>

        <!-- Sticky Footer -->
        <div class="fixed bottom-0 left-0 right-0 lg:left-64 bg-white border-t border-slate-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] p-4 px-8 flex justify-between items-center z-40">
            <label class="flex items-center gap-3 cursor-pointer">
                <div class="relative">
                    <input type="checkbox" name="is_active" class="sr-only peer" {{ old('is_active', $role->is_active ?? true) ? 'checked' : '' }}>
                    <div class="w-10 h-5 bg-slate-200 rounded-full shadow-inner transition-colors peer-checked:bg-blue-500"></div>
                    <div class="absolute left-1 top-1 w-3 h-3 bg-white rounded-full shadow transform transition-transform peer-checked:translate-x-5"></div>
                </div>
                <span class="text-xs font-bold text-slate-700">Perfil Ativo</span>
            </label>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-6 rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<style>
    /* Custom Toggle Styles for the "Tudo" buttons */
    .toggle-all:checked ~ .toggle-bg {
        background-color: #3b82f6; /* bg-blue-500 */
    }
    .toggle-all:checked ~ .toggle-dot {
        transform: translateX(100%);
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle "Tudo" toggles
        document.querySelectorAll('.module-group').forEach(group => {
            const toggleAll = group.querySelector('.toggle-all');
            const checkboxes = group.querySelectorAll('.perm-checkbox');
            
            if(!toggleAll || checkboxes.length === 0) return;

            // Initialize toggle all state
            const updateToggleState = () => {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                toggleAll.checked = allChecked;
            };
            
            updateToggleState();

            // When "Tudo" is clicked
            toggleAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            });

            // When individual checkbox is clicked
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateToggleState);
            });
        });
    });
</script>
@endpush
