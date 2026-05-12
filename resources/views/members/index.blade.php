<x-app-layout>
    @section('title', 'Gestão de Membros')

    <div class="space-y-8">
        <!-- Action Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <h1 class="text-3xl font-bold text-primary-dark">Lista de Membros</h1>
                <p class="text-primary-light text-sm mt-1">Gerencie todos os membros e discípulos da igreja.</p>
            </div>
            <a href="{{ route('members.create') }}" class="btn-neo btn-primary">
                <i class="fas fa-user-plus"></i>
                <span>Novo Membro</span>
            </a>
        </div>

        <!-- Filters Card -->
        <div class="card-neo">
            <form action="{{ route('members.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Busca Nominal</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nome ou email..."
                           class="input-neo">
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Status</label>
                    <select name="status" class="input-neo">
                        <option value="">Todos</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Ativo</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inativo</option>
                        <option value="Visitor" {{ request('status') == 'Visitor' ? 'selected' : '' }}>Visitante</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Célula</label>
                    <select name="cell_id" class="input-neo">
                        <option value="">Todas</option>
                        @foreach($accessibleCells as $id => $name)
                            <option value="{{ $id }}" {{ request('cell_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-neo btn-primary w-full bg-primary hover:bg-primary-dark">
                        <i class="fas fa-filter text-xs"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Members Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold text-primary-light uppercase tracking-widest">Membro</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-primary-light uppercase tracking-widest">Vínculo</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-primary-light uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-primary-light uppercase tracking-widest text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($members as $member)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-accent/10 flex items-center justify-center text-accent font-bold rounded-lg border border-accent/20">
                                        {{ substr($member->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-primary-dark uppercase tracking-tight">{{ $member->user->name }}</div>
                                        <div class="text-[10px] text-primary-light">{{ $member->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-[10px] font-bold text-primary-dark uppercase tracking-wider">
                                    <i class="fas fa-church text-accent mr-2"></i>
                                    {{ $member->user->cell->name ?? 'Sem Célula' }}
                                </div>
                                <div class="text-[9px] text-primary-light mt-1 uppercase">
                                    Líder: {{ $member->user->cell->leader->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @if($member->status == 'Active')
                                    <span class="badge-success">Ativo</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 font-medium text-xs px-2.5 py-0.5 rounded-full border border-gray-200">
                                        {{ $member->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('members.show', $member) }}" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-primary-light hover:bg-accent hover:text-white transition-all">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('members.edit', $member) }}" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-primary-light hover:bg-accent hover:text-white transition-all">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center text-gray-200">
                                        <i class="fas fa-user-slash text-4xl"></i>
                                    </div>
                                    <p class="text-primary-light font-bold">Nenhum membro encontrado.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
            <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-100">
                {{ $members->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
