<x-app-layout>
    @section('header_title', 'Gestão de Células')

    <div class="flex flex-col gap-6">
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div class="animate-fade">
                <h1 class="text-3xl font-display font-black text-title tracking-tighter">Gestão de Células</h1>
                <p class="text-sm text-gray-500 font-medium">Gerencie as unidades e lideranças da instituição</p>
            </div>

            @can('create', App\Models\Cell::class)
            <a href="{{ route('cells.create') }}" class="btn-neo">
                <i class="fas fa-plus"></i>
                <span>Nova Célula</span>
            </a>
            @endcan
        </div>

        <!-- TOP FILTERS -->
        <div class="card-neo p-12 bg-primary-card mb-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="h-[1px] w-8 bg-accent"></div>
                <span class="font-black uppercase tracking-[0.3em] text-[10px] text-white">Filtros de Busca</span>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-1 relative group">
                    <input type="text" placeholder="BUSCAR CÉLULA OU LÍDER..." 
                           class="input-neo">
                </div>
                <button class="btn-neo">
                    <i class="fas fa-check-circle"></i>
                    Filtrar
                </button>
            </div>
        </div>

        <!-- CELLS TABLE -->
        <div class="card-neo bg-primary-card">
            <div class="p-12 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-display font-black text-white uppercase tracking-tighter">Listagem de Unidades</h3>
                    <p class="text-[10px] text-accent font-black uppercase mt-2 tracking-[0.3em]">Base de Dados Consolidada</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/30 dark:bg-black/5">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-white/5">Célula</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-white/5">Liderança</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-white/5">Cronograma</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-white/5 text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-white/5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($cells as $cell)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-12 py-8">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 bg-neutral-900 flex items-center justify-center text-accent font-black border border-white/5">
                                        {{ substr($cell->name, 0, 2) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-white font-black uppercase tracking-widest text-sm">{{ $cell->name }}</span>
                                        <span class="text-[10px] text-neutral-500 font-bold uppercase tracking-widest">{{ $cell->neighborhood ?? 'Sem Bairro' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-12 py-8">
                                <div class="flex flex-col">
                                    <span class="text-white text-xs font-black uppercase tracking-widest">{{ $cell->leader->name ?? 'Sem Líder' }}</span>
                                    <span class="text-[9px] text-accent font-black uppercase tracking-widest">{{ $cell->supervisor->name ?? 'Sem Supervisor' }}</span>
                                </div>
                            </td>
                            <td class="px-12 py-8">
                                <div class="flex items-center gap-4 text-neutral-500">
                                    <i class="far fa-calendar-alt text-accent"></i>
                                    <span class="font-black text-white text-[10px] uppercase tracking-widest">{{ $cell->meeting_day }}</span>
                                    <span class="text-neutral-800">|</span>
                                    <span class="text-[10px] font-black uppercase">{{ $cell->meeting_time }}</span>
                                </div>
                            </td>
                            <td class="px-12 py-8 text-center">
                                <span class="px-4 py-2 border text-[9px] font-black uppercase tracking-[0.2em] {{ $cell->is_active ? 'border-accent text-accent' : 'border-red-500 text-red-500' }}">
                                    {{ $cell->is_active ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td class="px-12 py-8 text-right">
                                <div class="flex items-center justify-end gap-6 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('cells.edit', $cell) }}" class="text-neutral-500 hover:text-accent transition-all">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center text-gray-300 dark:text-gray-700 border border-gray-100 dark:border-white/5">
                                        <i class="fas fa-folder-open text-3xl"></i>
                                    </div>
                                    <p class="text-gray-400 font-semibold italic text-sm">Nenhuma célula cadastrada até o momento.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-8 bg-gray-50/30 dark:bg-black/5 border-t border-gray-100 dark:border-white/5">
                {{ $cells->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
