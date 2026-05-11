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
            <a href="{{ route('cells.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                <span class="uppercase tracking-widest text-xs font-black">Nova Célula</span>
            </a>
            @endcan
        </div>

        <!-- TOP FILTERS -->
        <div class="card-elite p-8 animate-fade" style="animation-delay: 0.1s">
            <div class="flex items-center gap-2 mb-6 text-title">
                <i class="fas fa-filter text-accent"></i>
                <span class="font-black uppercase tracking-widest text-[10px]">Filtros de Busca</span>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1 relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-accent transition-colors"></i>
                    <input type="text" placeholder="Buscar célula por nome ou líder..." 
                           class="input-elite pl-12 pr-6 py-4 w-full">
                </div>
                <button class="bg-gray-800 dark:bg-accent text-white px-8 py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-accent-hover transition-all flex items-center gap-2 shadow-lg shadow-gray-200 dark:shadow-accent/20">
                    <i class="fas fa-check-circle"></i>
                    Filtrar
                </button>
                <button class="bg-white dark:bg-gray-800 text-gray-400 px-6 py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:text-accent transition-all border border-gray-200 dark:border-white/5">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        <!-- CELLS TABLE -->
        <div class="card-elite animate-fade" style="animation-delay: 0.2s">
            <div class="p-8 border-b border-gray-100 dark:border-white/5 flex items-center justify-between bg-gray-50/50 dark:bg-black/10">
                <div>
                    <h3 class="text-lg font-display font-black text-title uppercase tracking-tighter">Listagem de Unidades</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-widest">Base de dados consolidada</p>
                </div>
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-accent border border-gray-100 dark:border-white/5 transition-all shadow-sm">
                        <i class="fas fa-download"></i>
                    </button>
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
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-accent/5 dark:bg-primary-light flex items-center justify-center text-accent font-black border border-accent/10 shadow-sm">
                                        {{ substr($cell->name, 0, 2) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-title font-bold tracking-tight">{{ $cell->name }}</span>
                                        <span class="text-[11px] text-gray-400 font-medium">{{ $cell->neighborhood ?? 'Bairro não informado' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-title text-sm font-semibold">{{ $cell->leader->name ?? 'Sem Líder' }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $cell->supervisor->name ?? 'Supervisor não definido' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2 text-gray-400 text-sm">
                                    <i class="far fa-calendar-alt text-accent/50"></i>
                                    <span class="font-semibold text-title text-xs">{{ $cell->meeting_day }}</span>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <span class="text-[11px]">{{ $cell->meeting_time }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $cell->is_active ? 'bg-green-500/10 text-green-600 border-green-500/20' : 'bg-red-500/10 text-red-600 border-red-500/20' }}">
                                    {{ $cell->is_active ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('cells.edit', $cell) }}" class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-accent border border-gray-200 dark:border-white/5 transition-all shadow-sm">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-red-500 border border-gray-200 dark:border-white/5 transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
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
